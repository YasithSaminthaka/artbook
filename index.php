<?php
require_once 'config.php';
require 'api/vendor/autoload.php';
require 'URI.php';
// Session management
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ArtBook - Creative Learning Platform</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Roboto:wght@300;400;500;700&display=swap"
        rel="stylesheet">

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
            margin-bottom: 3rem;
            border-radius: 0 0 20px 20px;
            position: relative;
            overflow: hidden;
            padding: 0;
        }

        .image-slider-container {
            position: relative;
            width: 100%;
            height: 100vh;
            /* responsive height */
            max-height: 500px;
            overflow: hidden;
        }

        .image-slider {
            width: 100%;
            height: 100%;
            position: relative;
        }

        .slide {
            display: none;
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
        }

        

        .hero-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 10;
            text-align: center;
            padding: 1rem;
            color: white;
            text-shadow: 1px 1px 8px rgba(0, 0, 0, 0.6);
            width: 90%;
        }

        @media (max-width: 768px) {
            .image-slider-container {
                height: 40vh;
            }

            .hero-text h1 {
                font-size: 1.8rem;
            }

            .hero-text p {
                font-size: 1rem;
            }

            .btn {
                font-size: 0.9rem;
                padding: 0.6rem 1.2rem;
            }
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

        .bg-custom {
            background-color: #4d44db;
            /* deep blue */
            color: #ffffff;
            /* white text */
        }

        .btn-custom-accent {
            background-color: #facc15;
            /* yellow accent */
            color: #4d44db;
            border: none;
        }

        .btn-custom-accent:hover {
            background-color: #eab308;
            color: #ffffff;
        }
    </style>
</head>


<body>
    <!-- Navigation -->
    <?php require 'header.php';

    $slider_images = [
        'images/1.png',
        'images/2.png',
        'images/3.png'
    ]; ?>
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="image-slider-container">
            <div class="image-slider">
                <?php foreach ($slider_images as $img): ?>
                    <div class="slide"><img src="<?= $img ?>" alt="Art Course Slide"></div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="container text-center hero-text">
            <!-- <h1 class="display-4 fw-bold mb-4">Unlock Your Creative Potential</h1>
            <p class="lead mb-5">Discover our collection of art courses designed to help you grow as an artist</p> -->
            <a href="courses.php" class="btn btn-light btn-lg px-4 me-2">Browse Courses</a>
        </div>
    </section>

    <!-- Features Section -->
    <section class="container mb-5">
        <div class="row text-center mb-5">
            <div class="col">
                <h2 class="fw-bold">Why Choose ArtBook?</h2>
                <p class="text-muted">We provide the best learning experience for artists of all levels</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="card feature-card p-4 text-center h-100">
                    <div class="feature-icon">
                        <i class="bi bi-collection-play-fill"></i>
                    </div>
                    <h3>Comprehensive Courses</h3>
                    <p>Learn from industry professionals with our structured curriculum covering all art fundamentals.
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card feature-card p-4 text-center h-100">
                    <div class="feature-icon">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <h3>Community Support</h3>
                    <p>Join our vibrant community of artists to get feedback and share your progress.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card feature-card p-4 text-center h-100">
                    <div class="feature-icon">
                        <i class="bi bi-laptop"></i>
                    </div>
                    <h3>Anywhere Access</h3>
                    <p>Access your courses anytime, anywhere with our responsive platform.</p>
                </div>
            </div>
        </div>
    </section>



    <!-- Call to Action -->
    <section class="bg-custom py-5">
        <div class="container text-center">
            <h2 class="fw-bold mb-4">Ready to Start Your Artistic Journey?</h2>
            <p class="lead mb-4">Join thousands of artists who have improved their skills with ArtBook</p>
            <a href="courses.php" class="btn btn-custom-accent btn-lg px-4">Get Started for Free</a>
        </div>
    </section>


    <!-- Footer -->
    <footer class="bg-light py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <h5>ArtBook</h5>
                    <p>Empowering artists through quality education and community support.</p>
                    <div class="social-icons">
                        <a href="#" class="text-dark me-2"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-dark me-2"><i class="bi bi-twitter"></i></a>
                        <a href="#" class="text-dark me-2"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="text-dark me-2"><i class="bi bi-youtube"></i></a>
                    </div>
                </div>
                <div class="col-md-2 mb-3">
                    <h5>Links</h5>
                    <ul class="list-unstyled">
                        <!-- <li><a href="#" class="text-decoration-none text-dark">Home</a></li>
                        <li><a href="#" class="text-decoration-none text-dark">Courses</a></li>
                        <li><a href="#" class="text-decoration-none text-dark">About</a></li>
                        <li><a href="#" class="text-decoration-none text-dark">Contact</a></li> -->
                    </ul>
                </div>
                <div class="col-md-3 mb-3">
                    <h5>Legal</h5>
                    <ul class="list-unstyled">
                        <li><a href="terms.html" class="text-decoration-none text-dark">Terms of Service</a></li>
                        <li><a href="privacy_policy.html" class="text-decoration-none text-dark">Privacy Policy</a></li>
                        <li><a href="return_policy.html" class="text-decoration-none text-dark">Privacy Policy</a></li>
                    </ul>
                </div>
                <div class="col-md-3 mb-3">
                    <h5>Contact</h5>
                    <ul class="list-unstyled">
                        <li><i class="bi bi-envelope me-2"></i> contact@artbook.com</li>
                        <li><i class="bi bi-telephone me-2"></i> +94 78 601 4396</li>
                        <!-- <li><i class="bi bi-geo-alt me-2"></i> 123 Art Street, Creative City</li> -->
                    </ul>
                </div>
            </div>
            <hr>
            <div class="text-center">
                <p class="mb-0">&copy; 2025 ArtBook. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Your existing script -->
    <script>
        let currentSlide = 0;
        const slides = document.querySelectorAll('.slide');
        const totalSlides = slides.length;

        function showSlide(index) {
            slides.forEach((slide, i) => {
                slide.style.display = (i === index) ? 'block' : 'none';
            });
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % totalSlides;
            showSlide(currentSlide);
        }

        // Initialize
        showSlide(currentSlide);
        setInterval(nextSlide, 5000); // Change every 3 seconds
    </script>
</body>

</html>