<?php require_once 'auth.php'; ?>
<?php
require_once 'init_db.php';

try {
    $pdo = initializeDatabase();
    
    $seedStockId = $_GET['id'] ?? '';
    
    if (!$seedStockId) {
        throw new Exception('Seed stock ID is required');
    }
    
    $sql = "SELECT s.*, g.name as genetics_name
            FROM SeedStock s 
            LEFT JOIN Genetics g ON s.genetics_id = g.id 
            WHERE s.id = ?";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$seedStockId]);
    $seedStock = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$seedStock) {
        throw new Exception('Seed stock not found');
    }
    
    header('Content-Type: application/json');
    echo json_encode($seedStock);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>