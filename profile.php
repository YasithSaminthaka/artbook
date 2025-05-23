<?php session_start(); require 'URI.php';?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Profile - ArtBook</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

  <style>
    :root {
            --primary-color: #6c63ff;
            --secondary-color: #4d44db;
            --accent-color: #ff6584;
            --light-bg: #f8f9fa;
            --dark-bg: #212529;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light-bg);
            color: #333;
        }

        .hero-section {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 5rem 0;
            margin-bottom: 3rem;
            border-radius: 0 0 20px 20px;
        }

        .feature-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            border-radius: 15px;
            overflow: hidden;
            margin-bottom: 20px;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .feature-icon {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .btn-accent {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
            color: white;
        }

        .navbar {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--primary-color) !important;
        }

    .profile-card {
      background-color: #fff;
      border-radius: 15px;
      padding: 2rem;
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
      margin-top: 50px;
    }

    .profile-title {
      font-size: 1.6rem;
      font-weight: 600;
      margin-bottom: 1rem;
    }

    .profile-info label {
      font-weight: 500;
      color: #555;
    }

    .logout-btn {
      background-color: #dc3545;
      border: none;
    }

    .logout-btn:hover {
      background-color: #c82333;
    }

    .navbar-brand {
      color: #6c63ff !important;
      font-weight: bold;
    }
  </style>
</head>
<body>


<?php require 'header.php'; ?>
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="profile-card">
        <h2 class="profile-title"><i class="bi bi-person-circle me-2"></i>My Profile</h2>
        <div class="profile-info">
          <p><label>User ID:</label> <span id="user-id">Loading...</span></p>
          <p><label>Name:</label> <span id="user-name">Loading...</span></p>
          <p><label>Email:</label> <span id="user-email">Loading...</span></p>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  window.addEventListener('DOMContentLoaded', () => {
    fetch(`${SITE_URL}/api/show_user.php`)
      .then(res => res.json())
      .then(data => {
        if (data.status === "success") {
          document.getElementById("user-id").textContent = data.user.id;
          document.getElementById("user-name").textContent = data.user.name;
          document.getElementById("user-email").textContent = data.user.email;
        } else {
          alert("Error loading profile.");
        }
      })
      .catch(() => {
        alert("Failed to load profile data.");
      });
  });
</script>

</body>
</html>
