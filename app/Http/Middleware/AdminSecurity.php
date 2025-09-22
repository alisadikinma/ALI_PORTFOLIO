<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AdminSecurity
{
    /**
     * Handle an incoming request.
     *
     * Professional admin security middleware for consulting business
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            Log::warning('Unauthorized admin access attempt', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
                'timestamp' => now()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Authentication required',
                    'message' => 'Please log in to access admin panel'
                ], 401);
            }

            return redirect()->route('login')
                ->with('error', 'Please log in to access the admin panel');
        }

        // Log admin access for security audit
        Log::info('Admin panel access', [
            'user_id' => Auth::id(),
            'user_email' => Auth::user()->email,
            'ip' => $request->ip(),
            'action' => $request->method() . ' ' . $request->path(),
            'timestamp' => now()
        ]);

        // Add security headers
        $response = $next($request);

        if ($response instanceof \Illuminate\Http\Response) {
            $response->headers->set('X-Frame-Options', 'DENY');
            $response->headers->set('X-Content-Type-Options', 'nosniff');
            $response->headers->set('X-XSS-Protection', '1; mode=block');
            $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        }

        return $response;
    }
}