<?php
session_start();
include "db.php";

if(!isset($_SESSION['uid']) || $_SESSION['role'] !== 'admin'){
    header("location:register.php");
    exit();
}

$users = mysqli_query($conn,"SELECT id,name,email,role FROM users");
?>

<!DOCTYPE html>
<html>
<head>
<title>Manage Users</title>
</head>

<body>

<h2>👤 Registered Users</h2>

<table border="1" cellpadding="10">
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Email</th>
    <th>Role</th>
</tr>

<?php while($u = mysqli_fetch_assoc($users)){ ?>
<tr>
    <td><?php echo $u['id']; ?></td>
    <td><?php echo $u['name']; ?></td>
    <td><?php echo $u['email']; ?></td>
    <td><?php echo $u['role']; ?></td>
</tr>
<?php } ?>

</table>

<br>
<a href="admin_dashboard.php">⬅ Back</a>

</body>
</html>
