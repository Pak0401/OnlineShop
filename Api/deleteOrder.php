<?php
require_once '../Order-db.php'; // 連接資料庫

header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['order_id'])) {
    echo json_encode(["success" => false, "message" => "缺少訂單 ID"]);
    exit;
}

$order_id = $data['order_id'];

try {
    $stmt = $pdo->prepare("DELETE FROM orders WHERE order_id = :order_id");
    $stmt->bindParam(':order_id', $order_id, PDO::PARAM_STR);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "刪除失敗"]);
    }
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "錯誤: " . $e->getMessage()]);
}
?>
