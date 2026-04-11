@extends('admin.layouts.master')
@section('title', 'Lịch hẹn làm việc')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
    <style>
        .lh-shell {
            background: linear-gradient(180deg, #f8fbff 0%, #ffffff 55%);
            border: 1px solid #e5edf8;
            border-radius: 14px;
            padding: 1rem;
        }

        .lh-kpi {
            background: #fff;
            border: 1px solid #e8eef7;
            border-radius: 12px;
            box-shadow: 0 4px 14px rgba(15, 23, 42, 0.05);
            padding: .8rem .9rem;
            height: 100%;
        }

        .lh-kpi .k-label {
            color: #64748b;
            font-size: .78rem;
        }

        .lh-kpi .k-value {
            font-size: 1.25rem;
            font-weight: 800;
            line-height: 1.1;
            color: #0f172a;
        }

        .lh-tabs .nav-link {
            font-weight: 700;
            color: #64748b;
            border: 0;
            border-bottom: 3px solid transparent;
            padding: 10px 18px;
            background: transparent;
        }

        .lh-tabs .nav-link.active {
            color: #0d6efd;
            border-bottom-color: #0d6efd;
        }

        .lh-legend {
            display: flex;
            flex-wrap: wrap;
            gap: .55rem;
            margin-bottom: .85rem;
        }

        .legend-pill {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            padding: .35rem .6rem;
            border-radius: 999px;
            font-size: .78rem;
            font-weight: 600;
            background: #fff;
            border: 1px solid #e2e8f0;
        }

        #calendar {
            background: #fff;
            padding: 14px;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 10px 28px rgba(15, 23, 42, 0.08);
        }

        .fc-event {
            cursor: pointer;
            border: none;
            border-radius: 8px;
            padding: 2px 6px;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.18);
        }

        .fc .fc-timegrid-event {
            min-height: 30px;
        }

        .fc .fc-timegrid-event .fc-event-main {
            padding: 2px 4px;
        }

        .fc .fc-event-time {
            font-weight: 700;
            font-size: .74rem;
        }

        .fc .fc-event-title {
            font-size: .73rem;
            line-height: 1.25;
            white-space: normal;
            word-break: break-word;
        }

        .fc .fc-col-header-cell-cushion,
        .fc .fc-timegrid-axis-cushion {
            color: #334155;
            font-weight: 700;
        }

        .fc .fc-timegrid-slot-label-cushion {
            color: #64748b;
            font-size: .75rem;
        }

        .fc .fc-scrollgrid,
        .fc-theme-standard td,
        .fc-theme-standard th {
            border-color: #e2e8f0;
        }

        .fc .fc-day-today {
            background: rgba(37, 99, 235, 0.05) !important;
        }

        .fc .fc-daygrid-day-number,
        .fc .fc-col-header-cell-cushion {
            text-decoration: none;
        }

        .fc-toolbar-title {
            font-size: 1.25rem !important;
            font-weight: bold;
            color: var(--navy);
        }

        .legend-box {
            width: 12px;
            height: 12px;
            display: inline-block;
            border-radius: 999px;
            vertical-align: middle;
        }

        .lh-list-card {
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.06);
            overflow: hidden;
        }

        .lh-filter-wrap {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.04);
        }

        @media (max-width: 768px) {
            .lh-shell {
                padding: .75rem;
            }

            #calendar {
                padding: 8px;
            }

            .fc-toolbar-title {
                font-size: 1rem !important;
            }
        }
    </style>
@endpush

@section('content')
    @php
        $isDoiGio = static function ($lh) {
            $noteSale = mb_strtolower((string) $lh->ghi_chu_sale);
            $noteNguon = mb_strtolower((string) $lh->ghi_chu_nguon_hang);

            return str_contains((string) $lh->ghi_chu_sale, '[DOI_GIO]') ||
                str_contains((string) $lh->ghi_chu_nguon_hang, '[DOI_GIO]') ||
                str_contains($noteSale, 'doi gio') ||
                str_contains($noteSale, 'dời') ||
                str_contains($noteNguon, 'doi gio') ||
                str_contains($noteNguon, 'dời');
        };
    @endphp

    <div class="mb-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
            <h1 class="page-header-title"><i class="fas fa-calendar-alt text-primary me-2"></i> Lịch Hẹn Khách Hàng</h1>
            <p class="text-muted mb-0">Quản lý và điều phối lịch xem nhà với bộ phận Nguồn hàng.</p>
        </div>
        <div class="d-flex gap-2">
            @if ($nhanVien->isAdmin())
                <a href="{{ route('nhanvien.admin.lich-hen.index', ['giao_dien' => 'nguon_hang']) }}"
                    class="btn btn-outline-success shadow-sm">
                    <i class="fas fa-clipboard-list me-1"></i> Chế độ Nguồn hàng
                </a>
            @endif
            <a href="{{ route('nhanvien.admin.lich-hen.create') }}" class="btn btn-primary shadow-sm">
                <i class="fas fa-plus me-1"></i> Đặt lịch mới
            </a>
        </div>
    </div>

    @include('frontend.partials.flash-messages')

    @php
        $isListTab = request('tab') == 'list';
        $isContactTab = request('tab') == 'contact';
        $lhView = request('lh_view', 'list');
        $tongDangMo = ($stats['moi_dat'] ?? 0) + ($stats['cho_xac_nhan'] ?? 0) + ($stats['da_xac_nhan'] ?? 0);
    @endphp

    <div class="lh-shell">
        <div class="row g-2 mb-3">
            <div class="col-6 col-md-3 col-xl-2">
                <div class="lh-kpi">
                    <div class="k-label">Đang mở</div>
                    <div class="k-value text-primary">{{ $tongDangMo }}</div>
                </div>
            </div>
            <div class="col-6 col-md-3 col-xl-2">
                <div class="lh-kpi">
                    <div class="k-label">Mới đặt</div>
                    <div class="k-value text-warning">{{ $stats['moi_dat'] ?? 0 }}</div>
                </div>
            </div>
            <div class="col-6 col-md-3 col-xl-2">
                <div class="lh-kpi">
                    <div class="k-label">Chờ nguồn xác nhận</div>
                    <div class="k-value text-info">{{ $stats['cho_xac_nhan'] ?? 0 }}</div>
                </div>
            </div>
            <div class="col-6 col-md-3 col-xl-2">
                <div class="lh-kpi">
                    <div class="k-label">Đã xác nhận</div>
                    <div class="k-value text-success">{{ $stats['da_xac_nhan'] ?? 0 }}</div>
                </div>
            </div>
            <div class="col-6 col-md-3 col-xl-2">
                <div class="lh-kpi">
                    <div class="k-label">Lịch hôm nay</div>
                    <div class="k-value text-danger">{{ $stats['hom_nay'] ?? 0 }}</div>
                </div>
            </div>
            <div class="col-6 col-md-3 col-xl-2">
                <div class="lh-kpi">
                    <div class="k-label">Đã hoàn thành</div>
                    <div class="k-value text-secondary">{{ $stats['hoan_thanh'] ?? 0 }}</div>
                </div>
            </div>
        </div>

        <ul class="nav lh-tabs mb-3 border-bottom-2" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ !$isListTab && !$isContactTab ? 'active' : '' }}" id="calendar-tab"
                    data-bs-toggle="tab" data-bs-target="#tab-calendar" type="button" role="tab">
                    <i class="fas fa-calendar-week me-1"></i> Xem dạng Lịch (Calendar)
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $isListTab ? 'active' : '' }}" id="list-tab" data-bs-toggle="tab"
                    data-bs-target="#tab-list" type="button" role="tab">
                    <i class="fas fa-list me-1"></i> Danh sách Toàn bộ
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $isContactTab ? 'active' : '' }}" id="contact-tab" data-bs-toggle="tab"
                    data-bs-target="#tab-contact" type="button" role="tab">
                    <i class="fas fa-headset me-1"></i> Yêu cầu liên hệ
                </button>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade {{ !$isListTab && !$isContactTab ? 'show active' : '' }}" id="tab-calendar"
                role="tabpanel">
                <div class="lh-legend">
                    <span class="legend-pill"><span class="legend-box" style="background:#f59e0b;"></span>Mới đặt</span>
                    <span class="legend-pill"><span class="legend-box" style="background:#3b82f6;"></span>Chờ xác
                        nhận</span>
                    <span class="legend-pill"><span class="legend-box" style="background:#10b981;"></span>Đã xác nhận</span>
                    <span class="legend-pill"><span class="legend-box" style="background:#ef4444;"></span>Hủy/Từ chối</span>
                    <span class="legend-pill"><span class="legend-box" style="background:#6b7280;"></span>Hoàn thành</span>
                </div>
                <div id='calendar'></div>
            </div>

            <div class="tab-pane fade {{ $isListTab ? 'show active' : '' }}" id="tab-list" role="tabpanel">
                {{-- BỘ LỌC TÌM KIẾM --}}
                <div class="lh-filter-wrap mb-3">
                    <div class="card-body p-3">
                        <form method="GET" class="row g-2 align-items-center">
                            <input type="hidden" name="tab" value="list">
                            <div class="col-12 col-md-3">
                                <input type="text" name="tim_kiem" class="form-control"
                                    value="{{ request('tim_kiem') }}" placeholder="🔍 Tìm khách hàng, SĐT, BĐS...">
                            </div>
                            <div class="col-6 col-md-2">
                                <select name="trang_thai" class="form-select">
                                    <option value="">-- Trạng thái --</option>
                                    <option value="moi_dat" {{ request('trang_thai') == 'moi_dat' ? 'selected' : '' }}>Mới
                                        đặt
                                    </option>
                                    <option value="cho_xac_nhan"
                                        {{ request('trang_thai') == 'cho_xac_nhan' ? 'selected' : '' }}>Chờ xác nhận
                                    </option>
                                    <option value="da_xac_nhan"
                                        {{ request('trang_thai') == 'da_xac_nhan' ? 'selected' : '' }}>
                                        Đã xác nhận</option>
                                    <option value="hoan_thanh"
                                        {{ request('trang_thai') == 'hoan_thanh' ? 'selected' : '' }}>
                                        Hoàn thành</option>
                                    <option value="tu_choi" {{ request('trang_thai') == 'tu_choi' ? 'selected' : '' }}>Từ
                                        chối
                                    </option>
                                    <option value="huy" {{ request('trang_thai') == 'huy' ? 'selected' : '' }}>Đã hủy
                                    </option>
                                </select>
                            </div>
                            <div class="col-6 col-md-4 d-flex gap-1">
                                <input type="date" name="tu_ngay" class="form-control w-50"
                                    value="{{ request('tu_ngay') }}" title="Từ ngày">
                                <input type="date" name="den_ngay" class="form-control w-50"
                                    value="{{ request('den_ngay') }}" title="Đến ngày">
                            </div>
                            <div class="col-12 col-md-3 d-flex gap-2">
                                <button type="submit" class="btn btn-navy grow"><i class="fas fa-search"></i>
                                    Lọc</button>
                                @if (request()->anyFilled(['tim_kiem', 'trang_thai', 'tu_ngay', 'den_ngay']))
                                    <a href="{{ route('nhanvien.admin.lich-hen.index', ['tab' => 'list']) }}"
                                        class="btn btn-light border"><i class="fas fa-undo"></i></a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>

                {{-- BẢNG DỮ LIỆU --}}
                <div class="lh-list-card">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Thời gian hẹn</th>
                                    <th>Khách hàng</th>
                                    <th>Bất động sản</th>
                                    <th>Phụ trách</th>
                                    <th>Trạng thái</th>
                                    <th class="text-end">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($lichHensList as $lh)
                                    @php
                                        $bgMap = [
                                            'moi_dat' => 'warning',
                                            'cho_xac_nhan' => 'info',
                                            'da_xac_nhan' => 'success',
                                            'hoan_thanh' => 'secondary',
                                            'tu_choi' => 'danger',
                                            'huy' => 'danger',
                                        ];
                                        $lblMap = [
                                            'moi_dat' => 'Mới đặt',
                                            'cho_xac_nhan' => 'Chờ Nguồn XN',
                                            'da_xac_nhan' => 'Đã chốt giờ',
                                            'hoan_thanh' => 'Hoàn thành',
                                            'tu_choi' => 'Bị từ chối',
                                            'huy' => 'Đã hủy',
                                        ];
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="fw-bold text-danger">
                                                {{ \Carbon\Carbon::parse($lh->thoi_gian_hen)->format('H:i') }}</div>
                                            <div class="small text-muted">
                                                {{ \Carbon\Carbon::parse($lh->thoi_gian_hen)->format('d/m/Y') }}</div>
                                            @if ($isDoiGio($lh))
                                                <span class="badge bg-warning text-dark mt-1"><i
                                                        class="fas fa-clock me-1"></i>Đã đổi giờ</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="fw-bold">{{ $lh->ten_khach_hang }}</div>
                                            <div class="small"><i
                                                    class="fas fa-phone text-success me-1"></i>{{ $lh->sdt_khach_hang }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-medium text-truncate" style="max-width: 250px;"
                                                title="{{ $lh->batDongSan->tieu_de ?? 'Nhà lẻ' }}">
                                                {{ $lh->batDongSan->tieu_de ?? 'Nhà lẻ' }}
                                            </div>
                                        </td>
                                        <td class="small">
                                            <div><span class="text-muted">Sale:</span> <strong
                                                    class="text-dark">{{ $lh->nhanVienSale->ho_ten ?? '---' }}</strong>
                                            </div>
                                            <div><span class="text-muted">Nguồn:</span> <strong
                                                    class="text-dark">{{ $lh->nhanVienNguonHang->ho_ten ?? '---' }}</strong>
                                            </div>
                                        </td>
                                        <td>
                                            <span
                                                class="badge bg-{{ $bgMap[$lh->trang_thai] ?? 'dark' }}">{{ $lblMap[$lh->trang_thai] ?? $lh->trang_thai }}</span>
                                        </td>
                                        <td class="text-end">
                                            <button type="button"
                                                class="btn btn-sm btn-outline-primary rounded-pill px-3 btn-trigger-modal"
                                                data-id="{{ $lh->id }}" data-start="{{ $lh->thoi_gian_hen }}"
                                                data-trang_thai="{{ $lh->trang_thai }}"
                                                data-ten_khach="{{ $lh->ten_khach_hang }}"
                                                data-sdt_khach="{{ $lh->sdt_khach_hang }}"
                                                data-bds="{{ $lh->batDongSan->tieu_de ?? 'Nhà lẻ' }}"
                                                data-sale_id="{{ $lh->nhan_vien_sale_id }}"
                                                data-is_doi_gio="{{ $isDoiGio($lh) ? '1' : '0' }}"
                                                data-ly_do_huy="{{ in_array($lh->trang_thai, ['tu_choi', 'hoan_thanh', 'huy']) ? $lh->ly_do_tu_choi ?? $lh->ghi_chu_sale : null }}">
                                                Xử lý
                                            </button>
                                            <a href="{{ route('nhanvien.admin.lich-hen.show', $lh->id) }}"
                                                class="btn btn-sm btn-light border px-2 ms-1"
                                                title="Xem chi tiết trang riêng"><i
                                                    class="fas fa-external-link-alt text-secondary"></i></a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">Không tìm thấy lịch hẹn
                                            nào.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if (isset($lichHensList) && $lichHensList->hasPages())
                        <div class="card-footer bg-white border-top p-3 d-flex justify-content-end">
                            {{ $lichHensList->appends(request()->query())->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>
            </div>

            <div class="tab-pane fade {{ $isContactTab ? 'show active' : '' }}" id="tab-contact" role="tabpanel">
                <div class="lh-filter-wrap mb-3">
                    <div class="card-body p-3">
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <span class="badge rounded-pill bg-dark">Tổng: {{ $lienHeThongKe['tat_ca'] ?? 0 }}</span>
                            @foreach (\App\Models\YeuCauLienHe::TRANG_THAI as $key => $info)
                                <span class="badge rounded-pill"
                                    style="background: {{ $info['bg'] }}; color: {{ $info['color'] }};">
                                    {{ $info['label'] }}: {{ $lienHeThongKe[$key] ?? 0 }}
                                </span>
                            @endforeach
                        </div>

                        <form method="GET" class="row g-2 align-items-center">
                            <input type="hidden" name="tab" value="contact">
                            <input type="hidden" name="lh_view" value="{{ $lhView }}">

                            <div class="col-12 col-md-4">
                                <input type="text" name="lh_search" class="form-control"
                                    value="{{ request('lh_search') }}" placeholder="Tìm tên, SĐT, email, BĐS...">
                            </div>
                            <div class="col-6 col-md-3">
                                <select name="lh_trang_thai" class="form-select">
                                    <option value="">-- Trạng thái lead --</option>
                                    @foreach (\App\Models\YeuCauLienHe::TRANG_THAI as $key => $info)
                                        <option value="{{ $key }}"
                                            {{ request('lh_trang_thai') == $key ? 'selected' : '' }}>
                                            {{ $info['label'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="btn-group w-100" role="group" aria-label="Chế độ hiển thị lead">
                                    <a href="{{ route('nhanvien.admin.lich-hen.index', array_merge(request()->query(), ['tab' => 'contact', 'lh_view' => 'list'])) }}"
                                        class="btn btn-sm {{ $lhView === 'list' ? 'btn-primary' : 'btn-outline-primary' }}">
                                        <i class="fas fa-list me-1"></i>List
                                    </a>
                                    <a href="{{ route('nhanvien.admin.lich-hen.index', array_merge(request()->query(), ['tab' => 'contact', 'lh_view' => 'card'])) }}"
                                        class="btn btn-sm {{ $lhView === 'card' ? 'btn-primary' : 'btn-outline-primary' }}">
                                        <i class="fas fa-grip-horizontal me-1"></i>Thẻ
                                    </a>
                                </div>
                            </div>
                            <div class="col-12 col-md-2 d-flex gap-2">
                                <button type="submit" class="btn btn-navy grow"><i class="fas fa-search"></i>
                                    Lọc</button>
                                @if (request()->anyFilled(['lh_search', 'lh_trang_thai']))
                                    <a href="{{ route('nhanvien.admin.lich-hen.index', ['tab' => 'contact', 'lh_view' => $lhView]) }}"
                                        class="btn btn-light border"><i class="fas fa-undo"></i></a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>

                @if (($lhView ?? 'list') === 'card')
                    <div class="row g-3">
                        @forelse ($lienHeItems as $lead)
                            @php $info = $lead->trang_thai_info; @endphp
                            <div class="col-12 col-md-6 col-xl-4">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between gap-2 mb-2">
                                            <div>
                                                <div class="fw-bold">{{ $lead->ho_ten }}</div>
                                                <div class="small text-muted">{{ $lead->ma_yeu_cau }}</div>
                                            </div>
                                            <span class="badge rounded-pill"
                                                style="background: {{ $info['bg'] }}; color: {{ $info['color'] }};">
                                                {{ $info['label'] }}
                                            </span>
                                        </div>
                                        <div class="small mb-2">
                                            <i class="fas fa-phone text-success me-1"></i>{{ $lead->so_dien_thoai }}
                                        </div>
                                        <div class="small text-muted mb-2">
                                            {{ $lead->thoi_diem_lien_he?->format('H:i d/m/Y') ?? $lead->created_at->format('H:i d/m/Y') }}
                                        </div>
                                        <div class="small mb-3 text-truncate"
                                            title="{{ $lead->batDongSan->tieu_de ?? $lead->noi_dung }}">
                                            {{ $lead->batDongSan->tieu_de ?? $lead->noi_dung }}
                                        </div>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('nhanvien.admin.lien-he.show', $lead) }}"
                                                class="btn btn-outline-primary btn-sm w-100">Chi tiết</a>
                                            @if (!$lead->nhan_vien_phu_trach_id && $nhanVien->isSale())
                                                <form action="{{ route('nhanvien.admin.lien-he.nhan-lead', $lead) }}"
                                                    method="POST" class="w-100">
                                                    @csrf
                                                    <button type="submit"
                                                        class="btn btn-warning btn-sm w-100 fw-bold">Nhận</button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="text-center text-muted py-4">Không có yêu cầu liên hệ phù hợp.</div>
                            </div>
                        @endforelse
                    </div>
                @else
                    <div class="lh-list-card">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Khách hàng</th>
                                        <th>Bối cảnh</th>
                                        <th>Phụ trách</th>
                                        <th>Trạng thái</th>
                                        <th class="text-end">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($lienHeItems as $lead)
                                        @php $info = $lead->trang_thai_info; @endphp
                                        <tr>
                                            <td>
                                                <div class="fw-bold">{{ $lead->ho_ten }}</div>
                                                <div class="small text-muted">{{ $lead->so_dien_thoai }}</div>
                                                <div class="small text-muted">{{ $lead->ma_yeu_cau }}</div>
                                            </td>
                                            <td>
                                                <div class="small text-truncate" style="max-width: 280px;"
                                                    title="{{ $lead->batDongSan->tieu_de ?? $lead->noi_dung }}">
                                                    {{ $lead->batDongSan->tieu_de ?? $lead->noi_dung }}
                                                </div>
                                            </td>
                                            <td class="small">{{ $lead->nhanVienPhuTrach->ho_ten ?? 'Chưa nhận' }}</td>
                                            <td>
                                                <span class="badge rounded-pill"
                                                    style="background: {{ $info['bg'] }}; color: {{ $info['color'] }};">
                                                    {{ $info['label'] }}
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('nhanvien.admin.lien-he.show', $lead) }}"
                                                    class="btn btn-sm btn-outline-primary">Chi tiết</a>
                                                @if (!$lead->nhan_vien_phu_trach_id && $nhanVien->isSale())
                                                    <form action="{{ route('nhanvien.admin.lien-he.nhan-lead', $lead) }}"
                                                        method="POST" class="d-inline-block ms-1">
                                                        @csrf
                                                        <button type="submit"
                                                            class="btn btn-sm btn-warning fw-bold">Nhận</button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">Không có yêu cầu liên
                                                hệ phù hợp.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                @if (isset($lienHeItems) && $lienHeItems->hasPages())
                    <div class="d-flex justify-content-end mt-3">
                        {{ $lienHeItems->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- MODAL CHI TIẾT SỰ KIỆN (DÙNG CHUNG CHO CẢ CALENDAR & LIST) --}}
    <div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold" id="eventModalLabel">Chi tiết lịch hẹn</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body p-4">

                    <div class="mb-3">
                        <span class="text-muted small">Khách hàng:</span>
                        <h5 class="fw-bold text-dark mb-0" id="m_khach"></h5>
                        <div class="mt-1">
                            <i class="fas fa-phone text-success"></i>
                            <a href="#" id="m_sdt" class="fw-bold text-success text-decoration-none"></a>
                        </div>
                    </div>

                    <div class="mb-3 border-start border-4 border-primary ps-3">
                        <span class="text-muted small">Căn nhà / Dự án:</span>
                        <div class="fw-bold" id="m_bds"></div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-6">
                            <span class="text-muted small">Thời gian:</span>
                            <div class="fw-bold text-danger" id="m_time"></div>
                        </div>
                        <div class="col-6">
                            <span class="text-muted small">Trạng thái:</span>
                            <div id="m_status" class="fw-bold text-primary"></div>
                        </div>
                    </div>

                    <div id="m_doi_gio_badge" class="mb-3" style="display:none;">
                        <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i>Lịch này đã đổi
                            giờ</span>
                    </div>

                    <div id="div_ghi_chu" class="alert alert-warning border-warning" style="display:none;"></div>
                    <hr>

                    {{-- ACTION: Mới đặt --}}
                    <div id="action_moi_dat" style="display:none;">
                        <form id="frmTiepNhan" method="POST">
                            @csrf @method('PATCH')
                            <label class="form-label small fw-bold">Chọn Nguồn hàng để liên hệ chủ nhà:</label>
                            <select name="nhan_vien_nguon_hang_id" class="form-select mb-2" required>
                                <option value="">-- Chọn Nguồn hàng --</option>
                                @foreach ($dsNguonHang as $nv)
                                    <option value="{{ $nv->id }}">{{ $nv->ho_ten }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-warning w-100 fw-bold shadow-sm">
                                <i class="fas fa-hand-paper me-1"></i> NHẬN LỊCH NÀY
                            </button>
                        </form>
                    </div>

                    {{-- ACTION: Đã xác nhận --}}
                    <div id="action_da_xac_nhan" style="display:none;">
                        <form id="frmHoanThanh" method="POST">
                            @csrf @method('PATCH')
                            <label class="form-label small fw-bold text-success">Báo cáo kết quả xem nhà:</label>
                            <input type="text" name="ghi_chu_sale" class="form-control mb-2"
                                placeholder="Ghi chú sau khi xem (Khách chê/ưng...)" required>
                            <button type="submit" class="btn btn-success w-100 shadow-sm">
                                <i class="fas fa-flag-checkered me-1"></i> XÁC NHẬN HOÀN THÀNH
                            </button>
                        </form>
                    </div>

                    {{-- ACTION: Hủy / Dời giờ --}}
                    <div id="action_huy" style="display:none;" class="mt-3 border-top pt-3">
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-outline-warning w-50 fw-bold text-dark shadow-sm"
                                id="btnShowDoiGio">Khách dời lịch</button>
                            <button type="button" class="btn btn-outline-danger w-50 fw-bold shadow-sm"
                                id="btnShowHuy">Khách hủy xem</button>
                        </div>

                        <form id="frmDoiGioSale" method="POST"
                            class="mt-3 p-3 bg-warning bg-opacity-10 rounded border border-warning" style="display:none;">
                            @csrf @method('PATCH')
                            <h6 class="fw-bold text-dark mb-2">Đề xuất giờ mới cho Nguồn:</h6>
                            <input type="datetime-local" name="thoi_gian_hen" class="form-control mb-2" required>
                            <input type="text" name="ghi_chu_sale" class="form-control mb-2"
                                placeholder="Ghi chú: Khách bận kẹt xe, dời 30p..." required>
                            <button type="submit" class="btn btn-warning w-100 fw-bold text-dark">Gửi Yêu Cầu Đổi
                                Giờ</button>
                        </form>

                        <form id="frmHuy" method="POST"
                            class="mt-3 p-3 bg-danger bg-opacity-10 rounded border border-danger" style="display:none;">
                            @csrf @method('PATCH')
                            <h6 class="fw-bold text-danger mb-2">Lý do Hủy lịch:</h6>
                            <input type="text" name="ly_do" class="form-control mb-2"
                                placeholder="VD: Khách mua chỗ khác rồi, bận đột xuất..." required>
                            <button type="submit" class="btn btn-danger w-100 fw-bold">Xác Nhận Hủy Lịch</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/vi.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let currentUserId = {{ $nhanVien->id }};
            var calendarEl = document.getElementById('calendar');
            var eventModalEl = document.getElementById('eventModal');

            const byId = (id) => document.getElementById(id);
            const setDisplay = (id, value) => {
                const el = byId(id);
                if (el) el.style.display = value;
            };

            // Hàm mở Modal dùng chung cho cả Calendar và List
            function openLichHenModal(id, props, startString) {
                const mKhach = byId('m_khach');
                if (mKhach) mKhach.textContent = props.ten_khach || 'Không có tên';

                var sdtEl = byId('m_sdt');
                if (sdtEl) {
                    sdtEl.textContent = props.sdt_khach || 'Không có SĐT';
                    sdtEl.href = props.sdt_khach ? ('tel:' + props.sdt_khach) : '#';
                }

                const mBds = byId('m_bds');
                if (mBds) mBds.textContent = props.bds || 'Nhà lẻ / Chưa xác định';

                var d = new Date(startString);
                if (d instanceof Date && !isNaN(d)) {
                    var hh = String(d.getHours()).padStart(2, '0');
                    var mm = String(d.getMinutes()).padStart(2, '0');
                    const mTime = byId('m_time');
                    if (mTime) {
                        mTime.textContent = hh + ':' + mm + ' ' + String(d.getDate()).padStart(2, '0') + '/' +
                            String(d.getMonth() + 1).padStart(2, '0') + '/' + d.getFullYear();
                    }
                }

                var statusMap = {
                    'moi_dat': 'Mới đặt',
                    'cho_xac_nhan': 'Chờ Nguồn xác nhận',
                    'da_xac_nhan': 'Đã chốt giờ đi',
                    'hoan_thanh': 'Đã hoàn thành',
                    'tu_choi': 'Bị từ chối',
                    'huy': 'Đã hủy'
                };
                const mStatus = byId('m_status');
                if (mStatus) {
                    mStatus.textContent = statusMap[props.trang_thai] || props.trang_thai || 'Không xác định';
                }

                setDisplay('action_moi_dat', 'none');
                setDisplay('action_da_xac_nhan', 'none');
                setDisplay('action_huy', 'none');
                setDisplay('div_ghi_chu', 'none');
                setDisplay('frmHuy', 'none');
                setDisplay('frmDoiGioSale', 'none');
                setDisplay('m_doi_gio_badge', 'none');

                var isDoiGio = String(props.is_doi_gio || '') === '1' || props.is_doi_gio === true;
                if (isDoiGio) {
                    setDisplay('m_doi_gio_badge', 'block');
                }

                if (props.ly_do_huy) {
                    var ghiChuEl = byId('div_ghi_chu');
                    if (ghiChuEl) {
                        ghiChuEl.style.display = 'block';
                        ghiChuEl.textContent = 'Lý do/Ghi chú: ' + props.ly_do_huy;
                    }
                }

                if (props.trang_thai === 'moi_dat') {
                    setDisplay('action_moi_dat', 'block');
                    const frmTiepNhan = byId('frmTiepNhan');
                    if (frmTiepNhan) frmTiepNhan.action = '/nhan-vien/admin/lich-hen/' + id + '/tiep-nhan';
                } else if (props.trang_thai === 'da_xac_nhan' && String(props.sale_id) === String(currentUserId)) {
                    setDisplay('action_da_xac_nhan', 'block');
                    const frmHoanThanh = byId('frmHoanThanh');
                    if (frmHoanThanh) frmHoanThanh.action = '/nhan-vien/admin/lich-hen/' + id + '/hoan-thanh';
                }

                var trangThaiKetThuc = ['hoan_thanh', 'huy', 'tu_choi'];
                if (!trangThaiKetThuc.includes(props.trang_thai) && String(props.sale_id) === String(
                        currentUserId)) {
                    setDisplay('action_huy', 'block');
                    const frmHuy = byId('frmHuy');
                    const frmDoiGioSale = byId('frmDoiGioSale');
                    if (frmHuy) frmHuy.action = '/nhan-vien/admin/lich-hen/' + id + '/huy';
                    if (frmDoiGioSale) frmDoiGioSale.action = '/nhan-vien/admin/lich-hen/' + id + '/sale-doi-gio';

                    const btnShowDoiGio = byId('btnShowDoiGio');
                    const btnShowHuy = byId('btnShowHuy');
                    if (btnShowDoiGio) {
                        btnShowDoiGio.onclick = function() {
                            setDisplay('frmDoiGioSale', 'block');
                            setDisplay('frmHuy', 'none');
                        };
                    }
                    if (btnShowHuy) {
                        btnShowHuy.onclick = function() {
                            setDisplay('frmHuy', 'block');
                            setDisplay('frmDoiGioSale', 'none');
                        };
                    }
                }

                var bsModal = new bootstrap.Modal(eventModalEl);
                bsModal.show();
            }

            // Dọn dẹp DOM khi đóng modal
            eventModalEl.addEventListener('hidden.bs.modal', function() {
                setDisplay('frmDoiGioSale', 'none');
                setDisplay('frmHuy', 'none');
                document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                document.body.classList.remove('modal-open');
                document.body.style.overflow = '';
                document.body.style.paddingRight = '';
            });

            // Khởi tạo FullCalendar
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'timeGridWeek',
                locale: 'vi',
                height: 'auto',
                expandRows: true,
                nowIndicator: true,
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                slotMinTime: '07:00:00',
                slotMaxTime: '21:00:00',
                slotDuration: '00:30:00',
                slotLabelInterval: '01:00:00',
                allDaySlot: false,
                slotEventOverlap: false,
                eventMaxStack: 3,
                dayMaxEvents: true,
                views: {
                    dayGridMonth: {
                        dayMaxEventRows: 3
                    },
                    timeGridWeek: {
                        dayHeaderFormat: {
                            weekday: 'short',
                            day: '2-digit',
                            month: '2-digit'
                        }
                    },
                    timeGridDay: {
                        dayHeaderFormat: {
                            weekday: 'long',
                            day: '2-digit',
                            month: '2-digit'
                        }
                    }
                },
                events: "{{ route('nhanvien.admin.lich-hen.api-events') }}",
                eventTimeFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false
                },
                eventContent: function(arg) {
                    var wrap = document.createElement('div');
                    wrap.className = 'fc-custom-event';

                    var time = document.createElement('div');
                    time.className = 'fc-event-time';
                    time.textContent = arg.timeText || '';

                    var title = document.createElement('div');
                    title.className = 'fc-event-title';
                    title.textContent = arg.event.title || '';

                    wrap.appendChild(time);
                    wrap.appendChild(title);
                    return {
                        domNodes: [wrap]
                    };
                },
                eventClick: function(info) {
                    if (info.jsEvent) {
                        info.jsEvent.preventDefault();
                        info.jsEvent.stopPropagation();
                    }
                    openLichHenModal(info.event.id, info.event.extendedProps || {}, info.event.start);
                }
            });

            // Bắt sự kiện chuyển Tab để fix lỗi render của Calendar
            var tabCalendarEl = document.getElementById('calendar-tab');
            if (tabCalendarEl) {
                tabCalendarEl.addEventListener('shown.bs.tab', function() {
                    calendar.render();
                });
            }

            if (document.getElementById('tab-calendar').classList.contains('show')) {
                calendar.render();
            }

            // Sự kiện click nút "Xử lý" ở danh sách
            document.querySelectorAll('.btn-trigger-modal').forEach(btn => {
                btn.addEventListener('click', function() {
                    let id = this.dataset.id;
                    let start = this.dataset.start;
                    let props = {
                        trang_thai: this.dataset.trang_thai,
                        ten_khach: this.dataset.ten_khach,
                        sdt_khach: this.dataset.sdt_khach,
                        bds: this.dataset.bds,
                        sale_id: this.dataset.sale_id,
                        is_doi_gio: this.dataset.is_doi_gio,
                        ly_do_huy: this.dataset.ly_do_huy
                    };
                    openLichHenModal(id, props, start);
                });
            });

        });
    </script>
@endpush
