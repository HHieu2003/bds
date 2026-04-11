<div class="modal fade" id="modalEditChuNha" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <form id="editChuNhaForm" class="modal-content border-0 shadow" method="POST" action="#">
            @csrf
            @method('PUT')
            <div class="modal-header bg-light border-bottom-0">
                <h5 class="modal-title fw-bold"><i class="fas fa-edit text-primary me-2"></i>Cập nhật thông tin chủ nhà
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <input type="hidden" name="_form_mode" value="edit">
                <input type="hidden" name="_edit_id" id="edit_id" value="{{ old('_edit_id') }}">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Họ và tên <span class="text-danger">*</span></label>
                        <input type="text" id="edit_ho_ten" name="ho_ten"
                            class="form-control @error('ho_ten') is-invalid @enderror"
                            value="{{ old('_form_mode') === 'edit' ? old('ho_ten') : '' }}"
                            oninvalid="this.setCustomValidity('Vui lòng nhập họ tên chủ nhà!')"
                            oninput="this.setCustomValidity('')" required>
                        @error('ho_ten')
                            @if (old('_form_mode') === 'edit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @endif
                        @enderror
                    </div>

                    {{-- XÁC MINH SỐ ĐIỆN THOẠI TRỰC TIẾP --}}
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Số điện thoại <span class="text-danger">*</span></label>
                        <input type="tel" id="edit_so_dien_thoai" name="so_dien_thoai"
                            class="form-control @error('so_dien_thoai') is-invalid @enderror"
                            value="{{ old('_form_mode') === 'edit' ? old('so_dien_thoai') : '' }}" minlength="9"
                            maxlength="11" pattern="[0-9]{9,11}"
                            oninvalid="if(this.value === ''){ this.setCustomValidity('Vui lòng nhập số điện thoại!'); } else { this.setCustomValidity('Số điện thoại không hợp lệ (Phải từ 9 đến 11 chữ số)!'); }"
                            oninput="this.setCustomValidity(''); this.value = this.value.replace(/[^0-9]/g, '');"
                            required>
                        @error('so_dien_thoai')
                            @if (old('_form_mode') === 'edit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @endif
                        @enderror
                    </div>

                    {{-- XÁC MINH EMAIL --}}
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Email</label>
                        <input type="email" id="edit_email" name="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('_form_mode') === 'edit' ? old('email') : '' }}" placeholder="vidu@gmail.com"
                            oninvalid="this.setCustomValidity('Vui lòng nhập đúng định dạng Email (có chứa ký tự @)!')"
                            oninput="this.setCustomValidity('')">
                        @error('email')
                            @if (old('_form_mode') === 'edit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @endif
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-medium">CCCD/CMND</label>
                        <input type="text" id="edit_cccd" name="cccd" class="form-control"
                            value="{{ old('_form_mode') === 'edit' ? old('cccd') : '' }}" maxlength="12" minlength="9"
                            pattern="[0-9]{9,12}"
                            oninvalid="if(this.value !== ''){ this.setCustomValidity('CCCD/CMND phải là số từ 9 đến 12 ký tự!'); }"
                            oninput="this.setCustomValidity(''); this.value = this.value.replace(/[^0-9]/g, '');">
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-medium">Địa chỉ</label>
                        <input type="text" id="edit_dia_chi" name="dia_chi" class="form-control"
                            value="{{ old('_form_mode') === 'edit' ? old('dia_chi') : '' }}">
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-medium text-primary"><i class="fas fa-building me-1"></i> Quản lý
                            Căn hộ (BĐS) của chủ nhà này</label>
                        <select name="bat_dong_san_ids[]" id="edit_bat_dong_san_ids" multiple>
                        </select>
                        <div class="form-text">Gõ từ khóa để tìm thêm căn hộ. Để bỏ chọn căn hộ hiện tại, bấm dấu X bên
                            cạnh tên căn hộ đó.</div>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-medium">Nhân viên phụ trách</label>
                        <select id="edit_nhan_vien_phu_trach_id" name="nhan_vien_phu_trach_id" class="form-select">
                            <option value="">— Chung của công ty (Mọi người đều thấy) —</option>
                            @foreach ($nhanViens as $nv)
                                <option value="{{ $nv->id }}" @selected(old('_form_mode') === 'edit' && old('nhan_vien_phu_trach_id') == $nv->id)>
                                    {{ $nv->ho_ten }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-medium">Ghi chú thêm</label>
                        <textarea id="edit_ghi_chu" name="ghi_chu" class="form-control" rows="3">{{ old('_form_mode') === 'edit' ? old('ghi_chu') : '' }}</textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light border-top-0">
                <button type="button" class="btn btn-secondary bg-white text-dark border"
                    data-bs-dismiss="modal">Hủy</button>
                <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-2"></i>Lưu thay
                    đổi</button>
            </div>
        </form>
    </div>
</div>
