<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database credentials
$mysqli = new mysqli('your-server', 'your-username', 'your-password', 'your-database');

// Check for connection errors
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$action = $_GET['action'] ?? '';

if ($action === 'fetch') {
    // Fetch today's and yesterday's total duration
    $today_result = $mysqli->query("SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(duration))) AS total_duration FROM clock_records WHERE DATE(clock_in_time) = CURDATE()");
    $yesterday_result = $mysqli->query("SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(duration))) AS total_duration FROM clock_records WHERE DATE(clock_in_time) = CURDATE() - INTERVAL 1 DAY");

    // Fetch total duration for the last 7 days, grouped by date
    $last7days_result = $mysqli->query("
        SELECT DATE(clock_in_time) AS date, SEC_TO_TIME(SUM(TIME_TO_SEC(duration))) AS total_duration 
        FROM clock_records 
        WHERE clock_in_time >= CURDATE() - INTERVAL 6 DAY
        GROUP BY DATE(clock_in_time)
        ORDER BY DATE(clock_in_time) DESC
    ");

    $last7days_durations = [];
    while ($row = $last7days_result->fetch_assoc()) {
        $last7days_durations[] = $row;
    }

    // Fetch the latest 5 clock records
    $result = $mysqli->query("SELECT clock_in_time, clock_out_time, duration FROM clock_records ORDER BY clock_in_time DESC LIMIT 5");

    $clock_records = [];
    while ($record = $result->fetch_assoc()) {
        $clock_records[] = $record;
    }

    // Check the current status (clocked in or out)
    $status_result = $mysqli->query("SELECT * FROM clock_records WHERE clock_out_time IS NULL ORDER BY clock_in_time DESC LIMIT 1");
    $status = 'none';
    if ($status_result->num_rows > 0) {
        $status = 'clocked_in';
    } else {
        $status_result = $mysqli->query("SELECT * FROM clock_records WHERE clock_in_time IS NOT NULL AND clock_out_time IS NOT NULL ORDER BY clock_in_time DESC LIMIT 1");
        if ($status_result->num_rows > 0) {
            $status = 'clocked_out';
        }
    }

    // Prepare the response data
    echo json_encode([
        'today_duration' => $today_result->fetch_assoc()['total_duration'] ?? '00:00:00',
        'yesterday_duration' => $yesterday_result->fetch_assoc()['total_duration'] ?? '00:00:00',
        'last7days_durations' => $last7days_durations,
        'records' => $clock_records,
        'clock_status' => $status
    ]);
} elseif ($action === 'clock_in') {
    // Prevent multiple clock-ins
    $result = $mysqli->query("SELECT * FROM clock_records WHERE clock_out_time IS NULL ORDER BY clock_in_time DESC LIMIT 1");
    if ($result->num_rows > 0) {
        echo json_encode(['error' => 'You are already clocked in.']);
    } else {
        $stmt = $mysqli->prepare("INSERT INTO clock_records (clock_in_time) VALUES (NOW())");
        $stmt->execute();
        echo json_encode(['clock_in_time' => date('Y-m-d H:i:s')]);
    }
} elseif ($action === 'clock_out') {
    // Ensure user is clocked in before clocking out
    $result = $mysqli->query("SELECT * FROM clock_records WHERE clock_out_time IS NULL ORDER BY clock_in_time DESC LIMIT 1");
    if ($result->num_rows === 0) {
        echo json_encode(['error' => 'You must clock in before clocking out.']);
    } else {
        $stmt = $mysqli->prepare("UPDATE clock_records SET clock_out_time = NOW(), duration = TIMEDIFF(NOW(), clock_in_time) WHERE clock_out_time IS NULL LIMIT 1");
        $stmt->execute();
        echo json_encode(['clock_out_time' => date('Y-m-d H:i:s')]);
    }
}

$mysqli->close();
?>
