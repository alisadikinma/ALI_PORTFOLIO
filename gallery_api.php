<?php
// gallery_api.php - Working Gallery API
// Access: http://localhost/ALI_PORTFOLIO/gallery_api.php?award_id=1

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, X-Requested-With');

try {
    // Get award ID from query parameter
    $awardId = $_GET['award_id'] ?? null;
    
    if (!$awardId) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => 'award_id parameter is required',
            'usage' => 'gallery_api.php?award_id=1'
        ]);
        exit;
    }
    
    // Database connection - use the correct database name from screenshot
    $host = 'localhost';
    $username = 'root';
    $password = '';
    
    // Based on screenshot, database name is "portfolio_db"
    $possibleDbs = ['portfolio_db', 'ali_portfolio', 'db_portfolio', 'portfolio'];
    $pdo = null;
    $connectedDb = null;
    
    foreach ($possibleDbs as $db) {
        try {
            $pdo = new PDO("mysql:host=$host;dbname=$db", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $connectedDb = $db;
            break;
        } catch (PDOException $e) {
            continue; // Try next database
        }
    }
    
    if (!$pdo) {
        throw new Exception('Could not connect to any database. Tried: ' . implode(', ', $possibleDbs));
    }
    
    // Get gallery items for this award
    $stmt = $pdo->prepare("
        SELECT 
            gi.*,
            g.nama_galeri as gallery_name
        FROM gallery_items gi 
        LEFT JOIN galeri g ON gi.id_galeri = g.id_galeri
        WHERE gi.id_award = ? AND gi.status = 'Active'
        ORDER BY gi.sequence ASC, gi.id_gallery_item ASC
    ");
    
    $stmt->execute([$awardId]);
    $galleryItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get server info for URL construction
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $serverHost = $_SERVER['HTTP_HOST'];
    $baseUrl = $protocol . '://' . $serverHost;
    
    // Detect project path
    $currentPath = $_SERVER['REQUEST_URI'];
    $basePath = '';
    if (strpos($currentPath, '/ALI_PORTFOLIO/') !== false) {
        $basePath = '/ALI_PORTFOLIO';
    }
    
    // Format items for frontend
    $items = [];
    foreach ($galleryItems as $item) {
        $data = [
            'id' => $item['id_gallery_item'],
            'type' => $item['type'],
            'sequence' => $item['sequence'],
            'title' => $item['title'] ?? 'Gallery Item',
            'gallery_name' => $item['gallery_name'] ?? 'Gallery',
            'status' => $item['status']
        ];
        
        if ($item['type'] === 'image' && $item['file_name']) {
            // Try both public and direct paths
            $data['file_url'] = $baseUrl . $basePath . '/public/file/galeri/' . $item['file_name'];
            $data['file_url_alt'] = $baseUrl . $basePath . '/file/galeri/' . $item['file_name'];
            $data['thumbnail_url'] = $data['file_url'];
        } elseif ($item['type'] === 'youtube' && $item['youtube_url']) {
            $data['file_url'] = $item['youtube_url'];
            $data['youtube_url'] = $item['youtube_url'];
            // Extract YouTube ID for thumbnail
            preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([^&\n?#]+)/', $item['youtube_url'], $matches);
            $videoId = $matches[1] ?? null;
            $data['thumbnail_url'] = $videoId ? "https://img.youtube.com/vi/{$videoId}/maxresdefault.jpg" : null;
        }
        
        $items[] = $data;
    }
    
    // Return response
    echo json_encode([
        'success' => true,
        'items' => $items,
        'total' => count($items),
        'award_id' => $awardId,
        'debug' => [
            'database' => $connectedDb,
            'base_url' => $baseUrl,
            'base_path' => $basePath,
            'current_path' => $currentPath,
            'raw_items_count' => count($galleryItems),
            'timestamp' => date('Y-m-d H:i:s'),
            'query_executed' => true
        ]
    ], JSON_PRETTY_PRINT);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Database error: ' . $e->getMessage(),
        'award_id' => $awardId ?? null,
        'debug' => [
            'tried_databases' => $possibleDbs ?? ['portfolio_db', 'ali_portfolio'],
            'connected_db' => $connectedDb ?? null,
            'php_error' => $e->getMessage()
        ]
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Error: ' . $e->getMessage(),
        'award_id' => $awardId ?? null
    ]);
}
?>