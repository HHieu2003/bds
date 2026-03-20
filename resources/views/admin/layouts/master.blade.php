<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Thành Công Land Admin</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --navy: #1a3c5e;
            --navy-dark: #122a43;
            --navy-light: #2d5a8e;
            --primary: #FF8C42;
            --primary-dark: #FF6B1A;
            --sidebar-w: 260px;
            --topbar-h: 64px;
            --text: #1f2937;
            --text-sub: #6b7280;
            --border: #e5e7eb;
            --bg: #f4f6f9;
            --radius: 10px;
            --shadow: 0 2px 12px rgba(0, 0, 0, .07);
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        html,
        body {
            height: 100%;
            margin: 0;
        }

        body {
            font-family: 'Be Vietnam Pro', system-ui, sans-serif;
            font-size: 14px;
            background: var(--bg);
            color: var(--text);
            overflow-x: hidden;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        ::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary);
        }

        /* ──────────── SIDEBAR ──────────── */
        #sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-w);
            height: 100vh;
            background: var(--navy-dark);
            display: flex;
            flex-direction: column;
            z-index: 1000;
            transition: transform .3s ease, width .3s ease;
            overflow: hidden;
        }

        #sidebar.collapsed {
            width: 64px;
        }

        #sidebar.mobile-hidden {
            transform: translateX(-100%);
        }

        /* Logo */
        .sidebar-logo {
            height: var(--topbar-h);
            display: flex;
            align-items: center;
            padding: 0 1.1rem;
            gap: .75rem;
            border-bottom: 1px solid rgba(255, 255, 255, .07);
            flex-shrink: 0;
        }

        .sidebar-logo img {
            height: 30px;
            flex-shrink: 0;

        }

        /* Thêm vào phần CSS sidebar */
        .nav-badge-gray {
            background: rgba(255, 255, 255, 0.2) !important;
            color: #fff !important;
        }

        .sidebar-logo-text {
            overflow: hidden;
            white-space: nowrap;
            transition: opacity .2s, width .2s;
        }

        .sidebar-logo-text .name {
            font-size: .8rem;
            font-weight: 900;
            color: #fff;
            line-height: 1.1;
        }

        .sidebar-logo-text .sub {
            font-size: .7rem;
            color: rgba(255, 255, 255, .45);
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        #sidebar.collapsed .sidebar-logo-text {
            opacity: 0;
            width: 0;
        }

        /* Nav */
        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: .6rem 0;
        }

        .nav-group-label {
            font-size: .62rem;
            font-weight: 800;
            color: rgba(255, 255, 255, .28);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            padding: .8rem 1.2rem .3rem;
            white-space: nowrap;
            overflow: hidden;
            transition: opacity .2s;
        }

        #sidebar.collapsed .nav-group-label {
            opacity: 0;
        }

        .nav-item {
            display: block;
            margin: .15rem .5rem;
        }

        .nav-link-item {
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: .62rem .75rem;
            border-radius: 8px;
            color: rgba(255, 255, 255, .65);
            font-weight: 500;
            font-size: .84rem;
            transition: background .2s, color .2s;
            white-space: nowrap;
            position: relative;
        }

        .nav-link-item:hover {
            background: rgba(255, 255, 255, .07);
            color: #fff;
        }

        .nav-link-item.active {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: #fff;
            box-shadow: 0 4px 12px rgba(255, 140, 66, .3);
        }

        .nav-link-item i.nav-icon {
            width: 18px;
            text-align: center;
            font-size: .9rem;
            flex-shrink: 0;
        }

        .nav-link-text {
            overflow: hidden;
            white-space: nowrap;
            transition: opacity .2s, width .2s;
        }

        #sidebar.collapsed .nav-link-text {
            opacity: 0;
            width: 0;
        }

        .nav-badge {
            margin-left: auto;
            font-size: .62rem;
            font-weight: 800;
            padding: .12rem .45rem;
            border-radius: 20px;
            background: var(--primary);
            color: #fff;
            transition: opacity .2s;
        }

        #sidebar.collapsed .nav-badge {
            opacity: 0;
        }

        /* Tooltip khi collapsed */
        #sidebar.collapsed .nav-link-item:hover::after {
            content: attr(data-tooltip);
            position: absolute;
            left: calc(100% + .6rem);
            top: 50%;
            transform: translateY(-50%);
            background: var(--navy);
            color: #fff;
            padding: .35rem .7rem;
            border-radius: 6px;
            font-size: .75rem;
            white-space: nowrap;
            pointer-events: none;
            z-index: 999;
            box-shadow: 0 4px 12px rgba(0, 0, 0, .2);
        }

        /* Footer sidebar */
        .sidebar-footer {
            padding: .8rem .6rem;
            border-top: 1px solid rgba(255, 255, 255, .07);
            flex-shrink: 0;
        }

        .sidebar-user {
            display: flex;
            align-items: center;
            gap: .7rem;
            padding: .5rem .6rem;
            border-radius: 8px;
            transition: background .2s;
            cursor: default;
        }

        .sidebar-user:hover {
            background: rgba(255, 255, 255, .07);
        }

        .sidebar-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid rgba(255, 255, 255, .2);
            flex-shrink: 0;
            background: var(--navy-light);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: .8rem;
            font-weight: 700;
        }

        .sidebar-user-info {
            overflow: hidden;
            transition: opacity .2s, width .2s;
        }

        .sidebar-user-name {
            font-size: .8rem;
            font-weight: 700;
            color: #fff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sidebar-user-role {
            font-size: .68rem;
            color: rgba(255, 255, 255, .45);
            white-space: nowrap;
        }

        #sidebar.collapsed .sidebar-user-info {
            opacity: 0;
            width: 0;
        }

        /* ──────────── TOPBAR ──────────── */
        #topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-w);
            right: 0;
            height: var(--topbar-h);
            background: #fff;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            gap: 1rem;
            z-index: 900;
            transition: left .3s ease;
            box-shadow: var(--shadow);
        }

        #topbar.sidebar-collapsed {
            left: 64px;
        }

        .btn-toggle-sidebar {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            border: none;
            background: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--text-sub);
            transition: background .2s, color .2s;
            flex-shrink: 0;
        }

        .btn-toggle-sidebar:hover {
            background: var(--bg);
            color: var(--navy);
        }

        .topbar-title {
            font-size: .95rem;
            font-weight: 700;
            color: var(--navy);
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .topbar-right {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: .6rem;
        }

        .topbar-icon-btn {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            border: none;
            background: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--text-sub);
            transition: background .2s;
            position: relative;
        }

        .topbar-icon-btn:hover {
            background: #e5e7eb;
            color: var(--navy);
        }

        .notif-dot {
            position: absolute;
            top: 6px;
            right: 6px;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--primary);
            border: 2px solid #fff;
        }

        .topbar-user {
            display: flex;
            align-items: center;
            gap: .5rem;
            padding: .35rem .7rem;
            border-radius: 8px;
            cursor: pointer;
            border: none;
            background: #f3f4f6;
            transition: background .2s;
        }

        .topbar-user:hover {
            background: #e5e7eb;
        }

        .topbar-user-avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            object-fit: cover;
            background: var(--navy);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: .72rem;
            font-weight: 700;
            flex-shrink: 0;
            overflow: hidden;
        }

        .topbar-user-name {
            font-size: .8rem;
            font-weight: 700;
            color: var(--navy);
            max-width: 120px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* Dropdown user */
        .user-dropdown {
            position: absolute;
            top: calc(100% + .5rem);
            right: 1.5rem;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            width: 200px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, .1);
            display: none;
            z-index: 999;
            overflow: hidden;
        }

        .user-dropdown.show {
            display: block;
        }

        .user-dropdown-header {
            padding: .85rem 1rem;
            border-bottom: 1px solid var(--border);
            background: #f9fafb;
        }

        .user-dropdown-header .uname {
            font-weight: 700;
            font-size: .84rem;
            color: var(--navy);
        }

        .user-dropdown-header .urole {
            font-size: .72rem;
            color: var(--text-sub);
            margin-top: .1rem;
        }

        .user-dropdown a,
        .user-dropdown button {
            display: flex;
            align-items: center;
            gap: .6rem;
            padding: .62rem 1rem;
            font-size: .83rem;
            color: var(--text);
            transition: background .15s;
            width: 100%;
            border: none;
            background: none;
            cursor: pointer;
            font-family: inherit;
            text-align: left;
        }

        .user-dropdown a:hover,
        .user-dropdown button:hover {
            background: #f3f4f6;
        }

        .user-dropdown .logout-btn {
            color: #e74c3c;
        }

        .user-dropdown .logout-btn:hover {
            background: #fff5f5;
        }

        .user-dropdown i {
            width: 16px;
            text-align: center;
            font-size: .85rem;
        }

        /* ──────────── MAIN ──────────── */
        #main-wrapper {
            margin-left: var(--sidebar-w);
            padding-top: var(--topbar-h);
            transition: margin-left .3s ease;
            min-height: 100vh;
        }

        #main-wrapper.sidebar-collapsed {
            margin-left: 64px;
        }

        .main-content {
            padding: 1.5rem;
            max-width: 1600px;
        }

        /* Page header */
        .page-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .page-header-title {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--navy);
        }

        .page-header-sub {
            font-size: .8rem;
            color: var(--text-sub);
            margin-top: .2rem;
        }

        .page-header-actions {
            display: flex;
            gap: .6rem;
            align-items: center;
            flex-wrap: wrap;
        }

        /* Card */
        .card-admin {
            background: #fff;
            border-radius: var(--radius);
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
        }

        .card-admin-header {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .5rem;
        }

        .card-admin-title {
            font-size: .9rem;
            font-weight: 700;
            color: var(--navy);
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        .card-admin-title i {
            color: var(--primary);
        }

        .card-admin-body {
            padding: 1.25rem;
        }

        /* Flash */
        .flash-admin {
            display: flex;
            align-items: center;
            gap: .7rem;
            padding: .85rem 1.1rem;
            border-radius: var(--radius);
            font-size: .84rem;
            font-weight: 500;
            margin-bottom: 1.2rem;
            animation: fadeInDown .3s ease;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .flash-admin-success {
            background: #f0fff4;
            border-left: 4px solid #27ae60;
            color: #276749;
        }

        .flash-admin-error {
            background: #fff5f5;
            border-left: 4px solid #e74c3c;
            color: #c0392b;
        }

        .flash-admin-info {
            background: #eff8ff;
            border-left: 4px solid #3b82f6;
            color: #1e40af;
        }

        .flash-admin-warning {
            background: #fffbeb;
            border-left: 4px solid #f59e0b;
            color: #92400e;
        }

        .flash-admin-close {
            margin-left: auto;
            background: none;
            border: none;
            cursor: pointer;
            color: inherit;
            opacity: .5;
            font-size: .85rem;
            padding: 0;
        }

        .flash-admin-close:hover {
            opacity: 1;
        }

        /* Btn */
        .btn-primary-admin {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: .55rem 1.1rem;
            font-size: .83rem;
            font-weight: 700;
            cursor: pointer;
            font-family: inherit;
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            transition: transform .2s, box-shadow .2s;
        }

        .btn-primary-admin:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(255, 140, 66, .35);
            color: #fff;
        }

        .btn-secondary-admin {
            background: #f3f4f6;
            color: var(--text);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: .55rem 1.1rem;
            font-size: .83rem;
            font-weight: 600;
            cursor: pointer;
            font-family: inherit;
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            transition: background .2s;
        }

        .btn-secondary-admin:hover {
            background: #e5e7eb;
        }

        /* Table */
        .table-admin {
            width: 100%;
            border-collapse: collapse;
            font-size: .83rem;
        }

        .table-admin th {
            padding: .7rem 1rem;
            background: #f8fafc;
            border-bottom: 2px solid var(--border);
            font-weight: 700;
            color: var(--text-sub);
            text-transform: uppercase;
            font-size: .68rem;
            letter-spacing: .5px;
            white-space: nowrap;
        }

        .table-admin td {
            padding: .75rem 1rem;
            border-bottom: 1px solid #f3f4f6;
            vertical-align: middle;
            color: var(--text);
        }

        .table-admin tr:last-child td {
            border-bottom: none;
        }

        .table-admin tr:hover td {
            background: #fafafa;
        }

        /* Badge trạng thái */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: .3rem;
            padding: .2rem .65rem;
            border-radius: 20px;
            font-size: .72rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .status-badge::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
            display: block;
        }

        .badge-con-hang {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-con-hang::before {
            background: #10b981;
        }

        .badge-da-ban {
            background: #e0e7ff;
            color: #3730a3;
        }

        .badge-da-ban::before {
            background: #6366f1;
        }

        .badge-cho-thue {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-cho-thue::before {
            background: #f59e0b;
        }

        .badge-dang-cho {
            background: #fef9c3;
            color: #854d0e;
        }

        .badge-dang-cho::before {
            background: #eab308;
        }

        .badge-dang-chat {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-dang-chat::before {
            background: #10b981;
        }

        .badge-cho-duyet {
            background: #fce7f3;
            color: #9d174d;
        }

        .badge-cho-duyet::before {
            background: #ec4899;
        }

        .badge-da-duyet {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-da-duyet::before {
            background: #10b981;
        }

        /* Mobile overlay */
        #sidebarOverlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .5);
            z-index: 999;
        }

        #sidebarOverlay.show {
            display: block;
        }

        /* Responsive */
        @media (max-width: 992px) {
            #sidebar {
                transform: translateX(-100%);
            }

            #sidebar.mobile-open {
                transform: translateX(0);
                width: var(--sidebar-w) !important;
            }

            #topbar {
                left: 0 !important;
            }

            #main-wrapper {
                margin-left: 0 !important;
            }
        }

        @media (max-width: 576px) {
            .main-content {
                padding: 1rem;
            }

            .page-header {
                flex-direction: column;
            }
        }
    </style>

    @stack('styles')
</head>

<body>

    {{-- ── Overlay mobile ── --}}
    <div id="sidebarOverlay" onclick="closeMobileSidebar()"></div>

    {{-- ══════════════════════════════════
     SIDEBAR
══════════════════════════════════ --}}
    @include('admin.partials.sidebar')

    {{-- ══════════════════════════════════
     TOPBAR
══════════════════════════════════ --}}
    @include('admin.partials.topbar')

    {{-- ══════════════════════════════════
     MAIN WRAPPER
══════════════════════════════════ --}}
    <div id="main-wrapper">
        <div class="main-content">

            {{-- Flash Messages --}}
            @foreach (['success', 'error', 'info', 'warning'] as $type)
                @if (session($type))
                    <div class="flash-admin flash-admin-{{ $type }}">
                        <i
                            class="fas {{ match ($type) {
                                'success' => 'fa-check-circle',
                                'error' => 'fa-exclamation-circle',
                                'info' => 'fa-info-circle',
                                default => 'fa-exclamation-triangle',
                            } }}"></i>
                        <span>{{ session($type) }}</span>
                        <button class="flash-admin-close" onclick="this.closest('.flash-admin').remove()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif
            @endforeach

            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // ── Toggle Sidebar ──
        var sidebarCollapsed = localStorage.getItem('adminSidebarCollapsed') === 'true';
        var sidebar = document.getElementById('sidebar');
        var topbar = document.getElementById('topbar');
        var mainWrap = document.getElementById('main-wrapper');

        function applySidebarState() {
            if (window.innerWidth > 992) {
                if (sidebarCollapsed) {
                    sidebar?.classList.add('collapsed');
                    topbar?.classList.add('sidebar-collapsed');
                    mainWrap?.classList.add('sidebar-collapsed');
                } else {
                    sidebar?.classList.remove('collapsed');
                    topbar?.classList.remove('sidebar-collapsed');
                    mainWrap?.classList.remove('sidebar-collapsed');
                }
            }
        }
        applySidebarState();

        window.toggleSidebar = function() {
            if (window.innerWidth <= 992) {
                sidebar?.classList.toggle('mobile-open');
                document.getElementById('sidebarOverlay')?.classList.toggle('show');
            } else {
                sidebarCollapsed = !sidebarCollapsed;
                localStorage.setItem('adminSidebarCollapsed', sidebarCollapsed);
                applySidebarState();
            }
        };

        window.closeMobileSidebar = function() {
            sidebar?.classList.remove('mobile-open');
            document.getElementById('sidebarOverlay')?.classList.remove('show');
        };

        // ── User Dropdown ──
        window.toggleUserDropdown = function() {
            document.getElementById('userDropdown')?.classList.toggle('show');
        };
        document.addEventListener('click', function(e) {
            if (!e.target.closest('#topbarUserBtn')) {
                document.getElementById('userDropdown')?.classList.remove('show');
            }
        });

        // ── Flash auto dismiss ──
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                document.querySelectorAll('.flash-admin').forEach(function(el) {
                    el.style.transition = 'opacity .4s';
                    el.style.opacity = '0';
                    setTimeout(function() {
                        el.remove();
                    }, 400);
                });
            }, 5000);
        });
    </script>

    @stack('scripts')
</body>

</html>
