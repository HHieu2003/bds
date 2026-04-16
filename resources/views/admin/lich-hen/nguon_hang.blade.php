@extends('admin.layouts.master')
@section('title', 'Nhiệm vụ Nguồn hàng')

@section('content')
    <div class="mb-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
            <h1 class="page-header-title text-success mb-1"><i class="fas fa-key me-2"></i> Trạm Điều Phối Nguồn Hàng</h1>
            <p class="text-muted mb-0">Bộ phận Nguồn Hàng: Liên hệ chủ nhà, chốt giờ mở cửa, xử lý yêu cầu từ Sale.</p>
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
        $todoView = request('todo_view', 'card');
        $todoStatus = request('todo_status', 'all');

        $isDoiGio = static function ($lh) {
            return str_contains((string) $lh->ghi_chu_sale, '[DOI_GIO]') ||
                str_contains((string) $lh->ghi_chu_nguon_hang, '[DOI_GIO]');
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
            'today' => $todoCollection
                ->filter(fn($lh) => \Carbon\Carbon::parse($lh->thoi_gian_hen)->toDateString() === $todayDate)
                ->count(),
        ];

        $todoItems = match ($todoStatus) {
            'cho_xac_nhan' => $todoCollection->where('trang_thai', 'cho_xac_nhan')->values(),
            'da_xac_nhan' => $todoCollection->where('trang_thai', 'da_xac_nhan')->values(),
            'today' => $todoCollection
                ->filter(fn($lh) => \Carbon\Carbon::parse($lh->thoi_gian_hen)->toDateString() === $todayDate)
                ->values(),
            default => $todoCollection->values(),
        };

        $buildTodoUrl = fn($params = []) => route(
            'nhanvien.admin.lich-hen.index',
            array_merge($queryBase, ['tab' => 'todo'], $params),
        );

        $tabTodoUrl = route('nhanvien.admin.lich-hen.index', array_merge($queryBase, ['tab' => 'todo']));
        $tabListUrl = route('nhanvien.admin.lich-hen.index', array_merge($queryBase, ['tab' => 'list']));
    @endphp

    <ul class="nav nav-tabs mb-4 border-bottom-2" role="tablist">
        <li class="nav-item">
            <a href="{{ $tabTodoUrl }}"
                class="nav-link px-4 {{ !$isListTab ? 'active fw-bold text-success border-success border-bottom-0' : 'text-muted' }}">
                <i class="fas fa-bell me-1"></i> Cần Xử Lý <span
                    class="badge bg-{{ !$isListTab ? 'success' : 'secondary' }} ms-1">{{ $todoMetrics['all'] }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ $tabListUrl }}"
                class="nav-link px-4 {{ $isListTab ? 'active fw-bold text-success border-success border-bottom-0' : 'text-muted' }}">
                <i class="fas fa-history me-1"></i> Lịch Sử Nhiệm Vụ
            </a>
        </li>
    </ul>

    <div class="tab-content">
        {{-- ===================== TAB CẦN XỬ LÝ ===================== --}}
        <div class="tab-pane fade {{ !$isListTab ? 'show active' : '' }}" id="tab-todo">

            {{-- BỘ LỌC DẠNG METRICS CARDS --}}
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <a href="{{ $buildTodoUrl(['todo_view' => $todoView, 'todo_status' => 'all']) }}"
                        class="text-decoration-none d-block h-100">
                        <div
                            class="card border-0 shadow-sm h-100 metric-card {{ $todoStatus === 'all' ? 'bg-secondary text-white ring-active' : 'bg-light text-dark' }}">
                            <div class="card-body p-3 d-flex align-items-center gap-3">
                                <div class="fs-2 opacity-50"><i class="fas fa-layer-group"></i></div>
                                <div>
                                    <div class="small fw-medium mb-1">Tổng việc</div>
                                    <div class="fs-4 fw-bold lh-1">{{ $todoMetrics['all'] }}</div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-6 col-md-3">
                    <a href="{{ $buildTodoUrl(['todo_view' => $todoView, 'todo_status' => 'cho_xac_nhan']) }}"
                        class="text-decoration-none d-block h-100">
                        <div
                            class="card border-0 shadow-sm h-100 metric-card {{ $todoStatus === 'cho_xac_nhan' ? 'bg-info text-dark ring-active' : 'bg-info bg-opacity-10 text-dark' }}">
                            <div class="card-body p-3 d-flex align-items-center gap-3">
                                <div class="fs-2 opacity-50"><i class="fas fa-phone-volume"></i></div>
                                <div>
                                    <div class="small fw-medium mb-1">Cần gọi Chủ nhà</div>
                                    <div class="fs-4 fw-bold lh-1">{{ $todoMetrics['cho_xac_nhan'] }}</div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-6 col-md-3">
                    <a href="{{ $buildTodoUrl(['todo_view' => $todoView, 'todo_status' => 'da_xac_nhan']) }}"
                        class="text-decoration-none d-block h-100">
                        <div
                            class="card border-0 shadow-sm h-100 metric-card {{ $todoStatus === 'da_xac_nhan' ? 'bg-success text-white ring-active' : 'bg-success bg-opacity-10 text-success' }}">
                            <div class="card-body p-3 d-flex align-items-center gap-3">
                                <div class="fs-2 opacity-50"><i class="fas fa-check-circle"></i></div>
                                <div>
                                    <div class="small fw-medium mb-1">Đã chốt mở cửa</div>
                                    <div class="fs-4 fw-bold lh-1">{{ $todoMetrics['da_xac_nhan'] }}</div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-6 col-md-3">
                    <a href="{{ $buildTodoUrl(['todo_view' => $todoView, 'todo_status' => 'today']) }}"
                        class="text-decoration-none d-block h-100">
                        <div
                            class="card border-0 shadow-sm h-100 metric-card {{ $todoStatus === 'today' ? 'bg-warning text-dark ring-active' : 'bg-warning bg-opacity-10 text-dark' }}">
                            <div class="card-body p-3 d-flex align-items-center gap-3">
                                <div class="fs-2 opacity-50"><i class="fas fa-calendar-day"></i></div>
                                <div>
                                    <div class="small fw-medium mb-1">Lịch hôm nay</div>
                                    <div class="fs-4 fw-bold lh-1">{{ $todoMetrics['today'] }}</div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0 text-dark fw-bold">Danh sách công việc</h5>
                <div class="btn-group bg-white shadow-sm rounded-3 p-1">
                    <a href="{{ $todoCardUrl }}"
                        class="btn btn-sm rounded-2 {{ $todoView === 'card' ? 'btn-success fw-bold' : 'btn-light border-0 text-muted' }}"><i
                            class="fas fa-grip me-1"></i> Dạng Thẻ</a>
                    <a href="{{ $todoListUrl }}"
                        class="btn btn-sm rounded-2 {{ $todoView === 'list' ? 'btn-success fw-bold' : 'btn-light border-0 text-muted' }}"><i
                            class="fas fa-table me-1"></i> Dạng Bảng</a>
                </div>
            </div>

            @if ($todoItems->isEmpty())
                <div class="text-center py-5 bg-white rounded-3 shadow-sm border">
                    <i class="fas fa-coffee text-success mb-3 opacity-25" style="font-size: 5rem;"></i>
                    <h4 class="text-muted fw-bold">Trống lịch!</h4>
                    <p class="text-muted">Không có nhiệm vụ nào đang chờ xử lý trong bộ lọc này.</p>
                </div>
            @else
                @if ($todoView === 'list')
                    {{-- DẠNG BẢNG (TABLE) --}}
                    <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="py-3 px-3">Thời gian</th>
                                        <th class="py-3">Bất động sản</th>
                                        <th class="py-3">Chủ nhà</th>
                                        <th class="py-3">Ghi chú từ Sale</th>
                                        <th class="py-3 text-end px-4">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($todoItems as $lh)
                                        <tr>
                                            <td class="px-3" style="width: 140px;">
                                                <div class="fw-bold fs-5 text-danger">
                                                    {{ \Carbon\Carbon::parse($lh->thoi_gian_hen)->format('H:i') }}</div>
                                                <div class="small text-muted fw-medium">
                                                    {{ \Carbon\Carbon::parse($lh->thoi_gian_hen)->format('d/m/Y') }}</div>
                                                @if ($isDoiGio($lh))
                                                    <span class="badge bg-warning text-dark mt-1"><i
                                                            class="fas fa-sync-alt me-1"></i>Đã dời</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="fw-bold text-primary mb-1">
                                                    {{ $lh->batDongSan->tieu_de ?? 'Nhà lẻ / BĐS Ngoài' }}</div>
                                                <div class="small text-muted"><i
                                                        class="fas fa-user-tie text-secondary me-1"></i>Sale hẹn:
                                                    <strong>{{ $lh->nhanVienSale->ho_ten ?? '---' }}</strong></div>
                                            </td>
                                            <td>
                                                @if ($lh->batDongSan && $lh->batDongSan->chuNha)
                                                    <div class="fw-bold text-dark">{{ $lh->batDongSan->chuNha->ho_ten }}
                                                    </div>
                                                    <a href="tel:{{ $lh->batDongSan->chuNha->so_dien_thoai }}"
                                                        class="small fw-bold text-success text-decoration-none">
                                                        <i
                                                            class="fas fa-phone-alt me-1"></i>{{ $lh->batDongSan->chuNha->so_dien_thoai }}
                                                    </a>
                                                @else
                                                    <span class="badge bg-danger">Chưa có data chủ</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="small bg-light p-2 rounded text-muted"
                                                    style="max-width: 250px;">
                                                    {!! $lh->ghi_chu_sale ? nl2br(e(Str::limit($lh->ghi_chu_sale, 80))) : '<i>Không có ghi chú</i>' !!}
                                                </div>
                                            </td>
                                            <td class="text-end px-3">
                                                @if ($lh->trang_thai == 'cho_xac_nhan')
                                                    <button class="btn btn-sm btn-success fw-bold me-1 mb-1"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalXacNhan{{ $lh->id }}"><i
                                                            class="fas fa-check"></i> Có chìa</button>
                                                    <button class="btn btn-sm btn-warning fw-bold text-dark me-1 mb-1"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalBaoLai{{ $lh->id }}"><i
                                                            class="fas fa-clock"></i> Dời</button>
                                                    <button class="btn btn-sm btn-danger fw-bold mb-1"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalTuChoi{{ $lh->id }}"><i
                                                            class="fas fa-times"></i> Từ chối</button>
                                                @else
                                                    <button class="btn btn-sm btn-light border fw-bold text-dark me-1"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalBaoLai{{ $lh->id }}"><i
                                                            class="fas fa-clock text-warning"></i> Dời lịch</button>
                                                    <button class="btn btn-sm btn-light border fw-bold text-danger"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalHuyNguon{{ $lh->id }}"><i
                                                            class="fas fa-ban"></i> Hủy gấp</button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    {{-- DẠNG THẺ (CARDS) --}}
                    <div class="row g-3">
                        @foreach ($todoItems as $lh)
                            <div class="col-12 col-xxl-6">
                                <div
                                    class="card border-0 shadow-sm h-100 transition-hover {{ $lh->trang_thai == 'cho_xac_nhan' ? 'border-start border-4 border-info' : 'border-start border-4 border-success' }}">
                                    <div class="card-body p-3 p-md-4">
                                        <div class="d-flex flex-column flex-md-row gap-3">

                                            {{-- Block Thời Gian --}}
                                            <div class="text-center bg-light rounded p-3" style="min-width: 130px;">
                                                <div class="text-danger display-6 fw-bold" style="line-height: 1;">
                                                    {{ \Carbon\Carbon::parse($lh->thoi_gian_hen)->format('H:i') }}</div>
                                                <div class="small fw-bold text-muted mt-2">
                                                    {{ \Carbon\Carbon::parse($lh->thoi_gian_hen)->format('d/m/Y') }}</div>
                                                <hr class="my-2 border-secondary">
                                                <span
                                                    class="badge w-100 py-2 {{ $lh->trang_thai == 'cho_xac_nhan' ? 'bg-info text-dark' : 'bg-success' }}">
                                                    {{ $lh->trang_thai == 'cho_xac_nhan' ? 'CẦN CHỐT CHỦ' : 'ĐÃ BÁO SALE' }}
                                                </span>
                                                @if ($isDoiGio($lh))
                                                    <div class="mt-2 text-warning fw-bold small"><i
                                                            class="fas fa-sync-alt me-1"></i>Đã dời</div>
                                                @endif
                                            </div>

                                            {{-- Block Thông tin --}}
                                            <div class="flex-fill">
                                                <div class="fw-bold text-primary fs-5 mb-1">
                                                    {{ $lh->batDongSan->tieu_de ?? 'Bất động sản ngoài hệ thống' }}</div>
                                                <div class="text-muted small mb-2"><i
                                                        class="fas fa-map-marker-alt text-danger me-1"></i>{{ optional($lh->batDongSan)->khuVuc?->ten_khu_vuc ?? '---' }}
                                                </div>

                                                <div class="row g-2 mb-2">
                                                    <div class="col-sm-6">
                                                        <div class="p-2 border rounded bg-white">
                                                            <div class="small text-muted"><i
                                                                    class="fas fa-user-tie me-1"></i>Sale đặt lịch:</div>
                                                            <div class="fw-bold">{{ $lh->nhanVienSale->ho_ten ?? '---' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div
                                                            class="p-2 border rounded bg-success bg-opacity-10 border-success">
                                                            <div class="small text-success fw-bold"><i
                                                                    class="fas fa-id-card me-1"></i>Chủ nhà:</div>
                                                            <div class="fw-bold text-dark">
                                                                {{ optional($lh->batDongSan?->chuNha)->ho_ten ?? 'Chưa rõ' }}
                                                            </div>
                                                            @if ($lh->batDongSan?->chuNha)
                                                                <a href="tel:{{ $lh->batDongSan->chuNha->so_dien_thoai }}"
                                                                    class="small fw-bold text-success text-decoration-none">
                                                                    {{ $lh->batDongSan->chuNha->so_dien_thoai }}
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                                @if ($lh->ghi_chu_sale)
                                                    <div
                                                        class="small p-2 bg-warning bg-opacity-10 border border-warning rounded text-dark">
                                                        <strong><i class="fas fa-comment-dots me-1"></i>Sale nhắn:</strong>
                                                        {{ $lh->ghi_chu_sale }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Block Thao Tác (Footer) --}}
                                    <div class="card-footer bg-white border-top p-3">
                                        @if ($lh->trang_thai == 'cho_xac_nhan')
                                            <div class="d-flex gap-2">
                                                <button class="btn btn-success fw-bold flex-fill" data-bs-toggle="modal"
                                                    data-bs-target="#modalXacNhan{{ $lh->id }}"><i
                                                        class="fas fa-key me-1"></i> Có chìa khóa</button>
                                                <button class="btn btn-warning fw-bold text-dark flex-fill"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalBaoLai{{ $lh->id }}"><i
                                                        class="fas fa-clock me-1"></i> Dời lịch</button>
                                                <button class="btn btn-outline-danger fw-bold flex-fill"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalTuChoi{{ $lh->id }}"><i
                                                        class="fas fa-times me-1"></i> Từ chối</button>
                                            </div>
                                        @else
                                            <div class="d-flex gap-2 justify-content-end">
                                                <button class="btn btn-light border fw-bold text-dark px-4"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalBaoLai{{ $lh->id }}"><i
                                                        class="fas fa-clock text-warning me-1"></i> Dời lịch</button>
                                                <button class="btn btn-light border fw-bold text-danger px-4"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalHuyNguon{{ $lh->id }}"><i
                                                        class="fas fa-ban me-1"></i> Hủy lịch đột xuất</button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            @endif
        </div>

        {{-- ===================== TAB LỊCH SỬ TỔNG HỢP (Giữ nguyên gọn gàng) ===================== --}}
        <div class="tab-pane fade {{ $isListTab ? 'show active' : '' }}" id="tab-list">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white p-3">
                    <form method="GET" class="row g-2 align-items-center">
                        <input type="hidden" name="tab" value="list">
                        <div class="col-md-3"><input type="text" name="tim_kiem" class="form-control"
                                value="{{ request('tim_kiem') }}" placeholder="Tìm kiếm SĐT, BĐS..."></div>
                        <div class="col-md-3">
                            <select name="trang_thai" class="form-select">
                                <option value="">-- Mọi trạng thái --</option>
                                <option value="da_xac_nhan"
                                    {{ request('trang_thai') == 'da_xac_nhan' ? 'selected' : '' }}>Đã chốt giờ</option>
                                <option value="hoan_thanh" {{ request('trang_thai') == 'hoan_thanh' ? 'selected' : '' }}>
                                    Hoàn thành xem</option>
                                <option value="tu_choi" {{ request('trang_thai') == 'tu_choi' ? 'selected' : '' }}>Bị từ
                                    chối</option>
                                <option value="huy" {{ request('trang_thai') == 'huy' ? 'selected' : '' }}>Đã hủy
                                </option>
                            </select>
                        </div>
                        <div class="col-md-4 d-flex gap-2">
                            <input type="date" name="tu_ngay" class="form-control" value="{{ request('tu_ngay') }}">
                            <input type="date" name="den_ngay" class="form-control"
                                value="{{ request('den_ngay') }}">
                        </div>
                        <div class="col-md-2 d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-100">Lọc</button>
                            <a href="{{ route('nhanvien.admin.lich-hen.index', ['tab' => 'list']) }}"
                                class="btn btn-light border"><i class="fas fa-undo"></i></a>
                        </div>
                    </form>
                </div>
                <div class="table-responsive">
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

    {{-- ===================== KHU VỰC MODALS CHO CÁC THAO TÁC ===================== --}}
    <div id="modals-container">
        @foreach ($todoItems as $lh)
            {{-- 1. MODAL XÁC NHẬN --}}
            @if ($lh->trang_thai == 'cho_xac_nhan')
                <div class="modal fade" id="modalXacNhan{{ $lh->id }}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <form action="{{ route('nhanvien.admin.lich-hen.xac-nhan', $lh->id) }}" method="POST"
                            class="modal-content border-0 shadow-lg">
                            @csrf @method('PATCH')
                            <div class="modal-header bg-success text-white">
                                <h5 class="modal-title fw-bold"><i class="fas fa-check-circle me-2"></i> Báo Sale Dẫn
                                    Khách Qua</h5>
                                <button type="button" class="btn-close btn-close-white"
                                    data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body p-4 text-center">
                                <i class="fas fa-key text-success mb-3" style="font-size: 3rem;"></i>
                                <h5>Chủ nhà đã đồng ý mở cửa?</h5>
                                <p class="text-muted">Bạn xác nhận có chìa khóa hoặc đã báo chủ nhà lúc
                                    <strong>{{ \Carbon\Carbon::parse($lh->thoi_gian_hen)->format('H:i - d/m/Y') }}</strong>?
                                </p>
                            </div>
                            <div class="modal-footer bg-light">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                <button type="submit" class="btn btn-success fw-bold">Chắc chắn, báo cho Sale</button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- NEW: MODAL TỪ CHỐI (Dành riêng cho trạng thái chờ xác nhận) --}}
                <div class="modal fade" id="modalTuChoi{{ $lh->id }}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <form action="{{ route('nhanvien.admin.lich-hen.tu-choi', $lh->id) }}" method="POST"
                            class="modal-content border-0 shadow-lg">
                            @csrf @method('PATCH')
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title fw-bold"><i class="fas fa-times-circle me-2"></i> Từ Chối Lịch Xem
                                    Nhà</h5>
                                <button type="button" class="btn-close btn-close-white"
                                    data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body p-4">
                                <div class="alert alert-danger bg-opacity-10 small mb-3">
                                    Sử dụng khi: Chủ nhà không hợp tác, nhà đã bán, hoặc không thể lấy chìa khóa. Sale sẽ
                                    nhận được thông báo để báo lại khách.
                                </div>
                                <label class="form-label fw-bold">Lý do từ chối (Bắt buộc):</label>
                                <textarea name="ly_do_tu_choi" class="form-control" rows="3" required
                                    placeholder="VD: Chủ nhà báo đã nhận cọc người khác..."></textarea>
                            </div>
                            <div class="modal-footer bg-light">
                                <button type="submit" class="btn btn-danger fw-bold w-100">Xác nhận Từ chối</button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

            {{-- 2. MODAL DỜI GIỜ --}}
            @if (in_array($lh->trang_thai, ['cho_xac_nhan', 'da_xac_nhan']))
                <div class="modal fade" id="modalBaoLai{{ $lh->id }}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <form action="{{ route('nhanvien.admin.lich-hen.bao-lai-gio', $lh->id) }}" method="POST"
                            class="modal-content border-0 shadow-lg">
                            @csrf @method('PATCH')
                            <div class="modal-header bg-warning">
                                <h5 class="modal-title fw-bold text-dark"><i class="fas fa-clock me-2"></i> Đề Xuất Dời
                                    Giờ</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body p-4">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Chủ nhà muốn hẹn lúc:</label>
                                    <input type="datetime-local" name="thoi_gian_hen"
                                        class="form-control form-control-lg"
                                        value="{{ \Carbon\Carbon::parse($lh->thoi_gian_hen)->format('Y-m-d\TH:i') }}"
                                        required>
                                </div>
                                <div class="mb-0">
                                    <label class="form-label fw-bold">Lời nhắn cho Sale:</label>
                                    <textarea name="ghi_chu_nguon_hang" class="form-control" rows="2" placeholder="VD: Cô chủ đi làm 5h mới về..."
                                        required></textarea>
                                </div>
                            </div>
                            <div class="modal-footer bg-light">
                                <button type="submit" class="btn btn-dark fw-bold w-100">Báo lại cho Sale</button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

            {{-- 3. MODAL HỦY ĐỘT XUẤT (Cho trạng thái đã xác nhận) --}}
            @if ($lh->trang_thai == 'da_xac_nhan')
                <div class="modal fade" id="modalHuyNguon{{ $lh->id }}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <form action="{{ route('nhanvien.admin.lich-hen.huy', $lh->id) }}" method="POST"
                            class="modal-content border-0 shadow-lg">
                            @csrf @method('PATCH')
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title fw-bold"><i class="fas fa-ban me-2"></i> Báo Hủy Lịch Đột Xuất</h5>
                                <button type="button" class="btn-close btn-close-white"
                                    data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body p-4">
                                <div class="alert alert-danger bg-opacity-10 small mb-3">
                                    Hành động này sẽ <strong>Hủy bỏ hoàn toàn</strong> lịch xem. Chỉ dùng khi có sự cố phát
                                    sinh sau khi đã chốt giờ.
                                </div>
                                <label class="form-label fw-bold">Lý do Hủy (Bắt buộc):</label>
                                <textarea name="ly_do" class="form-control" rows="3" required
                                    placeholder="VD: Chủ nhà có việc đột xuất đóng cửa đi vắng..."></textarea>
                            </div>
                            <div class="modal-footer bg-light">
                                <button type="submit" class="btn btn-danger fw-bold w-100">Xác nhận Hủy Lịch</button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
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

        .metric-card {
            border: 2px solid transparent;
            transition: all 0.2s ease-in-out;
            cursor: pointer;
        }

        .metric-card:hover {
            transform: translateY(-3px);
        }

        .metric-card.ring-active {
            border-color: currentColor;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
        }

        .nav-tabs .nav-link {
            font-size: 1.05rem;
            color: #6c757d;
            border: none;
            border-bottom: 3px solid transparent;
            padding: 12px 20px;
            transition: all 0.2s ease;
        }

        .nav-tabs .nav-link:hover {
            color: var(--bs-success);
            border-bottom-color: rgba(25, 135, 84, 0.3);
        }

        .nav-tabs .nav-link.active {
            color: var(--bs-success);
            border-bottom-color: var(--bs-success);
            background: transparent;
        }
    </style>
@endpush
