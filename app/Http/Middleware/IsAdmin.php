<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please login first');
        }

        if (!auth()->user()->is_admin) {
            return redirect()->route('home')->with('error', 'Unauthorized access');
        }

        // Auto-verify admin certificate for admin access
        if (!auth()->user()->certificate_verified) {
            auth()->user()->update(['certificate_verified' => true]);
        }

        return $next($request);
    }
}
