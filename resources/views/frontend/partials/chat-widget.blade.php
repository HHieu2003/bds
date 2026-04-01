{{-- ╔══════════════════════════════════════════════╗
     ║         CHAT WIDGET — THÀNH CÔNG LAND        ║
     ╚══════════════════════════════════════════════╝ --}}

{{-- Nút mở chat --}}
<button id="chatWidgetBtn" onclick="toggleChatWindow()" title="Chat tư vấn">
    <div class="cw-btn-inner">
        <div class="cw-btn-icon"><i class="fas fa-comments"></i></div>
        <div class="cw-btn-label">
            <strong>Tu van nhanh</strong>
            <small>Online</small>
        </div>
    </div>
    <span id="chatUnreadBadge" class="cw-unread-badge" style="display:none;">1</span>
    <span class="cw-pulse"></span>
</button>

{{-- Cửa sổ chat --}}
<div id="chatWindow" class="chat-window">

    <div class="chat-header">
        <div class="chat-header-main">
            <div class="chat-brand-mark">
                <div class="chat-brand-avatar">
                    <i class="fas fa-headset"></i>
                </div>
                <div class="chat-brand-copy">
                    <h6>CSKH Thanh Cong Land</h6>
                    <small><i class="fas fa-circle"></i> Truc tuyen - phan hoi trong vai phut</small>
                </div>
            </div>
            <div class="chat-head-actions">
                <button type="button" class="chat-icon-btn" onclick="openAuthModal('login')" title="Dang nhap">
                    <i class="far fa-user"></i>
                </button>
                <button class="chat-close-btn" onclick="toggleChatWindow()" aria-label="Dong chat">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

    </div>

    <div class="chat-stage">
        <div id="chatKhachHangChuaDangNhap" class="chat-guest-block" style="display:none;">
            <div class="chat-guest-card">
                <h6><i class="fas fa-id-card"></i> Bat dau chat nhanh</h6>
                <p>Chon cach bat dau: dang nhap de luu lich su, hoac chat nhanh voi tu cach khach.</p>

                <button type="button" onclick="startGuestChat()" class="btn btn-primary btn-sm w-100 mb-2">
                    <i class="fas fa-comment-dots me-1"></i> Chat nhanh voi tu cach khach
                </button>

                <div class="chat-guest-alert">
                    <i class="fas fa-exclamation-triangle me-1"></i>
                    Chua xac thuc tai khoan: doan chat tam co the bi xoa sau 24 gio.
                </div>

                <button onclick="openAuthModal('login')" class="btn btn-outline-primary btn-sm w-100 chat-verify-btn">
                    <i class="fas fa-shield-alt me-1"></i> Dang nhap de luu lich su chat
                </button>
            </div>
        </div>

        <div class="chat-body" id="chatBody"></div>
        <div id="chatAiThinking" class="chat-ai-thinking" style="display:none;">
            <div class="chat-ai-thinking-bubble">
                <span class="chat-ai-thinking-label">Gemini dang suy nghi</span>
                <span class="chat-ai-dots" aria-hidden="true">
                    <i></i><i></i><i></i>
                </span>
            </div>
        </div>

        <div id="chatTransferWrap" class="chat-transfer-wrap" style="display:none;">
            <div class="chat-transfer-card">
                <div class="chat-transfer-copy">
                    <strong>Can ho tro sau hon?</strong>
                    <small>Chuyen hoi thoai cho nhan vien kinh doanh ngay.</small>
                </div>
                <button type="button" class="btn btn-outline-theme btn-sm" onclick="transferToAgent()">
                    <i class="fas fa-user-tie me-1"></i> Chuyen nhan vien
                </button>
                <button type="button" class="btn btn-outline-secondary btn-sm" id="chatBackToBotBtn"
                    onclick="transferBackToBot()" style="display:none;">
                    <i class="fas fa-robot me-1"></i> Chuyen lai AI
                </button>
            </div>
        </div>
    </div>

    <div class="chat-footer">
        <form id="chatForm" onsubmit="sendFrontendMessage(event)" enctype="multipart/form-data">

            <input type="hidden" id="currentPhienChatId" value="">
            <input type="hidden" id="chatContextLoai" value="{{ $chat_loai_ngu_canh ?? '' }}">
            <input type="hidden" id="chatContextId" value="{{ $chat_ngu_canh_id ?? '' }}">
            <input type="hidden" id="chatContextTen" value="{{ $chat_ten_ngu_canh ?? '' }}">
            <input type="file" id="chatFileInput" accept="image/*,video/*" style="display:none;">

            <div class="chat-input-wrap">
                <button type="button" id="chatAttachBtn" class="btn btn-light rounded-pill px-2 me-1"
                    onclick="openChatFilePicker()" disabled aria-label="Dinh kem tep">
                    <i class="fas fa-paperclip"></i>
                </button>
                <span class="chat-input-icon"><i class="far fa-comment-dots"></i></span>
                <input type="text" id="chatInput" class="form-control border-0 bg-transparent ps-3"
                    placeholder="Nhap tin nhan..." autocomplete="off" disabled>
                <button type="submit" id="chatSendBtn" class="btn btn-primary rounded-pill px-3" disabled
                    aria-label="Gui tin nhan">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </form>
    </div>

</div>
