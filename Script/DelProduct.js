document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll(".delete-btn").forEach(button => {
        button.addEventListener("click", function() {
            let pid = this.getAttribute("data-pid");

            if (!confirm("確定要刪除這個產品嗎？")) return;

            fetch("../Api/delete-product.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "pid=" + encodeURIComponent(pid)
            })
            .then(response => response.text())  // 先解析為 text
            .then(data => {
                console.log("伺服器回應:", data); // 檢查回應內容
                return JSON.parse(data);
            })
            .then(parsedData => {
                alert(parsedData.message);
                if (parsedData.status === "success") {
                    location.reload(); // 刪除成功後刷新頁面
                }
            })
            .catch(error => console.error("錯誤:", error));
        });
    });
});
