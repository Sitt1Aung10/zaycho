<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $kind = $_POST['kind'];
    $about_product = $_POST['about_product'];
    $price = $_POST['price'];
    $instock_amount = $_POST['instock_amount'];

    $servername = "localhost";
    $dbname = "zaycho";
    $mysqli = new mysqli($servername, "root", "", $dbname);

    if ($mysqli->connect_errno) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    // Prepare the update query
    $query = "UPDATE `$kind` SET about_product = ?, price = ?, instock_amount = ? WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("sdii", $about_product, $price, $instock_amount, $id);

    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }

    $stmt->close();
    $mysqli->close();
}
?>
