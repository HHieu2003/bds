@php $isEdit = !is_null($kyGui ?? null); @endphp

<div class="row g-4">
    {{-- ════ CỘT TRÁI (Nội dung chính) ════ --}}
    <div class="col-12 col-lg-8">

        {{-- THÔNG TIN CHỦ NHÀ --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3"><i class="fas fa-user text-primary me-2"></i>Thông tin chủ nhà</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Họ tên chủ nhà <span class="text-danger">*</span></label>
                        <input type="text" name="ho_ten_chu_nha"
                            class="form-control @error('ho_ten_chu_nha') is-invalid @enderror"
                            value="{{ old('ho_ten_chu_nha', $isEdit ? $kyGui->ho_ten_chu_nha : '') }}"
                            placeholder="Nguyễn Văn A">
                        @error('ho_ten_chu_nha')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                        <input type="tel" name="so_dien_thoai"
                            class="form-control @error('so_dien_thoai') is-invalid @enderror"
                            value="{{ old('so_dien_thoai', $isEdit ? $kyGui->so_dien_thoai : '') }}"
                            placeholder="0901 234 567">
                        @error('so_dien_thoai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control"
                            value="{{ old('email', $isEdit ? $kyGui->email : '') }}" placeholder="email@example.com">
                    </div>
                </div>
            </div>
        </div>

        {{-- PHÂN LOẠI & THÔNG TIN BĐS --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3"><i class="fas fa-home text-success me-2"></i>Thông tin Bất động sản
                Ký gửi</div>
            <div class="card-body">
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Loại hình <span class="text-danger">*</span></label>
                        <select name="loai_hinh" class="form-select @error('loai_hinh') is-invalid @enderror">
                            <option value="">— Chọn loại hình —</option>
                            @foreach (\App\Models\KyGui::LOAI_HINH as $v => $info)
                                <option value="{{ $v }}" @selected(old('loai_hinh', $isEdit ? $kyGui->loai_hinh : '') == $v)>{{ $info['label'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('loai_hinh')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nhu cầu <span class="text-danger">*</span></label>
                        <select name="nhu_cau" class="form-select kg-nhu-cau-sel">
                            <option value="ban" @selected(old('nhu_cau', $isEdit ? $kyGui->nhu_cau : 'ban') == 'ban')>Bán</option>
                            <option value="thue" @selected(old('nhu_cau', $isEdit ? $kyGui->nhu_cau : 'ban') == 'thue')>Cho thuê</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Địa chỉ BĐS</label>
                    <input type="text" name="dia_chi" class="form-control"
                        value="{{ old('dia_chi', $isEdit ? $kyGui->dia_chi : '') }}"
                        placeholder="Số nhà, đường, phường, quận...">
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-8">
                        <label class="form-label">Dự án</label>
                        <input type="text" name="du_an" class="form-control @error('du_an') is-invalid @enderror"
                            value="{{ old('du_an', $isEdit ? $kyGui->du_an : '') }}"
                            placeholder="VD: Vinhomes Smart City">
                        @error('du_an')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Mã căn</label>
                        <input type="text" name="ma_can" class="form-control @error('ma_can') is-invalid @enderror"
                            value="{{ old('ma_can', $isEdit ? $kyGui->ma_can : '') }}" placeholder="VD: S2.03-1212">
                        @error('ma_can')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Diện tích (m²) <span class="text-danger">*</span></label>
                        <input type="number" name="dien_tich"
                            class="form-control @error('dien_tich') is-invalid @enderror"
                            value="{{ old('dien_tich', $isEdit ? $kyGui->dien_tich : '') }}" min="1"
                            step="0.1">
                        @error('dien_tich')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Phòng ngủ</label>
                        <input type="number" name="so_phong_ngu" class="form-control"
                            value="{{ old('so_phong_ngu', $isEdit ? $kyGui->so_phong_ngu : 0) }}" min="0"
                            max="20">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Phòng tắm (WC)</label>
                        <input type="number" name="so_phong_tam" class="form-control"
                            value="{{ old('so_phong_tam', $isEdit ? $kyGui->so_phong_tam : 0) }}" min="0"
                            max="20">
                    </div>
                </div>

                <div class="row g-3 mb-0">
                    <div class="col-md-6">
                        <label class="form-label">Hướng nhà</label>
                        <select name="huong_nha" class="form-select">
                            <option value="">— Chọn —</option>
                            @foreach (['dong' => 'Đông', 'tay' => 'Tây', 'nam' => 'Nam', 'bac' => 'Bắc', 'dong_nam' => 'Đông Nam', 'dong_bac' => 'Đông Bắc', 'tay_nam' => 'Tây Nam', 'tay_bac' => 'Tây Bắc'] as $v => $l)
                                <option value="{{ $v }}" @selected(old('huong_nha', $isEdit ? $kyGui->huong_nha : '') == $v)>{{ $l }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nội thất</label>
                        <select name="noi_that" class="form-select">
                            <option value="">— Chọn —</option>
                            @foreach (\App\Models\KyGui::NOI_THAT as $v => $l)
                                <option value="{{ $v }}" @selected(old('noi_that', $isEdit ? $kyGui->noi_that : '') == $v)>{{ $l }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        {{-- BOX GIÁ --}}
        <div class="card border-0 shadow-sm border-top border-warning border-3 mb-4 {{ old('nhu_cau', $isEdit ? $kyGui->nhu_cau : 'ban') !== 'ban' ? 'd-none' : '' }}"
            id="kgAdminBanBox">
            <div class="card-body">
                <h6 class="fw-bold text-warning mb-3"><i class="fas fa-tag"></i> Thông tin Giá Bán</h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Giá bán mong muốn (VNĐ)</label>
                        <input type="number" name="gia_ban_mong_muon" class="form-control"
                            value="{{ old('gia_ban_mong_muon', $isEdit ? $kyGui->gia_ban_mong_muon : '') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Pháp lý</label>
                        <select name="phap_ly" class="form-select">
                            <option value="">— Chọn —</option>
                            @foreach (\App\Models\KyGui::PHAP_LY as $v => $l)
                                <option value="{{ $v }}" @selected(old('phap_ly', $isEdit ? $kyGui->phap_ly : '') == $v)>{{ $l }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm border-top border-info border-3 mb-4 {{ old('nhu_cau', $isEdit ? $kyGui->nhu_cau : 'ban') !== 'thue' ? 'd-none' : '' }}"
            id="kgAdminThueBox">
            <div class="card-body">
                <h6 class="fw-bold text-info mb-3"><i class="fas fa-key"></i> Thông tin Cho Thuê</h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Giá thuê/tháng (VNĐ)</label>
                        <input type="number" name="gia_thue_mong_muon" class="form-control"
                            value="{{ old('gia_thue_mong_muon', $isEdit ? $kyGui->gia_thue_mong_muon : '') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Hình thức thanh toán</label>
                        <select name="hinh_thuc_thanh_toan" class="form-select">
                            <option value="">— Chọn —</option>
                            @foreach (\App\Models\KyGui::HINH_THUC_THANH_TOAN as $v => $l)
                                <option value="{{ $v }}" @selected(old('hinh_thuc_thanh_toan', $isEdit ? $kyGui->hinh_thuc_thanh_toan : '') == $v)>{{ $l }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <label class="form-label">Ghi chú của khách hàng</label>
                <textarea name="ghi_chu" class="form-control" rows="3">{{ old('ghi_chu', $isEdit ? $kyGui->ghi_chu : '') }}</textarea>
            </div>
        </div>

        {{-- HÌNH ẢNH --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3"><i class="fas fa-images text-warning me-2"></i>Hình ảnh tham khảo
            </div>
            <div class="card-body">
                @if ($isEdit && $kyGui->hinh_anh_tham_khao)
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        @foreach ($kyGui->hinh_anh_tham_khao as $img)
                            <div class="position-relative border rounded" style="width: 100px; height: 100px;">
                                <img src="{{ asset('storage/' . $img) }}"
                                    class="w-100 h-100 object-fit-cover rounded img-chk-target">
                                <label
                                    class="position-absolute top-0 end-0 m-1 bg-danger text-white rounded d-flex align-items-center justify-content-center cursor-pointer"
                                    style="width: 22px; height: 22px; font-size: 0.7rem;" title="Xóa ảnh này">
                                    <input type="checkbox" name="xoa_hinh_anh[]" value="{{ $img }}"
                                        class="d-none chk-delete-img">
                                    <i class="fas fa-times"></i>
                                </label>
                            </div>
                        @endforeach
                    </div>
                    <div class="text-muted mb-3" style="font-size: 0.8rem"><i class="fas fa-info-circle"></i> Tích
                        vào dấu X để đánh dấu xóa ảnh khi lưu.</div>
                @endif

                <label for="kgAdminFileInput"
                    class="d-flex flex-column align-items-center justify-content-center border border-dashed rounded bg-light cursor-pointer mb-2"
                    style="height: 100px; transition: 0.2s">
                    <i class="fas fa-cloud-upload-alt fs-3 text-muted mb-1"></i>
                    <span class="text-secondary" style="font-size: 0.85rem">Tải thêm ảnh mới lên</span>
                    <input type="file" id="kgAdminFileInput" name="hinh_anh_tham_khao[]" multiple
                        accept="image/*" class="d-none" onchange="kgAdminPreview(this)">
                </label>
                <div id="kgAdminImgPreview" class="d-flex flex-wrap gap-2 mt-3"></div>
            </div>
        </div>

    </div>

    {{-- ════ CỘT PHẢI ════ --}}
    <div class="col-12 col-lg-4">

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-3">
                <button type="submit" form="kgAdminForm" class="btn btn-primary w-100 mb-2 py-2">
                    <i class="fas fa-save me-1"></i> {{ $isEdit ? 'Lưu thay đổi' : 'Tạo ký gửi' }}
                </button>
                <a href="{{ route('nhanvien.admin.ky-gui.index') }}" class="btn btn-light border w-100 py-2">Hủy</a>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3"><i class="fas fa-cog text-secondary me-2"></i>Quyết định Thẩm định
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Trạng thái xử lý <span class="text-danger">*</span></label>
                    <select name="trang_thai" class="form-select bg-light border-secondary">
                        @foreach (\App\Models\KyGui::TRANG_THAI as $v => $info)
                            <option value="{{ $v }}" @selected(old('trang_thai', $isEdit ? $kyGui->trang_thai : 'cho_duyet') == $v)>{{ $info['label'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nhân viên Thẩm định</label>
                    <select name="nhan_vien_phu_trach_id" class="form-select">
                        <option value="">— Chưa phân công —</option>
                        @foreach (\App\Models\NhanVien::whereIn('vai_tro', ['admin', 'nguon_hang'])->where('kich_hoat', true)->get() as $nv)
                            <option value="{{ $nv->id }}" @selected(old('nhan_vien_phu_trach_id', $isEdit ? $kyGui->nhan_vien_phu_trach_id : '') == $nv->id)>{{ $nv->ho_ten }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-0">
                    <label class="form-label">Nguồn ký gửi</label>
                    <select name="nguon_ky_gui" class="form-select">
                        @foreach (['website' => 'Website', 'phone' => 'Điện thoại', 'sale' => 'Qua Sale', 'zalo' => 'Zalo', 'walk_in' => 'Trực tiếp'] as $v => $l)
                            <option value="{{ $v }}" @selected(old('nguon_ky_gui', $isEdit ? $kyGui->nguon_ky_gui : 'website') == $v)>{{ $l }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        @if ($isEdit)
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body" style="font-size: 0.8rem">
                    <div class="d-flex justify-content-between border-bottom pb-2 mb-2"><span>Mã Phiếu</span> <span
                            class="fw-bold text-primary font-monospace">#KG-{{ str_pad($kyGui->id, 5, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="d-flex justify-content-between"><span>Ngày gửi</span> <span
                            class="text-muted">{{ $kyGui->created_at->format('d/m/Y H:i') }}</span></div>
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Hiện ẩn giá
            document.querySelectorAll('.kg-nhu-cau-sel').forEach(sel => {
                sel.addEventListener('change', function() {
                    const banBox = document.getElementById('kgAdminBanBox');
                    const thueBox = document.getElementById('kgAdminThueBox');
                    if (this.value === 'ban') {
                        banBox.classList.remove('d-none');
                        thueBox.classList.add('d-none');
                    } else {
                        thueBox.classList.remove('d-none');
                        banBox.classList.add('d-none');
                    }
                });
            });

            // Xóa ảnh (Làm mờ khi click vào dấu X)
            document.querySelectorAll('.chk-delete-img').forEach(chk => {
                chk.addEventListener('change', function() {
                    const img = this.closest('div').querySelector('.img-chk-target');
                    if (this.checked) {
                        img.style.opacity = '0.3';
                        img.style.filter = 'grayscale(100%)';
                    } else {
                        img.style.opacity = '1';
                        img.style.filter = 'none';
                    }
                });
            });
        });

        // Preview đa ảnh
        function kgAdminPreview(input) {
            const preview = document.getElementById('kgAdminImgPreview');
            preview.innerHTML = '';
            Array.from(input.files).slice(0, 10).forEach(file => {
                const reader = new FileReader();
                reader.onload = e => {
                    const div = document.createElement('div');
                    div.className = 'border rounded';
                    div.style.cssText = 'width: 80px; height: 80px;';
                    div.innerHTML =
                        `<img src="${e.target.result}" class="w-100 h-100 object-fit-cover rounded">`;
                    preview.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        }
    </script>
@endpush
