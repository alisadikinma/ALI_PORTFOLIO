<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\GaleriController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\AwardController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| PROFESSIONAL ADMIN ROUTES
|--------------------------------------------------------------------------
| Secure admin panel routes for Digital Transformation Consulting business
*/

// =============================================
// AUTHENTICATION ROUTES (PUBLIC)
// =============================================

// Admin auth routes moved to admin_simple.php to avoid conflicts

// =============================================
// SECURE ADMIN PANEL ROUTES
// =============================================

Route::middleware(['auth', 'admin.security'])->group(function () {

    // Dashboard Routes
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard.index');

    Route::get('/dashboard/enhanced', [DashboardController::class, 'index'])
        ->name('dashboard.enhanced');

    // =============================================
    // PORTFOLIO PROJECT MANAGEMENT
    // =============================================

    Route::prefix('admin/projects')->name('project.')->group(function () {
        Route::get('/', [ProjectController::class, 'index'])->name('index');
        Route::get('/create', [ProjectController::class, 'create'])->name('create');
        Route::post('/store', [ProjectController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [ProjectController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ProjectController::class, 'update'])->name('update');
        Route::delete('/{id}', [ProjectController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/admin', [ProjectController::class, 'showAdmin'])->name('showAdmin');
        Route::delete('/{id}/image', [ProjectController::class, 'deleteImage'])->name('deleteImage');

        // Professional CSRF-Protected Upload Routes
        Route::post('/upload-editor-image', [ProjectController::class, 'uploadEditorImage'])
            ->name('upload-editor-image');
    });

    // Project Search API (AJAX)
    Route::get('/api/projects/search', [ProjectController::class, 'searchProjects'])
        ->name('projects.search');

    // =============================================
    // SITE SETTINGS & CONFIGURATION
    // =============================================

    Route::prefix('admin/settings')->name('setting.')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('index');
        Route::get('/create', [SettingController::class, 'create'])->name('create');
        Route::post('/store', [SettingController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [SettingController::class, 'edit'])->name('edit');
        Route::put('/{id}', [SettingController::class, 'update'])->name('update');
        Route::delete('/{id}', [SettingController::class, 'destroy'])->name('destroy');

        // Homepage Section Management
        Route::get('/sections/manage', [SettingController::class, 'sections'])->name('sections');
        Route::put('/sections/update', [SettingController::class, 'updateSections'])->name('sections.update');

        // Professional Image Upload
        Route::post('/image-upload', [SettingController::class, 'storeImage'])->name('image.upload');

        // About System
        Route::get('/about-system', [SettingController::class, 'about'])->name('about');
    });

    // =============================================
    // CONTENT MANAGEMENT
    // =============================================

    // Articles/Blog Management
    Route::prefix('admin/articles')->name('berita.')->group(function () {
        Route::get('/', [BeritaController::class, 'index'])->name('index');
        Route::get('/create', [BeritaController::class, 'create'])->name('create');
        Route::post('/store', [BeritaController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [BeritaController::class, 'edit'])->name('edit');
        Route::put('/{id}', [BeritaController::class, 'update'])->name('update');
        Route::delete('/{id}', [BeritaController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/show', [BeritaController::class, 'show'])->name('show');
    });

    // Article Search API
    Route::get('/api/articles/search', [BeritaController::class, 'searchArticles'])
        ->name('articles.search');

    // Services Management
    Route::prefix('admin/services')->name('layanan.')->group(function () {
        Route::get('/', [LayananController::class, 'index'])->name('index');
        Route::get('/create', [LayananController::class, 'create'])->name('create');
        Route::post('/store', [LayananController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [LayananController::class, 'edit'])->name('edit');
        Route::put('/{id}', [LayananController::class, 'update'])->name('update');
        Route::delete('/{id}', [LayananController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/show', [LayananController::class, 'show'])->name('show');
    });

    // Gallery Management
    Route::prefix('admin/gallery')->name('galeri.')->group(function () {
        Route::get('/', [GaleriController::class, 'index'])->name('index');
        Route::get('/create', [GaleriController::class, 'create'])->name('create');
        Route::post('/store', [GaleriController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [GaleriController::class, 'edit'])->name('edit');
        Route::put('/{id}', [GaleriController::class, 'update'])->name('update');
        Route::delete('/{id}', [GaleriController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/show', [GaleriController::class, 'show'])->name('show');
    });

    // Testimonials Management
    Route::prefix('admin/testimonials')->name('testimonial.')->group(function () {
        Route::get('/', [TestimonialController::class, 'index'])->name('index');
        Route::get('/create', [TestimonialController::class, 'create'])->name('create');
        Route::post('/store', [TestimonialController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [TestimonialController::class, 'edit'])->name('edit');
        Route::put('/{id}', [TestimonialController::class, 'update'])->name('update');
        Route::delete('/{id}', [TestimonialController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/show', [TestimonialController::class, 'show'])->name('show');
    });

    // Awards Management
    Route::prefix('admin/awards')->name('award.')->group(function () {
        Route::get('/', [AwardController::class, 'index'])->name('index');
        Route::get('/create', [AwardController::class, 'create'])->name('create');
        Route::post('/store', [AwardController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [AwardController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AwardController::class, 'update'])->name('update');
        Route::delete('/{id}', [AwardController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/show', [AwardController::class, 'show'])->name('show');
    });

    // =============================================
    // CONTACT & CLIENT MANAGEMENT
    // =============================================

    Route::prefix('admin/contacts')->name('contacts.')->group(function () {
        Route::get('/', [ContactController::class, 'index'])->name('index');
        Route::get('/{id}/show', [ContactController::class, 'show'])->name('show');
        Route::delete('/{id}', [ContactController::class, 'destroy'])->name('destroy');
        Route::put('/{id}/mark-read', [ContactController::class, 'markAsRead'])->name('mark-read');
        Route::put('/{id}/mark-important', [ContactController::class, 'markAsImportant'])->name('mark-important');
    });

    // =============================================
    // PROFILE MANAGEMENT
    // =============================================

    Route::prefix('admin/profile')->name('admin.profile.')->group(function () {
        Route::get('/', function () {
            return view('profile.index');
        })->name('show');

        Route::put('/update', [ProfileController::class, 'update'])->name('update');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
    });

    // =============================================
    // PROFESSIONAL BUSINESS FEATURES
    // =============================================

    // Business Analytics & Reports
    Route::prefix('admin/analytics')->name('analytics.')->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.analytics.dashboard');
        })->name('dashboard');

        Route::get('/projects', function () {
            return view('admin.analytics.projects');
        })->name('projects');

        Route::get('/contacts', function () {
            return view('admin.analytics.contacts');
        })->name('contacts');
    });

    // Consultation Management
    Route::prefix('admin/consultations')->name('consultations.')->group(function () {
        Route::get('/', function () {
            return view('admin.consultations.index');
        })->name('index');

        Route::get('/calendar', function () {
            return view('admin.consultations.calendar');
        })->name('calendar');

        Route::get('/bookings', function () {
            return view('admin.consultations.bookings');
        })->name('bookings');
    });

    // Professional Tools
    Route::prefix('admin/tools')->name('tools.')->group(function () {
        Route::get('/backup', function () {
            return view('admin.tools.backup');
        })->name('backup');

        Route::get('/security', function () {
            return view('admin.tools.security');
        })->name('security');

        Route::get('/maintenance', function () {
            return view('admin.tools.maintenance');
        })->name('maintenance');
    });
});

// =============================================
// LEGACY ROUTE COMPATIBILITY
// =============================================

// Legacy authentication redirects disabled to prevent conflicts
// Route::get('/login', function () {
//     return redirect()->route('admin.login');
// })->name('login');

Route::post('/logout', function () {
    return redirect()->route('admin.logout');
})->name('logout');