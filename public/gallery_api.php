<?php
/**
 * FIXED GALLERY API - Direct Database Connection
 */

// Set headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Get parameters
$type = $_GET['type'] ?? 'gallery';
$id = $_GET['id'] ?? null;

if (!$id) {
    echo json_encode([
        'success' => false,
        'error' => 'Missing ID parameter',
        'items' => []
    ]);
    exit;
}

// Read database config from Laravel .env
$envPath = __DIR__ . '/../.env';
$env = [];

if (file_exists($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            list($key, $value) = explode('=', $line, 2);
            $env[trim($key)] = trim($value, '"\'');
        }
    }
}

// Use actual credentials from .env (no fallback)
$host = $env['DB_HOST'];
$username = $env['DB_USERNAME'];
$password = $env['DB_PASSWORD'];
$database = $env['DB_DATABASE'];
$galleryPath = $env['GALLERY_STORAGE_PATH'] ?? '/ALI_PORTFOLIO/public/file/galeri';
$awardPath = $env['AWARD_STORAGE_PATH'] ?? '/ALI_PORTFOLIO/public/file/award';

try {
    $mysqli = new mysqli($host, $username, $password, $database);
    
    if ($mysqli->connect_error) {
        throw new Exception('Database connection failed: ' . $mysqli->connect_error);
    }
    
    $items = [];
    $debugInfo = [];
    
    if ($type === 'award') {
        // Get award info
        $stmt = $mysqli->prepare("SELECT * FROM award WHERE id_award = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $award = $result->fetch_assoc();
        
        if (!$award) {
            echo json_encode([
                'success' => false,
                'error' => 'Award not found with id: ' . $id,
                'items' => []
            ]);
            exit;
        }
        
        $debugInfo['award_found'] = $award['nama_award'];
        
        // Try to find gallery items - multiple approaches
        $queries = [
            "SELECT * FROM gallery_items WHERE id_award = ? AND status = 'Active' ORDER BY sequence ASC",
            "SELECT * FROM gallery_items WHERE id_galeri = ? AND status = 'Active' ORDER BY sequence ASC"
        ];
        
        $galleryItems = [];
        foreach ($queries as $query) {
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $galleryItems[] = $row;
                }
                $debugInfo['found_by'] = str_replace('?', $id, $query);
                break;
            }
        }
        
        $debugInfo['items_found'] = count($galleryItems);
        
        // Process gallery items
        foreach ($galleryItems as $item) {
            $fileUrl = null;
            $itemType = 'unknown';
            
            if (!empty($item['file_name'])) {
                $fileUrl = 'http://' . $_SERVER['HTTP_HOST'] . $galleryPath . '/' . $item['file_name'];
                
                $extension = strtolower(pathinfo($item['file_name'], PATHINFO_EXTENSION));
                if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                    $itemType = 'image';
                } else {
                    $itemType = 'document';
                }
            } elseif (!empty($item['youtube_url'])) {
                $fileUrl = $item['youtube_url'];
                $itemType = 'youtube';
            }
            
            if ($fileUrl) {
                $items[] = [
                    'id' => $item['id_gallery_item'],
                    'type' => $itemType,
                    'file_url' => $fileUrl,
                    'gallery_name' => $award['nama_award'],
                    'sequence' => $item['sequence'] ?? 0,
                    'file_name' => $item['file_name'] ?? null
                ];
            }
        }
        
        // Don't create demo items - let frontend handle no data state
        // if (empty($items)) {
        //     Demo items creation removed
        // }
        
    } elseif ($type === 'gallery') {
        // Regular gallery handling
        $stmt = $mysqli->prepare("SELECT * FROM galeri WHERE id_galeri = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $gallery = $result->fetch_assoc();
        
        if (!$gallery) {
            echo json_encode([
                'success' => false,
                'error' => 'Gallery not found with id: ' . $id,
                'items' => []
            ]);
            exit;
        }
        
        $stmt = $mysqli->prepare("SELECT * FROM gallery_items WHERE id_galeri = ? AND status = 'Active' ORDER BY sequence ASC");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        while ($item = $result->fetch_assoc()) {
            $fileUrl = null;
            $itemType = 'unknown';
            
            if (!empty($item['file_name'])) {
                $fileUrl = 'http://' . $_SERVER['HTTP_HOST'] . $galleryPath . '/' . $item['file_name'];
                
                $extension = strtolower(pathinfo($item['file_name'], PATHINFO_EXTENSION));
                if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                    $itemType = 'image';
                } else {
                    $itemType = 'document';
                }
            } elseif (!empty($item['youtube_url'])) {
                $fileUrl = $item['youtube_url'];
                $itemType = 'youtube';
            }
            
            if ($fileUrl) {
                $items[] = [
                    'id' => $item['id_gallery_item'],
                    'type' => $itemType,
                    'file_url' => $fileUrl,
                    'gallery_name' => $gallery['nama_galeri'],
                    'sequence' => $item['sequence'] ?? 0,
                    'file_name' => $item['file_name'] ?? null
                ];
            }
        }
    }
    
    echo json_encode([
        'success' => true,
        'type' => $type,
        'id' => $id,
        'items' => $items,
        'count' => count($items),
        'debug' => $debugInfo,
        'timestamp' => date('Y-m-d H:i:s')
    ]);
    
    $mysqli->close();
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'items' => [],
        'debug' => $debugInfo ?? []
    ]);
}
?>
