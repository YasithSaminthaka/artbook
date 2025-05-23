<?php
require_once 'config.php';
require 'api/vendor/autoload.php';
require 'URI.php';
// Session management
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<script>
        document.addEventListener('DOMContentLoaded', function() {
            fetch(`${SITE_URL}/api/get_courses.php`)
                .then(response => response.json())
                .then(courses => {
                    displayCourses(courses);
                    setupSearch(courses);
                })
                .catch(error => {
                    console.error('Error fetching courses:', error);
                    showError();
                });
        });

        function displayCourses(courses) {
            const coursesListDiv = document.getElementById('courses-list');
            
            if (courses.length === 0) {
                coursesListDiv.innerHTML = `
                    <div class="no-results">
                        <i class="bi bi-book"></i>
                        <h3>No courses found</h3>
                        <p>We couldn't find any courses matching your criteria</p>
                    </div>
                `;
                return;
            }
            
            coursesListDiv.innerHTML = '';
            
            courses.forEach(course => {
                const priceDisplay = course.price > 0 ? 
                    `Rs.${course.price}` : 
                    '<span class="text-success">FREE</span>';
                
                const badgeClass = course.price > 0 ? 'badge-premium' : 'badge-free';
                const badgeText = course.price > 0 ? 'Premium' : 'Free';
                
                const courseDiv = document.createElement('div');
                courseDiv.classList.add('course-card');
                courseDiv.innerHTML = `
                    ${course.thumbnail ? 
                        `<img src="${SITE_URL}/api/thumbnails/${course.thumbnail}" 
                              alt="${course.title}" class="course-thumbnail">` : 
                        `<div style="height:180px; background:#eee; display:flex; align-items:center; justify-content:center;">
                            <i class="bi bi-image" style="font-size:3rem; color:#aaa;"></i>
                        </div>`}
                    <div class="course-body">
                        <span class="course-badge ${badgeClass}">${badgeText}</span>
                        <h3 class="course-title">${course.title}</h3>
                        <p class="course-description">${course.description}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="course-price">${priceDisplay}</span>
                            
                        </div>
                        <div><button class="btn btn-view" data-course-id="${course.id}">
                                View Course
                            </button>
                            </div>
                    </div>
                `;
                coursesListDiv.appendChild(courseDiv);
            });

            // Add event listeners to all buttons
            document.querySelectorAll('.btn-view').forEach(button => {
                button.addEventListener('click', function() {
                    const courseId = this.dataset.courseId;
                    window.location.href = `api/check_access.php?id=${courseId}`;
                });
            });
        }

        function setupSearch(courses) {
            const searchInput = document.getElementById('search-input');
            
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase().trim();
                
                if (searchTerm === '') {
                    displayCourses(courses);
                    return;
                }
                
                const filteredCourses = courses.filter(course => 
                    course.title.toLowerCase().includes(searchTerm) || 
                    course.description.toLowerCase().includes(searchTerm) ||
                    (course.tags && course.tags.toLowerCase().includes(searchTerm))
                );
                
                displayCourses(filteredCourses);
            });
        }

        function showError() {
            const coursesListDiv = document.getElementById('courses-list');
            coursesListDiv.innerHTML = `
                <div class="no-results">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <h3>Error loading courses</h3>
                    <p>Please try again later or contact support</p>
                </div>
            `;
        }
    </script>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ArtBook - Courses</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
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
        
        .page-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 3rem 0;
            margin-bottom: 3rem;
            border-radius: 0 0 20px 20px;
        }
        
        .search-container {
            max-width: 600px;
            margin: 0 auto 2rem;
            position: relative;
        }
        
        .search-input {
            padding: 12px 20px;
            border-radius: 50px;
            border: 2px solid #e9ecef;
            width: 100%;
            font-size: 1rem;
            transition: all 0.3s;
            padding-left: 45px;
        }
        
        .search-input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(108, 99, 255, 0.25);
        }
        
        .search-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }
        
        .courses-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
            padding: 0 15px;
        }
        
        .course-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            border: none;
        }
        
        .course-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .course-thumbnail {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }
        
        .course-body {
            padding: 1.5rem;
        }
        
        .course-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
            color: var(--dark-bg);
        }
        
        .course-description {
            color: #6c757d;
            margin-bottom: 1.25rem;
            font-size: 0.95rem;
        }
        
        .course-price {
            font-weight: 700;
            font-size: 1.1rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }
        
        .course-badge {
            font-size: 0.8rem;
            padding: 5px 10px;
            border-radius: 50px;
            font-weight: 500;
        }
        
        .badge-free {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .badge-premium {
            background-color: #dbeafe;
            color: #1e40af;
        }
        
        .btn-view {
            background-color: var(--primary-color);
            color: white;
            border-radius: 50px;
            padding: 8px 20px;
            font-weight: 500;
            border: none;
            width: 100%;
            transition: all 0.3s;
        }
        
        .btn-view:hover {
            background-color: var(--secondary-color);
            color: white;
        }
        
        .no-results {
            text-align: center;
            padding: 3rem;
            color: #6c757d;
            grid-column: 1 / -1;
        }
        
        .no-results i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: var(--primary-color);
        }
    </style>
</head>
<body>
    <?php require 'header.php'; ?>
    <!-- Main Content -->
    <main class="container">
        <!-- Search Bar -->
        <div class="search-container">
            <i class="bi bi-search search-icon"></i>
            <input type="text" id="search-input" class="search-input" placeholder="Search courses...">
        </div>
        
        <!-- Courses Grid -->
        <div class="courses-grid" id="courses-list">
            <!-- Courses will be loaded here dynamically -->
        </div>
    </main>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript -->
    
</body>
</html>