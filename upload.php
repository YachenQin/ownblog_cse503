<?php

require 'database.php';
session_start();
$story_name=$_GET['story_name'];
$link=$_GET['link'];
$contents=$_GET['contents'];
$username=$_SESSION['username'];

if(!hash_equals($_SESSION['token'], $_GET['token'])){
	die("Request forgery detected");}

$stmt=$mysqli->prepare("insert into stories(username,story_name,link,contents) values (?,?,?,?)");
if(!$stmt){
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}
$stmt->bind_param('ssss',$username,$story_name,$link,$contents);
$stmt->execute();
$stmt->close();
header("Location:upload_success.html");
?>