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



$request_count = get_request_count_by_cook(
    $cook_username,
    $conn
);

if ($request_count === false) {

    echo json_encode([
        "success" => false,
        "message" => "Database error."
    ]);

    exit;
}


echo json_encode([
    "success" => true,
    "request_count" => $request_count
]);

?>