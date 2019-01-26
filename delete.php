<?php


require "database.php";
session_start();
$story_id=$_GET["story_id"];
if(!hash_equals($_SESSION['token'], $_GET['token'])){
	die("Request forgery detected");}
//delete comments first
$stmt=$mysqli->prepare("delete from comments where comments.story_id= ? ");
if(!$stmt){
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}
printf($mysqli->error);
$stmt->execute();
$stmt->bind_param('i', $story_id);
$stmt->execute();
$stmt->fetch();
$stmt->close();


//delete story then
$stmtm=$mysqli->prepare("delete from stories where stories.story_id= ?");
if(!$stmtm){
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}
printf($mysqli->error);
$stmtm->execute();
$stmtm->bind_param('i', $story_id);
$stmtm->execute();
$stmtm->fetch();
$stmtm->close();
header("Location:main.php");
?>