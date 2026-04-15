@extends('admin.layouts.master')
@section('title', 'Tạo lịch hẹn xem nhà')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />

    <style>
        .card-header-custom {
            background-color: #f8f9fa;
            border-bottom: 2px solid var(--bs-primary);
        }

        .icon-circle {
            width: 35px;
            height: 35px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }

        /* Chỉnh lại chiều cao của ô chọn Select2 cho đồng bộ Bootstrap 5 */
        .select2-container .select2-selection--single {
            height: 42px;
            display: flex;
            align-items: center;
        }

        .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
            padding-top: 4px;
        }
    </style>
@endpush

@section('content')
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <div>
            <a href="{{ route('nhanvien.admin.lich-hen.index') }}" class="btn btn-sm btn-light border mb-2"><i
                    class="fas fa-arrow-left"></i> Quay lại</a>
            <h1 class="page-header-title text-primary"><i class="fas fa-calendar-plus me-2"></i> Tạo lịch hẹn mới</h1>
            <p class="text-muted mb-0">Hệ thống sẽ tự động gửi thông báo đến bộ phận Nguồn hàng để xác nhận.</p>
        </div>
    </div>

    <form action="{{ route('nhanvien.admin.lich-hen.store') }}" method="POST">
        @csrf
        <div class="row g-4">

            <div class="col-12 col-xl-7">
                {{-- KHỐI CHỌN BĐS --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header card-header-custom py-3 d-flex align-items-center">
                        <div class="icon-circle bg-primary bg-opacity-10 text-primary me-2"><i class="fas fa-building"></i>
                        </div>
                        <h5 class="mb-0 fw-bold">Thông tin Bất động sản</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-0">
                            <label class="form-label fw-bold">Chọn Căn hộ / Nhà đất <span
                                    class="text-danger">*</span></label>

                            {{-- Class select2-bds dùng để bắt sự kiện search --}}
                            <select name="bat_dong_san_id" id="bds_select"
                                class="form-select select2-bds @error('bat_dong_san_id') is-invalid @enderror" required>
                                <option value="">— Gõ chữ để tìm mã căn hoặc tiêu đề BĐS —</option>
                                @foreach ($dsBds as $bds)
                                    {{-- data-nguon: Lưu ID của Nguồn quản lý để JS đọc --}}
                                    <option value="{{ $bds->id }}" data-nguon="{{ $bds->nhan_vien_phu_trach_id }}"
                                        {{ old('bat_dong_san_id', $batDongSanId) == $bds->id ? 'selected' : '' }}>
                                        [{{ $bds->ma_bat_dong_san }}] - {{ $bds->tieu_de }}
                                    </option>
                                @endforeach
                            </select>
                            @error('bat_dong_san_id')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- KHỐI THÔNG TIN KHÁCH HÀNG --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-header card-header-custom py-3 d-flex align-items-center border-success">
                        <div class="icon-circle bg-success bg-opacity-10 text-success me-2"><i class="fas fa-user-tie"></i>
                        </div>
                        <h5 class="mb-0 fw-bold">Thông tin Khách đi xem</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="alert alert-light border border-success-subtle text-dark small mb-4">
                            <i class="fas fa-info-circle text-success me-1"></i> Nếu là khách cũ, hãy chọn trong danh sách
                            để tự động điền thông tin. Nếu là khách mới, vui lòng nhập tay vào các ô bên dưới.
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Tìm trong Data Khách hàng (Không bắt buộc)</label>

                            {{-- Class select2-khach --}}
                            <select name="khach_hang_id" class="form-select select2-khach" id="selectKhachHang"
                                onchange="autoFillKhach(this)">
                                <option value="">— Gõ tên hoặc SĐT để tìm khách —</option>
                                @foreach ($dsKhachHang as $kh)
                                    <option value="{{ $kh->id }}" data-ten="{{ $kh->ho_ten }}"
                                        data-sdt="{{ $kh->so_dien_thoai }}" data-email="{{ $kh->email }}"
                                        {{ old('khach_hang_id', $khachHangId) == $kh->id ? 'selected' : '' }}>
                                        {{ $kh->so_dien_thoai }} — {{ $kh->ho_ten }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Tên khách hàng <span class="text-danger">*</span></label>
                                <input type="text" name="ten_khach_hang" id="tenKhach"
                                    value="{{ old('ten_khach_hang') }}"
                                    class="form-control form-control-lg @error('ten_khach_hang') is-invalid @enderror"
                                    placeholder="Ví dụ: Anh Tuấn" required>
                                @error('ten_khach_hang')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Số điện thoại <span class="text-danger">*</span></label>
                                <input type="text" name="sdt_khach_hang" id="sdtKhach"
                                    value="{{ old('sdt_khach_hang') }}"
                                    class="form-control form-control-lg text-danger fw-bold @error('sdt_khach_hang') is-invalid @enderror"
                                    placeholder="09xx..." required>
                                @error('sdt_khach_hang')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold">Email (Nếu có)</label>
                                <input type="email" name="email_khach_hang" id="emailKhach"
                                    value="{{ old('email_khach_hang') }}" class="form-control"
                                    placeholder="khachhang@email.com">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-5">

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header card-header-custom py-3 d-flex align-items-center border-danger">
                        <div class="icon-circle bg-danger bg-opacity-10 text-danger me-2"><i class="fas fa-clock"></i></div>
                        <h5 class="mb-0 fw-bold">Lịch trình</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <label class="form-label fw-bold">Thời gian đến xem <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="thoi_gian_hen" value="{{ old('thoi_gian_hen') }}"
                                class="form-control form-control-lg fw-bold text-danger @error('thoi_gian_hen') is-invalid @enderror"
                                min="{{ now()->format('Y-m-d\TH:i') }}" required>
                            @error('thoi_gian_hen')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-bold">Địa điểm gặp mặt</label>
                            <input type="text" name="dia_diem_hen" value="{{ old('dia_diem_hen') }}"
                                class="form-control" placeholder="VD: Sảnh tòa nhà, Quán cafe chân đế...">
                            <small class="text-muted mt-1 d-block">Để trống nếu gặp trực tiếp tại địa chỉ BĐS.</small>
                        </div>
                    </div>
                </div>

                {{-- KHỐI YÊU CẦU NGUỒN HÀNG --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header card-header-custom py-3 d-flex align-items-center border-warning">
                        <div class="icon-circle bg-warning bg-opacity-10 text-dark me-2"><i
                                class="fas fa-user-shield"></i></div>
                        <h5 class="mb-0 fw-bold">Yêu cầu Nguồn hỗ trợ</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <label class="form-label fw-bold">Chuyển cho Nhân sự Nguồn <span
                                    class="text-danger">*</span></label>

                            {{-- Class select2-nguon --}}
                            <select name="nhan_vien_nguon_hang_id" id="nguon_select"
                                class="form-select select2-nguon @error('nhan_vien_nguon_hang_id') is-invalid @enderror"
                                required>
                                <option value="">— Gõ tên Nguồn để tìm (nếu muốn đổi người) —</option>
                                @foreach ($dsNguonHang as $nv)
                                    <option value="{{ $nv->id }}"
                                        {{ old('nhan_vien_nguon_hang_id') == $nv->id ? 'selected' : '' }}>
                                        {{ $nv->ho_ten }} - {{ $nv->so_dien_thoai ?? '---' }}
                                    </option>
                                @endforeach
                            </select>

                            <div id="nguon-hint" class="small mt-2 text-muted">Hệ thống sẽ tự gán Nguồn khi bạn chọn BĐS.
                                Bạn vẫn có thể đổi thủ công.</div>

                            @error('nhan_vien_nguon_hang_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-0">
                            <label class="form-label fw-bold">Ghi chú cho Nguồn hàng</label>
                            <textarea name="ghi_chu_sale" class="form-control bg-warning bg-opacity-10 border-warning" rows="4"
                                placeholder="Ví dụ: Nguồn xin chủ nhà bớt giá chút đỉnh nhé, khách rất nét...">{{ old('ghi_chu_sale') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg fw-bold py-3 shadow-sm">
                        <i class="fas fa-paper-plane me-2"></i> TẠO LỊCH HẸN VÀ XUẤT KHO
                    </button>
                </div>

            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {

            // 1. Khởi tạo Select2 cho ô chọn BĐS
            $('.select2-bds').select2({
                theme: 'bootstrap-5',
                placeholder: "— Gõ để tìm mã căn hoặc tiêu đề BĐS —",
                allowClear: true
            });

            // 2. Khởi tạo Select2 cho ô chọn Khách hàng
            $('.select2-khach').select2({
                theme: 'bootstrap-5',
                placeholder: "— Gõ tên hoặc SĐT để tìm khách —",
                allowClear: true
            });

            // 3. Khởi tạo Select2 cho ô chọn Nguồn Hàng
            $('.select2-nguon').select2({
                theme: 'bootstrap-5',
                placeholder: "— Ai đang giữ chìa khóa / nắm chủ nhà? —",
                allowClear: true
            });

            // ==========================================
            // LOGIC: TỰ ĐỘNG CHỌN NGUỒN THEO BĐS ĐÃ CHỌN
            // ==========================================
            $('#bds_select').on('select2:select', function(e) {
                // Lấy data-nguon từ thẻ <option> vừa được chọn
                let nguonId = $(this).find(':selected').data('nguon');
                let $nguonSelect = $('#nguon_select');

                if (nguonId && $nguonSelect.find("option[value='" + nguonId + "']").length > 0) {
                    // Nếu BĐS có Nguồn và Nguồn đó tồn tại trong danh sách -> Auto Select
                    $nguonSelect.val(nguonId).trigger('change');

                    // Hiển thị thông báo nhấp nháy cho người dùng biết
                    $('#nguon-hint').html(
                        '<span class="text-success fw-bold"><i class="fas fa-magic"></i> Đã tự động chọn Nguồn phụ trách căn này.</span>'
                        );
                } else {
                    // Nếu BĐS không có người phụ trách (Nhà ngoài / Bị lỗi data) -> Reset ô Nguồn
                    $nguonSelect.val('').trigger('change');
                    $('#nguon-hint').html(
                        '<span class="text-danger fw-bold"><i class="fas fa-exclamation-triangle"></i> BĐS này chưa có người phụ trách, vui lòng tự chọn Nguồn!</span>'
                        );
                }
            });
        });

        // ==========================================
        // LOGIC: TỰ ĐỘNG ĐIỀN DATA KHÁCH HÀNG
        // ==========================================
        function autoFillKhach(sel) {
            const opt = sel.options[sel.selectedIndex];

            const tenInput = document.getElementById('tenKhach');
            const sdtInput = document.getElementById('sdtKhach');
            const emailInput = document.getElementById('emailKhach');

            if (opt.value !== "") {
                tenInput.value = opt.dataset.ten || '';
                sdtInput.value = opt.dataset.sdt || '';
                emailInput.value = opt.dataset.email || '';

                // Thêm viền xanh/nền xanh nhẹ để báo hiệu vừa auto-fill
                tenInput.classList.add('bg-success', 'bg-opacity-10', 'border-success');
                sdtInput.classList.add('bg-success', 'bg-opacity-10', 'border-success');

                setTimeout(() => {
                    tenInput.classList.remove('bg-success', 'bg-opacity-10', 'border-success');
                    sdtInput.classList.remove('bg-success', 'bg-opacity-10', 'border-success');
                }, 1500);
            } else {
                tenInput.value = '';
                sdtInput.value = '';
                emailInput.value = '';
            }
        }
    </script>
@endpush
