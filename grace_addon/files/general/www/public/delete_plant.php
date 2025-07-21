<?php require_once 'auth.php'; ?>
<?php
require_once 'init_db.php';

try {
    $pdo = initializeDatabase();
    
    $plantId = $_POST['plant_id'] ?? '';
    
    if (!$plantId) {
        throw new Exception('Plant ID is required');
    }
    
    // Start transaction
    $pdo->beginTransaction();
    
    // Get plant info for logging
    $plantStmt = $pdo->prepare("SELECT tracking_number, plant_tag, genetics_id FROM Plants WHERE id = ?");
    $plantStmt->execute([$plantId]);
    $plant = $plantStmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$plant) {
        throw new Exception('Plant not found');
    }
    
    // Delete related records first (to maintain referential integrity)
    
    // Delete plant photos
    $photoStmt = $pdo->prepare("DELETE FROM PlantPhotos WHERE plant_id = ?");
    $photoStmt->execute([$plantId]);
    
    // Delete batch plant details if exists
    $batchStmt = $pdo->prepare("DELETE FROM BatchPlantDetails WHERE plant_id = ?");
    $batchStmt->execute([$plantId]);
    
    // Update any plants that have this as their mother (set mother_id to null)
    $updateChildrenStmt = $pdo->prepare("UPDATE Plants SET mother_id = NULL WHERE mother_id = ?");
    $updateChildrenStmt->execute([$plantId]);
    
    // Delete the plant itself
    $deleteStmt = $pdo->prepare("DELETE FROM Plants WHERE id = ?");
    $deleteStmt->execute([$plantId]);
    
    // Log the deletion (optional - you can create a deletion log table)
    $logStmt = $pdo->prepare("INSERT INTO PlantDeletionLog (plant_id, tracking_number, plant_tag, genetics_id, deleted_at, deleted_by) VALUES (?, ?, ?, ?, datetime('now'), 'system')");
    
    // Create the log table if it doesn't exist
    $pdo->exec("CREATE TABLE IF NOT EXISTS PlantDeletionLog (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        plant_id INTEGER,
        tracking_number TEXT,
        plant_tag TEXT,
        genetics_id INTEGER,
        deleted_at DATETIME,
        deleted_by TEXT
    )");
    
    $logStmt->execute([$plantId, $plant['tracking_number'], $plant['plant_tag'], $plant['genetics_id']]);
    
    // Commit transaction
    $pdo->commit();
    
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'message' => 'Plant deleted successfully']);
    
} catch (Exception $e) {
    // Rollback transaction on error
    if ($pdo->inTransaction()) {
        $pdo->rollback();
    }
    
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>