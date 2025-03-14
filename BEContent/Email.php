<?php
session_start();
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理後台</title>
    <link rel="stylesheet" href="../css/Backend.css">
</head>
<body>

<!-- 頂部導航欄 -->
<div class="top-bar">
    <div class="logo"><img src="../image/logo.png" style="width: 100%"></div>
    <h1>後台管理系統</h1>
    <a href="../T-Index.php" class="back-button">返回首頁</a>
</div>

<!-- 側邊欄 -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <button class="toggle-sidebar" onclick="toggleSidebar()">❮</button>
    </div>
    <ul class="menu">
        <li><button class="sidebar-btn" onclick="window.location.href='Product-Be.php'"><span>管理數據庫</span></button></li>
        <li><button class="sidebar-btn" onclick="window.location.href='Order.php'"><span>訂單管理</span></button></li>
        <li><button class="sidebar-btn" onclick="window.location.href='Inventory.php'"><span>庫存表</span></button></li>
        <li><button class="sidebar-btn" onclick="window.location.href='Email.php'"><span>發送郵件</span></button></li>
    </ul>
</div>

<!-- 主要內容區域 -->
<div class="main-content">
    <h2 id="page-title">後台管理</h2>
    <div id="content-area">
        <h3>發送郵件</h3>
        <form action="send-email.php" method="post">
            <label for="email">收件人：</label>
            <input type="email" id="email" name="email" required>
            <label for="subject">主旨：</label>
            <input type="text" id="subject" name="subject" required>
            <label for="message">內容：</label>
            <textarea id="message" name="message" required></textarea>
            <button type="submit">發送</button>
        </form>
    </div>
    <h1>尚未完成</h1>
</div>
<script src="../Script/Backend.js"></script>
