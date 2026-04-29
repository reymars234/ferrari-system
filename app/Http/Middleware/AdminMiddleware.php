<?php
// FILE: app/Http/Middleware/AdminMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Not logged in → redirect to admin login page
        if (!auth()->check()) {
            return redirect()->route('admin.login')
                ->with('error', 'Please log in to access the admin panel.');
        }

        // Logged in but not an admin → kick to home with error
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home')
                ->with('error', 'Access denied. Admin accounts only.');
        }

        return $next($request);
    }
}