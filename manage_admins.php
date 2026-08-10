<?php
session_start();
require_once("database.php");

// Must be logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Must already BE an admin to add another admin
if (!is_admin($_SESSION['username'], $conn)) {
    header("Location: index.php");
    exit();
}

$error_message = null;
$success_message = null;

if (isset($_POST['add_admin'])) {
    $username  = trim($_POST['username'] ?? '');
    $email     = trim($_POST['email'] ?? '');
    $name      = trim($_POST['name'] ?? '');
    $password1 = $_POST['password1'] ?? '';
    $password2 = $_POST['password2'] ?? '';

  if (check_user_in_db($username, $conn)) {
        $error_message = "That username is already taken.";
    } elseif (!register_user($username, $email, $password1, $name, $conn)) {
        $error_message = "Error creating user: " . mysqli_error($conn);
    } elseif (!register_admin($username, $email, $conn)) {
        $error_message = "User was created, but granting admin role failed: " . mysqli_error($conn);
    } else {
        $success_message = "Admin account '{$username}' created successfully.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add an Admin</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="form_area">
        <p class="title">Add an Admin</p>

        <?php if ($error_message): ?>
            <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
        <?php endif; ?>
        <?php if ($success_message): ?>
            <p class="success-message"><?php echo htmlspecialchars($success_message); ?></p>
        <?php endif; ?>

        <form id="addAdminForm" method="post" novalidate>
            <div class="form_group">
                <label class="sub_title" for="reg_username">Username</label>
                <input id="reg_username" data-error="Please enter a valid username" class="form_style" type="text" name="username" autocomplete="username" required>
                <span class="error-message" aria-live="polite"></span>
            </div>
            <div class="form_group">
                <label class="sub_title" for="name">Full Name</label>
                <input id="name" data-error="Please enter a full name" class="form_style" type="text" name="name" autocomplete="name" required>
                <span class="error-message" aria-live="polite"></span>
            </div>
            <div class="form_group">
                <label class="sub_title" for="email">Email</label>
                <input id="email" data-error="Please enter a valid email address" class="form_style" type="email" name="email" autocomplete="email" required>
                <span class="error-message" aria-live="polite"></span>
            </div>
            <div class="form_group">
                <label class="sub_title" for="reg_password1">Password</label>
                <input minlength="8" id="reg_password1" data-error="Password must be at least 8 characters" class="form_style" type="password" name="password1" autocomplete="new-password" required>
                <span class="error-message" aria-live="polite"></span>
            </div>
            <div class="form_group">
                <label class="sub_title" for="reg_password2">Confirm Password</label>
                <input minlength="8" id="reg_password2" data-error="Passwords do not match" class="form_style" type="password" name="password2" autocomplete="new-password" required>
                <span class="error-message" aria-live="polite"></span>
            </div>
            <div class="form_group">
                <button class="btn" type="submit" name="add_admin">CREATE ADMIN</button>
            </div>
            <div class="form_group">
                <button class="btn" type="button" onclick="window.location.href='admin_dashboard.php'">BACK TO DASHBOARD</button>
            </div>
        </form>
    </div>

    <script src="form.js" defer></script>
</body>
</html>