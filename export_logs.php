<?php
$conn = new mysqli("localhost", "root", "", "alumni_connector");

header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename=activity_logs.csv');

$output = fopen("php://output", "w");
fputcsv($output, ['#', 'User', 'Action', 'Details', 'Level', 'Timestamp']);

$result = $conn->query("SELECT users.username, logs.action, logs.details, logs.level, logs.timestamp 
                        FROM activity_logs logs 
                        JOIN users ON logs.user_id = users.id 
                        ORDER BY logs.timestamp DESC");

$i = 1;
while ($row = $result->fetch_assoc()) {
    fputcsv($output, [
        $i++, 
        $row['username'], 
        $row['action'], 
        $row['details'], 
        $row['level'], 
        $row['timestamp']
    ]);
}
fclose($output);
exit();
