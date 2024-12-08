// delete_post.php
<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_POST['postId']) && isset($_POST['productKind'])) {
    $postId = $_POST['postId'];
    $productKind = $_POST['productKind'];
    
    $servername = "localhost";
    $dbname = "zaycho";
    $mysqli = new mysqli($servername, "root", "", $dbname);

    if ($mysqli->connect_errno) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    // Prepare and execute the delete query
    $query = "DELETE FROM `$productKind` WHERE id = ?";
    $stmt = $mysqli->prepare($query);

    if ($stmt) {
        $stmt->bind_param('i', $postId);
        if ($stmt->execute()) {
            echo "success";
        } else {
            echo "Database error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Query preparation failed: " . $mysqli->error;
    }

    $mysqli->close();
} else {
    echo "Missing postId or productKind.";
}
?>
