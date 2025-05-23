<?php
session_start();
if (!isset($_SESSION['admin_id']) || !$_SESSION['admin_username'] ) {
    // If the user is not logged in or not an admin, redirect
    header('Location: login.php');
    exit;
}

require '../config.php'; // Make sure to include your database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $title = $_POST['course_title'];
    $description = $_POST['course_description'];
    $price = $_POST['course_price'];
    $discount = $_POST['course_discount'] ? $_POST['course_discount'] : null;  // Discount is optional
    $course_url = $_POST['course_url'] ? $_POST['course_url'] : null;  // Course URL is optional
    $is_active = $_POST['is_active']; // Active status (1 or 0)

    // Handle the image upload
    if (isset($_FILES['course_thumbnail']) && $_FILES['course_thumbnail']['error'] === 0) {
        $imageName = $_FILES['course_thumbnail']['name'];
        $imageTmpName = $_FILES['course_thumbnail']['tmp_name'];
        $imagePath = '../api/thumbnails/' . basename($imageName);

        // Check if file is a valid image (optional, you can expand this check)
        if (move_uploaded_file($imageTmpName, $imagePath)) {
            // Prepare the SQL query to insert the course
            $query = "INSERT INTO courses (title, description, price, discount, thumbnail, course_url, is_active) 
                      VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $db->prepare($query);

            // Execute the query
            if ($stmt->execute([$title, $description, $price, $discount, $imagePath, $course_url, $is_active])) {
                echo "Course added successfully.";
                header('Location: add_courses.php'); // Redirect to courses list or dashboard
                exit;
            } else {
                echo "Error: Unable to add course.";
            }
        } else {
            echo "Error uploading the image.";
        }
    } else {
        echo "Error: No image file was uploaded.";
    }
}
?>
