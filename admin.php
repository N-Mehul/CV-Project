<?php
session_start();
include "db.php";

/* Admin protection */
if(!isset($_SESSION['uid']) || $_SESSION['role'] !== 'admin'){
    header("location:register.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LeafGuard AI | Professional Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        :root {
            /* Professional Sidebar: Deep Midnight Emerald */
            --sidebar-bg: #064e3b; 
            --sidebar-hover: #065f46;
            --active-green: #10b981; 
            --text-dark: #334155;
            --text-muted: #94a3b8;
            --bg-body: #f1f5f9;
            --white: #ffffff;
            --border: #e2e8f0;
            --sidebar-width: 280px;
            --sidebar-collapsed-width: 80px;
        }

        .dark-mode {
            --bg-body: #020617;
            --white: #1e293b;
            --text-dark: #f8fafc;
            --text-muted: #64748b;
            --border: #334155;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background-color: var(--bg-body); color: var(--text-dark); display: flex; transition: background 0.3s; height: 100vh; overflow: hidden; }

        /* --- SIDEBAR --- */
        .sidebar {
            width: var(--sidebar-width); 
            height: 100vh;
            background: var(--sidebar-bg);
            display: flex; flex-direction: column;
            padding: 25px 15px; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: fixed; z-index: 1000;
        }

        .sidebar.collapsed { width: var(--sidebar-collapsed-width); padding: 25px 10px; }

        .brand { 
            display: flex; align-items: center; gap: 12px; 
            font-size: 1.4rem; font-weight: 700; color: #ecfdf5; 
            margin-bottom: 40px; padding-left: 10px; overflow: hidden; white-space: nowrap;
        }

        .nav-label { 
            font-size: 0.7rem; font-weight: 700; color: #34d399; 
            text-transform: uppercase; letter-spacing: 0.05em; 
            margin: 20px 0 10px 12px; transition: opacity 0.3s;
        }
        .sidebar.collapsed .nav-label { opacity: 0; height: 0; margin: 0; }

        .nav-link {
            text-decoration: none; color: #a7f3d0;
            padding: 14px 18px; border-radius: 12px;
            display: flex; align-items: center; gap: 15px;
            font-weight: 500; margin-bottom: 5px;
            cursor: pointer; transition: 0.2s; border: none; background: transparent; width: 100%;
        }

        .nav-link:hover { background: var(--sidebar-hover); color: white; }
        .nav-link.active { background: var(--active-green); color: white; box-shadow: 0 4px 12px rgba(0,0,0,0.2); }

        .nav-link span { transition: opacity 0.3s; white-space: nowrap; }
        .sidebar.collapsed .nav-link span { opacity: 0; display: none; }
        .sidebar.collapsed .nav-link { justify-content: center; padding: 14px 0; }
        .sidebar.collapsed .nav-link i { font-size: 1.2rem; }

        .logout-link { margin-top: auto; color: #fca5a5 !important; }

        /* --- MAIN CONTENT WRAPPER --- */
        .wrapper { 
            flex: 1; display: flex; flex-direction: column; overflow: hidden; 
            margin-left: var(--sidebar-width); transition: margin-left 0.4s ease; 
        }
        .wrapper.expanded { margin-left: var(--sidebar-collapsed-width); }

        .top-nav {
            height: 70px; background: var(--white);
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 30px; transition: background 0.3s;
        }

        .menu-toggle {
            background: none; border: none; font-size: 1.4rem; 
            color: var(--text-dark); cursor: pointer; padding: 10px; border-radius: 8px;
        }
        .menu-toggle:hover { background: rgba(0,0,0,0.05); }

        .main-content { padding: 40px; overflow-y: auto; flex: 1; }

        /* --- PAGES --- */
        .page { display: none; animation: fadeIn 0.3s ease; }
        .page.active { display: block; }

        @keyframes fadeIn { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }

        /* --- CARDS & STATS --- */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .card { background: var(--white); border-radius: 16px; padding: 25px; border: 1px solid var(--border); box-shadow: 0 4px 6px rgba(0,0,0,0.02); }
        .card h4 { font-size: 0.8rem; color: var(--text-muted); margin-bottom: 8px; text-transform: uppercase; }

        .avatar { width: 38px; height: 38px; background: var(--active-green); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; }
        .theme-toggle { background: none; border: none; font-size: 1.2rem; color: var(--text-muted); cursor: pointer; margin-right: 15px; }

    </style>
</head>
<body>

    <aside class="sidebar" id="sidebar">
        <div class="brand">
            <i class="fas fa-leaf"></i>
            <span>LeafGuard AI</span>
        </div>
        
        <p class="nav-label">Main Console</p>
        <button class="nav-link active" onclick="switchPage('dashboard', this)">
            <i class="fas fa-chart-pie"></i> <span>Dashboard</span>
        </button>
        <button class="nav-link" onclick="switchPage('history', this)">
            <i class="fas fa-history"></i> <span>Scan History</span>
        </button>

        <p class="nav-label">Intelligence</p>
        <button class="nav-link" onclick="switchPage('dataset', this)">
            <i class="fas fa-folder-tree"></i> <span>Dataset Library</span>
        </button>
        <button class="nav-link" onclick="switchPage('model', this)">
            <i class="fas fa-microchip"></i> <span>Model Analytics</span>
        </button>

        <a href="logout.php" class="nav-link logout-link"><i class="fas fa-power-off"></i> <span>Sign Out</span></a>
    </aside>

    <div class="wrapper" id="wrapper">
        <nav class="top-nav">
            <button class="menu-toggle" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
            
            <div style="display: flex; align-items: center;">
                <button class="theme-toggle" onclick="toggleDarkMode()">
                    <i id="themeIcon" class="fas fa-moon"></i>
                </button>
                <div style="display: flex; align-items: center; gap: 12px; border-left: 1px solid var(--border); padding-left: 20px;">
                    <div style="text-align: right">
                        <p style="font-size: 0.85rem; font-weight: 700;">Admin User</p>
                        <small style="color: var(--text-muted);">Root Manager</small>
                    </div>
                    <div class="avatar">A</div>
                </div>
            </div>
        </nav>

        <main class="main-content">
            
            <div id="dashboard" class="page active">
                <h1 style="margin-bottom: 25px;">System Intelligence</h1>
                <div class="stats-grid">
                    <div class="card"><h4>Model Accuracy</h4><h2>97.8%</h2></div>
                    <div class="card"><h4>Total Diagnostics</h4><h2>12,402</h2></div>
                    <div class="card"><h4>Active Users</h4><h2>1,150</h2></div>
                    <div class="card"><h4>Server Health</h4><h2 style="color: #10b981;">Optimal</h2></div>
                </div>
                <div class="card" style="height: 380px;">
                    <h3>Detection Trends</h3>
                    <canvas id="trendChart"></canvas>
                </div>
            </div>

            <div id="dataset" class="page">
    <h1>Dataset Library</h1>

    <div class="card" style="margin-top:20px; max-width:600px;">
        <h3>Upload Dataset CSV</h3>

        <form action="upload_dataset.php" method="post" enctype="multipart/form-data">

    <input type="file" name="dataset_zip" accept=".zip" required>

    <br><br>

    <button type="submit" name="upload">
        Upload Dataset
    </button>

</form>

        <p style="margin-top:10px;color:#64748b;">
            Upload a CSV file containing plant disease dataset metadata.
        </p>
    </div>
</div>

            </main>
    </div>

    <script>
        // SIDEBAR COLLAPSE LOGIC
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const wrapper = document.getElementById('wrapper');
            sidebar.classList.toggle('collapsed');
            wrapper.classList.toggle('expanded');
        }

        // PAGE SWITCHING LOGIC
        function switchPage(pageId, btn) {
            document.querySelectorAll('.nav-link').forEach(link => link.classList.remove('active'));
            btn.classList.add('active');
            
            document.querySelectorAll('.page').forEach(page => page.classList.remove('active'));
            const targetPage = document.getElementById(pageId);
            if(targetPage) targetPage.classList.add('active');
        }

        // DARK MODE LOGIC
        function toggleDarkMode() {
            document.body.classList.toggle('dark-mode');
            const icon = document.getElementById('themeIcon');
            icon.className = document.body.classList.contains('dark-mode') ? 'fas fa-sun' : 'fas fa-moon';
        }

        // CHART.JS INITIALIZATION
        const ctx = document.getElementById('trendChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [{
                    label: 'Scans',
                    data: [420, 580, 480, 710, 820, 550, 400],
                    backgroundColor: '#10b981',
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { 
                    y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } },
                    x: { grid: { display: false } }
                }
            }
        });
    </script>
</body>
</html>