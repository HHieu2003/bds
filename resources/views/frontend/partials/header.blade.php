{{-- ============================================================
     TOPBAR
============================================================ --}}
<div class="topbar">
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
    <div class="container-fluid px-4">
        <div class="navbar-inner">

            {{-- ── Logo ── --}}
            <a href="{{ route('frontend.home') }}" class="navbar-logo">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height: 40px; margin-right: 12px">
            </a>

            {{-- ── Menu chính (desktop) ── --}}
            <ul class="nav-menu" id="navMenu">

                {{-- Trang chủ --}}
                <li class="nav-item">
                    <a href="{{ route('frontend.home') }}"
                        class="nav-link {{ request()->routeIs('frontend.home') ? 'active' : '' }}">
                        Trang chủ
                    </a>
                </li>

                {{-- Mua căn hộ --}}
                <li class="nav-item has-dropdown">
                    <a href="{{ route('frontend.bat-dong-san.index', ['nhu_cau' => 'ban']) }}"
                        class="nav-link {{ request()->routeIs('frontend.bat-dong-san.*') && request('nhu_cau') === 'ban' ? 'active' : '' }}">

                        Mua căn hộ
                        <i class="fas fa-chevron-down nav-arrow"></i>
                    </a>
                    <div class="nav-dropdown can-ho-mega">
                        <div class="can-ho-mega-inner">

                            {{-- Cột trái: Theo khu vực (Thay thế Số phòng ngủ) --}}
                            <div class="can-ho-col">
                                <div class="can-ho-col-title">
                                    <i class="fas fa-map-marked-alt"></i> Theo khu vực
                                </div>
                                <a href="{{ route('frontend.bat-dong-san.index', ['nhu_cau' => 'ban']) }}"
                                    class="dropdown-all">
                                    <span><i class="fas fa-th-large"></i> Tất cả khu vực bán</span>
                                </a>

                                {{-- Vòng lặp lấy danh sách Khu vực --}}
                                @forelse($khuVucMenu ?? [] as $kv)
                                    @if ($loop->iteration <= 4)
                                        {{-- Giới hạn 4 khu vực để menu không bị quá dài --}}
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
                                    class="highlight-link">
                                    <span><i class="fas fa-arrow-right"></i> Xem tất cả khu vực</span>
                                </a>
                            </div>

                            {{-- Cột phải: Theo dự án --}}
                            <div class="can-ho-col can-ho-col-right">
                                <div class="can-ho-col-title">
                                    <i class="fas fa-city"></i> Theo dự án
                                </div>
                                @forelse($duAnMenu ?? [] as $da)
                                    <a
                                        href="{{ route('frontend.bat-dong-san.index', ['nhu_cau' => 'ban', 'du_an' => $da->id]) }}">
                                        <span><i class="fas fa-building"></i>
                                            {{ Str::limit($da->ten_du_an, 22) }}</span>
                                        <small class="badge-pn">{{ $da->so_can_ban ?? 0 }} căn</small>
                                    </a>
                                @empty
                                    <a href="{{ route('frontend.du-an.index') }}">
                                        <span><i class="fas fa-building"></i> Xem tất cả dự án</span>
                                    </a>
                                @endforelse
                                <div class="dropdown-divider"></div>
                                <a href="{{ route('frontend.du-an.index') }}" class="highlight-link">
                                    <span><i class="fas fa-arrow-right"></i> Xem tất cả dự án</span>
                                </a>
                            </div>
                        </div>
                        {{-- Footer dropdown --}}
                        <div class="can-ho-footer">
                            <a href="{{ route('frontend.bat-dong-san.index', ['nhu_cau' => 'ban', 'noi_bat' => 1]) }}">
                                <i class="fas fa-fire"></i> Căn hộ nổi bật
                            </a>
                            <a
                                href="{{ route('frontend.bat-dong-san.index', ['nhu_cau' => 'ban', 'sap_xep' => 'moi_nhat']) }}">
                                <i class="fas fa-clock"></i> Mới đăng gần đây
                            </a>
                            <a
                                href="{{ route('frontend.bat-dong-san.index', ['nhu_cau' => 'ban', 'gia_den' => 2000000000]) }}">
                                <i class="fas fa-tags"></i> Dưới 2 tỷ
                            </a>
                        </div>
                    </div>
                </li>

                {{-- Thuê căn hộ --}}
                <li class="nav-item has-dropdown">
                    <a href="{{ route('frontend.bat-dong-san.index', ['nhu_cau' => 'thue']) }}"
                        class="nav-link {{ request()->routeIs('frontend.bat-dong-san.*') && request('nhu_cau') === 'thue' ? 'active' : '' }}">

                        Thuê căn hộ
                        <i class="fas fa-chevron-down nav-arrow"></i>
                    </a>
                    <div class="nav-dropdown can-ho-mega">
                        <div class="can-ho-mega-inner">

                            {{-- Cột trái: Theo khu vực (Thay thế Số phòng ngủ) --}}
                            <div class="can-ho-col">
                                <div class="can-ho-col-title">
                                    <i class="fas fa-map-marked-alt"></i> Theo khu vực
                                </div>
                                <a href="{{ route('frontend.bat-dong-san.index', ['nhu_cau' => 'thue']) }}"
                                    class="dropdown-all dropdown-all-blue">
                                    <span><i class="fas fa-th-large"></i> Tất cả khu vực thuê</span>
                                </a>

                                {{-- Vòng lặp lấy danh sách Khu vực --}}
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
                                    class="highlight-link-blue">
                                    <span><i class="fas fa-arrow-right"></i> Xem tất cả khu vực</span>
                                </a>
                            </div>

                            {{-- Cột phải: Theo dự án --}}
                            <div class="can-ho-col can-ho-col-right">
                                <div class="can-ho-col-title">
                                    <i class="fas fa-city"></i> Theo dự án
                                </div>
                                @forelse($duAnMenu ?? [] as $da)
                                    <a
                                        href="{{ route('frontend.bat-dong-san.index', ['nhu_cau' => 'thue', 'du_an' => $da->id]) }}">
                                        <span><i class="fas fa-building"></i>
                                            {{ Str::limit($da->ten_du_an, 22) }}</span>
                                        <small class="badge-pn badge-pn-blue">{{ $da->so_can_thue ?? 0 }} căn</small>
                                    </a>
                                @empty
                                    <a href="{{ route('frontend.du-an.index') }}">
                                        <span><i class="fas fa-building"></i> Xem tất cả dự án</span>
                                    </a>
                                @endforelse
                                <div class="dropdown-divider"></div>
                                <a href="{{ route('frontend.du-an.index') }}" class="highlight-link-blue">
                                    <span><i class="fas fa-arrow-right"></i> Xem tất cả dự án</span>
                                </a>
                            </div>
                        </div>
                        {{-- Footer dropdown --}}
                        <div class="can-ho-footer can-ho-footer-blue">
                            <a
                                href="{{ route('frontend.bat-dong-san.index', ['nhu_cau' => 'thue', 'vao_o' => 'ngay']) }}">
                                <i class="fas fa-bolt"></i> Vào ở ngay
                            </a>
                            <a
                                href="{{ route('frontend.bat-dong-san.index', ['nhu_cau' => 'thue', 'noi_that' => 'full']) }}">
                                <i class="fas fa-couch"></i> Full nội thất
                            </a>
                            <a
                                href="{{ route('frontend.bat-dong-san.index', ['nhu_cau' => 'thue', 'gia_den' => 10000000]) }}">
                                <i class="fas fa-tags"></i> Dưới 10 tr/th
                            </a>
                        </div>
                    </div>
                </li>
                {{-- Dự án --}}
                <li class="nav-item has-dropdown">
                    <a href="{{ route('frontend.du-an.index') }}"
                        class="nav-link {{ request()->routeIs('frontend.du-an.*') ? 'active' : '' }}">
                        Dự án
                        <i class="fas fa-chevron-down nav-arrow"></i>
                    </a>
                    <div class="nav-dropdown">
                        <a href="{{ route('frontend.du-an.index') }}" class="dropdown-all">
                            <span><i class="fas fa-th-large"></i> Tất cả dự án</span>
                            <small>{{ $tongSoDuAn ?? 0 }} dự án</small>
                        </a>
                        @if (isset($khuVucMenu) && $khuVucMenu->count() > 0)
                            <div class="dropdown-divider"></div>

                            @foreach ($khuVucMenu as $kv)
                                <a href="{{ route('frontend.du-an.index', ['khu_vuc' => $kv->id]) }}">
                                    <span><i class="fas fa-map-marker-alt"></i> {{ $kv->ten_khu_vuc }}</span>
                                    <small>{{ $kv->so_du_an }} dự án</small>
                                </a>
                            @endforeach
                        @endif
                    </div>
                </li>

                {{-- Tin tức --}}
                <li class="nav-item has-dropdown">
                    <a href="{{ route('frontend.tin-tuc.index') }}"
                        class="nav-link {{ request()->routeIs('frontend.bai-viet.*') ? 'active' : '' }}">
                        Tin tức
                        <i class="fas fa-chevron-down nav-arrow"></i>
                    </a>
                    <div class="nav-dropdown">
                        <a href="{{ route('frontend.tin-tuc.index', ['loai' => 'tin_tuc']) }}">
                            <i class="fas fa-newspaper me-2"></i> Thị trường BĐS
                        </a>
                        <a href="{{ route('frontend.tin-tuc.index', ['loai' => 'kien_thuc']) }}">
                            <i class="fas fa-book me-2"></i> Kiến thức nhà đất
                        </a>
                        <a href="{{ route('frontend.tin-tuc.index', ['loai' => 'phong_thuy']) }}">
                            <i class="fas fa-yin-yang me-2"></i> Phong thủy
                        </a>
                    </div>
                </li>


                {{-- Giới thiệu --}}
                <li class="nav-item">
                    <a href="{{ route('frontend.gioi-thieu') }}"
                        class="nav-link {{ request()->routeIs('frontend.gioi-thieu') ? 'active' : '' }}">
                        Giới thiệu
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('frontend.gioi-thieu') }}"
                        class="nav-link {{ request()->routeIs('frontend.gioi-thieu') ? 'active' : '' }}">
                        Nội Thất
                    </a>
                </li>
                {{-- Ký gửi --}}
                <li class="nav-item">
                    <a href="{{ route('frontend.ky-gui.create') }}"
                        class="nav-link nav-link-cta {{ request()->routeIs('frontend.ky-gui.*') ? 'active' : '' }}">
                        <i class="fas fa-paper-plane me-1"></i> Ký gửi BĐS
                    </a>
                </li>


            </ul>

            {{-- ── Actions ── --}}
            <div class="navbar-actions">

                {{-- Yêu thích --}}
                <a href="{{ route('frontend.yeu-thich.index') }}" class="action-btn" title="BĐS yêu thích">
                    <i class="far fa-heart"></i>
                    <span class="action-badge" id="soYeuThich" style="display:none">0</span>
                </a>

                {{-- Tài khoản --}}
                @auth('customer')
                    <div class="nav-item has-dropdown">
                        <button class="action-btn user-btn">
                            <i class="fas fa-user-circle"></i>
                            <span class="d-none d-lg-inline ms-1">
                                {{ Str::limit(Auth::guard('customer')->user()->ho_ten ?? 'Tài khoản', 12) }}
                            </span>
                            <i class="fas fa-chevron-down ms-1" style="font-size:.7rem"></i>
                        </button>
                        <div class="nav-dropdown nav-dropdown-right">
                            <a href="{{ route('frontend.yeu-thich.index') }}">
                                <i class="far fa-heart me-2"></i> BĐS yêu thích
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="#"
                                onclick="event.preventDefault();document.getElementById('logoutCustomer').submit()">
                                <i class="fas fa-sign-out-alt me-2 text-danger"></i>
                                <span class="text-danger">Đăng xuất</span>
                            </a>
                            <form id="logoutCustomer" action="{{ route('khach-hang.logout') }}" method="POST"
                                style="display:none">
                                @csrf
                            </form>
                        </div>
                    </div>
                @else
                    <button class="btn-login" id="btnMoLogin" title="Đăng nhập / Đăng ký"
                        onclick="openAuthModal('login')">
                        <i class="fas fa-user me-1"></i>
                        <span class="d-none d-lg-inline">Đăng nhập</span>
                    </button>
                @endauth

                {{-- Hamburger --}}
                <button class="hamburger" id="hamburger" aria-label="Menu">
                    <span></span><span></span><span></span>
                </button>

            </div>
        </div>
    </div>
</nav>

{{-- Overlay mobile --}}
<div class="nav-overlay" id="navOverlay"></div>


{{-- ============================================================
     CSS
============================================================ --}}
<style>
    /* ===================== TOPBAR ===================== */
    .topbar {
        background: #1a3c5e;
        padding: .45rem 0;
        font-size: .8rem;
    }

    .topbar-inner {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
    }

    .topbar-left,
    .topbar-right {
        display: flex;
        align-items: center;
        gap: 1.2rem;
    }

    .topbar-left span,
    .topbar-left a {
        color: rgba(255, 255, 255, .8);
        display: flex;
        align-items: center;
        gap: .4rem;
        text-decoration: none;
    }

    .topbar-left i {
        color: #e8a020;
        font-size: .75rem;
    }

    .topbar-phone {
        background: #e8a020;
        color: #1a3c5e !important;
        font-weight: 800;
        padding: .25rem .8rem;
        border-radius: 20px;
        text-decoration: none !important;
        display: flex;
        align-items: center;
        gap: .4rem;
        transition: background .2s;
    }

    .topbar-phone:hover {
        background: #f5c842;
    }

    .topbar-socials {
        display: flex;
        gap: .5rem;
    }

    .topbar-socials a {
        width: 26px;
        height: 26px;
        border-radius: 50%;
        background: rgba(255, 255, 255, .1);
        color: rgba(255, 255, 255, .8);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .75rem;
        text-decoration: none;
        transition: background .2s, color .2s;
    }

    .topbar-socials a:hover {
        background: #e8a020;
        color: #fff;
    }

    /* ===================== MAIN NAVBAR ===================== */
    .main-navbar {
        background: #fff;
        box-shadow: 0 2px 20px rgba(0, 0, 0, .08);
        position: sticky;
        top: 0;
        z-index: 1000;
        transition: box-shadow .3s;
    }

    .main-navbar.scrolled {
        box-shadow: 0 4px 30px rgba(0, 0, 0, .15);
    }

    .navbar-inner {
        display: flex;
        align-items: center;
        justify-content: space-between;
        height: 68px;
        gap: .5rem;
        flex-wrap: nowrap;
    }

    /* Logo */
    .navbar-logo {
        display: flex;
        align-items: center;
        text-decoration: none;
        flex-shrink: 0;
    }

    /* Nav menu */
    .nav-menu {
        list-style: none;
        margin: 0;
        padding: 0;
        display: flex;
        align-items: center;
        gap: 0;
        flex: 1;
        justify-content: center;
    }

    .nav-item {
        position: relative;
        margin: 0px 5px;
        padding: 3px;
    }

    .nav-link {
        display: flex;
        align-items: center;
        gap: .2rem;
        padding: .45rem .5rem;
        color: #333;
        font-weight: 600;
        font-size: .85rem;
        text-decoration: none;
        border-radius: 8px;
        transition: color .2s, background .2s;
        white-space: nowrap;
    }

    .nav-icon-small {
        font-size: .7rem;
    }

    .nav-link:hover,
    .nav-link.active {
        color: #1a3c5e;
        background: #f0f6ff;
    }

    .nav-link.active {
        color: #e8a020;
    }

    .nav-arrow {
        font-size: .6rem;
        color: #bbb;
        transition: transform .25s;
    }

    .nav-item.has-dropdown:hover .nav-arrow {
        transform: rotate(180deg);
    }

    .navbar-logo {
        flex-shrink: 0;
    }

    .navbar-actions {
        flex-shrink: 0;
    }

    /* CTA Ký gửi */
    .nav-link-cta {
        background: linear-gradient(135deg, #e8a020, #f5c842) !important;
        color: #1a3c5e !important;
        border-radius: 8px;
        padding: .45rem .9rem !important;
    }

    .nav-link-cta:hover {
        background: linear-gradient(135deg, #d4911c, #e8a020) !important;
        box-shadow: 0 4px 15px rgba(232, 160, 32, .35);
    }

    /* ===================== DROPDOWN BASE ===================== */
    .nav-dropdown {
        display: none;
        position: absolute;
        top: calc(100% + 1px);
        left: 0;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 15px 50px rgba(0, 0, 0, .15);
        border: 1px solid #f0f0f0;
        min-width: 220px;
        padding: .6rem;
        z-index: 999;
        animation: dropIn .2s ease;
    }

    .nav-dropdown-right {
        left: auto;
        right: 0;
    }

    @keyframes dropIn {
        from {
            opacity: 0;
            transform: translateY(-6px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .nav-item.has-dropdown:hover>.nav-dropdown {
        display: block;
    }

    .nav-dropdown a {
        display: flex;
        align-items: center;
        /* justify-content: space-between; */
        padding: .52rem .85rem;
        color: #444;
        font-size: .87rem;
        font-weight: 500;
        text-decoration: none;
        border-radius: 8px;
        white-space: nowrap;
        transition: background .15s, color .15s;
    }

    .nav-dropdown a span {
        display: flex;
        align-items: center;
        gap: .45rem;
        flex: 1;
        min-width: 0;
    }

    .nav-dropdown a:hover {
        background: #f0f6ff;
        color: #1a3c5e;
        font-weight: 600;
    }

    .nav-dropdown a i {
        color: #2d6a9f;
        width: 16px;
        text-align: center;
    }

    .nav-dropdown a small {
        color: #bbb;
        font-size: .72rem;
        font-weight: 500;
        padding-left: .5rem;
        flex-shrink: 0;
    }

    .dropdown-divider {
        height: 1px;
        background: #f0f0f0;
        margin: .4rem .6rem;
    }

    /* Dòng "Tất cả" */
    .dropdown-all {
        background: #f0f6ff;
        font-weight: 700 !important;
        color: #1a3c5e !important;
        border-radius: 8px;
        margin-bottom: .2rem;
    }

    .dropdown-all i {
        color: #1a3c5e !important;
    }

    .dropdown-all:hover {
        background: #dceeff !important;
    }

    .dropdown-all-blue {
        background: #eef5ff !important;
        color: #2d6a9f !important;
    }

    .dropdown-all-blue i {
        color: #2d6a9f !important;
    }

    .dropdown-all-blue:hover {
        background: #dceeff !important;
    }

    /* Highlight links */
    .highlight-link {
        color: #e8a020 !important;
        font-weight: 600 !important;
    }

    .highlight-link i {
        color: #e8a020 !important;
    }

    .highlight-link:hover {
        background: #fff8e7 !important;
    }

    .highlight-link-blue {
        color: #2d6a9f !important;
        font-weight: 600 !important;
    }

    .highlight-link-blue i {
        color: #2d6a9f !important;
    }

    .highlight-link-blue:hover {
        background: #eef5ff !important;
    }

    /* ===================== MEGA DROPDOWN CĂN HỘ ===================== */
    .can-ho-mega {
        /* width: 490px !important; */
        padding: 0 !important;
        overflow: hidden;
        left: 50% !important;
        transform: translateX(-50%) !important;
    }

    .can-ho-mega-inner {
        display: grid;
        grid-template-columns: 1fr 1fr;
    }

    .can-ho-col {
        padding: .75rem .30rem;
    }

    .can-ho-col-right {
        border-left: 1px solid #f0f0f0;
        background: #fafcff;
    }

    .can-ho-col-title {
        font-size: .72rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #e8a020;
        padding: .15rem .85rem .5rem;
        border-bottom: 2px solid #e8a020;
        margin-bottom: .35rem;
        display: flex;
        align-items: center;
        gap: .35rem;
    }

    .badge-hot {
        background: #e74c3c;
        color: #fff;
        font-size: .58rem;
        font-weight: 800;
        padding: .08rem .32rem;
        border-radius: 3px;
        text-transform: uppercase;
        vertical-align: middle;
    }

    .badge-pn {
        font-size: .68rem;
        font-weight: 600;
        background: #f0f6ff;
        color: #2d6a9f;
        padding: .12rem .42rem;
        border-radius: 20px;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .badge-pn-blue {
        background: #eef5ff;
        color: #2d6a9f;
    }

    /* Footer chips */
    .can-ho-footer {
        border-top: 1px solid #f0f0f0;
        background: #f8f9ff;
        padding: .55rem .85rem;
        display: flex;
        gap: .4rem;
        flex-wrap: wrap;
    }

    .can-ho-footer-blue {
        background: #f0f6ff;
    }

    .can-ho-footer a {
        font-size: .76rem;
        font-weight: 600;
        padding: .28rem .65rem;
        border-radius: 20px;
        background: #fff;
        border: 1px solid #e0e8f0;
        color: #555;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: .3rem;
        transition: all .15s;
        white-space: nowrap;
        justify-content: flex-start;
    }

    .can-ho-footer a i {
        color: #e8a020;
        font-size: .76rem;
    }

    .can-ho-footer-blue a i {
        color: #2d6a9f;
    }

    .can-ho-footer a:hover {
        background: #1a3c5e;
        color: #fff;
        border-color: #1a3c5e;
    }

    .can-ho-footer a:hover i {
        color: #f5c842;
    }

    /* ===================== ACTIONS ===================== */
    .navbar-actions {
        display: flex;
        align-items: center;
        gap: .4rem;
        flex-shrink: 0;
    }

    .action-btn {
        position: relative;
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: #f0f6ff;
        border: none;
        color: #1a3c5e;
        font-size: .85rem;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        text-decoration: none;
        transition: background .2s, transform .2s;
    }

    .action-btn:hover {
        background: #1a3c5e;
        color: #e8a020;
        transform: scale(1.05);
    }

    .action-badge {
        position: absolute;
        top: -4px;
        right: -4px;
        background: #e74c3c;
        color: #fff;
        font-size: .62rem;
        font-weight: 700;
        width: 17px;
        height: 17px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #fff;
    }

    .user-btn {
        height: 38px;
        padding: 0 .8rem;
        border-radius: 10px;
        background: #f0f6ff;
        border: none;
        color: #1a3c5e;
        font-size: .86rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: .3rem;
        cursor: pointer;
        transition: background .2s;
    }

    .user-btn:hover {
        background: #1a3c5e;
        color: #fff;
    }

    .btn-login {
        background: linear-gradient(135deg, #1a3c5e, #2d6a9f);
        color: #fff;
        border: none;
        padding: .48rem 1rem;
        border-radius: 10px;
        font-weight: 700;
        font-size: .86rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: .35rem;
        transition: transform .2s, box-shadow .2s;
    }

    .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(26, 60, 94, .3);
    }

    /* ===================== HAMBURGER ===================== */
    .hamburger {
        display: none;
        flex-direction: column;
        gap: 5px;
        background: none;
        border: none;
        cursor: pointer;
        padding: .4rem;
    }

    .hamburger span {
        display: block;
        width: 22px;
        height: 2px;
        background: #1a3c5e;
        border-radius: 2px;
        transition: all .3s;
    }

    .hamburger.open span:nth-child(1) {
        transform: rotate(45deg) translate(5px, 5px);
    }

    .hamburger.open span:nth-child(2) {
        opacity: 0;
    }

    .hamburger.open span:nth-child(3) {
        transform: rotate(-45deg) translate(5px, -5px);
    }

    /* Overlay */
    .nav-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, .5);
        z-index: 998;
    }

    .nav-overlay.show {
        display: block;
    }

    /* ===================== RESPONSIVE ===================== */
    @media (max-width: 1199px) {
        .hamburger {
            display: flex;
        }

        .nav-icon-small {
            display: none !important;
        }

        .nav-menu {
            position: fixed;
            top: 0;
            left: -100%;
            width: 300px;
            height: 100vh;
            background: #fff;
            flex-direction: column;
            align-items: flex-start;
            padding: 5rem 1rem 2rem;
            gap: 0;
            overflow-y: auto;
            z-index: 999;
            box-shadow: 5px 0 30px rgba(0, 0, 0, .15);
            transition: left .3s ease;
            justify-content: flex-start;
            flex: none;
        }

        .nav-menu.open {
            left: 0;
        }

        .nav-item {
            width: 100%;
        }

        .nav-link {
            width: 100%;
            padding: .72rem 1rem;
            font-size: .9rem;
            border-radius: 8px;
        }

        /* Tắt hover trên mobile */
        .nav-item.has-dropdown:hover>.nav-dropdown,
        .nav-item.has-dropdown:hover>.can-ho-mega {
            display: none;
        }

        /* Mở bằng click */
        .nav-item.has-dropdown.mobile-open>.nav-dropdown,
        .nav-item.has-dropdown.mobile-open>.can-ho-mega {
            display: block !important;
        }

        /* Dropdown static trên mobile */
        .nav-dropdown {
            position: static !important;
            width: 100% !important;
            transform: none !important;
            box-shadow: none !important;
            border: none !important;
            border-radius: 0 !important;
            padding: 0 0 0 .5rem !important;
            animation: none !important;
            min-width: auto !important;
            background: #f8faff;
            border-left: 3px solid #e0eaf5 !important;
            margin: .2rem 0 .4rem 1rem;
        }

        /* Can ho mega trên mobile */
        .can-ho-mega {
            width: 100% !important;
            transform: none !important;
            left: 0 !important;
            border-radius: 0 !important;
            box-shadow: none !important;
            border: none !important;
            position: static !important;
            margin: .2rem 0 .4rem 1rem;
        }

        .can-ho-mega-inner {
            grid-template-columns: 1fr;
        }

        .can-ho-col-right {
            border-left: none;
            border-top: 1px solid #f0f0f0;
            background: #fff;
        }

        .can-ho-footer {
            flex-wrap: wrap;
        }

        .topbar-left span:not(:first-child) {
            display: none;
        }
    }

    @media (max-width: 575px) {
        .topbar-left {
            display: none;
        }
    }
</style>


{{-- ============================================================
     JAVASCRIPT
============================================================ --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {

        // ── Sticky navbar ──
        const navbar = document.getElementById('mainNavbar');
        window.addEventListener('scroll', () => {
            navbar?.classList.toggle('scrolled', window.scrollY > 50);
        });

        // ── Hamburger ──
        const hamburger = document.getElementById('hamburger');
        const navMenu = document.getElementById('navMenu');
        const navOverlay = document.getElementById('navOverlay');

        hamburger?.addEventListener('click', () => {
            hamburger.classList.toggle('open');
            navMenu.classList.toggle('open');
            navOverlay.classList.toggle('show');
            document.body.style.overflow = navMenu.classList.contains('open') ? 'hidden' : '';
        });

        navOverlay?.addEventListener('click', closeMenu);

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeMenu();
        });

        function closeMenu() {
            hamburger?.classList.remove('open');
            navMenu?.classList.remove('open');
            navOverlay?.classList.remove('show');
            document.body.style.overflow = '';
        }

        // ── Mobile dropdown (click toggle) ──
        document.querySelectorAll('.nav-item.has-dropdown > .nav-link, .nav-item.has-dropdown > button')
            .forEach(link => {
                link.addEventListener('click', function(e) {
                    if (window.innerWidth >= 1200) return;
                    e.preventDefault();
                    const item = this.closest('.nav-item');
                    const isOpen = item.classList.contains('mobile-open');
                    // Đóng tất cả item khác
                    document.querySelectorAll('.nav-item.has-dropdown.mobile-open')
                        .forEach(el => el.classList.remove('mobile-open'));
                    // Toggle item hiện tại
                    if (!isOpen) item.classList.add('mobile-open');
                });
            });

        // ── Badge yêu thích từ localStorage ──
        try {
            const ds = JSON.parse(localStorage.getItem('yeu_thich') || '[]');
            const badge = document.getElementById('soYeuThich');
            if (badge) {
                if (ds.length > 0) {
                    badge.textContent = ds.length;
                    badge.style.display = 'flex';
                } else {
                    badge.style.display = 'none';
                }
            }
        } catch (e) {}

    });
</script>
