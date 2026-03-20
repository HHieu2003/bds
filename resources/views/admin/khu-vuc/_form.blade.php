@php
    $isEdit = !is_null($khuVuc);
    $oldCap = old('cap_khu_vuc', $capMacDinh ?? 'quan_huyen');
    $oldChaId = old('khu_vuc_cha_id', $chaMacDinh ?? '');
@endphp

<div class="kv-fg-grid">

    {{-- ══════════ CỘT TRÁI ══════════ --}}
    <div class="kv-fg-left">

        {{-- ① THÔNG TIN CƠ BẢN --}}
        <div class="kv-fc">
            <div class="kv-fc-head"><i class="fas fa-info-circle"></i> Thông tin khu vực</div>
            <div class="kv-fc-body">

                {{-- Cấp khu vực --}}
                <div class="kv-fg">
                    <label class="kv-fl req">Cấp hành chính</label>
                    <div class="kv-cap-grid">
                        @foreach (\App\Models\KhuVuc::CAP as $v => $info)
                            <label class="kv-cap-item {{ $oldCap == $v ? 'active' : '' }}"
                                style="{{ $oldCap == $v ? 'border-color:' . $info['color'] . ';background:' . $info['bg'] . ';color:' . $info['color'] : '' }}"
                                id="capLabel_{{ $v }}">
                                <input type="radio" name="cap_khu_vuc" value="{{ $v }}"
                                    {{ $oldCap == $v ? 'checked' : '' }} style="display:none" class="kv-cap-radio">
                                <i class="{{ $info['icon'] }}" style="font-size:1.3rem;margin-bottom:5px"></i>
                                <span>{{ $info['label'] }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('cap_khu_vuc')
                        <div class="kv-fe"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="kv-row-2">

                    {{-- Tên khu vực --}}
                    <div class="kv-fg">
                        <label class="kv-fl req">Tên khu vực</label>
                        <input type="text" name="ten_khu_vuc" id="kvTenInput"
                            class="kv-fi @error('ten_khu_vuc') kv-fi-err @enderror"
                            value="{{ old('ten_khu_vuc', $isEdit ? $khuVuc->ten_khu_vuc : '') }}"
                            placeholder="VD: Quận 1, Hà Nội, Phường Bến Nghé...">
                        <div class="kv-slug-preview">
                            Slug: <span id="slugPreview" style="color:#2d6a9f;font-family:monospace">
                                {{ $isEdit ? $khuVuc->slug : '' }}
                            </span>
                        </div>
                        @error('ten_khu_vuc')
                            <div class="kv-fe"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Mã hành chính --}}
                    <div class="kv-fg">
                        <label class="kv-fl">
                            Mã hành chính
                            <span class="kv-hint">VD: 01, 10, 268</span>
                        </label>
                        <input type="text" name="ma_hanh_chinh"
                            class="kv-fi @error('ma_hanh_chinh') kv-fi-err @enderror"
                            value="{{ old('ma_hanh_chinh', $isEdit ? $khuVuc->ma_hanh_chinh : '') }}"
                            placeholder="Mã theo chuẩn Bộ Nội vụ...">
                        @error('ma_hanh_chinh')
                            <div class="kv-fe"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>

                </div>

                {{-- Mô tả --}}
                <div class="kv-fg">
                    <label class="kv-fl">Mô tả khu vực</label>
                    <textarea name="mo_ta" class="kv-fi" rows="4" placeholder="Mô tả đặc điểm khu vực, thị trường BĐS tại đây...">{{ old('mo_ta', $isEdit ? $khuVuc->mo_ta : '') }}</textarea>
                </div>

            </div>
        </div>

        {{-- ② SEO --}}
        <div class="kv-fc">
            <div class="kv-fc-head">
                <i class="fas fa-search"></i> SEO — Landing page khu vực
            </div>
            <div class="kv-fc-body">

                <div class="kv-fg">
                    <label class="kv-fl">
                        SEO Title
                        <span id="seoTitleCount" class="kv-char-badge">0/70</span>
                    </label>
                    <input type="text" name="seo_title" id="seoTitle" class="kv-fi" maxlength="70"
                        value="{{ old('seo_title', $isEdit ? $khuVuc->seo_title : '') }}"
                        placeholder="BĐS {{ $isEdit ? $khuVuc->ten_khu_vuc : 'Khu vực' }} — Mua bán nhà đất...">
                    <div class="kv-seo-bar">
                        <div id="seoTitleBar" class="kv-seo-fill"></div>
                    </div>
                </div>

                <div class="kv-fg">
                    <label class="kv-fl">
                        SEO Description
                        <span id="seoDescCount" class="kv-char-badge">0/160</span>
                    </label>
                    <textarea name="seo_description" id="seoDesc" class="kv-fi" rows="3" maxlength="160"
                        placeholder="Tổng hợp bất động sản tại...">{{ old('seo_description', $isEdit ? $khuVuc->seo_description : '') }}</textarea>
                    <div class="kv-seo-bar">
                        <div id="seoDescBar" class="kv-seo-fill"></div>
                    </div>
                </div>

                <div class="kv-fg">
                    <label class="kv-fl">SEO Keywords</label>
                    <input type="text" name="seo_keywords" class="kv-fi"
                        value="{{ old('seo_keywords', $isEdit ? $khuVuc->seo_keywords : '') }}"
                        placeholder="mua bán nhà quận 1, bất động sản hà nội...">
                    <div style="font-size:.72rem;color:#bbb;margin-top:3px">Phân cách bằng dấu phẩy</div>
                </div>

            </div>
        </div>

    </div>{{-- end left --}}

    {{-- ══════════ CỘT PHẢI ══════════ --}}
    <div class="kv-fg-right">

        {{-- NÚT LƯU --}}
        <div class="kv-fc">
            <div class="kv-fc-body" style="padding:16px">
                <button type="submit" class="kv-btn-save" form="kvForm">
                    <i class="fas fa-save"></i>
                    {{ $isEdit ? 'Lưu thay đổi' : 'Thêm khu vực' }}
                </button>
                <a href="{{ route('nhanvien.admin.khu-vuc.index') }}" class="kv-btn-cancel">
                    <i class="fas fa-times"></i> Hủy bỏ
                </a>
            </div>
        </div>

        {{-- KHU VỰC CHA (động theo cấp) --}}
        <div class="kv-fc" id="kv-cha-box">
            <div class="kv-fc-head" id="kvChaHead">
                <i class="fas fa-sitemap"></i>
                <span id="kvChaTitle">Thuộc tỉnh / thành phố</span>
            </div>
            <div class="kv-fc-body">

                {{-- Chọn tỉnh/thành --}}
                <div class="kv-fg" id="kv-chon-tinh">
                    <label class="kv-fl">Tỉnh / Thành phố cha</label>
                    <select name="khu_vuc_cha_id" id="selectTinh" class="kv-fi kv-select">
                        <option value="">— Không có cha —</option>
                        @foreach ($tinhThanhs as $tinh)
                            <option value="{{ $tinh->id }}"
                                {{ (string) $oldChaId === (string) $tinh->id ? 'selected' : '' }}>
                                {{ $tinh->ten_khu_vuc }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Chọn quận/huyện (dành cho phuong_xa) --}}
                <div class="kv-fg" id="kv-chon-quan" style="display:none">
                    <label class="kv-fl">Quận / Huyện cha</label>
                    <select name="khu_vuc_cha_id" id="selectQuan" class="kv-fi kv-select" disabled>
                        <option value="">— Chọn quận/huyện —</option>
                        @foreach ($quanHuyens as $quan)
                            <option value="{{ $quan->id }}"
                                {{ (string) $oldChaId === (string) $quan->id ? 'selected' : '' }}>
                                {{ $quan->ten_khu_vuc }}
                                @if ($quan->cha)
                                    ({{ $quan->cha->ten_khu_vuc }})
                                @endif
                            </option>
                        @endforeach
                    </select>
                    <div style="font-size:.72rem;color:#bbb;margin-top:4px">
                        <i class="fas fa-info-circle"></i>
                        Chọn tỉnh/thành trước để lọc danh sách
                    </div>
                </div>

                @error('khu_vuc_cha_id')
                    <div class="kv-fe"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- THIẾT LẬP --}}
        <div class="kv-fc">
            <div class="kv-fc-head"><i class="fas fa-cog"></i> Thiết lập</div>
            <div class="kv-fc-body">

                <div class="kv-tg-row">
                    <div class="kv-tg-info">
                        <span>Hiển thị khu vực</span>
                        <small>Dùng được trong tìm kiếm BĐS</small>
                    </div>
                    <label class="kv-sw">
                        <input type="checkbox" name="hien_thi" value="1"
                            {{ old('hien_thi', $isEdit ? $khuVuc->hien_thi : true) ? 'checked' : '' }}>
                        <span class="kv-sw-track"><span class="kv-sw-thumb"></span></span>
                    </label>
                </div>

                <div class="kv-divider"></div>

                <div class="kv-fg" style="margin-bottom:0">
                    <label class="kv-fl">Thứ tự hiển thị</label>
                    <input type="number" name="thu_tu_hien_thi" class="kv-fi"
                        value="{{ old('thu_tu_hien_thi', $isEdit ? $khuVuc->thu_tu_hien_thi : 0) }}" min="0"
                        max="9999" placeholder="0">
                    <div style="font-size:.72rem;color:#bbb;margin-top:3px">Số nhỏ hiển thị trước</div>
                </div>

            </div>
        </div>

        {{-- THÔNG TIN HỆ THỐNG --}}
        @if ($isEdit)
            <div class="kv-fc">
                <div class="kv-fc-head"><i class="fas fa-info-circle"></i> Thông tin</div>
                <div class="kv-fc-body">
                    <div class="kv-sys-row"><span class="kv-sys-key"><i class="fas fa-hashtag"></i> ID</span>
                        <span class="kv-sys-val"
                            style="font-family:monospace;background:#f0f4ff;color:#2d6a9f;padding:.1rem .4rem;border-radius:4px">#{{ $khuVuc->id }}</span>
                    </div>
                    <div class="kv-sys-row"><span class="kv-sys-key"><i class="fas fa-route"></i> Đường dẫn</span>
                        <span class="kv-sys-val"
                            style="font-size:.72rem;text-align:right">{{ $khuVuc->duong_dan }}</span>
                    </div>
                    <div class="kv-sys-row"><span class="kv-sys-key"><i class="fas fa-calendar"></i> Tạo lúc</span>
                        <span class="kv-sys-val">{{ $khuVuc->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="kv-sys-row"><span class="kv-sys-key"><i class="fas fa-sync"></i> Cập nhật</span>
                        <span class="kv-sys-val">{{ $khuVuc->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>
        @endif

        {{-- NÚT LƯU cuối --}}
        <div class="kv-fc">
            <div class="kv-fc-body" style="padding:16px">
                <button type="submit" class="kv-btn-save" form="kvForm">
                    <i class="fas fa-save"></i>
                    {{ $isEdit ? 'Lưu thay đổi' : 'Thêm khu vực' }}
                </button>
            </div>
        </div>

    </div>{{-- end right --}}
</div>{{-- end grid --}}

@push('styles')
    <style>
        .kv-form-hdr {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 12px
        }

        .kv-bc {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: .8rem;
            color: #aaa;
            margin-bottom: 6px
        }

        .kv-bc a {
            color: #aaa;
            text-decoration: none
        }

        .kv-bc a:hover {
            color: #FF8C42
        }

        .kv-bc i {
            font-size: .65rem
        }

        .kv-form-ttl {
            font-size: 1.3rem;
            font-weight: 700;
            color: #1a3c5e;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px
        }

        .kv-form-ttl i {
            color: #FF8C42
        }

        .kv-fg-grid {
            display: grid;
            grid-template-columns: 1fr 300px;
            gap: 20px;
            align-items: start
        }

        @media(max-width:980px) {
            .kv-fg-grid {
                grid-template-columns: 1fr
            }

            .kv-fg-right {
                order: -1
            }
        }

        .kv-fc {
            background: #fff;
            border-radius: 14px;
            border: 1px solid #f0f2f5;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .05);
            margin-bottom: 18px;
            overflow: hidden
        }

        .kv-fc:last-child {
            margin-bottom: 0
        }

        .kv-fc-head {
            padding: 12px 18px;
            background: linear-gradient(135deg, #f8faff, #eef3ff);
            border-bottom: 1px solid #e8eeff;
            font-weight: 700;
            font-size: .86rem;
            color: #1a3c5e;
            display: flex;
            align-items: center;
            gap: 8px
        }

        .kv-fc-head i {
            color: #FF8C42
        }

        .kv-hint {
            font-weight: 400;
            color: #bbb;
            font-size: .72rem;
            margin-left: 4px
        }

        .kv-fc-body {
            padding: 18px
        }

        .kv-fg {
            margin-bottom: 14px
        }

        .kv-fg:last-child {
            margin-bottom: 0
        }

        .kv-fl {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
            font-weight: 700;
            font-size: .75rem;
            color: #666;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: .3px
        }

        .kv-fl.req::after {
            content: ' *';
            color: #e74c3c
        }

        .kv-fi {
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

        input.kv-fi {
            height: 40px;
            padding: 0 12px
        }

        .kv-fi:focus {
            border-color: #FF8C42;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(255, 140, 66, .1)
        }

        .kv-fi.kv-fi-err {
            border-color: #e74c3c;
            background: #fff8f8
        }

        .kv-select {
            height: 40px;
            padding: 0 32px 0 12px;
            appearance: none;
            cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath fill='%23aaa' d='M5 6L0 0h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center
        }

        .kv-fe {
            font-size: .78rem;
            color: #e74c3c;
            margin-top: 4px;
            display: flex;
            align-items: center;
            gap: 4px
        }

        .kv-slug-preview {
            font-size: .72rem;
            color: #bbb;
            margin-top: 4px
        }

        .kv-row-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px
        }

        @media(max-width:640px) {
            .kv-row-2 {
                grid-template-columns: 1fr
            }
        }

        /* CAP RADIO */
        .kv-cap-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px
        }

        .kv-cap-item {
            border: 1.5px solid #f0f2f5;
            border-radius: 10px;
            padding: 12px 8px;
            text-align: center;
            cursor: pointer;
            transition: all .2s;
            background: #fafafa;
            font-size: .78rem;
            font-weight: 600;
            color: #aaa;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 5px
        }

        .kv-cap-item:hover {
            border-color: #dde5f5;
            background: #f5f8ff
        }

        .kv-cap-item.active {
            box-shadow: 0 3px 10px rgba(0, 0, 0, .08)
        }

        /* SEO */
        .kv-char-badge {
            font-weight: 400;
            color: #aaa;
            font-size: .72rem;
            background: #f0f2f5;
            padding: .1rem .4rem;
            border-radius: 4px;
            margin-left: auto
        }

        .kv-seo-bar {
            height: 4px;
            background: #f0f2f5;
            border-radius: 2px;
            margin-top: 5px;
            overflow: hidden
        }

        .kv-seo-fill {
            height: 100%;
            border-radius: 2px;
            transition: width .2s, background .2s;
            background: #27ae60;
            width: 0
        }

        /* TOGGLE */
        .kv-tg-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 4px 0
        }

        .kv-tg-info span {
            display: block;
            font-weight: 600;
            font-size: .86rem;
            color: #333;
            margin-bottom: 2px
        }

        .kv-tg-info small {
            color: #bbb;
            font-size: .76rem
        }

        .kv-sw {
            position: relative;
            cursor: pointer;
            flex-shrink: 0;
            display: inline-block
        }

        .kv-sw input {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0
        }

        .kv-sw-track {
            display: block;
            width: 44px;
            height: 25px;
            background: #dde0e8;
            border-radius: 13px;
            transition: background .25s;
            position: relative
        }

        .kv-sw input:checked~.kv-sw-track {
            background: #27ae60
        }

        .kv-sw-thumb {
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

        .kv-sw input:checked~.kv-sw-track .kv-sw-thumb {
            transform: translateX(19px)
        }

        .kv-divider {
            border: none;
            border-top: 1px solid #f5f5f5;
            margin: 12px 0
        }

        /* BUTTONS */
        .kv-btn-save {
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

        .kv-btn-save:hover {
            transform: translateY(-1px)
        }

        .kv-btn-cancel {
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

        .kv-btn-cancel:hover {
            background: #ffeee0;
            color: #FF8C42;
            border-color: #FF8C42
        }

        /* SYS INFO */
        .kv-sys-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 6px 0;
            border-bottom: 1px solid #fafafa
        }

        .kv-sys-row:last-child {
            border-bottom: none
        }

        .kv-sys-key {
            font-size: .75rem;
            color: #bbb;
            display: flex;
            align-items: center;
            gap: 4px
        }

        .kv-sys-val {
            font-size: .78rem;
            font-weight: 600;
            color: #555;
            text-align: right
        }
    </style>
@endpush

@push('scripts')
    <script>
        // ── Màu sắc theo cấp ──
        const capColors = {
            tinh_thanh: {
                color: '#e74c3c',
                bg: '#fff0f0'
            },
            quan_huyen: {
                color: '#2d6a9f',
                bg: '#e8f4fd'
            },
            phuong_xa: {
                color: '#27ae60',
                bg: '#e8f8f0'
            },
        };

        const capTitles = {
            tinh_thanh: {
                show: false,
                title: ''
            },
            quan_huyen: {
                show: true,
                title: 'Thuộc tỉnh / thành phố',
                selectType: 'tinh'
            },
            phuong_xa: {
                show: true,
                title: 'Thuộc quận / huyện',
                selectType: 'quan'
            },
        };

        // ── Radio cấp: xử lý chọn và hiện/ẩn cha ──
        const capBox = document.getElementById('kv-cha-box');
        const capTitle = document.getElementById('kvChaTitle');
        const chonTinh = document.getElementById('kv-chon-tinh');
        const chonQuan = document.getElementById('kv-chon-quan');
        const selectTinh = document.getElementById('selectTinh');
        const selectQuan = document.getElementById('selectQuan');
        const CSRF = document.querySelector('meta[name=csrf-token]').content;

        function applyCapUI(val) {
            // Màu radio cards
            document.querySelectorAll('.kv-cap-item').forEach(item => {
                const iv = item.querySelector('input').value;
                const c = capColors[iv] || {
                    color: '#999',
                    bg: '#fafafa'
                };
                item.classList.remove('active');
                item.style.borderColor = '#f0f2f5';
                item.style.background = '#fafafa';
                item.style.color = '#aaa';
                if (iv === val) {
                    item.classList.add('active');
                    item.style.borderColor = c.color;
                    item.style.background = c.bg;
                    item.style.color = c.color;
                }
            });

            // Hiện/ẩn box cha
            const cfg = capTitles[val];
            if (!cfg || !cfg.show) {
                capBox.style.display = 'none';
                // Disable selects để không gửi giá trị
                selectTinh.disabled = true;
                selectQuan.disabled = true;
                return;
            }
            capBox.style.display = '';
            capTitle.textContent = cfg.title;

            if (cfg.selectType === 'tinh') {
                chonTinh.style.display = '';
                chonQuan.style.display = 'none';
                selectTinh.disabled = false;
                selectTinh.name = 'khu_vuc_cha_id';
                selectQuan.disabled = true;
                selectQuan.name = '_ignore';
            } else {
                // phuong_xa: cần chọn tỉnh để lọc quận
                chonTinh.style.display = '';
                chonQuan.style.display = '';
                selectTinh.disabled = false;
                selectTinh.name = '_tinh_filter'; // không submit
                selectQuan.disabled = false;
                selectQuan.name = 'khu_vuc_cha_id';

                // Load danh sách quận theo tỉnh đang chọn
                const tinhId = selectTinh.value;
                if (tinhId) loadQuanByTinh(tinhId);
            }
        }

        // Khởi tạo giao diện theo cấp hiện tại
        applyCapUI('{{ $oldCap }}');

        // Sự kiện khi chọn cấp
        document.querySelectorAll('.kv-cap-radio').forEach(radio => {
            radio.addEventListener('change', () => applyCapUI(radio.value));
        });
        document.querySelectorAll('.kv-cap-item').forEach(item => {
            item.addEventListener('click', () => {
                const r = item.querySelector('input');
                r.checked = true;
                applyCapUI(r.value);
            });
        });

        // ── Khi chọn tỉnh → load quận cho phường/xã ──
        selectTinh.addEventListener('change', function() {
            const cap = document.querySelector('.kv-cap-radio:checked')?.value;
            if (cap === 'phuong_xa' && this.value) {
                loadQuanByTinh(this.value);
            }
        });

        function loadQuanByTinh(tinhId) {
            fetch('/nhan-vien/admin/khu-vuc/ajax/danh-sach-con?cha_id=' + tinhId + '&cap=quan_huyen', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF
                    }
                })
                .then(r => r.json())
                .then(data => {
                    const currentVal = '{{ $oldChaId }}';
                    selectQuan.innerHTML = '<option value="">— Chọn quận/huyện —</option>';
                    data.forEach(q => {
                        const opt = document.createElement('option');
                        opt.value = q.id;
                        opt.textContent = q.ten_khu_vuc;
                        if (String(q.id) === String(currentVal)) opt.selected = true;
                        selectQuan.appendChild(opt);
                    });
                });
        }

        // ── Slug preview ──
        const tenInput = document.getElementById('kvTenInput');
        const slugEl = document.getElementById('slugPreview');
        const isEdit = {{ $isEdit ? 'true' : 'false' }};

        if (!isEdit && tenInput && slugEl) {
            tenInput.addEventListener('input', function() {
                const slug = this.value.toLowerCase()
                    .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
                    .replace(/đ/g, 'd').replace(/[^a-z0-9\s-]/g, '')
                    .trim().replace(/\s+/g, '-');
                slugEl.textContent = slug || '...';
            });
        }

        // ── SEO counter + bar ──
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
    </script>
@endpush
