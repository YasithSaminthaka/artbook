<?php
header('Content-Type: application/json');
require_once '../config.php';

// Simple admin check (replace with your actual authentication)
if (!isset($_GET['admin_key']) || $_GET['admin_key'] !== '0') {
    die(json_encode(['success' => false, 'message' => 'Unauthorized']));
}

// Get parameters
$courseId = (int)($_GET['course_id'] ?? 0);
$discount = (float)($_GET['discount'] ?? 0);
$expiry = $_GET['expiry'] ?? date('Y-m-d', strtotime('+1 month'));
$quantity = (int)($_GET['quantity'] ?? 1);
$prefix = $_GET['prefix'] ?? 'CODE';

// Generate codes
$codes = [];
for ($i = 0; $i < $quantity; $i++) {
    $code = $prefix . strtoupper(substr(md5(uniqid()), 0, 8));
    
    $stmt = $db->prepare("INSERT INTO redeem_codes 
                         (code, course_id, discount_amount, expiry_date) 
                         VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sids", $code, $courseId, $discount, $expiry);
    $stmt->execute();
    
    $codes[] = $code;
}

echo json_encode([
    'success' => true,
    'codes' => $codes,
    'count' => count($codes)
]);