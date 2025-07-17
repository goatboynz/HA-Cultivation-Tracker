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
    
    $stmt = $pdo->prepare("UPDATE Companies SET name = ?, license_number = ?, address = ?, primary_contact_name = ?, primary_contact_email = ?, primary_contact_phone = ? WHERE id = ?");
    
    $result = $stmt->execute([
        $input['name'],
        $input['license_number'],
        $input['address'],
        $input['primary_contact_name'],
        $input['primary_contact_email'],
        $input['primary_contact_phone'],
        $input['id']
    ]);
    
    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Company updated successfully']);
    } else {
        throw new Exception('Failed to update company');
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>