@auth('customer')
    @php $kh = Auth::guard('customer')->user(); @endphp

    <div class="kh-modal-backdrop" id="khModalBackdrop" onclick="closeModalHoSo()"></div>

    <div class="kh-modal" id="modalHoSo">
        <div class="kh-modal-header">
            <div class="kh-modal-icon"
                style="background: linear-gradient(135deg, #deae48, var(--primary)); box-shadow: 0 4px 10px rgba(192, 102, 42, 0.3);">
                <i class="fas fa-user-edit"></i>
            </div>
            <div>
                <div class="kh-modal-title">Quản lý tài khoản</div>
                <div class="kh-modal-sub">
                    <i class="fas fa-id-badge text-primary-brand me-1"></i>{{ $kh->ho_ten ?? 'Khách hàng' }}
                </div>
            </div>
            <button class="kh-modal-close" onclick="closeModalHoSo()" title="Đóng cửa sổ"><i
                    class="fas fa-times"></i></button>
        </div>

        <div class="kh-tab-bar">
            <button class="kh-tab active" id="tabBtnThongTin" onclick="switchModalTab('thong-tin')">
                <i class="fas fa-info-circle"></i> Thông tin cá nhân
            </button>
            <button class="kh-tab" id="tabBtnMatKhau" onclick="switchModalTab('mat-khau')">
                <i class="fas fa-shield-alt"></i> Đổi mật khẩu
            </button>
        </div>

        <div class="kh-modal-body" style="padding: 1.5rem;">

            <div id="tabThongTin" class="kh-tab-panel active">
                <form onsubmit="submitThongTin(event)">
                    <div class="kh-field">
                        <label class="kh-field-label"><i class="fas fa-user"></i>Họ và tên <span>*</span></label>
                        <input type="text" id="f_ho_ten" value="{{ $kh->ho_ten }}" class="kh-field-input"
                            placeholder="Nhập họ và tên của bạn...">
                        <div class="kh-field-err" id="err_ho_ten"></div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="kh-field">
                                <label class="kh-field-label"><i class="fas fa-phone-alt"></i>Điện thoại
                                    <span>*</span></label>
                                <input type="text" id="f_so_dien_thoai" value="{{ $kh->so_dien_thoai }}"
                                    class="kh-field-input" placeholder="09xx...">
                                <div class="kh-field-err" id="err_so_dien_thoai"></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="kh-field">
                                <label class="kh-field-label"><i class="fas fa-envelope"></i>Email liên hệ</label>
                                <input type="email" id="f_email" value="{{ $kh->email }}" class="kh-field-input"
                                    placeholder="email@example.com" readonly>
                                <div class="kh-field-err" id="err_email"></div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn-primary-brand w-100 mt-3" id="btnSubmitThongTin">
                        <i class="fas fa-save"></i> Lưu Thay Đổi
                    </button>
                </form>
            </div>

            <div id="tabMatKhau" class="kh-tab-panel">
                <form id="formMatKhau" onsubmit="submitMatKhau(event)">
                    <div class="kh-field">
                        <label class="kh-field-label"><i class="fas fa-lock"></i>Mật khẩu hiện tại <span>*</span></label>
                        <div class="kh-pw-wrap">
                            <input type="password" id="f_mat_khau_cu" class="kh-field-input"
                                placeholder="Nhập mật khẩu cũ...">
                            <button type="button" class="kh-pw-eye" onclick="togglePasswordHoSo('f_mat_khau_cu', this)">
                                <i class="far fa-eye"></i>
                            </button>
                        </div>
                        <div class="kh-field-err" id="err_mat_khau_cu"></div>
                    </div>

                    <div class="kh-field">
                        <label class="kh-field-label"><i class="fas fa-key"></i>Mật khẩu mới <span>*</span></label>
                        <div class="kh-pw-wrap">
                            <input type="password" id="f_mat_khau_moi" class="kh-field-input"
                                placeholder="Mật khẩu từ 6 ký tự trở lên...">
                            <button type="button" class="kh-pw-eye" onclick="togglePasswordHoSo('f_mat_khau_moi', this)">
                                <i class="far fa-eye"></i>
                            </button>
                        </div>
                        <div class="kh-field-err" id="err_mat_khau_moi"></div>
                    </div>

                    <div class="kh-field">
                        <label class="kh-field-label"><i class="fas fa-check-circle"></i>Xác nhận mật khẩu
                            <span>*</span></label>
                        <div class="kh-pw-wrap">
                            <input type="password" id="f_mat_khau_moi_confirmation" class="kh-field-input"
                                placeholder="Nhập lại mật khẩu mới...">
                            <button type="button" class="kh-pw-eye"
                                onclick="togglePasswordHoSo('f_mat_khau_moi_confirmation', this)">
                                <i class="far fa-eye"></i>
                            </button>
                        </div>
                        <div class="kh-field-err" id="err_mat_khau_moi_confirmation"></div>
                    </div>

                    <button type="submit" class="btn-primary-brand w-100 mt-3" id="btnSubmitMk">
                        <i class="fas fa-sync-alt"></i> Cập Nhật Mật Khẩu
                    </button>
                </form>
            </div>

        </div>
    </div>

    <script>
        function togglePasswordHoSo(inputId, btnElement) {
            const input = document.getElementById(inputId);
            const icon = btnElement.querySelector('i');

            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
                icon.style.color = 'var(--primary)'; // Đổi sang màu cam khi hiện MK
            } else {
                input.type = "password";
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
                icon.style.color = 'var(--text-muted)';
            }
        }
    </script>
@endauth
