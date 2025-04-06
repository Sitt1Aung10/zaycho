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

// Fetch necessary data from the session and POST request
$ownerUsername = $_SESSION['username'] ?? null;
$postId = $_POST['postId'] ?? null;
$postKind = $_POST['postKind'] ?? null;
$aboutProduct = $_POST['about_product'] ?? null;
$buyerUsername = $_POST['buyerUsername'] ?? null;
$quantity = (int) ($_POST['quantity'] ?? 0);
$action = $_POST['action'] ?? null; // "confirm" or "reject"

// Basic validation
if (!$ownerUsername || !$postId || !$postKind || !$aboutProduct || !$buyerUsername || $quantity <= 0) {
    die("Error: Missing required data or invalid quantity.");
}

// Check which action to perform: confirm or reject
if ($action === "confirm") {
    // Confirmation message
    $message = "Your $postKind order with $aboutProduct has been confirmed by $ownerUsername.";

    // Insert the confirmation into the orderConfirm table
    $confirmOrderQuery = "INSERT INTO orderConfirm (post_id, post_kind, about_product, buyer_username, owner_username, confirmation_time, message) 
                          VALUES (?, ?, ?, ?, ?, NOW(), ?)";
    $confirmStmt = $mysqli->prepare($confirmOrderQuery);
    $confirmStmt->bind_param('isssss', $postId, $postKind, $aboutProduct, $buyerUsername, $ownerUsername, $message);

    if ($confirmStmt->execute()) {
        // echo 'Order confirmation notification sent successfully!<br>';
        echo  "<script>alert ('Order Has Been Confirmed')</script>";
    } else {
        echo 'Error inserting order confirmation: ' . $confirmStmt->error . '<br>';
    }
    $confirmStmt->close();
} elseif ($action === "reject") {
    // Rejection message
    $rejectMessage = "Your Order is Being Rejected";

   // Insert the rejection into the reject_orders table
   $rejectOrderQuery = "INSERT INTO reject_orders (post_id, post_kind, about_product, buyer_username, owner_username, rejection_reason, rejection_time) 
   VALUES (?, ?, ?, ?, ?, ?, NOW())"; // Corrected placement of NOW() for rejection_time
$rejectStmt = $mysqli->prepare($rejectOrderQuery);
$rejectStmt->bind_param('isssss', $postId, $postKind, $aboutProduct, $buyerUsername, $ownerUsername, $rejectMessage);

    if ($rejectStmt->execute()) {
        echo '<script> alert("Order rejected succeed")</script>';
    } else {
        echo 'Error inserting order rejection: ' . $rejectStmt->error . '<br>';
    }
    $rejectStmt->close();
} else {
    echo "Error: Invalid action.";
}

$mysqli->close();
?>
