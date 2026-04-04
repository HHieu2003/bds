@extends('admin.layouts.master')
@section('title', 'Quản lý Ngân hàng')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">Danh sách Ngân hàng hỗ trợ vay</h3>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalCreateNganHang">
                <i class="fas fa-plus"></i> Thêm mới
            </button>
        </div>

        <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Logo</th>
                        <th>Tên Ngân Hàng</th>
                        <th>Lãi suất ưu đãi</th>
                        <th>Tỷ lệ vay tối đa</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($nganHangs as $nh)
                        <tr>
                            <td>{{ $nh->id }}</td>
                            <td>
                                <img src="{{ $nh->logo_url }}" alt="Logo"
                                    style="width: 50px; height: 50px; object-fit: contain;">
                            </td>
                            <td><strong>{{ $nh->ten_ngan_hang }}</strong></td>
                            <td><span class="badge bg-success">{{ $nh->lai_suat_uu_dai }}% / năm</span></td>
                            <td>Tối đa {{ $nh->ty_le_vay_toi_da }}%</td>
                            <td>
                                @if ($nh->trang_thai)
                                    <span class="badge bg-primary">Hiển thị</span>
                                @else
                                    <span class="badge bg-secondary">Đang ẩn</span>
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn btn-warning btn-sm btn-edit-ngan-hang"
                                    data-bs-toggle="modal" data-bs-target="#modalEditNganHang" data-id="{{ $nh->id }}"
                                    data-ten-ngan-hang="{{ $nh->ten_ngan_hang }}"
                                    data-lai-suat-uu-dai="{{ $nh->lai_suat_uu_dai }}"
                                    data-thoi-gian-uu-dai="{{ $nh->thoi_gian_uu_dai }}"
                                    data-lai-suat-tha-noi="{{ $nh->lai_suat_tha_noi }}"
                                    data-ty-le-vay-toi-da="{{ $nh->ty_le_vay_toi_da }}"
                                    data-thoi-gian-vay-toi-da="{{ $nh->thoi_gian_vay_toi_da }}"
                                    data-trang-thai="{{ $nh->trang_thai ? 1 : 0 }}" data-logo-url="{{ $nh->logo_url }}"
                                    title="Sửa">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <form id="frmDel_{{ $nh->id }}"
                                    action="{{ route('nhanvien.admin.ngan-hang.destroy', $nh->id) }}" method="POST"
                                    class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                <button type="button" class="btn btn-danger btn-sm btn-delete-ngan-hang"
                                    data-id="{{ $nh->id }}" data-name="{{ $nh->ten_ngan_hang }}" title="Xóa">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">Chưa có dữ liệu ngân hàng nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer clearfix">
            {{ $nganHangs->links() }}
        </div>
    </div>

    <div class="modal fade" id="modalCreateNganHang" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <form class="modal-content" method="POST" action="{{ route('nhanvien.admin.ngan-hang.store') }}"
                enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-plus-circle text-primary me-2"></i>Thêm ngân hàng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="_form_mode" value="create">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Tên ngân hàng <span class="text-danger">*</span></label>
                            <input type="text" name="ten_ngan_hang"
                                class="form-control @error('ten_ngan_hang') is-invalid @enderror"
                                value="{{ old('_form_mode') === 'create' ? old('ten_ngan_hang') : '' }}">
                            @error('ten_ngan_hang')
                                @if (old('_form_mode') === 'create')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @endif
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Logo</label>
                            <input type="file" name="logo" accept="image/*"
                                class="form-control @error('logo') is-invalid @enderror">
                            @error('logo')
                                @if (old('_form_mode') === 'create')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @endif
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Lãi suất ưu đãi (%) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" max="100" name="lai_suat_uu_dai"
                                class="form-control @error('lai_suat_uu_dai') is-invalid @enderror"
                                value="{{ old('_form_mode') === 'create' ? old('lai_suat_uu_dai') : '' }}">
                            @error('lai_suat_uu_dai')
                                @if (old('_form_mode') === 'create')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @endif
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Thời gian ưu đãi (tháng) <span class="text-danger">*</span></label>
                            <input type="number" min="0" name="thoi_gian_uu_dai"
                                class="form-control @error('thoi_gian_uu_dai') is-invalid @enderror"
                                value="{{ old('_form_mode') === 'create' ? old('thoi_gian_uu_dai') : '' }}">
                            @error('thoi_gian_uu_dai')
                                @if (old('_form_mode') === 'create')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @endif
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Lãi suất thả nổi (%)</label>
                            <input type="number" step="0.01" min="0" max="100" name="lai_suat_tha_noi"
                                class="form-control @error('lai_suat_tha_noi') is-invalid @enderror"
                                value="{{ old('_form_mode') === 'create' ? old('lai_suat_tha_noi') : '' }}">
                            @error('lai_suat_tha_noi')
                                @if (old('_form_mode') === 'create')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @endif
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Tỷ lệ vay tối đa (%) <span class="text-danger">*</span></label>
                            <input type="number" min="1" max="100" name="ty_le_vay_toi_da"
                                class="form-control @error('ty_le_vay_toi_da') is-invalid @enderror"
                                value="{{ old('_form_mode') === 'create' ? old('ty_le_vay_toi_da') : '' }}">
                            @error('ty_le_vay_toi_da')
                                @if (old('_form_mode') === 'create')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @endif
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Thời gian vay tối đa (năm) <span
                                    class="text-danger">*</span></label>
                            <input type="number" min="1" max="50" name="thoi_gian_vay_toi_da"
                                class="form-control @error('thoi_gian_vay_toi_da') is-invalid @enderror"
                                value="{{ old('_form_mode') === 'create' ? old('thoi_gian_vay_toi_da') : '' }}">
                            @error('thoi_gian_vay_toi_da')
                                @if (old('_form_mode') === 'create')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @endif
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Trạng thái <span class="text-danger">*</span></label>
                            <select name="trang_thai" class="form-select @error('trang_thai') is-invalid @enderror">
                                <option value="1" @selected(old('_form_mode') === 'create' ? old('trang_thai', '1') == '1' : true)>Hiển thị</option>
                                <option value="0" @selected(old('_form_mode') === 'create' && old('trang_thai') == '0')>Đang ẩn</option>
                            </select>
                            @error('trang_thai')
                                @if (old('_form_mode') === 'create')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @endif
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Thêm ngân
                        hàng</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="modalEditNganHang" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <form id="editNganHangForm" class="modal-content" method="POST" action="#"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-edit text-primary me-2"></i>Sửa ngân hàng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="_form_mode" value="edit">
                    <input type="hidden" name="_edit_id" id="edit_id" value="{{ old('_edit_id') }}">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Logo hiện tại</label>
                            <div class="border rounded d-flex align-items-center justify-content-center p-2"
                                style="height: 88px;">
                                <img id="edit_logo_preview" src="{{ asset('images/default-bank.png') }}" alt="Logo"
                                    style="max-width: 100%; max-height: 72px; object-fit: contain;">
                            </div>
                        </div>

                        <div class="col-md-8">
                            <label class="form-label">Logo mới</label>
                            <input type="file" name="logo" accept="image/*"
                                class="form-control @error('logo') is-invalid @enderror">
                            <div class="form-text">Để trống nếu không đổi logo.</div>
                            @error('logo')
                                @if (old('_form_mode') === 'edit')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @endif
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Tên ngân hàng <span class="text-danger">*</span></label>
                            <input type="text" id="edit_ten_ngan_hang" name="ten_ngan_hang"
                                class="form-control @error('ten_ngan_hang') is-invalid @enderror"
                                value="{{ old('_form_mode') === 'edit' ? old('ten_ngan_hang') : '' }}">
                            @error('ten_ngan_hang')
                                @if (old('_form_mode') === 'edit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @endif
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Lãi suất ưu đãi (%) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" max="100"
                                id="edit_lai_suat_uu_dai" name="lai_suat_uu_dai"
                                class="form-control @error('lai_suat_uu_dai') is-invalid @enderror"
                                value="{{ old('_form_mode') === 'edit' ? old('lai_suat_uu_dai') : '' }}">
                            @error('lai_suat_uu_dai')
                                @if (old('_form_mode') === 'edit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @endif
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Thời gian ưu đãi (tháng) <span class="text-danger">*</span></label>
                            <input type="number" min="0" id="edit_thoi_gian_uu_dai" name="thoi_gian_uu_dai"
                                class="form-control @error('thoi_gian_uu_dai') is-invalid @enderror"
                                value="{{ old('_form_mode') === 'edit' ? old('thoi_gian_uu_dai') : '' }}">
                            @error('thoi_gian_uu_dai')
                                @if (old('_form_mode') === 'edit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @endif
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Lãi suất thả nổi (%)</label>
                            <input type="number" step="0.01" min="0" max="100"
                                id="edit_lai_suat_tha_noi" name="lai_suat_tha_noi"
                                class="form-control @error('lai_suat_tha_noi') is-invalid @enderror"
                                value="{{ old('_form_mode') === 'edit' ? old('lai_suat_tha_noi') : '' }}">
                            @error('lai_suat_tha_noi')
                                @if (old('_form_mode') === 'edit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @endif
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Tỷ lệ vay tối đa (%) <span class="text-danger">*</span></label>
                            <input type="number" min="1" max="100" id="edit_ty_le_vay_toi_da"
                                name="ty_le_vay_toi_da"
                                class="form-control @error('ty_le_vay_toi_da') is-invalid @enderror"
                                value="{{ old('_form_mode') === 'edit' ? old('ty_le_vay_toi_da') : '' }}">
                            @error('ty_le_vay_toi_da')
                                @if (old('_form_mode') === 'edit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @endif
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Thời gian vay tối đa (năm) <span
                                    class="text-danger">*</span></label>
                            <input type="number" min="1" max="50" id="edit_thoi_gian_vay_toi_da"
                                name="thoi_gian_vay_toi_da"
                                class="form-control @error('thoi_gian_vay_toi_da') is-invalid @enderror"
                                value="{{ old('_form_mode') === 'edit' ? old('thoi_gian_vay_toi_da') : '' }}">
                            @error('thoi_gian_vay_toi_da')
                                @if (old('_form_mode') === 'edit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @endif
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Trạng thái <span class="text-danger">*</span></label>
                            <select id="edit_trang_thai" name="trang_thai"
                                class="form-select @error('trang_thai') is-invalid @enderror">
                                <option value="1" @selected(old('_form_mode') === 'edit' ? old('trang_thai', '1') == '1' : false)>Hiển thị</option>
                                <option value="0" @selected(old('_form_mode') === 'edit' && old('trang_thai') == '0')>Đang ẩn</option>
                            </select>
                            @error('trang_thai')
                                @if (old('_form_mode') === 'edit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @endif
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('.btn-delete-ngan-hang').forEach((btn) => {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;
                const name = this.dataset.name || 'ngân hàng';
                confirmDelete('ngân hàng ' + name, function() {
                    document.getElementById('frmDel_' + id).submit();
                });
            });
        });

        const editForm = document.getElementById('editNganHangForm');
        const updateRouteTemplate = `{{ route('nhanvien.admin.ngan-hang.update', ['ngan_hang' => '__ID__']) }}`;
        const editButtons = document.querySelectorAll('.btn-edit-ngan-hang');

        function fillEditModal(button) {
            if (!button || !editForm) return;
            const id = button.dataset.id;
            editForm.action = updateRouteTemplate.replace('__ID__', id || '');

            document.getElementById('edit_id').value = id || '';
            document.getElementById('edit_ten_ngan_hang').value = button.dataset.tenNganHang || '';
            document.getElementById('edit_lai_suat_uu_dai').value = button.dataset.laiSuatUuDai || '';
            document.getElementById('edit_thoi_gian_uu_dai').value = button.dataset.thoiGianUuDai || '';
            document.getElementById('edit_lai_suat_tha_noi').value = button.dataset.laiSuatThaNoi || '';
            document.getElementById('edit_ty_le_vay_toi_da').value = button.dataset.tyLeVayToiDa || '';
            document.getElementById('edit_thoi_gian_vay_toi_da').value = button.dataset.thoiGianVayToiDa || '';
            document.getElementById('edit_trang_thai').value = button.dataset.trangThai || '1';
            document.getElementById('edit_logo_preview').src = button.dataset.logoUrl ||
                '{{ asset('images/default-bank.png') }}';
        }

        editButtons.forEach((btn) => {
            btn.addEventListener('click', function() {
                fillEditModal(this);
            });
        });

        @if ($errors->any() && old('_form_mode') === 'create')
            new bootstrap.Modal(document.getElementById('modalCreateNganHang')).show();
        @endif

        @if ($errors->any() && old('_form_mode') === 'edit')
            const oldBtn = document.querySelector(`.btn-edit-ngan-hang[data-id="{{ old('_edit_id') }}"]`);
            if (oldBtn) fillEditModal(oldBtn);

            const editModal = new bootstrap.Modal(document.getElementById('modalEditNganHang'));
            editModal.show();

            document.getElementById('edit_ten_ngan_hang').value = `{{ old('ten_ngan_hang', '') }}`;
            document.getElementById('edit_lai_suat_uu_dai').value = `{{ old('lai_suat_uu_dai', '') }}`;
            document.getElementById('edit_thoi_gian_uu_dai').value = `{{ old('thoi_gian_uu_dai', '') }}`;
            document.getElementById('edit_lai_suat_tha_noi').value = `{{ old('lai_suat_tha_noi', '') }}`;
            document.getElementById('edit_ty_le_vay_toi_da').value = `{{ old('ty_le_vay_toi_da', '') }}`;
            document.getElementById('edit_thoi_gian_vay_toi_da').value = `{{ old('thoi_gian_vay_toi_da', '') }}`;
            document.getElementById('edit_trang_thai').value = `{{ old('trang_thai', '1') }}`;
        @endif
    </script>
@endpush
