<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$host = 'localhost';
$db = 'alumni_connector';
$user = 'root';
$pass = '';
$conn = new mysqli($host, $user, $pass, $db);

$admin_id = $_SESSION['userid'];
$success = $error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $alumni_default_role = $_POST['alumni_default_role'];
    $alumni_can_message = isset($_POST['alumni_can_message']) ? 1 : 0;
    $show_events = isset($_POST['show_events']) ? 1 : 0;

    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET username=?, email=?, password=? WHERE id=?");
        $stmt->bind_param("sssi", $username, $email, $hashedPassword, $admin_id);
    } else {
        $stmt = $conn->prepare("UPDATE users SET username=?, email=? WHERE id=?");
        $stmt->bind_param("ssi", $username, $email, $admin_id);
    }

    $stmt->execute();

    // Update alumni settings table
    $conn->query("UPDATE settings SET alumni_default_role='$alumni_default_role', alumni_can_message=$alumni_can_message, show_events=$show_events");

    $success = "Settings updated successfully.";
}

// Get admin data
$query = $conn->prepare("SELECT username, email FROM users WHERE id=?");
$query->bind_param("i", $admin_id);
$query->execute();
$query->bind_result($username, $email);
$query->fetch();
$query->close();

// Get alumni settings
$settings = $conn->query("SELECT alumni_default_role, alumni_can_message, show_events FROM settings LIMIT 1")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Settings</title>
  <style>
    body {
      font-family: "Segoe UI", sans-serif;
      background: #f0f2f5;
      padding: 40px;
    }
    .container {
      background: #fff;
      max-width: 700px;
      margin: auto;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    h2 {
      text-align: center;
      color: #333;
    }
    label {
      margin-top: 15px;
      display: block;
      font-weight: 600;
      color: #444;
    }
    input, select {
      width: 100%;
      padding: 10px;
      margin-top: 6px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 15px;
    }
    .checkbox-group {
      margin-top: 12px;
    }
    .checkbox-group label {
      display: inline-block;
      font-weight: normal;
      margin-right: 20px;
    }
    .btn {
      margin-top: 25px;
      padding: 12px;
      background: #0056b3;
      color: white;
      border: none;
      border-radius: 6px;
      font-size: 16px;
      cursor: pointer;
      width: 100%;
    }
    .btn:hover {
      background: #003d80;
    }
    .message {
      text-align: center;
      margin-top: 15px;
    }
    .success { color: green; }
    .error { color: red; }
    a.back {
      display: block;
      text-align: center;
      margin-top: 20px;
      color: #0056b3;
    }
  </style>
</head>
<body>
<div class="container">
    <h2>Admin Panel - Settings</h2>

    <?php if ($success): ?>
        <p class="message success"><?= $success ?></p>
    <?php elseif ($error): ?>
        <p class="message error"><?= $error ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Admin Username</label>
        <input type="text" name="username" value="<?= htmlspecialchars($username) ?>" required>

        <label>Admin Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" required>

        <label>New Password (optional)</label>
        <input type="password" name="password" placeholder="Leave blank to keep current">

        <hr style="margin-top: 30px; margin-bottom: 20px;">

        <h3>Alumni Settings</h3>

        <label>Default Role for New Alumni</label>
        <select name="alumni_default_role">
            <option value="user" <?= $settings['alumni_default_role'] === 'user' ? 'selected' : '' ?>>User</option>
            <option value="premium" <?= $settings['alumni_default_role'] === 'premium' ? 'selected' : '' ?>>Premium User</option>
        </select>

        <div class="checkbox-group">
            <label><input type="checkbox" name="alumni_can_message" <?= $settings['alumni_can_message'] ? 'checked' : '' ?>> Allow Messaging</label>
            <label><input type="checkbox" name="show_events" <?= $settings['show_events'] ? 'checked' : '' ?>> Show Events</label>
        </div>

        <button class="btn" type="submit">Save Settings</button>
    </form>

    <a class="back" href="admin_dashboard.php">← Back to Dashboard</a>
</div>
</body>
</html>
