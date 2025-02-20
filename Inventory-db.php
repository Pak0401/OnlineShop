<?php
// 設定資料庫連線
$host = "localhost";
$dbname = "storage"; // 確保這裡的資料庫名稱正確
$username = "root"; 
$password = ""; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("資料庫連接失敗: " . $e->getMessage());
}

// 查詢 `inventorydata` 資料表
$sql = "SELECT PID, PName, Quantity FROM inventorydata";
$result = $pdo->query($sql);

// 轉換為陣列
$inventory = $result->fetchAll(PDO::FETCH_ASSOC);
?>
