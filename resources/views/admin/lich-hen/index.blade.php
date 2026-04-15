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
            border-bottom: 2px solid transparent;
            padding: 12px 20px;
        }

        .lh-tabs .nav-link.active {
            color: #0d6efd;
            background: none;
            border-bottom: 2px solid #0d6efd;
        }

        /* Giao diện Modal Chi tiết dạng Ticket */
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

    @include('frontend.partials.flash-messages')

    {{-- KHỐI KPI --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3 col-lg-2">
            <div class="lh-kpi border-warning" style="border-left-width: 4px;">
                <div class="k-label text-warning">MỚI ĐẶT</div>
                <div class="k-value">{{ number_format($stats['moi_dat'] ?? 0) }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3 col-lg-2">
            <div class="lh-kpi border-info" style="border-left-width: 4px;">
                <div class="k-label text-info">CHỜ NGUỒN XN</div>
                <div class="k-value">{{ number_format($stats['cho_xac_nhan'] ?? 0) }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3 col-lg-2">
            <div class="lh-kpi border-success" style="border-left-width: 4px;">
                <div class="k-label text-success">ĐÃ CHỐT ĐI XEM</div>
                <div class="k-value">{{ number_format($stats['da_xac_nhan'] ?? 0) }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3 col-lg-2">
            <div class="lh-kpi border-secondary" style="border-left-width: 4px;">
                <div class="k-label text-secondary">HOÀN THÀNH</div>
                <div class="k-value">{{ number_format($stats['hoan_thanh'] ?? 0) }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3 col-lg-2">
            <div class="lh-kpi border-danger" style="border-left-width: 4px;">
                <div class="k-label text-danger">TỪ CHỐI / HỦY</div>
                <div class="k-value">{{ number_format(($stats['tu_choi'] ?? 0) + ($stats['huy'] ?? 0)) }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3 col-lg-2">
            <div class="lh-kpi border-primary" style="background: #eef2ff; border-left-width: 4px;">
                <div class="k-label text-primary">LỊCH TRONG NGÀY</div>
                <div class="k-value">{{ number_format($stats['hom_nay'] ?? 0) }}</div>
            </div>
        </div>
    </div>

    {{-- KHỐI TABS --}}
    <div class="lh-shell shadow-sm mb-5">
        <ul class="nav nav-tabs lh-tabs mb-4" id="lhTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="calendar-tab" data-bs-toggle="tab" data-bs-target="#tab-calendar"
                    type="button" role="tab"><i class="fas fa-calendar-day me-1"></i> Lịch của tôi (Calendar)</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="list-tab" data-bs-toggle="tab" data-bs-target="#tab-list" type="button"
                    role="tab"><i class="fas fa-list me-1"></i> Danh sách tổng hợp</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="request-tab" data-bs-toggle="tab" data-bs-target="#tab-request" type="button"
                    role="tab">
                    <i class="fas fa-inbox me-1"></i> Yêu cầu tư vấn / Kèo khách
                    @if (($lienHeThongKe['moi'] ?? 0) > 0)
                        <span class="badge bg-danger rounded-pill ms-1">{{ $lienHeThongKe['moi'] }}</span>
                    @endif
                </button>
            </li>
        </ul>

        <div class="tab-content" id="lhTabContent">
            {{-- TAB 1: CALENDAR --}}
            <div class="tab-pane fade show active" id="tab-calendar" role="tabpanel">
                <div class="row g-3">
                    <div class="col-12 col-xl-9">
                        <div class="bg-white p-3 rounded-3 border">
                            <div id="calendar" style="min-height: 600px;"></div>
                        </div>
                    </div>
                    <div class="col-12 col-xl-3">
                        <div class="bg-white p-3 rounded-3 border h-100">
                            <h6 class="fw-bold mb-3">Chú giải màu sắc</h6>
                            <ul class="list-unstyled mb-0" style="font-size: 0.85rem">
                                <li class="mb-2"><span class="d-inline-block rounded-circle me-2"
                                        style="width: 12px; height: 12px; background: #f59e0b;"></span> <strong>Mới
                                        đặt</strong> (Chờ Sale nhận)</li>
                                <li class="mb-2"><span class="d-inline-block rounded-circle me-2"
                                        style="width: 12px; height: 12px; background: #3b82f6;"></span> <strong>Chờ
                                        xác nhận</strong> (Chờ Nguồn chốt chủ)</li>
                                <li class="mb-2"><span class="d-inline-block rounded-circle me-2"
                                        style="width: 12px; height: 12px; background: #10b981;"></span> <strong>Đã chốt đi
                                        xem</strong></li>
                                <li class="mb-2"><span class="d-inline-block rounded-circle me-2"
                                        style="width: 12px; height: 12px; background: #6b7280;"></span> <strong>Hoàn
                                        thành</strong></li>
                                <li class="mb-2"><span class="d-inline-block rounded-circle me-2"
                                        style="width: 12px; height: 12px; background: #ef4444;"></span> <strong>Hủy / Từ
                                        chối</strong></li>
                            </ul>
                            <hr>
                            <p class="text-muted small"><em>Gợi ý: Nhấp vào sự kiện trên lịch để xem chi tiết hoặc thay đổi
                                    trạng thái.</em></p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TAB 2: DANH SÁCH --}}
            <div class="tab-pane fade" id="tab-list" role="tabpanel">
                <div class="bg-white p-3 rounded-3 border mb-3">
                    <form method="GET" action="{{ route('nhanvien.admin.lich-hen.index') }}">
                        <div class="row g-2">
                            <div class="col-12 col-md-3">
                                <input type="text" name="tim_kiem" class="form-control"
                                    placeholder="Tên khách, SĐT, Mã BĐS..." value="{{ request('tim_kiem') }}">
                            </div>
                            <div class="col-6 col-md-2">
                                <input type="date" name="tu_ngay" class="form-control" title="Từ ngày"
                                    value="{{ request('tu_ngay') }}">
                            </div>
                            <div class="col-6 col-md-2">
                                <input type="date" name="den_ngay" class="form-control" title="Đến ngày"
                                    value="{{ request('den_ngay') }}">
                            </div>
                            <div class="col-6 col-md-3">
                                <select name="trang_thai" class="form-select">
                                    <option value="">-- Mọi trạng thái --</option>
                                    <option value="moi_dat" @selected(request('trang_thai') == 'moi_dat')>Mới đặt (Chờ Sale nhận)
                                    </option>
                                    <option value="cho_xac_nhan" @selected(request('trang_thai') == 'cho_xac_nhan')>Chờ Nguồn chốt với
                                        Chủ</option>
                                    <option value="da_xac_nhan" @selected(request('trang_thai') == 'da_xac_nhan')>Đã chốt đi xem
                                    </option>
                                    <option value="hoan_thanh" @selected(request('trang_thai') == 'hoan_thanh')>Hoàn thành</option>
                                    <option value="tu_choi" @selected(request('trang_thai') == 'tu_choi')>Bị từ chối</option>
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
                                        <div class="small"><a href="tel:{{ $lh->sdt_khach_hang }}"
                                                class="text-success text-decoration-none"><i
                                                    class="fas fa-phone-alt me-1"></i>{{ $lh->sdt_khach_hang }}</a></div>
                                    </td>
                                    <td>
                                        @if ($lh->batDongSan)
                                            <div class="fw-medium text-dark text-truncate" style="max-width: 200px;"
                                                title="{{ $lh->batDongSan->tieu_de }}">
                                                {{ $lh->batDongSan->tieu_de }}
                                            </div>
                                            <div class="small text-muted"><i class="fas fa-map-marker-alt me-1"></i>Mã:
                                                {{ $lh->batDongSan->ma_bat_dong_san }}</div>
                                        @else
                                            <span class="text-muted fst-italic">BĐS ngoài hệ thống</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="small"><strong>Sale:</strong>
                                            {{ $lh->nhanVienSale->ho_ten ?? 'Chưa có' }}</div>
                                        <div class="small"><strong>Nguồn:</strong>
                                            {{ $lh->nhanVienNguonHang->ho_ten ?? 'Chưa xác định' }}</div>
                                    </td>
                                    <td>
                                        @php
                                            $ttMap = [
                                                'moi_dat' => ['bg-warning', 'Mới đặt (Chờ nhận)'],
                                                'cho_xac_nhan' => ['bg-info', 'Chờ Nguồn chốt'],
                                                'da_xac_nhan' => ['bg-success', 'Đã chốt đi xem'],
                                                'hoan_thanh' => ['bg-secondary', 'Hoàn thành'],
                                                'tu_choi' => ['bg-danger', 'Từ chối'],
                                                'huy' => ['bg-danger', 'Đã hủy'],
                                            ];
                                            $tt = $ttMap[$lh->trang_thai] ?? ['bg-dark', $lh->trang_thai];
                                        @endphp
                                        <span class="badge {{ $tt[0] }}">{{ $tt[1] }}</span>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-light border btn-trigger-modal"
                                            data-id="{{ $lh->id }}" data-trang_thai="{{ $lh->trang_thai }}"
                                            data-trang_thai_label="{{ $tt[1] }}"
                                            data-ten_khach="{{ $lh->ten_khach_hang }}"
                                            data-sdt_khach="{{ $lh->sdt_khach_hang }}"
                                            data-bds="{{ $lh->batDongSan ? $lh->batDongSan->tieu_de : 'Nhà lẻ / Chưa xác định' }}"
                                            data-sale_id="{{ $lh->nhan_vien_sale_id }}"
                                            data-nguon_phu_trach_id="{{ optional($lh->batDongSan)->nhan_vien_phu_trach_id }}"
                                            data-start="{{ $lh->thoi_gian_hen }}" title="Xử lý / Xem chi tiết">
                                            <i class="fas fa-magic text-primary"></i>
                                        </button>
                                        <a href="{{ route('nhanvien.admin.lich-hen.show', $lh->id) }}"
                                            class="btn btn-sm btn-light border" title="Chi tiết trang lớn">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">Không tìm thấy lịch hẹn nào.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $lichHensList->links('pagination::bootstrap-5') }}
                </div>
            </div>

            {{-- TAB 3: YÊU CẦU TƯ VẤN (TÍCH HỢP TỪ LIÊN HỆ) --}}
            <div class="tab-pane fade" id="tab-request" role="tabpanel">
                <div class="bg-white p-4 rounded-3 border">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold m-0"><i class="fas fa-inbox text-danger me-2"></i> Yêu cầu Tư vấn mới từ
                            Website</h5>
                        <a href="{{ route('nhanvien.admin.lien-he.index') }}" class="btn btn-sm btn-outline-primary">Tới
                            trang CRM Leads <i class="fas fa-arrow-right"></i></a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Khách hàng</th>
                                    <th>Nội dung / BĐS quan tâm</th>
                                    <th>Thời gian</th>
                                    <th class="text-center">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($lienHeItems as $lead)
                                    <tr
                                        class="{{ is_null($lead->nhan_vien_phu_trach_id) ? 'bg-danger bg-opacity-10' : '' }}">
                                        <td>
                                            <div class="fw-bold">{{ $lead->ho_ten }}</div>
                                            <div class="small"><i
                                                    class="fas fa-phone-alt text-success me-1"></i>{{ $lead->so_dien_thoai }}
                                            </div>
                                        </td>
                                        <td>
                                            @if ($lead->batDongSan)
                                                <div class="fw-bold text-primary" style="font-size: 0.85rem">Quan tâm:
                                                    {{ Str::limit($lead->batDongSan->tieu_de, 40) }}</div>
                                            @endif
                                            <div class="small text-muted fst-italic">
                                                "{{ Str::limit($lead->noi_dung, 60) }}"</div>
                                        </td>
                                        <td>
                                            <div class="small">{{ $lead->created_at->format('H:i d/m/Y') }}</div>
                                        </td>
                                        <td class="text-center">
                                            @if (is_null($lead->nhan_vien_phu_trach_id))
                                                <form action="{{ route('nhanvien.admin.lien-he.nhan-lead', $lead->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-danger fw-bold shadow-sm"
                                                        style="animation: pulse-animation 2s infinite;">
                                                        <i class="fas fa-hand-paper me-1"></i> NHẬN NGAY
                                                    </button>
                                                </form>
                                            @else
                                                <span class="badge bg-secondary">Đã nhận</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-3">Không có yêu cầu tư vấn mới.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ======================================================
         MODAL CHUẨN UX (TIẾP NHẬN & XỬ LÝ LỊCH HẸN)
         ====================================================== --}}
    <div class="modal fade" id="modalLichHen" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold text-primary"><i class="fas fa-calendar-check me-2"></i>Chi tiết lịch
                        hẹn</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">

                    {{-- Khung Ticket Thông Tin --}}
                    <div class="modal-ticket-info">
                        <div class="ticket-row">
                            <div class="ticket-label"><i class="fas fa-user me-1"></i> Khách hàng:</div>
                            <div class="ticket-data" id="md_ten_khach">---</div>
                        </div>
                        <div class="ticket-row">
                            <div class="ticket-label"><i class="fas fa-phone-alt me-1"></i> Điện thoại:</div>
                            <div class="ticket-data text-success" id="md_sdt_khach">---</div>
                        </div>
                        <div class="ticket-row">
                            <div class="ticket-label"><i class="fas fa-home me-1"></i> Căn nhà/Dự án:</div>
                            <div class="ticket-data text-primary" id="md_bds">---</div>
                        </div>
                        <div class="ticket-row">
                            <div class="ticket-label"><i class="far fa-clock me-1"></i> Thời gian:</div>
                            <div class="ticket-data text-danger" id="md_thoi_gian">---</div>
                        </div>
                        <div class="ticket-row">
                            <div class="ticket-label"><i class="fas fa-info-circle me-1"></i> Trạng thái:</div>
                            <div class="ticket-data"><span class="badge bg-secondary" id="md_trang_thai">---</span></div>
                        </div>
                    </div>

                    {{-- FORM 1: Nút Tiếp nhận (dành cho trạng thái moi_dat) --}}
                    <form id="frmTiepNhan" action="" method="POST" style="display: none;">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark"><i class="fas fa-user-tag text-warning me-1"></i>
                                Chọn Nguồn hàng để liên hệ chủ nhà:</label>
                            <select name="nhan_vien_nguon_hang_id" id="nhan_vien_nguon_hang_id" class="form-select"
                                required>
                                <option value="">-- Chọn Nguồn hàng (Hệ thống đã tự gợi ý) --</option>
                                @foreach ($dsNguonHang as $ng)
                                    <option value="{{ $ng->id }}">{{ $ng->ho_ten }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted d-block mt-1">Lưu ý: Hệ thống đã tự động trỏ đến Nguồn hàng đang quản
                                lý căn này.</small>
                        </div>
                        <button type="submit" class="btn btn-success w-100 fw-bold py-2"><i
                                class="fas fa-hand-holding-heart me-2"></i>TIẾP NHẬN & GỬI YÊU CẦU CHỐT CHỦ</button>
                    </form>

                    {{-- Khu vực hiển thị Nút thao tác nhanh cho các trạng thái khác --}}
                    <div id="actionButtons" class="d-flex flex-column gap-2 mt-3">
                        <a href="#" id="btnFullDetail" class="btn btn-outline-primary fw-bold"><i
                                class="fas fa-external-link-alt me-1"></i>Mở trang chi tiết lịch hẹn</a>
                    </div>

                    {{-- NÚT XÓA DÀNH RIÊNG CHO ADMIN --}}
                    @if ($nhanVien->vai_tro == 'admin')
                        <hr>
                        <form id="frmDeleteLichHen" action="" method="POST"
                            onsubmit="return confirm('Bạn có chắc chắn muốn xóa vĩnh viễn lịch hẹn này? Hành động này không thể hoàn tác!')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100"><i class="fas fa-trash-alt me-2"></i>Xóa
                                vĩnh viễn lịch hẹn</button>
                        </form>
                    @endif
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
            // Đổ dữ liệu vào giao diện Ticket
            document.getElementById('md_ten_khach').innerText = props.ten_khach || '---';
            document.getElementById('md_sdt_khach').innerText = props.sdt_khach || '---';
            document.getElementById('md_bds').innerText = props.bds || '---';
            document.getElementById('md_thoi_gian').innerText = moment(start).format('HH:mm DD/MM/YYYY');

            // Xử lý Gợi ý Nguồn hàng
            let nguonSelect = document.getElementById('nhan_vien_nguon_hang_id');
            if (nguonSelect) {
                if (props.nguon_phu_trach_id) {
                    nguonSelect.value = props.nguon_phu_trach_id;
                } else {
                    nguonSelect.value = "";
                }
            }

            let badgeHtml = '';
            switch (props.trang_thai) {
                case 'moi_dat':
                    badgeHtml = '<span class="badge bg-warning text-dark">Mới đặt</span>';
                    break;
                case 'cho_xac_nhan':
                    badgeHtml = '<span class="badge bg-info text-dark">Chờ Nguồn chốt</span>';
                    break;
                case 'da_xac_nhan':
                    badgeHtml = '<span class="badge bg-success">Đã chốt đi xem</span>';
                    break;
                case 'hoan_thanh':
                    badgeHtml = '<span class="badge bg-secondary">Hoàn thành</span>';
                    break;
                case 'tu_choi':
                    badgeHtml = '<span class="badge bg-danger">Bị từ chối</span>';
                    break;
                case 'huy':
                    badgeHtml = '<span class="badge bg-danger">Đã hủy</span>';
                    break;
                default:
                    badgeHtml = '<span class="badge bg-dark">' + props.trang_thai + '</span>';
            }
            document.getElementById('md_trang_thai').innerHTML = badgeHtml;

            // Xử lý Form Tiếp nhận
            let frmTiepNhan = document.getElementById('frmTiepNhan');
            if (props.trang_thai === 'moi_dat' && (!props.sale_id || props.sale_id == {{ $nhanVien->id }})) {
                frmTiepNhan.style.display = 'block';
                frmTiepNhan.action = `/nhan-vien/admin/lich-hen/${id}/tiep-nhan`;
            } else {
                frmTiepNhan.style.display = 'none';
            }

            // Xử lý Link Form Xóa (Admin)
            let frmDelete = document.getElementById('frmDeleteLichHen');
            if (frmDelete) {
                frmDelete.action = `/nhan-vien/admin/lich-hen/${id}`;
            }

            // Cập nhật link nút Xem chi tiết
            document.getElementById('btnFullDetail').href = `/nhan-vien/admin/lich-hen/${id}`;

            // Hiển thị modal
            var myModal = new bootstrap.Modal(document.getElementById('modalLichHen'));
            myModal.show();
        }

        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'timeGridWeek',
                locale: 'vi',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                },
                slotMinTime: "07:00:00",
                slotMaxTime: "21:00:00",
                allDaySlot: false,
                height: 650,
                events: '{{ route('nhanvien.admin.lich-hen.api.events') }}',
                eventClick: function(info) {
                    if (info.jsEvent) {
                        info.jsEvent.preventDefault();
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

            // Sự kiện click nút icon Ma thuật "Xử lý" ở danh sách
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
                        nguon_phu_trach_id: this.dataset
                            .nguon_phu_trach_id // <-- Data quan trọng
                    };
                    openLichHenModal(id, props, start);
                });
            });
        });
    </script>
@endpush
