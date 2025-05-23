<?php
session_start();
require_once '../config.php'; // This should define $db as a MySQLi connection

header('Content-Type: application/json');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'User not logged in'
    ]);
    exit;
}

$user_id = $_SESSION['user_id'];

// Prepare and execute the query
$stmt = $db->prepare("SELECT id, name, email FROM users WHERE id = ?");
if (!$stmt) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Statement preparation failed: ' . $db->error
    ]);
    exit;
}

$stmt->bind_param("i", $user_id);
if ($stmt->execute()) {
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        echo json_encode([
            'status' => 'success',
            'user' => $user
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'User not found'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Query execution failed: ' . $stmt->error
    ]);
}

$stmt->close();
