<?php
require_once 'auth.php';
require_once 'init_db.php';

header('Content-Type: application/json');

try {
    $pdo = initializeDatabase();
    
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        throw new Exception('Invalid request method');
    }

    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if (!isset($data['selectedPlants']) || !is_array($data['selectedPlants']) || !isset($data['action'])) {
        throw new Exception('Invalid or missing data');
    }

    $selectedPlantIds = $data['selectedPlants'];
    $action = $data['action'];
    $companyId = $data['companyId'] ?? null;
    
    // Additional data for batch operations
    $batchName = $data['batchName'] ?? null;
    $reason = $data['reason'] ?? null;
    $method = $data['method'] ?? null;
    $witnessName = $data['witnessName'] ?? null;
    $notes = $data['notes'] ?? null;
    $weights = $data['weights'] ?? [];

    if (empty($selectedPlantIds)) {
        throw new Exception('No plants selected.');
    }

    // Ensure batch tables exist
    $createBatchTablesSQL = [
        "CREATE TABLE IF NOT EXISTS BatchHarvests (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            batch_name TEXT NOT NULL,
            harvest_date DATETIME NOT NULL,
            total_plants INTEGER NOT NULL,
            total_wet_weight DECIMAL(10, 2),
            total_dry_weight DECIMAL(10, 2),
            total_flower_weight DECIMAL(10, 2),
            total_trim_weight DECIMAL(10, 2),
            notes TEXT,
            created_by TEXT,
            created_date DATETIME DEFAULT CURRENT_TIMESTAMP
        );",
        
        "CREATE TABLE IF NOT EXISTS BatchDestructions (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            batch_name TEXT NOT NULL,
            destruction_date DATETIME NOT NULL,
            reason TEXT NOT NULL,
            method TEXT,
            total_plants INTEGER NOT NULL,
            total_weight DECIMAL(10, 2),
            witness_name TEXT,
            compliance_notes TEXT,
            notes TEXT,
            created_by TEXT,
            created_date DATETIME DEFAULT CURRENT_TIMESTAMP
        );",
        
        "CREATE TABLE IF NOT EXISTS BatchPlantDetails (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            batch_id INTEGER NOT NULL,
            batch_type TEXT CHECK(batch_type IN ('harvest', 'destruction')) NOT NULL,
            plant_id INTEGER NOT NULL,
            tracking_number TEXT NOT NULL,
            plant_tag TEXT,
            genetics_name TEXT,
            growth_stage TEXT,
            room_name TEXT,
            age_days INTEGER,
            individual_weight DECIMAL(10, 2),
            individual_notes TEXT,
            FOREIGN KEY (plant_id) REFERENCES Plants(id) ON DELETE CASCADE ON UPDATE CASCADE
        );"
    ];
    
    foreach ($createBatchTablesSQL as $sql) {
        $pdo->exec($sql);
    }

    $pdo->beginTransaction();

    // Get plant details before processing
    $placeholders = implode(',', array_fill(0, count($selectedPlantIds), '?'));
    $plantDetailsSQL = "SELECT 
                            P.id, P.tracking_number, P.plant_tag, P.growth_stage,
                            G.name as genetics_name, R.name as room_name,
                            CAST((julianday('now') - julianday(P.date_created)) AS INTEGER) AS age_days
                        FROM Plants P
                        LEFT JOIN Genetics G ON P.genetics_id = G.id
                        LEFT JOIN Rooms R ON P.room_id = R.id
                        WHERE P.id IN ($placeholders)";
    
    $stmt = $pdo->prepare($plantDetailsSQL);
    $stmt->execute($selectedPlantIds);
    $plantDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($action === 'destroy') {
        // Handle batch destruction
        if (!$reason) {
            throw new Exception('Destruction reason is required');
        }

        // Generate batch name if not provided
        if (!$batchName) {
            $batchName = 'Destruction_' . date('Y-m-d_H-i-s');
        }

        // Calculate total weight
        $totalWeight = 0;
        foreach ($weights as $plantId => $weight) {
            $totalWeight += floatval($weight);
        }

        // Create batch destruction record
        $batchSQL = "INSERT INTO BatchDestructions 
                     (batch_name, destruction_date, reason, method, total_plants, total_weight, witness_name, notes, created_by) 
                     VALUES (?, DATETIME('now'), ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $pdo->prepare($batchSQL);
        $stmt->execute([
            $batchName,
            $reason,
            $method,
            count($selectedPlantIds),
            $totalWeight,
            $witnessName,
            $notes,
            'system' // You might want to track actual user
        ]);
        
        $batchId = $pdo->lastInsertId();

        // Update plants status and add destruction details
        foreach ($plantDetails as $plant) {
            // Update plant status
            $updateSQL = "UPDATE Plants SET 
                         status = 'Destroyed', 
                         destruction_date = DATETIME('now'),
                         destruction_reason = ?,
                         destruction_notes = ?
                         WHERE id = ?";
            
            $stmt = $pdo->prepare($updateSQL);
            $stmt->execute([$reason, $notes, $plant['id']]);

            // Add to batch plant details
            $detailSQL = "INSERT INTO BatchPlantDetails 
                         (batch_id, batch_type, plant_id, tracking_number, plant_tag, genetics_name, growth_stage, room_name, age_days, individual_weight) 
                         VALUES (?, 'destruction', ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $pdo->prepare($detailSQL);
            $stmt->execute([
                $batchId,
                $plant['id'],
                $plant['tracking_number'],
                $plant['plant_tag'],
                $plant['genetics_name'],
                $plant['growth_stage'],
                $plant['room_name'],
                $plant['age_days'],
                $weights[$plant['id']] ?? 0
            ]);

            // Create individual destruction record
            $destructionSQL = "INSERT INTO DestructionRecords 
                              (plant_id, destruction_date, reason, method, total_weight, witness_name, notes, created_by) 
                              VALUES (?, DATETIME('now'), ?, ?, ?, ?, ?, ?)";
            
            $stmt = $pdo->prepare($destructionSQL);
            $stmt->execute([
                $plant['id'],
                $reason,
                $method,
                $weights[$plant['id']] ?? 0,
                $witnessName,
                $notes,
                'system'
            ]);
        }

        $message = "Successfully destroyed " . count($selectedPlantIds) . " plants in batch: " . $batchName;

    } elseif ($action === 'harvest') {
        // Handle batch harvest
        if (!$batchName) {
            $batchName = 'Harvest_' . date('Y-m-d_H-i-s');
        }

        // Calculate total weights
        $totalWetWeight = 0;
        $totalDryWeight = 0;
        $totalFlowerWeight = 0;
        $totalTrimWeight = 0;

        foreach ($weights as $plantId => $plantWeights) {
            $totalWetWeight += floatval($plantWeights['wet'] ?? 0);
            $totalDryWeight += floatval($plantWeights['dry'] ?? 0);
            $totalFlowerWeight += floatval($plantWeights['flower'] ?? 0);
            $totalTrimWeight += floatval($plantWeights['trim'] ?? 0);
        }

        // Create batch harvest record
        $batchSQL = "INSERT INTO BatchHarvests 
                     (batch_name, harvest_date, total_plants, total_wet_weight, total_dry_weight, total_flower_weight, total_trim_weight, notes, created_by) 
                     VALUES (?, DATETIME('now'), ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $pdo->prepare($batchSQL);
        $stmt->execute([
            $batchName,
            count($selectedPlantIds),
            $totalWetWeight,
            $totalDryWeight,
            $totalFlowerWeight,
            $totalTrimWeight,
            $notes,
            'system'
        ]);
        
        $batchId = $pdo->lastInsertId();

        // Update plants and add harvest details
        foreach ($plantDetails as $plant) {
            $plantWeights = $weights[$plant['id']] ?? [];
            
            // Update plant status and weights
            $updateSQL = "UPDATE Plants SET 
                         status = 'Harvested', 
                         date_harvested = DATETIME('now'),
                         wet_weight = ?,
                         dry_weight = ?,
                         flower_weight = ?,
                         trim_weight = ?
                         WHERE id = ?";
            
            $stmt = $pdo->prepare($updateSQL);
            $stmt->execute([
                $plantWeights['wet'] ?? 0,
                $plantWeights['dry'] ?? 0,
                $plantWeights['flower'] ?? 0,
                $plantWeights['trim'] ?? 0,
                $plant['id']
            ]);

            // Add to batch plant details
            $totalIndividualWeight = ($plantWeights['wet'] ?? 0) + ($plantWeights['dry'] ?? 0) + 
                                   ($plantWeights['flower'] ?? 0) + ($plantWeights['trim'] ?? 0);
            
            $detailSQL = "INSERT INTO BatchPlantDetails 
                         (batch_id, batch_type, plant_id, tracking_number, plant_tag, genetics_name, growth_stage, room_name, age_days, individual_weight) 
                         VALUES (?, 'harvest', ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $pdo->prepare($detailSQL);
            $stmt->execute([
                $batchId,
                $plant['id'],
                $plant['tracking_number'],
                $plant['plant_tag'],
                $plant['genetics_name'],
                $plant['growth_stage'],
                $plant['room_name'],
                $plant['age_days'],
                $totalIndividualWeight
            ]);

            // Create individual harvest record
            $harvestSQL = "INSERT INTO HarvestRecords 
                          (plant_id, harvest_date, wet_weight, dry_weight, flower_weight, trim_weight, notes) 
                          VALUES (?, DATETIME('now'), ?, ?, ?, ?, ?)";
            
            $stmt = $pdo->prepare($harvestSQL);
            $stmt->execute([
                $plant['id'],
                $plantWeights['wet'] ?? 0,
                $plantWeights['dry'] ?? 0,
                $plantWeights['flower'] ?? 0,
                $plantWeights['trim'] ?? 0,
                $notes
            ]);
        }

        $message = "Successfully harvested " . count($selectedPlantIds) . " plants in batch: " . $batchName;

    } elseif ($action === 'send') {
        // Handle sending plants
        if (!$companyId) {
            throw new Exception('Company selection is required for sending plants');
        }

        $updateSQL = "UPDATE Plants SET status = 'Sent', company_id = ?, date_harvested = DATETIME('now') WHERE id IN ($placeholders)";
        $stmt = $pdo->prepare($updateSQL);
        $stmt->execute(array_merge([$companyId], $selectedPlantIds));

        $message = "Successfully sent " . count($selectedPlantIds) . " plants";
    } else {
        throw new Exception('Invalid action specified');
    }

    $pdo->commit();
    
    echo json_encode([
        'success' => true, 
        'message' => $message,
        'batch_id' => $batchId ?? null
    ]);

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log('Error in handle_batch_operations.php: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>