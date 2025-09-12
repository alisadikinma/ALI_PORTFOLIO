<?php
// Enhanced Laravel Bootstrap with Proper Route Loading
// This script properly boots Laravel and shows all routes

try {
    echo "<h2>🚀 Enhanced Laravel Bootstrap & Route Analysis</h2>";
    echo "<p><strong>Current Time:</strong> " . date('Y-m-d H:i:s') . "</p>";
    
    // Step 1: Bootstrap Laravel properly
    echo "<h3>1. Laravel Bootstrap Process:</h3>";
    
    require_once __DIR__ . '/../vendor/autoload.php';
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    
    echo "<p>✅ Laravel application instance created</p>";
    
    // Step 2: Boot the HTTP Kernel properly
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    echo "<p>✅ HTTP Kernel instantiated</p>";
    
    // Step 3: Bootstrap the application (this loads service providers)
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
    echo "<p>✅ Application bootstrapped (service providers loaded)</p>";
    
    // Step 4: Get router after bootstrap
    $router = $app->make('router');
    echo "<p>✅ Router instance retrieved</p>";
    
    // Step 5: Get route collection
    $routes = $router->getRoutes();
    $routeCount = $routes->count();
    echo "<p>✅ Route collection loaded</p>";
    echo "<p><strong>Total routes after bootstrap:</strong> $routeCount</p>";
    
    if ($routeCount === 0) {
        echo "<p style='color: red;'>❌ No routes loaded - checking providers...</p>";
        
        // Check if RouteServiceProvider is registered
        $providers = $app->getLoadedProviders();
        if (array_key_exists('App\Providers\RouteServiceProvider', $providers)) {
            echo "<p>✅ RouteServiceProvider is registered</p>";
        } else {
            echo "<p>❌ RouteServiceProvider is NOT registered</p>";
        }
        
        // Manually load routes
        echo "<p>🔧 Manually loading routes...</p>";
        try {
            $app->make(\App\Providers\RouteServiceProvider::class)->boot();
            echo "<p>✅ RouteServiceProvider booted manually</p>";
            
            // Get routes again
            $routes = $router->getRoutes();
            $newRouteCount = $routes->count();
            echo "<p><strong>Routes after manual boot:</strong> $newRouteCount</p>";
        } catch (Exception $providerError) {
            echo "<p>❌ Error booting RouteServiceProvider: " . $providerError->getMessage() . "</p>";
        }
    }
    
    // Step 6: Analyze routes
    if ($routes->count() > 0) {
        echo "<h3>2. Route Analysis:</h3>";
        
        // Categorize routes
        $webRoutes = [];
        $apiRoutes = [];
        $projectRoutes = [];
        $authRoutes = [];
        $publicRoutes = [];
        
        foreach ($routes as $route) {
            $uri = $route->uri();
            $name = $route->getName();
            $methods = $route->methods();
            $middleware = $route->middleware();
            $action = $route->getActionName();
            
            // Categorize by middleware
            if (in_array('api', $middleware)) {
                $apiRoutes[] = ['uri' => $uri, 'name' => $name, 'methods' => $methods];
            } else {
                $webRoutes[] = ['uri' => $uri, 'name' => $name, 'methods' => $methods, 'middleware' => $middleware];
            }
            
            // Check for project routes
            if (strpos($uri, 'project') !== false || strpos($name ?? '', 'project') !== false) {
                $projectRoutes[] = [
                    'uri' => $uri,
                    'name' => $name,
                    'methods' => implode('|', $methods),
                    'middleware' => implode(',', $middleware),
                    'action' => str_replace('App\\Http\\Controllers\\', '', $action)
                ];
                
                // Categorize by auth
                if (in_array('auth', $middleware)) {
                    $authRoutes[] = $uri;
                } else {
                    $publicRoutes[] = $uri;
                }
            }
        }
        
        echo "<p><strong>Web Routes:</strong> " . count($webRoutes) . "</p>";
        echo "<p><strong>API Routes:</strong> " . count($apiRoutes) . "</p>";
        echo "<p><strong>Project Routes:</strong> " . count($projectRoutes) . "</p>";
        
        // Show project routes in detail
        if (!empty($projectRoutes)) {
            echo "<h3>3. Project Routes Detail:</h3>";
            echo "<table border='1' style='border-collapse: collapse; width: 100%; margin: 10px 0;'>";
            echo "<tr style='background: #f0f0f0;'><th>URI</th><th>Name</th><th>Methods</th><th>Middleware</th><th>Action</th></tr>";
            foreach ($projectRoutes as $route) {
                $bgColor = strpos($route['middleware'], 'auth') !== false ? 'style="background: #ffe6e6;"' : 'style="background: #e6ffe6;"';
                echo "<tr $bgColor>";
                echo "<td>{$route['uri']}</td>";
                echo "<td>{$route['name']}</td>";
                echo "<td>{$route['methods']}</td>";
                echo "<td>{$route['middleware']}</td>";
                echo "<td>{$route['action']}</td>";
                echo "</tr>";
            }
            echo "</table>";
            echo "<p><small>🔴 Red = Requires Auth | 🟢 Green = Public</small></p>";
        } else {
            echo "<p>❌ No project routes found in route collection</p>";
        }
        
        // Show auth vs public project routes
        echo "<h3>4. Project Route Access:</h3>";
        if (!empty($publicRoutes)) {
            echo "<p><strong>✅ Public Project Routes (No Login Required):</strong></p>";
            foreach ($publicRoutes as $uri) {
                $fullUrl = "http://localhost/ALI_PORTFOLIO/public/$uri";
                echo "<p>• <a href='$fullUrl' target='_blank' style='color: green;'>$uri</a></p>";
            }
        } else {
            echo "<p>⚠️ No public project routes available</p>";
        }
        
        if (!empty($authRoutes)) {
            echo "<p><strong>🔒 Protected Project Routes (Login Required):</strong></p>";
            foreach ($authRoutes as $uri) {
                echo "<p>• <span style='color: red;'>$uri</span> (requires auth)</p>";
            }
        }
        
        // Test specific routes
        echo "<h3>5. Route Resolution Test:</h3>";
        $testRoutes = [
            'project.index' => null,
            'project.create' => null,
            'test.project.create' => null,
            'login' => null
        ];
        
        foreach ($testRoutes as $routeName => $url) {
            try {
                if ($routes->hasNamedRoute($routeName)) {
                    $route = $routes->getByName($routeName);
                    $uri = $route->uri();
                    $middleware = implode(',', $route->middleware());
                    echo "<p>✅ <strong>$routeName</strong>: <code>$uri</code> ($middleware)</p>";
                } else {
                    echo "<p>❌ <strong>$routeName</strong>: NOT FOUND</p>";
                }
            } catch (Exception $e) {
                echo "<p>❌ <strong>$routeName</strong>: ERROR - " . $e->getMessage() . "</p>";
            }
        }
        
    } else {
        echo "<h3>❌ No Routes Loaded</h3>";
        echo "<p>Possible issues:</p>";
        echo "<ul>";
        echo "<li>RouteServiceProvider not loading properly</li>";
        echo "<li>Routes/web.php syntax error</li>";
        echo "<li>Service provider registration issue</li>";
        echo "</ul>";
    }
    
    echo "<h2>✅ Enhanced Bootstrap Analysis Complete</h2>";
    echo "<h3>🎯 Recommended Actions:</h3>";
    echo "<ul>";
    if (!empty($publicRoutes)) {
        echo "<li>✅ Try public project routes above</li>";
    }
    if (!empty($authRoutes)) {
        echo "<li>🔒 Login first, then try: <a href='/login'>/login</a></li>";
    }
    echo "<li>🧪 Test no-auth route: <a href='/test-project-create'>/test-project-create</a></li>";
    echo "<li>🔧 Check routes file: <a href='/route-debug.php'>/route-debug.php</a></li>";
    echo "</ul>";
    
} catch (Exception $e) {
    echo "<h2>❌ Enhanced Bootstrap Error:</h2>";
    echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>File:</strong> " . $e->getFile() . "</p>";
    echo "<p><strong>Line:</strong> " . $e->getLine() . "</p>";
    echo "<h3>Stack Trace:</h3>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>
