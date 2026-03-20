@php
    $isEdit = isset($baiViet) && $baiViet !== null;
    $oldLoai = old('loai_bai_viet', $isEdit ? $baiViet->loai_bai_viet : 'tin_tuc');
@endphp

<div class="bv-fg-grid">

    {{-- ══════════ CỘT TRÁI — NỘI DUNG CHÍNH ══════════ --}}
    <div class="bv-fg-left">

        {{-- ① TIÊU ĐỀ & MÔ TẢ --}}
        <div class="bv-fc">
            <div class="bv-fc-head"><i class="fas fa-heading"></i> Nội dung bài viết</div>
            <div class="bv-fc-body">

                <div class="bv-fg">
                    <label class="bv-fl req">Tiêu đề bài viết</label>
                    <input type="text" name="tieu_de" id="bv_tieu_de"
                        class="bv-fi @error('tieu_de') bv-fi-err @enderror"
                        value="{{ old('tieu_de', $isEdit ? $baiViet->tieu_de : '') }}"
                        placeholder="Nhập tiêu đề hấp dẫn..." autofocus>
                    <div class="bv-slug-preview">
                        Slug: <span id="slugPreview" style="color:#2d6a9f">
                            {{ $isEdit ? $baiViet->slug : '' }}
                        </span>
                    </div>
                    @error('tieu_de')
                        <div class="bv-fe"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="bv-fg">
                    <label class="bv-fl">
                        Mô tả ngắn
                        <span class="bv-hint">Hiển thị ngoài trang danh sách (tối đa 300 ký tự)</span>
                    </label>
                    <textarea name="mo_ta_ngan" class="bv-fi @error('mo_ta_ngan') bv-fi-err @enderror" rows="3" maxlength="300"
                        placeholder="Tóm tắt nội dung bài viết...">{{ old('mo_ta_ngan', $isEdit ? $baiViet->mo_ta_ngan : '') }}</textarea>
                    <div style="text-align:right;font-size:.72rem;color:#bbb;margin-top:3px">
                        <span
                            id="moTaCount">{{ strlen(old('mo_ta_ngan', $isEdit ? $baiViet->mo_ta_ngan ?? '' : '')) }}</span>/300
                    </div>
                    @error('mo_ta_ngan')
                        <div class="bv-fe"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

            </div>
        </div>

        {{-- ② NỘI DUNG (TinyMCE) --}}
        <div class="bv-fc">
            <div class="bv-fc-head"><i class="fas fa-align-left"></i> Nội dung chi tiết</div>
            <div class="bv-fc-body" style="padding:0">
                <textarea name="noi_dung" id="bvEditor" class="@error('noi_dung') bv-fi-err @enderror">{{ old('noi_dung', $isEdit ? $baiViet->noi_dung : '') }}</textarea>
                @error('noi_dung')
                    <div class="bv-fe" style="padding:0 18px 10px">
                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        {{-- ③ ẢNH ĐẠI DIỆN + ALBUM --}}
        <div class="bv-fc">
            <div class="bv-fc-head"><i class="fas fa-images"></i> Hình ảnh</div>
            <div class="bv-fc-body">

                {{-- Ảnh đại diện --}}
                <div class="bv-fg">
                    <label class="bv-fl">Ảnh đại diện (thumbnail)</label>
                    <div class="bv-img-upload">
                        <div class="bv-img-preview" id="thumbPreview">
                            @if ($isEdit && $baiViet->hinh_anh_url)
                                <img src="{{ $baiViet->hinh_anh_url }}" alt="Ảnh hiện tại">
                                <div class="bv-img-overlay">
                                    <span>Thay ảnh</span>
                                </div>
                            @else
                                <div class="bv-img-placeholder">
                                    <i class="fas fa-image"></i>
                                    <span>Chọn ảnh đại diện</span>
                                </div>
                            @endif
                        </div>
                        <input type="file" id="thumbInput" name="hinh_anh" accept="image/jpeg,image/png,image/webp"
                            style="display:none">
                        <label for="thumbInput" class="bv-upload-btn">
                            <i class="fas fa-upload"></i>
                            {{ $isEdit && $baiViet->hinh_anh ? 'Đổi ảnh' : 'Chọn ảnh' }}
                        </label>
                        @if ($isEdit && $baiViet->hinh_anh)
                            <label class="bv-upload-del">
                                <input type="checkbox" name="xoa_hinh_anh" value="1" style="margin-right:4px">
                                Xóa ảnh hiện tại
                            </label>
                        @endif
                        <div class="bv-upload-hint">JPEG, PNG, WEBP • Tối đa 2MB</div>
                    </div>
                    @error('hinh_anh')
                        <div class="bv-fe"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                {{-- Album ảnh --}}
                <div class="bv-fg" style="margin-top:18px;padding-top:18px;border-top:1px dashed #f0f2f5">
                    <label class="bv-fl">
                        Album ảnh
                        <span class="bv-hint">Tối đa 10 ảnh, hiển thị trong bài</span>
                    </label>

                    @if ($isEdit && $baiViet->album_anh && count($baiViet->album_anh) > 0)
                        <div class="bv-album-current">
                            @foreach ($baiViet->album_anh as $idx => $anh)
                                <div class="bv-album-item">
                                    <img src="{{ asset('storage/' . $anh) }}" alt="Album {{ $idx + 1 }}">
                                    <span class="bv-album-num">{{ $idx + 1 }}</span>
                                </div>
                            @endforeach
                        </div>
                        <div style="font-size:.75rem;color:#aaa;margin-bottom:10px">
                            <i class="fas fa-info-circle"></i>
                            Upload mới sẽ thay thế toàn bộ album hiện tại
                        </div>
                    @endif

                    <input type="file" id="albumInput" name="album_anh[]" accept="image/jpeg,image/png,image/webp"
                        multiple style="display:none">
                    <label for="albumInput" class="bv-upload-btn">
                        <i class="fas fa-images"></i> Chọn nhiều ảnh
                    </label>
                    <div id="albumPreviewWrap" class="bv-album-preview-wrap" style="margin-top:10px"></div>
                    @error('album_anh.*')
                        <div class="bv-fe"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

            </div>
        </div>

        {{-- ④ SEO --}}
        <div class="bv-fc">
            <div class="bv-fc-head">
                <i class="fas fa-search"></i> SEO
                <span class="bv-hint">Tối ưu cho Google</span>
            </div>
            <div class="bv-fc-body">

                <div class="bv-fg">
                    <label class="bv-fl">
                        SEO Title
                        <span id="seoTitleCount" class="bv-char-badge">0/70</span>
                    </label>
                    <input type="text" name="seo_title" id="seoTitle" class="bv-fi" maxlength="70"
                        value="{{ old('seo_title', $isEdit ? $baiViet->seo_title : '') }}"
                        placeholder="Tiêu đề hiển thị trên Google...">
                    <div class="bv-seo-bar">
                        <div id="seoTitleBar" class="bv-seo-fill"></div>
                    </div>
                </div>

                <div class="bv-fg">
                    <label class="bv-fl">
                        SEO Description
                        <span id="seoDescCount" class="bv-char-badge">0/160</span>
                    </label>
                    <textarea name="seo_description" id="seoDesc" class="bv-fi" rows="3" maxlength="160"
                        placeholder="Mô tả hiển thị dưới tiêu đề trên Google...">{{ old('seo_description', $isEdit ? $baiViet->seo_description : '') }}</textarea>
                    <div class="bv-seo-bar">
                        <div id="seoDescBar" class="bv-seo-fill"></div>
                    </div>
                </div>

                <div class="bv-fg">
                    <label class="bv-fl">SEO Keywords</label>
                    <input type="text" name="seo_keywords" class="bv-fi"
                        value="{{ old('seo_keywords', $isEdit ? $baiViet->seo_keywords : '') }}"
                        placeholder="từ khóa 1, từ khóa 2, từ khóa 3...">
                    <div style="font-size:.72rem;color:#bbb;margin-top:3px">Phân cách bằng dấu phẩy</div>
                </div>

            </div>
        </div>

    </div>{{-- end left --}}

    {{-- ══════════ CỘT PHẢI — SIDEBAR ══════════ --}}
    <div class="bv-fg-right">

        {{-- NÚT ĐĂNG --}}
        <div class="bv-fc">
            <div class="bv-fc-body" style="padding:16px">
                <button type="submit" class="bv-btn-save" form="bvForm">
                    <i class="fas fa-paper-plane"></i>
                    {{ $isEdit ? 'Lưu thay đổi' : 'Đăng bài viết' }}
                </button>
                <a href="{{ route('nhanvien.admin.bai-viet.index') }}" class="bv-btn-cancel">
                    <i class="fas fa-times"></i> Hủy bỏ
                </a>
            </div>
        </div>

        {{-- PHÂN LOẠI --}}
        <div class="bv-fc">
            <div class="bv-fc-head"><i class="fas fa-tags"></i> Phân loại</div>
            <div class="bv-fc-body">
                <div class="bv-fg">
                    <label class="bv-fl req">Loại bài viết</label>
                    <div class="bv-loai-grid">
                        @foreach (\App\Models\BaiViet::LOAI as $v => $info)
                            <label class="bv-loai-item {{ $oldLoai == $v ? 'active' : '' }}"
                                style="{{ $oldLoai == $v ? 'border-color:' . $info['color'] . ';background:' . $info['bg'] . ';color:' . $info['color'] : '' }}">
                                <input type="radio" name="loai_bai_viet" value="{{ $v }}"
                                    {{ $oldLoai == $v ? 'checked' : '' }} style="display:none">
                                <i class="{{ $info['icon'] }}" style="font-size:1.1rem;margin-bottom:4px"></i>
                                <span>{{ $info['label'] }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- THIẾT LẬP --}}
        <div class="bv-fc">
            <div class="bv-fc-head"><i class="fas fa-cog"></i> Thiết lập</div>
            <div class="bv-fc-body">

                {{-- Hiển thị --}}
                <div class="bv-tg-row">
                    <div class="bv-tg-info">
                        <span>Hiển thị bài viết</span>
                        <small>Công khai trên website</small>
                    </div>
                    <label class="bv-sw">
                        <input type="checkbox" name="hien_thi" value="1"
                            {{ old('hien_thi', $isEdit ? $baiViet->hien_thi : true) ? 'checked' : '' }}>
                        <span class="bv-sw-track"><span class="bv-sw-thumb"></span></span>
                    </label>
                </div>

                <div class="bv-divider"></div>

                {{-- Nổi bật --}}
                <div class="bv-tg-row">
                    <div class="bv-tg-info">
                        <span>⭐ Bài viết nổi bật</span>
                        <small>Hiển thị trang chủ, banner</small>
                    </div>
                    <label class="bv-sw">
                        <input type="checkbox" name="noi_bat" value="1"
                            {{ old('noi_bat', $isEdit ? $baiViet->noi_bat : false) ? 'checked' : '' }}>
                        <span class="bv-sw-track"><span class="bv-sw-thumb"></span></span>
                    </label>
                </div>

                <div class="bv-divider"></div>

                {{-- Thứ tự --}}
                <div class="bv-fg">
                    <label class="bv-fl">Thứ tự hiển thị</label>
                    <input type="number" name="thu_tu_hien_thi" class="bv-fi"
                        value="{{ old('thu_tu_hien_thi', $isEdit ? $baiViet->thu_tu_hien_thi : 0) }}" min="0"
                        placeholder="0">
                    <div style="font-size:.72rem;color:#bbb;margin-top:3px">Số nhỏ hiển thị trước</div>
                </div>

                <div class="bv-divider"></div>

                {{-- Thời điểm đăng --}}
                <div class="bv-fg">
                    <label class="bv-fl">Thời điểm đăng</label>
                    <input type="datetime-local" name="thoi_diem_dang" class="bv-fi"
                        value="{{ old('thoi_diem_dang', $isEdit && $baiViet->thoi_diem_dang ? $baiViet->thoi_diem_dang->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}">
                </div>

            </div>
        </div>

        {{-- THÔNG TIN HỆ THỐNG --}}
        @if ($isEdit)
            <div class="bv-fc">
                <div class="bv-fc-head"><i class="fas fa-info-circle"></i> Thông tin</div>
                <div class="bv-fc-body">
                    <div class="bv-sysrow"><span class="bv-syskey"><i class="fas fa-hashtag"></i> ID</span>
                        <span class="bv-sysval"
                            style="font-family:monospace;background:#f0f4ff;color:#2d6a9f;padding:.1rem .4rem;border-radius:4px">#{{ $baiViet->id }}</span>
                    </div>
                    <div class="bv-sysrow"><span class="bv-syskey"><i class="fas fa-eye"></i> Lượt xem</span>
                        <span class="bv-sysval">{{ number_format($baiViet->luot_xem) }}</span>
                    </div>
                    <div class="bv-sysrow"><span class="bv-syskey"><i class="fas fa-user"></i> Tác giả</span>
                        <span class="bv-sysval">{{ $baiViet->tacGia?->ho_ten ?? '—' }}</span>
                    </div>
                    <div class="bv-sysrow"><span class="bv-syskey"><i class="fas fa-calendar"></i> Tạo lúc</span>
                        <span class="bv-sysval">{{ $baiViet->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="bv-sysrow"><span class="bv-syskey"><i class="fas fa-sync"></i> Sửa lần cuối</span>
                        <span class="bv-sysval">{{ $baiViet->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>
        @endif

        {{-- NÚT LƯU cuối --}}
        <div class="bv-fc">
            <div class="bv-fc-body" style="padding:16px">
                <button type="submit" class="bv-btn-save" form="bvForm">
                    <i class="fas fa-paper-plane"></i>
                    {{ $isEdit ? 'Lưu thay đổi' : 'Đăng bài viết' }}
                </button>
            </div>
        </div>

    </div>{{-- end right --}}

</div>{{-- end bv-fg-grid --}}

@push('styles')
    <style>
        .bv-form-hdr {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 12px
        }

        .bv-bc {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: .8rem;
            color: #aaa;
            margin-bottom: 6px
        }

        .bv-bc a {
            color: #aaa;
            text-decoration: none
        }

        .bv-bc a:hover {
            color: #FF8C42
        }

        .bv-form-ttl {
            font-size: 1.3rem;
            font-weight: 700;
            color: #1a3c5e;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px
        }

        .bv-form-ttl i {
            color: #FF8C42
        }

        .bv-fg-grid {
            display: grid;
            grid-template-columns: 1fr 300px;
            gap: 20px;
            align-items: start
        }

        @media(max-width:980px) {
            .bv-fg-grid {
                grid-template-columns: 1fr
            }

            .bv-fg-right {
                order: -1
            }
        }

        .bv-fc {
            background: #fff;
            border-radius: 14px;
            border: 1px solid #f0f2f5;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .05);
            margin-bottom: 18px;
            overflow: hidden
        }

        .bv-fc:last-child {
            margin-bottom: 0
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
            flex-wrap: wrap
        }

        .bv-fc-head i {
            color: #FF8C42
        }

        .bv-hint {
            font-weight: 400;
            color: #bbb;
            font-size: .72rem;
            margin-left: 4px
        }

        .bv-fc-body {
            padding: 18px
        }

        .bv-fg {
            margin-bottom: 14px
        }

        .bv-fg:last-child {
            margin-bottom: 0
        }

        .bv-fl {
            display: block;
            font-weight: 700;
            font-size: .75rem;
            color: #666;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: .3px;
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap
        }

        .bv-fl.req::after {
            content: ' *';
            color: #e74c3c
        }

        .bv-fi {
            width: 100%;
            border: 1.5px solid #e8e8e8;
            border-radius: 8px;
            padding: 9px 12px;
            font-size: .875rem;
            color: #333;
            background: #fafafa;
            outline: none;
            font-family: inherit;
            transition: border-color .2s, background .2s, box-shadow .2s;
            box-sizing: border-box
        }

        input.bv-fi {
            height: 40px;
            padding: 0 12px
        }

        .bv-fi:focus {
            border-color: #FF8C42;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(255, 140, 66, .1)
        }

        .bv-fi.bv-fi-err {
            border-color: #e74c3c;
            background: #fff8f8
        }

        .bv-fe {
            font-size: .78rem;
            color: #e74c3c;
            margin-top: 4px;
            display: flex;
            align-items: center;
            gap: 4px
        }

        .bv-slug-preview {
            font-size: .72rem;
            color: #bbb;
            margin-top: 4px;
            font-family: monospace
        }

        /* IMAGE */
        .bv-img-upload {
            display: flex;
            flex-direction: column;
            gap: 8px
        }

        .bv-img-preview {
            width: 100%;
            height: 160px;
            border-radius: 10px;
            overflow: hidden;
            border: 2px dashed #e8e8e8;
            position: relative;
            cursor: pointer;
            background: #fafafa
        }

        .bv-img-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover
        }

        .bv-img-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, .4);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity .2s;
            color: #fff;
            font-weight: 700;
            font-size: .9rem
        }

        .bv-img-preview:hover .bv-img-overlay {
            opacity: 1
        }

        .bv-img-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 8px;
            color: #bbb
        }

        .bv-img-placeholder i {
            font-size: 2rem
        }

        .bv-img-placeholder span {
            font-size: .8rem
        }

        .bv-upload-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #f0f4ff;
            color: #1a3c5e;
            border: 1.5px solid #d5e0f5;
            padding: 8px 14px;
            border-radius: 8px;
            font-weight: 600;
            font-size: .82rem;
            cursor: pointer;
            transition: all .2s
        }

        .bv-upload-btn:hover {
            background: #1a3c5e;
            color: #fff
        }

        .bv-upload-del {
            display: flex;
            align-items: center;
            font-size: .8rem;
            color: #e74c3c;
            cursor: pointer
        }

        .bv-upload-hint {
            font-size: .72rem;
            color: #bbb
        }

        .bv-album-current {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 6px;
            margin-bottom: 8px
        }

        .bv-album-item {
            position: relative;
            aspect-ratio: 1;
            border-radius: 6px;
            overflow: hidden;
            border: 1.5px solid #f0f2f5
        }

        .bv-album-item img {
            width: 100%;
            height: 100%;
            object-fit: cover
        }

        .bv-album-num {
            position: absolute;
            top: 3px;
            left: 3px;
            background: rgba(0, 0, 0, .5);
            color: #fff;
            font-size: .65rem;
            font-weight: 700;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center
        }

        .bv-album-preview-wrap {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 6px
        }

        .bv-album-prev-item {
            aspect-ratio: 1;
            border-radius: 6px;
            overflow: hidden;
            border: 1.5px solid #e8e8e8
        }

        .bv-album-prev-item img {
            width: 100%;
            height: 100%;
            object-fit: cover
        }

        /* SEO */
        .bv-char-badge {
            font-weight: 400;
            color: #aaa;
            font-size: .72rem;
            background: #f0f2f5;
            padding: .1rem .4rem;
            border-radius: 4px;
            margin-left: auto
        }

        .bv-seo-bar {
            height: 4px;
            background: #f0f2f5;
            border-radius: 2px;
            margin-top: 5px;
            overflow: hidden
        }

        .bv-seo-fill {
            height: 100%;
            border-radius: 2px;
            transition: width .2s, background .2s;
            background: #27ae60
        }

        /* LOẠI --radio cards --*/
        .bv-loai-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 6px
        }

        .bv-loai-item {
            border: 1.5px solid #f0f2f5;
            border-radius: 8px;
            padding: 8px 6px;
            text-align: center;
            cursor: pointer;
            transition: all .2s;
            background: #fafafa;
            font-size: .75rem;
            font-weight: 600;
            color: #aaa;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 3px
        }

        .bv-loai-item:hover {
            border-color: #dde5f5;
            background: #f5f8ff
        }

        .bv-loai-item.active {
            box-shadow: 0 2px 8px rgba(0, 0, 0, .08)
        }

        /* TOGGLE */
        .bv-tg-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 4px 0
        }

        .bv-tg-info span {
            display: block;
            font-weight: 600;
            font-size: .86rem;
            color: #333;
            margin-bottom: 2px
        }

        .bv-tg-info small {
            color: #bbb;
            font-size: .76rem
        }

        .bv-sw {
            position: relative;
            cursor: pointer;
            flex-shrink: 0;
            display: inline-block
        }

        .bv-sw input {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0
        }

        .bv-sw-track {
            display: block;
            width: 44px;
            height: 25px;
            background: #dde0e8;
            border-radius: 13px;
            transition: background .25s;
            position: relative
        }

        .bv-sw input:checked~.bv-sw-track {
            background: #27ae60
        }

        .bv-sw-thumb {
            position: absolute;
            width: 19px;
            height: 19px;
            background: #fff;
            border-radius: 50%;
            top: 3px;
            left: 3px;
            transition: transform .25s;
            box-shadow: 0 1px 5px rgba(0, 0, 0, .2)
        }

        .bv-sw input:checked~.bv-sw-track .bv-sw-thumb {
            transform: translateX(19px)
        }

        .bv-divider {
            border: none;
            border-top: 1px solid #f5f5f5;
            margin: 12px 0
        }

        /* SAVE */
        .bv-btn-save {
            width: 100%;
            height: 48px;
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
            box-shadow: 0 4px 14px rgba(255, 140, 66, .35);
            transition: all .2s;
            margin-bottom: 10px
        }

        .bv-btn-save:hover {
            transform: translateY(-1px)
        }

        .bv-btn-cancel {
            width: 100%;
            height: 40px;
            background: #f5f5f5;
            color: #888;
            border: 1.5px solid #e8e8e8;
            border-radius: 10px;
            font-weight: 600;
            font-size: .875rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            text-decoration: none;
            transition: all .2s
        }

        .bv-btn-cancel:hover {
            background: #ffeee0;
            color: #FF8C42;
            border-color: #FF8C42
        }

        /* SYSINFO */
        .bv-sysrow {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 6px 0;
            border-bottom: 1px solid #fafafa
        }

        .bv-sysrow:last-child {
            border-bottom: none
        }

        .bv-syskey {
            font-size: .75rem;
            color: #bbb;
            display: flex;
            align-items: center;
            gap: 4px
        }

        .bv-sysval {
            font-size: .78rem;
            font-weight: 600;
            color: #555;
            text-align: right
        }
    </style>
@endpush

@push('scripts')
    {{-- TinyMCE CDN --}}
    <script src="https://cdn.tiny.cloud/1/2afundtldp8p29mhx2gslya4jrjf81e5ppxm9z9rthlgfe1q/tinymce/8/tinymce.min.js"
        referrerpolicy="origin" crossorigin="anonymous"></script>

    <script>
        tinymce.init({
            selector: '#bvEditor',
            language: 'vi',
            height: 480,
            menubar: 'file edit view insert format tools table',
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'preview', 'wordcount'
            ],
            toolbar: 'undo redo | blocks | bold italic underline strikethrough | ' +
                'forecolor backcolor | alignleft aligncenter alignright alignjustify | ' +
                'bullist numlist outdent indent | link image media | ' +
                'removeformat code fullscreen',
            content_style: 'body { font-family: "Segoe UI", sans-serif; font-size: 15px; line-height: 1.8; }',
            images_upload_url: '/nhan-vien/admin/upload-anh',
            automatic_uploads: true,
            file_picker_types: 'image',
            setup(editor) {
                editor.on('change', () => editor.save());
            }
        });

        // ── Slug preview từ tiêu đề ──
        const tieude = document.getElementById('bv_tieu_de');
        const slugEl = document.getElementById('slugPreview');
        if (tieude && slugEl && !{{ $isEdit ? 'true' : 'false' }}) {
            tieude.addEventListener('input', function() {
                const slug = this.value.toLowerCase()
                    .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
                    .replace(/đ/g, 'd').replace(/[^a-z0-9\s-]/g, '')
                    .trim().replace(/\s+/g, '-');
                slugEl.textContent = slug || '...';
            });
        }

        // ── Đếm ký tự mô tả ngắn ──
        const moTaTa = document.querySelector('textarea[name="mo_ta_ngan"]');
        const moTaCount = document.getElementById('moTaCount');
        if (moTaTa && moTaCount) {
            moTaTa.addEventListener('input', () => {
                moTaCount.textContent = moTaTa.value.length;
            });
        }

        // ── SEO counter + progress bar ──
        function seoCounter(inputId, countId, barId, max) {
            const inp = document.getElementById(inputId);
            const cnt = document.getElementById(countId);
            const bar = document.getElementById(barId);
            if (!inp) return;

            function update() {
                const len = inp.value.length;
                const pct = Math.min(100, Math.round(len / max * 100));
                cnt.textContent = len + '/' + max;
                bar.style.width = pct + '%';
                bar.style.background = pct < 50 ? '#e74c3c' : pct < 80 ? '#e67e22' : '#27ae60';
            }
            inp.addEventListener('input', update);
            update();
        }
        seoCounter('seoTitle', 'seoTitleCount', 'seoTitleBar', 70);
        seoCounter('seoDesc', 'seoDescCount', 'seoDescBar', 160);

        // ── Preview ảnh đại diện ──
        const thumbInput = document.getElementById('thumbInput');
        const thumbPreview = document.getElementById('thumbPreview');
        if (thumbInput) {
            thumbInput.addEventListener('change', function() {
                const file = this.files[0];
                if (!file) return;
                const reader = new FileReader();
                reader.onload = e => {
                    thumbPreview.innerHTML =
                        '<img src="' + e.target.result + '" style="width:100%;height:100%;object-fit:cover">' +
                        '<div class="bv-img-overlay"><span>Thay ảnh</span></div>';
                };
                reader.readAsDataURL(file);
            });
        }

        // ── Preview album ──
        const albumInput = document.getElementById('albumInput');
        const albumWrap = document.getElementById('albumPreviewWrap');
        if (albumInput) {
            albumInput.addEventListener('change', function() {
                albumWrap.innerHTML = '';
                Array.from(this.files).slice(0, 10).forEach(file => {
                    const div = document.createElement('div');
                    div.className = 'bv-album-prev-item';
                    const img = document.createElement('img');
                    const reader = new FileReader();
                    reader.onload = e => img.src = e.target.result;
                    reader.readAsDataURL(file);
                    div.appendChild(img);
                    albumWrap.appendChild(div);
                });
            });
        }

        // ── Chọn loại bài viết ──
        const loaiColors = {
            tin_tuc: {
                color: '#2d6a9f',
                bg: '#e8f4fd'
            },
            phong_thuy: {
                color: '#27ae60',
                bg: '#e8f8f0'
            },
            tuyen_dung: {
                color: '#e67e22',
                bg: '#fff8f0'
            },
            kien_thuc: {
                color: '#8e44ad',
                bg: '#f5eeff'
            },
        };

        document.querySelectorAll('.bv-loai-item').forEach(item => {
            item.addEventListener('click', function() {
                const val = this.querySelector('input').value;
                const c = loaiColors[val] || {
                    color: '#999',
                    bg: '#f5f5f5'
                };

                document.querySelectorAll('.bv-loai-item').forEach(it => {
                    it.classList.remove('active');
                    it.style.borderColor = '#f0f2f5';
                    it.style.background = '#fafafa';
                    it.style.color = '#aaa';
                });

                this.classList.add('active');
                this.style.borderColor = c.color;
                this.style.background = c.bg;
                this.style.color = c.color;
            });
        });
    </script>
@endpush
