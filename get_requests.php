<?php

session_start();

require_once("database.php");

header("Content-Type: application/json");


if (!isset($_SESSION["username"])) {

    echo json_encode([
        "success" => false,
        "message" => "You must be logged in."
    ]);

    exit;
}


$cook_username = $_SESSION["username"];

$requests = get_requests_by_cook(
    $cook_username,
    $conn
);


if ($requests === false) {

    echo json_encode([
        "success" => false,
        "message" => "Database error."
    ]);

    exit;
}


echo json_encode([
    "success" => true,
    "requests" => $requests
]);

?>