<?php
// 設定資料庫連線
$host = "localhost";
$dbname = "product_data"; // 確保這裡的資料庫名稱正確
$username = "root"; // WAMP 預設 MySQL 帳號
$password = ""; // 如果沒有設密碼，應該是空字串

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("資料庫連接失敗: " . $e->getMessage());
}

// 查詢 `productdata` 資料表
$sql = "SELECT * FROM productdata";
$result = $pdo->query($sql);
?>
