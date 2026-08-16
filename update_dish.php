<?php

require_once "database.php";

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    echo json_encode([
        "success" => false,
        "message" => "Invalid request method"
    ]);

    exit;
}

$dish_id = (int) $_POST["dish_id"];
$title = trim($_POST["title"]);
$description = trim($_POST["description"]);
$allergens = trim($_POST["allergens"]);

$portions = (int) $_POST["portions"];

$pickup_location =trim($_POST["pickup_location"]);

$pickup_time_input =$_POST["pickup_time"];

$pickup_time = date(
    "Y-m-d H:i:s",
    strtotime($pickup_time_input)
);

$cook = "testcook"; // προσωρινά

$photo_url = null;

if (!empty($_FILES["image"]["name"])) {

    $file_name = $_FILES["image"]["name"];

    $photo_url = "uploads/" . $file_name;

    move_uploaded_file(
        $_FILES["image"]["tmp_name"],
        $photo_url
    );
}

update_dish(
    $dish_id,
    $cook,
    $title,
    $description,
    $allergens,
    $photo_url,
    $portions,
    $pickup_location,
    $pickup_time,
    $conn
);

echo json_encode([
    "success" => true,
    "message" => "Dish updated successfully"
]);

?>