<?php
// Check if the session is already started
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Start the session only if it hasn't started yet
}

// Database connection
$servername = "localhost";
$dbname = "zaycho";
$mysqli = new mysqli($servername, "root", "", $dbname);

// Check for connection errors
if ($mysqli->connect_errno) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Check if the form was submitted via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $productKind = $_POST['productKind'];
    $aboutProduct = $_POST['aboutProduct'];
    $amount = $_POST['amount'];
    $price = $_POST['price'];
    $discount = $_POST['discount'];
    $coupon = $_POST['coupon'];
    $phoneNumber = $_POST['number'];
    $address = $_POST['address'];

    // You might also retrieve the username from the session
    $username = $_SESSION['username'] ?? 'guest';  // Assuming you're storing username in session

    // Directory where images will be saved
    $targetDir = "uploads/";
    $imagePaths = []; // Array to store image paths

    // Loop through each uploaded file and save it
    foreach ($_FILES['images']['name'] as $key => $imageName) {
        $targetFilePath = $targetDir . basename($imageName);
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        // Allow only certain file formats
        $allowedTypes = array('jpg', 'jpeg', 'png', 'gif','avif','.webp');
        if (in_array(strtolower($fileType), $allowedTypes)) {
            // Move the uploaded file to the server directory
            if (move_uploaded_file($_FILES['images']['tmp_name'][$key], $targetFilePath)) {
                // Add the uploaded image path to the array
                $imagePaths[] = $imageName;  // Save only the image name in the database
            }
        }
    }

    // Convert the image paths array to a comma-separated string
    $imagesString = implode(',', $imagePaths);

    // Insert the data into the corresponding product kind table
    $query = "INSERT INTO `$productKind` (username, about_product, images, instock_amount, price, discount, coupon, phone_number, address)
              VALUES ('$username', '$aboutProduct', '$imagesString', '$amount', '$price', '$discount', '$coupon', '$phoneNumber', '$address')";
              
// Execute the query
if ($mysqli->query($query)) {
    header("Location: allProduct.php");
    exit(); // Ensures no further code is executed after the redirect
} else {
    echo "Error: " . $mysqli->error;
}
}


// Close the database connection
$mysqli->close();









