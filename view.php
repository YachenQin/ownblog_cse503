<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Article</title>
    <link rel="stylesheet" type="text/css" href="view.css" media="screen">
</head>
<body class='background'>
<?php

session_start();
require "database.php";


$story_id=$_POST['story_id'];
$stmt_content=$mysqli->prepare("select story_name,username,contents from stories where story_id = ?");
if(!$stmt_content){
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}
$stmt_content->execute();
$stmt_content -> bind_param('i', $story_id);
$stmt_content -> execute();
$stmt_content -> bind_result($story_name,$author,$contents);
$stmt_content ->fetch();




if($_POST["visitor_view"]==0){
//    visitor view
    echo"<div class='title'>$story_name</div>
        <div class='author'>author:$author</div>
        <div class='contents'>$contents</div>
        ";
    $stmt_content ->close();
    $stmt_flower=$mysqli->prepare("select count(count) from flowers where story_id = ?");
    if(!$stmt_flower){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $stmt_flower->execute();
    $stmt_flower->bind_param('i', $story_id);
    $stmt_flower->execute();
    $stmt_flower->bind_result($numofflowers);
    $stmt_flower->fetch();
    
    echo"<div class='like'>This article has $numofflowers flowers </div>
        <img src='flower.jpg' alt='flower' style='width:20px;height:20px'>";
    
    $stmt_flower->close();
    
    
    $stmt=$mysqli->prepare("select comments.comments_id,comments.comments,comments.username from comments where comments.story_id = ?");
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }   
    $stmt->execute();
    $stmt -> bind_param('i', $story_id);
    $stmt -> execute();
    $stmt -> bind_result($comment_id,$comment,$commentator); 

    echo "<table>\n";
    echo "<tr><th>commentator</th><th>comments</th></tr>";
    while($stmt->fetch()){
        echo sprintf("<tr>");
        echo sprintf("<td>%s</td>",$commentator);
        echo sprintf("<td>%s</td>",$comment);
        echo sprintf("</tr>");
    }
    echo "</table>\n";
    $stmt ->close();
    
    echo sprintf("<br>");
    echo sprintf("<a href=\"main.php\">Back to first page</a>");
    
}
else{

//this part shows comments
    echo"<div class='title'>$story_name</div>
        <div class='author'>author:$author</div>
        <div class='contents'>$contents</div>
        ";
    
    $token=$_SESSION['token'];
    
    if($author==$_SESSION["username"]){
    echo "<div>
            <form action = 'edit.php' method = 'post'>
                <input type = 'hidden' name = 'story_id' id = 'story_id' value = '$story_id'/>
                <input type = 'hidden' name = 'token' id='token' value = '$token'>
                <input class='editstorybutton' type = 'submit' value = 'Edit'/>
			</form>
		</div>";

    echo "<div>
            <form action = 'delete.php' method = 'get'>
				<input type = 'hidden' name = 'story_id' id = 'story_id' value = '$story_id'/>
                <input type = 'hidden' name = 'token' id='token' value = '$token'>
				<input class='deletestorybutton' type = 'submit' value = 'Delete'/>
			</form>
		</div>";
    }
    $stmt_content ->close();
    
    
    $stmt_flower=$mysqli->prepare("select count(count) from flowers where story_id = ?");
    if(!$stmt_flower){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $stmt_flower->execute();
    $stmt_flower->bind_param('i', $story_id);
    $stmt_flower->execute();
    $stmt_flower->bind_result($numofflowers);
    $stmt_flower->fetch();
    
    echo "<div>
            <form action = 'giveflower.php' method = 'post'>
				<input type = 'hidden' name = 'story_id' id = 'story_id' value = '$story_id'/>
                <input type = 'hidden' name = 'token' id='token' value = '$token'>
                <div class='like'>This article has $numofflowers flowers </div>
                <img src='flower.jpg' alt='flower' style='width:20px;height:20px'>
                <input class='likebutton' type = 'submit' value = 'Giveflower'/>
			</form>
		</div>";
    $stmt_flower->close();    
    

    $stmt=$mysqli->prepare("select comments.comments_id,comments.comments,comments.username from comments where comments.story_id = ?");
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }   
    $stmt->execute();
    $stmt -> bind_param('i', $story_id);
    $stmt -> execute();
    $stmt -> bind_result($comments_id,$comment, $commentator);
    $stmt -> execute();
    
    echo "<table>\n";
    echo "<tr><th>commentator</th><th>comments</th><th>edit</th><th>delete</th></tr>";
    while($stmt->fetch()){
        echo sprintf("<tr>");
        echo sprintf("<td>%s</td>",$commentator);
        echo sprintf("<td>%s</td>",$comment);


        if($commentator==$_SESSION["username"]){
        echo "<td>
                <form action = 'edit_comment.php' method = 'POST'>
                    <input type = 'hidden' name = 'comments_id' id = 'comments_id' value = '$comments_id'/>
                    <input type = 'hidden' name = 'token' id='token' value = '$token'>
                    <input type = 'hidden' name = 'contents' id = 'contents' value = '$comment'/>
                    <input class='editstorybutton' type = 'submit' value = 'Edit'/>
                </form>
            </td>";

        echo "<td>
                <form action = 'delete_comment.php' method = 'POST'>
                    <input type = 'hidden' name = 'comments_id' id = 'comments_id' value = '$comments_id'/>
                    <input type = 'hidden' name = 'token' id='token' value = '$token'>
                    <input class='deletestorybutton' type = 'submit' value = 'Delete'/>
                </form>
            </td>";
        }
        echo sprintf("</tr>");
    }
    echo "</table>\n";

//    users view

//    users upload comments
    echo sprintf("<label class=\"upload\">comments here:</label>");
    echo sprintf("<form action=\"upload_comment.php\" method=\"get\">
    <label for=\"upload_comment\"></label>
    <input type=\"hidden\" name=\"comment_user\" value=%s>
    <input type = 'hidden' name = 'token' id='token' value = '$token'>
    <input type=\"hidden\" name=\"story_id\" value=%d>
    <input type=\"text\" class=\"input\" id=\"upload_comment\" name=\"upload_comment\">
    <br>
    <input type=\"submit\" value=\"upload comment\">
</form>",$_SESSION["username"],$_POST["story_id"]);
    
    $stmt->close();
    
    echo sprintf("<br>");
    echo sprintf("<a href=\"main.php\">Back to first page</a>");
   
}



?>
</body>
</html>