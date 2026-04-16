@extends('admin.layouts.master')
@section('title', 'Nhiệm vụ Xác nhận Lịch hẹn')

@section('content')
    <div class="mb-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
            <h1 class="page-header-title text-success mb-1"><i class="fas fa-clipboard-list me-2"></i> Trạm Điều Phối Nguồn
                Hàng</h1>
            <p class="text-muted mb-0">Bộ phận Nguồn Hàng: Xác nhận giờ xem nhà, hỗ trợ Sale xuất kho.</p>
        </div>
        @if (!empty($adminMode))
            <div class="alert alert-success d-flex align-items-center mb-0 py-2 px-3 shadow-sm border-0">
                <span class="me-3"><i class="fas fa-user-shield me-2"></i> Đang xem quyền Admin</span>
                <a href="{{ route('nhanvien.admin.lich-hen.index') }}" class="btn btn-sm btn-success rounded-pill fw-bold">
                    <i class="fas fa-calendar-alt me-1"></i> Về Calendar
                </a>
            </div>
        @endif
    </div>

    @php
        $isListTab = request('tab') == 'list';
        $todoCollection = collect($lichHensTodo ?? []);
        $todayDate = now()->toDateString();

        $todoMetrics = [
            'all' => $todoCollection->count(),
            'cho_xac_nhan' => $todoCollection->where('trang_thai', 'cho_xac_nhan')->count(),
            'da_xac_nhan' => $todoCollection->where('trang_thai', 'da_xac_nhan')->count(),
            'today' => $todoCollection
                ->filter(fn($lh) => \Carbon\Carbon::parse($lh->thoi_gian_hen)->toDateString() === $todayDate)
                ->count(),
        ];

        // Hàm kiểm tra lịch có đang chờ Sale đổi giờ không
        $isDoiGio = static function ($lh) {
            return $lh->trang_thai === 'cho_sale_xac_nhan_doi_gio';
        };
    @endphp

    <ul class="nav nav-tabs mb-4 border-bottom-2" role="tablist">
        <li class="nav-item">
            <a href="{{ route('nhanvien.admin.lich-hen.index', ['giao_dien' => 'nguon_hang']) }}"
                class="nav-link px-4 {{ !$isListTab ? 'active fw-bold text-success border-success border-bottom-0' : 'text-muted' }}">
                <i class="fas fa-bell me-1"></i> Cần Xử Lý <span
                    class="badge bg-{{ !$isListTab ? 'success' : 'secondary' }} ms-1">{{ $todoMetrics['all'] }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('nhanvien.admin.lich-hen.index', ['tab' => 'list', 'giao_dien' => 'nguon_hang']) }}"
                class="nav-link px-4 {{ $isListTab ? 'active fw-bold text-success border-success border-bottom-0' : 'text-muted' }}">
                <i class="fas fa-history me-1"></i> Lịch Sử Nhiệm Vụ
            </a>
        </li>
    </ul>

    <div class="tab-content">
        {{-- ===================== TAB CẦN XỬ LÝ ===================== --}}
        <div class="tab-pane fade {{ !$isListTab ? 'show active' : '' }}" id="tab-todo">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0 text-dark fw-bold">Danh sách công việc</h5>
            </div>

            @if ($todoCollection->isEmpty())
                <div class="text-center py-5 bg-white rounded-3 shadow-sm border">
                    <i class="fas fa-coffee text-success mb-3 opacity-25" style="font-size: 5rem;"></i>
                    <h4 class="text-muted fw-bold">Tuyệt vời!</h4>
                    <p class="text-muted">Không có nhiệm vụ nào đang chờ xử lý.</p>
                </div>
            @else
                <div class="row g-3">
                    @foreach ($todoCollection as $lh)
                        <div class="col-12 col-xxl-6">
                            <div
                                class="card border-0 shadow-sm h-100 transition-hover {{ $lh->trang_thai == 'cho_xac_nhan' ? 'border-start border-4 border-info' : ($isDoiGio($lh) ? 'border-start border-4 border-warning' : 'border-start border-4 border-success') }}">
                                <div class="card-body p-3 p-md-4">
                                    <div class="d-flex flex-column flex-md-row gap-3">

                                        {{-- THỜI GIAN --}}
                                        <div class="text-center bg-light rounded p-3" style="min-width: 140px;">
                                            <div class="text-danger display-6 fw-bold" style="line-height: 1;">
                                                {{ \Carbon\Carbon::parse($lh->thoi_gian_hen)->format('H:i') }}</div>
                                            <div class="small fw-bold text-muted mt-2">
                                                {{ \Carbon\Carbon::parse($lh->thoi_gian_hen)->format('d/m/Y') }}</div>
                                            <hr class="my-2 border-secondary">
                                            @if ($isDoiGio($lh))
                                                <span class="badge w-100 py-2 bg-warning text-dark"><i
                                                        class="fas fa-spinner fa-spin me-1"></i>CHỜ SALE CHỐT</span>
                                            @elseif ($lh->trang_thai == 'cho_xac_nhan')
                                                <span class="badge w-100 py-2 bg-info text-dark">CẦN CHỐT CHỦ</span>
                                            @else
                                                <span class="badge w-100 py-2 bg-success">ĐÃ BÁO CÓ CHÌA</span>
                                            @endif
                                        </div>

                                        {{-- THÔNG TIN --}}
                                        <div class="flex-fill">
                                            <div class="fw-bold text-primary fs-5 mb-1">
                                                {{ $lh->batDongSan->tieu_de ?? 'BĐS Ngoài' }}</div>
                                            <div class="text-muted small mb-2"><i
                                                    class="fas fa-map-marker-alt text-danger me-1"></i>{{ optional($lh->batDongSan)->khuVuc?->ten_khu_vuc ?? '---' }}
                                            </div>

                                            <div class="row g-2 mb-2">
                                                <div class="col-sm-6">
                                                    <div class="p-2 border rounded bg-white h-100">
                                                        <div class="small text-muted"><i
                                                                class="fas fa-user-tie me-1"></i>Sale đặt lịch:</div>
                                                        <div class="fw-bold">{{ $lh->nhanVienSale->ho_ten ?? '---' }}</div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div
                                                        class="p-2 border rounded bg-success bg-opacity-10 border-success h-100">
                                                        <div class="small text-success fw-bold"><i
                                                                class="fas fa-id-card me-1"></i>Chủ nhà:</div>
                                                        <div class="fw-bold text-dark">
                                                            {{ optional($lh->batDongSan?->chuNha)->ho_ten ?? 'Chưa rõ' }}
                                                        </div>
                                                        @if ($lh->batDongSan?->chuNha)
                                                            <a href="tel:{{ $lh->batDongSan->chuNha->so_dien_thoai }}"
                                                                class="small fw-bold text-success text-decoration-none">{{ $lh->batDongSan->chuNha->so_dien_thoai }}</a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            @if ($lh->ghi_chu_sale)
                                                <div
                                                    class="small p-2 bg-warning bg-opacity-10 border border-warning rounded text-dark mt-2">
                                                    <strong><i class="fas fa-comment-dots me-1"></i>Sale nhắn:</strong>
                                                    {{ $lh->ghi_chu_sale }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                {{-- THAO TÁC THEO TRẠNG THÁI --}}
                                <div class="card-footer bg-white border-top p-3">
                                    @if ($lh->trang_thai == 'cho_xac_nhan')
                                        <div class="d-flex gap-2">
                                            <button class="btn btn-success fw-bold flex-fill" data-bs-toggle="modal"
                                                data-bs-target="#modalXacNhan{{ $lh->id }}"><i
                                                    class="fas fa-key me-1"></i> Có chìa khóa</button>
                                            <button class="btn btn-warning fw-bold text-dark flex-fill"
                                                data-bs-toggle="modal" data-bs-target="#modalBaoLai{{ $lh->id }}"><i
                                                    class="fas fa-clock me-1"></i> Xin dời lịch</button>
                                            <button class="btn btn-outline-danger fw-bold flex-fill" data-bs-toggle="modal"
                                                data-bs-target="#modalTuChoi{{ $lh->id }}"><i
                                                    class="fas fa-times me-1"></i> Từ chối</button>
                                        </div>
                                    @elseif ($isDoiGio($lh))
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="small text-muted"><i class="fas fa-info-circle me-1"></i> Đã xin dời
                                                lịch! Đang chờ Sale hỏi khách...</div>
                                            <button class="btn btn-light border fw-bold text-danger px-4"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalHuyNguon{{ $lh->id }}">Báo Hủy</button>
                                        </div>
                                    @else
                                        <div class="d-flex gap-2 justify-content-end">
                                            <button class="btn btn-light border fw-bold text-dark px-4"
                                                data-bs-toggle="modal" data-bs-target="#modalBaoLai{{ $lh->id }}"><i
                                                    class="fas fa-clock text-warning me-1"></i> Dời lịch</button>
                                            <button class="btn btn-light border fw-bold text-danger px-4"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalHuyNguon{{ $lh->id }}"><i
                                                    class="fas fa-ban me-1"></i> Hủy lịch</button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- ===================== TAB LỊCH SỬ TỔNG HỢP ===================== --}}
        <div class="tab-pane fade {{ $isListTab ? 'show active' : '' }}" id="tab-list">
            <div class="card border-0 shadow-sm">
                <div class="table-responsive p-3">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Thời gian</th>
                                <th>Khách hàng</th>
                                <th>Bất động sản</th>
                                <th>Trạng thái</th>
                                <th>Chi tiết</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lichHensList ?? [] as $lh)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($lh->thoi_gian_hen)->format('H:i d/m/Y') }}</td>
                                    <td>{{ $lh->ten_khach_hang }} ({{ $lh->sdt_khach_hang }})</td>
                                    <td class="text-primary fw-bold">{{ $lh->batDongSan->tieu_de ?? 'Ngoài hệ thống' }}
                                    </td>
                                    <td><span class="badge bg-secondary">{{ $lh->trang_thai }}</span></td>
                                    <td><a href="{{ route('nhanvien.admin.lich-hen.show', $lh->id) }}"
                                            class="btn btn-sm btn-outline-primary">Xem</a></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">Không có dữ liệu lịch sử.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3 p-3">{{ optional($lichHensList)->links() }}</div>
            </div>
        </div>
    </div>

    {{-- ===================== KHU VỰC MODALS ===================== --}}
    <div id="modals-container">
        @foreach ($todoCollection as $lh)
            {{-- MODAL XÁC NHẬN --}}
            <div class="modal fade" id="modalXacNhan{{ $lh->id }}" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <form action="{{ route('nhanvien.admin.lich-hen.xac-nhan', $lh->id) }}" method="POST"
                        class="modal-content border-0 shadow-lg">
                        @csrf @method('PATCH')
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title fw-bold"><i class="fas fa-check-circle me-2"></i> Báo Sale Dẫn Khách
                                Qua</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body p-4 text-center">
                            <i class="fas fa-key text-success mb-3" style="font-size: 3rem;"></i>
                            <h5>Chủ nhà đã đồng ý mở cửa?</h5>
                            <p class="text-muted">Xác nhận có chìa khóa hoặc đã báo chủ nhà lúc
                                <strong>{{ \Carbon\Carbon::parse($lh->thoi_gian_hen)->format('H:i - d/m/Y') }}</strong>?
                            </p>
                        </div>
                        <div class="modal-footer bg-light"><button type="submit"
                                class="btn btn-success fw-bold w-100">Chắc chắn, báo cho Sale</button></div>
                    </form>
                </div>
            </div>

            {{-- MODAL TỪ CHỐI --}}
            <div class="modal fade" id="modalTuChoi{{ $lh->id }}" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <form action="{{ route('nhanvien.admin.lich-hen.tu-choi', $lh->id) }}" method="POST"
                        class="modal-content border-0 shadow-lg">
                        @csrf @method('PATCH')
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title fw-bold"><i class="fas fa-times-circle me-2"></i> Từ Chối Lịch Xem Nhà
                            </h5><button type="button" class="btn-close btn-close-white"
                                data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body p-4">
                            <label class="form-label fw-bold">Lý do từ chối (Bắt buộc):</label>
                            <textarea name="ly_do_tu_choi" class="form-control" rows="3" required
                                placeholder="VD: Chủ nhà báo đã bán/nhận cọc..."></textarea>
                        </div>
                        <div class="modal-footer bg-light"><button type="submit"
                                class="btn btn-danger fw-bold w-100">Xác nhận Từ chối</button></div>
                    </form>
                </div>
            </div>

            {{-- MODAL DỜI GIỜ --}}
            <div class="modal fade" id="modalBaoLai{{ $lh->id }}" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <form action="{{ route('nhanvien.admin.lich-hen.bao-lai-gio', $lh->id) }}" method="POST"
                        class="modal-content border-0 shadow-lg">
                        @csrf @method('PATCH')
                        <div class="modal-header bg-warning">
                            <h5 class="modal-title fw-bold text-dark"><i class="fas fa-clock me-2"></i> Đề Xuất Dời Giờ
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body p-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Chủ nhà muốn hẹn lúc:</label>
                                <input type="datetime-local" name="thoi_gian_hen" class="form-control form-control-lg"
                                    value="{{ \Carbon\Carbon::parse($lh->thoi_gian_hen)->format('Y-m-d\TH:i') }}"
                                    required>
                            </div>
                            <div class="mb-0">
                                <label class="form-label fw-bold">Lời nhắn cho Sale (Để báo khách):</label>
                                <textarea name="ghi_chu_nguon_hang" class="form-control" rows="2" placeholder="VD: Cô chủ đi làm 5h mới về..."
                                    required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer bg-light"><button type="submit" class="btn btn-dark fw-bold w-100">Báo
                                lại cho Sale</button></div>
                    </form>
                </div>
            </div>

            {{-- MODAL HỦY --}}
            <div class="modal fade" id="modalHuyNguon{{ $lh->id }}" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <form action="{{ route('nhanvien.admin.lich-hen.huy', $lh->id) }}" method="POST"
                        class="modal-content border-0 shadow-lg">
                        @csrf @method('PATCH')
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title fw-bold"><i class="fas fa-ban me-2"></i> Báo Hủy Lịch Đột Xuất</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body p-4">
                            <label class="form-label fw-bold">Lý do Hủy (Bắt buộc):</label>
                            <textarea name="ly_do" class="form-control" rows="3" required
                                placeholder="VD: Chủ nhà có việc đột xuất đóng cửa đi vắng..."></textarea>
                        </div>
                        <div class="modal-footer bg-light"><button type="submit"
                                class="btn btn-danger fw-bold w-100">Xác nhận Hủy Lịch</button></div>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
@endsection

@push('styles')
    <style>
        .transition-hover {
            transition: all 0.3s ease;
        }

        .transition-hover:hover {
            transform: translateY(-3px);
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .08) !important;
            z-index: 1;
        }

        .nav-tabs .nav-link {
            font-size: 1.05rem;
            color: #6c757d;
            border: none;
            border-bottom: 3px solid transparent;
            padding: 12px 20px;
            transition: all 0.2s ease;
        }

        .nav-tabs .nav-link.active {
            color: var(--bs-success);
            border-bottom-color: var(--bs-success);
            background: transparent;
        }
    </style>
@endpush
