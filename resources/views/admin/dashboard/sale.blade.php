@extends('admin.layouts.master')
@section('title', 'Không gian làm việc Sale')

@section('content')
    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="page-header-title" style="font-size: 1.5rem;"><i class="fas fa-briefcase text-primary"></i> Không gian
                làm việc</h1>
            <div class="page-header-sub mt-1">Xin chào <strong>{{ $nhanVien->ho_ten }}</strong> · Hôm nay là
                {{ now()->format('l, d/m/Y') }}</div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('nhanvien.admin.khach-hang.create') }}" class="btn btn-primary"><i
                    class="fas fa-user-plus me-1"></i> Khách mới</a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-md-6">
            <div class="stat-card" style="border-left: 4px solid var(--primary);">
                <div class="stat-icon orange"><i class="fas fa-users"></i></div>
                <div class="stat-info">
                    <div class="stat-num">{{ number_format($tongKhachCuaToi) }}</div>
                    <div class="stat-label">Khách hàng hệ thống</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="stat-card" style="border-left: 4px solid #3b82f6;">
                <div class="stat-icon blue"><i class="fas fa-calendar-check"></i></div>
                <div class="stat-info">
                    <div class="stat-num">{{ number_format($lichHenThang) }}</div>
                    <div class="stat-label">Lịch hẹn tháng này</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- Cột 1: Cần tư vấn ngay (Leads) --}}
        <div class="col-12 col-xl-6">
            <div class="card h-100" style="border-top: 3px solid var(--red);">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <div class="fw-bold text-danger"><i class="fas fa-phone-volume me-2"></i> Leads Cần Tư Vấn Ngay</div>
                    <span class="badge bg-danger rounded-pill">{{ $leadsCuaToi->count() }}</span>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($leadsCuaToi as $lead)
                            <li class="list-group-item p-3 hover-bg-light transition">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="person-cell">
                                        <div class="avatar-mini bg-warning text-dark">
                                            {{ mb_strtoupper(mb_substr($lead->ho_ten, 0, 1)) }}</div>
                                        <div class="person-info ms-2">
                                            <div class="person-name fw-bold text-dark">{{ $lead->ho_ten }}</div>
                                            <div class="contact-row text-muted" style="font-size: 0.8rem;"><i
                                                    class="fas fa-phone me-1"></i>{{ $lead->so_dien_thoai }}</div>
                                            <div class="mt-1" style="font-size: 0.75rem;"><i
                                                    class="fas fa-comment-dots text-primary me-1"></i>{{ Str::limit($lead->noi_dung, 50) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <div class="text-muted" style="font-size: 0.7rem; margin-bottom: 5px;">
                                            {{ $lead->created_at->diffForHumans() }}</div>
                                        <a href="{{ route('nhanvien.admin.lien-he.show', $lead->id) }}"
                                            class="btn btn-sm btn-outline-danger px-3 rounded-pill">Xử lý</a>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item text-center py-5 text-muted">
                                <i class="fas fa-check-circle text-success fs-1 mb-2 opacity-50"></i>
                                <p class="mb-0">Tuyệt vời! Bạn đã xử lý hết yêu cầu.</p>
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        {{-- Cột 2: Lịch dẫn khách hôm nay --}}
        <div class="col-12 col-xl-6">
            <div class="card h-100" style="border-top: 3px solid var(--blue);">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <div class="fw-bold text-primary"><i class="fas fa-map-marked-alt me-2"></i> Lịch Dẫn Khách Hôm Nay
                    </div>
                    <span class="badge bg-primary rounded-pill">{{ $lichHenHomNay->count() }}</span>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($lichHenHomNay as $lh)
                            <li class="list-group-item p-3 hover-bg-light transition">
                                <div class="d-flex gap-3 align-items-center">
                                    <div class="bg-primary text-white rounded px-2 py-1 text-center"
                                        style="min-width: 60px;">
                                        <div class="fw-bold fs-5 lh-1">
                                            {{ \Carbon\Carbon::parse($lh->thoi_gian_hen)->format('H:i') }}</div>
                                    </div>
                                    <div class="flex-grow-1 min-width-0">
                                        <div class="fw-bold text-dark text-truncate">
                                            {{ $lh->khachHang?->ho_ten ?? 'Khách lẻ' }}</div>
                                        <div class="text-muted text-truncate" style="font-size: 0.8rem;">
                                            <i class="fas fa-home me-1"></i>
                                            {{ $lh->batDongSan?->tieu_de ?? 'Chưa chốt BĐS' }}
                                        </div>
                                    </div>
                                    <a href="{{ route('nhanvien.admin.lich-hen.show', $lh->id) }}"
                                        class="btn btn-sm btn-light border btn-action-view"><i class="fas fa-eye"></i></a>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item text-center py-5 text-muted">
                                <i class="fas fa-coffee fs-1 mb-2 opacity-50"></i>
                                <p class="mb-0">Hôm nay chưa có lịch hẹn nào.</p>
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
