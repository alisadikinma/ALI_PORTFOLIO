<?php

namespace App\Console\Commands;

use App\Services\PerformanceService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Portfolio Performance Optimization Command
 * Professional consulting business performance standards
 */
class OptimizePortfolioPerformance extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'portfolio:optimize
                            {--type=full : Type of optimization (full, database, cache, images)}
                            {--force : Force optimization even if recently run}
                            {--report : Generate detailed performance report}';

    /**
     * The console command description.
     */
    protected $description = 'Optimize portfolio performance for professional consulting standards';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 PORTFOLIO PERFORMANCE OPTIMIZATION');
        $this->info('===================================');

        $type = $this->option('type');
        $force = $this->option('force');
        $generateReport = $this->option('report');

        // Check if optimization was recently run
        if (!$force && $this->wasRecentlyOptimized()) {
            $this->warn('Performance optimization was run recently. Use --force to override.');
            return Command::SUCCESS;
        }

        $this->info("🎯 Starting {$type} optimization...");

        try {
            $results = $this->runOptimization($type);
            $this->displayResults($results);

            if ($generateReport) {
                $this->generatePerformanceReport();
            }

            // Mark optimization as completed
            Cache::put('last_performance_optimization', now(), 3600); // 1 hour

            $this->info('✅ Portfolio optimization completed successfully!');
            $this->info('💼 Professional consulting performance standards: ACHIEVED');

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error('❌ Optimization failed: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }

    /**
     * Check if optimization was recently run
     */
    protected function wasRecentlyOptimized()
    {
        return Cache::has('last_performance_optimization');
    }

    /**
     * Run the specified optimization type
     */
    protected function runOptimization($type)
    {
        switch ($type) {
            case 'database':
                $this->info('🗄️  Optimizing database performance...');
                return PerformanceService::createPerformanceIndexes();

            case 'cache':
                $this->info('⚡ Optimizing cache strategy...');
                return PerformanceService::optimizeCacheStrategy();

            case 'images':
                $this->info('🖼️  Optimizing image assets...');
                return PerformanceService::compressExistingImages();

            case 'full':
            default:
                $this->info('🔧 Running comprehensive optimization...');
                return PerformanceService::optimizeForProfessionalStandards();
        }
    }

    /**
     * Display optimization results
     */
    protected function displayResults($results)
    {
        $this->info('');
        $this->info('📊 OPTIMIZATION RESULTS:');
        $this->info('========================');

        foreach ($results as $category => $data) {
            $this->displayCategoryResults($category, $data);
        }
    }

    /**
     * Display results for a specific category
     */
    protected function displayCategoryResults($category, $data)
    {
        $this->info('');

        switch ($category) {
            case 'database_indexes':
                $this->info('🗄️  Database Indexes:');
                if (is_array($data)) {
                    foreach ($data as $index => $status) {
                        if ($status === 'created') {
                            $this->line("   ✅ {$index}: Created");
                        } else {
                            $this->line("   ⚠️  {$index}: {$status}");
                        }
                    }
                }
                break;

            case 'cache_optimization':
                $this->info('⚡ Cache Optimization:');
                if (is_array($data)) {
                    foreach ($data as $cache => $status) {
                        if (is_object($status) || is_array($status)) {
                            $this->line("   ✅ {$cache}: Warmed");
                        } else {
                            $this->line("   ✅ {$cache}: {$status}");
                        }
                    }
                }
                break;

            case 'image_compression':
                $this->info('🖼️  Image Compression:');
                if (is_array($data)) {
                    foreach ($data as $directory => $stats) {
                        if (is_array($stats) && isset($stats['optimized'])) {
                            $this->line("   📁 {$directory}:");
                            $this->line("      • Optimized: {$stats['optimized']} images");
                            $this->line("      • Saved: {$stats['mb_saved']} MB");
                            if ($stats['errors'] > 0) {
                                $this->line("      ⚠️  Errors: {$stats['errors']}");
                            }
                        }
                    }
                }
                break;

            case 'query_optimization':
                $this->info('🔍 Query Performance:');
                if (is_array($data)) {
                    foreach ($data as $test => $result) {
                        if (is_array($result) && isset($result['duration_ms'])) {
                            $status = $result['performance'] === 'excellent' ? '✅' :
                                     ($result['performance'] === 'good' ? '⚡' : '⚠️');
                            $this->line("   {$status} {$test}: {$result['duration_ms']}ms ({$result['performance']})");
                        }
                    }
                }
                break;
        }
    }

    /**
     * Generate comprehensive performance report
     */
    protected function generatePerformanceReport()
    {
        $this->info('');
        $this->info('📋 GENERATING PERFORMANCE REPORT...');
        $this->info('==================================');

        try {
            $metrics = PerformanceService::getPerformanceMetrics();

            $this->info('');
            $this->info('🎯 PERFORMANCE METRICS:');
            $this->line("   • Performance Score: {$metrics['performance_score']}/100");
            $this->line("   • Cache Hit Rate: {$metrics['cache_hit_rate']}%");
            $this->line("   • Average Query Time: {$metrics['average_query_time']}ms");

            if (isset($metrics['image_optimization_status'])) {
                $imageStats = $metrics['image_optimization_status'];
                $this->line("   • Total Images: {$imageStats['total_images']}");
                $this->line("   • Image Directory Size: {$imageStats['total_size_mb']} MB");
                $this->line("   • Average Image Size: {$imageStats['avg_size_kb']} KB");
            }

            if (isset($metrics['database_size'])) {
                $dbStats = $metrics['database_size'];
                $this->line("   • Database Size: {$dbStats['size_mb']} MB");
                $this->line("   • Tables: {$dbStats['table_count']}");
            }

            $this->testPagePerformance();

        } catch (\Exception $e) {
            $this->error('Failed to generate performance report: ' . $e->getMessage());
        }
    }

    /**
     * Test page performance
     */
    protected function testPagePerformance()
    {
        $this->info('');
        $this->info('🌐 PAGE PERFORMANCE TESTS:');
        $this->info('=========================');

        // Test database connection
        $this->testDatabasePerformance();

        // Test cache performance
        $this->testCachePerformance();

        // Test query performance
        $this->testQueryPerformance();
    }

    /**
     * Test database performance
     */
    protected function testDatabasePerformance()
    {
        try {
            $start = microtime(true);
            DB::select('SELECT 1');
            $duration = microtime(true) - $start;

            $status = $duration < 0.01 ? '✅ Excellent' :
                     ($duration < 0.05 ? '⚡ Good' : '⚠️ Slow');

            $this->line("   🗄️  Database Connection: {$status} (" . round($duration * 1000, 2) . "ms)");

        } catch (\Exception $e) {
            $this->line("   ❌ Database Connection: Failed ({$e->getMessage()})");
        }
    }

    /**
     * Test cache performance
     */
    protected function testCachePerformance()
    {
        try {
            $testKey = 'performance_test_' . time();
            $testValue = 'test_data';

            // Test cache write
            $start = microtime(true);
            Cache::put($testKey, $testValue, 60);
            $writeTime = microtime(true) - $start;

            // Test cache read
            $start = microtime(true);
            $retrieved = Cache::get($testKey);
            $readTime = microtime(true) - $start;

            // Clean up
            Cache::forget($testKey);

            $totalTime = $writeTime + $readTime;
            $status = $totalTime < 0.01 ? '✅ Excellent' : '⚡ Good';

            $this->line("   ⚡ Cache System: {$status} (" . round($totalTime * 1000, 2) . "ms)");

        } catch (\Exception $e) {
            $this->line("   ❌ Cache System: Failed ({$e->getMessage()})");
        }
    }

    /**
     * Test query performance
     */
    protected function testQueryPerformance()
    {
        $queries = [
            'Projects' => function() {
                return DB::table('project')->where('status', 'Active')->limit(10)->get();
            },
            'Settings' => function() {
                return DB::table('setting')->first();
            },
            'Categories' => function() {
                return DB::table('lookup_data')
                    ->where('lookup_type', 'project_category')
                    ->where('is_active', 1)
                    ->get();
            },
        ];

        foreach ($queries as $name => $query) {
            try {
                $start = microtime(true);
                $query();
                $duration = microtime(true) - $start;

                $status = $duration < 0.05 ? '✅ Excellent' :
                         ($duration < 0.1 ? '⚡ Good' : '⚠️ Slow');

                $this->line("   🔍 {$name} Query: {$status} (" . round($duration * 1000, 2) . "ms)");

            } catch (\Exception $e) {
                $this->line("   ❌ {$name} Query: Failed");
            }
        }
    }
}
