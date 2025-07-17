<?php require_once 'auth.php'; ?>
<?php
require_once 'init_db.php';
require_once 'generate_tracking_number.php';

try {
    $pdo = initializeDatabase();
    
    // Check if tracking_number column exists
    $sql = "PRAGMA table_info(Plants)";
    $stmt = $pdo->query($sql);
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $hasTrackingNumber = false;
    foreach ($columns as $column) {
        if ($column['name'] === 'tracking_number') {
            $hasTrackingNumber = true;
            break;
        }
    }
    
    if (!$hasTrackingNumber) {
        // Add tracking_number column if it doesn't exist
        $pdo->exec("ALTER TABLE Plants ADD COLUMN tracking_number TEXT");
        
        // Create unique index
        $pdo->exec("CREATE UNIQUE INDEX IF NOT EXISTS idx_plants_tracking_number ON Plants(tracking_number)");
    }
    
    // Find plants without tracking numbers
    $sql = "SELECT id FROM Plants WHERE tracking_number IS NULL OR tracking_number = ''";
    $stmt = $pdo->query($sql);
    $plantsWithoutTracking = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($plantsWithoutTracking) > 0) {
        $updateSql = "UPDATE Plants SET tracking_number = ? WHERE id = ?";
        $updateStmt = $pdo->prepare($updateSql);
        
        $updatedCount = 0;
        foreach ($plantsWithoutTracking as $plant) {
            $trackingNumber = generateTrackingNumber($pdo);
            $updateStmt->execute([$trackingNumber, $plant['id']]);
            $updatedCount++;
        }
        
        echo json_encode([
            'success' => true, 
            'message' => "Migration completed. Updated {$updatedCount} plants with tracking numbers."
        ]);
    } else {
        echo json_encode([
            'success' => true, 
            'message' => 'All plants already have tracking numbers.'
        ]);
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>