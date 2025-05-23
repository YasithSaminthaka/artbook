<?php
require_once '../config.php';
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Adjust for production
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Invalid request method']);
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['user_id']) || !is_numeric($data['user_id']) ||
    !isset($data['course_id']) || !is_numeric($data['course_id']) ||
    !isset($data['payment_amount']) || !is_numeric($data['payment_amount']) ||
    !isset($data['payment_status'])) {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Missing or invalid parameters']);
    exit();
}

$userId = intval($data['user_id']);
$courseId = intval($data['course_id']);
$paymentAmount = floatval($data['payment_amount']);
$paymentStatus = trim($data['payment_status']);
$transactionId = generateTransactionId();


$checkQuery = "SELECT id FROM user_payments WHERE user_id = ? AND course_id = ? AND payment_status = 'completed'";
$checkStmt = $db->prepare($checkQuery);
$checkStmt->bind_param("ii", $userId, $courseId);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();

if ($checkResult->num_rows > 0) {
    // A completed payment already exists
    http_response_code(409); // Conflict
    echo json_encode(['error' => 'User has already paid for this course']);
} else {
    $query = "INSERT INTO user_payments (user_id, course_id, payment_amount, payment_status, transaction_id) VALUES (?, ?, ?, ?, ?)";
    $stmt = $db->prepare($query);
    $stmt->bind_param("iidss", $userId, $courseId, $paymentAmount, $paymentStatus, $transactionId);
    
    if ($stmt->execute()) {
        http_response_code(201); // Created
        echo json_encode(['message' => 'Payment recorded successfully']);
    } else {
        http_response_code(500); // Internal Server Error
        echo json_encode(['error' => 'Failed to record payment: ' . $stmt->error]);
    }
}


function generateTransactionId($prefix = 'TXN-', $length = 20) {
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length - strlen($prefix); $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $prefix . $randomString;
}


?>