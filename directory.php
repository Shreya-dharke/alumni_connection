<?php
session_start();
$host = 'localhost';
$db = 'alumni_connector';
$user = 'root';
$pass = '';
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error)
    die("Connection failed: " . $conn->connect_error);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Alumni Directory</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            background: linear-gradient(to right, #f1f5f9, #e2e8f0);
            color: #1e293b;
            padding: 20px;
        }

        h1 {
            font-size: 32px;
            font-weight: 600;
            margin-bottom: 30px;
            text-align: center;
            color: #0f172a;
        }

        .filters {
            max-width: 900px;
            margin: 0 auto 30px;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
        }

        .filters input,
        .filters select {
            padding: 10px;
            font-size: 14px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            outline: none;
            min-width: 200px;
            background-color: #fff;
        }

        .directory {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
            max-width: 1300px;
            margin: 0 auto;
        }

        .card {
            background: white;
            padding: 20px;
            border-radius: 18px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.08);
        }

        .card img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 15px;
            border: 3px solid #e2e8f0;
        }

        .card h3 {
            font-size: 18px;
            margin-bottom: 6px;
            color: #0f172a;
        }

        .card p {
            margin: 4px 0;
            color: #475569;
            font-size: 14px;
        }

        .card a {
            display: inline-block;
            margin-top: 8px;
            font-size: 13px;
            color: #2563eb;
            text-decoration: none;
            font-weight: 500;
        }

        .card a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <h1>🎓 Alumni Directory</h1>

    <form method="GET" class="filters">
        <input type="text" name="search" placeholder="Search by name, job, location..."
            value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
        <select name="department">
            <option value="">All Departments</option>
            <option value="Computer Science">Computer Science</option>
            <option value="Business">Business</option>
            <option value="Engineering">Engineering</option>
            <option value="Arts">Arts</option>
            <!-- Add more dynamically if needed -->
        </select>
        <select name="year">
            <option value="">Graduation Year</option>
            <?php for ($y = date("Y"); $y >= 1980; $y--): ?>
                <option value="<?= $y ?>" <?= (($_GET['year'] ?? '') == $y) ? 'selected' : '' ?>><?= $y ?></option>
            <?php endfor; ?>
        </select>
        <input type="submit" value="🔍 Filter">
    </form>

    <div class="directory">
        <?php
        $conditions = [];
        $params = [];
        $types = "";

        if (!empty($_GET['search'])) {
            $search = "%" . $_GET['search'] . "%";
            $conditions[] = "(username LIKE ? OR company LIKE ? OR job_title LIKE ? OR location LIKE ?)";
            $params = array_merge($params, array_fill(0, 4, $search));
            $types .= "ssss";
        }

        if (!empty($_GET['department'])) {
            $conditions[] = "department = ?";
            $params[] = $_GET['department'];
            $types .= "s";
        }

        if (!empty($_GET['year'])) {
            $conditions[] = "graduation_year = ?";
            $params[] = $_GET['year'];
            $types .= "s";
        }

        $sql = "SELECT username, email, degree, department, graduation_year, job_title, company, location, linkedin_url, profile_pic FROM users";
        if ($conditions) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }

        $stmt = $conn->prepare($sql);
        if ($params) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()):
            ?>
            <div class="card">
                <img src="<?= htmlspecialchars($row['profile_pic'] ? 'uploads/' . $row['profile_pic'] : 'https://via.placeholder.com/100') ?>"
                    alt="Profile">
                <h3><?= htmlspecialchars($row['username']) ?></h3>
                <p><?= htmlspecialchars($row['degree']) ?> - <?= htmlspecialchars($row['department']) ?></p>
                <p>🎓 <?= htmlspecialchars($row['graduation_year']) ?></p>
                <p>💼 <?= htmlspecialchars($row['job_title']) ?> at <?= htmlspecialchars($row['company']) ?></p>
                <p>📍 <?= htmlspecialchars($row['location']) ?></p>
                <?php if (!empty($row['linkedin_url'])): ?>
                    <a href="<?= htmlspecialchars($row['linkedin_url']) ?>" target="_blank">🔗 LinkedIn Profile</a>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </div>
</body>

</html>