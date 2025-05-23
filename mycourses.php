<?php session_start();require 'URI.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>My Courses - ArtBook</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Roboto:wght@300;400;500;700&display=swap"
        rel="stylesheet">

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

        .course-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            border-left: 5px solid #6c63ff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        }

        .course-status.pending {
            color: #ffc107;
        }

        .course-status.failed {
            color: #dc3545;
        }

        .filter-buttons .btn {
            margin-right: 10px;
        }

        .search-input {
            max-width: 400px;
        }
    </style>
</head>

<body>

    <?php require 'header.php'; ?>
    <div class="container mt-5">
        <h2 class="mb-4">My Courses</h2>

        <!-- Filter Buttons + Search -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
            <div class="filter-buttons mb-2">
                <button class="btn btn-outline-primary active" data-filter="all">All</button>
                <button class="btn btn-outline-warning" data-filter="pending">Pending</button>
                <button class="btn btn-outline-danger" data-filter="completed">Paid</button>
            </div>
            <input type="text" id="search" class="form-control search-input" placeholder="Search course title">
        </div>

        <div id="courses-container">
            <p>Loading...</p>
        </div>
    </div>
    <script>
        let allCourses = [];

        function displayCourses(filter = 'all', searchTerm = '') {
            const container = document.getElementById('courses-container');
            container.innerHTML = '';

            const filtered = allCourses.filter(course => {
                const matchesStatus = filter === 'all' || course.payment_status === filter;
                const matchesSearch = course.title.toLowerCase().includes(searchTerm.toLowerCase());
                return matchesStatus && matchesSearch;
            });

            if (filtered.length === 0) {
                container.innerHTML = '<p>No matching courses found.</p>';
                return;
            }

            filtered.forEach(course => {
                const card = document.createElement('div');
                card.className = 'course-card';
                card.style.cursor = 'pointer';

                card.addEventListener('click', () => {
                    window.location.href = `api/check_access.php?id=${course.course_id}`;
                });
                card.innerHTML = `
        <h5>${course.title}</h5>
        <p>${course.description}</p>
        <span class="course-status ${course.payment_status}">
          Payment Status: ${course.payment_status.charAt(0).toUpperCase() + course.payment_status.slice(1)}
        </span>
      `;
                container.appendChild(card);
            });
        }

        fetch(`${SITE_URL}/api/show_my_courses.php`)
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    allCourses = data.courses;
                    displayCourses();
                } else {
                    document.getElementById('courses-container').innerHTML = '<p>Error loading courses.</p>';
                }
            });

        // Filter Buttons
        document.querySelectorAll('.filter-buttons .btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.filter-buttons .btn').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                const filter = btn.getAttribute('data-filter');
                const searchTerm = document.getElementById('search').value;
                displayCourses(filter, searchTerm);
            });
        });

        // Search Input
        document.getElementById('search').addEventListener('input', (e) => {
            const filter = document.querySelector('.filter-buttons .btn.active').getAttribute('data-filter');
            displayCourses(filter, e.target.value);
        });
    </script>


</body>

</html>