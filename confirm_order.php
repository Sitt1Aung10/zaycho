<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "zaycho";

$mysqli = new mysqli($servername, $username, $password, $dbname);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$ownerUsername = $_SESSION['username'] ?? null;
$postId = $_POST['postId'] ?? null;
$postKind = $_POST['postKind'] ?? null;
$aboutProduct = $_POST['about_product'] ?? null;
$buyerUsername = $_POST['buyerUsername'] ?? null;
$quantity = (int) $_POST['quantity'] ?? 0;

if (!$ownerUsername || !$postId || !$postKind || !$aboutProduct || !$buyerUsername || $quantity <= 0) {
    die("Error: Missing required data or invalid quantity.");
}

$message = "Your $postKind order with $aboutProduct has been confirmed by $ownerUsername.";

// Insert the confirmation message into the orderConfirm table
$confirmOrderQuery = "INSERT INTO orderConfirm (post_id, post_kind, about_product, buyer_username, owner_username, confirmation_time, message) 
                      VALUES (?, ?, ?, ?, ?, NOW(), ?)";
$confirmStmt = $mysqli->prepare($confirmOrderQuery);
$confirmStmt->bind_param('isssss', $postId, $postKind, $aboutProduct, $buyerUsername, $ownerUsername, $message);

if ($confirmStmt->execute()) {
    echo 'Order confirmation notification sent successfully!<br>';
} else {
    echo 'Error inserting order confirmation: ' . $confirmStmt->error . '<br>';
}

$confirmStmt->close();
$mysqli->close();
?>
