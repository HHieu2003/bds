{{-- resources/views/admin/khu-vuc/index.blade.php --}}
@extends('admin.layouts.master')
@section('title', 'Quản lý khu vực')

@section('content')

    {{-- ── PAGE HEADER ── --}}
    <div class="d-flex align-items-start justify-content-between gap-3 mb-4 flex-wrap">
        <div>
            <h1 class="fw-black mb-1" style="font-size:1.3rem;color:var(--navy)">
                <i class="fas fa-map-marked-alt me-2" style="color:var(--primary)"></i>Quản lý khu vực
            </h1>
            <div class="d-flex align-items-center gap-2 flex-wrap" style="font-size:.78rem;color:var(--text-sub)">
                <span>{{ $thongKe['tong'] ?? 0 }} khu vực</span>
                <span
                    style="width:4px;height:4px;border-radius:50%;background:var(--text-muted);display:inline-block"></span>
                <span style="color:var(--green)">{{ $thongKe['hien_thi'] ?? 0 }} đang hiển thị</span>
                <span
                    style="width:4px;height:4px;border-radius:50%;background:var(--text-muted);display:inline-block"></span>
                <span style="color:var(--blue)">{{ $thongKe['tong_du_an'] ?? 0 }} dự án</span>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2 flex-shrink-0">
            {{-- View toggle --}}
            <div class="d-none d-md-flex align-items-center gap-1"
                style="background:var(--bg-alt);border:1px solid var(--border);border-radius:var(--r-sm);padding:3px">
                <button type="button" id="btnViewTable" onclick="setView('table')" class="view-toggle-btn active"
                    data-tip="Dạng bảng">
                    <i class="fas fa-list"></i>
                </button>
                <button type="button" id="btnViewGrid" onclick="setView('grid')" class="view-toggle-btn"
                    data-tip="Dạng lưới">
                    <i class="fas fa-th-large"></i>
                </button>
            </div>
            <a href="{{ route('nhanvien.admin.khu-vuc.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
                <i class="fas fa-plus"></i>
                <span class="d-none d-sm-inline">Thêm khu vực</span>
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible d-flex align-items-center gap-2 mb-4" role="alert">
            <i class="fas fa-check-circle flex-shrink-0"></i>
            <span>{{ session('success') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ── STAT CARDS ── --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-sm-3">
            <div class="stat-card">
                <div class="stat-icon navy"><i class="fas fa-map-marked-alt"></i></div>
                <div>
                    <div class="stat-num">{{ $thongKe['tong'] ?? 0 }}</div>
                    <div class="stat-label">Tổng khu vực</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-sm-3">
            <div class="stat-card">
                <div class="stat-icon green"><i class="fas fa-eye"></i></div>
                <div>
                    <div class="stat-num">{{ $thongKe['hien_thi'] ?? 0 }}</div>
                    <div class="stat-label">Đang hiển thị</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-sm-3">
            <div class="stat-card">
                <div class="stat-icon blue"><i class="fas fa-building"></i></div>
                <div>
                    <div class="stat-num">{{ $thongKe['tong_du_an'] ?? 0 }}</div>
                    <div class="stat-label">Dự án</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-sm-3">
            <div class="stat-card">
                <div class="stat-icon orange"><i class="fas fa-home"></i></div>
                <div>
                    <div class="stat-num">{{ $thongKe['tong_bds'] ?? 0 }}</div>
                    <div class="stat-label">Bất động sản</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── FILTER ── --}}
    <div class="filter-box mb-3">
        <form method="GET" action="{{ route('nhanvien.admin.khu-vuc.index') }}" id="filterForm">
            <div class="filter-box-row">
                <input type="text" name="tukhoa" class="filter-ctrl filter-ctrl-search search-debounce"
                    value="{{ request('tukhoa') }}" placeholder="Tìm tên khu vực, slug..." style="min-width:200px;flex:1">

                <select name="hien_thi" class="filter-ctrl filter-auto-submit">
                    <option value="">Tất cả trạng thái</option>
                    <option value="1" {{ request('hien_thi') === '1' ? 'selected' : '' }}>Đang hiển thị</option>
                    <option value="0" {{ request('hien_thi') === '0' ? 'selected' : '' }}>Đã ẩn</option>
                </select>

                <select name="sapxep" class="filter-ctrl filter-auto-submit">
                    <option value="thu_tu" {{ request('sapxep', 'thu_tu') === 'thu_tu' ? 'selected' : '' }}>Thứ tự</option>
                    <option value="ten_az" {{ request('sapxep') === 'ten_az' ? 'selected' : '' }}>Tên A–Z</option>
                    <option value="nhieu_du_an" {{ request('sapxep') === 'nhieu_du_an' ? 'selected' : '' }}>Nhiều dự án
                    </option>
                    <option value="moi_nhat" {{ request('sapxep') === 'moi_nhat' ? 'selected' : '' }}>Mới nhất</option>
                </select>

                @if (request()->hasAny(['tukhoa', 'hien_thi', 'sapxep']))
                    <a href="{{ route('nhanvien.admin.khu-vuc.index') }}"
                        class="btn btn-secondary btn-sm d-flex align-items-center gap-1 flex-shrink-0">
                        <i class="fas fa-times"></i> Xóa lọc
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- ══════════════════════════════════════
     VIEW TABLE (Desktop mặc định)
══════════════════════════════════════ --}}
    <div id="viewTable">
        <div class="card">
            <div class="card-header">
                <span class="d-flex align-items-center gap-2">
                    <i class="fas fa-list"></i> Danh sách khu vực
                    <span class="count-chip count-chip-blue" style="font-size:.72rem">
                        {{ $khuVucs->total() }}
                    </span>
                </span>
                @if ($khuVucs->total())
                    <span class="result-info">
                        {{ $khuVucs->firstItem() }}–{{ $khuVucs->lastItem() }}
                        / {{ $khuVucs->total() }}
                    </span>
                @endif
            </div>

            {{-- Desktop table --}}
            <div class="table-wrap tbl-desktop">
                <table class="table table-hover mb-0" id="kvTable">
                    <thead>
                        <tr>
                            <th style="width:44px;text-align:center">
                                <i class="fas fa-sort" style="color:var(--text-muted);font-size:.75rem"
                                    data-tip="Kéo để sắp xếp"></i>
                            </th>
                            <th>Khu vực</th>
                            <th style="width:90px;text-align:center">Dự án</th>
                            <th style="width:90px;text-align:center">BĐS</th>
                            <th style="width:120px">Hiển thị</th>
                            <th style="width:80px;text-align:center">Thứ tự</th>
                            <th style="width:110px;text-align:center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody id="kvSortable">
                        @forelse($khuVucs as $kv)
                            <tr data-id="{{ $kv->id }}" class="kv-row">
                                {{-- Drag handle --}}
                                <td class="text-center" style="cursor:grab">
                                    <i class="fas fa-grip-vertical" style="color:var(--text-muted);font-size:.8rem"></i>
                                </td>

                                {{-- Khu vực info --}}
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        {{-- Thumbnail --}}
                                        <div style="flex-shrink:0">
                                            @if ($kv->anh_dai_dien)
                                                <img src="{{ asset('storage/' . $kv->anh_dai_dien) }}"
                                                    alt="{{ $kv->ten_khu_vuc }}" class="table-thumb"
                                                    style="width:60px;height:45px;border-radius:var(--r-sm)"
                                                    onerror="this.parentElement.innerHTML='<div style=\'width:60px;height:45px;border-radius:var(--r-sm);background:linear-gradient(135deg,var(--navy-soft),var(--primary-soft));display:flex;align-items:center;justify-content:center\'><i class=\'fas fa-map-marker-alt\' style=\'color:var(--primary);font-size:1.1rem\'></i></div>'">
                                            @else
                                                <div
                                                    style="width:60px;height:45px;border-radius:var(--r-sm);background:linear-gradient(135deg,var(--navy-soft),var(--primary-soft));display:flex;align-items:center;justify-content:center">
                                                    <i class="fas fa-map-marker-alt"
                                                        style="color:var(--primary);font-size:1.1rem"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div style="min-width:0">
                                            <div class="fw-bold" style="font-size:.85rem;color:var(--navy)">
                                                {{ $kv->ten_khu_vuc }}
                                            </div>
                                            @if ($kv->slug ?? null)
                                                <div class="d-flex align-items-center gap-1 mt-1"
                                                    style="font-size:.7rem;color:var(--text-muted)">
                                                    <i class="fas fa-link" style="font-size:.58rem"></i>
                                                    <span
                                                        style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:180px">
                                                        /{{ $kv->slug }}
                                                    </span>
                                                    <button type="button" class="btn-action btn-action-view"
                                                        style="width:18px;height:18px;font-size:.58rem"
                                                        data-tip="Sao chép slug"
                                                        onclick="copyToClipboard('{{ $kv->slug }}','Đã sao chép!')">
                                                        <i class="fas fa-copy"></i>
                                                    </button>
                                                </div>
                                            @endif
                                            @if ($kv->mo_ta ?? null)
                                                <div
                                                    style="font-size:.71rem;color:var(--text-sub);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:240px;margin-top:.15rem">
                                                    {{ Str::limit($kv->mo_ta, 60) }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                {{-- Dự án --}}
                                <td class="text-center">
                                    @if (($kv->so_du_an ?? 0) > 0)
                                        <a href="{{ route('nhanvien.admin.du-an.index', ['khu_vuc_id' => $kv->id]) }}"
                                            class="count-chip count-chip-blue" style="font-size:.76rem"
                                            data-tip="Xem {{ $kv->so_du_an }} dự án">
                                            <i class="fas fa-building me-1" style="font-size:.65rem"></i>
                                            {{ $kv->so_du_an }}
                                        </a>
                                    @else
                                        <span class="count-empty">—</span>
                                    @endif
                                </td>

                                {{-- BĐS --}}
                                <td class="text-center">
                                    @if (($kv->so_bds ?? 0) > 0)
                                        <span class="count-chip count-chip-orange" style="font-size:.76rem">
                                            <i class="fas fa-home me-1" style="font-size:.65rem"></i>
                                            {{ number_format($kv->so_bds) }}
                                        </span>
                                    @else
                                        <span class="count-empty">—</span>
                                    @endif
                                </td>

                                {{-- Hiển thị --}}
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <label class="toggle-sw">
                                            <input type="checkbox" {{ $kv->hien_thi ? 'checked' : '' }}
                                                data-toggle-url="{{ route('nhanvien.admin.khu-vuc.toggle-hien-thi', $kv) }}">
                                            <span class="toggle-sw-track"><span class="toggle-sw-thumb"></span></span>
                                        </label>
                                        <span class="toggle-label {{ $kv->hien_thi ? 'on' : 'off' }}"
                                            style="font-size:.71rem">
                                            {{ $kv->hien_thi ? 'Hiện' : 'Ẩn' }}
                                        </span>
                                    </div>
                                </td>

                                {{-- Thứ tự --}}
                                <td class="text-center">
                                    <span class="count-chip"
                                        style="background:var(--bg);color:var(--text-sub);font-size:.76rem">
                                        #{{ $kv->thu_tu_hien_thi ?? 0 }}
                                    </span>
                                </td>

                                {{-- Thao tác --}}
                                <td>
                                    <div class="btn-actions-group justify-content-center">
                                        <a href="{{ route('nhanvien.admin.khu-vuc.edit', $kv) }}"
                                            class="btn-action btn-action-edit" data-tip="Chỉnh sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form id="del-kv-{{ $kv->id }}" method="POST"
                                            action="{{ route('nhanvien.admin.khu-vuc.destroy', $kv) }}"
                                            style="display:none">
                                            @csrf @method('DELETE')
                                        </form>
                                        <button type="button" class="btn-action btn-action-delete"
                                            data-tip="Xóa khu vực"
                                            data-confirm-delete="{{ addslashes($kv->ten_khu_vuc) }}"
                                            data-form-id="del-kv-{{ $kv->id }}"
                                            @if (($kv->so_du_an ?? 0) > 0) onclick="event.preventDefault();showAdminToast('Không thể xóa khu vực đang có dự án!','warning')" @endif>
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="table-empty">
                                        <div class="table-empty-icon"><i class="fas fa-map-marked-alt"></i></div>
                                        <p>Chưa có khu vực nào</p>
                                        @if (request()->hasAny(['tukhoa', 'hien_thi']))
                                            <a href="{{ route('nhanvien.admin.khu-vuc.index') }}"
                                                style="font-size:.82rem;color:var(--primary)">
                                                <i class="fas fa-times me-1"></i>Xóa bộ lọc
                                            </a>
                                        @else
                                            <a href="{{ route('nhanvien.admin.khu-vuc.create') }}"
                                                class="btn btn-primary btn-sm mt-2">
                                                <i class="fas fa-plus me-1"></i>Thêm khu vực đầu tiên
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Mobile Card List --}}
            <div class="mobile-card-list">
                @forelse($khuVucs as $kv)
                    <div class="mobile-card" data-id="{{ $kv->id }}">
                        <div class="mobile-card-top">
                            <div class="d-flex align-items-center gap-3 flex-1" style="min-width:0">
                                @if ($kv->anh_dai_dien)
                                    <img src="{{ asset('storage/' . $kv->anh_dai_dien) }}" alt="{{ $kv->ten_khu_vuc }}"
                                        class="table-thumb flex-shrink-0" style="width:52px;height:40px">
                                @else
                                    <div
                                        style="width:52px;height:40px;border-radius:var(--r-sm);background:linear-gradient(135deg,var(--navy-soft),var(--primary-soft));display:flex;align-items:center;justify-content:center;flex-shrink:0">
                                        <i class="fas fa-map-marker-alt" style="color:var(--primary)"></i>
                                    </div>
                                @endif
                                <div style="min-width:0">
                                    <div class="fw-bold"
                                        style="font-size:.85rem;color:var(--navy);overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                                        {{ $kv->ten_khu_vuc }}
                                    </div>
                                    @if ($kv->slug ?? null)
                                        <div
                                            style="font-size:.7rem;color:var(--text-muted);overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                                            /{{ $kv->slug }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <span
                                class="badge-status {{ $kv->hien_thi ? 'badge-active' : 'badge-inactive' }} flex-shrink-0">
                                {{ $kv->hien_thi ? 'Hiển thị' : 'Đã ẩn' }}
                            </span>
                        </div>

                        <div class="mobile-card-meta">
                            <span><i class="fas fa-building"></i> {{ $kv->so_du_an ?? 0 }} dự án</span>
                            <span><i class="fas fa-home"></i> {{ number_format($kv->so_bds ?? 0) }} BĐS</span>
                            <span><i class="fas fa-sort-numeric-up"></i> Thứ tự #{{ $kv->thu_tu_hien_thi ?? 0 }}</span>
                        </div>

                        <div class="mobile-card-foot">
                            <label class="toggle-sw" data-tip="{{ $kv->hien_thi ? 'Ẩn' : 'Hiển thị' }}">
                                <input type="checkbox" {{ $kv->hien_thi ? 'checked' : '' }}
                                    data-toggle-url="{{ route('nhanvien.admin.khu-vuc.toggle-hien-thi', $kv) }}">
                                <span class="toggle-sw-track"><span class="toggle-sw-thumb"></span></span>
                            </label>
                            <div class="btn-actions-group">
                                <a href="{{ route('nhanvien.admin.khu-vuc.edit', $kv) }}"
                                    class="btn-action btn-action-edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn-action btn-action-delete"
                                    data-confirm-delete="{{ addslashes($kv->ten_khu_vuc) }}"
                                    data-form-id="del-kv-{{ $kv->id }}"
                                    @if (($kv->so_du_an ?? 0) > 0) onclick="event.preventDefault();showAdminToast('Không thể xóa khu vực đang có dự án!','warning')" @endif>
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="table-empty">
                        <div class="table-empty-icon"><i class="fas fa-map-marked-alt"></i></div>
                        <p>Chưa có khu vực nào</p>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if ($khuVucs->hasPages())
                <div class="pagination-wrap">
                    <span class="pagi-info">
                        Trang <strong>{{ $khuVucs->currentPage() }}</strong>
                        / <strong>{{ $khuVucs->lastPage() }}</strong>
                        &nbsp;·&nbsp; Tổng <strong>{{ $khuVucs->total() }}</strong>
                    </span>
                    <nav>
                        <ul class="pagination pagination-sm mb-0">
                            <li class="page-item {{ $khuVucs->onFirstPage() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $khuVucs->previousPageUrl() }}">
                                    <i class="fas fa-chevron-left" style="font-size:.6rem"></i>
                                </a>
                            </li>
                            @foreach ($khuVucs->getUrlRange(max(1, $khuVucs->currentPage() - 2), min($khuVucs->lastPage(), $khuVucs->currentPage() + 2)) as $page => $url)
                                <li class="page-item {{ $page == $khuVucs->currentPage() ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                </li>
                            @endforeach
                            <li class="page-item {{ !$khuVucs->hasMorePages() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $khuVucs->nextPageUrl() }}">
                                    <i class="fas fa-chevron-right" style="font-size:.6rem"></i>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            @endif
        </div>
    </div>

    {{-- ══════════════════════════════════════
     VIEW GRID (ảnh thumbnail cards)
══════════════════════════════════════ --}}
    <div id="viewGrid" style="display:none">
        @if ($khuVucs->count())
            <div class="row g-3">
                @foreach ($khuVucs as $kv)
                    <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                        <div class="kv-grid-card {{ !$kv->hien_thi ? 'kv-grid-card--hidden' : '' }}">
                            {{-- Ảnh --}}
                            <div class="kv-grid-img">
                                @if ($kv->anh_dai_dien)
                                    <img src="{{ asset('storage/' . $kv->anh_dai_dien) }}" alt="{{ $kv->ten_khu_vuc }}"
                                        onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                                    <div class="kv-grid-img-placeholder" style="display:none">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                @else
                                    <div class="kv-grid-img-placeholder">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                @endif
                                {{-- Badges overlay --}}
                                <div class="kv-grid-overlay">
                                    @if (!$kv->hien_thi)
                                        <span class="badge-status badge-inactive" style="font-size:.65rem">
                                            <i class="fas fa-eye-slash" style="font-size:.6rem"></i> Đã ẩn
                                        </span>
                                    @else
                                        <span class="badge-status badge-active" style="font-size:.65rem">
                                            <i class="fas fa-eye" style="font-size:.6rem"></i> Hiển thị
                                        </span>
                                    @endif
                                    <span class="kv-grid-order">#{{ $kv->thu_tu_hien_thi ?? 0 }}</span>
                                </div>
                            </div>

                            {{-- Body --}}
                            <div class="kv-grid-body">
                                <div class="kv-grid-name">{{ $kv->ten_khu_vuc }}</div>

                                @if ($kv->mo_ta ?? null)
                                    <div class="kv-grid-desc">{{ Str::limit($kv->mo_ta, 70) }}</div>
                                @endif

                                <div class="kv-grid-stats">
                                    <span>
                                        <i class="fas fa-building" style="color:var(--blue)"></i>
                                        {{ $kv->so_du_an ?? 0 }} dự án
                                    </span>
                                    <span>
                                        <i class="fas fa-home" style="color:var(--primary)"></i>
                                        {{ number_format($kv->so_bds ?? 0) }} BĐS
                                    </span>
                                </div>

                                <div class="kv-grid-foot">
                                    <label class="toggle-sw">
                                        <input type="checkbox" {{ $kv->hien_thi ? 'checked' : '' }}
                                            data-toggle-url="{{ route('nhanvien.admin.khu-vuc.toggle-hien-thi', $kv) }}">
                                        <span class="toggle-sw-track"><span class="toggle-sw-thumb"></span></span>
                                    </label>
                                    <div class="btn-actions-group">
                                        <a href="{{ route('nhanvien.admin.khu-vuc.edit', $kv) }}"
                                            class="btn-action btn-action-edit" data-tip="Chỉnh sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn-action btn-action-delete" data-tip="Xóa"
                                            data-confirm-delete="{{ addslashes($kv->ten_khu_vuc) }}"
                                            data-form-id="del-kv-{{ $kv->id }}"
                                            @if (($kv->so_du_an ?? 0) > 0) onclick="event.preventDefault();showAdminToast('Không thể xóa khu vực đang có dự án!','warning')" @endif>
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            {{-- Grid pagination --}}
            @if ($khuVucs->hasPages())
                <div class="pagination-wrap mt-3">
                    <span class="pagi-info">
                        Trang <strong>{{ $khuVucs->currentPage() }}</strong>
                        / <strong>{{ $khuVucs->lastPage() }}</strong>
                    </span>
                    <nav>
                        <ul class="pagination pagination-sm mb-0">
                            <li class="page-item {{ $khuVucs->onFirstPage() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $khuVucs->previousPageUrl() }}">
                                    <i class="fas fa-chevron-left" style="font-size:.6rem"></i>
                                </a>
                            </li>
                            @foreach ($khuVucs->getUrlRange(max(1, $khuVucs->currentPage() - 2), min($khuVucs->lastPage(), $khuVucs->currentPage() + 2)) as $page => $url)
                                <li class="page-item {{ $page == $khuVucs->currentPage() ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                </li>
                            @endforeach
                            <li class="page-item {{ !$khuVucs->hasMorePages() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $khuVucs->nextPageUrl() }}">
                                    <i class="fas fa-chevron-right" style="font-size:.6rem"></i>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            @endif
        @else
            <div class="table-empty">
                <div class="table-empty-icon"><i class="fas fa-map-marked-alt"></i></div>
                <p>Chưa có khu vực nào để hiển thị</p>
            </div>
        @endif
    </div>

@endsection

@push('styles')
    <style>
        /* View toggle button */
        .view-toggle-btn {
            width: 32px;
            height: 32px;
            border: none;
            background: none;
            border-radius: var(--r-sm);
            color: var(--text-sub);
            cursor: pointer;
            font-size: .82rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all var(--t);
        }

        .view-toggle-btn.active {
            background: var(--primary);
            color: #fff;
        }

        .view-toggle-btn:not(.active):hover {
            background: var(--border);
            color: var(--navy);
        }

        /* Grid card */
        .kv-grid-card {
            background: var(--surface);
            border-radius: var(--r-lg);
            border: 1px solid var(--border);
            overflow: hidden;
            transition: transform var(--t), box-shadow var(--t);
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .kv-grid-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--sh-md);
        }

        .kv-grid-card--hidden {
            opacity: .7;
        }

        .kv-grid-img {
            position: relative;
            aspect-ratio: 16/9;
            overflow: hidden;
            background: linear-gradient(135deg, var(--navy-soft), var(--primary-soft));
            flex-shrink: 0;
        }

        .kv-grid-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .4s ease;
        }

        .kv-grid-card:hover .kv-grid-img img {
            transform: scale(1.05);
        }

        .kv-grid-img-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .kv-grid-img-placeholder i {
            font-size: 2.5rem;
            color: var(--primary);
            opacity: .4;
        }

        .kv-grid-overlay {
            position: absolute;
            top: 8px;
            left: 8px;
            right: 8px;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
        }

        .kv-grid-order {
            background: rgba(0, 0, 0, .6);
            color: #fff;
            font-size: .68rem;
            font-weight: 800;
            padding: .18rem .5rem;
            border-radius: 20px;
            backdrop-filter: blur(4px);
        }

        .kv-grid-body {
            padding: .9rem 1rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .kv-grid-name {
            font-weight: 800;
            font-size: .9rem;
            color: var(--navy);
            margin-bottom: .35rem;
        }

        .kv-grid-desc {
            font-size: .75rem;
            color: var(--text-sub);
            line-height: 1.5;
            flex: 1;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            margin-bottom: .7rem;
        }

        .kv-grid-stats {
            display: flex;
            gap: 1rem;
            font-size: .75rem;
            color: var(--text-sub);
            font-weight: 600;
            margin-bottom: .7rem;
            padding-top: .6rem;
            border-top: 1px solid var(--border);
        }

        .kv-grid-stats span {
            display: flex;
            align-items: center;
            gap: .35rem;
        }

        .kv-grid-foot {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
    </style>
@endpush

@push('scripts')
    <script>
        'use strict';

        /* ── View toggle ── */
        function setView(v) {
            localStorage.setItem('kvView', v);
            document.getElementById('viewTable').style.display = v === 'table' ? 'block' : 'none';
            document.getElementById('viewGrid').style.display = v === 'grid' ? 'block' : 'none';
            document.getElementById('btnViewTable').classList.toggle('active', v === 'table');
            document.getElementById('btnViewGrid').classList.toggle('active', v === 'grid');
        }
        (function() {
            const saved = localStorage.getItem('kvView') || 'table';
            setView(saved);
        })();

        /* ── Drag-to-reorder (SortableJS) ── */
        document.addEventListener('DOMContentLoaded', function() {
            var tbody = document.getElementById('kvSortable');
            if (!tbody || typeof Sortable === 'undefined') return;
            Sortable.create(tbody, {
                handle: '.fa-grip-vertical',
                animation: 150,
                ghostClass: 'sortable-ghost',
                onEnd: function() {
                    const ids = Array.from(tbody.querySelectorAll('.kv-row'))
                        .map(tr => tr.dataset.id);
                    ajaxPost('{{ route('nhanvien.admin.khu-vuc.reorder') }}', {
                            ids
                        },
                        () => showAdminToast('Đã lưu thứ tự!', 'success'),
                        () => showAdminToast('Không thể lưu thứ tự!', 'error')
                    );
                }
            });
        });
    </script>
@endpush
