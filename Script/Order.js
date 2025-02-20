document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("updateOrderForm").addEventListener("submit", function(event) {
        event.preventDefault(); // 防止表單提交刷新頁面

        let orderID = document.getElementById("selectOrderID").value;
        let newStatus = document.getElementById("newStatus").value;
        let newShipmentStatus = document.getElementById("newShipmentStatus").value;

        let formData = new FormData();
        formData.append("order_id", orderID);
        formData.append("status", newStatus);
        formData.append("shipment_status", newShipmentStatus);

        fetch("../Api/updateOrder.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById("updateResponseMessage").innerText = data.message;
            if (data.status === "success") {
                setTimeout(() => {
                    location.reload();
                }, 1000); // 1秒後刷新頁面
            }
        })
        .catch(error => console.error("錯誤:", error));
    });
});
