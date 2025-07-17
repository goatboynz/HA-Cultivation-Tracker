<?php require_once 'auth.php'; ?>
<?php
require_once 'init_db.php';

try {
    $pdo = initializeDatabase();
    
    $sql = "SELECT s.*, g.name as genetics_name
            FROM SeedStock s 
            LEFT JOIN Genetics g ON s.genetics_id = g.id 
            ORDER BY s.created_date DESC";
    
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