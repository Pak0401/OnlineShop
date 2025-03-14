<?php
// 添加錯誤報告，協助調試
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 確保 Order-db.php 的路徑正確
// 使用絕對路徑或相對路徑，視您的檔案結構而定
require_once '../Order-db.php';

// 檢查 $pdo 變數是否已定義
if (!isset($pdo)) {
    // 嘗試重新連接資料庫
    $host = "localhost"; 
    $dbname = "orderdata"; 
    $username = "root"; 
    $password = ""; 
    
    try {
        // 建立 PDO 連線
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        error_log("在 deleteOrder.php 中重新建立了 PDO 連接");
    } catch (PDOException $e) {
        error_log("資料庫連接失敗: " . $e->getMessage());
        echo json_encode(["success" => false, "message" => "資料庫連接失敗"]);
        exit;
    }
}

header("Content-Type: application/json");

// 獲取原始的 POST 數據
$raw_data = file_get_contents("php://input");
// 將接收到的數據寫入日誌（用於調試）
error_log("接收到的原始數據: " . $raw_data);

try {
    // 解析 JSON 數據
    $data = json_decode($raw_data, true);
    
    // 檢查 JSON 解析錯誤
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("JSON 解析錯誤: " . json_last_error_msg());
    }
    
    // 檢查必要參數
    if (!isset($data['order_id']) || empty($data['order_id'])) {
        throw new Exception("缺少訂單 ID 或 ID 為空");
    }
    
    $order_id = $data['order_id'];
    error_log("準備刪除訂單: " . $order_id);
    
    // 執行刪除操作
    $stmt = $pdo->prepare("DELETE FROM orders WHERE order_id = :order_id");
    $stmt->bindParam(':order_id', $order_id, PDO::PARAM_STR);
    
    if ($stmt->execute()) {
        $affected_rows = $stmt->rowCount();
        error_log("刪除操作執行成功，影響的行數: " . $affected_rows);
        
        if ($affected_rows > 0) {
            echo json_encode([
                "success" => true, 
                "message" => "訂單刪除成功",
                "affected_rows" => $affected_rows
            ]);
        } else {
            echo json_encode([
                "success" => false, 
                "message" => "找不到該訂單或已被刪除"
            ]);
        }
    } else {
        throw new Exception("刪除操作執行失敗");
    }
} catch (Exception $e) {
    error_log("刪除訂單錯誤: " . $e->getMessage());
    echo json_encode([
        "success" => false, 
        "message" => "錯誤: " . $e->getMessage()
    ]);
}
?>