<?php require_once 'auth.php'; ?>
<?php
require_once 'init_db.php';

try {
    $pdo = initializeDatabase();

    $sql = "SELECT
                P.id,
                P.tracking_number,
                P.plant_tag,
                G.name AS geneticsName,
                P.growth_stage,
                R.name AS room_name,
                P.status,
                P.date_created,
                P.date_stage_changed,
                CAST((julianday('now') - julianday(P.date_created)) AS INTEGER) AS age,
                CAST((julianday('now') - julianday(P.date_stage_changed)) AS INTEGER) AS days_in_stage,
                P.is_mother,
                P.genetics_id,
                P.room_id
            FROM
                Plants P
            LEFT JOIN
                Genetics G ON P.genetics_id = G.id
            LEFT JOIN
                Rooms R ON P.room_id = R.id
            WHERE
                P.status IN ('Growing', 'Harvested', 'Destroyed', 'Sent')
            ORDER BY
                P.growth_stage, G.name, age DESC";

    $stmt = $pdo->query($sql);
    $plantsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Properly send JSON header
    header('Content-Type: application/json');
    echo json_encode($plantsData);
    exit(); // Ensure no further output occurs
} catch (PDOException $e) {
    http_response_code(500);
    exit(json_encode(['error' => 'Database error: ' . $e->getMessage()]));
}
