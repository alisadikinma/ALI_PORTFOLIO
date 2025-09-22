<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GaleriController;
use App\Http\Controllers\Api\AdminApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Gallery API Routes
Route::prefix('gallery')->group(function () {
    Route::get('{galleryId}/items', [GaleriController::class, 'getGalleryItems']);
    Route::get('item/{itemId}', [GaleriController::class, 'showItem']);
});

// Admin API Routes - Protected by authentication
Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
    // Dashboard & Statistics
    Route::get('dashboard/stats', [AdminApiController::class, 'getDashboardStats']);
    Route::get('system/health', [AdminApiController::class, 'getSystemHealth']);
    Route::get('performance/metrics', [AdminApiController::class, 'getPerformanceMetrics']);
    Route::get('content/stats', [AdminApiController::class, 'getContentStats']);
    Route::get('activity/recent', [AdminApiController::class, 'getRecentActivity']);
    Route::get('activity/users', [AdminApiController::class, 'getUserActivity']);

    // Reports
    Route::post('reports/generate', [AdminApiController::class, 'generateReport']);

    // System Operations
    Route::post('system/cache/clear', [AdminApiController::class, 'clearCaches']);
    Route::post('system/database/optimize', [AdminApiController::class, 'optimizeDatabase']);
    Route::post('system/maintenance/toggle', [AdminApiController::class, 'toggleMaintenanceMode']);

    // Content Management
    Route::post('projects/sequence/update', [AdminApiController::class, 'updateProjectSequence']);
});

// Public API Routes (Rate Limited)
Route::middleware(['throttle:api'])->group(function () {
    // Public content endpoints can be added here
    Route::get('projects/featured', function() {
        return \App\Services\ProjectService::getFeaturedProjects(6);
    });

    Route::get('testimonials/featured', function() {
        return \App\Services\ContentService::getFeaturedTestimonials(6);
    });

    Route::get('awards/recent', function() {
        return \App\Services\ContentService::getRecentAwards(6);
    });
});
