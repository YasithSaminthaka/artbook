<?php
$targetDir = "uploads/";
if (!file_exists($targetDir)) {
    mkdir($targetDir, 0777, true);
}

$allowedTypes = ['pdf', 'jpg', 'jpeg', 'png'];
$maxSize = 1 * 1024 * 1024; // 1MB

if (isset($_FILES['slip']) && $_FILES['slip']['error'] === UPLOAD_ERR_OK) {
    $fileTmp = $_FILES['slip']['tmp_name'];
    $fileName = basename($_FILES['slip']['name']);
    $fileSize = $_FILES['slip']['size'];
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    // Check file type and size
    if (!in_array($fileExt, $allowedTypes)) {
        //echo "Invalid file type. Only PDF, JPG, JPEG, PNG allowed.";
        echo json_encode(["status" => "false", "message" => "Invalid file type. Only PDF, JPG, JPEG, PNG allowed."]);
        exit;
    }

    if ($fileSize > $maxSize) {
        echo "File too large. Max size is 1MB.";
        exit;
    }

    // Save file with unique name
    $newName = uniqid("slip_", true) . '.' . $fileExt;
    $targetFile = $targetDir . $newName;

    if (move_uploaded_file($fileTmp, $targetFile)) {
       // echo "Slip uploaded successfully: " . htmlspecialchars($newName);
        echo json_encode(["status" => "success", "message" => "Slip uploaded successfully: " , "receipt_url" => htmlspecialchars($newName)]);
    } else {
        echo "Failed to upload slip.";
    }
} else {
    echo "No file uploaded or upload error.";
}
?>
