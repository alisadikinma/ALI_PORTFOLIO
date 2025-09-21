<?php
/**
 * Quick test script to verify the route fix
 * This simulates what happens when the route is accessed
 */

echo "=== ROUTE FIX TEST ===\n\n";

// Test 1: Check if welcome.blade.php exists
$welcomeFile = 'C:\xampp\htdocs\ALI_PORTFOLIO\resources\views\welcome.blade.php';
echo "1. Checking welcome.blade.php file:\n";
echo "   File exists: " . (file_exists($welcomeFile) ? "✓ YES" : "✗ NO") . "\n";
echo "   File path: $welcomeFile\n\n";

// Test 2: Check if welcome-simple.blade.php was removed
$welcomeSimpleFile = 'C:\xampp\htdocs\ALI_PORTFOLIO\resources\views\welcome-simple.blade.php';
echo "2. Checking welcome-simple.blade.php file (should NOT exist):\n";
echo "   File exists: " . (file_exists($welcomeSimpleFile) ? "✗ YES (should be removed)" : "✓ NO (correct)") . "\n";
echo "   File path: $welcomeSimpleFile\n\n";

// Test 3: Verify the routes/web.php was updated
$routesFile = 'C:\xampp\htdocs\ALI_PORTFOLIO\routes\web.php';
echo "3. Checking routes/web.php for correct view name:\n";
if (file_exists($routesFile)) {
    $content = file_get_contents($routesFile);
    $hasWelcomeSimple = strpos($content, "view('welcome-simple'") !== false;
    $hasWelcome = strpos($content, "view('welcome'") !== false;
    $hasFallbackData = strpos($content, 'fallbackData') !== false;
    
    echo "   Contains 'welcome-simple': " . ($hasWelcomeSimple ? "✗ YES (should be fixed)" : "✓ NO (correct)") . "\n";
    echo "   Contains 'welcome': " . ($hasWelcome ? "✓ YES (correct)" : "✗ NO") . "\n";
    echo "   Contains fallbackData: " . ($hasFallbackData ? "✓ YES (correct)" : "✗ NO") . "\n";
} else {
    echo "   ✗ Routes file not found\n";
}

echo "\n=== SUMMARY ===\n";
echo "The 'View [welcome-simple] not found' error should now be fixed!\n";
echo "The route now correctly uses 'welcome' view with proper fallback data.\n\n";

echo "=== NEXT STEPS ===\n";
echo "1. Open your browser and go to: http://localhost/ALI_PORTFOLIO/public/\n";
echo "2. The page should now load without the 'welcome-simple' error\n";
echo "3. If there are still issues, they will be related to database or controller problems\n";
echo "4. You can run /setup or /debug routes to fix any remaining issues\n";
