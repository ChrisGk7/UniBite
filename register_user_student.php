<?php

    session_start();
    include("header.html");
    include("database.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register as Student</title>
</head>
<body>
    <form action="register_user_student.php" method="post">
        <label>Full Name: </label><br>
        <input type="text" name="name" placeholder="John Doe"required><br>
        <label>Username: </label><br>
        <input type="text" name="username" placeholder="john_doe"required><br>
        <label>Email: </label><br>
        <input type="email" name="email" placeholder="upXXXXXXX@upnet.gr"required><br>
        <label>Password: </label><br>
        <input type="password" name="password1"required><br>
        <label>Confirm Password: </label><br>
        <input type="password" name="password2"required><br>
        <label>Street: </label><br>
        <input type="text" name="street" placeholder="Based Street"required><br>
        <label>Street Number: </label><br>
        <input type="text" name="snumber" placeholder="69"required><br>
        <label>City: </label><br>
        <input type="text" name="city" placeholder="Patrsas"required><br>
        <label>postcode: </label><br>
        <input type="text" name="postcode" placeholder="26XXX"required><br>
        <label>Mobile Number: </label><br>
        <input type="text" name="cell" placeholder="694XXXXXXX"required><br>
        <button type="submit">Sign Up</button>
    </form>
</body>
</html>

<?php


    $emails = array("1", "2", "3");

    if (isset($_POST["login"])){

        $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
        $name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_SPECIAL_CHARS);
        $password1 = filter_input(INPUT_POST, "password1", FILTER_SANITIZE_SPECIAL_CHARS);
        $password2 = filter_input(INPUT_POST, "password2", FILTER_SANITIZE_SPECIAL_CHARS);
        $street = filter_input(INPUT_POST, "street", FILTER_SANITIZE_SPECIAL_CHARS);
        $snumber = filter_input(INPUT_POST, "snumber", FILTER_SANITIZE_SPECIAL_CHARS);
        $city = filter_input(INPUT_POST, "city", FILTER_SANITIZE_SPECIAL_CHARS);
        $postcode = filter_input(INPUT_POST, "postcode", FILTER_SANITIZE_SPECIAL_CHARS);
        $mobile = filter_input(INPUT_POST, "cell", FILTER_SANITIZE_SPECIAL_CHARS);
        

        


        if (empty($email)){
            echo "Please Enter Email";
        }
        elseif (empty($password1)){
            echo "Please enter your password";
        }
        elseif (empty($password2)){
            echo "Please enter your password again";
        }
        elseif ($password1 != $password2){
            echo "Passwords don't match";
        }

        else{
            // FOR TESTING PURPOSES ONLY DELETE LATER
            echo "Email is: {$email} and your Password is: {$password1}<br>";
            
            //$_SESSION["email"] = $email;


            try{
                register_user($username, $email, $password1, $name, $conn);
                try{
                    register_student($username, $email, $street, $snumber, $city, $postcode,$mobile, $conn);
                }
                catch(mysqli_sql_exception){
                    echo "User with username {$username} already exists";
                    delete_table_row("user", "email", $username, $conn);
                }
            }
            
            catch(mysqli_sql_exception){
                echo "User with email {$email} already exists";
            }

            

        }    
    }

?>

<?php
    //mysqli_close($conn);
    
    try{
        mysqli_close($conn);
    }
    catch(TypeError){
        echo "";
    }
    
?>