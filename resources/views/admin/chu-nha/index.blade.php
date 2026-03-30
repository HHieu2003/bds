@extends('admin.layouts.master')
@section('title', 'Quản lý Chủ nhà')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h1 class="page-header-title mb-1"><i class="fas fa-user-tie text-primary"></i> Quản lý Chủ nhà</h1>
            <p class="page-header-sub mb-0">Lưu trữ bảo mật thông tin liên hệ nguồn hàng</p>
        </div>
        <a href="{{ route('nhanvien.admin.chu-nha.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus me-1"></i> Thêm chủ nhà
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0"><i
                class="fas fa-check-circle me-1"></i> {{ session('success') }}<button type="button" class="btn-close"
                data-bs-dismiss="alert"></button></div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0"><i
                class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}<button type="button" class="btn-close"
                data-bs-dismiss="alert"></button></div>
    @endif

    <div class="filter-box mb-4">
        <form method="GET">
            <div class="row g-2 align-items-center">
                <div class="col-12 col-md-4">
                    <input type="text" name="tim_kiem" class="filter-ctrl filter-ctrl-search w-100"
                        value="{{ request('tim_kiem') }}" placeholder="🔍 Tìm họ tên, SĐT, CCCD...">
                </div>
                <div class="col-12 col-md-auto ms-auto d-flex gap-2">
                    <button type="submit" class="btn btn-navy"><i class="fas fa-search"></i> Lọc</button>
                    @if (request('tim_kiem'))
                        <a href="{{ route('nhanvien.admin.chu-nha.index') }}" class="btn btn-danger"><i
                                class="fas fa-times"></i></a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <span class="text-muted fw-normal" style="font-size: 0.85rem">Hiển thị <strong>{{ $chuNhas->total() }}</strong>
                chủ nhà</span>
        </div>

        {{-- Bảng PC --}}
        <div class="table-responsive tbl-desktop" style="overflow-x: hidden;">
            <table class="table table-hover align-middle mb-0" style="table-layout: fixed; width: 100%;">
                <thead class="bg-light">
                    <tr>
                        <th class="text-center" style="width: 50px">#</th>
                        <th style="width: 25%">Họ tên</th>
                        <th style="width: 20%">Liên hệ</th>
                        <th style="width: 25%">Địa chỉ / Ghi chú</th>
                        <th class="text-center" style="width: 15%">Bất động sản</th>
                        <th class="text-center" style="width: 100px">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($chuNhas as $i => $cn)
                        <tr>
                            <td class="text-center text-muted">{{ $chuNhas->firstItem() + $i }}</td>
                            <td>
                                <a href="{{ route('nhanvien.admin.chu-nha.edit', $cn) }}"
                                    class="fw-bold text-navy text-decoration-none d-block">{{ $cn->ho_ten }}</a>
                                @if ($cn->cccd)
                                    <div class="text-muted" style="font-size: 0.75rem">CCCD: {{ $cn->cccd }}</div>
                                @endif
                            </td>
                            <td>
                                <div class="fw-bold text-success"><i
                                        class="fas fa-phone-alt me-1"></i>{{ $cn->so_dien_thoai }}</div>
                                @if ($cn->email)
                                    <div class="text-muted" style="font-size: 0.75rem"><i
                                            class="fas fa-envelope me-1"></i>{{ $cn->email }}</div>
                                @endif
                            </td>
                            <td>
                                <div class="text-truncate" style="font-size: 0.85rem" title="{{ $cn->dia_chi }}">
                                    {{ $cn->dia_chi ?: '—' }}</div>
                                @if ($cn->ghi_chu)
                                    <div class="text-muted text-truncate" style="font-size: 0.75rem"
                                        title="{{ $cn->ghi_chu }}"><i
                                            class="fas fa-info-circle me-1"></i>{{ $cn->ghi_chu }}</div>
                                @endif
                            </td>
                            <td class="text-center"><span
                                    class="badge bg-primary bg-opacity-10 text-primary border px-2 py-1"><i
                                        class="fas fa-building me-1"></i> {{ $cn->bat_dong_sans_count }} BĐS</span></td>
                            <td class="text-center">
                                <div class="btn-actions-group justify-content-center">
                                    <a href="{{ route('nhanvien.admin.chu-nha.edit', $cn) }}"
                                        class="btn-action btn-action-edit"><i class="fas fa-pen"></i></a>
                                    <form id="frmDel_{{ $cn->id }}"
                                        action="{{ route('nhanvien.admin.chu-nha.destroy', $cn) }}" method="POST"
                                        class="d-none">@csrf @method('DELETE')</form>
                                    <button type="button" class="btn-action btn-action-delete btn-delete-cn"
                                        data-id="{{ $cn->id }}" data-name="{{ $cn->ho_ten }}"><i
                                            class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state"><i class="fas fa-user-tie text-muted mb-3"></i>
                                    <p class="text-muted">Chưa có dữ liệu chủ nhà</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile Cards --}}
        <div class="mobile-card-list">
            @foreach ($chuNhas as $cn)
                <div class="mobile-card">
                    <div class="mobile-card-top">
                        <div style="flex: 1; min-width: 0;">
                            <a href="{{ route('nhanvien.admin.chu-nha.edit', $cn) }}"
                                class="fw-bold text-navy text-decoration-none d-block mb-1">{{ $cn->ho_ten }}</a>
                            <div class="fw-bold text-success" style="font-size: 0.85rem"><i
                                    class="fas fa-phone-alt me-1"></i>{{ $cn->so_dien_thoai }}</div>
                        </div>
                        <span class="badge bg-primary bg-opacity-10 text-primary border"><i class="fas fa-building"></i>
                            {{ $cn->bat_dong_sans_count }}</span>
                    </div>
                    <div class="mobile-card-meta flex-column align-items-start gap-1">
                        <div><i class="fas fa-map-marker-alt text-danger w-15px text-center"></i>
                            {{ Str::limit($cn->dia_chi, 30) ?: 'Chưa cập nhật' }}</div>
                    </div>
                    <div class="mobile-card-foot justify-content-end">
                        <div class="btn-actions-group">
                            <a href="{{ route('nhanvien.admin.chu-nha.edit', $cn) }}"
                                class="btn-action btn-action-edit"><i class="fas fa-pen"></i></a>
                            <button type="button" class="btn-action btn-action-delete btn-delete-cn"
                                data-id="{{ $cn->id }}" data-name="{{ $cn->ho_ten }}"><i
                                    class="fas fa-trash"></i></button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if ($chuNhas->hasPages())
            <div class="card-footer bg-white border-top p-3 d-flex justify-content-center justify-content-md-end">
                {{ $chuNhas->links('pagination::bootstrap-5') }}</div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('.btn-delete-cn').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;
                confirmDelete('chủ nhà ' + this.dataset.name, function() {
                    document.getElementById('frmDel_' + id).submit();
                });
            });
        });
    </script>
@endpush
