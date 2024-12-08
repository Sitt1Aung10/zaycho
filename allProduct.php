<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="CSS/allProduct.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/8cbc109b9d.js" crossorigin="anonymous"></script>
</head>

<body style="background-image: url(img/testHomeImg.webp);">
    <?php
    // Check if the session is already started
    if (session_status() === PHP_SESSION_NONE) {
        session_start(); // Start the session only if it hasn't started yet
    }
    ?>

    <script>
        $(document).ready(function() {
            $('body').css('opacity', 0).animate({
                opacity: 1
            }, 1000); // Adjust the duration as needed
        });
    </script>
    <?php include "navi.php" ?>
    <?php include "cartContainer.php" ?>
    <?php include 'wishList.php' ?>

    <div class="searchBar">
        <input type="search" id="searchInput" placeholder="Search Your Favourite Products!">
        <!-- <button id="searchBtn">Search</button> -->
    </div>

    <div class="category-buttons">
        <h4>Category Filter</h4>
        <button class="category-button" onclick="filterByCategory('all')">All</button>
        <button class="category-button" onclick="filterByCategory('car')">Car</button>
        <button class="category-button" onclick="filterByCategory('electronics')">Electronics</button>
        <button class="category-button" onclick="filterByCategory('beauty_products')">Beauty Products</button>
        <button class="category-button" onclick="filterByCategory('furniture')">Furniture</button>
        <button class="category-button" onclick="filterByCategory('bicycle')">Bicycle</button>
        <button class="category-button" onclick="filterByCategory('computers')">Computers</button>
        <button class="category-button" onclick="filterByCategory('spare_parts')">Spare Parts</button>
        <button class="category-button" onclick="filterByCategory('fashion')">Fashion</button>
        <button class="category-button" onclick="filterByCategory('toys')">Toys</button>
        <button class="category-button" onclick="filterByCategory('books')">Books</button>
        <button class="category-button" onclick="filterByCategory('sneaker')">Sneakers</button>
        <button class="category-button" onclick="filterByCategory('thrift_fashion')">Thrift Fashion</button>
        <!-- Add buttons for other product kinds -->
    </div>

    <script>
        function filterByCategory(category) {
            // Reload the page with the selected category as a query parameter
            const url = new URL(window.location.href);
            url.searchParams.set('productKind', category); // Set the productKind parameter
            window.location.href = url.toString();
        }
    </script>

    <?php
    $servername = "localhost";
    $dbname = "zaycho";
    $mysqli = new mysqli($servername, "root", "", $dbname);

    if ($mysqli->connect_errno) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    $productKinds = ['car', 'electronics', 'beauty_products', 'mobile', 'sports', 'furniture', 'bicycle', 'computers', 'spare_parts', 'fashion', 'toys', 'books', 'sneaker', 'thrift_fashion'];

    // Add buttons for filter methods
    echo '<button id="filterMethodBtn">Filter Methods</button>';
    echo '<div class="filter-buttons">';
    echo '<button class="filter-button" onclick="filterPosts(\'lowToHighPrice\')">Price: Low to High</button>';
    echo '<button  class="filter-button" onclick="filterPosts(\'highToLowPrice\')">Price: High to Low</button>';
    echo '<button class="filter-button" onclick="filterPosts(\'highToLowDiscount\')">Discount: High to Low</button>';
    echo '<button class="filter-button" onclick="filterPosts(\'discountProductOnly\')">Discount Items</button>';
    echo '<button class="filter-button" onclick="filterPosts(\'newestToOldest\')">Newest to Oldest</button>';
    echo '</div>';
    // Get the selected product kind from the query parameter (default: 'all')
    $selectedKind = isset($_GET['productKind']) ? htmlspecialchars($_GET['productKind']) : 'all';

    // Handle filter type in PHP based on a query parameter
    $filterType = isset($_GET['filter']) ? $_GET['filter'] : 'default';
    echo '<div id="productContainer">';
    foreach ($productKinds as $productKind) {
        // Skip unrelated kinds if a specific kind is selected
        if ($selectedKind !== 'all' && $selectedKind !== $productKind) {
            continue;
        }
        $query = "SELECT p.id, p.username AS owner_username, p.about_product, p.images, p.instock_amount, 
        p.price, p.discount, p.coupon, p.phone_number, p.address 
 FROM $productKind p
 JOIN users u ON p.username = u.name
 WHERE u.can_login = 1"; // Ensures only posts from active users are shown

        // Adjust ORDER BY clause based on filter type
        switch ($filterType) {
            case 'lowToHighPrice':
                $query .= " ORDER BY price ASC";
                break;
            case 'highToLowPrice':
                $query .= " ORDER BY price DESC";
                break;
            case 'highToLowDiscount':
                $query .= " ORDER BY discount DESC";
                break;
            case 'newestToOldest':
                $query .= " ORDER BY id DESC";
                break;
            case 'discountProductOnly': // Add this case for discounted products only
                $query .= " WHERE discount > 0 ORDER BY discount DESC"; // Filters products with a discount
                break;
            default:
                $query .= " ORDER BY id DESC"; // Default sorting (newest to oldest)
                break;
        }

        $result = $mysqli->query($query);

        if ($result->num_rows > 0) {

            while ($row = $result->fetch_assoc()) {
                $images = explode(',', $row['images']);
                $instockAmount = $row['instock_amount'] ?? 0;

                // Determine if the Buy button should be disabled
                $isOutOfStock = $instockAmount <= 0;
                $buttonClass = $isOutOfStock ? 'buyBtn disabled' : 'buyBtn';
                $buttonText = $isOutOfStock ? 'Out of Stock' : 'See More';

                echo '<div  class="post"
                 data-id="' . htmlspecialchars($row['id']) . '" data-kind="' . htmlspecialchars($productKind) . '" data-instock="' . htmlspecialchars($instockAmount) . '" 
                 data-about-product="' . htmlspecialchars($row['about_product']) . '"
                 data-coupon="' . htmlspecialchars($row['coupon']) . '" data-owner-username="' . htmlspecialchars($row['owner_username']) . '" 
                 data-images="' . htmlspecialchars($row['images']) . '" data-phone-number="' . htmlspecialchars($row['phone_number']) . '" 
                 data-address="' . htmlspecialchars($row['address']) . '" data-price="' . htmlspecialchars($row['price']) . '")>';
                echo '<div class="imgContainer">';
                if (!empty($images[0])) { // Only show the first image
                    $firstImage = $images[0];
                    if (file_exists('uploads/' . $firstImage)) {
                        echo '<img class="postImg" src="uploads/' . htmlspecialchars($firstImage) . '" alt="Product Image">';
                    } else {
                        echo '<p>Image not found.</p>';
                    }
                }
                echo '</div>';
                echo '<h4>' . ucfirst($productKind) . ' Post by ' . htmlspecialchars($row['owner_username']) . '</h4>';
                echo '<p class="about-product-text">' . htmlspecialchars($row['about_product'] ?? '') . '</p>';
                echo '<hr>';
                echo '<p>' . htmlspecialchars($row['price'] ?? 0.0) . ' kyats</p>';
                echo '<p>' . 'Have A Question About The Product?' . '<br>' . htmlspecialchars($row['phone_number'] ?? '') . '</p>';
                echo '<p>' . 'Address : ' . htmlspecialchars($row['address'] ?? '') . '</p>';
                if (isset($row['discount']) && $row['discount'] > 0) {
                    echo '<p id="discountNoti">Discount: ' . htmlspecialchars($row['discount']) . '%</p>';
                    echo '<input type="hidden" id="discountValue" value="' . htmlspecialchars($row['discount']) . '">';
                }
                echo '<button class="addToCartBtn"><i class="fa-solid fa-cart-shopping"></i></button>';
                echo '<button id="wishlistBtn"><i class="fa-solid fa-heart"></i></button>';
                echo '<button class="' . $buttonClass . '" ' . ($isOutOfStock ? 'disabled' : '') . '>' . $buttonText . '</button>';
                echo '</div>';
            }
        } else {
            // echo '<p style="color: #fff;">No posts found for ' . ucfirst($productKind) . '.</p>';
        }
    }
    echo '</div>';
    $mysqli->close();

    ?>
    <h6 style="width:100%;position: absolute;text-align:center;border-bottom:1px solid #fff;bottom:100px;color:#fff;">You Are All Caught Up For Now</h6>


    <!-- ======================= add to wish list funtion ======================-->
    <script>
        const currentUsername = "<?php echo $_SESSION['username']; ?>"; // PHP session for the username
        const wishlistKey = `wishlist_${currentUsername}`;
        const wishListButton = document.querySelector("#wishListButton");
        const wishListContainer = document.querySelector("#wishListContainer");
        wishListButton.addEventListener('click', () => {
            wishListContainer.classList.toggle("activeWishListContainer")
        });

        // Function to add a product to the wishlist
        function addToWishlist(product) {
            // Retrieve the existing wishlist from localStorage, or create a new array if none exists
            let wishlist = JSON.parse(localStorage.getItem('wishlist')) || [];

            // Check if the product already exists in the wishlist
            const productExists = wishlist.some(item => item.id === product.id);
            if (!productExists) {
                wishlist.push(product);
                localStorage.setItem('wishlistKey', JSON.stringify(wishlist));
                alert('Product added to wishlist successfully');
            } else {
                alert('Product is already in the wishlist');
            }

            renderWishlist();
        }

        // Function to render the wishlist in the wishListContainer
        function renderWishlist() {
            const wishListContainer = document.getElementById('wishListContainer');
            wishListContainer.innerHTML = ''; // Clear the container before rendering

            let wishlist = JSON.parse(localStorage.getItem('wishlistKey')) || [];

            wishlist.forEach(product => {
                const productElement = document.createElement('div');
                productElement.classList.add('wishlist-item');
                productElement.innerHTML = `
            <div class="imgContainer">
                <img src="uploads/${product.image}" alt="Product Image">
            </div>
            <h4>${product.kind} Post by ${product.owner}</h4>
            <p class="about-product-text">${product.about}</p>
            <p>${product.price} kyats</p>
            ${product.discount > 0 ? `<p id="discountNoti">Discount: ${product.discount}%</p>` : ''}
            <p>Phone number: ${product.phoneNumber}</p> 
            <p>Address: ${product.address}</p>
            <button class="buyFromWishList" data-id="${product.id}" data-kind="${product.kind}" data-about-product="${product.about}">Buy</button>
            <button class="removeFromWishlistBtn" data-id="${product.id}">Remove from Wishlist</button>
        `;
                // Append the product to the container
                wishListContainer.appendChild(productElement);
            });

            // Add event listeners for "Buy" buttons 
            document.querySelectorAll('.buyFromWishList').forEach(button => {
                button.addEventListener('click', function() {
                    const productId = this.getAttribute('data-id');
                    const productKind = this.getAttribute('data-kind');
                    const aboutProduct = this.getAttribute('data-about-product');

                    console.log(productId);
                    console.log(productKind)

                    // // Navigate to the product page
                    window.location.href = `allProduct.php?productId=${productId}&productKind=${productKind}&aboutProduct=${aboutProduct}`;

                    // Highlight the related post
                    const postElement = document.querySelector(
                        `.post[data-id="${productId}"][data-kind="${productKind}"][data-about-product="${aboutProduct}"]`
                    );
                });
            });

            // Add event listeners to remove buttons
            document.querySelectorAll('.removeFromWishlistBtn').forEach(button => {
                button.addEventListener('click', function() {
                    removeFromWishlist(this.getAttribute('data-id'));
                });
            });
        }

        // Function to remove a product from the wishlist
        function removeFromWishlist(productId) {
            let wishlist = JSON.parse(localStorage.getItem('wishlistKey')) || [];
            wishlist = wishlist.filter(product => product.id !== productId);
            localStorage.setItem('wishlistKey', JSON.stringify(wishlist));

            alert('Product removed from wishlist');
            renderWishlist();
        }

        // Add event listener to the "Add To Wish List" buttons
        document.querySelectorAll('#wishlistBtn').forEach(button => {
            button.addEventListener('click', function() {
                const post = this.closest('.post');
                const product = {
                    id: post.getAttribute('data-id'),
                    kind: post.getAttribute('data-kind'),
                    instock: post.getAttribute('data-instock'),
                    coupon: post.getAttribute('data-coupon'),
                    owner: post.getAttribute('data-owner-username'),
                    image: post.getAttribute('data-images').split(',')[0], // First image
                    about: post.querySelector('.about-product-text').innerText,
                    price: post.querySelector('p').innerText.replace(' kyats', ''), // Extract price
                    phoneNumber: post.getAttribute('data-phone-number'),
                    // Corrected data attribute name 
                    address: post.getAttribute('data-address'),
                    discount: post.querySelector('#discountNoti') ? post.querySelector('#discountNoti').innerText.replace('Discount: ', '').replace('%', '') : 0
                };

                addToWishlist(product);
            });
        });

        // Initial rendering of the wishlist when the page loads
        document.addEventListener('DOMContentLoaded', renderWishlist);
    </script>

    <!--==================add to cart function start ==============-->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const currentUsername = "<?php echo $_SESSION['username']; ?>"; // Assuming session contains the username

            // Unique localStorage key for this user
            const cartKey = `cart_${currentUsername}`;
            // Event delegation for add to cart buttons
            document.querySelectorAll('.addToCartBtn').forEach(button => {
                button.addEventListener('click', (e) => {
                    const postElement = e.target.closest('.post');
                    const productId = postElement.getAttribute('data-id');
                    const productKind = postElement.getAttribute('data-kind');
                    const ownerUsername = postElement.getAttribute('data-owner-username');
                    const price = postElement.querySelector('p:nth-child(5)').textContent.replace(' kyats', '');
                    const coupon = postElement.getAttribute('data-coupon');
                    const aboutProduct = postElement.querySelector('.about-product-text').textContent;
                    const images = postElement.getAttribute('data-images').split(',');
                    const phoneNumber = postElement.getAttribute('data-phone-number');
                    const address = postElement.getAttribute('data-address');

                    // Create a product object
                    const product = {
                        id: productId,
                        kind: productKind,
                        ownerUsername: ownerUsername,
                        price: parseFloat(price),
                        coupon: coupon,
                        about: aboutProduct,
                        images: images,
                        phoneNumber: phoneNumber,
                        address: address
                    };

                    // Get cart from localStorage or initialize if empty
                    let cart = JSON.parse(localStorage.getItem('cartKey')) || [];

                    // Check if the product is already in the cart
                    const productExists = cart.some(item => item.id === productId && item.kind === productKind);

                    if (!productExists) {
                        // Add product to cart
                        cart.push(product);
                        localStorage.setItem('cartKey', JSON.stringify(cart));

                        // Notify the user
                        alert('Added to cart successfully');
                        // Refresh the page
                        window.location.reload();
                    } else {
                        alert('Product is already in the cart');
                    }
                });
            });
        });

        document.addEventListener('DOMContentLoaded', () => {
            // Function to display the cart items in the cartContainer
            function displayCart() {
                const cart = JSON.parse(localStorage.getItem('cartKey')) || [];
                const cartContainer = document.getElementById('cartContainer');
                cartContainer.innerHTML = ''; // Clear any existing content

                if (cart.length === 0) {
                    cartContainer.innerHTML = '<p>Your cart is empty.</p>';
                    return;
                }

                cart.forEach(product => {
                    // Create a container for each product
                    const productDiv = document.createElement('div');
                    productDiv.classList.add('cart-item');

                    // Use the first image in the array or a placeholder if no image is available
                    const imageUrl = product.images && product.images.length > 0 ? product.images[0] : 'placeholder.png';

                    productDiv.innerHTML = `
                <div class="cart-item-image">
                    <img src="uploads/${imageUrl}" alt="Product Image" class="cart-image">
                </div>
                <div>
                <input type="checkbox" class="cart-checkbox">
                <h4>${product.kind} Post by ${product.ownerUsername}</h4>
                <p>About: ${product.about}</p>
                <p>Price: ${product.price} kyats</p>
                ${product.coupon ? `<p>Coupon: ${product.coupon}</p>` : ''}
                <p>Phone number: ${product.phoneNumber}</p>
                <p>Address: ${product.address}</p>
                                <button class="removeFromCartBtn" data-id="${product.id}" data-kind="${product.kind}">Remove</button>
                </div>
                <input type="hidden" id="userAddress" value="<?php echo htmlspecialchars($userAddress); ?>">

            `;

                    cartContainer.appendChild(productDiv);
                });

                // Add event listeners to the remove buttons
                document.querySelectorAll('.removeFromCartBtn').forEach(button => {
                    button.addEventListener('click', (e) => {
                        const productId = e.target.getAttribute('data-id');
                        const productKind = e.target.getAttribute('data-kind');

                        // Filter out the product to be removed
                        const updatedCart = cart.filter(item => !(item.id === productId && item.kind === productKind));

                        // Update localStorage and re-display the cart
                        localStorage.setItem('cartKey', JSON.stringify(updatedCart));
                        displayCart();
                    });
                });

                if (cart.length > 0) {
                    const purchaseAllBtn = document.createElement('button');
                    purchaseAllBtn.id = 'purchaseAllBtn';
                    purchaseAllBtn.textContent = 'Purchase Selected Items';
                    cartContainer.appendChild(purchaseAllBtn);

                    const paymentBox = `
        <div id="paymentBox">
            <div class="paymentSuccess" style="color: green; display: none;">Payment Success</div>
            <div id="paymentMethods" class="payment-methods">
                <div class="kpay" class="payment-method">
                    <h4>KBZ PAY</h4>
                    <p>Total Amount: <span class="totalPrice">0</span>
                 kyats</p>
                 <img src="img/kpayQr.jpg">
                 <br>
                    <input type="file" name="kpayQr"  id="kpayAmount" style="display:none;">
                                       <label for="kpayAmount">Upload Voucher</label>
                    <br>
                    <button class="payBtn"><i class="fa-solid fa-arrow-up-from-bracket"></i></button>
                </div>
                <div class="ayapay" class="payment-method">
                    <h4>AYA PAY</h4>
                    <p>Total Amount: <span class="totalPrice">0</span> kyats</p>
                    <img src="img/kpayQr.jpg">
                    <br>
                    <input type="file" id="ayapayAmount" style="display:none;">
                    <label for="ayapayAmount">Upload Voucher</label>
                    <br>
                    <button class="payBtn"><i class="fa-solid fa-arrow-up-from-bracket"></i></button>
                </div>
                <div class="wavepay" class="payment-method">
                    <h4>WAVE PAY</h4>
                    <p>Total Amount: <span class="totalPrice">0</span> kyats</p>
                       <img src="img/kpayQr.jpg">
                 <br>
                    <input type="file" id="wavepayAmount" style="display:none;">
                         <label for="wavepayAmount">Upload Voucher</label>
                    <br>
                    <button class="payBtn"><i class="fa-solid fa-arrow-up-from-bracket"></i></button>
                </div>
                <div class="uabpay" class="payment-method">
                    <h4>UAB PAY</h4>
                    <p>Total Amount: <span class="totalPrice">0</span> kyats</p>
                     <img src="img/kpayQr.jpg">
                 <br>
                 <label for="uabpayAmount">Upload Voucher</label>
                 <br>
                    <input type="file" id="uabpayAmount" style="display:none;">

                    <button class="payBtn"><i class="fa-solid fa-arrow-up-from-bracket"></i></button>
                </div>
                <div class="codpay" class="payment-method">
                    <button class="codpayBtn">Cash On Delivery</button>
                </div>
            </div>
            <button class="cancelBtn">Cancel</button>
        </div>
    `;
                    cartContainer.insertAdjacentHTML('beforeend', paymentBox);

                    const paymentBoxElement = document.getElementById('paymentBox');
                    const totalPriceElement = paymentBoxElement.querySelectorAll('.totalPrice');

                    purchaseAllBtn.addEventListener('click', () => {
                        const totalPrice = calculateTotalPrice();
                        if (isAnyCheckboxChecked()) {
                            totalPriceElement.forEach(el => el.textContent = totalPrice); // Update all total price fields
                            paymentBoxElement.style.right = '0'; // Show the payment box
                        } else {
                            alert('Please select at least one item to purchase.');
                        }
                    });

                    // Event listener for cancel button
                    paymentBoxElement.querySelector('.cancelBtn').addEventListener('click', () => {
                        paymentBoxElement.style.display = 'none'; // Hide the payment box
                    });

                    const codpayBtn = document.querySelector(".codpayBtn");
                    codpayBtn.addEventListener('click', () => {
                        alert('Payment Successful!');
                        purchaseAllItems(); // Proceed to purchaseAll()
                    })

                    // Add event listener for all pay buttons
                    paymentBoxElement.querySelectorAll('.payBtn').forEach(button => {
                        button.addEventListener('click', event => {
                            const inputField = event.target.previousElementSibling; // Get the corresponding input field
                            const file = inputField.files[0]; // Get the uploaded file
                            if (!file) { alert('Please upload the voucher.'); return; }
                                alert('Payment Successful!');
                                paymentBoxElement.querySelector('.paymentSuccess').style.display = 'block';
                                paymentBoxElement.style.display = 'none';
                                purchaseAllItems(); // Proceed to purchaseAll()
                        });
                    });

                    // Function to calculate total price of selected items
                    function calculateTotalPrice() {
                        let totalPrice = 0;
                        document.querySelectorAll('.cart-item').forEach(item => {
                            const checkbox = item.querySelector('.cart-checkbox');
                            if (checkbox.checked) {
                                const priceText = Array.from(item.querySelectorAll('p')).find(p => p.textContent.includes('Price:')).textContent;
                                const priceMatch = priceText.match(/Price:\s*(\d+(\.\d+)?)\s*kyats/);
                                const price = priceMatch ? parseFloat(priceMatch[1]) : 0;
                                totalPrice += price * 1.05;
                            }
                        });
                        return totalPrice;
                    }

                    // Function to check if any checkbox is checked
                    function isAnyCheckboxChecked() {
                        return Array.from(document.querySelectorAll('.cart-checkbox')).some(checkbox => checkbox.checked);
                    }
                }
            }


            // Function to purchase all selected items in the cart
            function purchaseAllItems() {

                const userAddress = document.getElementById('userAddress')?.value.trim();
                const selectedProducts = [];
                document.querySelectorAll('.cart-item').forEach(item => {
                    const checkbox = item.querySelector('.cart-checkbox');
                    if (checkbox.checked) {
                        const productId = item.querySelector('button').getAttribute('data-id');
                        const product = JSON.parse(localStorage.getItem('cartKey')).find(p => p.id === productId);
                        // Use userAddress if available, otherwise fallback to product.address
                        product.address = userAddress || product.address;

                        selectedProducts.push(product);
                    }
                });
                // const userAddress = document.getElementById('userAddress').value;
                // console.log(userAddress)
                selectedProducts.forEach(product => {
                    const {
                        id,
                        kind,
                        about,
                        ownerUsername,
                        phoneNumber,
                        address
                    } = product;
                    // const address = product.address || userAddress;
                    const quantity = 1; // Assuming quantity is 1 for each item in this example

                      // Here you would send the purchase request to the server
        console.log(`Purchasing product: ${id}, Address: ${address}`);

                    // AJAX request for fetchingNoti
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', 'fetchingNoti.php', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                            console.log('Response:', xhr.responseText);
                        }
                    };
                    xhr.send(`postId=${encodeURIComponent(id)}&postKind=${encodeURIComponent(kind)}&quantity=${encodeURIComponent(quantity)}&aboutProduct=${encodeURIComponent(about)}&buyerAddress=${encodeURIComponent(address)}&buyerPhone=${encodeURIComponent(phoneNumber)}&ownerUsername=${encodeURIComponent(ownerUsername)}`);

                    // AJAX request for stock_update
                    const xhr2 = new XMLHttpRequest();
                    xhr2.open('POST', 'stock_update.php', true);
                    xhr2.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr2.onreadystatechange = function() {
                        if (xhr2.readyState === XMLHttpRequest.DONE && xhr2.status === 200) {
                            console.log('Response from stock_update:', xhr2.responseText);
                        }
                    };
                    xhr2.send(`postId=${encodeURIComponent(id)}&postKind=${encodeURIComponent(kind)}&quantity=${encodeURIComponent(quantity)}`);
                });

                alert('Selected items purchased!');
                // Clear the selected items from the cart after purchase
                const newCart = JSON.parse(localStorage.getItem('cart')).filter(product => !selectedProducts.includes(product));
                localStorage.setItem('cart', JSON.stringify(newCart));
                // Reload the page to update the cart display
                window.location.reload();
            }

            // Display the cart on page load
            displayCart();
        });


        // Select the search input field and add an event listener
        document.querySelector('.searchBar input[type="search"]').addEventListener('input', function() {
            const searchTerm = this.value.trim().toLowerCase();
            const posts = document.querySelectorAll('.post');

            // If searchTerm is empty, show all posts
            if (!searchTerm) {
                posts.forEach(post => post.style.display = 'block');
                return;
            }

            // Otherwise, filter based on search term
            posts.forEach(post => {
                const aboutProduct = post.querySelector('p').textContent.toLowerCase();
                if (aboutProduct.includes(searchTerm)) {
                    post.style.display = 'block';
                } else {
                    post.style.display = 'none';
                }
            });
        });

        //================== Modal open from post ===================
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.buyBtn').forEach(function(button) {
                button.addEventListener('click', function() {
                    const post = button.closest('.post');
                    const instock = post.dataset.instock;
                    const postId = post.dataset.id;
                    const postKind = post.dataset.kind;
                    const coupon = post.dataset.coupon;
                    const price = post.dataset.price;
                    const aboutProduct = post.dataset.about_product; // Capture about_product
                    const ownerUsername = post.querySelector('h4').textContent.split(' by ')[1]; // Assuming owner username is in the post header


                    // Calculate total price including a 5% fee
                    const totalPrice = price * 1.05;
                    // Retrieve the paid amount (this example assumes you store the paid amount in a data attribute)
                    // Update the placeholders with the total price 
                    console.log(totalPrice)


                    const priceHolder = document.querySelectorAll('.priceHolder');
                    priceHolder.forEach(holder => {
                        holder.innerHTML = `Total Price: ${totalPrice} kyats`;
                    });

                    const images = post.dataset.images.split(','); // Split the images string into an array
                    const imageContainer = document.querySelector('.modal .imageContainer');

                    // Variables to track the current image index
                    let currentIndex = 0;

                    // Clear any existing content in the image container
                    imageContainer.innerHTML = '';

                    // Create and display the first image
                    const imgElement = document.createElement('img');
                    imgElement.src = 'uploads/' + images[currentIndex].trim();
                    imgElement.alt = 'Product Image';
                    imgElement.classList.add('modalImage'); // Optional: add a class for styling
                    imageContainer.appendChild(imgElement);

                    // Check if navigation buttons already exist, if not, create them
                    let prevButton = document.querySelector('.modal .prevBtn');
                    let nextButton = document.querySelector('.modal .nextBtn');

                    if (!prevButton) {
                        prevButton = document.createElement('button');
                        prevButton.textContent = 'Previous';
                        prevButton.classList.add('prevBtn');
                        imageContainer.appendChild(prevButton);
                    }

                    if (!nextButton) {
                        nextButton = document.createElement('button');
                        nextButton.textContent = 'Next';
                        nextButton.classList.add('nextBtn');
                        imageContainer.appendChild(nextButton);
                    }

                    // Event listeners for buttons
                    prevButton.addEventListener('click', function() {
                        if (currentIndex > 0) {
                            currentIndex--;
                            imgElement.src = 'uploads/' + images[currentIndex].trim();
                        }
                    });

                    nextButton.addEventListener('click', function() {
                        if (currentIndex < images.length - 1) {
                            currentIndex++;
                            imgElement.src = 'uploads/' + images[currentIndex].trim();
                        }
                    });

                    // console.log(aboutProduct)
                    document.getElementById('quantity').max = instock;
                    document.getElementById('quantity').value = 1;
                    document.querySelector('.modal-overlay').style.display = 'block';
                    document.querySelector('.modal').style.transform = 'scale(1)';
                    document.body.style.overflow = 'hidden';
                    // document.querySelectorAll('.post').forEach(p => p.style.opacity = '0.5');
                    // post.style.opacity = '1';
                    const confirmButton = document.getElementById('confirmPurchase');
                    confirmButton.dataset.postId = postId;
                    confirmButton.dataset.postKind = postKind;
                    confirmButton.dataset.coupon = coupon;
                    confirmButton.dataset.aboutProduct = aboutProduct;
                    confirmButton.dataset.price = price;
                    confirmButton.dataset.ownerUsername = ownerUsername;
                });
            });

            document.getElementById('validateCoupon').addEventListener('click', function() {
                const couponCode = document.getElementById('couponCode').value.trim();
                const expectedCoupon = document.getElementById('confirmPurchase').dataset.coupon; // Grab coupon from data attributes**
                const couponStatus = document.getElementById('couponStatus');
                let basePrice = parseFloat(document.getElementById('confirmPurchase').dataset.price);
                let totalPrice = basePrice * 1.05; // Calculate with the fee first
                // Calculate with the fee first
                let discount = 0; // Initialize discount to 0 // Retrieve the discount value from the hidden field 
                const discountValue = parseFloat(document.getElementById('discountValue').value);
                // Check if expectedCoupon is empty
                if (!expectedCoupon) {
                    couponStatus.textContent = 'No coupon code exists.';
                    couponStatus.style.color = 'red';
                } else {
                    // Validate the coupon code if it exists
                    if (couponCode === expectedCoupon) {
                        couponStatus.textContent = 'Coupon code is valid!';
                        couponStatus.style.color = 'green';
                        discount = discountValue / 100; // Convert percentage to decimal 
                        totalPrice = totalPrice - (totalPrice * discount);
                    } else {
                        couponStatus.textContent = 'Invalid coupon code.';
                        couponStatus.style.color = 'red';
                    }
                } // Update the placeholders with the total price
                // Update the placeholders with the total price 
                const priceHolder = document.querySelectorAll('.priceHolder');
                priceHolder.forEach(holder => {
                    holder.innerHTML = `Total Price: ${totalPrice} kyats`;
                });
            });
            document.getElementById('quantity').addEventListener('input', function() {
                const maxStock = parseInt(this.max, 10);
                const selectedQuantity = parseInt(this.value, 10);

                if (selectedQuantity > maxStock) {
                    alert('Quantity cannot exceed the available stock of ' + maxStock);
                    this.value = maxStock;
                } else if (selectedQuantity < 1) {
                    alert('Quantity must be at least 1.');
                    this.value = 1;
                }
            });
        });

        // Event listener for COD image click
        document.querySelector('.cod').addEventListener('click', function() {
            // Logic for when the user selects COD
            alert('You have selected Cash on Delivery');
            paymentCompleted = true;
            // Mark payment as completed for the sake of this example 
            document.querySelector('.paymentSuccess').style.display = 'block';
        });

        document.getElementById('confirmPurchase').addEventListener('click', function() {
            // Logic to check if the user has made a payment 
            const paymentCompleted = document.querySelector('.paymentSuccess').style.display === 'block';
            // Check if the payment success message is visible
            if (!paymentCompleted) {
                // Vibrate the payment button if the payment is not completed
                navigator.vibrate(200);
                // Vibrate for 200 milliseconds
                alert('You need to complete the payment to confirm the purchase.');
                return;
                // Prevents further execution if the payment is not completed 
            }

            // // Logic to check if the user is logged in
            const isLoggedIn = Boolean(document.cookie.match(/^(.*;)?\s*sessionId\s*=\s*[^;]+(.*)?$/)); // Checks for a session cookie

            // if (!isLoggedIn) {
            //     alert('You need to log in to confirm the purchase.');
            //     return; // Prevents further execution if the user is not logged in
            // }
            const modal = document.querySelector('.modal');
            const postId = this.dataset.postId;
            const postKind = this.dataset.postKind;
            const quantity = modal.querySelector('#quantity').value;
            const aboutProduct = this.dataset.aboutProduct;
            const buyerAddress = modal.querySelector('#buyerAddress').value;
            const buyerPhone = modal.querySelector('#buyerPhone').value;

            const ownerUsername = this.dataset.ownerUsername; // Now correctly sourced from HTML data attribute

            console.log('Sending data:', {
                postId,
                postKind,
                quantity,
                aboutProduct,
                buyerAddress,
                buyerPhone,
                ownerUsername
            });

            // AJAX request setup
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'fetchingNoti.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    console.log('Response:', xhr.responseText);
                    alert('Notification sent to the owner!');
                }
            };

            xhr.send('postId=' + encodeURIComponent(postId) +
                '&postKind=' + encodeURIComponent(postKind) +
                '&quantity=' + encodeURIComponent(quantity) +
                '&aboutProduct=' + encodeURIComponent(aboutProduct) +
                '&buyerAddress=' + encodeURIComponent(buyerAddress) +
                '&buyerPhone=' + encodeURIComponent(buyerPhone) +
                '&ownerUsername=' + encodeURIComponent(ownerUsername));

            // Second AJAX request
            const xhr2 = new XMLHttpRequest();
            xhr2.open('POST', 'stock_update.php', true);
            xhr2.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr2.onreadystatechange = function() {
                if (xhr2.readyState === XMLHttpRequest.DONE && xhr2.status === 200) {
                    console.log('Response from anotherFile:', xhr2.responseText);
                }
            };
            xhr2.send('postId=' + encodeURIComponent(postId) +
                '&postKind=' + encodeURIComponent(postKind) +
                '&quantity=' + encodeURIComponent(quantity));
        });

        function filterPosts(filterType) {
            // Get the current URL and update the filter query parameter
            const url = new URL(window.location);
            url.searchParams.set('filter', filterType);

            // Redirect to the updated URL with the filter parameter
            window.location = url.toString();
        }
    </script>
</body>

</html>