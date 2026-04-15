@extends('admin.layouts.master')
@section('title', 'Chi tiết lịch hẹn #' . $lichHen->id)
@section('page_title', 'Chi tiết Lịch hẹn')

@push('styles')
    <style>
        /* Modern Timeline CSS */
        .timeline {
            position: relative;
            padding-left: 2.5rem;
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 1rem;
            top: 0;
            bottom: 0;
            width: 3px;
            background: #e9ecef;
            border-radius: 3px;
        }

        .tl-step {
            position: relative;
            margin-bottom: 2rem;
        }

        .tl-step:last-child {
            margin-bottom: 0;
        }

        .tl-dot {
            position: absolute;
            left: -2.6rem;
            top: -0.1rem;
            width: 1.5rem;
            height: 1.5rem;
            border-radius: 50%;
            border: 3px solid #fff;
            background: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.65rem;
            color: #fff;
            z-index: 1;
            box-shadow: 0 0 0 1px #dee2e6;
        }

        .tl-dot.done {
            background: #198754;
            box-shadow: 0 0 0 1px #198754;
        }

        .tl-dot.pend {
            background: #0dcaf0;
            box-shadow: 0 0 0 1px #0dcaf0;
        }

        .tl-dot.fail {
            background: #dc3545;
            box-shadow: 0 0 0 1px #dc3545;
        }

        .tl-dot.wait {
            background: #ffc107;
            box-shadow: 0 0 0 1px #ffc107;
            color: #000;
        }

        .tl-label {
            font-size: 0.95rem;
            font-weight: 700;
            color: #212529;
            margin-bottom: 0.15rem;
        }

        .tl-sub {
            font-size: 0.8rem;
            color: #6c757d;
        }

        /* Info Box CSS */
        .info-row {
            display: flex;
            gap: 1rem;
            margin-bottom: 0.8rem;
            align-items: start;
        }

        .info-lbl {
            min-width: 120px;
            color: #6c757d;
            font-weight: 600;
            font-size: 0.85rem;
            padding-top: 0.15rem;
        }

        .info-val {
            flex: 1;
            font-weight: 500;
            font-size: 0.9rem;
            color: #212529;
        }

        .card-header-custom {
            background: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            font-weight: 700;
            font-size: 0.95rem;
            color: #495057;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid py-2">
        <div class="row justify-content-center">
            <div class="col-lg-10">

                {{-- HEADER: BẢNG TRẠNG THÁI --}}
                <div class="card border-0 shadow-sm border-top border-4 border-primary mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div>
                                <h4 class="fw-bold mb-1 text-primary">
                                    <i class="fas fa-calendar-alt me-2"></i>Lịch hẹn
                                    #{{ str_pad($lichHen->id, 5, '0', STR_PAD_LEFT) }}
                                </h4>
                                <div class="text-muted fw-semibold">
                                    <i class="far fa-clock me-1 text-danger"></i> Thời gian: <span
                                        class="text-danger">{{ $lichHen->thoi_gian_hen->format('H:i — d/m/Y') }}</span>
                                </div>
                            </div>
                            <div>
                                @php
                                    $badgeMap = [
                                        'moi_dat' => ['warning text-dark', 'Mới đặt (Chờ Sale nhận)', 'fas fa-inbox'],
                                        'cho_xac_nhan' => ['info text-dark', 'Chờ Nguồn chốt', 'fas fa-hourglass-half'],
                                        'da_xac_nhan' => ['success', 'Đã xác nhận (Sẵn sàng)', 'fas fa-check-circle'],
                                        'hoan_thanh' => ['secondary', 'Hoàn thành', 'fas fa-flag-checkered'],
                                        'tu_choi' => ['danger', 'Bị từ chối', 'fas fa-times-circle'],
                                        'huy' => ['danger', 'Đã hủy', 'fas fa-ban'],
                                    ];
                                    [$badgeCls, $badgeLbl, $badgeIcon] = $badgeMap[$lichHen->trang_thai] ?? [
                                        'dark',
                                        'Không xác định',
                                        'fas fa-question',
                                    ];
                                @endphp
                                <span class="badge bg-{{ $badgeCls }} fs-5 px-3 py-2 shadow-sm"><i
                                        class="{{ $badgeIcon }} me-2"></i>{{ $badgeLbl }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    {{-- CỘT TRÁI: THÔNG TIN CHI TIẾT --}}
                    <div class="col-md-7">

                        {{-- 1. Khách Hàng --}}
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header card-header-custom py-3"><i
                                    class="fas fa-user-circle text-primary me-2"></i>Thông tin Khách Hàng</div>
                            <div class="card-body p-4">
                                <div class="info-row">
                                    <div class="info-lbl">Họ và tên:</div>
                                    <div class="info-val fs-6">{{ $lichHen->ten_khach_hang }}</div>
                                </div>
                                <div class="info-row">
                                    <div class="info-lbl">Điện thoại:</div>
                                    <div class="info-val">
                                        <a href="tel:{{ $lichHen->sdt_khach_hang }}"
                                            class="text-success text-decoration-none fw-bold"><i
                                                class="fas fa-phone-alt me-1"></i>{{ $lichHen->sdt_khach_hang }}</a>
                                    </div>
                                </div>
                                @if ($lichHen->email_khach_hang)
                                    <div class="info-row">
                                        <div class="info-lbl">Email:</div>
                                        <div class="info-val"><a
                                                href="mailto:{{ $lichHen->email_khach_hang }}">{{ $lichHen->email_khach_hang }}</a>
                                        </div>
                                    </div>
                                @endif
                                <div class="info-row">
                                    <div class="info-lbl">Loại khách:</div>
                                    <div class="info-val">
                                        @if ($lichHen->khachHang)
                                            <a href="{{ route('nhanvien.admin.khach-hang.show', $lichHen->khachHang) }}"
                                                class="badge bg-primary text-decoration-none"><i
                                                    class="fas fa-id-card me-1"></i>Khách hàng Hệ thống</a>
                                        @else
                                            <span class="badge bg-secondary"><i class="fas fa-walking me-1"></i>Khách vãng
                                                lai</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- 2. Bất động sản --}}
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header card-header-custom py-3 border-success"><i
                                    class="fas fa-building text-success me-2"></i>Chi tiết Bất Động Sản</div>
                            <div class="card-body p-4 bg-success bg-opacity-10">
                                @if ($lichHen->batDongSan)
                                    <div class="info-row">
                                        <div class="info-lbl">Căn nhà/Dự án:</div>
                                        <div class="info-val fw-bold text-success fs-6">{{ $lichHen->batDongSan->tieu_de }}
                                        </div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-lbl">Mã căn:</div>
                                        <div class="info-val">
                                            {{ $lichHen->batDongSan->ma_bat_dong_san ?? $lichHen->batDongSan->ma_can }}
                                        </div>
                                    </div>
                                    @if ($lichHen->batDongSan->khuVuc)
                                        <div class="info-row">
                                            <div class="info-lbl">Khu vực:</div>
                                            <div class="info-val"><i
                                                    class="fas fa-map-marker-alt text-danger me-1"></i>{{ $lichHen->batDongSan->khuVuc->ten_khu_vuc }}
                                            </div>
                                        </div>
                                    @endif
                                @else
                                    <div class="alert alert-warning m-0">Bất động sản ngoài hệ thống hoặc đã bị xóa.</div>
                                @endif

                                @if ($lichHen->dia_diem_hen)
                                    <hr class="border-success opacity-25">
                                    <div class="info-row">
                                        <div class="info-lbl text-dark">Điểm gặp mặt:</div>
                                        <div class="info-val text-dark fw-bold"><i
                                                class="fas fa-street-view me-1"></i>{{ $lichHen->dia_diem_hen }}</div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- 3. Phụ trách & Ghi chú --}}
                        <div class="card border-0 shadow-sm">
                            <div class="card-header card-header-custom py-3 border-warning"><i
                                    class="fas fa-users-cog text-warning me-2"></i>Nhân sự Phụ trách</div>
                            <div class="card-body p-4">
                                <div class="info-row">
                                    <div class="info-lbl">NV Sale (Chăm sóc):</div>
                                    <div class="info-val fw-bold text-primary">
                                        {{ optional($lichHen->nhanVienSale)->ho_ten ?? '— Chưa có —' }}</div>
                                </div>
                                <div class="info-row">
                                    <div class="info-lbl">NV Nguồn (Mở cửa):</div>
                                    <div class="info-val fw-bold text-info-emphasis">
                                        {{ optional($lichHen->nhanVienNguonHang)->ho_ten ?? '— Chưa gán —' }}</div>
                                </div>
                                <hr>
                                <div class="info-row flex-column">
                                    <div class="info-lbl w-100 mb-1">Ghi chú từ Sale:</div>
                                    <div class="info-val w-100 bg-light p-3 rounded border" style="font-size: 0.85rem">
                                        {!! $lichHen->ghi_chu_sale ? nl2br(e($lichHen->ghi_chu_sale)) : '<i class="text-muted">Không có ghi chú.</i>' !!}
                                    </div>
                                </div>
                                <div class="info-row flex-column">
                                    <div class="info-lbl w-100 mb-1">Phản hồi từ Nguồn:</div>
                                    <div class="info-val w-100 bg-warning bg-opacity-10 p-3 rounded border border-warning"
                                        style="font-size: 0.85rem">
                                        {!! $lichHen->ghi_chu_nguon_hang
                                            ? nl2br(e($lichHen->ghi_chu_nguon_hang))
                                            : '<i class="text-muted">Chưa có phản hồi.</i>' !!}
                                    </div>
                                </div>
                                @if ($lichHen->ly_do_tu_choi)
                                    <div class="info-row flex-column mt-3">
                                        <div class="info-lbl w-100 text-danger mb-1"><i
                                                class="fas fa-exclamation-circle me-1"></i>Lý do Hủy / Từ chối:</div>
                                        <div
                                            class="info-val w-100 bg-danger bg-opacity-10 text-danger p-3 rounded border border-danger">
                                            {{ $lichHen->ly_do_tu_choi }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- CỘT PHẢI: TIMELINE & THAO TÁC --}}
                    <div class="col-md-5 d-flex flex-column gap-4">

                        {{-- Thao tác Buttons --}}
                        <div class="card border-0 shadow-sm border-top border-4 border-dark">
                            <div class="card-header card-header-custom py-3"><i class="fas fa-magic text-dark me-2"></i>Thao
                                tác xử lý</div>
                            <div class="card-body p-4 d-flex flex-column gap-3">

                                @if ($lichHen->trang_thai === 'cho_xac_nhan' && $nhanVien->hasRole(['admin', 'nguon_hang']))
                                    <form action="{{ route('nhanvien.admin.lich-hen.xac-nhan', $lichHen) }}" method="POST"
                                        class="m-0">
                                        @csrf @method('PATCH')
                                        <button class="btn btn-success btn-lg w-100 fw-bold shadow-sm"
                                            onclick="return confirm('Bạn đã liên hệ Chủ nhà và chắc chắn XÁC NHẬN lịch này?')">
                                            <i class="fas fa-check-circle me-2"></i>Xác nhận lịch hẹn
                                        </button>
                                    </form>
                                    <button class="btn btn-outline-danger w-100 fw-bold" onclick="openTuChoiModal()">
                                        <i class="fas fa-times-circle me-2"></i>Từ chối lịch
                                    </button>
                                @endif

                                @if ($lichHen->trang_thai === 'da_xac_nhan' && $nhanVien->hasRole(['admin', 'sale']))
                                    <form action="{{ route('nhanvien.admin.lich-hen.hoan-thanh', $lichHen) }}"
                                        method="POST" class="m-0">
                                        @csrf @method('PATCH')
                                        <button class="btn btn-secondary btn-lg w-100 fw-bold shadow-sm"
                                            onclick="return confirm('Đánh dấu là Đã đưa khách đi xem xong?')">
                                            <i class="fas fa-flag-checkered me-2"></i>Đánh dấu Hoàn thành
                                        </button>
                                    </form>
                                @endif

                                @if (
                                    !in_array($lichHen->trang_thai, ['hoan_thanh', 'huy', 'tu_choi']) &&
                                        $nhanVien->hasRole(['admin', 'sale', 'nguon_hang']))
                                    <button class="btn btn-outline-danger w-100 fw-bold" onclick="openHuyModal()">
                                        <i class="fas fa-ban me-2"></i>Báo Hủy đột xuất
                                    </button>
                                @endif
                                @if ($nhanVien->vai_tro == 'admin' || $nhanVien->hasRole('admin'))
                                    <form action="{{ route('nhanvien.admin.lich-hen.destroy', $lichHen) }}"
                                        method="POST" class="m-0">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger w-100 fw-bold shadow-sm"
                                            onclick="return confirm('Xóa VĨNH VIỄN lịch hẹn này? Hành động này không thể khôi phục!')">
                                            <i class="fas fa-trash-alt me-2"></i>Xóa Vĩnh Viễn Lịch
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('nhanvien.admin.lich-hen.index') }}"
                                    class="btn btn-light border w-100 mt-2">
                                    <i class="fas fa-arrow-left me-2"></i>Quay lại danh sách
                                </a>
                            </div>
                        </div>

                        {{-- Timeline Trạng thái --}}
                        <div class="card border-0 shadow-sm flex-fill">
                            <div class="card-header card-header-custom py-3"><i
                                    class="fas fa-stream text-info me-2"></i>Tiến trình</div>
                            <div class="card-body p-4">
                                <div class="timeline">

                                    <div class="tl-step">
                                        <div class="tl-dot done"><i class="fas fa-plus"></i></div>
                                        <div class="tl-label">Khách đặt lịch / Lên lịch</div>
                                        <div class="tl-sub">{{ $lichHen->created_at->format('H:i d/m/Y') }}</div>
                                    </div>

                                    @if (in_array($lichHen->trang_thai, ['cho_xac_nhan', 'da_xac_nhan', 'hoan_thanh']))
                                        <div class="tl-step">
                                            <div class="tl-dot done"><i class="fas fa-user-check"></i></div>
                                            <div class="tl-label">Sale tiếp nhận</div>
                                            <div class="tl-sub text-success">Đã gán cho Nguồn hàng</div>
                                        </div>
                                    @elseif($lichHen->trang_thai === 'moi_dat')
                                        <div class="tl-step">
                                            <div class="tl-dot wait"><i class="fas fa-hourglass-half"></i></div>
                                            <div class="tl-label text-warning">Đang chờ Sale nhận</div>
                                            <div class="tl-sub">Chưa gán Nguồn hàng</div>
                                        </div>
                                    @endif

                                    @if (in_array($lichHen->trang_thai, ['da_xac_nhan', 'hoan_thanh']))
                                        <div class="tl-step">
                                            <div class="tl-dot done"><i class="fas fa-check-double"></i></div>
                                            <div class="tl-label">Nguồn hàng xác nhận</div>
                                            <div class="tl-sub text-success">
                                                {{ optional($lichHen->xac_nhan_at)->format('H:i d/m/Y') }}</div>
                                        </div>
                                    @elseif($lichHen->trang_thai === 'cho_xac_nhan')
                                        <div class="tl-step">
                                            <div class="tl-dot pend"><i class="fas fa-spinner fa-spin"></i></div>
                                            <div class="tl-label text-info">Chờ Nguồn chốt với Chủ</div>
                                            <div class="tl-sub">Đang liên hệ chủ nhà</div>
                                        </div>
                                    @elseif($lichHen->trang_thai === 'tu_choi')
                                        <div class="tl-step">
                                            <div class="tl-dot fail"><i class="fas fa-times"></i></div>
                                            <div class="tl-label text-danger">Nguồn hàng từ chối</div>
                                            <div class="tl-sub">{{ optional($lichHen->tu_choi_at)->format('H:i d/m/Y') }}
                                            </div>
                                        </div>
                                    @elseif($lichHen->trang_thai === 'huy')
                                        <div class="tl-step">
                                            <div class="tl-dot fail"><i class="fas fa-ban"></i></div>
                                            <div class="tl-label text-danger">Lịch bị hủy</div>
                                            <div class="tl-sub">{{ optional($lichHen->huy_at)->format('H:i d/m/Y') }}
                                            </div>
                                        </div>
                                    @endif

                                    @if ($lichHen->trang_thai === 'hoan_thanh')
                                        <div class="tl-step">
                                            <div class="tl-dot done"><i class="fas fa-flag-checkered"></i></div>
                                            <div class="tl-label">Đã dẫn khách xem xong</div>
                                            <div class="tl-sub text-success">
                                                {{ optional($lichHen->hoan_thanh_at)->format('H:i d/m/Y') }}</div>
                                        </div>
                                    @elseif($lichHen->trang_thai === 'da_xac_nhan')
                                        <div class="tl-step">
                                            <div class="tl-dot wait"><i class="fas fa-walking"></i></div>
                                            <div class="tl-label text-warning">Chờ đến giờ đi xem</div>
                                            <div class="tl-sub">{{ $lichHen->thoi_gian_hen->format('H:i d/m/Y') }}</div>
                                        </div>
                                    @endif

                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Modal Từ chối --}}
    <div class="modal fade" id="modalTuChoiShow" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route('nhanvien.admin.lich-hen.tu-choi', $lichHen) }}" method="POST"
                class="modal-content border-0 shadow">
                @csrf @method('PATCH')
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title fw-bold"><i class="fas fa-times-circle me-2"></i>Từ chối lịch hẹn</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-danger bg-danger bg-opacity-10 border-danger small mb-3">
                        Nếu chủ nhà không đồng ý hoặc nhà đã bán, vui lòng ghi rõ lý do để Sale báo lại khách.
                    </div>
                    <label class="form-label fw-bold">Lý do từ chối <span class="text-danger">*</span></label>
                    <textarea name="ly_do_tu_choi" class="form-control" rows="3" required
                        placeholder="Ví dụ: Chủ nhà báo đã nhận cọc người khác..."></textarea>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-danger fw-bold"><i class="fas fa-check me-1"></i> Xác nhận Từ
                        chối</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Hủy đột xuất --}}
    <div class="modal fade" id="modalHuyShow" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route('nhanvien.admin.lich-hen.huy', $lichHen) }}" method="POST"
                class="modal-content border-0 shadow">
                @csrf @method('PATCH')
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title fw-bold"><i class="fas fa-ban me-2"></i>Báo Hủy Lịch</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <label class="form-label fw-bold">Lý do Hủy <span class="text-danger">*</span></label>
                    <textarea name="ly_do" class="form-control" rows="3" required
                        placeholder="Ví dụ: Khách bận đột xuất không đi xem được..."></textarea>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-danger fw-bold"><i class="fas fa-trash-alt me-1"></i> Xác nhận
                        Hủy</button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        function openTuChoiModal() {
            new bootstrap.Modal(document.getElementById('modalTuChoiShow')).show();
        }

        function openHuyModal() {
            new bootstrap.Modal(document.getElementById('modalHuyShow')).show();
        }
    </script>
@endpush
