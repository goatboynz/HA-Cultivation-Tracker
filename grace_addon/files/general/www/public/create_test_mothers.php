<?php require_once 'auth.php'; ?>
<?php
require_once 'init_db.php';

try {
    $pdo = initializeDatabase();
    
    // Check if we have any mother plants
    $sql = "SELECT COUNT(*) as count FROM Plants WHERE (is_mother = 1 OR growth_stage = 'Mother') AND status = 'Growing'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result['count'] == 0) {
        echo "<h2>No mother plants found. Creating test mother plants...</h2>";
        
        // Get first genetics and room
        $geneticsStmt = $pdo->prepare("SELECT id FROM Genetics LIMIT 1");
        $geneticsStmt->execute();
        $genetics = $geneticsStmt->fetch(PDO::FETCH_ASSOC);
        
        $roomStmt = $pdo->prepare("SELECT id FROM Rooms LIMIT 1");
        $roomStmt->execute();
        $room = $roomStmt->fetch(PDO::FETCH_ASSOC);
        
        if ($genetics && $room) {
            // Create a few test mother plants
            for ($i = 1; $i <= 3; $i++) {
                $trackingNumber = 'MOTHER-' . str_pad($i, 6, '0', STR_PAD_LEFT);
                $plantTag = "Mother Plant $i";
                
                $sql = "INSERT INTO Plants (tracking_number, plant_tag, genetics_id, growth_stage, room_id, status, is_mother, date_created) 
                        VALUES (?, ?, ?, 'Mother', ?, 'Growing', 1, datetime('now'))";
                
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$trackingNumber, $plantTag, $genetics['id'], $room['id']]);
                
                echo "<p>Created mother plant: $trackingNumber - $plantTag</p>";
            }
            
            echo "<p><strong>Test mother plants created successfully!</strong></p>";
            echo "<p><a href='test_mother_plants.php'>Test Mother Plants Query</a></p>";
            echo "<p><a href='receive_genetics.php'>Test Add Plants Form</a></p>";
        } else {
            echo "<p>Error: No genetics or rooms found. Please add genetics and rooms first.</p>";
        }
    } else {
        echo "<h2>Found {$result['count']} existing mother plants.</h2>";
        echo "<p><a href='test_mother_plants.php'>View Mother Plants</a></p>";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>