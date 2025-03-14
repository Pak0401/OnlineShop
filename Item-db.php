<?php
// 設定資料庫連線資訊
$host = "localhost"; 
$dbname = "orderdata"; 
$username = "root"; 
$password = ""; 

try {
    // 建立 PDO 連線
    $pdo = new PDO("mysql:host=$host;charset=utf8", $username, $password);
    $pdo->exec("USE orderdata");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("資料庫連接失敗: " . $e->getMessage());
}

// 查詢 orders 表 (使用新增的 items_text 欄位)
$sql = "SELECT order_id, UID, items_text, total_price, status, shipment_status, created_at
        FROM orderdata.orders 
        ORDER BY created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>