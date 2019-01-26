<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="loginpage.css" media="screen">
</head>
<body class="background">

<?php
    session_start();
    session_destroy();
?>

<div class="login">
    <form action="registerOrLogin.php" method="post">
        <label>Login:</label>
        <br><br/>
        <label for="login_username" class='loginfont'>username</label>
        <input type="text" id="login_username" name="login_username">
        <br><br/>
        <label for="login_password" class='loginfont'>password</label>
        <input type="password" id="login_password" name="login_password">
        <br><br/>
        <input type="submit" id="login" value="login" name="login">
        <br/>
        <label>Register:</label>
        <br><br/>
        <label for="register_username" class='regfont'>username</label>
        <input type="text" id="register_username" name="register_username">
        <br><br/>
        <label for="register_password" class='regfont'>password</label>
        <input type="password" id="register_password" name="register_password">
        <br><br/>
        <input type="submit" id="register" value="register" name="register">
        <br/>
    </form>
</div>
<br><br/>
<div class="default">
    <form action="main.php" method="post">
        <label>You can view stories and comments as visitors</label>
        <br>
        <input type="submit" name="visitor_view" id="visitor_view" value="visitor_view">
    </form>
</div>
<br><br/>
</body>
</html>