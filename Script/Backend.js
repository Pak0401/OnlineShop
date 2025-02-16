    document.addEventListener("DOMContentLoaded", function() {
        const sidebar = document.getElementById("sidebar");
        const mainContent = document.querySelector(".main-content");
        const toggleButton = document.querySelector(".toggle-sidebar");

        // 初始化 main-content 的 margin
        if (sidebar.classList.contains("collapsed")) {
            mainContent.style.marginLeft = "60px";
        } else {
            mainContent.style.marginLeft = "250px";
        }

        // 切換側邊欄展開/收起
        window.toggleSidebar = function() {
            sidebar.classList.toggle("collapsed");
            mainContent.style.marginLeft = sidebar.classList.contains("collapsed") ? "60px" : "250px";
            toggleButton.innerHTML = sidebar.classList.contains("collapsed") ? "❯" : "❮";
        };
    });

    // 點擊菜單按鈕時載入對應內容
    const menuItems = document.querySelectorAll(".menu li button");
    menuItems.forEach(button => {
        button.addEventListener("click", function() {
            const contentId = this.getAttribute("onclick").match(/'([^']+)'/)[1]; // 解析onclick
            loadContent(contentId);
        });
    });

    // 假設 loadContent 用來切換內容
    window.loadContent = function(contentId) {
        document.getElementById("page-title").innerText = contentId === "database" ? "管理數據庫" :
            contentId === "orders" ? "確認用戶訂單" :
            contentId === "inventory" ? "庫存表" : "發送郵件";
            
        document.getElementById("content-area").innerHTML = `<p>正在加載 ${contentId}...</p>`;
    };

