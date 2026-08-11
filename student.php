<?php
session_start();
include("database.php");
include("header.html");
// Require a logged-in session to view this page
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
    
}
$student = get_user_by_username($_SESSION['username'], $conn);
?>
<html>
<head>
   
    <link rel="stylesheet" href="studentd.css">
</head>
<body>
<!-- <div class="logo-container">
    <button class="logo" data-text="Awesome" onclick="window.location.href='logout.php'">
        <span class="actual-text">&nbsp;UNiBITE&nbsp;</span>
        <span aria-hidden="true" class="hover-text">&nbsp;UNiBITE&nbsp;</span>
    </button>
</div> -->
        <div class="dashboard-container">
            <h1>Student Dashboard</h1>
            <p>Welcome, <?php echo htmlspecialchars($student['name']); ?>!</p>
        </div>
<button  class="btn" onclick="window.location.href='logout.php'">Logout</button>
<button id="adddishBtn" class="btn" onclick="window.location.href='cook.html'">Add Dish </button>

        <div class="listing-card">
            <h2>Available Dishes</h2>
            <div id="dishList">Loading...</div>
            <button id="myRequestsBtn" class="btn" onclick="window.location.href='my_requests.php'">My Requests</button>
        </div>

        <div class="listing-card">
            <h2>Food nearby</h2>
            <div id= mapContainer>
                
            </div>
        </div>

</body>
</html>

<script src="student.js" defer></script>