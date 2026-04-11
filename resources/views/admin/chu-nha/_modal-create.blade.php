<div class="modal fade" id="modalCreateChuNha" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <form class="modal-content border-0 shadow" method="POST" action="{{ route('nhanvien.admin.chu-nha.store') }}">
            @csrf
            <div class="modal-header bg-light border-bottom-0">
                <h5 class="modal-title fw-bold"><i class="fas fa-plus-circle text-primary me-2"></i>Thêm chủ nhà mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <input type="hidden" name="_form_mode" value="create">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Họ và tên <span class="text-danger">*</span></label>
                        <input type="text" name="ho_ten" class="form-control @error('ho_ten') is-invalid @enderror"
                            value="{{ old('_form_mode') === 'create' ? old('ho_ten') : '' }}"
                            placeholder="Nhập tên chủ nhà">
                        @error('ho_ten')
                            @if (old('_form_mode') === 'create')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @endif
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Số điện thoại <span class="text-danger">*</span></label>
                        <input type="text" name="so_dien_thoai"
                            class="form-control @error('so_dien_thoai') is-invalid @enderror"
                            value="{{ old('_form_mode') === 'create' ? old('so_dien_thoai') : '' }}"
                            placeholder="09xx.xxx.xxx">
                        @error('so_dien_thoai')
                            @if (old('_form_mode') === 'create')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @endif
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Email</label>
                        <input type="email" name="email" class="form-control"
                            value="{{ old('_form_mode') === 'create' ? old('email') : '' }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-medium">CCCD/CMND</label>
                        <input type="text" name="cccd" class="form-control"
                            value="{{ old('_form_mode') === 'create' ? old('cccd') : '' }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-medium">Địa chỉ</label>
                        <input type="text" name="dia_chi" class="form-control"
                            value="{{ old('_form_mode') === 'create' ? old('dia_chi') : '' }}">
                    </div>

                    {{-- CHỌN BẤT ĐỘNG SẢN BẰNG GIAO DIỆN TÌM KIẾM --}}
                    <div class="col-md-12">
                        <label class="form-label fw-medium">Gán Căn hộ (BĐS) cho chủ nhà này</label>
                        <select name="bat_dong_san_ids[]" id="create_bat_dong_san_ids" multiple>
                        </select>
                        <div class="form-text text-primary"><i class="fas fa-search me-1"></i> Bạn có thể gõ "Tên Dự Án"
                            hoặc "Mã Căn" để tìm kiếm nhanh.</div>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-medium">Nhân viên phụ trách</label>
                        @php
                            $currentUserId = auth('nhanvien')->check() ? auth('nhanvien')->id() : auth()->id();
                        @endphp
                        <select name="nhan_vien_phu_trach_id" class="form-select">
                            <option value="">— Chung của công ty (Mọi người đều thấy) —</option>
                            @foreach ($nhanViens as $nv)
                                <option value="{{ $nv->id }}" @selected(old('_form_mode') === 'create' ? old('nhan_vien_phu_trach_id') == $nv->id : $currentUserId == $nv->id)>
                                    {{ $nv->ho_ten }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-medium">Ghi chú thêm</label>
                        <textarea name="ghi_chu" class="form-control" rows="3">{{ old('_form_mode') === 'create' ? old('ghi_chu') : '' }}</textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light border-top-0">
                <button type="button" class="btn btn-secondary bg-white text-dark border"
                    data-bs-dismiss="modal">Hủy</button>
                <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-2"></i>Lưu chủ nhà</button>
            </div>
        </form>
    </div>
</div>
