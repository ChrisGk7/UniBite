<?php
session_start();
require_once("database.php");

header('Content-Type: application/json');

if (!isset($_SESSION['username']) || !is_admin($_SESSION['username'], $conn)) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Not authorized']);
    exit;
}

$admins = get_all_admins($conn);

echo json_encode(['success' => true, 'admins' => $admins]);