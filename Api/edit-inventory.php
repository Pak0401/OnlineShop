<?php
require "../Inventory-db.php"; // 連接資料庫

header("Content-Type: application/json");

// 確保是 POST 請求
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $productId = $_POST["PID"] ?? "";
    $quantity = $_POST["Quantity"] ?? "";

    if (!$productId || !$quantity) {
        echo json_encode(["success" => false, "error" => "缺少參數"]);
        exit;
    }

    try {
        $sql = "UPDATE inventorydata SET Quantity = :quantity WHERE PID = :pid";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ":quantity" => $quantity,
            ":pid" => $productId
        ]);

        echo json_encode(["success" => true]);
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "error" => $e->getMessage()]);
    }
}
?>
