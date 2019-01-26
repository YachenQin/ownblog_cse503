<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="loginpage.css" media="screen">
</head>
<body>
<?php

require "database.php";
session_start();
$story_id=$_POST["story_id"];
$content=$_POST["story_id"];

$stmt=$mysqli->prepare("select contents from stories where story_id=?");
if(!$stmt){
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}
$stmt->execute();
$stmt -> bind_param('i', $story_id);
$stmt->execute();
$stmt -> bind_result($contents);
$stmt ->fetch();  


$token=$_SESSION['token'];
if(!hash_equals($_SESSION['token'], $_POST['token'])){
	die("Request forgery detected");}
echo sprintf(
    "<form action=\"edit_story.php\" method=\"post\">
    <label for=\"edit_story\">edit story</label>
    <textarea name = \"contents\" id = \"contents\" rows = \"8\" cols = \"80\">$contents</textarea>
    <input type = 'hidden' name = 'token' id='token' value = '$token'>
    <input type='hidden' id='story_id' name='story_id' value=%d>
    
    <br>
    <input type=\"submit\" value=\"edit_story\">  
</form>",$story_id);

$stmt->close();
?>
</body>
</html>