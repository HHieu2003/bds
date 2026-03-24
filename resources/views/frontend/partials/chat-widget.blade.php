{{-- NÚT BONG BÓNG CHAT GÓC PHẢI DƯỚI --}}
<button id="chatWidgetBtn" class="chat-widget-btn shadow-lg" onclick="toggleChatWindow()">
    <i class="fas fa-comment-dots fs-3"></i>
    <span class="position-absolute top-0 start-100 translate-middle p-2 bg-danger border border-light rounded-circle"
        id="chatUnreadBadge" style="display:none;"></span>
</button>

{{-- KHUNG CỬA SỔ CHAT FRONTEND --}}
<div id="chatWindow" class="chat-window shadow-lg rounded-4 overflow-hidden d-none">
    {{-- Header --}}
    <div class="chat-header p-3 d-flex justify-content-between align-items-center position-relative z-1"
        style="background:linear-gradient(135deg,#0F172A,#1A2948);">
        <div class="d-flex align-items-center gap-3">
            <div class="position-relative">
                <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center shadow-sm"
                    style="width:40px;height:40px;font-size:1.2rem;">
                    <i class="fas fa-headset" style="color:#FF8C42;"></i>
                </div>
                <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-white rounded-circle"></span>
            </div>
            <div class="text-white">
                <h6 class="mb-0 fw-bold" style="font-size:1rem;">CSKH Thành Công Land</h6>
                <small class="opacity-75" style="font-size:0.75rem;">
                    <i class="fas fa-bolt text-warning me-1"></i>Thường trả lời ngay
                </small>
            </div>
        </div>
        <button class="btn btn-sm text-white opacity-75" onclick="toggleChatWindow()" style="box-shadow:none;">
            <i class="fas fa-times fs-5"></i>
        </button>
    </div>

    {{-- Body --}}
    <div class="chat-body p-3 d-flex flex-column" id="chatBody">
        <div class="d-flex flex-column justify-content-center align-items-center h-100 text-center"
            id="chatKhachHangChuaDangNhap" style="display:none !important;">
            <div class="bg-light rounded-circle p-3 mb-3">
                <i class="fas fa-user-lock fs-2 text-muted"></i>
            </div>
            <h6 class="fw-bold text-dark mb-2">Xin chào quý khách!</h6>
            <p class="small text-muted mb-3">Vui lòng đăng nhập để lưu trữ cuộc trò chuyện.</p>
            <button onclick="openAuthModal('login')" class="btn rounded-pill px-4 text-white fw-bold shadow-sm"
                style="background-color:#FF8C42;">
                Đăng nhập ngay
            </button>
        </div>
    </div>

    {{-- Footer --}}
    <div class="chat-footer p-2 bg-white border-top z-1">
        <form id="chatForm" onsubmit="sendFrontendMessage(event)">
            <input type="hidden" id="currentPhienChatId" value="">
            <div class="input-group bg-light rounded-pill p-1 border">
                <input type="text" id="chatInput" class="form-control border-0 shadow-none bg-transparent ps-3"
                    placeholder="Nhập câu hỏi..." disabled autocomplete="off">
                <button type="submit"
                    class="btn rounded-pill d-flex align-items-center justify-content-center text-white"
                    id="chatSendBtn" style="background-color:#FF8C42;width:40px;height:40px;" disabled>
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    .chat-widget-btn {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 65px;
        height: 65px;
        border-radius: 50%;
        background: linear-gradient(135deg, #FF8C42, #FF6B1A);
        color: white;
        border: none;
        z-index: 1050;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    }

    .chat-widget-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 10px 25px rgba(255, 140, 66, 0.4) !important;
    }

    .chat-window {
        position: fixed;
        bottom: 110px;
        right: 30px;
        width: 360px;
        height: 500px;
        max-height: 70vh;
        background: #f8fafc;
        z-index: 1050;
        display: flex;
        flex-direction: column;
        opacity: 0;
        transform: translateY(20px) scale(0.95);
        transform-origin: bottom right;
        transition: all 0.3s cubic-bezier(0.19, 1, 0.22, 1);
        pointer-events: none;
    }

    .chat-window.show {
        opacity: 1;
        transform: translateY(0) scale(1);
        pointer-events: all;
    }

    @media (max-width: 576px) {
        .chat-window {
            width: calc(100% - 40px);
            right: 20px;
            bottom: 100px;
        }

        .chat-widget-btn {
            width: 55px;
            height: 55px;
            right: 20px;
            bottom: 20px;
        }
    }

    .chat-body {
        flex-grow: 1;
        overflow-y: auto;
        background-image: url('https://www.transparenttextures.com/patterns/cubes.png');
    }

    .chat-body::-webkit-scrollbar {
        width: 5px;
    }

    .chat-body::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }

    .fe-chat-row {
        display: flex;
        flex-direction: column;
        width: 100%;
        margin-bottom: 15px;
    }

    .fe-msg-bubble {
        max-width: 80%;
        padding: 10px 15px;
        font-size: 0.95rem;
        line-height: 1.4;
        word-wrap: break-word;
        position: relative;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }

    .fe-msg-system {
        background: rgba(0, 0, 0, 0.04);
        color: #64748B;
        font-size: 0.8rem;
        padding: 6px 12px;
        border-radius: 20px;
        align-self: center;
        text-align: center;
        box-shadow: none;
        max-width: 90%;
    }

    .fe-msg-customer {
        background: linear-gradient(135deg, #FF8C42, #FF6B1A);
        color: white;
        border-radius: 18px 18px 4px 18px;
        align-self: flex-end;
    }

    .fe-msg-admin {
        background: white;
        color: #0F172A;
        border-radius: 18px 18px 18px 4px;
        align-self: flex-start;
        border: 1px solid #e2e8f0;
    }

    .fe-msg-time {
        font-size: 0.7rem;
        margin-top: 5px;
        opacity: 0.8;
    }

    .fe-msg-customer .fe-msg-time {
        text-align: right;
        color: rgba(255, 255, 255, 0.9);
    }

    .fe-msg-admin .fe-msg-time {
        text-align: left;
        color: #94a3b8;
    }
</style>

<script>
    let feChatPolling = null;
    let feLastMessageCount = 0;

    function toggleChatWindow() {
        const win = document.getElementById('chatWindow');
        const badge = document.getElementById('chatUnreadBadge');
        win.classList.toggle('show');
        win.classList.toggle('d-none');
        if (win.classList.contains('show')) {
            badge.style.display = 'none';
            initFrontendChat();
        } else {
            clearInterval(feChatPolling);
        }
    }

    function initFrontendChat() {
        if (!window.APP || !APP.isLoggedIn) {
            document.getElementById('chatKhachHangChuaDangNhap').style.display = 'flex';
            return;
        }

        document.getElementById('chatKhachHangChuaDangNhap').style.display = 'none';

        if (feLastMessageCount === 0) {
            document.getElementById('chatBody').innerHTML =
                '<div class="d-flex justify-content-center align-items-center h-100"><div class="spinner-grow text-primary spinner-grow-sm me-2"></div><span class="text-muted small">Đang kết nối hệ thống...</span></div>';
        }

        fetch(APP.routes.chatKhoiTao, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': APP.csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(async res => {
                // NẾU CÓ LỖI TỪ SERVER, LẤY NỘI DUNG LỖI ĐỂ HIỂN THỊ
                if (!res.ok) {
                    const text = await res.text();
                    throw new Error("Lỗi Server " + res.status + ": " + text.substring(0, 150) + "...");
                }
                return res.json();
            })
            .then(data => {
                if (data.success) {
                    document.getElementById('currentPhienChatId').value = data.phien_chat_id;
                    document.getElementById('chatInput').disabled = false;
                    document.getElementById('chatSendBtn').disabled = false;

                    fetchFrontendMessages();

                    clearInterval(feChatPolling);
                    feChatPolling = setInterval(fetchFrontendMessages, 3000);
                }
            })
            .catch(err => {
                console.error('Lỗi khởi tạo chat:', err);
                // IN LỖI RA MÀN HÌNH THAY VÌ XOAY TRÒN
                document.getElementById('chatBody').innerHTML =
                    `<div class="d-flex flex-column align-items-center justify-content-center h-100 text-center p-3">
                    <i class="fas fa-exclamation-triangle text-danger fs-2 mb-2"></i>
                    <h6 class="text-dark fw-bold">Không thể kết nối</h6>
                    <small class="text-danger text-break">${err.message}</small>
                </div>`;
            });
    }

    function fetchFrontendMessages() {
        const phienChatId = document.getElementById('currentPhienChatId').value;
        if (!phienChatId) return;
        const url = APP.baseUrl.replace(/\/$/, '') + '/chat/lich-su/' + phienChatId;
        fetch(url, {
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) renderFrontendMessages(data.tin_nhans);
            })
            .catch(err => console.log('Lỗi lấy lịch sử:', err));
    }

    function renderFrontendMessages(messages) {
        const body = document.getElementById('chatBody');
        if (messages.length === feLastMessageCount && feLastMessageCount > 0) return;
        feLastMessageCount = messages.length;
        body.innerHTML = '';
        messages.forEach(msg => {
            const d = new Date(msg.created_at);
            const t = ('0' + d.getHours()).slice(-2) + ':' + ('0' + d.getMinutes()).slice(-2);
            let cls = '',
                time = '';
            if (msg.nguoi_gui === 'hethong') {
                cls = 'fe-msg-system';
            } else if (msg.nguoi_gui === 'nhanvien') {
                cls = 'fe-msg-admin';
                time = `<div class="fe-msg-time">${t}</div>`;
            } else {
                cls = 'fe-msg-customer';
                time = `<div class="fe-msg-time">${t} <i class="fas fa-check-double ms-1"></i></div>`;
            }
            body.innerHTML +=
                `<div class="fe-chat-row"><div class="fe-msg-bubble ${cls}">${msg.noi_dung}${time}</div></div>`;
        });
        body.scrollTo({
            top: body.scrollHeight,
            behavior: 'smooth'
        });
    }

    function sendFrontendMessage(e) {
        e.preventDefault();
        const input = document.getElementById('chatInput');
        const phienId = document.getElementById('currentPhienChatId').value;
        const noiDung = input.value.trim();
        if (!noiDung || !phienId) return;
        input.value = '';
        const body = document.getElementById('chatBody');
        const t = ('0' + new Date().getHours()).slice(-2) + ':' + ('0' + new Date().getMinutes()).slice(-2);
        body.innerHTML +=
            `<div class="fe-chat-row"><div class="fe-msg-bubble fe-msg-customer" style="opacity:.7">${noiDung}<div class="fe-msg-time">${t} <i class="far fa-clock ms-1"></i></div></div></div>`;
        body.scrollTo({
            top: body.scrollHeight,
            behavior: 'smooth'
        });
        fetch(APP.routes.chatGui, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': APP.csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    phien_chat_id: phienId,
                    noi_dung: noiDung
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) fetchFrontendMessages();
            });
    }
</script>
