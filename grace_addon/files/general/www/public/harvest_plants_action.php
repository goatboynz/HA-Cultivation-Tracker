<?php require_once 'auth.php'; ?>
<?php
require_once 'init_db.php';

try {
    $pdo = initializeDatabase();
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }
    
    $plants = json_decode($_POST['plants'], true);
    $currentStage = $_POST['current_stage'];
    
    if (!$plants || !$currentStage) {
        throw new Exception('Missing required parameters');
    }
    
    $pdo->beginTransaction();
    
    foreach ($plants as $plant) {
        $sql = "UPDATE Plants 
                SET status = 'Harvested', 
                    date_harvested = CURRENT_TIMESTAMP 
                WHERE genetics_id = ? 
                AND room_id = ? 
                AND growth_stage = ? 
                AND status = 'Growing'";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $plant['genetics_id'],
            $plant['current_room_id'],
            $currentStage
        ]);
    }
    
    $pdo->commit();
    
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'message' => 'Plants harvested successfully']);
    
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>