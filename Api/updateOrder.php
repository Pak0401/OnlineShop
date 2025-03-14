<?php
session_start();
require "../Item-db.php";
header('Content-Type: application/json');

try {
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $order_id = $_POST['order_id'] ?? null;
        $new_status = $_POST['status'] ?? null;
        $new_shipment_status = $_POST['shipment_status'] ?? null;

        if (!$order_id || !$new_status || !$new_shipment_status) {
            echo json_encode(["status" => "error", "message" => "缺少必要的參數"]);
            exit;
        }

        $stmt = $pdo->prepare("UPDATE orders SET status = ?, shipment_status = ? WHERE order_id = ?");
        $stmt->execute([$new_status, $new_shipment_status, $order_id]);

        echo json_encode(["status" => "success", "message" => "訂單已更新"]);
    } else {
        echo json_encode(["status" => "error", "message" => "無效的請求"]);
    }
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "資料庫錯誤：" . $e->getMessage()]);
}
?>