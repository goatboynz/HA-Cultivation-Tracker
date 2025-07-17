<?php require_once 'auth.php'; ?>
<?php
require_once 'init_db.php';

try {
    $pdo = initializeDatabase();
    
    $plantId = $_POST['plantId'] ?? '';
    $plantTag = $_POST['plantTag'] ?? '';
    $geneticsId = $_POST['geneticsName'] ?? '';
    $growthStage = $_POST['growthStage'] ?? '';
    $roomId = $_POST['roomName'] ?? '';
    $status = $_POST['status'] ?? '';
    $isMother = isset($_POST['isMother']) ? 1 : 0;
    $motherId = $_POST['motherId'] ?? null;
    $seedStockId = $_POST['seedStockId'] ?? null;
    $sourceType = $_POST['sourceType'] ?? '';
    $notes = $_POST['notes'] ?? '';
    
    // Date fields
    $dateCreated = $_POST['dateCreated'] ?? null;
    $dateStageChanged = $_POST['dateStageChanged'] ?? null;
    $dateHarvested = $_POST['dateHarvested'] ?? null;
    
    // Harvest data
    $wetWeight = $_POST['wetWeight'] ?? null;
    $dryWeight = $_POST['dryWeight'] ?? null;
    $flowerWeight = $_POST['flowerWeight'] ?? null;
    $trimWeight = $_POST['trimWeight'] ?? null;
    
    // Destruction data
    $destructionReason = $_POST['destructionReason'] ?? null;
    $destructionNotes = $_POST['destructionNotes'] ?? null;
    $destructionDate = $_POST['destructionDate'] ?? null;
    
    if (!$plantId || !$geneticsId || !$growthStage || !$roomId || !$status) {
        throw new Exception('All required fields must be filled');
    }
    
    // Convert empty strings to null
    if ($motherId === '') $motherId = null;
    if ($seedStockId === '') $seedStockId = null;
    if ($sourceType === '') $sourceType = null;
    if ($dateCreated === '') $dateCreated = null;
    if ($dateStageChanged === '') $dateStageChanged = null;
    if ($dateHarvested === '') $dateHarvested = null;
    if ($wetWeight === '') $wetWeight = null;
    if ($dryWeight === '') $dryWeight = null;
    if ($flowerWeight === '') $flowerWeight = null;
    if ($trimWeight === '') $trimWeight = null;
    if ($destructionReason === '') $destructionReason = null;
    if ($destructionNotes === '') $destructionNotes = null;
    if ($destructionDate === '') $destructionDate = null;
    
    // Validate destruction data if status is destroyed
    if ($status === 'Destroyed' && !$destructionReason) {
        throw new Exception('Destruction reason is required when marking plant as destroyed');
    }
    
    $sql = "UPDATE Plants SET 
            plant_tag = ?, 
            genetics_id = ?, 
            growth_stage = ?, 
            room_id = ?, 
            status = ?, 
            is_mother = ?, 
            mother_id = ?, 
            seed_stock_id = ?,
            source_type = ?,
            notes = ?,
            date_created = COALESCE(?, date_created),
            date_stage_changed = COALESCE(?, date_stage_changed),
            date_harvested = ?,
            wet_weight = ?,
            dry_weight = ?,
            flower_weight = ?,
            trim_weight = ?,
            destruction_reason = ?,
            destruction_notes = ?,
            destruction_date = ?
            WHERE id = ?";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $plantTag, 
        $geneticsId, 
        $growthStage, 
        $roomId, 
        $status, 
        $isMother, 
        $motherId, 
        $seedStockId,
        $sourceType,
        $notes,
        $dateCreated,
        $dateStageChanged,
        $dateHarvested,
        $wetWeight,
        $dryWeight,
        $flowerWeight,
        $trimWeight,
        $destructionReason,
        $destructionNotes,
        $destructionDate,
        $plantId
    ]);
    
    // Handle photo uploads
    $uploadDir = 'uploads/plants/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    // Handle captured photos
    if (isset($_FILES['captured_photos'])) {
        foreach ($_FILES['captured_photos']['tmp_name'] as $key => $tmpName) {
            if ($_FILES['captured_photos']['error'][$key] === UPLOAD_ERR_OK) {
                $fileName = uniqid('plant_' . $plantId . '_') . '.jpg';
                $uploadPath = $uploadDir . $fileName;
                
                if (move_uploaded_file($tmpName, $uploadPath)) {
                    $photoSql = "INSERT INTO PlantPhotos (plant_id, file_path, file_name, file_size, photo_type) VALUES (?, ?, ?, ?, 'general')";
                    $photoStmt = $pdo->prepare($photoSql);
                    $photoStmt->execute([$plantId, $uploadPath, $fileName, filesize($uploadPath)]);
                }
            }
        }
    }
    
    // Handle uploaded photos
    if (isset($_FILES['uploaded_photos'])) {
        foreach ($_FILES['uploaded_photos']['tmp_name'] as $key => $tmpName) {
            if ($_FILES['uploaded_photos']['error'][$key] === UPLOAD_ERR_OK) {
                $originalName = $_FILES['uploaded_photos']['name'][$key];
                $fileExtension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                
                if (in_array($fileExtension, $allowedExtensions)) {
                    $fileName = uniqid('plant_' . $plantId . '_') . '.' . $fileExtension;
                    $uploadPath = $uploadDir . $fileName;
                    
                    if (move_uploaded_file($tmpName, $uploadPath)) {
                        $photoSql = "INSERT INTO PlantPhotos (plant_id, file_path, file_name, file_size, photo_type) VALUES (?, ?, ?, ?, 'general')";
                        $photoStmt = $pdo->prepare($photoSql);
                        $photoStmt->execute([$plantId, $uploadPath, $fileName, filesize($uploadPath)]);
                    }
                }
            }
        }
    }
    
    header('Location: all_plants.php?success=' . urlencode('Plant updated successfully'));
    exit;
    
} catch (Exception $e) {
    header('Location: edit_plant.php?id=' . urlencode($_POST['plantId'] ?? '') . '&error=' . urlencode($e->getMessage()));
    exit;
}
?>