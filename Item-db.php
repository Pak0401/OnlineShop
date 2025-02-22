<?php
// 設定資料庫連線資訊
$host = "localhost"; // 你的 MySQL 主機
$dbname = "orderdata"; // 你的資料庫名稱
$username = "root"; // MySQL 帳號（WAMP 預設為 root）
$password = ""; // MySQL 密碼（如果沒有設定則留空）

try {
    // 建立 PDO 連線
    $pdo = new PDO("mysql:host=$host;charset=utf8", $username, $password);
    $pdo->exec("USE orderdata");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("資料庫連接失敗: " . $e->getMessage());
}

// 查詢 `order_items` 表
$sql = "SELECT o.order_id, o.UID, o.status, o.shipment_status, o.created_at, 
        COALESCE(GROUP_CONCAT(CONCAT(oi.PID, 'x', oi.quantity, '(', p.PName, 'x', oi.quantity, ')') SEPARATOR ', '), '無商品') AS items, 
        COALESCE(SUM(oi.subtotal)) AS total_price
        FROM orderdata.orders o
        LEFT JOIN orderdata.order_items oi ON o.order_id = oi.order_id
        LEFT JOIN product_data.productdata p ON oi.PID = p.PID 
        GROUP BY o.order_id
        ORDER BY o.created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
