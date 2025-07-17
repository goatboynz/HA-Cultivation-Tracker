<?php require_once 'auth.php'; ?>
<?php
require_once 'init_db.php';

try {
    $pdo = initializeDatabase();
    
    $batchName = $_POST['batchName'] ?? '';
    $geneticsId = $_POST['geneticsId'] ?? '';
    $supplier = $_POST['supplier'] ?? '';
    $seedCount = $_POST['seedCount'] ?? null;
    $purchaseDate = $_POST['purchaseDate'] ?? null;
    $expiryDate = $_POST['expiryDate'] ?? null;
    $storageLocation = $_POST['storageLocation'] ?? '';
    $germinationRate = $_POST['germinationRate'] ?? null;
    $price = $_POST['price'] ?? null;
    $notes = $_POST['notes'] ?? '';
    
    if (!$batchName || !$geneticsId) {
        throw new Exception('Batch name and genetics are required');
    }
    
    // Handle photo upload
    $photoPath = null;
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/seed_stock/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $fileExtension = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (in_array($fileExtension, $allowedExtensions)) {
            $fileName = uniqid('seed_') . '.' . $fileExtension;
            $uploadPath = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadPath)) {
                $photoPath = $uploadPath;
            }
        }
    }
    
    // Convert empty strings to null for numeric fields
    if ($seedCount === '') $seedCount = null;
    if ($purchaseDate === '') $purchaseDate = null;
    if ($expiryDate === '') $expiryDate = null;
    if ($germinationRate === '') $germinationRate = null;
    if ($price === '') $price = null;
    
    $sql = "INSERT INTO SeedStock (batch_name, genetics_id, supplier, seed_count, purchase_date, expiry_date, storage_location, germination_rate, price, photo_path, notes) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $batchName, 
        $geneticsId, 
        $supplier, 
        $seedCount, 
        $purchaseDate, 
        $expiryDate, 
        $storageLocation, 
        $germinationRate, 
        $price, 
        $photoPath, 
        $notes
    ]);
    
    header('Location: seed_stock.php?success=' . urlencode('Seed stock added successfully'));
    exit;
    
} catch (Exception $e) {
    header('Location: seed_stock.php?error=' . urlencode($e->getMessage()));
    exit;
}
?>