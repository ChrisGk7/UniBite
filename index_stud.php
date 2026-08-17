<?php

session_start();

require_once "DataBase_stud.php";

if(!isset($_SESSION['username'])){

    header("Location: index.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Food Ordering</title>
    <link rel="stylesheet" href="style_stud.css">
    <link
    rel="stylesheet"
    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    />
</head>

<body>

    
    <!-- Top navbar -->
    <header class="top-header">

        <nav class="top-navbar">

        <a href="index.php" class="logo" data-text="UniBite">
            <span class="actual-text">&nbsp;UniBite&nbsp;</span>
            <span aria-hidden="true" class="hover-text">&nbsp;UniBite&nbsp;</span>
        </a>

            <div class="top-nav-actions">

            <!-- Welcome message -->
            <span class="welcome-message">
                Welcome back, 
                <strong><?php echo htmlspecialchars($_SESSION['name']); ?></strong> 👋
            </span>


            <a href="#">
                Contact
            </a>

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
                    <a href="#">Home</a>
                </li>

                <li>
                    <a href="#">Menu</a>
                </li>

                <li>
                    <a href="#">Restaurants</a>
                </li>

                <li>
                    <a href="#">About</a>
                </li>

            </ul>

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
                placeholder="Search for food..."
                class="search-input"
            >

            <button type="button" class="search-button">
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


<!-- Food categories -->
<section class="categories-section">

    <h2>Categories</h2>

    <div class="categories-container">

        <!-- Pizza -->
        <div class="category-item">
            <div class="category-box">
                🍕
            </div>
            <p>Pizza</p>
        </div>

        <!-- Burgers -->
        <div class="category-item">
            <div class="category-box">
                🍔
            </div>
            <p>Burgers</p>
        </div>

        <!-- Sushi -->
        <div class="category-item">
            <div class="category-box">
                🍣
            </div>
            <p>Sushi</p>
        </div>

        <!-- Pasta -->
        <div class="category-item">
            <div class="category-box">
                🍝
            </div>
            <p>Pasta</p>
        </div>

        <!-- Salads -->
        <div class="category-item">
            <div class="category-box">
                🥗
            </div>
            <p>Salads</p>
        </div>

        <!-- Desserts -->
        <div class="category-item">
            <div class="category-box">
                🍰
            </div>
            <p>Desserts</p>
        </div>

    </div>

</section>


<!-- Popular dishes -->
<section class="popular-section">

    <div class="popular-header">
        <h2>Available Dishes</h2>
    </div>

    <div class="food-grid" id="food-grid">
        <!-- JavaScript will create the dish cards here -->
    </div>

</section>
<!-- Map -->
<section class="map-section">

    <div class="map-header">
        <h2>Find food near you</h2>
        <p>See where available dishes can be picked up.</p>
    </div>

    <div id="feed-map"></div>

</section>

</main>
<!-- Footer-->
<footer class="footer">

    <div class="footer-container">

        <div class="footer-top">

            <a href="#" class="footer-brand">
                UniBite
            </a>

            <ul class="footer-links">
                <li>
                    <a href="#">About</a>
                </li>

                <li>
                    <a href="#">Privacy Policy</a>
                </li>

                <li>
                    <a href="#">Contact</a>
                </li>
            </ul>

        </div>

        <hr class="footer-divider">

        <p class="footer-copyright">
            © 2026 UniBite. All Rights Reserved.
        </p>

    </div>

</footer>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script src="map.js"></script>

<script src="script_stud.js"></script>

</body>

</html>