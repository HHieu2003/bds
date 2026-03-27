@php
    $nhanVien = Auth::guard('nhanvien')->user();
    $avatarPath = $nhanVien->anh_dai_dien ?? ($nhanVien->avatar ?? null);
    $hasAvatar = $avatarPath && \Illuminate\Support\Facades\Storage::disk('public')->exists($avatarPath);
    $totalNotif =
        \App\Models\KyGui::where('trang_thai', 'cho_duyet')->count() +
        \App\Models\PhienChat::where('trang_thai', 'dang_cho')->count();
@endphp

<header id="topbar">

    {{-- Toggle Sidebar --}}
    <button class="btn-toggle-sidebar" onclick="toggleSidebar()" title="Thu/Mở menu">
        <i class="fas fa-bars"></i>
    </button>

    {{-- Tiêu đề trang --}}
    <div style="display:flex;align-items:center;gap:.5rem;flex:1;min-width:0;overflow:hidden;">
        <a href="{{ route('nhanvien.dashboard') }}"
            style="display:flex;align-items:center;justify-content:center;
                  width:28px;height:28px;border-radius:6px;background:#f3f4f6;
                  color:var(--text-sub);flex-shrink:0;transition:all .2s;"
            onmouseover="this.style.background='#fff5ef';this.style.color='var(--primary)'"
            onmouseout="this.style.background='#f3f4f6';this.style.color='var(--text-sub)'" title="Dashboard">
            <i class="fas fa-home" style="font-size:.75rem"></i>
        </a>
        <i class="fas fa-chevron-right" style="font-size:.6rem;color:var(--text-sub);flex-shrink:0"></i>
        <span
            style="font-size:.88rem;font-weight:700;color:var(--navy);
                     overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
            @yield('page_title', 'Dashboard')
        </span>
    </div>

    {{-- Right --}}
    <div class="topbar-right">

        {{-- Xem website --}}
        <a href="{{ route('frontend.home') }}" target="_blank" class="topbar-icon-btn" title="Xem trang chủ">
            <i class="fas fa-external-link-alt" style="font-size:.8rem"></i>
        </a>

        {{-- Thông báo --}}
        <button class="topbar-icon-btn" title="Thông báo" type="button">
            <i class="fas fa-bell" style="font-size:.85rem"></i>
            @if ($totalNotif > 0)
                <span class="notif-count"
                    style="position:absolute;top:-4px;right:-4px;
                             min-width:18px;height:18px;border-radius:9px;
                             background:#e74c3c;color:#fff;font-size:.58rem;
                             font-weight:800;display:flex;align-items:center;
                             justify-content:center;padding:0 4px;border:2px solid #fff;">
                    {{ $totalNotif > 99 ? '99+' : $totalNotif }}
                </span>
            @else
                <span class="notif-dot"></span>
            @endif
        </button>

        {{-- Divider --}}
        <div style="width:1px;height:22px;background:var(--border);flex-shrink:0;"></div>

        {{-- User Dropdown --}}
        <div style="position:relative;">

            <button class="topbar-user" id="topbarUserBtn" onclick="toggleUserDropdown()" type="button">
                <div class="topbar-user-avatar">
                    @if ($hasAvatar)
                        <img src="{{ asset('storage/' . $avatarPath) }}" alt="{{ $nhanVien->ho_ten }}"
                            style="width:100%;height:100%;object-fit:cover;">
                    @else
                        {{ mb_strtoupper(mb_substr($nhanVien->ho_ten, 0, 1)) }}
                    @endif
                </div>
                <div style="display:flex;flex-direction:column;align-items:flex-start;
                            overflow:hidden;max-width:130px;"
                    class="d-none d-md-flex">
                    <span class="topbar-user-name">{{ $nhanVien->ho_ten }}</span>
                    <span style="font-size:.67rem;color:var(--text-sub);white-space:nowrap;">
                        {{ $nhanVien->vai_tro_label }}
                    </span>
                </div>
                <i class="fas fa-chevron-down" id="topbarChevron"
                    style="font-size:.62rem;color:var(--text-sub);
                          transition:transform .2s;flex-shrink:0;"></i>
            </button>

            {{-- DROPDOWN --}}
            <div class="user-dropdown" id="userDropdown">

                <div class="user-dropdown-header">
                    <div class="uname">{{ $nhanVien->ho_ten }}</div>
                    <div class="urole">{{ $nhanVien->vai_tro_label }}</div>
                    @if ($nhanVien->email)
                        <div
                            style="font-size:.7rem;color:var(--text-sub);
                                    margin-top:.2rem;overflow:hidden;
                                    text-overflow:ellipsis;white-space:nowrap;">
                            {{ $nhanVien->email }}
                        </div>
                    @endif
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

                <div style="height:1px;background:var(--border);margin:.3rem 0;"></div>

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

{{-- Script inline — đảm bảo chạy ngay cả khi admin.js chưa load --}}
<script>
    // Chevron animation khi mở dropdown
    (function() {
        var origToggle = window.toggleUserDropdown;
        window.toggleUserDropdown = function() {
            var dd = document.getElementById('userDropdown');
            var chevron = document.getElementById('topbarChevron');
            if (!dd) return;
            dd.classList.toggle('show');
            if (chevron) {
                chevron.style.transform = dd.classList.contains('show') ? 'rotate(180deg)' : '';
            }
        };
    })();
</script>
