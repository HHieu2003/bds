<!DOCTYPE html>
<html lang="vi">

<head>
    {{-- ══════════════════════════════════════
         META CƠ BẢN
    ══════════════════════════════════════ --}}
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- SEO --}}
    <title>@yield('title', 'Thành Công Land — Bất động sản Vinhomes Smart City')</title>
    <meta name="description" content="@yield('meta_description', 'Đơn vị phân phối bất động sản uy tín tại Vinhomes Smart City và khu vực phía Tây Hà Nội.')">
    <meta name="keywords" content="@yield('meta_keywords', 'bất động sản, vinhomes smart city, căn hộ hà nội, mua bán căn hộ, cho thuê căn hộ')">
    <meta name="robots" content="@yield('meta_robots', 'index, follow')">
    <link rel="canonical" href="@yield('canonical', url()->current())">

    {{-- Open Graph --}}
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('og_title', 'Thành Công Land')">
    <meta property="og:description" content="@yield('og_description', 'Bất động sản Vinhomes Smart City')">
    <meta property="og:image" content="@yield('og_image', asset('images/og-default.jpg'))">
    <meta property="og:locale" content="vi_VN">
    <meta property="og:site_name" content="Thành Công Land">

    {{-- Favicon --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/apple-touch-icon.png') }}">

    {{-- ══════════════════════════════════════
         THƯ VIỆN CSS (load trước mọi thứ)
    ══════════════════════════════════════ --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <style>
        /* ─── CSS Variables — dùng chung toàn site ─── */
        :root {
            --primary: #FF8C42;
            --primary-dark: #FF6B1A;
            --primary-light: #fff5ef;
            --navy: #1a3c5e;
            --navy-2: #2d6a9f;
            --text: #333333;
            --text-sub: #6b7280;
            --text-muted: #9ca3af;
            --bg-light: #f8f4f1;
            --bg-tint: #fff5ef;
            --border: #e5e7eb;
            --border-light: #f3f4f6;
            --radius: 12px;
            --radius-lg: 20px;
            --shadow: 0 4px 24px rgba(0, 0, 0, .08);
            --shadow-lg: 0 8px 32px rgba(0, 0, 0, .12);
            --transition: .2s ease;
        }

        /* ─── Reset nhẹ (không override Bootstrap) ─── */
        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Be Vietnam Pro', 'Segoe UI', system-ui, sans-serif;
            font-size: 15px;
            color: var(--text);
            background: #fff;
            line-height: 1.65;
            overflow-x: hidden;
        }

        a {
            text-decoration: none;
            color: inherit;
            transition: color var(--transition);
        }

        img {
            max-width: 100%;
            height: auto;
        }

        ul,
        ol {
            margin: 0;
            padding: 0;
            list-style: none;
        }

        /* ─── Scrollbar ─── */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-dark);
        }

        /* ─── Main content ─── */
        #main-content {}

        /* ─── Breadcrumb ─── */
        .breadcrumb-wrap {
            background: #fdf8f5;
            border-bottom: 1px solid #f0e4da;
            padding: .7rem 0;
        }

        .breadcrumb {
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            gap: .3rem;
            flex-wrap: wrap;
            font-size: .8rem;
        }

        .breadcrumb-item {
            color: var(--text-sub);
        }

        .breadcrumb-item a {
            color: var(--navy-2);
        }

        .breadcrumb-item a:hover {
            color: var(--primary);
        }

        .breadcrumb-item.active {
            color: var(--primary);
            font-weight: 600;
        }

        .breadcrumb-item+.breadcrumb-item::before {
            content: '/';
            color: #d1d5db;
            margin-right: .3rem;
        }


        /* ─── Utility classes dùng chung ─── */
        .text-primary-brand {
            color: var(--primary) !important;
        }

        .bg-primary-brand {
            background: var(--primary) !important;
        }

        .fw-800 {
            font-weight: 800 !important;
        }

        .fw-900 {
            font-weight: 900 !important;
        }

        .btn-primary-brand {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: #fff;
            border: none;
            border-radius: var(--radius);
            padding: .6rem 1.4rem;
            font-weight: 700;
            cursor: pointer;
            font-family: inherit;
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            transition: transform var(--transition), box-shadow var(--transition);
        }

        .btn-primary-brand:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 140, 66, .35);
            color: #fff;
        }

        .badge-moi {
            background: var(--primary);
            color: #fff;
            font-size: .65rem;
            font-weight: 800;
            padding: .1rem .45rem;
            border-radius: 4px;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .badge-hot {
            background: #e74c3c;
            color: #fff;
            font-size: .65rem;
            font-weight: 800;
            padding: .1rem .45rem;
            border-radius: 4px;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .line-clamp-1 {
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* ─── Responsive helpers ─── */
        @media (max-width: 768px) {
            .auth-modal {
                padding: 1.5rem 1.2rem;
            }

            .auth-social-btns {
                flex-direction: column;
            }
        }
    </style>

    {{-- CSS từ từng trang con (@push('styles') hoặc @section('styles')) --}}
    @stack('styles')
</head>

<body>

    {{-- ══════════════════════════════════════
     HEADER — CSS nằm trong file đó
══════════════════════════════════════ --}}
    {{-- Page Loader --}}
    @include('frontend.partials.page-loader')


    {{-- Flash Messages --}}
    @include('frontend.partials.flash-messages')


    @include('frontend.partials.header')

    {{-- ══════════════════════════════════════
     BREADCRUMB (chỉ hiện khi trang có @section('breadcrumb'))
══════════════════════════════════════ --}}
    @hasSection('breadcrumb')
        <div class="breadcrumb-wrap">
            <div class="container-fluid px-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('frontend.home') }}">
                            <i class="fas fa-home"></i>
                        </a>
                    </li>
                    @yield('breadcrumb')
                </ol>
            </div>
        </div>
    @endif

    {{-- ══════════════════════════════════════
     NỘI DUNG CHÍNH
══════════════════════════════════════ --}}
    <main id="main-content">
        @yield('content')
    </main>

    {{-- ══════════════════════════════════════
     FOOTER — CSS nằm trong file đó
══════════════════════════════════════ --}}
    @include('frontend.partials.footer')

    {{-- ══════════════════════════════════════
     CHAT WIDGET — CSS nằm trong file đó
{{-- Trong master.blade.php, thay @include cũ --}}

    {{-- Container bọc cả 2 nút float --}}
    {{-- Thay dòng @include chat cũ thành 2 dòng --}}
    @include('frontend.partials.contact-panel')
    @include('frontend.partials.chat-widget')



    {{-- ══════════════════════════════════════
     AUTH MODAL — Login / Register
══════════════════════════════════════ --}}
    {{-- Auth Modal --}}
    @include('frontend.partials.auth-modal')


    {{-- So Sánh Bar + Modal --}}
    @include('frontend.partials.so-sanh-bar')


    {{-- Modal hồ sơ khách hàng --}}
    @include('frontend.partials.modal-khach-hang')
    {{-- ══════════════════════════════════════
     THƯ VIỆN JS
══════════════════════════════════════ --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- ══════════════════════════════════════
     GLOBAL JAVASCRIPT
══════════════════════════════════════ --}}
    <script>
        /* ════════════════════════════════════
                                                                                                                                                                               CONFIG GLOBAL — route + meta
                                                                                                                                                                            ════════════════════════════════════ */
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
                yeuThichToggle: '{{ route('frontend.yeu-thich.toggle', [], false) }}',
                chatKhoiTao: '{{ route('frontend.chat.khoi-tao', [], false) }}',
                chatGui: '{{ route('frontend.chat.gui', [], false) }}',
                soSanhIndex: '{{ route('frontend.so-sanh.index') }}',
                soSanhThem: '{{ url('/so-sanh/them') }}',
                soSanhXoa: '{{ url('/so-sanh/xoa') }}',
                soSanhModal: '{{ route('frontend.so-sanh.modal', [], false) }}',
            }
        };
        /* ════════════════════════════════════
           YÊU THÍCH
        ════════════════════════════════════ */
        function toggleYeuThich(btn, batDongSanId) {
            if (!APP.isLoggedIn) {
                openAuthModal('login');
                showFlash('Vui lòng đăng nhập để lưu yêu thích.', 'info');
                return;
            }
            var icon = btn.querySelector('i');
            // Optimistic UI
            var wasLiked = btn.classList.contains('liked');
            btn.classList.toggle('liked', !wasLiked);
            if (icon) icon.className = wasLiked ? 'far fa-heart' : 'fas fa-heart';

            fetch(APP.routes.yeuThichToggle, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': APP.csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        bat_dong_san_id: batDongSanId
                    }),
                })
                .then(function(r) {
                    return r.json();
                })
                .then(function(data) {
                    if (data.success) {
                        btn.classList.toggle('liked', data.is_liked);
                        if (icon) icon.className = data.is_liked ? 'fas fa-heart' : 'far fa-heart';
                        btn.title = data.is_liked ? 'Bỏ yêu thích' : 'Yêu thích';
                        showFlash(data.message, data.is_liked ? 'success' : 'info');
                    } else {
                        // Rollback nếu lỗi
                        btn.classList.toggle('liked', wasLiked);
                        if (icon) icon.className = wasLiked ? 'fas fa-heart' : 'far fa-heart';
                    }
                })
                .catch(function() {
                    btn.classList.toggle('liked', wasLiked);
                    if (icon) icon.className = wasLiked ? 'fas fa-heart' : 'far fa-heart';
                    showFlash('Có lỗi xảy ra, vui lòng thử lại.', 'error');
                });
        }


        /* ════════════════════════════════════
           SCROLL — Back to top (dùng bởi footer)
        ════════════════════════════════════ */
        window.addEventListener('scroll', function() {
            var btn = document.getElementById('backToTop');
            if (btn) btn.classList.toggle('show', window.scrollY > 400);
        });

        document.addEventListener('DOMContentLoaded', function() {
            const mainContent = document.getElementById('main-content');
            if (mainContent && mainContent.firstElementChild) {
                const firstEl = mainContent.firstElementChild;
                const style = window.getComputedStyle(firstEl);

                // Nếu trang có dính margin-top âm (cũ), nó sẽ tự động bị ép về 0
                if (parseInt(style.marginTop) < 0 || (firstEl.style.marginTop && firstEl.style.marginTop.includes(
                        '-'))) {
                    firstEl.style.setProperty('margin-top', '0px', 'important');
                }
            }
        });
    </script>
    @stack('scripts')

</body>

</html>
