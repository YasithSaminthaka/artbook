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

if (!isset($data['user_id']) || !isset($data['course_id'])) {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Missing or invalid user_id or course_id in the request body']);
    exit();
}

$userId = intval($data['user_id']);
$courseId = intval($data['course_id']);


$query = "SELECT id, payment_date, payment_amount FROM user_payments WHERE user_id = ? AND course_id = ? AND payment_status = 'completed'";
$stmt = $db->prepare($query);
$stmt->bind_param("ii", $userId, $courseId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {

    $paymentInfo = $result->fetch_assoc();

    $query = "SELECT price FROM courses WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $courseId);
    $stmt->execute();
    $results = $stmt->get_result();

    if ($results->num_rows == 1) {
        $course = $results->fetch_assoc();
        if ($course['price'] <= $paymentInfo['payment_amount']) {
            $courseQuery = "SELECT drivelink FROM courses WHERE id = ?";
            $courseStmt = $db->prepare($courseQuery);
            $courseStmt->bind_param("i", $courseId);
            $courseStmt->execute();
            $courseResult = $courseStmt->get_result();


            if ($courseResult->num_rows == 1) {
                $courseInfo = $courseResult->fetch_assoc();
                echo json_encode(['paid' => true, 'payment_date' => $paymentInfo['payment_date'], 'drive_link' => $courseInfo['drivelink']]);
            } else {
                // This scenario should ideally not happen if course IDs are consistent
                echo json_encode(['paid' => true, 'payment_date' => $paymentInfo['payment_date'], 'error' => 'Course not found for the payment']);
            }
            $courseStmt->close();
        } else {
            echo json_encode(['paid' => false, 'payment_date' => $paymentInfo['payment_date'], 'error' => 'Low Payments']);

        }


    } else {

        echo json_encode(['paid' => false, 'payment_date' => $paymentInfo['payment_date'], 'error' => 'Error payment']);
    }










} else {
    echo json_encode(['paid' => false]);
}

$stmt->close();
?>