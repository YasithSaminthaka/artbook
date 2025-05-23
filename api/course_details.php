<?php
require_once '../config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Adjust for production to your specific domain

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Invalid course ID']);
    exit;
}

$courseId = intval($_GET['id']);


$query = "SELECT id, title, description,  thumbnail, price, discount FROM courses WHERE id = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("i", $courseId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $course = $result->fetch_assoc();
    echo json_encode($course);
} else {
    http_response_code(404); // Not Found
    echo json_encode(['error' => 'Course not found']);
}

$stmt->close();
?>