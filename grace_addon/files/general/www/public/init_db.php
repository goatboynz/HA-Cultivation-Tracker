<?php

date_default_timezone_set('Pacific/Auckland'); 


function initializeDatabase($dbPath = '/data/grace.db') {
    try {
        // Check if the directory exists
        $dir = dirname($dbPath);
        if (!is_dir($dir)) {
            throw new Exception("Directory does not exist: $dir");
        }

        // Check if the directory is writable
        if (!is_writable($dir)) {
            throw new Exception("Directory is not writable: $dir");
        }

        // Create (or open) the SQLite database
        $pdo = new PDO('sqlite:' . $dbPath);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Enable foreign key constraints
        $pdo->exec('PRAGMA foreign_keys = ON;');

        // SQL statements to create tables
        $createTablesSQL = [
            // Companies
            "CREATE TABLE IF NOT EXISTS Companies (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                license_number TEXT NOT NULL,
                address TEXT,
                primary_contact_name TEXT,
                primary_contact_email TEXT,
                primary_contact_phone TEXT
            );",

            // Rooms
            "CREATE TABLE IF NOT EXISTS Rooms (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                room_type TEXT CHECK(room_type IN ('Clone', 'Veg', 'Flower', 'Mother', 'Dry', 'Storage')) NOT NULL,
                description TEXT
            );",

            // Genetics
            "CREATE TABLE IF NOT EXISTS Genetics (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                breeder TEXT,
                genetic_lineage TEXT,
                flowering_days INTEGER,
                thc_percentage DECIMAL(5,2),
                cbd_percentage DECIMAL(5,2),
                indica_sativa_ratio TEXT,
                description TEXT,
                photo_url TEXT,
                created_date DATETIME DEFAULT CURRENT_TIMESTAMP
            );",

            // Plants
            "CREATE TABLE IF NOT EXISTS Plants (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                tracking_number TEXT UNIQUE NOT NULL,
                genetics_id INTEGER,
                status TEXT CHECK(status IN ('Growing', 'Harvested', 'Destroyed', 'Sent')),
                growth_stage TEXT CHECK(growth_stage IN ('Clone', 'Veg', 'Flower', 'Mother')) DEFAULT 'Clone',
                room_id INTEGER,
                date_created DATETIME,
                date_harvested DATETIME,
                date_stage_changed DATETIME DEFAULT CURRENT_TIMESTAMP,
                company_id INTEGER,
                plant_tag TEXT,
                notes TEXT,
                is_mother BOOLEAN DEFAULT 0,
                mother_id INTEGER,
                seed_stock_id INTEGER,
                source_type TEXT CHECK(source_type IN ('mother', 'seed', 'clone', 'purchased')),
                wet_weight DECIMAL(10, 2),
                dry_weight DECIMAL(10, 2),
                flower_weight DECIMAL(10, 2),
                trim_weight DECIMAL(10, 2),
                destruction_reason TEXT,
                destruction_notes TEXT,
                destruction_date DATETIME,
                FOREIGN KEY (genetics_id) REFERENCES Genetics(id) ON DELETE SET NULL ON UPDATE CASCADE,
                FOREIGN KEY (room_id) REFERENCES Rooms(id) ON DELETE SET NULL ON UPDATE CASCADE,
                FOREIGN KEY (company_id) REFERENCES Companies(id) ON DELETE SET NULL ON UPDATE CASCADE,
                FOREIGN KEY (mother_id) REFERENCES Plants(id) ON DELETE SET NULL ON UPDATE CASCADE,
                FOREIGN KEY (seed_stock_id) REFERENCES SeedStock(id) ON DELETE SET NULL ON UPDATE CASCADE
            );",

            // Flower
            "CREATE TABLE IF NOT EXISTS Flower (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                genetics_id INTEGER,
                weight DECIMAL(10, 2) NOT NULL,
                transaction_type TEXT CHECK(transaction_type IN ('Add', 'Subtract')) NOT NULL,
                transaction_date DATETIME DEFAULT CURRENT_TIMESTAMP,
                reason TEXT,
                company_id INTEGER,
                FOREIGN KEY (company_id) REFERENCES Companies(id) ON DELETE SET NULL ON UPDATE CASCADE,
                FOREIGN KEY (genetics_id) REFERENCES Genetics(id) ON DELETE SET NULL ON UPDATE CASCADE
            );",

            // ShippingManifests
            "CREATE TABLE IF NOT EXISTS ShippingManifests (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                sender_id INTEGER,
                sending_company_id INTEGER,
                recipient_id INTEGER,
                shipment_date DATETIME DEFAULT CURRENT_TIMESTAMP,
                product_type TEXT,
                item_count INTEGER,
                net_weight DECIMAL(10, 2),
                gross_weight DECIMAL(10, 2),
                manifest_file TEXT,
                FOREIGN KEY (sending_company_id) REFERENCES Companies(id) ON DELETE SET NULL ON UPDATE CASCADE,
                FOREIGN KEY (recipient_id) REFERENCES Companies(id) ON DELETE SET NULL ON UPDATE CASCADE
            );",

            // PoliceVettingRecords
            "CREATE TABLE IF NOT EXISTS PoliceVettingRecords (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                record_date DATE DEFAULT CURRENT_TIMESTAMP,
                file_path TEXT
            );",

            // SOPs
            "CREATE TABLE IF NOT EXISTS SOPs (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                upload_date DATE DEFAULT CURRENT_TIMESTAMP,
                file_path TEXT
            );",

            // OwnCompany
            "CREATE TABLE IF NOT EXISTS OwnCompany (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                company_name TEXT NOT NULL,
                company_license_number TEXT NOT NULL,
                company_address TEXT,
                primary_contact_email TEXT
            );",
            
            // Documents
            "CREATE TABLE IF NOT EXISTS Documents (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                category TEXT NOT NULL, 
                original_filename TEXT NOT NULL,
                unique_filename TEXT NOT NULL,
                upload_date DATETIME DEFAULT CURRENT_TIMESTAMP
            );",

            // SeedStock
            "CREATE TABLE IF NOT EXISTS SeedStock (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                batch_name TEXT NOT NULL,
                genetics_id INTEGER,
                supplier TEXT,
                seed_count INTEGER,
                used_count INTEGER DEFAULT 0,
                purchase_date DATE,
                expiry_date DATE,
                storage_location TEXT,
                germination_rate DECIMAL(5,2),
                price DECIMAL(10,2),
                photo_path TEXT,
                notes TEXT,
                created_date DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (genetics_id) REFERENCES Genetics(id) ON DELETE SET NULL ON UPDATE CASCADE
            );",

            // PlantPhotos
            "CREATE TABLE IF NOT EXISTS PlantPhotos (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                plant_id INTEGER NOT NULL,
                file_path TEXT NOT NULL,
                file_name TEXT NOT NULL,
                file_size INTEGER,
                upload_date DATETIME DEFAULT CURRENT_TIMESTAMP,
                photo_type TEXT CHECK(photo_type IN ('general', 'issue', 'harvest', 'progress')) DEFAULT 'general',
                description TEXT,
                FOREIGN KEY (plant_id) REFERENCES Plants(id) ON DELETE CASCADE ON UPDATE CASCADE
            );",

            // HarvestRecords
            "CREATE TABLE IF NOT EXISTS HarvestRecords (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                plant_id INTEGER NOT NULL,
                harvest_date DATETIME NOT NULL,
                wet_weight DECIMAL(10, 2),
                dry_weight DECIMAL(10, 2),
                flower_weight DECIMAL(10, 2),
                trim_weight DECIMAL(10, 2),
                quality TEXT,
                trichome_color TEXT,
                aroma TEXT,
                density TEXT,
                notes TEXT,
                created_date DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (plant_id) REFERENCES Plants(id) ON DELETE CASCADE ON UPDATE CASCADE
            );",

            // DestructionRecords
            "CREATE TABLE IF NOT EXISTS DestructionRecords (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                plant_id INTEGER NOT NULL,
                destruction_date DATETIME NOT NULL,
                reason TEXT NOT NULL,
                method TEXT,
                plant_weight DECIMAL(10, 2),
                root_weight DECIMAL(10, 2),
                soil_weight DECIMAL(10, 2),
                total_weight DECIMAL(10, 2),
                notes TEXT,
                witness_name TEXT,
                compliance_notes TEXT,
                created_by TEXT,
                created_date DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (plant_id) REFERENCES Plants(id) ON DELETE CASCADE ON UPDATE CASCADE
            );"

        ];

        // Execute each CREATE TABLE statement
        foreach ($createTablesSQL as $sql) {
            $pdo->exec($sql);
        }

        return $pdo;

    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    } catch (Exception $e) {
        die("Initialization error: " . $e->getMessage());
    }
}

?>
