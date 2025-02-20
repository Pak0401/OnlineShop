document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("addProductForm").addEventListener("submit", function(event) {
        event.preventDefault(); // 防止表單提交刷新頁面

        let formData = new FormData(this);

        fetch("../Api/add-product.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById("responseMessage").innerText = data.message;
            if (data.status === "success") {
                this.reset(); // 清空表單
                setTimeout(() => {
                    location.reload(); // 重新載入頁面
                }, 1000);
            }
        })
        .catch(error => console.error("錯誤:", error));
    });
});
