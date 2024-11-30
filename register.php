<?php
// 資料庫連線
$host = "localhost"; // 主機名稱
$username = "root";  // 資料庫用戶名
$password = "";      // 資料庫密碼
$database = "userdata"; // 資料庫名稱

$conn = new mysqli($host, $username, $password, $database);

// 檢查連線
if ($conn->connect_error) {
    die("資料庫連線失敗：" . $conn->connect_error);
}

// 定義成功通知變數
$successMessage = "";
$errorMessage = "";

// 表單提交處理
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $confirm_email = trim($_POST['confirm_email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // 基本驗證
    if (empty($username) || empty($email) || empty($password)) {
        $errorMessage = "所有欄位均為必填！";
    } elseif ($email !== $confirm_email) {
        $errorMessage = "電子郵件地址不一致！";
    } elseif ($password !== $confirm_password) {
        $errorMessage = "密碼不一致！";
    } else {
        // 密碼加密
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // 插入資料庫
        $stmt = $conn->prepare("INSERT INTO data (UName, Email, Password) VALUES (?, ?, ?)");
        if (!$stmt) {
            $errorMessage = "SQL 語法錯誤：" . $conn->error;
        } else {
            $stmt->bind_param("sss", $username, $email, $hashed_password);
            if ($stmt->execute()) {
                $successMessage = "註冊成功！3秒後將返回登入頁面...";
                echo "<script>
                        setTimeout(function(){
                            window.location.href='Account.php';
                        }, 3000);
                      </script>";
            } else {
                $errorMessage = "註冊失敗：" . $stmt->error;
            }
            $stmt->close();
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OnlineShop | Register</title>
    <link rel="stylesheet" href="css/Style.css">
    <link rel="stylesheet" href="css/Register.css">
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
                    <li><a href="Account.php">登入</a></li>
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

    <!-- 註冊 -->
    <div class="account-page">
        <div class="form-row">
            <div class="form-container form-text">
                <!-- 訊息顯示區域 -->
                <?php if (!empty($successMessage)): ?>
                    <h2 style="color: green; font-size: 18px; margin-bottom: 20px; text-align: center;">
                        <?php echo htmlspecialchars($successMessage); ?>
                    </h2>
                <?php endif; ?>

                <?php if (!empty($errorMessage)): ?>
                    <h2 style="color: red; font-size: 18px; margin-bottom: 20px; text-align: center;">
                        <?php echo htmlspecialchars($errorMessage); ?>
                    </h2>
                <?php endif; ?>

                <!-- 註冊表單 -->
                <div class="form">
                    <h1 style="text-align: center; color: #333; margin-bottom: 20px;">註冊</h1>
                    <form action="Register.php" method="POST">
                        <label for="username">用戶名稱：</label>
                        <input type="text" name="username" id="username" required><br><br>
                
                        <label for="email">電子郵件地址：</label>
                        <input type="email" name="email" id="email" required><br><br>
                
                        <label for="confirm_email">確認電子郵件地址：</label>
                        <input type="email" name="confirm_email" id="confirm_email" required><br><br>
                
                        <label for="password">密碼：</label>
                        <input type="password" name="password" id="password" required><br><br>
                
                        <label for="confirm_password">確認密碼：</label>
                        <input type="password" name="confirm_password" id="confirm_password" required><br><br>
                
                        <button type="submit">註冊</button>
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