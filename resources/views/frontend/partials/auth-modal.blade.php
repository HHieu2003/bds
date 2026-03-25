<div class="kh-modal-backdrop" id="authModalBackdrop" onclick="closeAuthModal()"></div>

<div class="kh-modal" id="authModal" style="max-width:440px">
    <div class="kh-modal-header">
        <div class="kh-modal-icon" id="authModalIcon" style="background:linear-gradient(135deg,#FF8C42,#FF5722)">
            <i class="fas fa-user" id="authModalIconI"></i>
        </div>
        <div>
            <div class="kh-modal-title" id="authModalTitle">Đăng nhập</div>
            <div class="kh-modal-sub" id="authModalSub">Chào mừng bạn trở lại!</div>
        </div>
        <button class="kh-modal-close" onclick="closeAuthModal()"><i class="fas fa-times"></i></button>
    </div>

    <div class="kh-modal-body" style="padding:1.3rem 1.5rem 1.5rem">

        {{-- BƯỚC 1: ĐĂNG NHẬP --}}
        <div id="authStepLogin">
            <form id="formAuthLogin" autocomplete="off">
                @csrf
                <div class="kh-field">
                    <label class="kh-field-label"><i class="fas fa-envelope"></i> Email <span>*</span></label>
                    <input type="email" class="kh-field-input" id="loginEmail" name="email" placeholder="example@gmail.com">
                    <div class="kh-field-err" id="errLoginEmail"></div>
                </div>
                <div class="kh-field">
                    <label class="kh-field-label"><i class="fas fa-lock"></i> Mật khẩu <span>*</span></label>
                    <div class="kh-pw-wrap">
                        <input type="password" class="kh-field-input" id="loginPassword" name="password" placeholder="Nhập mật khẩu">
                        <button type="button" class="kh-pw-eye" onclick="toggleAuthEye('loginPassword',this)"><i class="far fa-eye"></i></button>
                    </div>
                    <div class="kh-field-err" id="errLoginPassword"></div>
                </div>
                <div style="text-align:right;margin:-4px 0 12px">
                    <a href="#" onclick="switchAuthStep('forgot');return false" style="font-size:.76rem;color:#FF8C42;font-weight:700;text-decoration:none">Quên mật khẩu?</a>
                </div>
                <div class="kh-field-err" id="errLoginGeneral" style="text-align:center;margin-bottom:10px;font-size:.8rem"></div>
                <button type="submit" class="kh-btn-submit" id="btnAuthLogin"><i class="fas fa-sign-in-alt"></i> Đăng nhập</button>
            </form>
            <div style="text-align:center;margin-top:1rem;font-size:.8rem;color:#888">
                Chưa có tài khoản? <a href="#" onclick="switchAuthStep('register');return false" style="color:#FF8C42;font-weight:700;text-decoration:none">Đăng ký ngay</a>
            </div>
        </div>

        {{-- BƯỚC 2: ĐĂNG KÝ --}}
        <div id="authStepRegister" style="display:none">
            <form id="formAuthRegister" autocomplete="off">
                @csrf
                <div class="kh-field">
                    <label class="kh-field-label"><i class="fas fa-user"></i> Họ và tên <span>*</span></label>
                    <input type="text" class="kh-field-input" id="regHoTen" name="ho_ten" placeholder="Nguyễn Văn A">
                    <div class="kh-field-err" id="errRegHoTen"></div>
                </div>
                <div class="kh-field">
                    <label class="kh-field-label"><i class="fas fa-envelope"></i> Email <span>*</span></label>
                    <input type="email" class="kh-field-input" id="regEmail" name="email" placeholder="example@gmail.com">
                    <div class="kh-field-err" id="errRegEmail"></div>
                </div>
                <div class="kh-field">
                    <label class="kh-field-label"><i class="fas fa-phone"></i> Số điện thoại</label>
                    <input type="tel" class="kh-field-input" id="regSdt" name="so_dien_thoai" placeholder="0912 345 678">
                    <div class="kh-field-err" id="errRegSoDienThoai"></div>
                </div>
                <div class="kh-field">
                    <label class="kh-field-label"><i class="fas fa-lock"></i> Mật khẩu <span>*</span></label>
                    <div class="kh-pw-wrap">
                        <input type="password" class="kh-field-input" id="regPassword" name="password" placeholder="Tối thiểu 6 ký tự">
                        <button type="button" class="kh-pw-eye" onclick="toggleAuthEye('regPassword',this)"><i class="far fa-eye"></i></button>
                    </div>
                    <div class="kh-field-err" id="errRegPassword"></div>
                </div>
                <div class="kh-field">
                    <label class="kh-field-label"><i class="fas fa-key"></i> Xác nhận mật khẩu <span>*</span></label>
                    <div class="kh-pw-wrap">
                        <input type="password" class="kh-field-input" id="regPasswordConfirm" name="password_confirmation" placeholder="Nhập lại mật khẩu">
                        <button type="button" class="kh-pw-eye" onclick="toggleAuthEye('regPasswordConfirm',this)"><i class="far fa-eye"></i></button>
                    </div>
                    <div class="kh-field-err" id="errRegPasswordConfirmation"></div>
                </div>
                <button type="submit" class="kh-btn-submit" id="btnAuthRegister"><i class="fas fa-user-plus"></i> Đăng ký</button>
            </form>
            <div style="text-align:center;margin-top:1rem;font-size:.8rem;color:#888">
                Đã có tài khoản? <a href="#" onclick="switchAuthStep('login');return false" style="color:#FF8C42;font-weight:700;text-decoration:none">Đăng nhập</a>
            </div>
        </div>

        {{-- BƯỚC 3: NHẬP OTP KÍCH HOẠT --}}
        <div id="authStepOtp" style="display:none">
            <div style="text-align:center;margin-bottom:1.4rem">
                <div style="width:58px;height:58px;border-radius:50%;margin:0 auto 12px;background:linear-gradient(135deg,#FF8C42,#FF5722);display:flex;align-items:center;justify-content:center;font-size:1.4rem;color:#fff;box-shadow:0 4px 16px rgba(255,140,66,.4)"><i class="fas fa-envelope-open-text"></i></div>
                <p style="font-size:.84rem;color:#555;margin:0">Nhập mã OTP đã gửi đến<br><strong id="otpEmailDisplay" style="color:#1a3c5e"></strong></p>
            </div>
            <div style="display:flex;gap:8px;justify-content:center;margin-bottom:1.2rem" id="otpInputs">
                @for ($i = 0; $i < 6; $i++) <input type="text" class="otp-box reg-otp-box" maxlength="1" inputmode="numeric"> @endfor
            </div>
            <div class="kh-field-err" id="errOtp" style="text-align:center;margin-bottom:10px;font-size:.8rem"></div>
            <button id="btnVerifyOtp" class="kh-btn-submit"><i class="fas fa-check-circle"></i> Xác thực ngay</button>
            <div style="text-align:center;margin-top:.9rem;font-size:.78rem;color:#888">
                <span id="otpCountdownWrap">Gửi lại sau <strong id="otpTimer" style="color:#FF8C42">60</strong>s</span>
                <a href="#" id="btnResendOtp" style="display:none;color:#FF8C42;font-weight:700;text-decoration:none"><i class="fas fa-redo-alt"></i> Gửi lại OTP</a>
            </div>
            <button onclick="switchAuthStep('register')" style="width:100%;margin-top:.6rem;padding:.5rem;border:1.5px solid #e5e7eb;border-radius:10px;background:#fff;color:#9ca3af;font-size:.78rem;cursor:pointer;font-family:inherit">← Quay lại đăng ký</button>
        </div>

        {{-- BƯỚC 4: QUÊN MẬT KHẨU (GỬI YÊU CẦU) --}}
        <div id="authStepForgot" style="display:none">
            <div style="text-align:center;margin-bottom:1.2rem">
                <p style="font-size:.84rem;color:#555;margin:0">Nhập email đăng ký — chúng tôi sẽ gửi mã OTP và link đặt lại mật khẩu.</p>
            </div>
            <form id="formAuthForgot" autocomplete="off">
                @csrf
                <div class="kh-field">
                    <label class="kh-field-label"><i class="fas fa-envelope"></i> Email <span>*</span></label>
                    <input type="email" class="kh-field-input" id="forgotEmail" name="email" placeholder="example@gmail.com">
                    <div class="kh-field-err" id="errForgotEmail"></div>
                </div>
                <div class="kh-field-err" id="errForgotGeneral" style="text-align:center;margin-bottom:10px;font-size:.8rem;color:#10b981"></div>
                <button type="submit" class="kh-btn-submit" id="btnAuthForgot"><i class="fas fa-paper-plane"></i> Nhận mã OTP</button>
            </form>
            <div style="text-align:center;margin-top:1rem;font-size:.8rem;color:#888">
                <a href="#" onclick="switchAuthStep('login');return false" style="color:#FF8C42;font-weight:700;text-decoration:none">← Quay lại đăng nhập</a>
            </div>
        </div>

        {{-- BƯỚC 5: NHẬP OTP ĐẶT LẠI MK --}}
        <div id="authStepResetConfirm" style="display:none">
            <div style="text-align:center;margin-bottom:1.4rem">
                <p style="font-size:.84rem;color:#555;margin:0">Nhập mã 6 số được gửi đến <strong id="resetEmailDisplay" style="color:#1a3c5e"></strong><br>và tạo mật khẩu mới.</p>
            </div>
            <form id="formAuthResetConfirm" autocomplete="off">
                @csrf
                <div class="kh-field">
                    <label class="kh-field-label"><i class="fas fa-shield-alt"></i> Mã OTP <span>*</span></label>
                    <input type="text" class="kh-field-input" id="resetOtp" placeholder="Nhập mã 6 số" maxlength="6" inputmode="numeric" required style="letter-spacing: 5px; font-weight: bold; text-align: center; font-size: 1.2rem;">
                    <div class="kh-field-err" id="errResetOtp"></div>
                </div>
                <div class="kh-field">
                    <label class="kh-field-label"><i class="fas fa-lock"></i> Mật khẩu mới <span>*</span></label>
                    <div class="kh-pw-wrap">
                        <input type="password" class="kh-field-input" id="resetNewPassword" placeholder="Tối thiểu 6 ký tự" required>
                        <button type="button" class="kh-pw-eye" onclick="toggleAuthEye('resetNewPassword',this)"><i class="far fa-eye"></i></button>
                    </div>
                    <div class="kh-field-err" id="errResetNewPassword"></div>
                </div>
                <div class="kh-field">
                    <label class="kh-field-label"><i class="fas fa-key"></i> Xác nhận mật khẩu <span>*</span></label>
                    <div class="kh-pw-wrap">
                        <input type="password" class="kh-field-input" id="resetNewPasswordConfirm" placeholder="Nhập lại mật khẩu" required>
                        <button type="button" class="kh-pw-eye" onclick="toggleAuthEye('resetNewPasswordConfirm',this)"><i class="far fa-eye"></i></button>
                    </div>
                </div>
                <div class="kh-field-err" id="errResetConfirmGeneral" style="text-align:center;margin-bottom:10px;font-size:.8rem"></div>
                <button type="submit" class="kh-btn-submit" id="btnAuthResetConfirm"><i class="fas fa-save"></i> Cập nhật mật khẩu</button>
            </form>
            <div style="text-align:center;margin-top:1rem;font-size:.8rem;color:#888">
                <a href="#" onclick="switchAuthStep('forgot');return false" style="color:#FF8C42;font-weight:700;text-decoration:none">← Gửi lại email</a>
            </div>
        </div>
    </div>
</div>

<style>
    .kh-modal-backdrop { display: none; position: fixed; inset: 0; background: rgba(0, 0, 0, 0.6); z-index: 1050; backdrop-filter: blur(3px); }
    .kh-modal-backdrop.show { display: block; }
    .kh-modal { display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%) scale(0.95); background: #fff; border-radius: 16px; z-index: 1051; width: 90%; max-width: 440px; box-shadow: 0 25px 50px rgba(0,0,0,0.25); opacity: 0; transition: transform 0.3s ease, opacity 0.3s ease; }
    .kh-modal.show { display: block; transform: translate(-50%, -50%) scale(1); opacity: 1; }
    .kh-modal-header { display: flex; align-items: center; gap: 15px; padding: 1.2rem 1.5rem; border-bottom: 1px solid #f0f0f0; position: relative; }
    .kh-modal-icon { width: 45px; height: 45px; border-radius: 50%; display: flex; justify-content: center; align-items: center; color: #fff; font-size: 1.2rem; flex-shrink: 0; transition: background 0.3s; }
    .kh-modal-title { font-size: 1.1rem; font-weight: 700; color: #1a3c5e; margin-bottom: 2px; }
    .kh-modal-sub { font-size: 0.8rem; color: #888; }
    .kh-modal-close { position: absolute; top: 1.2rem; right: 1.2rem; background: none; border: none; font-size: 1.2rem; color: #aaa; cursor: pointer; transition: color 0.2s; padding: 0; }
    .kh-modal-close:hover { color: #e74c3c; }
    .kh-field { margin-bottom: 1rem; }
    .kh-field-label { display: block; font-size: 0.82rem; font-weight: 600; margin-bottom: 0.4rem; color: #444; }
    .kh-field-label i { color: #888; margin-right: 4px; }
    .kh-field-label span { color: #e74c3c; }
    .kh-field-input { width: 100%; padding: 0.65rem 0.9rem; border: 1.5px solid #e0e0e0; border-radius: 8px; font-size: 0.9rem; outline: none; transition: all 0.2s; font-family: inherit; color: #333; }
    .kh-field-input:focus { border-color: #FF8C42; box-shadow: 0 0 0 3px rgba(255,140,66,0.15); }
    .kh-field-input.error { border-color: #e74c3c; box-shadow: 0 0 0 3px rgba(231,76,60,0.1); }
    .kh-pw-wrap { position: relative; }
    .kh-pw-eye { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #aaa; cursor: pointer; padding: 0; }
    .kh-pw-eye:hover { color: #333; }
    .kh-field-err { color: #e74c3c; font-size: 0.78rem; margin-top: 5px; display: none; align-items: center; gap: 4px; }
    .kh-btn-submit { width: 100%; padding: 0.75rem; background: linear-gradient(135deg,#FF8C42,#FF5722); border: none; border-radius: 8px; color: #fff; font-weight: 700; font-size: 0.95rem; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 6px; transition: transform 0.2s, box-shadow 0.2s; font-family: inherit; }
    .kh-btn-submit:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(255,140,66,0.35); }
    .kh-btn-submit:disabled { opacity: 0.7; cursor: not-allowed; transform: none; box-shadow: none; }
    .otp-box { width: 46px; height: 54px; text-align: center; font-size: 1.5rem; font-weight: 900; border: 2px solid #e5e7eb; border-radius: 10px; outline: none; transition: border-color .2s,box-shadow .2s; color: #1a3c5e; font-family: inherit; background: #fafafa; }
    .otp-box:focus { border-color: #FF8C42; box-shadow: 0 0 0 3px rgba(255,140,66,0.15); background: #fff; }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const AUTH_CSRF = '{{ csrf_token() }}';
        const URL_LOGIN = '{{ route("khach-hang.login.post") }}';
        const URL_REG = '{{ route("khach-hang.register.post") }}';
        const URL_OTP = '{{ route("khach-hang.verify-otp") }}';
        const URL_SENDOTP = '{{ route("khach-hang.send-otp") }}';
        const URL_FORGOT = '{{ route("khach-hang.forgot.post") }}';
        const URL_RESET = '{{ route("khach-hang.reset.post") }}';

        let _otpEmail = '', _resetEmail = '', _otpTimer = null;

        window.openAuthModal = function(step = 'login') {
            clearAuthErrors();
            document.getElementById('authModalBackdrop').classList.add('show');
            document.getElementById('authModal').classList.add('show');
            document.body.style.overflow = 'hidden';
            switchAuthStep(step);
        };

        window.closeAuthModal = function() {
            document.getElementById('authModalBackdrop').classList.remove('show');
            document.getElementById('authModal').classList.remove('show');
            document.body.style.overflow = '';
        };

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape' && document.getElementById('authModal').classList.contains('show')) closeAuthModal();
        });

        @if(session('open_auth_modal')) openAuthModal('{{ session('open_auth_modal') }}'); @endif

        const AUTH_STEPS = {
            login:        { title: 'Đăng nhập', sub: 'Chào mừng bạn trở lại!', icon: 'fa-user' },
            register:     { title: 'Tạo tài khoản', sub: 'Đăng ký miễn phí ngay hôm nay', icon: 'fa-user-plus' },
            otp:          { title: 'Xác thực OTP', sub: 'Kiểm tra hộp thư của bạn', icon: 'fa-shield-alt' },
            forgot:       { title: 'Quên mật khẩu', sub: 'Lấy lại quyền truy cập', icon: 'fa-key' },
            resetConfirm: { title: 'Tạo mật khẩu mới', sub: 'Bảo mật tài khoản của bạn', icon: 'fa-unlock-alt' }
        };

        window.switchAuthStep = function(step) {
            ['login', 'register', 'otp', 'forgot', 'resetConfirm'].forEach(s => {
                const el = document.getElementById('authStep' + s.charAt(0).toUpperCase() + s.slice(1));
                if(el) el.style.display = (s === step) ? 'block' : 'none';
            });
            const cfg = AUTH_STEPS[step];
            document.getElementById('authModalTitle').textContent = cfg.title;
            document.getElementById('authModalSub').textContent = cfg.sub;
            document.getElementById('authModalIconI').className = 'fas ' + cfg.icon;
            clearAuthErrors();
        };

        function clearAuthErrors() {
            document.querySelectorAll('[id^="err"]').forEach(el => { el.innerHTML = ''; el.style.display = 'none'; });
            document.querySelectorAll('.kh-field-input').forEach(el => el.classList.remove('error'));
        }

        function showAuthErrors(errors) {
            Object.entries(errors).forEach(([key, msgs]) => {
                const maps = { ho_ten: 'errRegHoTen', email: 'errRegEmail', password: 'errRegPassword', so_dien_thoai: 'errRegSoDienThoai', password_confirmation: 'errRegPasswordConfirmation' };
                const elId = maps[key];
                if (elId) {
                    const el = document.getElementById(elId);
                    if (el) { el.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${Array.isArray(msgs) ? msgs[0] : msgs}`; el.style.display = 'flex'; }
                }
            });
        }

        async function authPost(url, data, btn, origHtml) {
            btn.disabled = true; btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Đang xử lý...';
            try {
                const res = await fetch(url, { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': AUTH_CSRF, 'Accept': 'application/json' }, body: JSON.stringify(data) });
                return await res.json();
            } catch { return { success: false, message: 'Lỗi kết nối, vui lòng thử lại.' }; } finally { btn.disabled = false; btn.innerHTML = origHtml; }
        }

        // ĐĂNG NHẬP
        document.getElementById('formAuthLogin')?.addEventListener('submit', async function(e) {
            e.preventDefault(); clearAuthErrors(); const btn = document.getElementById('btnAuthLogin');
            const data = await authPost(URL_LOGIN, { email: document.getElementById('loginEmail').value.trim(), password: document.getElementById('loginPassword').value }, btn, '<i class="fas fa-sign-in-alt"></i> Đăng nhập');
            if (data.success) location.reload();
            else if (data.errors) {
                if (data.errors.email) { document.getElementById('errLoginEmail').innerHTML = `<i class="fas fa-exclamation-circle"></i> ${data.errors.email[0]}`; document.getElementById('errLoginEmail').style.display='flex'; }
                if (data.errors.password) { document.getElementById('errLoginPassword').innerHTML = `<i class="fas fa-exclamation-circle"></i> ${data.errors.password[0]}`; document.getElementById('errLoginPassword').style.display='flex'; }
            } else { document.getElementById('errLoginGeneral').innerHTML = `<i class="fas fa-exclamation-circle"></i> ${data.message}`; document.getElementById('errLoginGeneral').style.color = '#e74c3c'; document.getElementById('errLoginGeneral').style.display='block'; }
        });

        // ĐĂNG KÝ
        document.getElementById('formAuthRegister')?.addEventListener('submit', async function(e) {
            e.preventDefault(); clearAuthErrors(); const btn = document.getElementById('btnAuthRegister');
            const data = await authPost(URL_REG, { ho_ten: document.getElementById('regHoTen').value.trim(), email: document.getElementById('regEmail').value.trim(), so_dien_thoai: document.getElementById('regSdt').value.trim(), password: document.getElementById('regPassword').value, password_confirmation: document.getElementById('regPasswordConfirm').value }, btn, '<i class="fas fa-user-plus"></i> Đăng ký');
            if (data.success) {
                _otpEmail = data.email || document.getElementById('regEmail').value.trim();
                document.getElementById('otpEmailDisplay').textContent = _otpEmail;
                switchAuthStep('otp'); startOtpCountdown(); setTimeout(() => document.querySelector('.reg-otp-box')?.focus(), 100);
            } else if (data.errors) showAuthErrors(data.errors);
        });

        // OTP KÍCH HOẠT TÀI KHOẢN
        document.querySelectorAll('.reg-otp-box').forEach((inp, i, all) => {
            inp.addEventListener('input', function() { this.value = this.value.replace(/\D/g, ''); if (this.value && i < all.length - 1) all[i + 1].focus(); if ([...all].every(b => b.value)) submitOtp(); });
            inp.addEventListener('keydown', function(e) { if (e.key === 'Backspace' && !this.value && i > 0) all[i - 1].focus(); });
            inp.addEventListener('paste', function(e) { e.preventDefault(); const text = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, ''); [...all].forEach((box, idx) => { box.value = text[idx] || ''; }); if (text.length >= 6) submitOtp(); });
        });

        document.getElementById('btnVerifyOtp')?.addEventListener('click', submitOtp);
        async function submitOtp() {
            const otp = [...document.querySelectorAll('.reg-otp-box')].map(b => b.value).join(''); if (otp.length !== 6) return;
            const btn = document.getElementById('btnVerifyOtp');
            const data = await authPost(URL_OTP, { email: _otpEmail, otp }, btn, '<i class="fas fa-check-circle"></i> Xác thực ngay');
            if (data.success) { clearInterval(_otpTimer); btn.innerHTML = '<i class="fas fa-check-circle"></i> Thành công! Đang tải...'; btn.style.background = '#10b981'; setTimeout(() => location.reload(), 800); }
            else { document.getElementById('errOtp').innerHTML = `<i class="fas fa-exclamation-circle"></i> ${data.message}`; document.getElementById('errOtp').style.display='block'; document.querySelectorAll('.reg-otp-box').forEach(b => { b.value = ''; }); document.querySelector('.reg-otp-box')?.focus(); }
        }

        document.getElementById('btnResendOtp')?.addEventListener('click', async function(e) { e.preventDefault(); this.style.display = 'none'; document.getElementById('otpCountdownWrap').style.display = 'inline'; await fetch(URL_SENDOTP, { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': AUTH_CSRF }, body: JSON.stringify({ email: _otpEmail }) }); startOtpCountdown(); });

        function startOtpCountdown() {
            clearInterval(_otpTimer); let s = 60; document.getElementById('otpTimer').textContent = s; document.getElementById('otpCountdownWrap').style.display = 'inline'; document.getElementById('btnResendOtp').style.display = 'none'; document.getElementById('errOtp').style.display = 'none';
            _otpTimer = setInterval(() => { s--; document.getElementById('otpTimer').textContent = s; if (s <= 0) { clearInterval(_otpTimer); document.getElementById('otpCountdownWrap').style.display = 'none'; document.getElementById('btnResendOtp').style.display = 'inline'; } }, 1000);
        }

        // QUÊN MẬT KHẨU (GỬI YÊU CẦU)
        document.getElementById('formAuthForgot')?.addEventListener('submit', async function(e) {
            e.preventDefault(); clearAuthErrors(); const btn = document.getElementById('btnAuthForgot'); _resetEmail = document.getElementById('forgotEmail').value.trim();
            const data = await authPost(URL_FORGOT, { email: _resetEmail }, btn, '<i class="fas fa-paper-plane"></i> Nhận mã OTP');
            if (data.success) { document.getElementById('resetEmailDisplay').textContent = _resetEmail; switchAuthStep('resetConfirm'); }
            else {
                if (data.errors?.email) { document.getElementById('errForgotEmail').innerHTML = `<i class="fas fa-exclamation-circle"></i> ${data.errors.email[0]}`; document.getElementById('errForgotEmail').style.display = 'flex'; }
                else if(data.message) { document.getElementById('errForgotGeneral').innerHTML = `<i class="fas fa-exclamation-circle"></i> ${data.message}`; document.getElementById('errForgotGeneral').style.color = '#e74c3c'; document.getElementById('errForgotGeneral').style.display = 'block'; }
            }
        });

        // CẬP NHẬT MẬT KHẨU BẰNG MÃ OTP (TRÊN MODAL)
        document.getElementById('formAuthResetConfirm')?.addEventListener('submit', async function(e) {
            e.preventDefault(); clearAuthErrors(); const btn = document.getElementById('btnAuthResetConfirm');
            const data = await authPost(URL_RESET, { email: _resetEmail, token: document.getElementById('resetOtp').value.trim(), password: document.getElementById('resetNewPassword').value, password_confirmation: document.getElementById('resetNewPasswordConfirm').value }, btn, '<i class="fas fa-save"></i> Cập nhật mật khẩu');
            if (data.success) {
                btn.innerHTML = '<i class="fas fa-check-circle"></i> Đặt lại thành công!'; btn.style.background = '#10b981';
                setTimeout(() => { switchAuthStep('login'); document.getElementById('loginEmail').value = _resetEmail; document.getElementById('errLoginGeneral').innerHTML = `<i class="fas fa-check-circle"></i> Đặt lại mật khẩu thành công! Vui lòng đăng nhập.`; document.getElementById('errLoginGeneral').style.color = '#10b981'; document.getElementById('errLoginGeneral').style.display = 'block'; btn.innerHTML = '<i class="fas fa-save"></i> Cập nhật mật khẩu'; btn.style.background = ''; }, 1200);
            } else {
                if (data.errors) {
                    if (data.errors.password) { document.getElementById('errResetNewPassword').innerHTML = `<i class="fas fa-exclamation-circle"></i> ${data.errors.password[0]}`; document.getElementById('errResetNewPassword').style.display = 'flex'; }
                    if (data.errors.token) { document.getElementById('errResetOtp').innerHTML = `<i class="fas fa-exclamation-circle"></i> ${data.errors.token[0]}`; document.getElementById('errResetOtp').style.display = 'flex'; }
                } else { document.getElementById('errResetConfirmGeneral').innerHTML = `<i class="fas fa-exclamation-circle"></i> ${data.message}`; document.getElementById('errResetConfirmGeneral').style.color = '#e74c3c'; document.getElementById('errResetConfirmGeneral').style.display = 'block'; }
            }
        });

        window.toggleAuthEye = function(id, btn) { const inp = document.getElementById(id); const ico = btn.querySelector('i'); const show = inp.type === 'password'; inp.type = show ? 'text' : 'password'; ico.className = show ? 'far fa-eye-slash' : 'far fa-eye'; };
    });
</script>
