<head>
    <link rel="stylesheet" href="CSS/navi.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400..700&family=Montserrat+Alternates:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/8cbc109b9d.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <style>
        .fade-out {
            opacity: 0;
            transition: opacity 1s linear;
        }
    </style>
</head>

<body>

    <?php
    // Get the current file name
    $currentPage = basename($_SERVER['PHP_SELF']);
    if (session_status() === PHP_SESSION_NONE) {
        session_start(); // Start the session only if it hasn't started yet
    }
    $_SESSION['loggedIn'] = true; 
    
    // Assuming 'loggedIn' is the session variable set when the user logs in
    $isUserLoggedIn = isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true;
    ?>
    <header>
        <img class="logo" src="img/ZaychoLogo.png">
        <script>
            $(document).ready(function() {
                $('.logo').click(function() {
                    $('body').addClass('fade-out');
                    setTimeout(function() {
                        location.reload();
                    }, 1); // Match this duration with the CSS transition duration
                });
            })
        </script>
    </header>
    <nav>
        <ul>
            <li><a href="index.php">home<i class="fa-solid fa-house"></i></a></li>
            <li><a href="allProduct.php"> all<i class="fa-solid fa-bars"></i></a></li>
            <?php if ($isUserLoggedIn): ?>
                <li><a href="notification.php">notification<i class="fa-solid fa-bell"></i></a></li>
            <?php endif; ?>
            <!-- <?php if ($currentPage !== 'post.php'): ?>
                <li><a href="post.php">Post<i class="fa-solid fa-plus"></i></a></li>
            <?php endif; ?> -->
            <?php if($isUserLoggedIn && $currentPage !== 'post.php'): ?> <li><a href="post.php">Post<i class="fa-solid fa-plus"></i></a></li>
                <?php endif; ?>
            <?php if ($isUserLoggedIn): ?>
                <li id="wishlistButtonContainer"><button id="wishListButton">Wish List<img class="wishLogo" src="img/wishLogo.png"></button></li>
            <?php endif; ?>
            <?php if ($isUserLoggedIn && $currentPage !== 'post.php'): ?>
                <li id="CartButtonContainer"><button id="cart-open">Cart<i class="fa-solid fa-cart-shopping"></i></button></li>
            <?php endif; ?>
            <li>
                <a href="profile.php">
                    <?php
                    echo isset($_SESSION['username']) && !empty($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Me';
                    ?>
                    <i class="fa-solid fa-user"></i>
                </a>
            </li>
        </ul>
    </nav>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Check the current page URL
        const currentPage = window.location.pathname.split('/').pop();
        const wishlistButtonContainer = document.getElementById('wishlistButtonContainer');
        const cartButtonContainer =document.getElementById("CartButtonContainer");

        // If the current page is not 'allProduct.php', hide the wishlist button
        if (currentPage !== 'allProduct.php' && wishlistButtonContainer) {
            wishlistButtonContainer.style.display = 'none';
        }

        // If the current page is not 'allProduct.php', hide the wishlist button
        if (currentPage !== 'allProduct.php' && cartButtonContainer) {
            cartButtonContainer.style.display = 'none';
        }
    });
</script>

</body>