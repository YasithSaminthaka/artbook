<?php
session_start();

if (!isset($_SESSION['admin_id']) || !$_SESSION['admin_username'] ) {
    // If the user is not logged in or not an admin, redirect
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .admin-dashboard {
            max-width: 1200px;
            margin: 0 auto;
        }

        .card {
            margin-bottom: 20px;
        }

        .card-title {
            font-weight: bold;
        }

        .btn {
            width: 100%;
        }

        .dashboard-section {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .dashboard-card {
            width: 250px;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container admin-dashboard my-5">
        <h2 class="text-center mb-5">Admin Dashboard</h2>

        <div class="dashboard-section">
            <!-- Manage Students Button -->
            <div class="card dashboard-card shadow">
                <div class="card-body">
                    <h5 class="card-title">Manage Students</h5>
                    <a href="all_students.php" class="btn btn-primary">View Students</a>
                </div>
            </div>

            <!-- Manage Courses Button -->
            <div class="card dashboard-card shadow">
                <div class="card-body">
                    <h5 class="card-title">Manage Courses</h5>
                    <a href="manage_courses.php" class="btn btn-primary">View & Manage Courses</a>
                </div>
            </div>

            <!-- Manage Payments Button -->
            <div class="card dashboard-card shadow">
                <div class="card-body">
                    <h5 class="card-title">Manage Payments</h5>
                    <a href="manage_payments.php" class="btn btn-primary">View & Manage Payments</a>
                </div>
            </div>

            <!-- Generate Redeem Codes Button -->
            <div class="card dashboard-card shadow">
                <div class="card-body">
                    <h5 class="card-title">Generate Redeem Codes</h5>
                    <a href="generate_redeem_code.php" class="btn btn-primary">Generate Redeem Code</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
