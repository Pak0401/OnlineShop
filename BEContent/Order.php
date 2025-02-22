<?php
session_start();
require "../Order-db.php";
require "../Item-db.php";
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
    <a href="T-Index.php" class="back-button">返回首頁</a>
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
        <h3>訂單管理</h3>
        <table id="orderTable">
            <thead>
                <tr>
                    <th>訂單編號</th>
                    <th>用戶編號</th>
                    <th>商品</th>
                    <th>總價</th>
                    <th>狀態</th>
                    <th>運送狀態</th>
                    <th>下單時間</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($orders)): ?>
                    <?php foreach ($orders as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['order_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['UID']); ?></td>
                            <td><?php echo htmlspecialchars($row['items']); ?></td>  <!-- 顯示商品 -->
                            <td>$<?php echo number_format($row['total_price']); ?></td>  <!-- 顯示總價 -->
                            <td><?php echo htmlspecialchars($row['status']); ?></td>
                            <td><?php echo htmlspecialchars($row['shipment_status']); ?></td>
                            <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                            <td>
                                <button class="delete-btn" data-order_id="<?php echo htmlspecialchars($row['order_id']); ?>">刪除</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="8" style="color: red;">沒有訂單資料</td></tr>
                <?php endif; ?>
            </tbody>
        </table></br>

        <h3>更新訂單狀態</h3>
            <form id="updateOrderForm">
                <!-- 選擇訂單 -->
                <label for="selectOrderID">選擇訂單編號：</label>
                <select id="selectOrderID">
                    <?php foreach ($orders as $row): ?>
                        <option value="<?php echo htmlspecialchars($row['order_id']); ?>">
                            <?php echo htmlspecialchars($row['order_id']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <!-- 訂單狀態 -->
                <label for="newStatus">選擇新訂單狀態：</label>
                <select id="newStatus">
                    <option value="未付款">未付款</option>
                    <option value="已付款">已付款</option>
                    <option value="取消">取消</option>
                </select>

                <!-- 運送狀態 -->
                <label for="newShipmentStatus">選擇新運送狀態：</label>
                <select id="newShipmentStatus">
                    <option value="未發貨">未發貨</option>
                    <option value="已發貨">已發貨</option>
                </select>

                <!-- 更新按鈕 -->
                <button type="submit" id="UpdateO-Btn">更新訂單</button>
            </form>

        <!-- 訊息回應 -->
        <p id="updateResponseMessage"></p>
    </div>
</div>

<script src="../Script/Backend.js"></script>
<script src="../Script/Order.js"></script>
<script src="../Script/DelOrder.js"></script>
</body>
</html>
