<?php
session_start();

// 檢查 `user_role`
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    die("❌ 您沒有權限訪問此頁面！");
}

echo "<h1>歡迎來到後台管理系統</h1>";
?>
