<?php
session_start();
require 'Prod-db.php'; // 載入商品資料庫連線

// 確保 `conn_productdata` 連線正常
if (!isset($conn_productdata)) {
    die("❌ 資料庫連線錯誤，請檢查 Prod-db.php");
}

// **獲取 `pid`，預設為 1**
$pid = isset($_GET['pid']) ? intval($_GET['pid']) : 1;

// **獲取主產品數據**
$stmt = $conn_productdata->prepare("SELECT * FROM productdata WHERE PID = ?");
$stmt->execute([$pid]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    die("❌ 找不到該產品。");
}

// **獲取相同 GID 的所有變體（不同變體的產品）**
$gid = $product['GID'];
$stmt_variants = $conn_productdata->prepare("SELECT * FROM productdata WHERE GID = ?");
$stmt_variants->execute([$gid]);
$variants = $stmt_variants->fetchAll(PDO::FETCH_ASSOC);

// **確保購物車已初始化**
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// **處理加入購物車的請求**
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $pid = intval($_POST['pid']);
    $pname = htmlspecialchars($_POST['pname']);
    $price = floatval($_POST['price']);
    $quantity = intval($_POST['quantity']);
    $variant = htmlspecialchars($_POST['variant']);

    // **檢查購物車內是否已存在該產品（基於 PID 和 Variant）**
    $product_exists = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['pid'] === $pid && $item['variant'] === $variant) {
            $item['quantity'] += $quantity; // 如果已存在，增加數量
            $product_exists = true;
            break;
        }
    }

    // **如果產品尚未存在於購物車，新增到購物車**
    if (!$product_exists) {
        $_SESSION['cart'][] = [
            'pid' => $pid,
            'name' => $pname,
            'price' => $price,
            'quantity' => $quantity,
            'variant' => $variant
        ];
    }

    // **重新導向到購物車頁面（避免表單重複提交）**
    header("Location: T-Cart.php");
    exit();
}

// **檢查是否已登入**
$is_logged_in = isset($_SESSION['uid']);

// **獲取隨機的 4 個其他產品，基於 GID 分組，避免重複**
$stmt_random = $conn_productdata->prepare("
    SELECT p.GID, p.PID, p.PName, MIN(p.Price) AS minPrice, MAX(p.Price) AS maxPrice 
    FROM productdata p 
    WHERE p.GID != ? 
    GROUP BY p.GID 
    ORDER BY RAND() 
    LIMIT 4");
$stmt_random->execute([$gid]);
$random_products = $stmt_random->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['PName']); ?> | Details</title>
    <link rel="stylesheet" href="css/Style.css">
    <link rel="stylesheet" href="css/Products.css">
    <link rel="stylesheet" href="css/Product1-Details.css">
    <link rel="stylesheet" href="css/Chat.css">
</head>
<body>
    <div class="container">
        <div class="navbar">
            <div class="logo">
                <img src="image/logo.png" width="125px">
            </div>
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

    <!-- 產品詳細內容 -->
    <div class="small-container pd-detail">
        <div class="row">
            <div class="col-pd-1">
                <img src="image/<?php echo strtolower(str_replace(' ', '_', htmlspecialchars($product['PName']))); ?>.png" 
                    alt="<?php echo htmlspecialchars($product['PName']); ?>">
            </div>
            <div class="col-pd-1">
                <div class="small-title1" style="margin-bottom: 30px;">
                    主頁 / 產品 / <?php echo htmlspecialchars($product['PName']); ?>
                </div>
                <h1><?php echo htmlspecialchars($product['PName']); ?></h1>
                <h3>產品描述</h3>
                <p><?php echo nl2br(htmlspecialchars($product['Description'])); ?></p>
                
                <h4 id="price_display">
                    $<?php echo number_format($variants[0]['Price'], 2); ?>
                </h4>

                <!-- 選擇重量或其他變數 -->
                <form action="T-Cart.php" method="POST">
                    <input type="hidden" name="pid" value="<?php echo $product['PID']; ?>">
                    <input type="hidden" name="pname" value="<?php echo htmlspecialchars($product['PName']); ?>">
                    <input type="hidden" name="price" id="price_input" value="<?php echo isset($variants[0]['Price']) ? $variants[0]['Price'] : 0; ?>">

                    <label for="quantity">數量：</label>
                    <input type="number" name="quantity" value="1" min="1" required><br>

                    <label for="variant">選擇重量/規格:</label><br>
                    <select name="variant" id="variant_select" required onchange="updatePrice()">
                        <?php foreach ($variants as $variant): ?>
                            <option value="<?php echo htmlspecialchars($variant['Variant']); ?>" 
                                    data-price="<?php echo $variant['Price']; ?>">
                                <?php echo htmlspecialchars($variant['Variant']) . " - $" . number_format($variant['Price'], 2); ?>
                            </option>
                        <?php endforeach; ?>
                    </select><br>
                    <button type="submit" name="add_to_cart" class="AddCart">加到購物車</button>
                </form>

                <script>
                    function updatePrice() {
                        var select = document.getElementById("variant_select");
                        var priceDisplay = document.getElementById("price_display");
                        var priceInput = document.getElementById("price_input");
                        var selectedPrice = select.options[select.selectedIndex].getAttribute("data-price");
                        
                        priceDisplay.innerHTML = "$" + parseFloat(selectedPrice).toFixed(2);
                        priceInput.value = selectedPrice;
                    }
                </script>
            </div>
        </div>
    </div>

    <!-- 其他產品 -->
    <div class="small-container">
        <div class="other-pd">
            <h2>其他產品</h2>
            <div class="row-Otherpd">
                <?php if (!empty($random_products)): ?>
                    <?php foreach ($random_products as $random_product): ?>
                        <div class="col-pd-4">
                            <img src="image/<?php echo strtolower(str_replace(' ', '_', htmlspecialchars($random_product['PName']))); ?>.png" 
                                alt="<?php echo htmlspecialchars($random_product['PName']); ?>">
                            <h4><?php echo htmlspecialchars($random_product['PName']); ?></h4>
                            <p>
                                $
                                <?php 
                                    $minPrice = isset($random_product['minPrice']) ? number_format($random_product['minPrice'], 2) : 'N/A';
                                    $maxPrice = isset($random_product['maxPrice']) ? number_format($random_product['maxPrice'], 2) : 'N/A';

                                    // 檢查是否為單一價格
                                    if ($minPrice === $maxPrice) {
                                        echo $minPrice;
                                    } else {
                                        echo $minPrice . " - $" . $maxPrice;
                                    }
                                ?>
                            </p>
                            <a href="T-Product-Details.php?pid=<?php echo htmlspecialchars($random_product['PID']); ?>">
                                <button class="view-btn">查看商品</button>
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>目前沒有推薦產品。</p>
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