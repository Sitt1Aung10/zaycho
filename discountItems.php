    <link rel="stylesheet" href="CSS/discountItems.css">
<?php
// if (session_status() === PHP_SESSION_NONE) {
//     session_start(); // Start the session only if it hasn't started yet
// }

$servername = "localhost";
$dbname = "zaycho";
$mysqli = new mysqli($servername, "root", "", $dbname);

if ($mysqli->connect_errno) {
    die("Connection failed: " . $mysqli->connect_error);
}

$productKinds = ['car', 'electronics', 'beauty_products', 'mobile', 'sports', 'furniture', 'bicycle', 'computers', 'spare_parts', 'fashion', 'toys', 'books', 'sneaker', 'thrift_fashion'];

foreach ($productKinds as $productKind) {
    // Query to grab posts with a discount greater than 0
    $query = "SELECT id, username, about_product, images, instock_amount, price, discount, coupon, phone_number, address 
              FROM `$productKind` 
              WHERE discount > 0 
              ORDER BY id DESC";
    $result = $mysqli->query($query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $images = explode(',', $row['images']);
            echo '<div class="post" data-id="' . htmlspecialchars($row['id']) . '" data-kind="' . htmlspecialchars($productKind) . '" data-instock="' . htmlspecialchars($row['instock_amount']) . '" data-coupon="' . htmlspecialchars($row['coupon']) . '">';
            echo '<h4>' . ucfirst($productKind) . ' Post by ' . htmlspecialchars($row['username'] ?? '') . '</h4>';
            echo '<p>' . htmlspecialchars($row['about_product'] ?? '') . '</p>';
            echo '<hr>';
            echo '<p>Price: ' . htmlspecialchars($row['price'] ?? 0.0) . ' kyats</p>';
            echo '<p>In stock: ' . htmlspecialchars($row['instock_amount'] ?? 0) . '</p>';
            

            // Only show discount if it is greater than 0
            echo '<p id="discountNoti">Discount: ' . htmlspecialchars($row['discount']) . '%</p>';

            if (!empty($row['coupon'])) {
                echo '<p class="hidden">Coupon: ' . htmlspecialchars($row['coupon']) . '</p>';
            } else {
                echo '<p>No coupon code exists.</p>';
            }

            echo '<p>Phone: ' . htmlspecialchars($row['phone_number'] ?? '') . '</p>';
            echo '<p>Address: ' . htmlspecialchars($row['address'] ?? '') . '</p>';

            foreach ($images as $image) {
                if (!empty($image) && file_exists('uploads/' . $image)) {
                    echo '<img src="uploads/' . htmlspecialchars($image) . '" alt="Product Image" width="100">';
                } else {
                    echo '<p>Image not found.</p>';
                }
            }
            echo '<button class="buyBtn">Buy Products</button>';
            echo '</div>';
        }
    } else {
        // If no results are found, you can show a message or leave it empty
        // echo '<p>No posts with discounts found for ' . ucfirst($productKind) . '.</p>';
    }
}


$mysqli->close();
?>
