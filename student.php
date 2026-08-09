<?php
session_start();
include("database.php");

// Require a logged-in session to view this page
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
    
}
$student = get_user_by_username($_SESSION['username'], $conn);
?>
<html>
<head>
   
    <link rel="stylesheet" href="cook.css">
</head>
<body>

<h1>Student Dashboard</h1>
<p>Welcome, <?php echo htmlspecialchars($student['name']); ?>!</p>

<button  class="editBtn" onclick="window.location.href='logout.php'">Logout</button>
<button id="adddishBtn" class="editBtn" onclick="window.location.href='cook.html'">Add Dish </button>
</body>
</html>

<script src="student.js" defer></script>