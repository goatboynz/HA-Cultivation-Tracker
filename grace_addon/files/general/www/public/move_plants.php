<?php require_once 'auth.php'; ?>
<?php
require_once 'init_db.php';

try {
    $pdo = initializeDatabase();
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }
    
    $plants = json_decode($_POST['plants'], true);
    $targetStage = $_POST['target_stage'];
    $targetRoom = $_POST['target_room'];
    $currentStage = $_POST['current_stage'];
    
    if (!$plants || !$targetStage || !$targetRoom || !$currentStage) {
        throw new Exception('Missing required parameters');
    }
    
    if (!in_array($targetStage, ['Clone', 'Veg', 'Flower', 'Mother'])) {
        throw new Exception('Invalid target stage');
    }
    
    $pdo->beginTransaction();
    
    foreach ($plants as $plant) {
        // Handle different plant data structures
        if (is_array($plant) && isset($plant['genetics_id'])) {
            // Bulk move format from stage pages
            $sql = "UPDATE Plants 
                    SET growth_stage = ?, 
                        room_id = ?, 
                        date_stage_changed = CURRENT_TIMESTAMP 
                    WHERE genetics_id = ? 
                    AND room_id = ? 
                    AND growth_stage = ? 
                    AND status = 'Growing'";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $targetStage,
                $targetRoom,
                $plant['genetics_id'],
                $plant['current_room_id'],
                $currentStage
            ]);
        } else {
            // Individual plant ID format from all_plants page
            $plantId = is_array($plant) ? $plant['id'] : $plant;
            
            $sql = "UPDATE Plants 
                    SET growth_stage = ?, 
                        room_id = ?, 
                        date_stage_changed = CURRENT_TIMESTAMP 
                    WHERE id = ? 
                    AND status = 'Growing'";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $targetStage,
                $targetRoom,
                $plantId
            ]);
        }
    }
    
    $pdo->commit();
    
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'message' => 'Plants moved successfully']);
    
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>