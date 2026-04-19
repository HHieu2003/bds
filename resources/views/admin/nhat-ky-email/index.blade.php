@extends('admin.layouts.master')
@section('title', 'Nhật ký Email')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h1 class="page-header-title mb-1"><i class="fas fa-envelope-open-text text-primary"></i> Nhật ký Email</h1>
            <div style="font-size:.78rem;color:var(--text-sub)">
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <span><strong>{{ number_format($thongKe['tong']) }}</strong> tổng email</span>
                    <span
                        style="width:4px;height:4px;border-radius:50%;background:var(--text-muted);display:inline-block"></span>
                    <span style="color:#198754"><strong>{{ number_format($thongKe['thanh_cong']) }}</strong> thành
                        công</span>
                    <span
                        style="width:4px;height:4px;border-radius:50%;background:var(--text-muted);display:inline-block"></span>
                    <span style="color:#dc3545"><strong>{{ number_format($thongKe['that_bai']) }}</strong> thất bại</span>
                    <span
                        style="width:4px;height:4px;border-radius:50%;background:var(--text-muted);display:inline-block"></span>
                    <span style="color:#0d6efd"><strong>{{ number_format($thongKe['thu_cong']) }}</strong> gửi thủ
                        công</span>
                </div>
            </div>
        </div>
        <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalGuiEmail">
            <i class="fas fa-paper-plane me-1"></i> Gửi email thủ công
        </button>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert">
            <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('nhanvien.admin.nhat-ky-email.index') }}">
                <div class="row g-2 align-items-center">
                    <div class="col-12 col-md-3">
                        <input type="text" name="tim_kiem" class="form-control" value="{{ request('tim_kiem') }}"
                            placeholder="Tìm email, tiêu đề, tên khách...">
                    </div>
                    <div class="col-6 col-md-2">
                        <select name="loai_email" class="form-select">
                            <option value="">Loại email</option>
                            @foreach (['dat_lich_hen' => 'Đặt lịch hẹn', 'ky_gui' => 'Ký gửi', 'canh_bao_gia' => 'Cảnh báo giá', 'xac_thuc' => 'Xác thực', 'chao_mung' => 'Chào mừng', 'thu_cong' => 'Thủ công'] as $k => $v)
                                <option value="{{ $k }}" @selected(request('loai_email') === $k)>{{ $v }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 col-md-2">
                        <select name="trang_thai" class="form-select">
                            <option value="">Trạng thái</option>
                            <option value="thanh_cong" @selected(request('trang_thai') === 'thanh_cong')>Thành công</option>
                            <option value="that_bai" @selected(request('trang_thai') === 'that_bai')>Thất bại</option>
                        </select>
                    </div>
                    <div class="col-6 col-md-2">
                        <input type="date" name="tu_ngay" class="form-control" value="{{ request('tu_ngay') }}">
                    </div>
                    <div class="col-6 col-md-2">
                        <input type="date" name="den_ngay" class="form-control" value="{{ request('den_ngay') }}">
                    </div>
                    <div class="col-12 col-md-1 d-grid">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 60px">#</th>
                        <th>Người nhận</th>
                        <th>Tiêu đề</th>
                        <th style="width: 130px">Loại</th>
                        <th style="width: 110px">Trạng thái</th>
                        <th style="width: 180px">Thời điểm gửi</th>
                        <th style="width: 170px">Nhân viên gửi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($nhatKyEmails as $i => $item)
                        <tr>
                            <td class="text-muted">{{ $nhatKyEmails->firstItem() + $i }}</td>
                            <td>
                                <div class="fw-semibold">{{ $item->email_nguoi_nhan }}</div>
                                @if ($item->khachHang)
                                    <div class="small text-muted">{{ $item->khachHang->ho_ten }} -
                                        {{ $item->khachHang->so_dien_thoai }}</div>
                                @endif
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $item->tieu_de }}</div>
                                @if ($item->loi)
                                    <div class="small text-danger">Lỗi: {{ $item->loi }}</div>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $item->loai_email }}</span>
                            </td>
                            <td>
                                @if ($item->trang_thai === 'thanh_cong')
                                    <span class="badge bg-success">Thành công</span>
                                @elseif($item->trang_thai === 'that_bai')
                                    <span class="badge bg-danger">Thất bại</span>
                                @else
                                    <span class="badge bg-warning text-dark">{{ $item->trang_thai }}</span>
                                @endif
                            </td>
                            <td>
                                {{ optional($item->thoi_diem_gui)->format('d/m/Y H:i') ?? optional($item->created_at)->format('d/m/Y H:i') }}
                            </td>
                            <td>{{ optional($item->nhanVien)->ho_ten ?? 'Hệ thống' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">Chưa có dữ liệu nhật ký email.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($nhatKyEmails->hasPages())
            <div class="card-footer bg-white border-top p-3 d-flex justify-content-end">
                {{ $nhatKyEmails->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

    <div class="modal fade" id="modalGuiEmail" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <form class="modal-content border-0 shadow" method="POST"
                action="{{ route('nhanvien.admin.nhat-ky-email.send') }}">
                @csrf
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold"><i class="fas fa-paper-plane text-primary me-2"></i>Gửi email thủ công
                        cho khách hàng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Chọn khách hàng (tùy chọn)</label>
                            <select class="form-select" id="manual_khach_hang_id" name="khach_hang_id">
                                <option value="">-- Chọn khách hàng --</option>
                                @foreach ($khachHangs as $kh)
                                    <option value="{{ $kh->id }}" data-email="{{ $kh->email }}">
                                        {{ $kh->ho_ten }} - {{ $kh->so_dien_thoai }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Loại email <span class="text-danger">*</span></label>
                            <select class="form-select" name="loai_email" required>
                                <option value="thu_cong">Thủ công</option>
                                <option value="dat_lich_hen">Đặt lịch hẹn</option>
                                <option value="ky_gui">Ký gửi</option>
                                <option value="canh_bao_gia">Cảnh báo giá</option>
                                <option value="xac_thuc">Xác thực</option>
                                <option value="chao_mung">Chào mừng</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Email người nhận <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="manual_email_nguoi_nhan"
                                name="email_nguoi_nhan" value="{{ old('email_nguoi_nhan') }}" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Tiêu đề <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="tieu_de" value="{{ old('tieu_de') }}"
                                required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Nội dung <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="noi_dung" rows="8" required>{{ old('noi_dung') }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane me-1"></i>Gửi
                        email</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectKh = document.getElementById('manual_khach_hang_id');
            const inputEmail = document.getElementById('manual_email_nguoi_nhan');

            if (selectKh && inputEmail) {
                selectKh.addEventListener('change', function() {
                    const selected = this.options[this.selectedIndex];
                    const email = selected ? selected.getAttribute('data-email') : '';
                    if (email) inputEmail.value = email;
                });
            }

            @if ($errors->any())
                const modalEl = document.getElementById('modalGuiEmail');
                if (modalEl && window.bootstrap) {
                    const modal = new bootstrap.Modal(modalEl);
                    modal.show();
                }
            @endif
        });
    </script>
@endpush
