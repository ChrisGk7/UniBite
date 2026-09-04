<?php
require_once("database.php");


// Keep submitted values so fields aren't wiped out on a failed submit
$old = [
    'username' => '', 'name' => '', 'email' => '', 'street' => '',
    'snumber' => '', 'city' => '', 'postcode' => '', 'cell' => '',
    'signin_username' => ''
];


//................ REGISTRATION..................

if (isset($_POST['register'])) {


    $username  = trim($_POST['username'] ?? '');
    $name      = trim($_POST['name'] ?? '');
    $email     = trim($_POST['email'] ?? '');
    $password1 = $_POST['password1'] ?? '';
    $password2 = $_POST['password2'] ?? '';
    $street    = trim($_POST['street'] ?? '');
    $snumber   = trim($_POST['snumber'] ?? '');
    $city      = trim($_POST['city'] ?? '');
    $postcode  = trim($_POST['postcode'] ?? '');
    $cell      = trim($_POST['cell'] ?? '');

    $old = compact('username', 'name', 'email', 'street', 'snumber', 'city', 'postcode', 'cell');

    

        if (register_user($username, $email, $password1, $name, $conn)) {
            
           if(register_student($username, $email, $street, $snumber, $city, $postcode, $cell, $conn)) {
                $old = ['username' => '', 'name' => '', 'email' => '', 'street' => '',
                        'snumber' => '', 'city' => '', 'postcode' => '', 'cell' => '',
                        'signin_username' => ''];
                header("Location: index.php?success=1");
            
            }
        } 
        else {
            echo "Error: " . mysqli_error($conn);
        }    

}
// ---------------------------------------------------------------
// LOGIN
// ---------------------------------------------------------------
if (isset($_POST['login'])) {
    $signin_username = trim($_POST['username'] ?? '');
    $signin_password = $_POST['password'] ?? '';
    $old['signin_username'] = $signin_username;

    

    
        $user = get_user_by_username($signin_username, $conn);
        if ($user && password_verify($signin_password, $user['pass'])) {
            session_start();
            $_SESSION['username'] = $user['username'];
            $_SESSION['name']     = $user['name'];
            $_SESSION['email']    = $user['email'];
            header("Location: index_stud.php");
            exit();
        } else {
            // Deliberately vague: don't reveal whether the username or
            // the password was the one that was wrong.
            $signin_general_error = "Invalid username or password.";
        }
    }

?>  