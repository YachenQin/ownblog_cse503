<?php


require "database.php";
session_start();
$story_id=$_POST["story_id"];

if(!hash_equals($_SESSION['token'], $_POST['token'])){
	die("Request forgery detected");}

$stmt=$mysqli->prepare("INSERT INTO flowers VALUES ('NULL','$story_id', '1')");
if(!$stmt){
    printf("Query Prep Failed: %s\n", $mysqli->error);
    echo 1;
    exit;
}
$stmt->execute();
$stmt->close();

header("Location:main.php");
?>