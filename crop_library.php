<?php
session_start();

if(!isset($_SESSION['uid'])){
    header("location:login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Crop Library</title>

<link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

<!-- SIDEBAR -->
<?php 
$activePage = 'crop_library';
include 'sidebar.php'; 
?>


<!-- MAIN -->
<div class="main">

<h2>Crop Library</h2>
<br>

<p>Information about common crops and diseases.</p>

<br>

<div class="stats">

    <div class="stat-box">
        <h3>🌱 Tomato</h3>
        <p>Early Blight, Late Blight, Leaf Mold</p>
    </div>

    <div class="stat-box">
        <h3>🥔 Potato</h3>
        <p>Late Blight, Scab</p>
    </div>

    <div class="stat-box">
        <h3>🌽 Maize</h3>
        <p>Leaf Spot, Rust</p>
    </div>

    <div class="stat-box">
        <h3>🍎 Apple</h3>
        <p>Scab, Black Rot</p>
    </div>

</div>

<br>

<h3>Basic Prevention Tips</h3>

<ul style="margin-top:10px;line-height:25px;">

<li>✔ Use healthy seeds</li>
<li>✔ Avoid over-watering</li>
<li>✔ Remove infected leaves</li>
<li>✔ Use recommended fertilizers</li>
<li>✔ Rotate crops</li>

</ul>

</div>

</body>
</html>
