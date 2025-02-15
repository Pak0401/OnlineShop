<?php
session_start();

// 載入兩個資料庫連線
require 'User-db.php';
require 'Prod-db.php';

// 確保兩個連線都已經建立
if (!isset($conn_userdata) || !isset($conn_productdata)) {
    die("❌ 資料庫連線錯誤，請檢查 db.php 檔案");
}

// 從 productdata 查詢推薦產品
$sql = "
    SELECT 
        productdata.GID,
        MIN(productdata.Price) AS MinPrice,
        MAX(productdata.Price) AS MaxPrice,
        productdata.PID,
        productdata.PName,
        productdata.Variant,
        productdata.Description,
        featured_pdata.FPName
    FROM 
        productdata
    INNER JOIN 
        featured_pdata
    ON 
        productdata.GID = featured_pdata.GID
    GROUP BY 
        productdata.GID
    LIMIT 4;
";

$stmt = $conn_productdata->prepare($sql);
$stmt->execute();
$recommendedProducts = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OnlineShop | Index</title>
    <link rel="stylesheet" href="css/Style.css">
    <link rel="stylesheet" href="css/Chat.css">
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
                    <?php if (!empty($_SESSION['uid'])): ?>
                        <li><a href="T-Account.php">賬戶</a></li>
                    <?php else: ?>
                        <li><a href="T-Login.php">登入</a></li>
                    <?php endif; ?>
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

    <!-- *[col size max: 12]
        手機 col-p-12
        平板 col-ip-6
        電腦 col-pc-4 -->
    <div class="row">
        <!-- 主頁圖片 -->
        <div class="col-p-12 col-ip-6 col-pc-4 homeImg">
            <img src="image/cat2.png">
        </div>
            <!-- 主頁介紹 -->
            <div class="col-p-12 col-ip-6 col-pc-8">
                <h1>歡迎來到我們的網店 <br> 為你的毛孩提供更好、優質的生活</h1>
                <p>我們立志為您預與你的毛孩提供最好的服務 </p><br>
            </div>
        <div class="col-p-12 col-ip-6 col-pc-4 homeImg">
            <img src="image/discount.gif">
        </div>
    </div>

    <div class="categories">
        <div class="small-container">
            <h1 class="title">推薦產品</h1>
            <div class="row">
                <?php if (!empty($recommendedProducts) && is_array($recommendedProducts)): ?>
                    <?php foreach ($recommendedProducts as $product): ?>
                        <div class="col-pd-4">
                            <a href="T-Product-Details.php?pid=<?php echo htmlspecialchars($product['PID']); ?>">
                                <img src="image/<?php echo strtolower(str_replace(' ', '_', htmlspecialchars($product['PName']))); ?>.png" 
                                    alt="<?php echo htmlspecialchars($product['PName']); ?>">
                            </a>
                            <h3><b><?php echo htmlspecialchars($product['FPName']); ?></b></h3>
                            <div class="rating">
                                <p>價格: 
                                    <?php 
                                        echo isset($product['MinPrice']) && isset($product['MaxPrice']) 
                                            ? "$" . htmlspecialchars($product['MinPrice']) . " - $" . htmlspecialchars($product['MaxPrice'])
                                            : "暫無價格";
                                    ?>
                                </p>
                                <?php echo "<p class='heart'><span style='color: red;'>&#9829;</span> " . rand(50, 200) . "</p>"; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>目前沒有推薦產品。</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- 訂單 部分 -->
    <div class="offer">
        <div class="container2">
            <div class="row2">
                <div class="slidesContainer">
                    <?php if (!empty($newProducts) && is_array($newProducts)): ?>
                        <?php foreach ($newProducts as $product): ?>
                            <div class="slidesImg">
                                <img src="image/<?php echo strtolower(str_replace(' ', '_', htmlspecialchars($product['PName']))); ?>.png" 
                                    alt="<?php echo htmlspecialchars($product['PName']); ?>" width="100%">
                            </div>
                        <?php endforeach; ?>
                        <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
                        <a class="next" onclick="plusSlides(1)">&#10095;</a>
                    <?php else: ?>
                        <p>目前沒有新商品。</p>
                    <?php endif; ?>
                </div>
                                                    
                <div class="col-pd-7">
                    <h2 class="title2">最新商品出售中</h2>
                    <h1><b>毛孩用品[強力殺菌噴霧]</b></h1>
                    <h4>確保毛孩的健康, 殺菌尤其重要, <br>為毛孩提供最好的生活, 快來購買吧!</h4><br>
                    <p>
                        <?php 
                            if (!empty($newProducts)) {
                                $prices = array_column($newProducts, 'Price'); 
                                if (!empty($prices)) {
                                    echo "$" . htmlspecialchars(min($prices)) . " - $" . htmlspecialchars(max($prices));
                                } else {
                                    echo "暫無價格";
                                }
                            } else {
                                echo "暫無商品";
                            }
                        ?>
                    </p>
                    <a href="T-Products.php" class="btn">立即購買</a>
                </div>
            </div>
        </div>
    </div>


    <!-- 訂單 腳本 -->
    <script>
        let slideIndex = 1;
        showslides(slideIndex);

        function plusSlides(n) {
            showslides(slideIndex += n);
        }

        function currentSlide(n) {
            showslides(slideIndex = n);
        }

        function showslides(n) {
            let i;
            let slides = document.getElementsByClassName("slidesImg");
            if (n > slides.length) {slideIndex = 1}
            if (n < 1) {slideIndex = slides.length}
            for (i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
            }
            slides[slideIndex - 1].style.display = "block";
        }
    </script>

    
    <!-- 合作品牌 -->
    <div class="brands">
        <div class="small-container2">
            <h3 class="title3"><b>合作品牌</b></h3>
                <div class="row3">
                    <div class="col-b-5">
                        <img src="image/brand1.png">
                    </div>
                    <div class="col-b-5">
                        <img src="image/brand2.png">
                    </div>
                    <div class="col-b-5">
                        <img src="image/brand3.png">
                    </div>
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