<?php
session_start();
require "../ProdBe-db.php";

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $pid = $_POST['pid'] ?? null;

    if (!$pid) {
        echo json_encode(["status" => "error", "message" => "缺少 PID"]);
        exit;
    }

    try {
        $pdo->beginTransaction();

        // 先刪除 storage.inventorydata 內的對應 PID
        $stmt = $pdo->prepare("DELETE FROM storage.inventorydata WHERE PID = ?");
        $stmt->execute([$pid]);

        // 再刪除 productdata 內的對應 PID
        $stmt = $pdo->prepare("DELETE FROM productdata WHERE PID = ?");
        $stmt->execute([$pid]);

        $pdo->commit();
        echo json_encode(["status" => "success", "message" => "產品已刪除"]);
    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "無效的請求"]);
}
?>
