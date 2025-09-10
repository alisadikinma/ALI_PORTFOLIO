<?php
// Laravel Route Diagnostic Tool
echo "<h1>🔍 Laravel Route Diagnostics</h1>";

// Check if vendor exists
if (file_exists('../vendor/autoload.php')) {
    echo "<p style='color: green;'>✅ Composer dependencies: INSTALLED</p>";
    
    try {
        require_once '../vendor/autoload.php';
        $app = require_once '../bootstrap/app.php';
        
        echo "<p style='color: green;'>✅ Laravel bootstrap: SUCCESS</p>";
        
        // Check .env
        if (file_exists('../.env')) {
            echo "<p style='color: green;'>✅ .env file: EXISTS</p>";
            
            // Try to boot Laravel
            $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
            echo "<p style='color: green;'>✅ Laravel kernel: LOADED</p>";
            
            // Test routes
            $router = $app->make('router');
            $routes = $router->getRoutes();
            
            $galeriRoutes = [];
            foreach ($routes as $route) {
                if (strpos($route->uri(), 'galeri') !== false) {
                    $galeriRoutes[] = [
                        'method' => implode('|', $route->methods()),
                        'uri' => $route->uri(),
                        'name' => $route->getName(),
                        'action' => $route->getActionName()
                    ];
                }
            }
            
            echo "<h3>📋 Galeri Routes Found:</h3>";
            echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
            echo "<tr><th>Method</th><th>URI</th><th>Name</th><th>Controller</th></tr>";
            foreach ($galeriRoutes as $route) {
                echo "<tr>";
                echo "<td>{$route['method']}</td>";
                echo "<td>{$route['uri']}</td>";
                echo "<td>{$route['name']}</td>";
                echo "<td>{$route['action']}</td>";
                echo "</tr>";
            }
            echo "</table>";
            
            echo "<hr>";
            echo "<h3>🧪 Test Laravel Routes:</h3>";
            echo "<p><a href='/ALI_PORTFOLIO/public/galeri/create-test' target='_blank'>Test: galeri/create-test</a></p>";
            echo "<p><a href='/ALI_PORTFOLIO/public/galeri/create' target='_blank'>Test: galeri/create (auth required)</a></p>";
            echo "<p><a href='/ALI_PORTFOLIO/public/' target='_blank'>Test: Home page</a></p>";
            
        } else {
            echo "<p style='color: red;'>❌ .env file: MISSING</p>";
            echo "<p>Copy .env.example to .env and configure database settings</p>";
        }
        
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ Laravel error: " . $e->getMessage() . "</p>";
        echo "<p><strong>File:</strong> " . $e->getFile() . ":" . $e->getLine() . "</p>";
    }
    
} else {
    echo "<p style='color: red;'>❌ Composer dependencies: NOT INSTALLED</p>";
    echo "<p><strong>Run:</strong> <code>composer install</code></p>";
}

echo "<hr>";
echo "<h3>📁 File Check:</h3>";
$files = [
    '../vendor/autoload.php' => 'Composer Autoload',
    '../bootstrap/app.php' => 'Laravel Bootstrap',
    '../.env' => 'Environment Config',
    '../routes/web.php' => 'Web Routes',
    '../app/Http/Controllers/GaleriController.php' => 'Galeri Controller',
    '../resources/views/galeri/create.blade.php' => 'Create View'
];

foreach ($files as $file => $name) {
    if (file_exists($file)) {
        echo "<p style='color: green;'>✅ $name</p>";
    } else {
        echo "<p style='color: red;'>❌ $name</p>";
    }
}

echo "<hr>";
echo "<h3>🚀 Alternative Access Methods:</h3>";
echo "<ol>";
echo "<li><strong>Direct PHP:</strong> <a href='galeri-direct.php/galeri/create-test'>galeri-direct.php</a></li>";
echo "<li><strong>Laravel (if working):</strong> <a href='/ALI_PORTFOLIO/public/galeri/create-test'>/galeri/create-test</a></li>";
echo "<li><strong>With index.php:</strong> <a href='/ALI_PORTFOLIO/public/index.php/galeri/create-test'>/index.php/galeri/create-test</a></li>";
echo "</ol>";
?>
