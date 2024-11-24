<?php
// 連接數據庫
$host = 'localhost';
$user = 'root';
$pass = '';
$db_name = 'product_data';

$conn = new mysqli($host, $user, $pass, $db_name);
if ($conn->connect_error) {
    die("數據庫連接失敗: " . $conn->connect_error);
}

// 查詢基於 GID 的推薦產品，並計算價格範圍
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

$result = $conn->query($sql);

// 將結果存入 PHP 陣列
$recommendedProducts = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $recommendedProducts[] = $row;
    }
}

// 關閉數據庫連接
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>推薦產品</title>
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
                    <li><a href="Index.html">主頁</a></li>
                    <li><a href="Products.php">產品</a></li>
                    <li><a href="Contact.html">聯絡我們</a></li>
                    <li><a href="About.html">關於我們</a></li>
                    <li><a href="Account.html">登入</a></li>
                </ul>
            </nav>
            <a href="Cart.php">
                <img src="image/cart.png" width="40px" height="40px">
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

    <script>
        // 選取按鈕和聊天室窗口的元素
        const chatButton = document.getElementById("chatButton");
        const chatBox = document.getElementById("chatBox");
        const closeButton = document.getElementById("closeButton");

        // 點擊按鈕時，顯示或隱藏聊天室框
        chatButton.addEventListener("click", () => {
            chatBox.style.display = chatBox.style.display === "none" ? "flex" : "none";
        });

        // 點擊右上方的關閉按鈕時，隱藏聊天室框
        closeButton.addEventListener("click", () => {
            chatBox.style.display = "none";
        });

        // 送出訊息功能
        const sendButton = document.getElementById("sendButton");
        const chatInput = document.getElementById("chatInput");
        const chatContent = document.getElementById("chatContent");

        sendButton.addEventListener("click", () => {
            const message = chatInput.value.trim();
            if (message) {
                const messageElement = document.createElement("p");
                messageElement.textContent = message;
                chatContent.appendChild(messageElement);
                chatInput.value = "";
                chatContent.scrollTop = chatContent.scrollHeight; // 自動滾動到底部
            }
        });

        // 允許按 Enter 鍵送出訊息
        chatInput.addEventListener("keypress", (e) => {
            if (e.key === "Enter") {
                sendButton.click();
            }
        });
    </script>


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
                <?php foreach ($recommendedProducts as $product): ?>
                <div class="col-pd-4">
                    <a href="Product-Details.php?pid=<?php echo htmlspecialchars($product['PID']); ?>">
                        <img src="image/<?php echo htmlspecialchars($product['PName']); ?>.png" alt="<?php echo htmlspecialchars($product['PName']); ?>">
                    </a>
                    <h3><b><?php echo htmlspecialchars($product['FPName']); ?></b></h3>
                    <div class="rating">
                        <p>價格: $<?php echo htmlspecialchars($product['MinPrice']); ?> - $<?php echo htmlspecialchars($product['MaxPrice']); ?></p>
                        <?php echo "<p class='hreat'><span style='color: red;'>&#9829;</span> " . rand(50, 200) . "</p>";?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- 訂單 -->
    <div class="offer">
        <!-- 圖文格式2 -->
        <div class="container2">
            <!-- 最新商品 -->
            <div class="row2">
                    <!-- 圖片滑動 -->
                    <div class="slidesContainer">
                        <div class="slidesImg">
                            <img src="image/CleanP01.png" width="100%">
                        </div>
                        <div class="slidesImg">
                            <img src="image/CleanP02.png" width="100%">
                        </div>
                        <div class="slidesImg">
                            <img src="image/CleanP03.png" width="100%">
                        </div>
                        <div class="slidesImg">
                            <img src="image/CleanP04.png" width="100%">
                        </div>
                        <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
                        <a class="next" onclick="plusSlides(1)">&#10095;</a>
                    </div>
                    <div class="col-pd-7">
                        <h2 class="title2">最新商品出售中</h2>
                        <h1><b>毛孩用品[強力殺菌噴霧]</b></h1>
                        <h4>確保毛孩的健康, 殺菌尤其重要, 
                        <br>為毛孩提供最好的生活, 快來購買吧!</h4><br>
                        <p>$200 - 250</p>
                        <!-- 要記得add link!! -->
                        <a href="Product1-Details.html" class="btn">立即購買 </a>
                    </div>
                    
                <!-- 圖片滑動的腳本 -->
                <script>
                    // 預設數值是1 //
                    let slideIndex = 1;
                    showslides(slideIndex);

                    // 這邊數值會加1 //
                    function plusSlides(n) {
                        showslides(slideIndex += n);
                    }

                    // 顯示當前數值 //
                    function currentSlide(n) {
                        showslides(slideIndex = n);
                    }

                    function showslides(n) {
                        let i;
                        let slides = document.getElementsByClassName("slidesImg");
                        let dots = document.getElementsByClassName("test");
                        if (n > slides.length) {slideIndex = 1}
                        if (n < 1) {slideIndex = slides.length}
                        for (i = 0; i < slides.length; i++) {
                            slides[i].style.display = "none";
                        }
                        for (i = 0; i < dots.length; i++) {
                            dots[i].className = dots[i].className.replace(" active", "");
                        }
                        // 阻止指令 //
                        slides[slideIndex-1].style.display = "block";
                        // 允許指令 //
                        dots[slideIndex-1].className += " active";
                        captionText.innerHTML = dots[slideIndex-1].alt;
                    }
                </script>
            </div>
        </div>
    </div>
</body>
</html>