<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'alumni_connector');

if (!isset($_SESSION['userid']) || $_SESSION['role'] !== 'admin') {
    die("Access denied.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Panel - Alumni Connector</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: url('https://images.unsplash.com/photo-1497493292307-31c376b6e479') no-repeat center center fixed;
      background-size: cover;
      font-family: 'Segoe UI', sans-serif;
    }
    .card {
      backdrop-filter: blur(8px);
      background-color: rgba(255,255,255,0.8);
      border: none;
      border-radius: 20px;
      box-shadow: 0 8px 16px rgba(0,0,0,0.2);
    }
    .form-control, .btn {
      border-radius: 10px;
    }
  </style>
</head>
<body class="text-dark">
  <div class="container py-5">
    <h2 class="text-center mb-4">Welcome Admin 👋</h2>
    
    <!-- Event Add Form -->
    <div class="row justify-content-center mb-5">
      <div class="col-md-8">
        <div class="card p-4">
          <h4 class="mb-3">Add New Event</h4>
          <form method="POST" action="process_event.php">
            <input type="text" class="form-control mb-3" name="title" placeholder="Event Title" required>
            <textarea class="form-control mb-3" name="description" placeholder="Event Description" required></textarea>
            <input type="date" class="form-control mb-3" name="event_date" required>
            <input type="text" class="form-control mb-3" name="location" placeholder="Location" required>
            <button class="btn btn-success" type="submit">Create Event</button>
          </form>
        </div>
      </div>
    </div>

    <!-- Activity Logs -->
    <div class="row justify-content-center">
      <div class="col-md-10">
        <div class="card p-4">
          <h4 class="mb-3">Activity Logs</h4>
          <table class="table table-bordered table-hover table-striped bg-white">
            <thead class="table-dark">
              <tr>
                <th>#</th>
                <th>Action</th>
                <th>User</th>
                <th>Timestamp</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $logs = $conn->query("SELECT logs.*, users.username FROM logs JOIN users ON logs.user_id = users.id ORDER BY timestamp DESC LIMIT 10");
              $i = 1;
              while ($row = $logs->fetch_assoc()) {
                echo "<tr>
                        <td>{$i}</td>
                        <td>{$row['action']}</td>
                        <td>{$row['username']}</td>
                        <td>{$row['timestamp']}</td>
                      </tr>";
                $i++;
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
