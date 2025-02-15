<?php
require 'User-db.php'; // 載入用戶資料庫連線
session_start();

$is_logged_in = isset($_SESSION['uid']); // 檢查是否已登入

// 確保資料庫連線變數存在
if (!isset($conn_userdata)) {
    die("錯誤：用戶資料庫連線失敗，請檢查 User-db.php");
}

// 處理登入請求
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // 驗證是否填寫
    if (empty($email) || empty($password)) {
        die("請填寫所有欄位！");
    }

    try {
        // 從 userdata 資料庫查詢用戶
        $stmt = $conn_userdata->prepare("SELECT UID, UName, Password FROM data WHERE Email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // 密碼驗證
            if (password_verify($password, $user['Password'])) {
                $_SESSION['uid'] = $user['UID'];
                $_SESSION['username'] = $user['UName'];
                $_SESSION['email'] = $email;

                // 跳轉至首頁
                echo "<script>
                        alert('登入成功！');
                        window.location.href = 'T-Index.php';
                      </script>";
            } else {
                echo "<script>
                        alert('密碼錯誤！');
                        window.history.back();
                      </script>";
            }
        } else {
            echo "<script>
                    alert('用戶不存在！');
                    window.history.back();
                  </script>";
        }
    } catch (PDOException $e) {
        die("資料庫錯誤：" . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OnlineShop | Account</title>
    <link rel="stylesheet" href="css/Style.css">
    <link rel="stylesheet" href="css/Login.css">
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


    <!-- 登入 -->
    <div class="account-page">
        <div class="form-row">
            <div class="form-container">
                <h1>登入</h1>
                <form action="T-Login.php" method="POST">
                    <label for="email">電子郵件地址：</label>
                    <input type="email" name="email" id="email" required><br><br>
                    
                    <label for="password">密碼：</label>
                    <input type="password" name="password" id="password" required><br><br>
                    
                    <button type="submit" class="login-btn">登入</button>
                </form>
                    <!-- 記得add link -->
                    <div class="form-link">
                        <a href="T-ResetP.php"><u>忘記密碼</u></a><br>
                        <a href="T-Register.php"><u>立即註冊</u></a>
                    </div>
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