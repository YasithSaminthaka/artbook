<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once 'config.php';
require 'URI.php';
session_start();

// Configuration
$clientId = GOOGLE_CLIENT_ID;
$clientSecret = GOOGLE_CLIENT_SECRET;
$redirectUri = GOOGLE_REDIRECT_URI; // Must match Google Cloud Console
$folderId = '15_FyfTq0kKDk0In9Sj-dLxMF7stWdp0F';

// Initialize Google Client
$client = new Google\Client();
$client->setClientId($clientId);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope(Google\Service\Drive::DRIVE);
$client->setAccessType('offline');
$client->setPrompt('select_account consent');

// Step 1: Check for authorization code
if (isset($_GET['code'])) {
    // Exchange auth code for access token
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    
    if (!isset($token['error'])) {
        $_SESSION['access_token'] = $token;
        // Store refresh token if available (for long-term access)
        if (isset($token['refresh_token'])) {
            file_put_contents('refresh_token.txt', $token['refresh_token']);
        }
        header('Location: ' . $redirectUri);
        exit;
    } else {
        die("Error fetching access token: " . $token['error']);
    }
}

// Step 2: Check for existing access token
if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
    $client->setAccessToken($_SESSION['access_token']);
    
    // Refresh token if expired
    if ($client->isAccessTokenExpired()) {
        if (file_exists('refresh_token.txt')) {
            $refreshToken = file_get_contents('refresh_token.txt');
            $client->fetchAccessTokenWithRefreshToken($refreshToken);
            $_SESSION['access_token'] = $client->getAccessToken();
        } else {
            unset($_SESSION['access_token']);
            header('Location: ' . $client->createAuthUrl());
            exit;
        }
    }
    
    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email'])) {
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $role = 'writer'; // or 'reader', 'commenter'
        
        try {
            $drive = new Google\Service\Drive($client);
            $permission = new Google\Service\Drive\Permission([
                'type' => 'user',
                'role' => $role,
                'emailAddress' => $email
            ]);
            
            $result = $drive->permissions->create(
                $folderId,
                $permission,
                ['sendNotificationEmail' => true]
            );
            
            $success = "Successfully shared folder with $email";
        } catch (Exception $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
    
    // Display sharing form
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Share Google Drive Folder</title>
        <style>
            body { font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; }
            .form-group { margin-bottom: 15px; }
            label { display: block; margin-bottom: 5px; }
            input[type="email"] { width: 100%; padding: 8px; }
            button { padding: 8px 15px; background: #4285f4; color: white; border: none; cursor: pointer; }
            .success { color: green; }
            .error { color: red; }
        </style>
    </head>
    <body>
        <h2>Share Google Drive Folder</h2>
        <?php if (isset($success)): ?>
            <p class="success"><?= $success ?></p>
        <?php endif; ?>
        <?php if (isset($error)): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>
        <form method="post">
            <div class="form-group">
                <label for="email">Email Address:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="role">Access Level:</label>
                <select id="role" name="role">
                    <option value="reader">Can view</option>
                    <option value="commenter">Can comment</option>
                    <option value="writer" selected>Can edit</option>
                </select>
            </div>
            <button type="submit">Share Folder</button>
        </form>
        <p><a href="?logout">Logout</a></p>
    </body>
    </html>
    <?php
    
} elseif (isset($_GET['logout'])) {
    // Logout
    unset($_SESSION['access_token']);
    session_destroy();
    header('Location: ' . $redirectUri);
    exit;
} else {
    // Step 3: No access token - redirect to Google auth
    $authUrl = $client->createAuthUrl();
    header('Location: ' . $authUrl);
    exit;
}