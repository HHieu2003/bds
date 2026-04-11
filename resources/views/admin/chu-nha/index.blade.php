@extends('admin.layouts.master')
@section('title', 'Quản lý Chủ nhà')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
    <style>
        .ts-dropdown {
            z-index: 1060 !important;
        }

        .ts-control {
            border-radius: 8px;
            border: 1px solid #dee2e6;
            padding: 0.65rem 0.9rem;
        }

        /* Tối ưu hóa UI Mobile */
        @media (max-width: 768px) {
            .mobile-card {
                background: #fff;
                border-radius: 12px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
                margin-bottom: 1rem;
                border: 1px solid rgba(0, 0, 0, 0.08);
                overflow: hidden;
            }

            .mobile-card-header {
                background: #f8f9fa;
                padding: 12px 15px;
                border-bottom: 1px solid #eee;
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
            }

            .mobile-card-body {
                padding: 15px;
            }

            .mobile-card-footer {
                background: #fff;
                padding: 10px 15px;
                border-top: 1px dashed #eee;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .quick-action-btn {
                width: 36px;
                height: 36px;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 50%;
            }
        }
    </style>

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h1 class="page-header-title mb-1"><i class="fas fa-user-tie text-primary me-2"></i>Quản lý Chủ nhà
            </h1>
            <p class="page-header-sub mb-0 text-muted">{{ $thongKe['tong'] }} chủ nhà • {{ $thongKe['new_thang_nay'] }} mới
                trong tháng</p>
        </div>
        <button type="button" class="btn btn-primary shadow-sm px-4" data-bs-toggle="modal"
            data-bs-target="#modalCreateChuNha">
            <i class="fas fa-plus me-2"></i>Thêm chủ nhà
        </button>
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

    {{-- BỘ LỌC --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <form id="frmFilter" method="GET" action="{{ route('nhanvien.admin.chu-nha.index') }}">
                <div class="row g-2 align-items-end">
                    <div class="col-12 col-md-3">
                        <label class="form-label small text-muted fw-bold mb-1">Tìm Kiếm Chung</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i
                                    class="fas fa-search text-muted"></i></span>
                            <input type="text" name="tim_kiem" class="form-control border-start-0 ps-0"
                                value="{{ request('tim_kiem') }}" placeholder="Tên, SĐT, Mã Căn..."
                                onchange="this.form.submit()">
                        </div>
                    </div>

                    <div class="col-12 col-md-2">
                        <label class="form-label small text-muted fw-bold mb-1">Dự án</label>
                        <select name="du_an_id" id="filter_du_an" class="form-select">
                            <option value="">-- Tất cả Dự án --</option>
                            @foreach ($duAns as $da)
                                <option value="{{ $da->id }}" {{ request('du_an_id') == $da->id ? 'selected' : '' }}>
                                    {{ $da->ten_du_an }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-6 col-md-2">
                        <label class="form-label small text-muted fw-bold mb-1">Tòa</label>
                        <select name="toa" class="form-select" onchange="this.form.submit()">
                            <option value="">-- Tất cả --</option>
                            @foreach ($toas as $toa)
                                <option value="{{ $toa }}" {{ request('toa') == $toa ? 'selected' : '' }}>Tòa
                                    {{ $toa }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-6 col-md-2">
                        <label class="form-label small text-muted fw-bold mb-1">Sắp xếp Gọi</label>
                        <select name="sort_date" class="form-select" onchange="this.form.submit()">
                            <option value="newest" {{ request('sort_date', 'newest') === 'newest' ? 'selected' : '' }}>Mới
                                cập nhật</option>
                            <option value="oldest" {{ request('sort_date') === 'oldest' ? 'selected' : '' }}>Lâu chưa tương
                                tác</option>
                        </select>
                    </div>

                    <div class="col-12 col-md-2">
                        <label class="form-label small text-muted fw-bold mb-1">Phụ trách</label>
                        <select name="nhan_vien_phu_trach_id" class="form-select" onchange="this.form.submit()">
                            <option value="">-- Tất cả --</option>
                            <option value="none" {{ request('nhan_vien_phu_trach_id') === 'none' ? 'selected' : '' }}>🌐
                                Chung</option>
                            @foreach ($nhanViens as $nv)
                                <option value="{{ $nv->id }}"
                                    {{ request('nhan_vien_phu_trach_id') == $nv->id ? 'selected' : '' }}>👤
                                    {{ $nv->ho_ten }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-md-1 d-flex gap-2">
                        @if (request()->anyFilled(['tim_kiem', 'nhan_vien_phu_trach_id', 'du_an_id', 'toa', 'sort_date']))
                            <a href="{{ route('nhanvien.admin.chu-nha.index') }}"
                                class="btn btn-light border text-danger w-100" title="Xóa lọc">
                                <i class="fas fa-times"></i> Xóa
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <span class="text-muted fw-medium" style="font-size: 0.95rem">
                Đang hiển thị <strong class="text-primary fs-5">{{ $chuNhas->total() }}</strong> chủ nhà
            </span>
        </div>

        {{-- BẢNG PC --}}
        <div class="table-responsive tbl-desktop d-none d-md-block" style="overflow-x: hidden;">
            <table class="table table-hover align-middle mb-0" style="table-layout: fixed; width: 100%;">
                <thead class="bg-light">
                    <tr>
                        <th style="width: 20%">Thông tin Chủ nhà</th>
                        <th style="width: 20%">Liên hệ nhanh</th>
                        <th style="width: 22%">Tài sản (Mã căn / Tòa)</th>
                        <th style="width: 20%">Địa chỉ / Ghi chú</th>
                        <th class="text-center" style="width: 130px">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($chuNhas as $i => $cn)
                        <tr>
                            <td>
                                <button type="button"
                                    class="btn btn-link p-0 fw-bold text-navy text-decoration-none d-block text-start btn-edit-chu-nha mb-1 fs-6"
                                    data-bs-toggle="modal" data-bs-target="#modalEditChuNha" data-id="{{ $cn->id }}"
                                    data-ho-ten="{{ $cn->ho_ten }}" data-so-dien-thoai="{{ $cn->so_dien_thoai }}"
                                    data-email="{{ $cn->email }}" data-cccd="{{ $cn->cccd }}"
                                    data-dia-chi="{{ $cn->dia_chi }}" data-ghi-chu="{{ $cn->ghi_chu }}"
                                    data-nhan-vien-phu-trach-id="{{ $cn->nhan_vien_phu_trach_id }}"
                                    data-bds-ids="{{ json_encode($cn->batDongSans->pluck('id')->toArray()) }}">
                                    {{ $cn->ho_ten }}
                                </button>
                                <div class="mt-1">
                                    @if ($cn->nhanVienPhuTrach)
                                        <span
                                            class="badge bg-info bg-opacity-10 text-info border border-info-subtle fw-normal"><i
                                                class="fas fa-user-tag"></i> {{ $cn->nhanVienPhuTrach->ho_ten }}</span>
                                    @else
                                        <span
                                            class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary-subtle fw-normal"><i
                                                class="fas fa-globe"></i> Chung</span>
                                    @endif
                                </div>
                            </td>

                            <td>
                                <div class="fw-bold text-success mb-2 d-flex align-items-center gap-2">
                                    <span class="fs-6">{{ $cn->so_dien_thoai }}</span>

                                </div>
                                <div class="text-muted" style="font-size: 0.75rem;"><i class="far fa-clock me-1"></i>Cập
                                    nhật: <strong class="text-dark">{{ $cn->updated_at->format('d/m/Y H:i') }}</strong>
                                </div>
                            </td>

                            <td>
                                <div class="mb-2"><span class="fw-bold text-dark"><i
                                            class="fas fa-building text-primary me-1"></i> {{ $cn->bat_dong_sans_count }}
                                        tài sản</span></div>
                                @if ($cn->batDongSans->count() > 0)
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach ($cn->batDongSans->take(5) as $bds)
                                            <span class="badge bg-light border text-dark fw-normal"
                                                style="font-size: 0.75rem;"
                                                title="Dự án: {{ $bds->duAn ? $bds->duAn->ten_du_an : 'N/A' }}">
                                                {{ $bds->toa ? $bds->toa . '-' : '' }}{{ $bds->ma_can ?: '#' . $bds->id }}
                                            </span>
                                        @endforeach
                                        @if ($cn->batDongSans->count() > 5)
                                            <span class="badge bg-secondary bg-opacity-10 border fw-normal"
                                                style="font-size: 0.75rem;">+{{ $cn->batDongSans->count() - 5 }}</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-muted small fst-italic">Chưa cập nhật tài sản</span>
                                @endif
                            </td>

                            <td>
                                @if ($cn->ghi_chu)
                                    <div class="mb-1" style="font-size: 0.8rem; max-height: 40px; overflow-y: auto;">
                                        <i class="fas fa-pen-nib text-warning me-1"></i> <span
                                            class="text-dark fw-medium">{!! nl2br(e($cn->ghi_chu)) !!}</span>
                                    </div>
                                @endif
                                <div class="text-truncate" style="font-size: 0.8rem"><i
                                        class="fas fa-map-marker-alt text-danger me-1"></i>
                                    {{ $cn->dia_chi ?: 'Chưa có địa chỉ' }}</div>
                            </td>

                            <td class="text-center">
                                <div class="btn-actions-group justify-content-center">
                                    {{-- Nút Update Nhanh --}}
                                    <button type="button"
                                        class="btn btn-sm btn-outline-success btn-action btn-quick-update"
                                        data-bs-toggle="modal" data-bs-target="#modalQuickUpdate"
                                        data-id="{{ $cn->id }}" data-name="{{ $cn->ho_ten }}"
                                        title="Báo cáo Tương tác (Làm mới ngày)">
                                        <i class="fas fa-headset"></i>
                                    </button>

                                    <button type="button" class="btn-action btn-action-edit btn-edit-chu-nha"
                                        data-bs-toggle="modal" data-bs-target="#modalEditChuNha"
                                        data-id="{{ $cn->id }}" data-ho-ten="{{ $cn->ho_ten }}"
                                        data-so-dien-thoai="{{ $cn->so_dien_thoai }}" data-email="{{ $cn->email }}"
                                        data-cccd="{{ $cn->cccd }}" data-dia-chi="{{ $cn->dia_chi }}"
                                        data-ghi-chu="{{ $cn->ghi_chu }}"
                                        data-nhan-vien-phu-trach-id="{{ $cn->nhan_vien_phu_trach_id }}"
                                        data-bds-ids="{{ json_encode($cn->batDongSans->pluck('id')->toArray()) }}"
                                        title="Sửa"><i class="fas fa-pen"></i></button>

                                    <form id="frmDel_{{ $cn->id }}"
                                        action="{{ route('nhanvien.admin.chu-nha.destroy', $cn) }}" method="POST"
                                        class="d-none">@csrf @method('DELETE')</form>
                                    <button type="button" class="btn-action btn-action-delete btn-delete-cn"
                                        data-id="{{ $cn->id }}" data-name="{{ $cn->ho_ten }}"
                                        data-count="{{ $cn->bat_dong_sans_count }}" title="Xóa"><i
                                            class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">Không tìm thấy dữ liệu.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- DANH SÁCH MOBILE --}}
        <div class="d-md-none p-3 bg-light">
            @foreach ($chuNhas as $cn)
                <div class="mobile-card">
                    <div class="mobile-card-header">
                        <div>
                            <button type="button"
                                class="btn btn-link p-0 fw-bold text-navy text-decoration-none d-block text-start mb-1 fs-6 btn-edit-chu-nha"
                                data-bs-toggle="modal" data-bs-target="#modalEditChuNha" data-id="{{ $cn->id }}"
                                data-ho-ten="{{ $cn->ho_ten }}" data-so-dien-thoai="{{ $cn->so_dien_thoai }}"
                                data-email="{{ $cn->email }}" data-cccd="{{ $cn->cccd }}"
                                data-dia-chi="{{ $cn->dia_chi }}" data-ghi-chu="{{ $cn->ghi_chu }}"
                                data-nhan-vien-phu-trach-id="{{ $cn->nhan_vien_phu_trach_id }}"
                                data-bds-ids="{{ json_encode($cn->batDongSans->pluck('id')->toArray()) }}">{{ $cn->ho_ten }}</button>
                            <span class="badge bg-light text-dark border fw-normal" style="font-size: 0.7rem;"><i
                                    class="far fa-clock"></i> {{ $cn->updated_at->format('d/m H:i') }}</span>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="tel:{{ preg_replace('/[^0-9]/', '', $cn->so_dien_thoai) }}"
                                class="btn btn-success quick-action-btn"><i class="fas fa-phone-alt"></i></a>
                            <a href="https://zalo.me/{{ preg_replace('/[^0-9]/', '', $cn->so_dien_thoai) }}"
                                target="_blank" class="btn btn-primary quick-action-btn"
                                style="background-color:#0068FF; border:none;"><strong>Z</strong></a>
                        </div>
                    </div>

                    <div class="mobile-card-body pb-2">
                        <div class="fw-bold text-success mb-2 fs-6"><i
                                class="fas fa-mobile-alt me-2"></i>{{ $cn->so_dien_thoai }}</div>

                        {{-- BĐS --}}
                        <div class="bg-light p-2 rounded-3 border mb-2">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold text-dark" style="font-size: 0.8rem;"><i
                                        class="fas fa-building text-primary me-1"></i> Đang có:
                                    {{ $cn->bat_dong_sans_count }} căn</span>
                            </div>
                            <div class="d-flex flex-wrap gap-1">
                                @foreach ($cn->batDongSans->take(4) as $bds)
                                    <span class="badge bg-white border text-dark fw-normal"
                                        style="font-size: 0.75rem;">{{ $bds->toa ? $bds->toa . '-' : '' }}{{ $bds->ma_can ?: '#' . $bds->id }}</span>
                                @endforeach
                                @if ($cn->batDongSans->count() > 4)
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border fw-normal"
                                        style="font-size: 0.75rem;">+{{ $cn->batDongSans->count() - 4 }}</span>
                                @endif
                            </div>
                        </div>

                        {{-- Note & PT --}}
                        <div class="d-flex justify-content-between align-items-end mt-2">
                            <div style="flex:1" class="pe-2">
                                @if ($cn->ghi_chu)
                                    <div class="text-dark fw-medium text-truncate" style="font-size: 0.8rem;"><i
                                            class="fas fa-pen-nib text-warning me-1"></i>
                                        {{ Str::limit($cn->ghi_chu, 35) }}</div>
                                @endif
                            </div>
                            <div>
                                @if ($cn->nhanVienPhuTrach)
                                    <span class="badge bg-info bg-opacity-10 text-info border"><i
                                            class="fas fa-user-tag"></i>
                                        {{ Str::limit($cn->nhanVienPhuTrach->ho_ten, 10) }}</span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border"><i
                                            class="fas fa-globe"></i> Chung</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mobile-card-footer">
                        <button type="button" class="btn btn-sm btn-outline-success fw-bold btn-quick-update"
                            style="font-size:0.75rem;" data-bs-toggle="modal" data-bs-target="#modalQuickUpdate"
                            data-id="{{ $cn->id }}" data-name="{{ $cn->ho_ten }}">
                            <i class="fas fa-headset me-1"></i> Check-in Tương tác
                        </button>

                        <div class="btn-actions-group">
                            <button type="button" class="btn-action btn-action-edit btn-edit-chu-nha"
                                data-bs-toggle="modal" data-bs-target="#modalEditChuNha" data-id="{{ $cn->id }}"
                                data-ho-ten="{{ $cn->ho_ten }}" data-so-dien-thoai="{{ $cn->so_dien_thoai }}"
                                data-email="{{ $cn->email }}" data-cccd="{{ $cn->cccd }}"
                                data-dia-chi="{{ $cn->dia_chi }}" data-ghi-chu="{{ $cn->ghi_chu }}"
                                data-nhan-vien-phu-trach-id="{{ $cn->nhan_vien_phu_trach_id }}"
                                data-bds-ids="{{ json_encode($cn->batDongSans->pluck('id')->toArray()) }}"><i
                                    class="fas fa-pen"></i></button>
                            <button type="button" class="btn-action btn-action-delete btn-delete-cn"
                                data-id="{{ $cn->id }}" data-name="{{ $cn->ho_ten }}"
                                data-count="{{ $cn->bat_dong_sans_count }}"><i class="fas fa-trash"></i></button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if ($chuNhas->hasPages())
            <div class="card-footer bg-white border-top p-3 d-flex justify-content-center justify-content-md-end">
                {{ $chuNhas->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

    @include('admin.chu-nha._modal-create')
    @include('admin.chu-nha._modal-edit')
    @include('admin.chu-nha._modal-quick-update') {{-- INCLUDE MODAL MỚI --}}

    <div class="modal fade" id="modalConfirmDeleteChuNha" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-sm" style="border-radius: 14px; overflow: hidden;">
                <div class="modal-header border-0" style="background: #fff5f5;">
                    <h5 class="modal-title text-danger fw-bold mb-0">
                        <i class="fas fa-trash-alt me-2"></i>Xác nhận xóa chủ nhà
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="mb-2">Bạn có chắc chắn muốn xóa chủ nhà <strong id="deleteChuNhaName">-</strong>?</p>
                    <div class="alert alert-warning small mb-0 d-none" id="deleteChuNhaWarning"></div>
                </div>
                <div class="modal-footer border-0 bg-light">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-danger" id="btnConfirmDeleteChuNha">
                        <i class="fas fa-trash me-1"></i>Xóa chủ nhà
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script>
        const allBdsData = @json($allBdsList);

        document.addEventListener('DOMContentLoaded', function() {
            // Khởi tạo Tom Select
            new TomSelect('#filter_du_an', {
                create: false,
                sortField: {
                    field: "text",
                    direction: "asc"
                },
                onChange: function() {
                    document.getElementById('frmFilter').submit();
                }
            });
            window.createBdsSelect = new TomSelect('#create_bat_dong_san_ids', {
                plugins: ['remove_button'],
                searchField: ['text'],
                maxOptions: 50
            });
            allBdsData.forEach(bds => {
                if (bds.chu_nha_id === null) window.createBdsSelect.addOption({
                    value: bds.id,
                    text: `${bds.toa ? bds.toa + '-' : ''}${bds.ma_can || 'BĐS#' + bds.id} (${bds.du_an ? bds.du_an.ten_du_an : ''})`
                });
            });
            window.editBdsSelect = new TomSelect('#edit_bat_dong_san_ids', {
                plugins: ['remove_button'],
                searchField: ['text'],
                maxOptions: 50
            });
        });

        // Xử lý Xóa bằng modal xác nhận đẹp hơn
        let pendingDeleteFormId = null;
        const deleteModalEl = document.getElementById('modalConfirmDeleteChuNha');
        const deleteNameEl = document.getElementById('deleteChuNhaName');
        const deleteWarningEl = document.getElementById('deleteChuNhaWarning');
        const btnConfirmDelete = document.getElementById('btnConfirmDeleteChuNha');
        const deleteModal = deleteModalEl ? new bootstrap.Modal(deleteModalEl) : null;

        document.querySelectorAll('.btn-delete-cn').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;
                const name = this.dataset.name || 'không rõ tên';
                const count = parseInt(this.dataset.count || 0, 10);

                pendingDeleteFormId = 'frmDel_' + id;
                if (deleteNameEl) deleteNameEl.textContent = name;

                if (deleteWarningEl) {
                    if (count > 0) {
                        deleteWarningEl.classList.remove('d-none');
                        deleteWarningEl.innerHTML =
                            `<i class="fas fa-exclamation-triangle me-1"></i><strong>Cảnh báo:</strong> Chủ nhà này đang sở hữu <strong>${count}</strong> bất động sản. Khi xóa, các BĐS sẽ bị gỡ liên kết chủ nhà.`;
                    } else {
                        deleteWarningEl.classList.add('d-none');
                        deleteWarningEl.textContent = '';
                    }
                }

                if (deleteModal) deleteModal.show();
            });
        });

        if (btnConfirmDelete) {
            btnConfirmDelete.addEventListener('click', function() {
                if (!pendingDeleteFormId) return;
                const form = document.getElementById(pendingDeleteFormId);
                if (!form) return;
                form.submit();
            });
        }

        // Xử lý Modal Sửa
        const editForm = document.getElementById('editChuNhaForm');

        function fillEditModal(button) {
            if (!button || !editForm) return;
            const id = button.dataset.id;
            editForm.action = `{{ route('nhanvien.admin.chu-nha.update', ['chu_nha' => '__ID__']) }}`.replace('__ID__',
                id);
            document.getElementById('edit_id').value = id || '';
            document.getElementById('edit_ho_ten').value = button.dataset.hoTen || '';
            document.getElementById('edit_so_dien_thoai').value = button.dataset.soDienThoai || '';
            document.getElementById('edit_email').value = button.dataset.email || '';
            document.getElementById('edit_cccd').value = button.dataset.cccd || '';
            document.getElementById('edit_dia_chi').value = button.dataset.diaChi || '';
            document.getElementById('edit_ghi_chu').value = button.dataset.ghiChu || '';
            document.getElementById('edit_nhan_vien_phu_trach_id').value = button.dataset.nhanVienPhuTrachId || '';

            if (window.editBdsSelect) {
                window.editBdsSelect.clearOptions();
                window.editBdsSelect.clear();
                const selectedIds = JSON.parse(button.dataset.bdsIds || "[]");
                allBdsData.forEach(bds => {
                    if (bds.chu_nha_id === null || String(bds.chu_nha_id) === String(id)) {
                        window.editBdsSelect.addOption({
                            value: bds.id,
                            text: `${bds.toa ? bds.toa + '-' : ''}${bds.ma_can || 'BĐS#' + bds.id} (${bds.du_an ? bds.du_an.ten_du_an : ''})`
                        });
                    }
                });
                window.editBdsSelect.setValue(selectedIds);
            }
        }
        document.querySelectorAll('.btn-edit-chu-nha').forEach((btn) => {
            btn.addEventListener('click', function() {
                fillEditModal(this);
            });
        });

        // Xử lý Modal Cập nhật nhanh (Check-in)
        const quickUpdateForm = document.getElementById('quickUpdateForm');
        document.querySelectorAll('.btn-quick-update').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;
                document.getElementById('qu_chu_nha_name').innerText = this.dataset.name;
                quickUpdateForm.action =
                    `{{ route('nhanvien.admin.chu-nha.update', ['chu_nha' => '__ID__']) }}`.replace(
                        '__ID__', id);
                document.getElementById('ghi_chu_moi').value = ''; // Reset textarea
            });
        });
    </script>
@endpush
