<?php
require_once 'config.php';
require 'URI.php';
// Generate sample redeem codes
$courses = [
    1 => 'Web Development Fundamentals',
    2 => 'Advanced JavaScript',
    3 => 'Python for Beginners'
];

$codes = [];

try {
    $db->begin_transaction();

    foreach ($courses as $courseId => $courseName) {
        for ($i = 1; $i <= 5; $i++) {
            $code = 'CODE' . strtoupper(substr(md5(uniqid()), 0, 8));
            $discount = rand(5, 20); // Random discount between $5-$20
            $expiry = date('Y-m-d', strtotime('+3 months'));
            
            $stmt = $db->prepare("
                INSERT INTO redeem_codes 
                (code, course_id, discount_amount, expiry_date) 
                VALUES (?, ?, ?, ?)
            ");
            $stmt->bind_param("sids", $code, $courseId, $discount, $expiry);
            $stmt->execute();
            
            $codes[] = [
                'course' => $courseName,
                'code' => $code,
                'discount' => $discount,
                'expiry' => $expiry
            ];
        }
    }

    $db->commit();

    echo "<h1>Generated Redeem Codes</h1>";
    echo "<table border='1'><tr><th>Course</th><th>Code</th><th>Discount</th><th>Expiry</th></tr>";
    foreach ($codes as $code) {
        echo "<tr>";
        echo "<td>{$code['course']}</td>";
        echo "<td>{$code['code']}</td>";
        echo "<td>\${$code['discount']}</td>";
        echo "<td>{$code['expiry']}</td>";
        echo "</tr>";
    }
    echo "</table>";

} catch (Exception $e) {
    $db->rollback();
    echo "Error generating codes: " . $e->getMessage();
}