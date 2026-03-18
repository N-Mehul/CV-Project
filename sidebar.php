<?php
if (!isset($activePage)) {
    $activePage = '';
}
?>
<!-- ================= SIDEBAR ================= -->
<div class="sidebar">
    <h2>🌿 LeafGuard AI</h2>
    <a href="dashboard.php" class="<?php echo $activePage == 'dashboard' ? 'active' : ''; ?>">📊 Dashboard</a>
    <a href="upload.php" class="<?php echo $activePage == 'upload' ? 'active' : ''; ?>">➕ New Detection</a>
    <a href="history.php" class="<?php echo $activePage == 'history' ? 'active' : ''; ?>">📜 History</a>
    <a href="crop_library.php" class="<?php echo $activePage == 'crop_library' ? 'active' : ''; ?>">📚 Crop Library</a>
    <a href="profile.php" class="<?php echo $activePage == 'profile' ? 'active' : ''; ?>">⚙ Settings</a>
    <a href="logout.php">🚪 Logout</a>
</div>
