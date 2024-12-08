<?php
// Check if the session is already started
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Start the session only if it hasn't started yet
}

// Assume session is already started and user is logged in
if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

$username = $_SESSION['username']; // Get the current username

// Database connection
$servername = "localhost";
$usernameDB = "root";
$passwordDB = "";
$dbname = "zaycho";

$mysqli = new mysqli($servername, $usernameDB, $passwordDB, $dbname);

// Check for connection errors
if ($mysqli->connect_errno) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $mysqli->connect_error]);
    exit();
}

// Mark account as pending deletion
$sql = "UPDATE users SET deletion_requested_at = NOW(), can_login = 0 WHERE name = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('s', $username);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(['success' => true, 'message' => 'Account marked for deletion. The process will complete in 1 week.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error: Unable to mark account for deletion.']);
}

// Optionally, run a cleanup query for accounts pending deletion for more than 7 days
$sqlDelete = "DELETE FROM users WHERE deletion_requested_at IS NOT NULL AND deletion_requested_at <= (NOW() - INTERVAL 7 DAY)";
$mysqli->query($sqlDelete);

$stmt->close();
$mysqli->close();
?>
