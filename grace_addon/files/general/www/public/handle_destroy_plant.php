<?php require_once 'auth.php'; ?>
<?php
require_once 'init_db.php';

try {
    $pdo = initializeDatabase();
    
    $plantId = $_POST['plantId'] ?? '';
    $destructionDate = $_POST['destructionDate'] ?? '';
    $destructionReason = $_POST['destructionReason'] ?? '';
    $otherReason = $_POST['otherReason'] ?? '';
    $destructionMethod = $_POST['destructionMethod'] ?? '';
    $plantWeight = $_POST['plantWeight'] ?? null;
    $rootWeight = $_POST['rootWeight'] ?? null;
    $soilWeight = $_POST['soilWeight'] ?? null;
    $totalWeight = $_POST['totalWeight'] ?? null;
    $destructionNotes = $_POST['destructionNotes'] ?? '';
    $witnessName = $_POST['witnessName'] ?? '';
    $complianceNotes = $_POST['complianceNotes'] ?? '';
    $confirmDestruction = $_POST['confirmDestruction'] ?? '';
    $confirmCompliance = $_POST['confirmCompliance'] ?? '';
    
    if (!$plantId || !$destructionDate || !$destructionReason || !$destructionNotes) {
        throw new Exception('Plant ID, destruction date, reason, and notes are required');
    }
    
    if (!$confirmDestruction || !$confirmCompliance) {
        throw new Exception('Both confirmation checkboxes must be checked');
    }
    
    if ($destructionReason === 'other' && !$otherReason) {
        throw new Exception('Please specify the other reason for destruction');
    }
    
    // Use other reason if specified
    $finalReason = $destructionReason === 'other' ? $otherReason : $destructionReason;
    
    // Convert empty strings to null for numeric fields
    if ($plantWeight === '') $plantWeight = null;
    if ($rootWeight === '') $rootWeight = null;
    if ($soilWeight === '') $soilWeight = null;
    if ($totalWeight === '') $totalWeight = null;
    if ($destructionMethod === '') $destructionMethod = null;
    if ($witnessName === '') $witnessName = null;
    if ($complianceNotes === '') $complianceNotes = null;
    
    // Update plant status to destroyed
    $sql = "UPDATE Plants SET 
            status = 'Destroyed',
            destruction_reason = ?,
            destruction_date = ?,
            destruction_notes = ?
            WHERE id = ?";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $finalReason,
        $destructionDate,
        $destructionNotes,
        $plantId
    ]);
    
    // Create detailed destruction record
    $destructionRecordSql = "INSERT INTO DestructionRecords (
        plant_id, destruction_date, reason, method, plant_weight, root_weight, 
        soil_weight, total_weight, notes, witness_name, compliance_notes, created_by
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $destructionStmt = $pdo->prepare($destructionRecordSql);
    $destructionStmt->execute([
        $plantId,
        $destructionDate,
        $finalReason,
        $destructionMethod,
        $plantWeight,
        $rootWeight,
        $soilWeight,
        $totalWeight,
        $destructionNotes,
        $witnessName,
        $complianceNotes,
        'system_user' // This could be replaced with actual user tracking
    ]);
    
    // Handle photo uploads
    $uploadDir = 'uploads/plants/destruction/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    // Handle uploaded photos
    if (isset($_FILES['photos'])) {
        foreach ($_FILES['photos']['tmp_name'] as $key => $tmpName) {
            if ($_FILES['photos']['error'][$key] === UPLOAD_ERR_OK) {
                $originalName = $_FILES['photos']['name'][$key];
                $fileExtension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                
                if (in_array($fileExtension, $allowedExtensions)) {
                    $fileName = uniqid('destruction_' . $plantId . '_') . '.' . $fileExtension;
                    $uploadPath = $uploadDir . $fileName;
                    
                    if (move_uploaded_file($tmpName, $uploadPath)) {
                        $photoSql = "INSERT INTO PlantPhotos (plant_id, file_path, file_name, file_size, photo_type, description) VALUES (?, ?, ?, ?, 'issue', 'Destruction evidence')";
                        $photoStmt = $pdo->prepare($photoSql);
                        $photoStmt->execute([$plantId, $uploadPath, $fileName, filesize($uploadPath)]);
                    }
                }
            }
        }
    }
    
    header('Location: all_plants.php?success=' . urlencode('Plant destroyed and recorded for compliance'));
    exit;
    
} catch (Exception $e) {
    header('Location: destroy_plant.php?id=' . urlencode($_POST['plantId'] ?? '') . '&error=' . urlencode($e->getMessage()));
    exit;
}
?>