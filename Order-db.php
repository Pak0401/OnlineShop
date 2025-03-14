<?php
// 設定資料庫連線資訊
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "orderdata";

// 建立 MySQLi 連線
$conn = new mysqli($servername, $username, $password, $dbname);

// 檢查連線是否成功
if ($conn->connect_error) {
    die("資料庫連線失敗: " . $conn->connect_error);
}
$conn->set_charset("utf8");

// 查詢 `orders` 表
$sql = "SELECT order_id, UID, items_text, total_price, status, shipment_status, created_at FROM orders ORDER BY created_at DESC";
$result = $conn->query($sql);

// 轉換為陣列
$orders = [];
while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
}

// 釋放結果集
$result->free();
?>
