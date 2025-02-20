document.addEventListener("DOMContentLoaded", function () {
    const selectPID = document.getElementById("selectPID");
    const quantityInput = document.getElementById("Quantity");
    const updateButton = document.getElementById("updateInventoryBtn");

    // 建立一個物件來存放 PID 對應的數量
    const inventoryData = {};

    // 讀取所有 <option> 的資料
    selectPID.querySelectorAll("option").forEach(option => {
        const pid = option.value;
        const quantity = option.getAttribute("data-quantity");
        inventoryData[pid] = quantity;
    });

    // 監聽 `select` 變更事件，更新數量輸入框
    selectPID.addEventListener("change", function () {
        const selectedPID = selectPID.value;
        quantityInput.value = inventoryData[selectedPID] || "";
    });

    // 預設載入時顯示第一個產品的數量
    if (selectPID.options.length > 0) {
        quantityInput.value = inventoryData[selectPID.value] || "";
    }

    // 監聽「確定」按鈕，發送 AJAX 請求更新庫存
    updateButton.addEventListener("click", function (event) {
        event.preventDefault(); // 防止表單預設提交行為

        const selectedPID = selectPID.value;
        const updatedQuantity = quantityInput.value;

        if (!selectedPID || !updatedQuantity) {
            alert("請選擇產品並輸入數量！");
            return;
        }

        // 準備請求資料
        const formData = new FormData();
        formData.append("PID", selectedPID);
        formData.append("Quantity", updatedQuantity);

        fetch("../Api/edit-inventory.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("庫存更新成功！");
                location.reload(); // 重新整理頁面以顯示更新後的數據
            } else {
                alert("更新失敗：" + data.error);
            }
        })
        .catch(error => console.error("錯誤:", error));
    });
});
