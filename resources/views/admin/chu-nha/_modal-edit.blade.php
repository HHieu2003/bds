<div class="modal fade" id="modalEditChuNha" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <form id="editChuNhaForm" class="modal-content" method="POST" action="#">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-edit text-primary me-2"></i>Sửa chủ nhà</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="_form_mode" value="edit">
                <input type="hidden" name="_edit_id" id="edit_id" value="{{ old('_edit_id') }}">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Họ và tên <span class="text-danger">*</span></label>
                        <input type="text" id="edit_ho_ten" name="ho_ten"
                            class="form-control @error('ho_ten') is-invalid @enderror"
                            value="{{ old('_form_mode') === 'edit' ? old('ho_ten') : '' }}">
                        @error('ho_ten')
                            @if (old('_form_mode') === 'edit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @endif
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                        <input type="text" id="edit_so_dien_thoai" name="so_dien_thoai"
                            class="form-control @error('so_dien_thoai') is-invalid @enderror"
                            value="{{ old('_form_mode') === 'edit' ? old('so_dien_thoai') : '' }}">
                        @error('so_dien_thoai')
                            @if (old('_form_mode') === 'edit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @endif
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" id="edit_email" name="email" class="form-control"
                            value="{{ old('_form_mode') === 'edit' ? old('email') : '' }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">CCCD/CMND</label>
                        <input type="text" id="edit_cccd" name="cccd" class="form-control"
                            value="{{ old('_form_mode') === 'edit' ? old('cccd') : '' }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Địa chỉ</label>
                        <input type="text" id="edit_dia_chi" name="dia_chi" class="form-control"
                            value="{{ old('_form_mode') === 'edit' ? old('dia_chi') : '' }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nhân viên phụ trách</label>
                        <select id="edit_nhan_vien_phu_trach_id" name="nhan_vien_phu_trach_id" class="form-select">
                            <option value="">— Chung của công ty —</option>
                            @foreach ($nhanViens as $nv)
                                <option value="{{ $nv->id }}" @selected(old('_form_mode') === 'edit' && old('nhan_vien_phu_trach_id') == $nv->id)>
                                    {{ $nv->ho_ten }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Ghi chú</label>
                        <textarea id="edit_ghi_chu" name="ghi_chu" class="form-control" rows="3">{{ old('_form_mode') === 'edit' ? old('ghi_chu') : '' }}</textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Hủy</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Lưu thay đổi</button>
            </div>
        </form>
    </div>
</div>
