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

$postId = $_POST['postId'] ?? null;
$postKind = $_POST['postKind'] ?? null;
$quantity = (int)$_POST['quantity'] ?? 0;

if (!$postId || !$postKind || $quantity <= 0) {
    die("Error: Missing required data or invalid quantity.");
}

// Update the instock_amount after confirming purchase
$updateStockQuery = "UPDATE `$postKind` SET instock_amount = instock_amount - ? WHERE id = ?";
$updateStockStmt = $mysqli->prepare($updateStockQuery);
$updateStockStmt->bind_param('ii', $quantity, $postId);

if ($updateStockStmt->execute()) {
    echo 'Purchase confirmed, stock updated successfully!';
} else {
    echo 'Error updating stock: ' . $updateStockStmt->error;
}

$updateStockStmt->close();
$mysqli->close();
?>
