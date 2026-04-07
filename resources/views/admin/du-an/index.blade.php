@extends('admin.layouts.master')
@section('title', 'Quản lý Dự án')

@section('content')
    {{-- ══ HEADER ══ --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h1 class="page-header-title mb-1"><i class="fas fa-city"></i> Quản lý Dự án</h1>
            <p class="page-header-sub mb-0">Toàn bộ dự án bất động sản trên hệ thống</p>
        </div>
        <a href="{{ route('nhanvien.admin.du-an.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus me-1"></i> Thêm dự án mới
        </a>
    </div>

    {{-- Flash Messages --}}
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

    {{-- ══ THỐNG KÊ ══ --}}
    @php
        $tongHienThi = \App\Models\DuAn::whereNull('deleted_at')->where('hien_thi', 1)->count();
        $tongCanBan = $duAns->getCollection()->sum('so_can_ban');
        $tongCanThue = $duAns->getCollection()->sum('so_can_thue');
    @endphp
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card p-3">
                <div class="stat-icon orange bg-opacity-10"><i class="fas fa-city"></i></div>
                <div>
                    <div class="stat-num fs-4">{{ $duAns->total() }}</div>
                    <div class="stat-label">Tổng dự án</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card p-3">
                <div class="stat-icon green bg-opacity-10"><i class="fas fa-eye"></i></div>
                <div>
                    <div class="stat-num fs-4">{{ $tongHienThi }}</div>
                    <div class="stat-label">Đang hiển thị</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card p-3">
                <div class="stat-icon blue bg-opacity-10"><i class="fas fa-tag"></i></div>
                <div>
                    <div class="stat-num fs-4">{{ number_format($tongCanBan) }}</div>
                    <div class="stat-label">Căn đang bán</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card p-3">
                <div class="stat-icon purple bg-opacity-10"><i class="fas fa-key"></i></div>
                <div>
                    <div class="stat-num fs-4">{{ number_format($tongCanThue) }}</div>
                    <div class="stat-label">Căn đang thuê</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ══ BỘ LỌC ══ --}}
    <div class="filter-box mb-4">
        <form method="GET" action="{{ route('nhanvien.admin.du-an.index') }}" id="filterForm">
            <div class="row g-2 align-items-center">
                <div class="col-12 col-md-4">
                    <input type="text" name="tukhoa" class="filter-ctrl filter-ctrl-search w-100"
                        value="{{ request('tukhoa') }}" placeholder="🔍 Tìm tên dự án, địa chỉ...">
                </div>
                <div class="col-6 col-md-3">
                    <select name="khu_vuc_id" class="filter-ctrl w-100 filter-auto-submit">
                        <option value="">📍 Tất cả khu vực</option>
                        @foreach ($khuVucs as $kv)
                            <option value="{{ $kv->id }}" @selected(request('khu_vuc_id') == $kv->id)>{{ $kv->ten_khu_vuc }}</option>
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
                <div class="col-12 col-md-auto ms-auto d-flex gap-2">
                    <button type="submit" class="btn btn-navy"><i class="fas fa-filter"></i> Lọc</button>
                    @if (request()->hasAny(['tukhoa', 'khu_vuc_id', 'hien_thi']))
                        <a href="{{ route('nhanvien.admin.du-an.index') }}" class="btn btn-danger"><i
                                class="fas fa-times"></i></a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    {{-- ══ BẢNG DỮ LIỆU & MOBILE CARD ══ --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h3 class="card-title m-0 fs-6 fw-bold text-navy"><i class="fas fa-list text-primary me-2"></i>Danh sách dự án
            </h3>
            <span class="badge bg-primary bg-opacity-10 text-primary">{{ $duAns->total() }} dự án</span>
        </div>

        {{-- Bảng Desktop --}}
        <div class="table-responsive table-wrap tbl-desktop">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="text-center" style="width: 50px">#</th>
                        <th style="width: 80px">Ảnh</th>
                        <th>Tên dự án / Địa chỉ</th>
                        <th>Khu vực</th>
                        <th>Chủ đầu tư</th>
                        <th class="text-center">Nguồn hàng BĐS</th>
                        <th class="text-center">Thứ tự</th>
                        <th class="text-center">Hiển thị</th>
                        <th class="text-center" style="width: 120px">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($duAns as $i => $da)
                        <tr>
                            <td class="text-center text-muted">
                                {{ ($duAns->currentPage() - 1) * $duAns->perPage() + $i + 1 }}</td>
                            <td>
                                @if ($da->hinh_anh_dai_dien)
                                    <img src="{{ asset('storage/' . $da->hinh_anh_dai_dien) }}" class="table-thumb"
                                        alt="{{ $da->ten_du_an }}">
                                @else
                                    <div
                                        class="table-thumb d-flex align-items-center justify-content-center bg-light text-muted">
                                        <i class="fas fa-image"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('nhanvien.admin.du-an.edit', $da) }}"
                                    class="fw-bold text-navy text-decoration-none d-block mb-1">{{ $da->ten_du_an }}</a>
                                <div class="text-muted" style="font-size: 0.75rem"><i
                                        class="fas fa-map-marker-alt text-primary me-1"></i>{{ Str::limit($da->dia_chi, 55) }}
                                </div>
                            </td>
                            <td><span class="badge bg-light text-dark border"><i
                                        class="fas fa-map-marker-alt text-muted me-1"></i>{{ $da->khuVuc->ten_khu_vuc ?? '—' }}</span>
                            </td>
                            <td><span class="text-muted" style="font-size: 0.85rem">{{ $da->chu_dau_tu ?: '—' }}</span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex flex-column gap-1 align-items-center">
                                    <a href="{{ route('nhanvien.admin.bat-dong-san.index', ['du_an_id' => $da->id, 'nhu_cau' => 'ban', 'trang_thai' => 'con_hang']) }}"
                                        class="badge {{ $da->so_can_ban > 0 ? 'bg-warning text-dark' : 'bg-light text-muted border' }} text-decoration-none"
                                        title="Xem danh sách BĐS bán của dự án">Bán: {{ $da->so_can_ban }}</a>
                                    <a href="{{ route('nhanvien.admin.bat-dong-san.index', ['du_an_id' => $da->id, 'nhu_cau' => 'thue', 'trang_thai' => 'con_hang']) }}"
                                        class="badge {{ $da->so_can_thue > 0 ? 'bg-info text-dark' : 'bg-light text-muted border' }} text-decoration-none"
                                        title="Xem danh sách BĐS thuê của dự án">Thuê: {{ $da->so_can_thue }}</a>
                                </div>
                            </td>
                            <td class="text-center"><span
                                    class="text-muted fw-bold">{{ $da->thu_tu_hien_thi ?? 0 }}</span></td>
                            <td class="text-center">
                                <label class="toggle-sw">
                                    <input type="checkbox"
                                        data-toggle-url="{{ route('nhanvien.admin.du-an.toggle', $da) }}"
                                        {{ $da->hien_thi ? 'checked' : '' }}>
                                    <span class="toggle-sw-track"><span class="toggle-sw-thumb"></span></span>
                                </label>
                            </td>
                            <td class="text-center">
                                <div class="btn-actions-group justify-content-center">
                                    <a href="{{ route('nhanvien.admin.du-an.edit', $da) }}"
                                        class="btn-action btn-action-edit" data-bs-toggle="tooltip" title="Sửa"><i
                                            class="fas fa-pen"></i></a>
                                    <form id="frmDel_{{ $da->id }}"
                                        action="{{ route('nhanvien.admin.du-an.destroy', $da) }}" method="POST"
                                        class="d-none">@csrf @method('DELETE')</form>
                                    <button type="button" class="btn-action btn-action-delete btn-delete-da"
                                        data-id="{{ $da->id }}" data-name="{{ $da->ten_du_an }}"
                                        data-bs-toggle="tooltip" title="Xóa"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9">
                                <div class="empty-state">
                                    <i class="fas fa-city text-muted mb-3"></i>
                                    <p class="text-muted mb-2">Chưa có dự án nào</p>
                                    <a href="{{ route('nhanvien.admin.du-an.create') }}"
                                        class="btn btn-sm btn-outline-primary">Thêm dự án đầu tiên</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Danh sách Card Mobile --}}
        <div class="mobile-card-list">
            @forelse($duAns as $da)
                <div class="mobile-card">
                    <div class="mobile-card-top align-items-start">
                        @if ($da->hinh_anh_dai_dien)
                            <img src="{{ asset('storage/' . $da->hinh_anh_dai_dien) }}" class="table-thumb me-2"
                                alt="{{ $da->ten_du_an }}">
                        @else
                            <div
                                class="table-thumb d-flex align-items-center justify-content-center bg-light text-muted me-2">
                                <i class="fas fa-image"></i>
                            </div>
                        @endif
                        <div style="flex: 1; min-width: 0;">
                            <a href="{{ route('nhanvien.admin.du-an.edit', $da) }}"
                                class="fw-bold text-navy text-decoration-none d-block text-truncate">{{ $da->ten_du_an }}</a>
                            <div class="text-muted text-truncate" style="font-size: 0.75rem"><i
                                    class="fas fa-map-marker-alt text-primary me-1"></i>{{ $da->dia_chi }}</div>
                        </div>
                    </div>
                    <div class="mobile-card-meta">
                        <div><i class="fas fa-building"></i> {{ $da->chu_dau_tu ?: '—' }}</div>
                        <div><i class="fas fa-tag"></i> Bán:
                            <a href="{{ route('nhanvien.admin.bat-dong-san.index', ['du_an_id' => $da->id, 'nhu_cau' => 'ban', 'trang_thai' => 'con_hang']) }}"
                                class="fw-bold text-warning text-decoration-none">{{ $da->so_can_ban }}</a>
                        </div>
                        <div><i class="fas fa-key"></i> Thuê:
                            <a href="{{ route('nhanvien.admin.bat-dong-san.index', ['du_an_id' => $da->id, 'nhu_cau' => 'thue', 'trang_thai' => 'con_hang']) }}"
                                class="fw-bold text-info text-decoration-none">{{ $da->so_can_thue }}</a>
                        </div>
                    </div>
                    <div class="mobile-card-foot">
                        <label class="toggle-sw">
                            <input type="checkbox" data-toggle-url="{{ route('nhanvien.admin.du-an.toggle', $da) }}"
                                {{ $da->hien_thi ? 'checked' : '' }}>
                            <span class="toggle-sw-track"><span class="toggle-sw-thumb"></span></span>
                        </label>
                        <div class="btn-actions-group">
                            <a href="{{ route('nhanvien.admin.du-an.edit', $da) }}" class="btn-action btn-action-edit"><i
                                    class="fas fa-pen"></i></a>
                            <button type="button" class="btn-action btn-action-delete btn-delete-da"
                                data-id="{{ $da->id }}" data-name="{{ $da->ten_du_an }}"><i
                                    class="fas fa-trash"></i></button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <i class="fas fa-city text-muted mb-3"></i>
                    <p class="text-muted mb-2">Chưa có dự án nào</p>
                </div>
            @endforelse
        </div>

        {{-- Phân trang --}}
        @if ($duAns->hasPages())
            <div class="card-footer bg-white border-top p-3 d-flex justify-content-center justify-content-md-end">
                {{ $duAns->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if (window.innerWidth > 640) {
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            }

            // Gọi hàm confirmDelete từ admin.js
            document.querySelectorAll('.btn-delete-da').forEach(btn => {
                btn.addEventListener('click', function() {
                    const name = this.dataset.name;
                    const id = this.dataset.id;
                    confirmDelete('dự án ' + name, function() {
                        document.getElementById('frmDel_' + id).submit();
                    });
                });
            });
        });
    </script>
@endpush
