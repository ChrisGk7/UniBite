<?php
require_once("database.php");

header("Content-Type: application/json");

//Δεχεται μονο POST request
if($_SERVER["REQUEST_METHOD"]!=="POST"){
    echo json_encode(["success"=>false, "message"=>"Invalid request method"]);
    exit;
}

$title = trim($_POST["title"]);
$description = trim($_POST["description"]);
$allergens = trim($_POST["allergens"]);

$pickup_location = trim($_POST["pickup_location"]);
$pickup_time_input = $_POST["pickup_time"];

$portions = (int) $_POST["portions"];


$photo_url=null;
if(!empty($_FILES["image"]["name"])){
    $file_name = $_FILES["image"]["name"];
    $photo_url="uploads/".$file_name;

    move_uploaded_file($_FILES["image"]["tmp_name"],$photo_url);
}


//convert datetime for the correct saving.DATETIME
$pickup_time = date("Y-m-d H:i:s",strtotime($pickup_time_input));

$cook="testcook";// PROSORINO

create_dish($cook,$title,$description,$allergens,$photo_url,$pickup_location,$pickup_time,$portions,$conn);


echo json_encode(["success"=>true, "message"=>"Dish created succesfully"]);

?>