<?php
require 'database.php';
session_start();

if(isset($_POST['login'])){
//    if input is valid
    if((!empty($_POST['login_username']))&&(!empty($_POST['login_password']))){
        $login_username=$_POST['login_username'];
        $login_password=$_POST['login_password'];
        //get password
        $stmt=$mysqli->prepare("select count(*), hash_password from users where username=?");
        if(!$stmt){
            echo sprintf("Query Prep Failed:%s\n",$mysqli->error);
            header("Location:registerAndLoginError.html");
            exit;
        }
        $stmt->bind_param('s',$login_username);
        $stmt->execute();
        $stmt->bind_result($cnt,$correct_password);
        $stmt->fetch();
        
        
        if($cnt==1 && password_verify($login_password,$correct_password)){
            $_SESSION['username']=$login_username;
            $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
            header("Location:main.php");
        }
        
        else{
            session_destroy();
            echo"<a href='registerAndLoginError.html'>something wrong</a>";
            header("Locaiton: registerAndLoginError.html");
        }
    }
//    if input is invalid
    else{
        header("Location: registerAndLoginError.html");
    }
}
else{
//    if input is valid
    if((!empty($_POST['register_username']))&&(!empty($_POST['register_password']))){
        $register_username=$_POST['register_username'];
        $register_password=$_POST['register_password'];
        $pass_word_length=strlen($register_password);
        $username_length=strlen($register_username);
        
        if($pass_word_length<6 || $pass_word_length>15 || $username_length<6 || $username_length>30 ){
            header("Location: registerAndLoginError.html");
        }
        
        
        $hash_password=password_hash($register_password,PASSWORD_DEFAULT);
        $stmt = $mysqli->prepare("SELECT COUNT(*) FROM users WHERE username=?");

        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
            header("Location: registerAndLoginError.html");
        }

        $stmt->bind_param('s',$register_username);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
//      check if the username has been used
        if ($count == 1) {
            header("Location: registerAndLoginError.html");
            }
        else{
            $stmt=$mysqli->prepare("insert into users (username,hash_password) values (?,?)");
            if(!$stmt){
                printf("Query Prep Failed:%s\n",$mysqli->error);
                header("Location: registerAndLoginError.html");
                exit;
            }
            $stmt->bind_param('ss',$register_username,$hash_password);
            $stmt->execute();
            $stmt->close();
            echo"register success!";
            header("Location:register_success.html");    
        }
    }
//    if input is invalid
    else{
        header("Location: registerAndLoginError.html");
    }
}
?>
