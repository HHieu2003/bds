<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập — Thành Công Land</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            min-height: 100vh;
            display: flex;
            background: #f4f6f9;
        }

        /* ══════════ LEFT PANEL ══════════ */
        .login-left {
            flex: 1;
            background:
                linear-gradient(135deg, rgba(26, 60, 94, .93) 0%, rgba(255, 107, 26, .8) 100%),
                url('{{ asset('images/login-bg.jpg') }}') center/cover no-repeat;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 3rem;
            color: #fff;
            position: relative;
            overflow: hidden;
            min-height: 100vh;
        }

        .login-left::before {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .04);
            bottom: -150px;
            left: -100px;
            pointer-events: none;
        }

        .login-left::after {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .04);
            top: -80px;
            right: -80px;
            pointer-events: none;
        }

        .left-logo {
            display: flex;
            align-items: center;
            gap: .85rem;
            text-decoration: none;
            position: relative;
            z-index: 1;
        }

        .left-logo img {
            height: 46px;
        }

        .left-logo-text .name {
            font-size: 1.2rem;
            font-weight: 900;
            color: #fff;
            letter-spacing: .5px;
            line-height: 1.1;
        }

        .left-logo-text .sub {
            font-size: .68rem;
            font-weight: 700;
            color: rgba(255, 255, 255, .65);
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .left-body {
            position: relative;
            z-index: 1;
            max-width: 420px;
        }

        .left-body h1 {
            font-size: 2.4rem;
            font-weight: 900;
            line-height: 1.2;
            margin-bottom: 1rem;
        }

        .left-body h1 span {
            color: #FF8C42;
        }

        .left-body p {
            font-size: .95rem;
            line-height: 1.75;
            color: rgba(255, 255, 255, .72);
            margin-bottom: 2rem;
        }

        .left-stats {
            display: flex;
            gap: 2rem;
            flex-wrap: wrap;
        }

        .stat-item {
            text-align: left;
        }

        .stat-num {
            font-size: 1.8rem;
            font-weight: 900;
            color: #FF8C42;
            line-height: 1;
        }

        .stat-label {
            font-size: .73rem;
            color: rgba(255, 255, 255, .6);
            margin-top: .2rem;
        }

        .left-footer {
            position: relative;
            z-index: 1;
            font-size: .74rem;
            color: rgba(255, 255, 255, .38);
        }

        /* ══════════ RIGHT PANEL ══════════ */
        .login-right {
            width: 480px;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2.5rem 2rem;
            background: #fff;
            box-shadow: -4px 0 40px rgba(0, 0, 0, .08);
        }

        .login-box {
            width: 100%;
            max-width: 380px;
        }

        /* Header */
        .login-box-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-icon-wrap {
            width: 64px;
            height: 64px;
            border-radius: 18px;
            background: linear-gradient(135deg, #FF8C42, #FF6B1A);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.1rem;
            box-shadow: 0 8px 24px rgba(255, 140, 66, .35);
        }

        .login-icon-wrap i {
            font-size: 1.6rem;
            color: #fff;
        }

        .login-box-header h2 {
            font-size: 1.5rem;
            font-weight: 800;
            color: #1a3c5e;
            margin-bottom: .3rem;
        }

        .login-box-header p {
            font-size: .84rem;
            color: #8a9ab0;
        }

        /* Role badges */
        .role-badges {
            display: flex;
            gap: .45rem;
            justify-content: center;
            margin-bottom: 1.8rem;
            flex-wrap: wrap;
        }

        .role-badge {
            padding: .28rem .75rem;
            border-radius: 20px;
            font-size: .71rem;
            font-weight: 700;
            border: 1.5px solid transparent;
            cursor: default;
            display: flex;
            align-items: center;
            gap: .3rem;
        }

        .role-badge.admin {
            background: rgba(255, 140, 66, .1);
            color: #FF6B1A;
            border-color: rgba(255, 140, 66, .3);
        }

        .role-badge.sale {
            background: rgba(26, 60, 94, .08);
            color: #1a3c5e;
            border-color: rgba(26, 60, 94, .2);
        }

        .role-badge.nguon {
            background: rgba(45, 106, 159, .08);
            color: #2d6a9f;
            border-color: rgba(45, 106, 159, .2);
        }

        /* Alerts */
        .alert {
            border-radius: 10px;
            padding: .85rem 1rem;
            margin-bottom: 1.4rem;
            display: flex;
            align-items: flex-start;
            gap: .6rem;
            font-size: .84rem;
        }

        .alert-error {
            background: #fff5f5;
            border: 1px solid #fed7d7;
            border-left: 4px solid #e74c3c;
            color: #c0392b;
        }

        .alert-success {
            background: #f0fff4;
            border: 1px solid #c6f6d5;
            border-left: 4px solid #27ae60;
            color: #276749;
        }

        .alert i {
            flex-shrink: 0;
            margin-top: .1rem;
        }

        /* Form */
        .form-group {
            margin-bottom: 1.2rem;
        }

        .form-label {
            display: block;
            font-size: .79rem;
            font-weight: 700;
            color: #3a4a5c;
            margin-bottom: .45rem;
            letter-spacing: .2px;
        }

        .form-label span {
            color: #e74c3c;
        }

        .input-wrap {
            position: relative;
        }

        .input-wrap .input-icon {
            position: absolute;
            left: .95rem;
            top: 50%;
            transform: translateY(-50%);
            color: #b0bec5;
            font-size: .88rem;
            pointer-events: none;
            transition: color .2s;
        }

        .input-wrap input {
            width: 100%;
            padding: .78rem 2.8rem;
            border: 1.8px solid #e2e8f0;
            border-radius: 12px;
            font-size: .9rem;
            color: #2d3748;
            background: #f8fafc;
            outline: none;
            transition: border-color .2s, background .2s, box-shadow .2s;
        }

        .input-wrap input:focus {
            border-color: #FF8C42;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(255, 140, 66, .12);
        }

        .input-wrap input:focus~.input-icon {
            color: #FF8C42;
        }

        .input-wrap input.is-invalid {
            border-color: #e74c3c;
            background: #fff5f5;
        }

        .input-wrap input.is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(231, 76, 60, .1);
        }

        .btn-eye {
            position: absolute;
            right: .85rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #b0bec5;
            cursor: pointer;
            font-size: .88rem;
            padding: .2rem;
            transition: color .2s;
        }

        .btn-eye:hover {
            color: #FF8C42;
        }

        .invalid-feedback {
            font-size: .74rem;
            color: #e74c3c;
            margin-top: .35rem;
            display: flex;
            align-items: center;
            gap: .3rem;
        }

        /* Remember & Forgot */
        .form-row-check {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.6rem;
            flex-wrap: wrap;
            gap: .5rem;
        }

        .check-label {
            display: flex;
            align-items: center;
            gap: .45rem;
            cursor: pointer;
            font-size: .82rem;
            color: #5a6a7a;
            user-select: none;
        }

        .check-label input[type="checkbox"] {
            display: none;
        }

        .check-box {
            width: 18px;
            height: 18px;
            border: 2px solid #cbd5e0;
            border-radius: 5px;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all .18s;
            flex-shrink: 0;
        }

        .check-label input:checked~.check-box {
            background: #FF8C42;
            border-color: #FF8C42;
        }

        .check-label input:checked~.check-box::after {
            content: '';
            display: block;
            width: 4px;
            height: 8px;
            border: 2px solid #fff;
            border-top: none;
            border-left: none;
            transform: rotate(45deg) translate(-1px, -1px);
        }

        .forgot-link {
            font-size: .82rem;
            color: #FF8C42;
            text-decoration: none;
            font-weight: 600;
            transition: color .2s;
        }

        .forgot-link:hover {
            color: #FF6B1A;
        }

        /* Submit button */
        .btn-submit {
            width: 100%;
            padding: .9rem;
            background: linear-gradient(135deg, #FF8C42, #FF6B1A);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: .95rem;
            font-weight: 800;
            cursor: pointer;
            letter-spacing: .3px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .55rem;
            box-shadow: 0 4px 18px rgba(255, 140, 66, .4);
            transition: transform .2s, box-shadow .2s;
            position: relative;
            overflow: hidden;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 28px rgba(255, 140, 66, .45);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .btn-submit.loading {
            pointer-events: none;
            opacity: .82;
        }

        .spinner {
            display: none;
            width: 18px;
            height: 18px;
            border: 2.5px solid rgba(255, 255, 255, .4);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin .7s linear infinite;
        }

        .btn-submit.loading .spinner {
            display: block;
        }

        .btn-submit.loading .btn-text {
            display: none;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Divider */
        .login-divider {
            text-align: center;
            margin: 1.5rem 0;
            position: relative;
            font-size: .77rem;
            color: #b0bec5;
        }

        .login-divider::before,
        .login-divider::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 35%;
            height: 1px;
            background: #e2e8f0;
        }

        .login-divider::before {
            left: 0;
        }

        .login-divider::after {
            right: 0;
        }

        /* Support */
        .login-support {
            text-align: center;
            margin-top: 1.8rem;
            font-size: .8rem;
            color: #8a9ab0;
            line-height: 1.7;
        }

        .login-support a {
            color: #1a3c5e;
            font-weight: 700;
            text-decoration: none;
            transition: color .2s;
        }

        .login-support a:hover {
            color: #FF8C42;
        }

        /* Responsive */
        @media (max-width: 900px) {
            .login-left {
                display: none;
            }

            .login-right {
                width: 100%;
                box-shadow: none;
            }
        }

        @media (max-width: 480px) {
            .login-right {
                padding: 2rem 1.2rem;
            }

            .login-box-header h2 {
                font-size: 1.3rem;
            }
        }
    </style>
</head>

<body>

    {{-- ══════════ LEFT PANEL ══════════ --}}
    <div class="login-left">

        <a href="{{ route('frontend.home') }}" class="left-logo">
            <img src="{{ asset('images/logo.png') }}" alt="Thành Công Land" onerror="this.style.display='none'">
            <div class="left-logo-text">
                <div class="name">THÀNH CÔNG LAND</div>
                <div class="sub">Kết nối giá trị · kiến tạo thành công</div>
            </div>
        </a>

        <div class="left-body">
            <h1>
                Hệ thống<br>
                quản lý <span>nội bộ</span>
            </h1>
            <p>
                Nền tảng quản lý bất động sản tập trung — theo dõi khách hàng,
                lịch hẹn, ký gửi và hiệu suất sale trong một giao diện duy nhất.
            </p>
            <div class="left-stats">
                <div class="stat-item">
                    <div class="stat-num">500+</div>
                    <div class="stat-label">BĐS đang quản lý</div>
                </div>
                <div class="stat-item">
                    <div class="stat-num">1,200+</div>
                    <div class="stat-label">Khách hàng</div>
                </div>
                <div class="stat-item">
                    <div class="stat-num">24/7</div>
                    <div class="stat-label">Truy cập mọi lúc</div>
                </div>
            </div>
        </div>

        <div class="left-footer">
            &copy; {{ date('Y') }} Thành Công Land &nbsp;·&nbsp; Dành riêng cho nhân viên nội bộ
        </div>

    </div>

    {{-- ══════════ RIGHT PANEL ══════════ --}}
    <div class="login-right">
        <div class="login-box">

            {{-- Header --}}
            <div class="login-box-header">
                <div class="login-icon-wrap">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h2>Đăng nhập</h2>
                <p>Hệ thống quản lý Thành Công Land</p>
            </div>

            {{-- Role badges --}}
            <div class="role-badges">
                <span class="role-badge admin">
                    <i class="fas fa-crown"></i> Admin
                </span>
                <span class="role-badge sale">
                    <i class="fas fa-handshake"></i> Sale
                </span>
                <span class="role-badge nguon">
                    <i class="fas fa-building"></i> Nguồn hàng
                </span>
            </div>

            {{-- Alert lỗi --}}
            @if ($errors->any())
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            {{-- Alert success --}}
            @if (session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            {{-- Form đăng nhập --}}
            <form action="{{ route('nhanvien.login.post') }}" method="POST" id="loginForm" novalidate>
                @csrf

                {{-- Email --}}
                <div class="form-group">
                    <label class="form-label" for="email">
                        Email đăng nhập <span>*</span>
                    </label>
                    <div class="input-wrap">
                        <i class="fas fa-envelope input-icon"></i>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                            placeholder="example@thanhcongland.vn"
                            class="{{ $errors->has('email') ? 'is-invalid' : '' }}" autocomplete="email" autofocus>
                    </div>
                    @error('email')
                        <div class="invalid-feedback">
                            <i class="fas fa-times-circle"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="form-group">
                    <label class="form-label" for="password">
                        Mật khẩu <span>*</span>
                    </label>
                    <div class="input-wrap">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" id="password" name="password" placeholder="Nhập mật khẩu..."
                            class="{{ $errors->has('password') ? 'is-invalid' : '' }}" autocomplete="current-password">
                        <button type="button" class="btn-eye" id="btnEye" title="Hiện / Ẩn mật khẩu">
                            <i class="fas fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="invalid-feedback">
                            <i class="fas fa-times-circle"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Remember & Forgot --}}
                <div class="form-row-check">
                    <label class="check-label">
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <span class="check-box"></span>
                        Ghi nhớ đăng nhập
                    </label>
                    <a href="#" class="forgot-link">Quên mật khẩu?</a>
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn-submit" id="btnSubmit">
                    <div class="spinner"></div>
                    <div class="btn-text">
                        <i class="fas fa-sign-in-alt"></i>
                        Đăng nhập hệ thống
                    </div>
                </button>

            </form>

            <div class="login-divider">Cần hỗ trợ?</div>

            <div class="login-support">
                Liên hệ Admin qua hotline
                <a href="tel:+840123456789">0123 456 789</a><br>
                hoặc email
                <a href="mailto:admin@thanhcongland.vn">admin@thanhcongland.vn</a>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // ── Toggle hiện/ẩn mật khẩu ──
            const btnEye = document.getElementById('btnEye');
            const pwInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');

            btnEye?.addEventListener('click', () => {
                const show = pwInput.type === 'password';
                pwInput.type = show ? 'text' : 'password';
                eyeIcon.className = show ? 'fas fa-eye-slash' : 'fas fa-eye';
                pwInput.focus();
            });

            // ── Loading khi submit ──
            const form = document.getElementById('loginForm');
            const btnSubmit = document.getElementById('btnSubmit');

            form?.addEventListener('submit', function(e) {
                const email = document.getElementById('email').value.trim();
                const password = pwInput.value.trim();

                if (!email || !password) return; // Browser validation sẽ xử lý

                btnSubmit.classList.add('loading');
            });

            // ── Auto dismiss alert sau 5 giây ──
            setTimeout(() => {
                document.querySelectorAll('.alert-success').forEach(el => {
                    el.style.transition = 'opacity .5s';
                    el.style.opacity = '0';
                    setTimeout(() => el.remove(), 500);
                });
            }, 5000);

        });
    </script>
</body>

</html>
