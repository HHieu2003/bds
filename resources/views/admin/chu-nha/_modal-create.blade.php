<div class="modal fade" id="modalCreateChuNha" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <form class="modal-content" method="POST" action="{{ route('nhanvien.admin.chu-nha.store') }}">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-plus-circle text-primary me-2"></i>Thêm chủ nhà</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="_form_mode" value="create">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Họ và tên <span class="text-danger">*</span></label>
                        <input type="text" name="ho_ten" class="form-control @error('ho_ten') is-invalid @enderror"
                            value="{{ old('_form_mode') === 'create' ? old('ho_ten') : '' }}">
                        @error('ho_ten')
                            @if (old('_form_mode') === 'create')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @endif
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                        <input type="text" name="so_dien_thoai"
                            class="form-control @error('so_dien_thoai') is-invalid @enderror"
                            value="{{ old('_form_mode') === 'create' ? old('so_dien_thoai') : '' }}">
                        @error('so_dien_thoai')
                            @if (old('_form_mode') === 'create')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @endif
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control"
                            value="{{ old('_form_mode') === 'create' ? old('email') : '' }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">CCCD/CMND</label>
                        <input type="text" name="cccd" class="form-control"
                            value="{{ old('_form_mode') === 'create' ? old('cccd') : '' }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Địa chỉ</label>
                        <input type="text" name="dia_chi" class="form-control"
                            value="{{ old('_form_mode') === 'create' ? old('dia_chi') : '' }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nhân viên phụ trách</label>
                        <select name="nhan_vien_phu_trach_id" class="form-select">
                            <option value="">— Chung của công ty —</option>
                            @foreach ($nhanViens as $nv)
                                <option value="{{ $nv->id }}" @selected(old('_form_mode') === 'create' && old('nhan_vien_phu_trach_id') == $nv->id)>
                                    {{ $nv->ho_ten }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Ghi chú</label>
                        <textarea name="ghi_chu" class="form-control" rows="3">{{ old('_form_mode') === 'create' ? old('ghi_chu') : '' }}</textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Hủy</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Thêm chủ nhà</button>
            </div>
        </form>
    </div>
</div>
