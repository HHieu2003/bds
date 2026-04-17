{{-- resources/views/admin/nhan-vien/index.blade.php --}}
@extends('admin.layouts.master')
@section('title', 'Quản lý nhân viên')

@section('content')

    @php
        $exportQuery = array_merge(request()->except(['page', 'export']), ['export' => 'csv']);
    @endphp

    {{-- ═══════════════════════════════════════════════
     PAGE HEADER
═══════════════════════════════════════════════ --}}
    <div class="d-flex align-items-start justify-content-between gap-3 mb-4">
        <div>
            <h1 class="fw-black mb-1" style="font-size:1.35rem;color:var(--navy)">
                <i class="fas fa-users me-2" style="color:var(--primary)"></i>Quản lý nhân viên
            </h1>
            <div class="d-flex align-items-center gap-2" style="font-size:.8rem;color:var(--text-sub)">
                <span>{{ $thongKe['tong'] }} nhân viên</span>
                <span
                    style="width:4px;height:4px;background:var(--text-muted);border-radius:50%;display:inline-block"></span>
                <span>{{ $thongKe['kich_hoat'] }} đang hoạt động</span>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2 flex-shrink-0">
            <a href="{{ route('nhanvien.admin.nhan-vien.index', $exportQuery) }}"
                class="btn btn-success d-flex align-items-center gap-2" title="Xuất báo cáo theo bộ lọc hiện tại">
                <i class="fas fa-file-export"></i>
                <span class="d-none d-sm-inline">Xuất báo cáo</span>
            </a>
            <button class="btn btn-primary d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#nvModal"
                onclick="openModalAdd()">
                <i class="fas fa-user-plus"></i>
                <span class="d-none d-sm-inline">Thêm nhân viên</span>
            </button>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════
     STAT CARDS
═══════════════════════════════════════════════ --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg">
            <div class="stat-card">
                <div class="stat-icon navy"><i class="fas fa-users"></i></div>
                <div>
                    <div class="stat-num">{{ $thongKe['tong'] }}</div>
                    <div class="stat-label">Tổng nhân viên</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg">
            <div class="stat-card">
                <div class="stat-icon orange"><i class="fas fa-crown"></i></div>
                <div>
                    <div class="stat-num">{{ $thongKe['admin'] }}</div>
                    <div class="stat-label">Admin</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg">
            <div class="stat-card">
                <div class="stat-icon teal"><i class="fas fa-boxes"></i></div>
                <div>
                    <div class="stat-num">{{ $thongKe['nguon_hang'] }}</div>
                    <div class="stat-label">Nguồn hàng</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg">
            <div class="stat-card">
                <div class="stat-icon green"><i class="fas fa-handshake"></i></div>
                <div>
                    <div class="stat-num">{{ $thongKe['sale'] }}</div>
                    <div class="stat-label">Sale</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg">
            <div class="stat-card">
                <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
                <div>
                    <div class="stat-num">{{ $thongKe['kich_hoat'] }}</div>
                    <div class="stat-label">Đang hoạt động</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════
     FILTER BOX
═══════════════════════════════════════════════ --}}
    <div class="filter-box mb-3">
        <form method="GET" action="{{ route('nhanvien.admin.nhan-vien.index') }}" id="filterForm">
            <div class="filter-box-row">

                <input type="text" name="tukhoa" class="filter-ctrl filter-ctrl-search search-debounce"
                    value="{{ request('tukhoa') }}" placeholder="Tìm tên, email, SĐT..." style="min-width:200px;flex:1">

                <select name="vai_tro" class="filter-ctrl filter-auto-submit">
                    <option value="">Tất cả vai trò</option>
                    @foreach (\App\Models\NhanVien::VAI_TRO as $key => $info)
                        <option value="{{ $key }}" {{ request('vai_tro') == $key ? 'selected' : '' }}>
                            {{ $info['label'] }}
                        </option>
                    @endforeach
                </select>

                <select name="kich_hoat" class="filter-ctrl filter-auto-submit">
                    <option value="">Tất cả trạng thái</option>
                    <option value="1" {{ request('kich_hoat') === '1' ? 'selected' : '' }}>Đang hoạt động</option>
                    <option value="0" {{ request('kich_hoat') === '0' ? 'selected' : '' }}>Vô hiệu hóa</option>
                </select>

                <select name="sapxep" class="filter-ctrl filter-auto-submit">
                    <option value="moi_nhat" {{ request('sapxep', 'moi_nhat') == 'moi_nhat' ? 'selected' : '' }}>Mới nhất
                    </option>
                    <option value="ten_az" {{ request('sapxep') == 'ten_az' ? 'selected' : '' }}>Tên A–Z</option>
                    <option value="ten_za" {{ request('sapxep') == 'ten_za' ? 'selected' : '' }}>Tên Z–A</option>
                    <option value="dang_nhap"{{ request('sapxep') == 'dang_nhap' ? 'selected' : '' }}>Đăng nhập gần nhất
                    </option>
                </select>

                @if (request()->hasAny(['tukhoa', 'vai_tro', 'kich_hoat', 'sapxep']))
                    <a href="{{ route('nhanvien.admin.nhan-vien.index') }}"
                        class="btn btn-secondary btn-sm d-flex align-items-center gap-1">
                        <i class="fas fa-times"></i> Xóa lọc
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- ═══════════════════════════════════════════════
     TABLE CARD
═══════════════════════════════════════════════ --}}
    <div class="card">
        {{-- Card header --}}
        <div class="card-header">
            <span class="d-flex align-items-center gap-2">
                <i class="fas fa-list"></i> Danh sách nhân viên
            </span>
            @if ($nhanViens->total())
                <span class="result-info">
                    Hiển thị <strong>{{ $nhanViens->firstItem() }}–{{ $nhanViens->lastItem() }}</strong>
                    / <strong>{{ $nhanViens->total() }}</strong>
                </span>
            @endif
        </div>

        {{-- Desktop table --}}
        <div class="table-wrap tbl-desktop">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Nhân viên</th>
                        <th>Liên hệ</th>
                        <th>Vai trò</th>
                        <th>Trạng thái</th>
                        <th>Đăng nhập cuối</th>
                        <th style="width:120px;text-align:center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($nhanViens as $nv)
                        @php $isMe = $nv->id === auth('nhanvien')->id(); @endphp
                        <tr>

                            {{-- Nhân viên --}}
                            <td>
                                <div class="person-cell">
                                    <div style="position:relative;flex-shrink:0">
                                        <img src="{{ $nv->anh_dai_dien_url }}" alt="{{ $nv->ho_ten }}"
                                            class="avatar avatar-md"
                                            onerror="this.src='{{ asset('images/default-avatar.png') }}'">
                                        <span class="status-dot {{ $nv->kich_hoat ? 'on' : 'off' }}"></span>
                                    </div>
                                    <div class="person-info">
                                        <div class="person-name">
                                            {{ $nv->ho_ten }}
                                            @if ($isMe)
                                                <span class="me-badge">Bạn</span>
                                            @endif
                                        </div>
                                        <div class="person-sub">#{{ $nv->id }}</div>
                                    </div>
                                </div>
                            </td>

                            {{-- Liên hệ --}}
                            <td>
                                <div class="contact-row">
                                    <i class="fas fa-envelope" style="color:var(--blue)"></i>
                                    <a href="mailto:{{ $nv->email }}">{{ $nv->email }}</a>
                                </div>
                                @if ($nv->so_dien_thoai)
                                    <div class="contact-row">
                                        <i class="fas fa-phone" style="color:var(--green)"></i>
                                        <span>{{ $nv->so_dien_thoai }}</span>
                                    </div>
                                @endif
                            </td>

                            {{-- Vai trò --}}
                            <td>
                                @php $vt = $nv->vai_tro_info; @endphp
                                <span class="role-badge"
                                    style="background:{{ $vt['bg'] }};color:{{ $vt['color'] }}">
                                    <i class="{{ $vt['icon'] }}"></i> {{ $vt['label'] }}
                                </span>
                            </td>

                            {{-- Trạng thái --}}
                            <td>
                                @if (!$isMe)
                                    <label class="toggle-sw"
                                        data-tip="{{ $nv->kich_hoat ? 'Click để vô hiệu hóa' : 'Click để kích hoạt' }}">
                                        <input type="checkbox" class="nv-toggle" {{ $nv->kich_hoat ? 'checked' : '' }}
                                            data-toggle-url="{{ route('nhanvien.admin.nhan-vien.toggle', $nv) }}">
                                        <span class="toggle-sw-track">
                                            <span class="toggle-sw-thumb"></span>
                                        </span>
                                    </label>
                                @else
                                    <span class="toggle-label on">
                                        <i class="fas fa-circle" style="font-size:.5rem;vertical-align:middle"></i>
                                        Bạn
                                    </span>
                                @endif
                            </td>

                            {{-- Đăng nhập cuối --}}
                            <td>
                                @if ($nv->dang_nhap_cuoi_at)
                                    <div style="font-size:.8rem;font-weight:700;color:var(--navy)">
                                        {{ $nv->dang_nhap_cuoi_at->format('d/m/Y') }}
                                    </div>
                                    <div style="font-size:.71rem;color:var(--text-muted)">
                                        {{ $nv->dang_nhap_cuoi_at->diffForHumans() }}
                                    </div>
                                @else
                                    <span style="font-size:.76rem;color:var(--text-muted);font-style:italic">
                                        Chưa đăng nhập
                                    </span>
                                @endif
                            </td>

                            {{-- Thao tác --}}
                            <td>
                                <div class="btn-actions-group justify-content-center">
                                    {{-- Sửa --}}
                                    <button type="button" class="btn-action btn-action-edit" data-tip="Chỉnh sửa"
                                        data-bs-toggle="modal" data-bs-target="#nvModal"
                                        onclick="openModalEdit({{ $nv->id }})">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    {{-- Đổi mật khẩu --}}
                                    <button type="button" class="btn-action btn-action-warn" data-tip="Đổi mật khẩu"
                                        data-bs-toggle="modal" data-bs-target="#pwModal"
                                        onclick="openModalPw({{ $nv->id }},'{{ addslashes($nv->ho_ten) }}')">
                                        <i class="fas fa-key"></i>
                                    </button>

                                    {{-- Xóa --}}
                                    @if (!$isMe)
                                        <form id="del-{{ $nv->id }}" method="POST"
                                            action="{{ route('nhanvien.admin.nhan-vien.destroy', $nv) }}"
                                            style="display:none">
                                            @csrf @method('DELETE')
                                        </form>
                                        <button type="button" class="btn-action btn-action-delete"
                                            data-tip="Xóa nhân viên" data-confirm-delete="{{ addslashes($nv->ho_ten) }}"
                                            data-form-id="del-{{ $nv->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="table-empty">
                                    <div class="table-empty-icon">
                                        <i class="fas fa-users-slash"></i>
                                    </div>
                                    <p>Không tìm thấy nhân viên nào</p>
                                    @if (request()->hasAny(['tukhoa', 'vai_tro', 'kich_hoat']))
                                        <a href="{{ route('nhanvien.admin.nhan-vien.index') }}">
                                            <i class="fas fa-times me-1"></i>Xóa bộ lọc
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile card list --}}
        <div class="mobile-card-list">
            @forelse($nhanViens as $nv)
                @php
                    $isMe = $nv->id === auth('nhanvien')->id();
                    $vt = $nv->vai_tro_info;
                @endphp
                <div class="mobile-card">
                    <div class="mobile-card-top">
                        <div class="person-cell">
                            <div style="position:relative;flex-shrink:0">
                                <img src="{{ $nv->anh_dai_dien_url }}" alt="{{ $nv->ho_ten }}"
                                    class="avatar avatar-md"
                                    onerror="this.src='{{ asset('images/default-avatar.png') }}'">
                                <span class="status-dot {{ $nv->kich_hoat ? 'on' : 'off' }}"></span>
                            </div>
                            <div class="person-info">
                                <div class="person-name">
                                    {{ $nv->ho_ten }}
                                    @if ($isMe)
                                        <span class="me-badge">Bạn</span>
                                    @endif
                                </div>
                                <div class="person-sub">#{{ $nv->id }}</div>
                            </div>
                        </div>
                        <span class="role-badge flex-shrink-0"
                            style="background:{{ $vt['bg'] }};color:{{ $vt['color'] }}">
                            <i class="{{ $vt['icon'] }}"></i> {{ $vt['label'] }}
                        </span>
                    </div>

                    <div class="mobile-card-meta">
                        <span><i class="fas fa-envelope"></i> {{ $nv->email }}</span>
                        @if ($nv->so_dien_thoai)
                            <span><i class="fas fa-phone"></i> {{ $nv->so_dien_thoai }}</span>
                        @endif
                        @if ($nv->dang_nhap_cuoi_at)
                            <span><i class="fas fa-clock"></i> {{ $nv->dang_nhap_cuoi_at->diffForHumans() }}</span>
                        @endif
                    </div>

                    <div class="mobile-card-foot">
                        @if (!$isMe)
                            <label class="toggle-sw">
                                <input type="checkbox" {{ $nv->kich_hoat ? 'checked' : '' }}
                                    data-toggle-url="{{ route('nhanvien.admin.nhan-vien.toggle', $nv) }}">
                                <span class="toggle-sw-track">
                                    <span class="toggle-sw-thumb"></span>
                                </span>
                            </label>
                        @else
                            <span class="toggle-label on"><i class="fas fa-circle"
                                    style="font-size:.45rem;vertical-align:middle"></i> Bạn</span>
                        @endif

                        <div class="btn-actions-group">
                            <button class="btn-action btn-action-edit" data-bs-toggle="modal" data-bs-target="#nvModal"
                                onclick="openModalEdit({{ $nv->id }})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn-action btn-action-warn" data-bs-toggle="modal" data-bs-target="#pwModal"
                                onclick="openModalPw({{ $nv->id }},'{{ addslashes($nv->ho_ten) }}')">
                                <i class="fas fa-key"></i>
                            </button>
                            @if (!$isMe)
                                <button class="btn-action btn-action-delete"
                                    data-confirm-delete="{{ addslashes($nv->ho_ten) }}"
                                    data-form-id="del-{{ $nv->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="table-empty">
                    <div class="table-empty-icon"><i class="fas fa-users-slash"></i></div>
                    <p>Không có nhân viên nào</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if ($nhanViens->hasPages())
            <div class="pagination-wrap">
                <span class="pagi-info">
                    Trang <strong>{{ $nhanViens->currentPage() }}</strong>
                    / <strong>{{ $nhanViens->lastPage() }}</strong>
                    &nbsp;·&nbsp; Tổng <strong>{{ $nhanViens->total() }}</strong> nhân viên
                </span>
                <nav>
                    <ul class="pagination pagination-sm mb-0">
                        {{-- Prev --}}
                        <li class="page-item {{ $nhanViens->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $nhanViens->previousPageUrl() }}">
                                <i class="fas fa-chevron-left" style="font-size:.65rem"></i>
                            </a>
                        </li>

                        @foreach ($nhanViens->getUrlRange(max(1, $nhanViens->currentPage() - 2), min($nhanViens->lastPage(), $nhanViens->currentPage() + 2)) as $page => $url)
                            <li class="page-item {{ $page == $nhanViens->currentPage() ? 'active' : '' }}">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endforeach

                        {{-- Next --}}
                        <li class="page-item {{ !$nhanViens->hasMorePages() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $nhanViens->nextPageUrl() }}">
                                <i class="fas fa-chevron-right" style="font-size:.65rem"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        @endif
    </div>


    {{-- ═══════════════════════════════════════════════
     BOOTSTRAP MODAL — THÊM / SỬA NHÂN VIÊN
═══════════════════════════════════════════════ --}}
    <div class="modal fade" id="nvModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header"
                    style="background:linear-gradient(to right,var(--bg-alt),var(--surface));border-bottom-color:var(--border)">
                    <h5 class="modal-title fw-black" id="nvModalLabel" style="font-size:.95rem;color:var(--navy)">
                        <i class="fas fa-user-plus me-2" style="color:var(--primary)"></i>
                        <span id="nvModalTitleText">Thêm nhân viên</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="nvResetForm()"></button>
                </div>

                <div class="modal-body p-4">
                    <form id="nvForm" enctype="multipart/form-data" novalidate>
                        @csrf
                        <input type="hidden" name="_method" id="nvFormMethod" value="POST">

                        {{-- ── Avatar ── --}}
                        <div class="mb-4">
                            <label class="form-label">Ảnh đại diện</label>
                            <div class="avatar-upload">
                                <img id="nvAvatarPreview" src="{{ asset('images/default-avatar.png') }}"
                                    class="avatar avatar-xl"
                                    onerror="this.src='{{ asset('images/default-avatar.png') }}'">
                                <div>
                                    <label class="avatar-upload-label" for="nvAvatarFile">
                                        <i class="fas fa-camera"></i> Chọn ảnh
                                    </label>
                                    <input type="file" id="nvAvatarFile" name="anh_dai_dien"
                                        accept="image/jpeg,image/png,image/webp" style="display:none"
                                        onchange="previewImage(this,'nvAvatarPreview')">
                                    <div class="form-hint">JPG, PNG, WebP – tối đa 2MB</div>
                                    <div class="form-error d-none" id="err_anh_dai_dien">
                                        <i class="fas fa-exclamation-circle"></i>
                                        <span></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ── Họ tên + Email ── --}}
                        <div class="row g-3 mb-3">
                            <div class="col-sm-6">
                                <label class="form-label" for="nvHoTen">
                                    Họ và tên <span class="req">*</span>
                                </label>
                                <input type="text" id="nvHoTen" name="ho_ten" class="form-control"
                                    placeholder="Nguyễn Văn A">
                                <div class="form-error d-none" id="err_ho_ten">
                                    <i class="fas fa-exclamation-circle"></i><span></span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label" for="nvEmail">
                                    Email <span class="req">*</span>
                                </label>
                                <input type="email" id="nvEmail" name="email" class="form-control"
                                    placeholder="nhanvien@example.com">
                                <div class="form-hint d-none" id="nvEmailHint">
                                    <i class="fas fa-lock" style="font-size:.65rem"></i> Email không thể thay đổi
                                </div>
                                <div class="form-error d-none" id="err_email">
                                    <i class="fas fa-exclamation-circle"></i><span></span>
                                </div>
                            </div>
                        </div>

                        {{-- ── SĐT ── --}}
                        <div class="mb-3">
                            <label class="form-label" for="nvSdt">Số điện thoại</label>
                            <input type="tel" id="nvSdt" name="so_dien_thoai" class="form-control"
                                placeholder="0912 345 678">
                            <div class="form-error d-none" id="err_so_dien_thoai">
                                <i class="fas fa-exclamation-circle"></i><span></span>
                            </div>
                        </div>

                        {{-- ── Mật khẩu ── --}}
                        <div class="card mb-3" id="nvPwSection">
                            <div class="card-header">
                                <span><i class="fas fa-lock"></i></span>
                                <span id="nvPwSectionTitle">Thiết lập mật khẩu</span>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <label class="form-label">
                                            Mật khẩu <span class="req" id="nvPwReqStar">*</span>
                                        </label>
                                        <div class="pw-wrap position-relative">
                                            <input type="password" id="nvPw1" name="mat_khau" class="form-control"
                                                placeholder="Tối thiểu 6 ký tự" autocomplete="new-password">
                                            <button type="button" class="pw-eye"
                                                onclick="togglePwEye('nvPw1','nvEye1')">
                                                <i class="fas fa-eye" id="nvEye1"></i>
                                            </button>
                                        </div>
                                        <div class="form-error d-none" id="err_mat_khau">
                                            <i class="fas fa-exclamation-circle"></i><span></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-label">
                                            Xác nhận <span class="req" id="nvPwConfStar">*</span>
                                        </label>
                                        <div class="pw-wrap position-relative">
                                            <input type="password" id="nvPw2" name="mat_khau_confirmation"
                                                class="form-control" placeholder="Nhập lại mật khẩu"
                                                autocomplete="new-password">
                                            <button type="button" class="pw-eye"
                                                onclick="togglePwEye('nvPw2','nvEye2')">
                                                <i class="fas fa-eye" id="nvEye2"></i>
                                            </button>
                                        </div>
                                        <div class="form-error d-none" id="err_mat_khau_confirmation">
                                            <i class="fas fa-exclamation-circle"></i><span></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ── Vai trò ── --}}
                        <div class="mb-3">
                            <label class="form-label">Vai trò <span class="req">*</span></label>
                            <div class="role-card-grid" id="nvRoleGrid">
                                @foreach (\App\Models\NhanVien::VAI_TRO as $key => $info)
                                    <label class="role-card" id="nv-rc-{{ $key }}"
                                        onclick="selectRoleCard('{{ $key }}', this)">
                                        <input type="radio" name="vai_tro" value="{{ $key }}">
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
                            <div class="form-error d-none mt-2" id="err_vai_tro">
                                <i class="fas fa-exclamation-circle"></i><span></span>
                            </div>
                        </div>

                        {{-- ── Kích hoạt ── --}}
                        <div>
                            <label class="form-label">Trạng thái tài khoản</label>
                            <div class="d-flex align-items-center gap-3">
                                <label class="toggle-sw">
                                    <input type="checkbox" id="nvKichHoat" name="kich_hoat" value="1" checked>
                                    <span class="toggle-sw-track">
                                        <span class="toggle-sw-thumb"></span>
                                    </span>
                                </label>
                                <span id="nvKhLabel" class="toggle-label on">Đang hoạt động</span>
                            </div>
                        </div>

                    </form>
                </div>{{-- /modal-body --}}

                <div class="modal-footer" style="background:var(--bg-alt);border-top-color:var(--border)">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="nvResetForm()">
                        <i class="fas fa-times me-1"></i>Hủy
                    </button>
                    <button type="button" class="btn btn-primary" id="nvSubmitBtn" onclick="submitNvForm()">
                        <i class="fas fa-user-plus me-1" id="nvSubmitIcon"></i>
                        <span id="nvSubmitText">Tạo nhân viên</span>
                    </button>
                </div>

            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════
     BOOTSTRAP MODAL — ĐỔI MẬT KHẨU
═══════════════════════════════════════════════ --}}
    <div class="modal fade" id="pwModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" style="max-width:460px">
            <div class="modal-content">

                <div class="modal-header"
                    style="background:linear-gradient(to right,var(--bg-alt),var(--surface));border-bottom-color:var(--border)">
                    <h5 class="modal-title fw-black" style="font-size:.95rem;color:var(--navy)">
                        <i class="fas fa-key me-2" style="color:var(--yellow)"></i>Đổi mật khẩu
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body p-4">
                    <p style="font-size:.83rem;color:var(--text-sub);margin-bottom:1.2rem">
                        Đổi mật khẩu cho:
                        <strong id="pwModalName" style="color:var(--navy)"></strong>
                    </p>

                    <div class="mb-3">
                        <label class="form-label">Mật khẩu mới <span class="req">*</span></label>
                        <div class="pw-wrap position-relative">
                            <input type="password" id="pwNew" class="form-control" placeholder="Tối thiểu 6 ký tự">
                            <button type="button" class="pw-eye" onclick="togglePwEye('pwNew','pwNewEye')">
                                <i class="fas fa-eye" id="pwNewEye"></i>
                            </button>
                        </div>
                        <div class="form-error d-none" id="err_pw_new">
                            <i class="fas fa-exclamation-circle"></i><span></span>
                        </div>
                    </div>

                    <div>
                        <label class="form-label">Xác nhận mật khẩu <span class="req">*</span></label>
                        <div class="pw-wrap position-relative">
                            <input type="password" id="pwConfirm" class="form-control" placeholder="Nhập lại mật khẩu">
                            <button type="button" class="pw-eye" onclick="togglePwEye('pwConfirm','pwConfEye')">
                                <i class="fas fa-eye" id="pwConfEye"></i>
                            </button>
                        </div>
                        <div class="form-error d-none" id="err_pw_conf">
                            <i class="fas fa-exclamation-circle"></i><span></span>
                        </div>
                    </div>
                </div>

                <div class="modal-footer" style="background:var(--bg-alt);border-top-color:var(--border)">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Hủy
                    </button>
                    <button type="button" class="btn btn-navy" id="pwSubmitBtn" onclick="submitPwForm()">
                        <i class="fas fa-save me-1"></i>Đổi mật khẩu
                    </button>
                </div>

            </div>
        </div>
    </div>

@endsection


{{-- ═══════════════════════════════════════════════
     SCRIPTS
═══════════════════════════════════════════════ --}}
@push('scripts')
    <script>
        'use strict';

        /* ── State ── */
        let _nvEditId = null;
        let _pwNvId = null;
        const _nvModal = () => bootstrap.Modal.getOrCreateInstance(document.getElementById('nvModal'));
        const _pwModal = () => bootstrap.Modal.getOrCreateInstance(document.getElementById('pwModal'));

        /* ── Error helpers ── */
        function nvShowErr(id, msg) {
            const el = document.getElementById(id);
            if (!el) return;
            el.querySelector('span').textContent = msg;
            el.classList.remove('d-none');
            const inp = document.getElementById(id.replace('err_', 'nv').replace('err_pw_new', 'pwNew').replace(
                'err_pw_conf', 'pwConfirm'));
            inp?.classList.add('is-invalid');
        }

        function nvClearErrs() {
            document.querySelectorAll('#nvForm .form-error, #pwModal .form-error').forEach(e => {
                e.classList.add('d-none');
                e.querySelector('span').textContent = '';
            });
            document.querySelectorAll('#nvForm .is-invalid, #pwModal .is-invalid').forEach(e => e.classList.remove(
                'is-invalid'));
        }

        function nvResetForm() {
            document.getElementById('nvForm')?.reset();
            document.getElementById('nvAvatarPreview').src = "{{ asset('images/default-avatar.png') }}";
            document.querySelectorAll('#nvRoleGrid .role-card').forEach(c => c.classList.remove('selected'));
            nvClearErrs();
            _nvEditId = null;
        }

        /* ══ MODAL THÊM ══ */
        function openModalAdd() {
            nvResetForm();
            document.getElementById('nvModalTitleText').textContent = 'Thêm nhân viên';
            document.getElementById('nvSubmitIcon').className = 'fas fa-user-plus me-1';
            document.getElementById('nvSubmitText').textContent = 'Tạo nhân viên';
            document.getElementById('nvPwSectionTitle').textContent = 'Thiết lập mật khẩu';
            document.getElementById('nvPwReqStar').style.display = 'inline';
            document.getElementById('nvPwConfStar').style.display = 'inline';
            document.getElementById('nvPw1').placeholder = 'Tối thiểu 6 ký tự';
            document.getElementById('nvEmail').readOnly = false;
            document.getElementById('nvEmail').style.background = '';
            document.getElementById('nvEmailHint').classList.add('d-none');
            document.getElementById('nvFormMethod').value = 'POST';
            updateToggleLabel(document.getElementById('nvKichHoat'), document.getElementById('nvKhLabel'));
            setTimeout(() => document.getElementById('nvHoTen').focus(), 300);
        }

        /* ══ MODAL SỬA ══ */
        function openModalEdit(id) {
            nvResetForm();
            _nvEditId = id;
            document.getElementById('nvModalTitleText').textContent = 'Cập nhật nhân viên';
            document.getElementById('nvSubmitIcon').className = 'fas fa-save me-1';
            document.getElementById('nvSubmitText').textContent = 'Lưu thay đổi';
            document.getElementById('nvPwSectionTitle').textContent = 'Đổi mật khẩu (để trống nếu không đổi)';
            document.getElementById('nvPwReqStar').style.display = 'none';
            document.getElementById('nvPwConfStar').style.display = 'none';
            document.getElementById('nvPw1').placeholder = 'Để trống nếu không đổi';
            document.getElementById('nvEmail').readOnly = false;
            document.getElementById('nvEmail').style.background = '';
            document.getElementById('nvEmailHint').classList.add('d-none');
            document.getElementById('nvFormMethod').value = 'PUT';

            const btn = document.getElementById('nvSubmitBtn');
            btnLoading(btn, 'Đang tải...');

            fetch(`{{ url('nhan-vien/admin/nhan-vien') }}/${id}/edit-data`, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                        Accept: 'application/json'
                    }
                })
                .then(r => r.json())
                .then(d => {
                    btnRestore(btn);
                    if (!d.ok) {
                        showAdminToast(d.msg || 'Không tải được dữ liệu!', 'error');
                        _nvModal().hide();
                        return;
                    }
                    const nv = d.nhanVien;
                    document.getElementById('nvHoTen').value = nv.ho_ten || '';
                    document.getElementById('nvEmail').value = nv.email || '';
                    document.getElementById('nvSdt').value = nv.so_dien_thoai || '';
                    if (nv.anh_dai_dien_url) document.getElementById('nvAvatarPreview').src = nv.anh_dai_dien_url;
                    const rc = document.getElementById('nv-rc-' + nv.vai_tro);
                    if (rc) {
                        rc.classList.add('selected');
                        rc.querySelector('input[type=radio]').checked = true;
                    }
                    const kh = document.getElementById('nvKichHoat');
                    kh.checked = !!nv.kich_hoat;
                    updateToggleLabel(kh, document.getElementById('nvKhLabel'));
                })
                .catch(() => {
                    btnRestore(btn);
                    showAdminToast('Lỗi kết nối!', 'error');
                    _nvModal().hide();
                });
        }

        /* ══ SUBMIT NV FORM ══ */
        function submitNvForm() {
            nvClearErrs();
            const isEdit = !!_nvEditId;
            const hoTen = document.getElementById('nvHoTen').value.trim();
            const email = document.getElementById('nvEmail').value.trim();
            const pw1 = document.getElementById('nvPw1').value;
            const pw2 = document.getElementById('nvPw2').value;
            const vaiTro = document.querySelector('#nvRoleGrid input[type=radio]:checked')?.value;
            let hasErr = false;

            if (!hoTen) {
                nvShowErr('err_ho_ten', 'Vui lòng nhập họ và tên');
                hasErr = true;
            }
            if (!email) {
                nvShowErr('err_email', 'Vui lòng nhập email');
                hasErr = true;
            }
            if (!vaiTro) {
                nvShowErr('err_vai_tro', 'Vui lòng chọn vai trò');
                hasErr = true;
            }
            if (!isEdit && (!pw1 || pw1.length < 6)) {
                nvShowErr('err_mat_khau', 'Mật khẩu tối thiểu 6 ký tự');
                hasErr = true;
            }
            if (!isEdit && !pw2) {
                nvShowErr('err_mat_khau_confirmation', 'Vui lòng nhập xác nhận');
                hasErr = true;
            }
            if (isEdit && pw1 && pw1.length < 6) {
                nvShowErr('err_mat_khau', 'Mật khẩu tối thiểu 6 ký tự');
                hasErr = true;
            }
            if (pw1 && pw2 && pw1 !== pw2) {
                nvShowErr('err_mat_khau_confirmation', 'Mật khẩu không khớp');
                hasErr = true;
            }
            if (hasErr) return;

            const btn = document.getElementById('nvSubmitBtn');
            btnLoading(btn, isEdit ? 'Đang lưu...' : 'Đang tạo...');

            const fd = new FormData(document.getElementById('nvForm'));
            fd.set('kich_hoat', document.getElementById('nvKichHoat').checked ? '1' : '0');
            if (isEdit) fd.set('_method', 'PUT');

            const url = isEdit ?
                `{{ url('nhan-vien/admin/nhan-vien') }}/${_nvEditId}` :
                `{{ route('nhanvien.admin.nhan-vien.store') }}`;

            fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: fd
                })
                .then(r => r.json())
                .then(d => {
                    btnRestore(btn);
                    if (d.ok) {
                        _nvModal().hide();
                        nvResetForm();
                        showAdminToast(d.msg || (isEdit ? 'Cập nhật thành công!' : 'Đã thêm nhân viên!'), 'success');
                        setTimeout(() => location.reload(), 1400);
                    } else {
                        if (d.errors) {
                            Object.entries(d.errors).forEach(([k, v]) => {
                                nvShowErr('err_' + k, Array.isArray(v) ? v[0] : v);
                            });
                        } else {
                            showAdminToast(d.message || d.msg || 'Có lỗi xảy ra!', 'error');
                        }
                    }
                })
                .catch(() => {
                    btnRestore(btn);
                    showAdminToast('Lỗi kết nối!', 'error');
                });
        }

        /* ══ MODAL ĐỔI MẬT KHẨU ══ */
        function openModalPw(id, ten) {
            _pwNvId = id;
            document.getElementById('pwModalName').textContent = ten;
            document.getElementById('pwNew').value = '';
            document.getElementById('pwConfirm').value = '';
            ['err_pw_new', 'err_pw_conf'].forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    el.classList.add('d-none');
                    el.querySelector('span').textContent = '';
                }
            });
            ['pwNew', 'pwConfirm'].forEach(id => document.getElementById(id)?.classList.remove('is-invalid'));
            setTimeout(() => document.getElementById('pwNew').focus(), 300);
        }

        function submitPwForm() {
            const pw1 = document.getElementById('pwNew').value.trim();
            const pw2 = document.getElementById('pwConfirm').value.trim();
            ['err_pw_new', 'err_pw_conf'].forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    el.classList.add('d-none');
                    el.querySelector('span').textContent = '';
                }
            });
            ['pwNew', 'pwConfirm'].forEach(id => document.getElementById(id)?.classList.remove('is-invalid'));
            let ok = true;
            if (!pw1 || pw1.length < 6) {
                nvShowErr('err_pw_new', 'Mật khẩu tối thiểu 6 ký tự');
                ok = false;
            }
            if (!pw2) {
                nvShowErr('err_pw_conf', 'Vui lòng nhập xác nhận');
                ok = false;
            } else if (pw1 !== pw2) {
                nvShowErr('err_pw_conf', 'Mật khẩu không khớp');
                ok = false;
            }
            if (!ok) return;

            const btn = document.getElementById('pwSubmitBtn');
            btnLoading(btn, 'Đang đổi...');

            fetch(`{{ url('nhan-vien/admin/nhan-vien') }}/${_pwNvId}/doi-mat-khau`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        mat_khau_moi: pw1,
                        xac_nhan_mat_khau: pw2
                    })
                })
                .then(r => r.json())
                .then(d => {
                    btnRestore(btn);
                    if (d.ok) {
                        _pwModal().hide();
                        showAdminToast(d.msg || 'Đã đổi mật khẩu!', 'success');
                    } else {
                        showAdminToast(d.msg || 'Không thể đổi mật khẩu!', 'error');
                    }
                })
                .catch(() => {
                    btnRestore(btn);
                    showAdminToast('Lỗi kết nối!', 'error');
                });
        }

        /* ── Init toggle label khi load ── */
        document.addEventListener('DOMContentLoaded', function() {
            updateToggleLabel(
                document.getElementById('nvKichHoat'),
                document.getElementById('nvKhLabel')
            );
        });
    </script>
@endpush
