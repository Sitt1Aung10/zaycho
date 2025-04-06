<?php
// Database connection
$servername = "localhost";
$dbname = "zaycho";
$mysqli = new mysqli($servername, "root", "", $dbname);

if ($mysqli->connect_errno) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Get product ID and productKind from URL
$productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$productKind = isset($_GET['table']) ? $_GET['table'] : '';

// Validate the productKind to ensure it's one of the valid categories
$productKinds = ['car', 'electronics', 'beauty_products', 'mobile', 'sports', 'furniture', 'bicycle', 'computers', 'spare_parts', 'fashion', 'toys', 'books', 'sneaker', 'thrift_fashion'];

if (!in_array($productKind, $productKinds)) {
    die("Invalid product category.");
}

// Check if product ID is valid
if ($productId > 0) {
    // Prepare the delete query for the selected productKind table
    $query = "DELETE FROM `$productKind` WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $productId);

    if ($stmt->execute()) {
        echo "<script>alert('Product deleted successfully!'); window.location.href = 'admin_dashboard.php';</script>";
    } else {
        echo "<script>alert('Error deleting product. Please try again.'); window.location.href = 'admin_dashboard.php';</script>";
    }

    $stmt->close();
} else {
    echo "<script>alert('Invalid product ID.'); window.location.href = 'admin_dashboard.php';</script>";
}

$mysqli->close();
?>
