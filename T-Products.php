<?php
session_start();
require 'Prod-db.php'; // 載入商品資料庫連線

// 確保 `conn_productdata` 連線正常
if (!isset($conn_productdata)) {
    die("❌ 資料庫連線錯誤，請檢查 Prod-db.php");
}

// 獲取所有產品數據
$sql = "SELECT * FROM productdata";
$stmt = $conn_productdata->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 整理產品數據，合併相同主名稱的產品
$products = [];
foreach ($result as $row) {
    // 提取主名稱（假設名稱和規格用 "-" 分隔）
    $mainName = explode('-', $row['PName'])[0];
    if (!isset($products[$mainName])) {
        $products[$mainName] = [
            'name' => $mainName,
            'minPrice' => $row['Price'],
            'maxPrice' => $row['Price'],
            'pid' => $row['PID'], // 保存主要產品的 PID
            'variants' => 1
        ];
    } else {
        // 更新價格範圍和變體數量
        $products[$mainName]['minPrice'] = min($products[$mainName]['minPrice'], $row['Price']);
        $products[$mainName]['maxPrice'] = max($products[$mainName]['maxPrice'], $row['Price']);
        $products[$mainName]['variants'] += 1;
    }
}

// 檢查是否已登入
$is_logged_in = isset($_SESSION['uid']);

// 確保購物車已初始化
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OnlineShop | Products</title>
    <link rel="stylesheet" href="css/Style.css">
    <link rel="stylesheet" href="css/Products.css">
    <link rel="stylesheet" href="css/Chat.css">
</head>
<body>
    <!-- 圖文格式 -->
    <div class="container">
        <div class="navbar">
            <div class="logo">
                <img src="image/logo.png" width="125px">
            </div>
            <nav>
                <ul id="menuItems">
                    <li><a href="T-Index.php">主頁</a></li>
                    <li><a href="T-Products.php">產品</a></li>
                    <li><a href="T-Contact.php">聯絡我們</a></li>
                    <li><a href="T-About.php">關於我們</a></li>
                    <?php
                    if (isset($_SESSION['uid'])) {
                        // 如果已登入，顯示賬戶連結
                        echo '<li><a href="T-Account.php">賬戶</a></li>';
                    } else {
                        // 如果未登入，顯示登入連結
                        echo '<li><a href="T-Login.php">登入</a></li>';
                    }
                    ?>
                </ul>
            </nav>
            <a href="T-Cart.php">
                <img src="image/cart.png" width="40px" height="40px">
                <span id="cart-count"><?php echo count($_SESSION['cart']); ?></span>
            </a>
            <img src="image/menu.png" class="menu-icon" onclick="menutoggle()">
        </div>
    </div>

    <!-- 手機菜單的腳本 -->
    <script>
        var menuItems = document.getElementById("menuItems");

        menuItems.style.maxHeight = "0px";

        function menutoggle(){
            if(menuItems.style.maxHeight == "0px"){
                menuItems.style.maxHeight = "250px";
            }
            else{
                menuItems.style.maxHeight = "0px";
            }
        }
    </script>

    <!-- 客服 -->
    <div class="service">
        <!-- 聊天按鈕 -->
        <div id="chatButton">聯絡客服</div>

            <!-- 聊天室窗口 -->
            <div id="chatBox">
                <div id="chatBoxHeader">
                    聊天室
                    <span id="closeButton">&times;</span>
                </div>
                <div id="chatContent">
                    <p>歡迎來到客服聊天室! 有問題隨時詢問我們!</p>
                </div>
                <div id="chatInputArea">
                    <input type="text" id="chatInput" placeholder="輸入一則訊息...">
                    <button id="sendButton">發送</button>
                </div>
            </div>
    </div>

    <script src="botResponse.js"></script>
    <script src="Script.js"></script>

    <!-- 所有產品+排序 -->
    <div class="small-container">
        <div class="row row-2">
            <h2><b>所有產品</b></h2>
            <select>
                <option>預設排序</option>
                <option>按價格排序</option>
                <option>按人氣排序</option>
                <option>按評分排序</option>
                <option>按銷量排序</option>
            </select>
        </div>
        <!-- 動態生成產品列表 -->
        <div class="row">
            <?php
            foreach ($products as $product) {
                echo "<div class='col-pd-5'>";
                echo "<a href='T-Product-Details.php?pid=" . $product['pid'] . "'>";
                echo "<img src='image/" . strtolower(str_replace(' ', '_', htmlspecialchars($product['name']))) . ".png' alt='" . htmlspecialchars($product['name']) . "'>";
                echo "</a>";
                echo "<h3><b>" . htmlspecialchars($product['name']) . "</b></h3>";
                echo "<div class='rating'>";

                // **調整價格顯示**
                $minPrice = number_format($product['minPrice']);
                $maxPrice = number_format($product['maxPrice']);

                if ($minPrice == $maxPrice) {
                    echo "<p>$" . $minPrice . "</p>"; // 單一價格
                } else {
                    echo "<p>$" . $minPrice . " - $" . $maxPrice . "</p>"; // 價格範圍
                }

                echo "<p class='hreat'><span style='color: red;'>&#9829</span> " . rand(50, 200) . "</p>";
                echo "</div>";
                echo "</div>";
            }
            ?>
        </div>
    </div>

    <!-- 頁數 -->
    <div class="page-btn">
        <span>1</span>
        <span>2</span>
        <span>3</span>
        <span>4</span>
        <span>&#8594;</span>
    </div>

    <!-- 頁尾 -->
    <div class="footer">
        <div class="footer-container">
            <div class="footer-row">
                <div class="footer-col-1">
                    <h3><b>關於我們</b></h3>
                    <p>我們是一群熱愛毛孩的人，希望毛孩都能有幸福美滿的生活<br>
                    在此提供毛孩的日用品，為毛孩提供最好的生活，並希望領養代替購買。</p>
                </div>
                <div class="footer-col-2">
                    <h3><b>聯絡我們</b></h3>
                    <p>電話: +852 1234 5678</p>
                    <p>Whatsapp: +852 1234 5678</p>
                    <p>電郵: 7t6w4@example.com</p>
                    <p>地址: 香港九龍區鑽石山xx路xx號</p>
                </div>
                <div class="footer-col-3">
                    <h3><b>社群鏈接</b></h3>
                    <ul>
                        <li>Facebook</li>
                        <li>Instagram</li>
                    </ul>
                </div>
                <div class="footer-col-4">
                    <h3><b>服務鏈接</b></h3>
                    <ul>
                        <li>退款申請</li>
                        <li>加入我們</li>
                    </ul>
                </div>
            </div>
            <hr>
            <p class="copyright">© 2022 All Rights Reserved.</p>
        </div>
    </div>
</body>
</html>