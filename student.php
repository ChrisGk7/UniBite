<?php
session_start();
include("database.php");

// 1. Check session FIRST before any HTML output or includes
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$student = get_user_by_username($_SESSION['username'], $conn);


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
    
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
     integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    
    
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
     integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    
    <link rel="stylesheet" href="studentd.css">
</head>
<body>

    <div class="dashboard-container">
        <h1>  <button class="logo" data-text="Awesome" onclick="window.location.href='logout.php'">
            <span class="actual-text">&nbsp;UNiBITE&nbsp;</span>
            <span aria-hidden="true" class="hover-text">&nbsp;UNiBITE&nbsp;</span>
            </button>
        </h1>
      
    </div>
      <div class="welcome-message">
            <p>Welcome, <?php echo htmlspecialchars($student['name']); ?>!</p>
        </div>

    <button class="btn" onclick="window.location.href='logout.php'">Logout</button>
    <button id="adddishBtn" class="btn" onclick="window.location.href='cook.php'">Add Dish</button>

    <div class="listing-card">
        <h2>Available Dishes</h2>
        <div id="dishList">Loading...</div>
        <button id="myRequestsBtn" class="btn" onclick="window.location.href='my_requests.php'">My Requests</button>
    </div>

    <div class="listing-card">
        <h2>Food nearby</h2>
        <div id="mapContainer" ></div> <!-- Ensure map has explicit height -->
        <div class ="btn" onclick="findCurrentLocation()">Find my location</div>
    </div>

   
    <script src="map.js" defer></script>
    <script src="student.js" defer></script>
</body>
</html>