<?php
session_start();
require "../Inventory-db.php";
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
            <h3>庫存表</h3>
            <table id="inventoryTable">
                <thead>
                    <tr>
                        <th>商品ID</th>
                        <th>商品名稱</th>
                        <th>數量</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($inventory as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['PID']); ?></td>
                            <td><?php echo htmlspecialchars($row['PName']); ?></td>
                            <td><?php echo htmlspecialchars($row['Quantity']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <form id="editInventoryForm">
            <label for="selectPID">選擇產品：</label>
            <select id="selectPID">
                <?php foreach ($inventory as $row): ?>
                    <option value="<?php echo htmlspecialchars($row['PID']); ?>" 
                            data-product-name="<?php echo htmlspecialchars($row['PName']); ?>"
                            data-quantity="<?php echo htmlspecialchars($row['Quantity']); ?>">
                        <?php echo htmlspecialchars($row['PID'] . " - " . $row['PName']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="Quantity">數量：</label>
            <input type="number" id="Quantity" name="Quantity" required>

            <button type="button" id="updateInventoryBtn">確定</button>
        </form>
    </div>
    <script src="../Script/Backend.js"></script>
    <script src="../Script/Inventory.js"></script>
</body>
</html>