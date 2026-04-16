@php
    $nhanVien = Auth::guard('nhanvien')->user();
    $routeName = Route::currentRouteName() ?? '';

    $kyGuiCount = \App\Models\KyGui::where('trang_thai', 'cho_duyet')->count();

    if ($nhanVien->isSale()) {
        $lichHenCount = \App\Models\LichHen::where('nhan_vien_sale_id', $nhanVien->id)
            ->whereDate('thoi_gian_hen', today())
            ->whereNotIn('trang_thai', ['hoan_thanh', 'huy', 'tu_choi'])
            ->count();
        $lichHenMoiCount = \App\Models\LichHen::where('nhan_vien_sale_id', $nhanVien->id)
            ->where('trang_thai', 'moi_dat')
            ->count();
    } elseif ($nhanVien->isNguonHang()) {
        $lichHenCount = \App\Models\LichHen::where('nhan_vien_nguon_hang_id', $nhanVien->id)
            ->where('trang_thai', 'cho_xac_nhan')
            ->count();
        $lichHenMoiCount = 0;
    } else {
        $lichHenCount = \App\Models\LichHen::whereDate('thoi_gian_hen', today())
            ->whereNotIn('trang_thai', ['hoan_thanh', 'huy', 'tu_choi'])
            ->count();
        $lichHenMoiCount = \App\Models\LichHen::where('trang_thai', 'moi_dat')->count();
    }

    $chatCount = \App\Models\PhienChat::where('trang_thai', 'dang_cho')->count();
    $lienHeCanXuLyCount = \App\Models\YeuCauLienHe::whereNull('nhan_vien_phu_trach_id')->count();
    $soKhuVuc = $nhanVien->isAdmin() ? \App\Models\KhuVuc::count() : 0;
    $bdsFilter = request('nhu_cau');
    $isBdsMenuOpen = str_starts_with($routeName, 'nhanvien.admin.bat-dong-san');

    // Helper active
    $active = fn(string|array $pattern) => (is_array($pattern)
            ? collect($pattern)->contains(fn($p) => str_starts_with($routeName, $p) || $routeName === $p)
            : str_starts_with($routeName, $pattern) || $routeName === $pattern)
        ? 'active'
        : '';
@endphp

<aside id="sidebar">

    {{-- ══ LOGO ══ --}}
    <div class="sidebar-logo">
        <img src="{{ asset('images/logo.png') }}" alt="TCL" onerror="this.style.display='none'">
    </div>

    {{-- ══ NAV MENU ══ --}}
    <nav class="sidebar-nav" id="sidebarNav">

        {{-- ── 1. BẢNG ĐIỀU KHIỂN ── --}}
        <div class="nav-group-label">Tổng quan</div>
        <div class="nav-item">
            <a class="nav-link-item {{ $active('nhanvien.dashboard') }}" href="{{ route('nhanvien.dashboard') }}"
                data-tooltip="Dashboard">
                <i class="fas fa-chart-pie nav-icon"></i>
                <span class="nav-link-text">Dashboard</span>
            </a>
        </div>

        {{-- ── 2. BÁN HÀNG & CRM (Trái tim của hệ thống) ── --}}
        <div class="nav-group-label">Bán hàng & CRM</div>

        @if ($nhanVien->hasRole(['admin', 'sale']))
            <div class="nav-item">
                <a class="nav-link-item {{ $active('nhanvien.admin.lien-he') }}"
                    href="{{ route('nhanvien.admin.lien-he.index') }}" data-tooltip="Leads / Liên hệ">
                    <i class="fas fa-bullhorn nav-icon"></i>
                    <span class="nav-link-text">Leads / Yêu cầu</span>
                    @if ($lienHeCanXuLyCount > 0)
                        <span class="nav-badge">{{ $lienHeCanXuLyCount }}</span>
                    @endif
                </a>
            </div>

            <div class="nav-item">
                <a class="nav-link-item {{ $active('nhanvien.admin.khach-hang') }}"
                    href="{{ route('nhanvien.admin.khach-hang.index') }}" data-tooltip="Khách hàng">
                    <i class="fas fa-users nav-icon"></i>
                    <span class="nav-link-text">Khách hàng</span>
                </a>
            </div>
        @endif

        @if ($nhanVien->hasRole(['admin', 'sale', 'nguon_hang']))
            <div class="nav-item">
                <a class="nav-link-item {{ $active('nhanvien.admin.lich-hen') }}"
                    href="{{ route('nhanvien.admin.lich-hen.index') }}" data-tooltip="Lịch hẹn">
                    <i class="fas fa-calendar-alt nav-icon"></i>
                    <span class="nav-link-text">Lịch hẹn</span>
                    @php $totalLichHenBadge = $lichHenCount + $lichHenMoiCount; @endphp
                    @if ($totalLichHenBadge > 0)
                        <span class="nav-badge {{ $lichHenMoiCount > 0 ? 'nav-badge-red' : '' }}">
                            {{ $totalLichHenBadge }}
                        </span>
                    @endif
                </a>
            </div>
        @endif

        @if (!$nhanVien->isNguonHang())
            <div class="nav-item">
                <a class="nav-link-item {{ $active('nhanvien.admin.chat') }}"
                    href="{{ route('nhanvien.admin.chat.index') }}" data-tooltip="Chat Tư Vấn">
                    <i class="fas fa-comments nav-icon"></i>
                    <span class="nav-link-text">Tin nhắn</span>
                    @if ($chatCount > 0)
                        <span class="nav-badge">{{ $chatCount }}</span>
                    @endif
                </a>
            </div>
        @endif

        {{-- ── 3. QUẢN LÝ NGUỒN HÀNG (Kho dữ liệu sản phẩm) ── --}}
        <div class="nav-group-label">Kho Bất Động Sản</div>

        @if ($nhanVien->hasRole(['admin', 'sale', 'nguon_hang']))
            <div class="nav-item nav-item-has-submenu {{ $isBdsMenuOpen ? 'is-open' : '' }}">
                <a class="nav-link-item {{ $active('nhanvien.admin.bat-dong-san') }}"
                    href="{{ route('nhanvien.admin.bat-dong-san.index') }}" data-tooltip="Kho BĐS">
                    <i class="fas fa-building nav-icon"></i>
                    <span class="nav-link-text">Danh sách BĐS</span>
                </a>

                <div class="nav-submenu">
                    <a href="{{ route('nhanvien.admin.bat-dong-san.index', array_merge(request()->except(['page', 'nhu_cau']), ['nhu_cau' => 'ban'])) }}"
                        class="nav-submenu-link {{ $isBdsMenuOpen && $bdsFilter === 'ban' ? 'active' : '' }}">
                        <i class="fas fa-tag"></i> BĐS Bán
                    </a>
                    <a href="{{ route('nhanvien.admin.bat-dong-san.index', array_merge(request()->except(['page', 'nhu_cau']), ['nhu_cau' => 'thue'])) }}"
                        class="nav-submenu-link {{ $isBdsMenuOpen && $bdsFilter === 'thue' ? 'active' : '' }}">
                        <i class="fas fa-key"></i> BĐS Thuê
                    </a>
                </div>
            </div>

            <div class="nav-item">
                <a class="nav-link-item {{ $active('nhanvien.admin.du-an') }}"
                    href="{{ route('nhanvien.admin.du-an.index') }}" data-tooltip="Dự án">
                    <i class="fas fa-city nav-icon"></i>
                    <span class="nav-link-text">Quản lý Dự án</span>
                </a>
            </div>
            <div class="nav-item">
                <a class="nav-link-item {{ $active('nhanvien.admin.khu-vuc') }}"
                    href="{{ route('nhanvien.admin.khu-vuc.index') }}" data-tooltip="Khu vực">
                    <i class="fas fa-map-marked-alt nav-icon"></i>
                    <span class="nav-link-text">Danh mục Khu vực</span>
                </a>
            </div>

            <div class="nav-item">
                <a class="nav-link-item {{ $active('nhanvien.admin.ngan-hang') }}"
                    href="{{ route('nhanvien.admin.ngan-hang.index') }}" data-tooltip="Lãi suất">
                    <i class="fas fa-university nav-icon"></i>
                    <span class="nav-link-text">Cấu hình Lãi suất</span>
                </a>
            </div>
        @endif

        @if ($nhanVien->hasRole(['admin', 'nguon_hang']))
            <div class="nav-item">
                <a class="nav-link-item {{ $active('nhanvien.admin.ky-gui') }}"
                    href="{{ route('nhanvien.admin.ky-gui.index') }}" data-tooltip="Yêu cầu ký gửi">
                    <i class="fas fa-file-signature nav-icon"></i>
                    <span class="nav-link-text">Yêu cầu Ký gửi</span>
                    @if ($kyGuiCount > 0)
                        <span class="nav-badge">{{ $kyGuiCount }}</span>
                    @endif
                </a>
            </div>

            <div class="nav-item">
                <a class="nav-link-item {{ $active('nhanvien.admin.chu-nha') }}"
                    href="{{ route('nhanvien.admin.chu-nha.index') }}" data-tooltip="Chủ nhà">
                    <i class="fas fa-user-tie nav-icon"></i>
                    <span class="nav-link-text">Data Chủ Nhà</span>
                </a>
            </div>
        @endif

        {{-- ── 4. TRUYỀN THÔNG & MARKETING ── --}}
        <div class="nav-group-label">Truyền thông</div>

        <div class="nav-item">
            <a class="nav-link-item {{ $active('nhanvien.admin.bai-viet') }}"
                href="{{ route('nhanvien.admin.bai-viet.index') }}" data-tooltip="Tin tức">
                <i class="fas fa-newspaper nav-icon"></i>
                <span class="nav-link-text">Tin tức & Bài viết</span>
            </a>
        </div>

        {{-- ── 5. HỆ THỐNG & CÀI ĐẶT (Dành riêng cho Admin) ── --}}
        @if ($nhanVien->isAdmin())
            <div class="nav-group-label">Hệ thống & Cài đặt</div>

            <div class="nav-item">
                <a class="nav-link-item {{ $active('nhanvien.admin.nhan-vien') }}"
                    href="{{ route('nhanvien.admin.nhan-vien.index') }}" data-tooltip="Nhân sự">
                    <i class="fas fa-id-badge nav-icon"></i>
                    <span class="nav-link-text">Quản lý Nhân sự</span>
                </a>
            </div>


            {{-- 
            <div class="nav-item">
                <a class="nav-link-item" href="#" data-tooltip="Cài đặt chung">
                    <i class="fas fa-cog nav-icon"></i>
                    <span class="nav-link-text">Cài đặt chung</span>
                </a>
            </div> --}}
        @endif

    </nav>

    {{-- ══ FOOTER (USER INFO & COLLAPSE) ══ --}}
    <div class="sidebar-footer">
        <button class="sidebar-collapse-btn" onclick="toggleSidebar()" data-tooltip="Thu gọn / Mở rộng">
            <i class="fas fa-chevron-left sidebar-collapse-icon"></i>
        </button>

        <div class="sidebar-user">
            <div class="sidebar-avatar">
                @php
                    $avatarPath = $nhanVien->anh_dai_dien ?? ($nhanVien->avatar ?? null);
                    $hasAvatar =
                        $avatarPath && \Illuminate\Support\Facades\Storage::disk('public')->exists($avatarPath);
                @endphp
                @if ($hasAvatar)
                    <img src="{{ asset('storage/' . $avatarPath) }}" alt="{{ $nhanVien->ho_ten }}">
                @else
                    {{ mb_strtoupper(mb_substr($nhanVien->ho_ten, 0, 1)) }}
                @endif
                <span class="sidebar-online-dot"></span>
            </div>
            <div class="sidebar-user-info">
                <div class="sidebar-user-name">{{ $nhanVien->ho_ten }}</div>
                <div class="sidebar-user-role">
                    <span class="sidebar-online-label">● Online</span>
                    &nbsp;·&nbsp; {{ $nhanVien->vai_tro_label ?? 'Quản trị' }}
                </div>
            </div>
        </div>
    </div>

</aside>
