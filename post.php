<?php
    // Check if the session is already started
    if (session_status() === PHP_SESSION_NONE) {
        session_start(); // Start the session only if it hasn't started yet
    }
// Database connection details
$host = "localhost";
$dbname = "zaycho";
$username = "root";
$password = "";

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user address based on some unique identifier (e.g., email or user ID)
    // Get the logged-in user's username
    $username = $_SESSION['username'] ?? 'guest';  // Assuming username is stored in session
$query = "SELECT address FROM users WHERE name = '$username'";
$result = $conn->query($query);

// Initialize address variable
$userAddress = '';

if ($result->num_rows > 0) {
    // Fetch address from the result
    $row = $result->fetch_assoc();
    $userAddress = $row['address'];
}
?>
<?php include 'postDatabase.php' ?>
<!DOCTYPE html>
<html>

<head>
    <title>Product Submission</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="CSS/post.css">
</head>

<body>
    <?php include 'navi.php' ?>
 
    <form id="productDetailsForm" action="postDatabase.php" method="post" enctype="multipart/form-data">
        <label for="productKind">Choose A Product kind:</label>
        <select id="productKind" name="productKind" required>
            <option value="">Select...</option>
            <option value="car">Car</option>
            <option value="electronics">Electronics</option>
            <option value="beauty_products">Beauty Products</option>
            <option value="mobile">Mobile</option>
            <option value="sports">Sports</option>
            <option value="furniture">Furniture</option>
            <option value="bicycle">Bicycle</option>
            <option value="computers">Computers</option>
            <option value="spare_parts">Spare Parts</option>
            <option value="fashion">Fashion</option>
            <option value="toys">Toys</option>
            <option value="books">Books</option>
            <option value="sneaker">Sneaker</option>
            <option value="thrift_fashion">Thrift Fashion</option>
        </select>
        <textarea id="aboutProduct" placeholder="Tell Them About Your Product" name="aboutProduct" maxlength="1000" required></textarea>

        <!-- <div>Images will appear here</div> -->
        <label for="images" class="custom-file-upload">Choose Product Images + </label>
        <input type="file" id="images" name="images[]" multiple required>

        <input type="number" id="amount" name="amount" placeholder="Instocks Amount" required>

        <input type="number" id="price" name="price" placeholder="Price" required>

        <input type="number" id="discount" name="discount" placeholder="Discount(Optional)">

        <input type="text" id="coupon" name="coupon" placeholder="Discount Coupon Number" maxlength="30">

        <input type="number" id="number" placeholder="Your PhoneNumber" name="number" required>

        <textarea id="address"  maxlength="1000" name="address" required>Address :<?php echo htmlspecialchars($userAddress); ?> </textarea>

        <button type="submit" id="formSubmit">Submit</button>


    </form>

    <!-- Include jQuery if not already included -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script>
        $(document).ready(function() {
            // Function to show the product details form with relevant fields
            function showProductDetailsForm() {
                var productKind = $('#productKind').val();
                if (productKind) {
                    // Clear the form before appending new fields
                    $('#productDetailsForm').empty();

                    $('#productDetailsForm').append('<input type="hidden" name="productKind" value="' + productKind + '">');
                } else {
                    alert("Please choose a product kind.");
                }
            }
        });
    </script>

</body>

</html>