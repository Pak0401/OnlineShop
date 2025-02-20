document.addEventListener("DOMContentLoaded", function () {
    console.log("orderNo.js 已載入！");

    // 取得當前日期（格式：YYYYMMDD）
    const today = new Date();
    const dateStr = today.getFullYear().toString() + 
                    String(today.getMonth() + 1).padStart(2, '0') + 
                    String(today.getDate()).padStart(2, '0');

    console.log("今日日期：" + dateStr);

    // 取得當前儲存的訂單數量
    let lastOrderDate = localStorage.getItem("lastOrderDate");
    let orderCount = localStorage.getItem("orderCount");

    if (lastOrderDate !== dateStr) {
        orderCount = 1;
        localStorage.setItem("lastOrderDate", dateStr);
    } else {
        orderCount = parseInt(orderCount) + 1;
    }

    // 格式化訂單編號
    const orderId = `${dateStr}-${String(orderCount).padStart(4, '0')}`;
    localStorage.setItem("orderCount", orderCount);

    console.log("生成的訂單編號：" + orderId);

    // 確保 `orderIdDisplay` 存在
    const orderDisplay = document.getElementById("orderIdDisplay");
    if (orderDisplay) {
        orderDisplay.textContent = orderId;
    } else {
        console.error("找不到 #orderIdDisplay，請檢查 HTML");
    }
});
