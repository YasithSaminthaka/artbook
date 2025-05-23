<?php
// Database configuration
define('DB_HOST', 'localhost:3306');
define('DB_USER', 'artbooks_artbooks');
define('DB_PASS', 'Password@Yasith');
define('DB_NAME', 'artbooks_courses');
// define('SITE_URL', 'http://localhost/artbook');

// Google OAuth configuration
define('GOOGLE_CLIENT_ID', '931940573200-6tvmqqqul9v2a1v30ssm6us5eh7v7sms.apps.googleusercontent.com');
define('GOOGLE_CLIENT_SECRET', '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');
define('GOOGLE_REDIRECT_URI', 'https://artbooks.lk/api/redirect.php');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Autoload composer dependencies
require_once __DIR__ . '/vendor/autoload.php';

// Initialize Google Client


// Start session

$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($db->connect_error) {
    die("Database connection failed: " . $db->connect_error);
}
// Database connection

?>