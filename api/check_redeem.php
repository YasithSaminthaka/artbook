<?php
session_start();
header('Content-Type: application/json');
require_once '../config.php'; // Your database connection file
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    header("location: ../signup.php");
    //echo json_encode(['error' => 'User not authenticated']);
    exit;
}
// Get JSON input
$data = json_decode(file_get_contents("php://input"), true);
$code = $data['code'] ?? '';
$courseId = $data['course_id'] ?? 0;

if (empty($code) || empty($courseId)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Code and course ID are required']);
    exit;
}

try {
    // Check code validity
    $stmt = $db->prepare("
        SELECT id, discount_amount, expiry_date, is_used
        FROM redeem_codes 
        WHERE code = ?
        AND course_id = ?
    ");
    $stmt->bind_param("si", $code, $courseId);
    $stmt->execute();
    $result = $stmt->get_result();
    $codeData = $result->fetch_assoc();

    if (!$codeData) {
        echo json_encode(['success' => false, 'message' => 'Invalid code for this course']);
        exit;
    }

    // Validate code status
    if ($codeData['is_used']) {
        echo json_encode(['success' => false, 'message' => 'Code has already been used']);
        exit;
    }

    if (strtotime($codeData['expiry_date']) < time()) {
        echo json_encode(['success' => false, 'message' => 'Code has expired']);
        exit;
    }

    // Get course price
    $courseStmt = $db->prepare("SELECT price FROM courses WHERE id = ?");
    $courseStmt->bind_param("i", $courseId);
    $courseStmt->execute();
    $course = $courseStmt->get_result()->fetch_assoc();

    if (!$course) {
        throw new Exception("Course not found");
    }

    // Calculate final price
    $finalPrice = max($course['price'] - $codeData['discount_amount'], 0);

    // Return success response
    echo json_encode([
        'success' => true,
        'message' => 'Redeem code is valid',
        'discount' => $codeData['discount_amount'],
        'original_price' => $course['price'],
        'final_price' => $finalPrice,
        'expiry_date' => $codeData['expiry_date'],
        'redeem_id' => $codeData['id']
        
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}