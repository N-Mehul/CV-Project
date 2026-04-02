<?php
session_start();
include "db.php";

/* Admin protection */
if(!isset($_SESSION['uid']) || $_SESSION['role'] !== 'admin'){
    header("location:register.php");
    exit();
}

/* Global Stats */
$totalUsers = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS t FROM users"))['t'];
$totalScans = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS t FROM history"))['t'];

/* Top Disease */
$qTop = mysqli_query($conn, "SELECT disease, COUNT(*) as count FROM history GROUP BY disease ORDER BY count DESC LIMIT 1");
$topDisease = mysqli_fetch_assoc($qTop)['disease'] ?? "No Data";

/* System Accuracy */
$qSysAcc = mysqli_query($conn, "SELECT AVG(accuracy) AS avg_acc FROM history");
$sysAcc = round(mysqli_fetch_assoc($qSysAcc)['avg_acc'] ?? 0, 1);
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Dashboard</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

<!-- ================= SIDEBAR ================= -->
<?php 
$activePage = 'dashboard';
include 'sidebar.php'; 
?>

<!-- ================= MAIN ================= -->
<div class="main">

<div class="topbar">
    <h1>👑 Admin Overview</h1>
</div>

<div class="stats">

    <div class="stat-box">
        <h3>Total Users</h3>
        <p><?php echo $totalUsers; ?></p>
    </div>

    <div class="stat-box">
        <h3>Total Scans</h3>
        <p><?php echo $totalScans; ?></p>
    </div>

    <div class="stat-box">
        <h3>System Accuracy</h3>
        <p><?php echo $sysAcc; ?>%</p>
    </div>

</div>

<br>

<div class="stats">
    <div class="stat-box" style="grid-column: span 3;">
        <h3>Most Common Disease Detected</h3>
        <p style="color: #c62828; font-size: 32px;"><?php echo $topDisease; ?></p>
    </div>
</div>

<br>

<div class="report-box">
    <h3>🚀 Administrator Quick Actions</h3>
    <p style="color: var(--text-muted); margin-bottom: 20px;">Manage your system and monitor analytics.</p>
    
    <div style="display: flex; gap: 15px;">
        <a href="admin_users.php" class="btn" style="text-decoration: none;">👤 Manage Users</a>
        <a href="admin_history.php" class="btn" style="text-decoration: none; background: #555;">📜 View All History</a>
    </div>
</div>

</div>

</body>
</html>
