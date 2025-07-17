<?php
require_once 'auth.php';
require_once 'config.php';

header('Content-Type: application/json');

try {
    $pdo = new PDO('sqlite:' . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Start transaction
    $pdo->beginTransaction();
    
    $createdItems = [];
    
    // 1. Create dummy companies
    $companies = [
        ['Test Lab Co.', 'TL-2024-001', '123 Lab Street, Test City', 'Dr. Test', 'test@lab.com', '555-0001'],
        ['Green Buyer Ltd', 'GB-2024-002', '456 Buyer Ave, Purchase Town', 'Jane Buyer', 'jane@buyer.com', '555-0002'],
        ['Quality Testing Inc', 'QT-2024-003', '789 Quality Rd, Test Valley', 'Bob Quality', 'bob@quality.com', '555-0003']
    ];
    
    $companyIds = [];
    foreach ($companies as $company) {
        $stmt = $pdo->prepare("INSERT INTO Companies (name, license_number, address, primary_contact_name, primary_contact_email, primary_contact_phone) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute($company);
        $companyIds[] = $pdo->lastInsertId();
    }
    $createdItems['companies'] = count($companies);
    
    // 2. Create dummy rooms
    $rooms = [
        ['Test Clone Room', 'Clone', 'Dummy clone room for testing'],
        ['Test Veg Room A', 'Veg', 'Dummy vegetative room A'],
        ['Test Veg Room B', 'Veg', 'Dummy vegetative room B'],
        ['Test Flower Room 1', 'Flower', 'Dummy flowering room 1'],
        ['Test Flower Room 2', 'Flower', 'Dummy flowering room 2'],
        ['Test Mother Room', 'Mother', 'Dummy mother plant room'],
        ['Test Dry Room', 'Dry', 'Dummy drying room'],
        ['Test Storage', 'Storage', 'Dummy storage room']
    ];
    
    $roomIds = [];
    foreach ($rooms as $room) {
        $stmt = $pdo->prepare("INSERT INTO Rooms (name, room_type, description) VALUES (?, ?, ?)");
        $stmt->execute($room);
        $roomIds[] = $pdo->lastInsertId();
    }
    $createdItems['rooms'] = count($rooms);
    
    // 3. Create dummy genetics
    $genetics = [
        ['Test OG Kush', 'Test Breeder', 'OG Kush x Test', 65, 22.5, 0.8, '70% Indica / 30% Sativa', 'Classic test strain'],
        ['Dummy Purple Haze', 'Dummy Seeds', 'Purple Haze x Unknown', 70, 18.2, 1.2, '60% Sativa / 40% Indica', 'Purple test variety'],
        ['Sample White Widow', 'Sample Genetics', 'White Widow x Test Cross', 60, 20.1, 0.5, '50% Hybrid', 'Balanced test hybrid'],
        ['Mock Blue Dream', 'Mock Breeders', 'Blueberry x Haze', 75, 19.8, 1.8, '60% Sativa / 40% Indica', 'Sativa dominant test'],
        ['Test Gorilla Glue', 'Test Labs', 'Chem Sister x Sour Dubb', 63, 25.3, 0.3, '60% Indica / 40% Sativa', 'High THC test strain']
    ];
    
    $geneticsIds = [];
    foreach ($genetics as $genetic) {
        $stmt = $pdo->prepare("INSERT INTO Genetics (name, breeder, genetic_lineage, flowering_days, thc_percentage, cbd_percentage, indica_sativa_ratio, description) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute($genetic);
        $geneticsIds[] = $pdo->lastInsertId();
    }
    $createdItems['genetics'] = count($genetics);
    
    // 4. Create dummy seed stock
    $seedStocks = [];
    foreach ($geneticsIds as $i => $geneticsId) {
        $stmt = $pdo->prepare("INSERT INTO SeedStock (batch_name, genetics_id, supplier, seed_count, used_count, purchase_date, storage_location, germination_rate, price, notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $batchName = "TEST-BATCH-" . str_pad($i + 1, 3, '0', STR_PAD_LEFT);
        $stmt->execute([
            $batchName,
            $geneticsId,
            'Test Seed Supplier',
            rand(50, 200),
            rand(0, 20),
            date('Y-m-d', strtotime('-' . rand(30, 365) . ' days')),
            'Test Storage Location ' . ($i + 1),
            rand(75, 95),
            rand(50, 200),
            'Dummy seed stock for testing'
        ]);
        $seedStocks[] = $pdo->lastInsertId();
    }
    $createdItems['seed_stocks'] = count($seedStocks);
    
    // 5. Create dummy plants
    $plantCount = 0;
    $stages = ['Clone', 'Veg', 'Flower', 'Mother'];
    $statuses = ['Growing', 'Harvested'];
    
    foreach ($geneticsIds as $geneticsId) {
        // Create 3-5 plants per genetics
        $plantsPerGenetics = rand(3, 5);
        for ($i = 0; $i < $plantsPerGenetics; $i++) {
            $plantCount++;
            $trackingNumber = 'TEST-' . date('Y') . '-' . str_pad($plantCount, 6, '0', STR_PAD_LEFT);
            $stage = $stages[array_rand($stages)];
            $status = $statuses[array_rand($statuses)];
            $roomId = $roomIds[array_rand($roomIds)];
            
            $stmt = $pdo->prepare("INSERT INTO Plants (tracking_number, genetics_id, status, growth_stage, room_id, date_created, company_id, plant_tag, notes, source_type, wet_weight, dry_weight) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $trackingNumber,
                $geneticsId,
                $status,
                $stage,
                $roomId,
                date('Y-m-d H:i:s', strtotime('-' . rand(1, 180) . ' days')),
                $companyIds[array_rand($companyIds)],
                'TAG-' . $plantCount,
                'Dummy plant for testing purposes',
                'seed',
                $status === 'Harvested' ? rand(100, 500) : null,
                $status === 'Harvested' ? rand(20, 100) : null
            ]);
        }
    }
    $createdItems['plants'] = $plantCount;
    
    // 6. Create dummy flower transactions
    $flowerTransactions = 0;
    foreach ($geneticsIds as $geneticsId) {
        // Add some flower
        $stmt = $pdo->prepare("INSERT INTO Flower (genetics_id, weight, transaction_type, reason, company_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $geneticsId,
            rand(50, 200),
            'Add',
            'Test harvest from dummy plants',
            $companyIds[array_rand($companyIds)]
        ]);
        $flowerTransactions++;
        
        // Maybe subtract some
        if (rand(0, 1)) {
            $stmt->execute([
                $geneticsId,
                rand(10, 50),
                'Subtract',
                'Test sale to dummy company',
                $companyIds[array_rand($companyIds)]
            ]);
            $flowerTransactions++;
        }
    }
    $createdItems['flower_transactions'] = $flowerTransactions;
    
    // 7. Create some harvest records for harvested plants
    $stmt = $pdo->prepare("SELECT id FROM Plants WHERE status = 'Harvested' AND tracking_number LIKE 'TEST-%'");
    $stmt->execute();
    $harvestedPlants = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    $harvestRecords = 0;
    foreach ($harvestedPlants as $plantId) {
        $stmt = $pdo->prepare("INSERT INTO HarvestRecords (plant_id, harvest_date, wet_weight, dry_weight, flower_weight, trim_weight, quality, notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $wetWeight = rand(100, 500);
        $dryWeight = round($wetWeight * 0.2, 2);
        $flowerWeight = round($dryWeight * 0.8, 2);
        $trimWeight = round($dryWeight * 0.2, 2);
        
        $stmt->execute([
            $plantId,
            date('Y-m-d H:i:s', strtotime('-' . rand(1, 90) . ' days')),
            $wetWeight,
            $dryWeight,
            $flowerWeight,
            $trimWeight,
            ['A+', 'A', 'B+', 'B'][array_rand(['A+', 'A', 'B+', 'B'])],
            'Dummy harvest record for testing'
        ]);
        $harvestRecords++;
    }
    $createdItems['harvest_records'] = $harvestRecords;
    
    // Commit transaction
    $pdo->commit();
    
    $message = "Created dummy data:\n";
    $message .= "• {$createdItems['companies']} companies\n";
    $message .= "• {$createdItems['rooms']} rooms\n";
    $message .= "• {$createdItems['genetics']} genetics\n";
    $message .= "• {$createdItems['seed_stocks']} seed stocks\n";
    $message .= "• {$createdItems['plants']} plants\n";
    $message .= "• {$createdItems['flower_transactions']} flower transactions\n";
    $message .= "• {$createdItems['harvest_records']} harvest records";
    
    echo json_encode([
        'success' => true,
        'message' => $message,
        'created' => $createdItems
    ]);
    
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollback();
    }
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>