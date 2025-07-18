<?php
require_once 'auth.php';
require_once 'init_db.php';

header('Content-Type: application/json');

try {
    $pdo = initializeDatabase();
    
    // Get database file info
    $dbPath = __DIR__ . '/grace.db';
    $fileSize = file_exists($dbPath) ? filesize($dbPath) : 0;
    $lastModified = file_exists($dbPath) ? filemtime($dbPath) : 0;
    
    // Get table counts
    $tables = [];
    $stmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
    $tableNames = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    foreach ($tableNames as $tableName) {
        $countStmt = $pdo->query("SELECT COUNT(*) FROM `{$tableName}`");
        $count = $countStmt->fetchColumn();
        $tables[$tableName] = $count;
    }
    
    // Get version info (if available)
    $version = 'Unknown';
    try {
        $versionStmt = $pdo->query("SELECT value FROM settings WHERE key = 'version' LIMIT 1");
        if ($versionStmt) {
            $versionResult = $versionStmt->fetch(PDO::FETCH_ASSOC);
            if ($versionResult) {
                $version = $versionResult['value'];
            }
        }
    } catch (Exception $e) {
        // Settings table might not exist, that's okay
    }
    
    echo json_encode([
        'success' => true,
        'data' => [
            'file_size' => $fileSize,
            'file_size_formatted' => formatBytes($fileSize),
            'last_modified' => $lastModified,
            'last_modified_formatted' => date('Y-m-d H:i:s', $lastModified),
            'version' => $version,
            'tables' => $tables,
            'total_records' => array_sum($tables)
        ]
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

function formatBytes($size, $precision = 2) {
    $units = array('B', 'KB', 'MB', 'GB', 'TB');
    
    for ($i = 0; $size > 1024 && $i < count($units) - 1; $i++) {
        $size /= 1024;
    }
    
    return round($size, $precision) . ' ' . $units[$i];
}
?>