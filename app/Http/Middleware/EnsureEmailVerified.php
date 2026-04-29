<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && is_null(auth()->user()->email_verified_at)) {
            return redirect()->route('otp.verify')->with('info', 'Please verify your email first.');
        }
        return $next($request);
    }
}