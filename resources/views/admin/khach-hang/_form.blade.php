@php
    $isEdit = isset($khachHang) && $khachHang !== null;
    $oldNguon = old('nguon_khach_hang', $isEdit ? $khachHang->nguon_khach_hang : 'website');
    $oldMucDo = old('muc_do_tiem_nang', $isEdit ? $khachHang->muc_do_tiem_nang : 'am');
@endphp

<div class="kh-fg-grid">

    {{-- ══════════ CỘT TRÁI ══════════ --}}
    <div class="kh-fg-left">

        {{-- ① THÔNG TIN CÁ NHÂN --}}
        <div class="kh-fc">
            <div class="kh-fc-head">
                <i class="fas fa-user"></i> Thông tin cá nhân
            </div>
            <div class="kh-fc-body">

                <div class="kh-fg-row2">
                    <div class="kh-fg">
                        <label class="kh-fl">Họ và tên</label>
                        <input type="text" name="ho_ten" class="kh-fi"
                            value="{{ old('ho_ten', $isEdit ? $khachHang->ho_ten : '') }}"
                            placeholder="VD: Nguyễn Văn An">
                        @error('ho_ten')
                            <div class="kh-fe"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    <div class="kh-fg">
                        <label class="kh-fl">Số điện thoại</label>
                        <input type="text" name="so_dien_thoai"
                            class="kh-fi @error('so_dien_thoai') kh-fi-err @enderror"
                            value="{{ old('so_dien_thoai', $isEdit ? $khachHang->so_dien_thoai : '') }}"
                            placeholder="VD: 0912345678">
                        @error('so_dien_thoai')
                            <div class="kh-fe"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="kh-fg-row2">
                    <div class="kh-fg">
                        <label class="kh-fl">Email</label>
                        <input type="email" name="email" class="kh-fi @error('email') kh-fi-err @enderror"
                            value="{{ old('email', $isEdit ? $khachHang->email : '') }}"
                            placeholder="VD: khachhang@gmail.com">
                        @error('email')
                            <div class="kh-fe"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    <div class="kh-fg">
                        <label class="kh-fl">
                            Mật khẩu
                            @if ($isEdit)
                                <span class="kh-hint">để trống = không đổi</span>
                            @else
                                <span class="kh-hint">không bắt buộc</span>
                            @endif
                        </label>
                        <div class="kh-pw-wrap">
                            <input type="password" name="password" id="kh_pwd"
                                class="kh-fi @error('password') kh-fi-err @enderror"
                                placeholder="{{ $isEdit ? '••••••••' : 'Nhập mật khẩu (tuỳ chọn)' }}">
                            <button type="button" class="kh-pw-eye" onclick="togglePwd()">
                                <i class="fas fa-eye" id="pwdIcon"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="kh-fe"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                </div>

            </div>
        </div>

        {{-- ② PHÂN LOẠI --}}
        <div class="kh-fc">
            <div class="kh-fc-head">
                <i class="fas fa-tags"></i> Phân loại & Phân công
            </div>
            <div class="kh-fc-body">

                <div class="kh-fg-row2">
                    <div class="kh-fg">
                        <label class="kh-fl req">Nguồn khách hàng</label>
                        <select name="nguon_khach_hang" class="kh-fi @error('nguon_khach_hang') kh-fi-err @enderror">
                            @foreach ($constants['nguon'] as $v => $info)
                                <option value="{{ $v }}" {{ $oldNguon == $v ? 'selected' : '' }}>
                                    {{ $info['label'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('nguon_khach_hang')
                            <div class="kh-fe"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    <div class="kh-fg">
                        <label class="kh-fl req">Mức độ tiềm năng</label>
                        <div class="kh-mucdo-sel">
                            @foreach ($constants['muc_do'] as $v => $info)
                                <label class="kh-mds-item {{ $oldMucDo == $v ? 'active' : '' }}"
                                    style="{{ $oldMucDo == $v ? 'border-color:' . $info['color'] . ';background:' . $info['bg'] . ';color:' . $info['color'] : '' }}">
                                    <input type="radio" name="muc_do_tiem_nang" value="{{ $v }}"
                                        {{ $oldMucDo == $v ? 'checked' : '' }} style="display:none">
                                    {{ $info['label'] }}
                                </label>
                            @endforeach
                        </div>
                        @error('muc_do_tiem_nang')
                            <div class="kh-fe"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="kh-fg-row2">
                    <div class="kh-fg">
                        <label class="kh-fl">NV phụ trách</label>
                        <select name="nhan_vien_phu_trach_id" class="kh-fi">
                            <option value="">-- Chưa phân công --</option>
                            @foreach ($nhanViens as $nv)
                                <option value="{{ $nv->id }}"
                                    {{ old('nhan_vien_phu_trach_id', $isEdit ? $khachHang->nhan_vien_phu_trach_id : '') == $nv->id ? 'selected' : '' }}>
                                    {{ $nv->ho_ten }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="kh-fg">
                        <label class="kh-fl">Lần liên hệ cuối</label>
                        <input type="datetime-local" name="lien_he_cuoi_at" class="kh-fi"
                            value="{{ old('lien_he_cuoi_at', $isEdit && $khachHang->lien_he_cuoi_at ? $khachHang->lien_he_cuoi_at->format('Y-m-d\TH:i') : '') }}">
                    </div>
                </div>

            </div>
        </div>

        {{-- ③ GHI CHÚ NỘI BỘ --}}
        <div class="kh-fc">
            <div class="kh-fc-head">
                <i class="fas fa-sticky-note"></i> Ghi chú nội bộ
                <span class="kh-hint">Khách hàng không thấy</span>
            </div>
            <div class="kh-fc-body">
                <div class="kh-fg">
                    <textarea name="ghi_chu_noi_bo" class="kh-fi" rows="5" maxlength="2000"
                        placeholder="Ghi chú về nhu cầu, lịch sử trao đổi, sở thích, yêu cầu đặc biệt của khách hàng...">{{ old('ghi_chu_noi_bo', $isEdit ? $khachHang->ghi_chu_noi_bo : '') }}</textarea>
                    <div class="kh-char-count">
                        <span id="charCount">0</span>/2000 ký tự
                    </div>
                </div>
            </div>
        </div>

    </div>{{-- end left --}}

    {{-- ══════════ CỘT PHẢI — SIDEBAR ══════════ --}}
    <div class="kh-fg-right">

        {{-- NÚT LƯU trên cùng --}}
        <div class="kh-fc">
            <div class="kh-fc-body" style="padding:16px">
                <button type="submit" class="kh-btn-save" form="khForm">
                    <i class="fas fa-save"></i>
                    {{ $isEdit ? 'Lưu thay đổi' : 'Thêm khách hàng' }}
                </button>
                <a href="{{ route('nhanvien.admin.khach-hang.index') }}" class="kh-btn-cancel">
                    <i class="fas fa-times"></i> Hủy bỏ
                </a>
            </div>
        </div>

        {{-- TRẠNG THÁI TÀI KHOẢN --}}
        <div class="kh-fc">
            <div class="kh-fc-head"><i class="fas fa-shield-alt"></i> Trạng thái tài khoản</div>
            <div class="kh-fc-body">

                <div class="kh-tg-row">
                    <div class="kh-tg-info">
                        <span>Kích hoạt tài khoản</span>
                        <small>Cho phép khách đăng nhập website</small>
                    </div>
                    <label class="kh-sw">
                        <input type="checkbox" name="kich_hoat" value="1"
                            {{ old('kich_hoat', $isEdit ? $khachHang->kich_hoat : true) ? 'checked' : '' }}>
                        <span class="kh-sw-track"><span class="kh-sw-thumb"></span></span>
                    </label>
                </div>

                @if ($isEdit)
                    <div style="margin-top:14px">
                        <div class="kh-fc-divider" style="margin-bottom:12px"></div>

                        {{-- Xác thực SĐT --}}
                        <div class="kh-xacthuc-row">
                            <div>
                                <span style="font-size:.8rem;font-weight:600;color:#333">
                                    <i class="fas fa-phone" style="color:#27ae60;margin-right:4px"></i>
                                    Xác thực SĐT
                                </span>
                            </div>
                            @if ($khachHang->sdt_xac_thuc_at)
                                <span class="kh-badge-ok">
                                    <i class="fas fa-check"></i>
                                    {{ $khachHang->sdt_xac_thuc_at->format('d/m/Y') }}
                                </span>
                            @else
                                <span class="kh-badge-no">Chưa xác thực</span>
                            @endif
                        </div>

                        {{-- Xác thực Email --}}
                        <div class="kh-xacthuc-row" style="margin-top:8px">
                            <div>
                                <span style="font-size:.8rem;font-weight:600;color:#333">
                                    <i class="fas fa-envelope" style="color:#2d6a9f;margin-right:4px"></i>
                                    Xác thực Email
                                </span>
                            </div>
                            @if ($khachHang->email_xac_thuc_at)
                                <span class="kh-badge-ok">
                                    <i class="fas fa-check"></i>
                                    {{ $khachHang->email_xac_thuc_at->format('d/m/Y') }}
                                </span>
                            @else
                                <span class="kh-badge-no">Chưa xác thực</span>
                            @endif
                        </div>

                    </div>
                @endif

            </div>
        </div>

        {{-- THÔNG TIN HỆ THỐNG khi edit --}}
        @if ($isEdit)
            <div class="kh-fc">
                <div class="kh-fc-head"><i class="fas fa-info-circle"></i> Thông tin hệ thống</div>
                <div class="kh-fc-body">
                    <div class="kh-sysinfo">

                        <div class="kh-sysrow">
                            <span class="kh-syskey"><i class="fas fa-hashtag"></i> ID</span>
                            <span class="kh-sysval"
                                style="font-family:monospace;background:#f0f4ff;color:#2d6a9f;padding:.1rem .4rem;border-radius:4px">
                                #{{ $khachHang->id }}
                            </span>
                        </div>

                        <div class="kh-sysrow">
                            <span class="kh-syskey"><i class="fas fa-calendar-plus"></i> Đăng ký</span>
                            <span class="kh-sysval">{{ $khachHang->created_at->format('d/m/Y H:i') }}</span>
                        </div>

                        <div class="kh-sysrow">
                            <span class="kh-syskey"><i class="fas fa-sync"></i> Cập nhật</span>
                            <span class="kh-sysval">{{ $khachHang->updated_at->format('d/m/Y H:i') }}</span>
                        </div>

                        @if ($khachHang->lien_he_cuoi_at)
                            <div class="kh-sysrow">
                                <span class="kh-syskey"><i class="fas fa-phone"></i> LH cuối</span>
                                <span class="kh-sysval">{{ $khachHang->lien_he_cuoi_at->format('d/m/Y H:i') }}</span>
                            </div>
                        @endif

                    </div>

                    {{-- Thống kê nhanh --}}
                    <div class="kh-mini-stats">
                        <div class="kh-ms">
                            <div class="kh-ms-num" style="color:#e67e22">
                                {{ $khachHang->lichHens()->count() }}
                            </div>
                            <div class="kh-ms-lbl">Lịch hẹn</div>
                        </div>
                        <div class="kh-ms">
                            <div class="kh-ms-num" style="color:#e74c3c">
                                {{ $khachHang->yeuThichs()->count() }}
                            </div>
                            <div class="kh-ms-lbl">Yêu thích</div>
                        </div>
                        <div class="kh-ms">
                            <div class="kh-ms-num" style="color:#2d6a9f">
                                {{ $khachHang->canhBaoGias()->count() }}
                            </div>
                            <div class="kh-ms-lbl">Cảnh báo giá</div>
                        </div>
                    </div>

                </div>
            </div>
        @endif

        {{-- NÚT LƯU dưới cùng --}}
        <div class="kh-fc">
            <div class="kh-fc-body" style="padding:16px">
                <button type="submit" class="kh-btn-save" form="khForm">
                    <i class="fas fa-save"></i>
                    {{ $isEdit ? 'Lưu thay đổi' : 'Thêm khách hàng' }}
                </button>
            </div>
        </div>

    </div>{{-- end right --}}

</div>{{-- end kh-fg-grid --}}

@push('styles')
    <style>
        /* BREADCRUMB & HEADER FORM */
        .kh-form-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 12px
        }

        .kh-breadcrumb {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: .8rem;
            color: #aaa;
            margin-bottom: 6px
        }

        .kh-breadcrumb a {
            color: #aaa;
            text-decoration: none
        }

        .kh-breadcrumb a:hover {
            color: #FF8C42
        }

        .kh-breadcrumb i.fa-chevron-right {
            font-size: .65rem
        }

        .kh-form-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #1a3c5e;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px
        }

        .kh-form-title i {
            color: #FF8C42
        }

        /* GRID LAYOUT */
        .kh-fg-grid {
            display: grid;
            grid-template-columns: 1fr 300px;
            gap: 20px;
            align-items: start
        }

        @media(max-width:980px) {
            .kh-fg-grid {
                grid-template-columns: 1fr
            }

            .kh-fg-right {
                order: -1
            }
        }

        /* CARD */
        .kh-fc {
            background: #fff;
            border-radius: 14px;
            border: 1px solid #f0f2f5;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .05);
            margin-bottom: 18px;
            overflow: hidden
        }

        .kh-fc:last-child {
            margin-bottom: 0
        }

        .kh-fc-head {
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

        .kh-fc-head i {
            color: #FF8C42
        }

        .kh-hint {
            font-weight: 400;
            color: #bbb;
            font-size: .72rem;
            margin-left: 4px
        }

        .kh-fc-body {
            padding: 18px
        }

        .kh-fc-divider {
            border: none;
            border-top: 1px solid #f5f5f5;
            margin: 14px 0
        }

        /* FORM FIELDS */
        .kh-fg {
            margin-bottom: 14px
        }

        .kh-fg:last-child {
            margin-bottom: 0
        }

        .kh-fg-row2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 14px
        }

        @media(max-width:600px) {
            .kh-fg-row2 {
                grid-template-columns: 1fr
            }
        }

        .kh-fl {
            display: block;
            font-weight: 700;
            font-size: .75rem;
            color: #666;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: .3px
        }

        .kh-fl.req::after {
            content: ' *';
            color: #e74c3c
        }

        .kh-fi {
            width: 100%;
            height: 40px;
            border: 1.5px solid #e8e8e8;
            border-radius: 8px;
            padding: 0 12px;
            font-size: .875rem;
            color: #333;
            background: #fafafa;
            outline: none;
            font-family: inherit;
            transition: border-color .2s, background .2s, box-shadow .2s;
            box-sizing: border-box
        }

        .kh-fi:focus {
            border-color: #FF8C42;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(255, 140, 66, .1)
        }

        .kh-fi.kh-fi-err {
            border-color: #e74c3c;
            background: #fff8f8
        }

        textarea.kh-fi {
            height: auto;
            padding: 10px 12px;
            resize: vertical;
            line-height: 1.6
        }

        select.kh-fi {
            appearance: none;
            cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath fill='%23aaa' d='M5 6L0 0h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-color: #fafafa;
            padding-right: 32px
        }

        .kh-fe {
            font-size: .78rem;
            color: #e74c3c;
            margin-top: 4px;
            display: flex;
            align-items: center;
            gap: 4px
        }

        .kh-char-count {
            font-size: .72rem;
            color: #bbb;
            text-align: right;
            margin-top: 4px
        }

        /* PASSWORD TOGGLE */
        .kh-pw-wrap {
            position: relative
        }

        .kh-pw-wrap .kh-fi {
            padding-right: 42px
        }

        .kh-pw-eye {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #bbb;
            font-size: .85rem;
            padding: 0
        }

        .kh-pw-eye:hover {
            color: #555
        }

        /* MỨC ĐỘ TIỀM NĂNG --selector nút --*/
        .kh-mucdo-sel {
            display: flex;
            gap: 6px;
            flex-wrap: wrap
        }

        .kh-mds-item {
            flex: 1;
            min-width: 70px;
            text-align: center;
            padding: .45rem .4rem;
            border: 1.5px solid #e8e8e8;
            border-radius: 8px;
            cursor: pointer;
            font-size: .8rem;
            font-weight: 600;
            color: #aaa;
            background: #fafafa;
            transition: all .2s;
            user-select: none
        }

        .kh-mds-item:hover,
        .kh-mds-item.active {
            border-color: currentColor
        }

        /* TOGGLE SWITCH */
        .kh-tg-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 4px 0
        }

        .kh-tg-info span {
            display: block;
            font-weight: 600;
            font-size: .875rem;
            color: #333;
            margin-bottom: 2px
        }

        .kh-tg-info small {
            color: #bbb;
            font-size: .77rem
        }

        .kh-sw {
            position: relative;
            cursor: pointer;
            flex-shrink: 0;
            display: inline-block
        }

        .kh-sw input {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0
        }

        .kh-sw-track {
            display: block;
            width: 44px;
            height: 25px;
            background: #dde0e8;
            border-radius: 13px;
            transition: background .25s;
            position: relative
        }

        .kh-sw input:checked~.kh-sw-track {
            background: #27ae60
        }

        .kh-sw-thumb {
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

        .kh-sw input:checked~.kh-sw-track .kh-sw-thumb {
            transform: translateX(19px)
        }

        /* SAVE / CANCEL */
        .kh-btn-save {
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

        .kh-btn-save:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(255, 140, 66, .45)
        }

        .kh-btn-cancel {
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

        .kh-btn-cancel:hover {
            background: #ffeee0;
            color: #FF8C42;
            border-color: #FF8C42
        }

        /* XÁC THỰC */
        .kh-xacthuc-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px
        }

        .kh-badge-ok {
            font-size: .72rem;
            font-weight: 700;
            color: #27ae60;
            background: #e8f8f0;
            border: 1px solid #b7e4cb;
            padding: .18rem .5rem;
            border-radius: 5px;
            display: flex;
            align-items: center;
            gap: 3px;
            white-space: nowrap
        }

        .kh-badge-no {
            font-size: .72rem;
            font-weight: 600;
            color: #bbb;
            background: #f5f5f5;
            padding: .18rem .5rem;
            border-radius: 5px;
            white-space: nowrap
        }

        /* SYSINFO */
        .kh-sysinfo {
            margin-bottom: 14px
        }

        .kh-sysrow {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 5px 0;
            border-bottom: 1px solid #fafafa
        }

        .kh-sysrow:last-child {
            border-bottom: none
        }

        .kh-syskey {
            font-size: .75rem;
            color: #bbb;
            display: flex;
            align-items: center;
            gap: 4px;
            white-space: nowrap
        }

        .kh-sysval {
            font-size: .78rem;
            font-weight: 600;
            color: #555;
            text-align: right
        }

        /* MINI STATS */
        .kh-mini-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
            border-top: 1px solid #f5f5f5;
            padding-top: 14px
        }

        .kh-ms {
            text-align: center
        }

        .kh-ms-num {
            font-size: 1.4rem;
            font-weight: 800;
            line-height: 1
        }

        .kh-ms-lbl {
            font-size: .7rem;
            color: #bbb;
            margin-top: 3px
        }
    </style>
@endpush

@push('scripts')
    <script>
        // ── Hiển thị/ẩn mật khẩu ──
        function togglePwd() {
            const inp = document.getElementById('kh_pwd');
            const icon = document.getElementById('pwdIcon');
            if (inp.type === 'password') {
                inp.type = 'text';
                icon.className = 'fas fa-eye-slash';
            } else {
                inp.type = 'password';
                icon.className = 'fas fa-eye';
            }
        }

        // ── Đếm ký tự ghi chú ──
        const ta = document.querySelector('textarea[name="ghi_chu_noi_bo"]');
        const charCount = document.getElementById('charCount');
        if (ta && charCount) {
            function updateCount() {
                charCount.textContent = ta.value.length;
                charCount.style.color = ta.value.length > 1800 ? '#e74c3c' : '#bbb';
            }
            ta.addEventListener('input', updateCount);
            updateCount();
        }

        // ── Mức độ tiềm năng radio + style ──
        const mucDoColors = {
            nong: {
                color: '#e74c3c',
                bg: '#fff0f0'
            },
            am: {
                color: '#e67e22',
                bg: '#fff8f0'
            },
            lanh: {
                color: '#95a5a6',
                bg: '#f5f5f5'
            },
        };

        document.querySelectorAll('.kh-mds-item').forEach(label => {
            label.addEventListener('click', function() {
                // Reset tất cả
                document.querySelectorAll('.kh-mds-item').forEach(l => {
                    l.classList.remove('active');
                    l.style.borderColor = '#e8e8e8';
                    l.style.background = '#fafafa';
                    l.style.color = '#aaa';
                });
                // Active cái được chọn
                const val = this.querySelector('input').value;
                const c = mucDoColors[val] || {
                    color: '#999',
                    bg: '#f5f5f5'
                };
                this.classList.add('active');
                this.style.borderColor = c.color;
                this.style.background = c.bg;
                this.style.color = c.color;
            });
        });
    </script>
@endpush
