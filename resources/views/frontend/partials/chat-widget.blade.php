<button id="chatWidgetBtn" onclick="toggleChatWindow()">
    <div class="cw-btn-inner">
        <div class="cw-btn-icon"><i class="fas fa-comment-dots"></i></div>
        <div class="cw-btn-label">Chat ngay</div>
    </div>
    <span class="cw-pulse"></span>
</button>

<div id="chatWindow" class="chat-window">
    <div class="chat-header p-3 d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-white rounded-circle d-flex align-items-center justify-content-center"
                style="width:36px;height:36px;"><i class="fas fa-headset text-primary-brand"></i></div>
            <div class="text-white">
                <h6 class="mb-0 fw-bold">CSKH Thành Công Land</h6>
            </div>
        </div>
        <button class="btn btn-sm text-white" onclick="toggleChatWindow()"><i class="fas fa-times"></i></button>
    </div>
    <div class="chat-body" id="chatBody">
        <div class="d-flex flex-column justify-content-center align-items-center h-100" id="chatKhachHangChuaDangNhap"
            style="display:none !important;">
            <i class="fas fa-user-lock fs-2 text-muted mb-3"></i>
            <h6 class="fw-bold">Xin chào!</h6>
            <p class="small text-muted">Vui lòng đăng nhập để chat.</p>
            <button onclick="openAuthModal('login')" class="btn-primary-brand">Đăng nhập</button>
        </div>
    </div>
    <div class="chat-footer p-2 border-top">
        <form id="chatForm" onsubmit="sendFrontendMessage(event)">
            <input type="hidden" id="currentPhienChatId" value="">
            <div class="input-group bg-light rounded-pill p-1">
                <input type="text" id="chatInput" class="form-control border-0 bg-transparent ps-3"
                    placeholder="Nhập tin nhắn..." disabled>
                <button type="submit" class="btn-primary-brand" id="chatSendBtn" disabled
                    style="padding:0.5rem 1rem;"><i class="fas fa-paper-plane"></i></button>
            </div>
        </form>
    </div>
</div>
