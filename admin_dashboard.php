<?php
session_start();
include "db.php";

/* Admin protection */
if(!isset($_SESSION['uid']) || $_SESSION['role'] !== 'admin'){
    header("location:register.php");
    exit();
}

/* Stats */
$totalUsers = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT COUNT(*) AS t FROM users")
)['t'];

$totalScans = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT COUNT(*) AS t FROM history")
)['t'];
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Dashboard</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

<h2>👑 Admin Dashboard</h2>

<p><b>Total Users:</b> <?php echo $totalUsers; ?></p>
<p><b>Total Scans:</b> <?php echo $totalScans; ?></p>

<hr>

<ul>
    <li><a href="admin_users.php">👤 Manage Users</a></li>
    <li><a href="admin_history.php">📜 Detection History</a></li>
    <li><a href="logout.php">🚪 Logout</a></li>
</ul>

</body>
</html>
