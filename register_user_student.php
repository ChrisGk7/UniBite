<?php
include ("database.php" );
// ---- Default (empty) error messages for every field ----
$error_reg_username  = "";
$error_reg_name      = "";
$error_reg_email     = "";
$error_reg_password1 = "";
$error_reg_password2 = "";
$error_reg_street    = "";
$error_reg_snumber   = "";
$error_reg_city      = "";
$error_reg_postcode  = "";
$error_reg_cell      = "";
$register_success    = "";

$error_signin_username = "";
$error_signin_password = "";
$signin_general_error  = "";

// Keep submitted values so fields aren't wiped out on a failed submit
$old = [
    'username' => '', 'name' => '', 'email' => '', 'street' => '',
    'snumber' => '', 'city' => '', 'postcode' => '', 'cell' => '',
    'signin_username' => ''
];

// Which form should be visible after the page reloads
$show_signup = false;

// ---------------------------------------------------------------
// REGISTRATION
// ---------------------------------------------------------------
if (isset($_POST['register'])) {
    $show_signup = true;

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

    $isValid = true;

    if ($username === '') {
        $error_reg_username = "Please enter a valid username";
        $isValid = false;
    } elseif (strlen($username) < 3) {
        $error_reg_username = "Username must be at least 3 characters";
        $isValid = false;
    } elseif (check_user_in_db($username, $conn)) {
        $error_reg_username = "This username is already taken";
        $isValid = false;
    }

    if ($name === '') {
        $error_reg_name = "Please enter your full name";
        $isValid = false;
    }

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_reg_email = "Please enter a valid email address";
        $isValid = false;
    }

    if ($password1 === '') {
        $error_reg_password1 = "Please enter a valid password";
        $isValid = false;
    } elseif (strlen($password1) < 8) {
        $error_reg_password1 = "Password must be at least 8 characters";
        $isValid = false;
    }

    if ($password2 === '' ) {
        $error_reg_password2 = "Please confirm your password";
        $isValid = false;
    } elseif ($password1 !== $password2) {
        $error_reg_password2 = "Passwords do not match";
        $isValid = false;
    }

    if ($street === '') {
        $error_reg_street = "Please enter a valid street";
        $isValid = false;
    }

    if ($snumber === '' || !ctype_digit($snumber)) {
        $error_reg_snumber = "Please enter a valid street number";
        $isValid = false;
    }

    if ($city === '') {
        $error_reg_city = "Please enter a valid city";
        $isValid = false;
    }

    if ($postcode === '' || !preg_match('/^\d{5}$/', $postcode)) {
        $error_reg_postcode = "Please enter a valid 5-digit postcode";
        $isValid = false;
    }

    if ($cell === '' || !preg_match('/^\d{10}$/', $cell)) {
        $error_reg_cell = "Please enter a valid 10-digit mobile number";
        $isValid = false;
    }

    if ($isValid) {
        if (register_user($username, $name, $email, $password1, $conn)) {
            $register_success = "Registration successful. You can now log in.";
            $show_signup = false; // send them back to the sign-in form
            $old = ['username' => '', 'name' => '', 'email' => '', 'street' => '',
                    'snumber' => '', 'city' => '', 'postcode' => '', 'cell' => '',
                    'signin_username' => ''];
        } else {
            $error_reg_username = "Something went wrong. Please try again.";
        }
    }
}

// ---------------------------------------------------------------
// LOGIN
// ---------------------------------------------------------------
if (isset($_POST['login'])) {
    $signin_username = trim($_POST['username'] ?? '');
    $signin_password = $_POST['password'] ?? '';
    $old['signin_username'] = $signin_username;

    $isValid = true;

    if ($signin_username === '') {
        $error_signin_username = "Please enter a valid username";
        $isValid = false;
    }
    if ($signin_password === '') {
        $error_signin_password = "Please enter a valid password";
        $isValid = false;
    }

    if ($isValid) {
        $user = get_user_by_username($signin_username, $conn);
        if ($user && password_verify($signin_password, $user['pass'])) {
            session_start();
            $_SESSION['username'] = $user['username'];
            $_SESSION['name']     = $user['name'];
            $_SESSION['email']    = $user['email'];
            header("Location: student.php");
            exit();
        } else {
            // Deliberately vague: don't reveal whether the username or
            // the password was the one that was wrong.
            $signin_general_error = "Invalid username or password.";
        }
    }
}
?>  