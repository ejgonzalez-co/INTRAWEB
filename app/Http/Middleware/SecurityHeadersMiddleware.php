<?php

namespace App\Http\Middleware;

use Closure;

class SecurityHeadersMiddleware
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Strict-Transport-Security', 'max-age=6000; includeSubDomains');
        $response->headers->set('Content-Security-Policy',"frame-ancestors 'self'");
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        return $response;
    }
}