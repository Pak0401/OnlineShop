<?php
session_start();

// 確保只有 admin 可進入
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: T-Login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理後台</title>
    <link rel="stylesheet" href="css/Backend.css">
</head>
<body>

<!-- 頂部導航欄 -->
<div class="top-bar">
    <div class="logo"><img src="image/logo.png" style="width: 100%"></div>
    <h1>後台管理系統</h1>
    <a href="T-Index.php" class="back-button">返回首頁</a>
</div>

<!-- 側邊欄 -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <button class="toggle-sidebar" onclick="toggleSidebar()">❮</button>
    </div>
    <ul class="menu">
        <li><button class="sidebar-btn" onclick="window.location.href='BEContent/Product-Be.php'"><span>管理數據庫</span></button></li>
        <li><button class="sidebar-btn" onclick="window.location.href='BEContent/Order.php'"><span>訂單管理</span></button></li>
        <li><button class="sidebar-btn" onclick="window.location.href='BEContent/Inventory.php'"><span>庫存表</span></button></li>
        <li><button class="sidebar-btn" onclick="window.location.href='BEContent/Email.php'"><span>發送郵件</span></button></li>
    </ul>
</div>

<!-- 主要內容區域 -->
<div class="main-content">
    <h2 id="page-title">後台管理</h2>
    <div id="content-area">
        <h3>歡迎你，管理員來到後台管理</h3>
        <p>請選擇左側功能來管理後台。</p>
    </div>
</div>

<script src="Script/Backend.js"></script>
</body>
</html>
