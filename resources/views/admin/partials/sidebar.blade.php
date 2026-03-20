@php
    $nhanVien = Auth::guard('nhanvien')->user();
    $routeName = Route::currentRouteName();
@endphp

<aside id="sidebar">

    {{-- ── Logo ── --}}
    <div class="sidebar-logo">
        <img src="{{ asset('images/logo.png') }}" alt="TCL" onerror="this.style.display='none'">
        <div class="sidebar-logo-text">
            <div class="name">Thành Công Land</div>
            <div class="sub">Admin Panel</div>
        </div>
    </div>

    {{-- ── Navigation ── --}}
    <nav class="sidebar-nav">

        {{-- ════════════════════════════════
             TỔNG QUAN
        ════════════════════════════════ --}}
        <div class="nav-group-label">Tổng quan</div>

        <a class="nav-item nav-link-item {{ $routeName === 'nhanvien.dashboard' ? 'active' : '' }}"
            href="{{ route('nhanvien.dashboard') }}" data-tooltip="Dashboard">
            <i class="fas fa-chart-pie nav-icon"></i>
            <span class="nav-link-text">Dashboard</span>
        </a>

        {{-- ════════════════════════════════
             BẤT ĐỘNG SẢN
        ════════════════════════════════ --}}
        <div class="nav-group-label">Bất động sản</div>

        @if ($nhanVien->hasRole(['admin', 'sale']))
            <a class="nav-item nav-link-item {{ str_starts_with($routeName, 'nhanvien.admin.bat-dong-san') ? 'active' : '' }}"
                href="{{ route('nhanvien.admin.bat-dong-san.index') }}" data-tooltip="Bất động sản">
                <i class="fas fa-building nav-icon"></i>
                <span class="nav-link-text">Bất động sản</span>
            </a>
        @endif

        @if ($nhanVien->hasRole(['admin', 'nguon_hang']))
            <a class="nav-item nav-link-item {{ str_starts_with($routeName, 'nhanvien.admin.du-an') ? 'active' : '' }}"
                href="{{ route('nhanvien.admin.du-an.index') }}" data-tooltip="Dự án">
                <i class="fas fa-city nav-icon"></i>
                <span class="nav-link-text">Dự án</span>
            </a>

            <a class="nav-item nav-link-item {{ str_starts_with($routeName, 'nhanvien.admin.ky-gui') ? 'active' : '' }}"
                href="{{ route('nhanvien.admin.ky-gui.index') }}" data-tooltip="Ký gửi">
                <i class="fas fa-file-signature nav-icon"></i>
                <span class="nav-link-text">Ký gửi</span>
                @php $kyGuiCount = \App\Models\KyGui::where('trang_thai', 'cho_duyet')->count(); @endphp
                @if ($kyGuiCount > 0)
                    <span class="nav-badge">{{ $kyGuiCount }}</span>
                @endif
            </a>
        @endif

        {{-- ════════════════════════════════
             KHÁCH HÀNG
        ════════════════════════════════ --}}
        <div class="nav-group-label">Khách hàng</div>

        @if ($nhanVien->hasRole(['admin', 'sale']))
            <a class="nav-item nav-link-item {{ str_starts_with($routeName, 'nhanvien.admin.khach-hang') ? 'active' : '' }}"
                href="{{ route('nhanvien.admin.khach-hang.index') }}" data-tooltip="Khách hàng">
                <i class="fas fa-users nav-icon"></i>
                <span class="nav-link-text">Khách hàng</span>
            </a>

            <a class="nav-item nav-link-item {{ str_starts_with($routeName, 'nhanvien.admin.lich-hen') ? 'active' : '' }}"
                href="{{ route('nhanvien.admin.lich-hen.index') }}" data-tooltip="Lịch hẹn">
                <i class="fas fa-calendar-check nav-icon"></i>
                <span class="nav-link-text">Lịch hẹn</span>
                @php $lichHenCount = \App\Models\LichHen::whereDate('thoi_gian_hen', today())->count(); @endphp
                @if ($lichHenCount > 0)
                    <span class="nav-badge">{{ $lichHenCount }}</span>
                @endif
            </a>
        @endif

        <a class="nav-item nav-link-item {{ $routeName === 'nhanvien.admin.lien-he.index' ? 'active' : '' }}"
            href="{{ route('nhanvien.admin.lien-he.index') }}" data-tooltip="Liên hệ">
            <i class="fas fa-envelope nav-icon"></i>
            <span class="nav-link-text">Liên hệ</span>
        </a>

        <a class="nav-item nav-link-item {{ str_starts_with($routeName, 'nhanvien.admin.chat') ? 'active' : '' }}"
            href="{{ route('nhanvien.admin.chat.index') }}" data-tooltip="Chat">
            <i class="fas fa-comments nav-icon"></i>
            <span class="nav-link-text">Chat</span>
            @php $chatCount = \App\Models\PhienChat::where('trang_thai', 'dang_cho')->count(); @endphp
            @if ($chatCount > 0)
                <span class="nav-badge">{{ $chatCount }}</span>
            @endif
        </a>

        {{-- ════════════════════════════════
             NỘI DUNG
        ════════════════════════════════ --}}
        <div class="nav-group-label">Nội dung</div>

        <a class="nav-item nav-link-item {{ str_starts_with($routeName, 'nhanvien.admin.bai-viet') ? 'active' : '' }}"
            href="{{ route('nhanvien.admin.bai-viet.index') }}" data-tooltip="Bài viết">
            <i class="fas fa-newspaper nav-icon"></i>
            <span class="nav-link-text">Bài viết</span>
        </a>

        {{-- ════════════════════════════════
             HỆ THỐNG — chỉ Admin
        ════════════════════════════════ --}}
        @if ($nhanVien->isAdmin())

            <div class="nav-group-label">Danh mục</div>

            <a class="nav-item nav-link-item {{ str_starts_with($routeName, 'nhanvien.admin.khu-vuc') ? 'active' : '' }}"
                href="{{ route('nhanvien.admin.khu-vuc.index') }}" data-tooltip="Khu vực">
                <i class="fas fa-map-marked-alt nav-icon"></i>
                <span class="nav-link-text">Khu vực</span>
                @php $soKhuVuc = \App\Models\KhuVuc::count(); @endphp
                @if ($soKhuVuc > 0)
                    <span class="nav-badge nav-badge-gray">{{ $soKhuVuc }}</span>
                @endif
            </a>

            <div class="nav-group-label">Hệ thống</div>

            <a class="nav-item nav-link-item {{ str_starts_with($routeName, 'nhanvien.admin.nhan-vien') ? 'active' : '' }}"
                href="{{ route('nhanvien.admin.nhan-vien.index') }}" data-tooltip="Nhân viên">
                <i class="fas fa-user-tie nav-icon"></i>
                <span class="nav-link-text">Nhân viên</span>
            </a>

        @endif

    </nav>

    {{-- ── User Info ── --}}
    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div class="sidebar-avatar">
                @if ($nhanVien->anh_dai_dien && \Illuminate\Support\Facades\Storage::disk('public')->exists($nhanVien->anh_dai_dien))
                    <img src="{{ asset('storage/' . $nhanVien->anh_dai_dien) }}" alt="{{ $nhanVien->ho_ten }}"
                        style="width:100%;height:100%;border-radius:50%;object-fit:cover">
                @else
                    {{ mb_strtoupper(mb_substr($nhanVien->ho_ten, 0, 1)) }}
                @endif
            </div>
            <div class="sidebar-user-info">
                <div class="sidebar-user-name">{{ $nhanVien->ho_ten }}</div>
                <div class="sidebar-user-role">{{ $nhanVien->vai_tro_label }}</div>
            </div>
        </div>
    </div>

</aside>
