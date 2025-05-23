<?php
require '../config.php';
if (!isset($_SESSION['admin_id']) || !$_SESSION['admin_username'] ) {
    // If the user is not logged in or not an admin, redirect
    header('Location: login.php');
    exit;
}

$searchTerm = '';
if (isset($_GET['search'])) {
    $searchTerm = $_GET['search'];
}

// Fetch students based on search term
$query = "SELECT id, google_id, name, email, picture, locale FROM users WHERE name LIKE ? OR email LIKE ? ORDER BY id DESC";
$stmt = $db->prepare($query);
$searchTermLike = "%" . $searchTerm . "%";
$stmt->bind_param("ss", $searchTermLike, $searchTermLike);
$stmt->execute();
$result = $stmt->get_result();
$students = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Students</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .profile-img {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 50%;
        }
        .search-bar {
            margin-bottom: 20px;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container my-5">
        <h2 class="mb-4">All Registered Students</h2>

        <!-- Search Form -->
        <form method="get" class="search-bar">
            <div class="input-group">
                <input type="text" class="form-control" name="search" placeholder="Search by Name or Email" value="<?= htmlspecialchars($searchTerm) ?>">
                <button class="btn btn-primary" type="submit">Search</button>
            </div>
        </form>

        <div class="table-responsive shadow bg-white p-3 rounded">
            <table class="table table-striped table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <!-- <th>Picture</th> -->
                        <th>Name</th>
                        <th>Email</th>
                        <th>Google ID</th>
                        <!-- <th>Locale</th> -->
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td><?= htmlspecialchars($student['id']) ?></td>
                            <!-- <td><img src="<?= htmlspecialchars($student['picture']) ?>" class="profile-img" alt="Profile"></td> -->
                            <td><?= htmlspecialchars($student['name']) ?></td>
                            <td><?= htmlspecialchars($student['email']) ?></td>
                            <td><?= htmlspecialchars($student['google_id']) ?></td>
                            <!-- <td><?= htmlspecialchars($student['locale']) ?></td> -->
                        </tr>
                    <?php endforeach; ?>
                    <?php if (count($students) === 0): ?>
                        <tr><td colspan="6" class="text-center">No students found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
