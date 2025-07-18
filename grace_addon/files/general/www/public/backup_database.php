<?php
require_once 'auth.php';
require_once 'init_db.php';

try {
    // Get the database file path
    $dbPath = __DIR__ . '/grace.db';
    
    if (!file_exists($dbPath)) {
        throw new Exception('Database file not found');
    }
    
    // Create a backup filename with timestamp
    $timestamp = date('Y-m-d_H-i-s');
    $backupFilename = "cultivation_backup_{$timestamp}.db";
    
    // Set headers for file download
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . $backupFilename . '"');
    header('Content-Length: ' . filesize($dbPath));
    header('Cache-Control: no-cache, must-revalidate');
    header('Pragma: no-cache');
    header('Expires: 0');
    
    // Output the file
    readfile($dbPath);
    exit;
    
} catch (Exception $e) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>