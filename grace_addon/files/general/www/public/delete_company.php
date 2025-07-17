<?php
require_once 'auth.php';
require_once 'init_db.php';

header('Content-Type: application/json');

try {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input || !isset($input['id'])) {
        throw new Exception('Invalid input data');
    }
    
    $pdo = initializeDatabase();
    
    // Check if company is referenced in other tables
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM Plants WHERE company_id = ?");
    $stmt->execute([$input['id']]);
    $plantCount = $stmt->fetchColumn();
    
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM Flower WHERE company_id = ?");
    $stmt->execute([$input['id']]);
    $flowerCount = $stmt->fetchColumn();
    
    if ($plantCount > 0 || $flowerCount > 0) {
        throw new Exception('Cannot delete company: it is referenced by existing plants or flower transactions');
    }
    
    $stmt = $pdo->prepare("DELETE FROM Companies WHERE id = ?");
    $result = $stmt->execute([$input['id']]);
    
    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Company deleted successfully']);
    } else {
        throw new Exception('Failed to delete company');
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>