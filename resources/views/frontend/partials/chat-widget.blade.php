<style>
    .chat-window-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(12, 22, 38, 0.45);
        backdrop-filter: blur(2px);
        z-index: 9997;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.2s ease, visibility 0.2s ease;
    }

    .chat-window-backdrop.show {
        opacity: 1;
        visibility: visible;
    }

    .chat-window.is-expanded {
        right: auto !important;
        bottom: auto !important;
        left: 50% !important;
        top: 50% !important;
        transform: translate(-50%, -50%) !important;
        width: min(920px, calc(100vw - 36px)) !important;
        height: min(82vh, 760px) !important;
        max-height: 82vh !important;
        border-radius: 16px !important;
        z-index: 9998 !important;
        box-shadow: 0 24px 64px rgba(11, 24, 43, 0.35) !important;
    }

    @media (max-width: 768px) {
        .chat-window.is-expanded {
            width: calc(100vw - 12px) !important;
            height: calc(100vh - 12px) !important;
            max-height: calc(100vh - 12px) !important;
            border-radius: 12px !important;
        }
    }

    .chat-quick-replies {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        padding: 12px;
        border-top: 1px solid rgba(10, 20, 40, 0.08);
        background:
            radial-gradient(circle at 0% 0%, rgba(192, 102, 42, 0.12), transparent 60%),
            linear-gradient(145deg, #fbfcff 0%, #f6f8fc 100%);
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

    .chat-quick-replies .chat-qr-btn {
        font-size: 12px;
        padding: 8px 13px;
        border-radius: 999px;
        border: 1px solid #d9e3f4;
        background: #ffffff;
        color: #19345f;
        cursor: pointer;
        white-space: normal;
        line-height: 1.35;
        text-align: left;
        transition: all 0.2s;
        font-weight: 600;
        flex: 1 1 calc(50% - 8px);
        min-width: 140px;
        box-shadow: 0 1px 4px rgba(10, 20, 40, 0.08);
    }

    .chat-quick-replies .chat-qr-btn:hover {
        border-color: #c0662a;
        background: #fff8f2;
        color: #8d471d;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(192, 102, 42, 0.2);
    }

    .chat-quick-replies .chat-qr-btn:active {
        transform: translateY(0);
    }

    .chat-quick-replies .chat-qr-btn.is-ai {
        border-color: #19345f;
        background: linear-gradient(135deg, #19345f 0%, #264f8f 100%);
        color: #fff;
        box-shadow: 0 8px 18px rgba(25, 52, 95, 0.3);
    }

    .chat-quick-replies .chat-qr-btn.is-ai:hover {
        border-color: #19345f;
        background: linear-gradient(135deg, #152d52 0%, #1e447f 100%);
        color: #fff;
    }

    .chat-quick-replies .chat-qr-btn.is-ai-suggest {
        border-color: #d4e3ff;
        background: #f3f7ff;
        color: #20406f;
    }

    .chat-quick-replies .chat-qr-btn.is-support {
        border-color: #c0662a;
        background: #fff1e7;
        color: #9a4f21;
    }

    .chat-quick-replies .chat-qr-btn.is-support:hover {
        border-color: #9a4f21;
        background: #ffe4d3;
        color: #7e3f18;
    }

    @media (max-width: 576px) {
        .chat-quick-replies .chat-qr-btn {
            flex-basis: 100%;
        }
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
<div id="chatWindowBackdrop" class="chat-window-backdrop" onclick="toggleChatExpand(false)"></div>

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
                <button type="button" id="chatExpandBtn" class="chat-icon-btn" onclick="toggleChatExpand()"
                    title="Phóng to khung chat" aria-label="Phóng to khung chat">
                    <i id="chatExpandIcon" class="fas fa-expand"></i>
                </button>
                @if (!Auth::guard('customer')->check())
                    <button type="button" class="chat-icon-btn" onclick="openAuthModal('login')" title="Đăng nhập">
                        <i class="far fa-user"></i>
                    </button>
                @endif
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
