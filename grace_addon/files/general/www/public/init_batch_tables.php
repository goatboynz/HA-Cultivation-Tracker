<?php
// Simple script to initialize batch tables
require_once 'init_db.php';

try {
    $pdo = initializeDatabase();
    
    // Create batch operations tables
    $createBatchTablesSQL = [
        // Batch Harvest Operations
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

        // Batch Destruction Operations
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

        // Batch Plant Details (preserves individual plant info)
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

    // Execute each CREATE TABLE statement
    foreach ($createBatchTablesSQL as $sql) {
        $pdo->exec($sql);
    }

    echo "Batch tables created successfully!\n";
    
} catch (Exception $e) {
    echo "Error creating batch tables: " . $e->getMessage() . "\n";
}
?>