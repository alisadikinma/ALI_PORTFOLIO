<?php

use App\Http\Controllers\AwardController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\HomeWebController;
use App\Http\Controllers\dashboardController;
use App\Http\Controllers\GaleriController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TestimonialController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// PUBLIC ROUTES
Route::get('/', [HomeWebController::class, 'index'])->name('home');
Route::get('/sitemap.xml', [HomeWebController::class, 'sitemap'])->name('sitemap');
Route::get('/portfolio', [HomeWebController::class, 'portfolio'])->name('portfolio');
Route::get('/gallery', [HomeWebController::class, 'gallery'])->name('gallery');
Route::get('/portfolio/{slug}', [HomeWebController::class, 'portfolioDetail'])->name('portfolio.detail');
Route::get('/articles', [HomeWebController::class, 'articles'])->name('articles');
Route::get('/article/{slug}', [HomeWebController::class, 'articleDetail'])->name('article.detail');
// Gallery API routes
Route::get('/gallery/{award}/items', [GaleriController::class, 'getGalleryByAward'])->name('gallery.by-award');
Route::get('/api/gallery/{gallery}/items', [HomeWebController::class, 'getGalleryItems'])->name('api.gallery.items');

// Additional route for compatibility
Route::get('/ALI_PORTFOLIO/public/gallery/{award}/items', [GaleriController::class, 'getGalleryByAward']);

// Additional gallery API routes for better compatibility
Route::get('/gallery/{gallery}/items', [HomeWebController::class, 'getGalleryItems'])->name('gallery.items');

// Gallery Item Detail (AJAX, JSON)
Route::get('/gallery-item/{id}', [GaleriController::class, 'showItem'])->name('gallery.item.show');

Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// LOGIN ROUTE
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

// AUTHENTICATED ROUTES
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard.index');
    })->name('dashboard.index');
    
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
    Route::resource('dashboard', dashboardController::class);
    Route::resource('berita', BeritaController::class);
    Route::resource('layanan', LayananController::class);
    Route::resource('galeri', GaleriController::class);
    Route::resource('project', ProjectController::class);
    Route::resource('testimonial', TestimonialController::class);
    Route::resource('award', AwardController::class);
    Route::resource('contacts', ContactController::class);

    Route::get('/tentang-sistem', [SettingController::class, 'about'])->name('setting.about');
});
