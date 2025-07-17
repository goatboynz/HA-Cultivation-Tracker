<?php require_once 'auth.php'; ?>
<?php
require_once 'init_db.php';

try {
    $pdo = initializeDatabase();
    
    $plantId = $_POST['plantId'] ?? '';
    $harvestDate = $_POST['harvestDate'] ?? '';
    $dryRoom = $_POST['dryRoom'] ?? '';
    $wetWeight = $_POST['wetWeight'] ?? null;
    $dryWeight = $_POST['dryWeight'] ?? null;
    $flowerWeight = $_POST['flowerWeight'] ?? null;
    $trimWeight = $_POST['trimWeight'] ?? null;
    $quality = $_POST['quality'] ?? null;
    $trichomeColor = $_POST['trichomeColor'] ?? null;
    $aroma = $_POST['aroma'] ?? null;
    $density = $_POST['density'] ?? null;
    $harvestNotes = $_POST['harvestNotes'] ?? '';
    
    if (!$plantId || !$harvestDate || !$dryRoom || !$wetWeight) {
        throw new Exception('Plant ID, harvest date, dry room, and wet weight are required');
    }
    
    // Convert empty strings to null for numeric fields
    if ($dryWeight === '') $dryWeight = null;
    if ($flowerWeight === '') $flowerWeight = null;
    if ($trimWeight === '') $trimWeight = null;
    if ($quality === '') $quality = null;
    if ($trichomeColor === '') $trichomeColor = null;
    if ($aroma === '') $aroma = null;
    if ($density === '') $density = null;
    
    // Update plant status and move to dry room
    $sql = "UPDATE Plants SET 
            status = 'Harvested',
            room_id = ?,
            date_harvested = ?,
            wet_weight = ?,
            dry_weight = ?,
            flower_weight = ?,
            trim_weight = ?,
            notes = CASE 
                WHEN notes IS NULL OR notes = '' THEN ?
                ELSE notes || '\n\n--- HARVEST ---\n' || ?
            END
            WHERE id = ?";
    
    $harvestNotesFormatted = "HARVEST DATE: " . $harvestDate . "\n";
    if ($quality) $harvestNotesFormatted .= "QUALITY: " . $quality . "\n";
    if ($trichomeColor) $harvestNotesFormatted .= "TRICHOMES: " . $trichomeColor . "\n";
    if ($aroma) $harvestNotesFormatted .= "AROMA: " . $aroma . "\n";
    if ($density) $harvestNotesFormatted .= "DENSITY: " . $density . "\n";
    if ($harvestNotes) $harvestNotesFormatted .= "NOTES: " . $harvestNotes;
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $dryRoom,
        $harvestDate,
        $wetWeight,
        $dryWeight,
        $flowerWeight,
        $trimWeight,
        $harvestNotesFormatted,
        $harvestNotesFormatted,
        $plantId
    ]);
    
    // Handle photo uploads
    $uploadDir = 'uploads/plants/harvest/';
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
                    $fileName = uniqid('harvest_' . $plantId . '_') . '.' . $fileExtension;
                    $uploadPath = $uploadDir . $fileName;
                    
                    if (move_uploaded_file($tmpName, $uploadPath)) {
                        $photoSql = "INSERT INTO PlantPhotos (plant_id, file_path, file_name, file_size, photo_type, description) VALUES (?, ?, ?, ?, 'harvest', 'Harvest photo')";
                        $photoStmt = $pdo->prepare($photoSql);
                        $photoStmt->execute([$plantId, $uploadPath, $fileName, filesize($uploadPath)]);
                    }
                }
            }
        }
    }
    
    // Create harvest record for tracking
    $harvestRecordSql = "INSERT INTO HarvestRecords (plant_id, harvest_date, wet_weight, dry_weight, flower_weight, trim_weight, quality, trichome_color, aroma, density, notes) 
                         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $harvestStmt = $pdo->prepare($harvestRecordSql);
    $harvestStmt->execute([
        $plantId,
        $harvestDate,
        $wetWeight,
        $dryWeight,
        $flowerWeight,
        $trimWeight,
        $quality,
        $trichomeColor,
        $aroma,
        $density,
        $harvestNotes
    ]);
    
    header('Location: plants_flower.php?success=' . urlencode('Plant harvested successfully and moved to dry room'));
    exit;
    
} catch (Exception $e) {
    header('Location: harvest_plant.php?id=' . urlencode($_POST['plantId'] ?? '') . '&error=' . urlencode($e->getMessage()));
    exit;
}
?>