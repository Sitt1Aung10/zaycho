<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/home.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400..700&display=swap" rel="stylesheet">
</head>

<body>
    <script>
        $(document).ready(function() {
            $('body').css('opacity', 0).animate({
                opacity: 1
            }, 1000); // Adjust the duration as needed
        });
    </script>
    <!-- <script>
        const wishListButton = document.querySelector("#wishListButton");
        const wishListContainer = document.querySelector("#wishListContainer");
        wishListButton.addEventListener('click', () => {
            wishListContainer.classList.toggle("activeWishListContainer")
        });
    </script> -->

    <section id="connect-sell" style="background-image:url(img/homeImg.jpg);">
  <div style="display: flex; flex-wrap: wrap; align-items: center; max-width: 1200px; margin: 0 auto;">
    <!-- Text Content -->

    <div style="flex: 1; min-width: 300px; padding: 20px;">
      <h2>Connect and Sell Your Product Around the Globe</h2>
      <p>
        Join our platform to connect with millions of buyers and sellers worldwide. Expand your reach and grow your business in just a few clicks.
      </p>
      <p>
        Whether you're a small business owner or an individual seller, we make it easy for you to showcase your products, find customers, and manage your sales efficiently.
      </p>
      <a href="accountCreate.php">
        Get Started Today
      </a>
    </div>
  </div>
</section>


    <section id="get_ready">
        <h1>Ready To Get Started?</h1>
        <p> Explore millions of products from trusted suppliers by signing up today!</p>
        <a href="accountCreate.php" style="background-image: url(img/fountainpen.png);">Sign Up</a>
    </section>
    <section>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const body = document.body;

                // Start with the body hidden
                body.style.opacity = 0;
                body.style.transition = 'opacity 1s ease-in-out'; // Adjust the duration as needed

                // Fade in the body once the content is ready
                window.onload = () => {
                    body.style.opacity = 1;
                };
            });

            //enter event for searching
            $(".userInput").keypress(function(event) {
                var key = event.keyCode || event.which;
                var userInputText = $(".userInput").val().toLowerCase();
                var matchFound = false;
                if (key == 13) {
                    $(".post").each(function() {
                        var productName = $(this).find(".productName").text().toLowerCase();

                        if (productName.includes(userInputText)) {
                            $(this).show();
                            matchFound = true;
                        } else {
                            $(this).hide();
                        }
                    });
                    if (!matchFound) {
                        var alert = $("<p>").text("No Product Found").css({
                            "color": "red",
                            "position": "absolute",
                            "top": "50%",
                            "left": "50%",
                            "transform": "translate(-50%, -50%)",
                            "padding": "20px 0",
                        });
                        $("body").append(alert);
                    }
                }
            })

            //click event for searching
            $(".searchBtn").click(function() {
                var userInputText = $(".userInput").val().toLowerCase();
                var matchFound = false;

                $(".card").each(function() {
                    var productName = $(this).find(".productName").text().toLowerCase();

                    if (productName.includes(userInputText)) {
                        $(this).show();
                        matchFound = true;
                    } else {
                        $(this).hide();
                    }
                });
                if (!matchFound) {
                    var alert = $("<p>").text("No Product Found").css({
                        "color": "red",
                        "position": "absolute",
                        "top": "50%",
                        "left": "50%",
                        "transform": "translate(-50%, -50%)",
                        "padding": "20px 0",
                    });
                    $("#dailyDeal").append(alert);
                }
            });

            var clearDataBtn = $(".searchBtn ~ .fa-xmark");

            clearDataBtn.click(function() {
                $(".card").each(function() {
                    $(this).show();
                    $(".userInput").val(' ');

                })
            });

            const category = [{
                    name: "Car",
                    icon: "fa-solid fa-car-rear",
                    filterKey: "car"
                },
                {
                    name: "Spare Parts",
                    icon: "fa-solid fa-screwdriver-wrench",
                    filterKey: "spare_parts"
                },
                {
                    name: "Bicycle",
                    icon: "fa-solid fa-bicycle",
                    filterKey: "bicycle"
                },
                {
                    name: "Electronics",
                    icon: "fa-solid fa-lightbulb",
                    filterKey: "electronics"
                },
                {
                    name: "Mobile",
                    icon: "fa-solid fa-mobile",
                    filterKey: "mobile"
                },
                {
                    name: "Computers",
                    icon: "fa-solid fa-computer",
                    filterKey: "computers"
                },
                {
                    name: "Books",
                    icon: "fa-solid fa-book",
                    filterKey: "books"
                },
                {
                    name: "Furniture",
                    icon: "fa-solid fa-chair",
                    filterKey: "furniture"
                },
                {
                    name: "Fashion",
                    icon: "fa-solid fa-hat-cowboy",
                    filterKey: "fashion"
                },
                {
                    name: "Thrift Fashion",
                    icon: "fa-solid fa-shirt",
                    filterKey: "thrift_fashion"
                },
                {
                    name: "Beauty Products",
                    icon: "fa-solid fa-wand-magic-sparkles",
                    filterKey: "beauty_products"
                },
                {
                    name: "Toys",
                    icon: "fa-solid fa-gamepad",
                    filterKey: "toys"
                },
                {
                    name: "Sports",
                    icon: "fa-solid fa-volleyball",
                    filterKey: "sports"
                },
                {
                    name: "Sneakers",
                    icon: "fa-solid fa-shoe-prints",
                    filterKey: "sneaker"
                },
            ];

            // var categorySection = $("<section>");
            // categorySection.addClass("categorySection");

            category.forEach(function(categoryItem) {
                var productName = $("<p>").text(categoryItem.name);
                var productIcon = $("<i>").addClass(categoryItem.icon);

                // Button redirects to allProduct.php with query parameter
                var productButton = $("<button>")
                    .addClass("productFilterButton")
                    .on("click", function() {
                        window.location.href = `allProduct.php?category=${categoryItem.filterKey}`;
                    })
                    .append(productIcon, productName);

                // categorySection.append(productButton);
            });

            // $("body").append(categorySection);
        </script>

    </section>

    <script>
        const contents = [{
                title: "Millions of Business Offerings",
                text: "Explore products and suppliers for your business from millions of offerings worldwide. Discover a diverse range of products, from electronics to fashion, and connect with suppliers from various industries to meet your business needs.",
                icon: "fa-solid fa-table-cells-large",
            },
            {
                title: "About Us",
                text: "We are dedicated to connecting businesses with quality products and reliable suppliers from around the globe. Our mission is to facilitate seamless transactions and provide a platform where businesses can grow and thrive.",
                icon: "fa-regular fa-user",
            },
            {
                title: "Get In Touch",
                text: "We'd love to hear from you! Whether you have questions about our services, our team is here to help. Contact us via email, phone, or through our online contact form, and we'll get back to you as soon as possible. Your satisfaction is our priority.",
                icon: "fa-regular fa-envelope",
            },
            {
                title: "Why Choose Us",
                text: " We offer a vast selection of millions of products to meet your diverse needs, all from trusted global suppliers to ensure consistent quality. Our competitive pricing helps you maximize your profit margins.Join our community and elevate your business sourcing today!",
                icon: "fa-solid fa-shield",
            }
        ];

        var about_us = $("<section>");
        about_us.addClass("about_us");
        contents.forEach(function(contents) {
            var title = $("<h1>");
            title.text(contents.title);

            var text = $("<p>");
            text.text(contents.text);

            var icon = $("<i>");
            icon.attr("class", contents.icon);

            var about_us_card = $("<div>");
            about_us_card.addClass("about_us_card");
            about_us_card.append(icon, title, text);

            about_us.append(about_us_card);
        })
        $('body').append(about_us);
    </script>

    <section class="contactUs">
        <h1>Contact Us</h1>
        <h4>Email: zaychonoreply@gmail.com</h4>
        <h4>Phone Number:0997977979</h4>
        <p><i class="fa-solid fa-question"></i>What We do</p>
        <p><i class="fa-solid fa-handshake"></i>Customer Support Information</p>
        <p><i class="fa-solid fa-comments"></i>FAQ Section: Answer common questions related to shipping, returns, payments, etc.</p>
        <p><i class="fa-solid fa-credit-card"></i>Resolve issues related to payment methods, refunds, or cancellations</p>
    </section>
    <?php include 'cartContainer.php' ?>
</body>