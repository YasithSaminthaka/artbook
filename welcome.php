<?php
session_start();
require 'URI.php';
if (!isset($_SESSION['user'])) {
    header("Location: signup.php");
    exit();
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Welcome</title>
</head>
<body>
    <h1>Welcome, <?= htmlspecialchars($user['name']); ?>!</h1>
    <p>Email: <?= htmlspecialchars($user['email']); ?></p>
    <?php if (!empty($user['profile_pic'])): ?>
        <img src="<?= htmlspecialchars($user['profile_pic']); ?>" alt="Profile Picture">
    <?php endif; ?>
    <p>You have successfully signed up using Google.</p>
    <a href="logout.php">Logout</a>
</body>
</html>