<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\CacheOptimizationService;
use App\Models\Project;
use App\Models\LookupData;
use Illuminate\Support\Facades\Cache;

class CacheWarmup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:warmup {--force : Force cache refresh}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Warm up critical caches for optimal homepage performance';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔥 Starting cache warmup for optimal performance...');
        $this->newLine();

        $startTime = microtime(true);

        if ($this->option('force')) {
            $this->info('🧹 Clearing existing caches...');
            Cache::flush();
            $this->line('✅ Cache cleared');
        }

        // Warm up homepage data
        $this->info('📊 Warming up homepage data...');
        $homepageData = CacheOptimizationService::getHomepageData();
        $this->line('✅ Homepage data cached (' . count($homepageData) . ' components)');

        // Warm up project categories
        $this->info('📁 Warming up project categories...');
        $categories = LookupData::getProjectCategories();
        $this->line('✅ Project categories cached (' . $categories->count() . ' categories)');

        // Warm up homepage sections
        $this->info('🏠 Warming up homepage sections...');
        $sections = LookupData::getHomepageSections();
        $sectionCount = is_array($sections) ? count($sections) : $sections->count();
        $this->line('✅ Homepage sections cached (' . $sectionCount . ' sections)');

        // Warm up featured projects
        $this->info('⭐ Warming up featured projects...');
        $featuredProjects = Project::getFeaturedProjects();
        $this->line('✅ Featured projects cached (' . $featuredProjects->count() . ' projects)');

        // Warm up popular projects
        $this->info('🔥 Warming up popular projects...');
        $popularProjects = Project::getPopularProjects();
        $this->line('✅ Popular projects cached (' . $popularProjects->count() . ' projects)');

        // Warm up recent projects
        $this->info('🆕 Warming up recent projects...');
        $recentProjects = Project::getRecentProjects();
        $this->line('✅ Recent projects cached (' . $recentProjects->count() . ' projects)');

        $executionTime = (microtime(true) - $startTime) * 1000;

        $this->newLine();
        $this->info('🎉 Cache warmup completed successfully!');
        $this->line('⏱️  Total execution time: ' . round($executionTime, 2) . 'ms');

        // Display cache statistics
        $this->newLine();
        $this->info('📈 Cache Statistics:');
        $cacheStats = CacheOptimizationService::getCacheStats();

        $this->table(
            ['Cache Metric', 'Value'],
            [
                ['Total Cache Keys', $cacheStats['total_keys']],
                ['Cached Keys', $cacheStats['cached_keys']],
                ['Cache Hit Ratio', $cacheStats['hit_ratio'] . '%'],
            ]
        );

        if ($cacheStats['hit_ratio'] >= 90) {
            $this->info('✅ Cache performance is excellent!');
        } elseif ($cacheStats['hit_ratio'] >= 70) {
            $this->warn('⚠️  Cache performance is good but could be improved.');
        } else {
            $this->error('❌ Cache performance needs attention.');
        }

        return 0;
    }
}