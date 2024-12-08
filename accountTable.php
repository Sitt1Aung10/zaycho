<?php
// Database connection details
$host = "localhost";
$dbname = "zaycho";
$username = "root";
$password = "";

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

$userAccounts = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(20) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    nrcText VARCHAR(255) NOT NULL,
    nrcFile LONGBLOB NOT NULL,
    address VARCHAR(255) NOT NULL,
    can_login TINYINT(1) DEFAULT 1 NOT NULL
)";


if ($conn->query($userAccounts) !== TRUE) {
    // Handle error
}

error_reporting(1);
if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $address = mysqli_real_escape_string($conn,$_POST['address']);
    $nrcText = mysqli_real_escape_string($conn, $_POST['nrcText']);
    $nrcFile = mysqli_real_escape_string($conn, file_get_contents($_FILES['nrc']['tmp_name']));
    
    // Check if user already exists
    $query = "SELECT * FROM users WHERE name = '$name' AND email = '$email'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $numRows = mysqli_num_rows($result);
        if ($numRows > 0) {
            $err = "User Already Exists!";
        } else {
            // Insert new user
            $stmt = $conn->prepare("INSERT INTO users (name, email, password,address, nrcText, nrcFile , can_login) VALUES (?, ?, ?,?, ?, ? , 1)");
            $stmt->bind_param("ssssss", $name, $email, $password,$address, $nrcText, $nrcFile);
            $stmt->execute();
            $stmt->close();
            header("Location: accountLogin.php");
            exit();
        }
    } else {
        $err = "Error executing query: " . mysqli_error($conn);
    }
}
