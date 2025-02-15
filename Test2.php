<?php
// 啟用 Session
session_start();

// 檢查用戶是否已登入，未登入則重定向到登入頁面
if (!isset($_SESSION['uid'])) {
    header("Location: Login.php"); // 登入頁面
    exit();
}

// **載入資料庫連接**
require 'db.php';

// 取得當前用戶的資訊
$uid = $_SESSION['uid'];

try {
    // **使用 PDO 預處理語句來獲取用戶資料**
    $stmt = $conn->prepare("SELECT UID, UName, Email, Password FROM data WHERE UID = ?");
    $stmt->execute([$uid]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // **如果找不到用戶，則登出並重定向**
    if (!$user) {
        session_destroy();
        header("Location: Login.php");
        exit();
    }
} catch (PDOException $e) {
    die("❌ 資料庫錯誤：" . $e->getMessage());
}

// **更新密碼處理**
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_password'])) {
    $current_password = trim($_POST['current_password']);
    $new_password = trim($_POST['new_password']);
    $confirm_new_password = trim($_POST['confirm_new_password']);

    // **驗證當前密碼**
    if (!password_verify($current_password, $user['Password'])) {
        echo "<script>alert('❌ 當前密碼不正確！');</script>";
    } elseif ($new_password !== $confirm_new_password) {
        echo "<script>alert('❌ 新密碼與確認密碼不一致！');</script>";
    } else {
        // **更新密碼**
        $hashed_new_password = password_hash($new_password, PASSWORD_BCRYPT);
        try {
            $update_stmt = $conn->prepare("UPDATE data SET Password = ? WHERE UID = ?");
            $update_stmt->execute([$hashed_new_password, $uid]);
            echo "<script>alert('✅ 密碼更新成功！');</script>";
        } catch (PDOException $e) {
            echo "<script>alert('❌ 密碼更新失敗，請重試。');</script>";
        }
    }
}

// **處理登出請求**
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: Test3.php"); // 跳轉到首頁
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OnlineShop | About</title>
    <link rel="stylesheet" href="css/Style.css">
    <link rel="stylesheet" href="css/Chat.css">
    <link rel="stylesheet" href="css/Account.css">
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
                        echo '<li><a href="Test2.php">登入</a></li>';
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

    <!-- 賬戶 -->
    <div class="background-container">
        <div class="background-image left"></div>
        <div class="background-image right"></div>
            <div class="account-page">
                <h1>賬戶資訊</h1>
                <div class="account-info">
                    <p><strong>用戶 UID：</strong> <?php echo htmlspecialchars($user['UID']); ?></p>
                    <p><strong>用戶名稱：</strong> <?php echo htmlspecialchars($user['UName']); ?></p>
                    <p><strong>用戶電郵：</strong> <?php echo htmlspecialchars($user['Email']); ?></p>
                </div>

                <div class="change-password">
                    <h2>更改密碼</h2>
                    <form action="" method="POST">
                        <label for="current_password">當前密碼：</label>
                        <input type="password" id="current_password" name="current_password" required><br><br>

                        <label for="new_password">新密碼：</label>
                        <input type="password" id="new_password" name="new_password" required><br><br>

                        <label for="confirm_new_password">確認新密碼：</label>
                        <input type="password" id="confirm_new_password" name="confirm_new_password" required><br><br>

                        <button type="submit" name="update_password">更新密碼</button>
                    </form>
                </div>

                <div class="logout">
                    <form action="" method="POST">
                        <button type="submit" name="logout">登出</button>
                    </form>
                </div>
            </div>
        </div>
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