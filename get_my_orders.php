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

$student_username = $_SESSION["username"];

$sql = "
    SELECT
        request.id AS request_id,
        request.portions,
        request.credit_cost,
        request.status,
        request.pickup_status,
        request.rating,

        dish.title,
        dish.cook,
        dish.pickup_location,
        dish.pickup_time

    FROM request

    INNER JOIN dish
        ON request.dish_id = dish.id

    WHERE request.stu_username = ?
      AND request.status = 'accepted'

    ORDER BY request.request_datetime DESC
";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    echo json_encode([
        "success" => false,
        "message" => mysqli_error($conn)
    ]);
    exit;
}

mysqli_stmt_bind_param(
    $stmt,
    "s",
    $student_username
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$orders = [];

while ($row = mysqli_fetch_assoc($result)) {
    $orders[] = $row;
}

mysqli_stmt_close($stmt);

echo json_encode([
    "success" => true,
    "orders" => $orders
]);

?>