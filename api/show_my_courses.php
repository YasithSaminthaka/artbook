<?php
session_start();
require_once '../config.php'; // This should contain your $db connection

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $db->prepare("
    SELECT p.course_id, c.title, c.description, p.payment_status
    FROM payments p
    JOIN courses c ON p.course_id = c.id
    WHERE p.user_id = ? AND (p.payment_status = 'pending' OR p.payment_status = 'completed')
");

if (!$stmt) {
    echo json_encode(['status' => 'error', 'message' => 'Query preparation failed: ' . $db->error]);
    exit;
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$courses = [];
while ($row = $result->fetch_assoc()) {
    $courses[] = $row;
}

echo json_encode([
    'status' => 'success',
    'courses' => $courses
]);

$stmt->close();
