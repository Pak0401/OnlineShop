function getBotResponse(input) {
    const lowerInput = input.toLowerCase();

    const responses = {
        "你好": "你好！請問有什麼可以幫助您的？",
        "請問你們的營業時間？": "我們的營業時間是每天 9:00 AM - 6:00 PM。",
        "你們有提供退貨嗎？": "我們提供 7 天無條件退貨，請提供您的訂單號。",
        "運送要多久？": "一般來說，運送時間約 3-5 天。",
        "再見": "感謝您的查詢，祝您有美好的一天！"
    };

    for (let key in responses) {
        if (lowerInput.includes(key)) {
            return responses[key];
        }
    }

    return "很抱歉，我不太明白您的問題，請稍等，我們的客服人員將會回覆您！";
}