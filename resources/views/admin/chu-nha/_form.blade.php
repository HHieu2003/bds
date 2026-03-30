@php $isEdit = !is_null($chuNha ?? null); @endphp

<div class="row g-4">
    <div class="col-12 col-lg-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3"><i class="fas fa-info-circle text-primary me-2"></i>Thông tin liên hệ
            </div>
            <div class="card-body">
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Họ và tên <span class="text-danger">*</span></label>
                        <input type="text" name="ho_ten" class="form-control @error('ho_ten') is-invalid @enderror"
                            value="{{ old('ho_ten', $isEdit ? $chuNha->ho_ten : '') }}" placeholder="VD: Nguyễn Văn A">
                        @error('ho_ten')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                        <input type="text" name="so_dien_thoai"
                            class="form-control @error('so_dien_thoai') is-invalid @enderror"
                            value="{{ old('so_dien_thoai', $isEdit ? $chuNha->so_dien_thoai : '') }}">
                        @error('so_dien_thoai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control"
                            value="{{ old('email', $isEdit ? $chuNha->email : '') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Số CCCD/CMND</label>
                        <input type="text" name="cccd" class="form-control"
                            value="{{ old('cccd', $isEdit ? $chuNha->cccd : '') }}">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Địa chỉ liên hệ</label>
                    <input type="text" name="dia_chi" class="form-control"
                        value="{{ old('dia_chi', $isEdit ? $chuNha->dia_chi : '') }}">
                </div>
                <div class="mb-0">
                    <label class="form-label">Ghi chú nội bộ</label>
                    <textarea name="ghi_chu" class="form-control" rows="3" placeholder="Đặc điểm chủ nhà, giờ giấc có thể liên hệ...">{{ old('ghi_chu', $isEdit ? $chuNha->ghi_chu : '') }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-3">
                <button type="submit" class="btn btn-primary w-100 mb-2 py-2"><i class="fas fa-save me-1"></i>
                    {{ $isEdit ? 'Lưu thay đổi' : 'Thêm chủ nhà' }}</button>
                <a href="{{ route('nhanvien.admin.chu-nha.index') }}" class="btn btn-light border w-100 py-2">Hủy</a>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3"><i class="fas fa-user-shield text-secondary me-2"></i>Phân quyền quản
                lý</div>
            <div class="card-body">
                <label class="form-label">Nhân viên phụ trách nguồn</label>
                <select name="nhan_vien_phu_trach_id" class="form-select">
                    <option value="">— Chung của công ty —</option>
                    @foreach ($nhanViens as $nv)
                        <option value="{{ $nv->id }}" @selected(old('nhan_vien_phu_trach_id', $isEdit ? $chuNha->nhan_vien_phu_trach_id : '') == $nv->id)>{{ $nv->ho_ten }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>
