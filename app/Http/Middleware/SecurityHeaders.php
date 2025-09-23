<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Apply security headers from config
        $headers = config('security.headers', []);

        foreach ($headers as $header => $value) {
            $response->headers->set($header, $value);
        }

        // Additional security measures
        $response->headers->set('Server', 'Portfolio-Server'); // Hide server information
        $response->headers->remove('X-Powered-By'); // Remove PHP version disclosure

        return $response;
    }
}