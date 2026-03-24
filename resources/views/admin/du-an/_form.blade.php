@push('styles')
    <style>
        /* (Giữ nguyên toàn bộ CSS cũ tuyệt đẹp của bạn) */
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .page-header-left h1 {
            font-size: 1.4rem;
            font-weight: 700;
            color: #1a3c5e;
            margin: 0 0 4px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .page-header-left h1 i {
            color: #FF8C42;
        }

        .page-header-left p {
            color: #999;
            font-size: .85rem;
            margin: 0;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: #f0f4ff;
            color: #1a3c5e;
            border: 1.5px solid #d5e0f5;
            padding: 9px 18px;
            border-radius: 9px;
            font-weight: 600;
            font-size: .875rem;
            text-decoration: none;
            transition: all .2s;
        }

        .btn-back:hover {
            background: #1a3c5e;
            color: #fff;
            border-color: #1a3c5e;
        }

        .errors-summary {
            background: #fff5f5;
            border: 1px solid #fcc;
            border-radius: 10px;
            padding: 14px 18px;
            margin-bottom: 20px;
        }

        .errors-summary strong {
            color: #e74c3c;
            font-size: .875rem;
            display: block;
            margin-bottom: 6px;
        }

        .errors-summary ul {
            margin: 0;
            padding-left: 18px;
        }

        .errors-summary li {
            font-size: .82rem;
            color: #e74c3c;
            margin-bottom: 2px;
        }

        .form-layout {
            display: grid;
            grid-template-columns: 1fr 320px;
            gap: 20px;
            align-items: start;
        }

        @media(max-width: 960px) {
            .form-layout {
                grid-template-columns: 1fr;
            }
        }

        .form-card {
            background: #fff;
            border-radius: 14px;
            border: 1px solid #f0f0f0;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .06);
            margin-bottom: 20px;
            overflow: hidden;
        }

        .form-card:last-child {
            margin-bottom: 0;
        }

        .form-card-header {
            padding: 13px 20px;
            background: linear-gradient(135deg, #f8faff, #eef3ff);
            border-bottom: 1px solid #e8eeff;
            font-weight: 700;
            font-size: .88rem;
            color: #1a3c5e;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-card-header i {
            color: #FF8C42;
        }

        .form-card-body {
            padding: 20px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group:last-child {
            margin-bottom: 0;
        }

        .form-label {
            display: block;
            font-weight: 600;
            font-size: .78rem;
            color: #555;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: .4px;
        }

        .form-label.required::after {
            content: ' *';
            color: #e74c3c;
        }

        .label-hint {
            font-weight: 400;
            color: #aaa;
            font-size: .74rem;
            margin-left: 6px;
            text-transform: none;
        }

        .form-field {
            width: 100%;
            height: 42px;
            border: 1.5px solid #e8e8e8;
            border-radius: 9px;
            padding: 0 14px;
            font-size: .875rem;
            color: #333;
            background: #fafafa;
            outline: none;
            font-family: inherit;
            transition: border .2s, background .2s, box-shadow .2s;
        }

        .form-field:focus {
            border-color: #FF8C42;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(255, 140, 66, .1);
        }

        .form-field.is-error {
            border-color: #e74c3c;
            background: #fff8f8;
        }

        textarea.form-field {
            height: auto;
            padding: 12px 14px;
            resize: vertical;
        }

        select.form-field {
            appearance: none;
            cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath fill='%23999' d='M5 6L0 0h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
            padding-right: 36px;
        }

        .field-error {
            font-size: .78rem;
            color: #e74c3c;
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .form-row-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }

        @media(max-width: 600px) {
            .form-row-2 {
                grid-template-columns: 1fr;
            }
        }

        .toggle-group {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 4px 0;
            gap: 12px;
        }

        .toggle-info {
            flex: 1;
        }

        .toggle-label-text {
            font-weight: 600;
            font-size: .875rem;
            color: #333;
            display: block;
            margin-bottom: 2px;
        }

        .toggle-info small {
            color: #aaa;
            font-size: .78rem;
        }

        .switch {
            position: relative;
            width: 46px;
            height: 26px;
            flex-shrink: 0;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .switch-slider {
            position: absolute;
            inset: 0;
            background: #ddd;
            border-radius: 13px;
            cursor: pointer;
            transition: .3s;
        }

        .switch-slider::before {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            background: #fff;
            border-radius: 50%;
            left: 3px;
            top: 3px;
            transition: .3s;
            box-shadow: 0 1px 4px rgba(0, 0, 0, .25);
        }

        .switch input:checked+.switch-slider {
            background: #27ae60;
        }

        .switch input:checked+.switch-slider::before {
            transform: translateX(20px);
        }

        .status-select-wrap {
            position: relative;
        }

        .status-hint {
            font-size: .75rem;
            margin-top: 5px;
            padding: 5px 10px;
            border-radius: 7px;
            display: inline-block;
            font-weight: 600;
        }

        .status-hint.sap {
            background: #fff4ec;
            color: #FF8C42;
        }

        .status-hint.dang {
            background: #e8f8f0;
            color: #27ae60;
        }

        .status-hint.het {
            background: #ffeaea;
            color: #e74c3c;
        }

        .current-image {
            margin-bottom: 12px;
        }

        .current-image img {
            width: 100%;
            max-height: 190px;
            object-fit: cover;
            border-radius: 10px;
            border: 1px solid #eee;
            display: block;
        }

        .current-image-label {
            font-size: .78rem;
            color: #27ae60;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .img-placeholder {
            width: 100%;
            height: 120px;
            background: linear-gradient(135deg, #f8faff, #eef3ff);
            border: 2px dashed #d5e0f5;
            border-radius: 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 8px;
            color: #c0cfe0;
            margin-bottom: 12px;
        }

        .img-placeholder i {
            font-size: 2rem;
        }

        .img-placeholder span {
            font-size: .82rem;
            font-weight: 500;
        }

        .img-preview-new {
            width: 100%;
            max-height: 190px;
            object-fit: cover;
            border-radius: 10px;
            border: 2px solid #FF8C42;
            display: none;
            margin-bottom: 10px;
        }

        .upload-box {
            text-align: center;
        }

        .upload-input {
            display: none;
        }

        .upload-btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: #f0f4ff;
            color: #1a3c5e;
            border: 1.5px solid #d5e0f5;
            padding: 9px 20px;
            border-radius: 9px;
            font-weight: 600;
            font-size: .83rem;
            cursor: pointer;
            transition: all .2s;
        }

        .upload-btn:hover {
            background: #1a3c5e;
            color: #fff;
            border-color: #1a3c5e;
        }

        .upload-hint {
            display: block;
            font-size: .75rem;
            color: #bbb;
            margin-top: 6px;
        }

        .img-filename {
            font-size: .78rem;
            color: #FF8C42;
            margin-top: 5px;
            font-weight: 500;
        }

        .form-actions {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .btn-save {
            width: 100%;
            height: 46px;
            background: linear-gradient(135deg, #FF8C42, #f5a623);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-weight: 700;
            font-size: .95rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all .2s;
            box-shadow: 0 4px 12px rgba(255, 140, 66, .3);
        }

        .btn-save:hover {
            background: linear-gradient(135deg, #e67e22, #FF8C42);
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(255, 140, 66, .4);
        }

        .btn-cancel {
            width: 100%;
            height: 40px;
            background: #f5f5f5;
            color: #888;
            border: 1.5px solid #e0e0e0;
            border-radius: 10px;
            font-weight: 600;
            font-size: .875rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all .2s;
        }

        .btn-cancel:hover {
            background: #ffeee0;
            color: #FF8C42;
            border-color: #FF8C42;
        }

        .divider {
            border: none;
            border-top: 1px solid #f5f5f5;
            margin: 14px 0;
        }
    </style>
@endpush

<div class="form-layout">

    {{-- CỘT TRÁI --}}
    <div>
        {{-- THÔNG TIN CƠ BẢN --}}
        <div class="form-card">
            <div class="form-card-header"><i class="fas fa-info-circle"></i> Thông tin cơ bản</div>
            <div class="form-card-body">
                <div class="form-group">
                    <label class="form-label required">Tên dự án</label>
                    <input type="text" name="ten_du_an" class="form-field @error('ten_du_an') is-error @enderror"
                        value="{{ old('ten_du_an', $duAn->ten_du_an ?? '') }}" placeholder="VD: Vinhomes Smart City"
                        autofocus>
                    @error('ten_du_an')
                        <div class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="form-row-2">
                    <div class="form-group">
                        <label class="form-label required">Khu vực</label>
                        <select name="khu_vuc_id" class="form-field @error('khu_vuc_id') is-error @enderror">
                            <option value="">-- Chọn khu vực --</option>
                            @foreach ($khuVucs as $kv)
                                <option value="{{ $kv->id }}"
                                    {{ old('khu_vuc_id', $duAn->khu_vuc_id ?? '') == $kv->id ? 'selected' : '' }}>
                                    {{ $kv->ten_khu_vuc }}</option>
                            @endforeach
                        </select>
                        @error('khu_vuc_id')
                            <div class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label required">Địa chỉ</label>
                        <input type="text" name="dia_chi" class="form-field @error('dia_chi') is-error @enderror"
                            value="{{ old('dia_chi', $duAn->dia_chi ?? '') }}"
                            placeholder="VD: Tây Mỗ, Nam Từ Liêm, Hà Nội">
                        @error('dia_chi')
                            <div class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-row-2">
                    <div class="form-group">
                        <label class="form-label">Chủ đầu tư</label>
                        <input type="text" name="chu_dau_tu" class="form-field"
                            value="{{ old('chu_dau_tu', $duAn->chu_dau_tu ?? '') }}" placeholder="VD: Vinhomes JSC">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Đơn vị thi công</label>
                        <input type="text" name="don_vi_thi_cong" class="form-field"
                            value="{{ old('don_vi_thi_cong', $duAn->don_vi_thi_cong ?? '') }}"
                            placeholder="VD: Coteccons">
                    </div>
                </div>

                <div class="form-row-2">
                    <div class="form-group">
                        <label class="form-label">Video URL <span class="label-hint">YouTube, Vimeo...</span></label>
                        <input type="url" name="video_url" class="form-field"
                            value="{{ old('video_url', $duAn->video_url ?? '') }}"
                            placeholder="https://youtube.com/...">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Google Maps URL</label>
                        <input type="text" name="map_url" class="form-field"
                            value="{{ old('map_url', $duAn->map_url ?? '') }}"
                            placeholder="https://maps.google.com/...">
                    </div>
                </div>
            </div>
        </div>

        {{-- MÔ TẢ & NỘI DUNG (CKEDITOR ĐƯỢC TÍCH HỢP Ở ĐÂY) --}}
        <div class="form-card">
            <div class="form-card-header"><i class="fas fa-align-left"></i> Mô tả chi tiết</div>
            <div class="form-card-body">
                <div class="form-group">
                    <label class="form-label">Mô tả ngắn <span class="label-hint">Hiển thị ở trang danh sách, tối đa 500
                            ký tự</span></label>
                    <textarea name="mo_ta_ngan" class="form-field @error('mo_ta_ngan') is-error @enderror" rows="3" maxlength="500"
                        placeholder="Tóm tắt ngắn gọn về dự án...">{{ old('mo_ta_ngan', $duAn->mo_ta_ngan ?? '') }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Nội dung chi tiết <span class="label-hint">Dùng công cụ để soạn thảo giống
                            bài viết</span></label>
                    {{-- Gắn ID để CKEditor nhận diện --}}
                    <textarea name="noi_dung_chi_tiet" id="noiDungChiTietDuAn" class="form-field">{{ old('noi_dung_chi_tiet', $duAn->noi_dung_chi_tiet ?? '') }}</textarea>
                </div>
            </div>
        </div>

        {{-- SEO --}}
        <div class="form-card">
            <div class="form-card-header"><i class="fas fa-search"></i> SEO <span
                    style="font-weight:400;color:#aaa;font-size:.78rem;margin-left:4px">(không bắt buộc)</span></div>
            <div class="form-card-body">
                <div class="form-group">
                    <label class="form-label">SEO Title</label>
                    <input type="text" name="seo_title" class="form-field"
                        value="{{ old('seo_title', $duAn->seo_title ?? '') }}"
                        placeholder="Tiêu đề SEO — mặc định dùng tên dự án">
                </div>
                <div class="form-group">
                    <label class="form-label">SEO Description</label>
                    <textarea name="seo_description" class="form-field" rows="3" placeholder="Mô tả SEO, tối đa 160 ký tự...">{{ old('seo_description', $duAn->seo_description ?? '') }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Từ khóa <span class="label-hint">Cách nhau bằng dấu phẩy</span></label>
                    <input type="text" name="seo_keywords" class="form-field"
                        value="{{ old('seo_keywords', $duAn->seo_keywords ?? '') }}"
                        placeholder="vinhomes, smart city, hà nội...">
                </div>
            </div>
        </div>
    </div>

    {{-- CỘT PHẢI --}}
    <div>
        {{-- NÚT LƯU --}}
        <div class="form-actions" style="margin-bottom:20px">
            <button type="submit" class="btn-save">
                <i class="fas fa-save"></i> {{ $duAn ? 'Cập nhật dự án' : 'Lưu dự án mới' }}
            </button>
            <a href="{{ route('nhanvien.admin.du-an.index') }}" class="btn-cancel">Hủy bỏ</a>
        </div>

        {{-- CÀI ĐẶT --}}
        <div class="form-card">
            <div class="form-card-header"><i class="fas fa-sliders-h"></i> Cài đặt</div>
            <div class="form-card-body">
                <div class="toggle-group">
                    <div class="toggle-info">
                        <span class="toggle-label-text">Hiển thị trên website</span>
                        <small>Khách hàng có thể xem dự án này</small>
                    </div>
                    <label class="switch">
                        <input type="checkbox" name="hien_thi" value="1"
                            {{ old('hien_thi', $duAn->hien_thi ?? true) ? 'checked' : '' }}>
                        <span class="switch-slider"></span>
                    </label>
                </div>
                <hr class="divider">
                <div class="toggle-group">
                    <div class="toggle-info">
                        <span class="toggle-label-text">Dự án nổi bật</span>
                        <small>Hiển thị ở trang chủ / banner</small>
                    </div>
                    <label class="switch">
                        <input type="checkbox" name="noi_bat" value="1"
                            {{ old('noi_bat', $duAn->noi_bat ?? false) ? 'checked' : '' }}>
                        <span class="switch-slider"></span>
                    </label>
                </div>
                <hr class="divider">
                <div class="form-group">
                    <label class="form-label">Trạng thái mở bán</label>
                    <select name="trang_thai" class="form-field" id="selectTrangThai">
                        <option value="sap_mo_ban"
                            {{ old('trang_thai', $duAn->trang_thai ?? '') === 'sap_mo_ban' ? 'selected' : '' }}>🔔 Sắp
                            mở bán</option>
                        <option value="dang_mo_ban"
                            {{ old('trang_thai', $duAn->trang_thai ?? 'dang_mo_ban') === 'dang_mo_ban' ? 'selected' : '' }}>
                            ✅ Đang mở bán</option>
                        <option value="da_ban_het"
                            {{ old('trang_thai', $duAn->trang_thai ?? '') === 'da_ban_het' ? 'selected' : '' }}>🔴 Đã
                            bán hết</option>
                    </select>
                    <div id="statusHint" class="status-hint dang" style="margin-top:6px">✅ Đang mở bán</div>
                </div>
                <hr class="divider">
                <div class="form-group">
                    <label class="form-label">Thứ tự hiển thị <span class="label-hint">Số nhỏ lên trước</span></label>
                    <input type="number" name="thu_tu_hien_thi" class="form-field" min="0" max="999"
                        style="width:110px" value="{{ old('thu_tu_hien_thi', $duAn->thu_tu_hien_thi ?? 0) }}">
                </div>
            </div>
        </div>

        {{-- ẢNH ĐẠI DIỆN --}}
        <div class="form-card">
            <div class="form-card-header"><i class="fas fa-image"></i> Ảnh đại diện</div>
            <div class="form-card-body">
                @if (!empty($duAn->hinh_anh_dai_dien))
                    <div class="current-image" id="currentImage">
                        {{-- ĐÃ SỬA: Dùng hàm asset('storage/...') để gọi ảnh chuẩn xác --}}
                        <img src="{{ asset('storage/' . $duAn->hinh_anh_dai_dien) }}" alt="Ảnh hiện tại">
                        <div class="current-image-label"><i class="fas fa-check-circle"></i> Ảnh hiện tại</div>
                    </div>
                @else
                    <div class="img-placeholder" id="imgPlaceholder">
                        <i class="fas fa-cloud-upload-alt"></i><span>Chưa có ảnh</span>
                    </div>
                @endif

                <img src="" id="imgPreviewNew" class="img-preview-new" alt="Preview">
                <div class="img-filename" id="imgFilename"></div>

                <div class="upload-box">
                    <input type="file" name="hinh_anh_dai_dien" id="inputHinhAnh"
                        accept="image/jpeg,image/png,image/webp" class="upload-input">
                    <label for="inputHinhAnh" class="upload-btn">
                        <i class="fas fa-folder-open"></i>
                        {{ !empty($duAn->hinh_anh_dai_dien) ? 'Thay ảnh mới' : 'Chọn ảnh' }}
                    </label>
                    <small class="upload-hint">JPG, PNG, WEBP — tối đa 3MB</small>
                </div>
                @error('hinh_anh_dai_dien')
                    <div class="field-error" style="margin-top:8px"><i class="fas fa-exclamation-circle"></i>
                        {{ $message }}</div>
                @enderror
            </div>
        </div>

    </div>
</div>

@push('scripts')
    {{-- KHAI BÁO THƯ VIỆN CKEDITOR --}}
    <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
    <script>
        // Kích hoạt CKEditor cho mô tả chi tiết
        if (document.getElementById('noiDungChiTietDuAn')) {
            CKEDITOR.replace('noiDungChiTietDuAn', {
                height: 400,
                language: 'vi'
            });
        }

        // Bắt sự kiện form submit để nạp data từ CKEditor vào <textarea> ẩn
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', function() {
                if (CKEDITOR.instances['noiDungChiTietDuAn']) {
                    CKEDITOR.instances['noiDungChiTietDuAn'].updateElement();
                }
            });
        });

        // Preview ảnh
        document.getElementById('inputHinhAnh')?.addEventListener('change', function() {
            const file = this.files[0];
            if (!file) return;
            if (file.size > 3 * 1024 * 1024) {
                alert('⚠️ Ảnh vượt quá 3MB, vui lòng chọn ảnh nhỏ hơn!');
                this.value = '';
                return;
            }
            const reader = new FileReader();
            reader.onload = e => {
                const preview = document.getElementById('imgPreviewNew');
                preview.src = e.target.result;
                preview.style.display = 'block';
                document.getElementById('currentImage')?.style.setProperty('display', 'none');
                document.getElementById('imgPlaceholder')?.style.setProperty('display', 'none');
                document.getElementById('imgFilename').textContent = '📎 ' + file.name;
            };
            reader.readAsDataURL(file);
        });

        // Hint trạng thái
        const selectTrangThai = document.getElementById('selectTrangThai');
        const statusHint = document.getElementById('statusHint');
        const statusMap = {
            'sap_mo_ban': {
                cls: 'sap',
                text: '🔔 Sắp mở bán'
            },
            'dang_mo_ban': {
                cls: 'dang',
                text: '✅ Đang mở bán'
            },
            'da_ban_het': {
                cls: 'het',
                text: '🔴 Đã bán hết'
            },
        };

        function updateStatusHint(val) {
            const s = statusMap[val] || statusMap['dang_mo_ban'];
            statusHint.className = 'status-hint ' + s.cls;
            statusHint.textContent = s.text;
        }
        selectTrangThai?.addEventListener('change', e => updateStatusHint(e.target.value));
        updateStatusHint(selectTrangThai?.value);
    </script>
@endpush
