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
$query = "SELECT address , phonenumber FROM users WHERE name = '$username'";
$result = $conn->query($query);

// Initialize address variable
$userAddress = '';
$phoneNumber = '';

if ($result->num_rows > 0) {
    // Fetch address from the result
    $row = $result->fetch_assoc();
    $userAddress = $row['address'];
    $phoneNumber = $row['phonenumber'];
}
    ?>

 <link rel="stylesheet" href="CSS/cartContainer.css">

 <div class="modal-overlay"></div>
 <div class="modal">
     <input type="hidden" name="postId" id="postId">
     <input type="hidden" name="postKind" id="postKind">
     <input type="hidden" name="aboutProduct" id="aboutProduct">
     <input type="hidden" name="ownerUsername" id="ownerUsername">
     <div class="imageContainer">

     </div>
     <div> <label for="quantity">Product Quantity You like to order:</label>
         <input type="number" id="quantity" name="quantity" min="1">

         <label for="buyerAddress">Your Address:</label>
         <input type="text" id="buyerAddress" name="buyerAddress" required value="<?php echo htmlspecialchars($userAddress); ?> ">

         <label for="buyerPhone">Your Phone Number:</label>
         <input type="number" id="buyerPhone" name="buyerPhone" required value="<?php echo htmlspecialchars($phoneNumber); ?>">

         <label for="couponCode">Coupon Code:</label>
         <input type="text" id="couponCode" name="couponCode">
         <button id="validateCoupon">Validate Coupon</button>
         <p id="couponStatus"></p>
         <?php include 'payment.php' ?>
         <button id="confirmPurchase">Confirm Purchase</button>
         <button id="closeModal">Cancel</button>
     </div>

 </div>

 <div id="cartContainer">

 </div>



 <!-- JavaScript -->
 <script>
     document.addEventListener('DOMContentLoaded', function() {
         const cartContainer = document.getElementById('cartContainer');
         const modalOverlay = document.querySelector('.modal-overlay');
         const modal = document.querySelector('.modal');
         const closeModalButton = document.getElementById('closeModal');
         //         const addToCartBtn = document.getElementById('addToCart');

         const cartOpen = document.querySelector("#cart-open");
         if (cartOpen) {
             cartOpen.onclick = () => cartContainer.style.right = "0";
         }

         function closeCart() {
             cartContainer.style.right = '-100%';
         }
         // Close cart when clicking anywhere outside the cartContainer
         document.addEventListener('click', (event) => {
             const cartContainer = document.getElementById('cartContainer');
             if (!cartContainer.contains(event.target) && !document.querySelector("#cart-open").contains(event.target)) {
                 closeCart();
             }
         });
         // Prevent cart from closing when interacting with elements inside it 
         document.getElementById('cartContainer').addEventListener('click', (event) => {
             event.stopPropagation();
         });

         closeModalButton.addEventListener('click', function() {
             modalOverlay.style.display = 'none';
             modal.style.transform = 'scale(0)';

             document.body.style.overflow = 'auto';
         });

     });
 </script>