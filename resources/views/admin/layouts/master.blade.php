<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Thành Công Land Admin</title>



    <link rel="icon" type="image/png" href="{{ asset('images/logo-icon.png') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo-icon.png') }}">

    {{-- ── Thư viện CSS ── --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    {{-- ── Admin CSS ── --}}

    @vite(['resources/css/admin.css', 'resources/js/admin.js'])
    {{-- ── CSS riêng từng trang ── --}}
    @stack('styles')
</head>

<body>

    {{-- ── Overlay mobile sidebar ── --}}
    <div id="sidebarOverlay" onclick="closeMobileSidebar()"></div>

    {{-- ── Sidebar ── --}}
    @include('admin.partials.sidebar')

    {{-- ── Topbar ── --}}
    @include('admin.partials.topbar')

    {{-- ── Main Content ── --}}
    <div id="main-wrapper">
        <div class="main-content">

            {{-- Nội dung từng trang --}}
            @yield('content')

        </div>
    </div>
    <div id="confirmModal">
        <div class="confirm-box">
            <div class="confirm-icon">
                <i class="fas fa-trash-alt"></i>
            </div>
            <div class="confirm-title">Xác nhận xóa</div>
            <p class="confirm-sub">
                Bạn có chắc muốn xóa
                <strong class="confirm-name text-danger"></strong>?<br>
                <span class="confirm-sub"></span>
            </p>
            <div class="confirm-actions">
                <button class="btn btn-secondary" onclick="closeConfirmModal()">
                    <i class="fas fa-times me-1"></i> Hủy
                </button>
                <button class="btn btn-danger" onclick="executeConfirm()">
                    <i class="fas fa-trash-alt me-1"></i> Xóa ngay
                </button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


    @php
        $flashToasts = [];
        foreach (['success', 'error', 'info', 'warning'] as $type) {
            if (session()->has($type)) {
                $flashToasts[] = [
                    'type' => $type,
                    'message' => (string) session($type),
                ];
            }
        }
    @endphp
    @if (!empty($flashToasts))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const toasts = @json($flashToasts);
                toasts.forEach((item, idx) => {
                    if (typeof showAdminToast !== 'function' || !item?.message) {
                        return;
                    }
                    const type = item.type === 'error' ? 'error' : item.type;
                    setTimeout(() => showAdminToast(item.message, type), idx * 180);
                });
            });
        </script>
    @endif

    @stack('scripts')

</body>

</html>
