<link rel="stylesheet" href="CSS/history.css">
<!-- Edit Post Modal (Initially Hidden) -->
<div id="editPostModal" style="display:none;">
    <form id="editPostForm">
        <input type="hidden" id="edit-post-id">
        <input type="hidden" id="edit-product-kind">
        <h6 style="margin: 0;">Post Editing</h6>
        <label>About Product:</label>
        <textarea id="edit-about_product"></textarea>

        <label>Price (in Kyats):</label>
        <input type="number" id="edit-price">

        <label>In Stock:</label>
        <input type="number" id="edit-instock_amount">

        <button type="submit">Save Changes</button>
        <button type="button" id="closeEditModal">Cancel</button>
    </form>
</div>


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<div style="display: flex; align-items: center; gap: 8px;">
    <i class="fas fa-sort" style="font-size: 1.2em;"></i>
    <span>Sort by Timeline</span>
</div>
<section>
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

    // Get the logged-in user's username
    $username = $_SESSION['username'] ?? 'guest';  // Assuming username is stored in session

    // List of product kinds
    $productKinds = ['car', 'electronics', 'beauty_products', 'mobile', 'sports', 'furniture', 'bicycle', 'computers', 'spare_parts', 'fashion', 'toys', 'books', 'sneaker', 'thrift_fashion'];

    // Loop through each product kind
    foreach ($productKinds as $productKind) {
        // Query to select only the posts by the logged-in user from each product table
        $query = "SELECT id, username, about_product, images, instock_amount, price, discount, coupon, phone_number, address 
              FROM `$productKind` 
              WHERE username = ? 
              ORDER BY id DESC";

        // Prepare and execute the statement
        if ($stmt = $mysqli->prepare($query)) {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            // Check if there are any results
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $images = explode(',', $row['images']);

                    echo '<div class="post" data-id="' . htmlspecialchars($row['id']) . '" data-kind="' . htmlspecialchars($productKind) . '" data-instock="' . htmlspecialchars($row['instock_amount']) . '" data-coupon="' . htmlspecialchars($row['coupon']) . '">';
                    $sliderId = 0; // Unique ID for each slider

                
                    $sliderId = $row['id']; // Unique ID based on the post ID

                    echo '<div class="image-slider-container" data-slider-id="' . $sliderId . '">';
                    echo '<div class="image-slider">';
    
                    foreach ($images as $image) {
                        if (!empty($image) && file_exists('uploads/' . $image)) {
                            echo '<div class="slide">';
                            echo '<img src="uploads/' . htmlspecialchars($image) . '" alt="Product Image" width="100">';
                            echo '</div>';
                        } else {
                            echo '<div class="slide"><p>Image not found.</p></div>';
                        }
                    }
    
                    echo '</div>';
                    echo '<button class="prev" data-slider-id="' . $sliderId . '">❮</button>';
                    echo '<button class="next" data-slider-id="' . $sliderId . '">❯</button>';
                    echo '</div>'; // End of image-slider-container


                    echo '<h4>' . ucfirst($productKind) . ' Post by ' . htmlspecialchars($row['username']) . '</h4>';
                    echo '<p>' . htmlspecialchars($row['about_product']) . '</p>';
                    echo '<p>In stock: ' . htmlspecialchars($row['instock_amount']) . '</p>';
                    echo '<p>Price: ' . htmlspecialchars($row['price']) . ' kyats</p>';
                    if (isset($row['discount']) && $row['discount'] > 0) {
                        echo '<p id="discountNoti">Discount: ' . htmlspecialchars($row['discount']) . '%</p>';
                    }
                    if (!empty($row['coupon'])) {
                        echo '<p>Coupon: ' . htmlspecialchars($row['coupon']) . '</p>';
                    } else {
                        echo '<p>No coupon code exists.</p>';
                    }
                    echo '<p>Phone: ' . htmlspecialchars($row['phone_number']) . '</p>';
                    echo '<p>Address: ' . htmlspecialchars($row['address']) . '</p>';

            
                    echo '<br>';
                    echo '<br>';


                    // Add Edit Button, pale and disabled if out of stock
                    $editButtonClass = ($row['instock_amount'] == 0) ? 'editBtn disabled' : 'editBtn';
                    $editButtonDisabled = ($row['instock_amount'] == 0) ? 'disabled' : '';
                    echo '<button class="' . $editButtonClass . '" data-id="' . htmlspecialchars($row['id']) . '" data-kind="' . htmlspecialchars($productKind) . '" ' . $editButtonDisabled . '>Edit Post</button>';
                    // Add the Delete button
                    echo '<button type="button" class="delete-btn" data-id="' . htmlspecialchars($row['id']) . '" data-kind="' . htmlspecialchars($productKind) . '">Delete</button>';
                    echo '</div>';
                }
            } else {
                // echo "<p>No products found for this category.</p>";
            }

            // Close the statement
            $stmt->close();
        } else {
            echo "Error: " . $mysqli->error;
        }
    }

    // Close the database connection
    $mysqli->close();
    ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
       $(document).ready(function() {
    $('[data-slider-id]').each(function() {
        const $sliderContainer = $(this);
        const sliderId = $sliderContainer.data('slider-id');
        let currentIndex = 0;

        const $slides = $sliderContainer.find('.slide');
        const totalSlides = $slides.length;

        // Show the first slide initially
        $slides.hide().eq(currentIndex).show();

        // Shared next button functionality
        $('.next').click(function() {
            if ($(this).data('slider-id') == sliderId) {
                $slides.eq(currentIndex).fadeOut();
                currentIndex = (currentIndex + 1) % totalSlides;
                $slides.eq(currentIndex).fadeIn();
            }
        });

        // Shared previous button functionality
        $('.prev').click(function() {
            if ($(this).data('slider-id') == sliderId) {
                $slides.eq(currentIndex).fadeOut();
                currentIndex = (currentIndex - 1 + totalSlides) % totalSlides;
                $slides.eq(currentIndex).fadeIn();
            }
        });
    });
});


        // JavaScript for deleting posts
        // Attach click event to the delete button
        $(document).on('click', '.delete-btn', function() {
            var postElement = $(this).closest('.post');
            var postId = postElement.data('id');
            var productKind = postElement.data('kind');

            console.log("Post ID:", postId);
            console.log("Product Kind:", productKind);

            // Send the delete request to the server
            $.ajax({
                url: 'delete_post.php',
                type: 'POST',
                data: {
                    postId: postId,
                    productKind: productKind
                },
                success: function(response) {
                    console.log(response); // Log the server's response
                    if (response === "success") {
                        postElement.remove(); // Remove the post from the HTML only if successful
                    } else {
                        alert("Post Deleted Successfully"); // Show error message
                        location.reload(); // Auto-reload the page
                    }
                },
                error: function() {
                    alert("An error occurred while deleting the post.");
                }
            });
        });
        // Prevent action for disabled edit buttons
        $(document).on('click', '.editBtn.disabled', function(e) {
            e.preventDefault();
            return false;
        });

        // Open Edit Modal on "Edit" button click
        $(document).on('click', '.editBtn', function() {
            var postId = $(this).data('id');
            var productKind = $(this).data('kind');

            // Use AJAX to fetch the current details of the post
            $.ajax({
                url: 'fetchPostDetails.php',
                method: 'POST',
                data: {
                    id: postId,
                    kind: productKind
                },
                success: function(response) {
                    var postData = JSON.parse(response);

                    // Populate the form with existing data
                    $('#edit-post-id').val(postId);
                    $('#edit-product-kind').val(productKind);
                    $('#edit-about_product').val(postData.about_product);
                    $('#edit-price').val(postData.price);
                    $('#edit-instock_amount').val(postData.instock_amount);

                    // Show the modal
                    $('#editPostModal').show();
                }
            });
        });

        // Submit updated post data
        $('#editPostForm').on('submit', function(e) {
            e.preventDefault();

            var postId = $('#edit-post-id').val();
            var productKind = $('#edit-product-kind').val();
            var updatedAboutProduct = $('#edit-about_product').val();
            var updatedPrice = $('#edit-price').val();
            var updatedInStock = $('#edit-instock_amount').val();

            $.ajax({
                url: 'update_post.php',
                method: 'POST',
                data: {
                    id: postId,
                    kind: productKind,
                    about_product: updatedAboutProduct,
                    price: updatedPrice,
                    instock_amount: updatedInStock
                },
                success: function(response) {
                    if (response === 'success') {
                        alert('Post updated successfully!');
                        location.reload(); // Reload to reflect changes
                    } else {
                        alert('Failed to update the post.');
                    }
                }
            });
        });

        // Close the modal on "Cancel" button click
        $('#closeEditModal').on('click', function() {
            $('#editPostModal').hide();
        });
    </script>
</section>