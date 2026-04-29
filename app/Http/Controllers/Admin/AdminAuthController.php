<?php
// FILE: app/Http/Controllers/Admin/AdminAuthController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Notifications\AdminResetPasswordNotification;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use App\Models\User;

class AdminAuthController extends Controller
{
    // ── Show Admin Login Page ─────────────────────────────────────
    public function showLogin()
    {
        if (auth()->check() && auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        if (auth()->check() && !auth()->user()->isAdmin()) {
            return redirect()->route('home')
                ->with('error', 'Access denied. Admin accounts only.');
        }

        return view('admin.login');
    }

    // ── Handle Admin Login POST ───────────────────────────────────
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = auth()->user();

            if (!$user->isAdmin()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return back()->withErrors(['email' => 'Access denied. Admin accounts only.'])->withInput();
            }

            $request->session()->regenerate();
            AuditLogService::log('ADMIN_LOGIN', 'Auth', "Admin logged in: {$user->email}");

            return redirect()->route('admin.dashboard');
        }

        return back()
            ->withErrors(['email' => 'Invalid credentials.'])
            ->withInput($request->only('email'));
    }

    // ── Admin Logout ──────────────────────────────────────────────
    public function logout(Request $request)
    {
        $user = auth()->user();
        if ($user) {
            AuditLogService::log('ADMIN_LOGOUT', 'Auth', "Admin logged out: {$user->email}");
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
            ->with('success', 'You have been logged out.');
    }

    // ── Verify Admin Password (AJAX) ──────────────────────────────
    public function verifyPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        if (!Hash::check($request->password, $request->user()->password)) {
            return response()->json([
                'verified' => false,
                'message'  => 'Incorrect password. Please try again.',
            ], 401);
        }

        return response()->json(['verified' => true]);
    }

    // ══════════════════════════════════════════════════════════════
    //  FORGOT PASSWORD
    // ══════════════════════════════════════════════════════════════

    // ── Show Forgot Password Form ─────────────────────────────────
    public function showForgotForm()
    {
        return view('admin.auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Find admin — always return vague success to prevent email enumeration
        $admin = User::where('email', $request->email)
                     ->where('role', 'admin')
                     ->first();

        if (!$admin) {
            return back()
                ->with('status', 'If that email belongs to an admin account, a reset link has been sent.')
                ->withInput();
        }

        // Generate token via the 'admins' broker (stores in admin_password_reset_tokens)
        $token = Password::broker('admins')->createToken($admin);

        // Send custom notification so the link points to admin.password.reset,
        // NOT the default password.reset route (which causes "invalid token" error)
        $admin->notify(new AdminResetPasswordNotification($token));

        return back()
            ->with('status', 'If that email belongs to an admin account, a reset link has been sent.')
            ->withInput();
    }

    // ══════════════════════════════════════════════════════════════
    //  RESET PASSWORD
    // ══════════════════════════════════════════════════════════════

    // ── Show Reset Form ───────────────────────────────────────────
    public function showResetForm(Request $request, string $token)
    {
        return view('admin.auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email', ''),
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        // Use the 'admins' broker — validates token against admin_password_reset_tokens
        $status = Password::broker('admins')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password'       => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));

                AuditLogService::log(
                    'ADMIN_PASSWORD_RESET',
                    'Auth',
                    "Admin password reset via email: {$user->email}"
                );
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('admin.login')
                ->with('success', 'Password reset successfully! Please log in with your new password.');
        }

        return back()->withErrors(['email' => __($status)]);
    }
}