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
    // returns true if the email is registered in the database
    function check_user_in_db($username, $conn){
        
        $sql = "SELECT * FROM user WHERE username = '$username'";
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

    function create_dish($cook, $title, $description, $allergens, $portions, $credit_cost, $conn){
        $sql = "INSERT INTO dish (cook, title, description, allergens, portions, credit_cost)
                VALUES ('$cook', '$title', '$description', '$allergens', '$portions', '$credit_cost')";
        return mysqli_query($conn, $sql);
    }

   

    function acc_decl_request($student_username, $cook_username, $dish_id, $status, $conn){
        $sql = "UPDATE request SET status = '".$status."' WHERE stu_username = '".$student_username."' AND cook_username = '".$cook_username."' AND dish_id = '".$dish_id."' AND status = 'pending'";
        mysqli_query($conn, $sql);
        $sql = "UPDATE request SET reply_datetime = CURRENT_TIMESTAMP() WHERE stu_username = '".$student_username."' AND cook_username = '".$cook_username."' AND dish_id = '".$dish_id."' AND status = '".$status."'";
        mysqli_query($conn, $sql);
    }

    function add_examiner_to_thesis($thesis_id, $teacher_email, $student_email, $conn){
        
        // get the student thesis relation row and check for examiner availiability

        $sql = "SELECT * FROM student_thesis_relation WHERE stu_email = '".$student_email."' AND thesis_id = '".$thesis_id."' AND status = 'pending_assignment'";
        $thesis_relation_row = mysqli_fetch_assoc(mysqli_query($conn, $sql));

        if (!$thesis_relation_row["teach1_email"]){
            $sql = "UPDATE student_thesis_relation SET teach1_email = '".$teacher_email."' WHERE stu_email = '".$student_email."' AND thesis_id = '".$thesis_id."' AND status = 'pending_assignment'";
        }
        elseif (!$thesis_relation_row["teach2_email"]){
            $sql = "UPDATE student_thesis_relation SET teach2_email = '".$teacher_email."' WHERE stu_email = '".$student_email."' AND thesis_id = '".$thesis_id."' AND status = 'pending_assignment'";
        }
        mysqli_query($conn, $sql);

    }

    function auto_cancel_requests($thesis_id, $student_email, $conn) {

        $sql = "SELECT * FROM student_thesis_relation WHERE stu_email = '".$student_email."' AND thesis_id = '".$thesis_id."' AND status = 'pending_assignment'";
        $thesis_relation_row = mysqli_fetch_assoc(mysqli_query($conn, $sql));

        if ($thesis_relation_row["teach1_email"] && $thesis_relation_row["teach2_email"]){
            update_table_row_condition("student_thesis_relation", "status", "active", "stu_email = '".$student_email."' AND thesis_id = '".$thesis_id."' AND status = 'pending_assignment'", $conn);
            
            // once the thesis is accepted auto decline all the other requests

            $sql = "UPDATE request SET status = 'declined', reply_datetime = CURRENT_TIMESTAMP() WHERE stu_email = '".$student_email."' AND thesis_id = '".$thesis_id."' AND status = 'pending'";
            mysqli_query($conn, $sql);

        }
    }


?>