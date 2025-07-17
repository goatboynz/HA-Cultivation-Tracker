<?php require_once 'auth.php'; ?>
<?php
require_once 'init_db.php';

try {
    $pdo = initializeDatabase();
    
    $sql = "SELECT p.*, 
                   g.name as genetics_name, 
                   r.name as room_name,
                   (SELECT COUNT(*) FROM Plants c WHERE c.mother_id = p.id) as clone_count
            FROM Plants p 
            LEFT JOIN Genetics g ON p.genetics_id = g.id 
            LEFT JOIN Rooms r ON p.room_id = r.id 
            WHERE p.is_mother = 1 
            ORDER BY g.name, p.date_created DESC";
    
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