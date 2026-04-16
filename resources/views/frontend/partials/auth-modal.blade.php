<div class="kh-modal-backdrop" id="authModalBackdrop" onclick="closeAuthModal()"></div>

<div class="kh-modal" id="authModal">
    <div class="kh-modal-header">
        <div class="kh-modal-icon bg-primary-brand" id="authModalIcon">
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
                <div class="kh-field">
                    <label class="kh-field-label"><i class="fas fa-envelope"></i> Email <span>*</span></label>
                    <input type="email" class="kh-field-input" id="loginEmail" placeholder="example@gmail.com">
                    <div class="kh-field-err" id="errLoginEmail"></div>
                </div>
                <div class="kh-field">
                    <label class="kh-field-label"><i class="fas fa-lock"></i> Mật khẩu <span>*</span></label>
                    <div class="kh-pw-wrap">
                        <input type="password" class="kh-field-input" id="loginPassword" placeholder="Nhập mật khẩu">
                        <button type="button" class="kh-pw-eye" onclick="toggleAuthEye('loginPassword',this)"><i
                                class="far fa-eye"></i></button>
                    </div>
                    <div class="kh-field-err" id="errLoginPassword"></div>
                </div>
                <div style="text-align:right;margin:-4px 0 12px">
                    <a href="#" onclick="switchAuthStep('forgot');return false" class="text-primary-brand fw-bold"
                        style="font-size:.8rem;">Quên mật khẩu?</a>
                </div>
                <div class="kh-field-err" id="errLoginGeneral"
                    style="text-align:center;margin-bottom:10px;font-size:.85rem"></div>

                <button type="submit" class="btn-primary-brand w-100 justify-content-center py-2" id="btnAuthLogin"
                    style="font-size: 1rem;">
                    <i class="fas fa-sign-in-alt"></i> Đăng nhập
                </button>
            </form>
            <div style="text-align:center;margin-top:1rem;font-size:.85rem;color:var(--text-muted)">
                Chưa có tài khoản? <a href="#" onclick="switchAuthStep('register');return false"
                    class="text-primary-brand fw-bold">Đăng ký ngay</a>
            </div>
        </div>

        {{-- BƯỚC 2: ĐĂNG KÝ --}}
        <div id="authStepRegister" style="display:none">
            <form id="formAuthRegister" autocomplete="off">
                <div class="kh-field">
                    <label class="kh-field-label"><i class="fas fa-user"></i> Họ và tên <span>*</span></label>
                    <input type="text" class="kh-field-input" id="regHoTen" placeholder="Nguyễn Văn A">
                    <div class="kh-field-err" id="errRegHoTen"></div>
                </div>
                <div class="kh-field">
                    <label class="kh-field-label"><i class="fas fa-envelope"></i> Email <span>*</span></label>
                    <input type="email" class="kh-field-input" id="regEmail" placeholder="example@gmail.com">
                    <div class="kh-field-err" id="errRegEmail"></div>
                </div>
                <div class="kh-field">
                    <label class="kh-field-label"><i class="fas fa-phone"></i> Số điện thoại</label>
                    <input type="tel" class="kh-field-input" id="regSdt" placeholder="0912 345 678">
                    <div class="kh-field-err" id="errRegSoDienThoai"></div>
                </div>
                <div class="kh-field">
                    <label class="kh-field-label"><i class="fas fa-lock"></i> Mật khẩu <span>*</span></label>
                    <div class="kh-pw-wrap">
                        <input type="password" class="kh-field-input" id="regPassword" placeholder="Tối thiểu 6 ký tự">
                        <button type="button" class="kh-pw-eye" onclick="toggleAuthEye('regPassword',this)"><i
                                class="far fa-eye"></i></button>
                    </div>
                    <div class="kh-field-err" id="errRegPassword"></div>
                </div>
                <div class="kh-field">
                    <label class="kh-field-label"><i class="fas fa-key"></i> Xác nhận mật khẩu <span>*</span></label>
                    <div class="kh-pw-wrap">
                        <input type="password" class="kh-field-input" id="regPasswordConfirm"
                            placeholder="Nhập lại mật khẩu">
                        <button type="button" class="kh-pw-eye"
                            onclick="toggleAuthEye('regPasswordConfirm',this)"><i class="far fa-eye"></i></button>
                    </div>
                    <div class="kh-field-err" id="errRegPasswordConfirmation"></div>
                </div>

                <button type="submit" class="btn-primary-brand w-100 justify-content-center py-2"
                    id="btnAuthRegister" style="font-size: 1rem;">
                    <i class="fas fa-user-plus"></i> Đăng ký
                </button>
            </form>
            <div style="text-align:center;margin-top:1rem;font-size:.85rem;color:var(--text-muted)">
                Đã có tài khoản? <a href="#" onclick="switchAuthStep('login');return false"
                    class="text-primary-brand fw-bold">Đăng nhập</a>
            </div>
        </div>

        {{-- BƯỚC 3: NHẬP OTP KÍCH HOẠT --}}
        <div id="authStepOtp" style="display:none">
            <div style="text-align:center;margin-bottom:1.4rem">
                <div class="bg-primary-brand"
                    style="width:60px;height:60px;border-radius:50%;margin:0 auto 12px;display:flex;align-items:center;justify-content:center;font-size:1.5rem;color:#fff;box-shadow:var(--shadow-gold)">
                    <i class="fas fa-envelope-open-text"></i>
                </div>
                <p style="font-size:.88rem;color:var(--text-body);margin:0">Nhập mã OTP đã gửi đến<br><strong
                        id="otpEmailDisplay" style="color:var(--secondary)"></strong></p>
            </div>
            <div style="display:flex;gap:8px;justify-content:center;margin-bottom:1.2rem" id="otpInputs">
                @for ($i = 0; $i < 6; $i++)
                    <input type="text" class="otp-box reg-otp-box" maxlength="1" inputmode="numeric">
                @endfor
            </div>
            <div class="kh-field-err" id="errOtp" style="text-align:center;margin-bottom:10px;font-size:.85rem">
            </div>

            <button id="btnVerifyOtp" class="btn-primary-brand w-100 justify-content-center py-2"
                style="font-size: 1rem;">
                <i class="fas fa-check-circle"></i> Xác thực ngay
            </button>

            <div style="text-align:center;margin-top:1rem;font-size:.8rem;color:var(--text-muted)">
                <span id="otpCountdownWrap">Gửi lại sau <strong id="otpTimer"
                        class="text-primary-brand">60</strong>s</span>
                <a href="#" id="btnResendOtp" class="text-primary-brand fw-bold" style="display:none;"><i
                        class="fas fa-redo-alt"></i> Gửi lại OTP</a>
            </div>
            <button onclick="switchAuthStep('register')"
                style="width:100%;margin-top:.8rem;padding:.5rem;border:1.5px solid var(--border);border-radius:8px;background:var(--bg-main);color:var(--text-muted);font-size:.85rem;cursor:pointer;font-family:inherit;font-weight:600;">←
                Quay lại đăng ký</button>
        </div>

        {{-- BƯỚC 4: QUÊN MẬT KHẨU --}}
        <div id="authStepForgot" style="display:none">
            <div style="text-align:center;margin-bottom:1.2rem">
                <p style="font-size:.88rem;color:var(--text-body);margin:0">Nhập email đăng ký — chúng tôi sẽ gửi mã
                    OTP và link đặt lại mật khẩu.</p>
            </div>
            <form id="formAuthForgot" autocomplete="off">
                <div class="kh-field">
                    <label class="kh-field-label"><i class="fas fa-envelope"></i> Email <span>*</span></label>
                    <input type="email" class="kh-field-input" id="forgotEmail" placeholder="example@gmail.com">
                    <div class="kh-field-err" id="errForgotEmail"></div>
                </div>
                <div class="kh-field-err" id="errForgotGeneral"
                    style="text-align:center;margin-bottom:10px;font-size:.85rem;"></div>

                <button type="submit" class="btn-primary-brand w-100 justify-content-center py-2" id="btnAuthForgot"
                    style="font-size: 1rem;">
                    <i class="fas fa-paper-plane"></i> Nhận mã OTP
                </button>
            </form>
            <div style="text-align:center;margin-top:1rem;font-size:.85rem;">
                <a href="#" onclick="switchAuthStep('login');return false" class="text-primary-brand fw-bold">←
                    Quay lại đăng nhập</a>
            </div>
        </div>

        {{-- BƯỚC 5: NHẬP OTP ĐẶT LẠI MK --}}
        <div id="authStepResetConfirm" style="display:none">
            <div style="text-align:center;margin-bottom:1.4rem">
                <p style="font-size:.88rem;color:var(--text-body);margin:0">Nhập mã 6 số được gửi đến <br><strong
                        id="resetEmailDisplay" style="color:var(--secondary)"></strong><br>và tạo mật khẩu mới.</p>
            </div>
            <form id="formAuthResetConfirm" autocomplete="off">
                <div class="kh-field">
                    <label class="kh-field-label"><i class="fas fa-shield-alt"></i> Mã OTP <span>*</span></label>
                    <input type="text" class="kh-field-input" id="resetOtp" placeholder="Nhập mã 6 số"
                        maxlength="6" inputmode="numeric" required
                        style="letter-spacing: 5px; font-weight: bold; text-align: center; font-size: 1.2rem; color: var(--primary);">
                    <div class="kh-field-err" id="errResetOtp"></div>
                </div>
                <div class="kh-field">
                    <label class="kh-field-label"><i class="fas fa-lock"></i> Mật khẩu mới <span>*</span></label>
                    <div class="kh-pw-wrap">
                        <input type="password" class="kh-field-input" id="resetNewPassword"
                            placeholder="Tối thiểu 6 ký tự" required>
                        <button type="button" class="kh-pw-eye" onclick="toggleAuthEye('resetNewPassword',this)"><i
                                class="far fa-eye"></i></button>
                    </div>
                    <div class="kh-field-err" id="errResetNewPassword"></div>
                </div>
                <div class="kh-field">
                    <label class="kh-field-label"><i class="fas fa-key"></i> Xác nhận mật khẩu <span>*</span></label>
                    <div class="kh-pw-wrap">
                        <input type="password" class="kh-field-input" id="resetNewPasswordConfirm"
                            placeholder="Nhập lại mật khẩu" required>
                        <button type="button" class="kh-pw-eye"
                            onclick="toggleAuthEye('resetNewPasswordConfirm',this)"><i
                                class="far fa-eye"></i></button>
                    </div>
                </div>
                <div class="kh-field-err" id="errResetConfirmGeneral"
                    style="text-align:center;margin-bottom:10px;font-size:.85rem"></div>

                <button type="submit" class="btn-primary-brand w-100 justify-content-center py-2"
                    id="btnAuthResetConfirm" style="font-size: 1rem;">
                    <i class="fas fa-save"></i> Cập nhật mật khẩu
                </button>
            </form>
            <div style="text-align:center;margin-top:1rem;font-size:.85rem;">
                <a href="#" onclick="switchAuthStep('forgot');return false"
                    class="text-primary-brand fw-bold">← Gửi lại email</a>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if (session('open_auth_modal'))
            if (typeof window.openAuthModal === 'function') {
                window.openAuthModal('{{ session('open_auth_modal') }}');
            }
        @endif
    });
</script>
