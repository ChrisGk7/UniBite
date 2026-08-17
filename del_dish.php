<?php
session_start();
require_once "database.php";

if (!isset($_SESSION['username'])) {
    http_response_code(401);
    echo json_encode(["success" => false, "message" => "Not logged in"]);
    exit;
}



header("Content-Type: application/json");

if($_SERVER["REQUEST_METHOD"]!=="POST"){
    echo json_encode(["success"=>false,"message"=>"Invalid request method"]);
    exit;
}

$data=json_decode(file_get_contents("php://input"), true);

$dish_id = (int) $data["dish_id"];

$cook = $_SESSION['username'];// PROSORINO


del_dish($dish_id, $cook, $conn);

echo json_encode(["success"=>true, "message"=>"Dish deleted successfully"]);




?>