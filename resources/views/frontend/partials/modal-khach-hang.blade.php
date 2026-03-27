@auth('customer')
    @php $kh = Auth::guard('customer')->user(); @endphp
    <div class="kh-modal-backdrop" id="khModalBackdrop" onclick="closeModalHoSo()"></div>
    <div class="kh-modal" id="modalHoSo">
        <div class="kh-modal-header">
            <div class="kh-modal-icon bg-primary-brand"><i class="fas fa-user-cog"></i></div>
            <div>
                <div class="kh-modal-title">Quản lý tài khoản</div>
                <div class="kh-modal-sub">{{ $kh->ho_ten ?? '' }}</div>
            </div>
            <button class="kh-modal-close" onclick="closeModalHoSo()"><i class="fas fa-times"></i></button>
        </div>
        <div class="kh-tab-bar">
            <button class="kh-tab active" id="tabBtnThongTin" onclick="switchModalTab('thong-tin')">Thông tin</button>
            <button class="kh-tab" id="tabBtnMatKhau" onclick="switchModalTab('mat-khau')">Mật khẩu</button>
        </div>
        <div class="kh-modal-body">
            <div id="tabThongTin" class="kh-tab-panel active">
                <form onsubmit="submitThongTin(event)">
                    <div class="kh-field"><label class="kh-field-label">Họ tên</label><input type="text" id="f_ho_ten"
                            value="{{ $kh->ho_ten }}" class="kh-field-input">
                        <div class="kh-field-err" id="err_ho_ten"></div>
                    </div>
                    <div class="kh-field"><label class="kh-field-label">Điện thoại</label><input type="text"
                            id="f_so_dien_thoai" value="{{ $kh->so_dien_thoai }}" class="kh-field-input">
                        <div class="kh-field-err" id="err_so_dien_thoai"></div>
                    </div>
                    <div class="kh-field"><label class="kh-field-label">Email</label><input type="email" id="f_email"
                            value="{{ $kh->email }}" class="kh-field-input">
                        <div class="kh-field-err" id="err_email"></div>
                    </div>
                    <button type="submit" class="btn-primary-brand w-100 mt-2" id="btnSubmitThongTin">Lưu thay đổi</button>
                </form>
            </div>
            <div id="tabMatKhau" class="kh-tab-panel">
                <form onsubmit="submitMatKhau(event)">
                    <div class="kh-field"><label class="kh-field-label">MK Cũ</label><input type="password"
                            id="f_mat_khau_cu" class="kh-field-input">
                        <div class="kh-field-err" id="err_mat_khau_cu"></div>
                    </div>
                    <div class="kh-field"><label class="kh-field-label">MK Mới</label><input type="password"
                            id="f_mat_khau_moi" class="kh-field-input"></div>
                    <div class="kh-field"><label class="kh-field-label">Xác nhận</label><input type="password"
                            id="f_mat_khau_moi_confirmation" class="kh-field-input"></div>
                    <button type="submit" class="btn-primary-brand w-100 mt-2" id="btnSubmitMk">Cập nhật</button>
                </form>
            </div>
        </div>
    </div>
@endauth
