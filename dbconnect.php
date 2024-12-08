<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "zaycho";

// Create a connection to the MySQL server
$mysqli = new mysqli($servername, $username, $password, $dbname);

// Check for connection errors
if ($mysqli->connect_errno) {
    die("Connection failed: " . $mysqli->connect_error);
} 

echo "Connected successfully";

// Optional: You can close the connection when done
$mysqli->close();
?>
