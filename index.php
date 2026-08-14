<?php

require_once "db.php";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Food Ordering</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    
    <!-- Top navbar -->
    <header class="top-header">

        <nav class="top-navbar">

            <a href="#" class="brand">
                UniBite
            </a>

            <div class="top-nav-actions">

            <!-- Welcome message -->
            <span class="welcome-message">
                Welcome back, <strong>Alex</strong> 👋
            </span>


            <a href="#">
                Contact
            </a>

            <a href="#" class="login-link">
                Login
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

    </header>


    <!-- Main content -->
    <main class="main-content">

    <!-- Welcome section -->
    <section class="welcome-section">

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

</main>
<script src="script.js"></script>
</body>

</html>