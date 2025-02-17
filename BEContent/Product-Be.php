<?php
require "../Prod-db.php"; // 連接資料庫

$result = $conn_productdata->query("SELECT * FROM productdata");
?>

<h3>管理數據庫</h3>
<table id="productTable">
    <thead>
        <tr>
            <th>ID</th>
            <th>產品名稱</th>
            <th>價格</th>
            <th>變體</th>
            <th>GID</th>
            <th>描述</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <td><?php echo $row['PID']; ?></td>
                <td><?php echo $row['PName']; ?></td>
                <td><?php echo $row['Price']; ?></td>
                <td><?php echo $row['Variant']; ?></td>
                <td><?php echo $row['GID']; ?></td>
                <td><?php echo $row['Description']; ?></td>
                <td>
                    <button class="delete-btn" data-pid="<?php echo $row['PID']; ?>">刪除</button>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<h3>新增產品</h3>
<input type="text" id="productName" placeholder="產品名稱">
<input type="number" id="productPrice" placeholder="價格">
<input type="text" id="productVariant" placeholder="變體">
<input type="number" id="productGID" placeholder="GID">
<input type="text" id="productDescription" placeholder="描述">
<button onclick="addProduct()">新增產品</button>

<script src="Script/Product-Be.js"></script>
