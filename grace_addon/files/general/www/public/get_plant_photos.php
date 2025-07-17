<?php require_once 'auth.php'; ?>
<?php
require_once 'init_db.php';

try {
    $pdo = initializeDatabase();
    
    $plantId = $_GET['plant_id'] ?? '';
    
    if (!$plantId) {
        throw new Exception('Plant ID is required');
    }
    
    $sql = "SELECT * FROM PlantPhotos WHERE plant_id = ? ORDER BY upload_date DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$plantId]);
    $photos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    header('Content-Type: application/json');
    echo json_encode($photos);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>