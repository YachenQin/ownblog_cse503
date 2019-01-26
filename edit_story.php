<?php

require "database.php";
session_start();
$story_id=$_POST["story_id"];
$edit_story=$_POST["contents"];

if(!hash_equals($_SESSION['token'], $_POST['token'])){
	die("Request forgery detected");}

$stmt=$mysqli->prepare("UPDATE stories SET stories.contents=? WHERE stories.story_id = ?");
if(!$stmt){
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}
$stmt -> bind_param('si',$edit_story,$story_id);
$stmt->execute();
$stmt ->fetch();
$stmt->close();
header("Location:main.php");
?>