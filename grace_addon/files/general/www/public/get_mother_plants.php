<?php require_once 'auth.php'; ?>
<?php
require_once 'init_db.php';

try {
    $pdo = initializeDatabase();
    
    // Get mother plants (plants marked as mothers OR plants in Mother stage)
    $sql = "SELECT p.id, p.plant_tag, p.tracking_number, g.name as genetics_name, r.name as room_name,
                   p.growth_stage, p.is_mother
            FROM Plants p 
            LEFT JOIN Genetics g ON p.genetics_id = g.id 
            LEFT JOIN Rooms r ON p.room_id = r.id
            WHERE (p.is_mother = 1 OR p.growth_stage = 'Mother') 
            AND p.status = 'Growing'
            ORDER BY g.name, p.plant_tag, p.tracking_number";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $mothers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    header('Content-Type: application/json');
    echo json_encode($mothers);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>