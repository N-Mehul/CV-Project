<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "db.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $name  = $_POST['name'];
    $email = $_POST['email'];
    $pass  = $_POST['password'];
    $cpass = $_POST['cpassword'];

    if($pass !== $cpass){
        die("Passwords do not match");
    }

    // Check email
    $check = mysqli_query($conn,"SELECT id FROM users WHERE email='$email'");

    if(mysqli_num_rows($check) > 0){
        die("Email already exists");
    }

    // Hash password
    $hash = password_hash($pass, PASSWORD_DEFAULT);

    // Insert user
    $query = "INSERT INTO users(name,email,password,role)
              VALUES('$name','$email','$hash','user')";

    if(mysqli_query($conn,$query)){
        header("Location: login.php?msg=Registration successful");
    }else{
        die("Insert Error: ".mysqli_error($conn));
    }

}else{
    die("Invalid Request");
}
?>
