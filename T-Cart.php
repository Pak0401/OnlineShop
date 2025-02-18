<?php
session_start();
require 'Prod-db.php'; // 載入商品資料庫連線

// 確保購物車已初始化
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// 處理加入購物車請求
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $pid = intval($_POST['pid']);
    $pname = htmlspecialchars($_POST['pname']);
    $price = floatval($_POST['price']);
    $quantity = max(1, intval($_POST['quantity'])); // 確保數量至少為 1
    $variant = htmlspecialchars($_POST['variant']);

    // 檢查購物車內是否已存在該產品（基於 PID 和 Variant
    $product_exists = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['pid'] === $pid && $item['variant'] === $variant) {
            $item['quantity'] += $quantity; // 如果已存在，增加數量
            $product_exists = true;
            break;
        }
    }

    // 如果產品尚未存在於購物車，新增到購物車
    if (!$product_exists) {
        $_SESSION['cart'][] = [
            'pid' => $pid,
            'name' => $pname,
            'price' => $price,
            'quantity' => $quantity,
            'variant' => $variant
        ];
    }

    // 重新導向到購物車頁面，避免重複提交
    header("Location: T-Cart.php");
    exit();
}

// 刪除購物車商品
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['pid']) && isset($_GET['variant'])) {
    $pid = intval($_GET['pid']);
    $variant = htmlspecialchars($_GET['variant']);

    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['pid'] === $pid && $item['variant'] === $variant) {
            unset($_SESSION['cart'][$key]);
            break;
        }
    }

    $_SESSION['cart'] = array_values($_SESSION['cart']); // 重新索引
    header("Location: T-Cart.php");
    exit();
}

// 更新購物車數量
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_cart'])) {
    foreach ($_POST['quantities'] as $key => $quantity) {
        list($pid, $variant) = explode('_', $key);
        $pid = intval($pid);
        $quantity = max(1, intval($quantity));

        foreach ($_SESSION['cart'] as &$item) {
            if ($item['pid'] == $pid && $item['variant'] == $variant) {
                $item['quantity'] = $quantity;
                break;
            }
        }
    }
    
    header("Location: T-Cart.php");
    exit();
}

// 確保購物車不為空
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["checkout"])) {
    if (!empty($_SESSION["cart"])) {
        $_SESSION["checkout_cart"] = $_SESSION["cart"]; // 儲存購物車數據
        header("Location: T-Payment.php"); // 跳轉到結帳頁面
        exit();
    } else {
        echo "<script>alert('購物車是空的，請先加入商品！');</script>";
    }
}
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

    <script src="Script/botResponse.js"></script>
    <script src="Script/Script.js"></script>

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
                    <td>$<?php echo number_format(floatval($item['price'])); ?></td>
                    <td>$<?php echo number_format($subtotal); ?></td>
                    <td>
                        <a href="T-Cart.php?action=delete&pid=<?php echo $item['pid']; ?>&variant=<?php echo urlencode($item['variant']); ?>" class="btn delete-btn">刪除</a>
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
                    <td>$<?php echo number_format($total); ?></td>
                </tr>
                <tr>
                    <td>運費:</td>
                    <td>免費</td>
                </tr>
            </table>
        </div>
        <form method="POST" action="T-Cart.php">
            <button type="submit" name="checkout" class="btn pay-btn">前往結帳</button>
        </form>
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