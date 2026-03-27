<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Thành Công Land — Bất động sản Vinhomes Smart City')</title>
    <meta name="description" content="@yield('meta_description', 'Đơn vị phân phối bất động sản uy tín tại Vinhomes Smart City và khu vực phía Tây Hà Nội.')">
    <meta name="keywords" content="@yield('meta_keywords', 'bất động sản, vinhomes smart city, căn hộ hà nội, mua bán căn hộ, cho thuê căn hộ')">
    <meta name="robots" content="@yield('meta_robots', 'index, follow')">
    <link rel="canonical" href="@yield('canonical', url()->current())">

    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('og_title', 'Thành Công Land')">
    <meta property="og:description" content="@yield('og_description', 'Bất động sản Vinhomes Smart City')">
    <meta property="og:image" content="@yield('og_image', asset('images/og-default.jpg'))">
    <meta property="og:locale" content="vi_VN">
    <meta property="og:site_name" content="Thành Công Land">

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/apple-touch-icon.png') }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    {{-- File CSS Tổng (Có tham số thời gian để ép trình duyệt nhận CSS mới) --}}
    <link rel="stylesheet" href="{{ asset('css/frontend.css') }}?v={{ time() }}">

    @stack('styles')
</head>

<body>
    @include('frontend.partials.page-loader')
    @include('frontend.partials.flash-messages')
    @include('frontend.partials.header')

    @hasSection('breadcrumb')
        <div class="breadcrumb-wrap">
            <div class="container-fluid px-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('frontend.home') }}"><i class="fas fa-home"></i></a>
                    </li>
                    @yield('breadcrumb')
                </ol>
            </div>
        </div>
    @endif

    <main id="main-content">
        @yield('content')
    </main>

    @include('frontend.partials.footer')
    @include('frontend.partials.contact-panel')
    @include('frontend.partials.chat-widget')
    @include('frontend.partials.auth-modal')
    @include('frontend.partials.so-sanh-bar')
    @include('frontend.partials.modal-khach-hang')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- CẤU HÌNH ROUTING JS --}}
    <script>
        window.APP = {
            csrfToken: '{{ csrf_token() }}',
            baseUrl: '{{ url('/') }}',
            isLoggedIn: {{ Auth::guard('customer')->check() ? 'true' : 'false' }},
            user: @if (Auth::guard('customer')->check())
                {
                    id: {{ Auth::guard('customer')->id() }},
                    name: '{{ Auth::guard('customer')->user()->ho_ten }}'
                }
            @else
                null
            @endif ,
            routes: {
                login: '{{ route('khach-hang.login') }}',
                loginPost: '{{ route('khach-hang.login.post') }}',
                registerPost: '{{ route('khach-hang.register.post') }}',
                sendOtp: '{{ route('khach-hang.send-otp') }}',
                verifyOtp: '{{ route('khach-hang.verify-otp') }}',
                logout: '{{ route('khach-hang.logout') }}',
                forgotPost: '{{ route('khach-hang.forgot.post') }}',
                resetPost: '{{ route('khach-hang.reset.post') }}',
                profileUpdate: '{{ route('khach-hang.profile.update') }}',
                changePassword: '{{ route('khach-hang.change-password') }}',
                yeuThichToggle: '{{ route('frontend.yeu-thich.toggle', [], false) }}',
                chatKhoiTao: '{{ route('frontend.chat.khoi-tao', [], false) }}',
                chatGui: '{{ route('frontend.chat.gui', [], false) }}',
                soSanhIndex: '{{ route('frontend.so-sanh.index') }}',
                soSanhThem: '{{ url('/so-sanh/them') }}',
                soSanhXoa: '{{ url('/so-sanh/xoa') }}',
                soSanhModal: '{{ route('frontend.so-sanh.modal', [], false) }}'
            }
        };

        /* Xử lý Nút Yêu Thích (Phải nằm đây vì dùng Blade) */
        function toggleYeuThich(btn, batDongSanId) {
            if (!APP.isLoggedIn) {
                window.openAuthModal('login');
                showFlash('Vui lòng đăng nhập để lưu yêu thích.', 'info');
                return;
            }
            var icon = btn.querySelector('i');
            var wasLiked = btn.classList.contains('liked');
            btn.classList.toggle('liked', !wasLiked);
            if (icon) icon.className = wasLiked ? 'far fa-heart' : 'fas fa-heart';

            fetch(APP.routes.yeuThichToggle, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': APP.csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    bat_dong_san_id: batDongSanId
                })
            }).then(r => r.json()).then(data => {
                if (data.success) {
                    btn.classList.toggle('liked', data.is_liked);
                    if (icon) icon.className = data.is_liked ? 'fas fa-heart' : 'far fa-heart';
                    btn.title = data.is_liked ? 'Bỏ yêu thích' : 'Yêu thích';
                    if (typeof showFlash === 'function') showFlash(data.message, data.is_liked ? 'success' :
                        'info');
                } else {
                    btn.classList.toggle('liked', wasLiked);
                    if (icon) icon.className = wasLiked ? 'fas fa-heart' : 'far fa-heart';
                }
            }).catch(() => {
                btn.classList.toggle('liked', wasLiked);
                if (icon) icon.className = wasLiked ? 'fas fa-heart' : 'far fa-heart';
            });
        }
    </script>

    {{-- LOAD FILE FRONTEND.JS (Chống Cache Cực Mạnh) --}}
    <script src="{{ asset('js/frontend.js') }}?v={{ time() }}"></script>

    @stack('scripts')
</body>

</html>
