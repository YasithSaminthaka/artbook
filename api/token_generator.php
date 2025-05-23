<?php
require_once '../config.php';

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $user_id = isset($data['user_id']) ? intval($data['user_id']) : 0;

    if (!$user_id) {
        echo json_encode(['success' => false, 'message' => 'Missing user_id']);
        exit;
    }

    // Generate a 6-digit token
    $token = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
    $expiry = date('Y-m-d H:i:s', strtotime('+30 minutes'));

    // Delete existing token for the user
    $db->query("DELETE FROM tokens WHERE user_id = $user_id");

    // Insert new token
    $stmt = $db->prepare("INSERT INTO redeem_codes (user_id, code ) VALUES (?, ?)");
    $stmt->bind_param("is", $user_id, $token);

    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'token' => $token,
            'expiry' => $expiry
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to insert token']);
    }

    $stmt->close();
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Only POST requests are allowed']);
}

$db->close();
?>