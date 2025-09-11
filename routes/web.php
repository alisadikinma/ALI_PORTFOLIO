<?php

use App\Http\Controllers\AwardController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
// Gallery API routes - FIXED
Route::get('/api/award/{award}/gallery', [GaleriController::class, 'getGalleryByAward'])->name('api.award.gallery');
Route::get('/award/{award}/gallery', [GaleriController::class, 'getGalleryByAward'])->name('award.gallery');
Route::get('/gallery/{award}/items', [GaleriController::class, 'getGalleryByAward'])->name('gallery.by-award');

// Alternative endpoints for compatibility
Route::get('/api/gallery/{gallery}/items', [GaleriController::class, 'getGalleryItems'])->name('api.gallery.items');
Route::get('/gallery/{gallery}/items', [GaleriController::class, 'getGalleryItems'])->name('gallery.items');

// Gallery Item Detail (AJAX, JSON)
Route::get('/gallery-item/{id}', [GaleriController::class, 'showItem'])->name('gallery.item.show');

// Test route untuk debug gallery - SIMPLE VERSION
Route::get('/debug/gallery/{galleryId}', function($galleryId) {
    // Raw query untuk memastikan data ada
    $galeriData = DB::select("SELECT * FROM galeri WHERE id_galeri = ? AND status = 'Active'", [$galleryId]);
    $itemsData = DB::select("SELECT * FROM gallery_items WHERE id_galeri = ? AND status = 'Active' ORDER BY sequence", [$galleryId]);
    
    return response()->json([
        'success' => true,
        'debug_info' => [
            'gallery_id' => $galleryId,
            'galeri_found' => count($galeriData),
            'items_found' => count($itemsData),
            'galeri_data' => $galeriData,
            'items_data' => $itemsData,
            'all_galleries' => DB::select("SELECT id_galeri, nama_galeri, status FROM galeri ORDER BY id_galeri"),
            'current_url' => request()->fullUrl(),
            'base_path' => base_path(),
            'app_url' => config('app.url')
        ]
    ]);
})->name('debug.gallery.simple');


Route::get('/test/gallery-debug/{galleryId}', function($galleryId) {
    try {
        $galeri = \App\Models\Galeri::with(['galleryItems' => function($query) {
            $query->where('status', 'Active')
                  ->orderBy('sequence', 'asc')
                  ->orderBy('id_gallery_item', 'asc');
        }])
        ->where('id_galeri', $galleryId)
        ->where('status', 'Active')
        ->first();

        if (!$galeri) {
            return response()->json([
                'success' => false,
                'message' => 'Gallery not found or inactive',
                'gallery_id' => $galleryId,
                'debug' => [
                    'all_galleries' => \App\Models\Galeri::select('id_galeri', 'nama_galeri', 'status')->get()
                ]
            ], 404);
        }

        // Format items for frontend
        $items = $galeri->galleryItems->map(function($item) {
            $data = [
                'id' => $item->id_gallery_item,
                'type' => $item->type,
                'sequence' => $item->sequence,
                'title' => $item->galeri ? $item->galeri->nama_galeri : null,
                'gallery_name' => $item->galeri ? $item->galeri->nama_galeri : null,
                'status' => $item->status
            ];
            
            if ($item->type === 'image' && $item->file_name) {
                $data['file_url'] = asset('file/galeri/' . $item->file_name);
                $data['thumbnail_url'] = asset('file/galeri/' . $item->file_name);
            } elseif ($item->type === 'youtube' && $item->youtube_url) {
                $data['file_url'] = $item->youtube_url;
                $data['youtube_url'] = $item->youtube_url;
                // Extract YouTube ID for thumbnail
                preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([^&\n?#]+)/', $item->youtube_url, $matches);
                $videoId = $matches[1] ?? null;
                $data['thumbnail_url'] = $videoId ? "https://img.youtube.com/vi/{$videoId}/maxresdefault.jpg" : null;
            }
            
            return $data;
        });

        return response()->json([
            'success' => true,
            'gallery_id' => $galleryId,
            'gallery_name' => $galeri->nama_galeri,
            'items' => $items,
            'total' => $items->count(),
            'debug' => [
                'gallery_status' => $galeri->status,
                'raw_items_count' => $galeri->galleryItems->count(),
                'route' => 'test/gallery-debug/' . $galleryId,
                'timestamp' => now()->toISOString()
            ]
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to load gallery items',
            'error' => $e->getMessage(),
            'gallery_id' => $galleryId,
            'debug' => [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]
        ], 500);
    }
})->name('test.gallery.debug');


Route::get('/test/gallery/{award}', function($award) {
    try {
        $galleryItems = \App\Models\GalleryItem::with(['galeri'])
            ->where('id_award', $award)
            ->where('status', 'Active')
            ->orderBy('sequence', 'asc')
            ->get();
            
        $items = $galleryItems->map(function($item) {
            $data = [
                'id' => $item->id_gallery_item,
                'type' => $item->type,
                'sequence' => $item->sequence,
                'title' => $item->title,
                'gallery_name' => $item->galeri ? $item->galeri->nama_galeri : null,
                'status' => $item->status
            ];
            
            if ($item->type === 'image' && $item->file_name) {
                $data['file_url'] = asset('file/galeri/' . $item->file_name);
                $data['thumbnail_url'] = $data['file_url'];
            } elseif ($item->type === 'youtube' && $item->youtube_url) {
                $data['file_url'] = $item->youtube_url;
                $data['youtube_url'] = $item->youtube_url;
            }
            
            return $data;
        });
        
        return response()->json([
            'success' => true,
            'items' => $items,
            'total' => $items->count(),
            'award_id' => $award,
            'debug' => [
                'route' => 'test/gallery/' . $award,
                'timestamp' => now()->toISOString()
            ]
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'award_id' => $award
        ], 500);
    }
})->name('test.gallery');

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
// Simple gallery debug endpoint (no auth required for testing)
Route::get('/simple-gallery-test/{galleryId}', function($galleryId) {
    try {
        // Direct database query to test connection
        $galleryItems = \Illuminate\Support\Facades\DB::table('gallery_items')
            ->where('id_galeri', $galleryId)
            ->where('status', 'Active')
            ->orderBy('sequence')
            ->get();

        $galleryInfo = \Illuminate\Support\Facades\DB::table('galeri')
            ->where('id_galeri', $galleryId)
            ->where('status', 'Active')
            ->first();

        return response()->json([
            'success' => true,
            'method' => 'simple_direct_query',
            'gallery_id' => $galleryId,
            'gallery_info' => $galleryInfo,
            'items_count' => $galleryItems->count(),
            'items' => $galleryItems->map(function($item) {
                $typeString = '';
                switch ((int)$item->type) {
                    case 1: $typeString = 'image'; break;
                    case 2: $typeString = 'youtube'; break;
                    case 3: $typeString = 'video'; break;
                    default: $typeString = 'unknown';
                }
                
                return [
                    'id' => $item->id_gallery_item,
                    'type' => $typeString,
                    'type_numeric' => (int)$item->type,
                    'sequence' => $item->sequence,
                    'file_name' => $item->file_name,
                    'youtube_url' => $item->youtube_url,
                    'file_url' => $item->file_name ? asset('file/galeri/' . $item->file_name) : null
                ];
            }),
            'timestamp' => now()->toISOString()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'gallery_id' => $galleryId
        ], 500);
    }
})->name('simple.gallery.test');

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
