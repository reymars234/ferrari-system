<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
 
class DriverMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !auth()->user()->isDriver()) {
            abort(403, 'Driver access only.');
        }
        return $next($request);
    }
}