<?php
header("Content-Type: application/json");
session_start();
require_once "../Order-db.php"; // 連接資料庫

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['order_id'], $data['user_id'], $data['items'], $data['total_price'])) {
    echo json_encode(["status" => "error", "message" => "缺少必要參數"]);
    exit;
}

$order_id = $data['order_id'];
$user_id = $data['user_id'];
$total_price = $data['total_price'];
$items = json_encode($data['items'], JSON_UNESCAPED_UNICODE);
$status = "未付款";
$shipment_status = "未發貨";
$created_at = date("Y-m-d H:i:s");

// 插入訂單
$sql = "INSERT INTO orders (order_id, UID, items, total_price, status, shipment_status, created_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sisssss", $order_id, $user_id, $items, $total_price, $status, $shipment_status, $created_at);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "訂單提交成功"]);
} else {
    echo json_encode(["status" => "error", "message" => "資料庫錯誤: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
