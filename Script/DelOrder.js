document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".delete-btn").forEach(button => {
        button.addEventListener("click", function () {
            let orderId = this.getAttribute("data-order_id");

            if (confirm("確定要刪除此訂單嗎？")) {
                // 確保發送的資料是有效的 JSON
                const payload = JSON.stringify({ 
                    order_id: orderId 
                });
                
                console.log("發送的資料:", payload); // 調試用
                
                fetch("../API/deleteOrder.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: payload
                })
                .then(response => {
                    console.log("回應狀態:", response.status);
                    return response.json();
                })
                .then(data => {
                    console.log("回應資料:", data);
                    if (data.success) {
                        alert("訂單已刪除！");
                        this.closest("tr").remove();  // 移除該行
                    } else {
                        alert("刪除失敗：" + (data.message || "未知錯誤"));
                    }
                })
                .catch(error => {
                    console.error("錯誤:", error);
                    alert("刪除時發生錯誤，請查看控制台獲取詳細信息");
                });
            }
        });
    });
});