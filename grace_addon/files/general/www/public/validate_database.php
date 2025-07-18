<?php
require_once 'auth.php';
require_once 'init_db.php';

header('Content-Type: application/json');

try {
    $pdo = initializeDatabase();
    
    // Check if database is accessible and has expected tables
    $expectedTables = [
        'Plants',
        'Genetics',
        'Rooms',
        'Companies'
    ];
    
    $existingTables = [];
    $stmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
    $existingTables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    $missingTables = array_diff($expectedTables, $existingTables);
    $extraTables = array_diff($existingTables, $expectedTables);
    
    // Test basic operations
    $canRead = false;
    $canWrite = false;
    
    try {
        // Test read
        $stmt = $pdo->query("SELECT COUNT(*) FROM Plants LIMIT 1");
        $canRead = true;
        
        // Test write (create a temporary test record)
        $pdo->beginTransaction();
        $stmt = $pdo->prepare("INSERT INTO Plants (genetics_id, growth_stage, status, date_created) VALUES (?, ?, ?, ?)");
        $stmt->execute([1, 'Test', 'Test', date('Y-m-d H:i:s')]);
        $testId = $pdo->lastInsertId();
        
        // Clean up test record
        $stmt = $pdo->prepare("DELETE FROM Plants WHERE id = ?");
        $stmt->execute([$testId]);
        $pdo->commit();
        
        $canWrite = true;
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
    }
    
    echo json_encode([
        'success' => true,
        'data' => [
            'database_accessible' => true,
            'can_read' => $canRead,
            'can_write' => $canWrite,
            'existing_tables' => $existingTables,
            'missing_tables' => $missingTables,
            'extra_tables' => $extraTables,
            'validation_status' => (empty($missingTables) && $canRead && $canWrite) ? 'healthy' : 'issues_detected'
        ]
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'data' => [
            'database_accessible' => false,
            'validation_status' => 'error'
        ]
    ]);
}
?>