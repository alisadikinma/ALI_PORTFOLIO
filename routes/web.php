<?php

/*
|--------------------------------------------------------------------------
| SIMPLE WORKING ROUTES - EMERGENCY VERSION
|--------------------------------------------------------------------------
*/

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
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

// === PROJECT ROUTES FOR TESTING (UNPROTECTED) ===
Route::get('/project/create_project', function () {
    try {
        $title = 'Tambah Portfolio (Working Route!)';
        
        // Ensure images directory exists
        $imageDir = public_path('images/projects');
        if (!File::exists($imageDir)) {
            File::makeDirectory($imageDir, 0755, true);
        }
        
        // Ensure editor directory exists
        $editorDir = public_path('images/editor');
        if (!File::exists($editorDir)) {
            File::makeDirectory($editorDir, 0755, true);
        }
        
        return view('project.create', compact('title'));
    } catch (Exception $e) {
        return response()->json([
            'error' => 'Failed to load create form: ' . $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
})->name('project.create');

// EDITOR IMAGE UPLOAD ROUTE - MOVED TO TOP TO AVOID CONFLICTS
Route::post('/project/upload-editor-image', [ProjectController::class, 'uploadEditorImage'])->name('project.upload-editor-image');

// SIMPLE DIRECT UPLOAD ROUTE FOR TESTING
Route::post('/upload-image', function (\Illuminate\Http\Request $request) {
    try {
        Log::info('Simple upload route called', [
            'method' => $request->method(),
            'has_file' => $request->hasFile('file'),
            'all_files' => array_keys($request->allFiles())
        ]);

        $request->validate([
            'file' => 'required|image|mimes:jpeg,jpg,png,gif,webp|max:2048'
        ]);

        $editorDir = public_path('images/editor');
        if (!File::exists($editorDir)) {
            File::makeDirectory($editorDir, 0755, true);
        }

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            $filename = 'simple_' . time() . '_' . uniqid() . '.' . $extension;
            
            if ($file->move($editorDir, $filename)) {
                $imageUrl = asset('images/editor/' . $filename);
                
                return response()->json([
                    'success' => true,
                    'url' => $imageUrl,
                    'message' => 'Simple upload successful!',
                    'filename' => $filename
                ]);
            }
        }

        throw new Exception('Upload failed');

    } catch (Exception $e) {
        Log::error('Simple upload error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Upload failed: ' . $e->getMessage()
        ], 500);
    }
});

// TEST UPLOAD VERIFICATION ROUTE
Route::get('/test-upload-route', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'Upload route is accessible!',
        'route_name' => 'project.upload-editor-image',
        'route_url' => route('project.upload-editor-image'),
        'csrf_token' => csrf_token(),
        'directories' => [
            'images_exists' => File::exists(public_path('images')),
            'projects_exists' => File::exists(public_path('images/projects')),
            'editor_exists' => File::exists(public_path('images/editor')),
            'editor_writable' => is_writable(public_path('images/editor')) || !File::exists(public_path('images/editor'))
        ]
    ]);
});

// TEST UPLOAD ROUTE
Route::post('/test-upload', function (\Illuminate\Http\Request $request) {
    try {
        return response()->json([
            'success' => true,
            'message' => 'Test upload route is working!',
            'csrf_token' => csrf_token(),
            'request_data' => [
                'has_file' => $request->hasFile('file'),
                'files_count' => count($request->allFiles()),
                'method' => $request->method(),
                'content_type' => $request->header('Content-Type')
            ]
        ]);
    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Test upload failed: ' . $e->getMessage()
        ], 500);
    }
});

// TEST ROUTE: Create sample project with test data
Route::get('/create-test-project', function () {
    try {
        // Create a test project with all required fields including slug_project
        $testData = [
            'project_name' => 'Test Portfolio Project',
            'client_name' => 'Test Client Company', 
            'location' => 'Jakarta, Indonesia',
            'description' => '<h2>Test Project Description</h2><p>This is a test project created to verify the system functionality. <strong>Features include:</strong></p><ul><li>Auto-generated slug functionality</li><li>Image upload capabilities</li><li>Rich text editor</li><li>Project categorization</li></ul>',
            'summary_description' => 'A comprehensive test project to verify portfolio functionality',
            'project_category' => 'Web Application',
            'url_project' => 'https://example.com/test-project',
            'slug_project' => 'test-portfolio-project-web-application',
            'images' => json_encode(['default-project.jpg']),
            'featured_image' => 'default-project.jpg',
            'sequence' => 1,
            'status' => 'Active',
            'other_projects' => null,
            'created_at' => now(),
            'updated_at' => now()
        ];
        
        $result = DB::table('project')->insert($testData);
        
        if ($result) {
            return response()->json([
                'status' => 'success',
                'message' => 'Test project created successfully!',
                'data' => $testData,
                'project_list_url' => url('/project'),
                'project_detail_url' => url('/project/1/admin'),
                'project_edit_url' => url('/project/1/edit')
            ]);
        } else {
            throw new Exception('Failed to insert test project');
        }
        
    } catch (Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Failed to create test project: ' . $e->getMessage()
        ], 500);
    }
});

Route::get('/project', [ProjectController::class, 'index'])->name('project.index');
Route::get('/project/{id}/edit', [ProjectController::class, 'edit'])->name('project.edit');
Route::post('/project', [ProjectController::class, 'store'])->name('project.store');
Route::put('/project/{id}', [ProjectController::class, 'update'])->name('project.update');
Route::delete('/project/{id}', [ProjectController::class, 'destroy'])->name('project.destroy');
Route::get('/project/{id}/admin', [ProjectController::class, 'showAdmin'])->name('project.showAdmin');
Route::delete('/project/{id}/image', [ProjectController::class, 'deleteImage'])->name('project.deleteImage');
Route::get('/api/projects/search', [ProjectController::class, 'searchProjects'])->name('project.search');
// === END PROJECT ROUTES ===

// === DEBUGGING ROUTES - REMOVE AFTER TESTING ===
Route::get('/test-simple', function () {
    return 'Simple route works!';
});

Route::get('/test-project-direct', function () {
    return 'Project route test works!';
});

Route::get('/project/test123', function () {
    return 'Project namespace test works!';
});
// === END DEBUGGING ROUTES ===

// CLEAR ROUTE CACHE - REMOVE AFTER TESTING
Route::get('/clear-cache', function () {
    try {
        // Clear route cache
        Artisan::call('route:clear');
        $routeClearResult = Artisan::output();
        
        // Clear config cache
        Artisan::call('config:clear');
        $configClearResult = Artisan::output();
        
        // Clear view cache
        Artisan::call('view:clear');
        $viewClearResult = Artisan::output();
        
        return response()->json([
            'status' => 'success',
            'message' => 'All caches cleared successfully!',
            'results' => [
                'route_clear' => trim($routeClearResult),
                'config_clear' => trim($configClearResult),
                'view_clear' => trim($viewClearResult)
            ]
        ]);
    } catch (Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Failed to clear cache: ' . $e->getMessage()
        ], 500);
    }
});

// SIMPLE TEST ROUTE - REMOVE AFTER TESTING
Route::get('/test-route-works', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'Routes are working!',
        'timestamp' => now(),
        'test' => 'This route was added successfully'
    ]);
});

Route::get('/auth-status', function () {
    return response()->json([
        'authenticated' => Auth::check(),
        'user' => Auth::user(),
        'session_id' => session()->getId(),
        'csrf_token' => csrf_token()
    ]);
})->name('auth.status');

Route::get('/route-debug', function () {
    $routes = collect(Route::getRoutes())->map(function ($route) {
        return [
            'method' => implode('|', $route->methods()),
            'uri' => $route->uri(),
            'name' => $route->getName(),
            'action' => $route->getActionName(),
            'middleware' => $route->middleware()
        ];
    });
    
    $projectRoutes = $routes->filter(function ($route) {
        return str_contains($route['uri'], 'project') || str_contains($route['name'] ?? '', 'project');
    });
    
    return response()->json([
        'all_project_routes' => $projectRoutes->values(),
        'total_routes' => $routes->count(),
        'auth_middleware_routes' => $routes->filter(function($route) {
            return in_array('auth', $route['middleware'] ?? []);
        })->count()
    ]);
})->name('route.debug');

Route::get('/setup', function () {
    try {
        $results = [];
        
        // Check database connection
        try {
            DB::connection()->getPdo();
            $results['database'] = 'Connected successfully';
        } catch (Exception $e) {
            $results['database'] = 'Failed: ' . $e->getMessage();
            return response()->json(['error' => 'Database connection failed', 'details' => $results], 500);
        }
        
        // Check if project table exists
        try {
            $projectExists = Schema::hasTable('project');
            $results['project_table'] = $projectExists ? 'Exists' : 'Missing';
            
            if (!$projectExists) {
                // Create project table
                Schema::create('project', function (Blueprint $table) {
                    $table->id('id_project');
                    $table->string('project_name');
                    $table->string('client_name');
                    $table->string('location');
                    $table->text('description');
                    $table->string('summary_description', 500)->nullable();
                    $table->string('project_category');
                    $table->string('url_project')->nullable();
                    $table->string('slug_project')->unique();
                    $table->json('images')->nullable();
                    $table->string('featured_image')->nullable();
                    $table->string('status')->default('Active');
                    $table->integer('sequence')->default(0);
                    $table->text('other_projects')->nullable();
                    $table->timestamps();
                });
                $results['project_table'] = 'Created successfully';
            }
        } catch (Exception $e) {
            $results['project_table'] = 'Error: ' . $e->getMessage();
        }
        
        // Check/create users table and admin user
        try {
            $usersExists = Schema::hasTable('users');
            $results['users_table'] = $usersExists ? 'Exists' : 'Missing';
            
            if (!$usersExists) {
                Schema::create('users', function (Blueprint $table) {
                    $table->id();
                    $table->string('name');
                    $table->string('email')->unique();
                    $table->timestamp('email_verified_at')->nullable();
                    $table->string('password');
                    $table->rememberToken();
                    $table->timestamps();
                });
                $results['users_table'] = 'Created successfully';
            }
            
            // Check if admin user exists
            $adminExists = DB::table('users')->where('email', 'admin@portfolio.com')->exists();
            
            if (!$adminExists) {
                DB::table('users')->insert([
                    'name' => 'Administrator',
                    'email' => 'admin@portfolio.com',
                    'email_verified_at' => now(),
                    'password' => bcrypt('admin123'),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                $results['admin_user'] = 'Created successfully';
            } else {
                $results['admin_user'] = 'Already exists';
            }
            
        } catch (Exception $e) {
            $results['users_setup'] = 'Error: ' . $e->getMessage();
        }
        
        // Create necessary directories
        $directories = [
            'images/projects',
            'images/editor'
        ];
        
        foreach ($directories as $dir) {
            $fullPath = public_path($dir);
            if (!File::exists($fullPath)) {
                File::makeDirectory($fullPath, 0755, true);
                $results["directory_$dir"] = 'Created successfully';
            } else {
                $results["directory_$dir"] = 'Already exists';
            }
        }
        
        return response()->json([
            'status' => 'success',
            'message' => 'Setup completed successfully!',
            'results' => $results,
            'next_steps' => [
                '1. Visit /login to login with admin@portfolio.com / admin123',
                '2. After login, visit /project/create to create projects',
                '3. Visit /dashboard for admin panel'
            ]
        ]);
        
    } catch (Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Setup failed: ' . $e->getMessage(),
            'results' => $results ?? []
        ], 500);
    }
})->name('setup');

Route::get('/create-admin', function () {
    try {
        // Check if any user exists
        $userCount = DB::table('users')->count();
        
        if ($userCount > 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Admin user already exists. Total users: ' . $userCount
            ]);
        }
        
        // Create admin user
        $adminData = [
            'name' => 'Administrator',
            'email' => 'admin@portfolio.com',
            'email_verified_at' => now(),
            'password' => bcrypt('admin123'),
            'created_at' => now(),
            'updated_at' => now()
        ];
        
        $result = DB::table('users')->insert($adminData);
        
        if ($result) {
            return response()->json([
                'status' => 'success',
                'message' => 'Admin user created successfully!',
                'credentials' => [
                    'email' => 'admin@portfolio.com',
                    'password' => 'admin123'
                ],
                'login_url' => url('/login')
            ]);
        } else {
            throw new Exception('Failed to create admin user');
        }
        
    } catch (Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Error creating admin user: ' . $e->getMessage()
        ], 500);
    }
})->name('create.admin');

Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Add other routes only after confirming the basic ones work
Route::get('/gallery', [HomeWebController::class, 'gallery'])->name('gallery');
Route::get('/articles', [HomeWebController::class, 'articles'])->name('articles');
Route::get('/article/{slug}', [HomeWebController::class, 'articleDetail'])->name('article.detail');
Route::get('/portfolio', [HomeWebController::class, 'portfolio'])->name('portfolio');
Route::get('/portfolio/all', [HomeWebController::class, 'portfolioAll'])->name('portfolio.all');
Route::get('/portfolio/{slug}', [HomeWebController::class, 'portfolioDetail'])->name('portfolio.detail');
Route::get('/project/{slug}', [ProjectController::class, 'show'])->name('project.public.show');

// TEMPORARY: Unprotected project routes for testing - REMOVE IN PRODUCTION!
Route::get('/project-test/create_project', function () {
    try {
        $title = 'Tambah Portfolio (Test Mode)';
        
        // Ensure images directory exists
        $imageDir = public_path('images/projects');
        if (!File::exists($imageDir)) {
            File::makeDirectory($imageDir, 0755, true);
        }
        
        // Ensure editor directory exists
        $editorDir = public_path('images/editor');
        if (!File::exists($editorDir)) {
            File::makeDirectory($editorDir, 0755, true);
        }
        
        return view('project.create', compact('title'));
    } catch (Exception $e) {
        return response()->json([
            'error' => 'Failed to load create form: ' . $e->getMessage(),
            'suggestion' => 'Please check if the view file exists and there are no syntax errors'
        ], 500);
    }
})->name('project.test.create_project');

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
    
    // Project routes are now outside auth middleware for testing
    
    Route::resource('testimonial', TestimonialController::class);
    Route::resource('award', AwardController::class);
    Route::resource('contacts', ContactController::class);
    


    Route::get('/tentang-sistem', [SettingController::class, 'about'])->name('setting.about');
});
