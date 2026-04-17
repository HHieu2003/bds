@extends('admin.layouts.master')
@section('title', 'Lịch hẹn làm việc')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
    <style>
        /* CSS cho CRM Tabs */
        .nav-tabs .nav-link {
            font-weight: 600;
            color: #6c757d;
            background-color: #f8f9fa;
            border: 1px solid transparent;
            padding: 12px 20px;
            transition: all 0.2s ease-in-out;
        }

        .nav-tabs .nav-link:hover {
            color: var(--bs-primary);
            background-color: #e9ecef;
        }

        .nav-tabs .nav-link.active {
            color: var(--bs-primary);
            font-weight: 700;
            background-color: #fff;
            border-top: 3px solid var(--bs-primary) !important;
            border-bottom: 0 !important;
            box-shadow: 0 -3px 5px rgba(0, 0, 0, 0.02);
        }

        .modal-ticket-info {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            border-left: 4px solid #0d6efd;
            margin-bottom: 20px;
        }

        .ticket-row {
            display: flex;
            margin-bottom: 8px;
        }

        .ticket-label {
            width: 110px;
            color: #6c757d;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .ticket-data {
            flex: 1;
            color: #212529;
            font-weight: 600;
        }
    </style>
@endpush

@section('content')
    <div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h1 class="page-header-title"><i class="fas fa-calendar-alt text-primary me-2"></i> Quản lý Lịch hẹn</h1>
            @php
                $tongLich = array_sum($stats ?? []);
                $dangXuLy =
                    ($stats['sale_da_nhan'] ?? 0) +
                    ($stats['cho_xac_nhan'] ?? 0) +
                    ($stats['cho_sale_xac_nhan_doi_gio'] ?? 0);
                $trangThaiLabels = [
                    'moi_dat' => 'Mới đặt',
                    'sale_da_nhan' => 'Sale đang xác nhận',
                    'cho_xac_nhan' => 'Chờ Nguồn chốt',
                    'cho_sale_xac_nhan_doi_gio' => 'Chờ Sale chốt dời giờ',
                    'da_xac_nhan' => 'Đã chốt đi xem',
                    'hoan_thanh' => 'Hoàn thành',
                    'tu_choi' => 'Từ chối',
                    'huy' => 'Đã hủy',
                ];
            @endphp
            {{-- THỐNG KÊ NHANH --}}
            <div style="font-size:.78rem;color:var(--text-sub);" class="mt-1">
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <span><strong>{{ number_format($tongLich) }}</strong> lịch tổng</span>
                    <span
                        style="width:4px;height:4px;border-radius:50%;background:var(--text-muted);display:inline-block"></span>
                    <span style="color:#0d6efd"><strong>{{ number_format($stats['moi_dat'] ?? 0) }}</strong> 🆕 mới</span>
                    <span
                        style="width:4px;height:4px;border-radius:50%;background:var(--text-muted);display:inline-block"></span>
                    <span style="color:#FF9800"><strong>{{ number_format($dangXuLy) }}</strong> ⏳ đang xử lý</span>
                    <span
                        style="width:4px;height:4px;border-radius:50%;background:var(--text-muted);display:inline-block"></span>
                    <span style="color:#28a745"><strong>{{ number_format($stats['da_xac_nhan'] ?? 0) }}</strong> ✅ đã chốt
                        đi xem</span>
                    <span
                        style="width:4px;height:4px;border-radius:50%;background:var(--text-muted);display:inline-block"></span>
                    <span style="color:#6c757d"><strong>{{ number_format($stats['hoan_thanh'] ?? 0) }}</strong> 🏆 hoàn
                        thành</span>
                </div>
            </div>
        </div>
        <div class="d-flex gap-2">
            @if ($nhanVien->vai_tro == 'admin')
                <a href="{{ route('nhanvien.admin.lich-hen.index', ['giao_dien' => 'nguon_hang']) }}"
                    class="btn btn-warning shadow-sm fw-bold">
                    <i class="fas fa-user-shield me-1"></i> Bảng Nguồn Hàng
                </a>
            @endif
            <a href="{{ route('nhanvien.admin.lich-hen.create') }}" class="btn btn-primary shadow-sm"><i
                    class="fas fa-plus me-1"></i> Tạo lịch thủ công</a>
        </div>
    </div>

    {{-- KHỐI TABS --}}
    <ul class="nav nav-tabs mb-4 border-bottom-0" id="lhTab" role="tablist" style="gap: 5px;">
        <li class="nav-item">
            <button class="nav-link active border-0" id="request-tab" data-bs-toggle="tab" data-bs-target="#tab-request"
                type="button" style="border-radius: 6px 6px 0 0;">
                <i class="fas fa-home me-1"></i> Yêu cầu mới
                @if (($stats['moi_dat'] ?? 0) > 0)
                    <span class="badge bg-danger rounded-pill ms-1">{{ $stats['moi_dat'] }}</span>
                @endif
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link border-0" id="processing-tab" data-bs-toggle="tab" data-bs-target="#tab-processing"
                type="button" style="border-radius: 6px 6px 0 0;">
                <i class="fas fa-spinner fa-spin me-1"></i> Đang xử lý
                @if (($lichHenDangXuLyItems->total() ?? 0) > 0)
                    <span class="badge bg-primary ms-1">{{ $lichHenDangXuLyItems->total() }}</span>
                @endif
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link border-0" id="calendar-tab" data-bs-toggle="tab" data-bs-target="#tab-calendar"
                type="button" style="border-radius: 6px 6px 0 0;">
                <i class="fas fa-calendar-day me-1"></i> Lịch của tôi
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link border-0" id="list-tab" data-bs-toggle="tab" data-bs-target="#tab-list" type="button"
                style="border-radius: 6px 6px 0 0;">
                <i class="fas fa-list me-1"></i> Danh sách tổng hợp
            </button>
        </li>
    </ul>

    <div class="tab-content" id="lhTabContent">

        {{-- TAB YÊU CẦU XEM NHÀ --}}
        <div class="tab-pane fade show active" id="tab-request">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white border-bottom-0 pt-3 pb-0">
                    <h5 class="fw-bold mb-0 text-primary"><i class="fas fa-bell me-2"></i> Yêu cầu xem nhà cần nhận</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Khách hàng</th>
                                    <th>Bất động sản</th>
                                    <th>Thời gian</th>
                                    <th class="text-center">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($lichHenMoiItems as $lh)
                                    <tr>
                                        <td>
                                            <div class="fw-bold">{{ $lh->ten_khach_hang }}</div>
                                            <div class="small">{{ $lh->sdt_khach_hang }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-bold text-primary">{{ optional($lh->batDongSan)->tieu_de }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-bold text-danger">
                                                {{ $lh->thoi_gian_hen->format('H:i - d/m/Y') }}</div>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex gap-2 justify-content-center">
                                                <button class="btn btn-sm btn-light border btn-trigger-modal fw-bold"
                                                    data-id="{{ $lh->id }}" data-trang_thai="{{ $lh->trang_thai }}"
                                                    data-ten_khach="{{ $lh->ten_khach_hang }}"
                                                    data-sdt_khach="{{ $lh->sdt_khach_hang }}"
                                                    data-bds="{{ optional($lh->batDongSan)->tieu_de }}"
                                                    data-sale_id="{{ $lh->nhan_vien_sale_id }}"
                                                    data-start="{{ $lh->thoi_gian_hen }}">
                                                    <i class="fas fa-eye text-primary"></i> Xem
                                                </button>
                                                <form action="{{ route('nhanvien.admin.lich-hen.nhan-lich', $lh->id) }}"
                                                    method="POST">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-danger fw-bold shadow-sm"
                                                        style="animation: pulse-animation 2s infinite;">NHẬN LỊCH</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-3 text-muted">Không có yêu cầu xem nhà
                                            mới.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if ($lichHenMoiItems->hasPages())
                        <div class="p-3 border-top">
                            {{ $lichHenMoiItems->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- TAB ĐANG XỬ LÝ (ACTION TRỰC TIẾP BẰNG MODAL) --}}
        <div class="tab-pane fade" id="tab-processing">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white border-bottom-0 pt-3 pb-0">
                    <h5 class="fw-bold mb-0 text-primary"><i class="fas fa-tasks me-2"></i> Lịch hẹn đang quản lý</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Thời gian</th>
                                    <th>Khách hàng</th>
                                    <th>Bất động sản</th>
                                    <th>Nguồn hỗ trợ</th>
                                    <th class="text-center">Tình trạng & Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($lichHenDangXuLyItems as $lh)
                                    <tr>
                                        <td>
                                            <div class="fw-bold text-danger">{{ $lh->thoi_gian_hen->format('H:i') }}</div>
                                            <div class="small fw-medium">{{ $lh->thoi_gian_hen->format('d/m/Y') }}</div>
                                        </td>
                                        <td>
                                            <div class="fw-bold">{{ $lh->ten_khach_hang }}</div>
                                            <div class="small text-success">{{ $lh->sdt_khach_hang }}</div>
                                        </td>
                                        <td>
                                            <a href="javascript:void(0)" class="fw-bold text-primary text-decoration-none btn-trigger-modal"
                                                data-id="{{ $lh->id }}"
                                                data-trang_thai="{{ $lh->trang_thai }}"
                                                data-ten_khach="{{ $lh->ten_khach_hang }}"
                                                data-sdt_khach="{{ $lh->sdt_khach_hang }}"
                                                data-bds="{{ optional($lh->batDongSan)->tieu_de }}"
                                                data-sale_id="{{ $lh->nhan_vien_sale_id }}"
                                                data-start="{{ $lh->thoi_gian_hen }}">
                                                {{ optional($lh->batDongSan)->tieu_de ?? 'Nhà lẻ' }}
                                            </a>
                                        </td>
                                        <td>
                                            <div class="small fw-bold">
                                                {{ optional($lh->nhanVienNguonHang)->ho_ten ?? 'Chưa gán' }}</div>
                                        </td>
                                        <td class="text-center" style="width: 250px;">
                                            @if ($lh->trang_thai === 'sale_da_nhan')
                                                <span class="badge bg-primary d-block mb-2 py-2 w-100">Cần gọi khách xác
                                                    nhận</span>
                                                <button class="btn btn-sm btn-outline-primary w-100 fw-bold mb-1"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalChuyenNguon{{ $lh->id }}">Chuyển cho
                                                    Nguồn</button>
                                                <button class="btn btn-sm btn-outline-danger w-100 fw-bold"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalSaleTuChoi{{ $lh->id }}">Từ chối (Khách
                                                    ảo)</button>
                                            @elseif ($lh->trang_thai === 'cho_xac_nhan')
                                                <span class="badge bg-warning text-dark d-block mb-2 py-2 w-100">Đang chờ
                                                    Nguồn chốt mở cửa</span>
                                                <a href="{{ route('nhanvien.admin.lich-hen.show', $lh->id) }}"
                                                    class="btn btn-sm btn-light border w-100">Xem tiến độ chi tiết</a>
                                            @elseif ($lh->trang_thai === 'cho_sale_xac_nhan_doi_gio')
                                                <div class="alert alert-danger p-2 small text-start mb-2"><i
                                                        class="fas fa-exclamation-circle me-1"></i> <strong>Nguồn xin dời
                                                        giờ:</strong><br>{{ $lh->ghi_chu_nguon_hang }}</div>
                                                <form
                                                    action="{{ route('nhanvien.admin.lich-hen.xac-nhan-doi-gio', $lh->id) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf @method('POST')
                                                    <button type="submit"
                                                        class="btn btn-sm btn-success fw-bold w-100 mb-1"
                                                        onclick="return confirm('Khách hàng đồng ý giờ mới? Chốt lịch!')">Khách
                                                        OK - Chốt đi xem</button>
                                                </form>
                                                <button class="btn btn-sm btn-outline-danger w-100 fw-bold"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalHuyShow{{ $lh->id }}">Khách Hủy (Không đi
                                                    nữa)</button>
                                            @elseif ($lh->trang_thai === 'da_xac_nhan')
                                                <span class="badge bg-success d-block mb-2 py-2 w-100">Đã chốt giờ - Xách
                                                    xe đi xem</span>
                                                <button class="btn btn-sm btn-secondary w-100 fw-bold mb-1"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalHoanThanh{{ $lh->id }}">Báo kết quả Hoàn
                                                    Thành</button>
                                                <button class="btn btn-sm btn-outline-warning w-100 fw-bold"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalSaleDoiGio{{ $lh->id }}">Dời lịch</button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">Bạn không có lịch hẹn nào
                                            đang cần xử lý.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if ($lichHenDangXuLyItems->hasPages())
                        <div class="p-3 border-top">
                            {{ $lichHenDangXuLyItems->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- TAB LỊCH CỦA TÔI (FULLCALENDAR) --}}
        <div class="tab-pane fade" id="tab-calendar">
            <div class="row g-3">
                <div class="col-12 col-xl-9">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-0">
                            <div id="calendar" class="p-3"></div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-xl-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <h6 class="fw-bold mb-3">Chú giải màu sắc</h6>
                            <ul class="list-unstyled small mb-0">
                                <li class="mb-2"><span class="d-inline-block rounded-circle me-2"
                                        style="width: 12px; height: 12px; background: #f59e0b;"></span> <strong>Chưa ai
                                        nhận</strong></li>
                                <li class="mb-2"><span class="d-inline-block rounded-circle me-2"
                                        style="width: 12px; height: 12px; background: #8b5cf6;"></span> <strong>Sale đang
                                        xác nhận</strong></li>
                                <li class="mb-2"><span class="d-inline-block rounded-circle me-2"
                                        style="width: 12px; height: 12px; background: #3b82f6;"></span> <strong>Chờ Nguồn
                                        chốt</strong></li>
                                <li class="mb-2"><span class="d-inline-block rounded-circle me-2"
                                        style="width: 12px; height: 12px; background: #ef4444;"></span> <strong>Chờ Sale
                                        chốt dời giờ</strong></li>
                                <li class="mb-2"><span class="d-inline-block rounded-circle me-2"
                                        style="width: 12px; height: 12px; background: #10b981;"></span> <strong>Đã chốt đi
                                        xem</strong></li>
                                <li class="mb-2"><span class="d-inline-block rounded-circle me-2"
                                        style="width: 12px; height: 12px; background: #6b7280;"></span> <strong>Hoàn
                                        thành</strong></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- TAB DANH SÁCH TỔNG HỢP --}}
        <div class="tab-pane fade" id="tab-list">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body p-3">
                    <form method="GET" action="{{ route('nhanvien.admin.lich-hen.index') }}">
                        <div class="row g-2">
                            <div class="col-12 col-md-3"><input type="text" name="tim_kiem" class="form-control"
                                    placeholder="Tìm kiếm..." value="{{ request('tim_kiem') }}"></div>
                            <div class="col-6 col-md-2"><input type="date" name="tu_ngay" class="form-control"
                                    value="{{ request('tu_ngay') }}"></div>
                            <div class="col-6 col-md-2"><input type="date" name="den_ngay" class="form-control"
                                    value="{{ request('den_ngay') }}"></div>
                            <div class="col-6 col-md-3">
                                <select name="trang_thai" class="form-select">
                                    <option value="">-- Mọi trạng thái --</option>
                                    <option value="moi_dat" @selected(request('trang_thai') == 'moi_dat')>Chưa ai nhận</option>
                                    <option value="sale_da_nhan" @selected(request('trang_thai') == 'sale_da_nhan')>Sale đang xác nhận</option>
                                    <option value="cho_xac_nhan" @selected(request('trang_thai') == 'cho_xac_nhan')>Chờ Nguồn chốt</option>
                                    <option value="da_xac_nhan" @selected(request('trang_thai') == 'da_xac_nhan')>Đã chốt đi xem</option>
                                    <option value="hoan_thanh" @selected(request('trang_thai') == 'hoan_thanh')>Hoàn thành</option>
                                    <option value="tu_choi" @selected(request('trang_thai') == 'tu_choi')>Từ chối</option>
                                    <option value="huy" @selected(request('trang_thai') == 'huy')>Đã hủy</option>
                                </select>
                            </div>
                            <div class="col-6 col-md-2 d-flex gap-2">
                                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-filter"></i>
                                    Lọc</button>
                                @if (request()->anyFilled(['tim_kiem', 'tu_ngay', 'den_ngay', 'trang_thai']))
                                    <a href="{{ route('nhanvien.admin.lich-hen.index') }}"
                                        class="btn btn-light border text-danger"><i class="fas fa-times"></i></a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Thời gian</th>
                                    <th>Khách hàng</th>
                                    <th>Bất động sản</th>
                                    <th>Phụ trách</th>
                                    <th>Trạng thái</th>
                                    <th class="text-center">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($lichHensList as $lh)
                                    <tr>
                                        <td>
                                            <div class="fw-bold text-primary">{{ $lh->thoi_gian_hen->format('H:i') }}
                                            </div>
                                            <div class="small text-muted">{{ $lh->thoi_gian_hen->format('d/m/Y') }}</div>
                                        </td>
                                        <td>
                                            <div class="fw-bold">{{ $lh->ten_khach_hang }}</div>
                                            <div class="small text-success">{{ $lh->sdt_khach_hang }}</div>
                                        </td>
                                        <td>
                                            <div class="fw-medium">
                                                {{ optional($lh->batDongSan)->tieu_de ?? 'Ngoài hệ thống' }}</div>
                                        </td>
                                        <td>
                                            <div class="small">Sale:
                                                {{ optional($lh->nhanVienSale)->ho_ten ?? 'Chưa có' }}
                                            </div>
                                            <div class="small">Nguồn:
                                                {{ optional($lh->nhanVienNguonHang)->ho_ten ?? 'Chưa gán' }}</div>
                                        </td>
                                        <td><span
                                                class="badge bg-secondary">{{ $trangThaiLabels[$lh->trang_thai] ?? $lh->trang_thai }}</span>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('nhanvien.admin.lich-hen.show', $lh->id) }}"
                                                class="btn btn-sm btn-light border"><i class="fas fa-eye"></i></a>
                                            @if ($nhanVien->vai_tro == 'admin')
                                                <form action="{{ route('nhanvien.admin.lich-hen.destroy', $lh->id) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger border"
                                                        onclick="return confirm('Xác nhận XÓA lịch hẹn này?')"><i
                                                            class="fas fa-trash"></i></button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">Không có dữ liệu.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if ($lichHensList->hasPages())
                        <div class="p-3 border-top">
                            {{ $lichHensList->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL XỬ LÝ NHANH CHO TAB YÊU CẦU / CALENDAR --}}
    <div class="modal fade" id="modalLichHen" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold text-primary"><i class="fas fa-calendar-check me-2"></i>Chi tiết Yêu
                        cầu Lịch hẹn</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="modal-ticket-info">
                        <div class="ticket-row">
                            <div class="ticket-label">Khách hàng:</div>
                            <div class="ticket-data" id="md_ten_khach"></div>
                        </div>
                        <div class="ticket-row">
                            <div class="ticket-label">Điện thoại:</div>
                            <div class="ticket-data text-success" id="md_sdt_khach"></div>
                        </div>
                        <div class="ticket-row">
                            <div class="ticket-label">Nhà/Dự án:</div>
                            <div class="ticket-data text-primary" id="md_bds"></div>
                        </div>
                        <div class="ticket-row">
                            <div class="ticket-label">Thời gian:</div>
                            <div class="ticket-data text-danger" id="md_thoi_gian"></div>
                        </div>
                        <div class="ticket-row">
                            <div class="ticket-label">Trạng thái:</div>
                            <div class="ticket-data" id="md_trang_thai"></div>
                        </div>
                    </div>
                    <form id="frmNhanLich" action="" method="POST" style="display: none;" class="mb-2">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-danger w-100 fw-bold py-2"><i
                                class="fas fa-hand-paper me-2"></i>NHẬN XỬ LÝ LỊCH NÀY</button>
                    </form>
                    <div class="mt-3 text-center"><a href="#" id="btnFullDetail"
                            class="btn btn-outline-primary w-100 fw-bold">Vào trang Xem chi tiết toàn bộ</a></div>
                </div>
            </div>
        </div>
    </div>

    {{-- VÒNG LẶP MODALS CHO TAB ĐANG XỬ LÝ CỦA TỪNG LỊCH HẸN --}}
    <div id="modals-container">
        @foreach ($lichHenDangXuLyItems as $lh)
            {{-- MODAL CHUYỂN NGUỒN --}}
            @if ($lh->trang_thai === 'sale_da_nhan' && isset($dsNguonHang))
                <div class="modal fade" id="modalChuyenNguon{{ $lh->id }}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <form action="{{ route('nhanvien.admin.lich-hen.tiep-nhan', $lh->id) }}" method="POST"
                            class="modal-content shadow border-0">
                            @csrf @method('PATCH')
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title fw-bold">Chuyển cho Nguồn Hàng</h5><button type="button"
                                    class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body p-4">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Chọn Nguồn hàng phụ trách:</label>
                                    <select name="nhan_vien_nguon_hang_id" class="form-select form-select-lg" required>
                                        <option value="">-- Chọn Nguồn --</option>
                                        @php
                                            $phuTrachId =
                                                $lh->nhan_vien_nguon_hang_id ??
                                                optional($lh->batDongSan)->nhan_vien_phu_trach_id;
                                        @endphp
                                        @foreach ($dsNguonHang as $ng)
                                            <option value="{{ $ng->id }}"
                                                {{ $phuTrachId == $ng->id ? 'selected' : '' }}>
                                                {{ $ng->ho_ten }}
                                                {{ optional($lh->batDongSan)->nhan_vien_phu_trach_id == $ng->id ? '⭐ (Phụ trách)' : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label fw-bold">Ghi chú (Tùy chọn):</label>
                                    <input type="text" name="ghi_chu_sale" class="form-control"
                                        placeholder="Để lại lời nhắn cho nguồn...">
                                </div>
                            </div>
                            <div class="modal-footer"><button type="submit" class="btn btn-primary w-100 fw-bold">Xác
                                    nhận Gửi Nguồn</button></div>
                        </form>
                    </div>
                </div>

                {{-- MODAL SALE TỪ CHỐI --}}
                <div class="modal fade" id="modalSaleTuChoi{{ $lh->id }}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <form action="{{ route('nhanvien.admin.lich-hen.sale-tu-choi', $lh->id) }}" method="POST"
                            class="modal-content shadow border-0">
                            @csrf @method('PATCH')
                            <div class="modal-body p-4">
                                <h5 class="fw-bold text-danger mb-3">Từ chối Yêu cầu</h5>
                                <label class="form-label fw-bold">Lý do từ chối:</label>
                                <textarea name="ly_do_tu_choi" class="form-control" rows="3" required
                                    placeholder="Khách ảo, gọi không nghe máy..."></textarea>
                                <button type="submit" class="btn btn-danger w-100 fw-bold mt-4">Xác nhận Từ chối</button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

            {{-- MODAL HOÀN THÀNH --}}
            @if ($lh->trang_thai === 'da_xac_nhan')
                <div class="modal fade" id="modalHoanThanh{{ $lh->id }}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <form action="{{ route('nhanvien.admin.lich-hen.hoan-thanh', $lh->id) }}" method="POST"
                            class="modal-content shadow border-0">
                            @csrf @method('PATCH')
                            <div class="modal-header bg-success text-white">
                                <h5 class="modal-title fw-bold"><i class="fas fa-flag-checkered me-2"></i>Cập nhật Kết quả
                                    Xem nhà</h5><button type="button" class="btn-close btn-close-white"
                                    data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body p-4">
                                <div class="mb-4">
                                    <label class="form-label fw-bold fs-6">Khách hàng quyết định như thế nào?</label>
                                    <select name="ket_qua" class="form-select form-select-lg" required>
                                        <option value="">-- Chọn kết quả --</option>
                                        <option value="chot" class="text-success fw-bold">✅ ĐÃ CHỐT THÀNH CÔNG (Đặt cọc)
                                        </option>
                                        <option value="khong_chot" class="text-danger fw-bold">❌ KHÔNG CHỐT (Suy nghĩ
                                            thêm)</option>
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label fw-bold">Ghi chú & Review từ khách:</label>
                                    <textarea name="ghi_chu_sale" class="form-control" rows="3" placeholder="Khách chê nhà, giá cao..."></textarea>
                                </div>
                            </div>
                            <div class="modal-footer bg-light border-0"><button type="submit"
                                    class="btn btn-success btn-lg fw-bold w-100">Lưu kết quả & Đóng lịch</button></div>
                        </form>
                    </div>
                </div>
            @endif

            {{-- MODAL SALE DỜI GIỜ --}}
            @if ($lh->trang_thai === 'da_xac_nhan')
                <div class="modal fade" id="modalSaleDoiGio{{ $lh->id }}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <form action="{{ route('nhanvien.admin.lich-hen.sale-doi-gio', $lh->id) }}" method="POST"
                            class="modal-content shadow border-0">
                            @csrf @method('PATCH')
                            <div class="modal-header bg-warning text-dark">
                                <h5 class="modal-title fw-bold"><i class="fas fa-clock me-2"></i>Dời lịch xem nhà</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body p-4">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Thời gian hẹn mới:</label>
                                    <input type="datetime-local" name="thoi_gian_hen" class="form-control" required value="{{ $lh->thoi_gian_hen->format('Y-m-d\TH:i') }}">
                                </div>
                                <div class="mb-2">
                                    <label class="form-label fw-bold">Lý do dời lịch:</label>
                                    <textarea name="ghi_chu_sale" class="form-control" rows="3" required placeholder="Khách bận đột xuất, xin dời qua ngày mai..."></textarea>
                                </div>
                            </div>
                            <div class="modal-footer bg-light border-0"><button type="submit"
                                    class="btn btn-warning btn-lg fw-bold w-100">Xác nhận Dời lịch</button></div>
                        </form>
                    </div>
                </div>
            @endif

            {{-- MODAL HỦY --}}
            @if (!in_array($lh->trang_thai, ['hoan_thanh', 'huy', 'tu_choi']))
                <div class="modal fade" id="modalHuyShow{{ $lh->id }}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <form action="{{ route('nhanvien.admin.lich-hen.huy', $lh->id) }}" method="POST"
                            class="modal-content shadow border-0">
                            @csrf @method('PATCH')
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title fw-bold"><i class="fas fa-ban me-2"></i> Hủy lịch xem nhà</h5>
                                <button type="button" class="btn-close btn-close-white"
                                    data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body p-4">
                                <label class="form-label fw-bold">Nguyên nhân hủy:</label>
                                <textarea name="ly_do" class="form-control" required placeholder="Khách bận đột xuất, chủ nhà đi vắng..."></textarea>
                                <button type="submit" class="btn btn-danger mt-4 w-100 fw-bold">Xác nhận Hủy
                                    lịch</button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        @endforeach
    </div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>

    <script>
        function openLichHenModal(id, props, start) {
            const statusLabels = {
                moi_dat: 'Mới đặt',
                sale_da_nhan: 'Sale đang xác nhận',
                cho_xac_nhan: 'Chờ Nguồn chốt',
                cho_sale_xac_nhan_doi_gio: 'Chờ Sale chốt dời giờ',
                da_xac_nhan: 'Đã chốt đi xem',
                hoan_thanh: 'Hoàn thành',
                tu_choi: 'Từ chối',
                huy: 'Đã hủy',
            };

            document.getElementById('md_ten_khach').innerText = props.ten_khach || '---';
            document.getElementById('md_sdt_khach').innerText = props.sdt_khach || '---';
            document.getElementById('md_bds').innerText = props.bds || '---';
            document.getElementById('md_thoi_gian').innerText = moment(start).format('HH:mm DD/MM/YYYY');

            let badgeHtml = '';
            let frmNhanLich = document.getElementById('frmNhanLich');
            frmNhanLich.style.display = 'none';

            if (props.trang_thai === 'moi_dat' && (!props.sale_id)) {
                badgeHtml = '<span class="badge bg-warning text-dark">Chưa có người nhận</span>';
                frmNhanLich.style.display = 'block';
                frmNhanLich.action = `/nhan-vien/admin/lich-hen/${id}/nhan-lich`;
            } else {
                const label = statusLabels[props.trang_thai] || props.trang_thai;
                badgeHtml = `<span class="badge bg-secondary">${label}</span>`;
            }

            document.getElementById('md_trang_thai').innerHTML = badgeHtml;
            document.getElementById('btnFullDetail').href = `/nhan-vien/admin/lich-hen/${id}`;
            new bootstrap.Modal(document.getElementById('modalLichHen')).show();
        }

        document.addEventListener('DOMContentLoaded', function() {
            var calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
                initialView: 'timeGridWeek',
                locale: 'vi',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,listWeek'
                },
                events: '{{ route('nhanvien.admin.lich-hen.api.events') }}',
                eventClick: function(info) {
                    info.jsEvent.preventDefault();
                    openLichHenModal(info.event.id, info.event.extendedProps || {}, info.event.start);
                }
            });
            setTimeout(() => calendar.render(), 300);
            document.getElementById('calendar-tab').addEventListener('shown.bs.tab', () => calendar.render());

            document.querySelectorAll('.btn-trigger-modal').forEach(btn => {
                btn.addEventListener('click', function() {
                    openLichHenModal(this.dataset.id, this.dataset, this.dataset.start);
                });
            });
        });
    </script>
@endpush
