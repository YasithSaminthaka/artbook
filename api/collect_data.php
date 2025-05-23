<?php
require_once '../db.php';

// Set Content-Type to application/json
header('Content-Type: application/json');

// Enable CORS for cross-origin requests (adjust as needed for security)
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if ($data && isset($data['user_id']) && isset($data['api_data'])) {
        $userId = $db->real_escape_string($data['user_id']);
        $apiData = json_encode($data['api_data']); // Store API data as JSON

        $sql = "INSERT INTO collected_data (user_id, api_data, collected_at) VALUES (?, ?, NOW())";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("ss", $userId, $apiData);

        if ($stmt->execute()) {
            http_response_code(201); // Created
            echo json_encode(['message' => 'Data collected successfully']);
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(['error' => 'Failed to save data: ' . $stmt->error]);
        }

        $stmt->close();
    } else {
        http_response_code(400); // Bad Request
        echo json_encode(['error' => 'Invalid data format']);
    }
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Only POST requests are allowed']);
}
?>