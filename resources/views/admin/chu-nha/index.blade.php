@extends('admin.layouts.master')
@section('title', 'Quản lý Chủ nhà')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
    <style>
        .ts-dropdown {
            z-index: 1060 !important;
        }

        /* Đảm bảo dropdown nằm trên Modal */
        .ts-control {
            border-radius: 8px;
            border: 1px solid #dee2e6;
            padding: 0.65rem 0.9rem;
        }
    </style>

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h1 class="page-header-title mb-1"><i class="fas fa-user-tie text-primary me-2"></i>Quản lý Nguồn hàng / Chủ nhà
            </h1>
            <p class="page-header-sub mb-0 text-muted">Lưu trữ, tra cứu thông tin và quản lý tài sản chủ nhà</p>
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

    {{-- Bộ lọc Nâng cao (TỰ ĐỘNG SUBMIT) --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <form id="frmFilter" method="GET" action="{{ route('nhanvien.admin.chu-nha.index') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-12 col-md-3">
                        <label class="form-label small text-muted fw-bold mb-1">Tìm Tên, SĐT, Mã Căn, Dự án</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i
                                    class="fas fa-search text-muted"></i></span>
                            <input type="text" name="tim_kiem" class="form-control border-start-0 ps-0"
                                value="{{ request('tim_kiem') }}" placeholder="Nhập từ khóa và ấn Enter..."
                                onchange="this.form.submit()">
                        </div>
                    </div>

                    <div class="col-12 col-md-3">
                        <label class="form-label small text-muted fw-bold mb-1">Tìm theo Dự án</label>
                        <select name="du_an_id" id="filter_du_an" class="form-select">
                            <option value="">-- Tất cả Dự án --</option>
                            @foreach ($duAns as $da)
                                <option value="{{ $da->id }}" {{ request('du_an_id') == $da->id ? 'selected' : '' }}>
                                    {{ $da->ten_du_an }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-md-2">
                        <label class="form-label small text-muted fw-bold mb-1">Tòa (Tùy theo Dự án)</label>
                        <select name="toa" class="form-select" onchange="this.form.submit()">
                            <option value="">-- Tất cả Tòa --</option>
                            @foreach ($toas as $toa)
                                <option value="{{ $toa }}" {{ request('toa') == $toa ? 'selected' : '' }}>Tòa
                                    {{ $toa }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-md-2">
                        <label class="form-label small text-muted fw-bold mb-1">Người phụ trách</label>
                        <select name="nhan_vien_phu_trach_id" class="form-select" onchange="this.form.submit()">
                            <option value="">-- Tất cả --</option>
                            <option value="none" {{ request('nhan_vien_phu_trach_id') === 'none' ? 'selected' : '' }}>🌐
                                Chung công ty</option>
                            @foreach ($nhanViens as $nv)
                                <option value="{{ $nv->id }}"
                                    {{ request('nhan_vien_phu_trach_id') == $nv->id ? 'selected' : '' }}>
                                    👤 {{ $nv->ho_ten }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-md-2 d-flex gap-2">
                        @if (request()->anyFilled(['tim_kiem', 'nhan_vien_phu_trach_id', 'du_an_id', 'toa']))
                            <a href="{{ route('nhanvien.admin.chu-nha.index') }}"
                                class="btn btn-light border text-danger w-100" title="Xóa lọc">
                                <i class="fas fa-times me-1"></i> Xóa lọc
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Bảng hiển thị (GIỮ NGUYÊN GIAO DIỆN NHƯ BƯỚC TRƯỚC, CHỈ ĐỔI NÚT ACTION) --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <div>
                <span class="text-muted fw-medium" style="font-size: 0.95rem">
                    Đang hiển thị
                    <strong class="text-primary fs-5">{{ $chuNhas->total() }}</strong>
                    chủ nhà
                </span>
            </div>
        </div>

        <div class="table-responsive tbl-desktop" style="overflow-x: hidden;">
            <table class="table table-hover align-middle mb-0" style="table-layout: fixed; width: 100%;">
                <thead class="bg-light">
                    <tr>
                        <th class="text-center" style="width: 50px">#</th>
                        <th style="width: 20%">Thông tin Chủ nhà</th>
                        <th style="width: 20%">Liên hệ nhanh</th>
                        <th style="width: 25%">Tài sản (Mã căn / Tòa)</th>
                        <th style="width: 20%">Địa chỉ / Ghi chú</th>
                        <th class="text-center" style="width: 100px">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($chuNhas as $i => $cn)
                        <tr>
                            <td class="text-center text-muted">{{ $chuNhas->firstItem() + $i }}</td>
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

                                @if ($cn->cccd)
                                    <div class="text-muted mb-1" style="font-size: 0.8rem"><i
                                            class="far fa-id-card me-1"></i>{{ $cn->cccd }}</div>
                                @endif
                                <div class="mt-1">
                                    @if ($cn->nhanVienPhuTrach)
                                        <span
                                            class="badge bg-info bg-opacity-10 text-info border border-info-subtle fw-normal">
                                            <i class="fas fa-user-tag me-1"></i> {{ $cn->nhanVienPhuTrach->ho_ten }}
                                        </span>
                                    @else
                                        <span
                                            class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary-subtle fw-normal">
                                            <i class="fas fa-globe me-1"></i> Chung
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <td>
                                <div class="fw-bold text-success mb-2 d-flex align-items-center gap-2">
                                    <span class="fs-6">{{ $cn->so_dien_thoai }}</span>

                                </div>
                                @if ($cn->email)
                                    <div class="text-muted text-truncate" style="font-size: 0.8rem"
                                        title="{{ $cn->email }}">{{ $cn->email }}</div>
                                @endif
                            </td>

                            <td>
                                <div class="mb-2">
                                    <span class="fw-bold text-dark"><i class="fas fa-building text-primary me-1"></i>
                                        {{ $cn->bat_dong_sans_count }} tài sản</span>
                                </div>
                                @if ($cn->batDongSans->count() > 0)
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach ($cn->batDongSans->take(5) as $bds)
                                            <span class="badge bg-light border text-dark fw-normal"
                                                style="font-size: 0.78rem;"
                                                title="Dự án: {{ $bds->duAn ? $bds->duAn->ten_du_an : 'N/A' }}">
                                                <i class="fas fa-circle {{ $bds->nhu_cau == 'ban' ? 'text-danger' : 'text-success' }}"
                                                    style="font-size: 0.4rem; vertical-align: middle;"></i>
                                                {{ $bds->toa ? $bds->toa . ' - ' : '' }}{{ $bds->ma_can ?: '#' . $bds->id }}
                                            </span>
                                        @endforeach
                                        @if ($cn->batDongSans->count() > 5)
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary border fw-normal"
                                                style="font-size: 0.78rem;">+{{ $cn->batDongSans->count() - 5 }}</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-muted small fst-italic">Chưa cập nhật tài sản</span>
                                @endif
                            </td>

                            <td>
                                @if ($cn->ghi_chu)
                                    <div class="mb-1" style="font-size: 0.85rem;"><i
                                            class="fas fa-pen-nib text-warning me-1"></i> <span
                                            class="text-dark fw-medium">{{ Str::limit($cn->ghi_chu, 60) }}</span></div>
                                @endif
                                <div class="text-truncate" style="font-size: 0.8rem"><i
                                        class="fas fa-map-marker-alt text-danger me-1"></i>
                                    {{ $cn->dia_chi ?: 'Chưa có địa chỉ' }}</div>
                            </td>

                            <td class="text-center">
                                <div class="btn-actions-group justify-content-center">
                                    <button type="button" class="btn-action btn-action-edit btn-edit-chu-nha"
                                        data-bs-toggle="modal" data-bs-target="#modalEditChuNha"
                                        data-id="{{ $cn->id }}" data-ho-ten="{{ $cn->ho_ten }}"
                                        data-so-dien-thoai="{{ $cn->so_dien_thoai }}" data-email="{{ $cn->email }}"
                                        data-cccd="{{ $cn->cccd }}" data-dia-chi="{{ $cn->dia_chi }}"
                                        data-ghi-chu="{{ $cn->ghi_chu }}"
                                        data-nhan-vien-phu-trach-id="{{ $cn->nhan_vien_phu_trach_id }}"
                                        data-bds-ids="{{ json_encode($cn->batDongSans->pluck('id')->toArray()) }}"><i
                                            class="fas fa-pen"></i></button>
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
                                <div class="empty-state py-5 text-center">
                                    <i class="fas fa-search text-muted opacity-25 mb-3" style="font-size: 3rem;"></i>
                                    <p class="text-muted mb-0">Không tìm thấy chủ nhà nào phù hợp với bộ lọc.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($chuNhas->hasPages())
            <div class="card-footer bg-white border-top p-3 d-flex justify-content-center justify-content-md-end">
                {{ $chuNhas->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

    @include('admin.chu-nha._modal-create')
    @include('admin.chu-nha._modal-edit')
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

    <script>
        const allBdsData = @json($allBdsList);

        document.addEventListener('DOMContentLoaded', function() {

            // 1. Khởi tạo Tìm kiếm cho Filter Dự án, thay đổi sẽ auto submit form
            new TomSelect('#filter_du_an', {
                create: false,
                sortField: {
                    field: "text",
                    direction: "asc"
                },
                placeholder: "-- Gõ để tìm Dự án --",
                onChange: function() {
                    document.getElementById('frmFilter').submit();
                }
            });

            // 2. Khởi tạo Tìm kiếm Đa lựa chọn (Multi-select) cho Form Tạo Mới
            window.createBdsSelect = new TomSelect('#create_bat_dong_san_ids', {
                plugins: ['remove_button'],
                placeholder: 'Gõ mã căn, tòa, dự án để tìm...',
                searchField: ['text'],
                maxOptions: 50,
            });
            // Nạp dữ liệu vào Select Create
            allBdsData.forEach(bds => {
                if (bds.chu_nha_id === null) {
                    let text =
                        `${bds.toa ? bds.toa + ' - ' : ''}${bds.ma_can || 'BĐS #' + bds.id} (${bds.du_an ? bds.du_an.ten_du_an : 'Khác'})`;
                    window.createBdsSelect.addOption({
                        value: bds.id,
                        text: text
                    });
                }
            });

            // 3. Khởi tạo Tìm kiếm Đa lựa chọn cho Form Sửa
            window.editBdsSelect = new TomSelect('#edit_bat_dong_san_ids', {
                plugins: ['remove_button'],
                placeholder: 'Gõ mã căn, tòa, dự án để tìm...',
                searchField: ['text'],
                maxOptions: 50,
            });
        });

        // 4. Xử lý Logic bấm Xóa
        document.querySelectorAll('.btn-delete-cn').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;
                confirmDelete('chủ nhà ' + this.dataset.name, function() {
                    document.getElementById('frmDel_' + id).submit();
                });
            });
        });

        // 5. Xử lý Nạp dữ liệu Modal Edit
        const editForm = document.getElementById('editChuNhaForm');
        const editButtons = document.querySelectorAll('.btn-edit-chu-nha');
        const updateRouteTemplate = `{{ route('nhanvien.admin.chu-nha.update', ['chu_nha' => '__ID__']) }}`;

        function fillEditModal(button) {
            if (!button || !editForm) return;
            const id = button.dataset.id;
            editForm.action = updateRouteTemplate.replace('__ID__', id || '');
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
                    // Hiển thị căn trống, HOẶC căn đang được sở hữu bởi ID này
                    if (bds.chu_nha_id === null || String(bds.chu_nha_id) === String(id)) {
                        let text =
                            `${bds.toa ? bds.toa + ' - ' : ''}${bds.ma_can || 'BĐS #' + bds.id} (${bds.du_an ? bds.du_an.ten_du_an : 'Khác'})`;
                        window.editBdsSelect.addOption({
                            value: bds.id,
                            text: text
                        });
                    }
                });
                window.editBdsSelect.setValue(selectedIds);
            }
        }

        editButtons.forEach((btn) => {
            btn.addEventListener('click', function() {
                fillEditModal(this);
            });
        });

        @if ($errors->any() && old('_form_mode') === 'create')
            const createModal = new bootstrap.Modal(document.getElementById('modalCreateChuNha'));
            createModal.show();
        @endif

        @if ($errors->any() && old('_form_mode') === 'edit')
            const editModalEl = document.getElementById('modalEditChuNha');
            const oldBtn = document.querySelector(`.btn-edit-chu-nha[data-id="{{ old('_edit_id') }}"]`);
            if (oldBtn) fillEditModal(oldBtn);
            const editModal = new bootstrap.Modal(editModalEl);
            editModal.show();
        @endif
    </script>
@endpush
