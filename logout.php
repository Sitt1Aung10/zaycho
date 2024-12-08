<?php
// Start the session to access session variables
session_start();

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Delete the cookies by setting them to expire in the past
if (isset($_COOKIE['username'])) {
    setcookie('username', '', time() - 3600, '/'); // Setting cookie expiration in the past
}

if (isset($_COOKIE['email'])) {
    setcookie('email', '', time() - 3600, '/'); // Setting cookie expiration in the past
}

// Automatically refresh the page using JavaScript
echo "<script>
    alert('You have been logged out!');
    window.location.href = 'accountLogin.php'; // Redirect to accountLogin.php after logout
</script>";

exit();
?>
