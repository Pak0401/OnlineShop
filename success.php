<?php
session_start();

// 在這裡可以新增訂單到資料庫
if (isset($_SESSION["checkout_cart"])) {
    // 你可以將 $_SESSION["checkout_cart"] 存入資料庫
    unset($_SESSION["checkout_cart"]); // 清空購物車
}
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>付款成功</title>
</head>
<body>
    <h2>付款成功！</h2>
    <p>您的訂單已成功支付。</p>
    <a href="T-Index.php">返回首頁</a>
</body>
</html>
