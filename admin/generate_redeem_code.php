<?php
// Start session
session_start();

// Include database configuration
require '../config.php';  // Adjust path to config.php if necessary

if (!isset($_SESSION['admin_id']) || !$_SESSION['admin_username'] ) {
    // If the user is not logged in or not an admin, redirect
    header('Location: login.php');
    exit;
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $course_id = intval($_POST['course_id']);
    $discount_amount = floatval($_POST['discount_amount']);
    $expiry_date = $_POST['expiry_date']; // Format: YYYY-MM-DD

    // Generate a random redeem code
    $redeem_code = strtoupper(bin2hex(random_bytes(5))); // 10-character random code

    // Prepare the SQL statement to insert the redeem code into the database
    $query = "INSERT INTO redeem_codes (code, course_id, discount_amount, expiry_date) 
              VALUES ('$redeem_code', '$course_id', '$discount_amount', '$expiry_date')";

    // Execute the query
    if (mysqli_query($db, $query)) {
        // Successfully inserted
        echo "Redeem code generated successfully: $redeem_code";
    } else {
        // Error executing the query
        echo "Error generating redeem code: " . mysqli_error($db);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Redeem Code</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container">
    <h1 class="mt-5">Generate Redeem Code</h1>
    
    <form action="generate_redeem_code.php" method="POST" class="mt-4">
        <div class="mb-3">
            <label for="course_id" class="form-label">Course</label>
            <select name="course_id" id="course_id" class="form-control" required>
                <option value="">Select Course</option>
                <?php
                    // Fetch courses from the database
                    $courseQuery = "SELECT id, title FROM courses WHERE is_active = 1";
                    $courseResult = mysqli_query($db, $courseQuery);

                    if (mysqli_num_rows($courseResult) > 0) {
                        while ($course = mysqli_fetch_assoc($courseResult)) {
                            echo "<option value='" . $course['id'] . "'>" . $course['title'] . "</option>";
                        }
                    }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="discount_amount" class="form-label">Discount Amount</label>
            <input type="number" name="discount_amount" id="discount_amount" class="form-control" step="0.01" required>
        </div>

        <div class="mb-3">
            <label for="expiry_date" class="form-label">Expiry Date</label>
            <input type="date" name="expiry_date" id="expiry_date" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Generate Redeem Code</button>
    </form>
</div>

</body>
</html>
