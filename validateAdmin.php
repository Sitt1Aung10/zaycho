<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
</head>
<body>
    <h2>Admin Login</h2>
    <form action="admin_dashboard.php" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit">Login</button>
    </form>
</body>
</html>
<?php

session_start();

// Database connection details
$host = "localhost";
$dbname = "zaycho";
$username = "root";
$password = "";

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);


// Get data from the form
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

// Prepare SQL query to validate credentials
$adminQuery = "SELECT * FROM admin WHERE username = ? AND email = ? AND password = ?";
$stmt = $conn->prepare($adminQuery);
$stmt->bind_param("sss", $username, $email, $password);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();

if ($admin) {
    // Set session variables
    $_SESSION['admin_id'] = $admin['id'];
    $_SESSION['username'] = $admin['username'];
    $_SESSION['is_admin'] = true;

    // Update last login time
    $updateLoginTime = "UPDATE admin SET last_login = NOW() WHERE id = ?";
    $updateStmt = $conn->prepare($updateLoginTime);
    $updateStmt->bind_param("i", $admin['id']);
    $updateStmt->execute();

    // Redirect to admin dashboard
    header("Location: admin_dashboard.php");
    exit;
} else {
    echo "Invalid login credentials.";
}

// Assuming admin is authenticated and $username is their username
$loginTimeQuery = "UPDATE admin SET last_login = NOW() WHERE username = ?";
$stmt = $conn->prepare($loginTimeQuery);
$stmt->bind_param("s", $username);
$stmt->execute();

// Store login time in the session for reference if needed
$_SESSION['login_time'] = date("Y-m-d H:i:s");

// Update the last_logout time in the database
$logoutTimeQuery = "UPDATE admin SET last_logout = NOW() WHERE username = ?";
$stmt = $conn->prepare($logoutTimeQuery);
$stmt->bind_param("s", $_SESSION['username']);
$stmt->execute();

// Clear session data and redirect to the login page
session_unset();
session_destroy();
// header("Location:accountLogin.php");
exit;
?>
