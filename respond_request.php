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

$action =
    $_POST["action"] ?? "";

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
    $action !== "accept" &&
    $action !== "reject"
) {

    echo json_encode([
        "success" => false,
        "message" => "Invalid action."
    ]);

    exit;
}


$result = respond_to_request(
    $request_id,
    $cook_username,
    $action,
    $conn
);


echo json_encode($result);

?>