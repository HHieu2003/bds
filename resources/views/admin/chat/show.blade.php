@extends('admin.layout.master')

@section('content')
<div class="container-fluid h-100">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <a href="{{ route('admin.chat.index') }}" class="btn btn-outline-secondary btn-sm mb-2">
                <i class="fas fa-arrow-left"></i> Quay lại danh sách
            </a>
            <h4 class="mb-0 text-gray-800">
                Chat với: <span class="text-primary font-weight-bold">{{ $session->user_name }}</span>
            </h4>
            <div class="small text-muted mt-1">
                <i class="fas fa-phone-alt me-1"></i> {{ $session->user_phone }} 
                <span class="mx-2">|</span> 
                <i class="fas fa-link me-1"></i> Đang xem: <a href="{{ $session->context_url }}" target="_blank" class="text-decoration-none">Link sản phẩm</a>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4" style="height: 70vh;">
        <div class="card-body p-0 d-flex flex-column h-100">
            
            <div id="admin-chat-area" class="flex-grow-1 p-4 overflow-auto bg-light" style="scroll-behavior: smooth;">
                <div id="chat-loading" class="text-center mt-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Đang tải...</span>
                    </div>
                    <p class="text-muted mt-2">Đang đồng bộ tin nhắn...</p>
                </div>
            </div>

            <div class="card-footer bg-white py-3">
                <div class="input-group">
                    <input type="text" id="admin-chat-input" class="form-control" placeholder="Nhập tin nhắn trả lời..." autocomplete="off">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="button" onclick="sendAdminMessage()" id="btn-send">
                            <i class="fas fa-paper-plane"></i> Gửi
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Admin Chat Styles */
    .chat-msg { margin-bottom: 15px; display: flex; flex-direction: column; max-width: 75%; }
    .chat-msg.customer { align-self: flex-start; margin-right: auto; }
    .chat-msg.admin { align-self: flex-end; margin-left: auto; align-items: flex-end; }
    
    .chat-bubble { padding: 10px 15px; border-radius: 15px; position: relative; word-wrap: break-word; font-size: 0.95rem; box-shadow: 0 1px 2px rgba(0,0,0,0.1); }
    .customer .chat-bubble { background-color: #ffffff; color: #333; border-bottom-left-radius: 2px; border: 1px solid #e3e6f0; }
    .admin .chat-bubble { background-color: #4e73df; color: white; border-bottom-right-radius: 2px; }
    
    .chat-info { font-size: 0.75rem; color: #858796; margin-bottom: 4px; }
    .chat-time { font-size: 0.7rem; color: #b7b9cc; margin-top: 4px; }
    .admin .chat-time { text-align: right; }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    const sessionId = {{ $session->id }};
    let pollingInterval;
    let isFirstLoad = true;

    $(document).ready(function() {
        // Cấu hình CSRF Token cho AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Load lần đầu ngay lập tức
        loadAdminMessages();

        // Gửi bằng phím Enter
        $('#admin-chat-input').on('keypress', function(e) {
            if(e.which == 13) sendAdminMessage();
        });

        // Bắt đầu Polling (3 giây/lần)
        pollingInterval = setInterval(loadAdminMessages, 3000);
    });

    function loadAdminMessages() {
        $.ajax({
            url: '{{ route("admin.chat.get_messages") }}', // Đảm bảo route này tồn tại
            method: 'GET',
            data: { chat_session_id: sessionId },
            success: function(messages) {
                renderChat(messages);
                $('#chat-loading').remove(); // Xóa spinner khi tải xong
            },
            error: function(xhr, status, error) {
                console.error("Lỗi tải tin nhắn:", error);
                // Nếu lỗi 500 hoặc 404, hiển thị thông báo để biết
                if(isFirstLoad) {
                    $('#admin-chat-area').html(`
                        <div class="text-center text-danger mt-5">
                            <i class="fas fa-exclamation-triangle fa-2x mb-2"></i><br>
                            Không thể tải tin nhắn.<br>
                            <small>${xhr.responseJSON?.message || error}</small>
                        </div>
                    `);
                    isFirstLoad = false;
                }
            }
        });
    }

    function renderChat(messages) {
        const area = $('#admin-chat-area');
        
        // Lưu vị trí scroll hiện tại để xem có đang ở dưới cùng không
        const isAtBottom = area.scrollTop() + area.innerHeight() >= area[0].scrollHeight - 50;

        // Xóa nội dung cũ để render lại (hoặc bạn có thể dùng logic append thông minh hơn)
        // Ở đây ta xóa loading đi trước nếu còn
        if(messages.length === 0) {
            if(isFirstLoad) area.html('<div class="text-center text-muted mt-5">Chưa có tin nhắn nào.</div>');
            isFirstLoad = false;
            return;
        }

        // Tạo HTML mới
        let htmlContent = '';
        messages.forEach(msg => {
            // Logic: user_id != null là Admin/Sale
            const isMe = msg.user_id !== null; 
            const typeClass = isMe ? 'admin' : 'customer';
            const name = isMe ? 'Bạn' : '{{ $session->user_name }}';
            const avatar = isMe ? '<i class="fas fa-user-tie"></i>' : '<i class="fas fa-user"></i>';
            
            // Format time
            const time = new Date(msg.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});

            htmlContent += `
                <div class="chat-msg ${typeClass}">
                    <div class="chat-info">${isMe ? '' : name}</div>
                    <div class="chat-bubble">
                        ${msg.message}
                    </div>
                    <div class="chat-time">${time}</div>
                </div>
            `;
        });

        // Chỉ cập nhật DOM nếu có sự thay đổi (đơn giản nhất là ghi đè, tối ưu sau)
        // Để tránh giật lag khi polling, ta có thể so sánh độ dài chuỗi HTML hoặc ID tin cuối
        // Ở đây để chắc chắn, ta ghi đè nội dung tin nhắn (trừ spinner)
        area.html(htmlContent);

        // Auto Scroll: Chỉ cuộn xuống nếu là lần đầu hoặc người dùng đang ở dưới cùng
        if (isFirstLoad || isAtBottom) {
            area.scrollTop(area[0].scrollHeight);
        }
        
        isFirstLoad = false;
    }

    function sendAdminMessage() {
        const input = $('#admin-chat-input');
        const btn = $('#btn-send');
        const msg = input.val().trim();
        
        if(!msg) return;

        // Disable nút gửi để tránh spam
        btn.prop('disabled', true);

        $.ajax({
            url: '{{ route("admin.chat.reply") }}',
            method: 'POST',
            data: {
                chat_session_id: sessionId,
                message: msg
            },
            success: function(res) {
                input.val('').focus();
                loadAdminMessages(); // Load lại ngay
                // Cuộn xuống
                const area = $('#admin-chat-area');
                area.scrollTop(area[0].scrollHeight);
            },
            error: function(xhr) {
                const errorMsg = xhr.responseJSON?.message || `HTTP ${xhr.status}: ${xhr.statusText}`;
                alert('Lỗi gửi tin nhắn: ' + errorMsg);
                console.error('Chat error:', xhr.responseJSON || xhr);
            },
            complete: function() {
                btn.prop('disabled', false);
            }
        });
    }
</script>
@endsection