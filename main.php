<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Main Page</title>
    <link rel="stylesheet" type="text/css" href="mainpage.css" media="screen">
</head>
<body class='background'>

<?php
require 'database.php';
session_start();
$stmt=$mysqli->prepare("select story_id,username,story_name,link,substring(contents,1,300) from stories order by story_id");
if(!$stmt){
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}
$stmt->execute();
$stmt->bind_result($story_id, $username, $story_name,$link,$subContent);

if(isset($_POST['visitor_view'])){
    $visitor_view=0;
    while($stmt->fetch()){
    echo"<div class='title'>$story_name</div>
        <div class='author'>author:$username</div>
        <div class='subContent'>$subContent</div>
        ";
    echo"<div class='link'> Link to full story:
            <form action = 'view.php' method = 'post'>
                <input type = 'hidden' name = 'story_id' value = '$story_id'>
                <input type = 'hidden' name = 'visitor_view' value = '$visitor_view'>
                <input class='linkbutton' type = 'submit' value = '$link'>
			</form>
		</div>";
    }
    $stmt->close();
    echo sprintf("<br>");
    echo sprintf("<a href=\"first_page.html\">Back to first page</a>");
}
else{
//    this part is responsible for listing details about stories
    $visitor_view=1;
    
    
    while($stmt->fetch()){
    echo"<div class='title'>$story_name</div>
        <div class='author'>author:$username</div>
        <div class='subContent'>$subContent</div>
        ";
    echo"<div class='link'> Link to full story:
            <form action = 'view.php' method = 'post'>
                <input type = 'hidden' name = 'story_id' id='story_id' value = '$story_id'>
                <input type = 'hidden' name = 'visitor_view' id = 'visitor_view' value = '$visitor_view'>
                <input class='linkbutton' type = 'submit' value = '$link'>
			</form>
		</div>";
    }
    $stmt->close();
    echo sprintf("<br>");
//    this part is responsible for uploading
    $token=$_SESSION['token'];
    echo sprintf("
    <form action=\"upload.php\" method=\"get\">
        <div> name </div>
        <input type=\"text\" class=\"nameinput\" name=\"story_name\" id=\"story_name\">
        <div> link </div>
        <input type=\"text\" class=\"linkinput\" name=\"link\" id=\"link\">
        <div> content </div>
        <input type=\"text\" class=\"contentinput\" name=\"contents\" id=\"contents\">
        <input type = 'hidden' name = 'token' value = '$token'>
        <input type=\"submit\" class=\"uploadbutton\" name=\"upload\" id=\"upload_submit\" value=\"upload\">
    </form>
    ");
    
    $flowerer=$_SESSION['username'];
    $stmt_flower=$mysqli->prepare("select count(count) from flowers join stories where stories.story_id=flowers.story_id
                                  and stories.username = ?");
    if(!$stmt_flower){
        printf("Query Prep Faileda: %s\n", $mysqli->error);
        exit;
    }
    $stmt_flower->execute();
    $stmt_flower -> bind_param('s',$flowerer);
    $stmt_flower -> execute();
    $stmt_flower -> bind_result($numofflowers);
    $stmt_flower ->fetch();
    
    echo"<div class='flowershow'> Now I have $numofflowers flowers</div>";
    
    $stmt_flower ->close();
//    logout.
    echo sprintf("<form action=\"first_page.php\" method=\"post\">
    <input type=\"submit\" name=\"logout\" value=\"logout\" id=\"logout\">");
    echo sprintf("</form>");
}

?>

</body>
</html>
