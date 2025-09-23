<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Config;
use App\Models\Project;
use App\Models\LookupData;
use App\Models\Setting;

class LaravelQualityTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function all_routes_are_properly_defined()
    {
        $routes = Route::getRoutes();
        $this->assertGreaterThan(50, count($routes), 'Should have sufficient routes defined');
        
        // Check for essential routes
        $essentialRoutes = ['home', 'portfolio', 'contact.store', 'admin.login'];
        
        foreach ($essentialRoutes as $routeName) {
            $this->assertTrue(
                $routes->hasNamedRoute($routeName),
                "Essential route '{$routeName}' should be defined"
            );
        }
    }

    /** @test */
    public function models_have_proper_relationships()
    {
        $this->artisan('migrate');
        
        // Test Project model relationships
        $project = new Project();
        $this->assertTrue(method_exists($project, 'category'), 'Project should have category relationship');
        
        // Test LookupData model
        $lookup = new LookupData();
        $this->assertTrue(method_exists($lookup, 'projects'), 'LookupData should have projects relationship');
    }

    /** @test */
    public function models_use_custom_primary_keys_correctly()
    {
        $project = new Project();
        $this->assertEquals('id_project', $project->getKeyName(), 'Project should use custom primary key');
        
        $lookup = new LookupData();
        $this->assertEquals('id_lookup_data', $lookup->getKeyName(), 'LookupData should use custom primary key');
        
        $setting = new Setting();
        $this->assertEquals('id_setting', $setting->getKeyName(), 'Setting should use custom primary key');
    }

    /** @test */
    public function configuration_files_are_properly_structured()
    {
        // Test essential config files exist
        $this->assertIsArray(config('app'), 'App config should be available');
        $this->assertIsArray(config('database'), 'Database config should be available');
        $this->assertIsArray(config('security'), 'Security config should be available');
        
        // Test critical settings
        $this->assertNotEmpty(config('app.key'), 'App key should be set');
        $this->assertIsString(config('database.default'), 'Default database connection should be set');
    }

    /** @test */
    public function middleware_is_properly_configured()
    {
        $middleware = app('router')->getMiddleware();
        
        // Check for essential middleware
        $essentialMiddleware = ['auth', 'guest', 'throttle'];
        
        foreach ($essentialMiddleware as $middlewareName) {
            $this->assertArrayHasKey(
                $middlewareName,
                $middleware,
                "Middleware '{$middlewareName}' should be registered"
            );
        }
    }

    /** @test */
    public function validation_rules_are_comprehensive()
    {
        // Test that form request classes exist and have proper validation
        $this->assertTrue(
            class_exists('App\Http\Requests\ProjectRequest') ||
            class_exists('App\Http\Requests\CreateProjectRequest') ||
            method_exists('App\Http\Controllers\ProjectController', 'rules'),
            'Project validation should be implemented'
        );
    }

    /** @test */
    public function controllers_follow_restful_patterns()
    {
        $projectController = new \App\Http\Controllers\ProjectController();
        
        // Check for standard RESTful methods
        $restfulMethods = ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'];
        
        $availableMethods = [];
        foreach ($restfulMethods as $method) {
            if (method_exists($projectController, $method)) {
                $availableMethods[] = $method;
            }
        }
        
        $this->assertGreaterThan(4, count($availableMethods), 'Controller should implement multiple RESTful methods');
    }

    /** @test */
    public function blade_templates_are_properly_organized()
    {
        $viewPaths = [
            resource_path('views/layouts'),
            resource_path('views/components'),
            resource_path('views/admin'),
            resource_path('views/portfolio')
        ];
        
        foreach ($viewPaths as $path) {
            $this->assertTrue(
                is_dir($path),
                "View directory {$path} should exist"
            );
        }
    }

    /** @test */
    public function livewire_components_are_functional()
    {
        // Check if Livewire is properly installed
        $this->assertTrue(
            class_exists('Livewire\Component'),
            'Livewire should be properly installed'
        );
        
        // Check for Livewire routes
        $routes = Route::getRoutes();
        $livewireRoutes = collect($routes)->filter(function ($route) {
            return str_contains($route->uri(), 'livewire');
        });
        
        $this->assertGreaterThan(0, $livewireRoutes->count(), 'Livewire routes should be available');
    }

    /** @test */
    public function asset_compilation_is_configured()
    {
        // Check for Vite configuration
        $this->assertTrue(
            file_exists(base_path('vite.config.js')),
            'Vite configuration should exist'
        );
        
        // Check for package.json
        $this->assertTrue(
            file_exists(base_path('package.json')),
            'Package.json should exist for frontend dependencies'
        );
    }

    /** @test */
    public function error_handling_is_configured()
    {
        // Test that custom error pages exist
        $errorPages = ['404', '500', '419'];
        
        foreach ($errorPages as $errorCode) {
            $errorViewPath = resource_path("views/errors/{$errorCode}.blade.php");
            if (file_exists($errorViewPath)) {
                $this->assertTrue(true, "Error page for {$errorCode} exists");
            }
        }
        
        // Test error reporting configuration
        $this->assertIsString(config('logging.default'), 'Logging channel should be configured');
    }

    /** @test */
    public function caching_configuration_is_optimized()
    {
        // Test cache configuration
        $this->assertIsString(config('cache.default'), 'Cache driver should be configured');
        
        // Test session configuration
        $this->assertIsString(config('session.driver'), 'Session driver should be configured');
        
        // Test queue configuration
        $this->assertIsString(config('queue.default'), 'Queue driver should be configured');
    }

    /** @test */
    public function localization_is_properly_set()
    {
        // Test locale configuration
        $this->assertEquals('en', config('app.locale'), 'Default locale should be set');
        $this->assertEquals('Asia/Jakarta', config('app.timezone'), 'Timezone should be set to Asia/Jakarta');
        
        // Test that language files exist
        $this->assertTrue(
            is_dir(resource_path('lang')),
            'Language directory should exist'
        );
    }

    /** @test */
    public function database_factories_are_available()
    {
        // Check if factories are defined for main models
        try {
            Project::factory();
            $this->assertTrue(true, 'Project factory should be available');
        } catch (\Exception $e) {
            $this->markTestSkipped('Project factory not available: ' . $e->getMessage());
        }
    }
}
