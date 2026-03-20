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

    {{-- ══════════════════════════════════════
         CSS BIẾN TOÀN CỤC + RESET NHẸ
         ⚠️ KHÔNG định nghĩa bất kỳ style nào
            trùng với header / footer / chat_widget
    ══════════════════════════════════════ --}}
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

        /* ─── Page Loader ─── */
        #page-loader {
            position: fixed;
            inset: 0;
            z-index: 9999;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: opacity .4s, visibility .4s;
        }

        #page-loader.hidden {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }

        .loader-inner {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
        }

        .loader-logo {
            height: 48px;
            animation: loaderPulse 1.5s ease-in-out infinite;
        }

        @keyframes loaderPulse {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: .6;
                transform: scale(.95);
            }
        }

        .loader-bar {
            width: 160px;
            height: 3px;
            background: #f0e4da;
            border-radius: 2px;
            overflow: hidden;
        }

        .loader-bar::after {
            content: '';
            display: block;
            height: 100%;
            width: 40%;
            background: linear-gradient(90deg, var(--primary), var(--primary-dark));
            border-radius: 2px;
            animation: loaderSlide 1.2s ease-in-out infinite;
        }

        @keyframes loaderSlide {
            0% {
                transform: translateX(-100%);
            }

            100% {
                transform: translateX(350%);
            }
        }

        /* ─── Flash Messages ─── */
        .flash-container {
            position: fixed;
            top: 1.2rem;
            right: 1.2rem;
            z-index: 9998;
            display: flex;
            flex-direction: column;
            gap: .6rem;
            max-width: 360px;
            width: calc(100% - 2.4rem);
            pointer-events: none;
        }

        .flash-item {
            display: flex;
            align-items: flex-start;
            gap: .7rem;
            padding: .9rem 1.1rem;
            border-radius: var(--radius);
            font-size: .85rem;
            font-weight: 500;
            box-shadow: var(--shadow-lg);
            animation: flashIn .3s ease;
            pointer-events: all;
            cursor: default;
        }

        @keyframes flashIn {
            from {
                opacity: 0;
                transform: translateX(20px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .flash-success {
            background: #f0fff4;
            border-left: 4px solid #27ae60;
            color: #276749;
        }

        .flash-error {
            background: #fff5f5;
            border-left: 4px solid #e74c3c;
            color: #c0392b;
        }

        .flash-info {
            background: #eff8ff;
            border-left: 4px solid #2d6a9f;
            color: #1a4971;
        }

        .flash-warning {
            background: #fffbeb;
            border-left: 4px solid #f59e0b;
            color: #92400e;
        }

        .flash-item i {
            flex-shrink: 0;
            margin-top: .1rem;
        }

        .flash-close {
            margin-left: auto;
            background: none;
            border: none;
            color: inherit;
            opacity: .5;
            cursor: pointer;
            font-size: .85rem;
            padding: 0;
            flex-shrink: 0;
            transition: opacity var(--transition);
        }

        .flash-close:hover {
            opacity: 1;
        }

        /* ─── Auth Modal ─── */
        .auth-modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .55);
            z-index: 2000;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            backdrop-filter: blur(3px);
        }

        .auth-modal-overlay.show {
            display: flex;
        }

        .auth-modal {
            background: #fff;
            border-radius: var(--radius-lg);
            width: 100%;
            max-width: 440px;
            padding: 2rem;
            box-shadow: 0 24px 60px rgba(0, 0, 0, .18);
            animation: modalIn .3s ease;
            max-height: 92vh;
            overflow-y: auto;
            position: relative;
        }

        @keyframes modalIn {
            from {
                opacity: 0;
                transform: scale(.96) translateY(10px);
            }

            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .auth-modal-close {
            position: absolute;
            top: 1rem;
            right: 1rem;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: #f3f4f6;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-sub);
            transition: background var(--transition);
        }

        .auth-modal-close:hover {
            background: #fee2e2;
            color: #e74c3c;
        }

        /* Auth Tabs */
        .auth-tabs {
            display: flex;
            background: #f3f4f6;
            border-radius: 10px;
            padding: .25rem;
            margin-bottom: 1.5rem;
        }

        .auth-tab-btn {
            flex: 1;
            padding: .55rem;
            border-radius: 8px;
            border: none;
            background: transparent;
            font-size: .85rem;
            font-weight: 700;
            color: var(--text-sub);
            cursor: pointer;
            transition: all var(--transition);
            font-family: inherit;
        }

        .auth-tab-btn.active {
            background: #fff;
            color: var(--primary);
            box-shadow: 0 2px 8px rgba(0, 0, 0, .08);
        }

        .auth-form-wrap {
            display: none;
        }

        .auth-form-wrap.active {
            display: block;
        }

        /* Auth Input */
        .auth-input-group {
            margin-bottom: 1rem;
        }

        .auth-input-group label {
            display: block;
            font-size: .78rem;
            font-weight: 700;
            color: #374151;
            margin-bottom: .4rem;
        }

        .auth-input-group label span {
            color: #e74c3c;
        }

        .auth-input-wrap {
            position: relative;
        }

        .auth-input-wrap>i.field-icon {
            position: absolute;
            left: .9rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: .85rem;
            pointer-events: none;
        }

        .auth-input-wrap input {
            width: 100%;
            padding: .72rem .9rem .72rem 2.5rem;
            border: 1.8px solid var(--border);
            border-radius: 10px;
            font-size: .88rem;
            color: #1f2937;
            background: #f9fafb;
            outline: none;
            transition: border-color var(--transition), box-shadow var(--transition);
            font-family: inherit;
        }

        .auth-input-wrap input:focus {
            border-color: var(--primary);
            background: #fff;
            box-shadow: 0 0 0 3px rgba(255, 140, 66, .12);
        }

        .auth-input-wrap input.is-invalid {
            border-color: #e74c3c;
            background: #fff5f5;
        }

        .auth-invalid-msg {
            font-size: .73rem;
            color: #e74c3c;
            margin-top: .3rem;
            display: flex;
            align-items: center;
            gap: .3rem;
        }

        /* Auth Submit Button */
        .auth-btn-submit {
            width: 100%;
            padding: .82rem;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: .92rem;
            font-weight: 800;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .5rem;
            box-shadow: 0 4px 16px rgba(255, 140, 66, .35);
            transition: transform var(--transition), box-shadow var(--transition);
            font-family: inherit;
            margin-top: 1.2rem;
        }

        .auth-btn-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(255, 140, 66, .45);
        }

        /* Auth Divider */
        .auth-divider {
            text-align: center;
            font-size: .75rem;
            color: var(--text-muted);
            margin: 1rem 0;
            position: relative;
        }

        .auth-divider::before,
        .auth-divider::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 38%;
            height: 1px;
            background: var(--border);
        }

        .auth-divider::before {
            left: 0;
        }

        .auth-divider::after {
            right: 0;
        }

        /* Social Buttons */
        .auth-social-btns {
            display: flex;
            gap: .6rem;
        }

        .auth-social-btn {
            flex: 1;
            padding: .6rem;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            background: #fff;
            cursor: pointer;
            font-size: .82rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .4rem;
            transition: background var(--transition), border-color var(--transition);
            font-family: inherit;
        }

        .auth-social-btn:hover {
            background: #f9fafb;
            border-color: var(--primary);
        }

        /* OTP */
        .otp-field-wrap {
            display: flex;
            gap: .5rem;
        }

        .otp-field-wrap input[name="otp"] {
            letter-spacing: .4em;
            font-size: 1.1rem;
            text-align: center;
        }

        .btn-send-otp {
            padding: .7rem 1rem;
            background: var(--navy);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: .78rem;
            font-weight: 700;
            cursor: pointer;
            white-space: nowrap;
            transition: background var(--transition);
            font-family: inherit;
        }

        .btn-send-otp:hover {
            background: var(--navy-2);
        }

        .btn-send-otp:disabled {
            background: var(--text-muted);
            cursor: not-allowed;
        }

        /* ─── So Sánh Floating Bar ─── */
        #so-sanh-bar {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: var(--navy);
            color: #fff;
            padding: .8rem 1.5rem;
            z-index: 980;
            box-shadow: 0 -4px 20px rgba(0, 0, 0, .2);
        }

        .ss-bar-inner {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: .8rem;
        }

        .ss-bar-left {
            display: flex;
            align-items: center;
            gap: .8rem;
            flex-wrap: wrap;
        }

        .ss-bar-title {
            font-weight: 700;
            font-size: .85rem;
        }

        .ss-bar-title i {
            color: var(--primary);
        }

        .ss-item-chip {
            background: rgba(255, 255, 255, .15);
            padding: .25rem .7rem;
            border-radius: 20px;
            font-size: .75rem;
            display: inline-flex;
            align-items: center;
            gap: .4rem;
        }

        .ss-item-remove {
            background: none;
            border: none;
            color: #fff;
            cursor: pointer;
            font-size: .7rem;
            padding: 0;
            opacity: .7;
            transition: opacity var(--transition);
        }

        .ss-item-remove:hover {
            opacity: 1;
        }

        .ss-bar-actions {
            display: flex;
            gap: .6rem;
        }

        .ss-btn-compare {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: .45rem 1rem;
            font-size: .82rem;
            font-weight: 700;
            cursor: pointer;
            font-family: inherit;
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            text-decoration: none;
            transition: transform var(--transition), box-shadow var(--transition);
        }

        .ss-btn-compare:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(255, 140, 66, .4);
            color: #fff;
        }

        .ss-btn-clear {
            background: rgba(255, 255, 255, .12);
            border: 1px solid rgba(255, 255, 255, .25);
            color: #fff;
            padding: .45rem .9rem;
            border-radius: 8px;
            cursor: pointer;
            font-size: .82rem;
            font-family: inherit;
            transition: background var(--transition);
        }

        .ss-btn-clear:hover {
            background: rgba(255, 255, 255, .2);
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
     PAGE LOADER
══════════════════════════════════════ --}}
    <div id="page-loader">
        <div class="loader-inner">
            <img src="{{ asset('images/logo.png') }}" alt="Thành Công Land" class="loader-logo"
                onerror="this.style.display='none'">
            <div class="loader-bar"></div>
        </div>
    </div>

    {{-- ══════════════════════════════════════
     FLASH MESSAGES (từ session)
══════════════════════════════════════ --}}
    <div class="flash-container" id="flashContainer">
        @if (session('success'))
            <div class="flash-item flash-success">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
                <button class="flash-close" onclick="this.closest('.flash-item').remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif
        @if (session('error'))
            <div class="flash-item flash-error">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ session('error') }}</span>
                <button class="flash-close" onclick="this.closest('.flash-item').remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif
        @if (session('info'))
            <div class="flash-item flash-info">
                <i class="fas fa-info-circle"></i>
                <span>{{ session('info') }}</span>
                <button class="flash-close" onclick="this.closest('.flash-item').remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif
        @if (session('warning'))
            <div class="flash-item flash-warning">
                <i class="fas fa-exclamation-triangle"></i>
                <span>{{ session('warning') }}</span>
                <button class="flash-close" onclick="this.closest('.flash-item').remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif
    </div>

    {{-- ══════════════════════════════════════
     HEADER — CSS nằm trong file đó
══════════════════════════════════════ --}}
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
══════════════════════════════════════ --}}
    @include('frontend.partials.chat_widget')

    {{-- ══════════════════════════════════════
     AUTH MODAL — Login / Register
══════════════════════════════════════ --}}
    <div class="auth-modal-overlay" id="authModalOverlay">
        <div class="auth-modal">

            <button class="auth-modal-close" id="btnCloseAuthModal" title="Đóng">
                <i class="fas fa-times"></i>
            </button>

            {{-- Tabs --}}
            <div class="auth-tabs">
                <button class="auth-tab-btn active" data-tab="login">
                    <i class="fas fa-sign-in-alt"></i> Đăng nhập
                </button>
                <button class="auth-tab-btn" data-tab="register">
                    <i class="fas fa-user-plus"></i> Đăng ký
                </button>
            </div>

            {{-- ── Form đăng nhập ── --}}
            <div class="auth-form-wrap active" id="authTabLogin">
                <form id="formLogin" action="{{ route('khach-hang.login.post') }}" method="POST">
                    @csrf

                    <div class="auth-input-group">
                        <label>Email hoặc SĐT <span>*</span></label>
                        <div class="auth-input-wrap">
                            <i class="fas fa-user field-icon"></i>
                            <input type="text" name="email" placeholder="Email hoặc số điện thoại..."
                                value="{{ old('email') }}" autocomplete="username" required>
                        </div>
                    </div>

                    <div class="auth-input-group">
                        <label>Mật khẩu <span>*</span></label>
                        <div class="auth-input-wrap">
                            <i class="fas fa-lock field-icon"></i>
                            <input type="password" name="password" placeholder="Mật khẩu..."
                                autocomplete="current-password" required>
                        </div>
                    </div>

                    <div
                        style="display:flex;justify-content:space-between;align-items:center;
                            font-size:.8rem;margin-bottom:.5rem;">
                        <label
                            style="display:flex;align-items:center;gap:.4rem;
                                  cursor:pointer;color:var(--text-sub);">
                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            Ghi nhớ đăng nhập
                        </label>
                        <a href="{{ route('khach-hang.forgot') }}" style="color:var(--primary);font-weight:600;">
                            Quên mật khẩu?
                        </a>
                    </div>

                    <button type="submit" class="auth-btn-submit">
                        <i class="fas fa-sign-in-alt"></i> Đăng nhập
                    </button>

                </form>

                <div class="auth-divider">hoặc đăng nhập bằng</div>
                <div class="auth-social-btns">
                    <button class="auth-social-btn" type="button">
                        <img src="{{ asset('images/google-icon.svg') }}" width="18" alt="Google"
                            onerror="this.outerHTML='<i class=\'fab fa-google\' style=\'color:#ea4335\'></i>'">
                        Google
                    </button>
                    <button class="auth-social-btn" type="button">
                        <i class="fab fa-facebook-f" style="color:#1877f2"></i>
                        Facebook
                    </button>
                </div>
            </div>

            {{-- ── Form đăng ký ── --}}
            <div class="auth-form-wrap" id="authTabRegister">
                <form id="formRegister" action="{{ route('khach-hang.register.post') }}" method="POST">
                    @csrf

                    <div class="auth-input-group">
                        <label>Họ và tên <span>*</span></label>
                        <div class="auth-input-wrap">
                            <i class="fas fa-user field-icon"></i>
                            <input type="text" name="ho_ten" placeholder="Nguyễn Văn A"
                                value="{{ old('ho_ten') }}" required>
                        </div>
                    </div>

                    <div class="auth-input-group">
                        <label>Số điện thoại <span>*</span></label>
                        <div class="auth-input-wrap">
                            <i class="fas fa-phone field-icon"></i>
                            <input type="tel" name="so_dien_thoai" id="inputSdtRegister"
                                placeholder="0912 345 678" value="{{ old('so_dien_thoai') }}" required>
                        </div>
                    </div>

                    {{-- OTP --}}
                    <div class="auth-input-group">
                        <label>Xác thực OTP</label>
                        <div class="otp-field-wrap">
                            <div class="auth-input-wrap" style="flex:1">
                                <i class="fas fa-shield-alt field-icon"></i>
                                <input type="text" name="otp" id="inputOtp" placeholder="Nhập mã 6 số..."
                                    maxlength="6" inputmode="numeric">
                            </div>
                            <button type="button" class="btn-send-otp" id="btnSendOtp">
                                Gửi OTP
                            </button>
                        </div>
                        <div id="otpCountdown"
                            style="font-size:.73rem;color:var(--primary);
                                margin-top:.3rem;display:none;">
                        </div>
                    </div>

                    <div class="auth-input-group">
                        <label>Email <small style="color:var(--text-muted)">(tuỳ chọn)</small></label>
                        <div class="auth-input-wrap">
                            <i class="fas fa-envelope field-icon"></i>
                            <input type="email" name="email" placeholder="example@gmail.com"
                                value="{{ old('email') }}">
                        </div>
                    </div>

                    <div class="auth-input-group">
                        <label>Mật khẩu <span>*</span></label>
                        <div class="auth-input-wrap">
                            <i class="fas fa-lock field-icon"></i>
                            <input type="password" name="password" placeholder="Ít nhất 6 ký tự" required>
                        </div>
                    </div>

                    <div class="auth-input-group">
                        <label>Xác nhận mật khẩu <span>*</span></label>
                        <div class="auth-input-wrap">
                            <i class="fas fa-lock field-icon"></i>
                            <input type="password" name="password_confirmation" placeholder="Nhập lại mật khẩu"
                                required>
                        </div>
                    </div>

                    <button type="submit" class="auth-btn-submit">
                        <i class="fas fa-user-plus"></i> Tạo tài khoản
                    </button>

                </form>
            </div>

        </div>
    </div>

    {{-- ══════════════════════════════════════
     SO SÁNH FLOATING BAR
══════════════════════════════════════ --}}
    <div id="so-sanh-bar">
        <div class="ss-bar-inner">
            <div class="ss-bar-left">
                <span class="ss-bar-title">
                    <i class="fas fa-balance-scale"></i>
                    So sánh: <strong id="ssSanhCount">0</strong>/3 BĐS
                </span>
                <div id="ssSanhItems"></div>
            </div>
            <div class="ss-bar-actions">
                {{-- ĐỔI TỪ THẺ <a> THÀNH THẺ <button> GỌI JS --}}
                <button type="button" class="ss-btn-compare" onclick="openSoSanhModal()">
                    <i class="fas fa-chart-bar"></i> So sánh ngay
                </button>
                <button type="button" class="ss-btn-clear" onclick="clearSoSanh()">
                    <i class="fas fa-trash"></i> Xoá tất cả
                </button>
            </div>
        </div>
    </div>

    {{-- MODAL HIỂN THỊ BẢNG SO SÁNH --}}
    <div class="modal fade" id="soSanhModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-bottom-0 bg-light p-3">
                    <h5 class="modal-title fw-bold serif-font text-dark mb-0">
                        <i class="fas fa-balance-scale me-2" style="color: #FF8C42;"></i>Bảng So Sánh Chi Tiết
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0" id="soSanhModalBody">
                    {{-- Dữ liệu AJAX sẽ được đổ vào đây --}}
                </div>
            </div>
        </div>
    </div>

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
                chatKhoiTao: '{{ route('frontend.chat.khoi-tao') }}',
                chatGui: '{{ route('frontend.chat.gui') }}',
                soSanhIndex: '{{ route('frontend.so-sanh.index') }}',
                soSanhThem: '{{ url('/so-sanh/them') }}',
                soSanhXoa: '{{ url('/so-sanh/xoa') }}',
                soSanhModal: '{{ route('frontend.so-sanh.modal', [], false) }}',
            }
        };

        /* ════════════════════════════════════
           PAGE LOADER
        ════════════════════════════════════ */
        window.addEventListener('load', function() {
            setTimeout(function() {
                var loader = document.getElementById('page-loader');
                if (loader) loader.classList.add('hidden');
            }, 250);
        });

        /* ════════════════════════════════════
           FLASH AUTO-DISMISS
        ════════════════════════════════════ */
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                document.querySelectorAll('.flash-item').forEach(function(el) {
                    el.style.transition = 'opacity .5s, transform .5s';
                    el.style.opacity = '0';
                    el.style.transform = 'translateX(20px)';
                    setTimeout(function() {
                        el.remove();
                    }, 500);
                });
            }, 4500);
        });

        /* ════════════════════════════════════
           AUTH MODAL
        ════════════════════════════════════ */
        document.addEventListener('DOMContentLoaded', function() {
            var overlay = document.getElementById('authModalOverlay');
            var closeBtn = document.getElementById('btnCloseAuthModal');

            window.openAuthModal = function(tab) {
                if (!overlay) return;
                overlay.classList.add('show');
                document.body.style.overflow = 'hidden';
                if (tab) switchAuthTab(tab);
            };

            window.closeAuthModal = function() {
                if (!overlay) return;
                overlay.classList.remove('show');
                document.body.style.overflow = '';
            };

            if (closeBtn) closeBtn.addEventListener('click', closeAuthModal);
            if (overlay) overlay.addEventListener('click', function(e) {
                if (e.target === overlay) closeAuthModal();
            });
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') closeAuthModal();
            });

            function switchAuthTab(tab) {
                document.querySelectorAll('.auth-tab-btn').forEach(function(btn) {
                    btn.classList.toggle('active', btn.dataset.tab === tab);
                });
                document.querySelectorAll('.auth-form-wrap').forEach(function(wrap) {
                    var isActive = wrap.id === 'authTab' + tab.charAt(0).toUpperCase() + tab.slice(1);
                    wrap.classList.toggle('active', isActive);
                });
            }

            document.querySelectorAll('.auth-tab-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    switchAuthTab(btn.dataset.tab);
                });
            });
        });

        /* ════════════════════════════════════
           OTP — Gửi & Đếm ngược
        ════════════════════════════════════ */
        document.addEventListener('DOMContentLoaded', function() {
            var btnOtp = document.getElementById('btnSendOtp');
            var countdown = document.getElementById('otpCountdown');

            if (!btnOtp) return;

            btnOtp.addEventListener('click', function() {
                var sdt = document.getElementById('inputSdtRegister')?.value?.trim();
                if (!sdt) {
                    showFlash('Vui lòng nhập số điện thoại trước.', 'warning');
                    return;
                }

                fetch(APP.routes.sendOtp, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': APP.csrfToken,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            so_dien_thoai: sdt
                        }),
                    })
                    .then(function(r) {
                        return r.json();
                    })
                    .then(function(data) {
                        if (data.success) {
                            showFlash('OTP đã được gửi đến SĐT của bạn.', 'success');
                            startOtpCountdown(60);
                            if (data.otp_dev) console.log('[DEV OTP]', data.otp_dev);
                        } else {
                            showFlash(data.message || 'Không thể gửi OTP.', 'error');
                        }
                    })
                    .catch(function() {
                        showFlash('Lỗi kết nối, thử lại sau.', 'error');
                    });
            });

            function startOtpCountdown(seconds) {
                btnOtp.disabled = true;
                if (countdown) countdown.style.display = 'block';
                var remaining = seconds;
                var timer = setInterval(function() {
                    if (countdown) countdown.textContent = 'Gửi lại sau ' + remaining + 's';
                    remaining--;
                    if (remaining < 0) {
                        clearInterval(timer);
                        btnOtp.disabled = false;
                        btnOtp.textContent = 'Gửi lại OTP';
                        if (countdown) countdown.style.display = 'none';
                    }
                }, 1000);
            }
        });

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
           SO SÁNH BĐS
        ════════════════════════════════════ */
        var soSanhList = JSON.parse(localStorage.getItem('tcl_sosánh') || '[]');

        function addSoSanh(id, ten) {
            id = parseInt(id);
            if (soSanhList.find(function(x) {
                    return x.id === id;
                })) {
                showFlash('BĐS này đã có trong danh sách so sánh.', 'info');
                return;
            }
            if (soSanhList.length >= 3) {
                showFlash('Chỉ so sánh tối đa 3 BĐS cùng lúc.', 'warning');
                return;
            }
            soSanhList.push({
                id: id,
                ten: ten
            });
            saveSoSanh();
            showFlash('"' + ten.substring(0, 25) + '..." đã thêm vào so sánh.', 'success');
        }

        function removeSoSanh(id) {
            id = parseInt(id);
            soSanhList = soSanhList.filter(function(x) {
                return x.id !== id;
            });
            saveSoSanh();
        }

        function clearSoSanh() {
            soSanhList = [];
            saveSoSanh();
        }

        function saveSoSanh() {
            localStorage.setItem('tcl_sosánh', JSON.stringify(soSanhList));
            renderSoSanhBar();
        }

        function renderSoSanhBar() {
            var bar = document.getElementById('so-sanh-bar');
            var count = document.getElementById('ssSanhCount');
            var items = document.getElementById('ssSanhItems');

            if (!bar) return;

            if (soSanhList.length === 0) {
                bar.style.display = 'none';
                // Tự động đóng Modal nếu xóa hết danh sách
                var modalEl = document.getElementById('soSanhModal');
                if (modalEl && modalEl.classList.contains('show')) {
                    bootstrap.Modal.getInstance(modalEl).hide();
                }
                return;
            }

            bar.style.display = 'block';
            if (count) count.textContent = soSanhList.length;

            if (items) {
                items.innerHTML = soSanhList.map(function(item) {
                    var shortName = item.ten.length > 22 ? item.ten.substring(0, 22) + '…' : item.ten;
                    return '<span class="ss-item-chip">' + shortName +
                        '<button class="ss-item-remove" onclick="removeSoSanhTuModal(' + item.id +
                        ')" title="Xoá"><i class="fas fa-times"></i></button></span>';
                }).join('');
            }
        }

        // Hàm xóa và Refresh lại Modal nếu đang mở
        function removeSoSanhTuModal(id) {
            removeSoSanh(id);
            var modalEl = document.getElementById('soSanhModal');
            if (modalEl && modalEl.classList.contains('show') && soSanhList.length > 0) {
                openSoSanhModal(); // Load lại AJAX
            }
        }

        // Hàm mở Popup (Modal) và tải Bảng So Sánh
        function openSoSanhModal() {
            if (soSanhList.length === 0) return;

            var modalEl = document.getElementById('soSanhModal');
            var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            var modalBody = document.getElementById('soSanhModalBody');

            // 1. Mở modal và hiện hiệu ứng Loading
            modal.show();
            modalBody.innerHTML =
                '<div class="text-center py-5"><div class="spinner-border" style="color: #FF8C42;" role="status"></div><p class="mt-2 text-muted fw-bold">Đang tải dữ liệu so sánh...</p></div>';

            // 2. Lấy ID và gọi AJAX
            var ids = soSanhList.map(function(item) {
                return item.id;
            }).join(',');

            fetch(APP.routes.soSanhModal + '?ids=' + ids)
                .then(response => response.text())
                .then(html => {
                    modalBody.innerHTML = html; // Đổ bảng vào
                })
                .catch(err => {
                    modalBody.innerHTML =
                        '<div class="text-center py-5 text-danger"><i class="fas fa-exclamation-triangle fs-1 mb-3"></i><p>Có lỗi xảy ra. Vui lòng thử lại sau.</p></div>';
                });
        }

        document.addEventListener('DOMContentLoaded', renderSoSanhBar);

        /* ════════════════════════════════════
           FLASH HELPER (dùng từ JS)
        ════════════════════════════════════ */
        function showFlash(message, type) {
            type = type || 'success';
            var icons = {
                success: 'fa-check-circle',
                error: 'fa-exclamation-circle',
                info: 'fa-info-circle',
                warning: 'fa-exclamation-triangle',
            };
            var container = document.getElementById('flashContainer');
            if (!container) return;

            var el = document.createElement('div');
            el.className = 'flash-item flash-' + type;
            el.innerHTML = '<i class="fas ' + (icons[type] || icons.info) + '"></i>' +
                '<span>' + message + '</span>' +
                '<button class="flash-close" onclick="this.closest(\'.flash-item\').remove()">' +
                '<i class="fas fa-times"></i></button>';
            container.appendChild(el);

            setTimeout(function() {
                el.style.transition = 'opacity .4s, transform .4s';
                el.style.opacity = '0';
                el.style.transform = 'translateX(20px)';
                setTimeout(function() {
                    if (el.parentNode) el.remove();
                }, 400);
            }, 4000);
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
