<?php require_once 'auth.php'; ?>
<?php
require_once 'init_db.php';

try {
    $pdo = initializeDatabase();
    
    // Fetch rooms data
    $stmt = $pdo->query("SELECT id, name, room_type FROM Rooms ORDER BY room_type, name");
    $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Send data as JSON
    header('Content-Type: application/json');
    echo json_encode($rooms);
} catch (Exception $e) {
    // Handle errors gracefully (log the error or send an error response)
    http_response_code(500); // Internal Server Error
    echo json_encode(['error' => 'Database error']);
}
?>
