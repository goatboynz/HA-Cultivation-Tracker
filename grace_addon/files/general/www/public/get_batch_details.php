<?php
require_once 'auth.php';
require_once 'init_db.php';

header('Content-Type: application/json');

try {
    $pdo = initializeDatabase();
    
    $batchId = $_GET['id'] ?? null;
    $batchType = $_GET['type'] ?? null;
    
    if (!$batchId || !$batchType) {
        throw new Exception('Missing batch ID or type');
    }
    
    if (!in_array($batchType, ['harvest', 'destruction'])) {
        throw new Exception('Invalid batch type');
    }
    
    // Get batch information
    if ($batchType === 'destruction') {
        $batchSQL = "SELECT *, 'destruction' as batch_type, destruction_date as operation_date FROM BatchDestructions WHERE id = ?";
    } else {
        $batchSQL = "SELECT *, 'harvest' as batch_type, harvest_date as operation_date FROM BatchHarvests WHERE id = ?";
    }
    
    $stmt = $pdo->prepare($batchSQL);
    $stmt->execute([$batchId]);
    $batch = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$batch) {
        throw new Exception('Batch not found');
    }
    
    // Get plant details for this batch
    $plantsSQL = "SELECT * FROM BatchPlantDetails WHERE batch_id = ? AND batch_type = ? ORDER BY tracking_number";
    $stmt = $pdo->prepare($plantsSQL);
    $stmt->execute([$batchId, $batchType]);
    $plants = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'batch' => $batch,
        'plants' => $plants
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'batch' => null,
        'plants' => []
    ]);
}
?>