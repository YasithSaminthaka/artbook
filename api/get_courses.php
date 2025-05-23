<?php
// Assuming you have a database connection established in 'db.php'
require_once '../config.php';


$query = "SELECT id, title, description, is_active, thumbnail,price , discount FROM courses";
$result = $db->query($query);

$courses = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $courses[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($courses);

$result->free();
?>