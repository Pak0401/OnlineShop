<?php
// 資料庫連線設定（userdata）
$host = "localhost";   
$username = "root";    
$password = "";        
$database = "userdata"; 

try {
    // 建立 PDO 連線
    $conn_userdata = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // 啟用錯誤模式
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // 預設關聯數組
        PDO::ATTR_EMULATE_PREPARES => false // 禁用模擬準備語句，提高安全性
    ]);
} catch (PDOException $e) {
    die("❌ userdata 資料庫連線失敗：" . $e->getMessage());
}

// **將變數設為全域可用**
$GLOBALS['conn_userdata'] = $conn_userdata;

// 確保購物車已初始化
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// 檢查用戶是否已登入
$is_logged_in = isset($_SESSION['uid']);

?>