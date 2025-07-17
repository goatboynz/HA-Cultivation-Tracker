<?php require_once 'auth.php'; ?>
<?php
require_once 'init_db.php';

try {
    $pdo = initializeDatabase();
    
    $sql = "SELECT g.*, 
                   COUNT(p.id) as plant_count
            FROM Genetics g 
            LEFT JOIN Plants p ON g.id = p.genetics_id AND p.status = 'Growing'
            GROUP BY g.id
            ORDER BY g.name";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $genetics = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    header('Content-Type: application/json');
    echo json_encode($genetics);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>