<?php
require_once 'init_db.php';

function generateTrackingNumber($pdo, $motherId = null) {
    if ($motherId) {
        // Generate format: MOTHER_ID-XX (e.g., 5-01, 5-02, etc.)
        return generateMotherBasedTrackingNumber($pdo, $motherId);
    } else {
        // Generate format: CT-YYYY-XXXXXX (CultivationTracker-Year-6DigitNumber)
        return generateStandardTrackingNumber($pdo);
    }
}

function generateMotherBasedTrackingNumber($pdo, $motherId) {
    // Get the next sequential number for this mother
    $sql = "SELECT tracking_number FROM Plants WHERE mother_id = ? AND tracking_number LIKE ? ORDER BY tracking_number DESC LIMIT 1";
    $pattern = $motherId . '-%';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$motherId, $pattern]);
    $lastTracking = $stmt->fetchColumn();
    
    if ($lastTracking) {
        // Extract the number part and increment
        $parts = explode('-', $lastTracking);
        $lastNumber = intval($parts[1]);
        $nextNumber = $lastNumber + 1;
    } else {
        // First clone from this mother
        $nextNumber = 1;
    }
    
    // Format as MOTHER_ID-XX (zero-padded to 2 digits)
    return $motherId . '-' . str_pad($nextNumber, 2, '0', STR_PAD_LEFT);
}

function generateStandardTrackingNumber($pdo) {
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

function generateBatchTrackingNumbers($pdo, $count, $motherId = null) {
    $trackingNumbers = [];
    
    for ($i = 0; $i < $count; $i++) {
        $trackingNumbers[] = generateTrackingNumber($pdo, $motherId);
    }
    
    return $trackingNumbers;
}

function generateMultiMotherTrackingNumbers($pdo, $motherDistribution) {
    $trackingNumbers = [];
    
    foreach ($motherDistribution as $motherId => $count) {
        for ($i = 0; $i < $count; $i++) {
            $trackingNumbers[] = generateTrackingNumber($pdo, $motherId);
        }
    }
    
    return $trackingNumbers;
}
?>