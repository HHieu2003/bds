{{-- ══════════════════════════════════════
     AUTH MODAL — Đăng nhập / Đăng ký
     Mở từ JS: openAuthModal('login') hoặc openAuthModal('register')
══════════════════════════════════════ --}}

<style>
    /* ── Overlay nền mờ ── */
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

    /* ── Hộp modal ── */
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

    /* ── Nút đóng ── */
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

    /* ── Tabs Đăng nhập / Đăng ký ── */
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

    /* ── Input ── */
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

    /* ── Nút submit ── */
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

    /* ── Divider & Social ── */
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

    /* ── OTP ── */
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

    @media (max-width: 768px) {
        .auth-modal {
            padding: 1.5rem 1.2rem;
        }

        .auth-social-btns {
            flex-direction: column;
        }
    }
</style>

{{-- ══ HTML ══ --}}
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

        {{-- ── Form Đăng nhập ── --}}
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
                        <input type="password" name="password" placeholder="Mật khẩu..." autocomplete="current-password"
                            required>
                    </div>
                </div>

                <div
                    style="display:flex;justify-content:space-between;
                            align-items:center;font-size:.8rem;margin-bottom:.5rem">
                    <label
                        style="display:flex;align-items:center;gap:.4rem;
                                  cursor:pointer;color:var(--text-sub)">
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        Ghi nhớ đăng nhập
                    </label>
                    <a href="{{ route('khach-hang.forgot') }}" style="color:var(--primary);font-weight:600">
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
                    <i class="fab fa-facebook-f" style="color:#1877f2"></i> Facebook
                </button>
            </div>
        </div>

        {{-- ── Form Đăng ký ── --}}
        <div class="auth-form-wrap" id="authTabRegister">
            <form id="formRegister" action="{{ route('khach-hang.register.post') }}" method="POST">
                @csrf

                <div class="auth-input-group">
                    <label>Họ và tên <span>*</span></label>
                    <div class="auth-input-wrap">
                        <i class="fas fa-user field-icon"></i>
                        <input type="text" name="ho_ten" placeholder="Nguyễn Văn A" value="{{ old('ho_ten') }}"
                            required>
                    </div>
                </div>

                <div class="auth-input-group">
                    <label>Số điện thoại <span>*</span></label>
                    <div class="auth-input-wrap">
                        <i class="fas fa-phone field-icon"></i>
                        <input type="tel" name="so_dien_thoai" id="inputSdtRegister" placeholder="0912 345 678"
                            value="{{ old('so_dien_thoai') }}" required>
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
                                margin-top:.3rem;display:none">
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
                        <input type="password" name="password_confirmation" placeholder="Nhập lại mật khẩu" required>
                    </div>
                </div>

                <button type="submit" class="auth-btn-submit">
                    <i class="fas fa-user-plus"></i> Tạo tài khoản
                </button>
            </form>
        </div>

    </div>
</div>

{{-- ══ JS ══ --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var overlay = document.getElementById('authModalOverlay');
        var closeBtn = document.getElementById('btnCloseAuthModal');

        /* Mở modal — gọi: openAuthModal('login') hoặc openAuthModal('register') */
        window.openAuthModal = function(tab) {
            if (!overlay) return;
            overlay.classList.add('show');
            document.body.style.overflow = 'hidden';
            if (tab) switchAuthTab(tab);
        };

        /* Đóng modal */
        window.closeAuthModal = function() {
            if (!overlay) return;
            overlay.classList.remove('show');
            document.body.style.overflow = '';
        };

        /* Đóng khi click nút X */
        if (closeBtn) closeBtn.addEventListener('click', closeAuthModal);

        /* Đóng khi click ra ngoài overlay */
        if (overlay) overlay.addEventListener('click', function(e) {
            if (e.target === overlay) closeAuthModal();
        });

        /* Đóng khi nhấn Escape */
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeAuthModal();
        });

        /* Chuyển tab */
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

        /* ── Gửi OTP + đếm ngược ── */
        var btnOtp = document.getElementById('btnSendOtp');
        var countdown = document.getElementById('otpCountdown');

        if (btnOtp) {
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
                            if (data.otp_dev) console.log('DEV OTP:', data.otp_dev);
                        } else {
                            showFlash(data.message || 'Không thể gửi OTP.', 'error');
                        }
                    })
                    .catch(function() {
                        showFlash('Lỗi kết nối, thử lại sau.', 'error');
                    });
            });
        }

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
</script>
@if (session('open_auth_modal'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            openAuthModal('{{ session('open_auth_modal') }}');
        });
    </script>
@endif
