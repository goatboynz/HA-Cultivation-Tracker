<?php require_once 'auth.php'; ?>
<?php
require_once 'init_db.php';

try {
    $pdo = initializeDatabase();
    
    $sql = "SELECT s.id, s.batch_name, g.name as genetics_name, s.seed_count, s.used_count
            FROM SeedStock s 
            LEFT JOIN Genetics g ON s.genetics_id = g.id 
            WHERE (s.seed_count - COALESCE(s.used_count, 0)) > 0
            ORDER BY s.batch_name";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $seedStocks = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    header('Content-Type: application/json');
    echo json_encode($seedStocks);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>