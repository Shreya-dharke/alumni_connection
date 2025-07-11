<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'alumni_connector');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Admin-only: Add story
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['userid']) && $_SESSION['userid'] == 1) {
    $name = $_POST['name'];
    $title = $_POST['title'];
    $story = $_POST['story'];
    $image_url = $_POST['image_url'];

    $stmt = $conn->prepare("INSERT INTO success_stories (name, title, story, image_url) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $title, $story, $image_url);
    $stmt->execute();
}

// Fetch stories
$stories = $conn->query("SELECT * FROM success_stories ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Success Stories</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f9fafb;
            margin: 0;
            padding: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }

        .card {
            background: #fff;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
            transition: transform 0.2s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .card h3 {
            margin: 0 0 5px;
        }

        .card p {
            font-size: 14px;
            color: #444;
        }

        form {
            max-width: 500px;
            margin: 30px auto;
            background: #fff;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.07);
        }

        form input, form textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 8px;
            border: 1px solid #ccc;
        }

        form button {
            background: #2563eb;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }

        form button:hover {
            background: #1e40af;
        }
    </style>
</head>
<body>

<h1>🌟 Alumni Success Stories</h1>

<?php if (isset($_SESSION['userid']) && $_SESSION['userid'] == 1): ?>
<form method="POST">
    <h3>Add New Story</h3>
    <input type="text" name="name" placeholder="Alumni Name" required>
    <input type="text" name="title" placeholder="Story Title" required>
    <textarea name="story" placeholder="Success story..." required></textarea>
    <input type="text" name="image_url" placeholder="Image URL" required>
    <button type="submit">Post Story</button>
</form>
<?php endif; ?>

<div class="grid">
    <?php while ($row = $stories->fetch_assoc()): ?>
        <div class="card">
            <img src="<?= htmlspecialchars($row['image_url']) ?>" alt="Success">
            <h3><?= htmlspecialchars($row['name']) ?></h3>
            <strong><?= htmlspecialchars($row['title']) ?></strong>
            <p><?= nl2br(htmlspecialchars($row['story'])) ?></p>
        </div>
    <?php endwhile; ?>
</div>

</body>
</html>
