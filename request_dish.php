<?php

session_start();

require_once("database.php");

header("Content-Type: application/json");


/* -------------------------
   Check login
------------------------- */

if (!isset($_SESSION["username"])) {

    echo json_encode([
        "success" => false,
        "message" => "You must be logged in."
    ]);

    exit;
}


/* -------------------------
   Only allow POST
------------------------- */

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    echo json_encode([
        "success" => false,
        "message" => "Invalid request method."
    ]);

    exit;
}


/* -------------------------
   Get request data
------------------------- */

$student_username = $_SESSION["username"];

$dish_id =
    (int)($_POST["dish_id"] ?? 0);

$request_portions =
    (int)($_POST["portions"] ?? 1);


if ($dish_id <= 0) {

    echo json_encode([
        "success" => false,
        "message" => "Invalid dish."
    ]);

    exit;
}


if ($request_portions < 1) {

    echo json_encode([
        "success" => false,
        "message" => "Invalid number of portions."
    ]);

    exit;
}


/* -------------------------
   Get dish information
------------------------- */

$sql = "
    SELECT
        id,
        cook,
        portions,
        credits_per_portion
    FROM dish
    WHERE id = ?
";

$stmt = mysqli_prepare($conn, $sql);


if (!$stmt) {

    echo json_encode([
        "success" => false,
        "message" => "Database error: " . mysqli_error($conn)
    ]);

    exit;
}


mysqli_stmt_bind_param(
    $stmt,
    "i",
    $dish_id
);

mysqli_stmt_execute($stmt);

$result =
    mysqli_stmt_get_result($stmt);

$dish =
    mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);


if (!$dish) {

    echo json_encode([
        "success" => false,
        "message" => "Dish not found."
    ]);

    exit;
}


/* -------------------------
   Check availability
------------------------- */

$available_portions =
    (int)$dish["portions"];


if ($available_portions <= 0) {

    echo json_encode([
        "success" => false,
        "message" => "This dish is unavailable."
    ]);

    exit;
}


if ($request_portions > $available_portions) {

    echo json_encode([
        "success" => false,
        "message" => "Not enough portions available."
    ]);

    exit;
}


/* -------------------------
   Dish information
------------------------- */

$cook_username =
    $dish["cook"];

// Prevent users from requesting their own dish

if ($cook_username === $student_username) {

    echo json_encode([
        "success" => false,
        "message" => "You cannot request your own dish."
    ]);
    
    exit;
}

$credits_per_portion =
    (int)$dish["credits_per_portion"];


/* -------------------------
   Calculate total credit cost
------------------------- */

$credit_cost =
    $request_portions *
    $credits_per_portion;


/* -------------------------
   Get student's credits
------------------------- */

$sql = "
    SELECT credits
    FROM student
    WHERE username = ?
";

$stmt = mysqli_prepare($conn, $sql);


if (!$stmt) {

    echo json_encode([
        "success" => false,
        "message" => "Database error: " . mysqli_error($conn)
    ]);

    exit;
}


mysqli_stmt_bind_param(
    $stmt,
    "s",
    $student_username
);

mysqli_stmt_execute($stmt);

$result =
    mysqli_stmt_get_result($stmt);

$student =
    mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);


if (!$student) {

    echo json_encode([
        "success" => false,
        "message" => "Student account not found."
    ]);

    exit;
}


$student_credits =
    (int)$student["credits"];


/* -------------------------
   Check if student has enough credits
------------------------- */

if ($student_credits < $credit_cost) {

    echo json_encode([
        "success" => false,
        "message" => "You do not have enough credits."
    ]);

    exit;
}


/* -------------------------
   Create pending request
------------------------- */

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


if (!$stmt) {

    echo json_encode([
        "success" => false,
        "message" => "Database error: " . mysqli_error($conn)
    ]);

    exit;
}


mysqli_stmt_bind_param(
    $stmt,
    "ssiii",
    $student_username,
    $cook_username,
    $dish_id,
    $request_portions,
    $credit_cost
);


if (!mysqli_stmt_execute($stmt)) {

    echo json_encode([
        "success" => false,
        "message" => "Request failed: " . mysqli_stmt_error($stmt)
    ]);

    mysqli_stmt_close($stmt);

    exit;
}


mysqli_stmt_close($stmt);


/* -------------------------
   Success
------------------------- */

echo json_encode([
    "success" => true,
    "message" => "Request sent successfully.",
    "portions" => $request_portions,
    "credit_cost" => $credit_cost
]);

?>