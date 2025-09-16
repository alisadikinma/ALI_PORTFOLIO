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

// COMPREHENSIVE UPLOAD DEBUGGING ROUTES
Route::get('/debug-upload-system', function () {
    try {
        return response()->json([
            'status' => 'success',
            'message' => 'Upload system debug information',
            'system_info' => [
                'php_version' => PHP_VERSION,
                'laravel_version' => app()->version(),
                'upload_max_filesize' => ini_get('upload_max_filesize'),
                'post_max_size' => ini_get('post_max_size'),
                'max_execution_time' => ini_get('max_execution_time'),
                'memory_limit' => ini_get('memory_limit')
            ],
            'directories' => [
                'public_exists' => File::exists(public_path()),
                'images_exists' => File::exists(public_path('images')),
                'editor_exists' => File::exists(public_path('images/editor')),
                'projects_exists' => File::exists(public_path('images/projects')),
                'editor_writable' => is_writable(public_path('images/editor')),
                'projects_writable' => is_writable(public_path('images/projects'))
            ],
            'routes_available' => [
                'upload_editor_image' => Route::has('project.upload-editor-image.direct'),
                'upload_controller' => Route::has('project.upload-editor-image.controller'),
                'simple_upload' => true, // This is a closure route
            ],
            'csrf_token' => csrf_token(),
            'current_url' => url('/'),
            'base_url' => config('app.url')
        ]);
    } catch (Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Debug failed: ' . $e->getMessage()
        ], 500);
    }
});

// SIMPLE UPLOAD TEST ROUTE
Route::post('/debug-simple-upload', function (\Illuminate\Http\Request $request) {
    Log::info('=== DEBUG SIMPLE UPLOAD START ===');
    Log::info('Request method: ' . $request->method());
    Log::info('Content type: ' . $request->header('Content-Type'));
    Log::info('Has file: ' . ($request->hasFile('file') ? 'YES' : 'NO'));
    Log::info('All files: ' . json_encode(array_keys($request->allFiles())));
    Log::info('File details: ' . json_encode($_FILES));
    
    try {
        if (!$request->hasFile('file')) {
            throw new Exception('No file in request');
        }
        
        $file = $request->file('file');
        Log::info('File object: ' . get_class($file));
        Log::info('File valid: ' . ($file->isValid() ? 'YES' : 'NO'));
        Log::info('File error: ' . $file->getError());
        Log::info('File size: ' . $file->getSize());
        Log::info('File type: ' . $file->getMimeType());
        Log::info('File original name: ' . $file->getClientOriginalName());
        
        // Simple validation
        if (!$file->isValid()) {
            throw new Exception('File is not valid, error: ' . $file->getError());
        }
        
        if ($file->getSize() > 2048000) {
            throw new Exception('File too large: ' . $file->getSize() . ' bytes');
        }
        
        $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($file->getMimeType(), $allowedMimes)) {
            throw new Exception('Invalid file type: ' . $file->getMimeType());
        }
        
        // Create directory
        $uploadDir = public_path('images/editor');
        if (!File::exists($uploadDir)) {
            File::makeDirectory($uploadDir, 0755, true);
            Log::info('Created directory: ' . $uploadDir);
        }
        
        // Generate filename
        $extension = $file->getClientOriginalExtension();
        $filename = 'debug_' . time() . '_' . uniqid() . '.' . $extension;
        
        Log::info('Attempting to save file as: ' . $filename);
        
        // Save file
        $result = $file->move($uploadDir, $filename);
        
        if ($result) {
            $imageUrl = asset('images/editor/' . $filename);
            Log::info('File saved successfully: ' . $imageUrl);
            
            return response()->json([
                'success' => true,
                'url' => $imageUrl,
                'message' => 'Debug upload successful!',
                'filename' => $filename,
                'file_path' => $uploadDir . '/' . $filename,
                'file_exists' => File::exists($uploadDir . '/' . $filename)
            ]);
        } else {
            throw new Exception('Failed to move file');
        }
        
    } catch (Exception $e) {
        Log::error('Debug upload error: ' . $e->getMessage());
        Log::error('Exception trace: ' . $e->getTraceAsString());
        
        return response()->json([
            'success' => false,
            'message' => 'Debug upload failed: ' . $e->getMessage(),
            'debug_info' => [
                'request_method' => $request->method(),
                'content_type' => $request->header('Content-Type'),
                'has_file' => $request->hasFile('file'),
                'files_count' => count($request->allFiles()),
                'post_data' => $request->except(['file', '_token']),
                'server_info' => [
                    'upload_max_filesize' => ini_get('upload_max_filesize'),
                    'post_max_size' => ini_get('post_max_size'),
                    'max_file_uploads' => ini_get('max_file_uploads')
                ]
            ]
        ], 500);
    }
});

// DEBUGGING DASHBOARD
Route::get('/debug-dashboard', function () {
    return response()->file(public_path('debug-upload.html'));
});

// VIEW LARAVEL LOGS FOR DEBUGGING
Route::get('/debug-logs', function () {
    try {
        $logFile = storage_path('logs/laravel.log');
        
        if (!File::exists($logFile)) {
            return response()->json([
                'status' => 'info',
                'message' => 'No log file found',
                'log_path' => $logFile
            ]);
        }
        
        // Get last 50 lines of log
        $command = 'tail -n 50 ' . escapeshellarg($logFile);
        $output = shell_exec($command);
        
        if ($output === null) {
            // Fallback: read file directly
            $content = File::get($logFile);
            $lines = explode("\n", $content);
            $output = implode("\n", array_slice($lines, -50));
        }
        
        return response()->json([
            'status' => 'success',
            'message' => 'Last 50 lines of Laravel log',
            'log_path' => $logFile,
            'log_content' => $output,
            'file_size' => File::size($logFile),
            'last_modified' => date('Y-m-d H:i:s', File::lastModified($logFile))
        ]);
        
    } catch (Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Failed to read logs: ' . $e->getMessage()
        ], 500);
    }
});

// CHECK ROUTES EXISTENCE
Route::get('/debug-routes', function () {
    $allRoutes = collect(Route::getRoutes())->map(function ($route) {
        return [
            'method' => implode('|', $route->methods()),
            'uri' => $route->uri(),
            'name' => $route->getName(),
            'action' => $route->getActionName()
        ];
    });
    
    $uploadRoutes = $allRoutes->filter(function ($route) {
        return str_contains($route['uri'], 'upload') || 
               str_contains($route['name'] ?? '', 'upload') ||
               str_contains($route['action'], 'upload');
    });
    
    return response()->json([
        'status' => 'success',
        'upload_routes' => $uploadRoutes->values(),
        'total_routes' => $allRoutes->count(),
        'test_urls' => [
            'debug_upload' => url('/debug-simple-upload'),
            'simple_upload' => url('/upload-image'),
            'editor_upload' => url('/project/upload-editor-image'),
            'controller_upload' => url('/upload-editor-image-controller'),
            'php_upload' => url('/test-upload.php')
        ]
    ]);
});

// TEST UPLOAD ROUTE AVAILABILITY
Route::get('/test-upload-availability', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'Upload test route is accessible!',
        'available_routes' => [
            'direct_upload' => url('/project/upload-editor-image'),
            'controller_upload' => url('/upload-editor-image-controller'),
            'simple_upload' => url('/upload-image')
        ],
        'directory_status' => [
            'images_exists' => File::exists(public_path('images')),
            'projects_exists' => File::exists(public_path('images/projects')),
            'editor_exists' => File::exists(public_path('images/editor')),
            'editor_writable' => is_writable(public_path('images/editor')) || !File::exists(public_path('images/editor'))
        ],
        'csrf_token' => csrf_token()
    ]);
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

// TEST SIMPLE INSERT ROUTE
Route::get('/test-simple-insert', function () {
    try {
        // Try inserting a very simple project to isolate the issue
        $testData = [
            'project_name' => 'Simple Test Project',
            'client_name' => 'Simple Test Client',
            'location' => 'Test Location',
            'description' => 'Simple test description',
            'project_category' => 'Test',
            'slug_project' => 'simple-test-' . time(),
            'images' => json_encode(['test.jpg']),
            'featured_image' => 'test.jpg',
            'sequence' => 1,
            'status' => 'Active',
            'other_projects' => null, // Start with null
            'created_at' => now(),
            'updated_at' => now()
        ];
        
        $result = DB::table('project')->insert($testData);
        
        if ($result) {
            // Now try with the problematic value
            $testData2 = $testData;
            $testData2['project_name'] = 'Test with Other Projects';
            $testData2['slug_project'] = 'test-other-projects-' . time();
            $testData2['other_projects'] = 'BUS Request MYSATNUSA'; // The problematic value
            
            $result2 = DB::table('project')->insert($testData2);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Both test inserts successful!',
                'results' => [
                    'simple_insert' => $result,
                    'with_other_projects' => $result2
                ],
                'next_step' => 'Try creating your project again: ' . url('/project/create_project')
            ]);
        }
        
    } catch (Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Test insert failed: ' . $e->getMessage(),
            'error_code' => $e->getCode(),
            'sql_state' => method_exists($e, 'errorInfo') ? $e->errorInfo : 'N/A',
            'suggestion' => 'Try the constraint fix: ' . url('/fix-other-projects-constraint')
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
