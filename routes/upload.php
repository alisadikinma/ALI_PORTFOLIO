<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| PROFESSIONAL CSRF-PROTECTED UPLOAD ROUTES
|--------------------------------------------------------------------------
| Secure file upload endpoints for admin panel functionality
*/

// Professional Project Image Upload (CSRF Protected)
Route::post('/admin/upload/project-editor-image', function (Request $request) {
    try {
        Log::info('Professional upload route accessed', [
            'method' => $request->method(),
            'has_file' => $request->hasFile('file'),
            'csrf_token' => $request->header('X-CSRF-TOKEN') ? 'present' : 'missing',
            'ip' => $request->ip()
        ]);

        // Validate CSRF token is present
        if (!$request->header('X-CSRF-TOKEN') && !$request->input('_token')) {
            Log::warning('Upload attempt without CSRF token', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'CSRF token missing. Please refresh the page and try again.',
                'error_code' => 'CSRF_MISSING'
            ], 422);
        }

        // Validate the uploaded file
        $request->validate([
            'file' => 'required|image|mimes:jpeg,jpg,png,gif,webp|max:2048'
        ], [
            'file.required' => 'Image file is required',
            'file.image' => 'File must be an image',
            'file.mimes' => 'File must be: JPEG, JPG, PNG, GIF, or WebP',
            'file.max' => 'File size must be less than 2MB'
        ]);

        // Create secure upload directory
        $editorDir = public_path('images/editor');
        if (!File::exists($editorDir)) {
            File::makeDirectory($editorDir, 0755, true);
            Log::info('Created editor directory', ['path' => $editorDir]);
        }

        if ($request->hasFile('file')) {
            $file = $request->file('file');

            // Generate secure filename
            $extension = $file->getClientOriginalExtension();
            $filename = 'editor_' . time() . '_' . uniqid() . '_' . bin2hex(random_bytes(8)) . '.' . $extension;

            // Security check: Validate file type by content
            $mimeType = $file->getMimeType();
            $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];

            if (!in_array($mimeType, $allowedMimes)) {
                Log::warning('Invalid file type uploaded', [
                    'filename' => $file->getClientOriginalName(),
                    'mime_type' => $mimeType,
                    'ip' => $request->ip()
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Invalid file type. Only images are allowed.',
                    'error_code' => 'INVALID_FILE_TYPE'
                ], 422);
            }

            // Move file securely
            if ($file->move($editorDir, $filename)) {
                $imageUrl = asset('images/editor/' . $filename);

                Log::info('Professional image upload successful', [
                    'filename' => $filename,
                    'original_name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                    'mime_type' => $mimeType,
                    'url' => $imageUrl,
                    'ip' => $request->ip()
                ]);

                return response()->json([
                    'success' => true,
                    'url' => $imageUrl,
                    'filename' => $filename,
                    'message' => 'Image uploaded successfully',
                    'upload_info' => [
                        'original_name' => $file->getClientOriginalName(),
                        'size' => $file->getSize(),
                        'type' => $mimeType
                    ]
                ]);
            } else {
                throw new \Exception('Failed to move uploaded file to destination');
            }
        }

        throw new \Exception('No file was uploaded');

    } catch (\Illuminate\Validation\ValidationException $e) {
        Log::error('Upload validation failed', [
            'errors' => $e->errors(),
            'ip' => $request->ip()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Validation failed: ' . implode(', ', $e->errors()['file'] ?? ['Invalid file']),
            'errors' => $e->errors(),
            'error_code' => 'VALIDATION_FAILED'
        ], 422);

    } catch (\Exception $e) {
        Log::error('Professional upload error', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'ip' => $request->ip()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Upload failed: ' . $e->getMessage(),
            'error_code' => 'UPLOAD_FAILED'
        ], 500);
    }
})->middleware(['web', 'auth', 'admin.security'])->name('admin.upload.project-editor-image');

// Simple Debug Upload (For Testing - Remove in Production)
Route::post('/debug-simple-upload', function (Request $request) {
    try {
        Log::info('Debug upload route accessed');

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
            $filename = 'debug_' . time() . '_' . uniqid() . '.' . $extension;

            if ($file->move($editorDir, $filename)) {
                $imageUrl = asset('images/editor/' . $filename);

                return response()->json([
                    'success' => true,
                    'url' => $imageUrl,
                    'filename' => $filename,
                    'message' => 'Debug upload successful!',
                    'debug_info' => 'This is a debug route - should be removed in production'
                ]);
            }
        }

        throw new \Exception('Debug upload failed');

    } catch (\Exception $e) {
        Log::error('Debug upload error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Debug upload failed: ' . $e->getMessage()
        ], 500);
    }
})->middleware('web')->name('debug.upload');

// Gallery Image Upload (CSRF Protected)
Route::post('/admin/upload/gallery-image', function (Request $request) {
    try {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,jpg,png,gif,webp|max:5120' // 5MB for gallery
        ]);

        $galleryDir = public_path('images/galeri');
        if (!File::exists($galleryDir)) {
            File::makeDirectory($galleryDir, 0755, true);
        }

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            $filename = 'gallery_' . time() . '_' . uniqid() . '.' . $extension;

            if ($file->move($galleryDir, $filename)) {
                $imageUrl = asset('images/galeri/' . $filename);

                Log::info('Gallery image uploaded', [
                    'filename' => $filename,
                    'url' => $imageUrl
                ]);

                return response()->json([
                    'success' => true,
                    'url' => $imageUrl,
                    'filename' => $filename,
                    'message' => 'Gallery image uploaded successfully'
                ]);
            }
        }

        throw new \Exception('Gallery upload failed');

    } catch (\Exception $e) {
        Log::error('Gallery upload error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Gallery upload failed: ' . $e->getMessage()
        ], 500);
    }
})->middleware(['web', 'auth', 'admin.security'])->name('admin.upload.gallery-image');

// Award Image Upload (CSRF Protected)
Route::post('/admin/upload/award-image', function (Request $request) {
    try {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,jpg,png,gif,webp|max:2048'
        ]);

        $awardDir = public_path('images/award');
        if (!File::exists($awardDir)) {
            File::makeDirectory($awardDir, 0755, true);
        }

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            $filename = 'award_' . time() . '_' . uniqid() . '.' . $extension;

            if ($file->move($awardDir, $filename)) {
                $imageUrl = asset('images/award/' . $filename);

                Log::info('Award image uploaded', [
                    'filename' => $filename,
                    'url' => $imageUrl
                ]);

                return response()->json([
                    'success' => true,
                    'url' => $imageUrl,
                    'filename' => $filename,
                    'message' => 'Award image uploaded successfully'
                ]);
            }
        }

        throw new \Exception('Award upload failed');

    } catch (\Exception $e) {
        Log::error('Award upload error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Award upload failed: ' . $e->getMessage()
        ], 500);
    }
})->middleware(['web', 'auth', 'admin.security'])->name('admin.upload.award-image');