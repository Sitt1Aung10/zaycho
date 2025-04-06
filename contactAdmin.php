<?php
// Load PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Use this if installed via Composer

// Database connection (optional)
$host = 'localhost';
$db = 'zaycho';
$user = '';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $phone = htmlspecialchars($_POST['phone']);
    $message = htmlspecialchars($_POST['message']);

    // Check if email exists in the database (optional)
    $stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Email is registered, proceed with sending the email
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP(); // Set mailer to use SMTP
            $mail->Host = 'smtp.gmail.com'; // Specify main and backup SMTP servers
            $mail->SMTPAuth = true; // Enable SMTP authentication
            $mail->Username = 'myinthtay765221570@gmail.com'; // Your Gmail address
            $mail->Password = 'SittAung'; // Gmail app-specific password
            $mail->SMTPSecure = 'tls'; // Enable TLS encryption
            $mail->Port = 587; // TCP port to connect to

            // Recipients
            $mail->setFrom($email, 'Website User');
            $mail->addAddress('myinthtay765221570@gmail.com'); // Your receiving email

            // Content
            $mail->isHTML(true); // Set email format to HTML
            $mail->Subject = 'User Problem or Suggestion';
            $mail->Body = "Email: $email<br>Phone Number: $phone<br>Message: $message";

            // Send email
            $mail->send();
            echo "Your message has been sent successfully!";
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "The email you entered is not registered.";
    }

    // Close connection
    $stmt->close();
    $conn->close();
}
?>
