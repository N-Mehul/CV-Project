<?php
if (!isset($activePage)) {
    $activePage = '';
}
?>
<!-- ================= SIDEBAR ================= -->
<div class="sidebar">
    <h2>🌿 LeafGuard AI</h2>
    <a href="dashboard.php" class="<?php echo $activePage == 'dashboard' ? 'active' : ''; ?>">📊 Dashboard</a>
    
    <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'){ ?>
        <a href="admin_users.php" class="<?php echo $activePage == 'admin_users' ? 'active' : ''; ?>">👤 Manage Users</a>
        <a href="admin_history.php" class="<?php echo $activePage == 'admin_history' ? 'active' : ''; ?>">📜 System History</a>
    <?php } else { ?>
        <a href="upload.php" class="<?php echo $activePage == 'upload' ? 'active' : ''; ?>">➕ New Detection</a>
        <a href="history.php" class="<?php echo $activePage == 'history' ? 'active' : ''; ?>">📜 History</a>
    <?php } ?>

    <a href="crop_library.php" class="<?php echo $activePage == 'crop_library' ? 'active' : ''; ?>">📚 Crop Library</a>
    <a href="profile.php" class="<?php echo $activePage == 'profile' ? 'active' : ''; ?>">⚙ Settings</a>
    <a href="logout.php">🚪 Logout</a>
</div>
