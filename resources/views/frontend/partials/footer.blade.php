{{-- ============================================================
     FOOTER — Thành Công Land
============================================================ --}}

{{-- ── Pre-footer: Đăng ký nhận tin ── --}}
<div class="footer-newsletter">
    <div class="container-fluid px-4">
        <div class="newsletter-inner">
            <div class="newsletter-text" data-aos="fade-right">
                <i class="fas fa-paper-plane newsletter-icon"></i>
                <div>
                    <h5>Nhận thông tin BĐS mới nhất</h5>
                    <p>Cập nhật dự án, tin tức thị trường và ưu đãi đặc biệt mỗi tuần</p>
                </div>
            </div>
            <form class="newsletter-form" onsubmit="return handleNewsletter(event)" data-aos="fade-left">
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
            <div class="footer-col footer-col-brand" data-aos="fade-up" data-aos-delay="100">
                <a href="{{ route('frontend.home') }}" class="footer-logo">
                    <img src="{{ asset('images/logo.png') }}" alt="Thành Công Land" style="height:44px">
                </a>
                <p class="footer-desc">
                    Đơn vị phân phối bất động sản uy tín hàng đầu tại Vinhomes Smart City
                    và khu vực phía Tây Hà Nội. <br> Kết nối giá trị — kiến tạo thành công.
                </p>

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

                <div class="footer-socials">
                    <a href="#" title="Facebook" target="_blank" class="social-btn"><i
                            class="fab fa-facebook-f"></i></a>
                    <a href="#" title="YouTube" target="_blank" class="social-btn"><i
                            class="fab fa-youtube"></i></a>
                    <a href="#" title="Zalo" target="_blank" class="social-btn"><i
                            class="fas fa-comment-dots"></i></a>
                    <a href="#" title="TikTok" target="_blank" class="social-btn"><i
                            class="fab fa-tiktok"></i></a>
                    <a href="#" title="Instagram" target="_blank" class="social-btn"><i
                            class="fab fa-instagram"></i></a>
                </div>
            </div>

            {{-- ══ Cột 2: Về chúng tôi ══ --}}
            <div class="footer-col" data-aos="fade-up" data-aos-delay="200">
                <h6 class="footer-col-title"><i class="fas fa-sitemap"></i> Về chúng tôi</h6>
                <ul class="footer-links">
                    <li><a href="{{ route('frontend.gioi-thieu') }}"><i class="fas fa-angle-right"></i> Giới thiệu công
                            ty</a></li>
                    <li><a href="{{ route('frontend.du-an.index') }}"><i class="fas fa-angle-right"></i> Dự án nổi
                            bật</a></li>
                    <li><a href="{{ route('frontend.tin-tuc.index') }}"><i class="fas fa-angle-right"></i> Tin tức thị
                            trường</a></li>
                    <li><a href="{{ route('frontend.tin-tuc.index', ['loai' => 'kien_thuc']) }}"><i
                                class="fas fa-angle-right"></i> Kiến thức nhà đất</a></li>
                    <li><a href="{{ route('frontend.tin-tuc.index', ['loai' => 'phong_thuy']) }}"><i
                                class="fas fa-angle-right"></i> Phong thủy</a></li>
                    <li><a href="{{ route('frontend.tuyen-dung') }}"><i class="fas fa-angle-right"></i> Tuyển dụng</a>
                    </li>
                    <li><a href="{{ route('frontend.lien-he.index') }}"><i class="fas fa-angle-right"></i> Liên hệ</a>
                    </li>
                </ul>
            </div>

            {{-- ══ Cột 3: Sản phẩm ══ --}}
            <div class="footer-col" data-aos="fade-up" data-aos-delay="300">
                <h6 class="footer-col-title"><i class="fas fa-building"></i> Sản phẩm</h6>
                <ul class="footer-links">
                    <li><a href="{{ route('frontend.bat-dong-san.index', ['nhu_cau' => 'ban']) }}"><i
                                class="fas fa-angle-right"></i> Căn hộ chuyển nhượng</a></li>
                    <li><a href="{{ route('frontend.bat-dong-san.index', ['nhu_cau' => 'thue']) }}"><i
                                class="fas fa-angle-right"></i> Căn hộ cho thuê</a></li>
                    <li><a href="{{ route('frontend.bat-dong-san.index', ['nhu_cau' => 'ban', 'noi_bat' => 1]) }}"><i
                                class="fas fa-angle-right"></i> BĐS nổi bật</a></li>
                    <li><a href="{{ route('frontend.bat-dong-san.index', ['nhu_cau' => 'thue', 'vao_o' => 'ngay']) }}"><i
                                class="fas fa-angle-right"></i> Vào ở ngay</a></li>
                    <li><a href="{{ route('frontend.ky-gui.create') }}"><i class="fas fa-angle-right"></i> Ký gửi nhà
                            đất</a></li>
                    <li><a href="{{ route('frontend.yeu-thich.index') }}"><i class="fas fa-angle-right"></i> BĐS yêu
                            thích</a></li>
                </ul>
            </div>

            {{-- ══ Cột 4: Liên hệ ══ --}}
            <div class="footer-col" data-aos="fade-up" data-aos-delay="400">
                <h6 class="footer-col-title"><i class="fas fa-headset"></i> Liên hệ với chúng tôi</h6>
                <ul class="footer-contact">
                    <li>
                        <div class="contact-icon"><i class="fas fa-map-marker-alt"></i></div>
                        <div class="contact-body">
                            <span class="contact-label">Văn phòng</span>
                            <span>Tòa SA5 Vinhome SmartCity,<br>Tây Mỗ, Nam Từ Liêm, Hà Nội</span>
                        </div>
                    </li>
                    <li>
                        <div class="contact-icon"><i class="fas fa-phone-alt"></i></div>
                        <div class="contact-body">
                            <span class="contact-label">Hotline tư vấn 24/7</span>
                            <a href="tel:+84336123130" class="contact-phone">0336 123 130</a>
                        </div>
                    </li>
                    <li>
                        <div class="contact-icon"><i class="fas fa-envelope"></i></div>
                        <div class="contact-body">
                            <span class="contact-label">Email</span>
                            <a href="mailto:contact@thanhcongland.vn">contact@thanhcongland.vn</a>
                        </div>
                    </li>
                    <li>
                        <div class="contact-icon"><i class="fas fa-clock"></i></div>
                        <div class="contact-body">
                            <span class="contact-label">Giờ làm việc</span>
                            <span>Thứ 2 – Thứ 7: 8:00 – 17:30<br>Chủ nhật: 8:00 – 12:00</span>
                        </div>
                    </li>
                </ul>
            </div>

        </div>

        <div class="footer-divider"></div>

        {{-- ── Bottom bar ── --}}
        <div class="footer-bottom">
            <div class="footer-bottom-left">
                <p>&copy; {{ date('Y') }} <strong>Thành Công Land</strong>. Bảo lưu mọi quyền.</p>
                <p class="footer-bottom-sub">
                    Thiết kế &amp; phát triển bởi đội ngũ Thành Công Technology và chúng tôi chỉ có trách nhiệm chuyển
                    tải thông tin. <br>
                    Mọi thông tin chỉ có giá trị tham khảo. Chúng tôi không chịu trách nhiệm từ các tin đăng và thông
                    tin quy hoạch được đăng tải trên trang này.
                </p>
            </div>
            <div class="footer-bottom-right">
                <a href="#">Điều khoản sử dụng</a>
                <span class="footer-sep">·</span>
                <a href="#">Chính sách bảo mật</a>
            </div>
        </div>
    </div>
</footer>

<button class="back-to-top" id="backToTop" title="Lên đầu trang" aria-label="Lên đầu trang">
    <i class="fas fa-chevron-up"></i>
</button>
