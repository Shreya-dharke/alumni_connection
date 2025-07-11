<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Alumni Connector - Register</title>
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <div class="form-wrapper">
        <form method="POST" action="register.php">
            <h2>Registeration for Alumni Connection</h2>

            <?php
            session_start();
            if (isset($_SESSION['error'])) {
                echo '<p class="message error">' . $_SESSION['error'] . '</p>';
                unset($_SESSION['error']);
            }
            ?>

            <input type="text" name="username" placeholder="Username" required />
            <input type="email" name="email" placeholder="Email" required />
            <input type="password" name="password" placeholder="Password" required />
            <select name="role" required>
                <option value="" disabled selected>Select Role</option>
                <option value="user">Alumni</option>
                <option value="admin">Admin</option>
            </select>
            <button type="submit">Register</button>

            <p>Already registered? <a href="login.php">Login here</a></p>
        </form>
    </div>
</body>

</html>