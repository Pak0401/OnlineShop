<?php
session_start();

// 確保購物車已初始化
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// 處理刪除商品
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['pid']) && isset($_GET['variant'])) {
    $pid = intval($_GET['pid']);
    $variant = htmlspecialchars($_GET['variant']);

    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['pid'] === $pid && $item['variant'] === $variant) {
            unset($_SESSION['cart'][$key]);
            break;
        }
    }

    $_SESSION['cart'] = array_values($_SESSION['cart']); // 重新索引購物車
}


// 處理更改數量
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_cart'])) {
    $updated_cart = []; // 初始化一個新的購物車數據陣列

    // 獲取數量和產品識別數據
    if (isset($_POST['quantities']) && is_array($_POST['quantities'])) {
        foreach ($_POST['quantities'] as $key => $quantity) {
            // 分離 PID 和 Variant 作為鍵
            list($pid, $variant) = explode('_', $key);

            // 確保數量為有效整數
            $quantity = max(1, intval($quantity));

            // 查找原始購物車數據並保留其他資訊
            foreach ($_SESSION['cart'] as $item) {
                if ($item['pid'] == $pid && $item['variant'] == $variant) {
                    $item['quantity'] = $quantity; // 更新數量
                    $updated_cart[] = $item; // 加回到新的購物車
                    break;
                }
            }
        }
    }

    $_SESSION['cart'] = $updated_cart; // 更新購物車
}

$is_logged_in = isset($_SESSION['user_id']); // 檢查是否已登入
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OnlineShop | Cart</title>
    <link rel="stylesheet" href="css/Style.css">
    <link rel="stylesheet" href="css/Cart.css">
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
            <!-- 橫向菜單 -->
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

    <!-- 購物車 -->
    <div class="small-container cart-page">
        <div class="small-title4">購物車</div>
        <?php if (!empty($_SESSION['cart'])): ?>
        <form method="POST" action="">
            <table>
                <tr>
                    <th>商品</th>
                    <th>類別</th>
                    <th>數量</th>
                    <th>單價</th>
                    <th>總價格</th>
                    <th>操作</th>
                </tr>
                <?php
                $total = 0; // 初始化總金額
                foreach ($_SESSION['cart'] as $item):
                    $subtotal = floatval($item['price']) * intval($item['quantity']);
                    $total += $subtotal;
                ?>
                <tr>
                    <td>
                        <div class="cart-info">
                            <img src="image/<?php echo htmlspecialchars($item['name']); ?>.png" alt="<?php echo htmlspecialchars($item['name']); ?>">
                            <div>
                                <p><?php echo htmlspecialchars($item['name']); ?></p>
                                <middle>價格: $<?php echo number_format(floatval($item['price']), 2); ?></middle><br>
                            </div>
                        </div>
                    </td>
                    <td><?php echo htmlspecialchars($item['variant']); ?></td>
                    <td>
                        <!-- 使用 PID 和 Variant 作為鍵 -->
                        <input type="number" name="quantities[<?php echo $item['pid'] . '_' . urlencode($item['variant']); ?>]" value="<?php echo intval($item['quantity']); ?>" min="1">
                    </td>
                    <td>$<?php echo number_format(floatval($item['price']), 2); ?></td>
                    <td>$<?php echo number_format($subtotal, 2); ?></td>
                    <td>
                        <a href="Cart.php?action=delete&pid=<?php echo $item['pid']; ?>&variant=<?php echo urlencode($item['variant']); ?>" class="btn delete-btn">刪除</a>
                    </td>
                </tr>

                <?php endforeach; ?>
            </table>
            <button type="submit" name="update_cart" class="btn update-btn">更新購物車</button>
        </form>
        <hr style="color: #2eceff">
        <div class="total-price">
            <table>
                <tr>
                    <td>總計:</td>
                    <td>$<?php echo number_format($total, 2); ?></td>
                </tr>
                <tr>
                    <td>運費:</td>
                    <td>免費</td>
                </tr>
            </table>
        </div>
        <a href="Payment.html" class="btn pay-btn">前往結帳</a>
        <?php else: ?>
            <p>購物車是空的。</p>
        <?php endif; ?>
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
