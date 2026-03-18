<?php
session_start();
include "db.php";

/* Check Login */
if(!isset($_SESSION['uid'])){
    header("location:login.php");
    exit();
}

$uid = $_SESSION['uid'];

/* Fetch User History */
$q = "SELECT * FROM history 
      WHERE user_id='$uid'
      ORDER BY id DESC";

$result = mysqli_query($conn,$q);
?>

<!DOCTYPE html>
<html>
<head>
<title>Scan History</title>

<link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

<?php 
$activePage = 'history';
include 'sidebar.php'; 
?>


<div class="main">

<h2>Scan History</h2>
<br>

<?php if(mysqli_num_rows($result)==0){ ?>

    <!-- If No Records -->
    <p style="color:gray;font-size:16px;">
        No scans found. Upload an image to start.
    </p>

<?php }else{ ?>

<table>

<tr>
    <th>Date</th>
    <th>Image</th>
    <th>Disease</th>
    <th>Accuracy</th>
</tr>

<?php while($row=mysqli_fetch_assoc($result)){ ?>

<tr>
    <td><?php echo $row['date']; ?></td>

    <td>
        <img src="uploads/<?php echo $row['image']; ?>" width="60">
    </td>

    <td><?php echo $row['disease']; ?></td>

    <td><?php echo $row['accuracy']; ?>%</td>
</tr>

<?php } ?>

</table>

<?php } ?>

</div>

</body>
</html>
