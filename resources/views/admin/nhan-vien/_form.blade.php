{{-- resources/views/admin/nhan-vien/_form.blade.php --}}
{{-- $nhanVien : NhanVien|null --}}
{{-- $isEdit   : bool          --}}
@php
    $isEdit = $isEdit ?? isset($nhanVien);
    $old = fn(string $key, $default = '') => old($key, $isEdit ? $nhanVien->{$key} ?? $default : $default);
@endphp

<div class="row g-4 align-items-start">

    {{-- ══════════════════════════════════════
         CỘT TRÁI  (col-lg-8)
    ══════════════════════════════════════ --}}
    <div class="col-lg-8">

        {{-- ── THÔNG TIN CƠ BẢN ── --}}
        <div class="card mb-4">
            <div class="card-header">
                <span class="d-flex align-items-center gap-2">
                    <i class="fas fa-id-card"></i> Thông tin cơ bản
                </span>
                <span style="font-size:.71rem;font-weight:500;color:var(--text-muted)">
                    <span style="color:var(--red)">*</span> Bắt buộc
                </span>
            </div>
            <div class="card-body">

                {{-- Họ tên + SĐT --}}
                <div class="row g-3 mb-3">
                    <div class="col-sm-7">
                        <label class="form-label" for="f_ho_ten">
                            Họ và tên <span class="req">*</span>
                        </label>
                        <input type="text" id="f_ho_ten" name="ho_ten"
                            class="form-control @error('ho_ten') is-invalid @enderror" value="{{ $old('ho_ten') }}"
                            placeholder="Nguyễn Văn A" autocomplete="name" required>
                        @error('ho_ten')
                            <div class="form-error">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-sm-5">
                        <label class="form-label" for="f_sdt">Số điện thoại</label>
                        <input type="tel" id="f_sdt" name="so_dien_thoai"
                            class="form-control @error('so_dien_thoai') is-invalid @enderror"
                            value="{{ $old('so_dien_thoai') }}" placeholder="0912 345 678">
                        @error('so_dien_thoai')
                            <div class="form-error">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                {{-- Email --}}
                <div class="mb-3">
                    <label class="form-label" for="f_email">
                        Email đăng nhập <span class="req">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text" style="border-color:var(--border);background:var(--bg-alt)">
                            <i class="fas fa-envelope" style="color:var(--blue);font-size:.8rem"></i>
                        </span>
                        <input type="email" id="f_email" name="email"
                            class="form-control @error('email') is-invalid @enderror" value="{{ $old('email') }}"
                            placeholder="email@thanh-cong.vn" {{ $isEdit ? 'readonly' : '' }}
                            style="{{ $isEdit ? 'background:var(--bg-alt);cursor:default' : '' }}" required>
                    </div>
                    @if ($isEdit)
                        <div class="form-hint">
                            <i class="fas fa-lock" style="font-size:.65rem"></i>
                            Email không thể thay đổi sau khi tạo tài khoản
                        </div>
                    @endif
                    @error('email')
                        <div class="form-error">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Ngày sinh + Giới tính --}}
                <div class="row g-3 mb-3">
                    <div class="col-sm-6">
                        <label class="form-label" for="f_ngay_sinh">Ngày sinh</label>
                        <div class="input-group">
                            <span class="input-group-text" style="border-color:var(--border);background:var(--bg-alt)">
                                <i class="fas fa-calendar" style="color:var(--text-muted);font-size:.8rem"></i>
                            </span>
                            <input type="date" id="f_ngay_sinh" name="ngay_sinh"
                                class="form-control @error('ngay_sinh') is-invalid @enderror"
                                value="{{ old('ngay_sinh', $isEdit && $nhanVien->ngay_sinh ? $nhanVien->ngay_sinh->format('Y-m-d') : '') }}">
                        </div>
                        @error('ngay_sinh')
                            <div class="form-error">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label" for="f_gioi_tinh">Giới tính</label>
                        <select id="f_gioi_tinh" name="gioi_tinh"
                            class="form-select @error('gioi_tinh') is-invalid @enderror">
                            <option value="">-- Chọn giới tính --</option>
                            @foreach (['Nam' => 'fas fa-mars', 'Nữ' => 'fas fa-venus', 'Khác' => 'fas fa-genderless'] as $g => $icon)
                                <option value="{{ $g }}" @selected($old('gioi_tinh') == $g)>
                                    {{ $g }}
                                </option>
                            @endforeach
                        </select>
                        @error('gioi_tinh')
                            <div class="form-error">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                {{-- Địa chỉ --}}
                <div class="mb-3">
                    <label class="form-label" for="f_dia_chi">Địa chỉ</label>
                    <div class="input-group">
                        <span class="input-group-text" style="border-color:var(--border);background:var(--bg-alt)">
                            <i class="fas fa-map-marker-alt" style="color:var(--red);font-size:.8rem"></i>
                        </span>
                        <input type="text" id="f_dia_chi" name="dia_chi" class="form-control"
                            value="{{ $old('dia_chi') }}" placeholder="Số nhà, đường, quận, thành phố...">
                    </div>
                </div>

                {{-- Ghi chú --}}
                <div>
                    <label class="form-label" for="f_ghi_chu">Ghi chú nội bộ</label>
                    <textarea id="f_ghi_chu" name="ghi_chu" class="form-control" rows="3"
                        placeholder="Ghi chú về nhân viên này (chỉ admin thấy)..." style="resize:vertical">{{ $old('ghi_chu') }}</textarea>
                    <div class="form-hint">Không hiển thị với nhân viên</div>
                </div>

            </div>{{-- /card-body --}}
        </div>{{-- /card thông tin cơ bản --}}

        {{-- ── MẬT KHẨU ── --}}
        <div class="card mb-4">
            <div class="card-header">
                <span class="d-flex align-items-center gap-2">
                    <i class="fas fa-lock"></i>
                    @if ($isEdit)
                        Đổi mật khẩu
                    @else
                        Thiết lập mật khẩu
                    @endif
                </span>
                @if ($isEdit)
                    <span class="badge-status badge-pending" style="font-size:.68rem">
                        Để trống nếu không đổi
                    </span>
                @endif
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <label class="form-label" for="f_pw1">
                            {{ $isEdit ? 'Mật khẩu mới' : 'Mật khẩu' }}
                            @if (!$isEdit)
                                <span class="req">*</span>
                            @endif
                        </label>
                        <div class="pw-wrap position-relative">
                            <input type="password" id="f_pw1" name="mat_khau"
                                class="form-control @error('mat_khau') is-invalid @enderror"
                                placeholder="{{ $isEdit ? 'Để trống nếu không đổi' : 'Tối thiểu 6 ký tự' }}"
                                autocomplete="new-password">
                            <button type="button" class="pw-eye" onclick="togglePwEye('f_pw1','f_eye1')">
                                <i class="fas fa-eye" id="f_eye1"></i>
                            </button>
                        </div>
                        @error('mat_khau')
                            <div class="form-error">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                        <div class="form-hint">
                            <i class="fas fa-shield-alt" style="font-size:.65rem"></i>
                            Tối thiểu 6 ký tự, nên kết hợp chữ và số
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label" for="f_pw2">
                            Xác nhận mật khẩu
                            @if (!$isEdit)
                                <span class="req">*</span>
                            @endif
                        </label>
                        <div class="pw-wrap position-relative">
                            <input type="password" id="f_pw2" name="mat_khau_confirmation" class="form-control"
                                placeholder="Nhập lại mật khẩu" autocomplete="new-password">
                            <button type="button" class="pw-eye" onclick="togglePwEye('f_pw2','f_eye2')">
                                <i class="fas fa-eye" id="f_eye2"></i>
                            </button>
                        </div>
                        <div class="form-hint" id="f_pw_match_hint" style="display:none">
                            <i class="fas fa-check-circle" style="color:var(--green);font-size:.65rem"></i>
                            Mật khẩu khớp
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>{{-- /col-lg-8 --}}


    {{-- ══════════════════════════════════════
         CỘT PHẢI  (col-lg-4)
    ══════════════════════════════════════ --}}
    <div class="col-lg-4">

        {{-- ── ẢNH ĐẠI DIỆN ── --}}
        <div class="card mb-4">
            <div class="card-header">
                <span class="d-flex align-items-center gap-2">
                    <i class="fas fa-camera"></i> Ảnh đại diện
                </span>
            </div>
            <div class="card-body text-center">
                {{-- Preview --}}
                <div style="position:relative;display:inline-block;margin-bottom:1rem">
                    <img id="fAvatarPreview"
                        src="{{ $isEdit && $nhanVien->anh_dai_dien
                            ? asset('storage/' . $nhanVien->anh_dai_dien)
                            : asset('images/default-avatar.png') }}"
                        class="avatar avatar-xl mx-auto" style="width:96px;height:96px;border-width:3px"
                        onerror="this.src='{{ asset('images/default-avatar.png') }}'">
                    <label for="f_anh_dai_dien"
                        style="position:absolute;bottom:-2px;right:-2px;width:28px;height:28px;background:var(--primary);border-radius:50%;display:flex;align-items:center;justify-content:center;cursor:pointer;border:2px solid #fff;box-shadow:0 2px 8px rgba(0,0,0,.2)">
                        <i class="fas fa-camera" style="font-size:.65rem;color:#fff"></i>
                    </label>
                </div>

                <input type="file" id="f_anh_dai_dien" name="anh_dai_dien" class="d-none"
                    accept="image/jpeg,image/png,image/webp" onchange="previewImage(this,'fAvatarPreview')">

                <div>
                    <label for="f_anh_dai_dien" class="avatar-upload-label d-inline-flex">
                        <i class="fas fa-upload"></i> Chọn ảnh
                    </label>
                </div>
                <div class="form-hint mt-2">
                    JPG, PNG, WebP &nbsp;·&nbsp; Tối đa 2MB<br>
                    Khuyến nghị: hình vuông ≥ 200×200px
                </div>
                @error('anh_dai_dien')
                    <div class="form-error justify-content-center mt-2">
                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        {{-- ── VAI TRÒ ── --}}
        <div class="card mb-4">
            <div class="card-header">
                <span class="d-flex align-items-center gap-2">
                    <i class="fas fa-user-shield"></i> Vai trò <span class="req ms-1">*</span>
                </span>
            </div>
            <div class="card-body">
                <div class="role-card-grid" id="fRoleGrid" style="grid-template-columns:1fr">
                    @php
                        $selectedVaiTro = \App\Models\NhanVien::normalizeVaiTro(
                            old('vai_tro', $isEdit ? $nhanVien->vai_tro : ''),
                        );
                    @endphp
                    @foreach (\App\Models\NhanVien::VAI_TRO as $key => $info)
                        @php
                            $isSelected = $selectedVaiTro == $key;
                        @endphp
                        <label class="role-card {{ $isSelected ? 'selected' : '' }}"
                            onclick="selectRoleCard('{{ $key }}', this)">
                            <input type="radio" name="vai_tro" value="{{ $key }}"
                                {{ $isSelected ? 'checked' : '' }}>
                            <div class="role-card-icon"
                                style="background:{{ $info['bg'] }};color:{{ $info['color'] }}">
                                <i class="{{ $info['icon'] }}"></i>
                            </div>
                            <div>
                                <div class="role-card-name">{{ $info['label'] }}</div>
                                <div class="role-card-desc">
                                    @switch($key)
                                        @case('admin')
                                            Toàn quyền hệ thống
                                        @break

                                        @case('sale')
                                            Quản lý BĐS & khách hàng
                                        @break

                                        @case('nguon_hang')
                                            Quản lý & nhập nguồn hàng
                                        @break

                                        @default
                                            {{ $key }}
                                    @endswitch
                                </div>
                            </div>
                            @if ($isSelected)
                                <i class="fas fa-check-circle ms-auto"
                                    style="color:var(--primary);font-size:.85rem;flex-shrink:0"></i>
                            @endif
                        </label>
                    @endforeach
                </div>
                @error('vai_tro')
                    <div class="form-error mt-2">
                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        {{-- ── TRẠNG THÁI ── --}}
        <div class="card mb-4">
            <div class="card-header">
                <span class="d-flex align-items-center gap-2">
                    <i class="fas fa-toggle-on"></i> Trạng thái tài khoản
                </span>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between gap-3">
                    <div>
                        <div style="font-weight:700;font-size:.87rem;color:var(--navy)">
                            Kích hoạt tài khoản
                        </div>
                        <div class="form-hint">
                            Tài khoản có thể đăng nhập vào hệ thống
                        </div>
                    </div>
                    <label class="toggle-sw flex-shrink-0">
                        <input type="checkbox" id="f_kich_hoat" name="kich_hoat" value="1"
                            {{ old('kich_hoat', $isEdit ? $nhanVien->kich_hoat : true) ? 'checked' : '' }}
                            onchange="updateToggleLabel(this, document.getElementById('f_kh_label'))">
                        <span class="toggle-sw-track">
                            <span class="toggle-sw-thumb"></span>
                        </span>
                    </label>
                </div>
                <div class="mt-2 pt-2" style="border-top:1px solid var(--border)">
                    <span id="f_kh_label"
                        class="toggle-label {{ old('kich_hoat', $isEdit ? $nhanVien->kich_hoat : true) ? 'on' : 'off' }}">
                        {{ old('kich_hoat', $isEdit ? $nhanVien->kich_hoat : true) ? 'Đang hoạt động' : 'Vô hiệu hóa' }}
                    </span>
                </div>
            </div>
        </div>

        {{-- ── NÚT SUBMIT ── --}}
        <div class="card">
            <div class="card-body d-flex flex-column gap-2">
                <button type="submit" id="fSubmitBtn"
                    class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2"
                    style="height:44px;font-size:.9rem">
                    <i class="fas {{ $isEdit ? 'fa-save' : 'fa-user-plus' }}"></i>
                    {{ $isEdit ? 'Lưu thay đổi' : 'Tạo nhân viên' }}
                </button>
                <a href="{{ route('nhanvien.admin.nhan-vien.index') }}"
                    class="btn btn-secondary w-100 d-flex align-items-center justify-content-center gap-2">
                    <i class="fas fa-times"></i> Hủy bỏ
                </a>

                @if ($isEdit)
                    <div style="border-top:1px dashed var(--border);padding-top:.75rem;margin-top:.25rem">
                        {{-- Xóa nhanh --}}
                        @if (auth('nhanvien')->id() !== $nhanVien->id)
                            <form method="POST" action="{{ route('nhanvien.admin.nhan-vien.destroy', $nhanVien) }}"
                                id="fDelForm" style="display:none">
                                @csrf @method('DELETE')
                            </form>
                            <button type="button"
                                class="btn btn-danger w-100 d-flex align-items-center justify-content-center gap-2"
                                style="font-size:.8rem" data-confirm-delete="{{ addslashes($nhanVien->ho_ten) }}"
                                data-form-id="fDelForm">
                                <i class="fas fa-trash"></i> Xóa nhân viên này
                            </button>
                        @else
                            <div class="d-flex align-items-center gap-2 justify-content-center"
                                style="font-size:.75rem;color:var(--text-muted);padding:.35rem 0">
                                <i class="fas fa-info-circle"></i>
                                Không thể xóa tài khoản của chính bạn
                            </div>
                        @endif
                    </div>
                @endif

            </div>
        </div>

        {{-- Thông tin meta (chỉ khi edit) --}}
        @if ($isEdit)
            <div class="card mt-3" style="background:var(--bg-alt);border-style:dashed">
                <div class="card-body" style="padding:.9rem 1rem">
                    <div
                        style="font-size:.7rem;font-weight:800;color:var(--text-muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:.6rem">
                        Thông tin hệ thống
                    </div>
                    <div class="d-flex flex-column gap-2">
                        <div class="d-flex align-items-center justify-content-between" style="font-size:.76rem">
                            <span style="color:var(--text-sub)">
                                <i class="fas fa-hashtag me-1" style="width:13px;text-align:center"></i>ID
                            </span>
                            <strong style="color:var(--navy)">#{{ $nhanVien->id }}</strong>
                        </div>
                        <div class="d-flex align-items-center justify-content-between" style="font-size:.76rem">
                            <span style="color:var(--text-sub)">
                                <i class="fas fa-calendar-plus me-1" style="width:13px;text-align:center"></i>Tạo lúc
                            </span>
                            <strong style="color:var(--navy)">
                                {{ $nhanVien->created_at->format('d/m/Y H:i') }}
                            </strong>
                        </div>
                        <div class="d-flex align-items-center justify-content-between" style="font-size:.76rem">
                            <span style="color:var(--text-sub)">
                                <i class="fas fa-edit me-1" style="width:13px;text-align:center"></i>Cập nhật
                            </span>
                            <strong style="color:var(--navy)">
                                {{ $nhanVien->updated_at->format('d/m/Y H:i') }}
                            </strong>
                        </div>
                        @if ($nhanVien->dang_nhap_cuoi_at)
                            <div class="d-flex align-items-center justify-content-between" style="font-size:.76rem">
                                <span style="color:var(--text-sub)">
                                    <i class="fas fa-sign-in-alt me-1" style="width:13px;text-align:center"></i>Đăng
                                    nhập cuối
                                </span>
                                <strong style="color:var(--green)">
                                    {{ $nhanVien->dang_nhap_cuoi_at->diffForHumans() }}
                                </strong>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

    </div>{{-- /col-lg-4 --}}
</div>{{-- /row --}}


@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            /* ── Khởi tạo toggle label ── */
            updateToggleLabel(
                document.getElementById('f_kich_hoat'),
                document.getElementById('f_kh_label')
            );

            /* ── Check mật khẩu khớp real-time ── */
            const pw1 = document.getElementById('f_pw1');
            const pw2 = document.getElementById('f_pw2');
            const hint = document.getElementById('f_pw_match_hint');

            function checkPwMatch() {
                if (!pw1 || !pw2 || !hint) return;
                const v1 = pw1.value,
                    v2 = pw2.value;
                if (v2.length === 0) {
                    hint.style.display = 'none';
                    pw2.classList.remove('is-invalid');
                    return;
                }
                if (v1 === v2) {
                    hint.style.display = 'flex';
                    pw2.classList.remove('is-invalid');
                    pw2.style.borderColor = 'var(--green)';
                } else {
                    hint.style.display = 'none';
                    pw2.style.borderColor = 'var(--red)';
                }
            }
            pw1?.addEventListener('input', checkPwMatch);
            pw2?.addEventListener('input', checkPwMatch);

            /* ── Disable nút submit khi đang gửi ── */
            const form = document.getElementById('nvCreateForm') || document.getElementById('nvEditForm');
            form?.addEventListener('submit', function() {
                const btn = document.getElementById('fSubmitBtn');
                btnLoading(btn, '{{ $isEdit ? 'Đang lưu...' : 'Đang tạo...' }}');
            });

            /* ── Check icon selected khi chọn role ── */
            document.querySelectorAll('#fRoleGrid .role-card').forEach(card => {
                card.addEventListener('click', function() {
                    document.querySelectorAll('#fRoleGrid .role-card .fa-check-circle').forEach(i =>
                        i.remove());
                    const icon = document.createElement('i');
                    icon.className = 'fas fa-check-circle ms-auto';
                    icon.style.cssText = 'color:var(--primary);font-size:.85rem;flex-shrink:0';
                    this.appendChild(icon);
                });
            });

        });
    </script>
@endpush
