<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register error and login error</title>
</head>
<body>
<?php
$comments_id=$_POST["comments_id"];
$contents=$_POST["contents"];
$token=$_POST['token'];
echo sprintf(
    "<form action=\"edit_comment1.php\" method=\"post\">
    <label>edit comments</label>
    <textarea name = \"contents\" id = \"contents\" rows = \"8\" cols = \"80\">$contents</textarea>
    <input type='hidden' id='comments_id' name='comments_id' value=%d>
    <input type = 'hidden' name = 'token' id='token' value = '$token'>
    <br>
    <input type=\"submit\" value=\"edit_comments\">
</form>",$comments_id);
?>
</body>
</html>