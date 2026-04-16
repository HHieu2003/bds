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
                <button class="nav-link " id="calendar-tab" data-bs-toggle="tab" data-bs-target="#tab-calendar"
                    type="button"><i class="fas fa-calendar-day me-1"></i> Lịch của tôi</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="list-tab" data-bs-toggle="tab" data-bs-target="#tab-list" type="button"><i
                        class="fas fa-list me-1"></i> Danh sách tổng hợp</button>
            </li>

        </ul>

        <div class="tab-content" id="lhTabContent">
            {{-- TAB 1: CALENDAR --}}
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

            {{-- TAB 2: DANH SÁCH --}}
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
                                    <td>
                                        @php
                                            $ttMap = [
                                                'moi_dat' => ['bg-warning text-dark', 'Chưa nhận'],
                                                'sale_da_nhan' => ['bg-primary', 'Đang xác nhận'],
                                                'cho_xac_nhan' => ['bg-info text-dark', 'Chờ Nguồn chốt'],
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
                                            data-ten_khach="{{ $lh->ten_khach_hang }}"
                                            data-sdt_khach="{{ $lh->sdt_khach_hang }}"
                                            data-bds="{{ optional($lh->batDongSan)->tieu_de }}"
                                            data-sale_id="{{ $lh->nhan_vien_sale_id }}"
                                            data-nguon_phu_trach_id="{{ optional($lh->batDongSan)->nhan_vien_phu_trach_id }}"
                                            data-start="{{ $lh->thoi_gian_hen }}">
                                            <i class="fas fa-magic text-primary"></i>
                                        </button>
                                        <a href="{{ route('nhanvien.admin.lich-hen.show', $lh->id) }}"
                                            class="btn btn-sm btn-light border"><i
                                                class="fas fa-external-link-alt"></i></a>
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
                <div class="mt-3">{{ $lichHensList->links() }}</div>
            </div>

            {{-- TAB 3: YÊU CẦU XEM NHÀ (MỚI ĐẶT CHƯA AI NHẬN) --}}
            <div class="tab-pane fade  show active" id="tab-request">
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
                                            <form action="{{ route('nhanvien.admin.lich-hen.nhan-lich', $lh->id) }}"
                                                method="POST">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-danger fw-bold shadow-sm"
                                                    style="animation: pulse-animation 2s infinite;">
                                                    <i class="fas fa-hand-paper me-1"></i> NHẬN LỊCH NGAY
                                                </button>
                                            </form>
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
        </div>
    </div>

    {{-- MODAL XỬ LÝ LỊCH HẸN --}}
    <div class="modal fade" id="modalLichHen" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold text-primary"><i class="fas fa-calendar-check me-2"></i>Thao tác lịch
                        hẹn</h5>
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

                    {{-- Form 1: NHẬN LỊCH --}}
                    <form id="frmNhanLich" action="" method="POST" style="display: none;" class="mb-2">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-danger w-100 fw-bold py-2"><i
                                class="fas fa-hand-paper me-2"></i>NHẬN XỬ LÝ LỊCH NÀY</button>
                    </form>

                    {{-- Form 2: CHUYỂN NGUỒN & TỪ CHỐI (Sau khi nhận) --}}
                    <div id="boxXacNhan" style="display: none;">
                        <div class="alert alert-info small mb-3">Hãy gọi khách hàng xác nhận. Nếu thông tin chuẩn, chọn
                            Nguồn để yêu cầu mở cửa.</div>
                        <form id="frmChuyenNguon" action="" method="POST" class="mb-3">
                            @csrf @method('PATCH')
                            <div class="mb-2">
                                <label class="form-label fw-bold small">Ghi chú (Tùy chọn):</label>
                                <input type="text" name="ghi_chu_sale" class="form-control form-control-sm"
                                    placeholder="Ghi chú cho Nguồn...">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-dark"><i
                                        class="fas fa-user-tag text-warning me-1"></i> Chọn Nguồn hàng:</label>
                                <select name="nhan_vien_nguon_hang_id" id="nhan_vien_nguon_hang_id" class="form-select"
                                    required>
                                    <option value="">-- Chọn Nguồn hàng --</option>
                                    @foreach ($dsNguonHang as $ng)
                                        <option value="{{ $ng->id }}">{{ $ng->ho_ten }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 fw-bold py-2"><i
                                    class="fas fa-paper-plane me-2"></i>GỬI CHO NGUỒN</button>
                        </form>

                        <form id="frmTuChoi" action="" method="POST">
                            @csrf @method('PATCH')
                            <input type="hidden" name="ly_do_tu_choi" value="Sai số hoặc khách hủy">
                            <button type="button" class="btn btn-outline-danger w-100 btn-sm"
                                onclick="if(confirm('Khách ảo/sai số? Từ chối lịch này?')) this.form.submit();">Khách ảo /
                                Báo Hủy</button>
                        </form>
                    </div>

                    <div class="mt-3 text-center">
                        <a href="#" id="btnFullDetail" class="text-decoration-none">Vào trang xem chi tiết toàn
                            bộ</a>
                    </div>
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

            let nguonSelect = document.getElementById('nhan_vien_nguon_hang_id');
            if (nguonSelect) nguonSelect.value = props.nguon_phu_trach_id || "";

            let badgeHtml = '';
            let frmNhanLich = document.getElementById('frmNhanLich');
            let boxXacNhan = document.getElementById('boxXacNhan');

            frmNhanLich.style.display = 'none';
            boxXacNhan.style.display = 'none';

            if (props.trang_thai === 'moi_dat' && (!props.sale_id)) {
                badgeHtml = '<span class="badge bg-warning text-dark">Chưa có người nhận</span>';
                frmNhanLich.style.display = 'block';
                frmNhanLich.action = `/nhan-vien/admin/lich-hen/${id}/nhan-lich`;
            } else if (props.trang_thai === 'sale_da_nhan' && props.sale_id == {{ $nhanVien->id }}) {
                badgeHtml = '<span class="badge bg-primary">Đang xác nhận thông tin</span>';
                boxXacNhan.style.display = 'block';
                document.getElementById('frmChuyenNguon').action = `/nhan-vien/admin/lich-hen/${id}/tiep-nhan`;
                document.getElementById('frmTuChoi').action = `/nhan-vien/admin/lich-hen/${id}/sale-tu-choi`;
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
