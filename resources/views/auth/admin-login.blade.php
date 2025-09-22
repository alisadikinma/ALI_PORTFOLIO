<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin Login - Ali Sadikin Portfolio</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-2xl overflow-hidden sm:rounded-lg border border-gray-100">
            <!-- Logo -->
            <div class="flex justify-center mb-8">
                <div class="text-center">
                    <h1 class="text-2xl font-bold text-gray-900">Portfolio Admin</h1>
                    <p class="text-sm text-gray-600 mt-1">Digital Transformation Consultant</p>
                </div>
            </div>

            <!-- Session Status -->
            @if (session('success'))
                <div class="mb-4 px-4 py-3 rounded-md bg-green-50 border border-green-200">
                    <p class="text-sm text-green-800">{{ session('success') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 px-4 py-3 rounded-md bg-red-50 border border-red-200">
                    <p class="text-sm text-red-800">{{ session('error') }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login') }}">
                @csrf

                <!-- Email Address -->
                <div>
                    <label for="email" class="block font-medium text-sm text-gray-700">
                        Email Address
                    </label>
                    <input id="email"
                           class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full @error('email') border-red-500 @enderror"
                           type="email"
                           name="email"
                           value="{{ old('email') }}"
                           required
                           autofocus
                           autocomplete="username"
                           placeholder="admin@alisadikin.com" />
                    @error('email')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <label for="password" class="block font-medium text-sm text-gray-700">
                        Password
                    </label>
                    <input id="password"
                           class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full @error('password') border-red-500 @enderror"
                           type="password"
                           name="password"
                           required
                           autocomplete="current-password" />
                    @error('password')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="block mt-4">
                    <label for="remember_me" class="flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                        <span class="ml-2 text-sm text-gray-600">Remember me</span>
                    </label>
                </div>

                <div class="flex items-center justify-between mt-6">
                    <a href="{{ route('admin.password.reset') }}" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Forgot your password?
                    </a>

                    <button type="submit" class="ml-3 inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Login
                    </button>
                </div>
            </form>

            <!-- Security Notice -->
            <div class="mt-8 p-4 bg-gray-50 rounded-md">
                <h3 class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Security Notice</h3>
                <p class="text-xs text-gray-600 mt-1">
                    This admin panel is secured with professional authentication.
                    Multiple failed login attempts will result in temporary account lockout.
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-8 text-center">
            <p class="text-xs text-gray-500">
                © {{ date('Y') }} Ali Sadikin - Digital Transformation Consultant. All rights reserved.
            </p>
            <p class="text-xs text-gray-400 mt-1">
                Professional Portfolio Management System
            </p>
        </div>
    </div>
</body>
</html>