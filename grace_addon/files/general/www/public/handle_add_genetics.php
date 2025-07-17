<?php require_once 'auth.php'; ?>
<?php
require_once 'init_db.php';

try {
    $pdo = initializeDatabase();
    
    $name = $_POST['name'] ?? '';
    $breeder = $_POST['breeder'] ?? '';
    $genetic_lineage = $_POST['genetic_lineage'] ?? '';
    $indica_sativa_ratio = $_POST['indica_sativa_ratio'] ?? '';
    $flowering_days = $_POST['flowering_days'] ?? null;
    $thc_percentage = $_POST['thc_percentage'] ?? null;
    $cbd_percentage = $_POST['cbd_percentage'] ?? null;
    $description = $_POST['description'] ?? '';
    
    if (!$name) {
        throw new Exception('Strain name is required');
    }
    
    // Handle photo upload
    $photo_url = null;
    if (isset($_FILES['photo_upload']) && $_FILES['photo_upload']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/genetics/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $fileExtension = strtolower(pathinfo($_FILES['photo_upload']['name'], PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (in_array($fileExtension, $allowedExtensions)) {
            $fileName = uniqid('genetics_') . '.' . $fileExtension;
            $uploadPath = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES['photo_upload']['tmp_name'], $uploadPath)) {
                $photo_url = $uploadPath;
            }
        }
    }
    
    // Convert empty strings to null for numeric fields
    if ($flowering_days === '') $flowering_days = null;
    if ($thc_percentage === '') $thc_percentage = null;
    if ($cbd_percentage === '') $cbd_percentage = null;
    
    $sql = "INSERT INTO Genetics (name, breeder, genetic_lineage, indica_sativa_ratio, flowering_days, thc_percentage, cbd_percentage, description, photo_url) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $name, 
        $breeder, 
        $genetic_lineage, 
        $indica_sativa_ratio, 
        $flowering_days, 
        $thc_percentage, 
        $cbd_percentage, 
        $description, 
        $photo_url
    ]);
    
    header('Location: manage_genetics.php?success=' . urlencode('Genetics added successfully'));
    exit;
    
} catch (Exception $e) {
    header('Location: manage_genetics.php?error=' . urlencode($e->getMessage()));
    exit;
}
?>