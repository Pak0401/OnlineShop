<?php
session_start();
require 'user-db.php'; // 確保正確載入 PDO 連線

// 初始化 $message，防止 Undefined Variable 錯誤
$message = "";

// 處理忘記密碼
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['check_email'])) {
    $email = trim($_POST['email']);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "請輸入有效的電子郵件地址。";
    } else {
        $stmt = $conn_userdata->prepare("SELECT UID FROM data WHERE Email = ?");
        $stmt->bindValue(1, $email, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $_SESSION['email_verified'] = $email;
            $message = "電子郵件驗證成功，請輸入新密碼。";
        } else {
            $message = "該電子郵件地址未註冊。";
        }
    }
}

// 處理更改密碼
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);
    $email = $_SESSION['email_verified'] ?? null;

    if (!$email) {
        $message = "未驗證電子郵件地址，請先驗證。";
    } elseif (empty($new_password) || empty($confirm_password)) {
        $message = "請輸入並確認新密碼。";
    } elseif ($new_password !== $confirm_password) {
        $message = "新密碼與確認密碼不一致。";
    } else {
        // 確保新密碼與舊密碼不同
        $stmt = $conn_userdata->prepare("SELECT Password FROM data WHERE Email = ?");
        $stmt->bindValue(1, $email, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (password_verify($new_password, $user['Password'])) {
            $message = "新密碼不能與當前密碼相同，請嘗試其他密碼。";
        } else {
            // 更新密碼
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $conn_userdata->prepare("UPDATE data SET Password = ? WHERE Email = ?");
            $stmt->bindValue(1, $hashed_password, PDO::PARAM_STR);
            $stmt->bindValue(2, $email, PDO::PARAM_STR);
            if ($stmt->execute()) {
                unset($_SESSION['email_verified']); // 清除 session
                header("Location: Login.php");
                exit();
            } else {
                $message = "密碼更改失敗，請稍後再試。";
            }
        }
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
    <link rel="stylesheet" href="css/ResetP.css">
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

    <!-- 重設密碼表單 -->
    <div class="background-container">
        <div class="background-image left"></div>
        <div class="background-image right"></div>
        <div class="main-container">
            <div class="reset-container">
                <h2>忘記密碼</h2>

                <!-- 訊息提示 -->
                <?php if ($message): ?>
                    <div class="message"><?php echo $message; ?></div>
                <?php endif; ?>

                <?php if (!isset($_SESSION['email_verified'])): ?>
                    <!-- 驗證電子郵件 -->
                    <form method="POST" action="">
                        <input type="email" name="email" placeholder="輸入您的電子郵件地址" required>
                        <button type="submit" name="check_email">驗證電子郵件</button>
                    </form>
                <?php else: ?>
                    <!-- 更改密碼 -->
                    <form method="POST" action="">
                        <div class="password-container">
                            <input type="password" name="new_password" id="new_password" placeholder="輸入新密碼" required>
                            <span class="toggle-password" onclick="togglePassword('new_password', this)">
                                <i class="bi bi-eye-fill"></i>
                            </span>
                        </div>

                        <div class="password-container">
                            <input type="password" name="confirm_password" id="confirm_password" placeholder="確認新密碼" required>
                            <span class="toggle-password" onclick="togglePassword('confirm_password', this)">
                                <i class="bi bi-eye-fill"></i>
                            </span>
                        </div>

                        <button type="submit" name="change_password">更改密碼</button>
                    </form>
                <?php endif; ?>
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