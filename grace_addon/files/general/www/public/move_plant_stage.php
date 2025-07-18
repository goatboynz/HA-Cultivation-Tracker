<?php
require_once 'auth.php';
require_once 'init_db.php';

header('Content-Type: application/json');

try {
    $pdo = initializeDatabase();
    
    // Handle both GET and POST requests
    $plantId = $_GET['plant_id'] ?? $_POST['plant_id'] ?? null;
    $targetStage = $_GET['stage'] ?? $_POST['stage'] ?? null;
    
    if (!$plantId || !$targetStage) {
        throw new Exception('Missing plant ID or target stage');
    }
    
    if (!in_array($targetStage, ['Clone', 'Veg', 'Flower', 'Mother'])) {
        throw new Exception('Invalid target stage');
    }
    
    // Get plant details
    $stmt = $pdo->prepare("SELECT * FROM Plants WHERE id = ?");
    $stmt->execute([$plantId]);
    $plant = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$plant) {
        throw new Exception('Plant not found');
    }
    
    // Get a suitable room for the target stage
    $stmt = $pdo->prepare("SELECT id FROM Rooms WHERE room_type = ? LIMIT 1");
    $stmt->execute([$targetStage]);
    $room = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$room) {
        // If no specific room type exists, get any room
        $stmt = $pdo->prepare("SELECT id FROM Rooms LIMIT 1");
        $stmt->execute();
        $room = $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    if (!$room) {
        throw new Exception('No rooms available. Please create rooms first.');
    }
    
    // Update the plant
    $stmt = $pdo->prepare("UPDATE Plants SET growth_stage = ?, room_id = ?, date_stage_changed = CURRENT_TIMESTAMP WHERE id = ?");
    $result = $stmt->execute([$targetStage, $room['id'], $plantId]);
    
    if ($result) {
        echo json_encode([
            'success' => true,
            'message' => "Plant moved to {$targetStage} stage successfully"
        ]);
    } else {
        throw new Exception('Failed to update plant stage');
    }
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>