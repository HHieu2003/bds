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

        .src-alert {
            border-left: 4px solid #ef4444;
            background: #fff5f5;
        }

        .src-priority {
            border-left: 4px solid #f97316;
            background: #fff7ed;
        }

        .src-empty {
            text-align: center;
            color: var(--text-muted);
            padding: 1.2rem 1rem;
        }
    </style>
@endpush

@section('content')
    @php $now = now(); @endphp

    <div class="src-page">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <div>
                <h1 class="page-header-title mb-1" style="font-size:1.5rem;"><i class="fas fa-boxes text-success me-2"></i>Quản
                    lý Nguồn Hàng</h1>
                <div class="page-header-sub">Xin chào <strong>{{ $nhanVien->ho_ten }}</strong> · Cập nhật lúc
                    {{ $now->format('H:i - d/m/Y') }}</div>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('nhanvien.admin.bat-dong-san.create') }}" class="btn btn-success"><i
                        class="fas fa-plus me-1"></i>Đăng BĐS mới</a>
                <a href="{{ route('nhanvien.admin.lich-hen.index', ['tab' => 'todo']) }}" class="btn btn-outline-primary"><i
                        class="fas fa-calendar-check me-1"></i>Vào Bảng Lịch Hẹn</a>
            </div>
        </div>

        @if (($tongQuan['lich_can_xu_ly'] ?? 0) > 0 || $lichQuaHan->count() > 0 || $kyGuiChoDuyet->count() > 0)
            <div
                class="alert alert-warning src-priority-alert border-0 d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3 shadow-sm">
                <div class="fw-bold"><i class="fas fa-bolt me-1"></i>Công việc ưu tiên cần xử lý ngay</div>
                <div class="d-flex flex-wrap gap-2">
                    @if (($tongQuan['lich_can_xu_ly'] ?? 0) > 0)
                        <a href="{{ route('nhanvien.admin.lich-hen.index', ['tab' => 'todo', 'todo_trang_thai' => 'cho_xac_nhan']) }}"
                            class="text-decoration-none">
                            <span class="badge bg-danger border shadow-sm py-2 px-3"><i class="fas fa-key me-1"></i>Cần xác
                                nhận chìa: {{ $tongQuan['lich_can_xu_ly'] }}</span>
                        </a>
                    @endif
                    @if ($lichQuaHan->count() > 0)
                        <span class="badge bg-white text-danger border py-2">Lịch trễ giờ: {{ $lichQuaHan->count() }}</span>
                    @endif
                    @if ($kyGuiChoDuyet->count() > 0)
                        <a href="{{ route('nhanvien.admin.ky-gui.index') }}" class="text-decoration-none">
                            <span class="badge bg-white text-dark border py-2">Ký gửi chờ duyệt:
                                {{ $kyGuiChoDuyet->count() }}</span>
                        </a>
                    @endif
                </div>
            </div>
        @endif

        <div class="row g-3 mb-3">
            <div class="col-6 col-xl-2">
                <div class="src-kpi">
                    <div class="src-kpi-value text-primary">{{ number_format($tongQuan['tong_bds']) }}</div>
                    <div class="small text-muted">Tổng BĐS</div>
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
                    <div class="small text-muted">Đã bán/thuê</div>
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
                    <div class="small text-muted">Cần xác nhận chìa</div>
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
                            cần xử lý ngay</div>
                        <a href="{{ route('nhanvien.admin.lich-hen.index', ['tab' => 'todo']) }}"
                            class="badge bg-danger text-white text-decoration-none">Vào bảng xử lý</a>
                    </div>
                    <div class="src-body src-body-scroll">
                        @forelse($lichCanXuLyNgay as $lh)
                            @php
                                $tg = \Carbon\Carbon::parse($lh->thoi_gian_hen);
                                $isDoiGio = $lh->trang_thai === 'cho_sale_xac_nhan_doi_gio';
                            @endphp
                            <div class="src-item {{ $isDoiGio ? 'src-priority' : 'src-alert' }}">
                                <div class="src-item-top">
                                    <div class="src-main">
                                        <div class="fw-bold fs-6">{{ $lh->batDongSan->tieu_de ?? 'BĐS ngoài' }}</div>
                                        <div class="small text-muted src-subline mt-1">Sale:
                                            {{ $lh->nhanVienSale->ho_ten ?? 'N/A' }}</div>
                                        <div class="mt-2">
                                            @if ($isDoiGio)
                                                <span class="badge bg-warning text-dark"><i
                                                        class="fas fa-spinner fa-spin me-1"></i> Đã dời. Chờ Sale xác nhận
                                                    với khách</span>
                                            @else
                                                <span class="badge bg-info text-dark">Bạn cần gọi Chủ nhà chốt chìa
                                                    khóa</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="src-actions-wrap">
                                        <div class="src-time bg-danger text-white">{{ $tg->format('H:i d/m') }}</div>
                                        <a href="{{ route('nhanvien.admin.lich-hen.index', ['tab' => 'todo']) }}"
                                            class="btn btn-sm btn-outline-danger mt-1 fw-bold">Xử lý</a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="src-empty"><i
                                    class="fas fa-check-circle text-success fs-1 opacity-50 d-block mb-2"></i>Không có lịch
                                nào đang chờ xác nhận.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-5">
                <div class="src-board mb-3" style="height: auto">
                    <div class="src-head">
                        <div class="fw-bold text-success src-head-title"><i class="fas fa-route me-2"></i>Timeline hôm nay
                            (Đã chốt)</div>
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
                                        <div class="fw-bold">{{ $tg->format('H:i') }} - Khách của
                                            {{ optional($lh->nhanVienSale)->ho_ten ?? 'N/A' }}</div>
                                        <div class="small text-muted">
                                            {{ Str::limit($lh->batDongSan->tieu_de ?? 'N/A', 40) }}</div>
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

                
            </div>
            <div class="src-board">
                    <div class="src-head">
                        <div class="fw-bold text-danger src-head-title"><i class="fas fa-triangle-exclamation me-2"></i>Lịch
                            quá hạn chưa xử lý</div>
                        <span class="badge bg-danger rounded-pill">{{ $lichQuaHan->count() }}</span>
                    </div>
                    <div class="src-body src-body-scroll">
                        @forelse($lichQuaHan as $lh)
                            <div class="src-item src-alert">
                                <div class="src-item-top">
                                    <div class="src-main">
                                        <div class="fw-bold">{{ Str::limit($lh->batDongSan->tieu_de ?? 'N/A', 40) }}</div>
                                        <div class="small text-muted src-subline">
                                            {{ optional($lh->thoi_gian_hen)->format('d/m H:i') }} ·
                                            {{ optional($lh->thoi_gian_hen)->diffForHumans() }}</div>
                                    </div>
                                    <a href="{{ route('nhanvien.admin.lich-hen.show', $lh->id) }}"
                                        class="btn btn-sm btn-outline-danger">Mở</a>
                                </div>
                            </div>
                        @empty
                            <div class="src-empty">Không có lịch quá hạn.</div>
                        @endforelse
                    </div>
                </div>
        </div>

        <div class="row g-3">
            <div class="col-12 col-xl-5">
                <div class="src-board">
                    <div class="src-head">
                        <div class="fw-bold src-head-title"><i class="fas fa-clipboard-list me-2 text-danger"></i>Ký gửi
                            chờ duyệt</div>
                        <a href="{{ route('nhanvien.admin.ky-gui.index') }}" class="small text-decoration-none">Vào trang
                            duyệt</a>
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
                                        class="btn btn-sm btn-outline-warning fw-bold">Duyệt</a>
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
                    <div class="src-body src-body-scroll">
                        @forelse($bdsMoiNhat as $bds)
                            <div class="src-item">
                                <div class="fw-bold text-truncate">{{ $bds->tieu_de }}</div>
                                <div class="small text-muted">Mã: {{ $bds->ma_bat_dong_san ?? 'BĐS#' . $bds->id }}</div>
                                <div class="small text-muted">Giá: {{ $bds->gia_hien_thi ?? 'Thỏa thuận' }}</div>
                                <div class="small mt-1"><span
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
                    </div>
                    <div class="src-body src-body-scroll">
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
