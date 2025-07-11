<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
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

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $event_date = $_POST['event_date'];

    if (empty($title) || empty($event_date)) {
        $error = "Title and Event Date are required.";
    } else {
        $stmt = $conn->prepare("INSERT INTO events (title, description, event_date) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $title, $description, $event_date);
        if ($stmt->execute()) {
            $success = "Event added successfully!";
        } else {
            $error = "Error adding event: " . $conn->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Add Event - Admin Panel</title>
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
            max-width: 600px;
            margin: auto;
        }

        h1 {
            margin-bottom: 20px;
        }

        form {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
        }

        input[type="text"],
        input[type="date"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 14px;
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        button {
            background-color: #3b82f6;
            border: none;
            padding: 12px 20px;
            color: white;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #2563eb;
        }

        .message {
            margin-bottom: 20px;
            padding: 12px;
            border-radius: 8px;
        }

        .error {
            background-color: #fee2e2;
            color: #b91c1c;
        }

        .success {
            background-color: #d1fae5;
            color: #065f46;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <a href="admin_dashboard.php">🏠 Dashboard</a>
        <a href="manage_users.php">👥 Manage Users</a>
        <a href="add_event.php" style="background:#334155;">📅 Add Event</a>
        <a href="logout.php">🚪 Logout</a>
    </div>

    <div class="main-content">
        <h1>Add New Event</h1>

        <?php if ($error): ?>
            <div class="message error"><?php echo htmlspecialchars($error); ?></div>
        <?php elseif ($success): ?>
            <div class="message success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <form method="post" action="add_event.php">
            <label for="title">Event Title *</label>
            <input type="text" id="title" name="title" required />

            <label for="description">Description</label>
            <textarea id="description" name="description"></textarea>

            <label for="event_date">Event Date *</label>
            <input type="date" id="event_date" name="event_date" required />

            <button type="submit">Add Event</button>
        </form>
    </div>
</body>

</html>