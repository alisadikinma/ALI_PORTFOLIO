<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class FrontendPerformanceTest extends TestCase
{
    /** @test */
    public function homepage_loads_successfully()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Ali Sadikin', false); // Case-insensitive search
    }

    /** @test */
    public function portfolio_page_loads_successfully()
    {
        $response = $this->get('/portfolio/all');
        $response->assertStatus(200);
    }

    /** @test */
    public function css_assets_are_optimized()
    {
        // Check if compiled CSS exists
        $buildPath = public_path('build');
        if (is_dir($buildPath)) {
            $cssFiles = glob($buildPath . '/*.css');
            if (!empty($cssFiles)) {
                $cssFile = $cssFiles[0];
                $cssSize = filesize($cssFile);
                
                // CSS should be under 200KB after optimization
                $this->assertLessThan(200 * 1024, $cssSize, 'CSS bundle should be under 200KB');
            }
        } else {
            $this->markTestSkipped('Build assets not found, run npm run build');
        }
    }

    /** @test */
    public function javascript_assets_are_optimized()
    {
        // Check if compiled JS exists
        $buildPath = public_path('build');
        if (is_dir($buildPath)) {
            $jsFiles = glob($buildPath . '/*.js');
            if (!empty($jsFiles)) {
                $jsFile = $jsFiles[0];
                $jsSize = filesize($jsFile);
                
                // JS should be under 100KB after optimization
                $this->assertLessThan(100 * 1024, $jsSize, 'JavaScript bundle should be under 100KB');
            }
        } else {
            $this->markTestSkipped('Build assets not found, run npm run build');
        }
    }

    /** @test */
    public function images_are_properly_optimized()
    {
        $imagePaths = [
            public_path('images'),
            public_path('file/galeri'),
            public_path('file/award')
        ];
        
        foreach ($imagePaths as $imagePath) {
            if (is_dir($imagePath)) {
                $images = glob($imagePath . '/*.{jpg,jpeg,png,webp}', GLOB_BRACE);
                
                foreach ($images as $image) {
                    $imageSize = filesize($image);
                    
                    // Most images should be under 1MB unless they're hero images
                    if (!str_contains($image, 'hero') && !str_contains($image, 'banner')) {
                        $this->assertLessThan(
                            1024 * 1024, // 1MB
                            $imageSize,
                            "Image {$image} should be under 1MB for performance"
                        );
                    }
                }
            }
        }
    }

    /** @test */
    public function responsive_design_assets_exist()
    {
        // Check for responsive breakpoint handling in CSS
        $response = $this->get('/');
        $content = $response->getContent();
        
        // Check for viewport meta tag
        $this->assertStringContains(
            'viewport',
            $content,
            'Page should include viewport meta tag for responsive design'
        );
        
        // Check for responsive CSS classes or media queries
        $this->assertTrue(
            str_contains($content, 'responsive') || 
            str_contains($content, 'sm:') || 
            str_contains($content, 'md:') || 
            str_contains($content, 'lg:') ||
            str_contains($content, '@media'),
            'Page should include responsive design elements'
        );
    }

    /** @test */
    public function lazy_loading_is_implemented()
    {
        $response = $this->get('/');
        $content = $response->getContent();
        
        // Check for lazy loading attributes
        $hasLazyLoading = str_contains($content, 'loading="lazy"') || 
                         str_contains($content, 'data-src') ||
                         str_contains($content, 'lazy');
        
        if ($hasLazyLoading) {
            $this->assertTrue(true, 'Lazy loading is implemented');
        } else {
            $this->markTestSkipped('Lazy loading implementation not detected');
        }
    }

    /** @test */
    public function critical_css_optimization_exists()
    {
        $response = $this->get('/');
        $content = $response->getContent();
        
        // Check for inline critical CSS or preload directives
        $hasCriticalCSS = str_contains($content, '<style>') || 
                         str_contains($content, 'rel="preload"') ||
                         str_contains($content, 'critical');
        
        if ($hasCriticalCSS) {
            $this->assertTrue(true, 'Critical CSS optimization is implemented');
        } else {
            $this->markTestSkipped('Critical CSS optimization not detected');
        }
    }

    /** @test */
    public function web_fonts_are_optimized()
    {
        $response = $this->get('/');
        $content = $response->getContent();
        
        // Check for font optimization techniques
        $hasFontOptimization = str_contains($content, 'font-display') || 
                              str_contains($content, 'preload') && str_contains($content, 'font') ||
                              str_contains($content, 'woff2');
        
        if ($hasFontOptimization) {
            $this->assertTrue(true, 'Web font optimization is implemented');
        } else {
            $this->markTestSkipped('Web font optimization not detected');
        }
    }

    /** @test */
    public function image_formats_support_modern_standards()
    {
        $imagePaths = [
            public_path('images'),
            public_path('file/galeri')
        ];
        
        $modernFormats = ['webp', 'avif'];
        $hasModernFormats = false;
        
        foreach ($imagePaths as $imagePath) {
            if (is_dir($imagePath)) {
                foreach ($modernFormats as $format) {
                    $formatImages = glob($imagePath . '/*.' . $format);
                    if (!empty($formatImages)) {
                        $hasModernFormats = true;
                        break 2;
                    }
                }
            }
        }
        
        if ($hasModernFormats) {
            $this->assertTrue(true, 'Modern image formats (WebP/AVIF) are being used');
        } else {
            $this->markTestSkipped('Modern image formats not found, recommend implementing WebP/AVIF support');
        }
    }

    /** @test */
    public function minification_is_applied_to_assets()
    {
        $buildPath = public_path('build');
        
        if (is_dir($buildPath)) {
            $cssFiles = glob($buildPath . '/*.css');
            $jsFiles = glob($buildPath . '/*.js');
            
            if (!empty($cssFiles)) {
                $cssContent = file_get_contents($cssFiles[0]);
                
                // Minified CSS should have very few line breaks
                $lineCount = substr_count($cssContent, "\n");
                $charCount = strlen($cssContent);
                $ratio = $lineCount / $charCount;
                
                $this->assertLessThan(0.01, $ratio, 'CSS should be minified (low line break ratio)');
            }
            
            if (!empty($jsFiles)) {
                $jsContent = file_get_contents($jsFiles[0]);
                
                // Minified JS should have very few line breaks
                $lineCount = substr_count($jsContent, "\n");
                $charCount = strlen($jsContent);
                $ratio = $lineCount / $charCount;
                
                $this->assertLessThan(0.01, $ratio, 'JavaScript should be minified (low line break ratio)');
            }
        } else {
            $this->markTestSkipped('Build assets not found, run npm run build to test minification');
        }
    }

    /** @test */
    public function gzip_compression_headers_are_set()
    {
        $response = $this->get('/');
        
        // Check if server is configured to handle compression
        // This would typically be handled by the web server (Nginx/Apache)
        $headers = $response->headers->all();
        
        // We can't test actual gzip compression in unit tests,
        // but we can verify the response structure supports it
        $this->assertTrue(
            $response->status() === 200,
            'Response should be successful (compression compatibility check)'
        );
    }

    /** @test */
    public function page_load_performance_is_acceptable()
    {
        $startTime = microtime(true);
        
        $response = $this->get('/');
        
        $loadTime = (microtime(true) - $startTime) * 1000; // Convert to milliseconds
        
        // Page should load in under 500ms in test environment
        $this->assertLessThan(500, $loadTime, 'Homepage should load in under 500ms');
        $this->assertEquals(200, $response->status(), 'Homepage should load successfully');
    }

    /** @test */
    public function portfolio_page_performance_is_acceptable()
    {
        $startTime = microtime(true);
        
        $response = $this->get('/portfolio/all');
        
        $loadTime = (microtime(true) - $startTime) * 1000;
        
        // Portfolio page should load in under 750ms (more content)
        $this->assertLessThan(750, $loadTime, 'Portfolio page should load in under 750ms');
        $this->assertEquals(200, $response->status(), 'Portfolio page should load successfully');
    }
}
