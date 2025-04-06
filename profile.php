<?php
session_start(); // Start the session

if (!isset($_SESSION['username'])) {
    header("Location: accountLogin.php"); // Redirect to login if not logged in
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="CSS/profile.css">
    <style>
     
    </style>
</head>
<?php  include 'navi.php' ?>
<body style="background-image: url(img/testHomeImg.webp);">
    <script>
        $(document).ready(function() {
            $('body').css('opacity', 0).animate({
                opacity: 1
            }, 1000); // Adjust the duration as needed

               // When the 'Delete Account' button is clicked, show the warning
    $('#accountDeleteBtn').click(function() {
        $('.delete-account-warning').show(); // Show the warning
    });

    // Optionally, hide the warning when 'Cancel' is clicked
    $('button:contains("Cancel")').click(function() {
        $('.delete-account-warning').hide(); // Hide the warning
    });

    function confirmDeletion() {
        var confirmationInput = $('#confirm-delete').val().trim();
        if (confirmationInput === 'DELETE') {
            if (confirm('Are you sure you want to delete your account? This process will take 1 week and you won’t be able to log in during this period.')) {
                $.ajax({
                    url: 'delete_account.php', // The PHP file that marks the account for deletion
                    success: function(response) {
                        alert(response);
                        // Optionally, redirect the user or log them out
                        window.location.href = 'logout.php'; // Redirect to logout or home
                    }
                });
            }
        } else {
            alert('Please type DELETE to confirm account deletion.');
        }
    }

    function cancelDeletion() {
        alert('Account deletion has been canceled.');
    }

    // Bind the functions to the buttons
    window.confirmDeletion = confirmDeletion;
    window.cancelDeletion = cancelDeletion;
});


    </script>
    <h1 class="name"><?php echo htmlspecialchars($_SESSION['username']); ?>'s Profile</h1>

    <div>
        <p><a  href="logout.php">Logout</a></p>

        <button id="accountDeleteBtn">Delete Account</button>
    </div>

    <div class="delete-account-warning">
    <h1 style="color: red;">Are you sure you want to delete your account?</h1>
    <p>Deleting your account will permanently remove all your data and cannot be undone.</p>
    <p>Before proceeding, make sure you've saved any important data or information you'd like to keep.</p>
    
    <h3>Consequences:</h3>
    <ul>
        <li>Loss of access to all services.</li>
        <li>Permanent deletion of all personal data.</li>
        <li>Any pending orders or transactions will be canceled.</li>
    </ul>
    <hr>
    
    <h3>Frequently Asked Questions:</h3>
    <p><strong><li>Can I recover my account after deletion?</li><br></strong> No, once your account is deleted, it cannot be restored.</p>
    <p><strong><li>What happens to my personal data after account deletion?</li><br></strong> All personal data will be permanently erased, in compliance with our privacy policy.</p>
    <p><strong><li>Can I delete only my data but keep my account?</li><br></strong> You can contact support if you want to delete specific data or modify your account settings.</p>

    <p>To confirm account deletion, please type<span style="color: red;"> 'DELETE' </span>in the box below and click the button to proceed:</p>
    <input type="text" id="confirm-delete" placeholder="Type DELETE to confirm">
    
    <button onclick="confirmDeletion()">Delete Account</button>
    <button onclick="cancelDeletion()">Cancel</button>
</div>



    <h1 style="border-bottom: 1px solid #fff;">History Of Your Post</h1>

    <?php include 'history.php' ?>
</body>

</html>