<?php
// 1. 資料庫連線
$host = "localhost"; // 主機名稱
$username = "root";  // 資料庫用戶名
$password = "";      // 資料庫密碼
$database = "userdata"; // 資料庫名稱

$conn = new mysqli($host, $username, $password, $database);

// 檢查連線
if ($conn->connect_error) {
    die("資料庫連線失敗：" . $conn->connect_error);
}

// 2. 檢查表單是否提交
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $confirm_email = trim($_POST['confirm_email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // 3. 基本驗證
    if (empty($username) || empty($email) || empty($password)) {
        die("所有欄位均為必填！");
    }

    if ($email !== $confirm_email) {
        die("電子郵件地址不一致！");
    }

    if ($password !== $confirm_password) {
        die("密碼不一致！");
    }

    // 4. 密碼加密
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // 5. 插入資料庫
    $stmt = $conn->prepare("INSERT INTO data (UName, Email, Password) VALUES (?, ?, ?)");
    if (!$stmt) {
        die("SQL 語法錯誤：" . $conn->error);
    }

    $stmt->bind_param("sss", $username, $email, $hashed_password);

    if ($stmt->execute()) {
        echo "<p style='color: green; font-size: 18px; margin-top: 50px;'>註冊成功！3秒後將返回登入頁面...</p>";
        echo "<script>
                setTimeout(function(){
                    window.location.href='Account.html';
                    }, 3000);
                </script>";
    } else {
        echo "註冊失敗：" . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
