<?php
// Ensure session_start() is at the very top of the file
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notification</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="CSS/allProduct.css">
    <script src="https://kit.fontawesome.com/8cbc109b9d.js" crossorigin="anonymous"></script>
    <style>
        body {
            position: relative;
            background-color: #090015;
        }

        .notiPage {
            display: grid;
           grid-template-columns: 1fr 1fr 1fr;
            gap: 5%;
            padding-top: 100px;
            padding-bottom: 300px;
        }

        body::after {
            content: 'Notification';
            position: fixed;
            left: 50%;
            transform: translateX(-50%);
            top: 10px;
            color: #fff;
            background-color: #232F3E;
            padding: 10px;
            border-radius: 5px;
            z-index: 999;
        }

        .notification {
            width: 400px;
            height: auto;
            padding: 20px;
            box-sizing: border-box;
            background-color:  #000;
            /* Light background color for readability */
            border: 1px solid #000;
            /* Subtle border */
            border-radius: 8px;
            /* Rounded corners */
            font-family: Arial, sans-serif;
            margin-top: 50px;
            margin-left: 40px;
            position: relative;
            /* Font choice */
        }

        .notification h3 ,
        .notification h5{
            color: #fff;
            background-color: #232F3E;
            padding: 10px;
            border-radius: 5px;
            box-sizing: border-box;
            margin-bottom: 10px;
            text-transform: capitalize;
            /* Ensure productKind displays nicely */
        }

        .notification p {
            margin: 6px 0;
            color: #fff;
            font-size: 14px;
            line-height: 1.4;
        }

        .notification p strong {
            color: #fff;
        }

        .notification p:not(:last-child) {
            border-bottom: 1px solid gray;
            /* Divider between entries */
            padding-bottom: 10px;
            margin-bottom: 10px;
        }

        .comfirmOrder {
            color: #fff;
            background-color: #4CAF50;
            padding: 10px;
            box-sizing: border-box;
            border: none;
            cursor: pointer;
        }

        #status {
            color: green;

            font-weight: bolder;
        }

        .modal {
            display: none;
            /* Hidden by default */
            transition: opacity 0.3s ease;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 400px;
            height: 500px;
            overflow-y: scroll;
            background: #fff;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            z-index: 1000;
            padding: 20px;
        }

        .modal input,
        .modal button {
            width: 100%;
            margin: 5px 0;
        }

        .modal-overlay {
            display: none;
            /* Hidden by default */
            position: fixed;
            transition: opacity 0.3s ease, transform 0.3s ease;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 900;
        }

        .modal.show {
            opacity: 1;
            transform: translate(-50%, -50%) translateY(0);
            pointer-events: auto;
            /* Enable clicks when visible */
        }

        .modal-overlay.show {
            opacity: 1;
            pointer-events: auto;
            /* Enable clicks when visible */
        }

        #buyerAddress {
            width: 100%;
            height: 200px;
            resize: none;
        }

        .fa-question {
            position: absolute;
            right: 20px;top: 10px;
        }
        @media (max-width:800px) {
            .notiPage {
                grid-template-columns: 1fr 1fr;
                gap: 0%;
            }
            .notification {
                width: 370px;
                margin-left: 10px;
            }
        }
    </style>
</head>

<body>
    <div class="notiPage">
        <?php
    // Check if the session is already started

        // Assuming the owner (logged-in user) is stored in session
        $ownerUsername = $_SESSION['username'];  // Owner username from session

        // Database connection
        $servername = "localhost";
        $dbname = "zaycho";
        $mysqli = new mysqli($servername, "root", "", $dbname);

        if ($mysqli->connect_errno) {
            die("Connection failed: " . $mysqli->connect_error);
        }

        // Query to fetch notifications for the logged-in owner, using the id column as the post ID
        $query = "SELECT id AS notificationPostId, post_kind,buyer_username, quantity, notification_time, about_product, phone_number, buyerAddress 
          FROM notification 
          WHERE owner_username = ? 
          ORDER BY notification_time DESC";

        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('s', $ownerUsername);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if notifications are found
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $postId = htmlspecialchars($row['notificationPostId']); // Now using id as notificationPostId
                $postKind = htmlspecialchars($row['post_kind']);
                $buyerUsername = htmlspecialchars($row['buyer_username']);
                $quantity = htmlspecialchars($row['quantity']);
                $notificationTime = htmlspecialchars($row['notification_time']);
                $aboutProduct = htmlspecialchars($row['about_product']);
                $phoneNumber = htmlspecialchars($row['phone_number']);
                $address = htmlspecialchars($row['buyerAddress']);

                // HTML structure for each notification
                echo '<form method="post" class="notification" action="confirm_order.php">';
                echo '<input type="hidden" name="postId" class="notificationPostId" value="' . htmlspecialchars($postId) . '">';
                echo '<input type="hidden" name="postKind" class="postKind" value="' . htmlspecialchars($postKind) . '">';
                echo '<input type="hidden" name="quantity" class="quantity" value="' . htmlspecialchars($quantity) . '">';
                echo '<input type="hidden" name="about_product" class="aboutProduct" value="' . htmlspecialchars($aboutProduct) . '">';
                echo '<input type="hidden" name="buyerUsername" value="' . htmlspecialchars($buyerUsername) . '"> ';
                echo '<h3>New Order Notification</h3>';
                echo '<i class="fa-solid fa-question" title="Why My Card Turn To Green"></i>';
                echo '<p><strong>Buyer:</strong> ' . htmlspecialchars($buyerUsername) . '</p>';
                echo '<p><strong>Product Details:</strong> ' . $aboutProduct . '</p>';
                echo '<p><strong>Product Kind:</strong> ' . ucfirst(htmlspecialchars($postKind)) . '</p>';
                echo '<p><strong>Quantity:</strong> ' . htmlspecialchars($quantity) . '</p>';
                echo '<p><strong>Time:</strong> ' . htmlspecialchars($notificationTime) . '</p>';
                echo '<p><strong>Phone Number:</strong> ' . htmlspecialchars($phoneNumber) . '</p>';
                echo '<p><strong>Address:</strong> ' . htmlspecialchars($address) . '</p>';

                // Check if the order has already been confirmed
                $confirmedQuery = "SELECT 1 FROM orderConfirm WHERE post_id = ? AND buyer_username = ?";
                $confirmedStmt = $mysqli->prepare($confirmedQuery);
                $confirmedStmt->bind_param('is', $postId, $buyerUsername);
                $confirmedStmt->execute();
                $isConfirmed = $confirmedStmt->get_result()->num_rows > 0;

                $buttonState = $isConfirmed ? 'disabled style="opacity:0.5; cursor:not-allowed;"' : '';
                $buttonText = $isConfirmed ? 'Order Confirmed' : 'Confirm Order';
                $confirmedStmt->close();
                
                echo '<button type="submit" class="confirmOrder" ' . $buttonState . '>' . $buttonText . '</button>';
                
                echo '<button type="submit" class="reljectOrder">Reject Order</button>';
                echo '</form>';
            }
        } else {
            echo '<p>No order notifications found.</p>';
        }


         //this is where i fix the problem
          // Retrieve session username safely
          $ownerUsername = $_SESSION['username'] ?? null;
            
          if (!$ownerUsername) {
              echo "Error: Buyer is not logged in.";
              exit; // Stop further code execution
          }
        // Get buyer username from session (assumes buyer is logged in)
        $buyerUsername = $_SESSION['username'] ?? null;

        if (!$buyerUsername) {
            die("Error: Buyer is not logged in.");
        }

        // Query to fetch order confirmation messages for the logged-in buyer
        $query = "SELECT post_id, post_kind, about_product, message, status, confirmation_time 
          FROM orderConfirm 
          WHERE buyer_username = ? 
          ORDER BY confirmation_time DESC";

        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('s', $buyerUsername);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if messages are found
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $postId = htmlspecialchars($row['post_id']);
                $postKind = htmlspecialchars($row['post_kind']);
                $aboutProduct = htmlspecialchars($row['about_product']);
                $message = htmlspecialchars($row['message']);
                $status = htmlspecialchars($row['status']);
                $confirmationTime = htmlspecialchars($row['confirmation_time']);

                // Display the order confirmation message
                echo '<div class="notification">';
                echo '<p id="status">Status  : '. ucfirst($status).'</p>';
                echo '<h5>Order Confirmation<br>Voucher</h5>';
                echo '<p><strong>Product Kind:</strong> ' . ucfirst($postKind) . '</p>';
                echo '<p><strong>Product Details:</strong> ' . $aboutProduct . '</p>';
                echo '<p><strong>Message:</strong> ' . $message . '</p>';
                echo '<p><strong>Confirmation Time:</strong> ' . $confirmationTime . '</p>';
                echo '</div>';
            }
        } else {
            echo '<p>No order confirmations available.</p>';
        }
        $stmt->close();
        $mysqli->close();
        ?>
        <?php include 'navi.php' ?>
        <?php include 'cartContainer.php' ?>
    </div>
    <script>
        document.querySelectorAll(".confirm_order_btn").forEach(button => {
            const instock = parseInt(button.dataset.instock, 10); // assuming instock is set in data-instock attribute
            const confirmed = button.dataset.confirmed === "true"; // assuming confirmed status is stored in data-confirmed

            if (instock <= 0 || confirmed) {
                button.disabled = true;
                button.style.opacity = 0.5; // make button look pale
            }
        });
    </script>
    <!-- <script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.confirmOrder').forEach(function(button) {
        button.addEventListener('click', function () {
            const form = this.closest('.notification');
            const postId = form.querySelector('.postId').value;
            const postKind = form.querySelector('.postKind').value;
            const quantity = form.querySelector('.quantity').value;
            const aboutProduct = form.querySelector('.aboutProduct').value; // Include about_product

            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'confirm_order.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            alert('Order confirmed successfully!');
                        } else {
                            alert(response.message);
                        }
                    } catch (e) {
                        alert("Error: " + xhr.responseText);
                    }
                }
            };

            xhr.send(
                'postId=' + encodeURIComponent(postId) +
                '&postKind=' + encodeURIComponent(postKind) +
                '&quantity=' + encodeURIComponent(quantity) +
                '&aboutProduct=' + encodeURIComponent(aboutProduct) // Send about_product
            );
        });
    });
}); -->

    <!-- </script> -->
</body>

</html>