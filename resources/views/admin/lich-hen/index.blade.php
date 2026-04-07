@extends('admin.layouts.master')
@section('title', 'Lịch hẹn làm việc')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
    <style>
        #calendar {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .fc-event {
            cursor: pointer;
            padding: 2px 4px;
            border: none;
        }

        .fc-toolbar-title {
            font-size: 1.25rem !important;
            font-weight: bold;
            color: var(--navy);
        }

        .legend-box {
            width: 15px;
            height: 15px;
            display: inline-block;
            border-radius: 3px;
            margin-right: 5px;
            vertical-align: middle;
        }
    </style>
@endpush

@section('content')
    <div class="mb-4">
        <h1 class="page-header-title"><i class="fas fa-calendar-alt text-primary me-2"></i> Lịch Hẹn Khách Hàng</h1>
        <p class="text-muted">Nhấp vào sự kiện trên lịch để xem chi tiết hoặc thao tác.</p>
    </div>

    <div class="d-flex flex-wrap gap-3 mb-3 bg-white p-3 rounded shadow-sm">
        <div><span class="legend-box" style="background:#f59e0b;"></span>Mới đặt (Cần người nhận)</div>
        <div><span class="legend-box" style="background:#3b82f6;"></span>Chờ Nguồn xác nhận</div>
        <div><span class="legend-box" style="background:#10b981;"></span>Đã chốt (Đi xem)</div>
        <div><span class="legend-box" style="background:#ef4444;"></span>Hủy/Từ chối</div>
        <div><span class="legend-box" style="background:#6b7280;"></span>Đã xong</div>
    </div>

    <div id='calendar'></div>

    <div class="modal fade" id="eventModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold" id="m_title">Chi tiết lịch hẹn</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <span class="text-muted small">Khách hàng:</span>
                        <h5 class="fw-bold text-dark mb-0" id="m_khach"></h5>
                        <div class="mt-1"><i class="fas fa-phone text-success"></i> <a href="#" id="m_sdt"
                                class="fw-bold text-success text-decoration-none"></a></div>
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
                            <div id="m_status" class="fw-bold"></div>
                        </div>
                    </div>

                    <div id="div_ghi_chu" class="alert alert-warning" style="display:none;"></div>

                    <hr>
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
                            <button type="submit" class="btn btn-warning w-100 fw-bold"><i
                                    class="fas fa-hand-paper me-1"></i> NHẬN LỊCH NÀY</button>
                        </form>
                    </div>

                    <div id="action_da_xac_nhan" style="display:none;" class="row g-2">
                        <div class="col-12">
                            <form id="frmHoanThanh" method="POST">
                                @csrf @method('PATCH')
                                <input type="text" name="ghi_chu_sale" class="form-control mb-2"
                                    placeholder="Ghi chú sau khi xem (Khách chê/ưng...)" required>
                                <button type="submit" class="btn btn-success w-100"><i
                                        class="fas fa-flag-checkered me-1"></i> Báo Cáo Hoàn Thành</button>
                            </form>
                        </div>
                    </div>

                    <div id="action_huy" style="display:none;" class="mt-3 border-top pt-3">
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-outline-warning w-50 fw-bold text-dark"
                                id="btnShowDoiGio">Khách dời lịch</button>
                            <button type="button" class="btn btn-outline-danger w-50 fw-bold" id="btnShowHuy">Khách
                                hủy xem</button>
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
                                placeholder="VD: Khách mua chỗ khác rồi, Khách bận không đi nữa..." required>
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
            var calendarEl = document.getElementById('calendar');
            const eventModalEl = document.getElementById('eventModal');
            const bsModalInstance = (window.bootstrap && typeof window.bootstrap.Modal === 'function') ?
                window.bootstrap.Modal.getOrCreateInstance(eventModalEl) : null;

            // Ho tro ca Bootstrap 5 (bootstrap.Modal) va truong hop chi co jQuery modal.
            const showEventModal = () => {
                if (bsModalInstance) {
                    if (eventModalEl.classList.contains('show')) {
                        bsModalInstance.hide();
                    }
                    setTimeout(() => bsModalInstance.show(), 30);
                    return;
                }

                if (window.$ && typeof window.$(eventModalEl).modal === 'function') {
                    window.$(eventModalEl).modal('show');
                }
            };

            // Reset du lieu form sau khi dong modal, tranh giu trang thai cu giua cac lan click.
            eventModalEl.addEventListener('hidden.bs.modal', function() {
                const frmDoiGioSale = document.getElementById('frmDoiGioSale');
                const frmHuy = document.getElementById('frmHuy');
                const divGhiChu = document.getElementById('div_ghi_chu');

                if (frmDoiGioSale) frmDoiGioSale.style.display = 'none';
                if (frmHuy) frmHuy.style.display = 'none';
                if (divGhiChu) divGhiChu.style.display = 'none';
            });

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'timeGridWeek', // Hiển thị theo tuần
                locale: 'vi',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                slotMinTime: "07:00:00", // Giờ bắt đầu làm việc
                slotMaxTime: "21:00:00", // Giờ kết thúc
                events: "{{ route('nhanvien.admin.lich-hen.api-events') }}",

                eventClick: function(info) {
                    if (info.jsEvent) {
                        info.jsEvent.preventDefault();
                        info.jsEvent.stopPropagation();
                    }

                    let props = info.event.extendedProps || {};
                    let currentUserId = {{ $nhanVien->id }};

                    // Đổ dữ liệu vào Modal
                    document.getElementById('m_khach').textContent = props.ten_khach || 'Khong co ten';
                    document.getElementById('m_sdt').textContent = props.sdt_khach || 'Khong co SDT';
                    document.getElementById('m_sdt').href = props.sdt_khach ? ("tel:" + props
                        .sdt_khach) : '#';
                    document.getElementById('m_bds').textContent = props.bds ||
                        'Nha le / Chua xac dinh';

                    // Format time
                    let d = info.event.start;
                    if (d instanceof Date && !isNaN(d)) {
                        document.getElementById('m_time').textContent = d.getHours() + ':' + (d
                                .getMinutes() < 10 ? '0' : '') + d.getMinutes() + ' ' + d.getDate() +
                            '/' +
                            (d
                                .getMonth() + 1) + '/' + d.getFullYear();
                    } else {
                        document.getElementById('m_time').textContent = 'Khong xac dinh thoi gian';
                    }

                    // Trạng thái
                    let statusMap = {
                        'moi_dat': 'Mới đặt',
                        'cho_xac_nhan': 'Chờ Nguồn xác nhận',
                        'da_xac_nhan': 'Đã chốt giờ đi',
                        'hoan_thanh': 'Đã hoàn thành',
                        'tu_choi': 'Bị từ chối',
                        'huy': 'Đã hủy'
                    };
                    document.getElementById('m_status').textContent = statusMap[props.trang_thai] ||
                        props
                        .trang_thai || 'Khong xac dinh';

                    // Xử lý nút hiển thị
                    document.getElementById('action_moi_dat').style.display = 'none';
                    document.getElementById('action_da_xac_nhan').style.display = 'none';
                    document.getElementById('action_huy').style.display = 'none';
                    document.getElementById('div_ghi_chu').style.display = 'none';
                    document.getElementById('frmHuy').style.display = 'none';
                    document.getElementById('frmDoiGioSale').style.display = 'none';

                    if (props.ly_do_huy) {
                        document.getElementById('div_ghi_chu').style.display = 'block';
                        document.getElementById('div_ghi_chu').textContent = "Lý do/Ghi chú: " + props
                            .ly_do_huy;
                    }

                    // Logic hiện nút
                    if (props.trang_thai === 'moi_dat') {
                        document.getElementById('action_moi_dat').style.display = 'block';
                        document.getElementById('frmTiepNhan').action = '/nhan-vien/admin/lich-hen/' +
                            info.event.id + '/tiep-nhan';
                    } else if (props.trang_thai === 'da_xac_nhan' && props.sale_id == currentUserId) {
                        document.getElementById('action_da_xac_nhan').style.display = 'block';
                        document.getElementById('frmHoanThanh').action = '/nhan-vien/admin/lich-hen/' +
                            info.event.id + '/hoan-thanh';
                    }

                    if (props.trang_thai !== 'hoan_thanh' && props.trang_thai !== 'huy' && props
                        .trang_thai !== 'tu_choi' && props.sale_id == currentUserId) {
                        document.getElementById('action_huy').style.display = 'block';
                        document.getElementById('frmHuy').style.display = 'none';
                        document.getElementById('frmDoiGioSale').style.display = 'none';
                        document.getElementById('frmHuy').action = '/nhan-vien/admin/lich-hen/' + info
                            .event.id + '/huy';
                        document.getElementById('frmDoiGioSale').action = '/nhan-vien/admin/lich-hen/' +
                            info.event.id + '/sale-doi-gio';

                        document.getElementById('btnShowDoiGio').onclick = function() {
                            document.getElementById('frmDoiGioSale').style.display = 'block';
                            document.getElementById('frmHuy').style.display = 'none';
                        };
                        document.getElementById('btnShowHuy').onclick = function() {
                            document.getElementById('frmHuy').style.display = 'block';
                            document.getElementById('frmDoiGioSale').style.display = 'none';
                        };
                    }

                    showEventModal();
                }
            });
            calendar.render();
        });
    </script>
@endpush
