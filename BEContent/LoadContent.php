<?php
// 允許的頁面
$allowed_pages = [
    'BEContent/Product-Be.php',
    'BEContent/Order.php',
    'BEContent/Inventory.php',
    'BEContent/Email.php'
];

// 獲取 GET 參數
if (isset($_GET['page'])) {
    $page = $_GET['page'];

    // 檢查頁面是否存在於 `allowed_pages` 陣列內
    if (in_array($page, $allowed_pages) && file_exists($page)) {
        require $page;
    } else {
        echo "<p style='color: red;'>錯誤: 找不到頁面: $page</p>";
    }
}
?>
