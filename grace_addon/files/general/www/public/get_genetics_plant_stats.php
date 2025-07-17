<?php require_once 'auth.php'; ?>
<?php
require_once 'init_db.php';

try {
    $pdo = initializeDatabase();
    
    $geneticsId = $_GET['id'] ?? '';
    
    if (!$geneticsId) {
        throw new Exception('Genetics ID is required');
    }
    
    // Current plants by stage
    $currentSql = "SELECT growth_stage, COUNT(*) as count 
                   FROM Plants 
                   WHERE genetics_id = ? AND status = 'Growing' 
                   GROUP BY growth_stage";
    $currentStmt = $pdo->prepare($currentSql);
    $currentStmt->execute([$geneticsId]);
    $currentResults = $currentStmt->fetchAll(PDO::FETCH_ASSOC);
    
    $current = [];
    foreach ($currentResults as $result) {
        $current[$result['growth_stage']] = $result['count'];
    }
    
    // Historical data
    $historicalSql = "SELECT 
                        COUNT(*) as total,
                        SUM(CASE WHEN status = 'Harvested' THEN 1 ELSE 0 END) as harvested,
                        ROUND(
                            (SUM(CASE WHEN status = 'Harvested' THEN 1 ELSE 0 END) * 100.0 / COUNT(*)), 1
                        ) as success_rate,
                        ROUND(
                            AVG(CASE 
                                WHEN status = 'Harvested' AND date_harvested IS NOT NULL 
                                THEN julianday(date_harvested) - julianday(date_created) 
                                ELSE NULL 
                            END), 1
                        ) as avg_days
                      FROM Plants 
                      WHERE genetics_id = ?";
    $historicalStmt = $pdo->prepare($historicalSql);
    $historicalStmt->execute([$geneticsId]);
    $historical = $historicalStmt->fetch(PDO::FETCH_ASSOC);
    
    // Format historical data
    $historical['success_rate'] = $historical['success_rate'] ? $historical['success_rate'] . '%' : '0%';
    $historical['avg_days'] = $historical['avg_days'] ? $historical['avg_days'] . ' days' : '-';
    
    $stats = [
        'current' => $current,
        'historical' => $historical
    ];
    
    header('Content-Type: application/json');
    echo json_encode($stats);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>