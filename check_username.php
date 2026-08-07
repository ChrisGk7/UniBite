<?php
require_once("database.php");

header('Content-Type: application/json');

$username = $_GET['username'] ?? '';

if (empty($username)) {
    echo json_encode(['exists' => false]);
    exit;
}

// check_user_in_db expects ($username, $conn)
$exists = check_user_in_db($username, $conn);

echo json_encode(['exists' => (bool)$exists]);
?>