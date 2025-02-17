document.addEventListener("DOMContentLoaded", function () {
    console.log("Backend.js 已載入");

    const sidebar = document.getElementById("sidebar");
    const mainContent = document.querySelector(".main-content");
    const toggleButton = document.querySelector(".toggle-sidebar");

    // 初始化 main-content 的 margin
    function updateMainContentMargin() {
        mainContent.style.marginLeft = sidebar.classList.contains("collapsed") ? "60px" : "250px";
    }
    updateMainContentMargin();

    // 側邊欄展開/收起
    window.toggleSidebar = function () {
        sidebar.classList.toggle("collapsed");
        updateMainContentMargin();
        toggleButton.innerHTML = sidebar.classList.contains("collapsed") ? "❯" : "❮";
    };

    // AJAX 載入內容
    window.loadContent = function (page, element = null) {
        console.log(`正在加載: ${page}`);

        fetch(page)
            .then(response => {
                if (!response.ok) throw new Error("HTTP error, status = " + response.status);
                return response.text();
            })
            .then(data => {
                document.getElementById("content-area").innerHTML = data;

                // 更新標題
                let titleElement = document.getElementById("page-title");
                if (titleElement) {
                    titleElement.innerText =
                        page.includes("Product-Be") ? "管理數據庫" :
                        page.includes("Order") ? "訂單管理" :
                        page.includes("Inventory") ? "庫存表" :
                        page.includes("Email") ? "發送郵件" : "後台管理";
                }

                // 移除所有按鈕的 active 樣式
                document.querySelectorAll(".sidebar-btn").forEach(btn => btn.classList.remove("active"));

                // 設定當前點擊的按鈕為 active
                if (element) {
                    element.classList.add("active");
                } else {
                    let matchedButton = [...document.querySelectorAll(".sidebar-btn")].find(
                        btn => btn.getAttribute("data-page") === page
                    );
                    if (matchedButton) matchedButton.classList.add("active");
                }

                // 確保 `addProduct()` 存在於 `Product-Be.php` 頁面
                if (page.includes("Product-Be")) {
                    console.log("Product-Be 內容已載入，綁定 addProduct()");

                    window.addProduct = function () {
                        let name = document.getElementById("productName").value;
                        let price = document.getElementById("productPrice").value;
                        let variant = document.getElementById("productVariant").value;
                        let gid = document.getElementById("productGID").value;
                        let description = document.getElementById("productDescription").value;

                        if (!name || !price || !variant || !gid || !description) {
                            alert("請填寫所有欄位！");
                            return;
                        }

                        let formData = `action=add&name=${encodeURIComponent(name)}&price=${encodeURIComponent(price)}&variant=${encodeURIComponent(variant)}&gid=${encodeURIComponent(gid)}&description=${encodeURIComponent(description)}`;

                        console.log("發送請求:", formData); // 確保請求有送出

                        fetch("Be-Api/Product-api.php", {
                            method: "POST",
                            headers: { "Content-Type": "application/x-www-form-urlencoded" },
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            console.log("API 回應:", data); // 確保 API 有回應

                            if (data.status === "success") {
                                alert("產品新增成功！");
                                loadContent("BEContent/Product-Be.php"); // 重新載入
                            } else {
                                alert("新增失敗：" + data.error);
                            }
                        })
                        .catch(error => console.error("錯誤:", error));
                    };
                }
            })
            .catch(error => {
                console.error("載入錯誤:", error);
                document.getElementById("content-area").innerHTML = `<p style="color: red;">錯誤: 找不到頁面: ${page}</p>`;
            });
    };

    // 預設載入 "管理數據庫"
    loadContent("BEContent/Product-Be.php");
});
