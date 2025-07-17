<?php require_once 'auth.php'; ?>
<?php
require_once 'init_db.php';
require_once 'generate_tracking_number.php'; // Include your database initialization script

// Get the PDO instance from the initializeDatabase function
$pdo = initializeDatabase();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get data from the form
    $plantCount = $_POST['plantCount'];
    $geneticsId = $_POST['geneticsName'];
    $roomId = $_POST['roomName'];
    $sourceType = $_POST['sourceType'] ?? '';
    $motherId = $_POST['motherId'] ?? null;
    $seedStockId = $_POST['seedStockId'] ?? null;
    $growthStage = $_POST['growthStage'] ?? 'Clone';
    $notes = $_POST['notes'] ?? '';

    // Basic input validation
    if (empty($plantCount) || empty($geneticsId) || empty($roomId) || empty($sourceType)) {
        $data = urlencode(json_encode($_POST));
        header("Location: receive_genetics.php?error=" . urlencode("All required fields must be filled") . "&data=$data");
        exit();
    }
    
    // Validate source-specific requirements
    if (($sourceType === 'mother' || $sourceType === 'clone') && empty($motherId)) {
        $data = urlencode(json_encode($_POST));
        header("Location: receive_genetics.php?error=" . urlencode("Mother plant must be selected for this source type") . "&data=$data");
        exit();
    }
    
    if ($sourceType === 'seed' && empty($seedStockId)) {
        $data = urlencode(json_encode($_POST));
        header("Location: receive_genetics.php?error=" . urlencode("Seed stock must be selected for this source type") . "&data=$data");
        exit();
    }
    
    // Convert empty strings to null
    if ($motherId === '') $motherId = null;
    if ($seedStockId === '') $seedStockId = null;

    try {
        // Generate tracking numbers for all plants
        $trackingNumbers = generateBatchTrackingNumbers($pdo, $plantCount);
        
        // Prepare the SQL statement outside the loop for efficiency
        $stmt = $pdo->prepare("INSERT INTO Plants (tracking_number, genetics_id, room_id, growth_stage, status, source_type, mother_id, seed_stock_id, notes, date_created, date_stage_changed)
                                VALUES (:trackingNumber, :geneticsId, :roomId, :growthStage, 'Growing', :sourceType, :motherId, :seedStockId, :notes, DATETIME('now'), DATETIME('now'))");

        // If using seed stock, update the used count
        if ($sourceType === 'seed' && $seedStockId) {
            $updateSeedStmt = $pdo->prepare("UPDATE SeedStock SET used_count = COALESCE(used_count, 0) + ? WHERE id = ?");
            $updateSeedStmt->execute([$plantCount, $seedStockId]);
        }

        // Insert into Plants table directly with tracking numbers
        for ($i = 0; $i < $plantCount; $i++) {
            $stmt->execute([
                ':trackingNumber' => $trackingNumbers[$i],
                ':geneticsId' => $geneticsId,
                ':roomId' => $roomId,
                ':growthStage' => $growthStage,
                ':sourceType' => $sourceType,
                ':motherId' => $motherId,
                ':seedStockId' => $seedStockId,
                ':notes' => $notes
            ]);
        }

        // Redirect with success message and tracking numbers
        $trackingNumbersParam = urlencode(json_encode($trackingNumbers));
        header("Location: receive_genetics.php?success=" . urlencode("Successfully added {$plantCount} plants") . "&tracking_numbers=" . $trackingNumbersParam);
        exit();
    } catch (PDOException $e) {
        // Handle Plants insertion error
        $data = urlencode(json_encode($_POST));
        $error = urlencode("Error inserting plants: " . $e->getMessage());
        header("Location: receive_genetics.php?error=$error&data=$data");
        exit();
    }
}
?>
