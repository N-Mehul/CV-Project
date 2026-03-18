<?php
session_start();
include "db.php";

/* ================= REGISTER ================= */
if(isset($_POST['register'])){
    $name = mysqli_real_escape_string($conn,$_POST['name']);
    $email = mysqli_real_escape_string($conn,$_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $check = mysqli_query($conn,"SELECT id FROM users WHERE email='$email'");
    if(mysqli_num_rows($check) > 0){
        echo "<script>alert('Email already registered');</script>";
    }else{
        mysqli_query($conn,
        "INSERT INTO users (name,email,password,role)
         VALUES ('$name','$email','$password','$role')");
        echo "<script>alert('Registration successful. Please login');</script>";
    }
}

/* ================= LOGIN ================= */
if(isset($_POST['login'])){
    $email = mysqli_real_escape_string($conn,$_POST['email']);
    $password = $_POST['password'];

    $q = mysqli_query($conn,"SELECT * FROM users WHERE email='$email'");
    if(mysqli_num_rows($q) == 1){
        $user = mysqli_fetch_assoc($q);

        if(password_verify($password,$user['password'])){
            // 🔑 IMPORTANT SESSION FIX
            $_SESSION['uid']  = $user['id'];
            $_SESSION['role'] = $user['role'];

            if($user['role'] === 'admin'){
                header("Location: admin_dashboard.php");
            }else{
                header("Location: dashboard.php");
            }
            exit();
        }else{
            echo "<script>alert('Incorrect password');</script>";
        }
    }else{
        echo "<script>alert('User not found');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>LeafGuard | Authentication</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
:root{
    --primary:#2d6a4f;
    --dark:#1b4332;
}

*{
    box-sizing:border-box;
}

body{
    margin:0;
    height:100vh;
    font-family:'Segoe UI',sans-serif;
    display:flex;
    justify-content:center;
    align-items:center;
    background:
    linear-gradient(rgba(0,0,0,.6),rgba(0,0,0,.6)),
    url("https://images.unsplash.com/photo-1523348837708-15d4a09cfac2");
    background-size:cover;
    background-position:center;
}

/* CONTAINER */
.auth-container{
    width:420px;
    background:rgba(255,255,255,0.96);
    padding:40px;
    border-radius:20px;
    box-shadow:0 15px 35px rgba(0,0,0,.3);
    text-align:center;
}

/* HEADER */
.auth-header i{
    font-size:48px;
    color:var(--primary);
}

.auth-header h2{
    margin:15px 0 30px;
    color:var(--dark);
}

/* FORM */
form{
    width:100%;
}

/* INPUT GROUP */
.form-group{
    position:relative;
    margin-bottom:18px;
}

.form-group i{
    position:absolute;
    left:15px;
    top:50%;
    transform:translateY(-50%);
    color:#777;
    font-size:16px;
}

/* INPUTS */
.form-group input,
.form-group select{
    width:100%;
    height:48px;
    padding-left:45px;
    padding-right:15px;
    border:1px solid #ddd;
    border-radius:10px;
    font-size:15px;
    outline:none;
    background:white;
}

.form-group select{
    appearance:none;
}

.form-group input:focus,
.form-group select:focus{
    border-color:var(--primary);
}

/* BUTTON */
.btn-auth{
    width:100%;
    height:48px;
    margin-top:10px;
    background:var(--primary);
    color:white;
    border:none;
    border-radius:10px;
    font-size:16px;
    font-weight:600;
    cursor:pointer;
}

.btn-auth:hover{
    background:var(--dark);
}

/* FOOTER */
.auth-footer{
    margin-top:18px;
    font-size:14px;
    color:#555;
}

.auth-footer a{
    color:var(--primary);
    font-weight:600;
    text-decoration:none;
}

/* HIDE */
.hidden{
    display:none;
}
</style>
</head>

<body>

<div class="auth-container">

<!-- HEADER -->
<div class="auth-header">
    <i class="fas fa-leaf"></i>
    <h2 id="form-title">Join the Community</h2>
</div>

<!-- LOGIN FORM -->
<form id="login-form" class="hidden" method="post">

    <div class="form-group">
        <i class="fas fa-envelope"></i>
        <input type="email" name="email" placeholder="Email Address" required>
    </div>

    <div class="form-group">
        <i class="fas fa-lock"></i>
        <input type="password" name="password" placeholder="Password" required>
    </div>

    <button class="btn-auth" name="login">Login</button>

    <div class="auth-footer">
        Don’t have an account?
        <a href="#" onclick="toggleForm('register')">Register here</a>
    </div>

</form>

<!-- REGISTER FORM -->
<form id="register-form" method="post">

    <div class="form-group">
        <i class="fas fa-user"></i>
        <input type="text" name="name" placeholder="Full Name" required>
    </div>

    <div class="form-group">
        <i class="fas fa-envelope"></i>
        <input type="email" name="email" placeholder="Email Address" required>
    </div>

    <div class="form-group">
        <i class="fas fa-lock"></i>
        <input type="password" name="password" placeholder="Create Password" required>
    </div>

    <div class="form-group">
        <i class="fas fa-user-shield"></i>
        <select name="role" required>
            <option value="">Select Role</option>
            <option value="user">User / Farmer</option>
            <option value="admin">Admin</option>
        </select>
    </div>

    <button class="btn-auth" name="register">Create Account</button>

    <div class="auth-footer">
        Already have an account?
        <a href="#" onclick="toggleForm('login')">Login here</a>
    </div>

</form>

</div>

<script>
function toggleForm(type){
    const login = document.getElementById("login-form");
    const register = document.getElementById("register-form");
    const title = document.getElementById("form-title");

    if(type === "login"){
        register.classList.add("hidden");
        login.classList.remove("hidden");
        title.innerText = "Login to LeafGuard";
    }else{
        login.classList.add("hidden");
        register.classList.remove("hidden");
        title.innerText = "Join the Community";
    }
}
</script>

</body>
</html>
