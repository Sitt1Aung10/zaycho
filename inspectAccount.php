<!DOCTYPE html>
<html lang="en">
<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Document</title>
     <style>
          /* Global Styles */
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
    color: #333;
}

h2, h3 {
    text-align: center;
    color: #333;
text-decoration: underline;
    margin-top: 100px;
    text-transform: uppercase;
}

h5 {
    color: #333;
    font-size: 18px;
    text-align: center;
    margin: 20px 0;
}

/* Table Styles */
.product-table {
    width: 90%;
    margin: 20px auto;
    border-collapse: collapse;
    background-color: white;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.product-table th, .product-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

.product-table th {
    background-color: #F4F4F4;
    color: #000;
    font-weight: bold;
}

.product-table td {
    background-color: #f9f9f9;
}

.product-table tr:nth-child(even) td {
    background-color: #f1f1f1;
}

.product-table tr:hover td {
    background-color: #e1e1e1;
}


/* Image Styles for Product */
.product-table img {
    width: 50px;
    height: auto;
    border-radius: 5px;
}

/* Post Details */
.notification {
    background-color: #fff;
    padding: 15px;
    margin: 20px 0;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.notification p {
    font-size: 16px;
    margin: 5px 0;
}

.notification h5 {
    margin-bottom: 15px;
    color: #4CAF50;
}

#status {
    font-weight: bold;
    color: #333;
}

/* Responsive Styling */
@media screen and (max-width: 768px) {
    .product-table {
        width: 100%;
        font-size: 14px;
    }

    .product-table th, .product-table td {
        padding: 10px;
    }
}

@media screen and (max-width: 480px) {
    body {
        font-size: 14px;
    }

    .product-table {
        font-size: 12px;
    }
}

     </style>
</head>
<body>
     <a href="admin_dashboard.php">Back</a>
<?php
// Fetch the name from the URL query parameter
$name = $_GET['name'] ?? null;
if (!$name) {
    die("Name is missing.");
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "zaycho";

$mysqli = new mysqli($servername, $username, $password, $dbname);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Define product categories
$productKinds = ['car', 'electronics', 'beauty_products', 'mobile', 'sports', 'furniture', 'bicycle', 'computers', 'spare_parts', 'fashion', 'toys', 'books', 'sneaker', 'thrift_fashion'];

echo "<h2>Inspecting Account: $name</h2>";

foreach ($productKinds as $productKind) {
    // Fetch posts from the respective product kind table where the owner_username matches the name
    $query = "SELECT id, username AS username, about_product, images, instock_amount, price, discount, phone_number, address FROM `$productKind` WHERE username = ? ORDER BY id DESC";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('s', $name); // Use the retrieved name here
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<h3>Posts in $productKind</h3>";
        echo "<table class='product-table'>
                <tr>
                    <th style='background-color:green;color:#fff;'>ID</th>
                    <th>About Product</th>
                    <th>Instock Amount</th>
                    <th>Price</th>
                    <th>Discount</th>
                    <th>Phone Number</th>
                    <th>Address</th>
                    <th>Images</th>
                </tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['about_product']}</td>
                    <td>{$row['instock_amount']}</td>
                    <td>{$row['price']}</td>
                    <td>{$row['discount']}</td>
                    <td>{$row['phone_number']}</td>
                    <td>{$row['address']}</td>
                    <td><img src='uploads/{$row['images']}' alt='Product Image' style='width:150px;'></td>
                </tr>";
        }
        echo "</table>";
    } else {
     //    echo "<p>No posts found in $productKind.</p>";
    }
}
?>
</body>
</html>

