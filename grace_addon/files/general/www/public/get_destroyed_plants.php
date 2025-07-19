<?php
require_once 'auth.php';
require_once 'init_db.php';

header('Content-Type: application/json');

try {
    $pdo = initializeDatabase();
    
    // First, ensure batch tables exist
    $createBatchTablesSQL = [
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
    
    // Get individual destroyed plants
    $plantsSQL = "SELECT 
                    P.id,
                    P.tracking_number,
                    P.plant_tag,
                    P.destruction_date,
                    P.destruction_reason,
                    P.destruction_notes,
                    P.growth_stage,
                    P.wet_weight + P.dry_weight + P.flower_weight + P.trim_weight as total_weight,
                    G.name as genetics_name,
                    R.name as room_name,
                    DR.witness_name,
                    DR.method,
                    DR.total_weight as destruction_weight,
                    'individual' as operation_type,
                    NULL as batch_id
                FROM Plants P
                LEFT JOIN Genetics G ON P.genetics_id = G.id
                LEFT JOIN Rooms R ON P.room_id = R.id
                LEFT JOIN DestructionRecords DR ON P.id = DR.plant_id
                WHERE P.status = 'Destroyed'
                ORDER BY P.destruction_date DESC";
    
    $stmt = $pdo->query($plantsSQL);
    $plants = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get batch destruction operations
    $batchSQL = "SELECT 
                    BD.*,
                    COUNT(BPD.plant_id) as plant_count
                FROM BatchDestructions BD
                LEFT JOIN BatchPlantDetails BPD ON BD.id = BPD.batch_id AND BPD.batch_type = 'destruction'
                GROUP BY BD.id
                ORDER BY BD.destruction_date DESC";
    
    $stmt = $pdo->query($batchSQL);
    $batches = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get plants from batch operations
    $batchPlantsSQL = "SELECT 
                        BPD.*,
                        BD.destruction_date,
                        BD.reason as destruction_reason,
                        BD.witness_name,
                        BD.method,
                        'batch' as operation_type,
                        BD.id as batch_id
                    FROM BatchPlantDetails BPD
                    JOIN BatchDestructions BD ON BPD.batch_id = BD.id
                    WHERE BPD.batch_type = 'destruction'
                    ORDER BY BD.destruction_date DESC";
    
    $stmt = $pdo->query($batchPlantsSQL);
    $batchPlants = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Combine individual and batch plants
    $allPlants = array_merge($plants, $batchPlants);
    
    echo json_encode([
        'success' => true,
        'plants' => $allPlants,
        'batches' => $batches
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'plants' => [],
        'batches' => []
    ]);
}
?>