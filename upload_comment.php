<?php

require 'database.php';
session_start();
$comment_user=$_GET["comment_user"];
$story_id=$_GET["story_id"];
$upload_comment=$_GET["upload_comment"];

$stmt=$mysqli->prepare("insert into comments(username,story_id,comments) values (?,?,?)");
if(!$stmt){
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}
$stmt->bind_param('sds',$comment_user,$story_id,$upload_comment);
$stmt->execute();
$stmt->close();
header("Location:upload_success.html");
?>