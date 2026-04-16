@extends('admin.layouts.master')
@section('title', 'Nhiệm vụ Xác nhận Lịch hẹn')

@section('content')
    <div class="mb-4">
        <h1 class="page-header-title text-success"><i class="fas fa-clipboard-list me-2"></i> Nhiệm vụ Lịch hẹn</h1>
        <p class="text-muted">Bộ phận Nguồn Hàng: Xác nhận giờ xem nhà, hỗ trợ Sale xuất kho.</p>
    </div>

    @if (!empty($adminMode))
        <div class="alert alert-success d-flex justify-content-between align-items-center">
            <span><i class="fas fa-user-shield me-2"></i> Bạn đang xem giao diện Nguồn hàng dưới quyền Admin.</span>
            <a href="{{ route('nhanvien.admin.lich-hen.index') }}" class="btn btn-sm btn-outline-success">
                <i class="fas fa-calendar-alt me-1"></i> Về Calendar
            </a>
        </div>
    @endif

    @php
        $isListTab = request('tab') == 'list';
        $todoView = request('todo_view', 'card');
        $todoStatus = request('todo_status', 'all');
        $isDoiGio = static function ($lh) {
            $noteSale = mb_strtolower((string) $lh->ghi_chu_sale);
            $noteNguon = mb_strtolower((string) $lh->ghi_chu_nguon_hang);

            return str_contains((string) $lh->ghi_chu_sale, '[DOI_GIO]') ||
                str_contains((string) $lh->ghi_chu_nguon_hang, '[DOI_GIO]') ||
                str_contains($noteSale, 'doi gio') ||
                str_contains($noteSale, 'dời') ||
                str_contains($noteNguon, 'doi gio') ||
                str_contains($noteNguon, 'dời');
        };
        $queryBase = request()->query();
        $todoCardUrl = route(
            'nhanvien.admin.lich-hen.index',
            array_merge($queryBase, ['tab' => 'todo', 'todo_view' => 'card']),
        );
        $todoListUrl = route(
            'nhanvien.admin.lich-hen.index',
            array_merge($queryBase, ['tab' => 'todo', 'todo_view' => 'list']),
        );

        $todoCollection = collect($lichHensTodo ?? []);
        $todayDate = now()->toDateString();

        $todoMetrics = [
            'all' => $todoCollection->count(),
            'cho_xac_nhan' => $todoCollection->where('trang_thai', 'cho_xac_nhan')->count(),
            'da_xac_nhan' => $todoCollection->where('trang_thai', 'da_xac_nhan')->count(),
            'overdue' => $todoCollection
                ->filter(
                    fn($lh) => \Carbon\Carbon::parse($lh->thoi_gian_hen)->isPast() && $lh->trang_thai !== 'hoan_thanh',
                )
                ->count(),
            'today' => $todoCollection
                ->filter(fn($lh) => \Carbon\Carbon::parse($lh->thoi_gian_hen)->toDateString() === $todayDate)
                ->count(),
        ];

        $todoItems = match ($todoStatus) {
            'cho_xac_nhan' => $todoCollection->where('trang_thai', 'cho_xac_nhan')->values(),
            'da_xac_nhan' => $todoCollection->where('trang_thai', 'da_xac_nhan')->values(),
            'overdue' => $todoCollection
                ->filter(
                    fn($lh) => \Carbon\Carbon::parse($lh->thoi_gian_hen)->isPast() && $lh->trang_thai !== 'hoan_thanh',
                )
                ->values(),
            'today' => $todoCollection
                ->filter(fn($lh) => \Carbon\Carbon::parse($lh->thoi_gian_hen)->toDateString() === $todayDate)
                ->values(),
            default => $todoCollection->values(),
        };

        $buildTodoUrl = function ($params = []) use ($queryBase) {
            return route('nhanvien.admin.lich-hen.index', array_merge($queryBase, ['tab' => 'todo'], $params));
        };

        $tabTodoUrl = route('nhanvien.admin.lich-hen.index', array_merge($queryBase, ['tab' => 'todo']));
        $tabListUrl = route('nhanvien.admin.lich-hen.index', array_merge($queryBase, ['tab' => 'list']));
    @endphp
    <ul class="nav nav-tabs mb-4 border-bottom-2" role="tablist">
        <li class="nav-item" role="presentation">
            <a href="{{ $tabTodoUrl }}"
                class="nav-link {{ !$isListTab ? 'active fw-bold text-success border-success border-bottom-0' : 'text-muted' }}"
                role="tab" aria-current="{{ !$isListTab ? 'page' : 'false' }}">
                <i class="fas fa-bell me-1"></i> Cần Xử Lý ({{ $todoMetrics['all'] }})
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a href="{{ $tabListUrl }}"
                class="nav-link {{ $isListTab ? 'active fw-bold text-success border-success border-bottom-0' : 'text-muted' }}"
                role="tab" aria-current="{{ $isListTab ? 'page' : 'false' }}">
                <i class="fas fa-history me-1"></i> Tất Cả Lịch Sử
            </a>
        </li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane fade {{ !$isListTab ? 'show active' : '' }}" id="tab-todo" role="tabpanel">
            <div class="row g-2 mb-3">
                <div class="col-6 col-md-2">
                    <div class="border rounded-3 p-2 bg-light">
                        <div class="small text-muted">Tổng việc</div>
                        <div class="fw-bold fs-5">{{ $todoMetrics['all'] }}</div>
                    </div>
                </div>
                <div class="col-6 col-md-2">
                    <div class="border rounded-3 p-2 bg-warning bg-opacity-10">
                        <div class="small text-muted">Chờ xác nhận</div>
                        <div class="fw-bold fs-5 text-warning-emphasis">{{ $todoMetrics['cho_xac_nhan'] }}</div>
                    </div>
                </div>
                <div class="col-6 col-md-2">
                    <div class="border rounded-3 p-2 bg-success bg-opacity-10">
                        <div class="small text-muted">Đã xác nhận</div>
                        <div class="fw-bold fs-5 text-success">{{ $todoMetrics['da_xac_nhan'] }}</div>
                    </div>
                </div>
                <div class="col-6 col-md-2">
                    <div class="border rounded-3 p-2 bg-danger bg-opacity-10">
                        <div class="small text-muted">Quá giờ</div>
                        <div class="fw-bold fs-5 text-danger">{{ $todoMetrics['overdue'] }}</div>
                    </div>
                </div>
                <div class="col-6 col-md-2">
                    <div class="border rounded-3 p-2 bg-info bg-opacity-10">
                        <div class="small text-muted">Lịch hôm nay</div>
                        <div class="fw-bold fs-5 text-info-emphasis">{{ $todoMetrics['today'] }}</div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0 text-muted fw-bold">Hiển thị cần xử lý</h6>
                <div class="btn-group btn-group-sm shadow-sm" role="group" aria-label="Chuyển kiểu hiển thị">
                    <a href="{{ $todoCardUrl }}"
                        class="btn {{ $todoView === 'card' ? 'btn-success' : 'btn-outline-success' }}">
                        <i class="fas fa-grip me-1"></i> Thẻ
                    </a>
                    <a href="{{ $todoListUrl }}"
                        class="btn {{ $todoView === 'list' ? 'btn-success' : 'btn-outline-success' }}">
                        <i class="fas fa-table me-1"></i> Danh sách
                    </a>
                </div>
            </div>

            <div class="d-flex flex-wrap gap-2 mb-3">
                <a href="{{ $buildTodoUrl(['todo_view' => $todoView, 'todo_status' => 'all']) }}"
                    class="btn btn-sm {{ $todoStatus === 'all' ? 'btn-dark' : 'btn-outline-dark' }}">Tất cả
                    ({{ $todoMetrics['all'] }})</a>
                <a href="{{ $buildTodoUrl(['todo_view' => $todoView, 'todo_status' => 'cho_xac_nhan']) }}"
                    class="btn btn-sm {{ $todoStatus === 'cho_xac_nhan' ? 'btn-warning text-dark' : 'btn-outline-warning' }}">Chờ
                    xác nhận ({{ $todoMetrics['cho_xac_nhan'] }})</a>
                <a href="{{ $buildTodoUrl(['todo_view' => $todoView, 'todo_status' => 'da_xac_nhan']) }}"
                    class="btn btn-sm {{ $todoStatus === 'da_xac_nhan' ? 'btn-success' : 'btn-outline-success' }}">Đã
                    xác nhận ({{ $todoMetrics['da_xac_nhan'] }})</a>
                <a href="{{ $buildTodoUrl(['todo_view' => $todoView, 'todo_status' => 'overdue']) }}"
                    class="btn btn-sm {{ $todoStatus === 'overdue' ? 'btn-danger' : 'btn-outline-danger' }}">Quá
                    giờ ({{ $todoMetrics['overdue'] }})</a>
                <a href="{{ $buildTodoUrl(['todo_view' => $todoView, 'todo_status' => 'today']) }}"
                    class="btn btn-sm {{ $todoStatus === 'today' ? 'btn-info text-dark' : 'btn-outline-info' }}">Hôm
                    nay ({{ $todoMetrics['today'] }})</a>
            </div>

            @if ($todoView === 'list')
                <div class="card border-0 shadow-sm">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Thời gian</th>
                                    <th>Bất động sản</th>
                                    <th>Sale hẹn</th>
                                    <th>Chủ nhà</th>
                                    <th>Trạng thái</th>
                                    <th class="text-end">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($todoItems as $lh)
                                    <tr>
                                        <td>
                                            <div class="fw-bold text-danger">

                                                @if ($lh->trang_thai != 'cho_xac_nhan')
                                                    <div class="modal fade text-start"
                                                        id="modalHuyNguon{{ $lh->id }}" tabindex="-1">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <form
                                                                action="{{ route('nhanvien.admin.lich-hen.huy', $lh->id) }}"
                                                                method="POST" class="modal-content border-0 shadow">
                                                                @csrf @method('PATCH')
                                                                <div class="modal-header bg-danger text-white">
                                                                    <h5 class="modal-title fw-bold"><i
                                                                            class="fas fa-exclamation-triangle me-2"></i>
                                                                        Báo hủy
                                                                        lịch đột xuất</h5>
                                                                    <button type="button" class="btn-close btn-close-white"
                                                                        data-bs-dismiss="modal"></button>
                                                                </div>
                                                                <div class="modal-body p-4">
                                                                    <label class="form-label fw-bold">Lý do Hủy (Sale sẽ
                                                                        thấy):</label>
                                                                    <textarea name="ly_do" class="form-control" rows="3" placeholder="VD: Chủ nhà đã chốt bán cho khách khác..."
                                                                        required></textarea>
                                                                </div>
                                                                <div class="modal-footer bg-light">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Đóng</button>
                                                                    <button type="submit"
                                                                        class="btn btn-danger fw-bold">Xác nhận
                                                                        Hủy Lịch</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                @endif

                                                @if (in_array($lh->trang_thai, ['cho_xac_nhan', 'da_xac_nhan']))
                                                    <div class="modal fade" id="modalBaoLai{{ $lh->id }}"
                                                        tabindex="-1" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <form
                                                                action="{{ route('nhanvien.admin.lich-hen.bao-lai-gio', $lh->id) }}"
                                                                method="POST" class="modal-content border-0 shadow">
                                                                @csrf @method('PATCH')
                                                                <div class="modal-header bg-warning">
                                                                    <h5 class="modal-title fw-bold text-dark"><i
                                                                            class="fas fa-history me-2"></i> Báo lại giờ
                                                                        cho Sale</h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal"
                                                                        aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body p-4">
                                                                    <div
                                                                        class="alert alert-light border text-dark small mb-4">
                                                                        Chủ nhà đi vắng hoặc không tiện tiếp khách vào
                                                                        lúc
                                                                        <strong>{{ \Carbon\Carbon::parse($lh->thoi_gian_hen)->format('H:i d/m/Y') }}</strong>?
                                                                        Hãy đề xuất một giờ khác.
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label class="form-label fw-bold">Giờ mới chủ
                                                                            nhà hẹn:</label>
                                                                        <input type="datetime-local" name="thoi_gian_hen"
                                                                            class="form-control"
                                                                            value="{{ \Carbon\Carbon::parse($lh->thoi_gian_hen)->format('Y-m-d\TH:i') }}"
                                                                            required>
                                                                    </div>
                                                                    <div class="mb-0">
                                                                        <label class="form-label fw-bold">Lý do / Ghi
                                                                            chú cho Sale:</label>
                                                                        <textarea name="ghi_chu_nguon_hang" class="form-control" rows="3"
                                                                            placeholder="Ví dụ: Chủ đi làm, hẹn lại 4h chiều nay mới có người ở nhà nhé..." required></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer bg-light">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Hủy</button>
                                                                    <button type="submit" class="btn btn-dark fw-bold"><i
                                                                            class="fas fa-paper-plane me-1"></i> Gửi
                                                                        thông báo cho
                                                                        Sale</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                @endif
                                                {{ \Carbon\Carbon::parse($lh->thoi_gian_hen)->format('H:i') }}
                                            </div>
                                            <div class="small text-muted">
                                                {{ \Carbon\Carbon::parse($lh->thoi_gian_hen)->format('d/m/Y') }}</div>
                                            @if ($isDoiGio($lh))
                                                <span class="badge bg-warning text-dark mt-1"><i
                                                        class="fas fa-clock me-1"></i>Đã đổi giờ</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="fw-bold text-primary text-truncate" style="max-width: 260px;"
                                                title="{{ $lh->batDongSan->tieu_de ?? 'Nhà lẻ' }}">
                                                {{ $lh->batDongSan->tieu_de ?? 'Nhà lẻ' }}
                                            </div>
                                            <div class="small text-muted text-truncate" style="max-width: 260px;"
                                                title="{{ optional($lh->batDongSan)->duAn?->ten_du_an ?? 'Dự án chưa rõ' }}">
                                                <i class="fas fa-sitemap text-danger me-1"></i>
                                                {{ optional($lh->batDongSan)->duAn?->ten_du_an ?? 'Dự án chưa rõ' }}
                                            </div>
                                        </td>
                                        <td class="small fw-bold">{{ $lh->nhanVienSale->ho_ten ?? 'N/A' }}</td>
                                        <td>
                                            @if ($lh->batDongSan && $lh->batDongSan->chuNha)
                                                <div class="fw-bold">{{ $lh->batDongSan->chuNha->ho_ten }}</div>
                                                <a href="tel:{{ $lh->batDongSan->chuNha->so_dien_thoai }}"
                                                    class="small text-decoration-none">
                                                    <i class="fas fa-phone-alt me-1 text-success"></i>
                                                    {{ $lh->batDongSan->chuNha->so_dien_thoai }}
                                                </a>
                                            @else
                                                <span class="text-danger small fw-bold">Thiếu dữ liệu chủ nhà</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span
                                                class="badge {{ $lh->trang_thai == 'cho_xac_nhan' ? 'bg-warning text-dark' : 'bg-success' }}">
                                                {{ $lh->trang_thai == 'cho_xac_nhan' ? 'Cần xác nhận' : 'Đã báo Sale' }}
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            @if ($lh->trang_thai == 'cho_xac_nhan')
                                                <div class="d-inline-flex gap-2 flex-wrap justify-content-end">
                                                    <form
                                                        action="{{ route('nhanvien.admin.lich-hen.xac-nhan', $lh->id) }}"
                                                        method="POST" class="m-0">
                                                        @csrf @method('PATCH')
                                                        <button type="submit" class="btn btn-sm btn-confirm-action"
                                                            onclick="return confirm('Bạn đã liên hệ Chủ nhà và chốt lịch đi xem?')">
                                                            <i class="fas fa-check-circle me-1"></i> Xác nhận
                                                        </button>
                                                    </form>
                                                    <button type="button" class="btn btn-sm btn-reschedule-action"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalBaoLai{{ $lh->id }}">
                                                        <i class="fas fa-clock me-1"></i> Đổi giờ
                                                    </button>
                                                </div>
                                            @else
                                                <div class="d-inline-flex gap-2 flex-wrap justify-content-end">
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-warning text-dark fw-bold"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalBaoLai{{ $lh->id }}">
                                                        <i class="fas fa-clock me-1"></i> Dời lịch
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-danger fw-bold"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalHuyNguon{{ $lh->id }}">
                                                        <i class="fas fa-times me-1"></i> Hủy
                                                    </button>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">Không có nhiệm vụ phù
                                            hợp bộ lọc.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="card border-0 shadow-sm">
                    <div class="list-group list-group-flush">
                        @forelse($todoItems as $lh)
                            <div
                                class="list-group-item p-3 p-md-4 transition-hover {{ $lh->trang_thai == 'cho_xac_nhan' ? 'bg-warning bg-opacity-10 border-start border-4 border-warning' : 'border-start border-4 border-success' }}">
                                <div class="row align-items-center g-3">

                                    {{-- Cột 1: Thời gian & Trạng thái --}}
                                    <div class="col-12 col-md-2 text-md-center border-md-end">
                                        <div class="fw-bold text-danger fs-4" style="line-height: 1;">
                                            {{ \Carbon\Carbon::parse($lh->thoi_gian_hen)->format('H:i') }}</div>
                                        <div class="text-muted small mb-2 fw-medium">
                                            {{ \Carbon\Carbon::parse($lh->thoi_gian_hen)->format('d/m/Y') }}</div>
                                        <span
                                            class="badge {{ $lh->trang_thai == 'cho_xac_nhan' ? 'bg-warning text-dark' : 'bg-success' }} w-100 py-2">
                                            {{ $lh->trang_thai == 'cho_xac_nhan' ? 'CẦN XÁC NHẬN' : 'ĐÃ BÁO SALE' }}
                                        </span>
                                        @if ($isDoiGio($lh))
                                            <div class="mt-2">
                                                <span class="badge bg-warning text-dark"><i
                                                        class="fas fa-clock me-1"></i>Đã đổi giờ</span>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Cột 2: Thông tin Bất động sản & Sale --}}
                                    <div class="col-12 col-md-4">
                                        <span class="text-muted" style="font-size: 0.75rem;">Cần dẫn khách xem:</span>
                                        <div class="fw-bold text-primary fs-6 mb-1 text-truncate"
                                            title="{{ $lh->batDongSan->tieu_de ?? 'Nhà lẻ' }}">
                                            {{ $lh->batDongSan->tieu_de ?? 'Nhà lẻ' }}
                                        </div>
                                        <div class="text-muted small mb-2 text-truncate"
                                            title="{{ optional($lh->batDongSan)->duAn?->ten_du_an ?? 'Dự án chưa rõ' }}">
                                            <i class="fas fa-sitemap text-danger me-1"></i>
                                            {{ optional($lh->batDongSan)->duAn?->ten_du_an ?? 'Dự án chưa rõ' }}
                                        </div>
                                        <div class="small">
                                            <span class="text-muted"><i class="fas fa-user-tag me-1"></i> Sale
                                                hẹn:</span>
                                            <strong class="text-dark">{{ $lh->nhanVienSale->ho_ten ?? 'N/A' }}</strong>
                                        </div>
                                    </div>

                                    {{-- Cột 3: Thông tin Chủ nhà --}}
                                    <div class="col-12 col-md-3 border-start-md px-md-3">
                                        <span class="text-muted" style="font-size: 0.75rem;">Thông tin Chủ Nhà:</span>
                                        @if ($lh->batDongSan && $lh->batDongSan->chuNha)
                                            <div class="fw-bold text-dark mb-1">{{ $lh->batDongSan->chuNha->ho_ten }}
                                            </div>
                                            <a href="tel:{{ $lh->batDongSan->chuNha->so_dien_thoai }}"
                                                class="btn btn-sm btn-outline-info text-info-emphasis border-info fw-bold w-100 text-start shadow-sm mt-1">
                                                <i class="fas fa-phone-alt me-2"></i>
                                                {{ $lh->batDongSan->chuNha->so_dien_thoai }}
                                            </a>
                                        @else
                                            <div class="text-danger fw-bold small mt-1"><i
                                                    class="fas fa-exclamation-triangle me-1"></i> Không có data chủ nhà
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Cột 4: Thao tác --}}
                                    <div class="col-12 col-md-3 text-md-end">
                                        @if ($lh->trang_thai == 'cho_xac_nhan')
                                            <div class="d-flex flex-md-column gap-2 justify-content-end">
                                                <form action="{{ route('nhanvien.admin.lich-hen.xac-nhan', $lh->id) }}"
                                                    method="POST" class="w-100 m-0">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" class="btn btn-confirm-action w-100"
                                                        onclick="return confirm('Bạn đã liên hệ Chủ nhà và chốt lịch đi xem?')">
                                                        <i class="fas fa-check-circle me-1"></i> Xác nhận ngay
                                                    </button>
                                                </form>
                                                <button type="button" class="btn btn-reschedule-action w-100"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalBaoLai{{ $lh->id }}">
                                                    <i class="fas fa-clock me-1"></i> ĐỔI GIỜ
                                                </button>
                                            </div>
                                        @else
                                            <div class="d-flex flex-column align-items-md-end">
                                                <div class="text-success fw-bold mb-2"><i
                                                        class="fas fa-check-circle me-1"></i> Đã phản hồi Sale</div>
                                                <div class="d-flex gap-2">
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-warning text-dark fw-bold shadow-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalBaoLai{{ $lh->id }}">
                                                        <i class="fas fa-clock"></i> Dời lịch
                                                    </button>
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-danger fw-bold shadow-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalHuyNguon{{ $lh->id }}">
                                                        <i class="fas fa-times"></i> Hủy
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="modal fade text-start" id="modalHuyNguon{{ $lh->id }}"
                                                tabindex="-1">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <form action="{{ route('nhanvien.admin.lich-hen.huy', $lh->id) }}"
                                                        method="POST" class="modal-content border-0 shadow">
                                                        @csrf @method('PATCH')
                                                        <div class="modal-header bg-danger text-white">
                                                            <h5 class="modal-title fw-bold"><i
                                                                    class="fas fa-exclamation-triangle me-2"></i> Báo
                                                                hủy
                                                                lịch đột xuất</h5>
                                                            <button type="button" class="btn-close btn-close-white"
                                                                data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body p-4">
                                                            <label class="form-label fw-bold">Lý do Hủy (Sale sẽ
                                                                thấy):</label>
                                                            <textarea name="ly_do" class="form-control" rows="3" placeholder="VD: Chủ nhà đã chốt bán cho khách khác..."
                                                                required></textarea>
                                                        </div>
                                                        <div class="modal-footer bg-light">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Đóng</button>
                                                            <button type="submit" class="btn btn-danger fw-bold">Xác
                                                                nhận
                                                                Hủy Lịch</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                </div>
                            </div>

                            {{-- MODAL ĐỔI GIỜ --}}
                            @if (in_array($lh->trang_thai, ['cho_xac_nhan', 'da_xac_nhan']))
                                <div class="modal fade" id="modalBaoLai{{ $lh->id }}" tabindex="-1"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <form action="{{ route('nhanvien.admin.lich-hen.bao-lai-gio', $lh->id) }}"
                                            method="POST" class="modal-content border-0 shadow">
                                            @csrf @method('PATCH')
                                            <div class="modal-header bg-warning">
                                                <h5 class="modal-title fw-bold text-dark"><i
                                                        class="fas fa-history me-2"></i> Báo lại giờ cho Sale</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body p-4">
                                                <div class="alert alert-light border text-dark small mb-4">
                                                    Chủ nhà đi vắng hoặc không tiện tiếp khách vào lúc
                                                    <strong>{{ \Carbon\Carbon::parse($lh->thoi_gian_hen)->format('H:i d/m/Y') }}</strong>?
                                                    Hãy đề xuất một giờ khác.
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Giờ mới chủ nhà hẹn:</label>
                                                    <input type="datetime-local" name="thoi_gian_hen"
                                                        class="form-control"
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
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Hủy</button>
                                                <button type="submit" class="btn btn-dark fw-bold"><i
                                                        class="fas fa-paper-plane me-1"></i> Gửi thông báo cho
                                                    Sale</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <div class="text-center py-5">
                                <i class="fas fa-check-double text-success mb-3 opacity-25" style="font-size: 4rem;"></i>
                                <h5 class="text-muted fw-bold">Không có nhiệm vụ phù hợp bộ lọc.</h5>
                            </div>
                        @endforelse
                    </div>
                </div>
            @endif
        </div>

        <div class="tab-pane fade {{ $isListTab ? 'show active' : '' }}" id="tab-list" role="tabpanel">
            {{-- BỘ LỌC --}}
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body p-3">
                    <form method="GET" class="row g-2 align-items-center">
                        <input type="hidden" name="tab" value="list">
                        <div class="col-12 col-md-3">
                            <input type="text" name="tim_kiem" class="form-control"
                                value="{{ request('tim_kiem') }}" placeholder="🔍 Tìm khách hàng, SĐT...">
                        </div>
                        <div class="col-6 col-md-2">
                            <select name="trang_thai" class="form-select">
                                <option value="">-- Trạng thái --</option>
                                <option value="cho_xac_nhan"
                                    {{ request('trang_thai') == 'cho_xac_nhan' ? 'selected' : '' }}>Chờ xác nhận</option>
                                <option value="da_xac_nhan"
                                    {{ request('trang_thai') == 'da_xac_nhan' ? 'selected' : '' }}>Đã xác nhận</option>
                                <option value="hoan_thanh" {{ request('trang_thai') == 'hoan_thanh' ? 'selected' : '' }}>
                                    Hoàn thành</option>
                                <option value="tu_choi" {{ request('trang_thai') == 'tu_choi' ? 'selected' : '' }}>Từ chối
                                </option>
                                <option value="huy" {{ request('trang_thai') == 'huy' ? 'selected' : '' }}>Đã hủy
                                </option>
                            </select>
                        </div>
                        <div class="col-12 col-md-4 d-flex gap-1">
                            <input type="date" name="tu_ngay" class="form-control w-50"
                                value="{{ request('tu_ngay') }}">
                            <input type="date" name="den_ngay" class="form-control w-50"
                                value="{{ request('den_ngay') }}">
                        </div>
                        <div class="col-12 col-md-3 d-flex gap-2">
                            <button type="submit" class="btn btn-navy grow"><i class="fas fa-search"></i>
                                Lọc</button>
                            @if (request()->anyFilled(['tim_kiem', 'trang_thai', 'tu_ngay', 'den_ngay']))
                                <a href="{{ route('nhanvien.admin.lich-hen.index', ['tab' => 'list']) }}"
                                    class="btn btn-light border"><i class="fas fa-undo"></i></a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            {{-- BẢNG DATA --}}
            <div class="card border-0 shadow-sm">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Thời gian</th>
                                <th>Khách hàng</th>
                                <th>Bất động sản</th>
                                <th>Phụ trách</th>
                                <th>Trạng thái</th>
                                <th class="text-end">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (isset($lichHensList))
                                @forelse($lichHensList as $lh)
                                    @php
                                        $bgMap = [
                                            'cho_xac_nhan' => 'info',
                                            'da_xac_nhan' => 'success',
                                            'hoan_thanh' => 'secondary',
                                            'tu_choi' => 'danger',
                                            'huy' => 'danger',
                                        ];
                                        $lblMap = [
                                            'cho_xac_nhan' => 'Chờ Nguồn XN',
                                            'da_xac_nhan' => 'Đã chốt giờ',
                                            'hoan_thanh' => 'Hoàn thành',
                                            'tu_choi' => 'Bị từ chối',
                                            'huy' => 'Đã hủy',
                                        ];
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="fw-bold text-danger">
                                                {{ \Carbon\Carbon::parse($lh->thoi_gian_hen)->format('H:i') }}</div>
                                            <div class="small text-muted">
                                                {{ \Carbon\Carbon::parse($lh->thoi_gian_hen)->format('d/m/Y') }}</div>
                                            @if ($isDoiGio($lh))
                                                <span class="badge bg-warning text-dark mt-1"><i
                                                        class="fas fa-clock me-1"></i>Đã đổi giờ</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="fw-bold">{{ $lh->ten_khach_hang }}</div>
                                            <div class="small"><i
                                                    class="fas fa-phone text-success me-1"></i>{{ $lh->sdt_khach_hang }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-medium text-truncate" style="max-width: 250px;">
                                                {{ $lh->batDongSan->tieu_de ?? 'Nhà lẻ' }}</div>
                                        </td>
                                        <td class="small">
                                            <div><span class="text-muted">Sale:</span> <strong
                                                    class="text-dark">{{ $lh->nhanVienSale->ho_ten ?? '---' }}</strong>
                                            </div>
                                        </td>
                                        <td><span
                                                class="badge bg-{{ $bgMap[$lh->trang_thai] ?? 'dark' }}">{{ $lblMap[$lh->trang_thai] ?? $lh->trang_thai }}</span>
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('nhanvien.admin.lich-hen.show', $lh->id) }}"
                                                class="btn btn-sm btn-outline-primary rounded-pill px-3">Chi tiết <i
                                                    class="fas fa-arrow-right ms-1"></i></a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">Không có lịch sử.</td>
                                    </tr>
                                @endforelse
                            @endif
                        </tbody>
                    </table>
                </div>
                @if (isset($lichHensList) && $lichHensList->hasPages())
                    <div class="card-footer bg-white border-top p-3 d-flex justify-content-end">
                        {{ $lichHensList->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
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

        .nav-tabs .nav-link {
            font-weight: bold;
            color: #6c757d;
            border: none;
            border-bottom: 3px solid transparent;
            padding: 10px 20px;
        }

        .nav-tabs .nav-link.active {
            color: var(--bs-success);
            border-bottom-color: var(--bs-success);
            background: transparent;
        }

        .btn-confirm-action {
            border: 0;
            font-weight: 700;
            color: #fff;
            background: linear-gradient(135deg, #0f9d58, #14b866);
            box-shadow: 0 6px 14px rgba(15, 157, 88, 0.25);
        }

        .btn-confirm-action:hover,
        .btn-confirm-action:focus {
            color: #fff;
            background: linear-gradient(135deg, #0d8b4d, #11a75b);
            box-shadow: 0 8px 18px rgba(15, 157, 88, 0.35);
        }

        .btn-reschedule-action {
            border: 0;
            font-weight: 700;
            color: #2b2b2b;
            background: linear-gradient(135deg, #ffe08a, #ffcc4d);
            box-shadow: 0 6px 14px rgba(255, 193, 7, 0.28);
        }

        .btn-reschedule-action:hover,
        .btn-reschedule-action:focus {
            color: #222;
            background: linear-gradient(135deg, #ffd766, #ffbf1f);
            box-shadow: 0 8px 18px rgba(255, 193, 7, 0.35);
        }

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
