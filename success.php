<?php
session_start(); // Start the session

if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        img {
            max-width: 150px;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <h1>Account Created Successfully!</h1>
    <p><a href="accountLogin.php">Log In</a></p>
</body>
</html>