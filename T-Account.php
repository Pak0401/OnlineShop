<?php
session_start();
require 'User-db.php';

// 檢查是否登入
if (!isset($_SESSION['uid'])) {
    header("Location: T-Login.php");
    exit();
}

// 確保資料庫連線
if (!isset($conn_userdata)) {
    die("❌ 錯誤：用戶資料庫連線失敗，請檢查 User-db.php！");
}

// 獲取用戶資料
$uid = $_SESSION['uid'];

$stmt = $conn_userdata->prepare("SELECT UID, UName, Email, Role FROM data WHERE UID = ?");
$stmt->execute([$uid]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// 確保 `user_role` 被設置
$_SESSION['user_role'] = $user['Role'] ?? 'user';

// 更改密碼邏輯
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_password'])) {
    $current_password = trim($_POST['current_password']);
    $new_password = trim($_POST['new_password']);
    $confirm_new_password = trim($_POST['confirm_new_password']);

    if ($new_password !== $confirm_new_password) {
        echo "<script>alert('❌ 新密碼與確認密碼不一致！');</script>";
    } else {
        $hashed_new_password = password_hash($new_password, PASSWORD_BCRYPT);
        $update_stmt = $conn_userdata->prepare("UPDATE data SET Password = ? WHERE UID = ?");
        $update_stmt->execute([$hashed_new_password, $uid]);
        echo "<script>alert('✅ 密碼更新成功！');</script>";
    }
}

// 登出邏輯
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['logout'])) {
    session_destroy();
    header("Location: T-Index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OnlineShop | Account</title>
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

    <!-- 賬戶 -->
    <div class="background-container">
        <div class="background-image left"></div>
        <div class="background-image right"></div>
        <div class="account-container">
            <h1>我的帳戶</h1>

            <p><strong>用戶名稱：</strong> <?php echo htmlspecialchars($user['UName']); ?></p>
            <p><strong>電子郵件：</strong> <?php echo htmlspecialchars($user['Email']); ?></p>

            <h2>更改密碼</h2>
            <form method="POST">
                <label for="current_password">當前密碼：</label>
                <input type="password" name="current_password" required>

                <label for="new_password">新密碼：</label>
                <input type="password" name="new_password" required>

                <label for="confirm_new_password">確認新密碼：</label>
                <input type="password" name="confirm_new_password" required>

                <button type="submit" name="update_password">更改密碼</button>
            </form>

            <?php if ($_SESSION['user_role'] === 'admin'): ?>
                <div style="text-align: center; margin-top: 20px;">
                    <a href="T-Backend.php" class="admin-button">前往後台</a>
                </div>
            <?php endif; ?>

            <form method="POST">
                <button type="submit" name="logout" class="logout-btn">登出</button>
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