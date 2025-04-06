<?php
$servername = "localhost";
$dbname = "zaycho";
$mysqli = new mysqli($servername, "root", "", $dbname);

if ($mysqli->connect_errno) {
    die("Connection failed: " . $mysqli->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if file is uploaded
    if (isset($_FILES['voucher']) && $_FILES['voucher']['error'] == 0) {
        $uploadDir = 'uploads/';
        $uploadFile = $uploadDir . basename($_FILES['voucher']['name']);
        
        // Move the uploaded file to the server directory
        if (move_uploaded_file($_FILES['voucher']['tmp_name'], $uploadFile)) {
            // Prepare SQL to insert file path into database
            $sql = "INSERT INTO voucher_table (voucher_path, upload_time) VALUES (?, NOW())";
            
            if ($stmt = $mysqli->prepare($sql)) {
                $stmt->bind_param('s', $uploadFile);

                if ($stmt->execute()) {
                    echo "Payment Success! Voucher uploaded successfully.";
                } else {
                    echo "Error: " . $stmt->error;
                }
                
                $stmt->close();
            } else {
                echo "Error: " . $mysqli->error;
            }
        } else {
            echo "Error uploading file.";
        }
    } else {
        echo "No file uploaded or an error occurred.";
    }
}
?>
