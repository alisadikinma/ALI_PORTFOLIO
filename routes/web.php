<?php

/*
|--------------------------------------------------------------------------
| SIMPLE WORKING ROUTES - EMERGENCY VERSION
|--------------------------------------------------------------------------
*/

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeWebController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ContactController;

// TEST ROUTE - Remove after confirming it works
Route::get('/test', function () {
    return 'Laravel is working! Route system is functional.';
});

// MAIN HOME ROUTE - This MUST work
Route::get('/', function () {
    try {
        $controller = new HomeWebController();
        return $controller->index();
    } catch (Exception $e) {
        return view('welcome-simple', [
            'error' => $e->getMessage(),
            'message' => 'Emergency fallback page - Laravel is running but HomeWebController has issues'
        ]);
    }
})->name('home');

// PORTFOLIO ROUTE
Route::get('/portfolio', function () {
    try {
        $controller = new HomeWebController();
        return $controller->portfolio();
    } catch (Exception $e) {
        return view('portfolio-simple', [
            'error' => $e->getMessage(),
            'message' => 'Emergency fallback - Portfolio controller has issues'
        ]);
    }
})->name('portfolio');

// OTHER ESSENTIAL ROUTES
Route::get('/debug', function() {
    return response()->json([
        'status' => 'working',
        'message' => 'Laravel routes are functional!',
        'timestamp' => now(),
        'php_version' => PHP_VERSION,
        'laravel_version' => app()->version()
    ]);
})->name('debug');

Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Add other routes only after confirming the basic ones work
Route::get('/gallery', [HomeWebController::class, 'gallery'])->name('gallery');
Route::get('/articles', [HomeWebController::class, 'articles'])->name('articles');
Route::get('/article/{slug}', [HomeWebController::class, 'articleDetail'])->name('article.detail');
Route::get('/portfolio/{slug}', [HomeWebController::class, 'portfolioDetail'])->name('portfolio.detail');

// AUTH ROUTES - Simple version
Route::get('/login', function () {
    return '<h1>Login Page</h1><p>Auth system placeholder</p>';
})->name('login');

Route::post('/logout', function () {
    return redirect('/');
})->name('logout');
