@php
    $nhanVien = Auth::guard('nhanvien')->user();
    $routeName = Route::currentRouteName() ?? '';

    // ── Badges ──
    $kyGuiCount = \App\Models\KyGui::where('trang_thai', 'cho_duyet')->count();

    // Lịch hẹn: badge theo role
    if ($nhanVien->isSale()) {
        // Sale: lịch hẹn do mình tạo, hôm nay, chưa xong
        $lichHenCount = \App\Models\LichHen::where('nhan_vien_sale_id', $nhanVien->id)
            ->whereDate('thoi_gian_hen', today())
            ->whereNotIn('trang_thai', ['hoan_thanh', 'huy', 'tu_choi'])
            ->count();
        // Badge riêng: lịch cần xác nhận từ KH mới đặt
        $lichHenMoiCount = \App\Models\LichHen::where('nhan_vien_sale_id', $nhanVien->id)
            ->where('trang_thai', 'moi_dat')
            ->count();
    } elseif ($nhanVien->isNguonHang()) {
        // Nguồn hàng: lịch chờ mình xác nhận
        $lichHenCount = \App\Models\LichHen::where('nhan_vien_nguon_hang_id', $nhanVien->id)
            ->where('trang_thai', 'cho_xac_nhan')
            ->count();
        $lichHenMoiCount = 0;
    } else {
        // Admin: tất cả lịch hôm nay chưa xong
        $lichHenCount = \App\Models\LichHen::whereDate('thoi_gian_hen', today())
            ->whereNotIn('trang_thai', ['hoan_thanh', 'huy', 'tu_choi'])
            ->count();
        $lichHenMoiCount = \App\Models\LichHen::where('trang_thai', 'moi_dat')->count();
    }

    $chatCount = \App\Models\PhienChat::where('trang_thai', 'dang_cho')->count();
    $soKhuVuc = $nhanVien->isAdmin() ? \App\Models\KhuVuc::count() : 0;

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
        <div class="sidebar-logo-text">
            <div class="name">Thành Công Land</div>
            <div class="sub">Admin Panel</div>
        </div>
    </div>

    {{-- ══ NAV ══ --}}
    <nav class="sidebar-nav" id="sidebarNav">

        {{-- ── TỔNG QUAN ── --}}
        <div class="nav-group-label">Tổng quan</div>

        <div class="nav-item">
            <a class="nav-link-item {{ $active('nhanvien.dashboard') }}" href="{{ route('nhanvien.dashboard') }}"
                data-tooltip="Dashboard">
                <i class="fas fa-chart-pie nav-icon"></i>
                <span class="nav-link-text">Dashboard</span>
            </a>
        </div>

        {{-- ── BẤT ĐỘNG SẢN ── --}}
        <div class="nav-group-label">Bất động sản</div>

        @if ($nhanVien->hasRole(['admin', 'sale']))
            <div class="nav-item">
                <a class="nav-link-item {{ $active('nhanvien.admin.bat-dong-san') }}"
                    href="{{ route('nhanvien.admin.bat-dong-san.index') }}" data-tooltip="Bất động sản">
                    <i class="fas fa-building nav-icon"></i>
                    <span class="nav-link-text">Bất động sản</span>
                </a>
            </div>
        @endif

        @if ($nhanVien->hasRole(['admin', 'nguon_hang']))
            <div class="nav-item">
                <a class="nav-link-item {{ $active('nhanvien.admin.du-an') }}"
                    href="{{ route('nhanvien.admin.du-an.index') }}" data-tooltip="Dự án">
                    <i class="fas fa-city nav-icon"></i>
                    <span class="nav-link-text">Dự án</span>
                </a>
            </div>
            <li class="nav-item">
                <a href="{{ route('nhanvien.admin.chu-nha.index') }}"
                    class="nav-link-item {{ $active('nhanvien.admin.chu-nha') }}" data-tooltip="Chủ nhà">
                    <i class="fas fa-user-tie text-center me-2 text-secondary"></i>
                    <span class="nav-link-text">Chủ nhà</span>
                </a>
            </li>
            <div class="nav-item">
                <a class="nav-link-item {{ $active('nhanvien.admin.ky-gui') }}"
                    href="{{ route('nhanvien.admin.ky-gui.index') }}" data-tooltip="Ký gửi">
                    <i class="fas fa-file-signature nav-icon"></i>
                    <span class="nav-link-text">Ký gửi</span>
                    @if ($kyGuiCount > 0)
                        <span class="nav-badge">{{ $kyGuiCount }}</span>
                    @endif
                </a>
            </div>
        @endif

        {{-- ── KHÁCH HÀNG & LỊCH HẸN ── --}}
        <div class="nav-group-label">Khách hàng</div>

        @if ($nhanVien->hasRole(['admin', 'sale']))
            <div class="nav-item">
                <a class="nav-link-item {{ $active('nhanvien.admin.khach-hang') }}"
                    href="{{ route('nhanvien.admin.khach-hang.index') }}" data-tooltip="Khách hàng">
                    <i class="fas fa-users nav-icon"></i>
                    <span class="nav-link-text">Khách hàng</span>
                </a>
            </div>
        @endif

        {{-- Lịch hẹn: tất cả role liên quan đều thấy --}}
        @if ($nhanVien->hasRole(['admin', 'sale', 'nguon_hang']))
            <div class="nav-item">
                <a class="nav-link-item {{ $active('nhanvien.admin.lich-hen') }}"
                    href="{{ route('nhanvien.admin.lich-hen.index') }}" data-tooltip="Lịch hẹn">
                    <i class="fas fa-calendar-check nav-icon"></i>
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

        <div class="nav-item">
            <a class="nav-link-item {{ $active('nhanvien.admin.lien-he') }}"
                href="{{ route('nhanvien.admin.lien-he.index') }}" data-tooltip="Liên hệ">
                <i class="fas fa-envelope nav-icon"></i>
                <span class="nav-link-text">Liên hệ</span>
            </a>
        </div>

        <div class="nav-item">
            <a class="nav-link-item {{ $active('nhanvien.admin.chat') }}"
                href="{{ route('nhanvien.admin.chat.index') }}" data-tooltip="Chat">
                <i class="fas fa-comments nav-icon"></i>
                <span class="nav-link-text">Chat</span>
                @if ($chatCount > 0)
                    <span class="nav-badge">{{ $chatCount }}</span>
                @endif
            </a>
        </div>

        {{-- ── NỘI DUNG ── --}}
        <div class="nav-group-label">Nội dung</div>

        <div class="nav-item">
            <a class="nav-link-item {{ $active('nhanvien.admin.bai-viet') }}"
                href="{{ route('nhanvien.admin.bai-viet.index') }}" data-tooltip="Bài viết">
                <i class="fas fa-newspaper nav-icon"></i>
                <span class="nav-link-text">Bài viết</span>
            </a>
        </div>

        {{-- ── DANH MỤC & HỆ THỐNG — Admin ── --}}
        @if ($nhanVien->isAdmin())
            <div class="nav-group-label">Danh mục</div>

            <div class="nav-item">
                <a class="nav-link-item {{ $active('nhanvien.admin.khu-vuc') }}"
                    href="{{ route('nhanvien.admin.khu-vuc.index') }}" data-tooltip="Khu vực">
                    <i class="fas fa-map-marked-alt nav-icon"></i>
                    <span class="nav-link-text">Khu vực</span>
                    @if ($soKhuVuc > 0)
                        <span class="nav-badge nav-badge-gray">{{ $soKhuVuc }}</span>
                    @endif
                </a>
            </div>

            <div class="nav-group-label">Hệ thống</div>

            <div class="nav-item">
                <a class="nav-link-item {{ $active('nhanvien.admin.nhan-vien') }}"
                    href="{{ route('nhanvien.admin.nhan-vien.index') }}" data-tooltip="Nhân viên">
                    <i class="fas fa-user-tie nav-icon"></i>
                    <span class="nav-link-text">Nhân viên</span>
                </a>
            </div>

            <div class="nav-item">
                <a class="nav-link-item" href="#" data-tooltip="Cài đặt">
                    <i class="fas fa-cog nav-icon"></i>
                    <span class="nav-link-text">Cài đặt</span>
                </a>
            </div>
        @endif

    </nav>

    {{-- ══ FOOTER ══ --}}
    <div class="sidebar-footer">
        <button class="sidebar-collapse-btn" onclick="toggleSidebar()" data-tooltip="Thu gọn menu">
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
                    &nbsp;·&nbsp; {{ $nhanVien->vai_tro_label }}
                </div>
            </div>
        </div>
    </div>

</aside>
