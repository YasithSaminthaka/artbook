<?php require 'URI.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up | ArtBook</title>
    <style>
        :root {
            --primary-color: #6C63FF;
            --primary-hover: #5a52e0;
            --text-color: #2D3748;
            --light-gray: #F7FAFC;
            --white: #FFFFFF;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: var(--light-gray);
            color: var(--text-color);
            margin: 0;
            padding: 20px;
        }

        .container {
            background-color: var(--white);
            padding: 40px;
            border-radius: 12px;
            box-shadow: var(--shadow);
            text-align: center;
            max-width: 400px;
            width: 100%;
        }

        .logo {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        h1 {
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .subtitle {
            color: #718096;
            margin-bottom: 30px;
            font-size: 14px;
        }

        .google-btn {
            background: var(--white);
            color: var(--text-color);
            border: 1px solid #E2E8F0;
            padding: 12px 24px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            width: 100%;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .google-btn:hover {
            background: #F8FAFC;
            border-color: #CBD5E0;
            transform: translateY(-1px);
        }

        .google-icon {
            width: 18px;
            height: 18px;
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 24px 0;
            color: #A0AEC0;
            font-size: 12px;
        }

        .divider::before, .divider::after {
            content: "";
            flex: 1;
            border-bottom: 1px solid #E2E8F0;
        }

        .divider::before {
            margin-right: 16px;
        }

        .divider::after {
            margin-left: 16px;
        }

        .footer-text {
            margin-top: 24px;
            font-size: 12px;
            color: #718096;
        }

        .footer-text a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">ArtBook</div>
        <h1>Sign with google</h1>
        <p class="subtitle">Join our creative community and showcase your artwork</p>
        
        <button class="google-btn" onclick="signInWithGoogle()">
            <img class="google-icon" src="https://www.google.com/images/branding/googleg/1x/googleg_standard_color_128dp.png" alt="Google Icon">
            Continue with Google
        </button>

    </div>

    <script>
        function signInWithGoogle() {
            // Redirect the user to the Google OAuth URL generated on the backend
            window.location.href = `${SITE_URL}/api/google_login.php`;
        }
    </script>
</body>
</html>