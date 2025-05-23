<?php

require_once '../config.php';

require 'vendor/autoload.php'; // Ensure Composer autoloader is included


$client = new Google_Client(['client_id' => GOOGLE_CLIENT_ID]);
$client->setClientSecret(GOOGLE_CLIENT_SECRET);
$client->setRedirectUri(GOOGLE_REDIRECT_URI);

if (isset($_GET['code'])) {
    try {
        // Exchange authorization code for access token
        $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

        if (isset($token['error'])) {
            echo "Error fetching access token: " . htmlspecialchars($token['error'] . " - " . $token['error_description']);
            exit;
        }

        $client->setAccessToken($token['access_token']);

        // Get user information from Google
        $service = new Google_Service_Oauth2($client);
        $user = $service->userinfo->get();

        $googleId = $user->getId();
        $email = $user->getEmail();
        $name = $user->getName();
        $picture = $user->getPicture();
        $locale = $user->getLocale();
        $accessToken = $token['access_token'];

        // Database insertion
        $checkStmt = $db->prepare("SELECT id, name FROM users WHERE google_id = ? OR email = ?");
        $checkStmt->bind_param("ss", $googleId, $email);
        $checkStmt->execute();
        $result = $checkStmt->get_result();

        if ($result->num_rows > 0) {
            // User exists - log them in
            $user = $result->fetch_assoc();

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $email;
            // $_SESSION['access_token'] = $accessToken;
            echo "<br>Welcome back! Redirecting...";


        } else {


            $insertStmt = $db->prepare("INSERT INTO users (google_id, email, name,  created_at) VALUES (?, ?, ?, NOW())");
            $insertStmt->bind_param("sss", $googleId, $email, $name);

            if ($insertStmt->execute()) {
                
                echo json_encode(['user_id' => $user_id, 'message' => 'successed']);
                $user_id = $db->insert_id;
                // Optionally, set session and redirect
                $_SESSION['user_id'] = $user_id;  // Store the database ID, not Google ID
                $_SESSION['name'] = $name;        // Store the name directly
                $_SESSION['email'] = $email;
                // header('Location: ' . SITE_URL . '/profile.php');
                // exit;
            } else {

                echo json_encode(['user_id' =>  $_SESSION['user_id'], 'message' => 'exit user']);

            }

        }

    } catch (Google_Service_Exception $e) {
        echo 'Google Sign-in Error: ' . htmlspecialchars($e->getMessage());
    } catch (Exception $e) {

        echo 'Error during authentication: ' . htmlspecialchars($e->getMessage());
    }
} else {
    echo 'Authentication failed. No code received.';
}
?>