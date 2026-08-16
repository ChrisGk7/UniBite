<?php

require_once "database.php";

header("Content-Type: application/json");

if($_SERVER["REQUEST_METHOD"]!=="POST"){
    echo json_encode(["success"=>false,"message"=>"Invalid request method"]);
    exit;
}

$data=json_decode(file_get_contents("php://input"), true);

$dish_id = (int) $data["dish_id"];

$cook = "testcook";// PROSORINO


del_dish($dish_id, $cook, $conn);

echo json_encode(["success"=>true, "message"=>"Dish deleted successfully"]);




?>