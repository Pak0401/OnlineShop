```
<?php
// Database configuration
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "OnlineShop";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to handle special characters
$conn->set_charset('utf8');

// ======================================================
// Optional: Start transaction if needed
$conn->autocommit(FALSE);
// ======================================================
// --- Step 1: Insert a new row into the orders table ---
$sql_orders = "INSERT INTO orders (Status, created_at) VALUES (?, NOW())";
$stmt_orders = $conn->prepare($sql_orders);
if (!$stmt_orders) {
    die("Prepare failed for orders: " . $conn->error);
}

$status = "未付款"; // Must exactly match one of the allowed enum values
$stmt_orders->bind_param("s", $status);

if ($stmt_orders->execute()) {
    $order_id = $conn->insert_id;
    error_log("New order inserted, order_id: " . $order_id);

    if ($order_id > 0) {
        // --- Step 2: Insert a new row into the order_items table ---
        $sql_order_items = "INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)";
        $stmt_order_items = $conn->prepare($sql_order_items);
        if (!$stmt_order_items) {
            die("Prepare failed for order_items: " . $conn->error);
        }
        
        $product_id = 123; // Replace with your actual product ID
        $quantity   = 1;   // Replace with actual quantity
        $stmt_order_items->bind_param("iii", $order_id, $product_id, $quantity);
        
        if ($stmt_order_items->execute()) {
            if ($conn->affected_rows > 0) {
                echo "<script>console.log('Insert into order_items was successful.');</script>";
            } else {
                echo "<script>console.log('Insert into order_items executed but no rows were affected.');</script>";
            }
            error_log("Order item inserted for order_id: " . $order_id);
        } else {
            $error_message = addslashes($stmt_order_items->error);
            echo "<script>console.log('Insert into order_items failed: {$error_message}');</script>";
            error_log("Error inserting order item: " . $stmt_order_items->error);
        }
        $stmt_order_items->close();
    } else {
        echo "<script>console.log('Order insert did not return a valid order_id.');</script>";
    }
} else {
    echo "Insert into orders failed: " . $stmt_orders->error;
    error_log("Error inserting order: " . $stmt_orders->error);
}
$stmt_orders->close();

// ======================================================
// Commit the transaction if both inserts are successful
if ($conn->commit()) {
    error_log("Transaction committed successfully.");
} else {
    error_log("Transaction commit failed.");
}
// ======================================================
$conn->close();
?>
```