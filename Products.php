<?php
// 連接資料庫
$conn = new mysqli("localhost", "root", "", "product_data");

if ($conn->connect_error) {
    die("資料庫連線失敗：" . $conn->connect_error);
}

// 獲取所有產品數據
$sql = "SELECT * FROM productdata";
$result = $conn->query($sql);

// 整理產品數據，合併相同主名稱的產品
$products = [];
while ($row = $result->fetch_assoc()) {
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

session_start();
$is_logged_in = isset($_SESSION['user_id']); // 檢查是否已登入

session_start(); // 啟用 Session

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
                    <li><a href="Index.php">主頁</a></li>
                    <li><a href="Products.php">產品</a></li>
                    <li><a href="Contact.php">聯絡我們</a></li>
                    <li><a href="About.php">關於我們</a></li>
                    <?php
                    if (isset($_SESSION['uid'])) {
                        // 如果已登入，顯示賬戶連結
                        echo '<li><a href="Account.php">賬戶</a></li>';
                    } else {
                        // 如果未登入，顯示登入連結
                        echo '<li><a href="Login.php">登入</a></li>';
                    }
                    ?>
                </ul>
            </nav>
            <a href="Cart.php">
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
                echo "<a href='Product-Details.php?pid=" . $product['pid'] . "'>";
                echo "<img src='image/" . $product['name'] . ".png' alt='" . $product['name'] . "'>";
                echo "</a>";
                echo "<h3><b>" . $product['name'] . "</b></h3>";
                echo "<div class='rating'>";
                echo "<p>$" . $product['minPrice'] . " - $" . $product['maxPrice'] . "</p>";
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
                    <p>地址: 香港九龍區xx路xx號</p>
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

<?php
$conn->close();
?>
