<?php
require "../Order-db.php"; // 連接資料庫

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $orderId = $_GET['order_id'];

    $stmt = $conn_orders->prepare("DELETE FROM orders WHERE order_id = :order_id");
    $stmt->bindParam(':order_id', $orderId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
}
?>
