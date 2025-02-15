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
    <title>OnlineShop | About</title>
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

    <!-- 關於我們 -->
    <div class="abt-container">
        <div class="abt-text">
            <h1>關於我們</h1><hr><br>
            <p>我們是一個專注於寵物用品的公司，致力於為寵物和它們的主人提供最好的服務和產品。 我們的目標是為寵物提供高品質的生活，讓每一隻寵物都能健康、幸福地成長。</p>
            <p>我們提供各種各樣的寵物食品、玩具和日常用品。每一件產品都經過嚴格的測試，確保符合安全標準。</p>
            <p>我們明瞭作為寵物主人的你，於工作的壓力及生活的緊張下，已經忙得分身不暇，餘下的時間亦想跟心愛的寵物多些相聚及溝通，實在不能花太多的時間外出四處尋找優質的寵物產品，
               亦因此，我們為各位寵物家長篩選世界各地各式各樣的優質產品，將好的留低，將差的淘汰，令到各位寵物主人們買得放心，用得安心，這就是我們的信念及承諾。</p>
            <p>我們保證，決不為謀取暴利而售賣危害寵物健康的產品，務求以全線健康的寵物產品，為你的寵物帶來健康及全面的呵護，寵物食品由原產地入口，經本地特約代理分銷，
               信心十足，我們不會將水貨混合行貨出售，以不正當手法，欺騙顧客。我們將歇力地為大家搜羅世界各地的高質素產品，集合在我們的網店，令你能夠足不出戶，就能夠安心又放心地挑選心儀的產品。</p>
                
        </div>

        <!-- 轉換圖片 -->
        <div class="abtslide-container">
            <div class="abtslide fade">
                <img src="image/cat.png" style="width:100%">
            </div>
            <div class="abtslide fade">
                <img src="image/cat2.png" style="width:100%">
            </div>
            <div class="abtslide fade">
                <img src="image/cat3.png" style="width:100%">
            </div>
        </div>
    </div>

    <!-- 轉換圖片的腳本 -->
    <script>
        let slideIndex = 0;
        showSlides();

        function showSlides() {
            let i;
            let slides = document.getElementsByClassName("abtslide");
            for (i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
            }
            slideIndex++;
            if (slideIndex > slides.length) {
                slideIndex = 1;
            }
            slides[slideIndex-1].style.display = "block";
            setTimeout(showSlides, 3000); // 三秒自動換一張圖
        }
    </script>

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