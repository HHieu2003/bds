@php
    $isEdit = isset($nhanVien) && $nhanVien !== null;
    $action = $isEdit ? route('nhanvien.admin.nhan-vien.update', $nhanVien) : route('nhanvien.admin.nhan-vien.store');
    $method = $isEdit ? 'PUT' : 'POST';
    $vatRos = \App\Models\NhanVien::VAI_TRO;
@endphp

@push('styles')
    <style>
        /* ── Form styles ── */
        .nv-form-card {
            background: #fff;
            border-radius: 14px;
            border: 1px solid #eef0f5;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .06);
            overflow: hidden;
            margin-bottom: 1rem;
        }

        .nv-form-section-head {
            padding: .85rem 1.25rem;
            background: linear-gradient(to right, #f8faff, #fff);
            border-bottom: 1px solid #eef0f5;
            display: flex;
            align-items: center;
            gap: .5rem;
            font-weight: 800;
            font-size: .84rem;
            color: #1a3c5e;
        }

        .nv-form-section-head i {
            color: #FF8C42;
        }

        .nv-form-body {
            padding: 1.25rem;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: .85rem 1.1rem;
        }

        .nv-form-body.cols-1 {
            grid-template-columns: 1fr;
        }

        @media(max-width:600px) {
            .nv-form-body {
                grid-template-columns: 1fr !important;
            }
        }

        .col-span-2 {
            grid-column: span 2;
        }

        @media(max-width:600px) {
            .col-span-2 {
                grid-column: span 1;
            }
        }

        .nv-fg {
            margin: 0;
        }

        .nv-fl {
            display: block;
            font-weight: 700;
            font-size: .74rem;
            color: #555;
            margin-bottom: .4rem;
            text-transform: uppercase;
            letter-spacing: .4px;
        }

        .nv-fl .req {
            color: #e74c3c;
        }

        .nv-fi {
            width: 100%;
            height: 42px;
            border: 1.5px solid #e8eaef;
            border-radius: 9px;
            padding: 0 .9rem;
            font-size: .875rem;
            color: #1a3c5e;
            background: #fafbfc;
            outline: none;
            font-family: inherit;
            transition: border-color .2s, box-shadow .2s;
        }

        .nv-fi:focus {
            border-color: #FF8C42;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(255, 140, 66, .1);
        }

        .nv-fi.is-invalid {
            border-color: #e74c3c;
            box-shadow: 0 0 0 3px rgba(231, 76, 60, .08);
        }

        .nv-fi-hint {
            font-size: .73rem;
            color: #aaa;
            margin-top: .3rem;
            display: block;
        }

        .nv-err {
            font-size: .75rem;
            color: #e74c3c;
            margin-top: .3rem;
            display: flex;
            align-items: center;
            gap: .3rem;
        }

        select.nv-fi {
            appearance: none;
            cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath fill='%23aaa' d='M5 6L0 0h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right .9rem center;
            background-color: #fafbfc;
            padding-right: 2.2rem;
        }

        .nv-pw-wrap {
            position: relative;
        }

        .nv-pw-wrap .nv-fi {
            padding-right: 2.8rem;
        }

        .nv-pw-eye {
            position: absolute;
            right: .75rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #bbb;
            font-size: .82rem;
            padding: 0;
            transition: color .15s;
        }

        .nv-pw-eye:hover {
            color: #555;
        }

        /* Avatar upload */
        .nv-ava-upload {
            display: flex;
            align-items: center;
            gap: 1.1rem;
            flex-wrap: wrap;
        }

        .nv-ava-preview {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #eef0f5;
            background: #f4f6fb;
            flex-shrink: 0;
        }

        .nv-ava-upload-box {
            flex: 1;
            min-width: 180px;
        }

        .nv-file-label {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            height: 38px;
            padding: 0 1rem;
            border: 1.5px dashed #d0d8e8;
            border-radius: 8px;
            cursor: pointer;
            font-size: .82rem;
            color: #888;
            background: #fafbfc;
            transition: all .2s;
        }

        .nv-file-label:hover {
            border-color: #FF8C42;
            color: #FF8C42;
            background: #fff5ef;
        }

        #avatarFile {
            display: none;
        }

        /* Role cards */
        .nv-role-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: .65rem;
        }

        .nv-role-card {
            border: 2px solid #eef0f5;
            border-radius: 10px;
            padding: .85rem 1rem;
            cursor: pointer;
            transition: all .2s;
            background: #fff;
            display: flex;
            align-items: center;
            gap: .7rem;
        }

        .nv-role-card:hover {
            border-color: #FF8C42;
            background: #fff9f5;
        }

        .nv-role-card.selected {
            border-color: #FF8C42 !important;
            background: #fff5ef !important;
            box-shadow: 0 0 0 3px rgba(255, 140, 66, .12);
        }

        .nv-role-card input {
            display: none;
        }

        .nv-role-icon {
            width: 36px;
            height: 36px;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .9rem;
            flex-shrink: 0;
        }

        .nv-role-name {
            font-weight: 700;
            font-size: .82rem;
            color: #1a3c5e;
        }

        .nv-role-desc {
            font-size: .71rem;
            color: #aaa;
            margin-top: .1rem;
        }

        /* Form actions */
        .nv-form-actions {
            display: flex;
            gap: .75rem;
            justify-content: flex-end;
            padding: 1rem 1.25rem;
            background: #fafbfc;
            border-top: 1px solid #eef0f5;
            border-radius: 0 0 14px 14px;
        }

        .nv-btn-submit {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            height: 42px;
            padding: 0 1.5rem;
            background: linear-gradient(135deg, #FF8C42, #e8721e);
            color: #fff;
            border: none;
            border-radius: 9px;
            font-weight: 800;
            font-size: .875rem;
            cursor: pointer;
            font-family: inherit;
            box-shadow: 0 4px 14px rgba(255, 140, 66, .3);
            transition: all .2s;
        }

        .nv-btn-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(255, 140, 66, .4);
        }

        .nv-btn-back {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            height: 42px;
            padding: 0 1.1rem;
            background: #f5f5f7;
            color: #555;
            border: 1.5px solid #e8e8e8;
            border-radius: 9px;
            font-weight: 600;
            font-size: .84rem;
            text-decoration: none;
            transition: all .2s;
        }

        .nv-btn-back:hover {
            background: #eee;
            color: #1a3c5e;
        }
    </style>
@endpush

<form action="{{ $action }}" method="POST" enctype="multipart/form-data" id="nvFormMain">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    {{-- ── SECTION 1: Thông tin cơ bản ── --}}
    <div class="nv-form-card">
        <div class="nv-form-section-head">
            <i class="fas fa-user-circle"></i> Thông tin cơ bản
        </div>
        <div class="nv-form-body">

            {{-- Avatar --}}
            <div class="nv-fg col-span-2">
                <label class="nv-fl">Ảnh đại diện</label>
                <div class="nv-ava-upload">
                    <img id="avatarPreview"
                        src="{{ $isEdit && $nhanVien->anh_dai_dien_url ? $nhanVien->anh_dai_dien_url : asset('images/default-avatar.png') }}"
                        alt="Preview" class="nv-ava-preview"
                        onerror="this.src='{{ asset('images/default-avatar.png') }}'">
                    <div class="nv-ava-upload-box">
                        <label class="nv-file-label" for="avatarFile">
                            <i class="fas fa-camera"></i>
                            {{ $isEdit ? 'Đổi ảnh' : 'Chọn ảnh' }}
                        </label>
                        <input type="file" id="avatarFile" name="anh_dai_dien"
                            accept="image/jpeg,image/png,image/webp" onchange="previewAvatar(this)">
                        <span class="nv-fi-hint">JPG, PNG, WebP — tối đa 2MB</span>
                        @error('anh_dai_dien')
                            <span class="nv-err"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Họ tên --}}
            <div class="nv-fg col-span-2">
                <label class="nv-fl">Họ và tên <span class="req">*</span></label>
                <input type="text" name="ho_ten" class="nv-fi @error('ho_ten') is-invalid @enderror"
                    value="{{ old('ho_ten', $isEdit ? $nhanVien->ho_ten : '') }}" placeholder="Nguyễn Văn A" required>
                @error('ho_ten')
                    <span class="nv-err"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
                @enderror
            </div>

            {{-- Email --}}
            <div class="nv-fg">
                <label class="nv-fl">Email đăng nhập <span class="req">*</span></label>
                <input type="email" name="email" class="nv-fi @error('email') is-invalid @enderror"
                    value="{{ old('email', $isEdit ? $nhanVien->email : '') }}" placeholder="nhanvien@example.com"
                    required {{ $isEdit ? 'readonly' : '' }}>
                @if ($isEdit)
                    <span class="nv-fi-hint">Email đăng nhập không thể thay đổi</span>
                @endif
                @error('email')
                    <span class="nv-err"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
                @enderror
            </div>

            {{-- SĐT --}}
            <div class="nv-fg">
                <label class="nv-fl">Số điện thoại</label>
                <input type="tel" name="so_dien_thoai" class="nv-fi @error('so_dien_thoai') is-invalid @enderror"
                    value="{{ old('so_dien_thoai', $isEdit ? $nhanVien->so_dien_thoai : '') }}"
                    placeholder="0912 345 678">
                @error('so_dien_thoai')
                    <span class="nv-err"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
                @enderror
            </div>

        </div>
    </div>

    {{-- ── SECTION 2: Mật khẩu ── --}}
    <div class="nv-form-card">
        <div class="nv-form-section-head">
            <i class="fas fa-lock"></i>
            {{ $isEdit ? 'Đổi mật khẩu (để trống nếu không đổi)' : 'Thiết lập mật khẩu' }}
        </div>
        <div class="nv-form-body">

            <div class="nv-fg">
                <label class="nv-fl">
                    Mật khẩu @if (!$isEdit)
                        <span class="req">*</span>
                    @endif
                </label>
                <div class="nv-pw-wrap">
                    <input type="password" name="mat_khau" id="fPw1"
                        class="nv-fi @error('mat_khau') is-invalid @enderror"
                        placeholder="{{ $isEdit ? 'Để trống nếu không đổi' : 'Tối thiểu 6 ký tự' }}"
                        autocomplete="new-password" {{ !$isEdit ? 'required' : '' }}>
                    <button type="button" class="nv-pw-eye" onclick="fTogglePw('fPw1','fEye1')">
                        <i class="fas fa-eye" id="fEye1"></i>
                    </button>
                </div>
                @error('mat_khau')
                    <span class="nv-err"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
                @enderror
            </div>

            <div class="nv-fg">
                <label class="nv-fl">Xác nhận mật khẩu @if (!$isEdit)
                        <span class="req">*</span>
                    @endif
                </label>
                <div class="nv-pw-wrap">
                    <input type="password" name="mat_khau_confirmation" id="fPw2" class="nv-fi"
                        placeholder="Nhập lại mật khẩu" autocomplete="new-password" {{ !$isEdit ? 'required' : '' }}>
                    <button type="button" class="nv-pw-eye" onclick="fTogglePw('fPw2','fEye2')">
                        <i class="fas fa-eye" id="fEye2"></i>
                    </button>
                </div>
            </div>

        </div>
    </div>

    {{-- ── SECTION 3: Vai trò & Trạng thái ── --}}
    <div class="nv-form-card">
        <div class="nv-form-section-head">
            <i class="fas fa-shield-alt"></i> Vai trò & Quyền hạn
        </div>
        <div class="nv-form-body cols-1">

            <div class="nv-fg">
                <label class="nv-fl">Vai trò <span class="req">*</span></label>
                <div class="nv-role-grid" id="roleGrid">
                    @foreach ($vatRos as $key => $info)
                        @php $isSelected = old('vai_tro', $isEdit ? $nhanVien->vai_tro : '') === $key; @endphp
                        <label class="nv-role-card {{ $isSelected ? 'selected' : '' }}"
                            onclick="selectRole('{{ $key }}', this)">
                            <input type="radio" name="vai_tro" value="{{ $key }}"
                                {{ $isSelected ? 'checked' : '' }}>
                            <div class="nv-role-icon"
                                style="background:{{ $info['bg'] }};color:{{ $info['color'] }}">
                                <i class="{{ $info['icon'] }}"></i>
                            </div>
                            <div>
                                <div class="nv-role-name">{{ $info['label'] }}</div>
                                <div class="nv-role-desc">
                                    @switch($key)
                                        @case('admin')
                                            Toàn quyền hệ thống
                                        @break

                                        @case('sale')
                                            Quản lý BĐS & KH
                                        @break

                                        @case('nguon_hang')
                                            Quản lý nguồn hàng
                                        @break

                                        @default
                                            {{ $key }}
                                    @endswitch
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>
                @error('vai_tro')
                    <span class="nv-err" style="margin-top:.4rem">
                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                    </span>
                @enderror
            </div>

            {{-- Kích hoạt --}}
            <div class="nv-fg">
                <label class="nv-fl">Trạng thái tài khoản</label>
                <div style="display:flex;align-items:center;gap:.85rem;margin-top:.25rem">
                    <label class="nv-sw" style="cursor:pointer">
                        <input type="hidden" name="kich_hoat" value="0">
                        <input type="checkbox" name="kich_hoat" value="1" id="kichHoatToggle"
                            {{ old('kich_hoat', $isEdit ? $nhanVien->kich_hoat : true) ? 'checked' : '' }}>
                        <span class="nv-sw-track"><span class="nv-sw-thumb"></span></span>
                    </label>
                    <span id="kichHoatLabel" style="font-size:.84rem;font-weight:600;color:#16a34a">
                        Đang hoạt động
                    </span>
                </div>
            </div>

        </div>

        {{-- Actions --}}
        <div class="nv-form-actions">
            <a href="{{ route('nhanvien.admin.nhan-vien.index') }}" class="nv-btn-back">
                <i class="fas fa-times"></i> Hủy
            </a>
            <button type="submit" class="nv-btn-submit">
                <i class="fas fa-{{ $isEdit ? 'save' : 'user-plus' }}"></i>
                {{ $isEdit ? 'Lưu thay đổi' : 'Tạo nhân viên' }}
            </button>
        </div>
    </div>

</form>

@push('scripts')
    <script>
        /* ── Preview avatar ── */
        function previewAvatar(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => document.getElementById('avatarPreview').src = e.target.result;
                reader.readAsDataURL(input.files[0]);
            }
        }

        /* ── Toggle password visibility ── */
        function fTogglePw(id, eid) {
            const inp = document.getElementById(id);
            const ico = document.getElementById(eid);
            inp.type = inp.type === 'password' ? 'text' : 'password';
            ico.className = inp.type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
        }

        /* ── Select vai trò ── */
        function selectRole(val, el) {
            document.querySelectorAll('.nv-role-card').forEach(c => c.classList.remove('selected'));
            el.classList.add('selected');
            el.querySelector('input[type=radio]').checked = true;
        }

        /* ── Kích hoạt toggle label ── */
        const kh = document.getElementById('kichHoatToggle');
        const khLbl = document.getElementById('kichHoatLabel');
        if (kh && khLbl) {
            function updateKhLabel() {
                if (kh.checked) {
                    khLbl.textContent = 'Đang hoạt động';
                    khLbl.style.color = '#16a34a';
                } else {
                    khLbl.textContent = 'Vô hiệu hóa';
                    khLbl.style.color = '#e74c3c';
                }
            }
            kh.addEventListener('change', updateKhLabel);
            updateKhLabel();
        }
    </script>
@endpush
