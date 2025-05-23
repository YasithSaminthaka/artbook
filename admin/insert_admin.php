<?php
// Sample code to insert an admin user into the database

require '../config.php'; // Include your database connection

// Admin credentials
$username = 'yasith';
$password = password_hash('admin123', PASSWORD_BCRYPT); // Hash the password

// Insert query
$query = "INSERT INTO admins (username, password) VALUES ('$username', '$password')";
if (mysqli_query($db, $query)) {
    echo "Admin user created successfully.";
} else {
    echo "Error: " . mysqli_error($db);
}
?>
