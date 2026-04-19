@php
    $nhanVien  = Auth::guard('nhanvien')->user();
    $avatarPath = $nhanVien->anh_dai_dien ?? ($nhanVien->avatar ?? null);
    $hasAvatar  = $avatarPath && \Illuminate\Support\Facades\Storage::disk('public')->exists($avatarPath);
@endphp

<header id="topbar">

    {{-- Toggle Sidebar --}}
    <button class="btn-toggle-sidebar" onclick="toggleSidebar()" title="Thu/Mở menu">
        <i class="fas fa-bars"></i>
    </button>

    {{-- Tiêu đề trang --}}
    <div style="display:flex;align-items:center;gap:.5rem;flex:1;min-width:0;overflow:hidden;">
        <a href="{{ route('nhanvien.dashboard') }}"
            style="display:flex;align-items:center;justify-content:center;
                  width:28px;height:28px;border-radius:6px;background:#f3f4f6;
                  color:var(--text-sub);flex-shrink:0;transition:all .2s;"
            onmouseover="this.style.background='#fff5ef';this.style.color='var(--primary)'"
            onmouseout="this.style.background='#f3f4f6';this.style.color='var(--text-sub)'" title="Dashboard">
            <i class="fas fa-home" style="font-size:.75rem"></i>
        </a>
        <i class="fas fa-chevron-right" style="font-size:.6rem;color:var(--text-sub);flex-shrink:0"></i>
        <span
            style="font-size:.88rem;font-weight:700;color:var(--navy);
                     overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
            @yield('page_title', 'Dashboard')
        </span>
    </div>

    {{-- Right --}}
    <div class="topbar-right">

        {{-- Xem website --}}
        <a href="{{ route('frontend.home') }}" target="_blank" class="topbar-icon-btn" title="Xem trang chủ">
            <i class="fas fa-external-link-alt" style="font-size:.8rem"></i>
        </a>

        {{-- Thông báo --}}
        <div style="position:relative;" id="notifWrap">
            <button class="topbar-icon-btn" id="notifBtn" title="Thông báo" type="button"
                    onclick="toggleNotifDropdown()" style="position:relative;">
                <i class="fas fa-bell" style="font-size:.85rem"></i>
                <span id="notifBadge"
                      style="display:none;position:absolute;top:-4px;right:-4px;
                             min-width:18px;height:18px;border-radius:9px;
                             background:#e74c3c;color:#fff;font-size:.58rem;
                             font-weight:800;align-items:center;
                             justify-content:center;padding:0 4px;border:2px solid #fff;"></span>
            </button>

            {{-- Dropdown Panel --}}
            <div id="notifDropdown"
                 style="display:none;position:absolute;top:calc(100% + 10px);right:0;
                        width:360px;max-width:calc(100vw - 20px);
                        background:#fff;border-radius:14px;
                        border:1px solid var(--border);
                        box-shadow:0 16px 48px rgba(0,0,0,.14);
                        z-index:1500;overflow:hidden;">

                {{-- Header --}}
                <div style="padding:13px 16px 10px;border-bottom:1px solid var(--border);
                            display:flex;align-items:center;justify-content:space-between;">
                    <div style="font-size:.88rem;font-weight:800;color:var(--navy);display:flex;align-items:center;gap:7px;">
                        <i class="fas fa-bell" style="color:var(--primary)"></i>
                        Thông báo
                        <span id="notifCountLabel"
                              style="display:none;background:#e74c3c;color:#fff;font-size:.6rem;
                                     font-weight:800;border-radius:20px;padding:1px 7px;"></span>
                    </div>
                    <button id="notifMarkAll" type="button" onclick="notifMarkAllRead()"
                            style="display:none;font-size:.72rem;color:var(--primary);background:none;border:none;
                                   cursor:pointer;font-weight:600;padding:0;">
                        <i class="fas fa-check-double"></i> Đánh dấu đã đọc
                    </button>
                </div>

                {{-- Body --}}
                <div id="notifList"
                     style="max-height:400px;overflow-y:auto;
                            scrollbar-width:thin;scrollbar-color:#e0e3eb transparent;">
                    <div id="notifSpinner"
                         style="padding:32px;text-align:center;color:var(--text-sub);">
                        <i class="fas fa-circle-notch fa-spin" style="font-size:1.3rem;color:var(--primary);"></i>
                    </div>
                </div>

                {{-- Footer --}}
                <div style="padding:9px 16px;border-top:1px solid var(--border);
                            display:flex;justify-content:flex-end;background:#fafbfc;">
                    <a href="{{ route('nhanvien.admin.thong-bao.danh-sach') }}"
                       style="font-size:.73rem;color:var(--primary);font-weight:600;text-decoration:none;">
                        Xem tất cả <i class="fas fa-arrow-right" style="font-size:.65rem"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- Divider --}}
        <div style="width:1px;height:22px;background:var(--border);flex-shrink:0;"></div>

        {{-- User Dropdown --}}
        <div style="position:relative;">

            <button class="topbar-user" id="topbarUserBtn" onclick="toggleUserDropdown()" type="button">
                <div class="topbar-user-avatar">
                    @if ($hasAvatar)
                        <img src="{{ asset('storage/' . $avatarPath) }}" alt="{{ $nhanVien->ho_ten }}"
                            style="width:100%;height:100%;object-fit:cover;">
                    @else
                        {{ mb_strtoupper(mb_substr($nhanVien->ho_ten, 0, 1)) }}
                    @endif
                </div>
                <div style="display:flex;flex-direction:column;align-items:flex-start;
                            overflow:hidden;max-width:130px;"
                    class="d-none d-md-flex">
                    <span class="topbar-user-name" id="topbarUserName">{{ $nhanVien->ho_ten }}</span>
                    <span style="font-size:.67rem;color:var(--text-sub);white-space:nowrap;">
                        {{ $nhanVien->vai_tro_label }}
                    </span>
                </div>
                <i class="fas fa-chevron-down" id="topbarChevron"
                    style="font-size:.62rem;color:var(--text-sub);
                          transition:transform .2s;flex-shrink:0;"></i>
            </button>

            {{-- DROPDOWN --}}
            <div class="user-dropdown" id="userDropdown">

                <div class="user-dropdown-header">
                    <div class="uname" id="topbarDropdownName">{{ $nhanVien->ho_ten }}</div>
                    <div class="urole">{{ $nhanVien->vai_tro_label }}</div>
                    @if ($nhanVien->email)
                        <div id="topbarDropdownEmail"
                            style="font-size:.7rem;color:var(--text-sub);
                                    margin-top:.2rem;overflow:hidden;
                                    text-overflow:ellipsis;white-space:nowrap;">
                            {{ $nhanVien->email }}
                        </div>
                    @endif
                </div>

                <a href="#" id="topbarProfileBtn">
                    <i class="fas fa-user-circle"></i> Hồ sơ cá nhân
                </a>
                <a href="#" id="topbarChangePasswordBtn">
                    <i class="fas fa-key"></i> Đổi mật khẩu
                </a>
                <a href="{{ route('frontend.home') }}" target="_blank">
                    <i class="fas fa-globe"></i> Xem website
                </a>

                <div style="height:1px;background:var(--border);margin:.3rem 0;"></div>

                <form action="{{ route('nhanvien.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i> Đăng xuất
                    </button>
                </form>

            </div>
        </div>

    </div>
</header>

<div id="topbarProfileModal"
    style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:2000;align-items:center;justify-content:center;padding:16px;">
    <div
        style="width:min(520px,100%);background:#fff;border-radius:14px;border:1px solid var(--border);box-shadow:0 20px 60px rgba(0,0,0,.22);overflow:hidden;">
        <div
            style="padding:14px 16px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;gap:10px;">
            <div style="font-size:.92rem;font-weight:800;color:var(--navy);display:flex;align-items:center;gap:8px;">
                <i class="fas fa-user-circle" style="color:var(--primary)"></i> Hồ sơ cá nhân
            </div>
            <button type="button" id="closeTopbarProfileModal"
                style="width:28px;height:28px;border-radius:8px;border:1px solid var(--border);background:#f9fafb;color:var(--text-sub);cursor:pointer;">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form id="topbarProfileForm" style="padding:16px;display:grid;gap:10px;">
            <div>
                <label for="tb_ho_ten"
                    style="font-size:.76rem;font-weight:700;color:var(--navy);display:block;margin-bottom:4px;">Họ và
                    tên</label>
                <input id="tb_ho_ten" name="ho_ten" type="text" class="form-control" maxlength="100" required
                    value="{{ $nhanVien->ho_ten }}">
            </div>

            <div class="row g-2">
                <div class="col-12 col-md-6">
                    <label for="tb_email"
                        style="font-size:.76rem;font-weight:700;color:var(--navy);display:block;margin-bottom:4px;">Email</label>
                    <input id="tb_email" name="email" type="email" class="form-control" maxlength="150" required
                        value="{{ $nhanVien->email }}">
                </div>
                <div class="col-12 col-md-6">
                    <label for="tb_so_dien_thoai"
                        style="font-size:.76rem;font-weight:700;color:var(--navy);display:block;margin-bottom:4px;">Số
                        điện thoại</label>
                    <input id="tb_so_dien_thoai" name="so_dien_thoai" type="text" class="form-control"
                        maxlength="20" value="{{ $nhanVien->so_dien_thoai }}">
                </div>
            </div>

            <div>
                <label for="tb_dia_chi"
                    style="font-size:.76rem;font-weight:700;color:var(--navy);display:block;margin-bottom:4px;">Địa
                    chỉ</label>
                <input id="tb_dia_chi" name="dia_chi" type="text" class="form-control" maxlength="255"
                    value="{{ $nhanVien->dia_chi }}">
            </div>

            <div>
                <label style="font-size:.76rem;font-weight:700;color:var(--navy);display:block;margin-bottom:4px;">Vai
                    trò</label>
                <input type="text" class="form-control" value="{{ $nhanVien->vai_tro_label }}" readonly>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:8px;margin-top:4px;">
                <button type="button" id="cancelTopbarProfile" class="btn btn-secondary btn-sm">Hủy</button>
                <button type="submit" id="submitTopbarProfile" class="btn btn-primary btn-sm">
                    <i class="fas fa-save"></i> Lưu hồ sơ
                </button>
            </div>
        </form>
    </div>
</div>

<div id="topbarChangePasswordModal"
    style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:2000;align-items:center;justify-content:center;padding:16px;">
    <div
        style="width:min(440px,100%);background:#fff;border-radius:14px;border:1px solid var(--border);box-shadow:0 20px 60px rgba(0,0,0,.22);overflow:hidden;">
        <div
            style="padding:14px 16px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;gap:10px;">
            <div style="font-size:.92rem;font-weight:800;color:var(--navy);display:flex;align-items:center;gap:8px;">
                <i class="fas fa-key" style="color:var(--primary)"></i> Đổi mật khẩu
            </div>
            <button type="button" id="closeTopbarChangePasswordModal"
                style="width:28px;height:28px;border-radius:8px;border:1px solid var(--border);background:#f9fafb;color:var(--text-sub);cursor:pointer;">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form id="topbarChangePasswordForm" style="padding:16px;display:grid;gap:10px;">
            <div>
                <label for="tb_mat_khau_cu"
                    style="font-size:.76rem;font-weight:700;color:var(--navy);display:block;margin-bottom:4px;">
                    Mật khẩu hiện tại
                </label>
                <input id="tb_mat_khau_cu" name="mat_khau_cu" type="password" autocomplete="current-password"
                    class="form-control" required>
            </div>

            <div>
                <label for="tb_mat_khau_moi"
                    style="font-size:.76rem;font-weight:700;color:var(--navy);display:block;margin-bottom:4px;">
                    Mật khẩu mới
                </label>
                <input id="tb_mat_khau_moi" name="mat_khau_moi" type="password" autocomplete="new-password"
                    class="form-control" required minlength="6" maxlength="50">
            </div>

            <div>
                <label for="tb_mat_khau_moi_confirmation"
                    style="font-size:.76rem;font-weight:700;color:var(--navy);display:block;margin-bottom:4px;">
                    Xác nhận mật khẩu mới
                </label>
                <input id="tb_mat_khau_moi_confirmation" name="mat_khau_moi_confirmation" type="password"
                    autocomplete="new-password" class="form-control" required minlength="6" maxlength="50">
            </div>

            <div style="display:flex;justify-content:flex-end;gap:8px;margin-top:4px;">
                <button type="button" id="cancelTopbarChangePassword" class="btn btn-secondary btn-sm">Hủy</button>
                <button type="submit" id="submitTopbarChangePassword" class="btn btn-primary btn-sm">
                    <i class="fas fa-save"></i> Cập nhật
                </button>
            </div>
        </form>
    </div>
</div>
<script>
    // Topbar helpers
    (function() {
        var csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
        var dd = document.getElementById('userDropdown');
        var chevron = document.getElementById('topbarChevron');
        var topbarUserName = document.getElementById('topbarUserName');
        var topbarDropdownName = document.getElementById('topbarDropdownName');
        var topbarDropdownEmail = document.getElementById('topbarDropdownEmail');

        var profileInitial = {
            ho_ten: @json((string) ($nhanVien->ho_ten ?? '')),
            email: @json((string) ($nhanVien->email ?? '')),
            so_dien_thoai: @json((string) ($nhanVien->so_dien_thoai ?? '')),
            dia_chi: @json((string) ($nhanVien->dia_chi ?? '')),
        };

        function syncChevron() {
            if (chevron) {
                chevron.style.transform = dd && dd.classList.contains('show') ? 'rotate(180deg)' : '';
            }
        }

        // Chevron animation khi mở dropdown
        window.toggleUserDropdown = function() {
            if (!dd) return;
            dd.classList.toggle('show');
            syncChevron();
        };

        var modal = document.getElementById('topbarChangePasswordModal');
        var openBtn = document.getElementById('topbarChangePasswordBtn');
        var closeBtn = document.getElementById('closeTopbarChangePasswordModal');
        var cancelBtn = document.getElementById('cancelTopbarChangePassword');
        var form = document.getElementById('topbarChangePasswordForm');
        var submitBtn = document.getElementById('submitTopbarChangePassword');

        var profileModal = document.getElementById('topbarProfileModal');
        var openProfileBtn = document.getElementById('topbarProfileBtn');
        var closeProfileBtn = document.getElementById('closeTopbarProfileModal');
        var cancelProfileBtn = document.getElementById('cancelTopbarProfile');
        var profileForm = document.getElementById('topbarProfileForm');
        var profileSubmitBtn = document.getElementById('submitTopbarProfile');

        function normalizeText(v) {
            return String(v || '').trim();
        }

        function openModal() {
            if (!modal) return;
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            form?.reset();
            setTimeout(() => document.getElementById('tb_mat_khau_cu')?.focus(), 0);
        }

        function closeModal() {
            if (!modal) return;
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }

        function openProfileModal() {
            if (!profileModal) return;
            profileModal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            setTimeout(() => document.getElementById('tb_ho_ten')?.focus(), 0);
        }

        function closeProfileModal() {
            if (!profileModal) return;
            profileModal.style.display = 'none';
            document.body.style.overflow = '';
        }

        openProfileBtn?.addEventListener('click', function(e) {
            e.preventDefault();
            dd?.classList.remove('show');
            syncChevron();
            openProfileModal();
        });

        closeProfileBtn?.addEventListener('click', closeProfileModal);
        cancelProfileBtn?.addEventListener('click', closeProfileModal);
        profileModal?.addEventListener('click', function(e) {
            if (e.target === profileModal) closeProfileModal();
        });

        openBtn?.addEventListener('click', function(e) {
            e.preventDefault();
            dd?.classList.remove('show');
            syncChevron();
            openModal();
        });

        closeBtn?.addEventListener('click', closeModal);
        cancelBtn?.addEventListener('click', closeModal);
        modal?.addEventListener('click', function(e) {
            if (e.target === modal) closeModal();
        });

        document.addEventListener('keydown', function(e) {
            if (e.key !== 'Escape') return;
            if (profileModal?.style.display === 'flex') {
                closeProfileModal();
                return;
            }
            if (modal?.style.display === 'flex') {
                closeModal();
            }
        });

        profileForm?.addEventListener('submit', function(e) {
            e.preventDefault();

            var fd = new FormData(profileForm);
            var payload = {
                ho_ten: normalizeText(fd.get('ho_ten')),
                email: normalizeText(fd.get('email')),
                so_dien_thoai: normalizeText(fd.get('so_dien_thoai')),
                dia_chi: normalizeText(fd.get('dia_chi')),
            };

            // Tối ưu hiệu năng: không gọi API nếu người dùng chưa thay đổi dữ liệu.
            if (payload.ho_ten === profileInitial.ho_ten && payload.email === profileInitial.email &&
                payload.so_dien_thoai === profileInitial.so_dien_thoai && payload.dia_chi === profileInitial
                .dia_chi) {
                if (typeof showAdminToast === 'function') {
                    showAdminToast('Bạn chưa thay đổi thông tin nào.', 'info');
                }
                closeProfileModal();
                return;
            }

            if (typeof btnLoading === 'function') {
                btnLoading(profileSubmitBtn, 'Đang lưu...');
            } else if (profileSubmitBtn) {
                profileSubmitBtn.disabled = true;
            }

            fetch('{{ route('nhanvien.update-profile') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(payload),
                })
                .then(async (r) => {
                    let data = {};
                    try {
                        data = await r.json();
                    } catch (_) {}
                    return {
                        ok: r.ok,
                        data,
                    };
                })
                .then((res) => {
                    if (res.ok && (res.data.success || res.data.ok)) {
                        var nv = res.data?.nhan_vien || {};
                        var hoTenMoi = normalizeText(nv.ho_ten || payload.ho_ten);
                        var emailMoi = normalizeText(nv.email || payload.email);

                        profileInitial.ho_ten = hoTenMoi;
                        profileInitial.email = emailMoi;
                        profileInitial.so_dien_thoai = normalizeText(nv.so_dien_thoai || payload
                            .so_dien_thoai);
                        profileInitial.dia_chi = normalizeText(nv.dia_chi || payload.dia_chi);

                        if (topbarUserName) topbarUserName.textContent = hoTenMoi;
                        if (topbarDropdownName) topbarDropdownName.textContent = hoTenMoi;
                        if (topbarDropdownEmail) topbarDropdownEmail.textContent = emailMoi;

                        if (typeof showAdminToast === 'function') {
                            showAdminToast(res.data.message || 'Cập nhật hồ sơ thành công!', 'success');
                        }
                        closeProfileModal();
                        return;
                    }

                    var firstError = null;
                    if (res.data?.errors) {
                        for (const key in res.data.errors) {
                            if (Array.isArray(res.data.errors[key]) && res.data.errors[key].length) {
                                firstError = res.data.errors[key][0];
                                break;
                            }
                        }
                    }

                    if (typeof showAdminToast === 'function') {
                        showAdminToast(firstError || res.data?.message || 'Không thể cập nhật hồ sơ.',
                            'error');
                    }
                })
                .catch(() => {
                    if (typeof showAdminToast === 'function') {
                        showAdminToast('Lỗi kết nối, vui lòng thử lại.', 'error');
                    }
                })
                .finally(() => {
                    if (typeof btnRestore === 'function') {
                        btnRestore(profileSubmitBtn);
                    } else if (profileSubmitBtn) {
                        profileSubmitBtn.disabled = false;
                    }
                });
        });

        form?.addEventListener('submit', function(e) {
            e.preventDefault();

            var fd = new FormData(form);
            var payload = {
                mat_khau_cu: String(fd.get('mat_khau_cu') || ''),
                mat_khau_moi: String(fd.get('mat_khau_moi') || ''),
                mat_khau_moi_confirmation: String(fd.get('mat_khau_moi_confirmation') || ''),
            };

            if (payload.mat_khau_moi !== payload.mat_khau_moi_confirmation) {
                if (typeof showAdminToast === 'function') {
                    showAdminToast('Xác nhận mật khẩu mới không khớp.', 'warning');
                }
                return;
            }

            if (typeof btnLoading === 'function') {
                btnLoading(submitBtn, 'Đang cập nhật...');
            } else if (submitBtn) {
                submitBtn.disabled = true;
            }

            fetch('{{ route('nhanvien.change-password') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(payload),
                })
                .then(async (r) => {
                    let data = {};
                    try {
                        data = await r.json();
                    } catch (_) {}
                    return {
                        ok: r.ok,
                        status: r.status,
                        data,
                    };
                })
                .then((res) => {
                    if (res.ok && (res.data.success || res.data.ok)) {
                        if (typeof showAdminToast === 'function') {
                            showAdminToast(res.data.message || res.data.msg ||
                                'Đổi mật khẩu thành công!', 'success');
                        }
                        closeModal();
                        form.reset();
                        return;
                    }

                    var firstError = null;
                    if (res.data?.errors) {
                        for (const key in res.data.errors) {
                            if (Array.isArray(res.data.errors[key]) && res.data.errors[key].length) {
                                firstError = res.data.errors[key][0];
                                break;
                            }
                        }
                    }

                    if (typeof showAdminToast === 'function') {
                        showAdminToast(firstError || res.data?.message || res.data?.msg ||
                            'Không thể đổi mật khẩu.', 'error');
                    }
                })
                .catch(() => {
                    if (typeof showAdminToast === 'function') {
                        showAdminToast('Lỗi kết nối, vui lòng thử lại.', 'error');
                    }
                })
                .finally(() => {
                    if (typeof btnRestore === 'function') {
                        btnRestore(submitBtn);
                    } else if (submitBtn) {
                        submitBtn.disabled = false;
                    }
                });
        });
    })();
</script>

<style>
@keyframes notifSlideIn {
    from { opacity: 0; transform: translateY(-8px); }
    to   { opacity: 1; transform: translateY(0); }
}
#notifDropdown.show {
    animation: notifSlideIn .18s ease;
}
.notif-item {
    display: flex;
    align-items: flex-start;
    gap: 11px;
    padding: 11px 16px;
    border-bottom: 1px solid #f0f1f5;
    cursor: pointer;
    text-decoration: none;
    color: inherit;
    transition: background .15s;
}
.notif-item:last-child { border-bottom: none; }
.notif-item:hover { background: #f8f9ff; }
.notif-item.unread { background: #fffbf5; }
.notif-item.unread:hover { background: #fff5e6; }
.notif-icon {
    width: 36px; height: 36px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: .85rem;
    flex-shrink: 0;
}
.notif-body { flex: 1; min-width: 0; }
.notif-title {
    font-size: .78rem;
    font-weight: 700;
    color: var(--navy);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.notif-text {
    font-size: .72rem;
    color: var(--text-sub);
    margin-top: 2px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.notif-time {
    font-size: .66rem;
    color: #aab;
    margin-top: 3px;
}
.notif-unread-dot {
    width: 7px; height: 7px;
    border-radius: 50%;
    background: var(--primary);
    flex-shrink: 0;
    margin-top: 5px;
}
.notif-empty {
    padding: 32px 16px;
    text-align: center;
    color: var(--text-sub);
    font-size: .82rem;
}
.notif-empty i { font-size: 2rem; margin-bottom: 8px; display: block; opacity: .35; }
</style>

<script>
(function () {
    var csrf   = document.querySelector('meta[name="csrf-token"]')?.content || '';
    var apiUrl = '{{ route('nhanvien.admin.thong-bao.api') }}';
    var markUrl= '{{ route('nhanvien.admin.thong-bao.mark-all-read') }}';

    var wrap    = document.getElementById('notifWrap');
    var btn     = document.getElementById('notifBtn');
    var dd      = document.getElementById('notifDropdown');
    var list    = document.getElementById('notifList');
    var spinner = document.getElementById('notifSpinner');
    var badge   = document.getElementById('notifBadge');
    var countLbl= document.getElementById('notifCountLabel');
    var markBtn = document.getElementById('notifMarkAll');

    var isOpen   = false;
    var isLoaded = false;
    var POLL_MS  = 45000;
    var pollTimer= null;

    // ── TOGGLE ──────────────────────────────────────────────────
    window.toggleNotifDropdown = function () {
        isOpen ? closeNotif() : openNotif();
    };

    function openNotif() {
        isOpen = true;
        dd.style.display = 'block';
        dd.classList.add('show');
        // Close user dropdown if open
        var udd = document.getElementById('userDropdown');
        if (udd) udd.classList.remove('show');
        if (!isLoaded) fetchNotifs();
    }

    function closeNotif() {
        isOpen = false;
        dd.style.display = 'none';
        dd.classList.remove('show');
    }

    // Close on outside click
    document.addEventListener('click', function (e) {
        if (isOpen && wrap && !wrap.contains(e.target)) closeNotif();
    });

    // ── FETCH ────────────────────────────────────────────────────
    function fetchNotifs() {
        fetch(apiUrl, {
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf }
        })
        .then(r => r.ok ? r.json() : null)
        .then(data => {
            if (!data || !data.ok) return;
            isLoaded = true;
            renderNotifs(data.items || []);
        })
        .catch(() => {});
    }

    // Auto-poll
    function startPoll() {
        pollTimer = setInterval(function () {
            fetchNotifs();
        }, POLL_MS);
    }

    // ── RENDER ───────────────────────────────────────────────────
    var loaiLabel = {
        ky_gui:      'Ký gửi',
        lich_hen:    'Lịch hẹn',
        yeu_cau:     'Liên hệ',
        chat:        'Chat',
        yeu_cau_lien_he: 'Liên hệ',
        ky_gui_moi:  'Ký gửi',
        lich_hen_moi:'Lịch hẹn',
        tin_nhan_chat:'Chat',
    };

    function renderNotifs(items) {
        var unreadCount = items.filter(i => !i.da_doc).length;

        // Badge
        if (unreadCount > 0) {
            badge.textContent = unreadCount > 99 ? '99+' : unreadCount;
            badge.style.display = 'flex';
            countLbl.textContent = unreadCount;
            countLbl.style.display = 'inline';
            markBtn.style.display = 'inline-flex';
        } else {
            badge.style.display = 'none';
            countLbl.style.display = 'none';
            markBtn.style.display = 'none';
        }

        // Remove old spinner / content
        list.innerHTML = '';

        if (!items.length) {
            list.innerHTML =
                '<div class="notif-empty">' +
                '<i class="fas fa-bell-slash"></i>' +
                'Không có thông báo mới' +
                '</div>';
            return;
        }

        // Group by loai for section headers
        var groups = {};
        items.forEach(function (it) {
            var g = it.loai || 'khac';
            if (!groups[g]) groups[g] = [];
            groups[g].push(it);
        });

        // Render all (sorted by time — already sorted from server)
        items.forEach(function (it) {
            var el = document.createElement('a');
            el.className = 'notif-item' + (it.da_doc ? '' : ' unread');
            el.href = it.lien_ket || '#';

            el.innerHTML =
                '<div class="notif-icon" style="background:' + (it.bg || '#f0f1f5') + ';color:' + (it.color || '#666') + '">' +
                    '<i class="' + (it.icon || 'fas fa-bell') + '"></i>' +
                '</div>' +
                '<div class="notif-body">' +
                    '<div class="notif-title">' + escHtml(it.tieu_de || '') + '</div>' +
                    '<div class="notif-text">' + escHtml(it.noi_dung || '') + '</div>' +
                    '<div class="notif-time"><i class="far fa-clock" style="margin-right:3px"></i>' + escHtml(it.thoi_gian_relative || '') + '</div>' +
                '</div>' +
                (it.da_doc ? '' : '<div class="notif-unread-dot"></div>');

            list.appendChild(el);
        });
    }

    // ── MARK ALL READ ────────────────────────────────────────────
    window.notifMarkAllRead = function () {
        fetch(markUrl, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'Content-Type': 'application/json',
            }
        })
        .then(r => r.json())
        .then(() => {
            // Remove unread visual
            list.querySelectorAll('.notif-item.unread').forEach(function (el) {
                el.classList.remove('unread');
                var dot = el.querySelector('.notif-unread-dot');
                if (dot) dot.remove();
            });
            badge.style.display = 'none';
            countLbl.style.display = 'none';
            markBtn.style.display = 'none';
        })
        .catch(() => {});
    };

    // ── UTILS ────────────────────────────────────────────────────
    function escHtml(str) {
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    // Init: fetch once on page load for badge, then poll
    document.addEventListener('DOMContentLoaded', function () {
        // Initial silent fetch (badge only — don't open dropdown)
        fetch(apiUrl, { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf } })
            .then(r => r.ok ? r.json() : null)
            .then(data => {
                if (!data || !data.ok) return;
                var unread = (data.items || []).filter(i => !i.da_doc).length;
                if (unread > 0) {
                    badge.textContent = unread > 99 ? '99+' : unread;
                    badge.style.display = 'flex';
                }
            })
            .catch(() => {});

        startPoll();
    });
})();
</script>
