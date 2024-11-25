<?php
// 啟用 Session
session_start();

// 檢查用戶是否已登入，未登入則重定向到登入頁面
if (!isset($_SESSION['uid'])) {
    header("Location: Test2.php"); // 登入頁面
    exit();
}

// 資料庫連線
$host = "localhost";
$username = "root";
$password = "";
$database = "userdata";

$conn = new mysqli($host, $username, $password, $database);

// 檢查資料庫連線
if ($conn->connect_error) {
    die("資料庫連線失敗：" . $conn->connect_error);
}

// 取得當前用戶的資訊
$uid = $_SESSION['uid'];
$stmt = $conn->prepare("SELECT UID, UName, Email, Password FROM data WHERE UID = ?");
$stmt->bind_param("i", $uid);
$stmt->execute();
$result = $stmt->get_result();

// 如果用戶存在，將數據存入變數
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "無法找到用戶資料。";
    exit();
}

// 更新密碼處理
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_password'])) {
    $current_password = trim($_POST['current_password']);
    $new_password = trim($_POST['new_password']);
    $confirm_new_password = trim($_POST['confirm_new_password']);

    // 驗證當前密碼
    if (!password_verify($current_password, $user['Password'])) {
        echo "<script>alert('當前密碼不正確！');</script>";
    } elseif ($new_password !== $confirm_new_password) {
        echo "<script>alert('新密碼與確認密碼不一致！');</script>";
    } else {
        // 更新密碼
        $hashed_new_password = password_hash($new_password, PASSWORD_BCRYPT);
        $update_stmt = $conn->prepare("UPDATE data SET Password = ? WHERE UID = ?");
        $update_stmt->bind_param("si", $hashed_new_password, $uid);

        if ($update_stmt->execute()) {
            echo "<script>alert('密碼更新成功！');</script>";
        } else {
            echo "<script>alert('密碼更新失敗，請重試。');</script>";
        }
    }
}

// 處理登出請求
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: Test.php"); // 跳轉到登入頁面
    exit();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OnlineShop | 賬戶資訊</title>
    <link rel="stylesheet" href="css/Style.css">
</head>
<body>
    <div class="account-page">
        <h1>賬戶資訊</h1>
        <div class="account-info">
            <p><strong>用戶 UID：</strong> <?php echo htmlspecialchars($user['UID']); ?></p>
            <p><strong>用戶名稱：</strong> <?php echo htmlspecialchars($user['UName']); ?></p>
            <p><strong>用戶電郵：</strong> <?php echo htmlspecialchars($user['Email']); ?></p>
        </div>

        <div class="change-password">
            <h2>更改密碼</h2>
            <form action="" method="POST">
                <label for="current_password">當前密碼：</label>
                <input type="password" id="current_password" name="current_password" required><br><br>

                <label for="new_password">新密碼：</label>
                <input type="password" id="new_password" name="new_password" required><br><br>

                <label for="confirm_new_password">確認新密碼：</label>
                <input type="password" id="confirm_new_password" name="confirm_new_password" required><br><br>

                <button type="submit" name="update_password">更新密碼</button>
            </form>
        </div>

        <div class="logout">
            <h2>登出</h2>
            <form action="" method="POST">
                <button type="submit" name="logout">登出</button>
            </form>
        </div>
    </div>
</body>
</html>
