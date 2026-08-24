<?php

    $db_server = "localhost";
    $db_user = "root";
    $db_password = "";
    $db_name = "unibite_db"; // change this
   


    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    try{
        $conn = mysqli_connect($db_server, 
                               $db_user, 
                               $db_password, 
                               $db_name);

    }
    
    catch(mysqli_sql_exception $e){
        echo "Could not connect to the Database";
    }


     function get_user_by_username($username, $conn){
 
        $sql = "SELECT username, email, pass, name, reg_date FROM user WHERE username = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
 
        return $row ?: null;
    }

    // returns the combined user + student profile (name/email from user, address/credits from student)
    // returns null if the username isn't a registered student
    function get_student_by_username($username, $conn){

        $sql = "SELECT u.username, u.email, u.name, u.reg_date,
                       s.credits, s.street, s.number, s.city, s.postcode, s.mobile
                FROM user u
                JOIN student s ON s.username = u.username
                WHERE u.username = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        return $row ?: null;
    }
    // returns true if the username is registered in the database
    function check_user_in_db($username, $conn){
        
        $sql = "SELECT * FROM user WHERE username = '$username'";
        //empty($result)
        $result = mysqli_query($conn, $sql);
        
        return mysqli_num_rows($result)>0;
        
    
    }

    //returns true if there is a record of an existing email in the database
     function check_user_email_in_db($email, $conn){
        
        $sql = "SELECT * FROM user WHERE email = '$email'";
        //empty($result)
        $result = mysqli_query($conn, $sql);
        
        return mysqli_num_rows($result)>0;
        
    
    }

   
    function register_user($username, $email, $pass, $name, $conn){
        $hash = password_hash($pass, PASSWORD_DEFAULT);
        $sql = "INSERT INTO user (username, email, pass, name) VALUES ('$username','$email', '$hash', '$name')";
        $result = mysqli_query($conn, $sql);
        
        return $result;
    }
    
    function register_student($username, $email, $street, $snumber, $city, $postcode, $mobile, $conn){
        $sql = "INSERT INTO student (username, email, street, number, city, postcode, mobile) VALUES ('$username', '$email', '$street', '$snumber', '$city', '$postcode','$mobile')";
        $result = mysqli_query($conn, $sql);
        return $result;
    }

  
    function register_admin($username, $email, $conn){
        $sql = "INSERT INTO admin (username, email) VALUES (?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $username, $email);
        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $result;
    }

    function is_admin($username, $conn){
        $sql = "SELECT 1 FROM admin WHERE username = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $exists = mysqli_num_rows($result) > 0;
        mysqli_stmt_close($stmt);
        return $exists;
    }

    // Returns every admin's username, name, email, and reg_date, newest first.
    function get_all_admins($conn){
        $sql = "SELECT a.username, u.name, a.email, u.reg_date
                FROM admin a
                JOIN user u ON u.username = a.username
                ORDER BY u.reg_date DESC";
        $result = mysqli_query($conn, $sql);
        $admins = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $admins[] = $row;
        }
        return $admins;
    }

    // ---------------------------------------------------------------
    // ADMIN DASHBOARD STATS 
    // ---------------------------------------------------------------

    
    function get_total_portions_last_month($conn){
        $sql = "SELECT COALESCE(SUM(portions), 0) AS total
                FROM request
                WHERE pickup_status = 'picked_up'
                  AND pickup_datetime >= (NOW() - INTERVAL 1 MONTH)";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        return (int) $row['total'];
    }

    function get_top_donor($conn){
        $sql = "SELECT u.username, u.name, SUM(r.portions) AS total_portions
                FROM request r
                JOIN user u ON u.username = r.cook_username
                WHERE r.pickup_status = 'picked_up'
                GROUP BY r.cook_username
                ORDER BY total_portions DESC
                LIMIT 1";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        return $row ?: null;
    }

   
    function get_top_rated_dishes($conn, $limit = 5){
        $limit = (int) $limit; // not user input in practice, but cast defensively since it can't be bound as a placeholder in LIMIT
        $sql = "SELECT d.id, d.title, u.username AS cook_username, u.name AS cook_name,
                       AVG(r.rating + 0) AS avg_rating, COUNT(r.rating) AS rating_count
                FROM request r
                JOIN dish d ON d.id = r.dish_id
                JOIN user u ON u.username = r.cook_username
                WHERE r.rating IS NOT NULL
                GROUP BY d.id
                ORDER BY avg_rating DESC, rating_count DESC
                LIMIT $limit";
        $result = mysqli_query($conn, $sql);
        $rows = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }

    function register_cook($username, $email, $street, $snumber,$city, $postcode, $mobile, $conn){
        $sql = "INSERT INTO cook (username, email, street, number, city, postcode, mobile) VALUES ('$username', '$email', '$street', '$snumber', '$city', '$postcode','$mobile' )";
        $result = mysqli_query($conn, $sql);
        return $result;
    }

    // returns true if this username already has a cook row
    function is_cook($username, $conn){
        $sql = "SELECT 1 FROM cook WHERE username = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $exists = mysqli_num_rows($result) > 0;
        mysqli_stmt_close($stmt);
        return $exists;
    }

    // Promotes a student to also being a cook, reusing their existing student
    // address/mobile info so they aren't asked to re-enter it.
    // Safe to call every time "Add Dish" is clicked - does nothing if already a cook.
    // Returns true if the user is (or now is) a registered cook.
    function ensure_cook_registered($username, $conn){
        if (is_cook($username, $conn)) {
            return true;
        }

        $student = get_student_by_username($username, $conn);
        if (!$student) {
            // Not even a registered student - can't promote to cook
            return false;
        }

        return register_cook(
            $student['username'],
            $student['email'],
            $student['street'],
            $student['number'],
            $student['city'],
            $student['postcode'],
            $student['mobile'],
            $conn
        ) ? true : false;
    }

    

    // login logic

    
    

    // retrieve all rows from a table

    function get_rows_from_table($table, $conn){
        $sql = "SELECT * FROM $table";
        $result = mysqli_query($conn, $sql);
        return $result;

        
    }

    function get_rows_from_table_condition($table, $condition, $conn){
        $sql = "SELECT * FROM $table WHERE $condition";
        $result = mysqli_query($conn, $sql);
        return $result;
    }

    // get all rows from a table where $wherewhat is $wherewho
    // for example to get all thesis of teacher with key/email "teacher"
    // $wherewhat should be "email" and $wherewho should be "teacher"

    function get_rows_from_table_where($table, $wherewhat, $wherewho, $conn){
        $sql = "SELECT * FROM $table WHERE $wherewhat = '$wherewho'";
        $result = mysqli_query($conn, $sql);
        return $result;
    }

    // update table named $table
    // set column $colname to value $colvalue
    // $wherewhat and $wherewho work the same as in the above function

    function update_table_row($table, $colname, $colvalue, $wherewhat, $wherewho, $conn){
        $sql = "UPDATE $table SET $colname = '$colvalue' WHERE $wherewhat = '$wherewho'";
        mysqli_query($conn, $sql);
    }

    function update_table_row_condition($table, $colname, $colvalue, $condition, $conn){
        $sql = "UPDATE $table SET $colname = '$colvalue' WHERE $condition";
        mysqli_query($conn, $sql);
    }

    function delete_table_row($table, $wherewhat, $wherewho, $conn){
        $sql = "DELETE FROM $table WHERE $wherewhat = '$wherewho'";
        mysqli_query($conn, $sql);
    }

    // dish logic

    function create_dish($cook,$title,$description,$allergens,$photo_url,$pickup_location,$pickup_time,$latitude,$longitude,$portions,$conn){
    $sql="INSERT INTO dish(cook,title,description,allergens,photos_url,pickup_location,pickup_time,latitude,longitude,portions)VALUES(?,?,?,?,?,?,?,?,?,?)";
    $stmt= mysqli_prepare($conn,$sql);

    mysqli_stmt_bind_param($stmt,"sssssssddi",$cook,$title,$description,$allergens,$photo_url,$pickup_location,$pickup_time,$latitude,$longitude,$portions);

    $result = mysqli_stmt_execute($stmt);
    $new_id = mysqli_insert_id($conn);
    mysqli_stmt_close($stmt);
    return $result ? $new_id : false;
}
//READ function for listings

    function get_dishes_by_cook($cook,$conn){
        $sql = "SELECT* FROM dish
                where cook= ?
                ORDER BY reg_date DESC";

        $stmt = mysqli_prepare($conn,$sql);

        mysqli_stmt_bind_param($stmt,"s",$cook);

        mysqli_stmt_execute($stmt);
        $result= mysqli_stmt_get_result($stmt);

        $dishes = [];

        while($row=mysqli_fetch_assoc($result)){
            $dishes[]=$row;
        }

        mysqli_stmt_close($stmt);
        return $dishes;
    }


    //delete listing function

     function del_dish($dish_id, $cook,$conn){

    $sql = "DELETE FROM dish
            where id= ?
            AND cook= ?";

    $stmt= mysqli_prepare($conn,$sql);

    mysqli_stmt_bind_param($stmt, "is", $dish_id,$cook);

    mysqli_stmt_execute($stmt);
    $affected_rows = mysqli_stmt_affected_rows($stmt);

    mysqli_stmt_close($stmt);

    return $affected_rows > 0;

    }

    //UPDATE listing function

    function update_dish($dish_id, $cook, $title, $description, $allergens, $photo_url, $portions, $pickup_location, $pickup_time, $latitude, $longitude, $conn) {

    // Verify the dish actually belongs to this cook BEFORE updating.
    // Checking this separately (rather than relying on affected_rows after
    // the UPDATE) avoids a false "failure" when someone edits without
    // actually changing any values - affected_rows is 0 in that case too,
    // even though the request was legitimate.
    $check_sql = "SELECT 1 FROM dish WHERE id = ? AND cook = ?";
    $check_stmt = mysqli_prepare($conn, $check_sql);
    mysqli_stmt_bind_param($check_stmt, "is", $dish_id, $cook);
    mysqli_stmt_execute($check_stmt);
    $owns_dish = mysqli_num_rows(mysqli_stmt_get_result($check_stmt)) > 0;
    mysqli_stmt_close($check_stmt);

    if (!$owns_dish) {
        return false;
    }

    if ($photo_url !== null) {

        $sql = "UPDATE dish SET title = ?, description = ?, allergens = ?, photos_url = ?, portions = ?, pickup_location = ?, pickup_time = ?, latitude = ?, longitude = ? WHERE id = ? AND cook = ?";

        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param($stmt, "ssssissddis", $title, $description, $allergens, $photo_url, $portions, $pickup_location, $pickup_time, $latitude, $longitude, $dish_id, $cook);

    } else {

        $sql = "UPDATE dish SET title = ?, description = ?, allergens = ?, portions = ?, pickup_location = ?, pickup_time = ?, latitude = ?, longitude = ? WHERE id = ? AND cook = ?";

        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param($stmt, "sssissddis", $title, $description, $allergens, $portions, $pickup_location, $pickup_time, $latitude, $longitude, $dish_id, $cook);
    }

    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $result;
}
   

    function acc_decl_request($student_username, $cook_username, $dish_id, $status, $conn){
        $sql = "UPDATE request SET status = '".$status."' WHERE stu_username = '".$student_username."' AND cook_username = '".$cook_username."' AND dish_id = '".$dish_id."' AND status = 'pending'";
        mysqli_query($conn, $sql);
        $sql = "UPDATE request SET reply_datetime = CURRENT_TIMESTAMP() WHERE stu_username = '".$student_username."' AND cook_username = '".$cook_username."' AND dish_id = '".$dish_id."' AND status = '".$status."'";
        mysqli_query($conn, $sql);
    }


    // displays the requests to the cook
    function get_requests_by_cook($cook_username, $conn)
{
    $sql = "
        SELECT
            r.id,
            r.stu_username,
            r.dish_id,
            r.portions,
            r.credit_cost,
            r.status,
            r.pickup_status,
            r.request_datetime,
            r.reply_datetime,
            r.pickup_datetime,

            d.title,
            d.pickup_location,
            d.pickup_time

        FROM request r

        INNER JOIN dish d
            ON r.dish_id = d.id

        WHERE r.cook_username = ?

        ORDER BY r.request_datetime DESC
    ";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        return false;
    }

    mysqli_stmt_bind_param(
        $stmt,
        "s",
        $cook_username
    );

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $requests = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $requests[] = $row;
    }

    mysqli_stmt_close($stmt);

    return $requests;
}




//accept/reject requests 
function respond_to_request($request_id, $cook_username, $action, $conn)
{
    mysqli_begin_transaction($conn);

    try {

        $sql = "
            SELECT
                id,
                cook_username,
                dish_id,
                portions,
                status
            FROM request
            WHERE id = ?
              AND cook_username = ?
            FOR UPDATE
        ";

        $stmt = mysqli_prepare($conn, $sql);

        if (!$stmt) {
            throw new Exception(mysqli_error($conn));
        }

        mysqli_stmt_bind_param(
            $stmt,
            "is",
            $request_id,
            $cook_username
        );

        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        $request = mysqli_fetch_assoc($result);

        mysqli_stmt_close($stmt);


        if (!$request) {
            throw new Exception("Request not found.");
        }


        if ($request["status"] !== "pending") {
            throw new Exception("This request has already been answered.");
        }

        // REJECT
        if ($action === "reject") {

            $sql = "
                UPDATE request
                SET
                    status = 'declined',
                    reply_datetime = NOW()
                WHERE id = ?
            ";

            $stmt = mysqli_prepare($conn, $sql);

            mysqli_stmt_bind_param(
                $stmt,
                "i",
                $request_id
            );

            mysqli_stmt_execute($stmt);

            mysqli_stmt_close($stmt);

            mysqli_commit($conn);

            return [
                "success" => true,
                "status" => "declined"
            ];
        }

        // ACCEPT
        if ($action === "accept") {

            $dish_id =
                (int)$request["dish_id"];

            $requested_portions =
                (int)$request["portions"];


            $sql = "
                SELECT portions
                FROM dish
                WHERE id = ?
                FOR UPDATE
            ";

            $stmt = mysqli_prepare($conn, $sql);

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
                throw new Exception("Dish not found.");
            }


            if (
                (int)$dish["portions"]
                < $requested_portions
            ) {
                throw new Exception(
                    "Not enough portions available."
                );
            }


            // Μείωση διαθέσιμων μερίδων
            $sql = "
                UPDATE dish
                SET portions = portions - ?
                WHERE id = ?
            ";

            $stmt = mysqli_prepare($conn, $sql);

            mysqli_stmt_bind_param(
                $stmt,
                "ii",
                $requested_portions,
                $dish_id
            );

            mysqli_stmt_execute($stmt);

            mysqli_stmt_close($stmt);


            // Ενημέρωση request
            $sql = "
                UPDATE request
                SET
                    status = 'accepted',
                    pickup_status = 'awaiting_pickup',
                    reply_datetime = NOW()
                WHERE id = ?
            ";

            $stmt = mysqli_prepare($conn, $sql);

            mysqli_stmt_bind_param(
                $stmt,
                "i",
                $request_id
            );

            mysqli_stmt_execute($stmt);

            mysqli_stmt_close($stmt);


            mysqli_commit($conn);


            return [
                "success" => true,
                "status" => "accepted"
            ];
        }


        throw new Exception("Invalid action.");

    } catch (Throwable $e) {

        mysqli_rollback($conn);

        return [
            "success" => false,
            "message" => $e->getMessage()
        ];
    }
}
   


function update_pickup_status($request_id,$cook_username,$pickup_action,$conn) {
    mysqli_begin_transaction($conn);

    try {

        //Παίρνουμε και κλειδώνουμε το request
        $sql = "
            SELECT
                id,
                stu_username,
                cook_username,
                dish_id,
                portions,
                status,
                pickup_status
            FROM request
            WHERE id = ?
              AND cook_username = ?
            FOR UPDATE
        ";

        $stmt = mysqli_prepare($conn, $sql);

        if (!$stmt) {
            throw new Exception(mysqli_error($conn));
        }

        mysqli_stmt_bind_param(
            $stmt,
            "is",
            $request_id,
            $cook_username
        );

        mysqli_stmt_execute($stmt);

        $result =
            mysqli_stmt_get_result($stmt);

        $request =
            mysqli_fetch_assoc($result);

        mysqli_stmt_close($stmt);


        if (!$request) {
            throw new Exception("Request not found.");
        }


        if ($request["status"] !== "accepted") {
            throw new Exception(
                "Only accepted requests can be completed."
            );
        }


        if (
            $request["pickup_status"] !==
            "awaiting_pickup"
        ) {
            throw new Exception(
                "Pickup status has already been updated."
            );
        }


        // PICKED UP
        if ($pickup_action === "picked_up") {

            $sql = "
                UPDATE request
                SET
                    pickup_status = 'picked_up',
                    pickup_datetime = NOW()
                WHERE id = ?
            ";

            $stmt =
                mysqli_prepare($conn, $sql);

            if (!$stmt) {
                throw new Exception(mysqli_error($conn));
            }

            mysqli_stmt_bind_param(
                $stmt,
                "i",
                $request_id
            );

            mysqli_stmt_execute($stmt);

            mysqli_stmt_close($stmt);


            mysqli_commit($conn);


            return [
                "success" => true,
                "pickup_status" => "picked_up"
            ];
        }


        //NO SHOW
        if ($pickup_action === "no_show") {

            $student_username =
                $request["stu_username"];

            $dish_id =
                (int)$request["dish_id"];

            $request_portions =
                (int)$request["portions"];

            //Επιστροφή μερίδων στο dish
            $sql = "
                UPDATE dish
                SET portions = portions + ?
                WHERE id = ?
            ";

            $stmt =
                mysqli_prepare($conn, $sql);

            if (!$stmt) {
                throw new Exception(mysqli_error($conn));
            }

            mysqli_stmt_bind_param(
                $stmt,
                "ii",
                $request_portions,
                $dish_id
            );

            mysqli_stmt_execute($stmt);

            mysqli_stmt_close($stmt);


            // -1 credit στον student
            $sql = "
                UPDATE student
                SET credits = GREATEST(credits - 1, 0)
                WHERE username = ?
            ";

            $stmt =
                mysqli_prepare($conn, $sql);

            if (!$stmt) {
                throw new Exception(mysqli_error($conn));
            }

            mysqli_stmt_bind_param(
                $stmt,
                "s",
                $student_username
            );

            mysqli_stmt_execute($stmt);

            mysqli_stmt_close($stmt);


            //Ενημέρωση request
            $sql = "
                UPDATE request
                SET
                    pickup_status = 'no_show',
                    pickup_datetime = NOW()
                WHERE id = ?
            ";

            $stmt =
                mysqli_prepare($conn, $sql);

            if (!$stmt) {
                throw new Exception(mysqli_error($conn));
            }

            mysqli_stmt_bind_param(
                $stmt,
                "i",
                $request_id
            );

            mysqli_stmt_execute($stmt);

            mysqli_stmt_close($stmt);


            mysqli_commit($conn);


            return [
                "success" => true,
                "pickup_status" => "no_show"
            ];
        }


        throw new Exception("Invalid pickup action.");

    } catch (Throwable $e) {

        mysqli_rollback($conn);

        return [
            "success" => false,
            "message" => $e->getMessage()
        ];
    }
}



function student_confirm_pickup($request_id, $student_username, $conn)
{
    mysqli_begin_transaction($conn);

    try {

        // Lock the student's accepted request
        $sql = "
            SELECT
                id,
                stu_username,
                cook_username,
                dish_id,
                portions,
                status,
                pickup_status
            FROM request
            WHERE id = ?
              AND stu_username = ?
            FOR UPDATE
        ";

        $stmt = mysqli_prepare($conn, $sql);

        if (!$stmt) {
            throw new Exception(mysqli_error($conn));
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
            throw new Exception("Request not found.");
        }


        if ($request["status"] !== "accepted") {
            throw new Exception(
                "Only accepted requests can be picked up."
            );
        }


        if (
            $request["pickup_status"] !==
            "awaiting_pickup"
        ) {
            throw new Exception(
                "Pickup status has already been updated."
            );
        }


        // Mark as picked up
        $sql = "
            UPDATE request
            SET
                pickup_status = 'picked_up',
                pickup_datetime = NOW()
            WHERE id = ?
        ";

        $stmt =
            mysqli_prepare($conn, $sql);

        if (!$stmt) {
            throw new Exception(mysqli_error($conn));
        }

        mysqli_stmt_bind_param(
            $stmt,
            "i",
            $request_id
        );

        mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);


        mysqli_commit($conn);


        return [
            "success" => true,
            "pickup_status" => "picked_up"
        ];

    } catch (Throwable $e) {

        mysqli_rollback($conn);

        return [
            "success" => false,
            "message" => $e->getMessage()
        ];
    }
}

?>