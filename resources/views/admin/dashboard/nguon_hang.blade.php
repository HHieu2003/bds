@extends('admin.layouts.master')
@section('title', 'Tổng quan Nguồn Hàng')

@section('content')
    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="page-header-title" style="font-size: 1.5rem;"><i class="fas fa-boxes text-success"></i> Quản lý Nguồn
                Hàng</h1>
            <div class="page-header-sub mt-1">Cập nhật lúc: {{ now()->format('H:i - d/m/Y') }}</div>
        </div>
        <div>
            <a href="{{ route('nhanvien.admin.bat-dong-san.create') }}" class="btn btn-success"><i
                    class="fas fa-plus me-1"></i> Đăng BĐS Mới</a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon navy"><i class="fas fa-building"></i></div>
                <div class="stat-info">
                    <div class="stat-num">{{ number_format($tongQuan['tong_bds']) }}</div>
                    <div class="stat-label">Tổng Kho BĐS</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
                <div class="stat-info">
                    <div class="stat-num">{{ number_format($tongQuan['bds_con_hang']) }}</div>
                    <div class="stat-label">BĐS Đang Bán/Thuê</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon pink"><i class="fas fa-file-signature"></i></div>
                <div class="stat-info">
                    <div class="stat-num">{{ number_format($tongQuan['ky_gui_cho']) }}</div>
                    <div class="stat-label">Ký Gửi Chờ Duyệt</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon orange"><i class="fas fa-user-tie"></i></div>
                <div class="stat-info">
                    <div class="stat-num">{{ number_format($tongQuan['tong_chu_nha']) }}</div>
                    <div class="stat-label">Data Chủ Nhà</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- Bảng Ký Gửi --}}
        <div class="col-12 col-lg-5">
            <div class="card h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <div class="fw-bold"><i class="fas fa-clipboard-list me-2 text-danger"></i> Yêu cầu Ký gửi mới</div>
                    <a href="{{ route('nhanvien.admin.ky-gui.index') }}" class="text-decoration-none"
                        style="font-size: 0.8rem;">Xem tất cả</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <tbody>
                                @forelse($kyGuiChoDuyet as $kg)
                                    <tr>
                                        <td>
                                            <div class="fw-bold text-dark" style="font-size: 0.85rem;">
                                                {{ $kg->khachHang?->ho_ten ?? 'Khách hàng' }}</div>
                                            <div class="text-muted" style="font-size: 0.75rem;">
                                                {{ $kg->created_at->format('d/m/Y H:i') }}</div>
                                        </td>
                                        <td><span class="badge bg-warning text-dark" style="font-size: 0.7rem;">Chờ
                                                duyệt</span></td>
                                        <td class="text-end"><a href="{{ route('nhanvien.admin.ky-gui.show', $kg->id) }}"
                                                class="btn btn-sm btn-light border">Chi tiết</a></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-4 text-muted">Không có yêu cầu ký gửi nào
                                            chờ duyệt.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Bảng BĐS Vừa Lên Kệ --}}
        <div class="col-12 col-lg-7">
            <div class="card h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <div class="fw-bold"><i class="fas fa-home me-2 text-success"></i> Kho hàng vừa cập nhật</div>
                    <a href="{{ route('nhanvien.admin.bat-dong-san.index') }}" class="text-decoration-none"
                        style="font-size: 0.8rem;">Vào Kho</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Bất động sản</th>
                                    <th>Giá</th>
                                    <th>Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bdsMoiNhat as $bds)
                                    <tr>
                                        <td>
                                            <div class="fw-bold text-dark text-truncate"
                                                style="max-width: 250px; font-size: 0.85rem;">{{ $bds->tieu_de }}</div>
                                            <div class="text-muted" style="font-size: 0.75rem;">Mã: {{ $bds->id }}
                                            </div>
                                        </td>
                                        <td class="text-danger fw-bold" style="font-size: 0.85rem;">
                                            {{ number_format($bds->gia, 2) }} tỷ</td>
                                        <td>
                                            <span
                                                class="badge {{ $bds->trang_thai == 'con_hang' ? 'bg-success' : 'bg-secondary' }}"
                                                style="font-size: 0.7rem;">
                                                {{ $bds->trang_thai == 'con_hang' ? 'Còn hàng' : 'Đã bán/Thuê' }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-4 text-muted">Chưa có BĐS nào trong kho.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
