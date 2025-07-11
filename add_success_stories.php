<?php
$conn = new mysqli("localhost", "root", "", "alumni_connector");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST['name']);
    $title = trim($_POST['title']);
    $story = trim($_POST['story']);
    $image_url = "";
    $created_at = date('Y-m-d H:i:s');

    // Handle image upload
    if (!empty($_FILES['image']['name'])) {
        $upload_dir = "uploads/";
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $image_name = basename($_FILES['image']['name']);
        $image_path = $upload_dir . time() . "_" . $image_name;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
            $image_url = $image_path;
        }
    }

    $stmt = $conn->prepare("INSERT INTO success_stories (name, title, story, image_url, created_at) VALUES (?, ?, ?, ?, ?)");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("sssss", $name, $title, $story, $image_url, $created_at);

    if ($stmt->execute()) {
        $message = "🎉 Story added successfully!";
    } else {
        $message = "❌ Error: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Success Story</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #eef2f7;
            padding: 30px;
        }

        .form-box {
            background: #fff;
            max-width: 600px;
            margin: auto;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        label {
            font-weight: bold;
            margin-top: 10px;
            display: block;
        }

        input, textarea {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            border: 1px solid #ccc;
            border-radius: 8px;
            resize: vertical;
        }

        button {
            background-color: #007bff;
            color: white;
            padding: 12px 20px;
            margin-top: 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }

        button:hover {
            background-color: #0056b3;
        }

        .message {
            text-align: center;
            margin-top: 20px;
            color: green;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="form-box">
    <h2>Add Success Story</h2>
    <?php if (!empty($message)) echo "<p class='message'>$message</p>"; ?>

    <form method="POST" enctype="multipart/form-data">
        <label for="name">Alumni Name:</label>
        <input type="text" name="name" id="name" required>

        <label for="title">Story Title:</label>
        <input type="text" name="title" id="title" required>

        <label for="story">Story Content:</label>
        <textarea name="story" id="story" rows="6" required></textarea>

        <label for="image">Upload Image:</label>
        <input type="file" name="image" id="image" accept="image/*">

        <button type="submit">Submit Story</button>
    </form>
</div>

</body>
</html>
