<?php require_once 'auth.php'; ?>
<?php
require_once 'init_db.php';

try {
    $pdo = initializeDatabase();
    
    $photoId = $_POST['photo_id'] ?? '';
    
    if (!$photoId) {
        throw new Exception('Photo ID is required');
    }
    
    // Get photo info before deleting
    $sql = "SELECT file_path FROM PlantPhotos WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$photoId]);
    $photo = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($photo) {
        // Delete file from filesystem
        if (file_exists($photo['file_path'])) {
            unlink($photo['file_path']);
        }
        
        // Delete from database
        $deleteSql = "DELETE FROM PlantPhotos WHERE id = ?";
        $deleteStmt = $pdo->prepare($deleteSql);
        $deleteStmt->execute([$photoId]);
    }
    
    echo json_encode(['success' => true]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>