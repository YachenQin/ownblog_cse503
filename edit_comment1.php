<?php

require "database.php";
session_start();
$comments_id=$_POST["comments_id"];
$edit_comments=$_POST["contents"];
if(!hash_equals($_SESSION['token'], $_POST['token'])){
	die("Request forgery detected");}
$stmt=$mysqli->prepare("UPDATE comments SET comments.comments='$edit_comments' WHERE comments.comments_id='$comments_id';");
if(!$stmt){
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}
$stmt->execute();
$stmt->close();
header("Location:main.php");
?>