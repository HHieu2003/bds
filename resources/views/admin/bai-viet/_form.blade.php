
@php
    $isEdit = $isEdit ?? isset($baiViet);
    $v = fn($key, $default = '') => old($key, $isEdit ? $baiViet->{$key} ?? $default : $default);
@endphp

<div class="row g-4 align-items-start">

    {{-- ════════════════════════════════════
         CỘT TRÁI — col-lg-8
    ════════════════════════════════════ --}}
    <div class="col-lg-8">

        {{-- ── NỘI DUNG BÀI VIẾT ── --}}
        <div class="card mb-4">
            <div class="card-header">
                <span class="d-flex align-items-center gap-2">
                    <i class="fas fa-file-alt"></i> Nội dung bài viết
                </span>
                <span style="font-size:.71rem;color:var(--text-muted)">
                    <span style="color:var(--red)">*</span> Bắt buộc
                </span>
            </div>
            <div class="card-body">

                {{-- Tiêu đề --}}
                <div class="mb-4">
                    <label class="form-label" for="bv_tieu_de">
                        Tiêu đề bài viết <span class="req">*</span>
                    </label>
                    <input type="text" id="bv_tieu_de" name="tieu_de"
                        class="form-control @error('tieu_de') is-invalid @enderror" value="{{ $v('tieu_de') }}"
                        placeholder="Nhập tiêu đề hấp dẫn, rõ ràng..." maxlength="255"
                        style="font-size:.95rem;font-weight:700;height:50px" required>
                    <div class="d-flex align-items-center justify-content-between mt-1">
                        @error('tieu_de')
                            <div class="form-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @else
                            <div class="form-hint">Tiêu đề nên từ 40–70 ký tự để tối ưu SEO</div>
                        @enderror
                        <span id="bv_tieu_de_count" class="form-hint ms-auto flex-shrink-0">
                            {{ strlen($v('tieu_de')) }}/255
                        </span>
                    </div>
                </div>

                {{-- Slug --}}
                <div class="mb-4">
                    <label class="form-label" for="bv_slug">
                        Đường dẫn (Slug)
                        <span style="font-size:.68rem;font-weight:500;color:var(--text-muted)">(tự động tạo từ tiêu
                            đề)</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text"
                            style="border-color:var(--border);background:var(--bg-alt);font-size:.78rem;color:var(--text-sub);white-space:nowrap">
                            <i class="fas fa-link me-1" style="font-size:.7rem"></i>/tin-tuc/
                        </span>
                        <input type="text" id="bv_slug" name="slug"
                            class="form-control @error('slug') is-invalid @enderror" value="{{ $v('slug') }}"
                            placeholder="duong-dan-bai-viet" style="font-family:monospace;font-size:.83rem">
                        <button type="button" class="btn btn-secondary"
                            style="border-color:var(--border);font-size:.78rem"
                            onclick="copyToClipboard(document.getElementById('bv_slug').value, 'Đã sao chép slug!')"
                            data-tip="Sao chép">
                            <i class="fas fa-copy"></i>
                        </button>
                        <button type="button" class="btn btn-secondary"
                            style="border-color:var(--border);font-size:.78rem" onclick="regenerateSlug()"
                            data-tip="Tạo lại từ tiêu đề">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                    @error('slug')
                        <div class="form-error mt-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                    <div class="form-hint">Chỉ dùng chữ thường, số và dấu gạch ngang (-)</div>
                </div>

                {{-- Mô tả ngắn --}}
                <div class="mb-4">
                    <label class="form-label" for="bv_mo_ta_ngan">Mô tả ngắn</label>
                    <textarea id="bv_mo_ta_ngan" name="mo_ta_ngan" class="form-control @error('mo_ta_ngan') is-invalid @enderror"
                        rows="3" maxlength="300" placeholder="Tóm tắt nội dung bài viết, hiển thị trên trang danh sách..."
                        style="resize:vertical">{{ $v('mo_ta_ngan') }}</textarea>
                    <div class="d-flex align-items-center justify-content-between mt-1">
                        @error('mo_ta_ngan')
                            <div class="form-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @else
                            <div class="form-hint">Nên từ 120–160 ký tự để hiển thị đẹp</div>
                        @enderror
                        <span id="bv_mo_ta_count" class="form-hint ms-auto flex-shrink-0"
                            style="{{ strlen($v('mo_ta_ngan')) > 160 ? 'color:var(--yellow)' : '' }}">
                            <span id="bv_mo_ta_num">{{ strlen($v('mo_ta_ngan')) }}</span>/160
                        </span>
                    </div>
                </div>

                {{-- Nội dung chính --}}
                <div>
                    <label class="form-label" for="bv_noi_dung">
                        Nội dung <span class="req">*</span>
                    </label>

                    {{-- Toolbar giả (UX placeholder cho TinyMCE/CKEditor) --}}
                    <div id="bv_editor_toolbar"
                        style="border:1.5px solid var(--border);border-bottom:none;border-radius:var(--r-sm) var(--r-sm) 0 0;padding:.45rem .75rem;background:var(--bg-alt);display:flex;gap:.3rem;flex-wrap:wrap;align-items:center">
                        @foreach ([['fas fa-bold', 'B'], ['fas fa-italic', 'I'], ['fas fa-underline', 'U'], 'sep', ['fas fa-heading', 'H'], ['fas fa-list-ul', 'UL'], ['fas fa-list-ol', 'OL'], 'sep', ['fas fa-link', 'Link'], ['fas fa-image', 'Ảnh'], ['fas fa-table', 'Bảng'], 'sep', ['fas fa-align-left', 'L'], ['fas fa-align-center', 'C'], ['fas fa-align-right', 'R']] as $tb)
                            @if ($tb === 'sep')
                                <span
                                    style="width:1px;height:18px;background:var(--border);display:block;margin:0 .2rem"></span>
                            @else
                                <button type="button"
                                    style="width:28px;height:28px;border:1px solid var(--border);border-radius:4px;background:#fff;cursor:pointer;font-size:.72rem;color:var(--text-sub);display:flex;align-items:center;justify-content:center;transition:all .15s"
                                    onmouseover="this.style.background='var(--primary-soft)';this.style.borderColor='var(--primary)';this.style.color='var(--primary)'"
                                    onmouseout="this.style.background='#fff';this.style.borderColor='var(--border)';this.style.color='var(--text-sub)'">
                                    <i class="{{ $tb[0] }}"></i>
                                </button>
                            @endif
                        @endforeach
                        <span style="margin-left:auto;font-size:.68rem;color:var(--text-muted)">
                            <i class="fas fa-magic me-1"></i>TinyMCE/CKEditor
                        </span>
                    </div>

                    <textarea id="bv_noi_dung" name="noi_dung" class="form-control @error('noi_dung') is-invalid @enderror" rows="18"
                        placeholder="Viết nội dung bài viết tại đây..."
                        style="border-top-left-radius:0;border-top-right-radius:0;border-color:var(--border);resize:vertical;font-size:.87rem;line-height:1.8">{{ $v('noi_dung') }}</textarea>

                    <div class="d-flex align-items-center justify-content-between mt-1">
                        @error('noi_dung')
                            <div class="form-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @else
                            <div class="form-hint">
                                <i class="fas fa-info-circle me-1"></i>
                                Hỗ trợ tích hợp TinyMCE/CKEditor editor
                            </div>
                        @enderror
                        <span id="bv_word_count" class="form-hint ms-auto flex-shrink-0">0 từ</span>
                    </div>
                </div>

            </div>
        </div>

        {{-- ── SEO ── --}}
        <div class="card mb-4">
            <div class="card-header">
                <span class="d-flex align-items-center gap-2">
                    <i class="fas fa-search"></i> Tối ưu SEO
                </span>
                <span class="badge-status badge-new" style="font-size:.65rem">Google Preview</span>
            </div>
            <div class="card-body">

                {{-- SEO Preview --}}
                <div id="seo_preview" class="mb-4 p-3 rounded-2"
                    style="background:var(--bg-alt);border:1px solid var(--border)">
                    <div
                        style="font-size:.7rem;color:var(--text-muted);margin-bottom:.5rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px">
                        <i class="fab fa-google me-1" style="color:#4285f4"></i>Xem trước Google
                    </div>
                    <div id="seo_preview_title"
                        style="font-size:.95rem;color:#1a0dab;font-weight:400;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;cursor:pointer">
                        {{ $v('meta_title') ?: $v('tieu_de') ?: 'Tiêu đề bài viết...' }}
                    </div>
                    <div style="font-size:.73rem;color:#006621;margin:.1rem 0">
                        https://thanhcongland.vn/tin-tuc/<span
                            id="seo_preview_slug">{{ $v('slug') ?: 'duong-dan-bai-viet' }}</span>
                    </div>
                    <div id="seo_preview_desc"
                        style="font-size:.82rem;color:#545454;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical">
                        {{ $v('meta_description') ?: $v('mo_ta_ngan') ?: 'Mô tả bài viết hiển thị trên kết quả tìm kiếm...' }}
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label" for="bv_meta_title">Meta Title</label>
                        <input type="text" id="bv_meta_title" name="meta_title"
                            class="form-control @error('meta_title') is-invalid @enderror"
                            value="{{ $v('meta_title') }}" maxlength="70"
                            placeholder="Để trống sẽ dùng tiêu đề bài viết">
                        <div class="d-flex align-items-center justify-content-between mt-1">
                            <div class="form-hint">Tối ưu: 50–60 ký tự</div>
                            <span id="bv_meta_title_count" class="form-hint ms-auto"
                                style="{{ strlen($v('meta_title')) > 60 ? 'color:var(--yellow)' : '' }}">
                                <span id="bv_meta_title_num">{{ strlen($v('meta_title')) }}</span>/70
                            </span>
                        </div>
                        @error('meta_title')
                            <div class="form-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="bv_meta_desc">Meta Description</label>
                        <textarea id="bv_meta_desc" name="meta_description"
                            class="form-control @error('meta_description') is-invalid @enderror" rows="3" maxlength="165"
                            placeholder="Để trống sẽ dùng mô tả ngắn..." style="resize:none">{{ $v('meta_description') }}</textarea>
                        <div class="d-flex align-items-center justify-content-between mt-1">
                            <div class="form-hint">Tối ưu: 120–160 ký tự</div>
                            <span id="bv_meta_desc_count" class="form-hint ms-auto"
                                style="{{ strlen($v('meta_description')) > 160 ? 'color:var(--yellow)' : '' }}">
                                <span id="bv_meta_desc_num">{{ strlen($v('meta_description')) }}</span>/165
                            </span>
                        </div>
                        @error('meta_description')
                            <div class="form-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

    </div>{{-- /col-lg-8 --}}


    {{-- ════════════════════════════════════
         CỘT PHẢI — col-lg-4
    ════════════════════════════════════ --}}
    <div class="col-lg-4">

        {{-- ── THAO TÁC ── --}}
        <div class="card mb-4" style="border-color:var(--primary);box-shadow:0 0 0 3px var(--primary-glow)">
            <div class="card-header" style="background:linear-gradient(135deg,var(--primary-soft),#fff)">
                <span class="d-flex align-items-center gap-2" style="color:var(--primary)">
                    <i class="fas fa-rocket"></i>
                    {{ $isEdit ? 'Lưu thay đổi' : 'Xuất bản bài viết' }}
                </span>
            </div>
            <div class="card-body">
                {{-- Trạng thái --}}
                <div class="mb-3">
                    <label class="form-label">Trạng thái <span class="req">*</span></label>
                    <div class="d-flex flex-column gap-2">
                        @foreach (['hien_thi' => ['icon' => 'fa-check-circle', 'label' => 'Hiển thị', 'desc' => 'Hiển thị công khai', 'color' => 'var(--green)'], 'an' => ['icon' => 'fa-eye-slash', 'label' => 'Ẩn', 'desc' => 'Tạm thời ẩn khỏi website', 'color' => 'var(--text-muted)']] as $val => $info)
                            @php $checked = $v('trang_thai', $isEdit && !$baiViet->hien_thi ? 'an' : 'hien_thi') == $val; @endphp
                            <label class="d-flex align-items-center gap-3 p-2 rounded-2 cursor-pointer"
                                style="border:2px solid {{ $checked ? $info['color'] : 'var(--border)' }};background:{{ $checked ? 'var(--bg-alt)' : '#fff' }};cursor:pointer;transition:all .2s"
                                onmouseover="if(!this.querySelector('input').checked){this.style.borderColor='{{ $info['color'] }}'}"
                                onmouseout="if(!this.querySelector('input').checked){this.style.borderColor='var(--border)'}">
                                <input type="radio" name="trang_thai" value="{{ $val }}"
                                    class="d-none trang-thai-radio" {{ $checked ? 'checked' : '' }}>
                                <div
                                    style="width:34px;height:34px;border-radius:8px;background:{{ $checked ? $info['color'] : 'var(--bg)' }};display:flex;align-items:center;justify-content:center;flex-shrink:0;transition:all .2s">
                                    <i class="fas {{ $info['icon'] }}"
                                        style="font-size:.82rem;color:{{ $checked ? '#fff' : 'var(--text-muted)' }}"></i>
                                </div>
                                <div>
                                    <div style="font-weight:700;font-size:.82rem;color:var(--navy)">
                                        {{ $info['label'] }}</div>
                                    <div class="form-hint" style="margin:0">{{ $info['desc'] }}</div>
                                </div>
                                @if ($checked)
                                    <i class="fas fa-check-circle ms-auto"
                                        style="color:{{ $info['color'] }};flex-shrink:0"></i>
                                @endif
                            </label>
                        @endforeach
                    </div>
                    @error('trang_thai')
                        <div class="form-error mt-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                {{-- Ngày đăng --}}
                <div class="mb-3">
                    <label class="form-label" for="bv_ngay_dang">Ngày đăng</label>
                    <div class="input-group">
                        <span class="input-group-text" style="border-color:var(--border);background:var(--bg-alt)">
                            <i class="fas fa-calendar-alt" style="color:var(--primary);font-size:.8rem"></i>
                        </span>
                        <input type="datetime-local" id="bv_ngay_dang" name="ngay_dang"
                            class="form-control @error('ngay_dang') is-invalid @enderror"
                            value="{{ old('ngay_dang', $isEdit && $baiViet->ngay_dang ? $baiViet->ngay_dang->format('Y-m-d\TH:i') : '') }}">
                    </div>
                    <div class="form-hint">Để trống = đăng ngay lập tức</div>
                </div>

                {{-- Nổi bật + Bình luận --}}
                <div class="d-flex flex-column gap-2 mb-3 pt-2" style="border-top:1px solid var(--border)">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div style="font-weight:700;font-size:.82rem;color:var(--navy)">
                                <i class="fas fa-star me-1" style="color:var(--primary);font-size:.75rem"></i>Nổi bật
                            </div>
                            <div class="form-hint" style="margin:0">Hiển thị ở trang chủ</div>
                        </div>
                        <label class="toggle-sw">
                            <input type="checkbox" id="bv_noi_bat" name="noi_bat" value="1"
                                {{ $v('noi_bat') ? 'checked' : '' }}
                                onchange="updateToggleLabel(this,document.getElementById('bv_noi_bat_lbl'))">
                            <span class="toggle-sw-track"><span class="toggle-sw-thumb"></span></span>
                        </label>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div style="font-weight:700;font-size:.82rem;color:var(--navy)">
                                <i class="fas fa-comments me-1" style="color:var(--blue);font-size:.75rem"></i>Bình
                                luận
                            </div>
                            <div class="form-hint" style="margin:0">Cho phép bình luận</div>
                        </div>
                        <label class="toggle-sw">
                            <input type="checkbox" id="bv_binh_luan" name="cho_phep_binh_luan" value="1"
                                {{ $v('cho_phep_binh_luan', true) ? 'checked' : '' }}>
                            <span class="toggle-sw-track"><span class="toggle-sw-thumb"></span></span>
                        </label>
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="d-flex flex-column gap-2">
                    <button type="submit" id="bvSubmitBtn"
                        class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2"
                        style="height:44px;font-size:.9rem">
                        <i class="fas {{ $isEdit ? 'fa-save' : 'fa-paper-plane' }}"></i>
                        {{ $isEdit ? 'Lưu thay đổi' : 'Đăng bài viết' }}
                    </button>
                    <a href="{{ route('nhanvien.admin.bai-viet.index') }}"
                        class="btn btn-secondary w-100 d-flex align-items-center justify-content-center gap-2"
                        style="font-size:.84rem">
                        <i class="fas fa-times"></i> Hủy bỏ
                    </a>
                </div>

                {{-- Xóa (chỉ edit) --}}
                @if ($isEdit)
                    <div class="mt-3 pt-2" style="border-top:1px dashed var(--border)">
                        <button type="button"
                            class="btn btn-danger w-100 d-flex align-items-center justify-content-center gap-2"
                            style="font-size:.8rem"
                            data-delete-url="{{ route('nhanvien.admin.bai-viet.destroy', $baiViet) }}"
                            data-confirm-delete="{{ addslashes($baiViet->tieu_de) }}">
                            <i class="fas fa-trash"></i> Xóa bài viết
                        </button>
                    </div>
                @endif
            </div>
        </div>

        {{-- ── ẢNH ĐẠI DIỆN ── --}}
        <div class="card mb-4">
            <div class="card-header">
                <span class="d-flex align-items-center gap-2">
                    <i class="fas fa-image"></i> Ảnh đại diện
                </span>
            </div>
            <div class="card-body">
                {{-- Preview --}}
                <div id="bv_thumb_preview_wrap" class="mb-3"
                    style="{{ !$isEdit || !$baiViet->anh_dai_dien ? 'display:none' : '' }}">
                    <div
                        style="position:relative;border-radius:var(--r);overflow:hidden;aspect-ratio:16/9;background:var(--bg)">
                        <img id="bv_thumb_preview"
                            src="{{ $isEdit && $baiViet->anh_dai_dien ? \Storage::disk('r2')->url($baiViet->anh_dai_dien) : '' }}"
                            alt="Thumbnail" style="width:100%;height:100%;object-fit:cover">
                        <button type="button" onclick="removeThumb()"
                            style="position:absolute;top:8px;right:8px;width:28px;height:28px;border-radius:50%;background:rgba(0,0,0,.65);border:none;color:#fff;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:.7rem">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                {{-- Upload area --}}
                <div id="bv_thumb_upload" class="img-upload-wrap"
                    style="{{ $isEdit && $baiViet->anh_dai_dien ? 'display:none' : '' }}"
                    onclick="document.getElementById('bv_anh_dai_dien').click()">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <p class="fw-bold mb-1">Click để chọn ảnh</p>
                    <p style="font-size:.74rem;color:var(--text-muted)">JPG, PNG, WebP · Tối đa 5MB</p>
                    <p style="font-size:.74rem;color:var(--text-muted)">Khuyến nghị: 1200 × 628px (16:9)</p>
                </div>

                <input type="file" id="bv_anh_dai_dien" name="anh_dai_dien"
                    accept="image/jpeg,image/png,image/webp" style="display:none" onchange="handleThumbChange(this)">

                @error('anh_dai_dien')
                    <div class="form-error mt-2">
                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                    </div>
                @enderror
                <div class="form-hint mt-2 text-center">
                    Ảnh đẹp giúp tăng tỷ lệ click trên mạng xã hội
                </div>
            </div>
        </div>

        {{-- ── PHÂN LOẠI ── --}}
        <div class="card mb-4">
            <div class="card-header">
                <span class="d-flex align-items-center gap-2">
                    <i class="fas fa-tags"></i> Phân loại
                </span>
            </div>
            <div class="card-body">
                {{-- Danh mục --}}
                <div class="mb-3">
                    <label class="form-label" for="bv_danh_muc">Danh mục</label>
                    <select id="bv_danh_muc" name="danh_muc_id"
                        class="form-select @error('danh_muc_id') is-invalid @enderror">
                        <option value="">— Chưa phân loại —</option>
                        @foreach ($danhMucs as $dm)
                            <option value="{{ $dm->id }}" {{ $v('danh_muc_id') == $dm->id ? 'selected' : '' }}>
                                {{ $dm->ten }}
                                @if ($dm->so_bai_viet ?? false)
                                    ({{ $dm->so_bai_viet }})
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('danh_muc_id')
                        <div class="form-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                {{-- Tags --}}
                <div class="mb-3">
                    <label class="form-label" for="bv_tags">Tags</label>
                    <input type="text" id="bv_tags" name="tags"
                        class="form-control @error('tags') is-invalid @enderror" value="{{ $v('tags') }}"
                        placeholder="bất động sản, nhà đất, hà nội...">
                    <div class="form-hint">Ngăn cách bằng dấu phẩy (,)</div>
                    @error('tags')
                        <div class="form-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                    @enderror

                    {{-- Tag preview --}}
                    <div id="bv_tag_preview" class="d-flex flex-wrap gap-1 mt-2"></div>
                </div>

                {{-- Thứ tự --}}
                <div>
                    <label class="form-label" for="bv_thu_tu">Thứ tự hiển thị</label>
                    <input type="number" id="bv_thu_tu" name="thu_tu" class="form-control"
                        value="{{ $v('thu_tu', 0) }}" min="0" max="9999" placeholder="0">
                    <div class="form-hint">Số nhỏ hơn hiển thị trước (0 = mặc định)</div>
                </div>
            </div>
        </div>

        {{-- ── META INFO (chỉ edit) ── --}}
        @if ($isEdit)
            <div class="card" style="background:var(--bg-alt);border-style:dashed">
                <div class="card-body" style="padding:.9rem 1rem">
                    <div
                        style="font-size:.68rem;font-weight:800;color:var(--text-muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:.65rem">
                        Thông tin hệ thống
                    </div>
                    @foreach ([['fas fa-hashtag', 'ID', '#' . $baiViet->id, 'var(--navy)'], ['fas fa-eye', 'Lượt xem', number_format($baiViet->luot_xem ?? 0), 'var(--blue)'], ['fas fa-calendar-plus', 'Tạo lúc', $baiViet->created_at->format('d/m/Y H:i'), 'var(--navy)'], ['fas fa-sync-alt', 'Cập nhật', $baiViet->updated_at->diffForHumans(), 'var(--green)']] as [$icon, $label, $val, $color])
                        <div class="d-flex align-items-center justify-content-between mb-2" style="font-size:.76rem">
                            <span style="color:var(--text-sub)">
                                <i class="fas {{ $icon }} me-1"
                                    style="width:13px;text-align:center"></i>{{ $label }}
                            </span>
                            <strong style="color:{{ $color }}">{{ $val }}</strong>
                        </div>
                    @endforeach
                    @if ($baiViet->tacGia)
                        <div class="d-flex align-items-center justify-content-between" style="font-size:.76rem">
                            <span style="color:var(--text-sub)">
                                <i class="fas fa-user me-1" style="width:13px;text-align:center"></i>Tác giả
                            </span>
                            <div class="d-flex align-items-center gap-1">
                                <img src="{{ $baiViet->tacGia->anh_dai_dien_url }}" class="avatar"
                                    style="width:18px;height:18px;border-width:1px">
                                <strong style="color:var(--navy)">{{ $baiViet->tacGia->ho_ten }}</strong>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif

    </div>{{-- /col-lg-4 --}}
</div>{{-- /row --}}


@push('scripts')
    <script>
        'use strict';

        /* ── Slug helpers ── */
        function toSlug(str) {
            const map = {
                'à': 'a',
                'á': 'a',
                'ả': 'a',
                'ã': 'a',
                'ạ': 'a',
                'ă': 'a',
                'ắ': 'a',
                'ặ': 'a',
                'ằ': 'a',
                'ẳ': 'a',
                'ẵ': 'a',
                'â': 'a',
                'ấ': 'a',
                'ầ': 'a',
                'ẩ': 'a',
                'ẫ': 'a',
                'ậ': 'a',
                'đ': 'd',
                'è': 'e',
                'é': 'e',
                'ẻ': 'e',
                'ẽ': 'e',
                'ẹ': 'e',
                'ê': 'e',
                'ế': 'e',
                'ề': 'e',
                'ể': 'e',
                'ễ': 'e',
                'ệ': 'e',
                'ì': 'i',
                'í': 'i',
                'ỉ': 'i',
                'ĩ': 'i',
                'ị': 'i',
                'ò': 'o',
                'ó': 'o',
                'ỏ': 'o',
                'õ': 'o',
                'ọ': 'o',
                'ô': 'o',
                'ố': 'o',
                'ồ': 'o',
                'ổ': 'o',
                'ỗ': 'o',
                'ộ': 'o',
                'ơ': 'o',
                'ớ': 'o',
                'ờ': 'o',
                'ở': 'o',
                'ỡ': 'o',
                'ợ': 'o',
                'ù': 'u',
                'ú': 'u',
                'ủ': 'u',
                'ũ': 'u',
                'ụ': 'u',
                'ư': 'u',
                'ứ': 'u',
                'ừ': 'u',
                'ử': 'u',
                'ữ': 'u',
                'ự': 'u',
                'ỳ': 'y',
                'ý': 'y',
                'ỷ': 'y',
                'ỹ': 'y',
                'ỵ': 'y'
            };
            return str.toLowerCase().split('').map(c => map[c] ?? c).join('').replace(/[^a-z0-9\s-]/g, '').trim().replace(
                /\s+/g, '-').replace(/-+/g, '-');
        }

        function regenerateSlug() {
            const title = document.getElementById('bv_tieu_de').value;
            const slug = toSlug(title);
            document.getElementById('bv_slug').value = slug;
            document.getElementById('seo_preview_slug').textContent = slug || 'duong-dan-bai-viet';
        }

        /* ── Auto slug khi thêm mới ── */
        @if (!$isEdit)
            let _slugLocked = false;
            document.getElementById('bv_tieu_de').addEventListener('input', function() {
                if (!_slugLocked) {
                    const slug = toSlug(this.value);
                    document.getElementById('bv_slug').value = slug;
                    document.getElementById('seo_preview_slug').textContent = slug || 'duong-dan-bai-viet';
                }
                updateSeoPreview();
            });
            document.getElementById('bv_slug').addEventListener('input', function() {
                _slugLocked = !!this.value;
                this.value = toSlug(this.value);
                document.getElementById('seo_preview_slug').textContent = this.value || 'duong-dan-bai-viet';
            });
        @else
            document.getElementById('bv_tieu_de').addEventListener('input', updateSeoPreview);
            document.getElementById('bv_slug').addEventListener('input', function() {
                document.getElementById('seo_preview_slug').textContent = this.value || 'duong-dan-bai-viet';
            });
        @endif

        /* ── SEO Preview update ── */
        function updateSeoPreview() {
            const t = document.getElementById('bv_meta_title').value ||
                document.getElementById('bv_tieu_de').value ||
                'Tiêu đề bài viết...';
            const d = document.getElementById('bv_meta_desc').value ||
                document.getElementById('bv_mo_ta_ngan').value ||
                'Mô tả bài viết...';
            document.getElementById('seo_preview_title').textContent = t;
            document.getElementById('seo_preview_desc').textContent = d;
        }

        /* ── Char counters ── */
        function initCounter(inputId, numId, max, warnAt) {
            const el = document.getElementById(inputId);
            const nm = document.getElementById(numId);
            if (!el || !nm) return;
            el.addEventListener('input', function() {
                const len = this.value.length;
                nm.textContent = len;
                nm.parentElement.style.color = len > (warnAt || max) ? 'var(--yellow)' : '';
            });
        }
        initCounter('bv_tieu_de', 'bv_tieu_de_count', 255, 70);
        initCounter('bv_mo_ta_ngan', 'bv_mo_ta_num', 300, 160);
        initCounter('bv_meta_title', 'bv_meta_title_num', 70, 60);
        initCounter('bv_meta_desc', 'bv_meta_desc_num', 165, 160);

        /* Update counters cho bv_tieu_de (span, không phải inner num) */
        document.getElementById('bv_tieu_de').addEventListener('input', function() {
            document.getElementById('bv_tieu_de_count').textContent = this.value.length + '/255';
            updateSeoPreview();
        });
        document.getElementById('bv_meta_title').addEventListener('input', updateSeoPreview);
        document.getElementById('bv_meta_desc').addEventListener('input', updateSeoPreview);
        document.getElementById('bv_mo_ta_ngan').addEventListener('input', updateSeoPreview);

        /* ── Word count ── */
        document.getElementById('bv_noi_dung').addEventListener('input', function() {
            const words = this.value.trim() ? this.value.trim().split(/\s+/).length : 0;
            document.getElementById('bv_word_count').textContent = words.toLocaleString('vi-VN') + ' từ';
        });

        /* ── Thumbnail ── */
        function handleThumbChange(input) {
            if (!input.files?.[0]) return;
            const url = URL.createObjectURL(input.files[0]);
            document.getElementById('bv_thumb_preview').src = url;
            document.getElementById('bv_thumb_preview_wrap').style.display = 'block';
            document.getElementById('bv_thumb_upload').style.display = 'none';
        }

        function removeThumb() {
            document.getElementById('bv_anh_dai_dien').value = '';
            document.getElementById('bv_thumb_preview').src = '';
            document.getElementById('bv_thumb_preview_wrap').style.display = 'none';
            document.getElementById('bv_thumb_upload').style.display = 'block';
        }

        /* ── Tag preview ── */
        function renderTagPreview() {
            const raw = document.getElementById('bv_tags').value;
            const wrap = document.getElementById('bv_tag_preview');
            wrap.innerHTML = '';
            raw.split(',').map(t => t.trim()).filter(Boolean).forEach(tag => {
                const span = document.createElement('span');
                span.className = 'badge-status badge-sold';
                span.style.cssText = 'font-size:.68rem;cursor:default;user-select:none';
                span.innerHTML = `<i class="fas fa-tag" style="font-size:.55rem"></i> ${tag}`;
                wrap.appendChild(span);
            });
        }
        document.getElementById('bv_tags').addEventListener('input', debounce(renderTagPreview, 400));
        renderTagPreview();

        /* ── Trạng thái radio UX ── */
        document.querySelectorAll('.trang-thai-radio').forEach(radio => {
            radio.closest('label').addEventListener('click', function() {
                document.querySelectorAll('.trang-thai-radio').forEach(r => {
                    const lbl = r.closest('label');
                    const colors = {
                        'hien_thi': 'var(--green)',
                        'an': 'var(--text-muted)'
                    };
                    const c = colors[r.value] || 'var(--border)';
                    if (r.checked) {
                        lbl.style.borderColor = c;
                        lbl.style.background = 'var(--bg-alt)';
                        const icon = lbl.querySelector('div:first-of-type');
                        const i = lbl.querySelector(
                            'i.fas:not(.fa-check-circle):not(.fa-pencil-alt):not(.fa-check-circle):not(.fa-eye-slash)'
                        );
                        if (icon) {
                            icon.style.background = c;
                        }
                        let chk = lbl.querySelector('.fa-check-circle.ms-auto');
                        if (!chk) {
                            chk = document.createElement('i');
                            chk.className = 'fas fa-check-circle ms-auto';
                            chk.style.cssText = 'color:' + c + ';flex-shrink:0';
                            lbl.appendChild(chk);
                        }
                    } else {
                        lbl.style.borderColor = 'var(--border)';
                        lbl.style.background = '#fff';
                        const icon = lbl.querySelector('div:first-of-type');
                        if (icon) {
                            icon.style.background = 'var(--bg)';
                        }
                        lbl.querySelector('.fa-check-circle.ms-auto')?.remove();
                    }
                });
            });
        });

        function toDateTimeLocal(value = new Date()) {
            const pad = n => String(n).padStart(2, '0');
            return `${value.getFullYear()}-${pad(value.getMonth() + 1)}-${pad(value.getDate())}T${pad(value.getHours())}:${pad(value.getMinutes())}`;
        }

        function syncNgayDangByTrangThai() {
            const selected = document.querySelector('.trang-thai-radio:checked')?.value;
            const ngayDangEl = document.getElementById('bv_ngay_dang');
            if (!selected || !ngayDangEl) return;

            if (selected === 'hien_thi' && !ngayDangEl.value) {
                ngayDangEl.value = toDateTimeLocal();
            }
        }

        document.querySelectorAll('.trang-thai-radio').forEach(radio => {
            radio.addEventListener('change', syncNgayDangByTrangThai);
        });

        syncNgayDangByTrangThai();

        /* ── Xóa bài viết (tránh nested form gây submit nhầm) ── */
        document.querySelectorAll('[data-delete-url]').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const url = this.dataset.deleteUrl;
                const label = this.dataset.confirmDelete || 'mục này';
                confirmDelete(label, () => {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = url;

                    const csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = document.querySelector('meta[name="csrf-token"]')?.content || '';

                    const method = document.createElement('input');
                    method.type = 'hidden';
                    method.name = '_method';
                    method.value = 'DELETE';

                    form.appendChild(csrf);
                    form.appendChild(method);
                    document.body.appendChild(form);
                    form.submit();
                });
            });
        });

        /* ── Nổi bật toggle label ── */
        updateToggleLabel(document.getElementById('bv_noi_bat'), document.getElementById('bv_noi_bat_lbl'));

        /* ── Submit loading ── */
        document.getElementById('bvForm').addEventListener('submit', function() {
            btnLoading(document.getElementById('bvSubmitBtn'), '{{ $isEdit ? 'Đang lưu...' : 'Đang đăng...' }}');
        });

        /* ── Init TinyMCE (nếu có) ── */
        if (typeof tinymce !== 'undefined') {
            tinymce.init({
                selector: '#bv_noi_dung',
                toolbar_id: 'bv_editor_toolbar',
                height: 420,
                menubar: false,
                plugins: 'lists link image table code wordcount',
                toolbar: 'undo redo | formatselect | bold italic underline | alignleft aligncenter alignright | bullist numlist | link image table | code',
                language: 'vi',
                content_style: 'body { font-family: "Be Vietnam Pro", sans-serif; font-size: 14px; line-height: 1.7; }',
                setup: editor => {
                    editor.on('input', () => {
                        const wc = editor.plugins.wordcount.getCount();
                        document.getElementById('bv_word_count').textContent = wc.toLocaleString(
                            'vi-VN') + ' từ';
                    });
                }
            });
            document.getElementById('bv_editor_toolbar').style.display = 'none';
        }

        /* ── Init word count ── */
        (function() {
            const txt = document.getElementById('bv_noi_dung').value.trim();
            document.getElementById('bv_word_count').textContent = (txt ? txt.split(/\s+/).length : 0).toLocaleString(
                'vi-VN') + ' từ';
        })();
    </script>
@endpush
