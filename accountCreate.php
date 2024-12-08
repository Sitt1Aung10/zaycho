<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create an Account</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <style>
        body {
            background-image: url("img/testHomeImg.webp");
            background-size: cover;
            background-repeat: no-repeat;
            background-color: #000000c5;
            background-blend-mode: color;
        }
        .container {
            width: calc(100% - 70%);
            height: max-content;
            padding: 20px;
            box-sizing: border-box;
            border-radius: 5px;
            box-shadow: 0 0px 10px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 50%;left: 50%;
            transform: translate(-50% , -50%);
            border: 1px solid #fff;
        }
        h2 {
            color: #fff;
        }
        label {
            color: #fff;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: calc(100% - 10%);
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }
        input[type="text"]:focus {
            outline: none;
        }
        button {
            color: #fff;
            border: 1px solid #fff;
            padding: 10px 20px;
            border-radius: 5px;
            background-color: transparent;
            cursor: pointer;
            width: 100%;
            text-align: center;
        }
        input[type="submit"]:hover {
           color: #000;
            background-color: #EBEBEB;
        } 
        a {
            display: block;
            width: 100%;
        text-align: center;
          color: #fff;
          border: 1px solid #fff;
            border-radius: 5px;
            background-color: transparent;
        }
        input[type=text]:placeholder-shown {
            font-size: 10px;
        }
    </style>
</head>
<body>
     <?php include 'accountTable.php' ?>
    <div class="container">
        <h2>Create an Account</h2>
        <form method="post" enctype="multipart/form-data">
          <label for="username">UserName:</label>
          <input type="text" id="name" name="username" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <label for="repw">Reenter The Password</label>
            <input type="text" id="repw">
            <input type="text" id="address" name="address" required placeholder="Your Address (This Address Will Set To Default You Can Change Anytime)">
            <input type="text" id="nrcText"  name="nrcText" placeholder="Type Your NRC For Verification">
            <label for="nrc">NRC Card</label>
            <input type="file"  id="nrc" name="nrc" required>
            <br>
            <button type="submit" name="submit" id="submit">Create Account</button>
        </form>
        <p style="color: red;font-size:.6rem;"><strong>You Need To Create An Account To Access All Features</strong></p>
        <a href="accountLogin.php">Login page</a>
    </div>
    <script>
    $(document).ready(function() {
    $('#repw').on('input', function() {
        var password = $('#password').val();
        var reenteredPassword = $(this).val();
        var submitButton = $('#submit'); // Ensure your submit button has the ID 'submit'

        if (reenteredPassword === password) {
            $(this).css('border-color', 'green');
            submitButton.prop('disabled', false); // Enable the submit button
        } else {
            $(this).css('border-color', 'red'); // Reset to default
            submitButton.prop('disabled', true); // Disable the submit button
        }
    });
});
    </script>
</body>
</html>
