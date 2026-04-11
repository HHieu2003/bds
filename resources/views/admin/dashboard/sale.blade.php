@extends('admin.layouts.master')
@section('title', 'Không gian làm việc Sale')

@push('styles')
    <style>
        .sale-kpi-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: .95rem 1rem;
            box-shadow: var(--sh-xs);
            height: 100%;
        }

        .sale-kpi-value {
            font-size: 1.45rem;
            font-weight: 800;
            line-height: 1.15;
            color: var(--navy);
        }

        .sale-kpi-label {
            color: var(--text-sub);
            font-size: .78rem;
            margin-top: .2rem;
        }

        .sale-chip {
            border-radius: 999px;
            padding: .28rem .6rem;
            font-size: .72rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: .35rem;
        }

        .sale-board {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 14px;
            box-shadow: var(--sh-xs);
            overflow: hidden;
            height: 100%;
        }

        .sale-board-head {
            padding: .9rem 1rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #fff;
        }

        .sale-board-body {
            max-height: 430px;
            overflow: auto;
        }

        .sale-item {
            padding: .9rem 1rem;
            border-bottom: 1px solid #f1f5f9;
        }

        .sale-item:last-child {
            border-bottom: 0;
        }

        .sale-item-top {
            display: flex;
            justify-content: space-between;
            gap: .75rem;
            align-items: flex-start;
        }

        .sale-time-pill {
            min-width: 68px;
            text-align: center;
            border-radius: 8px;
            padding: .34rem .45rem;
            font-weight: 800;
            font-size: .78rem;
        }

        .sale-priority {
            border-left: 4px solid #f97316;
            background: #fff7ed;
        }

        .sale-overdue {
            border-left: 4px solid #ef4444;
            background: #fff5f5;
        }

        .sale-progress {
            height: 8px;
            border-radius: 10px;
            overflow: hidden;
            background: #eef2f7;
        }

        .sale-empty {
            text-align: center;
            color: var(--text-muted);
            padding: 2.2rem 1rem;
        }
    </style>
@endpush

@section('content')
    @php
        $todayLabel = now()->translatedFormat('l, d/m/Y');
        $hoanThanhSafe = max(0, $lichHenHoanThanhThang ?? 0);
        $huySafe = max(0, $lichHenHuyThang ?? 0);
        $totalCloseSafe = max(1, $hoanThanhSafe + $huySafe);
        $ptHoanThanh = round(($hoanThanhSafe / $totalCloseSafe) * 100, 1);
        $ptHuy = round(($huySafe / $totalCloseSafe) * 100, 1);
    @endphp

    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div>
            <h1 class="page-header-title mb-1" style="font-size:1.5rem;"><i
                    class="fas fa-briefcase text-primary me-2"></i>Không gian làm việc Sale</h1>
            <div class="page-header-sub">Xin chào <strong>{{ $nhanVien->ho_ten }}</strong> · {{ $todayLabel }}</div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('nhanvien.admin.khach-hang.create') }}" class="btn btn-primary"><i
                    class="fas fa-user-plus me-1"></i>Khách mới</a>
            <a href="{{ route('nhanvien.admin.lich-hen.index') }}" class="btn btn-outline-primary"><i
                    class="fas fa-calendar-check me-1"></i>Lịch hẹn</a>
            <a href="{{ route('nhanvien.admin.lien-he.index') }}" class="btn btn-outline-secondary"><i
                    class="fas fa-headset me-1"></i>Leads</a>
        </div>
    </div>

    @if (($leadMoiHomNay ?? 0) > 0 || ($lichHenCanXuLy ?? 0) > 0 || ($lichHen2hToi ?? 0) > 0 || ($leadsQuaHan ?? 0) > 0)
        <div class="alert alert-warning border-0 d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
            <div class="fw-bold"><i class="fas fa-bolt me-1"></i>Cảnh báo ưu tiên hôm nay</div>
            <div class="d-flex flex-wrap gap-2">
                @if (($leadMoiHomNay ?? 0) > 0)
                    <span class="sale-chip bg-white text-dark"><i class="fas fa-bullhorn text-warning"></i>Lead mới:
                        {{ $leadMoiHomNay }}</span>
                @endif
                @if (($lichHenCanXuLy ?? 0) > 0)
                    <span class="sale-chip bg-white text-dark"><i class="fas fa-list-check text-danger"></i>Lịch cần xử lý:
                        {{ $lichHenCanXuLy }}</span>
                @endif
                @if (($lichHen2hToi ?? 0) > 0)
                    <span class="sale-chip bg-white text-dark"><i class="fas fa-clock text-primary"></i>Lịch trong 2h:
                        {{ $lichHen2hToi }}</span>
                @endif
                @if (($leadsQuaHan ?? 0) > 0)
                    <span class="sale-chip bg-white text-dark"><i class="fas fa-triangle-exclamation text-danger"></i>Lead
                        quá hạn gọi: {{ $leadsQuaHan }}</span>
                @endif
            </div>
        </div>
    @endif

    <div class="row g-3 mb-3">
        <div class="col-6 col-xl-2">
            <div class="sale-kpi-card">
                <div class="sale-kpi-value text-primary">{{ number_format($tongKhachCuaToi) }}</div>
                <div class="sale-kpi-label">Khách hàng phụ trách</div>
            </div>
        </div>
        <div class="col-6 col-xl-2">
            <div class="sale-kpi-card">
                <div class="sale-kpi-value text-info">{{ number_format($lichHenThang) }}</div>
                <div class="sale-kpi-label">Lịch hẹn tháng này</div>
            </div>
        </div>
        <div class="col-6 col-xl-2">
            <div class="sale-kpi-card">
                <div class="sale-kpi-value text-success">{{ number_format($lichHenHoanThanhThang ?? 0) }}</div>
                <div class="sale-kpi-label">Đã hoàn thành tháng</div>
            </div>
        </div>
        <div class="col-6 col-xl-2">
            <div class="sale-kpi-card">
                <div class="sale-kpi-value text-danger">{{ number_format($lichHenHuyThang ?? 0) }}</div>
                <div class="sale-kpi-label">Boom/Hủy tháng</div>
            </div>
        </div>
        <div class="col-6 col-xl-2">
            <div class="sale-kpi-card">
                <div class="sale-kpi-value text-warning">{{ number_format($leadDaChotThang ?? 0) }}</div>
                <div class="sale-kpi-label">Lead chốt tháng</div>
            </div>
        </div>
        <div class="col-6 col-xl-2">
            <div class="sale-kpi-card">
                <div class="sale-kpi-value">{{ $tiLeChotLich ?? 0 }}%</div>
                <div class="sale-kpi-label">Tỷ lệ chốt lịch</div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-12 col-lg-8">
            <div class="sale-board">
                <div class="sale-board-head">
                    <div class="fw-bold text-danger"><i class="fas fa-phone-volume me-2"></i>Leads cần xử lý ngay</div>
                    <span class="badge bg-danger rounded-pill">{{ $leadsCuaToi->count() }}</span>
                </div>
                <div class="sale-board-body">
                    @forelse($leadsCuaToi as $lead)
                        @php
                            $isMoi = $lead->trang_thai === 'moi';
                            $isQuaHan = optional($lead->created_at)->lt(now()->subHours(2)) && $isMoi;
                        @endphp
                        <div class="sale-item {{ $isQuaHan ? 'sale-overdue' : ($isMoi ? 'sale-priority' : '') }}">
                            <div class="sale-item-top">
                                <div>
                                    <div class="fw-bold text-dark">{{ $lead->ho_ten }}</div>
                                    <div class="small text-muted"><i
                                            class="fas fa-phone me-1"></i>{{ $lead->so_dien_thoai }} ·
                                        {{ optional($lead->created_at)->diffForHumans() }}</div>
                                    <div class="small mt-1 text-muted"><i
                                            class="fas fa-comment-dots me-1"></i>{{ \Illuminate\Support\Str::limit($lead->noi_dung, 88) }}
                                    </div>
                                    @if ($lead->batDongSan)
                                        <div class="small mt-1"><i
                                                class="fas fa-home me-1 text-primary"></i>{{ \Illuminate\Support\Str::limit($lead->batDongSan->tieu_de, 60) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="text-end">
                                    @if ($isQuaHan)
                                        <span class="sale-chip" style="background:#fff1f2;color:#b91c1c;">Quá hạn gọi
                                            lại</span>
                                    @elseif($isMoi)
                                        <span class="sale-chip" style="background:#fff7ed;color:#c2410c;">Lead mới</span>
                                    @else
                                        <span class="sale-chip" style="background:#eff6ff;color:#1d4ed8;">Đang tư vấn</span>
                                    @endif
                                    <div class="mt-2 d-flex gap-1 justify-content-end">
                                        <a href="tel:{{ $lead->so_dien_thoai }}" class="btn btn-sm btn-outline-success"><i
                                                class="fas fa-phone"></i></a>
                                        <a href="{{ route('nhanvien.admin.lien-he.show', $lead->id) }}"
                                            class="btn btn-sm btn-outline-danger">Xử lý</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="sale-empty">
                            <i class="fas fa-check-circle fs-1 text-success opacity-50 d-block mb-2"></i>
                            Không còn lead cần xử lý ngay.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="sale-board mb-3">
                <div class="sale-board-head">
                    <div class="fw-bold text-primary"><i class="fas fa-map-marked-alt me-2"></i>Lịch dẫn khách hôm nay</div>
                    <span class="badge bg-primary rounded-pill">{{ $lichHenHomNay->count() }}</span>
                </div>
                <div class="sale-board-body">
                    @forelse($lichHenHomNay as $lh)
                        @php
                            $tg = \Carbon\Carbon::parse($lh->thoi_gian_hen);
                            $isNext2h = $tg->isFuture() && now()->diffInHours($tg) <= 2;
                        @endphp
                        <div class="sale-item {{ $isNext2h ? 'sale-priority' : '' }}">
                            <div class="sale-item-top">
                                <div
                                    class="sale-time-pill {{ $isNext2h ? 'bg-warning text-dark' : 'bg-primary text-white' }}">
                                    {{ $tg->format('H:i') }}</div>
                                <div style="flex:1;min-width:0;">
                                    <div class="fw-bold text-dark text-truncate">
                                        {{ $lh->ten_khach_hang ?? ($lh->khachHang?->ho_ten ?? 'Khách lẻ') }}</div>
                                    <div class="small text-muted text-truncate"><i
                                            class="fas fa-home me-1"></i>{{ $lh->batDongSan?->tieu_de ?? 'Chưa chốt BĐS' }}
                                    </div>
                                    <div class="small text-muted"><i
                                            class="fas fa-clock me-1"></i>{{ $tg->diffForHumans() }}</div>
                                </div>
                                <a href="{{ route('nhanvien.admin.lich-hen.show', $lh->id) }}"
                                    class="btn btn-sm btn-light border"><i class="fas fa-eye"></i></a>
                            </div>
                        </div>
                    @empty
                        <div class="sale-empty">
                            <i class="fas fa-coffee fs-1 opacity-50 d-block mb-2"></i>
                            Hôm nay chưa có lịch hẹn.
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="sale-kpi-card">
                <div class="d-flex justify-content-between mb-2">
                    <span class="fw-bold">Hiệu quả lịch tháng này</span>
                    <span class="small text-muted">{{ $tiLeChotLich }}% chốt</span>
                </div>
                <div class="small text-muted mb-1">Hoàn thành: {{ $hoanThanhSafe }} lịch</div>
                <div class="sale-progress mb-2">
                    <div style="height:100%;width:{{ $ptHoanThanh }}%;background:#16a34a;"></div>
                </div>
                <div class="small text-muted mb-1">Boom/Hủy: {{ $huySafe }} lịch</div>
                <div class="sale-progress">
                    <div style="height:100%;width:{{ $ptHuy }}%;background:#dc2626;"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-12 col-xl-6">
            <div class="sale-board">
                <div class="sale-board-head">
                    <div class="fw-bold text-danger"><i class="fas fa-triangle-exclamation me-2"></i>Lịch quá hạn / chưa
                        chốt</div>
                    <span class="badge bg-danger rounded-pill">{{ $lichHenQuaHan->count() }}</span>
                </div>
                <div class="sale-board-body">
                    @forelse($lichHenQuaHan as $lh)
                        @php $tg = \Carbon\Carbon::parse($lh->thoi_gian_hen); @endphp
                        <div class="sale-item sale-overdue">
                            <div class="sale-item-top">
                                <div>
                                    <div class="fw-bold">
                                        {{ $lh->ten_khach_hang ?? ($lh->khachHang?->ho_ten ?? 'Khách lẻ') }}</div>
                                    <div class="small text-muted">{{ $tg->format('d/m H:i') }} · Trễ
                                        {{ $tg->diffForHumans() }}</div>
                                    <div class="small text-muted text-truncate">
                                        {{ $lh->batDongSan?->tieu_de ?? 'Chưa có BĐS' }}</div>
                                </div>
                                <a href="{{ route('nhanvien.admin.lich-hen.show', $lh->id) }}"
                                    class="btn btn-sm btn-outline-danger">Xử lý</a>
                            </div>
                        </div>
                    @empty
                        <div class="sale-empty">Không có lịch quá hạn.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-6">
            <div class="sale-board">
                <div class="sale-board-head">
                    <div class="fw-bold text-info"><i class="fas fa-calendar-week me-2"></i>Lịch 7 ngày tới</div>
                    <span class="badge bg-info rounded-pill">{{ $lichHenSapToi->count() }}</span>
                </div>
                <div class="sale-board-body">
                    @forelse($lichHenSapToi as $lh)
                        @php
                            $tg = \Carbon\Carbon::parse($lh->thoi_gian_hen);
                            $isSoon = $tg->isFuture() && now()->diffInHours($tg) <= 6;
                        @endphp
                        <div class="sale-item {{ $isSoon ? 'sale-priority' : '' }}">
                            <div class="sale-item-top">
                                <div>
                                    <div class="fw-bold">{{ $tg->format('D, d/m/Y H:i') }}</div>
                                    <div class="small text-muted">
                                        {{ $lh->ten_khach_hang ?? ($lh->khachHang?->ho_ten ?? 'Khách lẻ') }} ·
                                        {{ $lh->sdt_khach_hang ?? ($lh->khachHang?->so_dien_thoai ?? '') }}</div>
                                    <div class="small text-muted text-truncate">
                                        {{ $lh->batDongSan?->tieu_de ?? 'Chưa có BĐS' }}</div>
                                </div>
                                <a href="{{ route('nhanvien.admin.lich-hen.show', $lh->id) }}"
                                    class="btn btn-sm btn-outline-primary">Mở</a>
                            </div>
                        </div>
                    @empty
                        <div class="sale-empty">Không có lịch hẹn sắp tới.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
