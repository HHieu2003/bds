{{-- resources/views/admin/bai-viet/index.blade.php --}}
@extends('admin.layouts.master')
@section('title', 'Quản lý bài viết')

@section('content')

    {{-- ── PAGE HEADER ── --}}
    <div class="d-flex align-items-start justify-content-between gap-3 mb-4 flex-wrap">
        <div>
            <h1 class="fw-black mb-1" style="font-size:1.3rem;color:var(--navy)">
                <i class="fas fa-newspaper me-2" style="color:var(--primary)"></i>Quản lý bài viết
            </h1>
            <div class="d-flex align-items-center gap-2 flex-wrap" style="font-size:.78rem;color:var(--text-sub)">
                <span>{{ $thongKe['tong'] }} bài viết</span>
                <span
                    style="width:4px;height:4px;border-radius:50%;background:var(--text-muted);display:inline-block"></span>
                <span style="color:var(--green)">{{ $thongKe['hien_thi'] }} hiển thị</span>
                <span
                    style="width:4px;height:4px;border-radius:50%;background:var(--text-muted);display:inline-block"></span>
                <span style="color:var(--text-muted)">{{ $thongKe['an'] }} đã ẩn</span>
            </div>
        </div>
        <a href="{{ route('nhanvien.admin.bai-viet.create') }}"
            class="btn btn-primary d-flex align-items-center gap-2 flex-shrink-0">
            <i class="fas fa-plus"></i>
            <span class="d-none d-sm-inline">Viết bài mới</span>
        </a>
    </div>

    {{-- ── STAT CARDS ── --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-sm-3">
            <div class="stat-card">
                <div class="stat-icon navy"><i class="fas fa-newspaper"></i></div>
                <div>
                    <div class="stat-num">{{ $thongKe['tong'] }}</div>
                    <div class="stat-label">Tổng bài viết</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-sm-3">
            <div class="stat-card">
                <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
                <div>
                    <div class="stat-num">{{ $thongKe['hien_thi'] }}</div>
                    <div class="stat-label">Hiển thị</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-sm-3">
            <div class="stat-card">
                <div class="stat-icon yellow"><i class="fas fa-eye-slash"></i></div>
                <div>
                    <div class="stat-num">{{ $thongKe['an'] }}</div>
                    <div class="stat-label">Đã ẩn</div>
                </div>
            </div>
        </div>

        <div class="col-6 col-sm-3">
            <div class="stat-card">
                <div class="stat-icon yellow"><i class="fas fa-eye"></i></div>
                <div>
                    <div class="stat-num">{{ number_format($thongKe['tong_luot_xem'] ?? 0) }}</div>
                    <div class="stat-label">Tổng lượt xem</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── FILTER ── --}}
    <div class="filter-box mb-3">
        <form method="GET" action="{{ route('nhanvien.admin.bai-viet.index') }}" id="filterForm">
            <div class="filter-box-row">
                <input type="text" name="tukhoa" class="filter-ctrl filter-ctrl-search search-debounce"
                    value="{{ request('tukhoa') }}" placeholder="Tìm tiêu đề, slug..." style="min-width:200px;flex:1">

                <select name="trang_thai" class="filter-ctrl filter-auto-submit">
                    <option value="">Tất cả trạng thái (gồm cả đã ẩn)</option>
                    <option value="hien_thi" {{ request('trang_thai') == 'hien_thi' ? 'selected' : '' }}>Hiển thị
                    </option>
                    <option value="an" {{ request('trang_thai') == 'an' ? 'selected' : '' }}>Đã ẩn</option>
                </select>

                <select name="sapxep" class="filter-ctrl filter-auto-submit">
                    <option value="moi_nhat" {{ request('sapxep', 'moi_nhat') == 'moi_nhat' ? 'selected' : '' }}>Mới nhất
                    </option>
                    <option value="cu_nhat" {{ request('sapxep') == 'cu_nhat' ? 'selected' : '' }}>Cũ nhất</option>
                    <option value="nhieu_xem" {{ request('sapxep') == 'nhieu_xem' ? 'selected' : '' }}>Nhiều lượt xem
                    </option>
                    <option value="tieu_de_az" {{ request('sapxep') == 'tieu_de_az' ? 'selected' : '' }}>Tiêu đề A–Z
                    </option>
                </select>

                @if (request()->hasAny(['tukhoa', 'trang_thai', 'sapxep']))
                    <a href="{{ route('nhanvien.admin.bai-viet.index') }}"
                        class="btn btn-secondary btn-sm d-flex align-items-center gap-1">
                        <i class="fas fa-times"></i> Xóa lọc
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- ── BULK ACTION BAR ── --}}
    <div id="bulkActionBar" style="display:none" class="d-flex align-items-center gap-3 mb-3 px-3 py-2 rounded-2"
        style="background:var(--navy-soft);border:1px solid var(--blue)">
        <i class="fas fa-check-square" style="color:var(--blue)"></i>
        <span class="bulk-count fw-bold" style="font-size:.83rem;color:var(--navy)">0 mục được chọn</span>
        <div class="d-flex gap-2 ms-auto">
            <button type="button" class="btn btn-sm btn-navy d-flex align-items-center gap-1" onclick="bulkPublish()">
                <i class="fas fa-check-circle"></i> Đăng tất cả
            </button>
            <button type="button" class="btn btn-sm btn-danger d-flex align-items-center gap-1" onclick="bulkDelete()">
                <i class="fas fa-trash"></i> Xóa tất cả
            </button>
        </div>
    </div>

    {{-- ── TABLE ── --}}
    <div class="card">
        <div class="card-header">
            <span class="d-flex align-items-center gap-2">
                <i class="fas fa-list"></i> Danh sách bài viết
            </span>
            @if ($baiViets->total())
                <span class="result-info">
                    Hiển thị <strong>{{ $baiViets->firstItem() }}–{{ $baiViets->lastItem() }}</strong>
                    / <strong>{{ $baiViets->total() }}</strong>
                </span>
            @endif
        </div>

        {{-- Desktop Table --}}
        <div class="table-wrap tbl-desktop">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width:40px">
                            <input type="checkbox" class="form-check-input" id="selectAll"
                                onchange="initSelectAll('selectAll','row-cb') || document.getElementById('selectAll').dispatchEvent(new Event('change'))">
                        </th>
                        <th>Bài viết</th>
                        <th style="width:110px">Trạng thái</th>
                        <th style="width:80px;text-align:center">Nổi bật</th>
                        <th style="width:80px;text-align:center">Lượt xem</th>
                        <th style="width:110px">Ngày đăng</th>
                        <th style="width:110px;text-align:center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($baiViets as $bv)
                        @php
                            $badgeClass = match ($bv->trang_thai) {
                                'hien_thi' => 'badge-active',
                                'an' => 'badge-inactive',
                                default => 'badge-pending',
                            };
                            $badgeText = match ($bv->trang_thai) {
                                'hien_thi' => 'Hiển thị',
                                'an' => 'Đã ẩn',
                                default => $bv->trang_thai,
                            };
                            $badgeIcon = match ($bv->trang_thai) {
                                'hien_thi' => 'fa-check-circle',
                                'an' => 'fa-eye-slash',
                                default => 'fa-circle',
                            };
                        @endphp
                        <tr>
                            {{-- Checkbox --}}
                            <td>
                                <input type="checkbox" class="form-check-input row-cb" value="{{ $bv->id }}">
                            </td>

                            {{-- Bài viết --}}
                            <td>
                                <div class="d-flex align-items-start gap-3">
                                    <div style="flex-shrink:0">
                                        @if ($bv->anh_dai_dien)
                                            <img src="{{ asset('storage/' . $bv->anh_dai_dien) }}"
                                                alt="{{ $bv->tieu_de }}" class="table-thumb"
                                                style="width:64px;height:48px" onerror="this.style.display='none'">
                                        @else
                                            <div class="table-thumb d-flex align-items-center justify-content-center"
                                                style="width:64px;height:48px;background:var(--bg)">
                                                <i class="fas fa-image"
                                                    style="color:var(--text-muted);font-size:.9rem"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div style="min-width:0;flex:1">
                                        <div class="d-flex align-items-start gap-2 flex-wrap">
                                            <a href="{{ route('nhanvien.admin.bai-viet.edit', $bv) }}"
                                                class="fw-bold text-decoration-none"
                                                style="font-size:.84rem;color:var(--navy);line-height:1.35;transition:color .2s"
                                                onmouseover="this.style.color='var(--primary)'"
                                                onmouseout="this.style.color='var(--navy)'">
                                                {{ Str::limit($bv->tieu_de, 60) }}
                                            </a>
                                            @if ($bv->noi_bat)
                                                <span class="badge-status badge-new"
                                                    style="font-size:.62rem;padding:.12rem .5rem">
                                                    <i class="fas fa-star" style="font-size:.55rem"></i> Nổi bật
                                                </span>
                                            @endif
                                        </div>
                                        <div class="d-flex align-items-center gap-1 mt-1"
                                            style="font-size:.71rem;color:var(--text-muted)">
                                            <i class="fas fa-link" style="font-size:.6rem"></i>
                                            <span
                                                style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:220px">
                                                /{{ $bv->slug }}
                                            </span>
                                            <button type="button" class="btn-action btn-action-view"
                                                style="width:20px;height:20px;font-size:.6rem" data-tip="Sao chép slug"
                                                onclick="copyToClipboard('{{ $bv->slug }}','Đã sao chép slug!')">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </div>
                                        @if ($bv->tacGia)
                                            <div class="d-flex align-items-center gap-1 mt-1"
                                                style="font-size:.71rem;color:var(--text-sub)">
                                                <img src="{{ $bv->tacGia->anh_dai_dien_url }}" class="avatar avatar-sm"
                                                    style="width:16px;height:16px;border-width:1px"
                                                    onerror="this.style.display='none'">
                                                <span>{{ $bv->tacGia->ho_ten }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            {{-- Trạng thái --}}
                            <td>
                                <button type="button" class="badge-status {{ $badgeClass }}"
                                    data-status-toggle-url="{{ route('nhanvien.admin.bai-viet.toggle-hien-thi', $bv) }}"
                                    data-current="{{ $bv->hien_thi ? '1' : '0' }}" onclick="toggleBaiVietStatus(this)"
                                    style="border:none;cursor:pointer">
                                    <i class="fas {{ $badgeIcon }}" style="font-size:.6rem"></i>
                                    {{ $badgeText }}
                                </button>
                            </td>

                            {{-- Nổi bật toggle --}}
                            <td class="text-center">
                                <label class="toggle-sw" data-tip="{{ $bv->noi_bat ? 'Bỏ nổi bật' : 'Đặt nổi bật' }}">
                                    <input type="checkbox" {{ $bv->noi_bat ? 'checked' : '' }}
                                        data-toggle-url="{{ route('nhanvien.admin.bai-viet.toggle-noi-bat', $bv) }}">
                                    <span class="toggle-sw-track"><span class="toggle-sw-thumb"></span></span>
                                </label>
                            </td>

                            {{-- Lượt xem --}}
                            <td class="text-center">
                                <span class="count-chip count-chip-orange" style="font-size:.73rem">
                                    <i class="fas fa-eye me-1" style="font-size:.6rem"></i>
                                    {{ number_format($bv->luot_xem ?? 0) }}
                                </span>
                            </td>

                            {{-- Ngày --}}
                            <td>
                                @if ($bv->ngay_dang)
                                    <div style="font-size:.79rem;font-weight:700;color:var(--navy)">
                                        {{ $bv->ngay_dang->format('d/m/Y') }}
                                    </div>
                                    <div style="font-size:.7rem;color:var(--text-muted)">
                                        {{ $bv->ngay_dang->diffForHumans() }}
                                    </div>
                                @else
                                    <span style="font-size:.76rem;color:var(--text-muted);font-style:italic">Chưa
                                        đăng</span>
                                @endif
                            </td>

                            {{-- Thao tác --}}
                            <td>
                                <div class="btn-actions-group justify-content-center">
                                    @if ($bv->trang_thai === 'hien_thi')
                                        <a href="{{ route('frontend.tin-tuc.show', $bv->slug) }}" target="_blank"
                                            class="btn-action btn-action-view" data-tip="Xem trực tiếp">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                    @endif
                                    <a href="{{ route('nhanvien.admin.bai-viet.edit', $bv) }}"
                                        class="btn-action btn-action-edit" data-tip="Chỉnh sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form id="del-bv-{{ $bv->id }}" method="POST"
                                        action="{{ route('nhanvien.admin.bai-viet.destroy', $bv) }}"
                                        style="display:none">
                                        @csrf @method('DELETE')
                                    </form>
                                    <button type="button" class="btn-action btn-action-delete" data-tip="Xóa bài viết"
                                        data-confirm-delete="{{ addslashes($bv->tieu_de) }}"
                                        data-form-id="del-bv-{{ $bv->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="table-empty">
                                    <div class="table-empty-icon">
                                        <i class="fas fa-newspaper"></i>
                                    </div>
                                    <p>Chưa có bài viết nào</p>
                                    @if (request()->hasAny(['tukhoa', 'trang_thai']))
                                        <a href="{{ route('nhanvien.admin.bai-viet.index') }}">
                                            <i class="fas fa-times me-1"></i>Xóa bộ lọc
                                        </a>
                                    @else
                                        <a href="{{ route('nhanvien.admin.bai-viet.create') }}"
                                            class="btn btn-primary btn-sm mt-2">
                                            <i class="fas fa-plus me-1"></i>Viết bài đầu tiên
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
            @forelse($baiViets as $bv)
                @php
                    $badgeClass = match ($bv->trang_thai) {
                        'hien_thi' => 'badge-active',
                        'an' => 'badge-inactive',
                        default => 'badge-pending',
                    };
                    $badgeText = match ($bv->trang_thai) {
                        'hien_thi' => 'Hiển thị',
                        'an' => 'Đã ẩn',
                        default => $bv->trang_thai,
                    };
                @endphp
                <div class="mobile-card">
                    <div class="mobile-card-top">
                        <div class="d-flex align-items-start gap-3 flex-1" style="min-width:0">
                            @if ($bv->anh_dai_dien)
                                <img src="{{ asset('storage/' . $bv->anh_dai_dien) }}" alt="{{ $bv->tieu_de }}"
                                    class="table-thumb flex-shrink-0" style="width:56px;height:42px">
                            @endif
                            <div style="min-width:0;flex:1">
                                <div class="fw-bold"
                                    style="font-size:.84rem;color:var(--navy);overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                                    {{ $bv->tieu_de }}
                                </div>
                                <div
                                    style="font-size:.71rem;color:var(--text-muted);overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                                    /{{ $bv->slug }}
                                </div>
                            </div>
                        </div>
                        <button type="button" class="badge-status {{ $badgeClass }} flex-shrink-0"
                            data-status-toggle-url="{{ route('nhanvien.admin.bai-viet.toggle-hien-thi', $bv) }}"
                            data-current="{{ $bv->hien_thi ? '1' : '0' }}" onclick="toggleBaiVietStatus(this)"
                            style="border:none;cursor:pointer">{{ $badgeText }}</button>
                    </div>

                    <div class="mobile-card-meta">
                        <span><i class="fas fa-eye"></i> {{ number_format($bv->luot_xem ?? 0) }} lượt xem</span>
                        @if ($bv->ngay_dang)
                            <span><i class="fas fa-calendar"></i> {{ $bv->ngay_dang->format('d/m/Y') }}</span>
                        @endif
                        @if ($bv->noi_bat)
                            <span style="color:var(--primary)"><i class="fas fa-star"></i> Nổi bật</span>
                        @endif
                    </div>

                    <div class="mobile-card-foot">
                        <label class="toggle-sw">
                            <input type="checkbox" {{ $bv->noi_bat ? 'checked' : '' }}
                                data-toggle-url="{{ route('nhanvien.admin.bai-viet.toggle-noi-bat', $bv) }}">
                            <span class="toggle-sw-track"><span class="toggle-sw-thumb"></span></span>
                        </label>
                        <div class="btn-actions-group">
                            <a href="{{ route('nhanvien.admin.bai-viet.edit', $bv) }}"
                                class="btn-action btn-action-edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" class="btn-action btn-action-delete"
                                data-confirm-delete="{{ addslashes($bv->tieu_de) }}"
                                data-form-id="del-bv-{{ $bv->id }}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="table-empty">
                    <div class="table-empty-icon"><i class="fas fa-newspaper"></i></div>
                    <p>Chưa có bài viết nào</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if ($baiViets->hasPages())
            <div class="pagination-wrap">
                <span class="pagi-info">
                    Trang <strong>{{ $baiViets->currentPage() }}</strong>
                    / <strong>{{ $baiViets->lastPage() }}</strong>
                    &nbsp;·&nbsp; Tổng <strong>{{ $baiViets->total() }}</strong> bài
                </span>
                <nav>
                    <ul class="pagination pagination-sm mb-0">
                        <li class="page-item {{ $baiViets->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $baiViets->previousPageUrl() }}">
                                <i class="fas fa-chevron-left" style="font-size:.6rem"></i>
                            </a>
                        </li>
                        @foreach ($baiViets->getUrlRange(max(1, $baiViets->currentPage() - 2), min($baiViets->lastPage(), $baiViets->currentPage() + 2)) as $page => $url)
                            <li class="page-item {{ $page == $baiViets->currentPage() ? 'active' : '' }}">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endforeach
                        <li class="page-item {{ !$baiViets->hasMorePages() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $baiViets->nextPageUrl() }}">
                                <i class="fas fa-chevron-right" style="font-size:.6rem"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        @endif
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            initSelectAll('selectAll', 'row-cb');
        });

        function renderBaiVietStatusBadge(el, isHienThi) {
            el.dataset.current = isHienThi ? '1' : '0';

            el.classList.remove('badge-active', 'badge-inactive', 'badge-pending');
            el.classList.add(isHienThi ? 'badge-active' : 'badge-inactive');

            const icon = isHienThi ? 'fa-check-circle' : 'fa-eye-slash';
            const text = isHienThi ? 'Hiển thị' : 'Đã ẩn';

            el.innerHTML = `<i class="fas ${icon}" style="font-size:.6rem"></i> ${text}`;
        }

        function toggleBaiVietStatus(el) {
            const url = el.dataset.statusToggleUrl;
            if (!url) return;

            el.disabled = true;

            fetch(url, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                        'Accept': 'application/json'
                    }
                })
                .then(r => r.json())
                .then(data => {
                    if (!data.ok) {
                        showAdminToast('Không thể cập nhật trạng thái!', 'error');
                        return;
                    }

                    const isHienThi = !!data.hien_thi;

                    // Cập nhật tất cả badge cùng bài trong trang (desktop + mobile)
                    const selector = `[data-status-toggle-url="${url}"]`;
                    document.querySelectorAll(selector).forEach((badge) => renderBaiVietStatusBadge(badge,
                        isHienThi));

                    showAdminToast(isHienThi ? 'Đã chuyển sang Hiển thị' : 'Đã chuyển sang Ẩn', 'success');
                })
                .catch(() => showAdminToast('Lỗi kết nối!', 'error'))
                .finally(() => {
                    el.disabled = false;
                });
        }

        function bulkPublish() {
            const ids = getSelectedIds('row-cb');
            if (!ids.length) {
                showAdminToast('Chưa chọn bài viết nào!', 'warning');
                return;
            }
            if (!confirm(`Đăng ${ids.length} bài viết đã chọn?`)) return;
            ajaxPost('{{ route('nhanvien.admin.bai-viet.bulk-action') }}', {
                    action: 'publish',
                    ids
                },
                () => {
                    showAdminToast('Đã đăng thành công!', 'success');
                    setTimeout(() => location.reload(), 1200);
                }
            );
        }

        function bulkDelete() {
            const ids = getSelectedIds('row-cb');
            if (!ids.length) {
                showAdminToast('Chưa chọn bài viết nào!', 'warning');
                return;
            }
            confirmDelete(`${ids.length} bài viết đã chọn`, () => {
                ajaxPost('{{ route('nhanvien.admin.bai-viet.bulk-action') }}', {
                        action: 'delete',
                        ids
                    },
                    () => {
                        showAdminToast('Đã xóa!', 'success');
                        setTimeout(() => location.reload(), 1200);
                    }
                );
            }, 'Toàn bộ bài đã chọn sẽ bị xóa vĩnh viễn!');
        }
    </script>
@endpush
