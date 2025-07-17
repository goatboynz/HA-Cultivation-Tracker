<?php require_once 'auth.php'; ?>
<?php
require_once 'init_db.php';

try {
    $pdo = initializeDatabase();
    
    // Check if rooms already exist
    $checkSql = "SELECT COUNT(*) FROM Rooms";
    $checkStmt = $pdo->query($checkSql);
    $roomCount = $checkStmt->fetchColumn();
    
    if ($roomCount > 0) {
        echo json_encode(['success' => false, 'message' => 'Rooms already exist in database']);
        exit;
    }
    
    // Default rooms to create
    $defaultRooms = [
        ['Clone Room 1', 'Clone', 'Main clone/cutting room'],
        ['Mother Room', 'Mother', 'Mother plant maintenance room'],
        ['Veg Room 1', 'Veg', 'Primary vegetative growth room'],
        ['Flower Room 1', 'Flower', 'Main flowering room'],
        ['Flower Room 2', 'Flower', 'Secondary flowering room'],
        ['Dry Room', 'Dry', 'Drying and curing room'],
        ['Storage', 'Storage', 'General storage area']
    ];
    
    $pdo->beginTransaction();
    
    $sql = "INSERT INTO Rooms (name, room_type, description) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    
    foreach ($defaultRooms as $room) {
        $stmt->execute($room);
    }
    
    $pdo->commit();
    
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'message' => 'Default rooms created successfully']);
    
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>