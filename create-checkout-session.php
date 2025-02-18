<?php
require 'vendor/autoload.php'; 
session_start();

\Stripe\Stripe::setApiKey("sk_test_51QQkF4JTvI7Ka6t7bYvNqPCYowx8jn0PiUv10nqr1mybXmaWNc6NFlMqjjXQfh0dbluYmQHPbryoohMkURVaNNM800t4vIbp0b"); 

header("Content-Type: application/json");

try {
    $data = json_decode(file_get_contents("php://input"), true);
    $cart = $data["cart"] ?? [];
    $currency = $data["currency"] ?? "hkd";

    if (empty($cart)) {
        throw new Exception("購物車為空，無法處理訂單");
    }

    // 計算總金額 (Stripe 使用分為單位)
    $totalAmount = 0;
    $lineItems = [];

    foreach ($cart as $item) {
        $price = floatval($item["price"]) * 100; // Stripe 以 "分" 為單位
        $quantity = intval($item["quantity"]);

        $lineItems[] = [
            "price_data" => [
                "currency" => $currency,
                "product_data" => ["name" => $item["name"]],
                "unit_amount" => $price,
            ],
            "quantity" => $quantity,
        ];

        $totalAmount += $price * $quantity;
    }

    // 創建 Stripe Checkout Session
    $checkout_session = \Stripe\Checkout\Session::create([
        "payment_method_types" => ["card"],
        "line_items" => $lineItems,
        "mode" => "payment",
        "success_url" => "http://localhost/OnlineShop/success.php?session_id={CHECKOUT_SESSION_ID}",
        "cancel_url" => "http://localhost/OnlineShop/cancel.php",
    ]);

    echo json_encode(["id" => $checkout_session->id]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
?>