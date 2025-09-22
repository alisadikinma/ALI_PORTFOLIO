<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Password Reset - Ali Sadikin Portfolio Admin</title>

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
                    <h1 class="text-2xl font-bold text-gray-900">Password Reset</h1>
                    <p class="text-sm text-gray-600 mt-1">Portfolio Admin Panel</p>
                </div>
            </div>

            <div class="mb-4 text-sm text-gray-600">
                Enter your email address and we'll send you password reset instructions.
            </div>

            <!-- Session Status -->
            @if (session('success'))
                <div class="mb-4 px-4 py-3 rounded-md bg-green-50 border border-green-200">
                    <p class="text-sm text-green-800">{{ session('success') }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.password.reset') }}">
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
                           placeholder="admin@alisadikin.com" />
                    @error('email')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between mt-6">
                    <a href="{{ route('admin.login') }}" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Back to Login
                    </a>

                    <button type="submit" class="ml-3 inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Send Reset Link
                    </button>
                </div>
            </form>

            <!-- Security Notice -->
            <div class="mt-8 p-4 bg-yellow-50 rounded-md border border-yellow-200">
                <h3 class="text-xs font-semibold text-yellow-800 uppercase tracking-wider">Security Notice</h3>
                <p class="text-xs text-yellow-700 mt-1">
                    For security reasons, we do not confirm whether an email address exists in our system.
                    If the email is valid, you will receive reset instructions.
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-8 text-center">
            <p class="text-xs text-gray-500">
                © {{ date('Y') }} Ali Sadikin - Digital Transformation Consultant. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>