<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Campus Connect - Login</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', sans-serif;
            background: url('https://images.unsplash.com/photo-1580587771525-78b9dba3b914?auto=format&fit=crop&w=1950&q=80') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            position: relative;
        }

        /* Overlay to improve contrast and brightness */
        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.6);
            z-index: 0;
            /* Remove the line below */
            /* backdrop-filter: blur(3px); */
        }

        .form-wrapper {
            position: relative;
            z-index: 1;
            background-color: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 400px;
            margin: 100px auto;
            text-align: center;
        }

        form h2 {
            margin-bottom: 20px;
            color: #2c3e50;
        }

        input {
            width: 100%;
            padding: 12px 15px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #0077b6;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 10px;
            transition: background 0.3s ease;
        }

        button:hover {
            background-color: #023e8a;
        }

        .message {
            font-size: 14px;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 15px;
        }

        .error {
            background-color: #fdecea;
            color: #d32f2f;
        }

        .success {
            background-color: #e7f6e7;
            color: #388e3c;
        }

        p {
            margin-top: 15px;
            font-size: 14px;
        }

        a {
            color: #0077b6;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        @media (max-width: 500px) {
            .form-wrapper {
                margin: 50px 20px;
            }
        }
    </style>
</head>

<body>
    <div class="form-wrapper">
        <form method="POST" action="login_process.php">
            <h2>Login to Alumni Connect</h2>

            <?php
            if (isset($_SESSION['error'])) {
                echo '<p class="message error">' . $_SESSION['error'] . '</p>';
                unset($_SESSION['error']);
            }
            if (isset($_SESSION['success'])) {
                echo '<p class="message success">' . $_SESSION['success'] . '</p>';
                unset($_SESSION['success']);
            }
            ?>

            <input type="email" name="email" placeholder="Email" required />
            <input type="password" name="password" placeholder="Password" required />
            <button type="submit">Login</button>

            <p>New user? <a href="regiser.php">Register here</a></p>
        </form>
    </div>
</body>

</html>