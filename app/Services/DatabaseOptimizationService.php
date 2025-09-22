<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Exception;

/**
 * Database Optimization Service - Handles database performance optimization
 */
class DatabaseOptimizationService
{
    /**
     * Critical performance indexes for ALI_PORTFOLIO
     */
    const PERFORMANCE_INDEXES = [
        // Project table indexes
        'project_performance_idx' => [
            'table' => 'project',
            'sql' => 'CREATE INDEX IF NOT EXISTS idx_project_status_sequence ON project(status, sequence)',
            'description' => 'Optimizes project listing queries with status and sequence filtering'
        ],
        'project_slug_idx' => [
            'table' => 'project',
            'sql' => 'CREATE INDEX IF NOT EXISTS idx_project_slug ON project(slug_project)',
            'description' => 'Optimizes project detail page queries by slug'
        ],
        'project_category_idx' => [
            'table' => 'project',
            'sql' => 'CREATE INDEX IF NOT EXISTS idx_project_category ON project(project_category)',
            'description' => 'Optimizes project filtering by category'
        ],
        'project_featured_idx' => [
            'table' => 'project',
            'sql' => 'CREATE INDEX IF NOT EXISTS idx_project_featured ON project(is_featured, status)',
            'description' => 'Optimizes featured project queries'
        ],
        'project_views_idx' => [
            'table' => 'project',
            'sql' => 'CREATE INDEX IF NOT EXISTS idx_project_views ON project(views_count DESC)',
            'description' => 'Optimizes popular project queries'
        ],
        'project_dates_idx' => [
            'table' => 'project',
            'sql' => 'CREATE INDEX IF NOT EXISTS idx_project_dates ON project(created_at, updated_at)',
            'description' => 'Optimizes recent project queries'
        ],

        // LookupData table indexes
        'lookup_performance_idx' => [
            'table' => 'lookup_data',
            'sql' => 'CREATE INDEX IF NOT EXISTS idx_lookup_type_active ON lookup_data(lookup_type, is_active)',
            'description' => 'Optimizes lookup data queries by type and status'
        ],
        'lookup_code_idx' => [
            'table' => 'lookup_data',
            'sql' => 'CREATE INDEX IF NOT EXISTS idx_lookup_code ON lookup_data(lookup_code)',
            'description' => 'Optimizes lookup data queries by code'
        ],
        'lookup_parent_idx' => [
            'table' => 'lookup_data',
            'sql' => 'CREATE INDEX IF NOT EXISTS idx_lookup_parent ON lookup_data(parent_id, sort_order)',
            'description' => 'Optimizes hierarchical lookup data queries'
        ],

        // Setting table indexes
        'setting_primary_idx' => [
            'table' => 'setting',
            'sql' => 'CREATE INDEX IF NOT EXISTS idx_setting_primary ON setting(id_setting)',
            'description' => 'Optimizes setting queries by primary key'
        ],

        // Award table indexes
        'award_status_sequence_idx' => [
            'table' => 'award',
            'sql' => 'CREATE INDEX IF NOT EXISTS idx_award_status_sequence ON award(status, sequence)',
            'description' => 'Optimizes award listing queries'
        ],
        'award_featured_idx' => [
            'table' => 'award',
            'sql' => 'CREATE INDEX IF NOT EXISTS idx_award_featured ON award(is_featured, status)',
            'description' => 'Optimizes featured award queries'
        ],
        'award_level_idx' => [
            'table' => 'award',
            'sql' => 'CREATE INDEX IF NOT EXISTS idx_award_level ON award(award_level, status)',
            'description' => 'Optimizes award queries by level'
        ],
        'award_date_idx' => [
            'table' => 'award',
            'sql' => 'CREATE INDEX IF NOT EXISTS idx_award_date ON award(award_date DESC)',
            'description' => 'Optimizes recent award queries'
        ],

        // Testimonial table indexes
        'testimonial_status_idx' => [
            'table' => 'testimonial',
            'sql' => 'CREATE INDEX IF NOT EXISTS idx_testimonial_status ON testimonial(status, is_verified)',
            'description' => 'Optimizes testimonial queries by status and verification'
        ],
        'testimonial_rating_idx' => [
            'table' => 'testimonial',
            'sql' => 'CREATE INDEX IF NOT EXISTS idx_testimonial_rating ON testimonial(rating DESC, status)',
            'description' => 'Optimizes high-rated testimonial queries'
        ],
        'testimonial_featured_idx' => [
            'table' => 'testimonial',
            'sql' => 'CREATE INDEX IF NOT EXISTS idx_testimonial_featured ON testimonial(is_featured, is_verified)',
            'description' => 'Optimizes featured testimonial queries'
        ],
        'testimonial_project_idx' => [
            'table' => 'testimonial',
            'sql' => 'CREATE INDEX IF NOT EXISTS idx_testimonial_project ON testimonial(project_id, status)',
            'description' => 'Optimizes project-specific testimonial queries'
        ],

        // Galeri table indexes
        'galeri_status_sequence_idx' => [
            'table' => 'galeri',
            'sql' => 'CREATE INDEX IF NOT EXISTS idx_galeri_status_sequence ON galeri(status, sequence)',
            'description' => 'Optimizes gallery listing queries'
        ],

        // Berita (Articles) table indexes
        'berita_featured_date_idx' => [
            'table' => 'berita',
            'sql' => 'CREATE INDEX IF NOT EXISTS idx_berita_featured_date ON berita(is_featured, tanggal_berita DESC)',
            'description' => 'Optimizes featured article queries'
        ],
        'berita_views_idx' => [
            'table' => 'berita',
            'sql' => 'CREATE INDEX IF NOT EXISTS idx_berita_views ON berita(views DESC)',
            'description' => 'Optimizes popular article queries'
        ],
        'berita_category_idx' => [
            'table' => 'berita',
            'sql' => 'CREATE INDEX IF NOT EXISTS idx_berita_category ON berita(kategori_berita)',
            'description' => 'Optimizes article queries by category'
        ],
        'berita_slug_idx' => [
            'table' => 'berita',
            'sql' => 'CREATE INDEX IF NOT EXISTS idx_berita_slug ON berita(slug_berita)',
            'description' => 'Optimizes article detail page queries'
        ],

        // Layanan table indexes
        'layanan_status_sequence_idx' => [
            'table' => 'layanan',
            'sql' => 'CREATE INDEX IF NOT EXISTS idx_layanan_status_sequence ON layanan(status, sequence)',
            'description' => 'Optimizes service listing queries'
        ],
    ];

    /**
     * Query optimization suggestions
     */
    const QUERY_OPTIMIZATIONS = [
        'project_homepage' => [
            'description' => 'Homepage project loading optimization',
            'original' => 'SELECT * FROM project WHERE status = "Active" ORDER BY sequence ASC',
            'optimized' => 'SELECT id_project, project_name, slug_project, featured_image, summary_description, client_name FROM project WHERE status = "Active" ORDER BY sequence ASC LIMIT 9',
            'benefit' => 'Reduces data transfer by selecting only required fields and limiting results'
        ],
        'project_with_category' => [
            'description' => 'Project with category join optimization',
            'original' => 'SELECT p.*, ld.* FROM project p LEFT JOIN lookup_data ld ON p.category_lookup_id = ld.id',
            'optimized' => 'SELECT p.id_project, p.project_name, p.slug_project, p.featured_image, ld.lookup_name as category_name FROM project p LEFT JOIN lookup_data ld ON p.category_lookup_id = ld.id WHERE p.status = "Active" AND ld.is_active = 1',
            'benefit' => 'Selective field loading and proper WHERE conditions on both tables'
        ],
        'testimonial_homepage' => [
            'description' => 'Homepage testimonial loading optimization',
            'original' => 'SELECT * FROM testimonial',
            'optimized' => 'SELECT client_name, company_name, rating, deskripsi_testimonial, gambar_testimonial FROM testimonial WHERE status = "Active" AND is_verified = 1 ORDER BY display_order ASC LIMIT 6',
            'benefit' => 'Loads only verified testimonials with required fields'
        ],
    ];

    /**
     * Create all performance indexes
     */
    public static function createAllIndexes()
    {
        $results = [
            'created' => [],
            'failed' => [],
            'skipped' => [],
        ];

        foreach (self::PERFORMANCE_INDEXES as $name => $indexInfo) {
            try {
                // Check if table exists
                if (!Schema::hasTable($indexInfo['table'])) {
                    $results['skipped'][] = [
                        'name' => $name,
                        'reason' => 'Table does not exist: ' . $indexInfo['table']
                    ];
                    continue;
                }

                // Execute index creation
                DB::statement($indexInfo['sql']);

                $results['created'][] = [
                    'name' => $name,
                    'table' => $indexInfo['table'],
                    'description' => $indexInfo['description']
                ];

                Log::info("Database index created successfully", [
                    'index' => $name,
                    'table' => $indexInfo['table']
                ]);

            } catch (Exception $e) {
                $results['failed'][] = [
                    'name' => $name,
                    'table' => $indexInfo['table'],
                    'error' => $e->getMessage()
                ];

                Log::error("Failed to create database index", [
                    'index' => $name,
                    'table' => $indexInfo['table'],
                    'error' => $e->getMessage()
                ]);
            }
        }

        return $results;
    }

    /**
     * Analyze query performance
     */
    public static function analyzeQueryPerformance()
    {
        $results = [
            'critical_queries' => [],
            'recommendations' => [],
            'statistics' => [],
        ];

        try {
            // Enable query logging
            DB::enableQueryLog();

            // Run critical queries to analyze
            self::runCriticalQueries();

            // Get query log
            $queries = DB::getQueryLog();

            // Analyze queries
            $results['critical_queries'] = self::analyzeQueries($queries);
            $results['recommendations'] = self::generateRecommendations($queries);
            $results['statistics'] = self::getQueryStatistics($queries);

            // Disable query logging
            DB::disableQueryLog();

        } catch (Exception $e) {
            Log::error('Query analysis failed', ['error' => $e->getMessage()]);
            $results['error'] = $e->getMessage();
        }

        return $results;
    }

    /**
     * Get database size and table statistics
     */
    public static function getDatabaseStatistics()
    {
        try {
            // Get overall database size
            $dbSize = DB::select("
                SELECT
                    ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS total_size_mb,
                    COUNT(*) AS table_count
                FROM information_schema.tables
                WHERE table_schema = DATABASE()
            ");

            // Get table-wise statistics
            $tableStats = DB::select("
                SELECT
                    table_name,
                    ROUND((data_length + index_length) / 1024 / 1024, 2) AS size_mb,
                    table_rows,
                    ROUND(data_length / 1024 / 1024, 2) AS data_size_mb,
                    ROUND(index_length / 1024 / 1024, 2) AS index_size_mb,
                    engine
                FROM information_schema.tables
                WHERE table_schema = DATABASE()
                ORDER BY (data_length + index_length) DESC
            ");

            // Get index usage statistics (MySQL 5.7+)
            $indexStats = [];
            try {
                $indexStats = DB::select("
                    SELECT
                        object_schema,
                        object_name,
                        index_name,
                        count_star,
                        sum_timer_wait / 1000000000 as total_time_seconds
                    FROM performance_schema.table_io_waits_summary_by_index_usage
                    WHERE object_schema = DATABASE() AND count_star > 0
                    ORDER BY count_star DESC
                    LIMIT 20
                ");
            } catch (Exception $e) {
                // Performance schema might not be available
                Log::warning('Could not retrieve index usage statistics', ['error' => $e->getMessage()]);
            }

            return [
                'database' => [
                    'total_size_mb' => $dbSize[0]->total_size_mb ?? 0,
                    'table_count' => $dbSize[0]->table_count ?? 0,
                ],
                'tables' => $tableStats,
                'indexes' => $indexStats,
                'recommendations' => self::generateTableRecommendations($tableStats),
            ];

        } catch (Exception $e) {
            Log::error('Database statistics retrieval failed', ['error' => $e->getMessage()]);
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Optimize database tables
     */
    public static function optimizeTables()
    {
        $results = [
            'optimized' => [],
            'failed' => [],
        ];

        $tables = [
            'project', 'setting', 'lookup_data', 'award',
            'testimonial', 'galeri', 'gallery_items', 'berita', 'layanan'
        ];

        foreach ($tables as $table) {
            try {
                if (Schema::hasTable($table)) {
                    DB::statement("OPTIMIZE TABLE `{$table}`");
                    $results['optimized'][] = $table;

                    Log::info("Table optimized", ['table' => $table]);
                }
            } catch (Exception $e) {
                $results['failed'][] = [
                    'table' => $table,
                    'error' => $e->getMessage()
                ];

                Log::error("Table optimization failed", [
                    'table' => $table,
                    'error' => $e->getMessage()
                ]);
            }
        }

        return $results;
    }

    /**
     * Check for missing indexes
     */
    public static function checkMissingIndexes()
    {
        $recommendations = [];

        try {
            // Check for common missing indexes based on query patterns

            // Check if project table has proper indexes
            $projectIndexes = self::getTableIndexes('project');

            if (!self::hasIndex($projectIndexes, 'status')) {
                $recommendations[] = [
                    'table' => 'project',
                    'column' => 'status',
                    'reason' => 'Frequently filtered in WHERE clauses',
                    'sql' => 'CREATE INDEX idx_project_status ON project(status)'
                ];
            }

            if (!self::hasIndex($projectIndexes, 'slug_project')) {
                $recommendations[] = [
                    'table' => 'project',
                    'column' => 'slug_project',
                    'reason' => 'Used for project detail page lookups',
                    'sql' => 'CREATE INDEX idx_project_slug ON project(slug_project)'
                ];
            }

            // Check lookup_data indexes
            $lookupIndexes = self::getTableIndexes('lookup_data');

            if (!self::hasCompositeIndex($lookupIndexes, ['lookup_type', 'is_active'])) {
                $recommendations[] = [
                    'table' => 'lookup_data',
                    'columns' => 'lookup_type, is_active',
                    'reason' => 'Common filtering pattern for active lookup data',
                    'sql' => 'CREATE INDEX idx_lookup_type_active ON lookup_data(lookup_type, is_active)'
                ];
            }

            // Check for foreign key indexes
            $recommendations = array_merge($recommendations, self::checkForeignKeyIndexes());

        } catch (Exception $e) {
            Log::error('Missing index check failed', ['error' => $e->getMessage()]);
        }

        return $recommendations;
    }

    /**
     * Generate performance report
     */
    public static function generatePerformanceReport()
    {
        return [
            'timestamp' => now(),
            'database_stats' => self::getDatabaseStatistics(),
            'query_analysis' => self::analyzeQueryPerformance(),
            'missing_indexes' => self::checkMissingIndexes(),
            'optimization_recommendations' => self::getOptimizationRecommendations(),
            'index_status' => self::getIndexStatus(),
        ];
    }

    /**
     * Private helper methods
     */
    private static function runCriticalQueries()
    {
        // Simulate critical queries

        // Homepage project query
        DB::table('project')
            ->where('status', 'Active')
            ->orderBy('sequence', 'asc')
            ->limit(9)
            ->get(['id_project', 'project_name', 'slug_project', 'featured_image']);

        // Lookup data queries
        DB::table('lookup_data')
            ->where('lookup_type', 'project_category')
            ->where('is_active', 1)
            ->orderBy('sort_order')
            ->get();

        // Testimonial queries
        DB::table('testimonial')
            ->where('status', 'Active')
            ->where('is_verified', 1)
            ->orderBy('display_order')
            ->limit(6)
            ->get();

        // Award queries
        DB::table('award')
            ->where('status', 'Active')
            ->orderBy('sequence')
            ->limit(6)
            ->get();
    }

    private static function analyzeQueries($queries)
    {
        $criticalQueries = [];

        foreach ($queries as $query) {
            if ($query['time'] > 100) { // Queries taking more than 100ms
                $criticalQueries[] = [
                    'sql' => $query['query'],
                    'time_ms' => $query['time'],
                    'bindings' => $query['bindings'],
                    'severity' => $query['time'] > 500 ? 'high' : 'medium'
                ];
            }
        }

        return $criticalQueries;
    }

    private static function generateRecommendations($queries)
    {
        $recommendations = [];

        foreach ($queries as $query) {
            $sql = strtolower($query['query']);

            // Check for SELECT * queries
            if (strpos($sql, 'select *') !== false) {
                $recommendations[] = [
                    'type' => 'SELECT_ALL',
                    'message' => 'Avoid SELECT * queries. Select only required columns.',
                    'query' => $query['query']
                ];
            }

            // Check for missing WHERE clauses on large tables
            if (strpos($sql, 'from project') !== false && strpos($sql, 'where') === false) {
                $recommendations[] = [
                    'type' => 'MISSING_WHERE',
                    'message' => 'Consider adding WHERE clause to filter project results.',
                    'query' => $query['query']
                ];
            }

            // Check for ORDER BY without LIMIT
            if (strpos($sql, 'order by') !== false && strpos($sql, 'limit') === false) {
                $recommendations[] = [
                    'type' => 'ORDER_WITHOUT_LIMIT',
                    'message' => 'ORDER BY without LIMIT can be expensive. Consider adding LIMIT.',
                    'query' => $query['query']
                ];
            }
        }

        return array_unique($recommendations, SORT_REGULAR);
    }

    private static function getQueryStatistics($queries)
    {
        $totalQueries = count($queries);
        $totalTime = array_sum(array_column($queries, 'time'));
        $avgTime = $totalQueries > 0 ? $totalTime / $totalQueries : 0;
        $slowQueries = array_filter($queries, function($q) { return $q['time'] > 100; });

        return [
            'total_queries' => $totalQueries,
            'total_time_ms' => $totalTime,
            'average_time_ms' => round($avgTime, 2),
            'slow_queries_count' => count($slowQueries),
            'slow_queries_percentage' => $totalQueries > 0 ? round((count($slowQueries) / $totalQueries) * 100, 2) : 0
        ];
    }

    private static function getTableIndexes($table)
    {
        try {
            return DB::select("SHOW INDEX FROM `{$table}`");
        } catch (Exception $e) {
            return [];
        }
    }

    private static function hasIndex($indexes, $column)
    {
        foreach ($indexes as $index) {
            if ($index->Column_name === $column) {
                return true;
            }
        }
        return false;
    }

    private static function hasCompositeIndex($indexes, $columns)
    {
        $indexGroups = [];
        foreach ($indexes as $index) {
            $indexGroups[$index->Key_name][] = $index->Column_name;
        }

        foreach ($indexGroups as $indexColumns) {
            if (count(array_intersect($columns, $indexColumns)) === count($columns)) {
                return true;
            }
        }

        return false;
    }

    private static function checkForeignKeyIndexes()
    {
        $recommendations = [];

        // Check common foreign key patterns
        $foreignKeys = [
            ['table' => 'testimonial', 'column' => 'project_id', 'references' => 'project.id_project'],
            ['table' => 'project', 'column' => 'category_lookup_id', 'references' => 'lookup_data.id_lookup_data'],
            ['table' => 'gallery_items', 'column' => 'id_galeri', 'references' => 'galeri.id_galeri'],
        ];

        foreach ($foreignKeys as $fk) {
            if (Schema::hasTable($fk['table']) && Schema::hasColumn($fk['table'], $fk['column'])) {
                $indexes = self::getTableIndexes($fk['table']);
                if (!self::hasIndex($indexes, $fk['column'])) {
                    $recommendations[] = [
                        'table' => $fk['table'],
                        'column' => $fk['column'],
                        'reason' => 'Foreign key column should be indexed',
                        'sql' => "CREATE INDEX idx_{$fk['table']}_{$fk['column']} ON {$fk['table']}({$fk['column']})"
                    ];
                }
            }
        }

        return $recommendations;
    }

    private static function generateTableRecommendations($tableStats)
    {
        $recommendations = [];

        foreach ($tableStats as $table) {
            // Large tables without proper indexes
            if ($table->table_rows > 10000 && $table->index_size_mb < ($table->data_size_mb * 0.1)) {
                $recommendations[] = [
                    'type' => 'INDEX_SIZE',
                    'table' => $table->table_name,
                    'message' => 'Large table with relatively small indexes. Consider adding more indexes for frequently queried columns.',
                    'details' => "Rows: {$table->table_rows}, Data: {$table->data_size_mb}MB, Indexes: {$table->index_size_mb}MB"
                ];
            }

            // Tables with excessive index size
            if ($table->index_size_mb > $table->data_size_mb && $table->data_size_mb > 0) {
                $recommendations[] = [
                    'type' => 'EXCESSIVE_INDEXES',
                    'table' => $table->table_name,
                    'message' => 'Index size exceeds data size. Review if all indexes are necessary.',
                    'details' => "Data: {$table->data_size_mb}MB, Indexes: {$table->index_size_mb}MB"
                ];
            }
        }

        return $recommendations;
    }

    private static function getOptimizationRecommendations()
    {
        return [
            'query_optimization' => self::QUERY_OPTIMIZATIONS,
            'general_recommendations' => [
                'Use specific column selection instead of SELECT *',
                'Add appropriate LIMIT clauses to queries',
                'Use indexes for WHERE, ORDER BY, and JOIN conditions',
                'Regularly run OPTIMIZE TABLE on frequently updated tables',
                'Monitor slow query log for performance bottlenecks',
                'Consider query result caching for frequently accessed data',
                'Use EXPLAIN to analyze query execution plans',
            ],
            'caching_recommendations' => [
                'Cache homepage data for 30 minutes',
                'Cache lookup data for 2 hours',
                'Cache project categories for 1 hour',
                'Implement query result caching for expensive operations',
                'Use Redis for session storage and caching',
            ]
        ];
    }

    private static function getIndexStatus()
    {
        $status = [
            'created_indexes' => [],
            'missing_indexes' => [],
            'recommendations' => []
        ];

        foreach (self::PERFORMANCE_INDEXES as $name => $indexInfo) {
            if (Schema::hasTable($indexInfo['table'])) {
                // This is simplified - in a real implementation, you'd check if the specific index exists
                $status['created_indexes'][] = [
                    'name' => $name,
                    'table' => $indexInfo['table'],
                    'status' => 'assumed_created'
                ];
            }
        }

        return $status;
    }
}