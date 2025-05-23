<?php
require_once __DIR__ . '/vendor/autoload.php';
require 'URI.php';
session_start();

// Configuration - Replace these values
$clientId = '931940573200-urjl4lptad5si73q80i7vj14hakjmb89.apps.googleusercontent.com';
$clientSecret = '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';
$redirectUri = SITE_URL . '/api/course_redirect.php';
$specialFolderName = 'Courses'; // e.g., 'Project Files'
$defaultRole = 'writer'; // 'reader', 'writer', or 'commenter'

// Initialize Google Client
$client = new Google\Client();
$client->setClientId($clientId);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope(Google\Service\Drive::DRIVE);
$client->setAccessType('offline');
$client->setPrompt('select_account consent');

// Helper function to find special folder by name
function findSpecialFolder($driveService, $folderName) {
    $pageToken = null;
    do {
        $response = $driveService->files->listFiles([
            'q' => "mimeType='application/vnd.google-apps.folder' and name='$folderName' and trashed=false",
            'fields' => 'files(id,name)',
            'pageToken' => $pageToken
        ]);
        
        foreach ($response->files as $file) {
            if ($file->name === $folderName) {
                return $file->id;
            }
        }
        
        $pageToken = $response->pageToken;
    } while ($pageToken != null);
    
    return null;
}

// Handle OAuth callback
if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $_SESSION['access_token'] = $token;
    header('Location: ' . $redirectUri);
    exit;
}

// Handle logout
if (isset($_GET['logout'])) {
    unset($_SESSION['access_token']);
    session_destroy();
    header('Location: ' . $redirectUri);
    exit;
}

// Main application flow
if (isset($_SESSION['access_token'])) {
    $client->setAccessToken($_SESSION['access_token']);
    
    // Refresh token if expired
    if ($client->isAccessTokenExpired()) {
        $refreshToken = $client->getRefreshToken();
        if ($refreshToken) {
            $client->fetchAccessTokenWithRefreshToken($refreshToken);
            $_SESSION['access_token'] = $client->getAccessToken();
        } else {
            unset($_SESSION['access_token']);
            header('Location: ' . $client->createAuthUrl());
            exit;
        }
    }
    
    $drive = new Google\Service\Drive($client);
    
    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email'])) {
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $role = $_POST['role'] ?? $defaultRole;
        
        try {
            // Find the special folder
            $folderId = findSpecialFolder($drive, $specialFolderName);
            
            if (!$folderId) {
                throw new Exception("Special folder '$specialFolderName' not found");
            }
            
            // Share the folder
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
            
            $success = "Successfully shared '$specialFolderName' folder with $email ($role access)";
        } catch (Exception $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
    
    // Display the sharing form
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Share Google Drive Folder</title>
        <style>
            body { font-family: Arial, sans-serif; max-width: 600px; margin: 20px auto; padding: 20px; }
            .form-group { margin-bottom: 15px; }
            label { display: block; margin-bottom: 5px; font-weight: bold; }
            input[type="email"], select { width: 100%; padding: 8px; box-sizing: border-box; }
            button { background: #4285f4; color: white; border: none; padding: 10px 15px; cursor: pointer; }
            .success { color: green; margin: 15px 0; }
            .error { color: red; margin: 15px 0; }
            .info { background: #f8f9fa; padding: 15px; margin: 15px 0; border-radius: 4px; }
        </style>
    </head>
    <body>
        <h2>Share Special Folder: <?= htmlspecialchars($specialFolderName) ?></h2>
        
        <?php if (isset($success)): ?>
            <div class="success"><?= $success ?></div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>
        
        <div class="info">
            <p>You're sharing folder: <strong><?= htmlspecialchars($specialFolderName) ?></strong></p>
            <p>Logged in as: <?= $client->getAccessToken()['email'] ?? 'Unknown' ?></p>
        </div>
        
        <form method="post">
            <div class="form-group">
                <label for="email">Email Address to Share With:</label>
                <input type="email" id="email" name="email" required placeholder="user@example.com">
            </div>
            
            <div class="form-group">
                <label for="role">Access Level:</label>
                <select id="role" name="role">
                    <option value="reader">Can view only</option>
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
} else {
    // Not authenticated - show login button
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Google Drive Folder Sharing</title>
        <style>
            body { font-family: Arial, sans-serif; text-align: center; margin-top: 50px; }
            .login-btn { background: #4285f4; color: white; padding: 12px 24px; 
                        text-decoration: none; border-radius: 4px; display: inline-block; }
        </style>
    </head>
    <body>
        <h2>Share Special Google Drive Folder</h2>
        <p>You need to authenticate with Google to continue</p>
        <a href="<?= $client->createAuthUrl() ?>" class="login-btn">Login with Google</a>
    </body>
    </html>
    <?php
}