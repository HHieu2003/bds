{{-- NÚT BONG BÓNG CHAT GÓC PHẢI DƯỚI --}}
<button id="chatWidgetBtn" class="chat-widget-btn shadow-lg" onclick="toggleChatWindow()">
    <i class="fas fa-comment-dots"></i>
</button>

{{-- KHUNG CỬA SỔ CHAT --}}
<div id="chatWindow" class="chat-window shadow-lg rounded-4 overflow-hidden d-none">
    {{-- Header --}}
    <div class="chat-header text-white p-3 d-flex justify-content-between align-items-center"
        style="background: linear-gradient(135deg, #FF8C42, #FF6B1A);">
        <div class="d-flex align-items-center gap-2">
            <div class="bg-white rounded-circle d-flex align-items-center justify-content-center text-primary"
                style="width: 35px; height: 35px;">
                <i class="fas fa-headset"></i>
            </div>
            <div>
                <h6 class="mb-0 fw-bold">Hỗ trợ trực tuyến</h6>
                <small class="opacity-75" style="font-size: 0.75rem;">Chúng tôi phản hồi trong vài phút</small>
            </div>
        </div>
        <button class="btn-close btn-close-white" onclick="toggleChatWindow()"></button>
    </div>

    {{-- Vùng chứa tin nhắn --}}
    <div class="chat-body p-3 bg-light" id="chatBody" style="height: 350px; overflow-y: auto;">
        {{-- Nơi đổ dữ liệu tin nhắn --}}
        <div class="text-center text-muted small mt-5" id="chatKhachHangChuaDangNhap">
            Vui lòng <a href="#" onclick="openAuthModal('login'); return false;" class="text-primary fw-bold">Đăng
                nhập</a> để bắt đầu trò chuyện với chuyên viên.
        </div>
    </div>

    {{-- Form nhập tin nhắn --}}
    <div class="chat-footer p-2 bg-white border-top">
        <form id="chatForm" onsubmit="sendChatMessage(event)">
            <input type="hidden" id="currentPhienChatId" value="">
            <div class="input-group">
                <input type="text" id="chatInput"
                    class="form-control border-0 shadow-none bg-light rounded-pill px-3" placeholder="Nhập tin nhắn..."
                    disabled>
                <button type="submit"
                    class="btn text-white rounded-circle ms-2 shadow-sm d-flex align-items-center justify-content-center"
                    id="chatSendBtn" style="background-color: #FF8C42; width: 40px; height: 40px;" disabled>
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    /* Nút bong bóng chat */
    .chat-widget-btn {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #FF8C42, #FF6B1A);
        color: white;
        border: none;
        font-size: 1.5rem;
        z-index: 1050;
        cursor: pointer;
        transition: transform 0.3s;
    }

    .chat-widget-btn:hover {
        transform: scale(1.1);
    }

    /* Khung cửa sổ chat */
    .chat-window {
        position: fixed;
        bottom: 100px;
        right: 30px;
        width: 350px;
        background: white;
        z-index: 1050;
        display: flex;
        flex-direction: column;
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.3s ease;
        pointer-events: none;
    }

    .chat-window.show {
        opacity: 1;
        transform: translateY(0);
        pointer-events: all;
    }

    /* Bong bóng tin nhắn */
    .chat-msg {
        max-width: 80%;
        padding: 10px 15px;
        border-radius: 15px;
        margin-bottom: 10px;
        font-size: 0.9rem;
        word-wrap: break-word;
    }

    .chat-msg.msg-admin {
        background-color: #e2e8f0;
        color: #0f172a;
        border-bottom-left-radius: 2px;
        align-self: flex-start;
    }

    .chat-msg.msg-customer {
        background-color: #FF8C42;
        color: white;
        border-bottom-right-radius: 2px;
        align-self: flex-end;
    }

    .chat-row {
        display: flex;
        flex-direction: column;
    }
</style>
<script>
    let chatPollingInterval = null;

    function toggleChatWindow() {
        const win = document.getElementById('chatWindow');
        win.classList.toggle('show');
        win.classList.toggle('d-none');

        if (win.classList.contains('show')) {
            initChat();
        } else {
            clearInterval(chatPollingInterval);
        }
    }

    function initChat() {
        if (!APP.isLoggedIn) {
            document.getElementById('chatKhachHangChuaDangNhap').style.display = 'block';
            return;
        }

        document.getElementById('chatKhachHangChuaDangNhap').style.display = 'none';
        document.getElementById('chatBody').innerHTML =
            '<div class="text-center mt-3"><div class="spinner-border spinner-border-sm text-primary"></div></div>';

        // Gọi API Khởi tạo bằng POST
        fetch(APP.routes.chatKhoiTao, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': APP.csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('currentPhienChatId').value = data.phien_chat_id;
                    document.getElementById('chatInput').disabled = false;
                    document.getElementById('chatSendBtn').disabled = false;

                    // Lấy lịch sử chat và bắt đầu Polling 3s/lần
                    fetchMessages();
                    clearInterval(chatPollingInterval);
                    chatPollingInterval = setInterval(fetchMessages, 3000);
                }
            });
    }

    function fetchMessages() {
        const phienChatId = document.getElementById('currentPhienChatId').value;
        if (!phienChatId) return;

        // Gọi API Lịch sử (Khớp với Route của bạn)
        const url = APP.baseUrl + '/chat/lich-su/' + phienChatId;

        fetch(url, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) renderMessages(data.tin_nhans);
            });
    }

    function renderMessages(messages) {
        const body = document.getElementById('chatBody');

        // Tránh giật màn hình nếu không có tin nhắn mới
        const currentMsgs = body.querySelectorAll('.chat-row').length;
        if (currentMsgs === messages.length) return;

        body.innerHTML = '';

        messages.forEach(msg => {
            // Khớp với CSDL của bạn (nguoi_gui = khachhang)
            const isCustomer = msg.nguoi_gui === 'khachhang';
            const msgClass = isCustomer ? 'msg-customer' : 'msg-admin';

            body.innerHTML += `
                <div class="chat-row">
                    <div class="chat-msg ${msgClass}">${msg.noi_dung}</div>
                </div>
            `;
        });

        // Luôn cuộn xuống dòng cuối cùng
        body.scrollTop = body.scrollHeight;
    }

    function sendChatMessage(e) {
        e.preventDefault();
        const input = document.getElementById('chatInput');
        const phienChatId = document.getElementById('currentPhienChatId').value;
        const noiDung = input.value.trim();

        if (!noiDung || !phienChatId) return;
        input.value = '';

        // Gửi tin nhắn lên Server bằng POST
        fetch(APP.routes.chatGui, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': APP.csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    phien_chat_id: phienChatId,
                    noi_dung: noiDung
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    fetchMessages(); // Refresh tin nhắn lập tức
                }
            });
    }
</script>
