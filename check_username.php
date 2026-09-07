<?php
require_once("database.php");

header('Content-Type: application/json');

$username = $_GET['username'] ?? '';
$email = $_GET['email'] ?? '';

// Check Username if provided
if (!empty($username)) {
    $exists = check_user_in_db($username, $conn);
    echo json_encode(['exists' => (bool)$exists]);
    exit;
}

// Check Email if provided
if (!empty($email)) {
    $email_exists = check_user_email_in_db($email, $conn);
    echo json_encode(['exists' => (bool)$email_exists]);
    exit;
}

// Neither provided
echo json_encode(['exists' => false]);