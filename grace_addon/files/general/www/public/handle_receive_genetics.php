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
    $motherIds = $_POST['motherIds'] ?? [];
    $motherCounts = $_POST['motherCounts'] ?? [];
    $seedStockId = $_POST['seedStockId'] ?? null;
    $growthStage = $_POST['growthStage'] ?? 'Clone';
    $notes = $_POST['notes'] ?? '';
    $dateAdded = $_POST['dateAdded'] ?? date('Y-m-d H:i:s');

    // Basic input validation
    if (empty($plantCount) || empty($geneticsId) || empty($roomId) || empty($sourceType) || empty($dateAdded)) {
        $data = urlencode(json_encode($_POST));
        header("Location: receive_genetics.php?error=" . urlencode("All required fields must be filled") . "&data=$data");
        exit();
    }
    
    // Validate source-specific requirements
    if ($sourceType === 'mother') {
        // Multi-mother validation
        if (empty($motherIds) || empty($motherCounts)) {
            $data = urlencode(json_encode($_POST));
            header("Location: receive_genetics.php?error=" . urlencode("Mother plants and clone counts must be specified") . "&data=$data");
            exit();
        }
        
        // Validate that counts add up to total plant count
        $totalAssigned = array_sum($motherCounts);
        if ($totalAssigned != $plantCount) {
            $data = urlencode(json_encode($_POST));
            header("Location: receive_genetics.php?error=" . urlencode("Clone distribution ($totalAssigned) must equal total plant count ($plantCount)") . "&data=$data");
            exit();
        }
    } elseif ($sourceType === 'clone' && empty($motherId)) {
        $data = urlencode(json_encode($_POST));
        header("Location: receive_genetics.php?error=" . urlencode("Mother plant must be selected for clone source type") . "&data=$data");
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
    
    // Convert date to proper format
    $dateCreated = date('Y-m-d H:i:s', strtotime($dateAdded));

    try {
        $trackingNumbers = [];
        
        // Prepare the SQL statement outside the loop for efficiency
        $stmt = $pdo->prepare("INSERT INTO Plants (tracking_number, genetics_id, room_id, growth_stage, status, source_type, mother_id, seed_stock_id, notes, date_created, date_stage_changed)
                                VALUES (:trackingNumber, :geneticsId, :roomId, :growthStage, 'Growing', :sourceType, :motherId, :seedStockId, :notes, :dateCreated, :dateCreated)");

        // If using seed stock, update the used count
        if ($sourceType === 'seed' && $seedStockId) {
            $updateSeedStmt = $pdo->prepare("UPDATE SeedStock SET used_count = COALESCE(used_count, 0) + ? WHERE id = ?");
            $updateSeedStmt->execute([$plantCount, $seedStockId]);
        }

        if ($sourceType === 'mother') {
            // Multi-mother handling
            $motherDistribution = array_combine($motherIds, $motherCounts);
            $trackingNumbers = generateMultiMotherTrackingNumbers($pdo, $motherDistribution);
            
            $index = 0;
            foreach ($motherDistribution as $currentMotherId => $count) {
                for ($i = 0; $i < $count; $i++) {
                    $stmt->execute([
                        ':trackingNumber' => $trackingNumbers[$index],
                        ':geneticsId' => $geneticsId,
                        ':roomId' => $roomId,
                        ':growthStage' => $growthStage,
                        ':sourceType' => $sourceType,
                        ':motherId' => $currentMotherId,
                        ':seedStockId' => null,
                        ':notes' => $notes,
                        ':dateCreated' => $dateCreated
                    ]);
                    $index++;
                }
            }
        } elseif ($sourceType === 'clone') {
            // Single mother handling
            $trackingNumbers = generateBatchTrackingNumbers($pdo, $plantCount, $motherId);
            
            for ($i = 0; $i < $plantCount; $i++) {
                $stmt->execute([
                    ':trackingNumber' => $trackingNumbers[$i],
                    ':geneticsId' => $geneticsId,
                    ':roomId' => $roomId,
                    ':growthStage' => $growthStage,
                    ':sourceType' => $sourceType,
                    ':motherId' => $motherId,
                    ':seedStockId' => null,
                    ':notes' => $notes,
                    ':dateCreated' => $dateCreated
                ]);
            }
        } else {
            // Standard tracking numbers for seeds and purchased plants
            $trackingNumbers = generateBatchTrackingNumbers($pdo, $plantCount);
            
            for ($i = 0; $i < $plantCount; $i++) {
                $stmt->execute([
                    ':trackingNumber' => $trackingNumbers[$i],
                    ':geneticsId' => $geneticsId,
                    ':roomId' => $roomId,
                    ':growthStage' => $growthStage,
                    ':sourceType' => $sourceType,
                    ':motherId' => null,
                    ':seedStockId' => $seedStockId,
                    ':notes' => $notes,
                    ':dateCreated' => $dateCreated
                ]);
            }
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
