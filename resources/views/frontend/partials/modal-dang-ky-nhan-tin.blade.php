<div class="kh-modal-backdrop" id="backdropDangKyNhanTin" style="display: none; z-index: 9998;"
    onclick="closeModalDangKy()"></div>

<div class="kh-modal" id="modalDangKyNhanTin"
    style="display: none; max-width: 500px; width: calc(100% - 24px); max-height: 90vh; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 9999; overflow: hidden;">
    <div class="kh-modal-header">
        <div class="kh-modal-icon"
            style="background: linear-gradient(135deg, var(--primary), #deae48); box-shadow: 0 4px 10px rgba(192, 102, 42, 0.3);">
            <i class="fas fa-bell text-white"></i>
        </div>
        <div>
            <div class="kh-modal-title">Đăng Ký Nhận Thông Tin</div>
            <div class="kh-modal-sub" style="font-size: 0.8rem;">Nhận thông báo khi có căn hộ phù hợp tiêu chí</div>
        </div>
        <button class="kh-modal-close" onclick="closeModalDangKy()" title="Đóng cửa sổ"><i
                class="fas fa-times"></i></button>
    </div>

    <div class="kh-modal-body" style="padding: 1.5rem; overflow-y: auto; max-height: calc(90vh - 110px);">
        <form id="formDangKyNhanTin" onsubmit="submitDangKyNhanTin(event)">
            @csrf
            <input type="hidden" name="bat_dong_san_id" id="dk_bat_dong_san_id" value="">

            <div id="dk_price_alert_notice"
                style="display:none;margin-bottom:12px;padding:10px 12px;border:1px solid #fde68a;background:#fffbeb;color:#92400e;border-radius:10px;font-size:.82rem;font-weight:600;">
            </div>

            <div class="kh-field">
                <label class="kh-field-label"><i class="fas fa-envelope"></i> Email nhận thông báo
                    <span>*</span></label>
                <input type="email" name="email" id="dk_email" class="kh-field-input"
                    placeholder="Nhập địa chỉ email của bạn..." required
                    value="{{ auth('customer')->check() ? auth('customer')->user()->email : '' }}">
                <div class="kh-field-err" id="err_dk_email"></div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <div class="kh-field">
                        <label class="kh-field-label"><i class="fas fa-tag"></i> Nhu cầu</label>
                        <select name="nhu_cau" id="dk_nhu_cau" class="kh-field-input" style="cursor: pointer;">
                            <option value="ban">Mua Bất động sản</option>
                            <option value="thue">Thuê Bất động sản</option>
                        </select>
                        <div class="kh-field-err" id="err_dk_nhu_cau"></div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="kh-field">
                        <label class="kh-field-label"><i class="fas fa-map-marker-alt"></i> Khu vực</label>
                        <select name="khu_vuc_id" id="dk_khu_vuc_id" class="kh-field-input" style="cursor: pointer;">
                            <option value="">-- Tất cả khu vực --</option>
                            @foreach (\App\Models\KhuVuc::where('hien_thi', 1)->orderBy('ten_khu_vuc')->get() as $kv)
                                <option value="{{ $kv->id }}">{{ $kv->ten_khu_vuc }}</option>
                            @endforeach
                        </select>
                        <div class="kh-field-err" id="err_dk_khu_vuc_id"></div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <div class="kh-field">
                        <label class="kh-field-label"><i class="fas fa-bed"></i> Phòng ngủ</label>
                        <select name="so_phong_ngu" id="dk_so_phong_ngu" class="kh-field-input"
                            style="cursor: pointer;">
                            <option value="">-- Bất kỳ --</option>
                            <option value="studio">Căn Studio</option>
                            <option value="1">1 Phòng ngủ</option>
                            <option value="2">2 Phòng ngủ</option>
                            <option value="3">3 Phòng ngủ trở lên</option>
                        </select>
                        <div class="kh-field-err" id="err_dk_so_phong_ngu"></div>
                    </div>
                </div>
            </div>

            <div class="kh-field">
                <label class="kh-field-label"><i class="fas fa-building"></i> Dự án quan tâm (Tùy chọn)</label>
                <select name="du_an_id" id="dk_du_an_id" class="kh-field-input" style="cursor: pointer;">
                    <option value="">-- Tất cả dự án --</option>
                    @foreach (\App\Models\DuAn::where('hien_thi', 1)->orderBy('ten_du_an')->get() as $da)
                        <option value="{{ $da->id }}">{{ $da->ten_du_an }}</option>
                    @endforeach
                </select>
                <div class="kh-field-err" id="err_dk_du_an_id"></div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <div class="kh-field">
                        <label class="kh-field-label"><i class="fas fa-money-bill-wave"></i> Ngân sách từ (VNĐ)</label>
                        <input type="number" name="muc_gia_tu" id="dk_muc_gia_tu" class="kh-field-input"
                            placeholder="VD: 1000000000">
                        <div class="kh-field-err" id="err_dk_muc_gia_tu"></div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="kh-field">
                        <label class="kh-field-label"><i class="fas fa-money-bill-wave"></i> Đến mức giá</label>
                        <input type="number" name="muc_gia_den" id="dk_muc_gia_den" class="kh-field-input"
                            placeholder="VD: 3000000000">
                        <div class="kh-field-err" id="err_dk_muc_gia_den"></div>
                    </div>
                </div>
            </div>

            <div style="font-size: 0.75rem; color: #64748b; margin-bottom: 1rem; font-style: italic;">
                * Hệ thống sẽ tự động đối chiếu hàng ngày và gửi thông tin sớm nhất cho bạn nếu có căn hộ phù hợp với
                các tiêu chí trên.
            </div>

            <button type="submit" class="btn-primary-brand w-100" id="btnSubmitDangKy">
                <i class="fas fa-paper-plane me-1"></i> Đăng Ký Nhận Tin
            </button>
        </form>
    </div>
</div>

<script>
    // Hàm Mở Modal (có thể nhận thêm options cho trường hợp theo dõi 1 BĐS cụ thể)
    function openModalDangKy(email = '', options = {}) {
        const backdrop = document.getElementById('backdropDangKyNhanTin');
        const modal = document.getElementById('modalDangKyNhanTin');
        const emailInput = document.getElementById('dk_email');
        const bdsIdInput = document.getElementById('dk_bat_dong_san_id');
        const notice = document.getElementById('dk_price_alert_notice');
        const nhuCauInput = document.getElementById('dk_nhu_cau');
        const khuVucInput = document.getElementById('dk_khu_vuc_id');
        const duAnInput = document.getElementById('dk_du_an_id');

        if (email) emailInput.value = email;

        bdsIdInput.value = options.batDongSanId || '';

        if (options.nhuCau && nhuCauInput) {
            nhuCauInput.value = options.nhuCau;
        }

        if (options.khuVucId && khuVucInput) {
            khuVucInput.value = String(options.khuVucId);
        }

        if (options.duAnId && duAnInput) {
            duAnInput.value = String(options.duAnId);
        }

        if (options.batDongSanId) {
            const tieuDeBds = options.batDongSanTitle || 'Bất động sản này';
            notice.textContent = 'Bạn đang đăng ký nhận email khi giá của "' + tieuDeBds + '" thay đổi.';
            notice.style.display = 'block';
        } else {
            notice.textContent = '';
            notice.style.display = 'none';
        }

        // Bật display
        backdrop.style.display = 'block';
        modal.style.display = 'block';

        // Ép các thuộc tính CSS hiển thị
        setTimeout(() => {
            modal.style.opacity = '1';
            modal.style.visibility = 'visible';
            backdrop.style.opacity = '1';
            backdrop.style.visibility = 'visible';
            backdrop.classList.add('active');
            modal.classList.add('active');
        }, 10);

        clearDangKyErrors();
    }

    // Hàm Đóng Modal
    function closeModalDangKy() {
        const backdrop = document.getElementById('backdropDangKyNhanTin');
        const modal = document.getElementById('modalDangKyNhanTin');

        // Tắt CSS
        modal.style.opacity = '0';
        modal.style.visibility = 'hidden';
        backdrop.style.opacity = '0';
        backdrop.style.visibility = 'hidden';
        backdrop.classList.remove('active');
        modal.classList.remove('active');

        // Reset form sau khi hiệu ứng tắt kết thúc
        setTimeout(() => {
            backdrop.style.display = 'none';
            modal.style.display = 'none';
            document.getElementById('formDangKyNhanTin').reset();
            resetDangKyContext();
            clearDangKyErrors();
        }, 300);
    }

    function resetDangKyContext() {
        const bdsIdInput = document.getElementById('dk_bat_dong_san_id');
        const notice = document.getElementById('dk_price_alert_notice');

        if (bdsIdInput) {
            bdsIdInput.value = '';
        }

        if (notice) {
            notice.textContent = '';
            notice.style.display = 'none';
        }
    }

    function clearDangKyErrors() {
        const errorIds = [
            'err_dk_email',
            'err_dk_nhu_cau',
            'err_dk_khu_vuc_id',
            'err_dk_du_an_id',
            'err_dk_so_phong_ngu',
            'err_dk_muc_gia_tu',
            'err_dk_muc_gia_den',
        ];

        errorIds.forEach(id => {
            const el = document.getElementById(id);
            if (!el) return;
            el.innerText = '';
        });
    }

    function setDangKyFieldError(field, message) {
        const errorMap = {
            email: 'err_dk_email',
            nhu_cau: 'err_dk_nhu_cau',
            khu_vuc_id: 'err_dk_khu_vuc_id',
            du_an_id: 'err_dk_du_an_id',
            so_phong_ngu: 'err_dk_so_phong_ngu',
            muc_gia_tu: 'err_dk_muc_gia_tu',
            muc_gia_den: 'err_dk_muc_gia_den',
        };

        const id = errorMap[field];
        const el = document.getElementById(id);
        if (!el) return;

        el.innerText = message;
    }

    // Xử lý Gửi Form bằng AJAX
    async function submitDangKyNhanTin(e) {
        e.preventDefault();

        const btn = document.getElementById('btnSubmitDangKy');
        const form = document.getElementById('formDangKyNhanTin');
        const formData = new FormData(form);

        // Reset lỗi cũ
        clearDangKyErrors();

        // Hiệu ứng đang tải
        const originalBtnText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
        btn.disabled = true;

        try {
            const response = await fetch("{{ route('frontend.dang-ky-nhan-tin.store') }}", {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                }
            });

            const data = await response.json();

            if (response.ok) {
                closeModalDangKy();
                // SỬ DỤNG HỆ THỐNG THÔNG BÁO CHUNG CỦA BẠN (Từ frontend.js / flash-messages.blade.php)
                if (typeof showFlash === 'function') {
                    showFlash(data.message, 'success');
                } else {
                    alert(data.message);
                }
            } else {
                // Xử lý lỗi Validate (Báo đỏ ở dưới ô input)
                if (response.status === 422 && data.errors) {
                    let firstValidationMessage = '';

                    Object.keys(data.errors).forEach(field => {
                        const firstError = Array.isArray(data.errors[field]) ? data.errors[field][0] : data
                            .errors[
                                field
                            ];

                        if (!firstValidationMessage) {
                            firstValidationMessage = firstError;
                        }

                        setDangKyFieldError(field, firstError);
                    });

                    if (typeof showFlash === 'function') {
                        showFlash(firstValidationMessage || data.message ||
                            'Vui lòng kiểm tra lại thông tin đã nhập.',
                            'danger');
                    }
                } else {
                    // Hiển thị lỗi bằng showFlash
                    if (typeof showFlash === 'function') {
                        showFlash(data.message || 'Có lỗi xảy ra, vui lòng thử lại sau.', 'danger');
                    } else {
                        alert(data.message || 'Có lỗi xảy ra, vui lòng thử lại sau.');
                    }
                }
            }
        } catch (error) {
            console.error('Lỗi:', error);
            if (typeof showFlash === 'function') {
                showFlash('Không thể kết nối đến máy chủ. Vui lòng kiểm tra mạng!', 'danger');
            } else {
                alert('Không thể kết nối đến máy chủ. Vui lòng kiểm tra mạng!');
            }
        } finally {
            // Khôi phục nút
            btn.innerHTML = originalBtnText;
            btn.disabled = false;
        }
    }
</script>
