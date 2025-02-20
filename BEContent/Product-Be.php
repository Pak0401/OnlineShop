<?php
session_start();
require "../ProdBe-db.php";
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
        
        <h3>管理數據庫</h3>
        <table id="productTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>產品名稱</th>
                    <th>價格</th>
                    <th>變體</th>
                    <th>GID</th>
                    <th>描述</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?php echo $row['PID']; ?></td>
                        <td><?php echo $row['PName']; ?></td>
                        <td><?php echo $row['Price']; ?></td>
                        <td><?php echo $row['Variant']; ?></td>
                        <td><?php echo $row['GID']; ?></td>
                        <td><?php echo $row['Description']; ?></td>
                        <td>
                            <button class="delete-btn" data-pid="<?php echo htmlspecialchars($row['PID']); ?>">刪除</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table></br>

        <h3>新增產品</h3>
        <form id="addProductForm">
            <label>產品名稱: <input type="text" name="pname" required></label>
            <label>價格: <input type="number" name="price" required></label>
            <label>變體: <input type="text" name="variant"></label>
            <label>GID: <input type="text" name="gid"></label>
            <label>描述: <textarea id="text" name="description" required></textarea></label>
            <button type="submit">新增產品</button>
        </form>
        <p id="responseMessage"></p>

    </div>
</div>

<script src="../Script/Backend.js"></script>
<script src="../Script/ProductBe.js"></script>
<script src="../script/DelProduct.js"></script>
</body>
</html>
