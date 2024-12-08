<?php
session_start();

// Database connection
$servername = "localhost";
$dbname = "zaycho";
$mysqli = new mysqli($servername, "root", "", $dbname);

if ($mysqli->connect_errno) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Get owner (logged-in user) from session
$buyerUsername = $_SESSION['username'] ?? null;

// Retrieve form data sent via POST
$postId = $_POST['postId'] ?? null;
$postKind = $_POST['postKind'] ?? null;
$quantity = $_POST['quantity'] ?? null;
$buyerAddress = $_POST['buyerAddress'] ?? null;
$buyerPhone = $_POST['buyerPhone'] ?? null;
$ownerUsername = $_POST['ownerUsername'] ?? null;


//Check for missing required fields
if (!$buyerUsername || !$postId || !$postKind || !$quantity || !$buyerAddress || !$buyerPhone) {
    die("Error: Missing required data.");
}

// Query to retrieve `buyer_username` and `about_product` from the relevant post table
$buyerQuery = "SELECT username, about_product FROM `$postKind` WHERE id = ?";
$buyerStmt = $mysqli->prepare($buyerQuery);
$buyerStmt->bind_param('i', $postId);

if (!$buyerStmt->execute()) {
    die("Error executing buyer query: " . $buyerStmt->error);
}

$buyerStmt->bind_result($ownerUsername, $aboutProduct);
$buyerStmt->fetch();
$buyerStmt->close();

// Check if data was retrieved correctly
if (!$ownerUsername || !$aboutProduct) {
    die("Error: Buyer username or about_product not found.");
}

// Insert order confirmation notification data into `notification` table
$confirmOrderQuery = "INSERT INTO notification (owner_username, buyer_username, post_id, post_kind,quantity, about_product, buyerAddress, phone_number, notification_time) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
$confirmStmt = $mysqli->prepare($confirmOrderQuery);
$confirmStmt->bind_param('ssisssss', $ownerUsername, $buyerUsername, $postId, $postKind, $quantity, $aboutProduct, $buyerAddress, $buyerPhone);

if ($confirmStmt->execute()) {
    echo 'Order confirmation notification sent successfully!';
} else {
    echo 'Error inserting order confirmation: ' . $confirmStmt->error;
}

// Close the database connection
$mysqli->close();
?>
