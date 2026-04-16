@extends('admin.layouts.master')
@section('title', 'Chi tiết lịch hẹn #' . $lichHen->id)
@section('page_title', 'Chi tiết Lịch hẹn')

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
            background: #ffc107;
            box-shadow: 0 0 0 1px #ffc107;
            color: #000;
        }

        .tl-dot.fail {
            background: #dc3545;
            box-shadow: 0 0 0 1px #dc3545;
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
    </style>
@endpush

@section('content')
    @php
        // LOGIC BẢO MẬT DỮ LIỆU
        $role = $nhanVien->vai_tro;
        $isHoanThanh = $lichHen->trang_thai === 'hoan_thanh';

        $canViewKhachHang = $role === 'admin' || $role === 'sale' || $isHoanThanh;
        $canViewChuNha = $role === 'admin' || $role === 'nguon_hang' || $isHoanThanh;
    @endphp

    <div class="container-fluid py-2">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                {{-- HEADER LỊCH HẸN --}}
                <div class="card border-0 shadow-sm border-top border-4 border-primary mb-4">
                    <div class="card-body p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div>
                            <h4 class="fw-bold mb-1 text-primary"><i class="fas fa-calendar-alt me-2"></i>Lịch hẹn
                                #{{ $lichHen->id }}</h4>
                            <div class="text-muted fw-semibold">Thời gian: <span
                                    class="text-danger">{{ $lichHen->thoi_gian_hen->format('H:i — d/m/Y') }}</span></div>
                        </div>
                        <div><span
                                class="badge bg-dark fs-5 px-3 py-2 text-uppercase">{{ str_replace('_', ' ', $lichHen->trang_thai) }}</span>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    {{-- CỘT THÔNG TIN --}}
                    <div class="col-md-7">

                        {{-- KHÁCH HÀNG --}}
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-light py-3 fw-bold"><i
                                    class="fas fa-user-circle text-primary me-2"></i>Thông tin Khách Hàng</div>
                            <div class="card-body p-4">
                                @if ($canViewKhachHang)
                                    <div class="info-row">
                                        <div class="info-lbl">Họ và tên:</div>
                                        <div class="info-val fs-6">{{ $lichHen->ten_khach_hang }}</div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-lbl">Điện thoại:</div>
                                        <div class="info-val"><a href="tel:{{ $lichHen->sdt_khach_hang }}"
                                                class="text-success fw-bold"><i
                                                    class="fas fa-phone-alt me-1"></i>{{ $lichHen->sdt_khach_hang }}</a>
                                        </div>
                                    </div>
                                @else
                                    <div class="alert alert-warning mb-0"><i class="fas fa-user-lock me-2"></i>Thông tin
                                        Khách hàng được bảo mật và chỉ hiển thị với Nguồn sau khi hoàn tất xem nhà.</div>
                                @endif
                            </div>
                        </div>

                        {{-- BẤT ĐỘNG SẢN & CHỦ NHÀ --}}
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-light py-3 fw-bold"><i
                                    class="fas fa-building text-success me-2"></i>Bất Động Sản & Chủ Nhà</div>
                            <div class="card-body p-4 bg-success bg-opacity-10">
                                <div class="info-row">
                                    <div class="info-lbl">Tên nhà/Dự án:</div>
                                    <div class="info-val fw-bold text-success fs-6">
                                        {{ optional($lichHen->batDongSan)->tieu_de }}</div>
                                </div>
                                <hr class="border-success opacity-25">
                                @if ($canViewChuNha)
                                    <div class="info-row">
                                        <div class="info-lbl">Chủ nhà:</div>
                                        <div class="info-val fw-bold">
                                            {{ optional($lichHen->batDongSan?->chuNha)->ho_ten ?? 'Chưa rõ' }}</div>
                                    </div>
                                    @if ($lichHen->batDongSan?->chuNha)
                                        <div class="info-row">
                                            <div class="info-lbl">Điện thoại:</div>
                                            <div class="info-val"><a
                                                    href="tel:{{ $lichHen->batDongSan->chuNha->so_dien_thoai }}"
                                                    class="text-success fw-bold"><i
                                                        class="fas fa-phone-alt me-1"></i>{{ $lichHen->batDongSan->chuNha->so_dien_thoai }}</a>
                                            </div>
                                        </div>
                                    @endif
                                @else
                                    <div class="alert alert-warning mb-0"><i class="fas fa-lock me-2"></i>Thông tin Chủ nhà
                                        được bảo mật và sẽ cấp cho Sale sau khi hoàn tất xem nhà thực tế.</div>
                                @endif
                            </div>
                        </div>

                        {{-- GHI CHÚ & NHÂN SỰ --}}
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-4">
                                <div class="row mb-3">
                                    <div class="col-6"><strong class="text-primary d-block">Sale phụ trách:</strong>
                                        {{ optional($lichHen->nhanVienSale)->ho_ten ?? '---' }}</div>
                                    <div class="col-6"><strong class="text-info d-block">Nguồn mở cửa:</strong>
                                        {{ optional($lichHen->nhanVienNguonHang)->ho_ten ?? '---' }}</div>
                                </div>
                                <div class="bg-light p-3 rounded mb-2"><strong><i
                                            class="fas fa-comment-dots text-primary me-1"></i> Sale ghi chú:</strong>
                                    {{ $lichHen->ghi_chu_sale ?: 'Không có' }}</div>
                                <div class="bg-warning bg-opacity-10 border-warning border p-3 rounded"><strong><i
                                            class="fas fa-comment-dots text-warning me-1"></i> Nguồn phản hồi:</strong>
                                    {{ $lichHen->ghi_chu_nguon_hang ?: 'Không có' }}</div>
                            </div>
                        </div>
                    </div>

                    {{-- CỘT THAO TÁC & TIMELINE --}}
                    <div class="col-md-5 d-flex flex-column gap-4">
                        <div class="card border-0 shadow-sm border-top border-4 border-dark">
                            <div class="card-header bg-light py-3 fw-bold"><i class="fas fa-magic text-dark me-2"></i>Bảng
                                Thao Tác Xử Lý</div>
                            <div class="card-body p-4 d-flex flex-column gap-3">

                                {{-- ACTION: SALE MỚI NHẬN LỊCH -> CHUYỂN NGUỒN --}}
                                @if ($lichHen->trang_thai === 'sale_da_nhan' && $nhanVien->hasRole(['admin', 'sale']))
                                    <button class="btn btn-primary w-100 fw-bold py-2"
                                        onclick="new bootstrap.Modal(document.getElementById('modalChuyenNguon')).show();">
                                        <i class="fas fa-paper-plane me-2"></i>Chuyển cho Nguồn
                                    </button>
                                    <button class="btn btn-outline-danger w-100 fw-bold"
                                        onclick="new bootstrap.Modal(document.getElementById('modalSaleTuChoi')).show();">
                                        Từ chối (Khách ảo)
                                    </button>
                                @endif

                                {{-- ACTION: NGUỒN XÁC NHẬN --}}
                                @if ($lichHen->trang_thai === 'cho_xac_nhan' && $nhanVien->hasRole(['admin', 'nguon_hang']))
                                    <form action="{{ route('nhanvien.admin.lich-hen.xac-nhan', $lichHen) }}" method="POST"
                                        class="m-0">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-success w-100 fw-bold py-2"
                                            onclick="return confirm('Xác nhận đã có chìa khóa/chủ nhà đã OK?')">
                                            <i class="fas fa-check-circle me-2"></i>Báo có chìa khóa
                                        </button>
                                    </form>
                                @endif

                                {{-- ACTION: NGUỒN DỜI GIỜ -> SALE CHỐT --}}
                                @if ($lichHen->trang_thai === 'cho_sale_xac_nhan_doi_gio')
                                    @if ($nhanVien->hasRole(['admin', 'sale']))
                                        <div class="alert alert-warning border-warning">
                                            <i class="fas fa-exclamation-triangle me-2"></i> <strong>Nguồn xin dời
                                                giờ:</strong> {{ $lichHen->thoi_gian_hen->format('H:i d/m/Y') }}<br>
                                            <small>{{ $lichHen->ghi_chu_nguon_hang }}</small>
                                        </div>
                                        <form action="{{ route('nhanvien.admin.lich-hen.xac-nhan-doi-gio', $lichHen) }}"
                                            method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-success w-100 fw-bold py-2 mb-2"
                                                onclick="return confirm('Khách hàng đồng ý giờ mới? Xác nhận chốt!')">
                                                <i class="fas fa-check-circle me-2"></i>Khách OK - Chốt giờ mới
                                            </button>
                                        </form>
                                    @else
                                        <div class="alert alert-info"><i class="fas fa-spinner fa-spin me-2"></i> Đang chờ
                                            Sale xác nhận lại giờ với khách hàng.</div>
                                    @endif
                                @endif

                                {{-- ACTION: KẾT QUẢ CUỐI --}}
                                @if ($lichHen->trang_thai === 'da_xac_nhan' && $nhanVien->hasRole(['admin', 'sale']))
                                    <button class="btn btn-secondary btn-lg w-100 fw-bold py-2 shadow-sm"
                                        onclick="new bootstrap.Modal(document.getElementById('modalHoanThanh')).show();">
                                        <i class="fas fa-flag-checkered me-2"></i>Cập nhật Kết quả (Chốt/Rớt)
                                    </button>
                                @endif

                                {{-- NÚT HỦY CHUNG --}}
                                @if (
                                    !in_array($lichHen->trang_thai, ['hoan_thanh', 'huy', 'tu_choi']) &&
                                        $nhanVien->hasRole(['admin', 'sale', 'nguon_hang']))
                                    <hr class="my-2">
                                    <button class="btn btn-outline-danger w-100 fw-bold"
                                        onclick="new bootstrap.Modal(document.getElementById('modalHuyShow')).show();">
                                        <i class="fas fa-ban me-1"></i> Báo Hủy lịch đột xuất
                                    </button>
                                @endif

                                <a href="{{ route('nhanvien.admin.lich-hen.index') }}"
                                    class="btn btn-light border w-100 mt-2">Quay lại danh sách</a>
                            </div>
                        </div>

                        {{-- TIMELINE QUÁ TRÌNH --}}
                        <div class="card border-0 shadow-sm flex-fill">
                            <div class="card-body p-4">
                                <div class="timeline">
                                    <div class="tl-step">
                                        <div class="tl-dot done"><i class="fas fa-plus"></i></div>
                                        <div class="tl-label">Khách gửi yêu cầu</div>
                                        <div class="tl-sub">{{ $lichHen->created_at->format('H:i d/m/Y') }}</div>
                                    </div>

                                    @if (in_array($lichHen->trang_thai, [
                                            'sale_da_nhan',
                                            'cho_xac_nhan',
                                            'cho_sale_xac_nhan_doi_gio',
                                            'da_xac_nhan',
                                            'hoan_thanh',
                                        ]))
                                        <div class="tl-step">
                                            <div class="tl-dot done"><i class="fas fa-headset"></i></div>
                                            <div class="tl-label">Sale tiếp nhận xử lý</div>
                                        </div>
                                    @endif

                                    @if ($lichHen->trang_thai === 'cho_sale_xac_nhan_doi_gio')
                                        <div class="tl-step">
                                            <div class="tl-dot pend"><i class="fas fa-sync-alt"></i></div>
                                            <div class="tl-label text-warning-emphasis">Nguồn xin dời giờ</div>
                                            <div class="tl-sub">Đang chờ Sale xác nhận với khách</div>
                                        </div>
                                    @endif

                                    @if (in_array($lichHen->trang_thai, ['da_xac_nhan', 'hoan_thanh']))
                                        <div class="tl-step">
                                            <div class="tl-dot done"><i class="fas fa-key"></i></div>
                                            <div class="tl-label">Đã chốt giờ có chìa khóa</div>
                                            <div class="tl-sub text-success">
                                                {{ optional($lichHen->xac_nhan_at)->format('H:i d/m/Y') }}</div>
                                        </div>
                                    @endif

                                    @if ($lichHen->trang_thai === 'hoan_thanh')
                                        <div class="tl-step">
                                            <div class="tl-dot done"><i class="fas fa-flag-checkered"></i></div>
                                            <div class="tl-label text-success fw-bold">Đã hoàn thành xem nhà</div>
                                            <div class="tl-sub text-success fw-bold">Kết thúc lúc:
                                                {{ optional($lichHen->hoan_thanh_at)->format('H:i d/m/Y') }}</div>
                                        </div>
                                    @elseif(in_array($lichHen->trang_thai, ['tu_choi', 'huy']))
                                        <div class="tl-step">
                                            <div class="tl-dot fail"><i class="fas fa-ban"></i></div>
                                            <div class="tl-label text-danger fw-bold">Lịch đã bị Hủy/Từ chối</div>
                                            <div class="tl-sub text-danger">
                                                {{ optional($lichHen->huy_at ?? $lichHen->tu_choi_at)->format('H:i d/m/Y') }}
                                            </div>
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

    {{-- MODALS THAO TÁC --}}
    @if ($lichHen->trang_thai === 'sale_da_nhan' && isset($dsNguonHang))
        <div class="modal fade" id="modalChuyenNguon" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <form action="{{ route('nhanvien.admin.lich-hen.tiep-nhan', $lichHen) }}" method="POST"
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
                        <label class="form-label fw-bold">Lý do từ chối (Khách ảo, thuê báo bán...):</label>
                        <textarea name="ly_do_tu_choi" class="form-control" rows="3" required
                            placeholder="Ví dụ: Gọi điện không bắt máy..."></textarea>
                        <button type="submit" class="btn btn-danger w-100 fw-bold mt-4">Từ chối</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <div class="modal fade" id="modalHoanThanh" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route('nhanvien.admin.lich-hen.hoan-thanh', $lichHen) }}" method="POST"
                class="modal-content shadow border-0">
                @csrf @method('PATCH')
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title fw-bold"><i class="fas fa-flag-checkered me-2"></i>Cập nhật Kết quả Xem nhà
                    </h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-4">
                        <label class="form-label fw-bold fs-6">Khách hàng quyết định như thế nào?</label>
                        <select name="ket_qua" class="form-select form-select-lg" required>
                            <option value="">-- Chọn kết quả --</option>
                            <option value="chot" class="text-success fw-bold">✅ ĐÃ CHỐT THÀNH CÔNG (Đặt cọc)</option>
                            <option value="khong_chot" class="text-danger fw-bold">❌ KHÔNG CHỐT (Suy nghĩ thêm)</option>
                        </select>
                        <small class="text-muted d-block mt-2"><i class="fas fa-info-circle me-1"></i>Nếu "Đã chốt", BĐS
                            sẽ tự động chuyển sang Đã bán.</small>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-bold">Ghi chú & Review từ khách:</label>
                        <textarea name="ghi_chu_sale" class="form-control" rows="3" placeholder="Lý do không chốt..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0"><button type="submit"
                        class="btn btn-success btn-lg fw-bold w-100">Lưu kết quả & Đóng lịch</button></div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="modalHuyShow" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route('nhanvien.admin.lich-hen.huy', $lichHen) }}" method="POST"
                class="modal-content shadow border-0">
                @csrf @method('PATCH')
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title fw-bold"><i class="fas fa-ban me-2"></i> Hủy lịch xem nhà</h5><button
                        type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <label class="form-label fw-bold">Nguyên nhân hủy:</label>
                    <textarea name="ly_do" class="form-control" required placeholder="Khách bận đột xuất, chủ nhà đi vắng..."></textarea>
                    <button type="submit" class="btn btn-danger mt-4 w-100 fw-bold">Xác nhận Hủy lịch</button>
                </div>
            </form>
        </div>
    </div>
@endsection
