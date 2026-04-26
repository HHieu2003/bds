@extends('admin.layouts.master')
@section('title', 'Cấu hình Lãi suất Ngân hàng')

@section('content')
    <div class="page-header d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h1 class="page-header-title d-flex align-items-center">
                <i class="fas fa-university text-primary me-2"></i> Chính sách vay Ngân hàng
                <span class="badge bg-primary text-white ms-3" style="font-size: 0.8rem;">Tổng: {{ $tongNganHang }}</span>
            </h1>
            <div class="page-header-sub mt-1">Quản lý tham số tính toán khoản vay cho Khách hàng</div>
        </div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createNganHangModal">
            <i class="fas fa-plus me-1"></i> Thêm Ngân Hàng
        </button>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-exclamation-circle me-1"></i> Vui lòng kiểm tra lại thông tin nhập vào!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <form action="{{ route('nhanvien.admin.ngan-hang.index') }}" method="GET" class="row g-3 align-items-center">

                <div class="col-12 col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-search"></i></span>
                        <input type="text" name="keyword" class="form-control border-start-0 ps-0"
                            placeholder="Nhập tên ngân hàng cần tìm..." value="{{ request('keyword') }}">
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <select name="trang_thai" class="form-select">
                        <option value="">-- Tất cả trạng thái --</option>
                        <option value="1" {{ request('trang_thai') === '1' ? 'selected' : '' }}>Đang áp dụng</option>
                        <option value="0" {{ request('trang_thai') === '0' ? 'selected' : '' }}>Tạm ngưng</option>
                    </select>
                </div>

                <div class="col-12 col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1"><i class="fas fa-filter me-1"></i> Lọc dữ
                        liệu</button>
                    @if (request()->anyFilled(['keyword', 'trang_thai']))
                        <a href="{{ route('nhanvien.admin.ngan-hang.index') }}" class="btn btn-light border"
                            title="Xóa bộ lọc">
                            <i class="fas fa-undo"></i>
                        </a>
                    @endif
                </div>

            </form>
        </div>
    </div>

    @if (request()->anyFilled(['keyword', 'trang_thai']))
        <div class="mb-3 text-muted"><i class="fas fa-info-circle me-1"></i> Tìm thấy <strong>{{ $tongTimKiem }}</strong>
            kết quả phù hợp.</div>
    @endif

    <div class="row g-4">
        @forelse($nganHangs as $nganHang)
            <div class="col-12 col-md-6 col-xl-4">
                <div class="card h-100 border-0 shadow-sm transition-hover">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                            <div class="bg-light rounded p-2 d-flex align-items-center justify-content-center"
                                style="width: 70px; height: 50px;">
                                @if ($nganHang->logo)
                                    <img src="{{ \Storage::disk('r2')->url($nganHang->logo) }}" alt="Logo"
                                        style="max-height: 100%; max-width: 100%;">
                                @else
                                    <i class="fas fa-building text-secondary fs-4"></i>
                                @endif
                            </div>
                            <div class="ms-3 flex-grow-1 min-width-0">
                                <h5 class="mb-1 fw-bold text-dark text-truncate">{{ $nganHang->ten_ngan_hang }}</h5>
                                <span class="badge {{ $nganHang->trang_thai ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $nganHang->trang_thai ? 'Đang áp dụng' : 'Tạm ngưng' }}
                                </span>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <div class="text-muted" style="font-size: 0.75rem;">Lãi suất ưu đãi</div>
                                <div class="fw-bold text-danger fs-5">{{ number_format($nganHang->lai_suat_uu_dai, 2) }}%
                                </div>
                                <div class="text-muted" style="font-size: 0.7rem;">Cố định
                                    {{ $nganHang->thoi_gian_uu_dai }} tháng</div>
                            </div>
                            <div class="col-6">
                                <div class="text-muted" style="font-size: 0.75rem;">Lãi thả nổi (dự kiến)</div>
                                <div class="fw-bold text-dark fs-5">
                                    {{ $nganHang->lai_suat_tha_noi ? number_format($nganHang->lai_suat_tha_noi, 2) . '%' : 'Thỏa thuận' }}
                                </div>
                            </div>
                        </div>

                        <div class="bg-light p-2 rounded" style="font-size: 0.8rem;">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted"><i class="fas fa-hand-holding-usd me-1"></i>Tỷ lệ vay tối
                                    đa:</span>
                                <span class="fw-bold">{{ $nganHang->ty_le_vay_toi_da }}%</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted"><i class="fas fa-calendar-alt me-1"></i>Thời gian tối đa:</span>
                                <span class="fw-bold">{{ $nganHang->thoi_gian_vay_toi_da }} năm</span>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-white border-top-0 pt-0 d-flex gap-2">
                        <button type="button" class="btn btn-sm btn-outline-primary flex-grow-1" data-bs-toggle="modal"
                            data-bs-target="#editNganHangModal{{ $nganHang->id }}">
                            <i class="fas fa-edit"></i> Sửa đổi
                        </button>
                        <form id="deleteNganHangForm{{ $nganHang->id }}"
                            action="{{ route('nhanvien.admin.ngan-hang.destroy', $nganHang->id) }}" method="POST"
                            class="d-inline">
                            @csrf @method('DELETE')
                            <button type="button" class="btn btn-sm btn-outline-danger px-3 btn-delete-ngan-hang"
                                data-form-id="deleteNganHangForm{{ $nganHang->id }}"
                                data-ten-ngan-hang="{{ $nganHang->ten_ngan_hang }}">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="editNganHangModal{{ $nganHang->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content border-0 shadow">
                        <div class="modal-header bg-light">
                            <h5 class="modal-title fw-bold"><i class="fas fa-edit text-primary me-2"></i>Sửa:
                                {{ $nganHang->ten_ngan_hang }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="{{ route('nhanvien.admin.ngan-hang.update', $nganHang->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf @method('PUT')
                            <div class="modal-body row g-3">
                                <div class="col-12 col-md-6">
                                    <label class="form-label">Tên ngân hàng <span class="text-danger">*</span></label>
                                    <input type="text" name="ten_ngan_hang" class="form-control"
                                        value="{{ $nganHang->ten_ngan_hang }}" required>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label">Logo ngân hàng</label>
                                    <input type="file" name="logo" class="form-control" accept="image/*">
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label">Lãi ưu đãi <span class="text-muted"
                                            style="font-size:0.8rem;">(%/năm)</span> <span
                                            class="text-danger">*</span></label>
                                    <input type="number" step="0.01" name="lai_suat_uu_dai" class="form-control"
                                        value="{{ $nganHang->lai_suat_uu_dai }}" required>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label">Thời gian ưu đãi <span class="text-muted"
                                            style="font-size:0.8rem;">(Tháng)</span> <span
                                            class="text-danger">*</span></label>
                                    <input type="number" name="thoi_gian_uu_dai" class="form-control"
                                        value="{{ $nganHang->thoi_gian_uu_dai }}" required>
                                </div>
                                <div class="col-12 col-md-12">
                                    <label class="form-label">Lãi thả nổi <span class="text-muted"
                                            style="font-size:0.8rem;">(%/năm)</span></label>
                                    <input type="number" step="0.01" name="lai_suat_tha_noi" class="form-control"
                                        value="{{ $nganHang->lai_suat_tha_noi }}">
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label">Tỷ lệ vay tối đa <span class="text-muted"
                                            style="font-size:0.8rem;">(%)</span></label>
                                    <input type="number" name="ty_le_vay_toi_da" class="form-control"
                                        value="{{ $nganHang->ty_le_vay_toi_da }}">
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label">Thời gian vay tối đa <span class="text-muted"
                                            style="font-size:0.8rem;">(Năm)</span></label>
                                    <input type="number" name="thoi_gian_vay_toi_da" class="form-control"
                                        value="{{ $nganHang->thoi_gian_vay_toi_da }}">
                                </div>
                                <div class="col-12">
                                    <div class="form-check form-switch mt-2">
                                        <input class="form-check-input" type="checkbox" name="trang_thai" value="1"
                                            {{ $nganHang->trang_thai ? 'checked' : '' }}>
                                        <label class="form-check-label">Kích hoạt hiển thị</label>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer bg-light">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy bỏ</button>
                                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Lưu thay
                                    đổi</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5 bg-white rounded shadow-sm">
                    <i class="fas fa-university text-muted opacity-25 mb-3" style="font-size: 4rem;"></i>
                    <h5 class="text-muted">Chưa có dữ liệu Ngân Hàng</h5>
                    <p class="text-muted small">Vui lòng thêm ngân hàng để hỗ trợ tính toán khoản vay.</p>
                </div>
            </div>
        @endforelse
    </div>

    <div class="modal fade" id="createNganHangModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold"><i class="fas fa-plus-circle text-success me-2"></i>Thêm Ngân hàng
                        liên kết</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('nhanvien.admin.ngan-hang.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body row g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label">Tên ngân hàng <span class="text-danger">*</span></label>
                            <input type="text" name="ten_ngan_hang" class="form-control"
                                placeholder="VD: Vietcombank" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Logo ngân hàng</label>
                            <input type="file" name="logo" class="form-control" accept="image/*">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Lãi ưu đãi <span class="text-muted"
                                    style="font-size:0.8rem;">(%/năm)</span> <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="lai_suat_uu_dai" class="form-control"
                                placeholder="VD: 6.50" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Thời gian ưu đãi <span class="text-muted"
                                    style="font-size:0.8rem;">(Tháng)</span> <span class="text-danger">*</span></label>
                            <input type="number" name="thoi_gian_uu_dai" class="form-control" value="12" required>
                        </div>
                        <div class="col-12 col-md-12">
                            <label class="form-label">Lãi thả nổi <span class="text-muted"
                                    style="font-size:0.8rem;">(%/năm)</span></label>
                            <input type="number" step="0.01" name="lai_suat_tha_noi" class="form-control"
                                placeholder="VD: 10.50">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Tỷ lệ vay tối đa <span class="text-muted"
                                    style="font-size:0.8rem;">(%)</span></label>
                            <input type="number" name="ty_le_vay_toi_da" class="form-control" value="70">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Thời gian vay tối đa <span class="text-muted"
                                    style="font-size:0.8rem;">(Năm)</span></label>
                            <input type="number" name="thoi_gian_vay_toi_da" class="form-control" value="25">
                        </div>
                        <div class="col-12">
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" name="trang_thai" value="1"
                                    checked>
                                <label class="form-check-label">Kích hoạt hiển thị ngay</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy bỏ</button>
                        <button type="submit" class="btn btn-success"><i class="fas fa-check me-1"></i> Thêm
                            mới</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteNganHangConfirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-danger text-white border-0">
                    <h5 class="modal-title fw-bold"><i class="fas fa-triangle-exclamation me-2"></i>Xác nhận xóa</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body py-4">
                    <p class="mb-2">Bạn có chắc chắn muốn xóa ngân hàng sau?</p>
                    <div class="fw-bold text-dark" id="deleteNganHangName"></div>
                    <p class="text-muted mt-3 mb-0" style="font-size: 0.9rem;">Hành động này không thể hoàn tác.</p>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-danger" id="btnConfirmDeleteNganHang">
                        <i class="fas fa-trash-alt me-1"></i> Xóa ngân hàng
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modalEl = document.getElementById('deleteNganHangConfirmModal');
            const nameEl = document.getElementById('deleteNganHangName');
            const confirmBtn = document.getElementById('btnConfirmDeleteNganHang');
            const modal = new bootstrap.Modal(modalEl);
            let targetForm = null;

            document.querySelectorAll('.btn-delete-ngan-hang').forEach(btn => {
                btn.addEventListener('click', function() {
                    const formId = this.dataset.formId;
                    targetForm = document.getElementById(formId);
                    nameEl.textContent = this.dataset.tenNganHang || 'Ngân hàng đã chọn';
                    modal.show();
                });
            });

            confirmBtn.addEventListener('click', function() {
                if (!targetForm) return;
                this.disabled = true;
                this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Đang xóa...';
                targetForm.submit();
            });
        });
    </script>
@endpush

@push('styles')
    <style>
        .transition-hover {
            transition: all 0.3s ease;
        }

        .transition-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
        }
    </style>
@endpush
