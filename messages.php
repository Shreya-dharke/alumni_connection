<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'alumni_connector');

if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['userid'];

// Handle message sending
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $receiver_id = $_POST['receiver_id'];
    $subject = $_POST['subject'];
    $body = $_POST['body'];

    $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, subject, body) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $user_id, $receiver_id, $subject, $body);
    $stmt->execute();
    $message_sent = true;
}

// Fetch users for dropdown
$users = $conn->query("SELECT id, username FROM users WHERE id != $user_id");

// Fetch inbox
$inbox = $conn->prepare("SELECT m.*, u.username AS sender_name FROM messages m JOIN users u ON m.sender_id = u.id WHERE m.receiver_id = ? ORDER BY m.sent_at DESC");
$inbox->bind_param("i", $user_id);
$inbox->execute();
$inbox_result = $inbox->get_result();

// Fetch sent messages
$sent = $conn->prepare("SELECT m.*, u.username AS receiver_name FROM messages m JOIN users u ON m.receiver_id = u.id WHERE m.sender_id = ? ORDER BY m.sent_at DESC");
$sent->bind_param("i", $user_id);
$sent->execute();
$sent_result = $sent->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Messages</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            margin: 0;
            padding: 20px;
        }

        h2 {
            color: #333;
        }

        .container {
            max-width: 1000px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
        }

        .message-box {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 8px;
            background: #f9f9f9;
        }

        label,
        select,
        input,
        textarea,
        button {
            display: block;
            width: 100%;
            margin-bottom: 15px;
            padding: 10px;
        }

        button {
            background-color: #1e293b;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #334155;
        }

        .tabs {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
        }

        .tab {
            cursor: pointer;
            font-weight: bold;
            color: #1e293b;
        }

        .active {
            text-decoration: underline;
        }

        .section {
            display: none;
        }

        .section.active {
            display: block;
        }

        .success {
            background: #d1fae5;
            color: #065f46;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
    </style>
    <script>
        function showSection(id) {
            document.querySelectorAll('.section').forEach(sec => sec.classList.remove('active'));
            document.getElementById(id).classList.add('active');
            document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));
            document.querySelector('[data-tab="' + id + '"]').classList.add('active');
        }

        window.onload = () => showSection('inbox');
    </script>
</head>

<body>

    <div class="container">
        <h2>📬 Alumni Messaging System</h2>

        <div class="tabs">
            <div class="tab active" data-tab="inbox" onclick="showSection('inbox')">Inbox</div>
            <div class="tab" data-tab="sent" onclick="showSection('sent')">Sent</div>
            <div class="tab" data-tab="compose" onclick="showSection('compose')">Compose</div>
        </div>

        <?php if (!empty($message_sent)): ?>
            <div class="success">✅ Message sent successfully!</div>
        <?php endif; ?>

        <!-- Inbox Section -->
        <div class="section active" id="inbox">
            <?php while ($row = $inbox_result->fetch_assoc()): ?>
                <div class="message-box">
                    <strong>From:</strong> <?= htmlspecialchars($row['sender_name']) ?><br>
                    <strong>Subject:</strong> <?= htmlspecialchars($row['subject']) ?><br>
                    <p><?= nl2br(htmlspecialchars($row['body'])) ?></p>
                    <small>📅 <?= $row['sent_at'] ?></small>
                </div>
            <?php endwhile; ?>
        </div>

        <!-- Sent Section -->
        <div class="section" id="sent">
            <?php while ($row = $sent_result->fetch_assoc()): ?>
                <div class="message-box">
                    <strong>To:</strong> <?= htmlspecialchars($row['receiver_name']) ?><br>
                    <strong>Subject:</strong> <?= htmlspecialchars($row['subject']) ?><br>
                    <p><?= nl2br(htmlspecialchars($row['body'])) ?></p>
                    <small>📅 <?= $row['sent_at'] ?></small>
                </div>
            <?php endwhile; ?>
        </div>

        <!-- Compose Section -->
        <div class="section" id="compose">
            <form method="POST">
                <label for="receiver_id">To:</label>
                <select name="receiver_id" required>
                    <option value="">-- Select a user --</option>
                    <?php while ($u = $users->fetch_assoc()): ?>
                        <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['username']) ?></option>
                    <?php endwhile; ?>
                </select>

                <label for="subject">Subject:</label>
                <input type="text" name="subject" required>

                <label for="body">Message:</label>
                <textarea name="body" rows="5" required></textarea>

                <button type="submit">Send Message</button>
            </form>
        </div>
    </div>

</body>

</html>