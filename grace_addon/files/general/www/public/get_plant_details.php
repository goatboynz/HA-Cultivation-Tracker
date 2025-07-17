<?php require_once 'auth.php'; ?>
<?php
require_once 'init_db.php';

try {
    $pdo = initializeDatabase();
    
    $plantId = $_GET['id'] ?? '';
    
    if (!$plantId) {
        throw new Exception('Plant ID is required');
    }
    
    $sql = "SELECT p.*, g.name as genetics_name, r.name as room_name,
                   (SELECT COUNT(*) FROM Plants c WHERE c.mother_id = p.id) as clone_count
            FROM Plants p 
            LEFT JOIN Genetics g ON p.genetics_id = g.id 
            LEFT JOIN Rooms r ON p.room_id = r.id 
            WHERE p.id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$plantId]);
    $plant = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$plant) {
        throw new Exception('Plant not found');
    }
    
    header('Content-Type: application/json');
    echo json_encode($plant);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>