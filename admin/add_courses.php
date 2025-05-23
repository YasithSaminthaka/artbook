<?php
session_start();
if (!isset($_SESSION['admin_id']) || !$_SESSION['admin_username'] ) {
    // If the user is not logged in or not an admin, redirect
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Course</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Add a New Course</h2>
    <form action="add_course_backend.php" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="course_title" class="form-label">Course Title</label>
            <input type="text" class="form-control" id="course_title" name="course_title" required>
        </div>
        <div class="mb-3">
            <label for="course_description" class="form-label">Course Description</label>
            <textarea class="form-control" id="course_description" name="course_description" required></textarea>
        </div>
        <div class="mb-3">
            <label for="course_price" class="form-label">Price</label>
            <input type="number" class="form-control" id="course_price" name="course_price" step="0.01" required>
        </div>
        <div class="mb-3">
            <label for="course_discount" class="form-label">Discount</label>
            <input type="number" class="form-control" id="course_discount" name="course_discount" step="0.01">
        </div>
        <div class="mb-3">
            <label for="course_thumbnail" class="form-label">Course Thumbnail (Image)</label>
            <input type="file" class="form-control" id="course_thumbnail" name="course_thumbnail" accept="image/*" required>
        </div>
        <div class="mb-3">
            <label for="course_url" class="form-label">Course URL</label>
            <input type="url" class="form-control" id="course_url" name="course_url">
        </div>
        <div class="mb-3">
            <label for="is_active" class="form-label">Course Status</label>
            <select class="form-control" id="is_active" name="is_active">
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Add Course</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
