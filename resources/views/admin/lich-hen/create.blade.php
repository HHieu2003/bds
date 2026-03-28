@extends('admin.layouts.master')
@section('title', 'Tạo lịch hẹn')
@section('page_title', 'Tạo lịch hẹn mới')

@section('content')
@php $nhanVien = Auth::guard('nhanvien')->user(); @endphp

<div class="container-fluid py-3">
<div class="row justify-content-center">
<div class="col-lg-8">

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-calendar-plus me-2"></i>Tạo lịch hẹn xem nhà</h5>
            <small class="opacity-75">Lịch sẽ được gửi đến Nguồn hàng để xác nhận</small>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('nhanvien.admin.lich-hen.store') }}" method="POST">
                @csrf

                {{-- ── Thông tin BDS ── --}}
                <h6 class="fw-700 mb-3 border-bottom pb-2 text-primary">
                    <i class="fas fa-building me-2"></i>Bất động sản
                </h6>

                <div class="mb-3">
                    <label class="form-label fw-600">Căn hộ / BDS <span class="text-danger">*</span></label>
                    <select name="bat_dong_san_id" class="form-select @error('bat_dong_san_id') is-invalid @enderror" required>
                        <option value="">— Chọn bất động sản —</option>
                        @foreach($dsBds as $bds)
                        <option value="{{ $bds->id }}" {{ old('bat_dong_san_id', $batDongSanId)==$bds->id ? 'selected' : '' }}>
                            [{{ $bds->ma_can }}] {{ $bds->ten_bat_dong_san }}
                        </option>
                        @endforeach
                    </select>
                    @error('bat_dong_san_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-600">Nhân viên Nguồn hàng phụ trách <span class="text-danger">*</span></label>
                    <select name="nhan_vien_nguon_hang_id" class="form-select @error('nhan_vien_nguon_hang_id') is-invalid @enderror" required>
                        <option value="">— Chọn nguồn hàng —</option>
                        @foreach($dsNguonHang as $nv)
                        <option value="{{ $nv->id }}" {{ old('nhan_vien_nguon_hang_id')==$nv->id ? 'selected' : '' }}>
                            {{ $nv->ho_ten }} ({{ $nv->so_dien_thoai ?? '—' }})
                        </option>
                        @endforeach
                    </select>
                    @error('nhan_vien_nguon_hang_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- ── Thông tin khách hàng ── --}}
                <h6 class="fw-700 mb-3 border-bottom pb-2 text-primary">
                    <i class="fas fa-user me-2"></i>Thông tin khách hàng
                </h6>

                <div class="mb-3">
                    <label class="form-label fw-600">Tài khoản khách hàng (nếu có)</label>
                    <select name="khach_hang_id" class="form-select" id="selectKhachHang"
                            onchange="autoFillKhach(this)">
                        <option value="">— Khách vãng lai / nhập tay —</option>
                        @foreach($dsKhachHang as $kh)
                        <option value="{{ $kh->id }}"
                                data-ten="{{ $kh->ho_ten }}"
                                data-sdt="{{ $kh->so_dien_thoai }}"
                                data-email="{{ $kh->email }}"
                                {{ old('khach_hang_id', $khachHangId)==$kh->id ? 'selected' : '' }}>
                            {{ $kh->ho_ten }} — {{ $kh->so_dien_thoai }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-600">Tên khách hàng <span class="text-danger">*</span></label>
                        <input type="text" name="ten_khach_hang" id="tenKhach"
                               value="{{ old('ten_khach_hang') }}" class="form-control @error('ten_khach_hang') is-invalid @enderror"
                               placeholder="Nguyễn Văn A" required>
                        @error('ten_khach_hang')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-600">Số điện thoại <span class="text-danger">*</span></label>
                        <input type="text" name="sdt_khach_hang" id="sdtKhach"
                               value="{{ old('sdt_khach_hang') }}" class="form-control @error('sdt_khach_hang') is-invalid @enderror"
                               placeholder="0901234567" required>
                        @error('sdt_khach_hang')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-600">Email khách hàng</label>
                    <input type="email" name="email_khach_hang" id="emailKhach"
                           value="{{ old('email_khach_hang') }}" class="form-control"
                           placeholder="khachhang@email.com">
                </div>

                {{-- ── Thời gian & địa điểm ── --}}
                <h6 class="fw-700 mb-3 border-bottom pb-2 text-primary">
                    <i class="fas fa-clock me-2"></i>Thời gian & địa điểm
                </h6>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-600">Thời gian hẹn <span class="text-danger">*</span></label>
                        <input type="datetime-local" name="thoi_gian_hen"
                               value="{{ old('thoi_gian_hen') }}" class="form-control @error('thoi_gian_hen') is-invalid @enderror"
                               min="{{ now()->format('Y-m-d\TH:i') }}" required>
                        @error('thoi_gian_hen')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-600">Địa điểm hẹn</label>
                        <input type="text" name="dia_diem_hen"
                               value="{{ old('dia_diem_hen') }}" class="form-control"
                               placeholder="Địa chỉ xem nhà, tầng, căn số...">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-600">Ghi chú cho Nguồn hàng</label>
                    <textarea name="ghi_chu_sale" class="form-control" rows="3"
                              placeholder="Yêu cầu đặc biệt, khách cần chú ý gì...">{{ old('ghi_chu_sale') }}</textarea>
                </div>

                {{-- ── Submit ── --}}
                <div class="d-flex gap-2 justify-content-end">
                    <a href="{{ route('nhanvien.admin.lich-hen.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Quay lại
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane me-1"></i> Tạo & Gửi xác nhận
                    </button>
                </div>

            </form>
        </div>
    </div>

</div>
</div>
</div>
@endsection

@push('scripts')
<script>
function autoFillKhach(sel) {
    const opt = sel.options[sel.selectedIndex];
    document.getElementById('tenKhach').value   = opt.dataset.ten   || '';
    document.getElementById('sdtKhach').value   = opt.dataset.sdt   || '';
    document.getElementById('emailKhach').value = opt.dataset.email || '';
}
</script>
@endpush
