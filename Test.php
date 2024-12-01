<?php
session_start();
// 資料庫連接
$conn = new mysqli("localhost", "root", "", "userdata");

if ($conn->connect_error) {
    die("資料庫連接失敗：" . $conn->connect_error);
}

// 初始化消息變數
$message = "";

// 處理檢查電子郵件是否存在
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['check_email'])) {
    $email = trim($_POST['email']);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "請輸入有效的電子郵件地址。";
    } else {
        // 檢查該電子郵件地址是否存在於資料庫
        $stmt = $conn->prepare("SELECT UID FROM data WHERE Email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // 電子郵件存在，允許用戶更改密碼
            $message = "電子郵件驗證成功，請輸入新密碼。";
            $_SESSION['email_verified'] = $email; // 保存已驗證的電子郵件地址
        } else {
            $message = "該電子郵件地址未註冊。";
        }
    }
}

// 處理更改密碼
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);
    $email = $_SESSION['email_verified'] ?? null;

    if (!$email) {
        $message = "未驗證電子郵件地址，請先驗證。";
    } elseif (empty($new_password) || empty($confirm_password)) {
        $message = "請輸入並確認新密碼。";
    } elseif ($new_password !== $confirm_password) {
        $message = "新密碼與確認密碼不一致。";
    } else {
        // 檢查是否與當前密碼相同
        $stmt = $conn->prepare("SELECT Password FROM data WHERE Email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if (password_verify($new_password, $user['Password'])) {
            $message = "新密碼不能與當前密碼相同，請嘗試其他密碼。";
        } else {
            // 更新用戶的密碼
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE data SET Password = ? WHERE Email = ?");
            $stmt->bind_param("ss", $hashed_password, $email);
            if ($stmt->execute()) {
                // 成功更改密碼，跳轉至 Login.php
                unset($_SESSION['email_verified']); // 清除已驗證的電子郵件地址
                header("Location: Login.php");
                exit();
            } else {
                // 更改失敗
                $message = "密碼更改失敗，請稍後再試。";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>忘記密碼</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .reset-container {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
        }
        .reset-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .reset-container input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .reset-container button {
            width: 100%;
            padding: 10px;
            background: #007BFF;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .reset-container button:hover {
            background: #0056b3;
        }
        .message {
            text-align: center;
            margin-top: 10px;
            color: red;
        }
    </style>
</head>
<body>
    <div class="reset-container">
        <h2>忘記密碼</h2>

        <?php if ($message): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <?php if (!isset($_SESSION['email_verified'])): ?>
            <!-- 驗證電子郵件 -->
            <form method="POST" action="">
                <input type="email" name="email" placeholder="輸入您的電子郵件地址" required>
                <button type="submit" name="check_email">驗證電子郵件</button>
            </form>
        <?php else: ?>
            <!-- 更改密碼 -->
            <form method="POST" action="">
                <input type="password" name="new_password" placeholder="輸入新密碼" required>
                <input type="password" name="confirm_password" placeholder="確認新密碼" required>
                <button type="submit" name="change_password">更改密碼</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
