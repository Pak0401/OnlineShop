<?php
session_start();
require "../Order-db.php"; // 確保連接資料庫

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 檢查是否為 POST 請求
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["status" => "error", "message" => "請使用 POST 請求"]);
    exit;
}

// 🔍 檢查收到的 POST 資料
if (empty($_POST)) {
    echo json_encode(["status" => "error", "message" => "未收到 POST 資料", "debug" => $_POST]);
    exit;
}

// 取得訂單 ID
$order_id = $_POST['order_id'] ?? null;

if (!$order_id) {
    echo json_encode(["status" => "error", "message" => "缺少訂單 ID"]);
    exit;
}

try {
    // 檢查 `order_id` 是否已存在
    $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM orders WHERE order_id = ?");
    $checkStmt->execute([$order_id]);
    $orderExists = $checkStmt->fetchColumn();

    if ($orderExists) {
        echo json_encode(["status" => "error", "message" => "訂單 ID 已存在"]);
        exit;
    }

    // 插入新訂單
    $stmt = $pdo->prepare("INSERT INTO orders (order_id, status, shipment_status, created_at) VALUES (?, '未付款', '未發貨', NOW())");
    $stmt->execute([$order_id]);

    echo json_encode(["status" => "success", "message" => "訂單已成功存入"]);
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => "存入失敗：" . $e->getMessage()]);
}
?>
