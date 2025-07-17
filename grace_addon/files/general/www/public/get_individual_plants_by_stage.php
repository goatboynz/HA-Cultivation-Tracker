<?php require_once 'auth.php'; ?>
<?php
require_once 'init_db.php';

try {
    $pdo = initializeDatabase();
    
    $stage = $_GET['stage'] ?? '';
    
    if (!in_array($stage, ['Clone', 'Veg', 'Flower', 'Mother'])) {
        throw new Exception('Invalid stage specified');
    }
    
    $sql = "SELECT p.*, 
                   g.name as genetics_name, 
                   r.name as room_name,
                   m.plant_tag as mother_plant_tag
            FROM Plants p 
            LEFT JOIN Genetics g ON p.genetics_id = g.id 
            LEFT JOIN Rooms r ON p.room_id = r.id 
            LEFT JOIN Plants m ON p.mother_id = m.id
            WHERE p.growth_stage = ? AND p.status = 'Growing'
            ORDER BY p.date_created DESC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$stage]);
    $plants = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    header('Content-Type: application/json');
    echo json_encode($plants);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>