<?php
require "../Prod-db.php"; // 確保正確引入資料庫

header("Content-Type: application/json"); // 設定回應格式為 JSON

// 確保請求方式為 POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["status" => "error", "error" => "請求方式錯誤"]);
    exit;
}

// 確保 action 參數存在
$action = $_POST["action"] ?? null;
if (!$action) {
    echo json_encode(["status" => "error", "error" => "未提供 action 參數"]);
    exit;
}

try {
    if ($action === "add") {
        // 取得 POST 傳入的資料
        $name = $_POST["name"] ?? "";
        $price = $_POST["price"] ?? "";
        $variant = $_POST["variant"] ?? "";
        $gid = $_POST["gid"] ?? "";
        $description = $_POST["description"] ?? "";

        // 確保欄位不為空
        if (empty($name) || empty($price) || empty($variant) || empty($gid) || empty($description)) {
            throw new Exception("所有欄位皆需填寫");
        }

        // 執行 SQL 插入
        $stmt = $conn_productdata->prepare("INSERT INTO productdata (PName, Price, Variant, GID, Description) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $price, $variant, $gid, $description]);

        echo json_encode(["status" => "success"]);
        exit;

    } elseif ($action === "delete") {
        // 刪除產品
        $pid = $_POST["pid"] ?? "";

        if (empty($pid)) {
            throw new Exception("缺少 PID");
        }

        // 確保 PID 存在
        $stmt_check = $conn_productdata->prepare("SELECT PID FROM productdata WHERE PID = ?");
        $stmt_check->execute([$pid]);
        if ($stmt_check->rowCount() === 0) {
            throw new Exception("產品不存在，無法刪除");
        }

        // 執行刪除
        $stmt = $conn_productdata->prepare("DELETE FROM productdata WHERE PID = ?");
        $stmt->execute([$pid]);

        echo json_encode(["status" => "success"]);
        exit;

    } else {
        throw new Exception("無效的 action");
    }

} catch (PDOException $e) {
    echo json_encode(["status" => "error", "error" => "資料庫錯誤：" . $e->getMessage()]);
    exit;
} catch (Exception $e) {
    echo json_encode(["status" => "error", "error" => $e->getMessage()]);
    exit;
}
?>
