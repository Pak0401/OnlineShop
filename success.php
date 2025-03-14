<?php
// 首先，您需要修改 orders 資料表結構，增加一個名為 items_text 的 TEXT 欄位

// 然後在 success.php 中這樣修改訂單處理
session_start();
include 'Order-db.php'; // 載入資料庫連線

if (!isset($conn)) {
    die("資料庫連線錯誤");
}

// 確保購物車不為空
if (!empty($_SESSION["cart"])) {
    // 將購物車項目轉換為簡化的文字格式
    $items_text = "";
    $total = 0;
    
    foreach ($_SESSION["cart"] as $item) {
        $subtotal = $item["price"] * $item["quantity"];
        $total += $subtotal;
        
        // 如果不是第一個項目，添加分隔符
        if ($items_text != "") {
            $items_text .= ", ";
        }
        
        // 格式: PID - 產品名稱 * 數量
        $items_text .= $item["pid"] . " - " . $item["name"] . " * " . $item["quantity"];
    }
    
    // 生成訂單編號
    $order_id = "ORD" . time() . rand(1000, 9999);

    // 獲取用戶 ID
    $user_id = isset($_SESSION['uid']) ? $_SESSION['uid'] : null;

    // 設定初始狀態
    $status = '已付款'; 
    $shipment_status = '未發貨';

    // 修改 SQL 語句，將購物車資料作為文字儲存
    $stmt = $conn->prepare("INSERT INTO orders (order_id, UID, status, shipment_status, total_price, items_text, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
    if (!$stmt) {
        die("SQL 準備錯誤: " . $conn->error);
    }
    $stmt->bind_param("sissds", $order_id, $user_id, $status, $shipment_status, $total, $items_text);

    // 執行訂單插入
    if ($stmt->execute()) {
        $_SESSION["last_order_id"] = $order_id; // 儲存訂單編號供前端顯示
        
        // 在成功儲存後才清空購物車
        unset($_SESSION['cart']); // 清空購物車
    } else {
        die("訂單插入錯誤: " . $stmt->error);
    }

    // 關閉 SQL 連線
    $stmt->close();
    $conn->close();
} else {
    // 購物車是空的，記錄錯誤
    error_log("結帳時購物車為空");
}
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stripe 付款</title>
    <script src="https://js.stripe.com/v3/"></script>
    <link rel="stylesheet" href="css/Payment.css">
    <link rel="stylesheet" href="css/Style.css">
    <link rel="stylesheet" href="css/Chat.css">
    <link rel="stylesheet" href="css/About.css">
</head>
<body>
    <!-- 圖文格式 -->
    <div class="container">
        <div class="navbar">
            <!-- Logo -->
            <div class="logo">
                <img src="image/logo.PNg" width="125px">
            </div>
            <img scr="image/menu.png">
            <!-- 橫向菜單 -->
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
                <span id="cart-count">
                    <?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>
                </span>
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

    <script src="Script/botResponse.js"></script>
    <script src="Script/Script.js"></script>

    <div class="main-container">
        <div class="payment-container">
            <h2>付款成功！</h2>
            <p>您的訂單已成功支付。</p>
            <p>訂單編號： <span id="orderIdDisplay">
                <?php echo isset($_SESSION["last_order_id"]) ? $_SESSION["last_order_id"] : "無訂單"; ?>
            </span></p> </br>
            <button class="cancel-btn" onclick="window.location.href='T-Index.php'">
                <span>返回購物車</span>
            </button>
        </div>
    </div>


    <!-- 頁尾 -->
    <div class="footer">
        <div class="footer-container">
            <!-- 避免row的部分出問題，另外分開了 -->
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
