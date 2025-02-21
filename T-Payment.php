<?php
session_start();
require 'Prod-db.php'; // 載入商品資料庫連線

// 檢查購物車是否有內容
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

// 計算購物車總額
$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $subtotal = floatval($item['price']) * intval($item['quantity']);
    $total += $subtotal;
}

// 確保總金額正確
if ($total <= 0) {
    die("購物車為空，無法結帳！");
}

// 確保 Stripe 環境
$stripePublicKey = "pk_test_51QQkF4JTvI7Ka6t7ixrWxfzNrutdimkSHB64XvDjNhq75VNsT0oKGDKQS86DnCHFkNGBHinQjpQMJWZ7ZIlBAbxe00CQfEKBjB"; 
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
            <h2>訂單確認</h2>
            <table class="order-summary">
            <p>您的訂單已建立，訂單編號如下：</p>
            <p id="orderIdDisplay"></p></br>
            <table class="order-summary">
                <tr>
                    <th>產品名稱</th>
                    <th>數量</th>
                    <th>價格</th>
                </tr>
                <?php
                if (!empty($_SESSION['cart'])) {
                    foreach ($_SESSION['cart'] as $item) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($item['name']) . "</td>";
                        echo "<td>" . intval($item['quantity']) . "</td>";
                        echo "<td>$" . number_format($item['price'] * $item['quantity'], 2) . "</td>";
                        echo "</tr>";
                    }
                } 
                ?>

                <tr>
                    <th>總計</th>
                    <td>$<span id="total-price"><?php echo number_format($total); ?></span></td>
                </tr>

                <tr>
                    <th>運費</th>
                    <td>免費</td>
                </tr>
            </table>

            <!-- Stripe 付款按鈕 -->
            <button id="checkout-button" class="btn">前往付款</button>
        </div>
    </div>

    <!-- <script>
        const stripe = Stripe("<?php echo $stripePublicKey; ?>");

        document.getElementById("checkout-button").addEventListener("click", function() {
            // 透過 AJAX 傳遞購物車數據
            fetch("create-checkout-session.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({
                    cart: <?php echo json_encode($_SESSION['checkout_cart']); ?>, // 把購物車數據轉為 JSON
                    currency: "hkd"
                })
            })
            .then(response => response.json())
            .then(session => {
                return stripe.redirectToCheckout({ sessionId: session.id });
            })
            .catch(error => console.error("錯誤:", error));
        });
    </script> -->
    <script>
        let userId = <?= json_encode($_SESSION['UID'] ?? 0); ?>;
        let cart = <?= json_encode($_SESSION['cart'] ?? []); ?>;
    </script>
    <script src="Script/orderNo.js"></script>
    <script src="Script/SaveOrder.js"></script>


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