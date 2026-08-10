<?php
session_start();
require_once("database.php");
include("header.html");

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
    <link rel="stylesheet" href="studentd.css">
</head>
<body>
      
      
<main>
    <h1>Admin Dashboard</h1>
<div class="listing-card">
    
        <p>Welcome, <strong><?php echo htmlspecialchars($_SESSION['name']); ?></strong>!</p>

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
</div>

</main>
</body>
</html>
