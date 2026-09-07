<?php
// create_admin.php
//Execute this script from the command line to create a new admin user.

if (php_sapi_name() !== 'cli') {
    http_response_code(403);
    die("This script can only be run from the command line.\n");
}

require_once __DIR__ . "/database.php";

if ($argc < 4) {
    echo "Usage: php create_admin.php <username> <email> <full name>\n";
    exit(1);
}

$username = $argv[1];
$email    = $argv[2];
$name     = $argv[3];

echo "Password: ";
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    
    $password = trim(fgets(STDIN));
} else {
    system('stty -echo'); 
    $password = trim(fgets(STDIN));
    system('stty echo');
    echo "\n";
}

if (strlen($password) < 8) {
    die("Password must be at least 8 characters.\n");
}

if (check_user_in_db($username, $conn)) {
    die("Error: a user with that username already exists.\n");
}

if (!register_user($username, $email, $password, $name, $conn)) {
    die("Error creating user: " . mysqli_error($conn) . "\n");
}

if (!register_admin($username, $email, $conn)) {
    die("Error granting admin role: " . mysqli_error($conn) . "\n");
}

echo "Admin account '$username' created successfully.\n";