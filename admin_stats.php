<?php
session_start();
require_once("database.php");

header('Content-Type: application/json');

if (!isset($_SESSION['username']) || !is_admin($_SESSION['username'], $conn)) {
    http_response_code(403);
    echo json_encode(['error' => 'Not authorized']);
    exit();
}

echo json_encode([
    'total_portions_last_month' => get_total_portions_last_month($conn),
    'top_donor'                 => get_top_donor($conn),
    'top_rated_dishes'          => get_top_rated_dishes($conn, 5),
]);
