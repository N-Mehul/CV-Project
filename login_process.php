<?php
session_start();
include "db.php";

$email = $_POST['email'];
$pass  = $_POST['password'];

$result = mysqli_query($conn,"SELECT * FROM users WHERE email='$email'");

if(mysqli_num_rows($result)==1){

    $row = mysqli_fetch_assoc($result);

    if(password_verify($pass,$row['password'])){

        $_SESSION['user_id'] = $row['id'];
        $_SESSION['user'] = $row['name'];
        $_SESSION['uid'] = $row['id'];
        $_SESSION['role'] = 'user';
        header("Location: dashboard.php");

    }else{
        header("Location: login.php?msg=Wrong password");
    }

}else{
    header("Location: login.php?msg=User not found");
}
?>
