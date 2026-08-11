<?php
require_once "database.php";


header("Content-Type: application/json");

$cook = "testcook";// PROSORINO

$dishes= get_dishes_by_cook($cook,$conn);

echo json_encode(["success"=>true,"dishes"=>$dishes]);



?>