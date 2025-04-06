<?php
ini_set('session.cookie_path', '/');
session_start(); // Start the session

// Display errors for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

if (isset($_POST['signinBtn'])) {

    // Sanitize input
    $name = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);

    // Check if the user can log in
$sqlCheckLoginStatus = "SELECT can_login FROM users WHERE name = ?";
$stmtCheck = $conn->prepare($sqlCheckLoginStatus);
$stmtCheck->bind_param('s', $name);
$stmtCheck->execute();
$stmtCheck->store_result();

// If user doesn't exist, exit with an error
if ($stmtCheck->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'User not found']);
    exit();
}

// Bind result and check if can_login is 0
$stmtCheck->bind_result($can_login);
$stmtCheck->fetch();

if ($can_login == 0) {
    echo json_encode(['success' => false, 'message' => 'Your account is marked for deletion and cannot be accessed at this time.']);
    exit();
}

// Continue with the rest of your code to proceed with login...
// Example: if username and password are valid, set session and redirect

$stmtCheck->close();


    // Check if user exists (consider hashing passwords in your database)
    $sql = "SELECT * FROM users WHERE name='$name' AND email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // User found, fetch the user's data
        $user = $result->fetch_assoc();

        // Verify the password (replace 'your_password_hash' with the hashed password from the database)
        if ($password === $user['password']) { // Change this to use password_verify() if hashed
            $_SESSION['username'] = $user['name'];

            // Check if 'Remember Me' checkbox is checked
            if (isset($_POST['checkbox'])) {
                // Set cookies for approximately 10 years
                setcookie('username', $name, time() + (10 * 365 * 24 * 60 * 60), "/");
                setcookie('email', $email, time() + (10 * 365 * 24 * 60 * 60), "/");
            }

            if ($user["usertype"] == "user") {
                // Redirect to profile page
                header("Location: profile.php");
                exit();
            }
            if ($user["usertype"] == "admin") {
                header("Location: admin_dashboard.php");
            }
        } else {
            echo "<script>alert('Incorrect password!'); window.location.href='accountCreate.php';</script>";
        }
    } else {
        // User not found
        echo "<script>alert('User not found!'); window.location.href='accountCreate.php';</script>";
    }
    $conn->close();
}

// Check if the user is logged in
$isLoggedIn = isset($_SESSION['username']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            margin: 0;
        }

        form {
            width: calc(100% - 70%);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: transparent;
            box-shadow: 0 0 10px #fff;
            padding: 10px;
            box-sizing: border-box;
        }

        form>h2 {
            color: #fff;
            background-color: #232F3E;
            padding: 10px;
            border-radius: 5px;
            box-sizing: border-box;
            text-transform: capitalize;
            /* Ensure productKind displays nicely */
        }

        label {
            color: gray;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: calc(100% - 10%);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        input[type=checkbox] {
            z-index: 999;
        }

        input[type=checkbox]+label {
            margin-left: -5px !important;
            user-select: none;
        }

        .sign-in-btn {
            display: block;
            padding: 10px;
            box-sizing: border-box;
            border-radius: 5px;
            border: 1px solid #fff;
            color: #fff;
            background-color: transparent;
            cursor: pointer;
        }

        .sign-in-btn:hover {
            background-color: #090015;
        }

        .sign-in-btn ~ a ,
        .sign-in-btn ~ a + a {
            display: block;
            padding: 10px;
            box-sizing: border-box;
            border-radius: 5px;
            border: 1px solid #fff;
            color: #fff;
            cursor: pointer;
            text-align: center;
            margin-top: 5px;
            text-decoration: none;
        }
        .disabled {
            pointer-events: none;
            color: gray;
        }

        .message {
            color: red;
            font-size: 14px;
        }

        section {
            width: 100%;
            height: 100vh;
            background-image: url("img/testHomeImg.webp");
            background-size: 100%;
            background-repeat: no-repeat;
            background-position: 0 0;
            background-color: #000000c5;
            background-blend-mode: color;
        }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>

<body>
    <script>
        $(document).ready(function() {
            $('body').css('opacity', 0).animate({
                opacity: 1
            }, 1000); // Adjust the duration as needed
        });
    </script>

    <section>
        <form method="post">
            <h2>Log In An Account</h2>
            <label for="name">UserName</label><br>
            <input type="text" id="name" name="username" required><br>

            <label for="email">Email</label><br>
            <input type="email" id="email" name="email" required><br>

            <label for="password">Password</label><br>
            <input type="password" id="password" name="password" required><br>

            <a href="resetPassword.php" class="forgot-password" style="color: #fff;">Forgot Your Password?</a>

            <div>
                <input type="checkbox" id="checkbox" name="checkbox">
                <label for="checkbox">Remember Me</label>
            </div>
            <br>

            <button class="sign-in-btn" type="submit" name="signinBtn">Log In</button>
            <a href="accountCreate.php">Sign Up</a>
            <a href="index.php">HomePage</a>
            <!-- <a style="z-index: 999;" href="validateAdmin.php">Admin Panel</a> -->
            <!-- 
        </form>
    </section>


</body>

</html>