<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'alumni_connector');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SESSION['role'] === 'admin') {
    $title = $_POST['title'];
    $desc = $_POST['description'];
    $date = $_POST['event_date'];
    $loc = $_POST['location'];

    $stmt = $conn->prepare("INSERT INTO events (title, description, event_date, location) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $title, $desc, $date, $loc);
    $stmt->execute();

    // Log this action
    $log = $conn->prepare("INSERT INTO logs (user_id, action) VALUES (?, ?)");
    $adminId = $_SESSION['userid'];
    $action = "Added new event: $title";
    $log->bind_param("is", $adminId, $action);
    $log->execute();

    header("Location: admin_dashboard.php?event=added");
    exit();
} else {
    die("Unauthorized");
}
?>
