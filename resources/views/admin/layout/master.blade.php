<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Bất Động Sản</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { min-height: 100vh; overflow-x: hidden; background-color: #f4f6f9; }
        #sidebar-wrapper { min-height: 100vh; margin-left: -15rem; transition: margin .25s ease-out; background-color: #343a40; color: white; }
        #sidebar-wrapper .sidebar-heading { padding: 0.875rem 1.25rem; font-size: 1.2rem; font-weight: bold; background: #212529; }
        #sidebar-wrapper .list-group { width: 15rem; }
        #page-content-wrapper { min-width: 100vw; }
        body.sb-sidenav-toggled #sidebar-wrapper { margin-left: 0; }
        
        .list-group-item { border: none; padding: 15px 20px; font-size: 15px; }
        .list-group-item.active { background-color: #0d6efd; color: white; font-weight: bold; }
        .list-group-item-action { color: #cfd8dc; background-color: #343a40; }
        .list-group-item-action:hover { background-color: #495057; color: white; }
        
        @media (min-width: 768px) {
            #sidebar-wrapper { margin-left: 0; }
            #page-content-wrapper { min-width: 0; width: 100%; }
            body.sb-sidenav-toggled #sidebar-wrapper { margin-left: -15rem; }
        }
    </style>
</head>
<body>
    <div class="d-flex" id="wrapper">
        <div class="border-end" id="sidebar-wrapper">
            <div class="sidebar-heading border-bottom text-warning">
                <i class="fa-solid fa-building-columns me-2"></i> ADMIN PRO
            </div>
            <div class="list-group list-group-flush">
                <a href="{{ route('bat-dong-san.index') }}" class="list-group-item list-group-item-action {{ request()->is('bat-dong-san*') ? 'active' : '' }}">
                    <i class="fa-solid fa-house me-2"></i> Kho Hàng BĐS
                </a>
                <a href="{{ route('du-an.index') }}" class="list-group-item list-group-item-action {{ request()->is('du-an*') ? 'active' : '' }}">
                    <i class="fa-solid fa-city me-2"></i> Quản lý Dự Án
                </a>
                <a href="{{ route('lien-he.index') }}" class="list-group-item list-group-item-action {{ request()->is('quan-ly-lien-he*') ? 'active' : '' }}">
                    <i class="fa-solid fa-users me-2"></i> Khách Hàng (Leads)
                </a>
                <l class="nav-item">
    <a href="{{ route('admin.chat.index') }}" class="nav-link list-group-item list-group-item-action {{ request()->routeIs('admin.chat.*') ? 'active bg-primary text-white' : '' }}">
        <i class="fa-solid fa-comments nav-icon"></i>
        
            Chat Hỗ Trợ
            
            @php
                $unreadCount = \App\Models\ChatSession::where('is_read', false)->count();
            @endphp
            
            @if($unreadCount > 0)
                <span class="badge bg-danger right ms-2">{{ $unreadCount }}</span>
            @endif
        
    </a>

                <a href="/" target="_blank" class="list-group-item list-group-item-action bg-secondary text-white mt-3">
                    <i class="fa-solid fa-eye me-2"></i> Xem Trang Chủ
                </a>
            </div>
        </div>

        <div id="page-content-wrapper">
            <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm px-4 py-3">
                <div class="container-fluid">
                    <span class="fw-bold text-secondary">Hệ thống quản lý Bất Động Sản</span>
                    
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ms-auto mt-2 mt-lg-0 align-items-center">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle fw-bold text-dark" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                    <i class="fa-solid fa-user-tie me-1"></i> {{ Auth::user()->name ?? 'Admin' }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end shadow">
                                    <li>
                                        <form action="{{ route('logout') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="fa-solid fa-right-from-bracket me-2"></i> Đăng xuất
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <div class="container-fluid p-4">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fa-solid fa-check-circle me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>