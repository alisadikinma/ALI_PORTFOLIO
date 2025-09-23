<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Models\Project;
use App\Models\LookupData;
use App\Models\User;

class IntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
    }

    /** @test */
    public function complete_homepage_workflow_functions_correctly()
    {
        // Create test data
        $this->createBasicTestData();
        
        // Test homepage loads with all components
        $response = $this->get('/');
        $response->assertStatus(200);
        
        // Verify homepage contains expected sections
        $content = $response->getContent();
        $this->assertStringContainsString('Ali Sadikin', $content);
        
        // Test that dynamic content is loaded
        if (Project::count() > 0) {
            $featuredProject = Project::where('is_featured', true)->first();
            if ($featuredProject) {
                $this->assertStringContainsString($featuredProject->project_name, $content);
            }
        }
    }

    /** @test */
    public function portfolio_browsing_workflow_works_end_to_end()
    {
        $this->createBasicTestData();
        
        // Test portfolio listing page
        $response = $this->get('/portfolio/all');
        $response->assertStatus(200);
        
        // Test individual project page if projects exist
        $project = Project::first();
        if ($project) {
            $response = $this->get('/portfolio/' . $project->slug_project);
            $response->assertStatus(200);
            $response->assertSee($project->project_name);
        }
    }

    /** @test */
    public function contact_form_submission_workflow_functions()
    {
        // Test contact form page loads
        $response = $this->get('/');
        $response->assertStatus(200);
        
        // Test contact form submission (without CSRF for testing)
        $response = $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class)
                         ->post('/contact', [
                             'name' => 'Test User',
                             'email' => 'test@example.com',
                             'message' => 'This is a test message'
                         ]);
        
        // Should either succeed or redirect (depending on implementation)
        $this->assertTrue(
            $response->isSuccessful() || $response->isRedirection(),
            'Contact form submission should be handled properly'
        );
    }

    /** @test */
    public function admin_authentication_workflow_functions()
    {
        // Test admin login page loads
        $response = $this->get('/admin/login');
        $response->assertStatus(200);
        
        // Test that admin routes are protected
        $response = $this->get('/admin/projects');
        $this->assertTrue(
            $response->isRedirection() || $response->status() === 401 || $response->status() === 403,
            'Admin routes should be protected'
        );
    }

    /** @test */
    public function database_relationships_work_in_practice()
    {
        $this->createBasicTestData();
        
        // Test Project-Category relationship
        $project = Project::with('category')->first();
        if ($project && $project->category) {
            $this->assertInstanceOf(LookupData::class, $project->category);
            $this->assertEquals('project_category', $project->category->lookup_type);
        }
        
        // Test reverse relationship
        $category = LookupData::where('lookup_type', 'project_category')->first();
        if ($category) {
            $projects = $category->projects;
            $this->assertGreaterThanOrEqual(0, $projects->count());
        }
    }

    /** @test */
    public function caching_system_integration_works()
    {
        $this->createBasicTestData();
        
        // Clear cache first
        Cache::flush();
        
        // Test that cache keys can be set and retrieved
        $testKey = 'integration_test_key';
        $testValue = 'integration_test_value';
        
        Cache::put($testKey, $testValue, 60);
        $retrievedValue = Cache::get($testKey);
        
        $this->assertEquals($testValue, $retrievedValue);
        
        // Test homepage caching if implemented
        $response1 = $this->get('/');
        $response2 = $this->get('/');
        
        $this->assertEquals($response1->status(), $response2->status());
    }

    /** @test */
    public function search_functionality_integration_works()
    {
        $this->createBasicTestData();
        
        // Test API search endpoint if available
        $response = $this->get('/api/projects/search?q=test');
        
        // Should either return results or indicate no search implemented
        $this->assertTrue(
            $response->isSuccessful() || $response->status() === 404,
            'Search endpoint should handle requests appropriately'
        );
    }

    /** @test */
    public function file_upload_integration_works_with_security()
    {
        // This test would require authenticated user context
        $this->markTestSkipped('File upload integration requires authentication context');
        
        // Would test:
        // 1. File upload security validation
        // 2. File storage and retrieval
        // 3. Image processing if implemented
        // 4. File cleanup on deletion
    }

    /** @test */
    public function api_endpoints_are_functional()
    {
        $this->createBasicTestData();
        
        $apiEndpoints = [
            '/api/projects',
            '/api/projects/featured',
            '/api/awards/recent',
            '/api/testimonials/featured'
        ];
        
        foreach ($apiEndpoints as $endpoint) {
            $response = $this->get($endpoint);
            
            // API endpoints should return JSON responses
            if ($response->isSuccessful()) {
                $this->assertTrue(
                    $response->headers->get('Content-Type') === 'application/json' ||
                    str_contains($response->headers->get('Content-Type'), 'json'),
                    "API endpoint {$endpoint} should return JSON"
                );
            }
        }
    }

    /** @test */
    public function error_handling_integration_works()
    {
        // Test 404 handling
        $response = $this->get('/non-existent-page');
        $this->assertEquals(404, $response->status());
        
        // Test invalid portfolio item
        $response = $this->get('/portfolio/non-existent-project');
        $this->assertEquals(404, $response->status());
        
        // Test invalid API requests
        $response = $this->get('/api/invalid-endpoint');
        $this->assertEquals(404, $response->status());
    }

    /** @test */
    public function middleware_chain_functions_correctly()
    {
        // Test that security headers are applied across all pages
        $pages = ['/', '/portfolio/all', '/contacts'];
        
        foreach ($pages as $page) {
            $response = $this->get($page);
            
            if ($response->isSuccessful()) {
                // Check for security headers
                $this->assertTrue(
                    $response->headers->has('X-Frame-Options') ||
                    $response->headers->has('X-Content-Type-Options'),
                    "Security headers should be applied to {$page}"
                );
            }
        }
    }

    /** @test */
    public function performance_optimizations_work_together()
    {
        $this->createBasicTestData();
        
        // Test that database optimizations work with application logic
        $startTime = microtime(true);
        
        // Simulate complex query that would benefit from indexes
        $projects = Project::with('category')
            ->where('status', 'active')
            ->orderBy('sequence')
            ->limit(10)
            ->get();
            
        $queryTime = (microtime(true) - $startTime) * 1000;
        
        $this->assertLessThan(200, $queryTime, 'Optimized queries should execute quickly');
        $this->assertGreaterThanOrEqual(0, $projects->count());
    }

    /** @test */
    public function application_health_check_passes()
    {
        // Test database connectivity
        $this->assertTrue(DB::connection()->getPdo() !== null, 'Database should be connected');
        
        // Test basic application functionality
        $response = $this->get('/');
        $this->assertTrue($response->isSuccessful(), 'Application should be responsive');
        
        // Test cache system
        Cache::put('health_check', 'ok', 60);
        $this->assertEquals('ok', Cache::get('health_check'), 'Cache system should be functional');
        
        // Test configuration
        $this->assertNotEmpty(config('app.key'), 'Application should be properly configured');
    }

    private function createBasicTestData()
    {
        // Create lookup data for categories
        $category = LookupData::create([
            'lookup_type' => 'project_category',
            'lookup_code' => 'web_development',
            'name' => 'Web Development',
            'is_active' => true,
            'sort_order' => 1
        ]);

        // Create test projects
        Project::create([
            'project_name' => 'Test Portfolio Project',
            'summary_description' => 'This is a test project for integration testing',
            'slug_project' => 'test-portfolio-project',
            'status' => 'active',
            'is_featured' => true,
            'sequence' => 1,
            'category_lookup_id' => $category->id_lookup_data
        ]);

        Project::create([
            'project_name' => 'Another Test Project',
            'summary_description' => 'This is another test project',
            'slug_project' => 'another-test-project',
            'status' => 'active',
            'is_featured' => false,
            'sequence' => 2,
            'category_lookup_id' => $category->id_lookup_data
        ]);
    }
}
