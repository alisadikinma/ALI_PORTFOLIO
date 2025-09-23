<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeWebController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ContactController;
use App\Config\FallbackConfiguration;

/*
|--------------------------------------------------------------------------
| PROFESSIONAL PORTFOLIO ROUTES
|--------------------------------------------------------------------------
| Digital Transformation Consulting Portfolio - Ali Sadikin
| Secure, performance-optimized routes for professional business operations
*/

// =============================================
// PUBLIC PORTFOLIO ROUTES
// =============================================

// Homepage route
Route::get('/', [HomeWebController::class, 'index'])->name('home');

// Portfolio Routes
Route::get('/portfolio/all', [HomeWebController::class, 'portfolio'])->name('portfolio');
Route::get('/portfolio/all/paginated', [HomeWebController::class, 'portfolioAll'])->name('portfolio.all');
Route::get('/portfolio/{slug}', [HomeWebController::class, 'portfolioDetail'])->name('portfolio.detail');
Route::get('/project/{slug}', [ProjectController::class, 'show'])->name('project.public.show');

// Content Routes
Route::get('/gallery', [HomeWebController::class, 'gallery'])->name('gallery');
Route::get('/articles', [HomeWebController::class, 'articles'])->name('articles');
Route::get('/article/{slug}', [HomeWebController::class, 'articleDetail'])->name('article.detail');

// Contact Form (Public)
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// =============================================
// DIGITAL TRANSFORMATION CONSULTING SEO ROUTES
// =============================================

Route::get('/sitemap.xml', function () {
    $seoService = app(\App\Services\SeoService::class);
    $urls = $seoService->generateSitemapData();

    return response()->view('sitemap', ['urls' => $urls])
        ->header('Content-Type', 'application/xml');
})->name('sitemap');

Route::get('/robots.txt', function () {
    $content = "User-agent: *\n";
    $content .= "Allow: /\n";
    $content .= "Disallow: /admin/\n";
    $content .= "Disallow: /dashboard/\n";
    $content .= "Disallow: /login\n";
    $content .= "Disallow: /api/\n";
    $content .= "\n";
    $content .= "Sitemap: " . url('/sitemap.xml') . "\n";

    return response($content)
        ->header('Content-Type', 'text/plain');
})->name('robots');

// Consulting-specific SEO routes
Route::get('/digital-transformation-consulting', function () {
    return redirect('/', 301);
})->name('consulting.redirect');

Route::get('/manufacturing-ai-implementation', function () {
    return redirect('/portfolio', 301);
})->name('ai-implementation.redirect');

Route::get('/smart-factory-solutions', function () {
    return redirect('/portfolio', 301);
})->name('smart-factory.redirect');

// =============================================
// SECURE FILE SERVING ROUTES
// =============================================

Route::get('/file/project/{filename}', function ($filename) {
    // Security: Sanitize filename to prevent directory traversal
    $filename = basename($filename);

    // Security: Only allow specific file extensions
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

    if (!in_array($extension, $allowedExtensions)) {
        abort(404);
    }

    // Security: Validate filename pattern (prevent malicious filenames)
    if (!preg_match('/^[a-zA-Z0-9_-]+\.' . $extension . '$/', $filename)) {
        abort(404);
    }

    $path = public_path('images/projects/' . $filename);

    if (!file_exists($path)) {
        $placeholderPath = public_path('images/placeholder/project-placeholder.jpg');
        if (file_exists($placeholderPath)) {
            return response()->file($placeholderPath);
        }
        abort(404);
    }

    return response()->file($path);
})->name('project.image');

Route::get('/file/galeri/{filename}', function ($filename) {
    $path = public_path('images/galeri/' . $filename);

    if (!file_exists($path)) {
        $placeholderPath = public_path('images/placeholder/gallery-placeholder.jpg');
        if (file_exists($placeholderPath)) {
            return response()->file($placeholderPath);
        }
        abort(404);
    }

    return response()->file($path);
})->name('galeri.image');

Route::get('/file/editor/{filename}', function ($filename) {
    $path = public_path('images/editor/' . $filename);

    if (!file_exists($path)) {
        abort(404);
    }

    return response()->file($path);
})->name('editor.image');

// =============================================
// API ROUTES FOR FRONTEND
// =============================================

Route::get('/api/projects', [ProjectController::class, 'getProjects'])->name('api.projects');

// =============================================
// PROFESSIONAL ADMIN PANEL ROUTES
// =============================================

require __DIR__.'/admin_simple.php';
require __DIR__.'/admin.php';
require __DIR__.'/upload.php';

// =============================================
// LEGACY ROUTE COMPATIBILITY
// =============================================

// Legacy authentication redirects
// Route::get('/login', function () {
//     return redirect()->route('admin.login');
// })->name('login');

Route::post('/logout', function () {
    return redirect()->route('admin.logout');
})->name('logout');

// Legacy admin redirects
Route::middleware(['auth'])->group(function () {
    // Dashboard redirect removed to prevent conflicts with admin.php routes

    Route::get('/setting', function () {
        return redirect()->route('setting.index');
    });

    Route::get('/project', function () {
        return redirect()->route('project.index');
    });

    Route::get('/berita', function () {
        return redirect()->route('berita.index');
    });

    Route::get('/layanan', function () {
        return redirect()->route('layanan.index');
    });

    Route::get('/galeri', function () {
        return redirect()->route('galeri.index');
    });

    Route::get('/testimonial', function () {
        return redirect()->route('testimonial.index');
    });

    Route::get('/award', function () {
        return redirect()->route('award.index');
    });

    Route::get('/contacts', function () {
        return redirect()->route('contacts.index');
    });
});

// =============================================
// SYSTEM HEALTH & DEBUG ROUTES (REMOVE IN PRODUCTION)
// =============================================

Route::get('/debug', function() {
    return response()->json([
        'status' => 'working',
        'message' => 'Professional Laravel portfolio system operational',
        'timestamp' => now(),
        'php_version' => PHP_VERSION,
        'laravel_version' => app()->version(),
        'environment' => app()->environment()
    ]);
})->name('debug');

Route::get('/auth-status', function () {
    return response()->json([
        'authenticated' => \Illuminate\Support\Facades\Auth::check(),
        'user' => \Illuminate\Support\Facades\Auth::user(),
        'session_id' => session()->getId(),
        'csrf_token' => csrf_token()
    ]);
})->name('auth.status');

// Clear cache route (remove in production)
Route::get('/clear-cache', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('route:clear');
        \Illuminate\Support\Facades\Artisan::call('config:clear');
        \Illuminate\Support\Facades\Artisan::call('view:clear');

        return response()->json([
            'status' => 'success',
            'message' => 'All caches cleared successfully!'
        ]);
    } catch (Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Failed to clear cache: ' . $e->getMessage()
        ], 500);
    }
});