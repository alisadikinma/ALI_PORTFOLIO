<?php
// Global Gallery API - Can serve both Awards and Gallery sections
// File: /public/global_gallery_api.php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, X-Requested-With');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Database configuration
$host = 'localhost';
$dbname = 'portfolio_db'; // Adjust to your database name
$username = 'ali';        // Adjust to your database username
$password = 'aL1889900@@@';            // Adjust to your database password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get parameters
    $type = $_GET['type'] ?? '';  // 'award' or 'gallery'
    $id = $_GET['id'] ?? '';      // award_id or gallery_id
    
    if (empty($type) || empty($id)) {
        echo json_encode([
            'success' => false,
            'error' => 'Missing required parameters: type and id',
            'debug' => [
                'type' => $type,
                'id' => $id,
                'all_params' => $_GET
            ]
        ]);
        exit;
    }
    
    $items = [];
    
    if ($type === 'award') {
        // Get gallery items for award
        $stmt = $pdo->prepare("
            SELECT 
                gi.id_gallery_item,
                gi.type,
                gi.sequence,
                gi.status,
                gi.file_name,
                gi.youtube_url,
                g.nama_galeri as gallery_name,
                CASE 
                    WHEN gi.file_name IS NOT NULL AND gi.file_name != '' 
                    THEN CONCAT(?, gi.file_name)
                    ELSE NULL 
                END as file_url
            FROM gallery_items gi
            LEFT JOIN galeri g ON gi.id_galeri = g.id_galeri
            WHERE gi.id_award = ? AND gi.status = 'Active'
            ORDER BY gi.sequence ASC, gi.id_gallery_item ASC
        ");
        
        // Base URL for files - adjust as needed
        $baseUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/ALI_PORTFOLIO/public/file/galeri/';
        $stmt->execute([$baseUrl, $id]);
        
    } elseif ($type === 'gallery') {
        // Get gallery items for gallery
        $stmt = $pdo->prepare("
            SELECT 
                gi.id_gallery_item,
                gi.type,
                gi.sequence,
                gi.status,
                gi.file_name,
                gi.youtube_url,
                g.nama_galeri as gallery_name,
                CASE 
                    WHEN gi.file_name IS NOT NULL AND gi.file_name != '' 
                    THEN CONCAT(?, gi.file_name)
                    ELSE NULL 
                END as file_url
            FROM gallery_items gi
            LEFT JOIN galeri g ON gi.id_galeri = g.id_galeri
            WHERE gi.id_galeri = ? AND gi.status = 'Active'
            ORDER BY gi.sequence ASC, gi.id_gallery_item ASC
        ");
        
        // Base URL for files - adjust as needed
        $baseUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/ALI_PORTFOLIO/public/file/galeri/';
        $stmt->execute([$baseUrl, $id]);
        
    } else {
        echo json_encode([
            'success' => false,
            'error' => 'Invalid type. Must be "award" or "gallery"',
            'debug' => [
                'type' => $type,
                'valid_types' => ['award', 'gallery']
            ]
        ]);
        exit;
    }
    
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Debug information
    $debug = [
        'query_type' => $type,
        'query_id' => $id,
        'items_found' => count($items),
        'base_url' => $baseUrl ?? 'Not set',
        'database_connected' => true
    ];
    
    if (count($items) > 0) {
        echo json_encode([
            'success' => true,
            'items' => $items,
            'total' => count($items),
            'debug' => $debug
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'items' => [],
            'total' => 0,
            'message' => "No gallery items found for {$type} ID: {$id}",
            'debug' => $debug
        ]);
    }
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => 'Database connection failed',
        'message' => $e->getMessage(),
        'debug' => [
            'host' => $host,
            'database' => $dbname,
            'error_code' => $e->getCode()
        ]
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => 'Unexpected error occurred',
        'message' => $e->getMessage(),
        'debug' => [
            'file' => __FILE__,
            'line' => $e->getLine()
        ]
    ]);
}
?>
