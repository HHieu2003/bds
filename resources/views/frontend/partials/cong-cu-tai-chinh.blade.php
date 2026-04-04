<div class="bds-finance-card">
    <h5><i class="fas fa-calculator"></i> Dự Toán Tài Chính</h5>

    @if ($bds->gia > 0)
        <div class="fin-tabs">
            <button class="fin-tab-btn active" data-target="tab-vay">
                <i class="fas fa-university me-1"></i> Vay Ngân Hàng
            </button>
            <button class="fin-tab-btn" data-target="tab-thue">
                <i class="fas fa-file-invoice-dollar me-1"></i> Thuế & Phí Phải Nộp
            </button>
        </div>

        <div id="tab-vay" class="fin-tab-content active">
            <div class="row g-4">
                <div class="col-md-7">
                    <div class="fin-form-group">
                        <label>Giá trị Bất động sản (VNĐ)</label>
                        <input type="text" class="fin-input fw-bold text-dark" id="fin_gia_tri"
                            value="{{ number_format($bds->gia, 0, ',', '.') }}" inputmode="numeric"
                            placeholder="Nhập giá trị BĐS">
                    </div>

                    <div class="fin-form-group">
                        <label class="d-flex justify-content-between">
                            <span>Mức vay ưu đãi: <strong id="fin_ty_le_text"
                                    style="color: #FF8C42;">50%</strong></span>
                            <span id="fin_so_tien_vay" class="fw-bold">0</span>
                        </label>
                        <input type="range" id="fin_ty_le" min="0" max="80" step="5" value="50"
                            class="fin-slider">
                    </div>

                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label class="fin-label">Ngân hàng</label>
                            <select id="fin_ngan_hang" class="fin-input">
                                @if (isset($nganHangs) && $nganHangs->count() > 0)
                                    @foreach ($nganHangs as $nh)
                                        <option value="{{ $nh->lai_suat_uu_dai }}">{{ $nh->ten_ngan_hang }}
                                            ({{ $nh->lai_suat_uu_dai }}%)
                                        </option>
                                    @endforeach
                                @else
                                    <option value="6.5">Ngân hàng Mặc định (6.5%)</option>
                                @endif
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label class="fin-label">Thời hạn</label>
                            <select id="fin_thoi_han" class="fin-input">
                                @for ($i = 5; $i <= 30; $i += 5)
                                    <option value="{{ $i }}" {{ $i == 20 ? 'selected' : '' }}>
                                        {{ $i }} năm ({{ $i * 12 }} tháng)</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="fin-result-box">
                        <div class="fin-result-title">Gốc + Lãi tháng đầu tiên</div>
                        <div class="fin-result-total" id="fin_tong_tra">0</div>

                        <div class="fin-result-breakdown">
                            <div class="breakdown-item">
                                <span><i class="fas fa-circle fa-xs me-1 text-secondary"></i>Tiền gốc:</span>
                                <strong id="fin_tien_goc">0</strong>
                            </div>
                            <div class="breakdown-item mt-2">
                                <span><i class="fas fa-circle fa-xs me-1" style="color: #FF8C42;"></i>Tiền lãi:</span>
                                <strong id="fin_tien_lai">0</strong>
                            </div>
                        </div>
                        <p class="fin-note">* Tính theo dư nợ giảm dần</p>
                    </div>
                </div>
            </div>
        </div>

        <div id="tab-thue" class="fin-tab-content" style="display: none;">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="tax-box">
                        <i class="fas fa-user-tag tax-icon"></i>
                        <div class="tax-label">Thuế TNCN (2%)</div>
                        <div class="tax-value" id="tax_tncn">0</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="tax-box">
                        <i class="fas fa-file-signature tax-icon"></i>
                        <div class="tax-label">Lệ phí trước bạ (0.5%)</div>
                        <div class="tax-value" id="tax_truoc_ba">0</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="tax-box">
                        <i class="fas fa-stamp tax-icon"></i>
                        <div class="tax-label">Công chứng (~0.15%)</div>
                        <div class="tax-value" id="tax_cong_chung">0</div>
                    </div>
                </div>
            </div>
            <div class="tax-total mt-3">
                <span>Tổng chi phí pháp lý ước tính:</span>
                <strong id="tax_tong">0 VNĐ</strong>
            </div>
        </div>
    @else
        <div class="text-center py-4">
            <div style="font-size: 3rem; color: #d1d5db; margin-bottom: 1rem;"><i class="fas fa-comments-dollar"></i>
            </div>
            <h6 class="fw-bold text-dark">Bất động sản này đang có giá Thỏa Thuận</h6>
            <p class="text-muted small">Vui lòng liên hệ chuyên viên tư vấn để nhận báo giá chi tiết và được hỗ trợ ước
                tính các khoản vay, thuế phí.</p>
        </div>
    @endif
</div>

@push('styles')
    <style>
        /* KHUNG BAO NGOÀI ĐỒNG BỘ VỚI WEBSITE */
        .bds-finance-card {
            background: #fff;
            border-radius: 16px;
            padding: 1.5rem 1.8rem;
            margin-bottom: 1.2rem;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .06);
        }

        .bds-finance-card h5 {
            font-size: .95rem;
            font-weight: 800;
            color: #0F172A;
            text-transform: uppercase;
            letter-spacing: .5px;
            margin-bottom: 1.2rem;
            padding-bottom: .8rem;
            border-bottom: 2px solid #f0e4da;
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        .bds-finance-card h5 i {
            color: #FF8C42;
        }

        /* NÚT TABS */
        .fin-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 1.5rem;
        }

        .fin-tab-btn {
            padding: 0.6rem 1.2rem;
            border-radius: 10px;
            border: 1.5px solid #e9ecef;
            background: #f8fafc;
            font-weight: 700;
            color: #6b7280;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
        }

        .fin-tab-btn:hover {
            background: #fff5ef;
            border-color: #fed7aa;
            color: #c2410c;
        }

        .fin-tab-btn.active {
            background: #FF8C42;
            border-color: #FF8C42;
            color: #fff;
            box-shadow: 0 4px 10px rgba(255, 140, 66, 0.3);
        }

        .fin-tab-btn.active i {
            color: #fff;
        }

        /* INPUTS & SLIDER */
        .fin-form-group {
            margin-bottom: 1.2rem;
        }

        .fin-form-group label,
        .fin-label {
            font-size: 0.85rem;
            font-weight: 700;
            color: #475569;
            margin-bottom: 0.4rem;
            display: block;
        }

        .fin-input {
            width: 100%;
            padding: 0.6rem 1rem;
            border: 1.5px solid #e9ecef;
            border-radius: 10px;
            font-size: 0.9rem;
            background: #f8fafc;
            color: #0F172A;
            transition: border-color 0.2s;
            outline: none;
        }

        .fin-input:focus {
            border-color: #FF8C42;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(255, 140, 66, .1);
        }

        .fin-slider {
            width: 100%;
            height: 6px;
            background: #e2e8f0;
            border-radius: 5px;
            outline: none;
            -webkit-appearance: none;
            margin-top: 10px;
        }

        .fin-slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #FF8C42;
            cursor: pointer;
            border: 3px solid #fff;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        }

        /* KẾT QUẢ VAY */
        .fin-result-box {
            background: linear-gradient(135deg, #fff8f3, #fff);
            border: 1.5px solid #f0e4da;
            border-radius: 14px;
            padding: 1.5rem;
            text-align: center;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .fin-result-title {
            font-size: 0.85rem;
            color: #6b7280;
            font-weight: 700;
            text-transform: uppercase;
        }

        .fin-result-total {
            font-size: 1.8rem;
            font-weight: 900;
            color: #FF5722;
            margin: 0.5rem 0 1rem 0;
            line-height: 1.2;
        }

        .fin-result-breakdown {
            text-align: left;
            background: #fff;
            padding: 1rem;
            border-radius: 10px;
            border: 1px dashed #e2e8f0;
        }

        .breakdown-item {
            display: flex;
            justify-content: space-between;
            font-size: 0.85rem;
            color: #475569;
        }

        .breakdown-item strong {
            color: #0F172A;
            font-weight: 800;
        }

        .fin-note {
            font-size: 0.7rem;
            color: #9ca3af;
            margin-top: 1rem;
            font-style: italic;
            margin-bottom: 0;
        }

        /* KẾT QUẢ THUẾ */
        .tax-box {
            background: #f8fafc;
            border: 1px solid #e9ecef;
            border-radius: 12px;
            padding: 1.2rem;
            text-align: center;
            transition: all 0.2s;
            height: 100%;
        }

        .tax-box:hover {
            border-color: #FF8C42;
            box-shadow: 0 4px 12px rgba(255, 140, 66, 0.1);
            transform: translateY(-3px);
        }

        .tax-icon {
            font-size: 1.8rem;
            color: #FF8C42;
            margin-bottom: 0.8rem;
            opacity: 0.8;
        }

        .tax-label {
            font-size: 0.75rem;
            color: #6b7280;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 0.3rem;
        }

        .tax-value {
            font-size: 1.1rem;
            font-weight: 900;
            color: #0F172A;
        }

        .tax-total {
            background: #fff5ef;
            border: 1.5px dashed #fed7aa;
            border-radius: 12px;
            padding: 1rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #c2410c;
            font-size: 0.95rem;
        }

        .tax-total strong {
            font-size: 1.3rem;
            font-weight: 900;
        }

        @media(max-width: 576px) {
            .fin-tabs {
                flex-direction: column;
            }

            .tax-total {
                flex-direction: column;
                gap: 0.5rem;
                text-align: center;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const giaTriInput = document.getElementById('fin_gia_tri');
            if (!giaTriInput) return; // Nếu BĐS giá thỏa thuận thì không chạy script này

            // Format tiền tệ
            const fm = (amount) => new Intl.NumberFormat('vi-VN').format(Math.round(amount)) + ' đ';
            const parseMoney = (val) => {
                const n = String(val || '').replace(/[^\d]/g, '');
                return n ? parseFloat(n) : 0;
            };
            const formatMoneyInput = (el) => {
                const value = parseMoney(el.value);
                el.value = value ? new Intl.NumberFormat('vi-VN').format(value) : '';
            };

            // Variables
            const slider = document.getElementById('fin_ty_le');
            const txtTyLe = document.getElementById('fin_ty_le_text');
            const txtVay = document.getElementById('fin_so_tien_vay');
            const selNganHang = document.getElementById('fin_ngan_hang');
            const selThoiHan = document.getElementById('fin_thoi_han');

            // Logic Vay
            function calcVay() {
                let giaTriBds = parseMoney(giaTriInput.value);
                if (giaTriBds <= 0) {
                    txtTyLe.innerText = slider.value + '%';
                    txtVay.innerText = fm(0);
                    document.getElementById('fin_tong_tra').innerText = fm(0);
                    document.getElementById('fin_tien_goc').innerText = fm(0);
                    document.getElementById('fin_tien_lai').innerText = fm(0);
                    return;
                }

                let tyLe = parseFloat(slider.value) / 100;
                let tienVay = giaTriBds * tyLe;
                let laiNam = parseFloat(selNganHang.value);
                let soThang = parseInt(selThoiHan.value) * 12;

                let gocThang = tienVay / soThang;
                let laiThang = tienVay * (laiNam / 100 / 12);

                txtTyLe.innerText = slider.value + '%';
                txtVay.innerText = fm(tienVay);

                document.getElementById('fin_tong_tra').innerText = fm(gocThang + laiThang);
                document.getElementById('fin_tien_goc').innerText = fm(gocThang);
                document.getElementById('fin_tien_lai').innerText = fm(laiThang);
            }

            // Logic Thuế
            function calcThue() {
                let giaTriBds = parseMoney(giaTriInput.value);
                if (giaTriBds <= 0) {
                    document.getElementById('tax_tncn').innerText = fm(0);
                    document.getElementById('tax_truoc_ba').innerText = fm(0);
                    document.getElementById('tax_cong_chung').innerText = fm(0);
                    document.getElementById('tax_tong').innerText = fm(0);
                    return;
                }

                let tncn = giaTriBds * 0.02;
                let tba = giaTriBds * 0.005;
                let cc = giaTriBds * 0.0015;

                document.getElementById('tax_tncn').innerText = fm(tncn);
                document.getElementById('tax_truoc_ba').innerText = fm(tba);
                document.getElementById('tax_cong_chung').innerText = fm(cc);
                document.getElementById('tax_tong').innerText = fm(tncn + tba + cc);
            }

            // Event Listeners
            slider.addEventListener('input', calcVay);
            selNganHang.addEventListener('change', calcVay);
            selThoiHan.addEventListener('change', calcVay);
            giaTriInput.addEventListener('input', function() {
                calcVay();
                calcThue();
            });
            giaTriInput.addEventListener('blur', function() {
                formatMoneyInput(giaTriInput);
                calcVay();
                calcThue();
            });

            // Chuyển Tabs
            document.querySelectorAll('.fin-tab-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.querySelectorAll('.fin-tab-btn').forEach(b => b.classList.remove(
                        'active'));
                    document.querySelectorAll('.fin-tab-content').forEach(c => c.style.display =
                        'none');

                    this.classList.add('active');
                    document.getElementById(this.dataset.target).style.display = 'block';
                });
            });

            // Run init
            formatMoneyInput(giaTriInput);
            calcVay();
            calcThue();
        });
    </script>
@endpush
