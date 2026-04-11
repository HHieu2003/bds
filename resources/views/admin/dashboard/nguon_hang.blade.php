@extends('admin.layouts.master')
@section('title', 'Tổng quan Nguồn Hàng')

@push('styles')
    <style>
        .src-page {
            background: linear-gradient(180deg, #f8fafc 0%, #ffffff 42%);
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 1rem;
        }

        .src-kpi {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: .95rem 1rem;
            box-shadow: var(--sh-xs);
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .src-kpi-value {
            font-size: 1.4rem;
            font-weight: 800;
            line-height: 1.1;
            color: var(--navy);
        }

        .src-board {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 14px;
            overflow: hidden;
            box-shadow: var(--sh-xs);
            height: 100%;
            min-width: 0;
            position: relative;
            z-index: 1;
        }

        .src-head {
            padding: .9rem 1rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: .65rem;
            flex-wrap: wrap;
        }

        .src-head-title {
            flex: 1;
            min-width: 0;
            line-height: 1.35;
        }

        .src-body {
            overflow: auto;
        }

        .src-body-scroll {
            max-height: 460px;
        }

        .src-item {
            padding: .9rem 1rem;
            border-bottom: 1px solid #f1f5f9;
            min-width: 0;
        }

        .src-item:last-child {
            border-bottom: 0;
        }

        .src-item-top {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            gap: .75rem;
            align-items: flex-start;
            min-width: 0;
        }

        .src-main {
            min-width: 0;
        }

        .src-main .fw-bold,
        .src-main .small,
        .src-main a {
            word-break: break-word;
            overflow-wrap: anywhere;
        }

        .src-subline {
            line-height: 1.35;
        }

        .src-two-line {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .src-time {
            min-width: 74px;
            text-align: center;
            border-radius: 8px;
            padding: .34rem .4rem;
            font-size: .76rem;
            font-weight: 800;
        }

        .src-actions-wrap {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: .45rem;
            flex-shrink: 0;
        }

        .src-actions-row {
            display: flex;
            gap: .4rem;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .src-action-btn {
            white-space: nowrap;
        }

        .src-alert {
            border-left: 4px solid #ef4444;
            background: #fff5f5;
        }

        .src-priority {
            border-left: 4px solid #f97316;
            background: #fff7ed;
        }

        .src-ok {
            border-left: 4px solid #16a34a;
            background: #f0fdf4;
        }

        .src-empty {
            text-align: center;
            color: var(--text-muted);
            padding: 1.2rem 1rem;
        }

        .src-touch-btn {
            min-height: 42px;
            font-weight: 700;
        }

        .src-top-actions {
            display: flex;
            gap: .5rem;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .src-top-actions .btn {
            white-space: nowrap;
        }

        .src-priority-alert {
            border-radius: 12px;
            box-shadow: var(--sh-xs);
        }

        @media (max-width: 768px) {
            .src-page {
                padding: .8rem;
            }

            .src-top-actions {
                justify-content: flex-start;
                width: 100%;
            }

            .src-top-actions .btn {
                flex: 1 1 calc(50% - .5rem);
            }

            .src-touch-btn {
                min-height: 44px;
                font-size: .86rem;
            }

            .src-head {
                align-items: flex-start;
            }

            .src-item-top {
                grid-template-columns: 1fr;
            }

            .src-actions-wrap {
                width: 100%;
                align-items: flex-start;
            }

            .src-actions-row {
                width: 100%;
                justify-content: flex-start;
            }

            .src-actions-row .btn,
            .src-item-top>.btn {
                flex: 1;
            }
        }

        @media (max-width: 576px) {
            .src-top-actions .btn {
                flex: 1 1 100%;
            }

            .src-kpi-value {
                font-size: 1.25rem;
            }
        }
    </style>
@endpush

@section('content')
    @php
        $now = now();
    @endphp

    <div class="src-page">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <div>
                <h1 class="page-header-title mb-1" style="font-size:1.5rem;"><i class="fas fa-boxes text-success me-2"></i>Quản
                    lý
                    Nguồn Hàng</h1>
                <div class="page-header-sub">Xin chào <strong>{{ $nhanVien->ho_ten }}</strong> · Cập nhật lúc
                    {{ $now->format('H:i - d/m/Y') }}</div>
            </div>
            <div class="src-top-actions">
                <a href="{{ route('nhanvien.admin.bat-dong-san.create') }}" class="btn btn-success src-touch-btn"><i
                        class="fas fa-plus me-1"></i>Đăng BĐS mới</a>
                <a href="{{ route('nhanvien.admin.lich-hen.index') }}" class="btn btn-outline-primary src-touch-btn"><i
                        class="fas fa-calendar-check me-1"></i>Lịch hẹn</a>
                <a href="{{ route('nhanvien.admin.ky-gui.index') }}" class="btn btn-outline-secondary src-touch-btn"><i
                        class="fas fa-file-signature me-1"></i>Ký gửi</a>
            </div>
        </div>

        @if (($tongQuan['lich_can_xu_ly'] ?? 0) > 0 || $lichQuaHan->count() > 0 || $kyGuiChoDuyet->count() > 0)
            <div
                class="alert alert-warning src-priority-alert border-0 d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                <div class="fw-bold"><i class="fas fa-bolt me-1"></i>Công việc ưu tiên cần xử lý ngay</div>
                <div class="d-flex flex-wrap gap-2">
                    @if (($tongQuan['lich_can_xu_ly'] ?? 0) > 0)
                        <span class="badge bg-white text-dark border">Lịch chờ xác nhận/đổi giờ:
                            {{ $tongQuan['lich_can_xu_ly'] }}</span>
                    @endif
                    @if ($lichQuaHan->count() > 0)
                        <span class="badge bg-white text-danger border">Lịch quá hạn: {{ $lichQuaHan->count() }}</span>
                    @endif
                    @if ($kyGuiChoDuyet->count() > 0)
                        <span class="badge bg-white text-dark border">Ký gửi chờ duyệt: {{ $kyGuiChoDuyet->count() }}</span>
                    @endif
                </div>
            </div>
        @endif

        <div class="row g-3 mb-3">
            <div class="col-6 col-xl-2">
                <div class="src-kpi">
                    <div class="src-kpi-value text-primary">{{ number_format($tongQuan['tong_bds']) }}</div>
                    <div class="small text-muted">Tổng BĐS phụ trách</div>
                </div>
            </div>
            <div class="col-6 col-xl-2">
                <div class="src-kpi">
                    <div class="src-kpi-value text-success">{{ number_format($tongQuan['bds_con_hang']) }}</div>
                    <div class="small text-muted">BĐS còn hàng</div>
                </div>
            </div>
            <div class="col-6 col-xl-2">
                <div class="src-kpi">
                    <div class="src-kpi-value text-secondary">{{ number_format($tongQuan['bds_da_ban_thue']) }}</div>
                    <div class="small text-muted">Đã bán/đã thuê</div>
                </div>
            </div>
            <div class="col-6 col-xl-2">
                <div class="src-kpi">
                    <div class="src-kpi-value text-warning">{{ number_format($tongQuan['ky_gui_cho']) }}</div>
                    <div class="small text-muted">Ký gửi chờ duyệt</div>
                </div>
            </div>
            <div class="col-6 col-xl-2">
                <div class="src-kpi">
                    <div class="src-kpi-value text-danger">{{ number_format($tongQuan['lich_can_xu_ly']) }}</div>
                    <div class="small text-muted">Lịch cần xử lý</div>
                </div>
            </div>
            <div class="col-6 col-xl-2">
                <div class="src-kpi">
                    <div class="src-kpi-value">{{ number_format($tongQuan['tong_chu_nha']) }}</div>
                    <div class="small text-muted">Data chủ nhà</div>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-12 col-xl-7">
                <div class="src-board">
                    <div class="src-head">
                        <div class="fw-bold text-danger src-head-title"><i class="fas fa-calendar-exclamation me-2"></i>Lịch
                            cần xử lý ngay
                        </div>
                        <span class="badge bg-danger rounded-pill">{{ $lichCanXuLyNgay->count() }}</span>
                    </div>
                    <div class="src-body src-body-scroll">
                        @forelse($lichCanXuLyNgay as $lh)
                            @php
                                $tg = \Carbon\Carbon::parse($lh->thoi_gian_hen);
                                $isOverdue =
                                    $tg->isPast() && !in_array($lh->trang_thai, ['hoan_thanh', 'huy', 'tu_choi']);
                                $rowClass = $isOverdue
                                    ? 'src-alert'
                                    : ($lh->trang_thai === 'sale_doi_gio'
                                        ? 'src-priority'
                                        : 'src-ok');
                            @endphp
                            <div class="src-item {{ $rowClass }}">
                                <div class="src-item-top">
                                    <div class="src-main">
                                        <div class="fw-bold">{{ $lh->batDongSan->tieu_de ?? 'BĐS chưa xác định' }}</div>
                                        <div class="small text-muted src-subline">Khách: {{ $lh->ten_khach_hang }} · <a
                                                href="tel:{{ $lh->sdt_khach_hang }}">{{ $lh->sdt_khach_hang }}</a></div>
                                        <div class="small text-muted src-subline">Sale:
                                            {{ $lh->nhanVienSale->ho_ten ?? 'N/A' }}
                                            {{ $lh->nhanVienSale->so_dien_thoai ?? '' }}</div>
                                        <div class="small text-muted src-subline">Trạng thái: <b>{{ $lh->trang_thai }}</b>
                                            ·
                                            {{ $tg->diffForHumans() }}</div>
                                    </div>
                                    <div class="src-actions-wrap">
                                        <div
                                            class="src-time {{ $isOverdue ? 'bg-danger text-white' : 'bg-warning text-dark' }}">
                                            {{ $tg->format('H:i') }}</div>
                                        <div class="src-actions-row">
                                            <a href="{{ route('nhanvien.admin.lich-hen.show', $lh->id) }}"
                                                class="btn btn-sm btn-outline-primary src-touch-btn src-action-btn">Mở
                                                lịch</a>
                                            <a href="{{ route('nhanvien.admin.lich-hen.index') }}"
                                                class="btn btn-sm btn-outline-success src-touch-btn src-action-btn">Xác
                                                nhận</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="src-empty">
                                <i class="fas fa-check-circle text-success fs-1 opacity-50 d-block mb-2"></i>
                                Không có lịch cần xử lý ngay.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-5">
                <div class="src-board mb-3" style="height: auto">
                    <div class="src-head">
                        <div class="fw-bold text-success src-head-title"><i class="fas fa-route me-2"></i>Timeline hôm nay
                            (đã xác nhận)
                        </div>
                        <span class="badge bg-success rounded-pill">{{ $lichHomNayDaXacNhan->count() }}</span>
                    </div>
                    <div class="src-body src-body-scroll">
                        @forelse($lichHomNayDaXacNhan as $lh)
                            @php
                                $tg = \Carbon\Carbon::parse($lh->thoi_gian_hen);
                                $hours = now()->diffInHours($tg, false);
                                $badgeClass =
                                    $hours < 0 ? 'bg-secondary' : ($hours <= 2 ? 'bg-warning text-dark' : 'bg-success');
                            @endphp
                            <div class="src-item">
                                <div class="src-item-top">
                                    <div class="src-main">
                                        <div class="fw-bold">{{ $tg->format('H:i') }} - {{ $lh->ten_khach_hang }}</div>
                                        <div class="small text-muted src-two-line">{{ $lh->batDongSan->tieu_de ?? 'N/A' }}
                                        </div>
                                        <div class="small text-muted src-subline">Sale:
                                            {{ $lh->nhanVienSale->ho_ten ?? 'N/A' }}</div>
                                    </div>
                                    <span
                                        class="badge {{ $badgeClass }}">{{ $hours < 0 ? 'Đã qua' : 'Còn ' . $hours . 'h' }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="src-empty">Không có lịch đã xác nhận hôm nay.</div>
                        @endforelse
                    </div>
                </div>

                <div class="src-board">
                    <div class="src-head">
                        <div class="fw-bold text-danger src-head-title"><i class="fas fa-triangle-exclamation me-2"></i>Lịch
                            quá hạn chưa xử lý
                        </div>
                        <span class="badge bg-danger rounded-pill">{{ $lichQuaHan->count() }}</span>
                    </div>
                    <div class="src-body src-body-scroll">
                        @forelse($lichQuaHan as $lh)
                            <div class="src-item src-alert">
                                <div class="src-item-top">
                                    <div class="src-main">
                                        <div class="fw-bold">{{ $lh->ten_khach_hang }}</div>
                                        <div class="small text-muted src-subline">
                                            {{ optional($lh->thoi_gian_hen)->format('d/m H:i') }} ·
                                            {{ optional($lh->thoi_gian_hen)->diffForHumans() }}</div>
                                        <div class="small text-muted src-two-line">{{ $lh->batDongSan->tieu_de ?? 'N/A' }}
                                        </div>
                                    </div>
                                    <a href="{{ route('nhanvien.admin.lich-hen.show', $lh->id) }}"
                                        class="btn btn-sm btn-outline-danger src-touch-btn src-action-btn">Xử lý</a>
                                </div>
                            </div>
                        @empty
                            <div class="src-empty">Không có lịch quá hạn.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-12 col-xl-5">
                <div class="src-board">
                    <div class="src-head">
                        <div class="fw-bold src-head-title"><i class="fas fa-clipboard-list me-2 text-danger"></i>Ký gửi
                            chờ duyệt</div>
                        <a href="{{ route('nhanvien.admin.ky-gui.index') }}" class="small text-decoration-none">Xem tất
                            cả</a>
                    </div>
                    <div class="src-body src-body-scroll">
                        @forelse($kyGuiChoDuyet as $kg)
                            <div class="src-item">
                                <div class="src-item-top">
                                    <div class="src-main">
                                        <div class="fw-bold">
                                            {{ $kg->khachHang?->ho_ten ?? ($kg->ho_ten_chu_nha ?? 'Khách hàng') }}</div>
                                        <div class="small text-muted">{{ $kg->created_at->format('d/m/Y H:i') }}</div>
                                        <div class="small text-muted src-subline">{{ $kg->loai_hinh ?? 'BĐS' }} ·
                                            {{ $kg->nhu_cau ?? 'N/A' }}</div>
                                    </div>
                                    <a href="{{ route('nhanvien.admin.ky-gui.show', $kg->id) }}"
                                        class="btn btn-sm btn-outline-warning src-touch-btn src-action-btn">Duyệt</a>
                                </div>
                            </div>
                        @empty
                            <div class="src-empty">Không có ký gửi chờ duyệt.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-4">
                <div class="src-board">
                    <div class="src-head">
                        <div class="fw-bold src-head-title"><i class="fas fa-home me-2 text-success"></i>Kho hàng mới cập
                            nhật</div>
                        <a href="{{ route('nhanvien.admin.bat-dong-san.index') }}" class="small text-decoration-none">Vào
                            kho</a>
                    </div>
                    <div class="src-body">
                        @forelse($bdsMoiNhat as $bds)
                            <div class="src-item">
                                <div class="fw-bold src-two-line">{{ $bds->tieu_de }}</div>
                                <div class="small text-muted">Mã: {{ $bds->ma_bat_dong_san ?? 'BĐS#' . $bds->id }}</div>
                                <div class="small text-muted">Giá: {{ $bds->gia_hien_thi ?? 'Thỏa thuận' }}</div>
                                <div class="small mt-1">
                                    <span
                                        class="badge {{ $bds->trang_thai == 'con_hang' ? 'bg-success' : 'bg-secondary' }}">{{ $bds->trang_thai == 'con_hang' ? 'Còn hàng' : 'Đã bán/thuê' }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="src-empty">Chưa có BĐS nào trong kho.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-3">
                <div class="src-board">
                    <div class="src-head">
                        <div class="fw-bold src-head-title"><i class="fas fa-user-tie me-2 text-primary"></i>Chủ nhà nổi
                            bật</div>
                        <span class="badge bg-primary rounded-pill">{{ $chuNhaNoiBat->count() }}</span>
                    </div>
                    <div class="src-body">
                        @forelse($chuNhaNoiBat as $cn)
                            <div class="src-item">
                                <div class="fw-bold">{{ $cn->ho_ten }}</div>
                                <div class="small text-muted"><a
                                        href="tel:{{ $cn->so_dien_thoai }}">{{ $cn->so_dien_thoai }}</a></div>
                                <div class="small text-muted">Đang có {{ $cn->bat_dong_sans_count }} BĐS</div>
                            </div>
                        @empty
                            <div class="src-empty">Chưa có dữ liệu chủ nhà.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
