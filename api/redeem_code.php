<?php
header('Content-Type: application/json');
require_once '../config.php';
session_start();

// Verify request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die(json_encode(['success' => false, 'message' => 'Method not allowed']));
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);
$code = $input['code'] ?? '';
$courseId = $input['course_id'] ?? 0;
$userId = $_SESSION['user_id'] ?? 0;

// Validate input
if (empty($code) || empty($courseId) || empty($userId)) {
    http_response_code(400);
    die(json_encode(['success' => false, 'message' => 'Invalid request data']));
}

try {
    $db->begin_transaction();

    // 1. Check redeem code validity
    $stmt = $db->prepare("
        SELECT id, discount_amount, expiry_date, is_used 
        FROM redeem_codes 
        WHERE code = ? AND course_id = ?
        FOR UPDATE
    ");
    $stmt->bind_param("si", $code, $courseId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        throw new Exception("Invalid redeem code for this course");
    }

    $codeData = $result->fetch_assoc();

    // 2. Validate code status
    if ($codeData['is_used']) {
        throw new Exception("This code has already been used");
    }

    if (strtotime($codeData['expiry_date']) < time()) {
        throw new Exception("This code has expired");
    }

    // 3. Get course price
    $courseStmt = $db->prepare("SELECT price FROM courses WHERE id = ?");
    $courseStmt->bind_param("i", $courseId);
    $courseStmt->execute();
    $course = $courseStmt->get_result()->fetch_assoc();

    if (!$course) {
        throw new Exception("Course not found");
    }

    // 4. Calculate final price
    $finalAmount = max($course['price'] - $codeData['discount_amount'], 0);

    // 5. Mark code as used
    $updateStmt = $db->prepare("UPDATE redeem_codes SET is_used = TRUE WHERE id = ?");
    $updateStmt->bind_param("i", $codeData['id']);
    $updateStmt->execute();

    // 6. Create payment record
    $paymentStmt = $db->prepare("
        INSERT INTO payments (
            user_id, course_id, amount, 
            payment_method, payment_status, redeem_code
        ) VALUES (?, ?, ?, 'redeem_code', 'completed', ?)
    ");
    $paymentStmt->bind_param("iids", $userId, $courseId, $finalAmount, $code);
    $paymentStmt->execute();

    $db->commit();

    // Return success response
    echo json_encode([
        'success' => true,
        'message' => 'Redeem code applied successfully',
        'discount' => $codeData['discount_amount'],
        'final_price' => $finalAmount,
        'payment_id' => $db->insert_id
    ]);

} catch (Exception $e) {
    $db->rollback();
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}