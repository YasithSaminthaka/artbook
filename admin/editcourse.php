<?php
require '../config.php';
if (!isset($_SESSION['admin_id']) || !$_SESSION['admin_username'] ) {
    // If the user is not logged in or not an admin, redirect
    header('Location: login.php');
    exit;
}
// Check course ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid Course ID.");
}
$courseId = (int) $_GET['id'];

// Fetch existing course data
$stmt = $db->prepare("SELECT * FROM courses WHERE id = ?");
$stmt->bind_param("i", $courseId);
$stmt->execute();
$result = $stmt->get_result();
$course = $result->fetch_assoc();

if (!$course) {
    die("Course not found.");
}

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $discount = $_POST['discount'];
    $thumbnail = $_POST['thumbnail'];
    $course_url = $_POST['course_url'];
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    $updateStmt = $db->prepare("UPDATE courses SET title = ?, description = ?, price = ?, discount = ?, thumbnail = ?, course_url = ?, is_active = ? WHERE id = ?");
    $updateStmt->bind_param("ssddssii", $title, $description, $price, $discount, $thumbnail, $course_url, $is_active, $courseId);

    if ($updateStmt->execute()) {
        header("Location: manage_courses.php?success=1");
        exit;
    } else {
        echo "<div class='alert alert-danger'>Update failed. Please try again.</div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Course</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="mb-4">Edit Course</h2>

    <form method="POST" class="bg-white p-4 shadow rounded">
        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($course['title']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="5"><?= htmlspecialchars($course['description']) ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Price (LKR)</label>
            <input type="number" name="price" step="0.01" class="form-control" value="<?= $course['price'] ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Discount (LKR)</label>
            <input type="number" name="discount" step="0.01" class="form-control" value="<?= $course['discount'] ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Thumbnail URL</label>
            <input type="text" name="thumbnail" class="form-control" value="<?= htmlspecialchars($course['thumbnail']) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Course URL</label>
            <input type="text" name="course_url" class="form-control" value="<?= htmlspecialchars($course['course_url']) ?>">
        </div>

        <div class="form-check mb-4">
            <input type="checkbox" name="is_active" class="form-check-input" id="is_active" <?= $course['is_active'] ? 'checked' : '' ?>>
            <label class="form-check-label" for="is_active">Active</label>
        </div>

        <button type="submit" class="btn btn-primary">Update Course</button>
        <a href="manage_courses.php" class="btn btn-secondary ms-2">Cancel</a>
    </form>
</div>
</body>
</html>
