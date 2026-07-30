<?php

    session_start();
    include("header.html");
    include("database.php");

    $registerError = "";

    if (isset($_POST["register"])) {

        $username  = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
        $email     = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
        $name      = filter_input(INPUT_POST, "name", FILTER_SANITIZE_SPECIAL_CHARS);
        $street    = filter_input(INPUT_POST, "street", FILTER_SANITIZE_SPECIAL_CHARS);
        $snumber   = filter_input(INPUT_POST, "snumber", FILTER_SANITIZE_SPECIAL_CHARS);
        $city      = filter_input(INPUT_POST, "city", FILTER_SANITIZE_SPECIAL_CHARS);
        $postcode  = filter_input(INPUT_POST, "postcode", FILTER_SANITIZE_SPECIAL_CHARS);
        $mobile    = filter_input(INPUT_POST, "cell", FILTER_SANITIZE_SPECIAL_CHARS);

        // Don't sanitize passwords — password_hash() needs the exact raw
        // characters, or a password with special characters in it will never
        // verify correctly again at login time.
        $password1 = $_POST["password1"] ?? "";
        $password2 = $_POST["password2"] ?? "";

        if (empty($username) || empty($email) || empty($name)) {
            $registerError = "Please fill in all required fields.";
        }
        elseif (empty($password1) || empty($password2)) {
            $registerError = "Please enter and confirm your password.";
        }
        elseif ($password1 !== $password2) {
            $registerError = "Passwords don't match.";
        }
        else {
            try {
                register_user($username, $email, $password1, $name, $conn);

                try {
                    register_student($username, $email, $street, $snumber, $city, $postcode, $mobile, $conn);

                    // Both inserts succeeded — send them to log in.
                    header("Location: index.php");
                    exit;
                }
                catch (mysqli_sql_exception $e) {
                    // student insert failed (e.g. duplicate) — roll back the user row
                    // we just created so we don't leave an orphaned account behind.
                    delete_table_row("user", "username", $username, $conn);
                    $registerError = "Could not complete registration. Please check your details and try again.";
                }
            }
            catch (mysqli_sql_exception $e) {
                $registerError = "An account with that username or email already exists.";
            }
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register as Student</title>
    <link rel="stylesheet" href="style.css">
    <script src="form.js" defer> </script>
</head>
<body>
    <div id="login_page">    
        <div class="form_area">
            <p class="title">UNIBITE</p>
            <p class="title">Register as Student</p>
        <form id="loginForm" action="register_user_student.php" method="post">
            <?php if ($registerError): ?>
                <p class="server-error" role="alert"><?= htmlspecialchars($registerError) ?></p>
            <?php endif; ?>
            <div class="form_group">
                    <label class="sub_title" for="username">Username</label>
                    <input id="username" data-error="Please enter a valid username" class="form_style" type="text" name="username" autocomplete="username" required>
                    <span class="error-message" aria-live="polite"></span>
            </div>
            <div class="form_group">
                <label class="sub_title" for="name">Full Name</label>
                <input id="name" data-error="Please enter your full name" class="form_style" type="text" name="name" placeholder="John Doe" autocomplete="name" required>
                <span class="error-message" aria-live="polite"></span>
            </div>
            <div class="form_group">
                <label class="sub_title" for="email">Email</label>
                <input id="email" data-error="Please enter a valid email address" class="form_style" type="email" name="email" placeholder="upXXXXXXX@upnet.gr" autocomplete="email" required>
                <span class="error-message" aria-live="polite"></span>
            </div>
            <div class="form_group">
                <label class="sub_title" for="password1">Password</label>
                <input id="password1" data-error="Please enter a valid password" class="form_style" type="password" name="password1" autocomplete="new-password" required>
                <span class="error-message" aria-live="polite"></span>
            </div>
            <div class="form_group">
                <label class="sub_title" for="password2">Confirm Password</label>
                <input id="password2" data-error="Please enter a valid password" class="form_style" type="password" name="password2" autocomplete="new-password" required>
                <span class="error-message" aria-live="polite"></span>
            </div>
            <div class="form_group">
                <label class="sub_title" for="street">Street</label>
                <input id="street" data-error="Please enter a valid street" class="form_style" type="text" name="street" placeholder="Based Street" autocomplete="street-address" required>
                <span class="error-message" aria-live="polite"></span>
            </div>
            <div class="form_group">
                <label class="sub_title" for="snumber">Street Number</label>
                <input id="snumber" data-error="Please enter a valid street number" class="form_style" type="text" name="snumber" placeholder="69" autocomplete="address-line1" required>
                <span class="error-message" aria-live="polite"></span>
            </div>
            <div class="form_group">
                <label class="sub_title" for="city">City</label>
                <input id="city" data-error="Please enter a valid city" class="form_style" type="text" name="city" placeholder="Patras" autocomplete="address-level2" required>
                <span class="error-message" aria-live="polite"></span>
            </div>
            <div class="form_group">
                <label class="sub_title" for="postcode">Postcode</label>
                <input id="postcode" data-error="Please enter a valid postcode" class="form_style" type="text" name="postcode" placeholder="26XXX" autocomplete="postal-code" required>
                <span class="error-message" aria-live="polite"></span>
            </div>
            <div class = "form_group">
                <label class="sub_title" for="cell">Mobile Number</label>
                <input id="cell" data-error="Please enter a valid mobile number" class="form_style" type="text" name="cell" placeholder="694XXXXXXX" autocomplete="tel" required>
                <span class="error-message" aria-live="polite"></span>
            </div>
          <div class="form_group">
                    <button class="btn" type="submit" name="register">SIGN UP</button>
                </div>
        </form>
    </div>
</body>
</html>

<?php
    try {
        mysqli_close($conn);
    }
    catch (TypeError $e) {
        // ignore if $conn is not a valid connection
    }
?>