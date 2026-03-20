@php
    $isEdit = isset($nhanVien) && $nhanVien !== null;
    $oldVaiTro = old('vai_tro', $isEdit ? $nhanVien->vai_tro : 'sale');
@endphp

<div class="nv-form-grid">

    {{-- ══════════ CỘT TRÁI ══════════ --}}
    <div class="nv-form-left">

        {{-- ① THÔNG TIN CÁ NHÂN --}}
        <div class="nv-fc">
            <div class="nv-fc-head"><i class="fas fa-user"></i> Thông tin cá nhân</div>
            <div class="nv-fc-body">

                <div class="nv-row2">
                    <div class="nv-fg">
                        <label class="nv-fl req">Họ và tên</label>
                        <input type="text" name="ho_ten" class="nv-fi @error('ho_ten') nv-fi-err @enderror"
                            value="{{ old('ho_ten', $isEdit ? $nhanVien->ho_ten : '') }}"
                            placeholder="VD: Nguyễn Văn An" autofocus>
                        @error('ho_ten')
                            <div class="nv-fe"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    <div class="nv-fg">
                        <label class="nv-fl">Số điện thoại</label>
                        <input type="text" name="so_dien_thoai"
                            class="nv-fi @error('so_dien_thoai') nv-fi-err @enderror"
                            value="{{ old('so_dien_thoai', $isEdit ? $nhanVien->so_dien_thoai : '') }}"
                            placeholder="VD: 0912345678">
                        @error('so_dien_thoai')
                            <div class="nv-fe"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="nv-row2">
                    <div class="nv-fg">
                        <label class="nv-fl req">Email đăng nhập</label>
                        <input type="email" name="email" class="nv-fi @error('email') nv-fi-err @enderror"
                            value="{{ old('email', $isEdit ? $nhanVien->email : '') }}"
                            placeholder="VD: nhanvien@company.com">
                        @error('email')
                            <div class="nv-fe"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    <div class="nv-fg">
                        <label class="nv-fl {{ $isEdit ? '' : 'req' }}">
                            Mật khẩu
                            @if ($isEdit)
                                <span class="nv-hint">để trống = không thay đổi</span>
                            @endif
                        </label>
                        <div class="nv-pw-wrap">
                            <input type="password" name="password" id="nvPwd"
                                class="nv-fi @error('password') nv-fi-err @enderror"
                                placeholder="{{ $isEdit ? '••••••••' : 'Tối thiểu 6 ký tự' }}"
                                autocomplete="new-password">
                            <button type="button" class="nv-pw-eye" onclick="toggleNvPwd()">
                                <i class="fas fa-eye" id="nvPwdIcon"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="nv-fe"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="nv-fg">
                    <label class="nv-fl">Địa chỉ</label>
                    <input type="text" name="dia_chi" class="nv-fi"
                        value="{{ old('dia_chi', $isEdit ? $nhanVien->dia_chi : '') }}"
                        placeholder="VD: 123 Nguyễn Trãi, Hà Nội">
                </div>

            </div>
        </div>

        {{-- ② VAI TRÒ & PHÂN QUYỀN --}}
        <div class="nv-fc">
            <div class="nv-fc-head"><i class="fas fa-shield-alt"></i> Vai trò & Phân quyền</div>
            <div class="nv-fc-body">

                <div class="nv-role-grid">
                    @foreach (\App\Models\NhanVien::VAI_TRO as $v => $info)
                        <label class="nv-role-card {{ $oldVaiTro == $v ? 'active' : '' }}"
                            style="{{ $oldVaiTro == $v ? 'border-color:' . $info['color'] . ';background:' . $info['bg'] : '' }}">
                            <input type="radio" name="vai_tro" value="{{ $v }}"
                                {{ $oldVaiTro == $v ? 'checked' : '' }} style="display:none">
                            <i class="{{ $info['icon'] }}"
                                style="font-size:1.6rem;color:{{ $info['color'] }};margin-bottom:6px"></i>
                            <div class="nv-role-name" style="color:{{ $oldVaiTro == $v ? $info['color'] : '#555' }}">
                                {{ $info['label'] }}
                            </div>
                            <div class="nv-role-desc">
                                @switch($v)
                                    @case('admin')
                                        Toàn quyền hệ thống
                                    @break

                                    @case('nguon_hang')
                                        Quản lý nguồn BĐS
                                    @break

                                    @case('sale')
                                        Kinh doanh, tư vấn
                                    @break
                                @endswitch
                            </div>
                            @if ($oldVaiTro == $v)
                                <div class="nv-role-check">
                                    <i class="fas fa-check-circle" style="color:{{ $info['color'] }}"></i>
                                </div>
                            @endif
                        </label>
                    @endforeach
                </div>

                @error('vai_tro')
                    <div class="nv-fe" style="margin-top:8px">
                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                    </div>
                @enderror

                {{-- Cảnh báo khi chọn admin --}}
                <div id="adminWarn"
                    style="display:{{ $oldVaiTro == 'admin' ? 'flex' : 'none' }};margin-top:14px;background:#fff8f0;border:1px solid #fde5c3;border-radius:8px;padding:10px 14px;gap:8px;font-size:.8rem;color:#e67e22">
                    <i class="fas fa-exclamation-triangle" style="margin-top:1px;flex-shrink:0"></i>
                    <span>Vai trò <strong>Admin</strong> có toàn quyền truy cập hệ thống. Chỉ cấp cho người tin
                        cậy!</span>
                </div>

            </div>
        </div>

    </div>{{-- end left --}}

    {{-- ══════════ CỘT PHẢI — SIDEBAR ══════════ --}}
    <div class="nv-form-right">

        {{-- NÚT LƯU --}}
        <div class="nv-fc">
            <div class="nv-fc-body" style="padding:16px">
                <button type="submit" class="nv-btn-save" form="nvForm">
                    <i class="fas fa-save"></i>
                    {{ $isEdit ? 'Lưu thay đổi' : 'Thêm nhân viên' }}
                </button>
                <a href="{{ route('nhanvien.admin.nhan-vien.index') }}" class="nv-btn-cancel">
                    <i class="fas fa-times"></i> Hủy bỏ
                </a>
            </div>
        </div>

        {{-- ẢNH ĐẠI DIỆN --}}
        <div class="nv-fc">
            <div class="nv-fc-head"><i class="fas fa-camera"></i> Ảnh đại diện</div>
            <div class="nv-fc-body">

                <div class="nv-avatar-preview-wrap">
                    <img id="avatarPreview"
                        src="{{ $isEdit ? $nhanVien->anh_dai_dien_url : 'https://ui-avatars.com/api/?name=NV&background=1a3c5e&color=fff&size=100&bold=true' }}"
                        alt="Avatar" class="nv-avatar-lg">
                    @if ($isEdit && $nhanVien->anh_dai_dien)
                        <div class="nv-avatar-current-lbl">Ảnh hiện tại</div>
                    @endif
                </div>

                <input type="file" id="nvAvatarInput" name="anh_dai_dien"
                    accept="image/jpeg,image/png,image/webp" style="display:none">

                <label for="nvAvatarInput" class="nv-upload-lbl">
                    <i class="fas fa-upload"></i>
                    {{ $isEdit && $nhanVien->anh_dai_dien ? 'Đổi ảnh' : 'Chọn ảnh' }}
                </label>
                <div id="avatarFname" class="nv-fname-hint"></div>
                <div class="nv-upload-hint">JPEG, PNG, WEBP • Tối đa 2MB</div>

                @error('anh_dai_dien')
                    <div class="nv-fe" style="margin-top:6px">
                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                    </div>
                @enderror

            </div>
        </div>

        {{-- TRẠNG THÁI --}}
        <div class="nv-fc">
            <div class="nv-fc-head"><i class="fas fa-toggle-on"></i> Trạng thái tài khoản</div>
            <div class="nv-fc-body">
                <div class="nv-tg-row">
                    <div class="nv-tg-info">
                        <span>Kích hoạt tài khoản</span>
                        <small>Cho phép nhân viên đăng nhập hệ thống</small>
                    </div>
                    <label class="nv-sw">
                        <input type="checkbox" name="kich_hoat" value="1"
                            {{ old('kich_hoat', $isEdit ? $nhanVien->kich_hoat : true) ? 'checked' : '' }}>
                        <span class="nv-sw-track"><span class="nv-sw-thumb"></span></span>
                    </label>
                </div>
            </div>
        </div>

        {{-- THÔNG TIN HỆ THỐNG --}}
        @if ($isEdit)
            <div class="nv-fc">
                <div class="nv-fc-head"><i class="fas fa-info-circle"></i> Thông tin hệ thống</div>
                <div class="nv-fc-body">
                    <div class="nv-sysinfo">

                        <div class="nv-sysrow">
                            <span class="nv-syskey"><i class="fas fa-hashtag"></i> ID</span>
                            <span class="nv-sysval"
                                style="font-family:monospace;background:#f0f4ff;color:#2d6a9f;padding:.1rem .4rem;border-radius:4px">
                                #{{ $nhanVien->id }}
                            </span>
                        </div>

                        <div class="nv-sysrow">
                            <span class="nv-syskey"><i class="fas fa-calendar-plus"></i> Tạo lúc</span>
                            <span class="nv-sysval">{{ $nhanVien->created_at->format('d/m/Y H:i') }}</span>
                        </div>

                        <div class="nv-sysrow">
                            <span class="nv-syskey"><i class="fas fa-sync"></i> Cập nhật</span>
                            <span class="nv-sysval">{{ $nhanVien->updated_at->format('d/m/Y H:i') }}</span>
                        </div>

                        @if ($nhanVien->dang_nhap_cuoi_at)
                            <div class="nv-sysrow">
                                <span class="nv-syskey"><i class="fas fa-sign-in-alt"></i> Đăng nhập cuối</span>
                                <span class="nv-sysval">{{ $nhanVien->dang_nhap_cuoi_at->format('d/m/Y H:i') }}</span>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        @endif

        {{-- NÚT LƯU dưới cùng --}}
        <div class="nv-fc">
            <div class="nv-fc-body" style="padding:16px">
                <button type="submit" class="nv-btn-save" form="nvForm">
                    <i class="fas fa-save"></i>
                    {{ $isEdit ? 'Lưu thay đổi' : 'Thêm nhân viên' }}
                </button>
            </div>
        </div>

    </div>{{-- end right --}}

</div>{{-- end nv-form-grid --}}

@push('styles')
    <style>
        /* BREADCRUMB & HEADER */
        .nv-form-hdr {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 12px
        }

        .nv-bc {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: .8rem;
            color: #aaa;
            margin-bottom: 6px
        }

        .nv-bc a {
            color: #aaa;
            text-decoration: none
        }

        .nv-bc a:hover {
            color: #FF8C42
        }

        .nv-bc .fa-chevron-right {
            font-size: .65rem
        }

        .nv-form-ttl {
            font-size: 1.3rem;
            font-weight: 700;
            color: #1a3c5e;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px
        }

        .nv-form-ttl i {
            color: #FF8C42
        }

        /* GRID */
        .nv-form-grid {
            display: grid;
            grid-template-columns: 1fr 290px;
            gap: 20px;
            align-items: start
        }

        @media(max-width:980px) {
            .nv-form-grid {
                grid-template-columns: 1fr
            }

            .nv-form-right {
                order: -1
            }
        }

        /* CARD */
        .nv-fc {
            background: #fff;
            border-radius: 14px;
            border: 1px solid #f0f2f5;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .05);
            margin-bottom: 18px;
            overflow: hidden
        }

        .nv-fc:last-child {
            margin-bottom: 0
        }

        .nv-fc-head {
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

        .nv-fc-head i {
            color: #FF8C42
        }

        .nv-hint {
            font-weight: 400;
            color: #bbb;
            font-size: .72rem;
            margin-left: 4px
        }

        .nv-fc-body {
            padding: 18px
        }

        /* FORM */
        .nv-row2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 14px
        }

        @media(max-width:600px) {
            .nv-row2 {
                grid-template-columns: 1fr
            }
        }

        .nv-fg {
            margin-bottom: 14px
        }

        .nv-fg:last-child {
            margin-bottom: 0
        }

        .nv-fl {
            display: block;
            font-weight: 700;
            font-size: .75rem;
            color: #666;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: .3px
        }

        .nv-fl.req::after {
            content: ' *';
            color: #e74c3c
        }

        .nv-fi {
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

        .nv-fi:focus {
            border-color: #FF8C42;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(255, 140, 66, .1)
        }

        .nv-fi.nv-fi-err {
            border-color: #e74c3c;
            background: #fff8f8
        }

        .nv-fe {
            font-size: .78rem;
            color: #e74c3c;
            margin-top: 4px;
            display: flex;
            align-items: center;
            gap: 4px
        }

        .nv-pw-wrap {
            position: relative
        }

        .nv-pw-wrap .nv-fi {
            padding-right: 42px
        }

        .nv-pw-eye {
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

        .nv-pw-eye:hover {
            color: #555
        }

        /* VAI TRÒ CARDS */
        .nv-role-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px
        }

        @media(max-width:500px) {
            .nv-role-grid {
                grid-template-columns: 1fr
            }
        }

        .nv-role-card {
            border: 2px solid #f0f2f5;
            border-radius: 12px;
            padding: 16px 10px;
            text-align: center;
            cursor: pointer;
            transition: all .2s;
            background: #fafafa;
            position: relative
        }

        .nv-role-card:hover {
            border-color: #dde5f5;
            background: #f5f8ff
        }

        .nv-role-card.active {
            box-shadow: 0 4px 16px rgba(0, 0, 0, .08)
        }

        .nv-role-name {
            font-weight: 700;
            font-size: .85rem;
            margin-bottom: 4px
        }

        .nv-role-desc {
            font-size: .72rem;
            color: #aaa;
            line-height: 1.4
        }

        .nv-role-check {
            position: absolute;
            top: 8px;
            right: 8px;
            font-size: .95rem
        }

        /* AVATAR UPLOAD */
        .nv-avatar-preview-wrap {
            text-align: center;
            margin-bottom: 14px
        }

        .nv-avatar-lg {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #f0f2f5;
            display: inline-block
        }

        .nv-avatar-current-lbl {
            font-size: .72rem;
            color: #aaa;
            margin-top: 4px
        }

        .nv-upload-lbl {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #f0f4ff;
            color: #1a3c5e;
            border: 1.5px solid #d5e0f5;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 600;
            font-size: .82rem;
            cursor: pointer;
            transition: all .2s;
            margin-bottom: 6px;
            width: 100%;
            justify-content: center
        }

        .nv-upload-lbl:hover {
            background: #1a3c5e;
            color: #fff;
            border-color: #1a3c5e
        }

        .nv-fname-hint {
            font-size: .75rem;
            color: #FF8C42;
            font-weight: 500;
            margin-bottom: 6px;
            text-align: center;
            min-height: 18px
        }

        .nv-upload-hint {
            font-size: .72rem;
            color: #bbb;
            text-align: center;
            line-height: 1.6
        }

        /* TOGGLE */
        .nv-tg-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px
        }

        .nv-tg-info span {
            display: block;
            font-weight: 600;
            font-size: .875rem;
            color: #333;
            margin-bottom: 2px
        }

        .nv-tg-info small {
            color: #bbb;
            font-size: .77rem
        }

        .nv-sw {
            position: relative;
            cursor: pointer;
            display: inline-block;
            flex-shrink: 0
        }

        .nv-sw input {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0
        }

        .nv-sw-track {
            display: block;
            width: 44px;
            height: 25px;
            background: #dde0e8;
            border-radius: 13px;
            transition: background .25s;
            position: relative
        }

        .nv-sw input:checked~.nv-sw-track {
            background: #27ae60
        }

        .nv-sw-thumb {
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

        .nv-sw input:checked~.nv-sw-track .nv-sw-thumb {
            transform: translateX(19px)
        }

        /* SAVE / CANCEL */
        .nv-btn-save {
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

        .nv-btn-save:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(255, 140, 66, .45)
        }

        .nv-btn-cancel {
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

        .nv-btn-cancel:hover {
            background: #ffeee0;
            color: #FF8C42;
            border-color: #FF8C42
        }

        /* SYSINFO */
        .nv-sysinfo {}

        .nv-sysrow {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 6px 0;
            border-bottom: 1px solid #fafafa
        }

        .nv-sysrow:last-child {
            border-bottom: none
        }

        .nv-syskey {
            font-size: .75rem;
            color: #bbb;
            display: flex;
            align-items: center;
            gap: 4px
        }

        .nv-sysval {
            font-size: .78rem;
            font-weight: 600;
            color: #555;
            text-align: right
        }
    </style>
@endpush

@push('scripts')
    <script>
        const roleColors = {
            admin: {
                color: '#e74c3c',
                bg: '#fff0f0'
            },
            nguon_hang: {
                color: '#8e44ad',
                bg: '#f5eeff'
            },
            sale: {
                color: '#2d6a9f',
                bg: '#e8f4fd'
            },
        };

        // ── Chọn vai trò ──
        document.querySelectorAll('.nv-role-card').forEach(card => {
            card.addEventListener('click', function() {
                const val = this.querySelector('input').value;
                const c = roleColors[val] || {
                    color: '#999',
                    bg: '#f5f5f5'
                };

                document.querySelectorAll('.nv-role-card').forEach(cl => {
                    cl.classList.remove('active');
                    cl.style.borderColor = '#f0f2f5';
                    cl.style.background = '#fafafa';
                    cl.querySelector('.nv-role-name').style.color = '#555';
                    const ck = cl.querySelector('.nv-role-check');
                    if (ck) ck.remove();
                });

                this.classList.add('active');
                this.style.borderColor = c.color;
                this.style.background = c.bg;
                this.querySelector('.nv-role-name').style.color = c.color;

                // Thêm check icon
                const chk = document.createElement('div');
                chk.className = 'nv-role-check';
                chk.innerHTML = '<i class="fas fa-check-circle" style="color:' + c.color + '"></i>';
                this.appendChild(chk);

                // Cảnh báo admin
                document.getElementById('adminWarn').style.display = val === 'admin' ? 'flex' : 'none';
            });
        });

        // ── Preview avatar ──
        const avatarInput = document.getElementById('nvAvatarInput');
        const avatarPreview = document.getElementById('avatarPreview');
        const avatarFname = document.getElementById('avatarFname');

        if (avatarInput) {
            avatarInput.addEventListener('change', function() {
                const file = this.files[0];
                if (!file) return;
                const reader = new FileReader();
                reader.onload = e => {
                    avatarPreview.src = e.target.result;
                };
                reader.readAsDataURL(file);
                avatarFname.textContent = file.name + ' (' + (file.size / 1024).toFixed(0) + ' KB)';
            });
        }

        // ── Toggle password ──
        function toggleNvPwd() {
            const inp = document.getElementById('nvPwd');
            const icon = document.getElementById('nvPwdIcon');
            inp.type = inp.type === 'password' ? 'text' : 'password';
            icon.className = inp.type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
        }
    </script>
@endpush
