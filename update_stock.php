<?php
header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['id']) || !isset($data['kind'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid data provided.']);
    exit;
}

$id = $data['id'];
$productKind = $data['kind'];

$mysqli = new mysqli('localhost', 'root', '', 'zaycho');

if ($mysqli->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed.']);
    exit;
}

// Update the instock_amount for the product
$updateQuery = "UPDATE `$productKind` SET instock_amount = instock_amount - 1 WHERE id = ? AND instock_amount > 0";
$stmt = $mysqli->prepare($updateQuery);
$stmt->bind_param('i', $id);

if ($stmt->execute() && $stmt->affected_rows > 0) {
    echo json_encode(['success' => true, 'message' => 'Stock updated successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update stock or product is out of stock.']);
}

$stmt->close();
$mysqli->close();
?>
