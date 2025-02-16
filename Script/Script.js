document.addEventListener("DOMContentLoaded", function () {
    const chatButton = document.getElementById("chatButton");
    const chatBox = document.getElementById("chatBox");
    const closeButton = document.getElementById("closeButton");
    const sendButton = document.getElementById("sendButton");
    const chatInput = document.getElementById("chatInput");
    const chatContent = document.getElementById("chatContent");

    chatButton.addEventListener("click", () => {
        chatBox.style.display = chatBox.style.display === "none" ? "block" : "none";
    });

    closeButton.addEventListener("click", () => {
        chatBox.style.display = "none";
    });

    sendButton.addEventListener("click", () => sendMessage());

    chatInput.addEventListener("keypress", (e) => {
        if (e.key === "Enter") {
            sendMessage();
        }
    });

    function sendMessage() {
        const message = chatInput.value.trim();
        if (!message) return;

        addMessage("您", message);

        setTimeout(() => {
            // 調用外部 botResponse.js 中的 getBotResponse() 方法
            const botResponse = getBotResponse(message);
            addMessage("客服機器人", botResponse);
        }, 1000);
        
        chatInput.value = "";
    }

    function addMessage(sender, text) {
        const messageElement = document.createElement("p");
        messageElement.innerHTML = `<strong>${sender}:</strong> ${text}`;
        chatContent.appendChild(messageElement);
        chatContent.scrollTop = chatContent.scrollHeight;
    }
});