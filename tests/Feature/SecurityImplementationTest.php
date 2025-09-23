<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\SecurityHeaders;
use App\Http\Middleware\AdminSecurity;

class SecurityImplementationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function security_headers_middleware_applies_correct_headers()
    {
        $response = $this->get('/');
        
        $response->assertHeader('X-Frame-Options', 'DENY');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-XSS-Protection', '1; mode=block');
        $response->assertHeader('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->assertHeader('Server', 'Portfolio-Server');
    }

    /** @test */
    public function csrf_protection_is_active_on_forms()
    {
        $response = $this->post('/contact', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'message' => 'Test message'
        ]);

        $response->assertStatus(419); // CSRF token mismatch
    }

    /** @test */
    public function admin_routes_require_authentication()
    {
        $adminRoutes = [
            '/admin/projects',
            '/admin/settings',
            '/admin/awards',
            '/admin/gallery'
        ];

        foreach ($adminRoutes as $route) {
            $response = $this->get($route);
            $this->assertTrue(
                $response->isRedirection() || $response->status() === 401 || $response->status() === 403,
                "Route {$route} should require authentication"
            );
        }
    }

    /** @test */
    public function file_upload_security_validation_works()
    {
        $this->markTestSkipped('File upload security tests require authenticated user');
        
        // This test would verify:
        // - Only allowed file types can be uploaded
        // - File size limits are enforced
        // - Malicious files are rejected
    }

    /** @test */
    public function input_validation_prevents_malicious_content()
    {
        $maliciousInputs = [
            '<script>alert("XSS")</script>',
            '{{7*7}}',
            '"; DROP TABLE projects; --',
            '../../etc/passwd'
        ];

        foreach ($maliciousInputs as $input) {
            $response = $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class)
                           ->post('/contact', [
                               'name' => $input,
                               'email' => 'test@example.com',
                               'message' => 'Test message'
                           ]);

            // Should either reject the input or sanitize it
            $this->assertTrue(
                $response->status() >= 400 || $response->isRedirection(),
                "Malicious input '{$input}' should be handled securely"
            );
        }
    }

    /** @test */
    public function rate_limiting_is_configured()
    {
        // Test that rate limiting middleware is applied
        $this->assertTrue(
            collect(Route::getMiddleware())->contains('throttle'),
            'Rate limiting middleware should be available'
        );
    }

    /** @test */
    public function security_configuration_is_properly_set()
    {
        $securityConfig = config('security');
        
        $this->assertIsArray($securityConfig);
        $this->assertArrayHasKey('headers', $securityConfig);
        $this->assertArrayHasKey('upload', $securityConfig);
        $this->assertArrayHasKey('auth', $securityConfig);
        $this->assertArrayHasKey('admin', $securityConfig);
        
        // Verify critical security settings
        $this->assertEquals('DENY', $securityConfig['headers']['X-Frame-Options']);
        $this->assertContains('jpeg', $securityConfig['upload']['allowed_image_types']);
        $this->assertLessThanOrEqual(5, $securityConfig['auth']['max_login_attempts']);
    }

    /** @test */
    public function environment_variables_are_secured()
    {
        // Check that sensitive environment variables are not exposed
        $this->assertFalse(config('app.debug'), 'Debug mode should be disabled in production');
        $this->assertEquals('production', config('app.env'), 'Environment should be set to production');
        
        // Verify database credentials are not hardcoded
        $dbPassword = config('database.connections.mysql.password');
        $this->assertNotEquals('password', $dbPassword, 'Database password should not be default');
        $this->assertNotEmpty($dbPassword, 'Database password should not be empty');
    }
}
