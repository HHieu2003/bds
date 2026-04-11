{{-- ============================================================
     TOPBAR — ẨN HOÀN TOÀN TRÊN MOBILE
============================================================ --}}
<div class="topbar d-none d-lg-block">
    <div class="container-fluid px-4">
        <div class="topbar-inner">
            <div class="topbar-left">
                <span class="d-none d-md-inline">
                    <i class="fas fa-map-marker-alt"></i>
                    Thành Công Land | Kết nối giá trị - kiến tạo thành công
                </span>
            </div>
            <div class="topbar-right">
                <a href="tel:+84336123130" class="topbar-phone">
                    <i class="fas fa-phone-alt"></i>
                    <span>0336 123 130</span>
                </a>
                <div class="topbar-socials">
                    <a href="#" title="Facebook" target="_blank"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" title="Zalo" target="_blank"><i class="fas fa-comment-dots"></i></a>
                    <a href="#" title="YouTube" target="_blank"><i class="fab fa-youtube"></i></a>
                    <a href="#" title="Instagram" target="_blank"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ============================================================
     MAIN NAVBAR
============================================================ --}}
<nav class="main-navbar" id="mainNavbar">
    <div class="container-fluid px-3 px-md-4">
        <div class="navbar-inner">

            {{-- ── Hamburger (mobile - bên TRÁI) ── --}}
            <button class="hamburger" id="hamburger" aria-label="Menu">
                <span></span><span></span><span></span>
            </button>

            {{-- ── Logo ── --}}
            <a href="{{ route('frontend.home') }}" class="navbar-logo">
                <img src="{{ asset('images/logo.png') }}" alt="Thành Công Land Logo" class="navbar-logo-img">
            </a>

            {{-- ── Menu chính (desktop) ── --}}
            <ul class="nav-menu" id="navMenu">
                {{-- Nút đóng menu trên mobile --}}
                <li class="nav-close-item">
                    <button class="nav-close-btn" id="navClose" aria-label="Đóng menu">
                        <i class="fas fa-times"></i>
                    </button>
                </li>

                <li class="nav-item">
                    <a href="{{ route('frontend.home') }}"
                        class="nav-link {{ request()->routeIs('frontend.home') ? 'active' : '' }}">Trang chủ</a>
                </li>

                {{-- Mua căn hộ --}}
                <li class="nav-item has-dropdown">
                    <a href="{{ route('frontend.bat-dong-san.index', ['nhu_cau' => 'ban']) }}"
                        class="nav-link {{ request()->routeIs('frontend.bat-dong-san.*') && request('nhu_cau') === 'ban' ? 'active' : '' }}">
                        Mua căn hộ <i class="fas fa-chevron-down nav-arrow"></i>
                    </a>
                    <div class="nav-dropdown can-ho-mega">

                        <div class="can-ho-mega-inner">
                            <div class="can-ho-col">
                                <div class="can-ho-col-title"><i class="fas fa-map-marked-alt"></i> Theo khu vực</div>

                                @forelse($khuVucMenu ?? [] as $kv)
                                    @if ($loop->iteration <= 5)
                                        <a
                                            href="{{ route('frontend.bat-dong-san.index', ['nhu_cau' => 'ban', 'khu_vuc' => $kv->id]) }}">
                                            <span><i class="fas fa-map-marker-alt"></i> {{ $kv->ten_khu_vuc }}</span>
                                            <small class="badge-pn">{{ $kv->so_du_an ?? 0 }} dự án</small>
                                        </a>
                                    @endif
                                @empty
                                    <a href="#"><span>Đang cập nhật khu vực...</span></a>
                                @endforelse
                                <div class="dropdown-divider"></div>
                                <a href="{{ route('frontend.bat-dong-san.index', ['nhu_cau' => 'ban']) }}"
                                    class="highlight-link"><span><i class="fas fa-arrow-right"></i> Xem tất cả khu
                                        vực</span></a>
                            </div>
                            <div class="can-ho-col can-ho-col-right">
                                <div class="can-ho-col-title"><i class="fas fa-city"></i> Theo dự án</div>
                                @forelse($duAnMenu ?? [] as $da)
                                    <a
                                        href="{{ route('frontend.bat-dong-san.index', ['nhu_cau' => 'ban', 'du_an' => $da->id]) }}">
                                        <span><i class="fas fa-building"></i>
                                            {{ Str::limit($da->ten_du_an, 22) }}</span>
                                        <small class="badge-pn">{{ $da->so_can_ban ?? 0 }} căn</small>
                                    </a>
                                @empty
                                    <a href="{{ route('frontend.du-an.index') }}"><span><i class="fas fa-building"></i>
                                            Xem tất cả dự án</span></a>
                                @endforelse
                                <div class="dropdown-divider"></div>
                                <a href="{{ route('frontend.du-an.index') }}" class="highlight-link"><span><i
                                            class="fas fa-arrow-right"></i> Xem tất cả dự án</span></a>
                            </div>
                        </div>
                        <div class="can-ho-footer">
                            <a href="{{ route('frontend.bat-dong-san.index', ['nhu_cau' => 'ban', 'noi_bat' => 1]) }}"><i
                                    class="fas fa-fire"></i> Căn hộ nổi bật</a>
                            <a
                                href="{{ route('frontend.bat-dong-san.index', ['nhu_cau' => 'ban', 'sap_xep' => 'moi_nhat']) }}"><i
                                    class="fas fa-clock"></i> Mới đăng gần đây</a>
                            <a
                                href="{{ route('frontend.bat-dong-san.index', ['nhu_cau' => 'ban', 'gia_den' => 2000000000]) }}"><i
                                    class="fas fa-tags"></i> Dưới 2 tỷ</a>
                        </div>
                    </div>
                </li>

                {{-- Thuê căn hộ --}}
                <li class="nav-item has-dropdown">
                    <a href="{{ route('frontend.bat-dong-san.index', ['nhu_cau' => 'thue']) }}"
                        class="nav-link {{ request()->routeIs('frontend.bat-dong-san.*') && request('nhu_cau') === 'thue' ? 'active' : '' }}">
                        Thuê căn hộ <i class="fas fa-chevron-down nav-arrow"></i>
                    </a>
                    <div class="nav-dropdown can-ho-mega">
                        <div class="can-ho-mega-inner">
                            <div class="can-ho-col">
                                <div class="can-ho-col-title"><i class="fas fa-map-marked-alt"></i> Theo khu vực</div>

                                @forelse($khuVucMenu ?? [] as $kv)
                                    @if ($loop->iteration <= 4)
                                        <a
                                            href="{{ route('frontend.bat-dong-san.index', ['nhu_cau' => 'thue', 'khu_vuc' => $kv->id]) }}">
                                            <span><i class="fas fa-map-marker-alt"></i> {{ $kv->ten_khu_vuc }}</span>
                                            <small class="badge-pn badge-pn-blue">{{ $kv->so_du_an ?? 0 }} dự
                                                án</small>
                                        </a>
                                    @endif
                                @empty
                                    <a href="#"><span>Đang cập nhật khu vực...</span></a>
                                @endforelse
                                <div class="dropdown-divider"></div>
                                <a href="{{ route('frontend.bat-dong-san.index', ['nhu_cau' => 'thue']) }}"
                                    class="highlight-link-blue"><span><i class="fas fa-arrow-right"></i> Xem tất cả
                                        khu vực</span></a>
                            </div>
                            <div class="can-ho-col can-ho-col-right">
                                <div class="can-ho-col-title"><i class="fas fa-city"></i> Theo dự án</div>
                                @forelse($duAnMenu ?? [] as $da)
                                    <a
                                        href="{{ route('frontend.bat-dong-san.index', ['nhu_cau' => 'thue', 'du_an' => $da->id]) }}">
                                        <span><i class="fas fa-building"></i>
                                            {{ Str::limit($da->ten_du_an, 22) }}</span>
                                        <small class="badge-pn badge-pn-blue">{{ $da->so_can_thue ?? 0 }} căn</small>
                                    </a>
                                @empty
                                    <a href="{{ route('frontend.du-an.index') }}"><span><i
                                                class="fas fa-building"></i> Xem tất cả dự án</span></a>
                                @endforelse
                                <div class="dropdown-divider"></div>
                                <a href="{{ route('frontend.du-an.index') }}" class="highlight-link-blue"><span><i
                                            class="fas fa-arrow-right"></i> Xem tất cả dự án</span></a>
                            </div>
                        </div>
                        <div class="can-ho-footer can-ho-footer-blue">
                            <a
                                href="{{ route('frontend.bat-dong-san.index', ['nhu_cau' => 'thue', 'vao_o' => 'ngay']) }}"><i
                                    class="fas fa-bolt"></i> Vào ở ngay</a>
                            <a
                                href="{{ route('frontend.bat-dong-san.index', ['nhu_cau' => 'thue', 'noi_that' => 'full']) }}"><i
                                    class="fas fa-couch"></i> Full nội thất</a>
                            <a
                                href="{{ route('frontend.bat-dong-san.index', ['nhu_cau' => 'thue', 'gia_den' => 10000000]) }}"><i
                                    class="fas fa-tags"></i> Dưới 10 tr/th</a>
                        </div>
                    </div>
                </li>

                {{-- Dự án --}}
                <li class="nav-item has-dropdown">
                    <a href="{{ route('frontend.du-an.index') }}"
                        class="nav-link {{ request()->routeIs('frontend.du-an.*') ? 'active' : '' }}">
                        Dự án <i class="fas fa-chevron-down nav-arrow"></i>
                    </a>
                    <div class="nav-dropdown">
                        <a href="{{ route('frontend.du-an.index') }}" class="dropdown-all"><span><i
                                    class="fas fa-th-large"></i> Tất cả dự án</span><small>{{ $tongSoDuAn ?? 0 }} dự
                                án</small></a>
                        @if (isset($khuVucMenu) && $khuVucMenu->count() > 0)
                            <div class="dropdown-divider"></div>
                            @foreach ($khuVucMenu as $kv)
                                <a href="{{ route('frontend.du-an.index', ['khu_vuc' => $kv->id]) }}"><span><i
                                            class="fas fa-map-marker-alt"></i>
                                        {{ $kv->ten_khu_vuc }}</span><small>{{ $kv->so_du_an }} dự án</small></a>
                            @endforeach
                        @endif
                    </div>
                </li>

                {{-- Tin tức --}}
                <li class="nav-item has-dropdown">
                    <a href="{{ route('frontend.tin-tuc.index') }}"
                        class="nav-link {{ request()->routeIs('frontend.tin-tuc.*', 'frontend.bai-viet.*') ? 'active' : '' }}">
                        Tin tức <i class="fas fa-chevron-down nav-arrow"></i>
                    </a>
                    <div class="nav-dropdown">
                        <a href="{{ route('frontend.tin-tuc.index', ['loai' => 'tin_tuc']) }}"><i
                                class="fas fa-newspaper me-2"></i> Thị trường BĐS</a>
                        <a href="{{ route('frontend.tin-tuc.index', ['loai' => 'kien_thuc']) }}"><i
                                class="fas fa-book me-2"></i> Kiến thức nhà đất</a>
                        <a href="{{ route('frontend.tin-tuc.index', ['loai' => 'phong_thuy']) }}"><i
                                class="fas fa-yin-yang me-2"></i> Phong thủy</a>
                    </div>
                </li>

                <li class="nav-item"><a href="{{ route('frontend.gioi-thieu') }}"
                        class="nav-link {{ request()->routeIs('frontend.gioi-thieu') ? 'active' : '' }}">Giới
                        thiệu</a></li>
                <li class="nav-item"><a href="{{ route('frontend.noi-that') }}"
                        class="nav-link {{ request()->routeIs('frontend.noi-that') ? 'active' : '' }}">Nội Thất</a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('frontend.ky-gui.create') }}"
                        class="nav-link nav-link-cta {{ request()->routeIs('frontend.ky-gui.*') ? 'active' : '' }}">
                        <i class="fas fa-paper-plane me-1"></i> Ký gửi BĐS
                    </a>
                </li>

                {{-- Thông tin liên hệ mobile (chỉ hiện trong menu) --}}
                <li class="nav-mobile-contact d-lg-none">
                    <a href="tel:+84336123130" class="nav-mobile-phone">
                        <i class="fas fa-phone-alt"></i> 0336 123 130
                    </a>
                </li>
            </ul>

            {{-- ── Actions (desktop + mobile) ── --}}
            <div class="navbar-actions">
                <a href="{{ route('frontend.yeu-thich.index') }}" class="action-btn" title="BĐS yêu thích">
                    <i class="far fa-heart"></i>
                    <span class="action-badge" id="soYeuThich" style="display:none">0</span>
                </a>

                @auth('customer')
                    @php $kh = Auth::guard('customer')->user(); @endphp
                    <div class="kh-profile-wrap" id="khProfileWrap">
                        <div class="kh-avatar-btn" id="khAvatarBtn">
                            <div class="kh-avatar">{{ strtoupper(mb_substr($kh->ho_ten ?? 'K', 0, 1)) }}</div>
                            <span
                                class="kh-name d-none d-md-inline">{{ Str::limit($kh->ho_ten ?? 'Tài khoản', 12) }}</span>
                            <i class="fas fa-chevron-down kh-chevron" id="khChevron"></i>
                        </div>
                        <div class="kh-dropdown" id="khDropdown">
                            <div class="kh-dd-body">
                                <button class="kh-dd-item" onclick="openModalHoSo('thong-tin')">
                                    <span class="kh-dd-icon"
                                        style="background:var(--primary-soft);color:var(--primary-color);"><i
                                            class="fas fa-user-edit"></i></span>
                                    <span>Đổi thông tin</span>
                                    <i class="fas fa-chevron-right kh-dd-arr"></i>
                                </button>
                                <div class="kh-dd-divider"></div>
                                <a href="{{ route('frontend.yeu-thich.index') }}" class="kh-dd-item">
                                    <span class="kh-dd-icon" style="background:#fff1f2;color:#e11d48;"><i
                                            class="fas fa-heart"></i></span>
                                    <span>BĐS yêu thích</span>
                                    <i class="fas fa-chevron-right kh-dd-arr"></i>
                                </a>
                                <a href="{{ route('khach-hang.lich-hen-cua-toi') }}" class="kh-dd-item">
                                    <span class="kh-dd-icon" style="background:#f0fdf4;color:#16a34a;"><i
                                            class="fas fa-calendar-check"></i></span>
                                    <span>Lịch hẹn của tôi</span>
                                    <i class="fas fa-chevron-right kh-dd-arr"></i>
                                </a>
                                <a href="{{ route('khach-hang.ky-gui-cua-toi') }}" class="kh-dd-item">
                                    <span class="kh-dd-icon" style="background:#fefce8;color:#ca8a04;"><i
                                            class="fas fa-file-signature"></i></span>
                                    <span>Ký gửi của tôi</span>
                                    <i class="fas fa-chevron-right kh-dd-arr"></i>
                                </a>
                                <a href="{{ route('frontend.cong-cu-tai-chinh') }}" class="kh-dd-item">
                                    <span class="kh-dd-icon" style="background:#eff6ff;color:#3b82f6;"><i
                                            class="fas fa-calculator"></i></span>
                                    <span>Công cụ tài chính</span>
                                    <i class="fas fa-chevron-right kh-dd-arr"></i>
                                </a>
                                <button type="button" class="kh-dd-item" onclick="openModalQuanLyDangKyNhanTin()">
                                    <span class="kh-dd-icon" style="background:#fff7ed;color:#ea580c;"><i
                                            class="fas fa-bell"></i></span>
                                    <span>Đăng ký nhận tin</span>
                                    <i class="fas fa-chevron-right kh-dd-arr"></i>
                                </button>
                                <div class="kh-dd-divider"></div>
                                <form action="{{ route('khach-hang.logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="kh-dd-item" style="color:#dc2626;">
                                        <span class="kh-dd-icon" style="background:#fff5f5;color:#dc2626;"><i
                                                class="fas fa-sign-out-alt"></i></span>
                                        <span>Đăng xuất</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <button type="button" class="btn-login-mobile" onclick="openAuthModal('login')">
                        <i class="fas fa-user"></i>
                        <span class="d-none d-sm-inline">Đăng nhập</span>
                    </button>
                @endauth
            </div>
        </div>
    </div>
</nav>

<div class="nav-overlay" id="navOverlay"></div>
