<?php

session_start();

require_once "database.php";

if(!isset($_SESSION['username'])){

    header("Location: index.php");
    exit();
}

$student_username = $_SESSION['username'];

$sql = "
    SELECT credits
    FROM student
    WHERE username = ?
";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "s",
    $student_username
);

mysqli_stmt_execute($stmt);

$result =
    mysqli_stmt_get_result($stmt);

$student_data =
    mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

$current_credits =
    $student_data
        ? (int)$student_data["credits"]
        : 0;

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>UniBite</title>
    <link rel="stylesheet" href="style_stud.css">
    <link rel="icon" type="image/png" href="images/Unibite_icon.png">
    <link
    rel="stylesheet"
    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    />
</head>

<body id="top">

    
    <!-- Top navbar -->
<header class="top-header">

<nav class="top-navbar">

    <a href="index_stud.php" class="logo" data-text="UniBite">
        <span class="actual-text">&nbsp;UniBite&nbsp;</span>
        <span aria-hidden="true" class="hover-text">
            &nbsp;UniBite&nbsp;
        </span>
    </a>


    <div class="top-nav-actions">

        <div class="credit-display">
            <span class="credit-number">
                <?php echo $current_credits; ?>
            </span>

            <span class="credit-label">
                Credits
            </span>
        </div>

        <button
            type="button"
            id="my-orders-button"
        >
            My Orders
        </button>

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
                    <a href="#dishes">Menu</a>
                </li>

                <li>
                    <a href="#map-section">Map</a>
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

    


    <!-- Main content -->
    <main class="main-content">

    <!-- Welcome section -->
    <section class="welcome-section">

    <div class="welcome-left">

        <h1>
            What do you want to eat?
        </h1>

        <div class="search-box">

            <input
                type="text"
                id="search-input"
                placeholder="Search for food..."
                class="search-input"
            >

            <button type="button" 
                    id="search-button"
                    class="search-button">
                    Search
            </button>

        </div>

    </div>

    <!-- Create new dish -->
    <div class="create-dish-card">

        <div class="create-dish-icon">
            🍳
        </div>

        <h3>
            Have food to share?
        </h3>

        <p>
            Share your extra food with another student.
        </p>

        <a href="cook.PHP" class="create-dish-button">
            + Create a Dish
        </a>

    </div>

</section>

<!-- Available Dishes -->
<section class="popular-section" id= "dishes">

    <div class="popular-header">
        <h2>Available Dishes</h2>

        <button
            type="button"
            id="near-me-button"
            class="near-me-button"
        >  
            <span></span> 
            ◎ Dishes near me 
        </button>

    </div>
    <div class="food-grid" id="food-grid">
        <!-- JavaScript will create the dish cards here -->
    </div>

</section>
<!-- Map -->
<section class="map-section" id="map-section">

    <div class="map-header">
        <h2>Find food near you</h2>
        <p>See where available dishes can be picked up.</p>
    </div>

    <div id="feed-map"></div>

</section>

</main>

<!-- Footer -->
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
            <div class="footer-section footer-contact-section">

                <h3>Contact</h3>

                <div class="footer-contact">

                    <div class="contact-item">

                        <img
                            src="images/mail_icon.png"
                            alt="Email"
                            class="contact-icon-image"
                        >

                        <span>
                            contact@unibite.gr
                        </span>

                    </div>


                    <div class="contact-item">

                        <img
                            src="images/phone_icon.png"
                            alt="Phone"
                            class="contact-icon-image"
                        >

                        <span>
                            +30 2610 123456
                        </span>

                    </div>


                    <div class="contact-item">

                        <img
                            src="images/location_icon.png"
                            alt="Location"
                            class="contact-icon-image"
                        >

                        <span>
                            Patras, Greece
                        </span>

                    </div>

                </div>

            </div>

        </div>


        <!-- Divider -->
        <hr class="footer-divider">


        <!-- Bottom -->
        <div class="footer-bottom">

            <p class="footer-copyright">
                © 2026 UniBite. All Rights Reserved.
            </p>


            <p class="footer-message">

                <span>
                    Made for students
                </span>

                <img
                    src="images/student_hat.png"
                    alt="Student"
                    class="footer-student-icon"
                >

            </p>

        </div>

    </div>

</footer>

<!-- My Orders Overlay -->
<div id="orders-overlay" class="orders-overlay"></div>

<!-- My Orders Drawer -->
<aside id="orders-drawer" class="orders-drawer">

    <div class="orders-drawer-header">

        <h2>My Orders</h2>

        <button
            type="button"
            id="close-orders"
            class="close-orders"
        >
            ✕
        </button>

    </div>


    <div id="orders-container" class="orders-container">

        <div class="orders-empty-state">
            <p>No accepted orders yet.</p>
        </div>

    </div>

</aside>

    <!-- Request Error Popup -->
<div id="request-error-overlay" class="request-error-overlay">

<div class="request-error-popup">

    <div class="request-error-icon">
        !
    </div>

    <h3>Request unavailable</h3>

    <p id="request-error-message">
        You do not have enough credits.
    </p>

    <button
        type="button"
        id="close-request-error"
        class="close-request-error"
    >
        Got it
    </button>

</div>

</div>





<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script src="map.js"></script>

<script>
    const currentUsername =
        <?php echo json_encode($_SESSION["username"]); ?>;
</script>


<script src="script_stud.js"></script>

</body>

</html>