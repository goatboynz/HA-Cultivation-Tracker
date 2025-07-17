<?php require_once 'auth.php'; ?>
<?php
require_once 'init_db.php';

try {
    $pdo = initializeDatabase();
    
    $sql = "SELECT p.id, p.plant_tag, g.name as genetics_name 
            FROM Plants p 
            LEFT JOIN Genetics g ON p.genetics_id = g.id 
            WHERE p.is_mother = 1 AND p.status = 'Growing'
            ORDER BY g.name, p.plant_tag";
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