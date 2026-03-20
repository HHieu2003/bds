@php $nhanVien = Auth::guard('nhanvien')->user(); @endphp

<header id="topbar">

    {{-- Toggle sidebar --}}
    <button class="btn-toggle-sidebar" onclick="toggleSidebar()" title="Thu/Mở menu">
        <i class="fas fa-bars"></i>
    </button>

    {{-- Tiêu đề trang --}}
    <span class="topbar-title">@yield('page_title', 'Dashboard')</span>

    {{-- Right actions --}}
    <div class="topbar-right">

        {{-- Xem trang chủ --}}
        <a href="{{ route('frontend.home') }}" target="_blank" class="topbar-icon-btn" title="Xem trang chủ">
            <i class="fas fa-external-link-alt"></i>
        </a>

        {{-- Thông báo --}}
        <button class="topbar-icon-btn" title="Thông báo">
            <i class="fas fa-bell"></i>
            <span class="notif-dot"></span>
        </button>

        {{-- User --}}
        <div style="position:relative;">
            <button class="topbar-user" id="topbarUserBtn" onclick="toggleUserDropdown()">
                <div class="topbar-user-avatar">
                    @if ($nhanVien->avatar)
                        <img src="{{ asset('storage/' . $nhanVien->avatar) }}" alt="{{ $nhanVien->ho_ten }}"
                            style="width:100%;height:100%;object-fit:cover;">
                    @else
                        {{ mb_strtoupper(mb_substr($nhanVien->ho_ten, 0, 1)) }}
                    @endif
                </div>
                <span class="topbar-user-name d-none d-md-block">
                    {{ $nhanVien->ho_ten }}
                </span>
                <i class="fas fa-chevron-down" style="font-size:.65rem;color:var(--text-sub);"></i>
            </button>

            {{-- Dropdown --}}
            <div class="user-dropdown" id="userDropdown">
                <div class="user-dropdown-header">
                    <div class="uname">{{ $nhanVien->ho_ten }}</div>
                    <div class="urole">{{ $nhanVien->vai_tro_label }}</div>
                </div>
                <a href="#">
                    <i class="fas fa-user-circle"></i> Hồ sơ cá nhân
                </a>
                <a href="#">
                    <i class="fas fa-key"></i> Đổi mật khẩu
                </a>
                <a href="{{ route('frontend.home') }}" target="_blank">
                    <i class="fas fa-globe"></i> Xem website
                </a>
                <div style="border-top:1px solid var(--border);margin:.3rem 0;"></div>
                <form action="{{ route('nhanvien.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i> Đăng xuất
                    </button>
                </form>
            </div>
        </div>

    </div>
</header>
