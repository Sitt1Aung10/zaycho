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
         <input type="text" id="buyerPhone" name="buyerPhone" required>
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
 <!-- Payment box structure -->
 <!-- <div id="paymentBox" style="display: none;">
     <div class="paymentSuccess" style="color: green; display: none;"> Payment Success </div>
     <div id="paymentMethods" class="payment-methods">
         <div id="kpay" class="payment-method">
             <h4>KBZ PAY</h4>
             <p>Total Amount: <span id="totalPrice">0</span> kyats</p> <input type="text" id="kpayAmount" placeholder="Type The Amount"> <button class="payBtn">Pay</button>
         </div>
         <div id="ayapay" class="payment-method">
             <h4>AYA PAY</h4>
             <p>Total Amount: <span id="totalPrice">0</span> kyats</p> <input type="text" id="ayapayAmount" placeholder="Type The Amount"> <button class="payBtn">Pay</button>
         </div>
         <div id="wavepay" class="payment-method">
             <h4>WAVE PAY</h4>
             <p>Total Amount: <span id="totalPrice">0</span> kyats</p> <input type="text" id="wavepayAmount" placeholder="Type The Amount"> <button class="payBtn">Pay</button>
         </div>
         <div id="uabpay" class="payment-method">
             <h4>UAB PAY</h4>
             <p>Total Amount: <span id="totalPrice">0</span> kyats</p> <input type="text" id="uabpayAmount" placeholder="Type The Amount"> <button class="payBtn">Pay</button>
         </div>
         <div id="codpay" class="payment-method">
             <h4>COD PAY</h4>
             <p>Total Amount: <span id="totalPrice">0</span> kyats</p> <input type="text" id="codpayAmount" placeholder="Type The Amount"> <button class="payBtn">Pay</button>
         </div>
     </div> <button class="cancelBtn">Cancel</button>
 </div> -->


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


         //         function showNotification(message) {
         //             const notification = document.createElement('div');
         //             notification.id = 'notification';
         //             notification.style.position = 'fixed';
         //             notification.style.top = '20px';
         //             notification.style.right = '20px';
         //             notification.style.padding = '10px';
         //             notification.style.backgroundColor = '#FF3A38';
         //             notification.style.color = 'white';
         //             notification.style.borderRadius = '10px';
         //             notification.style.zIndex = '1000';
         //             document.body.appendChild(notification);
         //             notification.textContent = message;
         //             setTimeout(() => notification.remove(), 3000);
         //         }

         //         addToCartBtn.addEventListener('click', function() {
         //             const postId = document.getElementById('postId').value;
         //             const postKind = document.getElementById('postKind').value;
         //             addToCart();
         //         });

         //         function loadCart() {
         //             cartContainer.innerHTML = '';
         //             const cartItems = JSON.parse(localStorage.getItem('cart')) || [];
         //             cartItems.forEach((item, index) => {
         //                 const postElement = document.createElement('div');
         //                 postElement.classList.add('cart-item');
         //                 postElement.innerHTML = `
         //                     ${item.html}
         //                     <button class="removeBtn" data-index="${index}"><i class="fa-solid fa-xmark"></i></button>
         //                     <button class="viewBtn" data-id="${item.id}" data-kind="${item.kind}">View</button>
         //                 `;
         //                 cartContainer.appendChild(postElement);
         //             });
         //         }
         //         loadCart();

         //         function addToCart(postId, postKind) {
         //     // Attempt to find the product element by ID and Kind
         //     const postElement = document.querySelector(`[data-id="${postId}"][data-kind="${postKind}"]`);

         //     if (postElement) {
         //         const cartItems = JSON.parse(localStorage.getItem('cart')) || [];
         //         const postHTML = postElement.cloneNode(true).outerHTML;

         //         // Check if the item already exists in the cart
         //         const existingIndex = cartItems.findIndex(item => item.id === postId && item.kind === postKind);

         //         if (existingIndex === -1) {
         //             cartItems.push({
         //                 id: postId,
         //                 kind: postKind,
         //                 html: postHTML
         //             });
         //             localStorage.setItem('cart', JSON.stringify(cartItems));
         //             showNotification('Added to cart successfully');
         //         } else {
         //             showNotification('Item already in cart');
         //         }

         //         // Reload cart contents
         //         loadCart();
         //     } else {
         //         console.error(`Unable to find element with data-id="${postId}" and data-kind="${postKind}"`);
         //         showNotification('Error: Unable to add this item.');
         //     }
         // }

         //         function removeFromCart(index) {
         //             const cartItems = JSON.parse(localStorage.getItem('cart')) || [];
         //             cartItems.splice(index, 1);
         //             localStorage.setItem('cart', JSON.stringify(cartItems));
         //             loadCart();
         //             showNotification('Item removed from cart successfully');
         //         }

         closeModalButton.addEventListener('click', function() {
             modalOverlay.style.display = 'none';
             modal.style.transform = 'scale(0)';

             document.body.style.overflow = 'auto';
         });

         // document.addEventListener("mousedown", (event) => {
         //     if (!modal.contains(event.target) && !closeModalButton.contains(event.target)) {
         //         closeModalButton.click();
         //     }
         // });
     });
 </script>