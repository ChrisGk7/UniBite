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

$student_username = $_SESSION["username"];

$dish_id = (int)($_POST["dish_id"] ?? 0);

if ($dish_id <= 0) {
    echo json_encode([
        "success" => false,
        "message" => "Invalid dish."
    ]);
    exit;
}


// Get the dish directly from the database
$sql = "
    SELECT id, cook, portions, credit_cost
    FROM dish
    WHERE id = ?
";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $dish_id
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$dish = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);


if (!$dish) {
    echo json_encode([
        "success" => false,
        "message" => "Dish not found."
    ]);
    exit;
}


if ((int)$dish["portions"] <= 0) {
    echo json_encode([
        "success" => false,
        "message" => "This dish is unavailable."
    ]);
    exit;
}


$cook_username = $dish["cook"];

$request_portions = 1;

$credit_cost = (int)$dish["credit_cost"];


// Create pending request
$sql = "
    INSERT INTO request (
        stu_username,
        cook_username,
        dish_id,
        portions,
        credit_cost
    )
    VALUES (?, ?, ?, ?, ?)
";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "ssiii",
    $student_username,
    $cook_username,
    $dish_id,
    $request_portions,
    $credit_cost
);

mysqli_stmt_execute($stmt);

mysqli_stmt_close($stmt);


echo json_encode([
    "success" => true,
    "message" => "Request sent successfully."
]);

?>