<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CSPMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // Configura la cabecera Content Security Policy (CSP)
        $response->header('Content-Security-Policy', "frame-ancestors 'self' https://docs.google.com");

        return $response;
    }
}
