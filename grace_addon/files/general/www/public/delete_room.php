<?php require_once 'auth.php'; ?>
<?php
require_once 'init_db.php';

try {
    $pdo = initializeDatabase();
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }
    
    $roomId = $_POST['room_id'] ?? '';
    
    if (empty($roomId)) {
        throw new Exception('Room ID is required');
    }
    
    // Check if room has plants assigned to it
    $checkSql = "SELECT COUNT(*) FROM Plants WHERE room_id = ? AND status = 'Growing'";
    $checkStmt = $pdo->prepare($checkSql);
    $checkStmt->execute([$roomId]);
    
    if ($checkStmt->fetchColumn() > 0) {
        throw new Exception('Cannot delete room that has plants assigned to it');
    }
    
    $sql = "DELETE FROM Rooms WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$roomId]);
    
    if ($stmt->rowCount() === 0) {
        throw new Exception('Room not found');
    }
    
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'message' => 'Room deleted successfully']);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>