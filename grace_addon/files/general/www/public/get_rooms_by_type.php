<?php require_once 'auth.php'; ?>
<?php
require_once 'init_db.php';

try {
    $pdo = initializeDatabase();
    
    $type = $_GET['type'] ?? '';
    
    if (!in_array($type, ['Clone', 'Veg', 'Flower', 'Mother', 'Dry', 'Storage'])) {
        throw new Exception('Invalid room type specified');
    }
    
    $sql = "SELECT id, name, description FROM Rooms WHERE room_type = ? ORDER BY name";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$type]);
    $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    header('Content-Type: application/json');
    echo json_encode($rooms);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>