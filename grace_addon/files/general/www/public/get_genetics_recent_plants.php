<?php require_once 'auth.php'; ?>
<?php
require_once 'init_db.php';

try {
    $pdo = initializeDatabase();
    
    $geneticsId = $_GET['id'] ?? '';
    
    if (!$geneticsId) {
        throw new Exception('Genetics ID is required');
    }
    
    $sql = "SELECT p.*, r.name as room_name 
            FROM Plants p 
            LEFT JOIN Rooms r ON p.room_id = r.id 
            WHERE p.genetics_id = ? 
            ORDER BY p.date_created DESC 
            LIMIT 20";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$geneticsId]);
    $plants = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    header('Content-Type: application/json');
    echo json_encode($plants);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>