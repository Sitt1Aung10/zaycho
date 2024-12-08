<?php
// Database connection
$servername = "localhost";
$dbname = "zaycho";
$mysqli = new mysqli($servername, "root", "", $dbname);

if ($mysqli->connect_errno) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Retrieve and sanitize POST data
$orderId = htmlspecialchars($_POST['orderId']);
$status = htmlspecialchars($_POST['status']);

// Update the status in the database
$query = "UPDATE orderConfirm SET status = ? WHERE post_id = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param('si', $status, $orderId);
$stmt->execute();

// Close the database connection
$stmt->close();
$mysqli->close();
?>
