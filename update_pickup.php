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


if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    echo json_encode([
        "success" => false,
        "message" => "Invalid request method."
    ]);

    exit;
}


$request_id =
    (int)($_POST["request_id"] ?? 0);

$pickup_action =
    $_POST["pickup_action"] ?? "";

$cook_username =
    $_SESSION["username"];


if ($request_id <= 0) {

    echo json_encode([
        "success" => false,
        "message" => "Invalid request."
    ]);

    exit;
}


if (
    $pickup_action !== "picked_up" &&
    $pickup_action !== "no_show"
) {

    echo json_encode([
        "success" => false,
        "message" => "Invalid pickup action."
    ]);

    exit;
}


$result = update_pickup_status(
    $request_id,
    $cook_username,
    $pickup_action,
    $conn
);


echo json_encode($result);

?>