@auth('customer')
    @php $kh = Auth::guard('customer')->user(); @endphp

    {{-- BACKDROP --}}
    <div class="kh-modal-backdrop" id="khModalBackdrop" onclick="closeModalHoSo()"></div>

    {{-- MODAL GỘP 2 TAB --}}
    <div class="kh-modal" id="modalHoSo">

        {{-- Header --}}
        <div class="kh-modal-header">
            <div class="kh-modal-icon" style="background:linear-gradient(135deg,#FF8C42,#FF5722);">
                <i class="fas fa-user-cog"></i>
            </div>
            <div>
                <div class="kh-modal-title">Quản lý tài khoản</div>
                <div class="kh-modal-sub">{{ $kh->ho_ten ?? 'Khách hàng' }}</div>
            </div>
            <button class="kh-modal-close" onclick="closeModalHoSo()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        {{-- Tab Bar --}}
        <div class="kh-tab-bar">
            <button class="kh-tab active" id="tabBtnThongTin" onclick="switchModalTab('thong-tin')">
                <i class="fas fa-user-edit"></i> Thông tin
            </button>
            <button class="kh-tab" id="tabBtnMatKhau" onclick="switchModalTab('mat-khau')">
                <i class="fas fa-shield-alt"></i> Mật khẩu
            </button>
        </div>

        <div class="kh-modal-body">

            {{-- TAB 1: THÔNG TIN --}}
            <div id="tabThongTin" class="kh-tab-panel active">
                <form id="formThongTin" onsubmit="submitThongTin(event)">
                    @csrf
                    <div class="kh-field">
                        <label class="kh-field-label">
                            <i class="fas fa-user"></i> Họ và tên <span>*</span>
                        </label>
                        <input type="text" class="kh-field-input" id="f_ho_ten" name="ho_ten"
                            value="{{ $kh->ho_ten }}" placeholder="Nguyễn Văn A" required>
                        <div class="kh-field-err" id="err_ho_ten"></div>
                    </div>
                    <div class="kh-field">
                        <label class="kh-field-label">
                            <i class="fas fa-phone"></i> Số điện thoại <span>*</span>
                        </label>
                        <input type="tel" class="kh-field-input" id="f_so_dien_thoai" name="so_dien_thoai"
                            value="{{ $kh->so_dien_thoai }}" placeholder="0912 345 678" required>
                        <div class="kh-field-err" id="err_so_dien_thoai"></div>
                    </div>
                    <div class="kh-field">
                        <label class="kh-field-label">
                            <i class="fas fa-envelope"></i> Email
                            <span style="color:#9ca3af;font-weight:500;font-size:.65rem;">(tùy chọn)</span>
                        </label>
                        <input type="email" class="kh-field-input" id="f_email" name="email"
                            value="{{ $kh->email }}" placeholder="example@gmail.com">
                        <div class="kh-field-err" id="err_email"></div>
                    </div>
                    <button type="submit" class="kh-btn-submit" id="btnSubmitThongTin">
                        <i class="fas fa-save"></i> Lưu thay đổi
                    </button>
                </form>
            </div>

            {{-- TAB 2: MẬT KHẨU --}}
            <div id="tabMatKhau" class="kh-tab-panel">
                <form id="formMatKhau" onsubmit="submitMatKhau(event)">
                    @csrf
                    <div class="kh-field">
                        <label class="kh-field-label">
                            <i class="fas fa-lock"></i> Mật khẩu hiện tại <span>*</span>
                        </label>
                        <div class="kh-pw-wrap">
                            <input type="password" class="kh-field-input" id="f_mat_khau_cu" name="mat_khau_cu"
                                placeholder="Nhập mật khẩu hiện tại" required>
                            <button type="button" class="kh-pw-eye" onclick="toggleEye('f_mat_khau_cu',this)">
                                <i class="far fa-eye"></i>
                            </button>
                        </div>
                        <div class="kh-field-err" id="err_mat_khau_cu"></div>
                    </div>
                    <div class="kh-field">
                        <label class="kh-field-label">
                            <i class="fas fa-key"></i> Mật khẩu mới <span>*</span>
                        </label>
                        <div class="kh-pw-wrap">
                            <input type="password" class="kh-field-input" id="f_mat_khau_moi" name="mat_khau_moi"
                                placeholder="Tối thiểu 6 ký tự" oninput="renderStrength(this.value)" required>
                            <button type="button" class="kh-pw-eye" onclick="toggleEye('f_mat_khau_moi',this)">
                                <i class="far fa-eye"></i>
                            </button>
                        </div>
                        <div id="strengthBox" style="display:none;margin-top:.45rem;">
                            <div style="height:5px;background:#e5e7eb;border-radius:4px;overflow:hidden;">
                                <div id="strengthBar"
                                    style="height:100%;border-radius:4px;width:0;transition:width .3s,background .3s;">
                                </div>
                            </div>
                            <span id="strengthTxt" style="font-size:.68rem;font-weight:800;"></span>
                        </div>
                        <div class="kh-field-err" id="err_mat_khau_moi"></div>
                    </div>
                    <div class="kh-field">
                        <label class="kh-field-label">
                            <i class="fas fa-key"></i> Xác nhận mật khẩu mới <span>*</span>
                        </label>
                        <div class="kh-pw-wrap">
                            <input type="password" class="kh-field-input" id="f_mat_khau_moi_confirmation"
                                name="mat_khau_moi_confirmation" placeholder="Nhập lại mật khẩu mới"
                                oninput="checkMatch()" required>
                            <button type="button" class="kh-pw-eye"
                                onclick="toggleEye('f_mat_khau_moi_confirmation',this)">
                                <i class="far fa-eye"></i>
                            </button>
                        </div>
                        <div class="kh-field-err" id="err_match" style="display:none;">
                            <i class="fas fa-exclamation-circle"></i> Mật khẩu xác nhận không khớp
                        </div>
                    </div>
                    <div
                        style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:9px;
                            padding:.5rem .75rem;margin-bottom:.25rem;
                            font-size:.72rem;color:#1e40af;font-weight:600;
                            display:flex;align-items:center;gap:.4rem;">
                        <i class="fas fa-info-circle" style="color:#3b82f6;"></i>
                        Kết hợp chữ hoa, số và ký tự đặc biệt để tăng độ bảo mật
                    </div>
                    <button type="submit" class="kh-btn-submit" id="btnSubmitMk"
                        style="background:linear-gradient(135deg,#3b82f6,#1d4ed8);
                               box-shadow:0 4px 14px rgba(59,130,246,.35);">
                        <i class="fas fa-shield-alt"></i> Cập nhật mật khẩu
                    </button>
                </form>
            </div>

        </div>
    </div>

    {{-- Toast thông báo --}}
    <div id="khToast" class="kh-toast">
        <span id="khToastIcon" style="font-size:1.2rem;flex-shrink:0;"></span>
        <span class="kh-toast-msg"></span>
    </div>

        <style>
            .kh-modal-backdrop {
                position: fixed;
                inset: 0;
                background: rgba(15, 23, 42, .52);
                backdrop-filter: blur(4px);
                z-index: 3000;
                opacity: 0;
                visibility: hidden;
                pointer-events: none;
                transition: opacity .25s;
            }

            .kh-modal-backdrop.show {
                opacity: 1;
                visibility: visible;
                pointer-events: all;
            }

            .kh-modal {
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%) scale(.94);
                z-index: 3001;
                width: min(420px, 95vw);
                background: #fff;
                border-radius: 20px;
                box-shadow: 0 24px 60px rgba(0, 0, 0, .2);
                opacity: 0;
                visibility: hidden;
                pointer-events: none;
                transition: all .28s cubic-bezier(.19, 1, .22, 1);
                overflow: hidden;
            }

            .kh-modal.show {
                opacity: 1;
                visibility: visible;
                pointer-events: all;
                transform: translate(-50%, -50%) scale(1);
            }

            .kh-modal-header {
                display: flex;
                align-items: center;
                gap: .8rem;
                padding: 1.15rem 1.25rem 1rem;
                border-bottom: 1px solid #f0f0f0;
            }

            .kh-modal-icon {
                width: 44px;
                height: 44px;
                border-radius: 12px;
                flex-shrink: 0;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.1rem;
                color: #fff;
            }

            .kh-modal-title {
                font-size: .95rem;
                font-weight: 800;
                color: #0F172A;
            }

            .kh-modal-sub {
                font-size: .7rem;
                color: #9ca3af;
                margin-top: .1rem;
            }

            .kh-modal-close {
                margin-left: auto;
                background: #f3f4f6;
                border: none;
                width: 30px;
                height: 30px;
                border-radius: 8px;
                cursor: pointer;
                color: #6b7280;
                font-size: .8rem;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: all .15s;
                flex-shrink: 0;
            }

            .kh-modal-close:hover {
                background: #fee2e2;
                color: #e74c3c;
            }

            .kh-tab-bar {
                display: grid;
                grid-template-columns: 1fr 1fr;
                background: #f8fafc;
                border-bottom: 2px solid #eef0f3;
                padding: .4rem .5rem 0;
                gap: .3rem;
            }

            .kh-tab {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: .35rem;
                padding: .6rem .5rem;
                background: none;
                border: none;
                border-bottom: 2.5px solid transparent;
                margin-bottom: -2px;
                cursor: pointer;
                font-family: inherit;
                font-size: .8rem;
                font-weight: 700;
                color: #6b7280;
                border-radius: 10px 10px 0 0;
                transition: all .2s;
            }

            .kh-tab:hover {
                background: #fff;
                color: #FF8C42;
            }

            .kh-tab.active {
                background: #fff;
                color: #FF8C42;
                border-bottom-color: #FF8C42;
            }

            .kh-tab i {
                font-size: .75rem;
            }

            .kh-tab-panel {
                display: none;
            }

            .kh-tab-panel.active {
                display: block;
                animation: khFadeIn .22s ease;
            }

            @keyframes khFadeIn {
                from {
                    opacity: 0;
                    transform: translateY(5px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .kh-modal-body {
                padding: 1.2rem 1.4rem 1.4rem;
            }

            .kh-field {
                margin-bottom: 1rem;
            }

            .kh-field-label {
                display: block;
                font-size: .72rem;
                font-weight: 700;
                color: #374151;
                margin-bottom: .4rem;
                letter-spacing: .2px;
            }

            .kh-field-label i {
                color: #FF8C42;
                margin-right: .3rem;
                font-size: .65rem;
            }

            .kh-field-label span {
                color: #e74c3c;
            }

            .kh-field-input {
                width: 100%;
                box-sizing: border-box;
                padding: .68rem .9rem;
                border: 1.8px solid #e5e7eb;
                border-radius: 10px;
                font-size: .86rem;
                background: #f9fafb;
                color: #1f2937;
                outline: none;
                font-family: inherit;
                transition: border-color .2s, box-shadow .2s, background .2s;
            }

            .kh-field-input::placeholder {
                color: #b0b8c4;
            }

            .kh-field-input:focus {
                border-color: #FF8C42;
                background: #fff;
                box-shadow: 0 0 0 3px rgba(255, 140, 66, .1);
            }

            .kh-field-input.error {
                border-color: #e74c3c;
                background: #fff5f5;
                box-shadow: 0 0 0 3px rgba(231, 76, 60, .08);
            }

            .kh-field-err {
                font-size: .7rem;
                color: #e74c3c;
                margin-top: .3rem;
                display: flex;
                align-items: center;
                gap: .25rem;
                min-height: .9rem;
            }

            .kh-field-err i {
                font-size: .65rem;
            }

            .kh-pw-wrap {
                position: relative;
            }

            .kh-pw-wrap .kh-field-input {
                padding-right: 2.5rem;
            }

            .kh-pw-eye {
                position: absolute;
                right: .75rem;
                top: 50%;
                transform: translateY(-50%);
                background: none;
                border: none;
                cursor: pointer;
                color: #9ca3af;
                font-size: .85rem;
                padding: 0;
                transition: color .2s;
                line-height: 1;
            }

            .kh-pw-eye:hover {
                color: #FF8C42;
            }

            .kh-btn-submit {
                width: 100%;
                padding: .78rem;
                background: linear-gradient(135deg, #FF8C42, #FF5722);
                color: #fff;
                border: none;
                border-radius: 12px;
                font-size: .9rem;
                font-weight: 800;
                font-family: inherit;
                cursor: pointer;
                margin-top: 1rem;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: .5rem;
                box-shadow: 0 4px 14px rgba(255, 140, 66, .35);
                transition: all .2s;
            }

            .kh-btn-submit:hover:not(:disabled) {
                transform: translateY(-2px);
                box-shadow: 0 6px 20px rgba(255, 140, 66, .45);
            }

            .kh-btn-submit:disabled {
                opacity: .65;
                cursor: not-allowed;
                transform: none;
            }

            .kh-toast {
                position: fixed;
                bottom: 24px;
                right: 24px;
                z-index: 9999;
                background: #fff;
                border-radius: 14px;
                box-shadow: 0 8px 30px rgba(0, 0, 0, .15), 0 0 0 1px rgba(0, 0, 0, .05);
                padding: .9rem 1.2rem;
                min-width: 260px;
                max-width: 320px;
                display: flex;
                align-items: center;
                gap: .8rem;
                border-left: 4px solid #10b981;
                transform: translateY(20px) scale(.97);
                opacity: 0;
                transition: all .32s cubic-bezier(.19, 1, .22, 1);
                pointer-events: none;
            }

            .kh-toast.show {
                transform: translateY(0) scale(1);
                opacity: 1;
            }

            .kh-toast.error {
                border-left-color: #e74c3c;
            }

            .kh-toast-msg {
                font-size: .82rem;
                font-weight: 700;
                color: #1f2937;
                flex: 1;
            }

            @media (max-width: 480px) {
                .kh-modal-body {
                    padding: 1rem;
                }

                .kh-tab {
                    font-size: .75rem;
                }

                .kh-toast {
                    right: 12px;
                    bottom: 12px;
                    min-width: unset;
                    width: calc(100vw - 24px);
                }
            }
        </style>

    {{-- ════ JAVASCRIPT ĐƯỢC BỌC TRONG PUSH ════ --}}
    @push('scripts')
        <script>
            (function() {
                var CSRF = '{{ csrf_token() }}';
                var URL_TT = '{{ route('khach-hang.profile.update') }}';
                var URL_MK = '{{ route('khach-hang.change-password') }}';

                window.openModalHoSo = function(tab) {
                    document.getElementById('khModalBackdrop').classList.add('show');
                    document.getElementById('modalHoSo').classList.add('show');
                    document.body.style.overflow = 'hidden';
                    switchModalTab(tab || 'thong-tin');
                };

                window.closeModalHoSo = function() {
                    document.getElementById('khModalBackdrop').classList.remove('show');
                    document.getElementById('modalHoSo').classList.remove('show');
                    document.body.style.overflow = '';
                    _clearErrors();
                };

                window.switchModalTab = function(tab) {
                    var isTT = tab === 'thong-tin';
                    document.getElementById('tabThongTin').classList.toggle('active', isTT);
                    document.getElementById('tabMatKhau').classList.toggle('active', !isTT);
                    document.getElementById('tabBtnThongTin').classList.toggle('active', isTT);
                    document.getElementById('tabBtnMatKhau').classList.toggle('active', !isTT);
                    _clearErrors();
                };

                function _clearErrors() {
                    document.querySelectorAll('.kh-field-err').forEach(function(el) {
                        el.textContent = '';
                        el.style.display = '';
                    });
                    document.querySelectorAll('.kh-field-input').forEach(function(el) {
                        el.classList.remove('error');
                    });
                }

                function _showToast(msg, isError) {
                    var t = document.getElementById('khToast');
                    var icon = document.getElementById('khToastIcon');
                    icon.className = isError ? 'fas fa-times-circle' : 'fas fa-check-circle';
                    icon.style.color = isError ? '#e74c3c' : '#10b981';
                    t.querySelector('.kh-toast-msg').textContent = msg;
                    t.classList.toggle('error', !!isError);
                    t.classList.add('show');
                    clearTimeout(t._t);
                    t._t = setTimeout(function() {
                        t.classList.remove('show');
                    }, 3500);
                }

                function _showErrors(errors) {
                    Object.entries(errors).forEach(function(e) {
                        var errEl = document.getElementById('err_' + e[0]);
                        var inpEl = document.getElementById('f_' + e[0]);
                        if (errEl) {
                            errEl.innerHTML = '<i class="fas fa-exclamation-circle"></i> ' +
                                (Array.isArray(e[1]) ? e[1][0] : e[1]);
                        }
                        if (inpEl) inpEl.classList.add('error');
                    });
                }

                function _post(url, data, btn, origHTML, onSuccess) {
                    btn.disabled = true;
                    btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Đang xử lý...';
                    fetch(url, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': CSRF,
                            },
                            body: JSON.stringify(data),
                        })
                        .then(function(r) {
                            return r.json();
                        })
                        .then(function(res) {
                            if (res.success) {
                                onSuccess(res);
                            } else {
                                if (res.errors) _showErrors(res.errors);
                                if (res.message && !res.errors) _showToast(res.message, true);
                            }
                        })
                        .catch(function() {
                            _showToast('Có lỗi xảy ra, vui lòng thử lại.', true);
                        })
                        .finally(function() {
                            btn.disabled = false;
                            btn.innerHTML = origHTML;
                        });
                }

                window.submitThongTin = function(e) {
                    e.preventDefault();
                    _clearErrors();
                    var btn = document.getElementById('btnSubmitThongTin');
                    var orig = '<i class="fas fa-save"></i> Lưu thay đổi';
                    _post(URL_TT, {
                        ho_ten: document.getElementById('f_ho_ten').value.trim(),
                        so_dien_thoai: document.getElementById('f_so_dien_thoai').value.trim(),
                        email: document.getElementById('f_email').value.trim(),
                    }, btn, orig, function(res) {
                        _showToast(res.message, false);
                        closeModalHoSo();
                    });
                };

                window.submitMatKhau = function(e) {
                    e.preventDefault();
                    _clearErrors();
                    var pw1 = document.getElementById('f_mat_khau_moi').value;
                    var pw2 = document.getElementById('f_mat_khau_moi_confirmation').value;
                    if (pw1 !== pw2) {
                        var em = document.getElementById('err_match');
                        em.innerHTML = '<i class="fas fa-exclamation-circle"></i> Mật khẩu xác nhận không khớp';
                        em.style.display = 'flex';
                        document.getElementById('f_mat_khau_moi_confirmation').classList.add('error');
                        return;
                    }
                    var btn = document.getElementById('btnSubmitMk');
                    var orig = '<i class="fas fa-shield-alt"></i> Cập nhật mật khẩu';
                    _post(URL_MK, {
                        mat_khau_cu: document.getElementById('f_mat_khau_cu').value,
                        mat_khau_moi: pw1,
                        mat_khau_moi_confirmation: pw2,
                    }, btn, orig, function(res) {
                        _showToast(res.message, false);
                        document.getElementById('formMatKhau').reset();
                        document.getElementById('strengthBox').style.display = 'none';
                        closeModalHoSo();
                    });
                };

                window.toggleEye = function(id, btn) {
                    var inp = document.getElementById(id);
                    var ico = btn.querySelector('i');
                    var show = inp.type === 'password';
                    inp.type = show ? 'text' : 'password';
                    ico.className = show ? 'far fa-eye-slash' : 'far fa-eye';
                };

                window.renderStrength = function(val) {
                    var box = document.getElementById('strengthBox');
                    var bar = document.getElementById('strengthBar');
                    var txt = document.getElementById('strengthTxt');
                    if (!val) {
                        box.style.display = 'none';
                        return;
                    }
                    box.style.display = 'block';
                    var s = 0;
                    if (val.length >= 6) s++;
                    if (val.length >= 10) s++;
                    if (/[A-Z]/.test(val)) s++;
                    if (/[0-9]/.test(val)) s++;
                    if (/[^A-Za-z0-9]/.test(val)) s++;
                    var lvs = [{
                            w: '20%',
                            c: '#e74c3c',
                            l: 'Rất yếu'
                        },
                        {
                            w: '40%',
                            c: '#f97316',
                            l: 'Yếu'
                        },
                        {
                            w: '60%',
                            c: '#f59e0b',
                            l: 'Trung bình'
                        },
                        {
                            w: '80%',
                            c: '#10b981',
                            l: 'Mạnh'
                        },
                        {
                            w: '100%',
                            c: '#059669',
                            l: 'Rất mạnh'
                        },
                    ];
                    var lv = lvs[Math.min(s, 4)];
                    bar.style.width = lv.w;
                    bar.style.background = lv.c;
                    txt.textContent = lv.l;
                    txt.style.color = lv.c;
                };

                window.checkMatch = function() {
                    var pw1 = document.getElementById('f_mat_khau_moi').value;
                    var pw2 = document.getElementById('f_mat_khau_moi_confirmation').value;
                    var err = document.getElementById('err_match');
                    var inp = document.getElementById('f_mat_khau_moi_confirmation');
                    if (pw2 && pw1 !== pw2) {
                        err.innerHTML = '<i class="fas fa-exclamation-circle"></i> Mật khẩu xác nhận không khớp';
                        err.style.display = 'flex';
                        inp.classList.add('error');
                    } else {
                        err.style.display = 'none';
                        inp.classList.remove('error');
                    }
                };

                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') closeModalHoSo();
                });
            }());
        </script>
    @endpush
@endauth
