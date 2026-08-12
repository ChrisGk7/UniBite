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
        <h2>Popular Dishes</h2>

        <a href="#" class="view-all">
            View all
        </a>
    </div>


    <div class="food-grid">

        <!-- Food Card 1 -->
        <div class="food-card">

            <div class="food-card-image">
                🍔
            </div>

            <div class="food-card-content">

                <div class="food-card-top">

                    <div>
                        <h3>Classic Burger</h3>
                        <p>Burger · Fast Food</p>
                    </div>

                    <span class="rating">
                        ★ 4.8
                    </span>

                </div>

                <div class="food-card-bottom">

                    <span class="price">
                        €8.50
                    </span>

                    <button class="add-button">
                        Add to cart
                    </button>

                </div>

            </div>

        </div>


        <!-- Food Card 2 -->
        <div class="food-card">

            <div class="food-card-image">
                🍕
            </div>

            <div class="food-card-content">

                <div class="food-card-top">

                    <div>
                        <h3>Margherita Pizza</h3>
                        <p>Pizza · Italian</p>
                    </div>

                    <span class="rating">
                        ★ 4.9
                    </span>

                </div>

                <div class="food-card-bottom">

                    <span class="price">
                        €10.00
                    </span>

                    <button class="add-button">
                        Add to cart
                    </button>

                </div>

            </div>

        </div>


        <!-- Food Card 3 -->
        <div class="food-card">

            <div class="food-card-image">
                🍣
            </div>

            <div class="food-card-content">

                <div class="food-card-top">

                    <div>
                        <h3>Sushi Box</h3>
                        <p>Sushi · Japanese</p>
                    </div>

                    <span class="rating">
                        ★ 4.7
                    </span>

                </div>

                <div class="food-card-bottom">

                    <span class="price">
                        €12.00
                    </span>

                    <button class="add-button">
                        Add to cart
                    </button>

                </div>

            </div>

        </div>

    </div>

</section>

</main>

</body>

</html>