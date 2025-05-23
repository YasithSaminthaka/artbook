<?php session_start();require 'URI.php';?>
<!DOCTYPE html>
<html>
<head>
    <title>Course Details</title>
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

   
        .course-details-container {
            background-color: var(--white);
            border-radius: 12px;
            padding: 30px;
            box-shadow: var(--shadow);
            margin-bottom: 20px;
        }

        .course-thumbnail {
            width: 100%;
            max-width: 300px;
            height: auto;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: var(--shadow);
        }

        .course-title {
            font-size: 1.8em;
            margin-top: 0;
            margin-bottom: 15px;
            color: var(--primary-color);
            font-weight: 600;
        }

        .course-description {
            font-size: 1.1em;
            color: var(--text-color);
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .course-details {
            line-height: 1.6;
            color: var(--text-color);
            margin-bottom: 20px;
        }

        .course-price {
            font-size: 1.4em;
            font-weight: bold;
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .course-discount {
            font-size: 1em;
            color: #888;
            margin-bottom: 15px;
            display: inline-block;
            background: #f8f9fa;
            padding: 5px 10px;
            border-radius: 4px;
        }

        .back-to-courses {
            display: inline-flex;
            align-items: center;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: var(--white);
            color: var(--primary-color);
            border: 1px solid var(--primary-color);
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .back-to-courses:hover {
            background-color: var(--primary-color);
            color: white;
            transform: translateY(-2px);
        }

        .access-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 20px 0;
            padding: 12px 24px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1em;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            width: 100%;
            max-width: 300px;
        }

        .access-button:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: var(--shadow);
        }

        .access-button:disabled {
            background-color: #ccc;
            cursor: not-allowed;
            transform: none !important;
            box-shadow: none !important;
        }

        .payment-status {
            margin: 20px 0;
            padding: 15px;
            border-radius: 8px;
            display: none;
            border-left: 4px solid transparent;
        }

        .paid-status {
            background-color: rgba(40, 167, 69, 0.1);
            border-left-color: #28a745;
            color: #155724;
        }

        .not-paid-status {
            background-color: rgba(220, 53, 69, 0.1);
            border-left-color: #dc3545;
            color: #721c24;
        }

        .button-container {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            margin-top: 30px;
        }

        @media (max-width: 768px) {
            .course-details-container {
                padding: 20px;
            }
            
            .course-title {
                font-size: 1.5em;
            }
            
            .button-container {
                flex-direction: column;
            }
            
            .access-button, .back-to-courses {
                width: 100%;
                max-width: none;
            }
        }
        
    </style>
</head>
<body>
<?php require 'header.php'; ?>
    <div class="container">
        <div id="course-details-container"></div>
        <div id="payment-status-container"></div>
        <div class="button-container">
            <button id="access-course-btn" class="access-button" >
                Enroll Now
            </button>
            <a href="http://localhost/artbook/courses.php" class="back-to-courses">
                Back to Courses
            </a>
        </div>
    </div>

    <script>
        
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const courseId = urlParams.get('id');
            const accessBtn = document.getElementById('access-course-btn');
            const paymentStatusContainer = document.getElementById('payment-status-container');

            if (courseId) {
                fetch(`${SITE_URL}/api/course_details.php?id=${courseId}`)
                    .then(response => response.json())
                    .then(course => {
                        displayCourseDetails(course);
                        accessBtn.disabled = false;
                    })
                    .catch(error => console.error('Error fetching course details:', error));
            } else {
                document.getElementById('course-details-container').innerHTML = '<p>Invalid course ID.</p>';
            }

            accessBtn.addEventListener('click', function() {
                checkPaymentStatus();
            });

            function checkPaymentStatus() {
                window.location.href = `payment_selection.php?id=${courseId}`;
            }

            function displayCourseDetails(course) {
                const container = document.getElementById('course-details-container');
                let html = `
                    <div class="course-details-container">
                       ${course.thumbnail ? 
                `<img src="${SITE_URL}/api/thumbnails/${course.thumbnail}" alt="${course.title} Thumbnail" class="course-thumbnail">` 
                : ''}
                        <h1 class="course-title">${course.title}</h1>
                        <p class="course-description">${course.description}</p>
                        
                        ${course.price > 0 ? `<p class="course-price">Price: Rs.${course.price}</p>` : '<p class="course-price">Free</p>'}
                        ${course.discount !== null && course.discount > 0 ? `<span class="course-discount">${(course.discount )}% discount</span>` : ''}
                    </div>
                `;
                container.innerHTML = html;
            }
        });
    </script>
</body>
</html>