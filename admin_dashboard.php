<?php
session_start();
require_once("database.php");
// include("header.html");

// Require a logged-in session
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Must be an admin to access this page
if (!is_admin($_SESSION['username'], $conn)) {
    header("Location: student.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel= "icon" type="image/png"  href="images/Unibite_icon.png">
    <link rel="stylesheet" href="style_stud.css">
</head>
<body>
    <header class="top-header">

        <nav class="top-navbar">

            <a href="admin_dashboard.php" class="logo" data-text="UniBite">
                <span class="actual-text">&nbsp;UniBite&nbsp;</span>
                <span aria-hidden="true" class="hover-text">
                    &nbsp;UniBite&nbsp;
                </span>
            </a>
        <h1>Admin Dashboard</h1>


            <div class="top-nav-actions">

                <div class="credit-display">
                    <span class="credit-number">5</span>
                    <span class="credit-label">Credits</span>
                </div>
             <button class="login-link" onclick="window.location.href='manage_admins.php'">Add an Admin</button> 
             <button class="login-link" onclick="window.location.href='index_stud.php.'">Student Dashboard</button> 
               
             <a href="logout.php" class="login-link">
                    Logout
                </a>

            </div>

        </nav>

    </header>
        <!-- Second navbar -->
        <nav class="secondary-navbar">

            <ul class="secondary-nav-links">

                <li>
                    <a href="#top">Home</a>
                </li>

                

                
                <li>
                    <a href="#contact">Contact</a>
                </li>

            </ul>

            <div class="secondary-welcome">
            Welcome back,
            <strong>
                <?php echo htmlspecialchars($_SESSION['name']); ?>
            </strong>
            👋
        </div>

        </nav>
      

<main class = "main-content">
<section class="welcome-section">
    
        

    <section class="container">
        
            <h2>Portions shared (last 30 days)</h2>
            <p id="statTotalPortions">Loading...</p>
        
    </section>

    <section class="container">
        <h2>Top Donor</h2>
        <p id="statTopDonor">Loading...</p>
    </section>

    <section class="container">
        <h2>Highest Rated Dishes</h2>
        <ul id="statTopRatedDishes"><li>Loading...</li></ul>
    </section>
     <button class="btn" onclick="window.location.href='manage_admins.php'">Add an Admin</button>
    <button class="btn" onclick="window.location.href='logout.php'">Logout</button>

    <script src="admin_dashboard.js" defer></script>
</section>

</main>
</body>
<!-- Footer-->
<footer class="footer">

    <div class="footer-container" id="contact">

        <div class="footer-content">

            <!-- UniBite -->
            <div class="footer-section footer-about">

                <a class="footer-brand">
                    <img
                        src="images/unibite_icon.png"
                        alt="UniBite logo"
                        class="footer-logo-image"
                    >

                    <span class="footer-logo-text">
                        <span class="footer-logo-uni">Uni</span><span class="footer-logo-bite">Bite</span>
                    </span>

                </a>

                <p class="footer-description">
                    Share food.<br>
                    Reduce waste.<br>
                    Connect students.
                    
                </p>

            </div>


            <!-- About -->
            <div class="footer-section footer-about-section">

                <h3>About UniBite</h3>

                <p>
                    UniBite connects students who have
                    extra food with those who need it.
                    Together, we build a stronger, more
                    caring community.
                </p>

            </div>


            <!-- Contact -->
            <div class="footer-section footer-contact-section" id="contact">

                <h3>Contact</h3>

                <div class="footer-contact">

                    <div class="contact-item">
                        <i data-lucide="mail"></i>
                        <span>contact@unibite.gr</span>
                    </div>

                    <div class="contact-item">
                        <i data-lucide="phone"></i>
                        <span>+30 2610 123456</span>
                    </div>

                    <div class="contact-item">
                        <i data-lucide="map-pin"></i>
                        <span>Patras, Greece</span>
                    </div>

                </div>

            </div>

        </div>


        <hr class="footer-divider">


        <div class="footer-bottom">

            <p class="footer-copyright">
                © 2026 UniBite. All Rights Reserved.
            </p>

            <p class="footer-message">
                Made for students
                <i data-lucide="graduation-cap"></i>
            </p>

        </div>

    </div>

</footer>
</html>
