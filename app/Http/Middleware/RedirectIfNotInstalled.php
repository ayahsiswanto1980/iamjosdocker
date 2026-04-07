<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfNotInstalled
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If the installation lock file doesn't exist, redirect to installer
        if (!file_exists(storage_path('installed')) && !$request->is('install*')) {
            return redirect()->route('install.welcome');
        }

        return $next($request);
    }
}
