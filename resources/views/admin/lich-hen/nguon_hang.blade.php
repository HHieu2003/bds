@extends('admin.layouts.master')
@section('title', 'Nhiệm vụ Xác nhận Lịch hẹn')

@section('content')
    <div class="mb-4">
        <h1 class="page-header-title text-success"><i class="fas fa-clipboard-list me-2"></i> Yêu cầu xuất kho (Từ Sale)</h1>
        <p class="text-muted">Nhiệm vụ: Gọi cho chủ nhà lấy chìa khóa hoặc xác nhận giờ xem nhà.</p>
    </div>

    @include('frontend.partials.flash-messages')

    <div class="card border-0 shadow-sm">
        <div class="list-group list-group-flush">
            @forelse($lichHens as $lh)
                <div
                    class="list-group-item p-3 p-md-4 transition-hover {{ $lh->trang_thai == 'cho_xac_nhan' ? 'bg-warning bg-opacity-10 border-start border-4 border-warning' : 'border-start border-4 border-success' }}">
                    <div class="row align-items-center g-3">

                        {{-- Cột 1: Thời gian & Trạng thái --}}
                        <div class="col-12 col-md-2 text-md-center border-md-end">
                            <div class="fw-bold text-danger fs-4" style="line-height: 1;">
                                {{ date('H:i', strtotime($lh->thoi_gian_hen)) }}</div>
                            <div class="text-muted small mb-2 fw-medium">{{ date('d/m/Y', strtotime($lh->thoi_gian_hen)) }}
                            </div>
                            <span
                                class="badge {{ $lh->trang_thai == 'cho_xac_nhan' ? 'bg-warning text-dark' : 'bg-success' }} w-100 py-2">
                                {{ $lh->trang_thai == 'cho_xac_nhan' ? 'CẦN XÁC NHẬN' : 'ĐÃ BÁO SALE' }}
                            </span>
                        </div>

                        {{-- Cột 2: Thông tin Bất động sản & Sale yêu cầu --}}
                        <div class="col-12 col-md-4">
                            <span class="text-muted" style="font-size: 0.75rem;">Cần dẫn khách xem:</span>
                            <div class="fw-bold text-primary fs-6 mb-1 text-truncate"
                                title="{{ $lh->batDongSan->tieu_de ?? 'Nhà lẻ' }}">
                                {{ $lh->batDongSan->tieu_de ?? 'Nhà lẻ' }}
                            </div>
                            <div class="text-muted small mb-2 text-truncate"
                                title="{{ $lh->batDongSan->dia_chi ?? 'Khu vực chưa rõ' }}">
                                <i class="fas fa-map-marker-alt text-danger me-1"></i>
                                {{ $lh->batDongSan->dia_chi ?? 'Khu vực chưa rõ' }}
                            </div>
                            <div class="small">
                                <span class="text-muted"><i class="fas fa-user-tag me-1"></i> Sale hẹn:</span>
                                <strong class="text-dark">{{ $lh->nhanVienSale->ho_ten ?? 'N/A' }}</strong>
                            </div>
                        </div>

                        {{-- Cột 3: Thông tin Chủ nhà (Rất quan trọng cho Nguồn) --}}
                        <div class="col-12 col-md-3 border-start-md px-md-3">
                            <span class="text-muted" style="font-size: 0.75rem;">Thông tin Chủ Nhà:</span>
                            @if ($lh->batDongSan && $lh->batDongSan->chuNha)
                                <div class="fw-bold text-dark mb-1">{{ $lh->batDongSan->chuNha->ho_ten }}</div>
                                <a href="tel:{{ $lh->batDongSan->chuNha->so_dien_thoai }}"
                                    class="btn btn-sm btn-outline-info text-info-emphasis border-info fw-bold w-100 text-start shadow-sm mt-1">
                                    <i class="fas fa-phone-alt me-2"></i> {{ $lh->batDongSan->chuNha->so_dien_thoai }}
                                </a>
                            @else
                                <div class="text-danger fw-bold small mt-1"><i class="fas fa-exclamation-triangle me-1"></i>
                                    Không có data chủ nhà</div>
                            @endif
                        </div>

                        {{-- Cột 4: Thao tác Xác nhận / Đổi giờ --}}
                        <div class="col-12 col-md-3 text-md-end">
                            @if ($lh->trang_thai == 'cho_xac_nhan')
                                <div class="d-flex flex-md-column gap-2 justify-content-end">
                                    {{-- Nút ĐỒNG Ý --}}
                                    <form action="{{ route('nhanvien.admin.lich-hen.xac-nhan', $lh->id) }}" method="POST"
                                        class="w-100 m-0">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-success w-100 fw-bold shadow-sm"
                                            onclick="return confirm('Bạn đã liên hệ Chủ nhà và chốt lịch đi xem?')">
                                            <i class="fas fa-check-circle me-1"></i> ĐỒNG Ý
                                        </button>
                                    </form>
                                    {{-- Nút ĐỔI GIỜ --}}
                                    <button type="button" class="btn btn-warning w-100 fw-bold text-dark shadow-sm"
                                        data-bs-toggle="modal" data-bs-target="#modalBaoLai{{ $lh->id }}">
                                        <i class="fas fa-clock me-1"></i> ĐỔI GIỜ
                                    </button>
                                </div>
                            @else
                                <div class="d-flex flex-column align-items-md-end">
                                    <div class="text-success fw-bold mb-2"><i class="fas fa-check-circle me-1"></i> Đã
                                        phản hồi Sale</div>
                                    <div class="d-flex gap-2">
                                        <button type="button"
                                            class="btn btn-sm btn-outline-warning text-dark fw-bold shadow-sm"
                                            data-bs-toggle="modal" data-bs-target="#modalBaoLai{{ $lh->id }}">
                                            <i class="fas fa-clock"></i> Dời lịch
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger fw-bold shadow-sm"
                                            data-bs-toggle="modal" data-bs-target="#modalHuyNguon{{ $lh->id }}">
                                            <i class="fas fa-times"></i> Hủy
                                        </button>
                                    </div>
                                </div>

                                <div class="modal fade text-start" id="modalHuyNguon{{ $lh->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <form action="{{ route('nhanvien.admin.lich-hen.huy', $lh->id) }}" method="POST"
                                            class="modal-content border-0 shadow">
                                            @csrf @method('PATCH')
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title fw-bold"><i
                                                        class="fas fa-exclamation-triangle me-2"></i> Báo hủy lịch đột xuất
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white"
                                                    data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body p-4">
                                                <label class="form-label fw-bold">Lý do Hủy (Sale sẽ thấy):</label>
                                                <textarea name="ly_do" class="form-control" rows="3"
                                                    placeholder="VD: Chủ nhà đã chốt bán cho khách khác, chủ nhà không tiếp nữa..." required></textarea>
                                            </div>
                                            <div class="modal-footer bg-light">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Đóng</button>
                                                <button type="submit" class="btn btn-danger fw-bold">Xác nhận Hủy
                                                    Lịch</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>

                {{-- MODAL ĐỔI GIỜ (Chỉ xuất hiện khi bấm nút) --}}
                @if (in_array($lh->trang_thai, ['cho_xac_nhan', 'da_xac_nhan']))
                    <div class="modal fade" id="modalBaoLai{{ $lh->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <form action="{{ route('nhanvien.admin.lich-hen.bao-lai-gio', $lh->id) }}" method="POST"
                                class="modal-content border-0 shadow">
                                @csrf @method('PATCH')
                                <div class="modal-header bg-warning">
                                    <h5 class="modal-title fw-bold text-dark"><i class="fas fa-history me-2"></i> Báo lại
                                        giờ cho Sale</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-4">
                                    <div class="alert alert-light border text-dark small mb-4">
                                        Chủ nhà đi vắng hoặc không tiện tiếp khách vào lúc
                                        <strong>{{ date('H:i d/m/Y', strtotime($lh->thoi_gian_hen)) }}</strong>? Hãy đề
                                        xuất một giờ khác.
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Giờ mới chủ nhà hẹn:</label>
                                        <input type="datetime-local" name="thoi_gian_hen" class="form-control"
                                            value="{{ \Carbon\Carbon::parse($lh->thoi_gian_hen)->format('Y-m-d\TH:i') }}"
                                            required>
                                    </div>
                                    <div class="mb-0">
                                        <label class="form-label fw-bold">Lý do / Ghi chú cho Sale:</label>
                                        <textarea name="ghi_chu_nguon_hang" class="form-control" rows="3"
                                            placeholder="Ví dụ: Chủ đi làm, hẹn lại 4h chiều nay mới có người ở nhà nhé..." required></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer bg-light">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                    <button type="submit" class="btn btn-dark fw-bold"><i
                                            class="fas fa-paper-plane me-1"></i> Gửi thông báo cho Sale</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif

            @empty
                <div class="text-center py-5">
                    <i class="fas fa-check-double text-success mb-3 opacity-25" style="font-size: 4rem;"></i>
                    <h5 class="text-muted fw-bold">Tuyệt vời! Bạn đã xử lý hết yêu cầu.</h5>
                    <p class="text-muted small">Hiện tại không có lịch hẹn nào chờ xác nhận.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .transition-hover {
            transition: background-color 0.2s ease;
        }

        .transition-hover:hover {
            background-color: #f8f9fa;
        }

        /* Giới hạn chiều dài chữ trên mobile để không bị vỡ giao diện */
        @media (max-width: 767.98px) {
            .border-md-end {
                border-right: none !important;
                border-bottom: 1px dashed #dee2e6;
                padding-bottom: 1rem;
            }

            .border-start-md {
                border-left: none !important;
                border-top: 1px dashed #dee2e6;
                padding-top: 1rem;
                margin-top: 0.5rem;
            }
        }

        @media (min-width: 768px) {
            .border-md-end {
                border-right: 1px dashed #dee2e6;
            }

            .border-start-md {
                border-left: 1px dashed #dee2e6;
            }
        }
    </style>
@endpush
