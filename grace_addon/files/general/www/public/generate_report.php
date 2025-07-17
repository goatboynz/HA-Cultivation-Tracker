<?php require_once 'auth.php'; ?>
<?php
require_once 'init_db.php';

try {
    $pdo = initializeDatabase();
    $reportType = $_GET['type'] ?? '';
    $startDate = $_GET['start_date'] ?? '';
    $endDate = $_GET['end_date'] ?? '';
    
    $reportData = [];
    $title = '';
    $headers = [];
    
    switch ($reportType) {
        case 'plants_all':
            $title = 'All Plants Report';
            $headers = ['ID', 'Plant Tag', 'Genetics', 'Stage', 'Room', 'Status', 'Created', 'Notes'];
            $sql = "SELECT p.id, p.plant_tag, g.name as genetics, p.growth_stage, r.name as room, 
                           p.status, p.date_created, p.notes
                    FROM Plants p 
                    LEFT JOIN Genetics g ON p.genetics_id = g.id 
                    LEFT JOIN Rooms r ON p.room_id = r.id 
                    ORDER BY p.date_created DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $reportData = $stmt->fetchAll(PDO::FETCH_ASSOC);
            break;
            
        case 'plants_by_stage':
            $title = 'Plants by Growth Stage';
            $headers = ['Stage', 'Count', 'Genetics', 'Room', 'Average Days'];
            $sql = "SELECT p.growth_stage as stage, COUNT(*) as count, g.name as genetics, 
                           r.name as room, 
                           ROUND(AVG(julianday('now') - julianday(p.date_created)), 1) as avg_days
                    FROM Plants p 
                    LEFT JOIN Genetics g ON p.genetics_id = g.id 
                    LEFT JOIN Rooms r ON p.room_id = r.id 
                    WHERE p.status = 'Growing'
                    GROUP BY p.growth_stage, g.name, r.name 
                    ORDER BY p.growth_stage, g.name";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $reportData = $stmt->fetchAll(PDO::FETCH_ASSOC);
            break;
            
        case 'plants_by_room':
            $title = 'Plants by Room';
            $headers = ['Room', 'Room Type', 'Plant Count', 'Genetics Variety', 'Stages Present'];
            $sql = "SELECT r.name as room, r.room_type, COUNT(p.id) as plant_count,
                           COUNT(DISTINCT p.genetics_id) as genetics_variety,
                           GROUP_CONCAT(DISTINCT p.growth_stage) as stages_present
                    FROM Rooms r 
                    LEFT JOIN Plants p ON r.id = p.room_id AND p.status = 'Growing'
                    GROUP BY r.id, r.name, r.room_type 
                    ORDER BY r.room_type, r.name";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $reportData = $stmt->fetchAll(PDO::FETCH_ASSOC);
            break;
            
        case 'mother_plants':
            $title = 'Mother Plants Report';
            $headers = ['Plant Tag', 'Genetics', 'Room', 'Created', 'Clone Count', 'Status', 'Notes'];
            $sql = "SELECT p.plant_tag, g.name as genetics, r.name as room, p.date_created,
                           (SELECT COUNT(*) FROM Plants c WHERE c.mother_id = p.id) as clone_count,
                           p.status, p.notes
                    FROM Plants p 
                    LEFT JOIN Genetics g ON p.genetics_id = g.id 
                    LEFT JOIN Rooms r ON p.room_id = r.id 
                    WHERE p.is_mother = 1 
                    ORDER BY g.name, p.date_created";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $reportData = $stmt->fetchAll(PDO::FETCH_ASSOC);
            break;
            
        case 'clone_tracking':
            $title = 'Clone Tracking Report';
            $headers = ['Clone ID', 'Clone Tag', 'Mother Plant', 'Genetics', 'Room', 'Created', 'Stage', 'Status'];
            $sql = "SELECT c.id as clone_id, c.plant_tag as clone_tag, 
                           COALESCE(m.plant_tag, 'ID: ' || m.id) as mother_plant,
                           g.name as genetics, r.name as room, c.date_created, 
                           c.growth_stage as stage, c.status
                    FROM Plants c 
                    LEFT JOIN Plants m ON c.mother_id = m.id 
                    LEFT JOIN Genetics g ON c.genetics_id = g.id 
                    LEFT JOIN Rooms r ON c.room_id = r.id 
                    WHERE c.mother_id IS NOT NULL 
                    ORDER BY c.date_created DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $reportData = $stmt->fetchAll(PDO::FETCH_ASSOC);
            break;
            
        case 'room_utilization':
            $title = 'Room Utilization Report';
            $headers = ['Room', 'Type', 'Current Plants', 'Capacity %', 'Last Activity'];
            $sql = "SELECT r.name as room, r.room_type as type, 
                           COUNT(p.id) as current_plants,
                           CASE 
                               WHEN r.room_type = 'Clone' THEN ROUND((COUNT(p.id) * 100.0 / 100), 1)
                               WHEN r.room_type = 'Veg' THEN ROUND((COUNT(p.id) * 100.0 / 50), 1)
                               WHEN r.room_type = 'Flower' THEN ROUND((COUNT(p.id) * 100.0 / 30), 1)
                               WHEN r.room_type = 'Mother' THEN ROUND((COUNT(p.id) * 100.0 / 10), 1)
                               ELSE 0
                           END as capacity_percent,
                           MAX(p.date_stage_changed) as last_activity
                    FROM Rooms r 
                    LEFT JOIN Plants p ON r.id = p.room_id AND p.status = 'Growing'
                    GROUP BY r.id, r.name, r.room_type 
                    ORDER BY capacity_percent DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $reportData = $stmt->fetchAll(PDO::FETCH_ASSOC);
            break;
            
        case 'genetics_performance':
            $title = 'Genetics Performance Report';
            $headers = ['Genetics', 'Total Plants', 'Active Plants', 'Harvested', 'Success Rate %', 'Avg Days to Harvest'];
            $sql = "SELECT g.name as genetics, 
                           COUNT(p.id) as total_plants,
                           SUM(CASE WHEN p.status = 'Growing' THEN 1 ELSE 0 END) as active_plants,
                           SUM(CASE WHEN p.status = 'Harvested' THEN 1 ELSE 0 END) as harvested,
                           ROUND((SUM(CASE WHEN p.status = 'Harvested' THEN 1 ELSE 0 END) * 100.0 / COUNT(p.id)), 1) as success_rate,
                           ROUND(AVG(CASE WHEN p.status = 'Harvested' AND p.date_harvested IS NOT NULL 
                                         THEN julianday(p.date_harvested) - julianday(p.date_created) 
                                         ELSE NULL END), 1) as avg_days_to_harvest
                    FROM Genetics g 
                    LEFT JOIN Plants p ON g.id = p.genetics_id 
                    GROUP BY g.id, g.name 
                    HAVING COUNT(p.id) > 0
                    ORDER BY success_rate DESC, total_plants DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $reportData = $stmt->fetchAll(PDO::FETCH_ASSOC);
            break;
            
        case 'plants_created':
            if ($startDate && $endDate) {
                $title = "Plants Created ($startDate to $endDate)";
                $headers = ['Date', 'Plant Tag', 'Genetics', 'Stage', 'Room', 'Status'];
                $sql = "SELECT DATE(p.date_created) as date, p.plant_tag, g.name as genetics, 
                               p.growth_stage as stage, r.name as room, p.status
                        FROM Plants p 
                        LEFT JOIN Genetics g ON p.genetics_id = g.id 
                        LEFT JOIN Rooms r ON p.room_id = r.id 
                        WHERE DATE(p.date_created) BETWEEN ? AND ? 
                        ORDER BY p.date_created DESC";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$startDate, $endDate]);
                $reportData = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            break;
            
        case 'plants_harvested':
            if ($startDate && $endDate) {
                $title = "Plants Harvested ($startDate to $endDate)";
                $headers = ['Harvest Date', 'Plant Tag', 'Genetics', 'Room', 'Days to Harvest'];
                $sql = "SELECT DATE(p.date_harvested) as harvest_date, p.plant_tag, g.name as genetics, 
                               r.name as room,
                               ROUND(julianday(p.date_harvested) - julianday(p.date_created), 1) as days_to_harvest
                        FROM Plants p 
                        LEFT JOIN Genetics g ON p.genetics_id = g.id 
                        LEFT JOIN Rooms r ON p.room_id = r.id 
                        WHERE p.status = 'Harvested' AND DATE(p.date_harvested) BETWEEN ? AND ? 
                        ORDER BY p.date_harvested DESC";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$startDate, $endDate]);
                $reportData = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            break;
            
        default:
            throw new Exception('Invalid report type');
    }
    
    header('Content-Type: application/json');
    echo json_encode([
        'title' => $title,
        'headers' => $headers,
        'data' => $reportData
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>