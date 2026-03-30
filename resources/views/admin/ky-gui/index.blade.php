@extends('admin.layouts.master')
@section('title', 'Quản lý Ký gửi')

@section('content')
    {{-- HEADER --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h1 class="page-header-title mb-1"><i class="fas fa-file-signature text-primary"></i> Quản lý Ký gửi</h1>
            <p class="page-header-sub mb-0">Nghiệp vụ Nguồn hàng: Tiếp nhận & Thẩm định BĐS</p>
        </div>
        <a href="{{ route('nhanvien.admin.ky-gui.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus me-1"></i> Tạo yêu cầu mới
        </a>
    </div>

    {{-- FLASH MESSAGES --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0"><i
                class="fas fa-check-circle me-1"></i> {!! session('success') !!}<button type="button" class="btn-close"
                data-bs-dismiss="alert"></button></div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0"><i
                class="fas fa-exclamation-circle me-1"></i> {!! session('error') !!}<button type="button" class="btn-close"
                data-bs-dismiss="alert"></button></div>
    @endif

    {{-- THỐNG KÊ --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-4 col-xl">
            <div class="stat-card p-3">
                <div class="stat-icon blue bg-opacity-10"><i class="fas fa-inbox"></i></div>
                <div>
                    <div class="stat-num fs-4">{{ number_format($thongKe['tong']) }}</div>
                    <div class="stat-label">Tổng số</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl">
            <div class="stat-card p-3">
                <div class="stat-icon orange bg-opacity-10"><i class="fas fa-clock"></i></div>
                <div>
                    <div class="stat-num fs-4">{{ number_format($thongKe['cho_duyet']) }}</div>
                    <div class="stat-label">Mới gửi</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl">
            <div class="stat-card p-3">
                <div class="stat-icon purple bg-opacity-10"><i class="fas fa-search"></i></div>
                <div>
                    <div class="stat-num fs-4">{{ number_format($thongKe['dang_tham_dinh']) }}</div>
                    <div class="stat-label">Đang thẩm định</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-6 col-xl">
            <div class="stat-card p-3">
                <div class="stat-icon green bg-opacity-10"><i class="fas fa-check-circle"></i></div>
                <div>
                    <div class="stat-num fs-4">{{ number_format($thongKe['da_duyet']) }}</div>
                    <div class="stat-label">Đã duyệt</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl">
            <div class="stat-card p-3">
                <div class="stat-icon red bg-opacity-10"><i class="fas fa-times-circle"></i></div>
                <div>
                    <div class="stat-num fs-4">{{ number_format($thongKe['tu_choi']) }}</div>
                    <div class="stat-label">Từ chối</div>
                </div>
            </div>
        </div>
    </div>

    {{-- BỘ LỌC --}}
    <div class="filter-box mb-4">
        <form method="GET" id="filterForm">
            <div class="row g-2 align-items-center">
                <div class="col-12 col-md-3">
                    <input type="text" name="tim_kiem" class="filter-ctrl filter-ctrl-search w-100"
                        value="{{ request('tim_kiem') }}" placeholder="🔍 Tìm tên, SĐT chủ nhà...">
                </div>
                <div class="col-6 col-md-2">
                    <select name="trang_thai" class="filter-ctrl w-100 filter-auto-submit">
                        <option value="">Trạng thái</option>
                        @foreach (\App\Models\KyGui::TRANG_THAI as $v => $info)
                            <option value="{{ $v }}" @selected(request('trang_thai') == $v)>{{ $info['label'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <select name="loai_hinh" class="filter-ctrl w-100 filter-auto-submit">
                        <option value="">Loại hình</option>
                        @foreach (\App\Models\KyGui::LOAI_HINH as $v => $info)
                            <option value="{{ $v }}" @selected(request('loai_hinh') == $v)>{{ $info['label'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <select name="nhan_vien_id" class="filter-ctrl w-100 filter-auto-submit">
                        <option value="">Nhân sự Nguồn</option>
                        @foreach ($nhanViens as $nv)
                            <option value="{{ $nv->id }}" @selected(request('nhan_vien_id') == $nv->id)>{{ $nv->ho_ten }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-3 d-flex gap-1">
                    <input type="date" name="tu_ngay" class="filter-ctrl w-50" value="{{ request('tu_ngay') }}">
                    <input type="date" name="den_ngay" class="filter-ctrl w-50" value="{{ request('den_ngay') }}">
                </div>
                <div class="col-12 mt-2 d-flex justify-content-end gap-2">
                    <button type="submit" class="btn btn-navy btn-sm px-3"><i class="fas fa-search"></i> Lọc</button>
                    @if (request()->hasAny(['tim_kiem', 'trang_thai', 'loai_hinh', 'nhan_vien_id', 'tu_ngay', 'den_ngay']))
                        <a href="{{ route('nhanvien.admin.ky-gui.index') }}" class="btn btn-danger btn-sm px-3"><i
                                class="fas fa-times"></i></a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    {{-- BẢNG DỮ LIỆU --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <span class="text-muted fw-normal" style="font-size: 0.85rem">
                @if ($kyGuis->total() > 0)
                    Hiển thị <strong>{{ $kyGuis->firstItem() }}–{{ $kyGuis->lastItem() }}</strong> /
                    <strong>{{ number_format($kyGuis->total()) }}</strong> yêu cầu
                @else
                    Không có kết quả nào
                @endif
            </span>
        </div>

        <div class="table-responsive tbl-desktop" style="overflow-x: hidden;">
            <table class="table table-hover align-middle mb-0"
                style="table-layout: fixed; width: 100%; word-wrap: break-word;">
                <thead class="bg-light">
                    <tr>
                        <th class="text-center" style="width: 40px">#</th>
                        <th style="width: 20%">Khách hàng / Chủ nhà</th>
                        <th style="width: 15%">Loại BĐS</th>
                        <th>Địa chỉ / Diện tích</th>
                        <th style="width: 12%">Giá mong muốn</th>
                        <th style="width: 15%">Trạng thái</th>
                        <th class="text-center" style="width: 90px">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kyGuis as $i => $kg)
                        @php
                            $ttInfo = \App\Models\KyGui::TRANG_THAI[$kg->trang_thai] ?? [
                                'label' => $kg->trang_thai,
                                'color' => '#999',
                                'bg' => '#f5f5f5',
                                'icon' => 'fas fa-info',
                            ];
                            $lhInfo = \App\Models\KyGui::LOAI_HINH[$kg->loai_hinh] ?? [
                                'label' => $kg->loai_hinh,
                                'icon' => 'fas fa-home',
                                'color' => '#999',
                            ];
                            $ncInfo = \App\Models\KyGui::NHU_CAU[$kg->nhu_cau] ?? [
                                'label' => $kg->nhu_cau,
                                'color' => '#999',
                                'bg' => '#f5f5f5',
                            ];
                        @endphp
                        <tr class="{{ $kg->trang_thai === 'cho_duyet' ? 'table-warning' : '' }}">
                            <td class="text-center text-muted">{{ $kyGuis->firstItem() + $i }}</td>
                            <td>
                                <a href="{{ route('nhanvien.admin.ky-gui.edit', $kg) }}"
                                    class="fw-bold text-navy text-decoration-none d-block mb-1">{{ $kg->ho_ten_chu_nha }}</a>
                                <div class="text-muted" style="font-size: 0.75rem">
                                    <div><i class="fas fa-phone text-success me-1"></i>{{ $kg->so_dien_thoai }}</div>
                                    @if ($kg->email)
                                        <div><i class="fas fa-envelope text-secondary me-1"></i>{{ $kg->email }}</div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold mb-1" style="color: {{ $lhInfo['color'] }}; font-size: 0.85rem;"><i
                                        class="{{ $lhInfo['icon'] }} me-1"></i>{{ $lhInfo['label'] }}</div>
                                <span class="badge"
                                    style="background: {{ $ncInfo['bg'] }}; color: {{ $ncInfo['color'] }}">{{ $ncInfo['label'] }}</span>
                            </td>
                            <td>
                                <div class="mb-1 text-truncate" title="{{ $kg->dia_chi }}" style="font-size: 0.85rem">
                                    <i
                                        class="fas fa-map-marker-alt text-danger me-1"></i>{{ $kg->dia_chi ?: 'Chưa cập nhật' }}
                                </div>
                                <div class="text-muted" style="font-size: 0.8rem">
                                    <i class="fas fa-vector-square me-1"></i>{{ (float) $kg->dien_tich }}m²
                                    @if ($kg->so_phong_ngu)
                                        | {{ $kg->so_phong_ngu }} PN
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold text-primary">{{ $kg->gia_hien_thi }}</div>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <span class="badge cursor-pointer dropdown-toggle" data-bs-toggle="dropdown"
                                        style="background: {{ $ttInfo['bg'] }}; color: {{ $ttInfo['color'] }}; font-size: 0.75rem; white-space: normal; text-align: left;">
                                        <i class="{{ $ttInfo['icon'] }} me-1"></i>{{ $ttInfo['label'] }}
                                    </span>
                                    <ul class="dropdown-menu shadow-sm" style="font-size: 0.85rem">
                                        @foreach (\App\Models\KyGui::TRANG_THAI as $val => $item)
                                            <li><a class="dropdown-item tt-update-btn" href="javascript:void(0)"
                                                    data-id="{{ $kg->id }}" data-val="{{ $val }}"><i
                                                        class="{{ $item['icon'] }} me-1"
                                                        style="color:{{ $item['color'] }}"></i> {{ $item['label'] }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="text-muted mt-1" style="font-size: 0.7rem" title="Ngày gửi"><i
                                        class="fas fa-calendar-alt"></i> {{ $kg->created_at->format('d/m/Y H:i') }}</div>
                            </td>
                            <td class="text-center">
                                <div class="btn-actions-group justify-content-center">
                                    <a href="{{ route('nhanvien.admin.ky-gui.edit', $kg) }}"
                                        class="btn-action btn-action-edit" title="Sửa/Duyệt"><i
                                            class="fas fa-pen"></i></a>
                                    <form id="frmDel_{{ $kg->id }}"
                                        action="{{ route('nhanvien.admin.ky-gui.destroy', $kg) }}" method="POST"
                                        class="d-none">@csrf @method('DELETE')</form>
                                    <button type="button" class="btn-action btn-action-delete btn-delete-kg"
                                        data-id="{{ $kg->id }}" data-name="{{ $kg->ho_ten_chu_nha }}"
                                        title="Xóa"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state"><i class="fas fa-inbox text-muted mb-3"></i>
                                    <p class="text-muted mb-2">Chưa có yêu cầu ký gửi nào</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile Cards --}}
        <div class="mobile-card-list">
            @foreach ($kyGuis as $kg)
                @php $ttInfo = \App\Models\KyGui::TRANG_THAI[$kg->trang_thai] ?? ['label' => $kg->trang_thai, 'color' => '#999', 'bg' => '#f5f5f5', 'icon' => '']; @endphp
                <div class="mobile-card {{ $kg->trang_thai === 'cho_duyet' ? 'border-warning' : '' }}">
                    <div class="mobile-card-top">
                        <div style="flex: 1; min-width: 0;">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="badge"
                                    style="background: {{ $ttInfo['bg'] }}; color: {{ $ttInfo['color'] }}"><i
                                        class="{{ $ttInfo['icon'] }}"></i> {{ $ttInfo['label'] }}</span>
                                <span class="text-muted"
                                    style="font-size: 0.7rem">{{ $kg->created_at->format('d/m H:i') }}</span>
                            </div>
                            <a href="{{ route('nhanvien.admin.ky-gui.edit', $kg) }}"
                                class="fw-bold text-navy text-decoration-none d-block text-truncate mb-1">{{ $kg->ho_ten_chu_nha }}</a>
                            <div class="text-muted" style="font-size: 0.75rem"><i
                                    class="fas fa-phone text-success me-1"></i>{{ $kg->so_dien_thoai }}</div>
                        </div>
                    </div>
                    <div class="mobile-card-meta flex-column align-items-start gap-1">
                        <div><i class="fas fa-map-marker-alt text-danger w-15px text-center"></i>
                            {{ Str::limit($kg->dia_chi, 30) ?: 'Chưa cập nhật' }}</div>
                        <div><i class="fas fa-tag text-primary w-15px text-center"></i> <strong
                                class="text-primary">{{ $kg->gia_hien_thi }}</strong> - {{ (float) $kg->dien_tich }}m²
                        </div>
                    </div>
                    <div class="mobile-card-foot justify-content-end">
                        <div class="btn-actions-group">
                            <a href="{{ route('nhanvien.admin.ky-gui.edit', $kg) }}"
                                class="btn-action btn-action-edit"><i class="fas fa-pen"></i></a>
                            <button type="button" class="btn-action btn-action-delete btn-delete-kg"
                                data-id="{{ $kg->id }}" data-name="{{ $kg->ho_ten_chu_nha }}"><i
                                    class="fas fa-trash"></i></button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if ($kyGuis->hasPages())
            <div class="card-footer bg-white border-top p-3 d-flex justify-content-center justify-content-md-end">
                {{ $kyGuis->links('pagination::bootstrap-5') }}</div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const CSRF = document.querySelector('meta[name=csrf-token]').content;

            document.querySelectorAll('.btn-delete-kg').forEach(btn => {
                btn.addEventListener('click', function() {
                    const name = this.dataset.name;
                    const id = this.dataset.id;
                    confirmDelete('yêu cầu ký gửi của ' + name, function() {
                        document.getElementById('frmDel_' + id).submit();
                    });
                });
            });

            // AJAX Update Trạng Thái từ Dropdown
            document.querySelectorAll('.tt-update-btn').forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    const id = this.dataset.id;
                    const val = this.dataset.val;

                    fetch(`/nhan-vien/admin/ky-gui/${id}/xu-ly`, {
                            method: 'PATCH',
                            headers: {
                                'X-CSRF-TOKEN': CSRF,
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                trang_thai: val
                            })
                        })
                        .then(r => r.json())
                        .then(data => {
                            if (data.ok) window.location.reload();
                        }).catch(() => showAdminToast('Lỗi kết nối', 'error'));
                });
            });
        });
    </script>
@endpush
