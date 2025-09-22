<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request for admin access.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required',
                'error' => 'Unauthorized access'
            ], 401);
        }

        $user = auth()->user();

        // Check if user has admin role (adjust based on your user model)
        if (!$this->isAdmin($user)) {
            return response()->json([
                'success' => false,
                'message' => 'Admin access required',
                'error' => 'Insufficient permissions'
            ], 403);
        }

        return $next($request);
    }

    /**
     * Check if user has admin privileges
     */
    private function isAdmin($user): bool
    {
        // Adjust this logic based on your User model structure
        return $user->role === 'admin' ||
               $user->is_admin === true ||
               $user->hasRole('admin') ||
               $user->email === config('app.admin_email', 'admin@aliportfolio.com');
    }
}