@extends('frontend.layouts.master')

@section('title', 'Công cụ Dự toán Tài chính — Thành Công Land')

@section('content')
    <div class="finance-page-wrapper">
        <div class="finance-hero">
            <div class="container text-center">
                <h1 class="finance-title">Dự Toán Tài Chính Bất Động Sản</h1>
                <p class="finance-subtitle">Chủ động tính toán khoản vay, lịch thanh toán hàng tháng và các loại thuế phí
                    sang tên một cách chính xác nhất.</p>
            </div>
        </div>

        <div class="container pb-5">
            <div class="row justify-content-center">
                <div class="col-xl-11">

                    <div class="bds-finance-card main-card hover-up">
                        <div class="fin-tabs">
                            <button class="fin-tab-btn active" data-target="tab-vay">
                                <i class="fas fa-university me-1"></i> Tính Khoản Vay
                            </button>
                            <button class="fin-tab-btn" data-target="tab-thue">
                                <i class="fas fa-file-invoice-dollar me-1"></i> Tính Thuế Phí Phải Nộp
                            </button>
                        </div>

                        <div id="tab-vay" class="fin-tab-content active">
                            <div class="row g-4">
                                <div class="col-lg-5">
                                    <div class="input-section">
                                        <h5 class="section-heading"><i class="fas fa-edit icon-muted me-2"></i>Thông tin
                                            khoản vay</h5>

                                        <div class="fin-form-group">
                                            <label>Giá trị Bất động sản (VNĐ)</label>
                                            <div class="input-group-custom">
                                                <input type="text" id="fin_gia_tri_nhap"
                                                    class="fin-input fw-900 text-primary-brand" value="2.000.000.000">
                                                <span class="unit">VNĐ</span>
                                            </div>
                                        </div>

                                        <div class="fin-form-group mt-4">
                                            <label class="d-flex justify-content-between align-items-center">
                                                <span>Tỷ lệ vay vốn: <strong id="fin_ty_le_text"
                                                        class="text-primary-brand">50%</strong></span>
                                            </label>
                                            <input type="range" id="fin_ty_le" min="0" max="80"
                                                step="5" value="50" class="fin-slider mt-2">
                                            <div class="d-flex justify-content-between text-muted"
                                                style="font-size: 0.75rem; margin-top: 5px;">
                                                <span>0%</span>
                                                <span>Tối đa 80%</span>
                                            </div>
                                        </div>

                                        <div class="fin-form-group mt-3">
                                            <label>Ngân hàng hỗ trợ</label>
                                            <select id="fin_ngan_hang" class="fin-input">
                                                @if (isset($nganHangs) && $nganHangs->count() > 0)
                                                    @foreach ($nganHangs as $nh)
                                                        <option value="{{ $nh->lai_suat_uu_dai }}">{{ $nh->ten_ngan_hang }}
                                                            (Lãi suất ưu đãi: {{ $nh->lai_suat_uu_dai }}%/năm)
                                                        </option>
                                                    @endforeach
                                                @else
                                                    <option value="6.5">Ngân hàng Mặc định (Lãi suất: 6.5%/năm)</option>
                                                @endif
                                            </select>
                                        </div>

                                        <div class="fin-form-group mt-3">
                                            <label>Thời hạn vay</label>
                                            <select id="fin_thoi_han" class="fin-input">
                                                @for ($i = 1; $i <= 30; $i++)
                                                    <option value="{{ $i }}" {{ $i == 20 ? 'selected' : '' }}>
                                                        {{ $i }} năm ({{ $i * 12 }} tháng)</option>
                                                @endfor
                                            </select>
                                        </div>

                                        <div class="alert-info-box mt-4">
                                            <i class="fas fa-info-circle text-primary-brand"></i> Bảng tính áp dụng phương
                                            pháp <strong>dư nợ giảm dần</strong> (tiền gốc trả đều hàng tháng, tiền lãi giảm
                                            dần theo dư nợ thực tế).
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-7">
                                    <div class="result-section">
                                        <h5 class="section-heading text-white"><i class="fas fa-chart-pie me-2"
                                                style="color: #deae48;"></i>Tóm tắt chi phí</h5>

                                        <div class="summary-grid">
                                            <div class="summary-box highlight">
                                                <div class="lbl">Số tiền cần vay</div>
                                                <div class="val" id="fin_so_tien_vay">0 đ</div>
                                            </div>
                                            <div class="summary-box">
                                                <div class="lbl">Vốn tự có cần chuẩn bị</div>
                                                <div class="val" id="fin_von_tu_co">0 đ</div>
                                            </div>
                                        </div>

                                        <div class="main-payment-box mt-3">
                                            <div class="lbl">Gốc + Lãi tháng đầu tiên</div>
                                            <div class="val price-gradient" id="fin_tong_tra">0 đ</div>
                                            <div class="breakdown">
                                                <span>Gốc: <b id="fin_tien_goc">0 đ</b></span>
                                                <span class="mx-2" style="color: var(--border);">|</span>
                                                <span>Lãi: <b id="fin_tien_lai" class="text-primary-brand">0 đ</b></span>
                                            </div>
                                        </div>

                                        <div class="summary-grid mt-3">
                                            <div class="summary-box warning-box">
                                                <div class="lbl">Tổng tiền lãi phải trả (Ước tính suốt kỳ hạn)</div>
                                                <div class="val text-danger" id="fin_tong_lai_phai_tra">0 đ</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="schedule-section mt-5">
                                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                                    <h4 class="schedule-title"><i
                                            class="fas fa-calendar-check text-primary-brand me-2"></i>Chi tiết lịch thanh
                                        toán</h4>
                                    <span class="badge-scroll"><i class="fas fa-mouse me-1"></i> Cuộn để xem các tháng tiếp
                                        theo</span>
                                </div>

                                <div class="table-responsive schedule-table-wrap">
                                    <table class="table table-hover schedule-table mb-0">
                                        <thead>
                                            <tr>
                                                <th class="text-center" width="10%">Kỳ (Tháng)</th>
                                                <th width="22.5%">Dư nợ đầu kỳ</th>
                                                <th width="22.5%">Tiền gốc</th>
                                                <th width="22.5%">Tiền lãi</th>
                                                <th width="22.5%" class="text-primary-brand">Tổng phải trả</th>
                                            </tr>
                                        </thead>
                                        <tbody id="schedule_body">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div id="tab-thue" class="fin-tab-content" style="display: none;">
                            <div class="row g-4 align-items-center">
                                <div class="col-lg-5">
                                    <div class="input-section h-100">
                                        <h5 class="section-heading"><i class="fas fa-calculator icon-muted me-2"></i>Xác
                                            định Giá trị</h5>
                                        <p class="text-muted small mb-4">Vui lòng nhập giá trị Bất động sản giao dịch thực
                                            tế để ước tính thuế phí nhà nước quy định.</p>

                                        <div class="fin-form-group">
                                            <label>Giá trị giao dịch (VNĐ)</label>
                                            <div class="input-group-custom">
                                                <input type="text" id="tax_gia_tri_nhap"
                                                    class="fin-input fw-900 text-primary-brand" value="2.000.000.000">
                                                <span class="unit">VNĐ</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-7">
                                    <div class="row g-3">
                                        <div class="col-sm-6">
                                            <div class="tax-box">
                                                <div class="tax-icon-wrap"><i class="fas fa-user-tag tax-icon"></i></div>
                                                <div class="tax-label">Thuế TNCN (2%)</div>
                                                <div class="tax-value text-primary-brand" id="tax_tncn">0 đ</div>
                                                <div class="tax-note">Người bán thường đóng</div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="tax-box">
                                                <div class="tax-icon-wrap"><i class="fas fa-file-signature tax-icon"></i>
                                                </div>
                                                <div class="tax-label">Lệ phí trước bạ (0.5%)</div>
                                                <div class="tax-value text-primary-brand" id="tax_truoc_ba">0 đ</div>
                                                <div class="tax-note">Người mua thường đóng</div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div
                                                class="tax-box d-flex justify-content-between align-items-center text-start">
                                                <div class="d-flex align-items-center gap-3">
                                                    <div class="tax-icon-wrap bg-transparent"><i
                                                            class="fas fa-stamp tax-icon"></i></div>
                                                    <div>
                                                        <div class="tax-label mb-1">Phí thẩm định, công chứng (~0.15%)
                                                        </div>
                                                        <div class="tax-note m-0">Lệ phí phòng công chứng và phí địa chính
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tax-value text-primary-brand m-0" id="tax_cong_chung">0 đ
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tax-total mt-4">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="fas fa-check-circle"></i>
                                            <span>Tổng chi phí pháp lý ước tính:</span>
                                        </div>
                                        <strong id="tax_tong">0 VNĐ</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .finance-page-wrapper {
                background: var(--bg-main);
                min-height: 100vh;
            }

            /* HERO BANNER - Dùng nền Nâu Than sang trọng */
            .finance-hero {
                background: #c58e38;
                background-image: radial-gradient(circle at 50% 0%, rgba(192, 102, 42, 0.15) 0%, transparent 70%);
                padding: 35px 0 90px;
                color: var(--text-light);
                margin-bottom: -50px;
            }

            .finance-title {
                font-size: 2.4rem;
                font-weight: 900;
                margin-bottom: 15px;
                color: var(--text-light);
                text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
            }

            .finance-subtitle {
                font-size: 1.1rem;
                opacity: 0.85;
                max-width: 700px;
                margin: 0 auto;
            }

            /* MAIN CARD */
            .main-card {
                background: #fff;
                border-radius: 24px;
                padding: 35px;
                border: 1px solid var(--border);
            }

            /* TABS NAV */
            .fin-tabs {
                display: flex;
                gap: 15px;
                border-bottom: 2px solid var(--border);
                padding-bottom: 25px;
                margin-bottom: 35px;
            }

            .fin-tab-btn {
                flex: 1;
                padding: 14px 20px;
                border-radius: 12px;
                border: 2px solid transparent;
                background: var(--bg-alt);
                font-weight: 700;
                color: var(--text-muted);
                font-size: 1.05rem;
                cursor: pointer;
                transition: var(--transition);
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .fin-tab-btn:hover {
                color: var(--primary);
                background: var(--primary-light);
            }

            .fin-tab-btn.active {
                background: var(--primary-light);
                border-color: var(--primary);
                color: var(--primary);
            }

            @media(max-width: 768px) {
                .fin-tabs {
                    flex-direction: column;
                }
            }

            /* INPUT SECTION */
            .input-section {
                padding-right: 15px;
            }

            .section-heading {
                font-size: 1.15rem;
                font-weight: 800;
                color: var(--secondary);
                text-transform: uppercase;
                margin-bottom: 25px;
                letter-spacing: 0.5px;
            }

            .fin-form-group label {
                font-size: 0.95rem;
                font-weight: 700;
                color: var(--text-heading);
                margin-bottom: 10px;
                display: block;
            }

            .input-group-custom {
                position: relative;
            }

            .input-group-custom .fin-input {
                padding-right: 60px;
                font-size: 1.2rem;
            }

            .input-group-custom .unit {
                position: absolute;
                right: 16px;
                top: 50%;
                transform: translateY(-50%);
                color: var(--text-muted);
                font-weight: 800;
                font-size: 0.9rem;
            }

            .fin-input {
                width: 100%;
                padding: 14px 18px;
                border: 2px solid var(--border);
                border-radius: 12px;
                font-size: 1rem;
                background: var(--bg-main);
                color: var(--text-heading);
                transition: var(--transition);
                outline: none;
                font-family: inherit;
            }

            .fin-input:focus {
                border-color: var(--primary);
                box-shadow: 0 0 0 4px var(--primary-light);
            }

            .fin-slider {
                width: 100%;
                height: 8px;
                background: var(--border);
                border-radius: 10px;
                outline: none;
                -webkit-appearance: none;
            }

            .fin-slider::-webkit-slider-thumb {
                -webkit-appearance: none;
                width: 26px;
                height: 26px;
                border-radius: 50%;
                background: var(--primary);
                cursor: pointer;
                border: 4px solid #fff;
                box-shadow: var(--shadow-sm);
                transition: transform 0.2s;
            }

            .fin-slider::-webkit-slider-thumb:hover {
                transform: scale(1.15);
            }

            .alert-info-box {
                background: var(--primary-light);
                border-left: 4px solid var(--primary);
                padding: 16px;
                border-radius: 8px;
                font-size: 0.85rem;
                color: var(--primary-dark);
                line-height: 1.6;
            }

            /* RESULT SECTION - Dùng màu Navy (Secondary) chuẩn Luxury */
            .result-section {
                background: var(--primary);
                background-image: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);
                border-radius: 20px;
                padding: 35px;
                height: 100%;
                color: var(--text-light);
                box-shadow: var(--shadow-lg);
            }

            .summary-grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 15px;
            }

            .summary-box {
                background: rgba(255, 255, 255, 0.06);
                border: 1px solid rgba(255, 255, 255, 0.1);
                padding: 18px;
                border-radius: 14px;
            }

            .summary-box.highlight {
                background: rgba(192, 102, 42, 0.15);
                /* Primary rgba */
                border-color: rgba(192, 102, 42, 0.4);
            }

            .summary-box.warning-box {
                grid-column: 1 / 3;
                background: rgba(198, 40, 40, 0.15);
                /* Danger rgba */
                border-color: rgba(198, 40, 40, 0.3);
            }

            .summary-box .lbl {
                font-size: 0.85rem;
                color: rgba(255, 255, 255, 0.7);
                margin-bottom: 6px;
            }

            .summary-box .val {
                font-size: 1.3rem;
                font-weight: 800;
                color: #fff;
            }

            .main-payment-box {
                background: var(--bg-main);
                padding: 28px;
                border-radius: 16px;
                text-align: center;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            }

            .main-payment-box .lbl {
                font-size: 0.95rem;
                color: var(--text-muted);
                font-weight: 800;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }

            /* Gradient chữ vàng Gold cho Số tiền đóng hàng tháng */
            .price-gradient {
                font-size: 2.4rem;
                font-weight: 900;
                margin: 8px 0 12px;
                line-height: 1.1;
                background: linear-gradient(135deg, #deae48, var(--primary));
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
            }

            .main-payment-box .breakdown {
                font-size: 0.95rem;
                color: var(--text-body);
            }

            .main-payment-box .breakdown b {
                color: var(--text-heading);
                font-weight: 800;
            }

            /* TABLE THỐNG KÊ (AMORTIZATION) */
            .schedule-section {
                border-top: 2px dashed var(--border);
                padding-top: 35px;
            }

            .schedule-title {
                font-size: 1.35rem;
                font-weight: 800;
                color: var(--secondary);
                margin: 0;
            }

            .badge-scroll {
                background: var(--bg-alt);
                color: var(--text-muted);
                border: 1px solid var(--border);
                padding: 6px 12px;
                border-radius: 20px;
                font-size: 0.8rem;
                font-weight: 600;
            }

            .schedule-table-wrap {
                max-height: 500px;
                overflow-y: auto;
                border: 1px solid var(--border);
                border-radius: 16px;
                background: var(--bg-main);
            }

            .schedule-table thead th {
                position: sticky;
                top: 0;
                background: var(--bg-alt);
                color: var(--secondary);
                font-weight: 800;
                text-transform: uppercase;
                font-size: 0.85rem;
                border-bottom: 2px solid var(--border);
                padding: 16px;
                z-index: 10;
                letter-spacing: 0.5px;
            }

            .schedule-table tbody td {
                padding: 14px 16px;
                vertical-align: middle;
                font-size: 0.95rem;
                color: var(--text-body);
                border-bottom: 1px solid var(--bg-alt);
            }

            .schedule-table tbody tr:hover {
                background-color: var(--bg-alt);
            }

            /* TAX SECTION */
            .tax-box {
                background: var(--bg-main);
                border: 1px solid var(--border);
                border-radius: 16px;
                padding: 24px;
                text-align: center;
                height: 100%;
                transition: var(--transition);
            }

            .tax-box:hover {
                border-color: var(--primary);
                box-shadow: var(--shadow-gold);
                transform: translateY(-4px);
            }

            .tax-icon-wrap {
                width: 50px;
                height: 50px;
                background: var(--primary-light);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 15px;
            }

            .tax-icon {
                font-size: 1.5rem;
                color: var(--primary);
            }

            .tax-label {
                font-size: 0.9rem;
                color: var(--text-heading);
                font-weight: 800;
                margin-bottom: 5px;
            }

            .tax-value {
                font-size: 1.4rem;
                font-weight: 900;
                margin-bottom: 5px;
            }

            .tax-note {
                font-size: 0.8rem;
                color: var(--text-muted);
                font-style: italic;
            }

            .tax-total {
                background: var(--primary-light);
                border: 2px dashed var(--primary);
                border-radius: 16px;
                padding: 22px 25px;
                display: flex;
                justify-content: space-between;
                align-items: center;
                color: var(--primary-dark);
                font-size: 1.15rem;
                font-weight: 700;
            }

            .tax-total strong {
                font-size: 1.8rem;
                font-weight: 900;
            }

            @media(max-width: 576px) {
                .tax-total {
                    flex-direction: column;
                    gap: 10px;
                    text-align: center;
                }

                .summary-grid {
                    grid-template-columns: 1fr;
                }

                .summary-box.warning-box {
                    grid-column: 1 / 2;
                }
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const fm = (amount) => new Intl.NumberFormat('vi-VN').format(Math.round(amount)) + ' đ';

                const inputGiaTri = document.getElementById('fin_gia_tri_nhap');
                const slider = document.getElementById('fin_ty_le');
                const txtTyLe = document.getElementById('fin_ty_le_text');
                const selNganHang = document.getElementById('fin_ngan_hang');
                const selThoiHan = document.getElementById('fin_thoi_han');

                const elTienVay = document.getElementById('fin_so_tien_vay');
                const elVonTuCo = document.getElementById('fin_von_tu_co');
                const elTongTraDau = document.getElementById('fin_tong_tra');
                const elGocDau = document.getElementById('fin_tien_goc');
                const elLaiDau = document.getElementById('fin_tien_lai');
                const elTongLai = document.getElementById('fin_tong_lai_phai_tra');
                const tbodySchedule = document.getElementById('schedule_body');

                const inputTaxGiaTri = document.getElementById('tax_gia_tri_nhap');

                let giaTriBds = 2000000000;

                if (inputGiaTri) {
                    inputGiaTri.addEventListener('input', function(e) {
                        let val = this.value.replace(/\D/g, '');
                        if (!val) val = 0;
                        giaTriBds = parseFloat(val);
                        this.value = new Intl.NumberFormat('vi-VN').format(val);
                        if (inputTaxGiaTri) inputTaxGiaTri.value = this.value;
                        calcVay();
                        calcThue();
                    });
                }

                if (inputTaxGiaTri) {
                    inputTaxGiaTri.addEventListener('input', function(e) {
                        let val = this.value.replace(/\D/g, '');
                        if (!val) val = 0;
                        giaTriBds = parseFloat(val);
                        this.value = new Intl.NumberFormat('vi-VN').format(val);
                        if (inputGiaTri) inputGiaTri.value = this.value;
                        calcVay();
                        calcThue();
                    });
                }

                function calcVay() {
                    if (!slider || !selNganHang || !selThoiHan || !tbodySchedule) return;

                    let tyLe = parseFloat(slider.value) / 100;
                    let tienVay = giaTriBds * tyLe;
                    let vonTuCo = giaTriBds - tienVay;

                    let laiNam = parseFloat(selNganHang.value);
                    let laiThang = laiNam / 100 / 12;
                    let soThang = parseInt(selThoiHan.value) * 12;

                    txtTyLe.innerText = slider.value + '%';
                    elTienVay.innerText = fm(tienVay);
                    elVonTuCo.innerText = fm(vonTuCo);

                    if (tienVay <= 0 || soThang <= 0) {
                        elTongTraDau.innerText = '0 đ';
                        elGocDau.innerText = '0 đ';
                        elLaiDau.innerText = '0 đ';
                        elTongLai.innerText = '0 đ';
                        tbodySchedule.innerHTML =
                            '<tr><td colspan="5" class="text-center text-muted py-5">Vui lòng điều chỉnh Tỷ lệ vay vốn để xem lịch trình thanh toán.</td></tr>';
                        return;
                    }

                    let gocHangThang = tienVay / soThang;
                    let duNoHienTai = tienVay;
                    let tongLaiPhaiTra = 0;
                    let htmlTable = '';

                    for (let i = 1; i <= soThang; i++) {
                        let laiKyNay = duNoHienTai * laiThang;
                        let tongDongKyNay = gocHangThang + laiKyNay;

                        tongLaiPhaiTra += laiKyNay;

                        if (i === 1) {
                            elTongTraDau.innerText = fm(tongDongKyNay);
                            elGocDau.innerText = fm(gocHangThang);
                            elLaiDau.innerText = fm(laiKyNay);
                        }

                        htmlTable += `
                    <tr>
                        <td class="text-center fw-bold text-muted">${i}</td>
                        <td class="fw-600">${fm(duNoHienTai)}</td>
                        <td>${fm(gocHangThang)}</td>
                        <td>${fm(laiKyNay)}</td>
                        <td class="fw-800 text-primary-brand">${fm(tongDongKyNay)}</td>
                    </tr>
                `;

                        duNoHienTai -= gocHangThang;
                        if (duNoHienTai < 0) duNoHienTai = 0;
                    }

                    tbodySchedule.innerHTML = htmlTable;
                    elTongLai.innerText = fm(tongLaiPhaiTra);
                }

                function calcThue() {
                    let tncn = giaTriBds * 0.02;
                    let tba = giaTriBds * 0.005;
                    let cc = giaTriBds * 0.0015;

                    let elTncn = document.getElementById('tax_tncn');
                    let elTba = document.getElementById('tax_truoc_ba');
                    let elCc = document.getElementById('tax_cong_chung');
                    let elTong = document.getElementById('tax_tong');

                    if (elTncn) elTncn.innerText = fm(tncn);
                    if (elTba) elTba.innerText = fm(tba);
                    if (elCc) elCc.innerText = fm(cc);
                    if (elTong) elTong.innerText = fm(tncn + tba + cc);
                }

                if (slider) slider.addEventListener('input', calcVay);
                if (selNganHang) selNganHang.addEventListener('change', calcVay);
                if (selThoiHan) selThoiHan.addEventListener('change', calcVay);

                document.querySelectorAll('.fin-tab-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        document.querySelectorAll('.fin-tab-btn').forEach(b => b.classList.remove(
                            'active'));
                        document.querySelectorAll('.fin-tab-content').forEach(c => c.style.display =
                            'none');

                        this.classList.add('active');
                        let targetId = this.getAttribute('data-target');
                        let targetEl = document.getElementById(targetId);
                        if (targetEl) targetEl.style.display = 'block';
                    });
                });

                calcVay();
                calcThue();
            });
        </script>
    @endpush
@endsection
