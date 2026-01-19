<div id="chat-circle" class="btn btn-primary rounded-circle shadow-lg d-flex align-items-center justify-content-center" 
     style="position: fixed; bottom: 30px; right: 30px; width: 60px; height: 60px; z-index: 9999; cursor: pointer;">
    <i class="fa-solid fa-comments fs-3"></i>
</div>

<div id="chat-box" class="card shadow-lg border-0 d-none" 
     style="position: fixed; bottom: 100px; right: 30px; width: 350px; height: 450px; z-index: 9999; border-radius: 15px;">
    
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center" style="border-radius: 15px 15px 0 0;">
        <span class="fw-bold"><i class="fa-solid fa-headset me-2"></i>Hỗ trợ trực tuyến</span>
        <button id="chat-close" class="btn btn-sm text-white"><i class="fa-solid fa-xmark"></i></button>
    </div>

    <div id="chat-login" class="card-body d-flex flex-column justify-content-center align-items-center text-center p-4">
        <i class="fa-solid fa-phone-volume text-primary mb-3" style="font-size: 3rem;"></i>
        <h6 class="fw-bold mb-3">Nhập số điện thoại để chat với nhân viên</h6>
        <input type="text" id="user-phone" class="form-control mb-3" placeholder="Số điện thoại của bạn...">
        <button id="btn-start-chat" class="btn btn-primary w-100 fw-bold">Bắt đầu Chat</button>
    </div>

    <div id="chat-interface" class="card-body p-0 d-flex flex-column d-none" style="height: 100%;">
        <div id="chat-messages" class="flex-grow-1 p-3" style="overflow-y: auto; background: #f8f9fa;">
            </div>
        
        <div class="p-2 border-top bg-white">
            <div class="input-group">
                <input type="text" id="chat-input" class="form-control border-0" placeholder="Nhập tin nhắn...">
                <button id="btn-send" class="btn btn-primary"><i class="fa-solid fa-paper-plane"></i></button>
            </div>
        </div>
    </div>
</div>

<style>
    .msg { margin-bottom: 10px; padding: 8px 12px; border-radius: 10px; max-width: 80%; font-size: 0.9rem; }
    .msg-admin { background: #e2e8f0; color: #333; align-self: flex-start; margin-right: auto; }
    .msg-user { background: #0d6efd; color: white; align-self: flex-end; margin-left: auto; }
    #chat-messages { display: flex; flex-direction: column; }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Cấu hình AJAX để luôn có CSRF Token
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        let sessionId = localStorage.getItem('chat_session_id');

        // 1. Ẩn/Hiện Box
        $('#chat-circle').click(() => $('#chat-box').removeClass('d-none'));
        $('#chat-close').click(() => $('#chat-box').addClass('d-none'));

        // 2. KIỂM TRA SESSION CŨ (Fix lỗi mất phần nhập SĐT)
        if(sessionId) {
            // Gọi lên server kiểm tra xem ID này còn sống không
            $.get("{{ route('chat.messages') }}", { session_id: sessionId })
                .done(function(data) {
                    // Nếu server trả về OK -> Vào màn hình chat
                    $('#chat-login').addClass('d-none');
                    $('#chat-interface').removeClass('d-none');
                    renderMessages(data);
                    startPolling();
                })
                .fail(function() {
                    // Nếu lỗi (do ID cũ không còn trong DB) -> Xóa storage, bắt nhập lại SĐT
                    console.log('Session cũ không hợp lệ, reset...');
                    localStorage.removeItem('chat_session_id');
                    sessionId = null;
                    $('#chat-login').removeClass('d-none');
                    $('#chat-interface').addClass('d-none');
                });
        }

        // 3. Xử lý nút "Bắt đầu Chat"
        $('#btn-start-chat').click(function(e) {
            e.preventDefault(); // Chặn reload trang
            let phone = $('#user-phone').val();
            
            if(!phone) {
                alert('Vui lòng nhập SĐT');
                return;
            }

            let data = { phone: phone };
            
            // Lấy ngữ cảnh (nếu có)
            if (typeof window.chatContext !== 'undefined') {
                if (window.chatContext.type === 'project') data.project_id = window.chatContext.id;
                else if (window.chatContext.type === 'property') data.property_id = window.chatContext.id;
            }

            $.post("{{ route('chat.start') }}", data)
                .done(function(res) {
                    if(res.status == 'success') {
                        sessionId = res.session_id;
                        localStorage.setItem('chat_session_id', sessionId);
                        $('#chat-login').addClass('d-none');
                        $('#chat-interface').removeClass('d-none');
                        startPolling();
                    }
                })
                .fail(function(xhr) {
                    alert('Lỗi: ' + xhr.responseText);
                });
        });

        // 4. Gửi tin nhắn
        function sendMessage() {
            let msg = $('#chat-input').val();
            if(!msg) return;

            // Hiển thị tạm thời (Optimistic UI)
            $('#chat-messages').append(`<div class="msg msg-user">${msg}</div>`);
            $('#chat-input').val('');
            scrollToBottom();

            $.post("{{ route('chat.send') }}", {
                session_id: sessionId,
                message: msg,
                is_admin: 0
            }).fail(function() {
                alert('Không thể gửi tin nhắn. Phiên chat có thể đã hết hạn.');
                location.reload(); // Load lại để nhập lại SĐT
            });
        }

        $('#btn-send').click(function(e) {
            e.preventDefault(); // Chặn reload trang
            sendMessage();
        });

        $('#chat-input').keypress(function(e) {
            if(e.which == 13) {
                e.preventDefault(); // Chặn reload trang khi ấn Enter
                sendMessage();
            }
        });

        // 5. Hàm hiển thị tin nhắn
        function renderMessages(msgs) {
            $('#chat-messages').html('');
            msgs.forEach(m => {
                let cls = m.is_admin ? 'msg-admin' : 'msg-user';
                $('#chat-messages').append(`<div class="msg ${cls}">${m.message}</div>`);
            });
            scrollToBottom();
        }

        // 6. Polling (Cập nhật tin mới)
        function startPolling() {
            setInterval(function() {
                if(!sessionId || $('#chat-box').hasClass('d-none')) return;
                
                $.get("{{ route('chat.messages') }}", { session_id: sessionId })
                    .done(function(msgs) {
                        // Logic đơn giản: Xóa đi render lại (hoặc bạn có thể check ID để append)
                        // Để đơn giản ta render lại, sau này tối ưu sau
                        renderMessages(msgs);
                    });
            }, 3000);
        }

        function scrollToBottom() {
            let d = $('#chat-messages');
            d.scrollTop(d.prop("scrollHeight"));
        }
    });
</script>