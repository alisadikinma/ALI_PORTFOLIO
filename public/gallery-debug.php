<?php
// Gallery Debug - Check file paths and existence
echo "<h1>Gallery Debug Tool</h1>";

// Check gallery folder
$galleryPath = __DIR__ . '/file/galeri';
echo "<h2>Gallery Folder: " . $galleryPath . "</h2>";

if (is_dir($galleryPath)) {
    echo "<p style='color: green;'>✅ Gallery folder exists</p>";
    
    // List all files
    $files = scandir($galleryPath);
    echo "<h3>Files in gallery folder:</h3>";
    echo "<ul>";
    foreach ($files as $file) {
        if ($file != '.' && $file != '..') {
            $filePath = $galleryPath . '/' . $file;
            $fileSize = filesize($filePath);
            $isImage = in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif']);
            
            echo "<li>";
            echo "<strong>$file</strong> ";
            echo "(" . number_format($fileSize / 1024, 2) . " KB) ";
            echo $isImage ? "<span style='color: green;'>📷 Image</span>" : "<span style='color: orange;'>📄 Other</span>";
            
            // Show URL
            $webUrl = "/ALI_PORTFOLIO/public/file/galeri/" . $file;
            echo " - <a href='$webUrl' target='_blank'>View</a>";
            echo "</li>";
        }
    }
    echo "</ul>";
    
    // Test specific files from database
    echo "<h3>Test Database Files:</h3>";
    $testFiles = [
        'gallery_1757584374_0.jpg',
        'gallery_1757584374_1.jpg', 
        'gallery_1757584374_2.jpg',
        'gallery_1757584374_3.jpg',
        'gallery_1757584329_0.jpg'
    ];
    
    foreach ($testFiles as $testFile) {
        $fullPath = $galleryPath . '/' . $testFile;
        if (file_exists($fullPath)) {
            $webUrl = "/ALI_PORTFOLIO/public/file/galeri/" . $testFile;
            echo "<p style='color: green;'>✅ $testFile exists - <a href='$webUrl' target='_blank'>View</a></p>";
        } else {
            echo "<p style='color: red;'>❌ $testFile NOT FOUND</p>";
        }
    }
    
} else {
    echo "<p style='color: red;'>❌ Gallery folder does not exist</p>";
}

// Check Laravel asset() function result
echo "<h3>Laravel Asset URLs:</h3>";
echo "<p>Base URL: " . (isset($_SERVER['HTTP_HOST']) ? "http://" . $_SERVER['HTTP_HOST'] : "Unknown") . "</p>";
echo "<p>Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "</p>";
echo "<p>Current Directory: " . __DIR__ . "</p>";

// Check web server access
echo "<h3>Web Server Test:</h3>";
echo "<img src='/ALI_PORTFOLIO/public/file/galeri/gallery_1757584374_0.jpg' alt='Test Image' style='max-width: 200px; border: 2px solid #ccc;' onerror='this.parentNode.innerHTML=\"❌ Image failed to load\"'>";

?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; }
h1 { color: #333; }
h2 { color: #666; border-bottom: 1px solid #ddd; }
h3 { color: #888; }
ul { background: #f5f5f5; padding: 15px; border-radius: 5px; }
li { margin: 5px 0; }
a { color: #007bff; text-decoration: none; }
a:hover { text-decoration: underline; }
</style>
