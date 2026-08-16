<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once "db.php";

$sql = "SELECT * FROM dishes";

$result = mysqli_query($conn, $sql);

$dishes = [];

while ($row = mysqli_fetch_assoc($result)) {
    $dishes[] = $row;
}

header("Content-Type: application/json");

echo json_encode($dishes, JSON_PRETTY_PRINT);

?>