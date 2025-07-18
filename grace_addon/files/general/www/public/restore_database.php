<?php
require_once 'auth.php';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }
    
    if (!isset($_FILES['backup_file']) || $_FILES['backup_file']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('No backup file uploaded or upload error occurred');
    }
    
    $uploadedFile = $_FILES['backup_file'];
    $dbPath = __DIR__ . '/grace.db';
    
    // Validate file type (basic check)
    $allowedExtensions = ['db', 'sqlite', 'sqlite3'];
    $fileExtension = strtolower(pathinfo($uploadedFile['name'], PATHINFO_EXTENSION));
    
    if (!in_array($fileExtension, $allowedExtensions)) {
        throw new Exception('Invalid file type. Please upload a .db, .sqlite, or .sqlite3 file');
    }
    
    // Validate file size (max 100MB)
    $maxSize = 100 * 1024 * 1024; // 100MB
    if ($uploadedFile['size'] > $maxSize) {
        throw new Exception('File too large. Maximum size is 100MB');
    }
    
    // Create backup of current database before replacing
    $currentBackupPath = __DIR__ . '/grace_backup_before_restore_' . date('Y-m-d_H-i-s') . '.db';
    if (file_exists($dbPath)) {
        if (!copy($dbPath, $currentBackupPath)) {
            throw new Exception('Failed to create backup of current database');
        }
    }
    
    // Validate the uploaded file is a valid SQLite database
    try {
        $testPdo = new PDO('sqlite:' . $uploadedFile['tmp_name']);
        $testPdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Try to query a basic table to ensure it's a valid database
        $stmt = $testPdo->query("SELECT name FROM sqlite_master WHERE type='table' LIMIT 1");
        $testPdo = null; // Close connection
        
    } catch (Exception $e) {
        // Remove the backup we created since restore failed
        if (file_exists($currentBackupPath)) {
            unlink($currentBackupPath);
        }
        throw new Exception('Invalid database file: ' . $e->getMessage());
    }
    
    // Replace the current database with the uploaded one
    if (!move_uploaded_file($uploadedFile['tmp_name'], $dbPath)) {
        // Try to restore the original database if move failed
        if (file_exists($currentBackupPath)) {
            copy($currentBackupPath, $dbPath);
            unlink($currentBackupPath);
        }
        throw new Exception('Failed to restore database file');
    }
    
    // Set proper permissions
    chmod($dbPath, 0666);
    
    // Clean up the backup file after successful restore
    if (file_exists($currentBackupPath)) {
        unlink($currentBackupPath);
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Database restored successfully'
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>