<?php

/*
|--------------------------------------------------------------------------
| SECURE WEB ROUTES - PRODUCTION VERSION
|--------------------------------------------------------------------------
| All debug routes removed, authentication enforced, input validation added
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

// PUBLIC ROUTES - No authentication required
Route::get('/', [HomeWebController::class, 'index'])->name('home');
Route::get('/portfolio/all', [HomeWebController::class, 'portfolio'])->name('portfolio');
Route::get('/portfolio/all/paginated', [HomeWebController::class, 'portfolioAll'])->name('portfolio.all');
Route::get('/portfolio/{slug}', [HomeWebController::class, 'portfolioDetail'])->name('portfolio.detail');
Route::get('/project/{slug}', [ProjectController::class, 'show'])->name('project.public.show');
Route::get('/gallery', [HomeWebController::class, 'gallery'])->name('gallery');
Route::get('/articles', [HomeWebController::class, 'articles'])->name('articles');
Route::get('/article/{slug}', [HomeWebController::class, 'articleDetail'])->name('article.detail');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// API ROUTES - Public access for portfolio display
Route::get('/api/projects', [ProjectController::class, 'getProjects'])->name('api.projects');

// SECURE FILE SERVING - With proper validation
Route::get('/file/project/{filename}', function ($filename) {
    // Validate filename to prevent path traversal
    if (!preg_match('/^[a-zA-Z0-9._-]+$/', $filename)) {
        abort(404);
    }

    $path = public_path('images/projects/' . $filename);

    if (!file_exists($path) || !is_file($path)) {
        $placeholderPath = public_path('images/placeholder/project-placeholder.jpg');
        if (file_exists($placeholderPath)) {
            return response()->file($placeholderPath);
        }
        abort(404);
    }

    return response()->file($path);
})->name('project.image');

Route::get('/file/galeri/{filename}', function ($filename) {
    // Validate filename to prevent path traversal
    if (!preg_match('/^[a-zA-Z0-9._-]+$/', $filename)) {
        abort(404);
    }

    $path = public_path('images/galeri/' . $filename);

    if (!file_exists($path) || !is_file($path)) {
        $placeholderPath = public_path('images/placeholder/gallery-placeholder.jpg');
        if (file_exists($placeholderPath)) {
            return response()->file($placeholderPath);
        }
        abort(404);
    }

    return response()->file($path);
})->name('galeri.image');

// AUTHENTICATION ROUTES
Route::get('/login', function () {
   return view('auth.login');
})->name('login');

Route::post('/login', function (\Illuminate\Http\Request $request) {
    $request->validate([
        'email' => ['required', 'email', 'max:255'],
        'password' => ['required', 'string', 'min:6', 'max:255'],
    ]);

    $credentials = $request->only(['email', 'password']);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->intended('/dashboard');
    }

    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ])->onlyInput('email');
});

Route::post('/logout', function (\Illuminate\Http\Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');

// AUTHENTICATED ROUTES - Require authentication
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    // Profile Management
    Route::get('/user/profile', function () {
        return view('profile.index');
    })->name('profile.show');

    Route::put('/user/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/user/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    // Settings Management
    Route::post('image-upload', [SettingController::class, 'storeImage'])->name('image.upload');
    Route::resource('setting', SettingController::class);
    Route::get('/setting/sections/manage', [SettingController::class, 'sections'])->name('setting.sections');
    Route::put('/setting/sections/update', [SettingController::class, 'updateSections'])->name('setting.sections.update');
    Route::get('/tentang-sistem', [SettingController::class, 'about'])->name('setting.about');

    // Content Management
    Route::resource('berita', BeritaController::class);
    Route::resource('layanan', LayananController::class);
    Route::resource('galeri', GaleriController::class);
    Route::resource('testimonial', TestimonialController::class);
    Route::resource('award', AwardController::class);
    Route::resource('contacts', ContactController::class);

    // Project Management - Custom routes
    Route::get('/project', [ProjectController::class, 'index'])->name('project.index');
    Route::get('/create_project', [ProjectController::class, 'create'])->name('project.create');
    Route::post('/project', [ProjectController::class, 'store'])->name('project.store');
    Route::get('/project/{id}', [ProjectController::class, 'show'])->name('project.show');
    Route::get('/project/{id}/show', [ProjectController::class, 'showAdmin'])->name('project.showAdmin');
    Route::get('/project/{id}/edit', [ProjectController::class, 'edit'])->name('project.edit');
    Route::put('/project/{id}', [ProjectController::class, 'update'])->name('project.update');
    Route::delete('/project/{id}', [ProjectController::class, 'destroy'])->name('project.destroy');
    Route::delete('/project/{id}/image', [ProjectController::class, 'deleteImage'])->name('project.deleteImage');

    // Secure file upload for editor
    Route::post('/project/upload-editor-image', [ProjectController::class, 'uploadEditorImage'])->name('project.upload-editor-image');

    // Secure file serving for editor
    Route::get('/file/editor/{filename}', function ($filename) {
        // Validate filename to prevent path traversal
        if (!preg_match('/^[a-zA-Z0-9._-]+$/', $filename)) {
            abort(404);
        }

        $path = public_path('images/editor/' . $filename);

        if (!file_exists($path) || !is_file($path)) {
            abort(404);
        }

        return response()->file($path);
    })->name('editor.image');

    // API endpoints - Protected
    Route::get('/api/projects/search', [ProjectController::class, 'searchProjects'])->name('project.search');
    Route::get('/projects/search', [ProjectController::class, 'searchProjects'])->name('projects.search.autocomplete');
    Route::get('/api/articles/search', [BeritaController::class, 'searchArticles'])->name('articles.search');
});

// Health check route - Limited information exposure
Route::get('/health', function() {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toISOString()
    ]);
})->name('health.check');