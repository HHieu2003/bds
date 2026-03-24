@php
    $isEdit = isset($baiViet) && $baiViet !== null;
    $oldLoai = old('loai_bai_viet', $isEdit ? $baiViet->loai_bai_viet : 'tin_tuc');
@endphp

<div class="bv-fg-grid">
    {{-- ══════════ CỘT TRÁI ══════════ --}}
    <div class="bv-fg-left">
        {{-- TIÊU ĐỀ & MÔ TẢ --}}
        <div class="bv-fc">
            <div class="bv-fc-head"><i class="fas fa-heading"></i> Nội dung bài viết</div>
            <div class="bv-fc-body">
                <div class="bv-fg">
                    <label class="bv-fl req">Tiêu đề bài viết</label>
                    <input type="text" name="tieu_de" id="bv_tieu_de"
                        class="bv-fi @error('tieu_de') bv-fi-err @enderror"
                        value="{{ old('tieu_de', $isEdit ? $baiViet->tieu_de : '') }}"
                        placeholder="Nhập tiêu đề hấp dẫn..." required autofocus>
                    <div class="bv-slug-preview">
                        Slug: <span id="slugPreview" style="color:#2d6a9f">{{ $isEdit ? $baiViet->slug : '---' }}</span>
                    </div>
                    @error('tieu_de')
                        <div class="bv-fe"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="bv-fg">
                    <label class="bv-fl">Mô tả ngắn <span class="bv-hint">Tóm tắt nội dung (tối đa 300 ký
                            tự)</span></label>
                    <textarea name="mo_ta_ngan" class="bv-fi @error('mo_ta_ngan') bv-fi-err @enderror" rows="3" maxlength="300"
                        placeholder="Nhập mô tả...">{{ old('mo_ta_ngan', $isEdit ? $baiViet->mo_ta_ngan : '') }}</textarea>
                    @error('mo_ta_ngan')
                        <div class="bv-fe"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        {{-- NỘI DUNG CHI TIẾT (CKEDITOR) --}}
        <div class="bv-fc">
            <div class="bv-fc-head"><i class="fas fa-align-left"></i> Nội dung chi tiết</div>
            <div class="bv-fc-body" style="padding:0">
                <textarea name="noi_dung" id="bvEditor" class="@error('noi_dung') bv-fi-err @enderror">{{ old('noi_dung', $isEdit ? $baiViet->noi_dung : '') }}</textarea>
                @error('noi_dung')
                    <div class="bv-fe" style="padding:10px 18px"><i class="fas fa-exclamation-circle"></i>
                        {{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- SEO --}}
        <div class="bv-fc">
            <div class="bv-fc-head"><i class="fas fa-search"></i> Tối ưu SEO</div>
            <div class="bv-fc-body">
                <div class="bv-fg">
                    <label class="bv-fl">SEO Title</label>
                    <input type="text" name="seo_title" class="bv-fi"
                        value="{{ old('seo_title', $isEdit ? $baiViet->seo_title : '') }}">
                </div>
                <div class="bv-fg">
                    <label class="bv-fl">SEO Description</label>
                    <textarea name="seo_description" class="bv-fi" rows="3">{{ old('seo_description', $isEdit ? $baiViet->seo_description : '') }}</textarea>
                </div>
                <div class="bv-fg">
                    <label class="bv-fl">SEO Keywords</label>
                    <input type="text" name="seo_keywords" class="bv-fi"
                        value="{{ old('seo_keywords', $isEdit ? $baiViet->seo_keywords : '') }}"
                        placeholder="Ngăn cách bằng dấu phẩy">
                </div>
            </div>
        </div>
    </div>

    {{-- ══════════ CỘT PHẢI ══════════ --}}
    <div class="bv-fg-right">
        {{-- NÚT LƯU --}}
        <div class="bv-fc">
            <div class="bv-fc-body" style="padding:16px">
                <button type="submit" class="bv-btn-save">
                    <i class="fas fa-paper-plane"></i> {{ $isEdit ? 'Lưu thay đổi' : 'Đăng bài viết' }}
                </button>
                <a href="{{ route('nhanvien.admin.bai-viet.index') }}" class="bv-btn-cancel">Hủy bỏ</a>
            </div>
        </div>

        {{-- PHÂN LOẠI --}}
        <div class="bv-fc">
            <div class="bv-fc-head"><i class="fas fa-tags"></i> Phân loại</div>
            <div class="bv-fc-body">
                <div class="bv-fg">
                    <select name="loai_bai_viet" class="bv-fi">
                        <option value="tin_tuc" @selected($oldLoai == 'tin_tuc')>📰 Tin tức thị trường</option>
                        <option value="phong_thuy" @selected($oldLoai == 'phong_thuy')>🧿 Phong thủy</option>
                        <option value="kien_thuc" @selected($oldLoai == 'kien_thuc')>📚 Kiến thức BĐS</option>
                        <option value="tuyen_dung" @selected($oldLoai == 'tuyen_dung')>💼 Tuyển dụng</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- ẢNH ĐẠI DIỆN --}}
        <div class="bv-fc">
            <div class="bv-fc-head"><i class="fas fa-image"></i> Ảnh đại diện</div>
            <div class="bv-fc-body">
                <div class="bv-img-upload">
                    <div class="bv-img-preview" id="thumbPreview"
                        onclick="document.getElementById('thumbInput').click()">
                        @if ($isEdit && $baiViet->hinh_anh)
                            <img src="{{ asset('storage/' . $baiViet->hinh_anh) }}" alt="Ảnh bìa"
                                style="width:100%;height:100%;object-fit:cover">
                        @else
                            <div class="bv-img-placeholder">
                                <i class="fas fa-cloud-upload-alt"></i><span>Chọn ảnh</span>
                            </div>
                        @endif
                    </div>
                    <input type="file" id="thumbInput" name="hinh_anh" accept="image/jpeg,image/png,image/webp"
                        style="display:none" onchange="previewThumb(this)">

                    @if ($isEdit && $baiViet->hinh_anh)
                        <label
                            style="cursor:pointer;font-size:0.8rem;color:#e74c3c;margin-top:8px;display:block;text-align:center;">
                            <input type="checkbox" name="xoa_hinh_anh" value="1"> Xóa ảnh hiện tại
                        </label>
                    @endif
                </div>
                @error('hinh_anh')
                    <div class="bv-fe"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- TÙY CHỌN & THIẾT LẬP --}}
        <div class="bv-fc">
            <div class="bv-fc-head"><i class="fas fa-cog"></i> Thiết lập</div>
            <div class="bv-fc-body">
                <div class="bv-tg-row">
                    <div class="bv-tg-info"><span>Hiển thị</span><small>Công khai trên web</small></div>
                    <label class="bv-sw">
                        <input type="checkbox" name="hien_thi" value="1"
                            {{ old('hien_thi', $isEdit ? $baiViet->hien_thi : true) ? 'checked' : '' }}>
                        <span class="bv-sw-track"><span class="bv-sw-thumb"></span></span>
                    </label>
                </div>
                <div class="bv-divider"></div>
                <div class="bv-tg-row">
                    <div class="bv-tg-info"><span>Nổi bật</span><small>Lên trang chủ</small></div>
                    <label class="bv-sw">
                        <input type="checkbox" name="noi_bat" value="1"
                            {{ old('noi_bat', $isEdit ? $baiViet->noi_bat : false) ? 'checked' : '' }}>
                        <span class="switch-slider"></span><span class="bv-sw-track"><span
                                class="bv-sw-thumb"></span></span>
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
    <style>
        /* CSS Giao diện đẹp của bạn giữ nguyên */
        .bv-fg-grid {
            display: grid;
            grid-template-columns: 1fr 300px;
            gap: 20px;
            align-items: start;
        }

        @media(max-width:980px) {
            .bv-fg-grid {
                grid-template-columns: 1fr;
            }

            .bv-fg-right {
                order: -1;
            }
        }

        .bv-fc {
            background: #fff;
            border-radius: 14px;
            border: 1px solid #f0f2f5;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .05);
            margin-bottom: 18px;
            overflow: hidden;
        }

        .bv-fc-head {
            padding: 12px 18px;
            background: linear-gradient(135deg, #f8faff, #eef3ff);
            border-bottom: 1px solid #e8eeff;
            font-weight: 700;
            font-size: .86rem;
            color: #1a3c5e;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .bv-fc-body {
            padding: 18px;
        }

        .bv-fg {
            margin-bottom: 14px;
        }

        .bv-fl {
            display: block;
            font-weight: 700;
            font-size: .75rem;
            color: #666;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .bv-fl.req::after {
            content: ' *';
            color: #e74c3c;
        }

        .bv-fi {
            width: 100%;
            border: 1.5px solid #e8e8e8;
            border-radius: 8px;
            padding: 9px 12px;
            font-size: .875rem;
            background: #fafafa;
            outline: none;
            transition: 0.2s;
            box-sizing: border-box;
        }

        .bv-fi:focus {
            border-color: #FF8C42;
            background: #fff;
        }

        .bv-fi-err {
            border-color: #e74c3c;
            background: #fff8f8;
        }

        .bv-fe {
            font-size: .78rem;
            color: #e74c3c;
            margin-top: 4px;
        }

        .bv-slug-preview {
            font-size: .72rem;
            color: #bbb;
            margin-top: 4px;
            font-family: monospace;
        }

        .bv-img-preview {
            width: 100%;
            height: 160px;
            border-radius: 10px;
            overflow: hidden;
            border: 2px dashed #e8e8e8;
            cursor: pointer;
            background: #fafafa;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .bv-img-placeholder {
            color: #bbb;
            text-align: center;
        }

        .bv-img-placeholder i {
            font-size: 2rem;
            display: block;
            margin-bottom: 5px;
        }

        .bv-tg-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .bv-tg-info span {
            font-weight: 600;
            font-size: .86rem;
            display: block;
        }

        .bv-tg-info small {
            color: #bbb;
            font-size: .76rem;
        }

        .bv-sw {
            position: relative;
            cursor: pointer;
            display: inline-block;
            width: 44px;
            height: 24px;
        }

        .bv-sw input {
            display: none;
        }

        .bv-sw-track {
            position: absolute;
            inset: 0;
            background: #dde0e8;
            border-radius: 12px;
            transition: 0.3s;
        }

        .bv-sw input:checked~.bv-sw-track {
            background: #27ae60;
        }

        .bv-sw-thumb {
            position: absolute;
            width: 18px;
            height: 18px;
            background: #fff;
            border-radius: 50%;
            top: 3px;
            left: 3px;
            transition: 0.3s;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .2);
        }

        .bv-sw input:checked~.bv-sw-track .bv-sw-thumb {
            transform: translateX(20px);
        }

        .bv-btn-save {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #FF8C42, #f5a623);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-weight: 700;
            cursor: pointer;
            margin-bottom: 10px;
        }

        .bv-btn-cancel {
            width: 100%;
            padding: 10px;
            text-align: center;
            display: block;
            background: #f5f5f5;
            color: #888;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
        }

        .bv-divider {
            border-top: 1px solid #f5f5f5;
            margin: 12px 0;
        }
    </style>
@endpush

@push('scripts')
    {{-- TÍCH HỢP CKEDITOR --}}
    <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
    <script>
        // 1. Kích hoạt CKEditor
        if (document.getElementById('bvEditor')) {
            CKEDITOR.replace('bvEditor', {
                height: 450,
                language: 'vi'
            });
        }

        // 2. Đồng bộ CKEditor trước khi submit
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', function() {
                if (CKEDITOR.instances['bvEditor']) {
                    CKEDITOR.instances['bvEditor'].updateElement();
                }
            });
        });

        // 3. Tự động tạo Slug Preview
        const titleInput = document.getElementById('bv_tieu_de');
        if (titleInput) {
            titleInput.addEventListener('input', function() {
                const slug = this.value.toLowerCase()
                    .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
                    .replace(/đ/g, 'd').replace(/[^a-z0-9\s-]/g, '')
                    .trim().replace(/\s+/g, '-');
                document.getElementById('slugPreview').textContent = slug || '---';
            });
        }

        // 4. Preview Ảnh bìa
        function previewThumb(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    document.getElementById('thumbPreview').innerHTML =
                        `<img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover">`;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endpush
