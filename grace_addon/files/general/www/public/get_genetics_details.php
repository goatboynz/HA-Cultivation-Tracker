<?php require_once 'auth.php'; ?>
<?php
require_once 'init_db.php';

try {
    $pdo = initializeDatabase();
    
    $geneticsId = $_GET['id'] ?? '';
    
    if (!$geneticsId) {
        throw new Exception('Genetics ID is required');
    }
    
    $sql = "SELECT * FROM Genetics WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$geneticsId]);
    $genetics = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$genetics) {
        throw new Exception('Genetics not found');
    }
    
    header('Content-Type: application/json');
    echo json_encode($genetics);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>