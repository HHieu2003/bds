<div class="row g-4">
    {{-- ════ CỘT TRÁI (Nội dung chính) ════ --}}
    <div class="col-12 col-lg-8">

        {{-- THÔNG TIN CƠ BẢN --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3"><i class="fas fa-info-circle text-primary me-2"></i>Thông tin cơ bản
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Tên dự án <span class="text-danger">*</span></label>
                    <input type="text" name="ten_du_an" class="form-control @error('ten_du_an') is-invalid @enderror"
                        value="{{ old('ten_du_an', $duAn->ten_du_an ?? '') }}" placeholder="VD: Vinhomes Smart City"
                        autofocus>
                    @error('ten_du_an')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Khu vực <span class="text-danger">*</span></label>
                        <select name="khu_vuc_id" class="form-select @error('khu_vuc_id') is-invalid @enderror">
                            <option value="">-- Chọn khu vực --</option>
                            @foreach ($khuVucs as $kv)
                                <option value="{{ $kv->id }}" @selected(old('khu_vuc_id', $duAn->khu_vuc_id ?? '') == $kv->id)>{{ $kv->ten_khu_vuc }}
                                </option>
                            @endforeach
                        </select>
                        @error('khu_vuc_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Địa chỉ <span class="text-danger">*</span></label>
                        <input type="text" name="dia_chi" class="form-control @error('dia_chi') is-invalid @enderror"
                            value="{{ old('dia_chi', $duAn->dia_chi ?? '') }}"
                            placeholder="VD: Tây Mỗ, Nam Từ Liêm, Hà Nội">
                        @error('dia_chi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Chủ đầu tư</label>
                        <input type="text" name="chu_dau_tu" class="form-control"
                            value="{{ old('chu_dau_tu', $duAn->chu_dau_tu ?? '') }}" placeholder="VD: Vinhomes JSC">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Đơn vị thi công</label>
                        <input type="text" name="don_vi_thi_cong" class="form-control"
                            value="{{ old('don_vi_thi_cong', $duAn->don_vi_thi_cong ?? '') }}"
                            placeholder="VD: Coteccons">
                    </div>
                </div>

                <div class="row g-3 mb-0">
                    <div class="col-md-6">
                        <label class="form-label">Video URL <span class="text-muted fw-normal">(YouTube,
                                Vimeo)</span></label>
                        <input type="url" name="video_url" class="form-control"
                            value="{{ old('video_url', $duAn->video_url ?? '') }}"
                            placeholder="https://youtube.com/...">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Google Maps URL</label>
                        <input type="text" name="map_url" class="form-control"
                            value="{{ old('map_url', $duAn->map_url ?? '') }}"
                            placeholder="Dán link hoặc iframe bản đồ">
                    </div>
                </div>
            </div>
        </div>

        {{-- MÔ TẢ & NỘI DUNG --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3"><i class="fas fa-align-left text-success me-2"></i>Mô tả chi tiết
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Mô tả ngắn <span class="text-muted fw-normal">(Hiển thị ở trang danh
                            sách)</span></label>
                    <textarea name="mo_ta_ngan" class="form-control @error('mo_ta_ngan') is-invalid @enderror" rows="3"
                        maxlength="500" placeholder="Tóm tắt ngắn gọn về dự án...">{{ old('mo_ta_ngan', $duAn->mo_ta_ngan ?? '') }}</textarea>
                </div>
                <div class="mb-0">
                    <label class="form-label">Nội dung chi tiết</label>
                    <textarea name="noi_dung_chi_tiet" id="noiDungChiTietDuAn" class="form-control">{{ old('noi_dung_chi_tiet', $duAn->noi_dung_chi_tiet ?? '') }}</textarea>
                </div>
            </div>
        </div>

        {{-- SEO --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3"><i class="fas fa-search text-warning me-2"></i>Tối ưu SEO <span
                    class="text-muted fw-normal">(Không bắt buộc)</span></div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">SEO Title</label>
                    <input type="text" name="seo_title" class="form-control"
                        value="{{ old('seo_title', $duAn->seo_title ?? '') }}"
                        placeholder="Tiêu đề SEO (mặc định lấy tên dự án)">
                </div>
                <div class="mb-3">
                    <label class="form-label">SEO Description</label>
                    <textarea name="seo_description" class="form-control" rows="2" placeholder="Mô tả SEO, tối đa 160 ký tự...">{{ old('seo_description', $duAn->seo_description ?? '') }}</textarea>
                </div>
                <div class="mb-0">
                    <label class="form-label">SEO Keywords</label>
                    <input type="text" name="seo_keywords" class="form-control"
                        value="{{ old('seo_keywords', $duAn->seo_keywords ?? '') }}"
                        placeholder="Cách nhau bằng dấu phẩy">
                </div>
            </div>
        </div>
    </div>

    {{-- ════ CỘT PHẢI (Cài đặt & Hình ảnh) ════ --}}
    <div class="col-12 col-lg-4">

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-3">
                <button type="submit" class="btn btn-primary w-100 mb-2 py-2">
                    <i class="fas fa-save me-1"></i> {{ $duAn ? 'Cập nhật dự án' : 'Lưu dự án mới' }}
                </button>
                <a href="{{ route('nhanvien.admin.du-an.index') }}" class="btn btn-light border w-100 py-2">Hủy
                    bỏ</a>
            </div>
        </div>

        {{-- CÀI ĐẶT --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3"><i class="fas fa-cog text-secondary me-2"></i>Cài đặt hiển thị
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <div class="fw-bold text-dark" style="font-size: 0.85rem">Hiển thị website</div>
                        <div class="text-muted" style="font-size: 0.75rem">Khách hàng có thể xem</div>
                    </div>
                    <label class="toggle-sw">
                        <input type="checkbox" name="hien_thi" value="1" @checked(old('hien_thi', $duAn->hien_thi ?? true))>
                        <span class="toggle-sw-track"><span class="toggle-sw-thumb"></span></span>
                    </label>
                </div>
                <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
                    <div>
                        <div class="fw-bold text-dark" style="font-size: 0.85rem">Dự án nổi bật</div>
                        <div class="text-muted" style="font-size: 0.75rem">Hiển thị banner trang chủ</div>
                    </div>
                    <label class="toggle-sw">
                        <input type="checkbox" name="noi_bat" value="1" @checked(old('noi_bat', $duAn->noi_bat ?? false))>
                        <span class="toggle-sw-track"><span class="toggle-sw-thumb"></span></span>
                    </label>
                </div>

                <div class="mb-3">
                    <label class="form-label">Trạng thái mở bán</label>
                    <select name="trang_thai" class="form-select" id="selectTrangThai">
                        <option value="sap_mo_ban" @selected(old('trang_thai', $duAn->trang_thai ?? '') === 'sap_mo_ban')>🔔 Sắp mở bán</option>
                        <option value="dang_mo_ban" @selected(old('trang_thai', $duAn->trang_thai ?? 'dang_mo_ban') === 'dang_mo_ban')>✅ Đang mở bán</option>
                        <option value="da_ban_het" @selected(old('trang_thai', $duAn->trang_thai ?? '') === 'da_ban_het')>🔴 Đã bán hết</option>
                    </select>
                    <div id="statusBadge" class="mt-2 text-center"></div>
                </div>

                <div class="mb-0">
                    <label class="form-label">Thứ tự hiển thị <span class="text-muted fw-normal">(Số nhỏ lên
                            trước)</span></label>
                    <input type="number" name="thu_tu_hien_thi" class="form-control" min="0"
                        value="{{ old('thu_tu_hien_thi', $duAn->thu_tu_hien_thi ?? 0) }}">
                </div>
            </div>
        </div>

        {{-- ẢNH ĐẠI DIỆN --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3"><i class="fas fa-image text-info me-2"></i>Ảnh đại diện</div>
            <div class="card-body text-center">
                @if (!empty($duAn->hinh_anh_dai_dien))
                    <div id="currentImageWrapper" class="mb-3">
                        <img src="{{ asset('storage/' . $duAn->hinh_anh_dai_dien) }}"
                            class="img-fluid rounded border mb-2"
                            style="max-height: 200px; object-fit: cover; width: 100%" alt="Ảnh hiện tại">
                        <span class="badge bg-success bg-opacity-10 text-success"><i
                                class="fas fa-check-circle me-1"></i>Đã có ảnh</span>
                    </div>
                @else
                    <div id="imgPlaceholder" class="p-4 mb-3 rounded bg-light border border-dashed text-muted">
                        <i class="fas fa-cloud-upload-alt fs-2 mb-2"></i>
                        <div style="font-size: 0.85rem">Chưa có ảnh đại diện</div>
                    </div>
                @endif

                <img src="" id="imgPreviewNew" class="img-fluid rounded border mb-2 d-none"
                    style="max-height: 200px; object-fit: cover; width: 100%">
                <div id="imgFilename" class="text-primary fw-bold mb-3" style="font-size: 0.8rem"></div>

                <div class="position-relative">
                    <input type="file" name="hinh_anh_dai_dien" id="inputHinhAnh"
                        accept="image/jpeg,image/png,image/webp" class="d-none">
                    <label for="inputHinhAnh" class="btn btn-light border border-secondary border-opacity-25 w-100">
                        <i class="fas fa-folder-open me-1"></i>
                        {{ !empty($duAn->hinh_anh_dai_dien) ? 'Đổi ảnh khác' : 'Chọn ảnh tải lên' }}
                    </label>
                    <div class="text-muted mt-2" style="font-size: 0.75rem">JPG, PNG, WEBP (Tối đa 3MB)</div>
                </div>
                @error('hinh_anh_dai_dien')
                    <div class="text-danger mt-2" style="font-size: 0.8rem"><i class="fas fa-exclamation-circle"></i>
                        {{ $message }}</div>
                @enderror
            </div>
        </div>

    </div>
</div>

@push('scripts')
    <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // CKEditor
            if (document.getElementById('noiDungChiTietDuAn')) {
                CKEDITOR.replace('noiDungChiTietDuAn', {
                    height: 350,
                    language: 'vi'
                });
            }
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', () => {
                    if (CKEDITOR.instances['noiDungChiTietDuAn']) CKEDITOR.instances[
                        'noiDungChiTietDuAn'].updateElement();
                });
            });

            // Image Preview
            document.getElementById('inputHinhAnh')?.addEventListener('change', function() {
                const file = this.files[0];
                if (!file) return;
                if (file.size > 3 * 1024 * 1024) {
                    alert('⚠️ Ảnh vượt quá 3MB!');
                    this.value = '';
                    return;
                }
                const reader = new FileReader();
                reader.onload = e => {
                    const preview = document.getElementById('imgPreviewNew');
                    preview.src = e.target.result;
                    preview.classList.remove('d-none');

                    const currImg = document.getElementById('currentImageWrapper');
                    if (currImg) currImg.style.display = 'none';

                    const placeholder = document.getElementById('imgPlaceholder');
                    if (placeholder) placeholder.style.display = 'none';

                    document.getElementById('imgFilename').textContent = '📎 ' + file.name;
                };
                reader.readAsDataURL(file);
            });

            // Status Badge UI
            const selectTrangThai = document.getElementById('selectTrangThai');
            const badge = document.getElementById('statusBadge');

            function updateBadge() {
                const val = selectTrangThai.value;
                if (val === 'sap_mo_ban') badge.innerHTML =
                    '<span class="badge bg-warning text-dark w-100 py-2">🔔 Sắp mở bán</span>';
                else if (val === 'dang_mo_ban') badge.innerHTML =
                    '<span class="badge bg-success w-100 py-2">✅ Đang mở bán</span>';
                else badge.innerHTML = '<span class="badge bg-danger w-100 py-2">🔴 Đã bán hết</span>';
            }
            selectTrangThai?.addEventListener('change', updateBadge);
            if (selectTrangThai) updateBadge();
        });
    </script>
@endpush
