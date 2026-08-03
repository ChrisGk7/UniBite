
<?php
include ("register_user_student.php");
include ("header.html");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in</title>
    <link rel="stylesheet" href="style.css">
    
</head>
<body>
    <div id="signupContainer" class="container" style="display: <?php echo $show_signup ? 'block' : 'none'; ?>;">
        <div class="form_area">
            <p class="title">UNIBITE</p>
            <p class="title">Register</p>
          
            <form id="signupForm" action="index.php" method="post" style="display: <?php echo $show_signup ? 'block' : 'none'; ?>;" novalidate>

                <div class="form_group">
                    <label class="sub_title" for="reg_username">Username</label>
                    <input id="reg_username" data-error="Please enter a valid username" class="form_style" type="text" name="username" value="<?php echo htmlspecialchars($old['username']); ?>" autocomplete="username" required>
                    <span class="error-message" aria-live="polite"><?php echo htmlspecialchars($error_reg_username); ?></span>
                </div>
                <div class="form_group">
                    <label class="sub_title" for="reg_name">Full Name</label>
                    <input id="reg_name" data-error="Please enter your full name" class="form_style" type="text" name="name" value="<?php echo htmlspecialchars($old['name']); ?>" placeholder="John Doe" autocomplete="name" required>
                    <span class="error-message" aria-live="polite"><?php echo htmlspecialchars($error_reg_name); ?></span>
                </div>
                <div class="form_group">
                    <label class="sub_title" for="reg_email">Email</label>
                    <input id="reg_email" data-error="Please enter a valid email address" class="form_style" type="email" name="email" value="<?php echo htmlspecialchars($old['email']); ?>" placeholder="upXXXXXXX@upnet.gr" autocomplete="email" required>
                    <span class="error-message" aria-live="polite"><?php echo htmlspecialchars($error_reg_email); ?></span>
                </div>
                <div class="form_group">
                    <label class="sub_title" for="reg_password1">Password</label>
                    <input id="reg_password1" data-error="Please enter a valid password" class="form_style" type="password" name="password1" autocomplete="new-password" required>
                    <span class="error-message" aria-live="polite"><?php echo htmlspecialchars($error_reg_password1); ?></span>
                </div>
                <div class="form_group">
                    <label class="sub_title" for="reg_password2">Confirm Password</label>
                    <input id="reg_password2" data-error="Please enter a valid password" class="form_style" type="password" name="password2" autocomplete="new-password" required>
                    <span class="error-message" aria-live="polite"><?php echo htmlspecialchars($error_reg_password2); ?></span>
                </div>
                <div class="form_group">
                    <label class="sub_title" for="reg_street">Street</label>
                    <input id="reg_street" data-error="Please enter a valid street" class="form_style" type="text" name="street" value="<?php echo htmlspecialchars($old['street']); ?>" placeholder="Based Street" autocomplete="street-address" required>
                    <span class="error-message" aria-live="polite"><?php echo htmlspecialchars($error_reg_street); ?></span>
                </div>
                <div class="form_group">
                    <label class="sub_title" for="reg_snumber">Street Number</label>
                    <input id="reg_snumber" data-error="Please enter a valid street number" class="form_style" type="text" name="snumber" value="<?php echo htmlspecialchars($old['snumber']); ?>" placeholder="69" autocomplete="address-line1" required>
                    <span class="error-message" aria-live="polite"><?php echo htmlspecialchars($error_reg_snumber); ?></span>
                </div>
                <div class="form_group">
                    <label class="sub_title" for="reg_city">City</label>
                    <input id="reg_city" data-error="Please enter a valid city" class="form_style" type="text" name="city" value="<?php echo htmlspecialchars($old['city']); ?>" placeholder="Patras" autocomplete="address-level2" required>
                    <span class="error-message" aria-live="polite"><?php echo htmlspecialchars($error_reg_city); ?></span>
                </div>
                <div class="form_group">
                    <label class="sub_title" for="reg_postcode">Postcode</label>
                    <input id="reg_postcode" data-error="Please enter a valid postcode" class="form_style" type="text" name="postcode" value="<?php echo htmlspecialchars($old['postcode']); ?>" placeholder="26XXX" autocomplete="postal-code" required>
                    <span class="error-message" aria-live="polite"><?php echo htmlspecialchars($error_reg_postcode); ?></span>
                </div>
                <div class="form_group">
                    <label class="sub_title" for="reg_cell">Mobile Number</label>
                    <input id="reg_cell" data-error="Please enter a valid mobile number" class="form_style" type="text" name="cell" value="<?php echo htmlspecialchars($old['cell']); ?>" placeholder="694XXXXXXX" autocomplete="tel" required>
                    <span class="error-message" aria-live="polite"><?php echo htmlspecialchars($error_reg_cell); ?></span>
                </div>
                <?php if ($signin_general_error): ?>
                <p class="error-message" aria-live="polite"><?php echo htmlspecialchars($signin_general_error); ?></p>
                <?php endif; ?>
                <div class="form_group">
                    <button id="signupSubmitButton" class="btn" type="submit" name="register">SIGN UP</button>
                </div>
            </form>
        </div>
    </div>

    <div id="signinContainer" class="container" style="display: <?php echo $show_signup ? 'none' : 'block'; ?>;">
        <div class="form_area">
            <p class="title">UNIBITE</p>
            
            <form id="signinForm" action="index.php" method="post" novalidate>

                <div class="form_group">
                    <label class="sub_title" for="signin_username">Username</label>
                    <input id="signin_username" data-error="Please enter a valid username" class="form_style" type="text" name="username" value="<?php echo htmlspecialchars($old['signin_username']); ?>" autocomplete="username" required>
                    <span class="error-message" aria-live="polite"><?php echo htmlspecialchars($error_signin_username); ?></span>
                </div>
                <div class="form_group">
                    <label class="sub_title" for="signin_password">Password</label>
                    <input id="signin_password" data-error="Please enter a valid password" class="form_style" type="password" name="password" autocomplete="current-password" required>
                    <span class="error-message" aria-live="polite"><?php echo htmlspecialchars($error_signin_password); ?></span>
                </div>
                <?php if ($register_success): ?>
                <p class="success-message"><?php echo htmlspecialchars($register_success); ?></p>
                <?php endif; ?>
                <div class="form_group">
                    <button id="signinButton" class="btn" type="submit" name="login">SIGN IN</button>
                </div>
                <div class="form_group">
                    <button id="signupButton" class="btn" type="button">SIGN UP</button>
                </div>
            </form>
        </div>
    </div>

    <script src="form.js" defer></script>
</body>

</html>