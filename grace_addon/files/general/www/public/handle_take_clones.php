<?php require_once 'auth.php'; ?>
<?php
require_once 'init_db.php';
require_once 'generate_tracking_number.php';

try {
    $pdo = initializeDatabase();
    
    $motherId = $_POST['motherId'] ?? '';
    $cloneCount = $_POST['cloneCount'] ?? '';
    $roomId = $_POST['roomName'] ?? '';
    $notes = $_POST['notes'] ?? '';
    
    if (!$motherId || !$cloneCount || !$roomId) {
        throw new Exception('All required fields must be filled');
    }
    
    // Get mother plant genetics
    $sql = "SELECT genetics_id FROM Plants WHERE id = ? AND is_mother = 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$motherId]);
    $mother = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$mother) {
        throw new Exception('Mother plant not found');
    }
    
    // Generate tracking numbers for all clones
    $trackingNumbers = generateBatchTrackingNumbers($pdo, $cloneCount);
    
    // Insert clones
    $sql = "INSERT INTO Plants (tracking_number, genetics_id, room_id, mother_id, status, growth_stage, notes, date_created) 
            VALUES (?, ?, ?, ?, 'Growing', 'Clone', ?, CURRENT_TIMESTAMP)";
    
    $stmt = $pdo->prepare($sql);
    
    for ($i = 0; $i < $cloneCount; $i++) {
        $stmt->execute([$trackingNumbers[$i], $mother['genetics_id'], $roomId, $motherId, $notes]);
    }
    
    header('Location: plants_mother.php?success=' . urlencode("Successfully took {$cloneCount} clones"));
    exit;
    
} catch (Exception $e) {
    header('Location: take_clones.php?mother_id=' . urlencode($_POST['motherId'] ?? '') . '&error=' . urlencode($e->getMessage()));
    exit;
}
?>