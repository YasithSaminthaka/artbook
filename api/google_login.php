<?php
require_once '../config.php';
require 'vendor/autoload.php'; // Include Composer autoloader

$client = new Google_Client(['client_id' => GOOGLE_CLIENT_ID]);
$client->setRedirectUri(GOOGLE_REDIRECT_URI);
$client->addScope('email');
$client->addScope('profile');

$authUrl = $client->createAuthUrl();
header('Location: ' . $authUrl);
exit;
?>