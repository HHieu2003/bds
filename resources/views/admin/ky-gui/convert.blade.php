@extends('admin.layouts.master')
@section('title', 'Duyệt và Chuyển Đổi Ký Gửi')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    <style>
        .select2-container .select2-selection--single {
            height: calc(2.25rem + 2px);
            padding: .375rem .75rem;
            display: flex;
            align-items: center;
        }
        .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
            padding-left: 0;
        }
    </style>
@endpush

@section('content')
    <div class="mb-4">
        <a href="{{ route('nhanvien.admin.ky-gui.index') }}" class="btn btn-sm btn-light border mb-3">
            <i class="fas fa-arrow-left"></i> Quay lại danh sách
        </a>
        <h1 class="page-header-title text-success"><i class="fas fa-magic me-2"></i> Xác nhận Hợp Lệ & Đưa Vào Kho</h1>
        <p class="text-muted">Hệ thống đã tự động điền các thông tin từ form Ký gửi. Vui lòng kiểm tra lại với Chủ nhà và bổ
            sung thông tin trước khi Lưu BĐS.</p>
    </div>

    <form id="kgAdminForm" action="{{ route('nhanvien.admin.ky-gui.duyet', $kyGui->id) }}" method="POST">
        @csrf
        <div class="row g-4">

            <div class="col-12 col-xl-8">
                <div class="card border-0 shadow-sm border-top border-4 border-success mb-4">
                    <div class="card-header bg-white fw-bold py-3">
                        <i class="fas fa-home text-success me-2"></i>Thông tin Bất Động Sản (Lưu vào kho)
                    </div>
                    <div class="card-body p-4">

                        <div class="mb-4">
                            <label class="form-label">Tiêu đề tin đăng <span class="text-danger">*</span></label>
                            @php
                                $prefix = $kyGui->nhu_cau === 'ban' ? 'Bán' : 'Cho thuê';
                                $loaiHinhLabel = str_replace('_', ' ', ucfirst($kyGui->loai_hinh));
                                $defaultTieuDe = $prefix . ' ' . $loaiHinhLabel . ($kyGui->dia_chi ? ' - ' . $kyGui->dia_chi : '');
                            @endphp
                            <input type="text" name="tieu_de" class="form-control form-control-lg text-primary fw-bold"
                                value="{{ old('tieu_de', $defaultTieuDe) }}"
                                required>
                        </div>

                        <div class="row g-4 mb-4">
                            <div class="col-md-3">
                                <label class="form-label">Nhu cầu <span class="text-danger">*</span></label>
                                <select name="nhu_cau" class="form-select border-danger fw-bold text-danger">
                                    <option value="ban" {{ $kyGui->nhu_cau == 'ban' ? 'selected' : '' }}>Bán</option>
                                    <option value="thue" {{ $kyGui->nhu_cau == 'thue' ? 'selected' : '' }}>Cho Thuê
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Loại hình <span class="text-danger">*</span></label>
                                <select name="loai_hinh" class="form-select">
                                    @foreach (\App\Models\KyGui::LOAI_HINH as $k => $v)
                                        <option value="{{ $k }}" {{ $kyGui->loai_hinh == $k ? 'selected' : '' }}>
                                            {{ $v['label'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Gán vào Dự án (Bỏ qua nếu nhà lẻ)</label>
                                <select name="du_an_id" class="form-select border-warning select2-du-an">
                                    <option value="">-- Không thuộc dự án --</option>
                                    @foreach ($duAns as $da)
                                        <option value="{{ $da->id }}">{{ $da->ten_du_an }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Giá + trường điều kiện: hiện/ẩn theo nhu cầu --}}
                        <div id="blockBan" class="row g-4 mb-4" style="{{ old('nhu_cau', $kyGui->nhu_cau) === 'ban' ? '' : 'display:none!important' }}">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Giá Bán (VNĐ) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" name="gia" id="inputGia" class="form-control fw-bold"
                                        value="{{ old('gia', $kyGui->nhu_cau == 'ban' ? $kyGui->gia_ban_mong_muon : '') }}"
                                        placeholder="Ví dụ: 3500000000" min="0">
                                    <span class="input-group-text text-muted" style="font-size:.8rem;">VNĐ</span>
                                </div>
                                <div class="text-muted mt-1" style="font-size:.75rem;" id="giaLabel"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Pháp lý</label>
                                <input type="text" name="phap_ly" class="form-control"
                                    value="{{ old('phap_ly', $kyGui->phap_ly) }}"
                                    placeholder="Sổ hồng, Sổ đỏ...">
                            </div>
                        </div>

                        <div id="blockThue" class="row g-4 mb-4" style="{{ old('nhu_cau', $kyGui->nhu_cau) === 'thue' ? '' : 'display:none!important' }}">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Giá Thuê (VNĐ/Tháng) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" name="gia_thue" id="inputGiaThue" class="form-control fw-bold"
                                        value="{{ old('gia_thue', $kyGui->nhu_cau == 'thue' ? $kyGui->gia_thue_mong_muon : '') }}"
                                        placeholder="Ví dụ: 15000000" min="0">
                                    <span class="input-group-text text-muted" style="font-size:.8rem;">VNĐ/th</span>
                                </div>
                                <div class="text-muted mt-1" style="font-size:.75rem;" id="giaThuеLabel"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Hình thức thanh toán</label>
                                <select name="hinh_thuc_thanh_toan" class="form-select">
                                    <option value="">-- Chọn --</option>
                                    @foreach(['thang' => 'Thanh toán theo tháng', 'quy' => 'Thanh toán theo quý', 'nam_1' => 'Thanh toán 1 năm/lần'] as $htVal => $htLabel)
                                        <option value="{{ $htVal }}" {{ old('hinh_thuc_thanh_toan', $kyGui->hinh_thuc_thanh_toan ?? '') == $htVal ? 'selected' : '' }}>{{ $htLabel }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row g-3 mb-4 bg-light p-3 rounded">
                            <div class="col-md-4">
                                <label class="form-label">Phòng ngủ</label>
                                <input type="number" min="0" name="so_phong_ngu" class="form-control"
                                    value="{{ $kyGui->so_phong_ngu }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Tầng</label>
                                <input type="text" name="tang" class="form-control" value="{{ $kyGui->tang }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Nội thất</label>
                                <select name="noi_that" class="form-select">
                                    <option value="">-- Chọn --</option>
                                    @foreach(['full' => 'Full nội thất', 'co_ban' => 'Nội thất cơ bản', 'tho' => 'Thô (chưa có NT)'] as $v => $l)
                                        <option value="{{ $v }}" {{ $kyGui->noi_that == $v ? 'selected' : '' }}>{{ $l }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-0">
                            <label class="form-label">Mô tả / Thông tin cung cấp từ khách</label>
                            <textarea name="mo_ta" class="form-control" rows="5">Vị trí: {{ $kyGui->dia_chi }}&#13;&#10;Ghi chú: {{ $kyGui->ghi_chu }}</textarea>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-4">
                <div class="card border-0 shadow-sm border-start border-4 border-info mb-4">
                    <div class="card-header bg-white fw-bold py-3">
                        <i class="fas fa-user-tie text-info me-2"></i>Tạo Data Chủ Nhà
                    </div>
                    <div class="card-body bg-light">
                        <div class="alert alert-info text-dark" style="font-size: 0.8rem; padding: 10px;">
                            <i class="fas fa-info-circle"></i> Nếu SĐT đã có trên hệ thống, BĐS sẽ được tự động liên kết
                            với Chủ nhà cũ.
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Họ tên Chủ nhà <span class="text-danger">*</span></label>
                            <input type="text" name="ho_ten_chu_nha" class="form-control fw-bold text-dark"
                                value="{{ $kyGui->ho_ten_chu_nha }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="text" name="so_dien_thoai" class="form-control fw-bold text-danger"
                                value="{{ $kyGui->so_dien_thoai }}" required>
                        </div>
                        <div class="mb-0">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ $kyGui->email }}">
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white fw-bold py-3">
                        <i class="fas fa-images text-warning me-2"></i>Ảnh khách đính kèm
                    </div>
                    <div class="card-body d-flex flex-wrap gap-2">
                        @if ($kyGui->hinh_anh_tham_khao && is_array($kyGui->hinh_anh_tham_khao))
                            @foreach ($kyGui->hinh_anh_tham_khao as $anh)
                                <a href="{{ asset('storage/' . $anh) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $anh) }}" class="img-thumbnail object-fit-cover"
                                        style="width: 100px; height: 100px;">
                                </a>
                            @endforeach
                            <p class="text-muted small mt-2 w-100">
                                <i class="fas fa-check text-success"></i> Hệ thống sẽ tự động Copy ảnh này sang BĐS.
                            </p>
                        @else
                            <p class="text-muted w-100 text-center py-3">Khách không tải lên hình ảnh.</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ===== HÀNG NÚT SUBMIT ===== --}}
            <div class="col-12 text-end mb-5">
                <a href="{{ route('nhanvien.admin.ky-gui.index') }}" class="btn btn-light border px-4 py-2 me-2">
                    Trở lại
                </a>
                <button type="button" class="btn btn-success px-5 py-2 fw-bold" id="btnLuuKyGui">
                    <i class="fas fa-check-circle me-2"></i> LƯU VÀ ĐƯA VÀO KHO BĐS
                </button>
            </div>

        </div>
    </form>

    {{-- ===== MODAL XÁC NHẬN ===== --}}
    <div class="modal fade" id="modalXacNhanLuu" tabindex="-1" aria-labelledby="modalXacNhanLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

                {{-- Header --}}
                <div class="modal-header border-0 pb-0"
                    style="background: linear-gradient(135deg, #198754 0%, #20c997 100%);">
                    <div class="d-flex align-items-center gap-3 py-2">
                        <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center"
                            style="width:52px;height:52px;">
                            <i class="fas fa-warehouse text-white fs-4"></i>
                        </div>
                        <div>
                            <h5 class="modal-title text-white fw-bold mb-0" id="modalXacNhanLabel">
                                Xác nhận đưa vào Kho BĐS
                            </h5>
                            <small class="text-white text-opacity-75">Hành động này không thể hoàn tác</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal"></button>
                </div>

                {{-- Body --}}
                <div class="modal-body px-4 py-4">
                    <div class="d-flex gap-3 align-items-start mb-3">
                        <div class="flex-shrink-0 bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
                            style="width:44px;height:44px;min-width:44px;">
                            <i class="fas fa-info-circle text-success fs-5"></i>
                        </div>
                        <div>
                            <p class="mb-1 fw-semibold" style="color:#1a1a1a;">Bạn sắp thực hiện các thao tác sau:</p>
                            <ul class="mb-0 ps-3" style="font-size:0.88rem; color:#444; line-height:1.8;">

                                <li>Đưa BĐS vào <strong>Kho dữ liệu</strong></li>
                                <li>Tạo hồ sơ <strong>Data Chủ nhà</strong> tương ứng</li>
                            </ul>
                        </div>
                    </div>

                    <div class="alert alert-warning border-0 rounded-3 d-flex align-items-center gap-2 py-2 px-3 mb-0"
                        style="font-size:0.83rem; background:#fff8e1;">
                        <i class="fas fa-exclamation-triangle text-warning"></i>
                        <span>Vui lòng kiểm tra kỹ thông tin trước khi xác nhận.</span>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="modal-footer border-0 pt-0 px-4 pb-4 gap-2">
                    <button type="button" class="btn btn-light px-4 fw-medium rounded-3" data-bs-dismiss="modal"
                        style="min-width:110px;">
                        <i class="fas fa-times me-1"></i> Hủy bỏ
                    </button>
                    <button type="button" class="btn btn-success px-4 fw-bold rounded-3" id="btnXacNhanSubmit"
                        style="min-width:160px;">
                        <span id="btnSubmitText">
                            <i class="fas fa-check-circle me-1"></i> Xác nhận & Lưu
                        </span>
                        <span id="btnSubmitLoading" class="d-none">
                            <span class="spinner-border spinner-border-sm me-1" role="status"></span>
                            Đang lưu...
                        </span>
                    </button>
                </div>

            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2-du-an').select2({
                theme: 'bootstrap-5',
                placeholder: '-- Không thuộc dự án --',
                allowClear: true,
                width: '100%'
            });
        });

        // ── Toggle giá + trường theo Nhu cầu ───────────────────────
        const selectNhuCau  = document.querySelector('select[name="nhu_cau"]');
        const blockBan      = document.getElementById('blockBan');
        const blockThue     = document.getElementById('blockThue');

        function toggleNhuCau(val) {
            if (val === 'ban') {
                blockBan.style.removeProperty('display');
                blockThue.style.display = 'none';
            } else {
                blockThue.style.removeProperty('display');
                blockBan.style.display = 'none';
            }
        }

        selectNhuCau.addEventListener('change', function () {
            toggleNhuCau(this.value);
        });

        // ── Format giá hiển thị ─────────────────────────────────────
        // Giá BÁN: hiển thị Tỷ hoặc Triệu (không có "/tháng")
        function formatGiaBan(val) {
            if (!val || isNaN(val)) return '';
            const n = parseFloat(val);
            if (n >= 1e9)  return '≈ ' + (n / 1e9).toFixed(2).replace(/\.?0+$/, '') + ' Tỷ';
            if (n >= 1e6)  return '≈ ' + (n / 1e6).toFixed(1).replace(/\.?0+$/, '') + ' Triệu';
            if (n >= 1e3)  return '≈ ' + (n / 1e3).toFixed(0) + ' Nghìn';
            return '';
        }

        // Giá THUÊ: hiển thị Triệu/tháng hoặc Nghìn/tháng
        function formatGiaThue(val) {
            if (!val || isNaN(val)) return '';
            const n = parseFloat(val);
            if (n >= 1e6)  return '≈ ' + (n / 1e6).toFixed(1).replace(/\.?0+$/, '') + ' Triệu/tháng';
            if (n >= 1e3)  return '≈ ' + (n / 1e3).toFixed(0) + ' Nghìn/tháng';
            return '';
        }

        const inputGia     = document.getElementById('inputGia');
        const inputGiaThue = document.getElementById('inputGiaThue');
        const giaLabel     = document.getElementById('giaLabel');
        const giaThuLabel  = document.getElementById('giaThu\u0435Label');

        if (inputGia) {
            inputGia.addEventListener('input', function () {
                if (giaLabel) giaLabel.textContent = formatGiaBan(this.value);
            });
            // Hiển thị ngay khi load trang
            if (giaLabel) giaLabel.textContent = formatGiaBan(inputGia.value);
        }
        if (inputGiaThue) {
            inputGiaThue.addEventListener('input', function () {
                if (giaThuLabel) giaThuLabel.textContent = formatGiaThue(this.value);
            });
            if (giaThuLabel) giaThuLabel.textContent = formatGiaThue(inputGiaThue.value);
        }


        // ── Mở modal khi click nút LƯU ─────────────────────────────
        document.getElementById('btnLuuKyGui').addEventListener('click', function() {
            var modal = new bootstrap.Modal(document.getElementById('modalXacNhanLuu'));
            modal.show();
        });

        // ── Submit form khi xác nhận trong modal ───────────────────
        document.getElementById('btnXacNhanSubmit').addEventListener('click', function() {
            document.getElementById('btnSubmitText').classList.add('d-none');
            document.getElementById('btnSubmitLoading').classList.remove('d-none');
            this.disabled = true;
            document.getElementById('kgAdminForm').submit();
        });
    </script>
@endpush

