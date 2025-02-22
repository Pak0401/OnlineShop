<?php
// 設定資料庫連線資訊
$host = "localhost"; // 你的 MySQL 主機
$dbname = "orderdata"; // 你的資料庫名稱
$username = "root"; // MySQL 帳號（WAMP 預設為 root）
$password = ""; // MySQL 密碼（如果沒有設定則留空）

try {
    // 建立 PDO 連線
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("資料庫連接失敗: " . $e->getMessage());
}

// 查詢 `orders` 表
$sql = "SELECT order_id, UID, status, shipment_status, created_at FROM orders ORDER BY created_at DESC";
$result = $pdo->query($sql);

// 轉換為陣列
$orders = $result->fetchAll(PDO::FETCH_ASSOC);
?>
