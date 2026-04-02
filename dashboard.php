<?php
session_start();
include "db.php";

if(!isset($_SESSION['uid'])){
    header("location:login.php");
    exit();
}

/* 🔴 FIX ADDED: ROLE CHECK (ONLY CHANGE) */
if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'){
    header("location:admin_dashboard.php");
    exit();
}

$uid = $_SESSION['uid'];

/* User Info */
$qUser = mysqli_query($conn,"SELECT * FROM users WHERE id='$uid'");
$user = mysqli_fetch_assoc($qUser);

/* Total Scans */
$qTotal = mysqli_query($conn,
"SELECT COUNT(*) AS total FROM history WHERE user_id='$uid'");
$total = mysqli_fetch_assoc($qTotal)['total'];

/* Avg Accuracy */
$qAvg = mysqli_query($conn,
"SELECT AVG(accuracy) AS avg_acc FROM history WHERE user_id='$uid'");
$avgAcc = round(mysqli_fetch_assoc($qAvg)['avg_acc'] ?? 0, 1);

/* Healthy Crops Count */
$qHealthy = mysqli_query($conn,
"SELECT COUNT(*) AS healthy FROM history WHERE user_id='$uid' AND disease LIKE '%Healthy%'");
$healthyCount = mysqli_fetch_assoc($qHealthy)['healthy'];

/* Latest Scan */
$qLast = mysqli_query($conn,
"SELECT * FROM history 
 WHERE user_id='$uid' 
 ORDER BY id DESC LIMIT 1");
$last = mysqli_fetch_assoc($qLast);
?>

<!DOCTYPE html>
<html>
<head>
<title>Dashboard</title>
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
    <h1>Welcome back, <?php echo htmlspecialchars($user['name']); ?> 👋</h1>
</div>

<!-- ================= STATS ================= -->
<div class="stats">

    <div class="stat-box">
        <h3>Total Scans</h3>
        <p><?php echo $total; ?></p>
    </div>

    <div class="stat-box">
        <h3>Avg Accuracy</h3>
        <p><?php echo $avgAcc; ?>%</p>
    </div>

    <div class="stat-box">
        <h3>Healthy Crops</h3>
        <p><?php echo $healthyCount; ?></p>
    </div>

</div>

<!-- ================= UPLOAD ================= -->
<div class="upload-box">
    <h3>☁ Upload Leaf Image for Analysis</h3>
    <p>Supported formats: JPG, PNG (Max 5MB)</p><br>

    <form action="upload.php" method="post" enctype="multipart/form-data">
        <input type="file" name="img" id="fileInput"
               style="display:none" accept="image/*"
               onchange="this.form.submit()">

        <button type="button" onclick="document.getElementById('fileInput').click()">
            Select Image
        </button>
    </form>
</div>

<!-- ================= RESULT ================= -->
<div class="result-area">

<div class="preview-box">
    <h3>Input Preview</h3>
    <?php if($last){ ?>
        <img src="uploads/<?php echo $last['image']; ?>">
    <?php } else { ?>
        <p style="color:#777;margin-top:20px;">No image uploaded yet</p>
    <?php } ?>
</div>

<div class="report-box">
    <h3>Diagnosis Report</h3>

    <?php if($last){ ?>
        <p>Status: <span style="color:red">Detected</span></p><br>
        <p>Detected Disease: <b><?php echo $last['disease']; ?></b></p><br>
        <p>Confidence: <b><?php echo $last['accuracy']; ?>%</b></p>
        <hr><br>
        <p><b>Recommended Action:</b></p>
        <p>Please follow treatment guidelines and consult agriculture expert.</p>
    <?php } else { ?>
        <p style="color:#777;">
            No diagnosis available yet.<br>
            Upload an image to start detection.
        </p>
    <?php } ?>
</div>

</div>
</div>

</body>
</html>
