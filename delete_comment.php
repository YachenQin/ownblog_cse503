<?php


require "database.php";
session_start();
$comments_id=$_POST["comments_id"];

if(!hash_equals($_SESSION['token'], $_POST['token'])){
	die("Request forgery detected");}

$stmt=$mysqli->prepare("delete from comments where comments.comments_id='$comments_id'");
if(!$stmt){
    printf("Query Prep Failed: %s\n", $mysqli->error);
    echo 1;
    exit;
}
$stmt->execute();
$stmt->close();

header("Location:main.php");
?>