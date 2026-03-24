{{-- ============================================================
     FOOTER — Thành Công Land
============================================================ --}}

{{-- ── Pre-footer: Đăng ký nhận tin ── --}}
<div class="footer-newsletter">
    <div class="container-fluid px-4">
        <div class="newsletter-inner">
            <div class="newsletter-text">
                <i class="fas fa-paper-plane newsletter-icon"></i>
                <div>
                    <h5>Nhận thông tin BĐS mới nhất</h5>
                    <p>Cập nhật dự án, tin tức thị trường và ưu đãi đặc biệt mỗi tuần</p>
                </div>
            </div>
            <form class="newsletter-form" onsubmit="return handleNewsletter(event)">
                <div class="newsletter-input-wrap">
                    <i class="fas fa-envelope"></i>
                    <input type="email" placeholder="Nhập email của bạn..." required>
                    <button type="submit">
                        <span>Đăng ký</span>
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
                <p class="newsletter-note">
                    <i class="fas fa-shield-alt"></i> Chúng tôi cam kết không gửi spam
                </p>
            </form>
        </div>
    </div>
</div>

{{-- ── Footer chính ── --}}
<footer class="site-footer">

    <div class="footer-top-border"></div>

    <div class="container-fluid px-4">
        <div class="footer-grid">

            {{-- ══ Cột 1: Thông tin công ty ══ --}}
            <div class="footer-col footer-col-brand">
                <a href="{{ route('frontend.home') }}" class="footer-logo">
                    <img src="{{ asset('images/logo.png') }}" alt="Thành Công Land" style="height:44px">
                </a>
                <p class="footer-desc">
                    Đơn vị phân phối bất động sản uy tín hàng đầu tại Vinhomes Smart City
                    và khu vực phía Tây Hà Nội. Kết nối giá trị — kiến tạo thành công.
                </p>

                {{-- Giấy phép / MST --}}
                <div class="footer-license">
                    <div class="footer-license-item">
                        <i class="fas fa-certificate"></i>
                        <span><strong>MST:</strong> 0123456789</span>
                    </div>
                    <div class="footer-license-item">
                        <i class="fas fa-calendar-check"></i>
                        <span><strong>Cấp ngày:</strong> 01/01/2026 · Sở KHĐT TP. Hà Nội</span>
                    </div>
                </div>

                {{-- Mạng xã hội --}}
                <div class="footer-socials">
                    <a href="#" title="Facebook" target="_blank" class="social-btn">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" title="YouTube" target="_blank" class="social-btn">
                        <i class="fab fa-youtube"></i>
                    </a>
                    <a href="#" title="Zalo" target="_blank" class="social-btn">
                        <i class="fas fa-comment-dots"></i>
                    </a>
                    <a href="#" title="TikTok" target="_blank" class="social-btn">
                        <i class="fab fa-tiktok"></i>
                    </a>
                    <a href="#" title="Instagram" target="_blank" class="social-btn">
                        <i class="fab fa-instagram"></i>
                    </a>
                </div>
            </div>

            {{-- ══ Cột 2: Về chúng tôi ══ --}}
            <div class="footer-col">
                <h6 class="footer-col-title">
                    <i class="fas fa-sitemap"></i> Về chúng tôi
                </h6>
                <ul class="footer-links">
                    <li>
                        <a href="{{ route('frontend.gioi-thieu') }}">
                            <i class="fas fa-angle-right"></i> Giới thiệu công ty
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('frontend.du-an.index') }}">
                            <i class="fas fa-angle-right"></i> Dự án nổi bật
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('frontend.tin-tuc.index') }}">
                            <i class="fas fa-angle-right"></i> Tin tức thị trường
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('frontend.tin-tuc.index', ['loai' => 'kien_thuc']) }}">
                            <i class="fas fa-angle-right"></i> Kiến thức nhà đất
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('frontend.tin-tuc.index', ['loai' => 'phong_thuy']) }}">
                            <i class="fas fa-angle-right"></i> Phong thủy
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('frontend.tuyen-dung') }}">
                            <i class="fas fa-angle-right"></i> Tuyển dụng
                        </a>

                    </li>
                </ul>
            </div>

            {{-- ══ Cột 3: Sản phẩm ══ --}}
            <div class="footer-col">
                <h6 class="footer-col-title">
                    <i class="fas fa-building"></i> Sản phẩm
                </h6>
                <ul class="footer-links">
                    <li>
                        <a href="{{ route('frontend.bat-dong-san.index', ['nhu_cau' => 'ban']) }}">
                            <i class="fas fa-angle-right"></i> Căn hộ chuyển nhượng
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('frontend.bat-dong-san.index', ['nhu_cau' => 'thue']) }}">
                            <i class="fas fa-angle-right"></i> Căn hộ cho thuê
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('frontend.bat-dong-san.index', ['nhu_cau' => 'ban', 'noi_bat' => 1]) }}">
                            <i class="fas fa-angle-right"></i> BĐS nổi bật
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('frontend.bat-dong-san.index', ['nhu_cau' => 'thue', 'vao_o' => 'ngay']) }}">
                            <i class="fas fa-angle-right"></i> Vào ở ngay
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('frontend.ky-gui.create') }}">
                            <i class="fas fa-angle-right"></i> Ký gửi nhà đất
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('frontend.yeu-thich.index') }}">
                            <i class="fas fa-angle-right"></i> BĐS yêu thích
                        </a>
                    </li>
                </ul>
            </div>

            {{-- ══ Cột 4: Liên hệ ══ --}}
            <div class="footer-col">
                <h6 class="footer-col-title">
                    <i class="fas fa-headset"></i> Liên hệ với chúng tôi
                </h6>
                <ul class="footer-contact">
                    <li>
                        <div class="contact-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="contact-body">
                            <span class="contact-label">Văn phòng</span>
                            <span>Tòa SA5 Vinhome SmartCity,<br>Tây Mỗ, Nam Từ Liêm, Hà Nội</span>
                        </div>
                    </li>
                    <li>
                        <div class="contact-icon">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <div class="contact-body">
                            <span class="contact-label">Hotline tư vấn 24/7</span>
                            <a href="tel:+840123456789" class="contact-phone">0123 456 789</a>
                        </div>
                    </li>
                    <li>
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="contact-body">
                            <span class="contact-label">Email</span>
                            <a href="mailto:contact@thanhcongland.vn">contact@thanhcongland.vn</a>
                        </div>
                    </li>
                    <li>
                        <div class="contact-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="contact-body">
                            <span class="contact-label">Giờ làm việc</span>
                            <span>Thứ 2 – Thứ 7: 8:00 – 17:30<br>Chủ nhật: 8:00 – 12:00</span>
                        </div>
                    </li>
                </ul>
            </div>

        </div>{{-- /.footer-grid --}}

        <div class="footer-divider"></div>

        {{-- ── Bottom bar ── --}}
        <div class="footer-bottom">
            <div class="footer-bottom-left">
                <p>
                    &copy; {{ date('Y') }}
                    <strong>Thành Công Land</strong>.
                    Bảo lưu mọi quyền.
                </p>
                <p class="footer-bottom-sub">
                    Thiết kế &amp; phát triển bởi đội ngũ Thành Công Technology
                    và chúng tôi chỉ có trách nhiệm chuyển tải thông tin. <br>
                    Mọi thông tin chỉ có giá trị tham khảo. Chúng
                    tôi
                    không chịu trách nhiệm từ các tin đăng và thông tin quy hoạch được đăng tải trên trang này.
                </p>
            </div>
            <div class="footer-bottom-right">
                <a href="#">Điều khoản sử dụng</a>
                <span class="footer-sep">·</span>
                <a href="#">Chính sách bảo mật</a>

            </div>
        </div>

    </div>{{-- /.container-fluid --}}
</footer>

{{-- ── Nút back-to-top ── --}}
<button class="back-to-top" id="backToTop" title="Lên đầu trang" aria-label="Lên đầu trang">
    <i class="fas fa-chevron-up"></i>
</button>


{{-- ============================================================
     CSS
============================================================ --}}
<style>
    /* ===================== PRE-FOOTER NEWSLETTER ===================== */
    .footer-newsletter {
        background: linear-gradient(135deg, #1a3c5e 0%, #2d6a9f 100%);
        padding: 2.2rem 0;
    }

    .newsletter-inner {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 2rem;
        flex-wrap: wrap;
    }

    .newsletter-text {
        display: flex;
        align-items: center;
        gap: 1.1rem;
        color: #fff;
        flex-shrink: 0;
    }

    .newsletter-icon {
        font-size: 2rem;
        color: #FF8C42;
        flex-shrink: 0;
    }

    .newsletter-text h5 {
        margin: 0 0 .2rem;
        font-size: 1.05rem;
        font-weight: 700;
        color: #fff;
    }

    .newsletter-text p {
        margin: 0;
        font-size: .82rem;
        color: rgba(255, 255, 255, .72);
    }

    .newsletter-form {
        flex: 1;
        min-width: 280px;
        max-width: 480px;
    }

    .newsletter-input-wrap {
        display: flex;
        align-items: center;
        background: rgba(255, 255, 255, .12);
        border: 1.5px solid rgba(255, 255, 255, .25);
        border-radius: 50px;
        overflow: hidden;
        padding: 0 0 0 1rem;
        transition: border-color .2s, background .2s;
    }

    .newsletter-input-wrap:focus-within {
        background: rgba(255, 255, 255, .18);
        border-color: #FF8C42;
    }

    .newsletter-input-wrap>i {
        color: rgba(255, 255, 255, .5);
        font-size: .85rem;
        margin-right: .5rem;
        flex-shrink: 0;
    }

    .newsletter-input-wrap input {
        flex: 1;
        background: transparent;
        border: none;
        outline: none;
        color: #fff;
        font-size: .88rem;
        padding: .7rem 0;
    }

    .newsletter-input-wrap input::placeholder {
        color: rgba(255, 255, 255, .45);
    }

    .newsletter-input-wrap button {
        background: #FF8C42;
        border: none;
        color: #fff;
        font-weight: 700;
        font-size: .84rem;
        padding: .7rem 1.2rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: .4rem;
        transition: background .2s;
        white-space: nowrap;
        border-radius: 0 50px 50px 0;
    }

    .newsletter-input-wrap button:hover {
        background: #FF6B1A;
    }

    .newsletter-note {
        margin: .5rem 0 0 1rem;
        font-size: .72rem;
        color: rgba(255, 255, 255, .45);
        display: flex;
        align-items: center;
        gap: .3rem;
    }

    /* ===================== FOOTER CHÍNH — LIGHT ===================== */
    .site-footer {
        background: #f8f4f1;
        /* trắng ngà ấm — hài hoà với cam */
        color: #3a3530;
        padding-top: 0;
    }

    .footer-top-border {
        height: 4px;
        background: linear-gradient(90deg, #1a3c5e 0%, #FF8C42 40%, #FFB07A 60%, #1a3c5e 100%);
    }

    /* Grid 4 cột */
    .footer-grid {
        display: grid;
        grid-template-columns: 1.8fr 1fr 1fr 1.5fr;
        gap: 2.5rem;
        padding: 2rem 0 0.5rem;
    }

    /* ── Cột Brand ── */
    .footer-logo {
        display: inline-block;
        margin-bottom: 1rem;
    }

    .footer-desc {
        font-size: .84rem;
        line-height: 1.75;
        color: #6b5e56;
        margin-bottom: 1.1rem;
    }

    .footer-license {
        display: flex;
        flex-direction: column;
        gap: .4rem;
        margin-bottom: 1.3rem;
    }

    .footer-license-item {
        display: flex;
        align-items: flex-start;
        gap: .55rem;
        font-size: .77rem;
        color: #8a7a72;
    }

    .footer-license-item i {
        color: #FF8C42;
        margin-top: .1rem;
        font-size: .72rem;
        flex-shrink: 0;
    }

    .footer-license-item strong {
        color: #3a3530;
    }

    /* Social */
    .footer-socials {
        display: flex;
        gap: .55rem;
        flex-wrap: wrap;
    }

    .social-btn {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        border: 1.5px solid #e0d5cd;
        background: #fff;
        color: #FF8C42;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .85rem;
        text-decoration: none;
        transition: all .2s;
        box-shadow: 0 1px 4px rgba(0, 0, 0, .06);
    }

    .social-btn:hover {
        background: #FF8C42;
        border-color: #FF8C42;
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 4px 14px rgba(255, 140, 66, .35);
    }

    /* Col title */
    .footer-col-title {
        font-size: .74rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        color: #1a3c5e;
        margin-bottom: 1.1rem;
        display: flex;
        align-items: center;
        gap: .5rem;
        padding-bottom: .6rem;
        border-bottom: 2px solid #f0e4da;
    }

    .footer-col-title i {
        font-size: .8rem;
        color: #FF8C42;
    }

    /* Footer links */
    .footer-links {
        list-style: none;
        margin: 0;
        padding: 0;
        display: flex;
        flex-direction: column;
        gap: .05rem;
    }

    .footer-links a {
        display: flex;
        align-items: center;
        gap: .55rem;
        padding: .38rem .2rem;
        font-size: .84rem;
        color: #6b5e56;
        text-decoration: none;
        border-radius: 6px;
        transition: color .18s, padding-left .18s;
    }

    .footer-links a i {
        color: #FF8C42;
        font-size: .68rem;
        flex-shrink: 0;
        transition: color .18s;
    }

    .footer-links a:hover {
        color: #FF6B1A;
        padding-left: .5rem;
    }

    .footer-links a:hover i {
        color: #FF6B1A;
    }

    /* Contact */
    .footer-contact {
        list-style: none;
        margin: 0;
        padding: 0;
        display: flex;
        flex-direction: column;
        gap: .9rem;
    }

    .footer-contact li {
        display: flex;
        align-items: flex-start;
        gap: .85rem;
    }

    .contact-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: rgba(255, 140, 66, .1);
        border: 1px solid rgba(255, 140, 66, .25);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .contact-icon i {
        color: #FF8C42;
        font-size: .88rem;
    }

    .contact-body {
        display: flex;
        flex-direction: column;
        gap: .13rem;
    }

    .contact-label {
        font-size: .67rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .8px;
        color: #b0a098;
    }

    .contact-body span,
    .contact-body a {
        font-size: .83rem;
        color: #3a3530;
        text-decoration: none;
        line-height: 1.55;
    }

    .contact-phone {
        font-size: 1.15rem !important;
        font-weight: 800 !important;
        color: #FF6B1A !important;
        letter-spacing: .4px;
    }

    .contact-body a:hover {
        color: #FF6B1A !important;
    }

    /* Divider */
    .footer-divider {
        height: 1px;
        background: #ecddd5;
        margin: .5rem 0;
    }

    /* Bottom bar */
    .footer-bottom {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: .75rem;
        padding: 1.1rem 0 1.5rem;
    }

    .footer-bottom-left p {
        margin: 0;
        font-size: .82rem;
        color: #8a7a72;
    }

    .footer-bottom-left strong {
        color: #1a3c5e;
        font-weight: 800;
    }

    .footer-bottom-sub {
        font-size: .72rem !important;
        color: #b0a098 !important;
        margin-top: .2rem !important;
    }

    .footer-bottom-right {
        display: flex;
        align-items: center;
        gap: .65rem;
        flex-wrap: wrap;
    }

    .footer-bottom-right a {
        font-size: .78rem;
        color: #a09088;
        text-decoration: none;
        transition: color .2s;
    }

    .footer-bottom-right a:hover {
        color: #FF8C42;
    }

    .footer-sep {
        color: #d9cdc8;
        font-size: .75rem;
    }

    /* ===================== BACK TO TOP ===================== */
    /* ── BACK TO TOP ── */
    .back-to-top {
        position: fixed;
        bottom: 172px;
        /* Xếp chồng: trên contact panel */
        right: 24px;
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: linear-gradient(135deg, #FF8C42, #FF6B1A);
        border: 2px solid #e5e7eb;
        color: #1a3c5e;
        font-size: .85rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 16px rgba(0, 0, 0, .10);
        opacity: 0;
        transform: translateY(10px);
        transition: opacity .3s, transform .3s, background .2s, border-color .2s;
        z-index: 980;
    }

    .back-to-top.show {
        opacity: 1;
        transform: translateY(0);
    }

    .back-to-top:hover {
        background: #1a3c5e;
        border-color: #1a3c5e;
        color: #fff;
        box-shadow: 0 6px 20px rgba(26, 60, 94, .25);
        transform: translateY(-2px);
    }

    /* Mobile */
    @media (max-width: 576px) {
        .back-to-top {
            bottom: 152px;
            right: 16px;
            width: 38px;
            height: 38px;
            border-radius: 10px;
        }
    }

    /* ===================== RESPONSIVE ===================== */
    @media (max-width: 1199px) {
        .footer-grid {
            grid-template-columns: 1.4fr 1fr 1fr;
            gap: 2rem;
        }

        .footer-col-brand {
            grid-column: 1 / -1;
        }

        .footer-col-brand .footer-desc {
            max-width: 520px;
        }
    }

    @media (max-width: 767px) {
        .footer-grid {
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            padding: 2rem 0 1.5rem;
        }

        .footer-col-brand {
            grid-column: 1 / -1;
        }

        .newsletter-inner {
            flex-direction: column;
            align-items: flex-start;
        }

        .newsletter-form {
            width: 100%;
            max-width: 100%;
        }
    }

    @media (max-width: 480px) {
        .footer-grid {
            grid-template-columns: 1fr;
        }

        .footer-bottom {
            flex-direction: column;
            align-items: flex-start;
            gap: .5rem;
        }

        .footer-bottom-right {
            gap: .5rem;
        }
    }
</style>


{{-- ============================================================
     JAVASCRIPT
============================================================ --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {

        // ── Back to top ──
        const btnTop = document.getElementById('backToTop');
        window.addEventListener('scroll', () => {
            btnTop?.classList.toggle('show', window.scrollY > 400);
        });
        btnTop?.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // ── Newsletter submit ──
        window.handleNewsletter = function(e) {
            e.preventDefault();
            const input = e.target.querySelector('input[type="email"]');
            const btn = e.target.querySelector('button[type="submit"]');
            if (!input?.value?.trim()) return false;

            btn.innerHTML = '<i class="fas fa-check"></i> <span>Đã đăng ký!</span>';
            btn.style.background = '#27ae60';
            input.value = '';

            setTimeout(() => {
                btn.innerHTML = '<span>Đăng ký</span><i class="fas fa-arrow-right"></i>';
                btn.style.background = '';
            }, 3000);

            return false;
        };

    });
</script>
