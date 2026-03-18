<?php
session_start();
include "db.php";

if(!isset($_SESSION['uid'])){
    header("location:login.php");
    exit();
}

$uid = $_SESSION['uid'];

/* Get User */
$q = mysqli_query($conn,"SELECT * FROM users WHERE id='$uid'");
$user = mysqli_fetch_assoc($q);


/* Update Profile */
if(isset($_POST['update'])){

    $name = $_POST['name'];

    if(!empty($_POST['password'])){

        $pass = password_hash($_POST['password'],PASSWORD_DEFAULT);

        mysqli_query($conn,
        "UPDATE users SET name='$name', password='$pass'
         WHERE id='$uid'");
    }
    else{

        mysqli_query($conn,
        "UPDATE users SET name='$name'
         WHERE id='$uid'");
    }

    header("location:profile.php?success=1");
}
?>

<!DOCTYPE html>
<html>
<head>

<title>Profile Settings</title>

<link rel="stylesheet" href="assets/css/style.css">

</head>

<body>

<!-- SIDEBAR -->
<?php 
$activePage = 'profile';
include 'sidebar.php'; 
?>


<!-- MAIN -->
<div class="main">

<h2>Profile Settings</h2>
<br>

<!-- SUCCESS MSG -->
<?php if(isset($_GET['success'])){ ?>

<div style="
background:#e8f5e9;
color:#2e7d32;
padding:12px;
border-radius:8px;
margin-bottom:15px;
">
✔ Profile updated successfully
</div>

<?php } ?>


<!-- PROFILE CARD -->
<div style="
background:white;
max-width:500px;
padding:30px;
border-radius:15px;
box-shadow:0 3px 10px rgba(0,0,0,0.1);
">

<h3 style="margin-bottom:20px;color:#2e7d32;">
👤 Account Information
</h3>

<form method="post">

<!-- NAME -->
<label>Name</label>
<input type="text" name="name"
value="<?php echo $user['name']; ?>" required>

<br><br>

<!-- EMAIL -->
<label>Email</label>
<input type="email"
value="<?php echo $user['email']; ?>" readonly>

<br><br>

<!-- PASSWORD -->
<label>New Password</label>
<input type="password" name="password"
placeholder="Leave empty to keep old password">

<br><br>

<button class="btn" name="update">
Update Profile
</button>

</form>

</div>

</div>

</body>
</html>
