<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApplyCSP
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Set the CSP header
        $response->headers->set(
            'Content-Security-Policy',
            "script-src 'self' https://cdn.jsdelivr.net; style-src 'self' https://cdn.jsdelivr.net;"
        );

        return $response;
    }
}
