<?php
session_start();
require_once("database.php");

header("Content-Type: application/json");

//Δεχεται μονο POST request
if($_SERVER["REQUEST_METHOD"]!=="POST"){
    echo json_encode(["success"=>false, "message"=>"Invalid request method"]);
    exit;
}

if (!isset($_SESSION['username'])) {
    http_response_code(401);
    echo json_encode(["success"=>false, "message"=>"Not logged in"]);
    exit;
}

$cook = $_SESSION['username'];


if (!ensure_cook_registered($cook, $conn)) {
    http_response_code(403);
    echo json_encode(["success"=>false, "message"=>"Could not register cook profile"]);
    exit;
}

$title            = trim($_POST["title"] ?? '');
$description      = trim($_POST["description"] ?? '');
$allergens        = trim($_POST["allergens"] ?? '');
$pickup_location  = trim($_POST["pickup_location"] ?? '');
$pickup_time_input = $_POST["pickup_time"] ?? '';
$portions         = (int) ($_POST["portions"] ?? 0);
$latitude         = $_POST["latitude"] ?? '';
$longitude        = $_POST["longitude"] ?? '';

if ($title === '' || $description === '' || $pickup_location === '' || $pickup_time_input === '' || $portions < 1) {
    echo json_encode(["success"=>false, "message"=>"Please fill in all required fields."]);
    exit;
}

if ($latitude === '' || $longitude === '') {
    echo json_encode(["success"=>false, "message"=>"Please select a pickup point on the map."]);
    exit;
}

$photo_url = null;
if (!empty($_FILES["image"]["name"])) {
    $file_name = basename($_FILES["image"]["name"]);
    $photo_url = "uploads/" . $file_name;
    move_uploaded_file($_FILES["image"]["tmp_name"], $photo_url);
}

//convert datetime for the correct saving.DATETIME
$pickup_time = date("Y-m-d H:i:s", strtotime($pickup_time_input));

$new_id = create_dish(
    $cook, $title, $description, $allergens, $photo_url,
    $pickup_location, $pickup_time, (float) $latitude, (float) $longitude,
    $portions, $conn
);

if ($new_id) {
    echo json_encode(["success"=>true, "message"=>"Dish created succesfully", "dish_id"=>$new_id]);
} else {
    echo json_encode(["success"=>false, "message"=>"Error: " . mysqli_error($conn)]);
}