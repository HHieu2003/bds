<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Thành Công Land Admin</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    {{-- ── Thư viện CSS ── --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    {{-- ── Admin CSS ── --}}
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}?v={{ @filemtime(public_path('css/admin.css')) }}">

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

            {{-- Nội dung từng trang --}}
            @yield('content')

        </div>
    </div>

    {{-- ── Confirm Delete Modal (dùng chung) ── --}}
    <div id="confirmModal">
        <div class="confirm-box">
            <div class="confirm-icon">
                <i class="fas fa-trash-alt"></i>
            </div>
            <div class="confirm-title">Xác nhận xóa</div>
            <div class="confirm-sub" id="confirmMessage">
                Bạn có chắc muốn xóa mục này? Hành động này không thể hoàn tác.
            </div>
            <div class="confirm-actions">
                <button class="btn-secondary-admin" onclick="closeConfirmModal()">
                    <i class="fas fa-times"></i> Hủy
                </button>
                <button class="btn-danger-admin" onclick="executeConfirm()">
                    <i class="fas fa-trash-alt"></i> Xóa ngay
                </button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/admin.js') }}"></script>
    @stack('scripts')


</body>

</html>
