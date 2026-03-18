<?php
session_start();
include "db.php";

/* ================= LOGIN ================= */

if(isset($_POST['login'])){

    $email = $_POST['email'];
    $pass  = $_POST['password'];

    $q = mysqli_query($conn,"SELECT * FROM users WHERE email='$email'");
    $row = mysqli_fetch_assoc($q);

    if($row && password_verify($pass,$row['password'])){

        $_SESSION['user_id'] = $row['id'];
        $_SESSION['user'] = $row['name'];
        $_SESSION['uid']  = $row['id'];
        $_SESSION['role'] = isset($row['role']) ? $row['role'] : 'user';

        if($_SESSION['role']=="admin"){
            header("location:admin.php");
        }else{
            header("location:dashboard.php");
        }
        exit();
    }
    else{
        $error = "Invalid Email or Password";
    }
}


/* ================= REGISTER ================= */

if(isset($_POST['register'])){

    $name  = $_POST['name'];
    $email = $_POST['email'];
    $role  = $_POST['role'];

    $pass = password_hash($_POST['password'],PASSWORD_DEFAULT);

    mysqli_query($conn,
    "INSERT INTO users(name,email,password,role)
     VALUES('$name','$email','$pass','$role')");

    $success = "Account Created! Please Login.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">

<title>LeafGuard Authentication</title>

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>

/* ================= COLORS ================= */

:root {
    --primary-green:#2d6a4f;
    --dark-green:#1b4332;
}

/* ================= BODY ================= */

body{
    font-family:'Segoe UI',sans-serif;
    margin:0;
    height:100vh;

    display:flex;
    justify-content:center;
    align-items:center;

    background:
    linear-gradient(rgba(0,0,0,0.6),rgba(0,0,0,0.6)),
    url("https://images.unsplash.com/photo-1523348837708-15d4a09cfac2");

    background-size:cover;
    background-position:center;
}

/* ================= BOX ================= */

.auth-container{
    background:rgba(255,255,255,0.95);
    width:400px;
    padding:40px;

    border-radius:20px;
    box-shadow:0 15px 35px rgba(0,0,0,0.3);

    text-align:center;
}

/* HEADER */

.auth-header i{
    font-size:3rem;
    color:var(--primary-green);
}

.auth-header h2{
    color:var(--dark-green);
    margin:15px 0 30px;
}

/* ================= FORM ================= */

form{
    width:100%;
}

.form-group{
    position:relative;
    margin-bottom:20px;
    width:100%;
}

.form-group i{
    position:absolute;
    left:15px;
    top:50%;
    transform:translateY(-50%);
    color:#777;
    font-size:16px;
}

/* INPUT */

.form-group input,
.form-group select{

    width:100%;
    height:48px;

    padding:0 15px 0 45px;

    border:1px solid #ddd;
    border-radius:8px;

    font-size:15px;
    outline:none;

    box-sizing:border-box;
}

.form-group input:focus,
.form-group select:focus{
    border-color:var(--primary-green);
}

/* BUTTON */

.btn-auth{

    width:100%;
    height:48px;

    background:var(--primary-green);
    color:white;

    border:none;
    border-radius:8px;

    font-size:16px;
    font-weight:600;

    cursor:pointer;
    margin-top:5px;
}

.btn-auth:hover{
    background:var(--dark-green);
}

/* FOOTER */

.auth-footer{
    margin-top:20px;
    font-size:14px;
    color:#555;
}

.auth-footer a{
    color:var(--primary-green);
    font-weight:bold;
    text-decoration:none;
}

/* MESSAGES */

.msg-error{
    color:red;
    margin-bottom:10px;
}

.msg-success{
    color:green;
    margin-bottom:10px;
}

/* TOGGLE */

.hidden{display:none;}

</style>
</head>


<body>

<div class="auth-container">


<!-- HEADER -->
<div class="auth-header">
    <i class="fas fa-leaf"></i>
    <h2 id="form-title">Login to LeafGuard</h2>
</div>


<!-- MESSAGES -->

<?php if(isset($error)){ ?>
<p class="msg-error"><?php echo $error; ?></p>
<?php } ?>

<?php if(isset($success)){ ?>
<p class="msg-success"><?php echo $success; ?></p>
<?php } ?>


<!-- ================= LOGIN ================= -->

<form method="post" id="login-form">

<div class="form-group">
<i class="fas fa-envelope"></i>
<input type="email" name="email"
placeholder="Email Address" required>
</div>

<div class="form-group">
<i class="fas fa-lock"></i>
<input type="password" name="password"
placeholder="Password" required>
</div>

<button name="login" class="btn-auth">
Login
</button>

<div class="auth-footer">
Don't have an account?
<a href="#" onclick="toggleForm('register')">
Register here
</a>
</div>

</form>


<!-- ================= REGISTER ================= -->

<form method="post" id="register-form" class="hidden">

<div class="form-group">
<i class="fas fa-user"></i>
<input type="text" name="name"
placeholder="Full Name" required>
</div>

<div class="form-group">
<i class="fas fa-envelope"></i>
<input type="email" name="email"
placeholder="Email Address" required>
</div>

<div class="form-group">
<i class="fas fa-lock"></i>
<input type="password" name="password"
placeholder="Create Password" required>
</div>

<div class="form-group">
<i class="fas fa-user-shield"></i>

<select name="role" required>
<option value="">Select Role</option>
<option value="user">User / Farmer</option>
<option value="admin">Admin</option>
</select>
</div>


<button name="register" class="btn-auth">
Create Account
</button>

<div class="auth-footer">
Already have an account?
<a href="#" onclick="toggleForm('login')">
Login here
</a>
</div>

</form>

</div>


<!-- ================= JS ================= -->

<script>

function toggleForm(type){

let login = document.getElementById("login-form");
let reg   = document.getElementById("register-form");
let title = document.getElementById("form-title");

if(type==="register"){

    login.classList.add("hidden");
    reg.classList.remove("hidden");
    title.innerText="Join the Community";

}else{

    reg.classList.add("hidden");
    login.classList.remove("hidden");
    title.innerText="Login to LeafGuard";
}

}

</script>

</body>
</html>
