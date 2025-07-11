<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $host = 'localhost';
    $db = 'alumni_connector';
    $user = 'root';
    $pass = '';

    $conn = new mysqli($host, $user, $pass, $db);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $username, $hashedPassword, $role);

    if ($stmt->num_rows === 1) {
        $stmt->fetch();
        if (password_verify($password, $hashedPassword)) {
            $_SESSION['userid'] = $id;
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $role;
            $_SESSION['success'] = "Login successful! Welcome, $username.";

            // Redirect based on role
            if ($role === 'admin') {
                header("Location: admin_dashboard.php");
            } else {
                header("Location: dashboard.php");
            }
            exit();
        } else {
            $_SESSION['error'] = "Invalid password.";
            header("Location: login.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "No account found with that email.";
        header("Location: login.php");
        exit();
    }
}
?>