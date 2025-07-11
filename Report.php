<?php
session_start();

// Ensure only admin can access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$host = 'localhost';
$db = 'alumni_connector';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, username, email, role, created_at FROM users ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Report Panel</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f7f9;
            margin: 0;
            padding: 20px;
        }

        .report-container {
            max-width: 1000px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            overflow: hidden;
            border-radius: 10px;
        }

        th, td {
            padding: 14px 18px;
            text-align: left;
        }

        thead {
            background-color: #0077cc;
            color: white;
        }

        tbody tr:nth-child(even) {
            background-color: #f1f9ff;
        }

        tbody tr:hover {
            background-color: #e6f2ff;
        }

        .btn-back {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            padding: 10px 18px;
            background-color: #0077cc;
            color: white;
            border-radius: 6px;
            transition: background 0.3s ease;
        }

        .btn-back:hover {
            background-color: #005999;
        }
    </style>
</head>
<body>

<div class="report-container">
    <h2>Admin Report - User Overview</h2>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Registered At</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['username']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= ucfirst($row['role']) ?></td>
                        <td><?= htmlspecialchars($row['created_at']) ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="5">No users found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <a href="admin_dashboard.php" class="btn-back">← Back to Dashboard</a>
</div>

</body>
</html>
