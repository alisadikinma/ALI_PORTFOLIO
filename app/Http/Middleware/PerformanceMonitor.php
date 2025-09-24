<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class PerformanceMonitor
{
    /**
     * Handle an incoming request and monitor performance metrics.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Start performance monitoring
        $startTime = microtime(true);
        $startMemory = memory_get_usage(true);

        // Enable query logging for performance analysis
        DB::enableQueryLog();

        // Process the request
        $response = $next($request);

        // Calculate performance metrics
        $executionTime = (microtime(true) - $startTime) * 1000; // Convert to milliseconds
        $memoryUsage = memory_get_usage(true) - $startMemory;
        $peakMemory = memory_get_peak_usage(true);
        $queryCount = count(DB::getQueryLog());
        $queries = DB::getQueryLog();

        // Calculate total query time
        $totalQueryTime = 0;
        foreach ($queries as $query) {
            $totalQueryTime += $query['time'] ?? 0;
        }

        // Determine if this is a performance-critical route
        $criticalRoutes = ['home', 'portfolio', 'portfolio.detail'];
        $isCritical = in_array($request->route()?->getName(), $criticalRoutes);

        // Log performance metrics for critical routes
        if ($isCritical || $executionTime > 500) { // Log if critical route or slow (>500ms)
            Log::channel('performance')->info('Performance Metrics', [
                'route' => $request->route()?->getName() ?? 'unknown',
                'method' => $request->method(),
                'url' => $request->url(),
                'execution_time_ms' => round($executionTime, 2),
                'memory_usage_mb' => round($memoryUsage / 1024 / 1024, 2),
                'peak_memory_mb' => round($peakMemory / 1024 / 1024, 2),
                'query_count' => $queryCount,
                'total_query_time_ms' => round($totalQueryTime, 2),
                'user_agent' => $request->userAgent(),
                'ip' => $request->ip(),
                'is_ajax' => $request->ajax(),
                'timestamp' => now()->toISOString(),
            ]);
        }

        // Add performance headers for development
        if (app()->environment('local', 'development')) {
            $response->headers->set('X-Execution-Time', round($executionTime, 2) . 'ms');
            $response->headers->set('X-Memory-Usage', round($memoryUsage / 1024 / 1024, 2) . 'MB');
            $response->headers->set('X-Query-Count', $queryCount);
            $response->headers->set('X-Query-Time', round($totalQueryTime, 2) . 'ms');
        }

        // Alert on performance issues
        $this->checkPerformanceThresholds($request, $executionTime, $memoryUsage, $queryCount);

        return $response;
    }

    /**
     * Check performance thresholds and alert if exceeded
     */
    private function checkPerformanceThresholds(Request $request, float $executionTime, int $memoryUsage, int $queryCount): void
    {
        $alerts = [];

        // Define thresholds
        $thresholds = [
            'execution_time' => 1000, // 1 second
            'memory_usage' => 100 * 1024 * 1024, // 100MB
            'query_count' => 20, // 20 queries
        ];

        // Check thresholds
        if ($executionTime > $thresholds['execution_time']) {
            $alerts[] = "Slow response time: {$executionTime}ms (threshold: {$thresholds['execution_time']}ms)";
        }

        if ($memoryUsage > $thresholds['memory_usage']) {
            $memoryMB = round($memoryUsage / 1024 / 1024, 2);
            $thresholdMB = round($thresholds['memory_usage'] / 1024 / 1024, 2);
            $alerts[] = "High memory usage: {$memoryMB}MB (threshold: {$thresholdMB}MB)";
        }

        if ($queryCount > $thresholds['query_count']) {
            $alerts[] = "Too many database queries: {$queryCount} (threshold: {$thresholds['query_count']})";
        }

        // Log alerts
        if (!empty($alerts)) {
            Log::channel('performance')->warning('Performance Threshold Exceeded', [
                'route' => $request->route()?->getName() ?? 'unknown',
                'url' => $request->url(),
                'alerts' => $alerts,
                'timestamp' => now()->toISOString(),
            ]);
        }
    }
}