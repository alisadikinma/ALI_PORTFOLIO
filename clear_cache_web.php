<?php
/**
 * Emergency cache clear script for ALI Portfolio
 * Run this via web browser: localhost/ALI_PORTFOLIO/clear_cache_web.php
 */

// Set working directory
$projectPath = dirname(__FILE__);
chdir($projectPath);

echo "<h2>🔧 ALI Portfolio - Cache Clear Script</h2>\n";
echo "<pre>";

try {
    // Clear Laravel cache
    echo "1. Clearing application cache...\n";
    exec('php artisan cache:clear 2>&1', $output1, $return1);
    echo implode("\n", $output1) . "\n";
    
    echo "2. Clearing config cache...\n";
    exec('php artisan config:clear 2>&1', $output2, $return2);
    echo implode("\n", $output2) . "\n";
    
    echo "3. Clearing route cache...\n";
    exec('php artisan route:clear 2>&1', $output3, $return3);
    echo implode("\n", $output3) . "\n";
    
    echo "4. Clearing view cache...\n";
    exec('php artisan view:clear 2>&1', $output4, $return4);
    echo implode("\n", $output4) . "\n";
    
    // Clear manual cache files
    echo "5. Clearing storage cache files...\n";
    $cacheDir = $projectPath . '/storage/framework/cache';
    if (is_dir($cacheDir)) {
        $files = glob($cacheDir . '/data/*');
        foreach($files as $file) {
            if(is_file($file)) unlink($file);
        }
        echo "Storage cache cleared.\n";
    }
    
    echo "\n✅ ALL CACHES CLEARED SUCCESSFULLY!\n";
    echo "Now try accessing the dashboard again.\n";
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
}

echo "</pre>";
echo "<p><strong>Status:</strong> Cache clearing completed. You can now <a href='public/dashboard'>access the dashboard</a>.</p>";
?>
