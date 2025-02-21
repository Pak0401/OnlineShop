document.addEventListener("DOMContentLoaded", function () {
    console.log("DOM 加載完成，開始執行 SaveOrder.js");

    let button = document.getElementById("checkout-button");
    if (!button) {
        console.error("找不到 checkout-button，請檢查 HTML！");
    } else {
        button.addEventListener("click", function () {
            alert("按鈕已點擊！");
        });
    }

    if (button) {
        button.addEventListener("click", function() {
            let orderIdElement = document.getElementById("orderIdDisplay");
            let totalPriceElement = document.getElementById("total-price");

            if (!orderIdElement || !totalPriceElement) {
                console.error("找不到 `orderIdDisplay` 或 `total-price`，請檢查 HTML 是否正確");
                return;
            }

            let orderId = orderIdElement.textContent.trim();
            let totalPrice = totalPriceElement.textContent.trim();

            let orderData = {
                order_id: orderId,
                user_id: userId, // 來自 PHP
                items: [],
                total_price: totalPrice
            };

            cart.forEach(item => {
                orderData.items.push({
                    name: item.name,
                    quantity: item.quantity,
                    price: item.price
                });
            });

            fetch("../Api/order-api.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(orderData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    alert("訂單已成功提交！");
                    window.location.href = "payment-page.php";
                } else {
                    alert("提交訂單失敗：" + data.message);
                }
            })
            .catch(error => console.error("Error:", error));
        });
    } else {
        console.error("找不到 `checkout-button` 按鈕，請檢查 HTML 是否正確");
    }
});
