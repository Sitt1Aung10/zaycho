if ($confirmStmt->execute()) {
    // Update stock quantity after confirmation
    $updateStockQuery = "UPDATE $postKind SET instock_amount = instock_amount - ? WHERE id = ? AND instock_amount >= ?";
    $updateStmt = $mysqli->prepare($updateStockQuery);
    $updateStmt->bind_param("iii", $quantity, $postId, $quantity);
    
    if ($updateStmt->execute() && $updateStmt->affected_rows > 0) {
        echo 'Order confirmation notification sent successfully!';
    } else {
        echo 'Insufficient stock for this order.';
    }

} else {
    echo 'Error inserting order confirmation: ' . $confirmStmt->error;
}