<?php
// 設定資料庫連線資訊
$host = "localhost"; 
$dbname = "orderdata"; 
$username = "root"; 
$password = ""; 

try {
    // 建立 PDO 連線 - 確保 $pdo 是全域變數
    global $pdo;
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // 查詢 orders 表
    $stmt = $pdo->prepare("SELECT order_id, UID, items_text, total_price, status, shipment_status, created_at FROM orders ORDER BY created_at DESC");
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    die("資料庫連接失敗: " . $e->getMessage());
}
?>