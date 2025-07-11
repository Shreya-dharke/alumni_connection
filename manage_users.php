<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// DB connection
$host = 'localhost';
$db = 'alumni_connector';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all users
$sql = "SELECT id, username, email, role FROM users ORDER BY id ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Manage Users - Admin Panel</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f5f7fb;
            margin: 0;
            padding-left: 240px;
        }

        .sidebar {
            width: 240px;
            background-color: #1e293b;
            color: #fff;
            padding: 20px;
            position: fixed;
            height: 100%;
            top: 0;
            left: 0;
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
            padding: 30px;
        }

        h1 {
            margin-bottom: 20px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }

        th {
            background-color: #1e293b;
            color: white;
        }

        tr:hover {
            background-color: #f1f5f9;
        }

        .action-btn {
            padding: 6px 12px;
            margin-right: 5px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            color: white;
        }

        .edit-btn {
            background-color: #3b82f6;
        }

        .delete-btn {
            background-color: #ef4444;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <a href="admin_dashboard.php">🏠 Dashboard</a>
        <a href="manage_users.php" style="background:#334155;">👥 Manage Users</a>
        <a href="reports.php">📊 Reports</a>
        <a href="settings.php">⚙️ Settings</a>
        <a href="logout.php">🚪 Logout</a>
    </div>

    <div class="main-content">
        <h1>Manage Users</h1>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($user = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['id']); ?></td>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo htmlspecialchars($user['role']); ?></td>
                            <td>
                                <button class="action-btn edit-btn"
                                    onclick="location.href='edit_user.php?id=<?php echo $user['id']; ?>'">Edit</button>
                                <button class="action-btn delete-btn"
                                    onclick="if(confirm('Are you sure you want to delete this user?')) location.href='delete_user.php?id=<?php echo $user['id']; ?>'">Delete</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No users found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>

</html>