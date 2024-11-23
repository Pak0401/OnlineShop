<?php
session_start();

// 確保購物車已初始化
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// 接收產品數據
$product_id = $_POST['product_id'];
$product_name = $_POST['product_name'];
$product_price = $_POST['product_price'];
$quantity = $_POST['quantity'];
$variant = $_POST['variant'];

// 檢查是否已經存在相同產品和規格
$found = false;
foreach ($_SESSION['cart'] as &$item) {
    if ($item['id'] === $product_id && $item['variant'] === $variant) {
        $item['quantity'] += $quantity; // 更新數量
        $found = true;
        break;
    }
}

// 如果產品和規格不存在，添加到購物車
if (!$found) {
    $_SESSION['cart'][] = [
        'id' => $product_id,
        'name' => $product_name,
        'price' => $product_price,
        'quantity' => $quantity,
        'variant' => $variant
    ];
}

// 跳轉到購物車頁面
header("Location: Cart.php");
exit;
?>
