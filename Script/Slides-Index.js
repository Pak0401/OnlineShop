let slideIndex = 1;
showslides(slideIndex);

function plusSlides(n) {
    showslides(slideIndex += n);
}

function currentSlide(n) {
    showslides(slideIndex = n);
}

function showslides(n) {
    let i;
    let slides = document.getElementsByClassName("slidesImg");
    console.log("找到幻燈片數量：" + slides.length); // 檢查找到了多少張幻燈片
    
    if (slides.length === 0) return; // 如果沒有幻燈片則返回
    
    if (n > slides.length) {slideIndex = 1}
    if (n < 1) {slideIndex = slides.length}
    
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }
    
    // 確保slideIndex在有效範圍內
    if (slideIndex > 0 && slideIndex <= slides.length) {
        slides[slideIndex - 1].style.display = "block";
    }
}

// 頁面加載時初始化
document.addEventListener('DOMContentLoaded', function() {
    showslides(slideIndex);
});

// 自動輪播
setInterval(function() {
    plusSlides(1);
}, 5000); // 每5秒切換一張圖片