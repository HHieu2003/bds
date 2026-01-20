<div id="chat-circle" class="btn btn-primary rounded-circle shadow-lg d-flex align-items-center justify-content-center" 
     style="position: fixed; bottom: 30px; right: 30px; width: 60px; height: 60px; z-index: 9999; cursor: pointer; transition: all 0.3s;">
    <i class="fas fa-comments fs-3 text-white"></i>
    <span class="position-absolute top-0 start-100 translate-middle p-2 bg-danger border border-light rounded-circle d-none" id="chat-badge">
        <span class="visually-hidden">New alerts</span>
    </span>
</div>

<div id="chat-box" class="card shadow-lg border-0 d-none" 
     style="position: fixed; bottom: 100px; right: 30px; width: 360px; height: 500px; z-index: 9999; border-radius: 15px">
    
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
        <div class="d-flex align-items-center">
            <i class="fas fa-headset me-2 fs-5"></i>
            <div>
                <span class="fw-bold d-block lh-1">Hỗ trợ trực tuyến</span>
                <small style="font-size: 0.75rem; opacity: 0.9;">Phản hồi trong 5 phút</small>
            </div>
        </div>
        <button id="chat-close" class="btn btn-sm text-white opacity-75 hover-opacity-100">
            <i class="fas fa-times fa-lg"></i>
        </button>
    </div>

    <div class="card-body p-0 position-relative bg-light" style="height: 100%;">
        
        <div id="step-login" class="position-absolute w-100 h-100 bg-white p-4 d-flex flex-column justify-content-center z-2">
            <div class="text-center mb-4">
                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                    <i class="fas fa-user-shield fs-3"></i>
                </div>
                <h5 class="fw-bold text-dark">Xin chào quý khách!</h5>
                <p class="text-muted small">Vui lòng để lại thông tin để nhân viên tư vấn hỗ trợ tốt nhất.</p>
            </div>
            <div class="mb-3">
                <input type="text" id="login-name" class="form-control form-control-lg fs-6" placeholder="Tên của bạn...">
            </div>
            <div class="mb-3">
                <input type="text" id="login-phone" class="form-control form-control-lg fs-6" placeholder="Số điện thoại (Zalo)...">
            </div>
            <button id="btn-get-otp" class="btn btn-primary w-100 py-2 fw-bold shadow-sm">
                <i class="fas fa-paper-plane me-2"></i>Bắt đầu chat
            </button>
        </div>

        <div id="step-verify" class="position-absolute w-100 h-100 bg-white p-4 d-flex flex-column justify-content-center d-none z-3">
            <div class="text-center mb-4">
                <div class="bg-success bg-opacity-10 text-success rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                    <i class="fas fa-sms fs-3"></i>
                </div>
                <h5 class="fw-bold">Xác thực SĐT</h5>
                <p class="text-muted small">Mã OTP đã được gửi đến SĐT của bạn (Check Log Demo).</p>
            </div>
            <div class="mb-4">
                <input type="text" id="verify-otp" class="form-control form-control-lg text-center letter-spacing-2 fw-bold" placeholder="• • • • • •" maxlength="6">
            </div>
            <button id="btn-verify-otp" class="btn btn-success w-100 py-2 fw-bold shadow-sm mb-3">Xác thực ngay</button>
            <button id="btn-back-login" class="btn btn-link text-decoration-none text-muted small w-100">Quay lại nhập SĐT</button>
        </div>

        <div id="step-chat" class="d-flex flex-column h-100 d-none z-4">
            <div id="chat-messages" class="flex-grow-1 p-3 overflow-auto" style="background-color: #f0f2f5;">
                <div class="text-center mt-4">
                    <span class="badge bg-secondary opacity-50 fw-normal">Cuộc trò chuyện được bảo mật</span>
                </div>
            </div>

            <div class="p-2 bg-white border-top">
                <div class="input-group">
                    <input type="text" id="chat-input" class="form-control border-0 bg-light rounded-pill px-3 py-2" placeholder="Nhập tin nhắn..." style="box-shadow: none;">
                    <button id="btn-send" class="btn btn-link text-primary pe-2">
                        <i class="fas fa-paper-plane fa-lg"></i>
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    .letter-spacing-2 { letter-spacing: 4px; }
    .z-4 { z-index: 4 !important; }
    #chat-circle:hover { transform: scale(1.1); }
    
    /* Scrollbar */
    #chat-messages::-webkit-scrollbar { width: 6px; }
    #chat-messages::-webkit-scrollbar-track { background: #f1f1f1; }
    #chat-messages::-webkit-scrollbar-thumb { background: #ccc; border-radius: 3px; }
    
    /* Bubble Chat */
    .msg-row { display: flex; margin-bottom: 12px; flex-direction: column; }
    .msg-bubble { padding: 8px 14px; border-radius: 18px; max-width: 80%; word-wrap: break-word; font-size: 0.95rem; position: relative; }
    
    /* Tin nhắn Admin/Sale */
    .msg-admin { align-items: flex-start; }
    .msg-admin .msg-bubble { background-color: #e4e6eb; color: #050505; border-bottom-left-radius: 4px; }
    .msg-admin-name { font-size: 0.7rem; color: #65676b; margin-left: 10px; margin-bottom: 2px; }

    /* Tin nhắn User */
    .msg-user { align-items: flex-end; }
    .msg-user .msg-bubble { background-color: #0084ff; color: white; border-bottom-right-radius: 4px; }
    .msg-time { font-size: 0.65rem; margin-top: 2px; opacity: 0.7; padding: 0 5px; }
    .msg-user .msg-time { text-align: right; }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // === CẤU HÌNH ===
    const ROUTES = {
        start: "{{ route('chat.start') }}",
        verify: "{{ route('chat.verify') }}",
        send: "{{ route('chat.send') }}",
        messages: "{{ route('chat.messages') }}"
    };
    
    // Setup CSRF cho Ajax
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    // Biến toàn cục
    let chatInterval = null;
    let sessionId = localStorage.getItem('chat_session_id');

    // === 1. XỬ LÝ GIAO DIỆN (UI) ===
    
    // Mở/Đóng chat
    $('#chat-circle').click(function() {
        $('#chat-circle').addClass('d-none');
        $('#chat-box').removeClass('d-none').addClass('d-flex flex-column');
        checkSessionState();
    });

    $('#chat-close').click(function() {
        $('#chat-box').addClass('d-none').removeClass('d-flex');
        $('#chat-circle').removeClass('d-none');
    });

    // === 2. LOGIC TƯƠNG TÁC ===

    // A. Kiểm tra session khi mở box
    function checkSessionState() {
        if (!sessionId) {
            showScreen('login');
            return;
        }

        // Nếu có ID, thử load tin nhắn để check ID còn sống không
        $.get(ROUTES.messages, { chat_session_id: sessionId })
            .done(function(msgs) {
                showScreen('chat');
                renderMessages(msgs);
                startPolling(); // Bắt đầu quét tin mới
            })
            .fail(function() {
                // Session lỗi hoặc hết hạn -> Xóa và về Login
                localStorage.removeItem('chat_session_id');
                sessionId = null;
                showScreen('login');
            });
    }

    // B. Gửi yêu cầu bắt đầu (Lấy OTP)
    $('#btn-get-otp').click(function() {
        const name = $('#login-name').val().trim();
        const phone = $('#login-phone').val().trim();

        if (!name || !phone) {
            alert('Vui lòng nhập đầy đủ Tên và Số điện thoại.');
            return;
        }

        $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Đang gửi...');

        $.post(ROUTES.start, {
            name: name,
            phone: phone,
            current_url: window.location.href
        }).done(function(res) {
            if (res.status === 'otp_sent') {
                showScreen('verify');
                // Lưu tạm sđt để verify
                $('#verify-otp').data('phone', phone); 
            }
        }).fail(function(xhr) {
            alert('Lỗi: ' + (xhr.responseJSON?.message || 'Không thể kết nối server'));
        }).always(function() {
            $('#btn-get-otp').prop('disabled', false).html('<i class="fas fa-paper-plane me-2"></i>Bắt đầu chat');
        });
    });

    // C. Xác thực OTP
    $('#btn-verify-otp').click(function() {
        const otp = $('#verify-otp').val().trim();
        const phone = $('#verify-otp').data('phone');

        if (otp.length < 6) {
            alert('Mã OTP không hợp lệ');
            return;
        }

        $.post(ROUTES.verify, { phone: phone, otp: otp })
            .done(function(res) {
                if (res.status === 'success') {
                    sessionId = res.session_id;
                    localStorage.setItem('chat_session_id', sessionId);
                    
                    showScreen('chat');
                    renderMessages(res.history); // Load lịch sử nếu có
                    startPolling();
                }
            })
            .fail(function(xhr) {
                alert(xhr.responseJSON?.message || 'Mã OTP sai hoặc đã hết hạn.');
            });
    });

    // Quay lại màn hình Login
    $('#btn-back-login').click(() => showScreen('login'));

    // D. Gửi tin nhắn
    function sendMessage() {
        const msg = $('#chat-input').val().trim();
        if (!msg) return;

        // UI Optimistic: Hiện tin nhắn ngay lập tức
        appendMessage({ message: msg, user_id: null, created_at: new Date().toISOString() });
        $('#chat-input').val('').focus();
        scrollToBottom();

        $.post(ROUTES.send, {
            chat_session_id: sessionId,
            message: msg
        }).fail(function() {
            alert('Lỗi gửi tin nhắn. Vui lòng thử lại.');
        });
    }

    $('#btn-send').click(sendMessage);
    $('#chat-input').keypress(function(e) {
        if (e.which == 13) sendMessage();
    });

    // === 3. CÁC HÀM HỖ TRỢ ===

    function showScreen(screenName) {
        $('#step-login, #step-verify, #step-chat').addClass('d-none');
        $('#step-' + screenName).removeClass('d-none');
    }

    function startPolling() {
        if (chatInterval) clearInterval(chatInterval);
        
        // Polling mỗi 3 giây
        chatInterval = setInterval(function() {
            if ($('#chat-box').hasClass('d-none')) return; // Không poll khi đóng box

            $.get(ROUTES.messages, { chat_session_id: sessionId })
                .done(function(msgs) {
                    renderMessages(msgs);
                });
        }, 3000);
    }

    function renderMessages(messages) {
        // Cách đơn giản nhất: Xóa hết render lại để tránh trùng lặp
        // (Có thể tối ưu bằng cách check ID tin nhắn cuối cùng)
        const container = $('#chat-messages');
        const isAtBottom = container.scrollTop() + container.innerHeight() >= container[0].scrollHeight - 50;

        container.html('<div class="text-center mt-4"><span class="badge bg-secondary opacity-50 fw-normal">Cuộc trò chuyện được bảo mật</span></div>');
        
        if (!messages || messages.length === 0) return;

        messages.forEach(msg => {
            const isMe = msg.user_id === null; // Null là khách (User)
            const type = isMe ? 'msg-user' : 'msg-admin';
            const name = isMe ? '' : `<div class="msg-admin-name">Tư vấn viên</div>`;
            const time = new Date(msg.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});

            const html = `
                <div class="msg-row ${type}">
                    ${name}
                    <div class="msg-bubble shadow-sm">
                        ${msg.message}
                    </div>
                    <div class="msg-time text-muted">${time}</div>
                </div>
            `;
            container.append(html);
        });

        // Chỉ auto scroll nếu người dùng đang ở dưới cùng
        if (isAtBottom) scrollToBottom();
    }
    
    // Hàm append 1 tin nhắn (dùng cho optimistic UI)
    function appendMessage(msg) {
        const container = $('#chat-messages');
        const time = new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
        const html = `
            <div class="msg-row msg-user">
                <div class="msg-bubble shadow-sm" style="opacity: 0.7;">
                    ${msg.message} <i class="fas fa-spinner fa-spin fa-xs ms-1"></i>
                </div>
                <div class="msg-time text-muted">${time}</div>
            </div>
        `;
        container.append(html);
    }

    function scrollToBottom() {
        const d = $('#chat-messages');
        d.animate({ scrollTop: d.prop("scrollHeight") }, 300);
    }
});
</script>