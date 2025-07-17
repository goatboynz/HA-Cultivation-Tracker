<?php require_once 'auth.php'; ?>
<?php
require_once 'init_db.php';

try {
    $pdo = initializeDatabase();
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }
    
    $roomName = trim($_POST['roomName'] ?? '');
    $roomType = $_POST['roomType'] ?? '';
    $roomDescription = trim($_POST['roomDescription'] ?? '');
    
    if (empty($roomName) || empty($roomType)) {
        throw new Exception('Room name and type are required');
    }
    
    if (!in_array($roomType, ['Clone', 'Veg', 'Flower', 'Dry', 'Storage'])) {
        throw new Exception('Invalid room type');
    }
    
    // Check if room name already exists
    $checkSql = "SELECT COUNT(*) FROM Rooms WHERE name = ?";
    $checkStmt = $pdo->prepare($checkSql);
    $checkStmt->execute([$roomName]);
    
    if ($checkStmt->fetchColumn() > 0) {
        throw new Exception('A room with this name already exists');
    }
    
    $sql = "INSERT INTO Rooms (name, room_type, description) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$roomName, $roomType, $roomDescription]);
    
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'message' => 'Room added successfully']);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>