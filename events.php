<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'alumni_connector');

if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit();
}

$today = date('Y-m-d');
$result = $conn->query("SELECT * FROM events WHERE event_date >= '$today' ORDER BY event_date ASC");

$sampleEvents = [
    [
        'title' => 'Alumni Homecoming 2025',
        'event_date' => '2025-07-15',
        'location' => 'University Grounds, Main Hall',
        'description' => 'Reconnect with old friends, professors, and enjoy a nostalgic day filled with campus tours, food, and networking.'
    ],
    [
        'title' => 'Tech Alumni Meetup',
        'event_date' => '2025-08-20',
        'location' => 'San Francisco, CA',
        'description' => 'Join fellow alumni working in the tech industry for talks, panels, and career networking opportunities.'
    ],
    [
        'title' => 'Virtual Career Fair',
        'event_date' => '2025-09-10',
        'location' => 'Online (Zoom)',
        'description' => 'Connect with top employers and discover exciting job opportunities across various industries.'
    ]
];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Upcoming Events</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            position: relative;
            background: url('https://images.unsplash.com/photo-1543269865-cbf427effbad?auto=format&fit=crop&w=1350&q=80') no-repeat center center/cover;
        }

        /* Dark overlay */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.6);
            /* adjust darkness here */
            z-index: -1;
        }

        .overlay {
            min-height: 100vh;
            padding: 60px 20px;
        }

        .container {
            max-width: 960px;
            margin: auto;
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 10px 24px rgba(0, 0, 0, 0.2);
        }

        h2 {
            font-size: 28px;
            color: #0f172a;
            text-align: center;
            margin-bottom: 40px;
        }

        .event {
            background: #f1f5f9;
            padding: 24px;
            border-left: 6px solid #3b82f6;
            border-radius: 12px;
            margin-bottom: 25px;
            transition: box-shadow 0.3s ease;
        }

        .event:hover {
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        }

        .event h3 {
            font-size: 20px;
            margin: 0 0 10px;
            color: #1e293b;
        }

        .info {
            display: flex;
            gap: 20px;
            font-size: 14px;
            color: #475569;
            margin-bottom: 10px;
        }

        .info span {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .info .date::before {
            content: "📅";
        }

        .info .location::before {
            content: "📍";
        }

        .event p {
            margin: 0;
            color: #334155;
            line-height: 1.5;
        }
    </style>
</head>

<body>
    <div class="overlay">
        <div class="container">
            <h2>📅 Upcoming Alumni Events</h2>

            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="event">
                        <h3><?= htmlspecialchars($row['title']) ?></h3>
                        <div class="info">
                            <span class="date"><?= htmlspecialchars($row['event_date']) ?></span>
                            <span class="location"><?= htmlspecialchars($row['location']) ?></span>
                        </div>
                        <p><?= nl2br(htmlspecialchars($row['description'])) ?></p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <?php foreach ($sampleEvents as $row): ?>
                    <div class="event">
                        <h3><?= htmlspecialchars($row['title']) ?></h3>
                        <div class="info">
                            <span class="date"><?= htmlspecialchars($row['event_date']) ?></span>
                            <span class="location"><?= htmlspecialchars($row['location']) ?></span>
                        </div>
                        <p><?= nl2br(htmlspecialchars($row['description'])) ?></p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>