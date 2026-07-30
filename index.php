<?php

    session_start();
    include("header.html");
    include("database.php");

    // Clear any stale session before evaluating this request.
    unset($_SESSION['username']);

    $loginError = "";

    if (isset($_POST["login"])) {
        $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
        // Don't sanitize the password before checking it — altering the raw
        // characters here would make password_verify() fail even for a
        // correct password if it contains special characters.
        $password = $_POST["password"] ?? "";

        $user = get_user_by_username($username, $conn);

        if (!$user) {
            $loginError = "User '$username' is not in the database.";
        } elseif (!password_verify($password, $user["pass"])) {
            $loginError = "Incorrect password.";
        } else {
            // Success: set the session and redirect.
            // This MUST happen before any HTML/echo output below,
            // otherwise header() will fail with "headers already sent".
            $_SESSION["username"] = $username;
            header('Location: student.php');
            //link_user_to_session($username, session_id(), $conn);
            exit;
        }
    }

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
    <div id="login_page">
        <div class="form_area">
            <p class="title">UNIBITE</p>
            <form id="loginForm" action="index.php" method="post" novalidate>
                <?php if ($loginError): ?>
                    <p class="server-error" role="alert"><?= htmlspecialchars($loginError) ?></p>
                <?php endif; ?>
                <div class="form_group">
                    <label class="sub_title" for="username">Username</label>
                    <input id="username" data-error="Please enter a valid username" class="form_style" type="text" name="username" autocomplete="username" required>
                    <span class="error-message" aria-live="polite"></span>
                </div>
                <div class="form_group">
                    <label class="sub_title" for="password">Password</label>
                    <input id="password" data-error="Please enter a valid password" class="form_style" type="password" name="password" autocomplete="current-password" required>
                    <span class="error-message" aria-live="polite"></span>
                </div>
                <div class="form_group">
                    <button class="btn" type="submit" name="login">SIGN IN</button>
                </div>
                <div class="form_group">
                    <button class="btn" type="button" onclick="window.location.href='register_user_student.php'">SIGN UP</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
<?php
    try {
        mysqli_close($conn);
    } catch (TypeError $e) {
        // ignore if $conn is not a valid connection
    }
?>