<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;

class PerformanceController extends Controller
{
    /**
     * Receive performance metrics from client-side monitoring
     */
    public function receiveMetrics(Request $request): JsonResponse
    {
        try {
            $metrics = $request->all();

            // Validate required fields
            if (!isset($metrics['metrics']) || !isset($metrics['page'])) {
                return response()->json(['error' => 'Invalid metrics data'], 400);
            }

            // Extract Core Web Vitals
            $coreWebVitals = [];
            if (isset($metrics['metrics']['lcp'])) {
                $coreWebVitals['lcp'] = $metrics['metrics']['lcp'];
            }
            if (isset($metrics['metrics']['fid'])) {
                $coreWebVitals['fid'] = $metrics['metrics']['fid'];
            }
            if (isset($metrics['metrics']['cls'])) {
                $coreWebVitals['cls'] = $metrics['metrics']['cls'];
            }

            // Log performance metrics
            Log::channel('performance')->info('Client Performance Metrics', [
                'core_web_vitals' => $coreWebVitals,
                'navigation' => $metrics['metrics']['navigation'] ?? null,
                'resources' => $metrics['metrics']['resources'] ?? null,
                'page_url' => $metrics['page']['url'] ?? null,
                'user_agent' => $metrics['page']['userAgent'] ?? null,
                'viewport' => $metrics['viewport'] ?? null,
                'connection' => $metrics['connection'] ?? null,
                'ip' => $request->ip(),
                'timestamp' => now()->toISOString(),
            ]);

            // Check for performance issues and alert
            $this->analyzePerformanceIssues($coreWebVitals, $metrics);

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error('Performance metrics processing error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json(['error' => 'Processing failed'], 500);
        }
    }

    /**
     * Analyze performance metrics and log issues
     */
    private function analyzePerformanceIssues(array $coreWebVitals, array $allMetrics): void
    {
        $issues = [];

        // Analyze LCP (Largest Contentful Paint)
        if (isset($coreWebVitals['lcp'])) {
            $lcp = $coreWebVitals['lcp'];
            if ($lcp['rating'] === 'poor') {
                $issues[] = "Poor LCP: {$lcp['value']}ms (should be ≤2500ms)";
            }
        }

        // Analyze FID (First Input Delay)
        if (isset($coreWebVitals['fid'])) {
            $fid = $coreWebVitals['fid'];
            if ($fid['rating'] === 'poor') {
                $issues[] = "Poor FID: {$fid['value']}ms (should be ≤100ms)";
            }
        }

        // Analyze CLS (Cumulative Layout Shift)
        if (isset($coreWebVitals['cls'])) {
            $cls = $coreWebVitals['cls'];
            if ($cls['rating'] === 'poor') {
                $issues[] = "Poor CLS: {$cls['value']} (should be ≤0.1)";
            }
        }

        // Analyze navigation timing
        if (isset($allMetrics['metrics']['navigation'])) {
            $nav = $allMetrics['metrics']['navigation'];
            if ($nav['total'] > 5000) { // 5 seconds
                $issues[] = "Slow page load: {$nav['total']}ms";
            }
        }

        // Analyze resource loading
        if (isset($allMetrics['metrics']['resources'])) {
            $resources = $allMetrics['metrics']['resources'];
            if (!empty($resources['slowResources'])) {
                $slowCount = count($resources['slowResources']);
                $issues[] = "Slow resources detected: {$slowCount} resources >1000ms";
            }
        }

        // Log issues if found
        if (!empty($issues)) {
            Log::channel('performance')->warning('Client Performance Issues Detected', [
                'issues' => $issues,
                'page_url' => $allMetrics['page']['url'] ?? null,
                'user_agent' => $allMetrics['page']['userAgent'] ?? null,
                'timestamp' => now()->toISOString(),
            ]);
        }
    }

    /**
     * Get performance statistics (for admin dashboard)
     */
    public function getStatistics(Request $request): JsonResponse
    {
        // This would typically query a database of stored metrics
        // For now, return sample data structure

        $stats = [
            'core_web_vitals' => [
                'lcp' => [
                    'average' => 1200,
                    'p75' => 1800,
                    'good_percentage' => 85,
                    'needs_improvement_percentage' => 12,
                    'poor_percentage' => 3,
                ],
                'fid' => [
                    'average' => 45,
                    'p75' => 80,
                    'good_percentage' => 92,
                    'needs_improvement_percentage' => 6,
                    'poor_percentage' => 2,
                ],
                'cls' => [
                    'average' => 0.05,
                    'p75' => 0.08,
                    'good_percentage' => 88,
                    'needs_improvement_percentage' => 10,
                    'poor_percentage' => 2,
                ],
            ],
            'page_performance' => [
                'average_load_time' => 1850,
                'bounce_rate' => 15.2,
                'user_engagement' => 78.5,
            ],
            'recommendations' => [
                'Optimize images for faster loading',
                'Implement lazy loading for below-fold content',
                'Minimize layout shifts during page load',
            ],
        ];

        return response()->json($stats);
    }
}