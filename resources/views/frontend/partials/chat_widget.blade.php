<div id="chat-widget-container" style="position: fixed; bottom: 20px; right: 20px; z-index: 9999;">
    
    <button id="chat-toggle-btn" class="btn btn-primary rounded-circle shadow-lg d-flex align-items-center justify-content-center" 
            style="width: 60px; height: 60px; transition: transform 0.3s;">
        <i class="fas fa-comment-dots fa-2x"></i>
    </button>

    <div id="chat-box" class="card shadow-lg d-none" style="width: 320px; height: 450px; position: absolute; bottom: 70px; right: 0; border-radius: 15px; overflow: hidden;">
        
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <span class="fw-bold"><i class="fas fa-headset me-2"></i>Hỗ trợ tư vấn</span>
            <button id="chat-close-btn" class="btn btn-sm text-white"><i class="fas fa-times"></i></button>
        </div>

        <div id="chat-messages" class="card-body overflow-auto bg-light" style="height: 340px; display: flex; flex-direction: column;">
            <div class="text-center text-muted small mt-5">
                <i class="fas fa-circle-notch fa-spin"></i> Đang kết nối...
            </div>
        </div>

        <div class="card-footer p-2 bg-white">
            <div class="input-group">
                <input type="text" id="chat-input" class="form-control border-0" placeholder="Nhập tin nhắn..." disabled>
                <button class="btn btn-primary" id="chat-send-btn" disabled>
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const chatBtn = document.getElementById('chat-toggle-btn');
    const chatBox = document.getElementById('chat-box');
    const closeBtn = document.getElementById('chat-close-btn');
    const msgArea = document.getElementById('chat-messages');
    const input = document.getElementById('chat-input');
    const sendBtn = document.getElementById('chat-send-btn');
    
    let isChatOpen = false;
    let pollInterval = null;

    // --- MỞ CHAT ---
    chatBtn.addEventListener('click', function() {
        if (!isChatOpen) {
            // Gọi API start chat để check login
            startChatSession();
        } else {
            closeChat();
        }
    });

    closeBtn.addEventListener('click', closeChat);

    function closeChat() {
        chatBox.classList.add('d-none');
        isChatOpen = false;
        clearInterval(pollInterval);
    }

    function startChatSession() {
        fetch('{{ route("chat.start") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'require_login') {
                // Chưa login -> Gọi Modal Quick Login (đã có ở Layout master)
                // Biến pendingAction để sau khi login xong thì tự mở lại chat
                if(typeof pendingAction !== 'undefined') {
                    pendingAction = { type: 'chat' };
                }
                var modalQuickLogin = new bootstrap.Modal(document.getElementById('modalQuickLogin'));
                modalQuickLogin.show();
            } else if (data.status === 'success') {
                // Đã login -> Mở hộp chat
                openChatBox(data);
            }
        })
        .catch(err => console.error(err));
    }

    function openChatBox(data) {
        chatBox.classList.remove('d-none');
        isChatOpen = true;
        input.disabled = false;
        sendBtn.disabled = false;
        
        // Render tin nhắn cũ
        renderMessages(data.messages);
        
        // Bắt đầu polling tin mới mỗi 3s
        pollInterval = setInterval(fetchNewMessages, 3000);
    }

    function renderMessages(messages) {
        let html = '';
        if(!messages || messages.length === 0) {
            html = '<div class="text-center text-muted small mt-3">Xin chào! Chúng tôi có thể giúp gì cho bạn?</div>';
        } else {
            messages.forEach(msg => {
                // msg.user_id == null là khách, != null là admin
                let isMe = (msg.user_id == null); 
                html += `
                    <div class="d-flex mb-2 ${isMe ? 'justify-content-end' : 'justify-content-start'}">
                        <div class="p-2 rounded shadow-sm text-break" 
                             style="max-width: 80%; background-color: ${isMe ? '#0d6efd' : '#e9ecef'}; color: ${isMe ? '#fff' : '#000'}">
                            ${msg.message}
                        </div>
                    </div>
                `;
            });
        }
        msgArea.innerHTML = html;
        msgArea.scrollTop = msgArea.scrollHeight; // Scroll xuống cuối
    }

    // --- GỬI TIN ---
    function sendMessage() {
        let txt = input.value.trim();
        if(!txt) return;

        // Hiện tạm tin nhắn lên trước khi gửi (UX tốt hơn)
        msgArea.innerHTML += `
            <div class="d-flex mb-2 justify-content-end">
                <div class="p-2 rounded shadow-sm text-break" style="max-width: 80%; background-color: #0d6efd; color: #fff; opacity: 0.7">
                    ${txt} <i class="fas fa-spinner fa-spin small ms-1"></i>
                </div>
            </div>
        `;
        msgArea.scrollTop = msgArea.scrollHeight;
        input.value = '';

        fetch('{{ route("chat.send") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ message: txt })
        })
        .then(res => res.json())
        .then(data => {
            fetchNewMessages(); // Tải lại để xóa icon loading
        });
    }

    sendBtn.addEventListener('click', sendMessage);
    input.addEventListener('keypress', function(e) {
        if(e.key === 'Enter') sendMessage();
    });

    // --- LẤY TIN MỚI ---
    function fetchNewMessages() {
        if(!isChatOpen) return;
        fetch('{{ route("chat.messages") }}')
        .then(res => res.json())
        .then(res => {
            renderMessages(res.data);
        });
    }
    
    // Hàm global để gọi từ bên ngoài (ví dụ sau khi login thành công)
    window.openChatWidget = function(data) {
        openChatBox(data);
    }
});
</script>