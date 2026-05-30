<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OneWayAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If not authenticated, redirect to login page
        if (!session()->has('authenticated') || session('authenticated') !== true) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}
