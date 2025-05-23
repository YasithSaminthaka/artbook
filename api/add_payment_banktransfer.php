<?php
session_start();
require_once '../config.php';

// Get raw POST data
$input = json_decode(file_get_contents('php://input'), true);

// Extract values safely
$user_id = $_SESSION['user_id'];
$course_id = $input['course_id'] ?? null;
$amount = $input['amount'] ?? null;
$receipt_url = $input['receipt_url'] ?? null;

// Validate required fields
if (!$user_id || !$course_id || !$amount || !$receipt_url) {
    echo json_encode(["success" => false, "message" => "Missing required fields"]);
    exit;
}

// $check_stmt = $db->prepare("SELECT id FROM redeem_codes WHERE id = ? AND code = ? AND is_used = 0");
// $check_stmt->bind_param("is", $redeem_id, $receipt_url);
// $check_stmt->execute();
// $check_stmt->store_result();
// if ($check_stmt->num_rows === 0) {

// } else {
$amount = (float) $amount; // Ensure it's a float
$stmt = $db->prepare("INSERT INTO payments (user_id, course_id, amount, payment_method, payment_status, receipt_url) VALUES (?, ?, ?, 'online_transfer', 'pending', ?)");
$stmt->bind_param("iids", $user_id, $course_id, $amount, $receipt_url);

if ($stmt->execute()) {

    // $update_stmt = $db->prepare("UPDATE redeem_codes SET is_used = 1 WHERE id = ?");
    // $update_stmt->bind_param("i", $redeem_id);
    // $update_stmt->execute();
    // $update_stmt->close();

    echo json_encode(["success" => true, "message" => "Payment added successfully"]);


} else {
    echo json_encode(["success" => false, "message" => "Error: " . $stmt->error]);
}
$stmt->close();
// }
// Prepare and execute query

// }



?>