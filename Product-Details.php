<?php
// 連接資料庫
$conn = new mysqli("localhost", "root", "", "product_data");

if ($conn->connect_error) {
    die("資料庫連線失敗：" . $conn->connect_error);
}

// 獲取 pid，預設為 1
$pid = isset($_GET['pid']) ? intval($_GET['pid']) : 1;

// 獲取主產品數據
$sql_product = "SELECT * FROM productdata WHERE PID = $pid";
$result_product = $conn->query($sql_product);

if ($result_product->num_rows > 0) {
    $product = $result_product->fetch_assoc();
} else {
    die("找不到該產品。");
}

// 獲取同一產品的所有變體（基於 GID 分組）
$gid = $product['GID']; // 使用 GID 進行分組
$sql_variants = "SELECT * FROM productdata WHERE GID = '$gid'";
$result_variants = $conn->query($sql_variants);
$variants = [];
while ($row = $result_variants->fetch_assoc()) {
    $variants[] = $row;
}
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
                    <li><a href="Index.html">主頁</a></li>
                    <li><a href="Products.php">產品</a></li>
                    <li><a href="Contact.html">聯絡我們</a></li>
                    <li><a href="About.html">關於我們</a></li>
                    <li><a href="Account.html">登入</a></li>
                </ul>
            </nav>
            <a href="Test.php">
                <img src="image/cart.png" width="40px" height="40px">
            </a>
            <img src="image/menu.png" class="menu-icon" onclick="menutoggle()">
        </div>
    </div>

    <!-- 產品詳細內容 -->
    <div class="small-container pd-detail">
        <div class="row">
            <div class="col-pd-1">
                <img src="image/<?php echo htmlspecialchars($product['PName']); ?>.png" alt="<?php echo htmlspecialchars($product['PName']); ?>">
            </div>
            <div class="col-pd-1">
                <div class="small-title1" style="margin-bottom: 30px;">主頁/產品/<?php echo htmlspecialchars($product['PName']); ?></div>
                <h1><?php echo htmlspecialchars($product['PName']); ?></h1>
                <h3>產品描述</h3>
                <p><?php echo nl2br(htmlspecialchars($product['Description'])); ?></p>
                <h4>$
                    <?php
                    // 計算價格範圍
                    $minPrice = min(array_column($variants, 'Price'));
                    $maxPrice = max(array_column($variants, 'Price'));
                    echo "$minPrice - $maxPrice";
                    ?>
                </h4>

                <!-- 選擇重量或其他變量 -->
                <form action="Test.php" method="get">
                    <input type="hidden" name="pid" value="<?php echo $product['PID']; ?>">
                    <input type="number" name="quantity" value="1" min="1"><br>

                    <label for="Variant">選擇重量/規格:</label><br>
                        <select name="variant">
                            <?php foreach ($variants as $variant): ?>
                                <option value="<?php echo $variant['PID']; ?>">
                                    <?php echo htmlspecialchars($variant['Variant']) . " - $" . htmlspecialchars($variant['Price']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select><br>
                    <button type="submit" class="btn">加到購物車</button>
                </form>
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

<?php
$conn->close();
?>