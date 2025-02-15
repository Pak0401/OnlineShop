<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("無權限訪問！");
}

// 取得所有 `user` 角色的用戶
$stmt = $pdo->query("SELECT id, username, email, role FROM users WHERE role = 'user'");
$users = $stmt->fetchAll();

// 處理升級請求
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];

    $stmt = $pdo->prepare("UPDATE users SET role = 'admin' WHERE id = ?");
    if ($stmt->execute([$user_id])) {
        echo "該用戶已升級為管理員！";
    } else {
        echo "升級失敗，請重試。";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>管理後台 - 升級用戶</title>
</head>
<body>
    <h1>升級用戶為管理員</h1>
    <form method="POST">
        <select name="user_id">
            <?php foreach ($users as $user): ?>
                <option value="<?= $user['id'] ?>"><?= $user['username'] ?> (<?= $user['email'] ?>)</option>
            <?php endforeach; ?>
        </select>
        <button type="submit">升級為管理員</button>
    </form>
</body>
</html>
