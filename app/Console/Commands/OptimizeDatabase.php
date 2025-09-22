<?php

namespace App\Console\Commands;

use App\Services\DatabaseOptimizationService;
use Illuminate\Console\Command;

class OptimizeDatabase extends Command
{
    protected $signature = 'portfolio:optimize-db {--force : Force optimization even if risky}';

    protected $description = 'Optimize ALI_PORTFOLIO database performance by creating indexes and analyzing queries';

    public function handle()
    {
        $this->info('🚀 Starting ALI_PORTFOLIO Database Optimization...');
        $this->newLine();

        // Create performance indexes
        $this->info('📊 Creating performance indexes...');
        $indexResults = DatabaseOptimizationService::createAllIndexes();

        $this->displayIndexResults($indexResults);

        // Analyze database statistics
        $this->info('📈 Analyzing database statistics...');
        $dbStats = DatabaseOptimizationService::getDatabaseStatistics();

        $this->displayDatabaseStats($dbStats);

        // Check for missing indexes
        $this->info('🔍 Checking for missing indexes...');
        $missingIndexes = DatabaseOptimizationService::checkMissingIndexes();

        if (!empty($missingIndexes)) {
            $this->warn('Missing indexes found:');
            foreach ($missingIndexes as $missing) {
                $this->line("  • {$missing['table']}.{$missing['column']}: {$missing['reason']}");
            }
        } else {
            $this->info('✅ No missing indexes detected.');
        }

        // Optimize tables if requested
        if ($this->option('force')) {
            $this->info('🔧 Optimizing database tables...');
            $optimizeResults = DatabaseOptimizationService::optimizeTables();

            $this->info('Optimized tables: ' . implode(', ', $optimizeResults['optimized']));
            if (!empty($optimizeResults['failed'])) {
                $this->warn('Failed to optimize: ' . implode(', ', array_column($optimizeResults['failed'], 'table')));
            }
        }

        $this->newLine();
        $this->info('✅ Database optimization completed!');

        return 0;
    }

    private function displayIndexResults($results)
    {
        if (!empty($results['created'])) {
            $this->info('✅ Created indexes:');
            foreach ($results['created'] as $index) {
                $this->line("  • {$index['name']} on {$index['table']}");
            }
        }

        if (!empty($results['skipped'])) {
            $this->warn('⚠️  Skipped indexes:');
            foreach ($results['skipped'] as $index) {
                $this->line("  • {$index['name']}: {$index['reason']}");
            }
        }

        if (!empty($results['failed'])) {
            $this->error('❌ Failed indexes:');
            foreach ($results['failed'] as $index) {
                $this->line("  • {$index['name']}: {$index['error']}");
            }
        }

        $this->newLine();
    }

    private function displayDatabaseStats($stats)
    {
        if (isset($stats['database'])) {
            $this->info("Database size: {$stats['database']['total_size_mb']} MB");
            $this->info("Tables count: {$stats['database']['table_count']}");
        }

        if (isset($stats['tables']) && !empty($stats['tables'])) {
            $this->info('Largest tables:');
            $topTables = array_slice($stats['tables'], 0, 5);
            foreach ($topTables as $table) {
                $this->line("  • {$table->table_name}: {$table->size_mb} MB ({$table->table_rows} rows)");
            }
        }

        $this->newLine();
    }
}