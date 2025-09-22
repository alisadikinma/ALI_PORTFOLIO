<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AdminLoginController;

/*
|--------------------------------------------------------------------------
| SIMPLE ADMIN AUTHENTICATION ROUTES
|--------------------------------------------------------------------------
| Direct admin authentication routes without conflicts
*/

// Admin Login Routes (No Auth Required)
Route::get('/admin/login', [AdminLoginController::class, 'showLoginForm'])
    ->name('admin.login')
    ->middleware('guest');

Route::post('admin/login', [AdminLoginController::class, 'login'])
    ->name('admin.login.submit')
    ->middleware('guest');

// Admin Logout (Auth Required)
Route::post('admin/logout', [AdminLoginController::class, 'logout'])
    ->name('admin.logout')
    ->middleware('auth');

// Password Reset
Route::get('admin/password/reset', [AdminLoginController::class, 'showPasswordResetForm'])
    ->name('admin.password.reset')
    ->middleware('guest');

Route::post('admin/password/reset', [AdminLoginController::class, 'resetPassword'])
    ->name('admin.password.reset.submit')
    ->middleware('guest');