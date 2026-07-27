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
    <title>Log in</title>
    <link rel="stylesheet" href="style.css">
    <script src="form.js" defer> </script>
</head>
<body>
    <!--<form action="index.php" method="post">
        <label>Username: </label><br>
        <input type="text" name="username" ><br>
        <label>Password: </label><br>
        <input type="password" name="password"><br>
        <input type="submit" name="login" value="Log in">
   
             <div class="container">-->
    <div id="login_page"> 
        <div class="form_area">
            <p class="title">UNIBITE</p>
            <form id="loginForm" action="index.php" method="post" novalidate>
                <div class="form_group">
                    <label class="sub_title" for="username">Username</label>
                    <input id="username" placeholder="Enter your username" class="form_style" type="text" name="username" autocomplete="username" required>
                    <span class="error-message"></span>
                </div>
                <div class="form_group">
                    <label class="sub_title" for="password">Password</label>
                    <input id="password" placeholder="Enter your password" class="form_style" type="password" name="password" autocomplete="current-password" required>
                    <span class="error-message"></span>
                </div>
                <div Class="form_group"> 
                    <button class="btn" type="submit" name="login">SIGN IN</button>
                </div>
                    <div Class="form_group">    
                    <button class="btn" type="button" onclick="window.location.href='register_user_student.php'">SIGN UP</button></p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

<?php
    
    unset($_SESSION['username']);
    

    if (isset($_POST["login"])){
        $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);
        // $_POST["email"];
        // $password = $_POST["password"];

        if (empty($username)){
            echo "<div>Please Enter Username</div>";
        }
        elseif (empty($password)){
            echo "<div>Please Enter Password</div>";
        }
        else{

            // FOR TESTING ONLY DELETE LATER
            echo "Username is: {$username} and your Password is: {$password}<br>";

            if (check_user_in_db($username, $conn)){
                echo "User '$username' is in the Database<br>";
                //$type = check_user_type($username, $conn);
                $_SESSION["username"] = $username;

               // jump_to_site($type);
                /*
                $user_row = mysqli_fetch_assoc(get_rows_from_table_where("user", "email", $email, $conn));
                $hash = password_hash($password, PASSWORD_DEFAULT);
                echo $hash;
                if ($hash == $user_row["pass"]){
                    jump_to_site($type);

                }
                else{
                    echo "Wrong Password";
                }
                */

               // echo"$type";
                
                
            }
            else{
                echo "User '$username' is not in the Database<br>";
                echo "Would you like to ";
                echo "<a href='register_user_student.php' title='Sign Up'>Sign up</a>?";
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