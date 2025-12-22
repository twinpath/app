<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsActive
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->isSuspended()) {
            // Allow access to suspended page and logout
            if ($request->routeIs('suspended') || $request->routeIs('logout')) {
                return $next($request);
            }
            
            // Redirect everything else to suspended page
            return redirect()->route('suspended');
        }

        return $next($request);
    }
}
