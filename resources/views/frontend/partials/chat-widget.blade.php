{{-- ╔══════════════════════════════════════════════╗
     ║         CHAT WIDGET — THÀNH CÔNG LAND        ║  ╚══════════════════════════════════════════════╝ --}}

{{-- Nút mở chat --}}


<style>
    .chat-quick-replies {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        padding: 10px 12px;
        border-top: 1px solid #f0f0f0;
        background: linear-gradient(135deg, #fafafa 0%, #f5f5f5 100%);
        animation: slideUp 0.3s ease-out;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .chat-quick-replies button {
        font-size: 12px;
        padding: 6px 12px;
        border-radius: 18px;
        border: 1px solid #d0e8ff;
        background: #fff;
        color: #0d6efd;
        cursor: pointer;
        white-space: nowrap;
        transition: all 0.2s;
        font-weight: 500;
        flex: 1;
        min-width: 100px;
    }

    .chat-quick-replies button:hover {
        background: #0d6efd;
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
    }

    .chat-quick-replies button:active {
        transform: translateY(0);
    }

    /* ===== TRANSFER CARD ===== */
    .chat-transfer-card {
        margin: 10px 12px;
        padding: 12px 14px;
        background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%);
        border: 1.5px solid #90caf9;
        border-radius: 12px;
        display: flex;
        align-items: center;
        gap: 12px;
        animation: slideIn 0.4s ease-out;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .chat-transfer-copy {
        flex: 1;
        min-width: 0;
    }

    .chat-transfer-copy strong {
        display: block;
        color: #1976d2;
        font-size: 13px;
        margin-bottom: 2px;
    }

    .chat-transfer-copy small {
        display: block;
        color: #5e6268;
        font-size: 11px;
        line-height: 1.3;
    }

    .chat-transfer-card button {
        font-size: 11px;
        padding: 6px 12px;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        white-space: nowrap;
        font-weight: 600;
        transition: all 0.2s;
        flex-shrink: 0;
    }

    .chat-transfer-card .btn-outline-theme {
        background: #fff;
        color: #1976d2;
        border: 1.5px solid #1976d2;
    }

    .chat-transfer-card .btn-outline-theme:hover {
        background: #1976d2;
        color: #fff;
    }

    .chat-transfer-card .btn-outline-secondary {
        background: transparent;
        color: #6c757d;
        border: 1.5px dashed #6c757d;
    }

    .chat-transfer-card .btn-outline-secondary:hover {
        background: #f8f9fa;
        border-color: #495057;
        color: #495057;
    }
</style>
<button id="chatWidgetBtn" onclick="toggleChatWindow()" title="Chat tư vấn">
    <div class="cw-btn-inner">
        <div class="cw-btn-icon"><i class="fas fa-comments"></i></div>
        <div class="cw-btn-label">
            <strong>Tư vấn nhanh</strong>
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
                    <h6>CSKH Thành Công Land</h6>
                    <small><i class="fas fa-circle"></i> Trực tuyến - phản hồi trong vài phút</small>
                </div>
            </div>
            <div class="chat-head-actions">
                <button type="button" class="chat-icon-btn" onclick="openAuthModal('login')" title="Đăng nhập">
                    <i class="far fa-user"></i>
                </button>
                <button class="chat-close-btn" onclick="toggleChatWindow()" aria-label="Đóng chat">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

    </div>

    <div class="chat-stage">
        <div id="chatKhachHangChuaDangNhap" class="chat-guest-block" style="display:none;">
            <div class="chat-guest-card">
                <h6><i class="fas fa-id-card"></i> Bắt đầu chat nhanh</h6>
                <p>Chọn cách bắt đầu: đăng nhập để lưu lịch sử, hoặc chat nhanh với tư cách khách.</p>

                <button type="button" onclick="startGuestChat()" class="btn btn-primary btn-sm w-100 mb-2">
                    <i class="fas fa-comment-dots me-1"></i> Chat nhanh với tư cách khách
                </button>

                <div class="chat-guest-alert">
                    <i class="fas fa-exclamation-triangle me-1"></i>
                    Chưa xác thực tài khoản: đoạn chat tạm có thể bị xóa sau 24 giờ.
                </div>

                <button onclick="openAuthModal('login')" class="btn btn-outline-primary btn-sm w-100 chat-verify-btn">
                    <i class="fas fa-shield-alt me-1"></i> Đăng nhập để lưu lịch sử chat
                </button>
            </div>
        </div>

        <div class="chat-body" id="chatBody"></div>
        <div id="chatAiThinking" class="chat-ai-thinking" style="display:none;">
            <div class="chat-ai-thinking-bubble">
                <span class="chat-ai-thinking-label">Gemini đang suy nghĩ</span>
                <span class="chat-ai-dots" aria-hidden="true">
                    <i></i><i></i><i></i>
                </span>
            </div>
        </div>

        {{-- Chuyển NV — có confirm --}}
        <div id="chatTransferWrap" class="chat-transfer-wrap" style="display:none;">
            <div class="chat-transfer-card">
                <div class="chat-transfer-copy">
                    <strong><i class="fas fa-user-tie" style="color:#1976d2"></i> Cần hỗ trợ sâu hơn?</strong>
                </div>
                <button type="button" class="btn btn-outline-theme" onclick="transferToAgent()">
                    Chuyển nhân viên tư vấn
                </button>
                <button type="button" class="btn btn-outline-secondary" id="chatBackToBotBtn"
                    onclick="transferBackToBot()" style="display:none;">
                    <i class="fas fa-robot me-1"></i> Chuyển lại AI
                </button>
            </div>
        </div>

    </div>
    {{-- ── Câu hỏi gợi ý (quick replies) ── --}}
    <div id="chatQuickReplies" class="chat-quick-replies" style="display:none;"></div>
    <div class="chat-footer">
        <form id="chatForm" onsubmit="sendFrontendMessage(event)" enctype="multipart/form-data">

            <input type="hidden" id="currentPhienChatId" value="">
            <input type="hidden" id="chatContextLoai" value="{{ $chat_loai_ngu_canh ?? '' }}">
            <input type="hidden" id="chatContextId" value="{{ $chat_ngu_canh_id ?? '' }}">
            <input type="hidden" id="chatContextTen" value="{{ $chat_ten_ngu_canh ?? '' }}">
            <input type="file" id="chatFileInput" accept="image/*,video/*" style="display:none;">

            <div class="chat-input-wrap">
                <button type="button" id="chatAttachBtn" class="btn btn-light rounded-pill px-2 me-1"
                    onclick="openChatFilePicker()" disabled aria-label="Đính kèm tệp">
                    <i class="fas fa-paperclip"></i>
                </button>
                <span class="chat-input-icon"><i class="far fa-comment-dots"></i></span>
                <input type="text" id="chatInput" class="form-control border-0 bg-transparent ps-3"
                    placeholder="Nhập tin nhắn..." autocomplete="off" disabled>
                <button type="submit" id="chatSendBtn" class="btn btn-primary rounded-pill px-3" disabled
                    aria-label="Gửi tin nhắn">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </form>
    </div>

</div>

@push('scripts')
    <script>
        function transferToAgent() {
            const transferWrap = document.getElementById('chatTransferWrap');
            if (transferWrap) {
                transferWrap.style.display = 'none';
            }
            // Gọi các handler khác liên quan đến transfer nếu có
            console.log('Chuyển sang nhân viên tư vấn');
        }

        function transferBackToBot() {
            const transferWrap = document.getElementById('chatTransferWrap');
            if (transferWrap) {
                transferWrap.style.display = 'block';
            }
            // Gọi các handler khác liên quan đến transfer nếu có
            console.log('Chuyển lại AI');
        }
    </script>
@endpush
