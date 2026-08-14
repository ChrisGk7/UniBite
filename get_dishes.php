<?php
session_start();
require_once "database.php";

if (!isset($_SESSION['username'])) {
    http_response_code(401);
    echo json_encode(["success" => false, "message" => "Not logged in"]);
    exit;
}

$cook = $_SESSION['username'];

$dishes = get_dishes_by_cook($cook, $conn);

echo json_encode(["success" => true, "dishes" => $dishes]);




?>