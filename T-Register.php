<?php
require 'User-db.php'; // 載入用戶資料庫連線

// 檢查連線是否成功
if (!isset($conn_userdata)) {
    die("資料庫連線錯誤，請檢查 User-db.php");
}

// 定義成功與錯誤通知變數
$successMessage = "";
$errorMessage = "";

// 表單提交處理
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $confirm_email = trim($_POST['confirm_email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $role = "user"; // 預設角色

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
        $stmt = $conn_userdata->prepare("INSERT INTO data (UName, Email, Password) VALUES (?, ?, ?)");
        if (!$stmt) {
            $errorMessage = "SQL 語法錯誤：" . $conn_userdata->error;
        } else {
            $stmt->bind_param("sss", $username, $email, $hashed_password);
            if ($stmt->execute()) {
                $successMessage = "註冊成功！3秒後將返回登入頁面...";
                echo "<script>
                        setTimeout(function(){
                            window.location.href='T-Account.php';
                        }, 3000);
                      </script>";
            } else {
                $errorMessage = "註冊失敗：" . $stmt->error;
            }
            $stmt = null; //關掉 Statement
        }
    }
}

$conn_userdata = null; // 關掉資料庫連線
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OnlineShop | Register</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
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

    <script src="Script/botResponse.js"></script>
    <script src="Script/Script.js"></script>

    <!-- 註冊 -->
    <div class="account-page">
        <div class="form-row">
            <div class="form-container">
                <h1 class="form-title">註冊</h1>
                <form action="T-Register.php" method="POST">
                    
                    <div class="input-container">
                        <label for="username">用戶名稱：</label>
                        <input type="text" name="username" id="username" required>
                    </div>

                    <div class="input-container">
                        <label for="email">電子郵件地址：</label>
                        <input type="email" name="email" id="email" required>
                    </div>

                    <div class="input-container">
                        <label for="confirm_email">確認電子郵件地址：</label>
                        <input type="email" name="confirm_email" id="confirm_email" required>
                    </div>

                    <div class="input-container password-container">
                        <label for="password">密碼：</label>
                        <input type="password" name="password" id="password" required>
                        <span class="toggle-password" onclick="togglePassword('password', this)">
                            <i class="bi bi-eye-fill"></i>
                        </span>
                    </div>

                    <div class="input-container password-container">
                        <label for="confirm_password">確認密碼：</label>
                        <input type="password" name="confirm_password" id="confirm_password" required>
                        <span class="toggle-password" onclick="togglePassword('confirm_password', this)">
                            <i class="bi bi-eye-fill"></i>
                        </span>
                    </div>

                    <button type="submit" class="register-btn">註冊</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // 即時檢查 Email & 密碼一致性
        document.getElementById("confirm_email").addEventListener("input", validateForm);
        document.getElementById("confirm_password").addEventListener("input", validateForm);
        
        function validateForm() {
            let email = document.getElementById("email").value;
            let confirmEmail = document.getElementById("confirm_email").value;
            let password = document.getElementById("password").value;
            let confirmPassword = document.getElementById("confirm_password").value;
            let submitBtn = document.getElementById("submitBtn");

            if (email !== confirmEmail || password !== confirmPassword) {
                submitBtn.disabled = true;
            } else {
                submitBtn.disabled = false;
            }
        }

        // 顯示 / 隱藏密碼
        function togglePassword(fieldId, iconElement) {
            var input = document.getElementById(fieldId);
            var icon = iconElement.querySelector("i");

            if (input.type === "password") {
                input.type = "text";  // 顯示密碼
                icon.classList.remove("bi-eye-fill");
                icon.classList.add("bi-eye-slash-fill");  // 變成 "閉眼" 圖示
            } else {
                input.type = "password";  // 隱藏密碼
                icon.classList.remove("bi-eye-slash-fill");
                icon.classList.add("bi-eye-fill");  // 變回 "睜眼" 圖示
            }
        }
    </script>

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