<?php require_once 'auth.php'; ?>
<?php
require_once 'init_db.php';

try {
    $pdo = initializeDatabase();
    
    echo "<h2>Testing Mother Plants Query</h2>";
    
    // Check all plants
    $sql = "SELECT id, plant_tag, tracking_number, growth_stage, is_mother, status FROM Plants ORDER BY id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $allPlants = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h3>All Plants:</h3>";
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Tag</th><th>Tracking</th><th>Stage</th><th>Is Mother</th><th>Status</th></tr>";
    foreach ($allPlants as $plant) {
        echo "<tr>";
        echo "<td>{$plant['id']}</td>";
        echo "<td>{$plant['plant_tag']}</td>";
        echo "<td>{$plant['tracking_number']}</td>";
        echo "<td>{$plant['growth_stage']}</td>";
        echo "<td>{$plant['is_mother']}</td>";
        echo "<td>{$plant['status']}</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Check mother plants specifically
    $sql = "SELECT p.id, p.plant_tag, p.tracking_number, g.name as genetics_name, r.name as room_name,
                   p.growth_stage, p.is_mother
            FROM Plants p 
            LEFT JOIN Genetics g ON p.genetics_id = g.id 
            LEFT JOIN Rooms r ON p.room_id = r.id
            WHERE (p.is_mother = 1 OR p.growth_stage = 'Mother') 
            AND p.status = 'Growing'
            ORDER BY g.name, p.plant_tag, p.tracking_number";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $mothers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h3>Mother Plants Query Result:</h3>";
    echo "<p>Found " . count($mothers) . " mother plants</p>";
    
    if (count($mothers) > 0) {
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>Tag</th><th>Tracking</th><th>Genetics</th><th>Room</th><th>Stage</th><th>Is Mother</th></tr>";
        foreach ($mothers as $mother) {
            echo "<tr>";
            echo "<td>{$mother['id']}</td>";
            echo "<td>{$mother['plant_tag']}</td>";
            echo "<td>{$mother['tracking_number']}</td>";
            echo "<td>{$mother['genetics_name']}</td>";
            echo "<td>{$mother['room_name']}</td>";
            echo "<td>{$mother['growth_stage']}</td>";
            echo "<td>{$mother['is_mother']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    // Test the API endpoint
    echo "<h3>API Endpoint Test:</h3>";
    $apiUrl = 'get_mother_plants.php';
    $apiResponse = file_get_contents($apiUrl);
    echo "<pre>" . htmlspecialchars($apiResponse) . "</pre>";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>