<?php
require_once 'auth.php';
require_once 'init_db.php';

header('Content-Type: application/json');

try {
    $pdo = initializeDatabase();
    $period = $_GET['period'] ?? 'monthly';
    
    $data = [];
    $title = '';
    $headers = [];
    
    switch ($period) {
        case 'monthly':
            $title = 'Monthly Plant Counts (Last 12 Months)';
            $headers = ['Month', 'Total Plants', 'Clone', 'Veg', 'Flower', 'Mother', 'Harvested', 'Destroyed'];
            
            // Get data for last 12 months
            for ($i = 11; $i >= 0; $i--) {
                $month = date('Y-m', strtotime("-$i months"));
                $monthStart = $month . '-01';
                $monthEnd = date('Y-m-t', strtotime($monthStart));
                
                // Count plants by stage for this month
                $stmt = $pdo->prepare("
                    SELECT 
                        growth_stage,
                        status,
                        COUNT(*) as count
                    FROM Plants 
                    WHERE date_created <= ? 
                    AND (date_harvested IS NULL OR date_harvested > ?)
                    AND (destruction_date IS NULL OR destruction_date > ?)
                    GROUP BY growth_stage, status
                ");
                $stmt->execute([$monthEnd, $monthStart, $monthStart]);
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                $counts = [
                    'Clone' => 0,
                    'Veg' => 0,
                    'Flower' => 0,
                    'Mother' => 0,
                    'Harvested' => 0,
                    'Destroyed' => 0
                ];
                
                foreach ($results as $result) {
                    if ($result['status'] === 'Growing') {
                        $counts[$result['growth_stage']] = $result['count'];
                    } elseif ($result['status'] === 'Harvested') {
                        $counts['Harvested'] += $result['count'];
                    } elseif ($result['status'] === 'Destroyed') {
                        $counts['Destroyed'] += $result['count'];
                    }
                }
                
                $totalPlants = $counts['Clone'] + $counts['Veg'] + $counts['Flower'] + $counts['Mother'];
                
                $data[] = [
                    'Month' => date('M Y', strtotime($monthStart)),
                    'Total Plants' => $totalPlants,
                    'Clone' => $counts['Clone'],
                    'Veg' => $counts['Veg'],
                    'Flower' => $counts['Flower'],
                    'Mother' => $counts['Mother'],
                    'Harvested' => $counts['Harvested'],
                    'Destroyed' => $counts['Destroyed']
                ];
            }
            break;
            
        case '6month':
            $title = '6-Month Plant Counts (Last 6 Months)';
            $headers = ['Month', 'Total Plants', 'Clone', 'Veg', 'Flower', 'Mother', 'New Plants', 'Harvested'];
            
            for ($i = 5; $i >= 0; $i--) {
                $month = date('Y-m', strtotime("-$i months"));
                $monthStart = $month . '-01';
                $monthEnd = date('Y-m-t', strtotime($monthStart));
                
                // Active plants at end of month
                $stmt = $pdo->prepare("
                    SELECT 
                        growth_stage,
                        COUNT(*) as count
                    FROM Plants 
                    WHERE status = 'Growing'
                    AND date_created <= ?
                    GROUP BY growth_stage
                ");
                $stmt->execute([$monthEnd]);
                $activeResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                $counts = ['Clone' => 0, 'Veg' => 0, 'Flower' => 0, 'Mother' => 0];
                foreach ($activeResults as $result) {
                    $counts[$result['growth_stage']] = $result['count'];
                }
                
                // New plants created this month
                $stmt = $pdo->prepare("
                    SELECT COUNT(*) as count
                    FROM Plants 
                    WHERE date_created >= ? AND date_created <= ?
                ");
                $stmt->execute([$monthStart, $monthEnd]);
                $newPlants = $stmt->fetchColumn();
                
                // Plants harvested this month
                $stmt = $pdo->prepare("
                    SELECT COUNT(*) as count
                    FROM Plants 
                    WHERE date_harvested >= ? AND date_harvested <= ?
                ");
                $stmt->execute([$monthStart, $monthEnd]);
                $harvested = $stmt->fetchColumn();
                
                $totalPlants = array_sum($counts);
                
                $data[] = [
                    'Month' => date('M Y', strtotime($monthStart)),
                    'Total Plants' => $totalPlants,
                    'Clone' => $counts['Clone'],
                    'Veg' => $counts['Veg'],
                    'Flower' => $counts['Flower'],
                    'Mother' => $counts['Mother'],
                    'New Plants' => $newPlants,
                    'Harvested' => $harvested
                ];
            }
            break;
            
        case 'yearly':
            $title = 'Yearly Plant Counts (Last 3 Years)';
            $headers = ['Year', 'Total Plants', 'Clone', 'Veg', 'Flower', 'Mother', 'Plants Created', 'Plants Harvested', 'Plants Destroyed'];
            
            for ($i = 2; $i >= 0; $i--) {
                $year = date('Y', strtotime("-$i years"));
                $yearStart = $year . '-01-01';
                $yearEnd = $year . '-12-31';
                
                // Active plants at end of year
                $stmt = $pdo->prepare("
                    SELECT 
                        growth_stage,
                        COUNT(*) as count
                    FROM Plants 
                    WHERE status = 'Growing'
                    AND date_created <= ?
                    GROUP BY growth_stage
                ");
                $stmt->execute([$yearEnd]);
                $activeResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                $counts = ['Clone' => 0, 'Veg' => 0, 'Flower' => 0, 'Mother' => 0];
                foreach ($activeResults as $result) {
                    $counts[$result['growth_stage']] = $result['count'];
                }
                
                // Plants created this year
                $stmt = $pdo->prepare("
                    SELECT COUNT(*) as count
                    FROM Plants 
                    WHERE date_created >= ? AND date_created <= ?
                ");
                $stmt->execute([$yearStart, $yearEnd]);
                $created = $stmt->fetchColumn();
                
                // Plants harvested this year
                $stmt = $pdo->prepare("
                    SELECT COUNT(*) as count
                    FROM Plants 
                    WHERE date_harvested >= ? AND date_harvested <= ?
                ");
                $stmt->execute([$yearStart, $yearEnd]);
                $harvested = $stmt->fetchColumn();
                
                // Plants destroyed this year
                $stmt = $pdo->prepare("
                    SELECT COUNT(*) as count
                    FROM Plants 
                    WHERE destruction_date >= ? AND destruction_date <= ?
                ");
                $stmt->execute([$yearStart, $yearEnd]);
                $destroyed = $stmt->fetchColumn();
                
                $totalPlants = array_sum($counts);
                
                $data[] = [
                    'Year' => $year,
                    'Total Plants' => $totalPlants,
                    'Clone' => $counts['Clone'],
                    'Veg' => $counts['Veg'],
                    'Flower' => $counts['Flower'],
                    'Mother' => $counts['Mother'],
                    'Plants Created' => $created,
                    'Plants Harvested' => $harvested,
                    'Plants Destroyed' => $destroyed
                ];
            }
            break;
            
        default:
            throw new Exception('Invalid period specified');
    }
    
    echo json_encode([
        'success' => true,
        'title' => $title,
        'headers' => $headers,
        'data' => $data
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>