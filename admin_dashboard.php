<!-- <?php
        // session_start();

        // if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
        //     header("Location: login.php"); // Redirect if the user is not an admin
        //     exit;
        // }

        // Admin-specific content below
        ?> -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="CSS/admin_dashboard.css">
</head>

<body>
    <nav>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const productKinds = [{
                        name: "#car",
                    },
                    {
                        name: "#electronics",
                    },
                    {
                        name: "#beauty_products",
                    },
                    {
                        name: "#mobile",
                    },
                    {
                        name: "#sports",
                    },
                    {
                        name: "#furniture",
                    },
                    {
                        name: "#bicycle",
                    },
                    {
                        name: "#computers",
                    },
                    {
                        name: "#spare_parts",
                    },
                    {
                        name: "#fashion",
                    },
                    {
                        name: "#toys",
                    },
                    {
                        name: "#books",
                    },
                    {
                        name: "#sneaker",
                    },
                    {
                        name: "#thrift_fashion",
                    },
                ]
                const body = document.querySelector("body")

                const nav = document.querySelector("nav");

                productKinds.forEach(product => {
                    const productTableLink = document.createElement("a");
                    productTableLink.href = product.name;
                    const productName = product.name.replace(/^#/, '');
                    productTableLink.innerHTML = productName;
                    nav.append(productTableLink);
                    body.append(nav);
                });

                const links = document.querySelectorAll('nav a');
                const sections = productKinds.map(product => document.querySelector(product.name));

                function updateActiveLink() {
                    let currentSection = -1; // Start with no active section
                    const scrollPosition = window.scrollY + 50; // Adjust for offset (50px buffer)

                    // Find the section that matches the scroll position
                    sections.forEach((section, index) => {
                        if (section) {
                            // Account for scroll-margin-top (80px in your CSS)
                            const adjustedOffsetTop = section.offsetTop - 80;
                            if (adjustedOffsetTop <= scrollPosition) {
                                currentSection = index;
                            }
                        }
                    });

                    // Remove and add the active class based on the current section
                    links.forEach(link => link.classList.remove('active'));
                    if (currentSection >= 0) {
                        links[currentSection].classList.add('active');
                    }
                }


                // Update active link on page load and scroll
                updateActiveLink();
                window.addEventListener('scroll', updateActiveLink);

                // Smooth scrolling for anchor links
                links.forEach(link => {
                    link.addEventListener('click', (e) => {
                        e.preventDefault();
                        const targetId = e.target.getAttribute('href');
                        const targetSection = document.querySelector(targetId);
                        if (targetSection) {
                            window.scrollTo({
                                top: targetSection.offsetTop - 50, // Adjust for navbar height
                                behavior: 'smooth',
                            });
                        }
                    });
                });
            });
        </script>
    </nav>
    <div class="banner">
        <a href="#orderConfirm">Order Confirmation</a>
        <a href="#userAccounts">User Accounts</a>
        <a href="logout.php">Log Out</a>
    </div>


    <?php
    // Database connection details
    $servername = "localhost";
    $dbname = "zaycho";
    $mysqli = new mysqli($servername, "root", "", $dbname);

    if ($mysqli->connect_errno) {
        die("Connection failed: " . $mysqli->connect_error);
    }


    $productKinds = ['car', 'electronics', 'beauty_products', 'mobile', 'sports', 'furniture', 'bicycle', 'computers', 'spare_parts', 'fashion', 'toys', 'books', 'sneaker', 'thrift_fashion'];


    foreach ($productKinds as $productKind) {
        $query = "SELECT id, username AS owner_username, about_product, images, instock_amount, price, discount, phone_number, address FROM `$productKind` ORDER BY id DESC";
        $result = $mysqli->query($query);

        echo "<h2>" . ucfirst(str_replace('_', ' ', $productKind)) . "</h2>";
        echo "<table class='product-table' id='$productKind'>";
        echo "<tr>
            <th>ID</th>
            <th>Owner</th>
            <th>About Product</th>
            <th>Image</th>
            <th>Stock</th>
            <th>Price</th>
            <th>Discount</th>
            <th>Phone</th>
            <th>Address</th>
            <th>Delete</th>
          </tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td style='background-color:#4CAF50;color:#fff;'>{$row['id']}</td>
                <td>{$row['owner_username']}</td>
                <td>{$row['about_product']}</td>
                <td><img src='{$row['images']}' alt='Product Image' class='product-image'></td>
                <td>{$row['instock_amount']}</td>
                <td>{$row['price']}</td>
                <td>{$row['discount']}%</td>
                <td>{$row['phone_number']}</td>
                <td>{$row['address']}</td>
                <td><button class='delete-btn' onclick='deleteProduct({$row['id']}, \"$productKind\")'>Delete</button></td>
              </tr>";
        }
        echo "</table><br>";
    }
    ?>

    <script>
        function deleteProduct(productId, productKind) {
            if (confirm("Are you sure you want to delete this product?")) {
                window.location.href = `deleteProduct.php?id=${productId}&table=${productKind}`;
            }
        }
    </script>

    <h2>Orders Management</h2>
    <table class="product-table" id="Notification">
        <tr>
            <th>ID</th>
            <th>Owner Username</th>
            <th>Buyer Username</th>
            <th>About Product</th>
            <th>Buyer Address</th>
            <th>Post Kind</th>
            <th>Quantity</th>
            <th>Phone Number</th>
        </tr>

        <?php
        $query = "SELECT id, owner_username, buyer_username, about_product, buyerAddress, post_kind, quantity, phone_number,price FROM `notification`";
        $result = $mysqli->query($query);

        while ($row = $result->fetch_assoc()) {
            $orderId = htmlspecialchars($row['id']);
            echo "<tr>
            <td style='background-color:#4CAF50;color:#fff;'>{$row['id']}</td>
            <td>{$row['owner_username']}</td>
            <td>{$row['buyer_username']}</td>
            <td>{$row['about_product']}</td>
            <td>{$row['buyerAddress']}</td>
            <td>{$row['post_kind']}</td>
            <td>{$row['quantity']}</td>
            <td>{$row['phone_number']}</td>
        </tr>";
        }
        ?>
    </table>




    <h2>Order Confirmations</h2>
    <table class="product-table" id="orderConfirm">
        <tr>
            <th>ID</th>
            <th>Owner Username</th>
            <th>Buyer Username</th>
            <th>Post ID</th>
            <th>Post Kind</th>
            <th>About Product</th>
            <th>Confirmation Time</th>
            <th>Status</th>
        </tr>

        <?php
        $query = "SELECT id, owner_username, buyer_username, post_id, post_Kind, about_product, status , confirmation_time FROM `orderconfirm` ORDER BY confirmation_time DESC";
        $result = $mysqli->query($query);

        while ($row = $result->fetch_assoc()) {
            $disabled = ($row['status'] === "Has Been Delivered Your Product") ? 'disabled' : '';
            echo "<tr>
            <td style='background-color:#4CAF50;color:#fff;'>{$row['id']}</td>
            <td>{$row['owner_username']}</td>
            <td>{$row['buyer_username']}</td>
            <td>{$row['post_id']}</td>
            <td>{$row['post_Kind']}</td>
            <td>{$row['about_product']}</td>
            <td>{$row['confirmation_time']}</td>
 
            <td>
           <button class='btn' onclick='nextStep(this, {$row['post_id']})'>{$row['status']}</button> </td>
            </td>
          </tr>";
        }
        ?>

    </table>

    <script>
        function nextStep(button, orderId) {
            const steps = ["Sending Order Notification To Post Owner", "Packaging Your Order", "Deliver Is On The Way", "Has Been Delivered Your Product"];
            let currentStep = button.innerText;
            let nextIndex = steps.indexOf(currentStep) + 1;
            if (nextIndex < steps.length) {
                button.innerText = steps[nextIndex];
            } else {
                button.innerText = steps[0];
            }
            // Disable the button if the order is delivered
            if (button.innerText === "Has Been Delivered Your Product") {
                button.disabled = true;
            }
            // Send the updated status to the server
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "update_status.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.send("orderId=" + orderId + "&status=" + button.innerText);
        }
        // Ensure that the button is disabled if the status is "Has Been Delivered Your Product" when the page loads
        document.addEventListener('DOMContentLoaded', function() {
            const buttons = document.querySelectorAll('.btn');
            buttons.forEach(button => {
                if (button.innerText === "Has Been Delivered Your Product") {
                    button.disabled = true;
                }
            });
        });
    </script>

    <h2>User Accounts</h2>
    <table class="product-table" id="userAccounts">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>

        <?php
        $query = "SELECT id, name, email FROM `users` ORDER BY id ASC";
        $result = $mysqli->query($query);

        while ($row = $result->fetch_assoc()) {
            echo "<tr>
            <td  style='background-color:#4CAF50;color:#fff;'>{$row['id']}</td>
            <td>{$row['name']}</td>
            <td>{$row['email']}</td>
            <td><button class='inspect-btn' onclick='inspectAccount({$row['id']})'>Inspect Account</button></td>
          </tr>";
        }
        ?>

    </table>

    <script>
        function inspectAccount(userId) {
            window.location.href = `inspectAccount.php?id=${userId}`;
        }
    </script>




    <!-- Admin functionalities go here -->
    <!-- <?php
            // $activityQuery = "SELECT username, email, last_login, last_logout FROM admin ORDER BY last_login DESC";
            // $result = $db->query($activityQuery);

            // while ($row = $result->fetch_assoc()) {
            //     echo "Username: " . $row['username'] . "<br>";
            //     echo "Email: " . $row['email'] . "<br>";
            //     echo "Last Login: " . $row['last_login'] . "<br>";
            //     echo "Last Logout: " . $row['last_logout'] . "<br><hr>";
            // }

            // 
            ?> -->
</body>

</html>