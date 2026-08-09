<?php
session_start();
include("database.php");

// Require a logged-in session to view this page
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}
?>
<html>

<h1>Student Dashboard</h1>

<button onclick="window.location.href='logout.php'">Logout</button>
</html>