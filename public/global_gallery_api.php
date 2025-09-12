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

// Load environment configuration from .env file
function loadEnvConfig() {
    $envPath = dirname(__DIR__) . '/.env';
    
    if (!file_exists($envPath)) {
        throw new Exception('.env file not found at: ' . $envPath);
    }
    
    $envVars = [];
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    foreach ($lines as $line) {
        // Skip comments
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        
        // Parse key=value pairs
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            
            // Remove quotes if present
            if ((substr($value, 0, 1) === '"' && substr($value, -1) === '"') ||
                (substr($value, 0, 1) === "'" && substr($value, -1) === "'")) {
                $value = substr($value, 1, -1);
            }
            
            $envVars[$key] = $value;
        }
    }
    
    return $envVars;
}

try {
    // Load environment configuration
    $env = loadEnvConfig();
    
    // Database configuration from .env
    $host = $env['DB_HOST'] ?? 'localhost';
    $port = $env['DB_PORT'] ?? '3306';
    $dbname = $env['DB_DATABASE'] ?? '';
    $username = $env['DB_USERNAME'] ?? '';
    $password = $env['DB_PASSWORD'] ?? '';
    
    // Validate required database config
    if (empty($dbname) || empty($username)) {
        throw new Exception('Database configuration incomplete in .env file');
    }
    
    // Create PDO connection
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password);
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
    $baseUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/ALI_PORTFOLIO/public/file/galeri/';
    
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
        'base_url' => $baseUrl,
        'database_connected' => true,
        'env_loaded' => true,
        'db_host' => $host,
        'db_name' => $dbname
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
            'db_host' => $host ?? 'not loaded',
            'db_name' => $dbname ?? 'not loaded',
            'error_code' => $e->getCode(),
            'env_file_path' => dirname(__DIR__) . '/.env'
        ]
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => 'Configuration or system error',
        'message' => $e->getMessage(),
        'debug' => [
            'file' => __FILE__,
            'line' => $e->getLine(),
            'env_file_exists' => file_exists(dirname(__DIR__) . '/.env')
        ]
    ]);
}
?>
