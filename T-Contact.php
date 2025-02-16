<?php
session_start(); // 啟用 Session
require 'User-db.php'; // 載入用戶資料庫連線
require 'Prod-db.php'; // 載入商品資料庫連線

// 確保購物車已初始化
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$is_logged_in = isset($_SESSION['uid']); // 檢查是否已登入
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>聯絡我們</title>
    <link rel="stylesheet" href="css/Style.css">
    <link rel="stylesheet" href="Test.css">
    <link rel="stylesheet" href="css/Chat.css">
</head>
<body>
    <!-- 圖文格式 -->
    <div class="container">
        <div class="navbar">
            <!-- Logo -->
            <div class="logo">
                <img src="image/logo.png" width="125px">
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

    <script src="Script/botResponse.js"></script>
    <script src="Script/Script.js"></script>

    <div class="contact-page">
        <!-- 查詢 -->
        <div class="contact-section">
            <h3><i class="fa fa-phone" style="color: #007BFF;"></i> 查詢</h3>
            <p>
                <i class="fa fa-phone"></i> 熱線 | Hot Line<br>
                電話: +852 1234 5678<br>
                <i class="fa fa-envelope"></i> 電郵地址 | E-Mail<br>
                76tw4@example.com<br>
                <i class="fa fa-whatsapp"></i> Whatsapp: +852 1234 5678
            </p>
        </div>

         <!-- 地址 -->
        <div class="contact-section">
            <h3>地址</h3>
            <p>
                香港九龍區鑽石山xx路xx號<br>
                xx, xxxxxx Road, Diamond Hill, Kowloon, Hong Kong
            </p>
        </div>

        <!-- 營業時間 -->
        <div class="contact-section">
            <h3>營業時間</h3>
            <p>
                星期一至五 | 10:00 – 20:00<br>
                星期六 | 10:00 – 17:00<br>
                星期日及公眾假期休息
            </p>
        </div>

        <!-- 追蹤我們 -->
        <div class="contact-section">
            <h3>追蹤我們</h3>
            <div class="social-icons">
                <a href="#"><i class="fa fa-facebook"></i> Facebook</a>
                <a href="#"><i class="fa fa-instagram"></i> Instagram</a>
                <a href="#"><i class="fa fa-whatsapp"></i> Whatsapp</a>
            </div>
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