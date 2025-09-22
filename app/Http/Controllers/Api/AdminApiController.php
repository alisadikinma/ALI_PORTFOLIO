<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AdminService;
use App\Services\ProjectService;
use App\Services\ContentService;
use App\Services\SettingService;
use App\Services\DatabaseOptimizationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Exception;

/**
 * Admin API Controller - Provides API endpoints for admin dashboard
 */
class AdminApiController extends Controller
{
    /**
     * Get dashboard statistics
     */
    public function getDashboardStats(): JsonResponse
    {
        try {
            $stats = AdminService::getDashboardStats();

            return response()->json([
                'success' => true,
                'data' => $stats,
                'timestamp' => now()->toISOString(),
            ]);

        } catch (Exception $e) {
            Log::error('Dashboard stats API error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to load dashboard statistics',
                'error' => app()->environment('production') ? 'Internal server error' : $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get system health status
     */
    public function getSystemHealth(): JsonResponse
    {
        try {
            $health = AdminService::getSystemHealth();

            return response()->json([
                'success' => true,
                'data' => $health,
                'timestamp' => now()->toISOString(),
            ]);

        } catch (Exception $e) {
            Log::error('System health API error', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to check system health',
                'data' => [
                    'overall_status' => 'error',
                    'score' => 0,
                    'checks' => [
                        'api' => ['status' => 'error', 'message' => 'API error occurred']
                    ]
                ]
            ], 500);
        }
    }

    /**
     * Get performance metrics
     */
    public function getPerformanceMetrics(): JsonResponse
    {
        try {
            $metrics = [
                'database' => DatabaseOptimizationService::getDatabaseStatistics(),
                'cache' => $this->getCacheMetrics(),
                'content' => ContentService::getContentStatistics(),
                'projects' => ProjectService::getProjectStatistics(),
            ];

            return response()->json([
                'success' => true,
                'data' => $metrics,
                'timestamp' => now()->toISOString(),
            ]);

        } catch (Exception $e) {
            Log::error('Performance metrics API error', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to load performance metrics',
                'error' => app()->environment('production') ? 'Internal server error' : $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Generate admin report
     */
    public function generateReport(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'type' => 'required|in:overview,content,performance,users',
                'period' => 'nullable|integer|min:1|max:365',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid request parameters',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $type = $request->get('type', 'overview');
            $period = $request->get('period', 30);

            $report = AdminService::generateReport($type, $period);

            return response()->json([
                'success' => true,
                'data' => $report,
                'timestamp' => now()->toISOString(),
            ]);

        } catch (Exception $e) {
            Log::error('Report generation API error', [
                'error' => $e->getMessage(),
                'type' => $request->get('type'),
                'period' => $request->get('period'),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to generate report',
                'error' => app()->environment('production') ? 'Internal server error' : $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Clear admin caches
     */
    public function clearCaches(): JsonResponse
    {
        try {
            AdminService::clearAdminCaches();
            ContentService::clearAllContentCaches();

            Log::info('Admin caches cleared via API', [
                'user' => auth()->user()->email ?? 'unknown',
                'ip' => request()->ip(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'All caches cleared successfully',
                'timestamp' => now()->toISOString(),
            ]);

        } catch (Exception $e) {
            Log::error('Cache clearing API error', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to clear caches',
                'error' => app()->environment('production') ? 'Internal server error' : $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Optimize database
     */
    public function optimizeDatabase(): JsonResponse
    {
        try {
            $results = [
                'indexes' => DatabaseOptimizationService::createAllIndexes(),
                'tables' => DatabaseOptimizationService::optimizeTables(),
                'analysis' => DatabaseOptimizationService::checkMissingIndexes(),
            ];

            Log::info('Database optimization completed via API', [
                'user' => auth()->user()->email ?? 'unknown',
                'results' => $results,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Database optimization completed',
                'data' => $results,
                'timestamp' => now()->toISOString(),
            ]);

        } catch (Exception $e) {
            Log::error('Database optimization API error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Database optimization failed',
                'error' => app()->environment('production') ? 'Internal server error' : $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Toggle maintenance mode
     */
    public function toggleMaintenanceMode(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'enabled' => 'required|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid request parameters',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $enabled = $request->get('enabled');
            $result = SettingService::toggleMaintenanceMode($enabled);

            Log::info('Maintenance mode toggled via API', [
                'enabled' => $result,
                'user' => auth()->user()->email ?? 'unknown',
                'ip' => request()->ip(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Maintenance mode ' . ($result ? 'enabled' : 'disabled'),
                'data' => ['maintenance_mode' => $result],
                'timestamp' => now()->toISOString(),
            ]);

        } catch (Exception $e) {
            Log::error('Maintenance mode toggle API error', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to toggle maintenance mode',
                'error' => app()->environment('production') ? 'Internal server error' : $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get content statistics
     */
    public function getContentStats(): JsonResponse
    {
        try {
            $stats = ContentService::getContentStatistics();

            return response()->json([
                'success' => true,
                'data' => $stats,
                'timestamp' => now()->toISOString(),
            ]);

        } catch (Exception $e) {
            Log::error('Content stats API error', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to load content statistics',
                'error' => app()->environment('production') ? 'Internal server error' : $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Bulk update project sequence
     */
    public function updateProjectSequence(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'sequence_data' => 'required|array',
                'sequence_data.*' => 'integer|min:0',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid sequence data',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $sequenceData = $request->get('sequence_data');
            ProjectService::bulkUpdateSequence($sequenceData);

            Log::info('Project sequence updated via API', [
                'updated_count' => count($sequenceData),
                'user' => auth()->user()->email ?? 'unknown',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Project sequence updated successfully',
                'data' => ['updated_count' => count($sequenceData)],
                'timestamp' => now()->toISOString(),
            ]);

        } catch (Exception $e) {
            Log::error('Project sequence update API error', [
                'error' => $e->getMessage(),
                'sequence_data' => $request->get('sequence_data', [])
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update project sequence',
                'error' => app()->environment('production') ? 'Internal server error' : $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get recent activity
     */
    public function getRecentActivity(): JsonResponse
    {
        try {
            $activity = AdminService::getDashboardStats()['recent_activity'] ?? [];

            return response()->json([
                'success' => true,
                'data' => $activity,
                'timestamp' => now()->toISOString(),
            ]);

        } catch (Exception $e) {
            Log::error('Recent activity API error', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to load recent activity',
                'error' => app()->environment('production') ? 'Internal server error' : $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get user activity summary
     */
    public function getUserActivity(Request $request): JsonResponse
    {
        try {
            $days = $request->get('days', 30);
            $activity = AdminService::getUserActivity($days);

            return response()->json([
                'success' => true,
                'data' => $activity,
                'period' => $days . ' days',
                'timestamp' => now()->toISOString(),
            ]);

        } catch (Exception $e) {
            Log::error('User activity API error', [
                'error' => $e->getMessage(),
                'days' => $request->get('days')
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to load user activity',
                'error' => app()->environment('production') ? 'Internal server error' : $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Private helper methods
     */
    private function getCacheMetrics()
    {
        try {
            // Basic cache metrics
            return [
                'driver' => config('cache.default'),
                'status' => 'working',
                'test_successful' => $this->testCache(),
            ];
        } catch (Exception $e) {
            return [
                'driver' => config('cache.default'),
                'status' => 'error',
                'error' => $e->getMessage(),
                'test_successful' => false,
            ];
        }
    }

    private function testCache()
    {
        try {
            $testKey = 'api_cache_test_' . time();
            cache([$testKey => 'test_value'], 60);
            $result = cache($testKey) === 'test_value';
            cache()->forget($testKey);
            return $result;
        } catch (Exception $e) {
            return false;
        }
    }
}