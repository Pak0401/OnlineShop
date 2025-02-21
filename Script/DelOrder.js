document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".delete-btn").forEach(button => {
        button.addEventListener("click", function () {
            let orderId = this.getAttribute("data-order_id");

            if (confirm("確定要刪除此訂單嗎？")) {
                fetch("../API/deleteOrder.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({ order_id: orderId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert("訂單已刪除！");
                        this.closest("tr").remove();  // 移除該行
                    } else {
                        alert("刪除失敗：" + data.message);
                    }
                })
                .catch(error => {
                    console.error("錯誤:", error);
                    alert("刪除時發生錯誤，請稍後再試");
                });
            }
        });
    });
});
