<?php
require 'vendor/autoload.php';
require_once __DIR__ . '/Order-db.php'; // 連接資料庫

\Stripe\Stripe::setApiKey("你的 Stripe Secret Key");

// 讀取 Webhook 事件
$payload = @file_get_contents('php://input');
$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
$endpoint_secret = "你的 Stripe Webhook Secret"; // 在 Stripe Dashboard 取得

try {
    $event = \Stripe\Webhook::constructEvent($payload, $sig_header, $endpoint_secret);
} catch (\UnexpectedValueException $e) {
    http_response_code(400);
    exit();
} catch (\Stripe\Exception\SignatureVerificationException $e) {
    http_response_code(400);
    exit();
}

// 處理不同的事件類型
if ($event->type == 'checkout.session.completed') {
    $session = $event->data->object;

    // 付款成功，從 session 取得資料
    $transactionId = $session->id;
    $totalPrice = $session->amount_total / 100; // 轉回元
    $customerEmail = $session->customer_details['email'] ?? '';

    // 取得購物車內容
    $cart = $session->metadata['cart'] ?? '[]';
    $cartItems = json_decode($cart, true);

    // 插入訂單到資料庫
    $db = new Database();
    $orderId = "ORD" . time();

    $db->query("INSERT INTO orders (order_id, transaction_id, total_price, customer_email, status)
                VALUES ('$orderId', '$transactionId', '$totalPrice', '$customerEmail', 'Paid')");

    // 插入訂單詳細資料
    foreach ($cartItems as $item) {
        $db->query("INSERT INTO order_items (order_id, product_name, quantity, price)
                    VALUES ('$orderId', '{$item['name']}', '{$item['quantity']}', '{$item['price']}')");
    }
}

// 返回 200 OK，告知 Stripe Webhook 成功接收
http_response_code(200);
?>
