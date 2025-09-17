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
use Illuminate\Support\Facades\Log;
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
Route::get('/portfolio/all', function () {
    try {
        $controller = new HomeWebController();
        return $controller->portfolio();
    } catch (Exception $e) {
        return view('portfolio_all', [
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
        $title = 'Tambah Portfolio';
        
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

// ENHANCED EDITOR IMAGE UPLOAD ROUTE WITH DEBUGGING
Route::post('/project/upload-editor-image', function (\Illuminate\Http\Request $request) {
    try {
        Log::info('Upload route accessed directly');
        
        // Validate the uploaded file
        $request->validate([
            'file' => 'required|image|mimes:jpeg,jpg,png,gif,webp|max:2048'
        ]);

        // Create editor directory if it doesn't exist
        $editorDir = public_path('images/editor');
        if (!File::exists($editorDir)) {
            File::makeDirectory($editorDir, 0755, true);
        }

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            $filename = 'editor_' . time() . '_' . uniqid() . '.' . $extension;
            
            // Move file to editor directory
            if ($file->move($editorDir, $filename)) {
                $imageUrl = asset('images/editor/' . $filename);
                
                return response()->json([
                    'success' => true,
                    'url' => $imageUrl,
                    'message' => 'Image uploaded successfully',
                    'filename' => $filename,
                    'debug' => 'Direct route worked!'
                ]);
            }
        }

        throw new Exception('Upload failed');

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Validation failed: ' . implode(', ', $e->errors()['file'] ?? ['Invalid file']),
            'errors' => $e->errors()
        ], 422);
    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Upload failed: ' . $e->getMessage()
        ], 500);
    }
})->name('project.upload-editor-image.direct');

// ORIGINAL CONTROLLER-BASED ROUTE
Route::post('/upload-editor-image-controller', [ProjectController::class, 'uploadEditorImage'])->name('project.upload-editor-image.controller');

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

// FIND AND REMOVE SPECIFIC CONSTRAINT
Route::get('/remove-other-projects-check-constraint', function () {
    try {
        // Get all constraints for the project table
        $constraints = DB::select("
            SELECT 
                CONSTRAINT_NAME, 
                CONSTRAINT_TYPE,
                CHECK_CLAUSE
            FROM information_schema.TABLE_CONSTRAINTS tc
            LEFT JOIN information_schema.CHECK_CONSTRAINTS cc 
                ON tc.CONSTRAINT_NAME = cc.CONSTRAINT_NAME 
                AND tc.CONSTRAINT_SCHEMA = cc.CONSTRAINT_SCHEMA
            WHERE tc.TABLE_SCHEMA = ? 
            AND tc.TABLE_NAME = 'project'
            AND (tc.CONSTRAINT_TYPE = 'CHECK' OR tc.CONSTRAINT_NAME LIKE '%other_projects%')
        ", [env('DB_DATABASE', 'portfolio_db')]);
        
        $removedConstraints = [];
        
        // Try to remove any constraints that might be related to other_projects
        $possibleConstraintNames = [
            'project_other_projects',
            'other_projects',
            'chk_other_projects',
            'project_chk_other_projects'
        ];
        
        foreach ($possibleConstraintNames as $constraintName) {
            try {
                DB::statement("ALTER TABLE project DROP CONSTRAINT `{$constraintName}`");
                $removedConstraints[] = $constraintName;
            } catch (Exception $e) {
                // Constraint doesn't exist or already removed
            }
        }
        
        // Also try the MySQL syntax for dropping check constraints
        foreach ($possibleConstraintNames as $constraintName) {
            try {
                DB::statement("ALTER TABLE project DROP CHECK `{$constraintName}`");
                $removedConstraints[] = $constraintName . " (CHECK)";
            } catch (Exception $e) {
                // Constraint doesn't exist
            }
        }
        
        // Now recreate the column without any constraints
        DB::statement("ALTER TABLE project MODIFY COLUMN other_projects LONGTEXT NULL");
        
        return response()->json([
            'status' => 'success',
            'message' => 'Attempted to remove all possible constraints on other_projects column',
            'found_constraints' => $constraints,
            'removed_constraints' => $removedConstraints,
            'final_action' => 'Modified other_projects column to LONGTEXT NULL',
            'test_url' => url('/project/create_project')
        ]);
        
    } catch (Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Failed to remove constraints: ' . $e->getMessage(),
            'suggestion' => 'Try the manual SQL approach: ' . url('/manual-fix-other-projects')
        ], 500);
    }
});

// MANUAL SQL FIX ROUTE
Route::get('/manual-fix-other-projects', function () {
    try {
        // Get the current CREATE TABLE statement to see the exact constraint
        $createTable = DB::select("SHOW CREATE TABLE project");
        
        // Try a more aggressive approach - drop and recreate the column
        DB::statement("ALTER TABLE project DROP COLUMN other_projects");
        DB::statement("ALTER TABLE project ADD COLUMN other_projects LONGTEXT NULL");
        
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully dropped and recreated other_projects column without any constraints',
            'previous_schema' => $createTable,
            'action' => 'Dropped and recreated other_projects column as LONGTEXT NULL',
            'test_url' => url('/project/create_project')
        ]);
        
    } catch (Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Manual fix failed: ' . $e->getMessage(),
            'suggestion' => 'You may need to manually run SQL commands in phpMyAdmin',
            'sql_commands' => [
                'ALTER TABLE project DROP COLUMN other_projects;',
                'ALTER TABLE project ADD COLUMN other_projects LONGTEXT NULL;'
            ]
        ], 500);
    }
});

// FIX OTHER_PROJECTS CONSTRAINT ROUTE
Route::get('/fix-other-projects-constraint', function () {
    try {
        // Check current column definition
        $currentSchema = DB::select("SHOW CREATE TABLE project");
        
        // The issue might be with utf8mb4_bin collation, let's change it to utf8mb4_unicode_ci
        DB::statement("ALTER TABLE project MODIFY COLUMN other_projects LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL");
        
        // Also check if there are any hidden check constraints and remove them
        try {
            DB::statement("ALTER TABLE project DROP CHECK project_other_projects");
        } catch (Exception $e) {
            // Constraint might not exist, that's fine
        }
        
        return response()->json([
            'status' => 'success',
            'message' => 'other_projects column constraint fixed!',
            'actions' => [
                'Modified other_projects column collation to utf8mb4_unicode_ci',
                'Attempted to remove any check constraints'
            ],
            'previous_schema' => $currentSchema,
            'test_urls' => [
                'create_project' => url('/project/create_project'),
                'schema_check' => url('/check-project-schema')
            ]
        ]);
        
    } catch (Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Failed to fix constraint: ' . $e->getMessage(),
            'suggestion' => 'Try checking the schema first: ' . url('/check-project-schema'),
            'alternative_solution' => 'Try the simple test: ' . url('/test-simple-insert')
        ], 500);
    }
});

// DATABASE SCHEMA CHECK ROUTE
Route::get('/check-project-schema', function () {
    try {
        // Get table schema
        $tableInfo = DB::select("DESCRIBE project");
        
        // Get constraints
        $constraints = DB::select("
            SELECT CONSTRAINT_NAME, CONSTRAINT_TYPE, CHECK_CLAUSE 
            FROM information_schema.CHECK_CONSTRAINTS 
            WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ?
        ", [env('DB_DATABASE', 'portfolio_db'), 'project']);
        
        // Try to insert a simple test record to see what fails
        $testData = [
            'project_name' => 'Test Project',
            'client_name' => 'Test Client',
            'location' => 'Test Location',
            'description' => 'Test Description',
            'summary_description' => 'Test Summary',
            'project_category' => 'Test Category',
            'slug_project' => 'test-project-' . time(),
            'images' => json_encode(['test.jpg']),
            'featured_image' => 'test.jpg',
            'sequence' => 1,
            'status' => 'Active',
            'other_projects' => null, // Try with null first
            'created_at' => now(),
            'updated_at' => now()
        ];
        
        return response()->json([
            'status' => 'success',
            'table_info' => $tableInfo,
            'constraints' => $constraints,
            'test_data' => $testData,
            'message' => 'Schema information retrieved successfully'
        ]);
        
    } catch (Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Schema check failed: ' . $e->getMessage(),
            'error_details' => [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]
        ], 500);
    }
});

// MAIN PROJECT UPLOAD ROUTE
Route::post('/project/upload-editor-image', function (\Illuminate\Http\Request $request) {
    try {
        Log::info('Upload route accessed via /project/upload-editor-image');
        
        // Validate the uploaded file
        $request->validate([
            'file' => 'required|image|mimes:jpeg,jpg,png,gif,webp|max:2048'
        ]);

        // Create editor directory if it doesn't exist
        $editorDir = public_path('images/editor');
        if (!File::exists($editorDir)) {
            File::makeDirectory($editorDir, 0755, true);
        }

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            $filename = 'editor_' . time() . '_' . uniqid() . '.' . $extension;
            
            // Move file to editor directory
            if ($file->move($editorDir, $filename)) {
                $imageUrl = asset('images/editor/' . $filename);
                
                return response()->json([
                    'success' => true,
                    'url' => $imageUrl,
                    'message' => 'Image uploaded successfully',
                    'filename' => $filename,
                    'route' => 'main upload route'
                ]);
            }
        }

        throw new Exception('Upload failed');

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Validation failed: ' . implode(', ', $e->errors()['file'] ?? ['Invalid file']),
            'errors' => $e->errors()
        ], 422);
    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Upload failed: ' . $e->getMessage()
        ], 500);
    }
})->name('project.upload-editor-image');

Route::get('/project', [ProjectController::class, 'index'])->name('project.index');
Route::get('/project/{id}/edit', [ProjectController::class, 'edit'])->name('project.edit');
Route::post('/project', [ProjectController::class, 'store'])->name('project.store');
Route::put('/project/{id}', [ProjectController::class, 'update'])->name('project.update');
Route::delete('/project/{id}', [ProjectController::class, 'destroy'])->name('project.destroy');
Route::get('/project/{id}/admin', [ProjectController::class, 'showAdmin'])->name('project.showAdmin');
Route::delete('/project/{id}/image', [ProjectController::class, 'deleteImage'])->name('project.deleteImage');
Route::get('/api/projects/search', [ProjectController::class, 'searchProjects'])->name('project.search');

// ADDITIONAL SEARCH ROUTE FOR OTHER PROJECTS AUTOCOMPLETE
Route::get('/projects/search', [ProjectController::class, 'searchProjects'])->name('projects.search.autocomplete');
// === END PROJECT ROUTES ===


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
Route::get('/portfolio/all', [HomeWebController::class, 'portfolio'])->name('portfolio');
Route::get('/portfolio/all/paginated', [HomeWebController::class, 'portfolioAll'])->name('portfolio.all');
Route::get('/portfolio/{slug}', [HomeWebController::class, 'portfolioDetail'])->name('portfolio.detail');
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
    
    // Project routes are now outside auth middleware for testing
    
    Route::resource('testimonial', TestimonialController::class);
    Route::resource('award', AwardController::class);
    Route::resource('contacts', ContactController::class);
    


    Route::get('/tentang-sistem', [SettingController::class, 'about'])->name('setting.about');
});
