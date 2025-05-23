<?php
session_start();
require '../config.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    header("location: ../signup.php");
    //echo json_encode(['error' => 'User not authenticated']);
    exit;
}

$userId = $_SESSION['user_id'];
$courseId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($courseId <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid course ID']);
    exit;
}

// Check if payment exists
$stmt = $db->prepare("SELECT payment_status FROM payments WHERE user_id = ? AND course_id = ?");
$stmt->bind_param("ii", $userId, $courseId);
$stmt->execute();
$result = $stmt->get_result();
$payment = $result->fetch_assoc();
$stmt->close();
if ($payment && $payment['payment_status'] === 'completed') {
    
    $stmts = $db->prepare("SELECT course_url FROM courses WHERE id = ?");
    $stmts->bind_param("i", $courseId);
    $stmts->execute();
    $result = $stmts->get_result();
    
    if ($result->num_rows == 1) {
        $course = $result->fetch_assoc();
        //echo json_encode($course["course_url"]);
        $stmts->close();
        header("Location: {$course['course_url']}");
        exit;
    } else {
        http_response_code(404); // Not Found
        echo json_encode(['error' => 'Course not found']);
    }
    $stmts->close();
    //header("locatidon: ../drive_access.php");
   // echo json_encode(['access' => true]);
 } elseif ($payment && $payment['payment_status'] === 'pending') {
    // User has paid, but payment is not yet approved
    header("Location: ../payment_pending.php?id=$courseId");
    exit;

} else {
    // No payment found
    header("Location: ../show_course.php?id=$courseId");
    exit;
}
?>
