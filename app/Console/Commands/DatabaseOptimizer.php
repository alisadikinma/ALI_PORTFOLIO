<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Project;
use App\Models\Setting;

class DatabaseOptimizer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:optimize
                            {--analyze : Analyze table statistics}
                            {--clean : Clean up orphaned data}
                            {--rebuild : Rebuild indexes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Optimize database performance for Ali Digital Transformation consulting portfolio';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Ali Digital Transformation - Database Optimizer');
        $this->info('================================================');

        if ($this->option('analyze')) {
            $this->analyzeDatabase();
        }

        if ($this->option('clean')) {
            $this->cleanDatabase();
        }

        if ($this->option('rebuild')) {
            $this->rebuildIndexes();
        }

        if (!$this->option('analyze') && !$this->option('clean') && !$this->option('rebuild')) {
            $this->performFullOptimization();
        }

        return 0;
    }

    /**
     * Perform full database optimization
     */
    private function performFullOptimization()
    {
        $this->info("🔧 Starting comprehensive database optimization...\n");

        $this->analyzeDatabase();
        $this->cleanDatabase();
        $this->rebuildIndexes();
        $this->optimizeTables();
        $this->validateIntegrity();

        $this->info("\n🎉 Database optimization completed successfully!");
        $this->info("✅ Your portfolio database is now optimized for professional performance.");
    }

    /**
     * Analyze database performance
     */
    private function analyzeDatabase()
    {
        $this->info("📊 Analyzing database performance...");

        try {
            // Get database size
            $dbName = config('database.connections.mysql.database');
            $dbSize = DB::selectOne("
                SELECT
                    ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size_mb
                FROM information_schema.tables
                WHERE table_schema = ?
            ", [$dbName]);

            $this->info("💾 Database size: {$dbSize->size_mb} MB");

            // Analyze table statistics
            $tables = DB::select("
                SELECT
                    table_name,
                    table_rows,
                    ROUND(((data_length + index_length) / 1024 / 1024), 2) AS size_mb,
                    ROUND((data_free / 1024 / 1024), 2) AS free_mb
                FROM information_schema.tables
                WHERE table_schema = ? AND table_type = 'BASE TABLE'
                ORDER BY size_mb DESC
            ", [$dbName]);

            $this->table(
                ['Table', 'Rows', 'Size (MB)', 'Free Space (MB)'],
                array_map(function($table) {
                    return [
                        $table->table_name,
                        number_format($table->table_rows),
                        $table->size_mb,
                        $table->free_mb
                    ];
                }, $tables)
            );

            // Check query cache performance
            $this->checkQueryPerformance();

        } catch (\Exception $e) {
            $this->error("❌ Analysis failed: " . $e->getMessage());
        }
    }

    /**
     * Clean orphaned and unnecessary data
     */
    private function cleanDatabase()
    {
        $this->info("🧹 Cleaning database...");

        try {
            $cleaned = 0;

            // Clean up projects without proper slugs
            $projectsWithoutSlugs = Project::whereNull('slug_project')->get();
            foreach ($projectsWithoutSlugs as $project) {
                $project->slug_project = \Illuminate\Support\Str::slug($project->project_name);
                $project->save();
                $cleaned++;
            }

            if ($cleaned > 0) {
                $this->info("✅ Generated slugs for {$cleaned} projects");
            }

            // Clean up empty or invalid image arrays
            $projectsWithInvalidImages = Project::where('images', '[]')
                ->orWhere('images', 'null')
                ->orWhereNull('images')
                ->get();

            foreach ($projectsWithInvalidImages as $project) {
                $project->images = json_encode([]);
                $project->save();
                $cleaned++;
            }

            if ($projectsWithInvalidImages->count() > 0) {
                $this->info("✅ Cleaned image data for {$projectsWithInvalidImages->count()} projects");
            }

            // Clean up inactive lookup data
            $inactiveItems = DB::table('lookup_data')
                ->where('is_active', false)
                ->count();

            if ($inactiveItems > 0) {
                $this->info("📋 Found {$inactiveItems} inactive lookup items (keeping for reference)");
            }

            $this->info("✅ Database cleanup completed");

        } catch (\Exception $e) {
            $this->error("❌ Cleanup failed: " . $e->getMessage());
        }
    }

    /**
     * Rebuild database indexes for optimal performance
     */
    private function rebuildIndexes()
    {
        $this->info("🔨 Rebuilding database indexes...");

        try {
            // Rebuild project indexes
            DB::statement('OPTIMIZE TABLE project');
            $this->info("✅ Optimized project table indexes");

            // Rebuild lookup_data indexes
            DB::statement('OPTIMIZE TABLE lookup_data');
            $this->info("✅ Optimized lookup_data table indexes");

            // Rebuild settings indexes
            DB::statement('OPTIMIZE TABLE setting');
            $this->info("✅ Optimized setting table indexes");

            $this->info("✅ All indexes rebuilt successfully");

        } catch (\Exception $e) {
            $this->error("❌ Index rebuild failed: " . $e->getMessage());
        }
    }

    /**
     * Optimize table storage
     */
    private function optimizeTables()
    {
        $this->info("⚡ Optimizing table storage...");

        try {
            $tables = ['project', 'setting', 'lookup_data', 'personal_access_tokens'];

            foreach ($tables as $table) {
                if (Schema::hasTable($table)) {
                    DB::statement("ANALYZE TABLE {$table}");
                    $this->info("✅ Analyzed {$table} table");
                }
            }

            $this->info("✅ Table optimization completed");

        } catch (\Exception $e) {
            $this->error("❌ Table optimization failed: " . $e->getMessage());
        }
    }

    /**
     * Validate data integrity
     */
    private function validateIntegrity()
    {
        $this->info("🔍 Validating data integrity...");

        try {
            $issues = 0;

            // Check for projects without names
            $projectsWithoutNames = Project::whereNull('project_name')
                ->orWhere('project_name', '')
                ->count();

            if ($projectsWithoutNames > 0) {
                $this->warn("⚠️ Found {$projectsWithoutNames} projects without names");
                $issues++;
            }

            // Check for projects without clients
            $projectsWithoutClients = Project::whereNull('client_name')
                ->orWhere('client_name', '')
                ->count();

            if ($projectsWithoutClients > 0) {
                $this->warn("⚠️ Found {$projectsWithoutClients} projects without client names");
                $issues++;
            }

            // Check settings integrity
            $settings = Setting::first();
            if (!$settings) {
                $this->warn("⚠️ No settings record found");
                $issues++;
            } else {
                if (!$settings->instansi_setting) {
                    $this->warn("⚠️ Company name not set in settings");
                    $issues++;
                }
            }

            if ($issues === 0) {
                $this->info("✅ Data integrity validation passed");
            } else {
                $this->warn("⚠️ Found {$issues} data integrity issues");
            }

        } catch (\Exception $e) {
            $this->error("❌ Integrity validation failed: " . $e->getMessage());
        }
    }

    /**
     * Check query performance
     */
    private function checkQueryPerformance()
    {
        $this->info("\n🏃 Query Performance Analysis:");

        try {
            // Test project queries
            $start = microtime(true);
            Project::active()->featured()->take(10)->get();
            $projectQueryTime = round((microtime(true) - $start) * 1000, 2);

            $this->info("📊 Featured projects query: {$projectQueryTime}ms");

            // Test lookup queries
            $start = microtime(true);
            DB::table('lookup_data')
                ->where('lookup_type', 'project_category')
                ->where('is_active', true)
                ->get();
            $lookupQueryTime = round((microtime(true) - $start) * 1000, 2);

            $this->info("📊 Category lookup query: {$lookupQueryTime}ms");

            // Performance recommendations
            if ($projectQueryTime > 100) {
                $this->warn("⚠️ Project queries are slow. Consider adding more indexes.");
            } else {
                $this->info("✅ Project query performance is good");
            }

            if ($lookupQueryTime > 50) {
                $this->warn("⚠️ Lookup queries are slow. Consider optimizing lookup_data table.");
            } else {
                $this->info("✅ Lookup query performance is good");
            }

        } catch (\Exception $e) {
            $this->warn("⚠️ Could not test query performance: " . $e->getMessage());
        }
    }
}