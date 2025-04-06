<?php
    // Check if the session is already started
    if (session_status() === PHP_SESSION_NONE) {
        session_start(); // Start the session only if it hasn't started yet
    }
// Database connection details
$host = "localhost";
$dbname = "zaycho";
$username = "root";
$password = "";

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user address based on some unique identifier (e.g., email or user ID)
    // Get the logged-in user's username
    $username = $_SESSION['username'] ?? 'guest';  // Assuming username is stored in session
$query = "SELECT address FROM users WHERE name = '$username'";
$result = $conn->query($query);

// Initialize address variable
$userAddress = '';

if ($result->num_rows > 0) {
    // Fetch address from the result
    $row = $result->fetch_assoc();
    $userAddress = $row['address'];
}
?>
