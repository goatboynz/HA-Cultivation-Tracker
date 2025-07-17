<?php require_once 'auth.php'; ?>
<?php
require_once 'init_db.php';
require_once 'generate_tracking_number.php';

try {
    $pdo = initializeDatabase();
    
    $geneticsId = $_POST['geneticsName'] ?? '';
    $roomId = $_POST['roomName'] ?? '';
    $plantTag = $_POST['plantTag'] ?? '';
    $notes = $_POST['notes'] ?? '';
    
    if (!$geneticsId || !$roomId) {
        throw new Exception('Genetics and Room are required');
    }
    
    // Generate tracking number
    $trackingNumber = generateTrackingNumber($pdo);
    
    $sql = "INSERT INTO Plants (tracking_number, genetics_id, room_id, plant_tag, notes, status, growth_stage, is_mother, date_created) 
            VALUES (?, ?, ?, ?, ?, 'Growing', 'Mother', 1, CURRENT_TIMESTAMP)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$trackingNumber, $geneticsId, $roomId, $plantTag, $notes]);
    
    header('Location: plants_mother.php?success=' . urlencode('Mother plant added successfully'));
    exit;
    
} catch (Exception $e) {
    header('Location: plants_mother.php?error=' . urlencode($e->getMessage()));
    exit;
}
?>