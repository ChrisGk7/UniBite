<?php
session_start();
require_once "database.php";

header("Content-Type: application/json");

if (!isset($_SESSION['username'])) {
    http_response_code(401);
    echo json_encode(["success" => false, "message" => "Not logged in"]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    echo json_encode([
        "success" => false,
        "message" => "Invalid request method"
    ]);

    exit;
}

$dish_id = (int) ($_POST["dish_id"] ?? 0);
$title = trim($_POST["title"] ?? '');
$description = trim($_POST["description"] ?? '');
$allergens = trim($_POST["allergens"] ?? '');

$portions = (int) ($_POST["portions"] ?? 0);

$pickup_location = trim($_POST["pickup_location"] ?? '');

$pickup_time_input = $_POST["pickup_time"] ?? '';

$latitude = $_POST["latitude"] ?? '';
$longitude = $_POST["longitude"] ?? '';

if ($dish_id < 1 || $title === '' || $description === '' || $pickup_location === '' || $pickup_time_input === '' || $portions < 1) {
    echo json_encode(["success" => false, "message" => "Please fill in all required fields."]);
    exit;
}

if ($latitude === '' || $longitude === '') {
    echo json_encode(["success" => false, "message" => "Please select a pickup point on the map."]);
    exit;
}

$pickup_time = date(
    "Y-m-d H:i:s",
    strtotime($pickup_time_input)
);

$cook = $_SESSION['username'];

$photo_url = null;

if (!empty($_FILES["image"]["name"])) {

    $file_name = basename($_FILES["image"]["name"]);

    $photo_url = "uploads/" . $file_name;

    move_uploaded_file(
        $_FILES["image"]["tmp_name"],
        $photo_url
    );
}

$updated = update_dish(
    $dish_id,
    $cook,
    $title,
    $description,
    $allergens,
    $photo_url,
    $portions,
    $pickup_location,
    $pickup_time,
    (float) $latitude,
    (float) $longitude,
    $conn
);

if ($updated) {
    echo json_encode([
        "success" => true,
        "message" => "Dish updated successfully"
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Dish not found or you don't have permission to edit it"
    ]);
}