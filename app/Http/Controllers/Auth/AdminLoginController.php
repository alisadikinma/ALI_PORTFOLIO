<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class AdminLoginController extends Controller
{
    /**
     * Show the admin login form.
     */
    public function showLoginForm()
    {
        return view('auth.admin-login');
    }

    /**
     * Handle admin login request with professional security measures.
     */
    public function login(Request $request)
    {
        // Rate limiting to prevent brute force attacks
        $key = 'login.' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);

            Log::warning('Rate limit exceeded for admin login', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'attempts_remaining' => 0,
                'retry_after' => $seconds
            ]);

            throw ValidationException::withMessages([
                'email' => ["Too many login attempts. Please try again in {$seconds} seconds."],
            ]);
        }

        // Validate credentials
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        // Add remember me option
        $remember = $request->boolean('remember');

        // Attempt authentication
        if (Auth::attempt($credentials, $remember)) {
            // Clear rate limiting on successful login
            RateLimiter::clear($key);

            // Regenerate session for security
            $request->session()->regenerate();

            // Log successful login
            Log::info('Successful admin login', [
                'user_id' => Auth::id(),
                'user_email' => Auth::user()->email,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'timestamp' => now()
            ]);

            // Redirect to intended page or dashboard
            return redirect()->intended(route('dashboard.enhanced'))
                ->with('success', 'Welcome back to your portfolio admin panel!');
        }

        // Failed login attempt
        RateLimiter::hit($key, 300); // 5 minute decay

        Log::warning('Failed admin login attempt', [
            'email' => $credentials['email'],
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now()
        ]);

        throw ValidationException::withMessages([
            'email' => ['The provided credentials do not match our records.'],
        ]);
    }

    /**
     * Handle admin logout.
     */
    public function logout(Request $request)
    {
        $user = Auth::user();

        Log::info('Admin logout', [
            'user_id' => $user?->id,
            'user_email' => $user?->email,
            'ip' => $request->ip(),
            'timestamp' => now()
        ]);

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'You have been logged out successfully.');
    }

    /**
     * Show password reset form.
     */
    public function showPasswordResetForm()
    {
        return view('auth.admin-password-reset');
    }

    /**
     * Handle password reset request.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        // For security, always show success message regardless of email existence
        return back()->with('success', 'If your email exists in our system, you will receive password reset instructions.');
    }
}