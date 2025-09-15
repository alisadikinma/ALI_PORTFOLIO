<?php

/*
|--------------------------------------------------------------------------
| SIMPLE WORKING ROUTES - EMERGENCY VERSION
|--------------------------------------------------------------------------
*/

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeWebController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\GaleriController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\AwardController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ProfileController;

// TEST ROUTE - Remove after confirming it works
Route::get('/test', function () {
    return 'Laravel is working! Route system is functional.';
});

// TEST HOMEPAGE WITHOUT LOADING ISSUES
Route::get('/test-homepage', function () {
    try {
        $controller = new HomeWebController();
        // Get all the same data as the main index
        $data = $controller->index()->getData();
        // But render with the test template
        return view('welcome-test', $data);
    } catch (Exception $e) {
        return view('welcome-simple', [
            'error' => $e->getMessage(),
            'message' => 'Test homepage failed to load'
        ]);
    }
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
Route::get('/portfolio', [HomeWebController::class, 'portfolio'])->name('portfolio');
Route::get('/portfolio/all', [HomeWebController::class, 'portfolioAll'])->name('portfolio.all');
Route::get('/portfolio/{slug}', [HomeWebController::class, 'portfolioDetail'])->name('portfolio.detail');
Route::get('/project/{slug}', [ProjectController::class, 'show'])->name('project.public.show');

// Add public project route
Route::get('/project/{slug}', [ProjectController::class, 'show'])->name('project.public.show');

// AUTH ROUTES - Simple version
Route::get('/login', function () {
   return view('auth.login');
})->name('login');

Route::post('/login', function (\Illuminate\Http\Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->intended('/dashboard');
    }

    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ]);
});

Route::post('/logout', function (\Illuminate\Http\Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    
    // Profile routes
    Route::get('/user/profile', function () {
        return view('profile.index');
    })->name('profile.show');
    
    // Profile update routes
    Route::put('/user/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/user/password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('password.update');
    
    Route::post('image-upload', [SettingController::class, 'storeImage'])->name('image.upload');
    Route::resource('setting', SettingController::class);
    
    // Section Management Routes
    Route::get('/setting/sections/manage', [SettingController::class, 'sections'])->name('setting.sections');
    Route::put('/setting/sections/update', [SettingController::class, 'updateSections'])->name('setting.sections.update');

    // API endpoint for article search
    Route::get('/api/articles/search', [BeritaController::class, 'searchArticles'])->name('articles.search');

    // Resource Routes
    Route::resource('berita', BeritaController::class);
    Route::resource('layanan', LayananController::class);
    Route::resource('galeri', GaleriController::class);
    
    // Project routes with additional showAdmin route and editor image upload
    Route::resource('project', ProjectController::class);
    Route::get('project/{id}/admin', [ProjectController::class, 'showAdmin'])->name('project.showAdmin');
    Route::delete('project/{id}/image', [ProjectController::class, 'deleteImage'])->name('project.deleteImage');
    Route::post('project/upload-editor-image', [ProjectController::class, 'uploadEditorImage'])->name('project.upload-editor-image');
    
    Route::resource('testimonial', TestimonialController::class);
    Route::resource('award', AwardController::class);
    Route::resource('contacts', ContactController::class);
    
    // Additional project routes
    Route::get('/project/{id}/admin', [ProjectController::class, 'showAdmin'])->name('project.showAdmin');

    Route::get('/tentang-sistem', [SettingController::class, 'about'])->name('setting.about');
});
