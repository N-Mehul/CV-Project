<?php
session_start();
include "db.php";

if(!isset($_SESSION['uid']) || $_SESSION['role'] !== 'admin'){
    header("location:register.php");
    exit();
}

$history = mysqli_query($conn,"
SELECT h.id, u.name, h.disease, h.accuracy
FROM history h
JOIN users u ON h.user_id = u.id
ORDER BY h.id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Detection History</title>
</head>

<body>

<h2>📜 Detection History</h2>

<table border="1" cellpadding="10">
<tr>
    <th>ID</th>
    <th>User</th>
    <th>Disease</th>
    <th>Accuracy</th>
</tr>

<?php while($h = mysqli_fetch_assoc($history)){ ?>
<tr>
    <td><?php echo $h['id']; ?></td>
    <td><?php echo $h['name']; ?></td>
    <td><?php echo $h['disease']; ?></td>
    <td><?php echo $h['accuracy']; ?>%</td>
</tr>
<?php } ?>

</table>

<br>
<a href="admin_dashboard.php">⬅ Back</a>

</body>
</html>
