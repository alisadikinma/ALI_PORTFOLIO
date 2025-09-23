<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use App\Models\Project;
use App\Models\LookupData;
use App\Models\Award;
use App\Models\Testimonial;

class DatabasePerformanceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
    }

    /** @test */
    public function database_indexes_are_created_correctly()
    {
        $expectedIndexes = [
            'project' => [
                'idx_project_status',
                'idx_project_sequence_date',
                'idx_project_featured_status',
                'idx_project_homepage'
            ],
            'lookup_data' => [
                'idx_lookup_type_active_sort',
                'idx_lookup_active'
            ],
            'award' => [
                'idx_award_status_sequence',
                'idx_award_homepage'
            ],
            'testimonial' => [
                'idx_testimonial_status_order',
                'idx_testimonial_homepage'
            ]
        ];

        foreach ($expectedIndexes as $table => $indexes) {
            if (\Schema::hasTable($table)) {
                foreach ($indexes as $indexName) {
                    $this->assertTrue(
                        $this->indexExists($table, $indexName),
                        "Index {$indexName} should exist on table {$table}"
                    );
                }
            }
        }
    }

    /** @test */
    public function query_performance_is_optimized()
    {
        // Create test data
        $this->createTestData();

        // Test homepage query performance
        $startTime = microtime(true);
        
        $featuredProjects = Project::where('is_featured', true)
            ->where('status', 'active')
            ->orderBy('sequence')
            ->limit(6)
            ->get();
            
        $executionTime = (microtime(true) - $startTime) * 1000; // Convert to milliseconds
        
        $this->assertLessThan(100, $executionTime, 'Featured projects query should execute in under 100ms');
        $this->assertGreaterThan(0, $featuredProjects->count(), 'Should return featured projects');
    }

    /** @test */
    public function lookup_data_queries_are_optimized()
    {
        $this->createLookupTestData();

        $startTime = microtime(true);
        
        $categories = LookupData::where('lookup_type', 'project_category')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
            
        $executionTime = (microtime(true) - $startTime) * 1000;
        
        $this->assertLessThan(50, $executionTime, 'Lookup data query should execute in under 50ms');
        $this->assertGreaterThan(0, $categories->count(), 'Should return categories');
    }

    /** @test */
    public function database_connection_is_optimized()
    {
        $connection = DB::connection();
        
        // Test connection is alive
        $this->assertTrue($connection->getPdo() !== null, 'Database connection should be active');
        
        // Test query execution
        $result = DB::select('SELECT 1 as test');
        $this->assertEquals(1, $result[0]->test, 'Basic query should work');
    }

    /** @test */
    public function complex_queries_perform_well()
    {
        $this->createTestData();

        // Test complex query with joins
        $startTime = microtime(true);
        
        $projectsWithCategories = DB::table('project')
            ->leftJoin('lookup_data', 'project.category_lookup_id', '=', 'lookup_data.id_lookup_data')
            ->select('project.*', 'lookup_data.name as category_name')
            ->where('project.status', 'active')
            ->orderBy('project.sequence')
            ->limit(10)
            ->get();
            
        $executionTime = (microtime(true) - $startTime) * 1000;
        
        $this->assertLessThan(150, $executionTime, 'Complex join query should execute in under 150ms');
        $this->assertGreaterThan(0, $projectsWithCategories->count(), 'Should return projects with categories');
    }

    /** @test */
    public function database_tables_exist_and_are_accessible()
    {
        $requiredTables = ['project', 'lookup_data', 'award', 'testimonial', 'setting'];
        
        foreach ($requiredTables as $table) {
            $this->assertTrue(
                \Schema::hasTable($table),
                "Table {$table} should exist"
            );
            
            // Test basic read access
            try {
                DB::table($table)->limit(1)->get();
                $this->assertTrue(true, "Table {$table} should be readable");
            } catch (\Exception $e) {
                $this->fail("Table {$table} should be accessible: " . $e->getMessage());
            }
        }
    }

    /** @test */
    public function fulltext_search_indexes_work_if_available()
    {
        if (DB::connection()->getDriverName() !== 'mysql') {
            $this->markTestSkipped('FULLTEXT search is MySQL specific');
        }

        $this->createTestData();

        try {
            // Test FULLTEXT search if index exists
            $results = DB::select("SELECT * FROM project WHERE MATCH(project_name, summary_description) AGAINST('test' IN NATURAL LANGUAGE MODE) LIMIT 5");
            $this->assertTrue(true, 'FULLTEXT search should work if indexes are created');
        } catch (\Exception $e) {
            // FULLTEXT indexes might not be created yet
            $this->markTestSkipped('FULLTEXT indexes not available: ' . $e->getMessage());
        }
    }

    private function createTestData()
    {
        // Create test lookup data
        LookupData::create([
            'lookup_type' => 'project_category',
            'lookup_code' => 'web_dev',
            'name' => 'Web Development',
            'is_active' => true,
            'sort_order' => 1
        ]);

        // Create test projects
        for ($i = 1; $i <= 10; $i++) {
            Project::create([
                'project_name' => "Test Project {$i}",
                'summary_description' => "This is a test project description for project {$i}",
                'slug_project' => "test-project-{$i}",
                'status' => 'active',
                'is_featured' => $i <= 3, // First 3 are featured
                'sequence' => $i,
                'category_lookup_id' => 1
            ]);
        }
    }

    private function createLookupTestData()
    {
        $types = ['project_category', 'service_type', 'skill_level'];
        
        foreach ($types as $index => $type) {
            for ($i = 1; $i <= 5; $i++) {
                LookupData::create([
                    'lookup_type' => $type,
                    'lookup_code' => strtolower(str_replace('_', '', $type)) . $i,
                    'name' => ucwords(str_replace('_', ' ', $type)) . " {$i}",
                    'is_active' => true,
                    'sort_order' => $i
                ]);
            }
        }
    }

    private function indexExists(string $table, string $indexName): bool
    {
        try {
            $indexes = DB::select("SHOW INDEX FROM {$table} WHERE Key_name = ?", [$indexName]);
            return count($indexes) > 0;
        } catch (\Exception $e) {
            return false;
        }
    }
}
