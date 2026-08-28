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


$student_username =
    $_SESSION["username"];


/* -------------------------
   Start transaction
------------------------- */

mysqli_begin_transaction($conn);


try {

    /* -------------------------
       Find expired ratings

       Conditions:
       - Order was picked up
       - No rating was given
       - Penalty has not already
         been processed
       - 48 hours have passed
    ------------------------- */

    $sql = "
        SELECT id
        FROM request
        WHERE stu_username = ?
          AND pickup_status = 'picked_up'
          AND rating IS NULL
          AND rated_datetime IS NULL
          AND pickup_datetime IS NOT NULL
          AND pickup_datetime <= NOW() - INTERVAL 48 HOUR
        FOR UPDATE
    ";


    $stmt =
        mysqli_prepare($conn, $sql);


    if (!$stmt) {

        throw new Exception(
            mysqli_error($conn)
        );
    }


    mysqli_stmt_bind_param(
        $stmt,
        "s",
        $student_username
    );


    mysqli_stmt_execute($stmt);


    $result =
        mysqli_stmt_get_result($stmt);


    $expired_request_ids = [];


    while (
        $row = mysqli_fetch_assoc($result)
    ) {

        $expired_request_ids[] =
            (int)$row["id"];

    }


    mysqli_stmt_close($stmt);


    /* -------------------------
       Number of penalties
    ------------------------- */

    $penalties =
        count($expired_request_ids);


    /*
       Nothing expired.
       We can finish immediately.
    */

    if ($penalties === 0) {

        mysqli_commit($conn);


        echo json_encode([
            "success" => true,
            "penalties" => 0
        ]);


        exit;
    }


    /* -------------------------
       Deduct credits

       -1 credit for each order
       that was not rated within
       48 hours.

       GREATEST prevents credits
       from becoming negative.
    ------------------------- */

    $sql = "
        UPDATE student
        SET credits =
            GREATEST(credits - ?, 0)
        WHERE username = ?
    ";


    $stmt =
        mysqli_prepare($conn, $sql);


    if (!$stmt) {

        throw new Exception(
            mysqli_error($conn)
        );
    }


    mysqli_stmt_bind_param(
        $stmt,
        "is",
        $penalties,
        $student_username
    );


    if (!mysqli_stmt_execute($stmt)) {

        throw new Exception(
            mysqli_stmt_error($stmt)
        );
    }


    mysqli_stmt_close($stmt);


    /* -------------------------
       Mark penalties as processed

       We use rated_datetime as
       the marker so the penalty
       cannot happen again.
    ------------------------- */

    foreach ($expired_request_ids as $request_id) {

        $sql = "
            UPDATE request
            SET rated_datetime = NOW()
            WHERE id = ?
              AND rating IS NULL
              AND rated_datetime IS NULL
        ";


        $stmt =
            mysqli_prepare($conn, $sql);


        if (!$stmt) {

            throw new Exception(
                mysqli_error($conn)
            );
        }


        mysqli_stmt_bind_param(
            $stmt,
            "i",
            $request_id
        );


        if (!mysqli_stmt_execute($stmt)) {

            throw new Exception(
                mysqli_stmt_error($stmt)
            );
        }


        mysqli_stmt_close($stmt);
    }


    /* -------------------------
       Everything succeeded
    ------------------------- */

    mysqli_commit($conn);


    echo json_encode([
        "success" => true,
        "penalties" => $penalties
    ]);


} catch (Throwable $e) {


    /* -------------------------
       Something failed
       Undo everything
    ------------------------- */

    mysqli_rollback($conn);


    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);

}

?>