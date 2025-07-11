<?php
session_start();

$host = 'localhost';
$db = 'alumni_connector';
$user = 'root';
$pass = '';
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['userid'];

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $bio = trim($_POST['bio']);
    $degree = trim($_POST['degree']);
    $department = trim($_POST['department']);
    $graduation_year = trim($_POST['graduation_year']);
    $job_title = trim($_POST['job_title']);
    $company = trim($_POST['company']);
    $package = trim($_POST['package']);
    $location = trim($_POST['location']);
    $linkedin_url = trim($_POST['linkedin_url']);

    $stmt = $conn->prepare("UPDATE users SET username=?, bio=?, degree=?, department=?, graduation_year=?, job_title=?, company=?,package=?, location=?, linkedin_url=? WHERE id=?");
    $stmt->bind_param("ssssissssi", $username, $bio, $degree, $department, $graduation_year, $job_title, $company, $package, $location, $linkedin_url, $user_id);
    $stmt->execute();

    // Handle image upload
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === 0) {
        $fileTmpPath = $_FILES['profile_pic']['tmp_name'];
        $fileName = $_FILES['profile_pic']['name'];
        $fileSize = $_FILES['profile_pic']['size'];
        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png'];

        if (in_array($extension, $allowedExtensions) && $fileSize <= 2 * 1024 * 1024) {
            $newFileName = 'user_' . $user_id . '.' . $extension;
            $destPath = 'uploads/' . $newFileName;
            if (move_uploaded_file($fileTmpPath, $destPath)) {
                $update_pic = $conn->prepare("UPDATE users SET profile_pic=? WHERE id=?");
                $update_pic->bind_param("si", $newFileName, $user_id);
                $update_pic->execute();
            }
        } else {
            $_SESSION['error'] = "Invalid image file (must be JPG/PNG & <2MB).";
        }
    }

    $_SESSION['success'] = "Profile updated.";
    header("Location: profile.php");
    exit();
}

// Get user data
$stmt = $conn->prepare("SELECT username, email, bio, profile_pic, degree, department, graduation_year, job_title, company,package, location, linkedin_url FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username, $email, $bio, $profile_pic, $degree, $department, $graduation_year, $job_title, $company, $package, $location, $linkedin_url);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>User Profile</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f5f7fb;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 900px;
            margin: 50px auto;
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        }

        .profile-header {
            text-align: center;
        }

        .profile-header img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #1e293b;
        }

        .profile-header h2 {
            margin: 20px 0 10px;
        }

        .profile-header p {
            color: #64748b;
        }

        form {
            margin-top: 30px;
        }

        input[type="text"],
        input[type="email"],
        input[type="number"],
        input[type="url"],
        textarea {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 16px;
        }

        textarea {
            resize: vertical;
            height: 100px;
        }

        input[type="file"] {
            padding: 10px;
            background: #f0f0f0;
            border-radius: 6px;
            border: none;
        }

        button {
            background-color: #1e293b;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #334155;
        }

        .message {
            padding: 10px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .success {
            background-color: #d1fae5;
            color: #065f46;
        }

        .error {
            background-color: #fee2e2;
            color: #991b1b;
        }

        a.back {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #1e293b;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="profile-header">
            <img src="<?php echo $profile_pic ? 'uploads/' . htmlspecialchars($profile_pic) : 'https://via.placeholder.com/150'; ?>"
                alt="Profile Picture">
            <h2><?php echo htmlspecialchars($username); ?></h2>
            <p><?php echo htmlspecialchars($email); ?></p>
        </div>

        <?php
        if (isset($_SESSION['success'])) {
            echo '<div class="message success">' . $_SESSION['success'] . '</div>';
            unset($_SESSION['success']);
        }

        if (isset($_SESSION['error'])) {
            echo '<div class="message error">' . $_SESSION['error'] . '</div>';
            unset($_SESSION['error']);
        }
        ?>

        <form method="POST" enctype="multipart/form-data">
            <label for="username">Name:</label>
            <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>" required>

            <label for="bio">Bio:</label>
            <textarea name="bio"><?php echo htmlspecialchars($bio); ?></textarea>

            <label for="degree">Degree:</label>
            <input type="text" name="degree" value="<?php echo htmlspecialchars($degree); ?>">

            <label for="department">Department:</label>
            <input type="text" name="department" value="<?php echo htmlspecialchars($department); ?>">

            <label for="graduation_year">Graduation Year:</label>
            <input type="number" name="graduation_year" value="<?php echo htmlspecialchars($graduation_year); ?>">

            <label for="job_title">Job Title:</label>
            <input type="text" name="job_title" value="<?php echo htmlspecialchars($job_title); ?>">

            <label for="company">Company:</label>
            <input type="text" name="company" value="<?php echo htmlspecialchars($company); ?>">

            <label for="location">Package:</label>
            <input type="text" name="package" value="<?php echo htmlspecialchars(string: $package); ?>">


            <label for="location">Location:</label>
            <input type="text" name="location" value="<?php echo htmlspecialchars(string: $location); ?>">

            <label for="linkedin_url">LinkedIn URL:</label>
            <input type="url" name="linkedin_url" value="<?php echo htmlspecialchars($linkedin_url); ?>">

            <label for="profile_pic">Update Profile Picture:</label>
            <input type="file" name="profile_pic" accept="image/*">

            <button type="submit">Save Changes</button>
        </form>

        <a class="back" href="dashboard.php">← Back to Dashboard</a>
    </div>

</body>

</html>