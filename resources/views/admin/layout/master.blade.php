<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard | Real Estate</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', sans-serif; }
        #sidebar { min-width: 260px; max-width: 260px; min-height: 100vh; background: #0F172A; color: #fff; transition: all 0.3s; }
        #sidebar .sidebar-header { padding: 20px; background: #1E293B; border-bottom: 1px solid #334155; }
        #sidebar ul.components { padding: 20px 0; }
        #sidebar ul li a { padding: 12px 20px; display: block; font-size: 0.95rem; color: #94a3b8; text-decoration: none; transition: 0.3s; display: flex; align-items: center; }
        #sidebar ul li a:hover { color: #fff; background: #1E293B; padding-left: 25px; }
        #sidebar ul li a.active { color: #38bdf8; background: #1E293B; border-left: 4px solid #38bdf8; font-weight: 600; }
        #sidebar ul li a i { width: 25px; font-size: 1.1rem; margin-right: 10px; }
        .sidebar-label { font-size: 0.75rem; text-transform: uppercase; color: #64748b; padding: 20px 20px 5px 20px; font-weight: 700; letter-spacing: 1px; }
        #content { width: 100%; min-height: 100vh; display: flex; flex-direction: column; }
        .top-navbar { background: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.05); padding: 10px 20px; }
    </style>
</head>
<body>
<div class="d-flex">
    <nav id="sidebar">
        <div class="sidebar-header d-flex align-items-center">
            <i class="fas fa-building fa-2x text-primary me-2"></i>
            <div>
                <h5 class="mb-0 fw-bold">QUẢN TRỊ</h5>
                <small class="text-muted" style="font-size: 0.7rem;">
                    Vai trò: {{ strtoupper(Auth::user()->role) }}
                </small>
            </div>
        </div>

        <ul class="list-unstyled components">
            {{-- DASHBOARD --}}
            <li>
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i> Tổng quan
                </a>
            </li>

            {{-- PHẦN 1: KHO HÀNG (DỰ ÁN & BĐS) --}}
            <div class="sidebar-label">Kho hàng & Sản phẩm</div>
            
            {{-- Admin & Nguồn: Quản lý toàn diện --}}
            @if(Auth::user()->role == 'admin' || Auth::user()->role == 'nguon')
                <li>
                    <a href="{{ route('admin.du-an.index') }}" class="{{ request()->routeIs('admin.du-an.*') ? 'active' : '' }}">
                        <i class="fas fa-city"></i> Quản lý Dự án
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.bat-dong-san.index') }}" class="{{ request()->routeIs('admin.bat-dong-san.*') ? 'active' : '' }}">
                        <i class="fas fa-home"></i> Quản lý Bất động sản
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.ky-gui.index') }}" class="{{ request()->routeIs('admin.ky-gui.*') ? 'active' : '' }}">
                        <i class="fas fa-file-contract"></i> Duyệt Ký gửi
                    </a>
                </li>
            @endif

            {{-- Sale: Chỉ được xem để tìm hàng --}}
            @if(Auth::user()->role == 'sale')
                <li>
                    <a href="{{ route('admin.bat-dong-san.index') }}" class="{{ request()->routeIs('admin.bat-dong-san.*') ? 'active' : '' }}">
                        <i class="fas fa-search"></i> Tra cứu Kho hàng
                    </a>
                </li>
            @endif

            {{-- PHẦN 2: KINH DOANH (KHÁCH & CHAT) --}}
            {{-- Chỉ Admin & Sale mới làm việc với khách --}}
            @if(Auth::user()->role == 'admin' || Auth::user()->role == 'sale')
            <div class="sidebar-label">Kinh doanh</div>
            <li>
                <a href="{{ route('admin.chat.index') }}" class="{{ request()->routeIs('admin.chat.*') ? 'active' : '' }}">
                    <i class="fas fa-comments"></i> Chat tư vấn
                </a>
            </li>
            <li>
                <a href="{{ route('admin.lien-he.index') }}" class="{{ request()->routeIs('admin.lien-he.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i> Khách hàng / Leads
                </a>
            </li>
            @endif

            {{-- PHẦN 3: VẬN HÀNH (LỊCH HẸN) --}}
            <div class="sidebar-label">Vận hành</div>
            <li>
                <a href="{{ route('admin.lich-hen.index') }}" class="{{ request()->routeIs('admin.lich-hen.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-check"></i> Quản lý Lịch hẹn
                </a>
            </li>
            
            {{-- Tin tức (Ai cũng xem được, Admin/Nguồn được sửa) --}}
            <li>
                <a href="{{ route('admin.bai-viet.index') }}" class="{{ request()->routeIs('admin.bai-viet.*') ? 'active' : '' }}">
                    <i class="fas fa-newspaper"></i> Tin tức
                </a>
            </li>

            {{-- Đăng xuất --}}
            <div class="sidebar-label">Hệ thống</div>
            <li>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="text-danger">
                    <i class="fas fa-sign-out-alt"></i> Đăng xuất
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>
        </ul>
    </nav>

    <div id="content">
        <nav class="navbar navbar-expand-lg top-navbar">
            <div class="container-fluid">
                <button type="button" id="sidebarCollapse" class="btn btn-light shadow-sm"><i class="fas fa-bars"></i></button>
                <div class="ms-auto d-flex align-items-center">
                    <span class="me-3 text-muted small">Xin chào, <strong>{{ Auth::user()->name }}</strong> ({{ Auth::user()->role }})</span>
                    <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=0D8ABC&color=fff" class="rounded-circle" width="35" height="35">
                </div>
            </div>
        </nav>
        <div class="p-4">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @yield('content')
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('sidebarCollapse').addEventListener('click', function() {
        document.getElementById('sidebar').classList.toggle('active');
        // Thêm CSS: #sidebar.active { margin-left: -260px; } trong file css nếu cần
    });
</script>
</body>
</html>