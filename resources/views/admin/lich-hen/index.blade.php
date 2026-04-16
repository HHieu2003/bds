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
            font-weight: bold;
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
            border-bottom: 2px solid transparent;
            padding: 12px 20px;
        }

        .lh-tabs .nav-link.active {
            color: #0d6efd;
            background: none;
            border-bottom: 2px solid #0d6efd;
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
            <p class="text-muted mb-0">Theo dõi, sắp xếp lịch đưa khách hàng đi xem nhà.</p>
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

    {{-- KHỐI KPI --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-2">
            <div class="lh-kpi border-warning" style="border-left-width: 4px;">
                <div class="k-label text-warning">MỚI (CHỜ NHẬN)</div>
                <div class="k-value">{{ number_format($stats['moi_dat'] ?? 0) }}</div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="lh-kpi border-primary" style="border-left-width: 4px;">
                <div class="k-label text-primary">ĐANG XÁC NHẬN</div>
                <div class="k-value">{{ number_format($stats['sale_da_nhan'] ?? 0) }}</div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="lh-kpi border-info" style="border-left-width: 4px;">
                <div class="k-label text-info">CHỜ NGUỒN XN</div>
                <div class="k-value">{{ number_format($stats['cho_xac_nhan'] ?? 0) }}</div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="lh-kpi border-success" style="border-left-width: 4px;">
                <div class="k-label text-success">ĐÃ CHỐT ĐI XEM</div>
                <div class="k-value">{{ number_format($stats['da_xac_nhan'] ?? 0) }}</div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="lh-kpi border-secondary" style="border-left-width: 4px;">
                <div class="k-label text-secondary">HOÀN THÀNH</div>
                <div class="k-value">{{ number_format($stats['hoan_thanh'] ?? 0) }}</div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="lh-kpi border-danger" style="border-left-width: 4px;">
                <div class="k-label text-danger">TỪ CHỐI / HỦY</div>
                <div class="k-value">{{ number_format(($stats['tu_choi'] ?? 0) + ($stats['huy'] ?? 0)) }}</div>
            </div>
        </div>
    </div>

    {{-- KHỐI TABS --}}
    <div class="lh-shell shadow-sm mb-5">
        <ul class="nav nav-tabs lh-tabs mb-4" id="lhTab" role="tablist">
            <li class="nav-item">
                <button class="nav-link active" id="request-tab" data-bs-toggle="tab" data-bs-target="#tab-request"
                    type="button">
                    <i class="fas fa-home me-1"></i> Yêu cầu xem nhà
                    @if (($stats['moi_dat'] ?? 0) > 0)
                        <span class="badge bg-danger rounded-pill ms-1">{{ $stats['moi_dat'] }}</span>
                    @endif
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link text-primary" id="processing-tab" data-bs-toggle="tab"
                    data-bs-target="#tab-processing" type="button">
                    <i class="fas fa-spinner fa-spin me-1"></i> Đang xử lý
                    @if (($lichHenDangXuLyItems->total() ?? 0) > 0)
                        <span class="badge bg-primary ms-1">{{ $lichHenDangXuLyItems->total() }}</span>
                    @endif
                </button>
            </li>
            <li class="nav-item"><button class="nav-link" id="calendar-tab" data-bs-toggle="tab"
                    data-bs-target="#tab-calendar" type="button"><i class="fas fa-calendar-day me-1"></i> Lịch của
                    tôi</button></li>
            <li class="nav-item"><button class="nav-link" id="list-tab" data-bs-toggle="tab" data-bs-target="#tab-list"
                    type="button"><i class="fas fa-list me-1"></i> Danh sách tổng hợp</button></li>
        </ul>

        <div class="tab-content" id="lhTabContent">

            {{-- TAB YÊU CẦU XEM NHÀ --}}
            <div class="tab-pane fade show active" id="tab-request">
                <div class="bg-white p-4 rounded-3 border border-warning shadow-sm">
                    <h5 class="fw-bold mb-3 text-warning"><i class="fas fa-bell me-2"></i> Yêu cầu xem nhà cần nhận</h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
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
                                            <div class="small"><i
                                                    class="fas fa-phone-alt text-success me-1"></i>{{ $lh->sdt_khach_hang }}
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
                                                        style="animation: pulse-animation 2s infinite;"><i
                                                            class="fas fa-hand-paper me-1"></i> NHẬN LỊCH</button>
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
                </div>
            </div>

            {{-- TAB ĐANG XỬ LÝ --}}
            <div class="tab-pane fade" id="tab-processing">
                <div class="bg-white p-4 rounded-3 border shadow-sm">
                    <h5 class="fw-bold mb-3 text-primary"><i class="fas fa-tasks me-2"></i> Lịch hẹn đang quản lý</h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle border">
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
                                            <div class="small text-success"><i
                                                    class="fas fa-phone-alt me-1"></i>{{ $lh->sdt_khach_hang }}</div>
                                        </td>
                                        <td>
                                            <div class="fw-bold text-primary">
                                                {{ optional($lh->batDongSan)->tieu_de ?? 'Nhà lẻ' }}</div>
                                        </td>
                                        <td>
                                            <div class="small fw-bold">
                                                {{ optional($lh->nhanVienNguonHang)->ho_ten ?? 'Chưa gán' }}</div>
                                        </td>
                                        <td class="text-center" style="width: 250px;">
                                            @if ($lh->trang_thai === 'sale_da_nhan')
                                                <span class="badge bg-primary d-block mb-2 py-2 w-100">Cần gọi khách xác
                                                    nhận</span>
                                                <a href="{{ route('nhanvien.admin.lich-hen.show', $lh->id) }}"
                                                    class="btn btn-sm btn-outline-primary w-100 fw-bold">Vào xem & Chuyển
                                                    Nguồn</a>
                                            @elseif ($lh->trang_thai === 'cho_xac_nhan')
                                                <span class="badge bg-warning text-dark d-block mb-2 py-2 w-100">Đang chờ
                                                    Nguồn chốt mở cửa</span>
                                                <a href="{{ route('nhanvien.admin.lich-hen.show', $lh->id) }}"
                                                    class="btn btn-sm btn-light border w-100">Xem tiến độ</a>
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
                                                <a href="{{ route('nhanvien.admin.lich-hen.show', $lh->id) }}"
                                                    class="btn btn-sm btn-outline-danger w-100">Khách Hủy / Chi tiết</a>
                                            @elseif ($lh->trang_thai === 'da_xac_nhan')
                                                <span class="badge bg-success d-block mb-2 py-2 w-100">Đã chốt giờ - Xách
                                                    xe đi xem</span>
                                                <a href="{{ route('nhanvien.admin.lich-hen.show', $lh->id) }}"
                                                    class="btn btn-sm btn-secondary w-100 fw-bold">Báo kết quả Hoàn
                                                    Thành</a>
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
                    <div class="mt-3">{{ $lichHenDangXuLyItems->links() }}</div>
                </div>
            </div>

            {{-- TAB CALENDAR --}}
            <div class="tab-pane fade" id="tab-calendar">
                <div class="row g-3">
                    <div class="col-12 col-xl-9">
                        <div class="bg-white p-3 rounded-3 border">
                            <div id="calendar" style="min-height: 600px;"></div>
                        </div>
                    </div>
                    <div class="col-12 col-xl-3">
                        <div class="bg-white p-3 rounded-3 border h-100">
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

            {{-- TAB DANH SÁCH --}}
            <div class="tab-pane fade" id="tab-list">
                <div class="bg-white p-3 rounded-3 border mb-3">
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
                <div class="table-responsive bg-white rounded-3 border">
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
                                        <div class="fw-bold text-primary">{{ $lh->thoi_gian_hen->format('H:i') }}</div>
                                        <div class="small text-muted">{{ $lh->thoi_gian_hen->format('d/m/Y') }}</div>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $lh->ten_khach_hang }}</div>
                                        <div class="small text-success"><i
                                                class="fas fa-phone-alt me-1"></i>{{ $lh->sdt_khach_hang }}</div>
                                    </td>
                                    <td>
                                        <div class="fw-medium">
                                            {{ optional($lh->batDongSan)->tieu_de ?? 'Ngoài hệ thống' }}</div>
                                    </td>
                                    <td>
                                        <div class="small">Sale: {{ optional($lh->nhanVienSale)->ho_ten ?? 'Chưa có' }}
                                        </div>
                                        <div class="small">Nguồn:
                                            {{ optional($lh->nhanVienNguonHang)->ho_ten ?? 'Chưa gán' }}</div>
                                    </td>
                                    <td><span class="badge bg-secondary">{{ $lh->trang_thai }}</span></td>
                                    <td class="text-center"><a
                                            href="{{ route('nhanvien.admin.lich-hen.show', $lh->id) }}"
                                            class="btn btn-sm btn-light border"><i
                                                class="fas fa-external-link-alt"></i></a></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">Không có dữ liệu.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">{{ $lichHensList->links() }}</div>
            </div>
        </div>
    </div>

    {{-- MODAL XỬ LÝ LỊCH HẸN TỪ TAB CALENDAR HOẶC YÊU CẦU --}}
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
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales-all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>

    <script>
        function openLichHenModal(id, props, start) {
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
                badgeHtml = `<span class="badge bg-secondary">${props.trang_thai}</span>`;
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
