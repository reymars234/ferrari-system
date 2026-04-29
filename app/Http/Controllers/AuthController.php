<?php
// FILE: app/Http/Controllers/AuthController.php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\OtpService;
use App\Services\AuditLogService;
use App\Helpers\RecaptchaHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // ── Register ──────────────────────────────────────────────────
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'                 => ['required', 'string', 'max:100', 'regex:/^[a-zA-Z\s]+$/'],
            'email'                => ['required', 'email', 'unique:users,email'],
            'contact_number'       => ['required', 'regex:/^[0-9]+$/', 'min:7', 'max:15'],
            'password'             => ['required', 'string', 'min:8', 'confirmed'],
            'g-recaptcha-response' => ['required'],
        ], [
            'name.regex'           => 'Full name must not contain numbers.',
            'contact_number.regex' => 'Contact number must contain numbers only.',
        ]);

        if (!RecaptchaHelper::verify($request->input('g-recaptcha-response'))) {
            return back()->withErrors(['g-recaptcha-response' => 'reCAPTCHA verification failed.'])->withInput();
        }

        $user = User::create([
            'name'           => $request->name,
            'email'          => $request->email,
            'contact_number' => $request->contact_number,
            'password'       => Hash::make($request->password),
            'role'           => 'user',
        ]);

        Auth::login($user);
        OtpService::generate($user);
        AuditLogService::log('REGISTER', 'Auth', "New user registered: {$user->email}");

        return redirect()->route('otp.verify')
            ->with('success', 'Registration successful! Please verify your email.');
    }

    // ── OTP ───────────────────────────────────────────────────────
    public function showOtp()
    {
        if (auth()->user()->email_verified_at) {
            return redirect()->route('home');
        }
        return view('auth.otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate(['otp' => 'required|string|size:6']);
        $user = auth()->user();

        if (!OtpService::verify($user, $request->otp)) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP.']);
        }

        $user->update(['email_verified_at' => now()]);
        OtpService::clear($user);
        AuditLogService::log('EMAIL_VERIFIED', 'Auth', "Email verified for: {$user->email}");

        return redirect()->route('home')->with('success', 'Email verified! Welcome to Veloce Vantage.');
    }

    public function resendOtp()
    {
        $user = auth()->user();
        OtpService::generate($user);
        return back()->with('success', 'A new OTP has been sent to your email.');
    }

    // ── Login ─────────────────────────────────────────────────────
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first(),
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $throttleKey = Str::lower($request->email) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $msg     = "Too many login attempts. Please try again in {$seconds} seconds.";

            if ($request->expectsJson()) {
                return response()->json([
                    'success'          => false,
                    'message'          => $msg,
                    'throttle_seconds' => $seconds,
                ], 429);
            }

            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => $msg])
                ->with('throttle_seconds', $seconds);
        }

        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();

            $user = auth()->user();
            AuditLogService::log('LOGIN', 'Auth', "User logged in: {$user->email}");

            if ($user->isAdmin()) {
                $redirect = route('admin.dashboard');
            } elseif ($user->isDriver()) {
                $redirect = route('driver.dashboard');
            } elseif (!$user->email_verified_at) {
                $redirect = route('otp.verify');
            } else {
                $redirect = route('home');
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'success'  => true,
                    'redirect' => $redirect,
                ]);
            }

            return redirect()->intended($redirect);
        }

        RateLimiter::hit($throttleKey, 60);

        $attempts  = min(RateLimiter::attempts($throttleKey), 5);
        $remaining = 5 - $attempts;

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $msg     = "Too many login attempts. Please try again in {$seconds} seconds.";

            if ($request->expectsJson()) {
                return response()->json([
                    'success'          => false,
                    'message'          => $msg,
                    'throttle_seconds' => $seconds,
                ], 429);
            }

            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => $msg])
                ->with('throttle_seconds', $seconds);
        }

        $msg = $remaining > 0
            ? "Invalid credentials. {$remaining} attempt(s) remaining before lockout."
            : "Invalid credentials.";

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => $msg,
            ], 401);
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => $msg]);
    }

    // ── Logout ────────────────────────────────────────────────────
    public function logout(Request $request)
    {
        AuditLogService::log('LOGOUT', 'Auth', 'User logged out.');
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }

    // ── Forgot Password ───────────────────────────────────────────
    public function showForgot()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Explicitly use the 'users' broker
        $status = Password::broker('users')->sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            AuditLogService::log('PASSWORD_RESET_REQUESTED', 'Auth', "Reset link sent: {$request->email}");
            return back()->with('status', 'We have emailed your password reset link!');
        }

        return back()->withErrors(['email' => __($status)]);
    }

    // ── FIXED: Read token from route param, email from query string ──
    public function showReset(Request $request, string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email', ''),
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Explicitly use the 'users' broker — never mixes with admin tokens
        $status = Password::broker('users')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password'       => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
                AuditLogService::log('PASSWORD_RESET', 'Auth', "Password reset: {$user->email}");
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')
                ->with('success', 'Password reset successfully! Please log in.');
        }

        return back()->withErrors(['email' => __($status)]);
    }
}