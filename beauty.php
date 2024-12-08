<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Posts</title>
    <link rel="stylesheet" href="CSS/allProduct.css">
</head>

<body>
<?php
include 'navi.php' ?>
    <?php
// Check if the session is already started
if (session_status() === PHP_SESSION_NONE) {
     session_start(); // Start the session only if it hasn't started yet
 }

    $servername = "localhost";
    $dbname = "zaycho";

    // Create a connection to the MySQL server
    $mysqli = new mysqli($servername, "root", "", $dbname);

    // Check for connection errors
    if ($mysqli->connect_errno) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    // Query to get only car posts
    $productKind = 'beauty_products';
    $query = "SELECT username, about_product, images, instock_amount, price, discount, coupon, phone_number, address 
              FROM `$productKind` 
              ORDER BY id DESC";
    $result = $mysqli->query($query);

    if ($result->num_rows > 0) {
        // Loop through each result and display the car posts
        while ($row = $result->fetch_assoc()) {
            // Split the images string into an array if multiple images were uploaded
            $images = explode(',', $row['images']);

            echo '<div class="post">';
            echo '<h2>Car Post by ' . htmlspecialchars($row['username'] ?? '') . '</h2>';
            echo '<p>' . htmlspecialchars($row['about_product'] ?? '') . '</p>';
            echo '<p>In stock: ' . htmlspecialchars($row['instock_amount'] ?? 0) . '</p>';
            echo '<p>Price: ' . htmlspecialchars($row['price'] ?? 0.0) . ' kyats</p>';
            if (!empty($row['discount'])) {
                echo '<p>Discount: ' . htmlspecialchars($row['discount']) . '%</p>';
            }
            if (!empty($row['coupon'])) {
                echo '<p>Coupon Code: ' . htmlspecialchars($row['coupon']) . '</p>';
            }
            echo '<p>Phone: ' . htmlspecialchars($row['phone_number'] ?? '') . '</p>';
            echo '<p>Address: ' . htmlspecialchars($row['address'] ?? '') . '</p>';

            // Display the images
            foreach ($images as $image) {
                if (!empty($image) && file_exists('uploads/' . $image)) {
                    echo '<img src="uploads/' . htmlspecialchars($image) . '" alt="Car Image" width="100">';
                } else {
                    echo '<p>Image not found.</p>'; // Display message if image doesn't exist
                }
            }

            echo '</div>';
        }
    } else {
        echo '<p>No beauty posts found.</p>';
    }

    // Close the database connection
    $mysqli->close();
    ?>
</body>

</html>

