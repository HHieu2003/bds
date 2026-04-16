{{-- NÚT MỞ PANEL --}}
<button id="contactToggleBtn" onclick="toggleContactPanel()" title="Liên hệ với chúng tôi" class="cp-toggle-btn">
    <div class="cp-btn-inner">
        <i class="fas fa-headset" id="contactBtnIcon"></i>
    </div>
    <span class="cp-pulse-ring"></span>
    <span class="cp-online-dot"></span>
</button>

{{-- PANEL --}}
<div id="contactPanel" class="cp-panel">

    {{-- ── HEADER ── --}}
    <div class="cp-panel-header">
        <div class="cp-header-bg"></div>
        <div class="cp-header-content">
            <div class="cp-brand">
                <div class="cp-brand-logo">
                    <img src="{{ asset('images/logo.png') }}" alt="Thành Công Land" width="40"
                        onerror="this.outerHTML='<i class=\'fas fa-building\'></i>'">
                </div>
                <div class="cp-brand-text">
                    <div class="cp-brand-name">Thành Công Land</div>
                    <div class="cp-brand-sub">
                        <span class="cp-live-dot"></span>
                        Kết nối giá trị — Kiến tạo thành công
                    </div>
                </div>
            </div>
            <button class="cp-btn-close" onclick="toggleContactPanel()">
                <i class="fas fa-times" id="cpCloseIcon"></i>
            </button>
        </div>
    </div>

    {{-- ── DANH SÁCH KÊNH ── --}}
    <div class="cp-channels">

        <div class="cp-section-title">Kênh liên lạc</div>

        <a href="tel:0336123130" class="cp-channel cp-channel--phone">
            <div class="cp-ch-icon">
                <i class="fas fa-phone-alt"></i>
                <span class="cp-ch-ripple"></span>
            </div>
            <div class="cp-ch-info">
                <span class="cp-ch-name">Gọi Hotline</span>
                <span class="cp-ch-detail">0336 123 130 — Miễn phí</span>
            </div>
            <div class="cp-ch-badge cp-ch-badge--live">LIVE</div>
        </a>

        <a href="https://zalo.me/0336123130" target="_blank" class="cp-channel cp-channel--zalo">
            <div class="cp-ch-icon">
                <i class="fas fa-comment-dots" aria-hidden="true"></i>
            </div>
            <div class="cp-ch-info">
                <span class="cp-ch-name">Nhắn tin Zalo</span>
                <span class="cp-ch-detail">Phản hồi trong vài phút</span>
            </div>
            <i class="fas fa-arrow-right cp-ch-arrow"></i>
        </a>

        <a href="mailto:info@thanhcongland.vn" class="cp-channel cp-channel--email">
            <div class="cp-ch-icon">
                <i class="fas fa-paper-plane"></i>
            </div>
            <div class="cp-ch-info">
                <span class="cp-ch-name">Gửi Email</span>
                <span class="cp-ch-detail">info@thanhcongland.vn</span>
            </div>
            <i class="fas fa-arrow-right cp-ch-arrow"></i>
        </a>

        <div class="cp-section-title cp-section-title--mt">Mạng xã hội</div>

        <div class="cp-social-row">
            <a href="https://facebook.com/thanhcongland" target="_blank" class="cp-social cp-social--fb"
                title="Facebook">
                <i class="fab fa-facebook-f"></i>
                <span>Facebook</span>
            </a>
            <a href="https://tiktok.com/@thanhcongland" target="_blank" class="cp-social cp-social--tt" title="TikTok">
                <i class="fab fa-tiktok"></i>
                <span>TikTok</span>
            </a>
            <a href="https://youtube.com/@thanhcongland" target="_blank" class="cp-social cp-social--yt"
                title="YouTube">
                <i class="fab fa-youtube"></i>
                <span>YouTube</span>
            </a>
        </div>

    </div>

    {{-- ── FOOTER ── --}}
    <div class="cp-panel-footer">
        <i class="fas fa-map-marker-alt"></i>
        <span>Vinhomes Smart City, Tây Mỗ</span>
        <a href="https://maps.google.com" target="_blank" class="cp-map-link">
            Xem bản đồ <i class="fas fa-external-link-alt"></i>
        </a>
    </div>

</div>
