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


$student_username =
    $_SESSION["username"];

$request_id =
    (int)($_POST["request_id"] ?? 0);

$rating =
    (int)($_POST["rating"] ?? 0);


if ($request_id <= 0) {

    echo json_encode([
        "success" => false,
        "message" => "Invalid request."
    ]);

    exit;
}


if ($rating < 1 || $rating > 5) {

    echo json_encode([
        "success" => false,
        "message" => "Rating must be between 1 and 5."
    ]);

    exit;
}


/* Check request */

$sql = "
    SELECT
        id,
        status,
        pickup_status,
        cook_username,
        rating
    FROM request
    WHERE id = ?
      AND stu_username = ?
";

$stmt =
    mysqli_prepare($conn, $sql);

if (!$stmt) {

    echo json_encode([
        "success" => false,
        "message" => mysqli_error($conn)
    ]);

    exit;
}


mysqli_stmt_bind_param(
    $stmt,
    "is",
    $request_id,
    $student_username
);

mysqli_stmt_execute($stmt);

$result =
    mysqli_stmt_get_result($stmt);

$request =
    mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);


if (!$request) {

    echo json_encode([
        "success" => false,
        "message" => "Request not found."
    ]);

    exit;
}


if ($request["status"] !== "accepted") {

    echo json_encode([
        "success" => false,
        "message" => "Only accepted orders can be rated."
    ]);

    exit;
}


if ($request["pickup_status"] !== "picked_up") {

    echo json_encode([
        "success" => false,
        "message" => "You can rate the order only after pickup."
    ]);

    exit;
}


if ($request["rating"] !== null) {

    echo json_encode([
        "success" => false,
        "message" => "This order has already been rated."
    ]);

    exit;
}


/* Save rating + give bonus points */

mysqli_begin_transaction($conn);

try {

    $sql = "
        UPDATE request
        SET
            rating = ?,
            rated_datetime = NOW()
        WHERE id = ?
          AND stu_username = ?
          AND rating IS NULL
    ";

    $stmt =
        mysqli_prepare($conn, $sql);

    if (!$stmt) {
        throw new Exception(mysqli_error($conn));
    }


    mysqli_stmt_bind_param(
        $stmt,
        "iis",
        $rating,
        $request_id,
        $student_username
    );


    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception(mysqli_stmt_error($stmt));
    }


    if (mysqli_stmt_affected_rows($stmt) !== 1) {

        mysqli_stmt_close($stmt);

        throw new Exception(
            "This order has already been rated."
        );
    }


    mysqli_stmt_close($stmt);


    /* Calculate rating bonus */

    $bonus_points = 0;

    if ($rating === 4) {
        $bonus_points = 2;
    }
    elseif ($rating === 5) {
        $bonus_points = 3;
    }


    /* Give bonus to cook */

   if ($bonus_points > 0) {

    $cook_username =
        $request["cook_username"];


    // Προσθήκη bonus στα συνολικά earned points του cook
    $sql = "
        UPDATE cook
        SET total_credits_earned =
            total_credits_earned + ?
        WHERE username = ?
    ";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        throw new Exception(mysqli_error($conn));
    }

    mysqli_stmt_bind_param(
        $stmt,
        "is",
        $bonus_points,
        $cook_username
    );

    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception(mysqli_stmt_error($stmt));
    }

    mysqli_stmt_close($stmt);


    // Προσθήκη bonus και στα διαθέσιμα credits του student
    $sql = "
        UPDATE student
        SET credits = credits + ?
        WHERE username = ?
    ";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        throw new Exception(mysqli_error($conn));
    }

    mysqli_stmt_bind_param(
        $stmt,
        "is",
        $bonus_points,
        $cook_username
    );

    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception(mysqli_stmt_error($stmt));
    }

    mysqli_stmt_close($stmt);
}
    mysqli_commit($conn);

    echo json_encode([
        "success" => true,
        "message" => "Rating submitted successfully.",
        "rating" => $rating,
        "bonus_points" => $bonus_points
    ]);

}
catch (Throwable $e) {

    mysqli_rollback($conn);

    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}

?>