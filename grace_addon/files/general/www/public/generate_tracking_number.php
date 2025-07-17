<?php
require_once 'init_db.php';

function generateTrackingNumber($pdo) {
    $maxAttempts = 10;
    $attempt = 0;
    
    while ($attempt < $maxAttempts) {
        // Generate format: CT-YYYY-XXXXXX (CultivationTracker-Year-6DigitNumber)
        $year = date('Y');
        $randomNumber = str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
        $trackingNumber = "CT-{$year}-{$randomNumber}";
        
        // Check if this tracking number already exists
        $sql = "SELECT COUNT(*) FROM Plants WHERE tracking_number = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$trackingNumber]);
        
        if ($stmt->fetchColumn() == 0) {
            return $trackingNumber;
        }
        
        $attempt++;
    }
    
    // Fallback: use timestamp-based number if random generation fails
    $timestamp = time();
    $trackingNumber = "CT-{$year}-" . substr($timestamp, -6);
    
    return $trackingNumber;
}

function generateBatchTrackingNumbers($pdo, $count) {
    $trackingNumbers = [];
    
    for ($i = 0; $i < $count; $i++) {
        $trackingNumbers[] = generateTrackingNumber($pdo);
    }
    
    return $trackingNumbers;
}
?>