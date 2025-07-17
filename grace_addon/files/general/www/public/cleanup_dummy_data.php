<?php
require_once 'auth.php';
require_once 'init_db.php';

header('Content-Type: application/json');

try {
    // Initialize the database and get PDO connection
    $pdo = initializeDatabase();
    
    // Start transaction
    $pdo->beginTransaction();
    
    $deletedItems = [];
    
    // Delete in reverse order of dependencies to avoid foreign key issues
    
    // 1. Delete harvest records for dummy plants
    $stmt = $pdo->prepare("DELETE FROM HarvestRecords WHERE plant_id IN (SELECT id FROM Plants WHERE tracking_number LIKE 'TEST-%')");
    $stmt->execute();
    $deletedItems['harvest_records'] = $stmt->rowCount();
    
    // 2. Delete plant photos for dummy plants
    $stmt = $pdo->prepare("DELETE FROM PlantPhotos WHERE plant_id IN (SELECT id FROM Plants WHERE tracking_number LIKE 'TEST-%')");
    $stmt->execute();
    $deletedItems['plant_photos'] = $stmt->rowCount();
    
    // 3. Delete destruction records for dummy plants
    $stmt = $pdo->prepare("DELETE FROM DestructionRecords WHERE plant_id IN (SELECT id FROM Plants WHERE tracking_number LIKE 'TEST-%')");
    $stmt->execute();
    $deletedItems['destruction_records'] = $stmt->rowCount();
    
    // 4. Delete flower transactions for dummy genetics
    $stmt = $pdo->prepare("DELETE FROM Flower WHERE genetics_id IN (SELECT id FROM Genetics WHERE name LIKE 'Test %' OR name LIKE 'Dummy %' OR name LIKE 'Sample %' OR name LIKE 'Mock %')");
    $stmt->execute();
    $deletedItems['flower_transactions'] = $stmt->rowCount();
    
    // 5. Delete dummy plants
    $stmt = $pdo->prepare("DELETE FROM Plants WHERE tracking_number LIKE 'TEST-%'");
    $stmt->execute();
    $deletedItems['plants'] = $stmt->rowCount();
    
    // 6. Delete dummy seed stock
    $stmt = $pdo->prepare("DELETE FROM SeedStock WHERE batch_name LIKE 'TEST-BATCH-%'");
    $stmt->execute();
    $deletedItems['seed_stocks'] = $stmt->rowCount();
    
    // 7. Delete shipping manifests for dummy companies
    $stmt = $pdo->prepare("DELETE FROM ShippingManifests WHERE sending_company_id IN (SELECT id FROM Companies WHERE license_number LIKE 'TL-%' OR license_number LIKE 'GB-%' OR license_number LIKE 'QT-%')");
    $stmt->execute();
    $deletedItems['shipping_manifests'] = $stmt->rowCount();
    
    // 8. Delete dummy genetics
    $stmt = $pdo->prepare("DELETE FROM Genetics WHERE name LIKE 'Test %' OR name LIKE 'Dummy %' OR name LIKE 'Sample %' OR name LIKE 'Mock %'");
    $stmt->execute();
    $deletedItems['genetics'] = $stmt->rowCount();
    
    // 9. Delete dummy rooms
    $stmt = $pdo->prepare("DELETE FROM Rooms WHERE name LIKE 'Test %'");
    $stmt->execute();
    $deletedItems['rooms'] = $stmt->rowCount();
    
    // 10. Delete dummy companies
    $stmt = $pdo->prepare("DELETE FROM Companies WHERE license_number LIKE 'TL-%' OR license_number LIKE 'GB-%' OR license_number LIKE 'QT-%'");
    $stmt->execute();
    $deletedItems['companies'] = $stmt->rowCount();
    
    // Commit transaction
    $pdo->commit();
    
    $message = "Deleted dummy data:\n";
    $message .= "• {$deletedItems['companies']} companies\n";
    $message .= "• {$deletedItems['rooms']} rooms\n";
    $message .= "• {$deletedItems['genetics']} genetics\n";
    $message .= "• {$deletedItems['seed_stocks']} seed stocks\n";
    $message .= "• {$deletedItems['plants']} plants\n";
    $message .= "• {$deletedItems['flower_transactions']} flower transactions\n";
    $message .= "• {$deletedItems['harvest_records']} harvest records\n";
    $message .= "• {$deletedItems['plant_photos']} plant photos\n";
    $message .= "• {$deletedItems['destruction_records']} destruction records\n";
    $message .= "• {$deletedItems['shipping_manifests']} shipping manifests";
    
    echo json_encode([
        'success' => true,
        'message' => $message,
        'deleted' => $deletedItems
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