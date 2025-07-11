<?php
session_start();
if (!isset($_SESSION['username'])) {
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
    <title>Alumni Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #f8fafc;
            --primary: #1d4ed8;
            --primary-dark: #1e40af;
            --text: #1e293b;
            --card-bg: #ffffff;
            --gray: #64748b;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: var(--bg);
            color: var(--text);
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 40px;
            background-color: var(--card-bg);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar h1 {
            font-size: 24px;
            color: var(--primary-dark);
        }

        .navbar .user {
            background: var(--primary);
            color: white;
            padding: 10px 16px;
            border-radius: 20px;
            font-weight: 500;
        }

        .menu {
            background: var(--primary-dark);
            padding: 30px 20px;
            color: white;
            width: 220px;
            min-height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 80px;
        }

        .menu h2 {
            margin-bottom: 30px;
            text-align: center;
        }

        .menu a {
            display: block;
            color: #cbd5e1;
            text-decoration: none;
            margin: 15px 0;
            padding: 10px 15px;
            border-radius: 10px;
            transition: background 0.2s ease;
        }

        .menu a:hover {
            background: #334155;
        }

        .main {
            margin-left: 240px;
            padding: 40px;
        }

        .hero {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: white;
            padding: 30px;
            border-radius: 20px;
            margin-bottom: 40px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }

        .hero-text h2 {
            font-size: 26px;
            margin-bottom: 10px;
        }

        .hero-text p {
            color: var(--gray);
            font-size: 16px;
        }

        .hero img {
            width: 180px;
            border-radius: 12px;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 30px;
        }

        .card {
            background: var(--card-bg);
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.08);
        }

        .card img {
            width: 100%;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .card h3 {
            margin-bottom: 10px;
            color: var(--primary-dark);
        }

        .card p {
            font-size: 14px;
            color: var(--gray);
        }

        @media (max-width: 768px) {
            .menu {
                display: none;
            }

            .main {
                margin-left: 0;
                padding: 20px;
            }

            .hero {
                flex-direction: column;
                text-align: center;
            }

            .hero img {
                margin-top: 20px;
            }

            .navbar {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</head>

<body>

    <div class="menu">
        <h2>Alumni Portal</h2>
        <a href="#">🏠 Dashboard</a>
        <a href="profile.php">📄 Profile</a>
        <a href="directory.php">👥 Directory</a>
        <a href="messages.php">📬 Messages</a>
        <a href="events.php">📅 Events</a>
        <a href="logout.php">🚪 Logout</a>
    </div>

    <div class="main">
        <div class="navbar">
            <h1>Welcome Back, <?php echo htmlspecialchars($username); ?> 👋</h1>
            <div class="user">👤 <?php echo htmlspecialchars($username); ?></div>
        </div>

        <div class="hero">
            <div class="hero-text">
                <h2>Stay Connected. Stay Involved.</h2>
                <p>Explore new events, find jobs, and reconnect with fellow alumni from your university!</p>
            </div>
            <img src="https://images.unsplash.com/photo-1596495577886-d920f1fb7238?auto=format&fit=crop&w=400&q=80"
                alt="Alumni">
        </div>

        <div class="cards">
            <!-- Repeat this block for each card -->
            <div class="card">
                <img src="https://www.shutterstock.com/image-vector/gradient-color-upcoming-events-origami-260nw-2502261829.jpg"
                    alt="Events">
                <h3>Upcoming Events</h3>
                <p>Join alumni meetups, reunions, and online webinars to stay involved.</p>
            </div>

            <div class="card">
                <img src="https://connect.smumn.edu/image/header-images/header-alumni-directory.png" alt="Directory">
                <h3>Alumni Directory</h3>
                <p>Browse alumni by batch, industry, or location and connect easily.</p>
            </div>

            <div class="card">
                <img src="https://t4.ftcdn.net/jpg/03/08/43/19/360_F_308431972_g5fuiXwgOZpDCMFQougq13hgSaQVHVro.jpg"
                    alt="Messages">
                <h3>Messages</h3>
                <p>Check your inbox and connect directly with old friends and mentors.</p>
            </div>

            <div class="card">
                <img src="https://c8.alamy.com/comp/CWRYNF/job-search-concept-in-word-tag-cloud-on-white-background-CWRYNF.jpg"
                    alt="Jobs">
                <h3>Job Board</h3>
                <p>Find and share exclusive job opportunities in the alumni network.</p>
            </div>

            <div class="card">
                 <a href="success_stories.php">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTFOl5z-_8DMzM63g5Jq0l4mB8VArAnTnQukg&s"
                    alt="Stories">
                <h3>Success Stories</h3>
                <p>Get inspired by featured alumni who are making a difference worldwide.</p>
            </div>

            <div class="card">
                <img src="https://ideas.shutterfly.com/wp-content/uploads/2024/08/gallery-wall-layouts-hero-1058x480.png"
                    alt="Photos">
                <h3>Photo Gallery</h3>
                <p>View and upload photos from past alumni events and reunions.</p>
            </div>
        </div>
    </div>

</body>

</html>