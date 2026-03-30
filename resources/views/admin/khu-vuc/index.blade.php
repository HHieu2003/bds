@extends('admin.layouts.master')
@section('title', 'Quản lý Khu vực')

@section('content')
    {{-- ══ HEADER ══ --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h1 class="page-header-title mb-1"><i class="fas fa-map-marked-alt"></i> Khu vực</h1>
            <p class="page-header-sub mb-0">Quản lý danh mục địa lý Tỉnh <span class="dot"></span> Quận <span
                    class="dot"></span> Phường</p>
        </div>
        <a href="{{ route('nhanvien.admin.khu-vuc.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus me-1"></i> Thêm khu vực
        </a>
    </div>

    {{-- Flash Messages (Dùng Bootstrap Alert) --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
            <i class="fas fa-check-circle me-1"></i> {!! session('success') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert">
            <i class="fas fa-exclamation-circle me-1"></i> {!! session('error') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- ══ THỐNG KÊ (Dùng Bootstrap Grid + stat-card từ admin.css) ══ --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-4 col-xl-2">
            <div class="stat-card p-3">
                <div class="stat-icon navy bg-opacity-10"><i class="fas fa-map"></i></div>
                <div>
                    <div class="stat-num fs-4">{{ number_format($thongKe['tong']) }}</div>
                    <div class="stat-label">Tổng số</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <div class="stat-card p-3">
                <div class="stat-icon red bg-opacity-10"><i class="fas fa-city"></i></div>
                <div>
                    <div class="stat-num fs-4">{{ number_format($thongKe['tinh_thanh']) }}</div>
                    <div class="stat-label">Tỉnh / Thành</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
            <div class="stat-card p-3">
                <div class="stat-icon blue bg-opacity-10"><i class="fas fa-map-marked-alt"></i></div>
                <div>
                    <div class="stat-num fs-4">{{ number_format($thongKe['quan_huyen']) }}</div>
                    <div class="stat-label">Quận / Huyện</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
            <div class="stat-card p-3">
                <div class="stat-icon green bg-opacity-10"><i class="fas fa-map-pin"></i></div>
                <div>
                    <div class="stat-num fs-4">{{ number_format($thongKe['phuong_xa']) }}</div>
                    <div class="stat-label">Phường / Xã</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-8 col-xl-2">
            <div class="stat-card p-3">
                <div class="stat-icon orange bg-opacity-10"><i class="fas fa-eye"></i></div>
                <div>
                    <div class="stat-num fs-4">{{ number_format($thongKe['hien_thi']) }}</div>
                    <div class="stat-label">Đang hiển thị</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ══ BỘ LỌC (Dùng filter-box từ admin.css) ══ --}}
    <div class="filter-box mb-4">
        <form method="GET" id="filterForm">
            <div class="row g-2 align-items-center">
                <div class="col-12 col-md-3">
                    <input type="text" name="tim_kiem" class="filter-ctrl filter-ctrl-search w-100"
                        value="{{ request('tim_kiem') }}" placeholder="🔍 Tìm tên, mã, slug...">
                </div>
                <div class="col-6 col-md-2">
                    <select name="cap" class="filter-ctrl w-100 filter-auto-submit">
                        <option value="">Tất cả cấp</option>
                        @foreach (\App\Models\KhuVuc::CAP as $v => $info)
                            <option value="{{ $v }}" @selected(request('cap') == $v)>{{ $info['label'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <select name="cha_id" class="filter-ctrl w-100 filter-auto-submit">
                        <option value="">Tất cả tỉnh/thành</option>
                        @foreach ($tinhThanhs as $tinh)
                            <option value="{{ $tinh->id }}" @selected(request('cha_id') == $tinh->id)>{{ $tinh->ten_khu_vuc }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <select name="hien_thi" class="filter-ctrl w-100 filter-auto-submit">
                        <option value="">Trạng thái</option>
                        <option value="1" @selected(request('hien_thi') === '1')>Đang hiển thị</option>
                        <option value="0" @selected(request('hien_thi') === '0')>Đang ẩn</option>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <select name="sapxep" class="filter-ctrl w-100 filter-auto-submit">
                        <option value="thu_tu" @selected(request('sapxep', 'thu_tu') == 'thu_tu')>Thứ tự</option>
                        <option value="moi_nhat" @selected(request('sapxep') == 'moi_nhat')>Mới nhất</option>
                        <option value="ten_az" @selected(request('sapxep') == 'ten_az')>Tên A-Z</option>
                    </select>
                </div>
                <div class="col-12 col-md-auto ms-auto d-flex gap-2">
                    <button type="submit" class="btn btn-navy"><i class="fas fa-search"></i> Lọc</button>
                    @if (request()->hasAny(['tim_kiem', 'cap', 'cha_id', 'hien_thi', 'sapxep']))
                        <a href="{{ route('nhanvien.admin.khu-vuc.index') }}" class="btn btn-danger"><i
                                class="fas fa-times"></i></a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    {{-- ══ BẢNG DỮ LIỆU & MOBILE CARD ══ --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
            <span class="text-muted fw-normal" style="font-size: 0.85rem">
                @if ($khuVucs->total() > 0)
                    Đang hiển thị <strong
                        class="text-dark">{{ $khuVucs->firstItem() }}–{{ $khuVucs->lastItem() }}</strong> trên <strong
                        class="text-dark">{{ number_format($khuVucs->total()) }}</strong>
                @else
                    Không tìm thấy dữ liệu
                @endif
            </span>
            <div class="d-flex gap-2">
                <a href="{{ route('nhanvien.admin.khu-vuc.create', ['cap' => 'tinh_thanh']) }}"
                    class="badge bg-danger bg-opacity-10 text-danger text-decoration-none border border-danger-subtle p-2"><i
                        class="fas fa-city"></i> + Tỉnh/TP</a>
                <a href="{{ route('nhanvien.admin.khu-vuc.create', ['cap' => 'quan_huyen']) }}"
                    class="badge bg-primary bg-opacity-10 text-primary text-decoration-none border border-primary-subtle p-2"><i
                        class="fas fa-map-marked-alt"></i> + Quận/Huyện</a>
                <a href="{{ route('nhanvien.admin.khu-vuc.create', ['cap' => 'phuong_xa']) }}"
                    class="badge bg-success bg-opacity-10 text-success text-decoration-none border border-success-subtle p-2"><i
                        class="fas fa-map-pin"></i> + Phường/Xã</a>
            </div>
        </div>

        {{-- Bảng chỉ hiện trên màn hình lớn (PC/Tablet) --}}
        <div class="table-responsive table-wrap tbl-desktop">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="text-center" style="width: 50px">#</th>
                        <th>Khu vực</th>
                        <th>Cấp bậc</th>
                        <th>Khu vực cha</th>
                        <th class="text-center">Khu vực con</th>
                        <th class="text-center">Dự án</th>
                        <th class="text-center">Thứ tự</th>
                        <th class="text-center">Hiển thị</th>
                        <th class="text-center" style="width: 120px">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($khuVucs as $kv)
                        @php
                            $capInfo = \App\Models\KhuVuc::CAP[$kv->cap_khu_vuc] ?? [
                                'label' => $kv->cap_khu_vuc,
                                'color' => '#6c757d',
                                'bg' => '#f8f9fa',
                                'icon' => 'fas fa-map',
                            ];
                            $capOrder = $capInfo['order'] ?? 1;
                        @endphp
                        <tr>
                            <td class="text-center text-muted">{{ $khuVucs->firstItem() + $loop->index }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if ($kv->cap_khu_vuc !== 'tinh_thanh')
                                        <div
                                            style="width: {{ ($capOrder - 1) * 1.5 }}rem; text-align: right; padding-right: 0.5rem">
                                            <i class="fas fa-level-up-alt text-muted"
                                                style="transform: rotate(90deg); opacity: 0.5; font-size: 0.8rem"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <a href="{{ route('nhanvien.admin.khu-vuc.edit', $kv) }}"
                                            class="fw-bold text-dark text-decoration-none hover-primary">{{ $kv->ten_khu_vuc }}</a>
                                        <div class="text-muted" style="font-size: 0.75rem">
                                            /{{ $kv->slug }}
                                            @if ($kv->seo_title)
                                                <span class="badge bg-success bg-opacity-10 text-success ms-1"><i
                                                        class="fas fa-check"></i> SEO</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge"
                                    style="color:{{ $capInfo['color'] }};background:{{ $capInfo['bg'] }}">
                                    <i class="{{ $capInfo['icon'] }} me-1"></i>{{ $capInfo['label'] }}
                                </span>
                            </td>
                            <td>
                                @if ($kv->cha)
                                    @if ($kv->cha->cha)
                                        <div class="text-muted" style="font-size: 0.7rem">
                                            {{ $kv->cha->cha->ten_khu_vuc }} ›</div>
                                    @endif
                                    <div class="fw-semibold text-secondary" style="font-size: 0.85rem">
                                        {{ $kv->cha->ten_khu_vuc }}</div>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($kv->con_count > 0)
                                    <a href="{{ route('nhanvien.admin.khu-vuc.index', ['cha_id' => $kv->id]) }}"
                                        class="badge bg-primary bg-opacity-10 text-primary text-decoration-none p-2">{{ $kv->con_count }}</a>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="text-center"><span
                                    class="fw-bold text-warning">{{ $kv->du_an_count ?? '0' }}</span></td>
                            <td class="text-center"><span
                                    class="badge bg-light text-dark border">{{ $kv->thu_tu_hien_thi }}</span></td>
                            <td class="text-center">
                                <label class="toggle-sw">
                                    <input type="checkbox"
                                        data-toggle-url="/nhan-vien/admin/khu-vuc/{{ $kv->id }}/toggle"
                                        {{ $kv->hien_thi ? 'checked' : '' }}>
                                    <span class="toggle-sw-track"><span class="toggle-sw-thumb"></span></span>
                                </label>
                            </td>
                            <td class="text-center">
                                <div class="btn-actions-group justify-content-center">
                                    <a href="{{ route('nhanvien.admin.khu-vuc.show', $kv) }}"
                                        class="btn-action btn-action-view" data-bs-toggle="tooltip" title="Xem"><i
                                            class="fas fa-eye"></i></a>
                                    <a href="{{ route('nhanvien.admin.khu-vuc.edit', $kv) }}"
                                        class="btn-action btn-action-edit" data-bs-toggle="tooltip" title="Sửa"><i
                                            class="fas fa-pen"></i></a>

                                    @if ($kv->cap_khu_vuc !== 'phuong_xa')
                                        <a href="{{ route('nhanvien.admin.khu-vuc.create', ['cap' => $kv->cap_khu_vuc === 'tinh_thanh' ? 'quan_huyen' : 'phuong_xa', 'cha_id' => $kv->id]) }}"
                                            class="btn-action btn-action-warn" data-bs-toggle="tooltip"
                                            title="Thêm cấp con"><i class="fas fa-plus"></i></a>
                                    @endif

                                    <form id="frmDel_{{ $kv->id }}"
                                        action="{{ route('nhanvien.admin.khu-vuc.destroy', $kv) }}" method="POST"
                                        class="d-none">
                                        @csrf @method('DELETE')
                                    </form>
                                    <button type="button" class="btn-action btn-action-delete btn-delete-kv"
                                        data-id="{{ $kv->id }}" data-name="{{ $kv->ten_khu_vuc }}"
                                        data-con="{{ $kv->con_count }}" data-bs-toggle="tooltip" title="Xóa"><i
                                            class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9">
                                <div class="empty-state">
                                    <i class="fas fa-map-marked-alt text-muted mb-3"></i>
                                    <p class="text-muted mb-2">Chưa có dữ liệu khu vực nào</p>
                                    <a href="{{ route('nhanvien.admin.khu-vuc.create') }}"
                                        class="btn btn-sm btn-outline-primary">Thêm khu vực đầu tiên</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Danh sách dạng Card chỉ hiển thị trên Điện Thoại (< 640px) --}}
        <div class="mobile-card-list">
            @forelse($khuVucs as $kv)
                @php
                    $capInfo = \App\Models\KhuVuc::CAP[$kv->cap_khu_vuc] ?? [
                        'label' => $kv->cap_khu_vuc,
                        'color' => '#6c757d',
                        'bg' => '#f8f9fa',
                        'icon' => 'fas fa-map',
                    ];
                @endphp
                <div class="mobile-card">
                    <div class="mobile-card-top">
                        <div style="flex: 1; min-width: 0;">
                            <a href="{{ route('nhanvien.admin.khu-vuc.edit', $kv) }}"
                                class="fw-bold text-dark text-decoration-none d-block text-truncate">{{ $kv->ten_khu_vuc }}</a>
                            <div class="text-muted text-truncate" style="font-size: 0.75rem">/{{ $kv->slug }}</div>
                        </div>
                        <span class="badge" style="color:{{ $capInfo['color'] }};background:{{ $capInfo['bg'] }}">
                            <i class="{{ $capInfo['icon'] }} me-1"></i>{{ $capInfo['label'] }}
                        </span>
                    </div>
                    <div class="mobile-card-meta">
                        <div><i class="fas fa-sitemap"></i> Cha: <span
                                class="fw-semibold text-secondary">{{ $kv->cha ? $kv->cha->ten_khu_vuc : '—' }}</span>
                        </div>
                        @if ($kv->con_count > 0)
                            <div><i class="fas fa-layer-group"></i> Con: <span
                                    class="fw-bold text-primary">{{ $kv->con_count }}</span></div>
                        @endif
                        <div><i class="fas fa-building"></i> Dự án: <span
                                class="fw-bold text-warning">{{ $kv->du_an_count ?? 0 }}</span></div>
                    </div>
                    <div class="mobile-card-foot">
                        <label class="toggle-sw">
                            <input type="checkbox" data-toggle-url="/nhan-vien/admin/khu-vuc/{{ $kv->id }}/toggle"
                                {{ $kv->hien_thi ? 'checked' : '' }}>
                            <span class="toggle-sw-track"><span class="toggle-sw-thumb"></span></span>
                        </label>
                        <div class="btn-actions-group">
                            <a href="{{ route('nhanvien.admin.khu-vuc.show', $kv) }}"
                                class="btn-action btn-action-view"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('nhanvien.admin.khu-vuc.edit', $kv) }}"
                                class="btn-action btn-action-edit"><i class="fas fa-pen"></i></a>

                            @if ($kv->cap_khu_vuc !== 'phuong_xa')
                                <a href="{{ route('nhanvien.admin.khu-vuc.create', ['cap' => $kv->cap_khu_vuc === 'tinh_thanh' ? 'quan_huyen' : 'phuong_xa', 'cha_id' => $kv->id]) }}"
                                    class="btn-action btn-action-warn"><i class="fas fa-plus"></i></a>
                            @endif
                            <button type="button" class="btn-action btn-action-delete btn-delete-kv"
                                data-id="{{ $kv->id }}" data-name="{{ $kv->ten_khu_vuc }}"
                                data-con="{{ $kv->con_count }}"><i class="fas fa-trash"></i></button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <i class="fas fa-map-marked-alt text-muted mb-3"></i>
                    <p class="text-muted mb-2">Chưa có dữ liệu khu vực nào</p>
                </div>
            @endforelse
        </div>

        {{-- Phân trang Bootstrap --}}
        @if ($khuVucs->hasPages())
            <div class="card-footer bg-white border-top p-3 d-flex justify-content-center justify-content-md-end">
                {{ $khuVucs->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Khởi tạo Tooltip Bootstrap (Chỉ chạy khi không phải mobile để tránh vướng víu)
            if (window.innerWidth > 640) {
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            }

            // Tích hợp confirmDelete từ admin.js
            document.querySelectorAll('.btn-delete-kv').forEach(btn => {
                btn.addEventListener('click', function() {
                    const ten = this.dataset.name;
                    const con = parseInt(this.dataset.con || 0);
                    const id = this.dataset.id;

                    if (con > 0) {
                        showAdminToast('Không thể xóa "' + ten + '" vì còn ' + con +
                            ' khu vực con!', 'error');
                        return;
                    }

                    // Gọi hàm từ admin.js
                    confirmDelete('khu vực ' + ten, function() {
                        document.getElementById('frmDel_' + id).submit();
                    });
                });
            });
        });
    </script>
@endpush
