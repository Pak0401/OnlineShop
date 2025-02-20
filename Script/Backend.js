document.addEventListener("DOMContentLoaded", function () {
    console.log("Backend.js 已載入");

    const sidebar = document.getElementById("sidebar");
    const mainContent = document.querySelector(".main-content");
    const toggleButton = document.querySelector(".toggle-sidebar");

    // 更新主內容邊距
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
});