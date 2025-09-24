<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Services\CacheOptimizationService;

class PerformanceAnalysis extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'homepage:performance-analysis';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Analyze homepage performance including database queries, cache efficiency, and Core Web Vitals metrics';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== HOMEPAGE PERFORMANCE ANALYSIS ===');
        $this->newLine();

        // Test 1: Database Query Performance
        $this->analyzeQueryPerformance();

        // Test 2: Cache Performance
        $this->analyzeCachePerformance();

        // Test 3: Data Analysis
        $this->analyzeDataLoad();

        // Test 4: Memory Analysis
        $this->analyzeMemoryUsage();

        // Test 5: Cache Statistics
        $this->analyzeCacheStatistics();

        // Test 6: Generate Recommendations
        $this->generateRecommendations();

        // Test 7: Performance Summary
        $this->generateSummary();

        $this->info('=== ANALYSIS COMPLETE ===');

        return 0;
    }

    private function analyzeQueryPerformance()
    {
        $this->info('1. DATABASE QUERY PERFORMANCE:');
        $this->line('================================');

        // Clear cache to test fresh queries
        Cache::flush();

        $startTime = microtime(true);
        $startMemory = memory_get_usage(true);

        // Enable query logging
        DB::enableQueryLog();

        // Test unoptimized queries
        $this->line('Testing unoptimized queries...');

        $config = DB::table('setting')->first();
        $projects = DB::table('project')->where('status', 'Active')->orderBy('sequence')->limit(9)->get();
        $services = DB::table('layanan')->where('status', 'Active')->orderBy('sequence')->get();

        $unoptimizedTime = microtime(true) - $startTime;
        $unoptimizedMemory = memory_get_usage(true) - $startMemory;
        $unoptimizedQueries = count(DB::getQueryLog());

        $this->table(
            ['Metric', 'Value'],
            [
                ['Execution Time', round($unoptimizedTime * 1000, 2) . ' ms'],
                ['Memory Usage', round($unoptimizedMemory / 1024 / 1024, 2) . ' MB'],
                ['Database Queries', $unoptimizedQueries],
            ]
        );

        // Store for comparison
        $this->unoptimizedTime = $unoptimizedTime;
        $this->unoptimizedQueries = $unoptimizedQueries;

        $this->newLine();
    }

    private function analyzeCachePerformance()
    {
        $this->info('2. CACHE PERFORMANCE:');
        $this->line('====================');

        DB::flushQueryLog();
        $startTime = microtime(true);

        // Test cache optimization service
        $this->line('Testing CacheOptimizationService...');

        $cachedData = CacheOptimizationService::getHomepageData();

        $cachedTime = microtime(true) - $startTime;
        $cachedQueries = count(DB::getQueryLog());

        $this->table(
            ['Metric', 'Value'],
            [
                ['Execution Time (Cached)', round($cachedTime * 1000, 2) . ' ms'],
                ['Database Queries (Cached)', $cachedQueries],
            ]
        );

        // Test cache hit performance
        DB::flushQueryLog();
        $startTime = microtime(true);

        $cachedData2 = CacheOptimizationService::getHomepageData();

        $cacheHitTime = microtime(true) - $startTime;
        $cacheHitQueries = count(DB::getQueryLog());

        $this->table(
            ['Cache Hit Metric', 'Value'],
            [
                ['Cache Hit Time', round($cacheHitTime * 1000, 2) . ' ms'],
                ['Cache Hit Queries', $cacheHitQueries],
            ]
        );

        // Store for comparison
        $this->cachedTime = $cachedTime;
        $this->cachedQueries = $cachedQueries;
        $this->cacheHitTime = $cacheHitTime;
        $this->cachedData = $cachedData;

        $this->newLine();
    }

    private function analyzeDataLoad()
    {
        $this->info('3. DATA ANALYSIS:');
        $this->line('================');

        $totalProjects = isset($this->cachedData['projects']) ? count($this->cachedData['projects']) : 0;
        $totalServices = isset($this->cachedData['layanan']) ? count($this->cachedData['layanan']) : 0;
        $totalGallery = isset($this->cachedData['galeri']) ? count($this->cachedData['galeri']) : 0;
        $totalTestimonials = isset($this->cachedData['testimonial']) ? count($this->cachedData['testimonial']) : 0;
        $totalAwards = isset($this->cachedData['award']) ? count($this->cachedData['award']) : 0;
        $totalArticles = isset($this->cachedData['article']) ? count($this->cachedData['article']) : 0;

        $this->table(
            ['Data Type', 'Count'],
            [
                ['Projects', $totalProjects],
                ['Services', $totalServices],
                ['Gallery Items', $totalGallery],
                ['Testimonials', $totalTestimonials],
                ['Awards', $totalAwards],
                ['Articles', $totalArticles],
            ]
        );

        $this->newLine();
    }

    private function analyzeMemoryUsage()
    {
        $this->info('4. MEMORY ANALYSIS:');
        $this->line('==================');

        $peakMemory = memory_get_peak_usage(true);
        $currentMemory = memory_get_usage(true);

        $this->table(
            ['Memory Metric', 'Value'],
            [
                ['Peak Memory', round($peakMemory / 1024 / 1024, 2) . ' MB'],
                ['Current Memory', round($currentMemory / 1024 / 1024, 2) . ' MB'],
                ['Memory Limit', ini_get('memory_limit')],
            ]
        );

        $this->peakMemory = $peakMemory;

        $this->newLine();
    }

    private function analyzeCacheStatistics()
    {
        $this->info('5. CACHE STATISTICS:');
        $this->line('===================');

        $cacheStats = CacheOptimizationService::getCacheStats();

        $this->table(
            ['Cache Metric', 'Value'],
            [
                ['Total Cache Keys', $cacheStats['total_keys']],
                ['Cached Keys', $cacheStats['cached_keys']],
                ['Cache Hit Ratio', $cacheStats['hit_ratio'] . '%'],
            ]
        );

        $this->cacheStats = $cacheStats;

        $this->newLine();
    }

    private function generateRecommendations()
    {
        $this->info('6. PERFORMANCE RECOMMENDATIONS:');
        $this->line('===============================');

        $recommendations = [];

        // Time performance
        if ($this->unoptimizedTime > 0.1) {
            $recommendations[] = ['Priority' => 'High', 'Recommendation' => 'Implement database query optimization'];
        }

        // Query optimization
        if ($this->unoptimizedQueries > 10) {
            $recommendations[] = ['Priority' => 'High', 'Recommendation' => 'Reduce N+1 query problems with eager loading'];
        }

        // Memory optimization
        if ($this->peakMemory > 50 * 1024 * 1024) { // 50MB
            $recommendations[] = ['Priority' => 'Medium', 'Recommendation' => 'Consider memory optimization for large datasets'];
        }

        // Cache optimization
        if ($this->cacheStats['hit_ratio'] < 80) {
            $recommendations[] = ['Priority' => 'High', 'Recommendation' => 'Improve cache hit ratio'];
        }

        // Performance gains
        $timeImprovement = (($this->unoptimizedTime - $this->cachedTime) / $this->unoptimizedTime) * 100;
        $queryReduction = (($this->unoptimizedQueries - $this->cachedQueries) / $this->unoptimizedQueries) * 100;

        $this->table(
            ['Performance Improvement', 'Value'],
            [
                ['Time Reduction', round($timeImprovement, 1) . '%'],
                ['Query Reduction', round($queryReduction, 1) . '%'],
                ['Cache Hit Speed Advantage', round(($this->cachedTime - $this->cacheHitTime) / $this->cachedTime * 100, 1) . '% faster'],
            ]
        );

        if (empty($recommendations)) {
            $this->info('✅ Performance is optimal!');
        } else {
            $this->table(['Priority', 'Recommendation'], $recommendations);
        }

        $this->newLine();
    }

    private function generateSummary()
    {
        $this->info('7. PERFORMANCE SUMMARY:');
        $this->line('=======================');

        $overallScore = 100;

        if ($this->cachedTime > 0.05) $overallScore -= 20;
        if ($this->cacheStats['hit_ratio'] < 90) $overallScore -= 15;
        if ($this->peakMemory > 30 * 1024 * 1024) $overallScore -= 10;
        if ($this->cachedQueries > 5) $overallScore -= 10;

        $finalScore = max(0, $overallScore);

        if ($finalScore >= 90) {
            $status = '✅ EXCELLENT';
            $color = 'green';
        } elseif ($finalScore >= 75) {
            $status = '✅ GOOD';
            $color = 'yellow';
        } elseif ($finalScore >= 60) {
            $status = '⚠️ NEEDS IMPROVEMENT';
            $color = 'yellow';
        } else {
            $status = '❌ POOR';
            $color = 'red';
        }

        $this->table(
            ['Metric', 'Value'],
            [
                ['Overall Performance Score', $finalScore . '/100'],
                ['Status', $status],
                ['Core Web Vitals Estimate', $this->estimateCoreWebVitals()],
            ]
        );

        if ($finalScore >= 75) {
            $this->info('🎉 Homepage performance is optimized for professional presentation!');
        } else {
            $this->warn('⚠️ Homepage needs optimization for optimal professional impression.');
        }

        $this->newLine();
    }

    private function estimateCoreWebVitals()
    {
        // Estimate based on our measurements
        $lcp = $this->cachedTime * 1000; // Largest Contentful Paint estimate
        $fid = min(10, $this->cachedQueries * 2); // First Input Delay estimate
        $cls = 0.1; // Cumulative Layout Shift (assuming optimized)

        $lcpStatus = $lcp < 2500 ? '✅ Good' : ($lcp < 4000 ? '⚠️ Needs Improvement' : '❌ Poor');
        $fidStatus = $fid < 100 ? '✅ Good' : ($fid < 300 ? '⚠️ Needs Improvement' : '❌ Poor');
        $clsStatus = $cls < 0.1 ? '✅ Good' : ($cls < 0.25 ? '⚠️ Needs Improvement' : '❌ Poor');

        return "LCP: {$lcpStatus}, FID: {$fidStatus}, CLS: {$clsStatus}";
    }
}