<?php
session_start();
require "../ProdBe-db.php"; // 連接資料庫

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $pname = $_POST['pname'];
    $price = $_POST['price'];
    $variant = $_POST['variant'];
    $gid = $_POST['gid'];
    $description = $_POST['description'];

    try {
        $pdo->beginTransaction(); // 開啟交易

        // 新增產品到 productdata
        $stmt = $pdo->prepare("INSERT INTO productdata (PName, Price, Variant, GID, Description) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$pname, $price, $variant, $gid, $description]);

        // 取得新產品的 PID
        $pid = $pdo->lastInsertId();

        // 新增到 inventorydata (預設庫存 100，可自行調整)
        $stmt = $pdo->prepare("INSERT INTO storage.inventorydata (PID, PName, Variant, Quantity) VALUES (?, ?, ?, ?)");
        $stmt->execute([$pid, $pname, $variant, 100]);

        $pdo->commit(); // 提交交易
        echo json_encode(["status" => "success", "message" => "產品已成功新增"]);
    } catch (Exception $e) {
        $pdo->rollBack(); // 取消交易
        echo json_encode(["status" => "error", "message" => "錯誤：" . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "無效的請求"]);
}
?>
