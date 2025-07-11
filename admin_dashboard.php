<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}
$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            display: flex;
            min-height: 100vh;
            background-color: #f5f7fb;
            color: #333;
        }

        .sidebar {
            width: 240px;
            background-color: #1e293b;
            color: #fff;
            padding: 20px;
            position: fixed;
            height: 100%;
        }

        .sidebar h2 {
            font-size: 22px;
            margin-bottom: 30px;
        }

        .sidebar a {
            display: block;
            color: #cbd5e1;
            text-decoration: none;
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 8px;
            transition: background 0.3s ease;
        }

        .sidebar a:hover {
            background-color: #334155;
        }

        .main-content {
            margin-left: 240px;
            padding: 30px;
            width: 100%;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .topbar h1 {
            font-size: 26px;
            font-weight: 600;
        }

        .topbar .user {
            background-color: #e2e8f0;
            padding: 10px 20px;
            border-radius: 20px;
            font-weight: 500;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 20px;
        }

        .card {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease;
            cursor: pointer;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card img {
            width: 100%;
            border-radius: 12px;
            margin-bottom: 15px;
        }

        .card h3 {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .card p {
            color: #64748b;
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .sidebar {
                display: none;
            }

            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <a href="admin_dashboard.php">🏠 Dashboard</a>
        <a href="manage_users.php">👥 Manage Users</a>
        <a href="reports.php">📊 Reports</a>
        <a href="settings.php">⚙️ Settings</a>
        <a href="logout.php">🚪 Logout</a>
    </div>

    <div class="main-content">
        <div class="topbar">
            <h1>Welcome Admin, <?php echo htmlspecialchars($username); ?> 👋</h1>
            <div class="user">👤 <?php echo htmlspecialchars($username); ?></div>
        </div>

        <div class="cards">
            <div class="card" onclick="location.href='manage_users.php'">
                <img src="https://cdn-icons-png.flaticon.com/512/1077/1077114.png" alt="Manage Users" />
                <h3>Manage Users</h3>
                <p>View, edit, or remove alumni accounts and admin users.</p>
            </div>

            <div class="card" onclick="location.href='Report.php'">
                <img src="https://cdn-icons-png.flaticon.com/512/126/126509.png" alt="Reports" />
                <h3>Reports</h3>
                <p>Analyze site activity, registrations, and user engagement.</p>
            </div>

            <div class="card" onclick="location.href='admin_setting.php'">
                <img src="https://cdn-icons-png.flaticon.com/512/3524/3524659.png" alt="Settings" />
                <h3>Settings</h3>
                <p>Configure site settings and admin preferences.</p>
            </div>

            <div class="card" onclick="location.href='activity_logs.php'">
                <img src="https://cdn-icons-png.flaticon.com/512/565/565547.png" alt="Logs" />
                <h3>Activity Logs</h3>
                <p>Review user actions and system events.</p>
            </div>
        </div>
    </div>
</body>

</html>