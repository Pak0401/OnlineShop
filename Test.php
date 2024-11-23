<?php
session_start();

// 模擬購物車資料（僅用於測試，可註解掉）
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [
        ['pid' => 1, 'name' => 'DogP01', 'variant' => '5kg', 'price' => 300, 'quantity' => 2],
        ['pid' => 2, 'name' => 'CatP01', 'variant' => '10kg', 'price' => 500, 'quantity' => 1],
    ];
}

// 刪除購物車中特定記錄
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['pid'])) {
    $pidToDelete = intval($_GET['pid']);
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['pid'] === $pidToDelete) {
            unset($_SESSION['cart'][$key]);
            break;
        }
    }
    // 重新整理索引
    $_SESSION['cart'] = array_values($_SESSION['cart']);

    // 重導至購物車頁面，避免重複刪除
    header("Location: Cart.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="eng">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>購物車</title>
    <link rel="stylesheet" href="css/Style.css">
    <link rel="stylesheet" href="css/Products.css">
    <link rel="stylesheet" href="css/Product1-Details.css">
    <link rel="stylesheet" href="css/Cart.css">
    <link rel="stylesheet" href="css/Chat.css">
</head>
<body>
    <div class="container">
            <div class="navbar">
                <!-- Logo -->
                <div class="logo">
                    <img src="image/logo.png" width="125px">
                </div>
                <!-- 橫向菜單 -->
                <nav>
                    <ul id="menuItems">
                        <li><a href="Index.html">主頁</a></li>
                        <li><a href="Products.html">產品</a></li>
                        <li><a href="Contact.html">聯絡我們</a></li>
                        <li><a href="About.html">關於我們</a></li>
                        <li><a href="Account.html">登入</a></li>
                    </ul>
                </nav>
                <a href="Cart.html">
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

    <h1>購物車</h1>
    <?php if (!empty($_SESSION['cart'])): ?>
        <table border="1" style="width: 100%; text-align: left;">
            <tr>
                <th>商品</th>
                <th>重量/規格</th>
                <th>單價</th>
                <th>數量</th>
                <th>小計</th>
                <th>操作</th>
            </tr>
            <?php
            $total = 0;
            foreach ($_SESSION['cart'] as $item):
                $subtotal = $item['price'] * $item['quantity'];
                $total += $subtotal;
            ?>
            <tr>
                <td><?php echo htmlspecialchars($item['name']); ?></td>
                <td><?php echo htmlspecialchars($item['variant']); ?></td>
                <td>$<?php echo htmlspecialchars($item['price']); ?></td>
                <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                <td>$<?php echo htmlspecialchars($subtotal); ?></td>
                <td>
                    <!-- 刪除按鈕 -->
                    <a href="Cart.php?action=delete&pid=<?php echo $item['pid']; ?>" onclick="return confirm('確定要刪除此商品嗎？');">刪除</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <h3>總計: $<?php echo $total; ?></h3>
    <?php else: ?>
        <p>購物車是空的。</p>
    <?php endif; ?>

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
