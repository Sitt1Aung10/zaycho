<style>
     form {
          width: 500px;
          height: 400px;
          position: fixed;
          left: 50%;top: 50%;
          transform: translate(-50% , -50%);
          background-color: #000;
          display: flex;
          flex-direction: column;
          gap: 10px;
          padding: 10px 20px 10px 20px;
          box-sizing: border-box;
     }
     input {
          padding: 10px 30px 10px 30px;
     }
     h1 {
          text-align: center;
     }
</style>
<h1>Password Reset</h1>
<form method="POST">
    <input type="text" name="username" placeholder="Enter your name" required>
    <br>
    <input type="email" name="email" placeholder="Enter your email" required>
    <br>
    <input type="text" name="nrcText" placeholder="Enter your NRC text" required>
    <br>
    <input type="password" name="newPassword" placeholder="Enter your new password" required>
    <button type="submit" name="reset">Reset Password</button>
</form>
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

error_reporting(1);

if (isset($_POST['reset'])) {
    $name = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $nrcText = mysqli_real_escape_string($conn, $_POST['nrcText']);
    $newPassword = mysqli_real_escape_string($conn, $_POST['newPassword']);
    // Print the values to debug 
    echo "Name: $name, Email: $email, NRC: $nrcText, New Password: $newPassword<br>";

    // Check if user exists
    $query = "SELECT * FROM users WHERE name = '$name' AND email = '$email' AND nrcText = '$nrcText'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $numRows = mysqli_num_rows($result);
        if ($numRows > 0) {
            // Update password
            $updateQuery = "UPDATE users SET password = '$newPassword' WHERE name = '$name' AND email = '$email' AND nrcText = '$nrcText'";
            if (mysqli_query($conn, $updateQuery)) {
                header("Location: accountLogin.php"); // Redirect to login page
                exit();
            } else {
                $err = "Error updating password: " . mysqli_error($conn);
            }
        } else {
            $err = "User does not exist or incorrect details provided.";
        }
    } else {
        $err = "Error executing query: " . mysqli_error($conn);
    }
}

// Display any errors
if (isset($err)) {
    echo $err;
}
?>
