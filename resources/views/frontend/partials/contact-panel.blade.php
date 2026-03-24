{{-- ══════════════════════════════════════════
     CONTACT PANEL — Thành Công Land
══════════════════════════════════════════ --}}

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
        {{-- Nền mờ dạng pattern --}}
        <div class="cp-header-bg"></div>

        <div class="cp-header-content">
            <div class="cp-brand">
                <div class="cp-brand-logo">
                    <i class="fas fa-building"></i>
                </div>
                <div class="cp-brand-text">
                    <div class="cp-brand-name">Thành Công Land</div>
                    <div class="cp-brand-sub">
                        <span class="cp-live-dot"></span>
                        Kết nối giá trị &mdash; Kiến tạo thành công
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

        <a href="tel:0912345678" class="cp-channel cp-channel--phone">
            <div class="cp-ch-icon">
                <i class="fas fa-phone-alt"></i>
                <span class="cp-ch-ripple"></span>
            </div>
            <div class="cp-ch-info">
                <span class="cp-ch-name">Gọi Hotline</span>
                <span class="cp-ch-detail">0912 345 678 — Miễn phí</span>
            </div>
            <div class="cp-ch-badge cp-ch-badge--live">LIVE</div>
        </a>

        <a href="https://zalo.me/0912345678" target="_blank" class="cp-channel cp-channel--zalo">
            <div class="cp-ch-icon">
                <img src="{{ asset('images/zalo-icon.png') }}" alt="Zalo" width="22"
                    style="filter:brightness(0) invert(1);"
                    onerror="this.outerHTML='<i class=\'fas fa-comment-dots\'></i>'">
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

{{-- ══════════════════════════════════════════ CSS ══ --}}
<style>
    /* ─── NÚT TOGGLE ─── */
    .cp-toggle-btn {
        position: fixed;
        bottom: 107px;
        right: 30px;
        width: 58px;
        height: 58px;
        border-radius: 50%;
        border: none;
        cursor: pointer;
        background: linear-gradient(135deg, #FF8C42 0%, #FF5722 100%);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1050;
        transition: transform .3s cubic-bezier(.68, -.55, .265, 1.55), box-shadow .3s;
        box-shadow: 0 6px 20px rgba(255, 140, 66, .45);
        padding: 0;
    }

    .cp-toggle-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 10px 28px rgba(255, 140, 66, .55);
    }

    .cp-toggle-btn.active {
        background: linear-gradient(135deg, #1a3c5e, #2d6a9f);
        box-shadow: 0 6px 20px rgba(26, 60, 94, .45);
        transform: rotate(0deg);
    }

    .cp-btn-inner {
        font-size: 1.35rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform .3s;
    }

    .cp-toggle-btn.active .cp-btn-inner {
        transform: rotate(180deg);
    }

    /* Vòng sóng pulse */
    .cp-pulse-ring {
        position: absolute;
        inset: -5px;
        border-radius: 50%;
        border: 2px solid rgba(255, 140, 66, .5);
        animation: cpRingPulse 2.5s ease-out infinite;
        pointer-events: none;
    }

    .cp-toggle-btn.active .cp-pulse-ring {
        display: none;
    }

    @keyframes cpRingPulse {
        0% {
            transform: scale(1);
            opacity: .8;
        }

        100% {
            transform: scale(1.55);
            opacity: 0;
        }
    }

    /* Chấm online */
    .cp-online-dot {
        position: absolute;
        top: 4px;
        right: 4px;
        width: 12px;
        height: 12px;
        background: #2ecc71;
        border-radius: 50%;
        border: 2px solid #fff;
        animation: cpDotPulse 2s ease-in-out infinite;
    }

    .cp-toggle-btn.active .cp-online-dot {
        display: none;
    }

    @keyframes cpDotPulse {

        0%,
        100% {
            box-shadow: 0 0 0 0 rgba(46, 204, 113, .5);
        }

        50% {
            box-shadow: 0 0 0 5px rgba(46, 204, 113, 0);
        }
    }

    /* ─── PANEL ─── */
    .cp-panel {
        position: fixed;
        bottom: 108px;
        right: 90px;
        width: 310px;
        background: #fff;
        border-radius: 20px;
        z-index: 1049;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0, 0, 0, .18), 0 0 0 1px rgba(0, 0, 0, .05);
        opacity: 0;
        transform: translateY(18px) scale(.95);
        transform-origin: bottom right;
        pointer-events: none;
        transition: opacity .3s cubic-bezier(.19, 1, .22, 1),
            transform .3s cubic-bezier(.19, 1, .22, 1);
    }

    .cp-panel.show {
        opacity: 1;
        transform: translateY(0) scale(1);
        pointer-events: all;
    }

    /* ─── HEADER ─── */
    .cp-panel-header {
        position: relative;
        background: linear-gradient(135deg, #FF8C42 0%, #FF5722 100%);
        padding: 0.8rem 0.5rem .7rem;
        overflow: hidden;
    }

    /* Hoa văn nền mờ */
    .cp-header-bg {
        position: absolute;
        inset: 0;
        background-image:
            radial-gradient(circle at 10% 20%, rgba(255, 255, 255, .12) 0%, transparent 50%),
            radial-gradient(circle at 90% 80%, rgba(255, 255, 255, .08) 0%, transparent 50%);
        pointer-events: none;
    }

    .cp-header-bg::after {
        content: '';
        position: absolute;
        right: -20px;
        top: -20px;
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: rgba(255, 255, 255, .08);
    }

    .cp-header-content {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: .65rem;
    }

    /* Brand */
    .cp-brand {
        display: flex;
        align-items: center;
        gap: .7rem;
    }

    .cp-brand-logo {
        width: 42px;
        height: 42px;
        background: rgba(255, 255, 255, .2);
        border: 1.5px solid rgba(255, 255, 255, .3);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        color: #fff;
        flex-shrink: 0;
        backdrop-filter: blur(4px);
    }

    .cp-brand-name {
        font-size: .95rem;
        font-weight: 900;
        color: #fff;
        letter-spacing: -.2px;
    }

    .cp-brand-sub {
        font-size: .58rem;
        color: rgba(255, 255, 255, .85);
        display: flex;
        align-items: center;
        gap: .35rem;
        margin-top: .15rem;
    }

    .cp-live-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #2ecc71;
        flex-shrink: 0;
        box-shadow: 0 0 6px rgba(46, 204, 113, .8);
        animation: cpDotPulse 2s infinite;
    }

    /* Nút đóng */
    .cp-btn-close {
        width: 30px;
        height: 30px;
        background: rgba(255, 255, 255, .2);
        border: 1px solid rgba(255, 255, 255, .25);
        border-radius: 8px;
        color: #fff;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .82rem;
        flex-shrink: 0;
        transition: background .2s;
        backdrop-filter: blur(4px);
    }

    .cp-btn-close:hover {
        background: rgba(255, 255, 255, .35);
    }

    /* Greeting */
    .cp-greeting {
        position: relative;
        background: rgba(255, 255, 255, .18);
        border: 1px solid rgba(255, 255, 255, .25);
        backdrop-filter: blur(4px);
        border-radius: 10px;
        padding: .45rem .75rem;
        font-size: .76rem;
        font-weight: 600;
        color: #fff;
    }

    /* ─── CHANNELS ─── */
    .cp-channels {
        padding: .75rem .7rem .5rem;
    }

    .cp-section-title {
        font-size: .65rem;
        font-weight: 800;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: .8px;
        padding: 0 .3rem .45rem;
    }

    .cp-section-title--mt {
        padding-top: .6rem;
    }

    /* Card kênh */
    .cp-channel {
        display: flex;
        align-items: center;
        gap: .75rem;
        padding: .65rem .8rem;
        border-radius: 12px;
        text-decoration: none !important;
        color: #1a3c5e !important;
        transition: background .15s, transform .15s;
        margin-bottom: .35rem;
        border: 1px solid transparent;
        position: relative;
        overflow: hidden;
    }

    .cp-channel:hover {
        background: #fff8f3;
        border-color: rgba(255, 140, 66, .2);
        transform: translateX(2px);
    }

    .cp-channel:last-of-type {
        margin-bottom: 0;
    }

    /* Icon kênh */
    .cp-ch-icon {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        color: #fff;
        flex-shrink: 0;
        position: relative;
    }

    .cp-channel--phone .cp-ch-icon {
        background: linear-gradient(135deg, #FF8C42, #FF5722);
        box-shadow: 0 4px 12px rgba(255, 140, 66, .35);
    }

    .cp-channel--zalo .cp-ch-icon {
        background: linear-gradient(135deg, #0068ff, #00a8ff);
        box-shadow: 0 4px 12px rgba(0, 104, 255, .3);
    }

    .cp-channel--email .cp-ch-icon {
        background: linear-gradient(135deg, #1a3c5e, #2d6a9f);
        box-shadow: 0 4px 12px rgba(26, 60, 94, .3);
    }

    /* Sóng cho phone */
    .cp-ch-ripple {
        position: absolute;
        inset: -3px;
        border-radius: 14px;
        border: 2px solid rgba(255, 140, 66, .4);
        animation: chRipple 2s ease-out infinite;
    }

    @keyframes chRipple {
        0% {
            transform: scale(1);
            opacity: .7;
        }

        100% {
            transform: scale(1.4);
            opacity: 0;
        }
    }

    .cp-channel:not(.cp-channel--phone) .cp-ch-ripple {
        display: none;
    }

    .cp-ch-info {
        flex: 1;
        min-width: 0;
    }

    .cp-ch-name {
        display: block;
        font-size: .82rem;
        font-weight: 700;
        color: #1a3c5e;
    }

    .cp-ch-detail {
        display: block;
        font-size: .7rem;
        color: #64748b;
        margin-top: .05rem;
    }

    /* Badge LIVE */
    .cp-ch-badge--live {
        background: #FF5722;
        color: #fff;
        font-size: .55rem;
        font-weight: 900;
        padding: .12rem .4rem;
        border-radius: 5px;
        letter-spacing: .5px;
        animation: badgeBlink 2s infinite;
    }

    @keyframes badgeBlink {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: .6;
        }
    }

    .cp-ch-arrow {
        font-size: .65rem;
        color: #FF8C42;
        opacity: 0;
        transition: opacity .15s, transform .15s;
    }

    .cp-channel:hover .cp-ch-arrow {
        opacity: 1;
        transform: translateX(3px);
    }

    /* ─── SOCIAL ROW ─── */
    .cp-social-row {
        display: flex;
        gap: .5rem;
        margin-top: .1rem;
    }

    .cp-social {
        flex: 1;
        padding: .55rem .4rem;
        border-radius: 12px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: .3rem;
        text-decoration: none !important;
        font-size: .65rem;
        font-weight: 700;
        transition: transform .2s, box-shadow .2s;
        border: 1.5px solid transparent;
    }

    .cp-social span {
        color: #374151;
    }

    .cp-social:hover {
        transform: translateY(-3px);
    }

    .cp-social--fb {
        background: #EBF5FF;
        border-color: #bfdbfe;
    }

    .cp-social--fb i {
        font-size: 1.1rem;
        color: #1877f2;
    }

    .cp-social--fb:hover {
        box-shadow: 0 4px 14px rgba(24, 119, 242, .2);
    }

    .cp-social--tt {
        background: #f0f0f0;
        border-color: #e5e7eb;
    }

    .cp-social--tt i {
        font-size: 1.1rem;
        color: #010101;
    }

    .cp-social--tt:hover {
        box-shadow: 0 4px 14px rgba(0, 0, 0, .12);
    }

    .cp-social--yt {
        background: #FFF0F0;
        border-color: #fecaca;
    }

    .cp-social--yt i {
        font-size: 1.1rem;
        color: #ff0000;
    }

    .cp-social--yt:hover {
        box-shadow: 0 4px 14px rgba(255, 0, 0, .15);
    }

    /* ─── FOOTER ─── */
    .cp-panel-footer {
        background: #fff8f3;
        border-top: 1px solid rgba(255, 140, 66, .15);
        padding: .6rem 1rem;
        display: flex;
        align-items: center;
        gap: .4rem;
        font-size: .7rem;
        color: #64748b;
    }

    .cp-panel-footer i {
        color: #FF8C42;
        flex-shrink: 0;
    }

    .cp-panel-footer span {
        flex: 1;
    }

    .cp-map-link {
        color: #FF8C42 !important;
        font-weight: 700;
        white-space: nowrap;
        text-decoration: none !important;
        font-size: .68rem;
        display: flex;
        align-items: center;
        gap: .25rem;
        transition: color .15s;
    }

    .cp-map-link:hover {
        color: #FF5722 !important;
    }

    .cp-map-link i {
        font-size: .55rem;
    }

    /* ─── MOBILE ─── */
    @media (max-width: 576px) {
        .cp-toggle-btn {
            bottom: 87px;
            right: 20px;
        }

        .cp-panel {
            bottom: 158px;
            right: 20px;
            width: calc(100vw - 40px);
            max-width: 310px;
        }
    }
</style>

<script>
    var _cpOpen = false;

    function toggleContactPanel() {
        _cpOpen = !_cpOpen;
        var panel = document.getElementById('contactPanel');
        var btn = document.getElementById('contactToggleBtn');
        var icon = document.getElementById('contactBtnIcon');
        if (_cpOpen) {
            panel.classList.add('show');
            btn.classList.add('active');
            icon.className = 'fas fa-times';
        } else {
            panel.classList.remove('show');
            btn.classList.remove('active');
            icon.className = 'fas fa-headset';
        }
    }
    document.addEventListener('click', function(e) {
        if (!_cpOpen) return;
        var panel = document.getElementById('contactPanel');
        var btn = document.getElementById('contactToggleBtn');
        if (!panel.contains(e.target) && !btn.contains(e.target)) {
            _cpOpen = true;
            toggleContactPanel();
        }
    });
</script>
