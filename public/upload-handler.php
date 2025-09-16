<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type, X-Requested-With');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Log function for debugging
function logToFile($message) {
    $logFile = __DIR__ . '/upload-debug.log';
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($logFile, "[$timestamp] $message\n", FILE_APPEND | LOCK_EX);
}

try {
    logToFile("=== UPLOAD HANDLER STARTED ===");
    logToFile("Request method: " . $_SERVER['REQUEST_METHOD']);
    logToFile("POST data: " . print_r($_POST, true));
    logToFile("FILES data: " . print_r($_FILES, true));

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Only POST method allowed');
    }

    if (!isset($_FILES['file'])) {
        throw new Exception('No file uploaded');
    }

    $file = $_FILES['file'];
    
    // Check for upload errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $errorMessages = [
            UPLOAD_ERR_INI_SIZE => 'File too large (ini_size)',
            UPLOAD_ERR_FORM_SIZE => 'File too large (form_size)',
            UPLOAD_ERR_PARTIAL => 'File partially uploaded',
            UPLOAD_ERR_NO_FILE => 'No file uploaded',
            UPLOAD_ERR_NO_TMP_DIR => 'No temporary directory',
            UPLOAD_ERR_CANT_WRITE => 'Cannot write file',
            UPLOAD_ERR_EXTENSION => 'Upload stopped by extension'
        ];
        throw new Exception('Upload error: ' . ($errorMessages[$file['error']] ?? 'Unknown error'));
    }

    // Validate file type
    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
    $fileType = mime_content_type($file['tmp_name']);
    
    logToFile("File type detected: $fileType");
    
    if (!in_array($fileType, $allowedTypes)) {
        throw new Exception('Invalid file type. Allowed: ' . implode(', ', $allowedTypes));
    }

    // Validate file size (max 5MB)
    $maxSize = 5 * 1024 * 1024; // 5MB
    if ($file['size'] > $maxSize) {
        throw new Exception('File too large. Max size: 5MB');
    }

    // Create upload directory if it doesn't exist
    $uploadDir = __DIR__ . '/uploads/editor/';
    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0755, true)) {
            throw new Exception('Cannot create upload directory');
        }
    }

    // Generate unique filename
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = 'editor_' . time() . '_' . uniqid() . '.' . $extension;
    $targetPath = $uploadDir . $filename;
    
    logToFile("Target path: $targetPath");

    // Move uploaded file
    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
        throw new Exception('Failed to move uploaded file');
    }

    // Generate URL
    $baseUrl = 'http://' . $_SERVER['HTTP_HOST'];
    $relativePath = '/uploads/editor/' . $filename;
    $fullUrl = $baseUrl . $relativePath;
    
    logToFile("File uploaded successfully: $fullUrl");

    // Return success response
    echo json_encode([
        'success' => true,
        'url' => $fullUrl,
        'filename' => $filename,
        'size' => $file['size'],
        'type' => $fileType
    ]);

} catch (Exception $e) {
    logToFile("ERROR: " . $e->getMessage());
    
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'debug' => [
            'post' => $_POST,
            'files' => $_FILES,
            'server' => [
                'request_method' => $_SERVER['REQUEST_METHOD'],
                'content_type' => $_SERVER['CONTENT_TYPE'] ?? 'not set'
            ]
        ]
    ]);
}
?>
