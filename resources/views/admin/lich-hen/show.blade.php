@extends('admin.layouts.master')
@section('title', 'Chi tiết lịch hẹn #' . $lichHen->id)

@push('styles')
    <style>
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

        .blur-text {
            filter: blur(4px);
            user-select: none;
        }
    </style>
@endpush

@section('content')
    @php
        // LOGIC PHÂN QUYỀN HIỂN THỊ THÔNG TIN NHẠY CẢM
        $role = $nhanVien->vai_tro;
        $isAdmin = $role === 'admin';
        $isHoanThanh = $lichHen->trang_thai === 'hoan_thanh';

        // Nguồn KHÔNG thấy Khách hàng (Trừ khi Admin, hoặc lịch đã Hoàn Thành)
        $canViewKhachHang = $isAdmin || $role === 'sale' || $isHoanThanh;

        // Sale KHÔNG thấy Chủ nhà (Trừ khi Admin, hoặc lịch đã Hoàn Thành)
        $canViewChuNha = $isAdmin || $role === 'nguon_hang' || $isHoanThanh;
    @endphp

    <div class="container-fluid py-2">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                {{-- HEADER: TỔNG QUAN LỊCH HẸN --}}
                <div class="card border-0 shadow-sm border-top border-4 border-primary mb-4">
                    <div class="card-body p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div>
                            <h4 class="fw-bold mb-1 text-primary"><i class="fas fa-calendar-alt me-2"></i>Lịch hẹn
                                #{{ $lichHen->id }}</h4>
                            <div class="text-muted fw-semibold">
                                <i class="far fa-clock me-1 text-danger"></i> Thời gian chốt:
                                <span class="text-danger">{{ $lichHen->thoi_gian_hen->format('H:i — d/m/Y') }}</span>
                            </div>
                        </div>
                        <div>
                            @php
                                $badgeClass = match ($lichHen->trang_thai) {
                                    'hoan_thanh', 'da_xac_nhan' => 'success',
                                    'cho_xac_nhan', 'moi_dat', 'sale_da_nhan' => 'warning text-dark',
                                    'tu_choi', 'huy' => 'danger',
                                    default => 'secondary',
                                };
                            @endphp
                            <span class="badge bg-{{ $badgeClass }} fs-5 px-3 py-2 shadow-sm text-uppercase">
                                {{ str_replace('_', ' ', $lichHen->trang_thai) }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    {{-- CỘT TRÁI: DỮ LIỆU CHI TIẾT --}}
                    <div class="col-md-7">

                        {{-- 1. CARD KHÁCH HÀNG --}}
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header card-header-custom py-3"><i
                                    class="fas fa-user-circle text-primary me-2"></i>Thông tin Khách Hàng (Người đi xem)
                            </div>
                            <div class="card-body p-4">
                                @if ($canViewKhachHang)
                                    <div class="info-row">
                                        <div class="info-lbl">Họ và tên:</div>
                                        <div class="info-val fs-6">{{ $lichHen->ten_khach_hang }}</div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-lbl">Điện thoại:</div>
                                        <div class="info-val"><a href="tel:{{ $lichHen->sdt_khach_hang }}"
                                                class="text-success text-decoration-none fw-bold"><i
                                                    class="fas fa-phone-alt me-1"></i>{{ $lichHen->sdt_khach_hang }}</a>
                                        </div>
                                    </div>
                                    @if ($lichHen->email_khach_hang)
                                        <div class="info-row">
                                            <div class="info-lbl">Email:</div>
                                            <div class="info-val">{{ $lichHen->email_khach_hang }}</div>
                                        </div>
                                    @endif
                                @else
                                    <div class="alert alert-warning mb-0 d-flex align-items-center">
                                        <i class="fas fa-user-lock fa-2x me-3"></i>
                                        <div>
                                            <strong>Bảo mật thông tin khách hàng</strong><br>
                                            Chỉ bộ phận Sale quản lý khách mới được xem. Nguồn hàng sẽ thấy thông tin này
                                            khi lịch chuyển sang trạng thái <strong>Hoàn thành</strong>.
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- 2. CARD BẤT ĐỘNG SẢN & CHỦ NHÀ --}}
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header card-header-custom py-3 border-success"><i
                                    class="fas fa-building text-success me-2"></i>Thông tin Bất Động Sản & Chủ Nhà</div>
                            <div class="card-body p-4 bg-success bg-opacity-10">

                                {{-- BĐS hiển thị chung --}}
                                @if ($lichHen->batDongSan)
                                    <div class="info-row">
                                        <div class="info-lbl">Tên nhà/Dự án:</div>
                                        <div class="info-val fw-bold text-success fs-6">{{ $lichHen->batDongSan->tieu_de }}
                                        </div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-lbl">Mã căn:</div>
                                        <div class="info-val">{{ $lichHen->batDongSan->ma_bat_dong_san ?? '---' }}</div>
                                    </div>
                                @endif
                                @if ($lichHen->dia_diem_hen)
                                    <div class="info-row">
                                        <div class="info-lbl text-dark">Điểm gặp nhau:</div>
                                        <div class="info-val text-dark fw-bold"><i
                                                class="fas fa-street-view me-1"></i>{{ $lichHen->dia_diem_hen }}</div>
                                    </div>
                                @endif

                                <hr class="border-success opacity-25">

                                {{-- CHỦ NHÀ (Có điều kiện) --}}
                                <h6 class="fw-bold text-success mb-3"><i class="fas fa-key me-1"></i> Thông tin Chủ Nhà
                                    (Người mở cửa)</h6>
                                @if ($canViewChuNha)
                                    @if ($lichHen->batDongSan && $lichHen->batDongSan->chuNha)
                                        <div class="info-row">
                                            <div class="info-lbl">Chủ nhà:</div>
                                            <div class="info-val fw-bold">{{ $lichHen->batDongSan->chuNha->ho_ten }}</div>
                                        </div>
                                        <div class="info-row">
                                            <div class="info-lbl">Điện thoại:</div>
                                            <div class="info-val"><a
                                                    href="tel:{{ $lichHen->batDongSan->chuNha->so_dien_thoai }}"
                                                    class="text-success text-decoration-none fw-bold"><i
                                                        class="fas fa-phone-alt me-1"></i>{{ $lichHen->batDongSan->chuNha->so_dien_thoai }}</a>
                                            </div>
                                        </div>
                                    @else
                                        <div class="text-danger small"><i class="fas fa-exclamation-triangle me-1"></i> Chưa
                                            có dữ liệu chủ nhà trong hệ thống.</div>
                                    @endif
                                @else
                                    <div class="alert alert-warning mb-0 d-flex align-items-center">
                                        <i class="fas fa-lock fa-2x me-3"></i>
                                        <div>
                                            <strong>Bảo mật Nguồn hàng</strong><br>
                                            Sale sẽ được cung cấp SĐT chủ nhà sau khi lịch hẹn này <strong>Hoàn
                                                thành</strong> xem nhà thực tế.
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- 3. CARD NHÂN SỰ & LỊCH SỬ GHI CHÚ --}}
                        <div class="card border-0 shadow-sm">
                            <div class="card-header card-header-custom py-3 border-warning"><i
                                    class="fas fa-users-cog text-warning me-2"></i>Nhân sự Phụ trách & Chi tiết Dời lịch
                            </div>
                            <div class="card-body p-4">
                                <div class="info-row">
                                    <div class="info-lbl">Sale phụ trách:</div>
                                    <div class="info-val fw-bold text-primary">
                                        {{ optional($lichHen->nhanVienSale)->ho_ten ?? '— Chưa có —' }}</div>
                                </div>
                                <div class="info-row">
                                    <div class="info-lbl">Nguồn mở cửa:</div>
                                    <div class="info-val fw-bold text-info-emphasis">
                                        {{ optional($lichHen->nhanVienNguonHang)->ho_ten ?? '— Chưa gán —' }}</div>
                                </div>

                                <hr class="border-secondary opacity-25">
                                <h6 class="fw-bold mb-3">Lịch sử Ghi chú / Dời lịch</h6>

                                <div class="bg-light p-3 rounded border mb-3">
                                    <strong class="text-primary"><i class="fas fa-comment-dots me-1"></i>Sale ghi
                                        chú:</strong>
                                    <div class="small mt-1 text-muted">
                                        {!! $lichHen->ghi_chu_sale ? nl2br(e($lichHen->ghi_chu_sale)) : '<i>(Không có)</i>' !!}
                                    </div>
                                </div>

                                <div class="bg-warning bg-opacity-10 p-3 rounded border border-warning">
                                    <strong class="text-warning-emphasis"><i class="fas fa-comment-dots me-1"></i>Nguồn phản
                                        hồi:</strong>
                                    <div class="small mt-1 text-muted">
                                        {!! $lichHen->ghi_chu_nguon_hang ? nl2br(e($lichHen->ghi_chu_nguon_hang)) : '<i>(Không có)</i>' !!}
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- CỘT PHẢI: THAO TÁC & TIMELINE --}}
                    <div class="col-md-5 d-flex flex-column gap-4">

                        {{-- MODULE THAO TÁC NHANH --}}
                        <div class="card border-0 shadow-sm border-top border-4 border-dark">
                            <div class="card-header card-header-custom py-3"><i class="fas fa-magic text-dark me-2"></i>Bảng
                                Điều khiển Xử lý</div>
                            <div class="card-body p-4 d-flex flex-column gap-3">

                                {{-- SALE ĐANG XÁC NHẬN -> CHUYỂN NGUỒN --}}
                                @if ($lichHen->trang_thai === 'sale_da_nhan' && $nhanVien->hasRole(['admin', 'sale']))
                                    <button class="btn btn-primary w-100 fw-bold py-2"
                                        onclick="new bootstrap.Modal(document.getElementById('modalChuyenNguon')).show();">
                                        <i class="fas fa-paper-plane me-2"></i>Chuyển Nguồn Mở Cửa
                                    </button>
                                    <button class="btn btn-outline-danger w-100 fw-bold"
                                        onclick="new bootstrap.Modal(document.getElementById('modalSaleTuChoi')).show();">
                                        Từ chối (Báo Khách ảo)
                                    </button>
                                @endif

                                {{-- NGUỒN CHỜ XÁC NHẬN CHỦ NHÀ --}}
                                @if ($lichHen->trang_thai === 'cho_xac_nhan' && $nhanVien->hasRole(['admin', 'nguon_hang']))
                                    <form action="{{ route('nhanvien.admin.lich-hen.xac-nhan', $lichHen) }}" method="POST"
                                        class="m-0">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-success w-100 fw-bold py-2"
                                            onclick="return confirm('Xác nhận đã có chìa khóa/chủ nhà đã OK?')">
                                            <i class="fas fa-check-circle me-2"></i>Nguồn: Xác nhận có chìa
                                        </button>
                                    </form>
                                @endif

                                {{-- SALE BÁO KẾT QUẢ CHỐT --}}
                                @if ($lichHen->trang_thai === 'da_xac_nhan' && $nhanVien->hasRole(['admin', 'sale']))
                                    <button class="btn btn-secondary btn-lg w-100 fw-bold py-2 shadow-sm"
                                        onclick="new bootstrap.Modal(document.getElementById('modalHoanThanh')).show();">
                                        <i class="fas fa-flag-checkered me-2"></i>Cập nhật Kết quả (Chốt / Rớt)
                                    </button>
                                @endif

                                {{-- HỦY CHUNG LÚC CHƯA HOÀN THÀNH --}}
                                @if (
                                    !in_array($lichHen->trang_thai, ['hoan_thanh', 'huy', 'tu_choi']) &&
                                        $nhanVien->hasRole(['admin', 'sale', 'nguon_hang']))
                                    <hr>
                                    <button class="btn btn-outline-danger w-100 fw-bold"
                                        onclick="new bootstrap.Modal(document.getElementById('modalHuyShow')).show();">
                                        <i class="fas fa-ban me-1"></i> Báo Hủy lịch đột xuất
                                    </button>
                                @endif

                                <a href="{{ route('nhanvien.admin.lich-hen.index') }}"
                                    class="btn btn-light border w-100 mt-2">Quay lại danh sách</a>
                            </div>
                        </div>

                        {{-- TIMELINE UPDATE ĐẦY ĐỦ --}}
                        <div class="card border-0 shadow-sm flex-fill">
                            <div class="card-header card-header-custom py-3"><i
                                    class="fas fa-project-diagram text-info me-2"></i>Trình tự Lịch hẹn</div>
                            <div class="card-body p-4">
                                <div class="timeline">
                                    {{-- Bước 1: --}}
                                    <div class="tl-step">
                                        <div class="tl-dot done"><i class="fas fa-plus"></i></div>
                                        <div class="tl-label">Khách gửi yêu cầu</div>
                                        <div class="tl-sub">{{ $lichHen->created_at->format('H:i d/m/Y') }}</div>
                                    </div>

                                    {{-- Bước 2: --}}
                                    @if (in_array($lichHen->trang_thai, ['sale_da_nhan', 'cho_xac_nhan', 'da_xac_nhan', 'hoan_thanh']))
                                        <div class="tl-step">
                                            <div class="tl-dot done"><i class="fas fa-headset"></i></div>
                                            <div class="tl-label">Sale tiếp nhận xử lý</div>
                                        </div>
                                    @endif

                                    {{-- Bước 3: --}}
                                    @if (in_array($lichHen->trang_thai, ['cho_xac_nhan', 'da_xac_nhan', 'hoan_thanh']))
                                        <div class="tl-step">
                                            <div class="tl-dot done"><i class="fas fa-share"></i></div>
                                            <div class="tl-label">Gửi Nguồn yêu cầu mở cửa</div>
                                        </div>
                                    @endif

                                    {{-- Bước 4: --}}
                                    @if (in_array($lichHen->trang_thai, ['da_xac_nhan', 'hoan_thanh']))
                                        <div class="tl-step">
                                            <div class="tl-dot done"><i class="fas fa-key"></i></div>
                                            <div class="tl-label">Nguồn xác nhận có chìa khóa</div>
                                            <div class="tl-sub text-success">
                                                {{ optional($lichHen->xac_nhan_at)->format('H:i d/m/Y') }}</div>
                                        </div>
                                    @endif

                                    {{-- Bước 5: HOÀN THÀNH --}}
                                    @if ($lichHen->trang_thai === 'hoan_thanh')
                                        <div class="tl-step">
                                            <div class="tl-dot done"><i class="fas fa-flag-checkered"></i></div>
                                            <div class="tl-label text-success fw-bold">Đã hoàn thành xem nhà</div>
                                            <div class="tl-sub text-success fw-bold">Kết thúc lúc:
                                                {{ optional($lichHen->hoan_thanh_at)->format('H:i d/m/Y') }}</div>
                                        </div>

                                        {{-- HOẶC: BỊ TỪ CHỐI / HỦY --}}
                                    @elseif($lichHen->trang_thai === 'tu_choi')
                                        <div class="tl-step">
                                            <div class="tl-dot fail"><i class="fas fa-times"></i></div>
                                            <div class="tl-label text-danger fw-bold">Bị từ chối</div>
                                            <div class="tl-sub text-danger">
                                                {{ optional($lichHen->tu_choi_at)->format('H:i d/m/Y') }}</div>
                                        </div>
                                    @elseif($lichHen->trang_thai === 'huy')
                                        <div class="tl-step">
                                            <div class="tl-dot fail"><i class="fas fa-ban"></i></div>
                                            <div class="tl-label text-danger fw-bold">Đã hủy lịch</div>
                                            <div class="tl-sub text-danger">
                                                {{ optional($lichHen->huy_at)->format('H:i d/m/Y') }}</div>
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

    {{-- ========================================================
                            CÁC MODAL THAO TÁC 
         ======================================================== --}}

    {{-- MODAL CHUYỂN NGUỒN (Cho Sale) --}}
    @if ($lichHen->trang_thai === 'sale_da_nhan' && isset($dsNguonHang))
        <div class="modal fade" id="modalChuyenNguon" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <form action="{{ route('nhanvien.admin.lich-hen.tiep-nhan', $lichHen) }}" method="POST"
                    class="modal-content shadow border-0">
                    @csrf @method('PATCH')
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title fw-bold">Chuyển cho Nguồn Hàng</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Chọn Nguồn hàng phụ trách kho này:</label>
                            <select name="nhan_vien_nguon_hang_id" class="form-select form-select-lg" required>
                                <option value="">-- Chọn Nguồn --</option>
                                @foreach ($dsNguonHang as $ng)
                                    <option value="{{ $ng->id }}"
                                        {{ optional($lichHen->batDongSan)->nhan_vien_phu_trach_id == $ng->id ? 'selected' : '' }}>
                                        {{ $ng->ho_ten }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer"><button type="submit" class="btn btn-primary w-100 fw-bold">Xác nhận Gửi
                            Nguồn</button></div>
                </form>
            </div>
        </div>

        <div class="modal fade" id="modalSaleTuChoi" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <form action="{{ route('nhanvien.admin.lich-hen.sale-tu-choi', $lichHen) }}" method="POST"
                    class="modal-content shadow border-0">
                    @csrf @method('PATCH')
                    <div class="modal-body p-4">
                        <h5 class="fw-bold text-danger mb-3">Từ chối Yêu cầu</h5>
                        <label class="form-label fw-bold">Lý do từ chối (Gửi lại khách):</label>
                        <textarea name="ly_do_tu_choi" class="form-control" rows="3" required
                            placeholder="Ví dụ: Gọi điện không bắt máy..."></textarea>
                        <div class="d-flex gap-2 mt-4">
                            <button type="button" class="btn btn-secondary flex-fill"
                                data-bs-dismiss="modal">Đóng</button>
                            <button type="submit" class="btn btn-danger flex-fill fw-bold">Từ chối</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- MODAL HOÀN THÀNH - KẾT QUẢ CHỐT (Sale Điền) --}}
    <div class="modal fade" id="modalHoanThanh" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route('nhanvien.admin.lich-hen.hoan-thanh', $lichHen) }}" method="POST"
                class="modal-content shadow border-0">
                @csrf @method('PATCH')
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title fw-bold"><i class="fas fa-flag-checkered me-2"></i>Cập nhật Kết quả Xem nhà
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-4">
                        <label class="form-label fw-bold fs-6">Khách hàng quyết định như thế nào?</label>
                        <select name="ket_qua" class="form-select form-select-lg" required>
                            <option value="">-- Chọn kết quả --</option>
                            <option value="chot" class="text-success fw-bold">✅ ĐÃ CHỐT THÀNH CÔNG (Đặt cọc)</option>
                            <option value="khong_chot" class="text-danger fw-bold">❌ KHÔNG CHỐT (Chê, suy nghĩ thêm)
                            </option>
                        </select>
                        <small class="text-muted d-block mt-2"><i class="fas fa-info-circle me-1"></i>Lưu ý: Nếu chọn "Đã
                            chốt", trạng thái Bất động sản này sẽ tự động chuyển sang Đã bán trên Website.</small>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-bold">Ghi chú & Review từ khách:</label>
                        <textarea name="ghi_chu_sale" class="form-control" rows="3"
                            placeholder="Lý do không chốt hoặc thông tin đặt cọc..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="submit" class="btn btn-success btn-lg fw-bold w-100">Lưu kết quả & Đóng lịch</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Hủy (Bất ngờ) --}}
    <div class="modal fade" id="modalHuyShow" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow border-0">
                <form action="{{ route('nhanvien.admin.lich-hen.huy', $lichHen) }}" method="POST">
                    @csrf @method('PATCH')
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title fw-bold"><i class="fas fa-ban me-2"></i> Hủy lịch xem nhà</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <label class="form-label fw-bold">Nguyên nhân hủy:</label>
                        <textarea name="ly_do" class="form-control" required placeholder="Khách xe hư, trời mưa, chủ nhà đi vắng..."></textarea>
                        <button type="submit" class="btn btn-danger mt-4 w-100 fw-bold">Xác nhận Hủy lịch</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
