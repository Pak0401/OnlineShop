<?php
session_start();

// 計算購物車總額
$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $subtotal = floatval($item['price']) * intval($item['quantity']);
    $total += $subtotal;
}

// 確保總金額正確
if ($total <= 0) {
    die("購物車為空，無法結帳！");
}

// 確保 Stripe 環境
$stripePublicKey = "pk_test_51QQkF4JTvI7Ka6t7ixrWxfzNrutdimkSHB64XvDjNhq75VNsT0oKGDKQS86DnCHFkNGBHinQjpQMJWZ7ZIlBAbxe00CQfEKBjB"; 
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stripe 付款</title>
    <script src="https://js.stripe.com/v3/"></script>
</head>
<body>
    <h2>訂單確認</h2>
    <div class="total-price">
        <table>
            <tr>
                <td>總計:</td>
                <td>$<span id="total-price"><?php echo number_format($total); ?></span></td>
            </tr>
            <tr>
                <td>運費:</td>
                <td>免費</td>
            </tr>
        </table>
    </div>

    <!-- Stripe 付款按鈕 -->
    <button id="checkout-button">前往付款</button>

    <script>
        const stripe = Stripe("<?php echo $stripePublicKey; ?>");

        document.getElementById("checkout-button").addEventListener("click", function() {
            // 透過 AJAX 傳遞購物車數據
            fetch("create-checkout-session.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({
                    cart: <?php echo json_encode($_SESSION['checkout_cart']); ?>, // 把購物車數據轉為 JSON
                    currency: "hkd"
                })
            })
            .then(response => response.json())
            .then(session => {
                return stripe.redirectToCheckout({ sessionId: session.id });
            })
            .catch(error => console.error("錯誤:", error));
        });
    </script>
</body>
</html>
