@extends('admin.layouts.master')
@section('title', 'Nhiệm vụ Xác nhận Lịch hẹn')

@push('styles')
    <style>
        /* CSS cho CRM Tabs */
        .nav-tabs .nav-link {
            font-weight: 600;
            color: #6c757d;
            background-color: #f8f9fa;
            border: 1px solid transparent;
            padding: 12px 20px;
            transition: all 0.2s ease-in-out;
        }

        .nav-tabs .nav-link:hover {
            color: var(--bs-success);
            background-color: #e9ecef;
        }

        .nav-tabs .nav-link.active {
            color: var(--bs-success);
            font-weight: 700;
            background-color: #fff;
            border-top: 3px solid var(--bs-success) !important;
            border-bottom: 0 !important;
            box-shadow: 0 -3px 5px rgba(0, 0, 0, 0.02);
        }
    </style>
@endpush

@section('content')
    <div class="mb-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
            <h1 class="page-header-title text-success mb-1"><i class="fas fa-clipboard-list me-2"></i> Trạm Điều Phối Nguồn
                Hàng</h1>
            @php
                $tongLich =
                    ($stats['cho_xac_nhan'] ?? 0) +
                    ($stats['cho_sale_xac_nhan_doi_gio'] ?? 0) +
                    ($stats['da_xac_nhan'] ?? 0) +
                    ($stats['hoan_thanh'] ?? 0) +
                    ($stats['tu_choi'] ?? 0) +
                    ($stats['huy'] ?? 0);
            @endphp
            {{-- THỐNG KÊ NHANH --}}
            <div style="font-size:.78rem;color:var(--text-sub);" class="mt-1">
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <span><strong>{{ number_format($tongLich) }}</strong> lịch tổng</span>
                    <span
                        style="width:4px;height:4px;border-radius:50%;background:var(--text-muted);display:inline-block"></span>
                    <span style="color:#0dcaf0"><strong>{{ number_format($stats['cho_xac_nhan'] ?? 0) }}</strong> 🗝️ chờ
                        chốt</span>
                    <span
                        style="width:4px;height:4px;border-radius:50%;background:var(--text-muted);display:inline-block"></span>
                    <span
                        style="color:#ffc107"><strong>{{ number_format($stats['cho_sale_xac_nhan_doi_gio'] ?? 0) }}</strong>
                        ⏳ xin dời</span>
                    <span
                        style="width:4px;height:4px;border-radius:50%;background:var(--text-muted);display:inline-block"></span>
                    <span style="color:#198754"><strong>{{ number_format($stats['da_xac_nhan'] ?? 0) }}</strong> ✅ đã báo
                        chìa</span>
                    <span
                        style="width:4px;height:4px;border-radius:50%;background:var(--text-muted);display:inline-block"></span>
                    <span style="color:#6c757d"><strong>{{ number_format($stats['hoan_thanh'] ?? 0) }}</strong> 🏆 hoàn
                        thành</span>
                </div>
            </div>
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
        $trangThaiLabels = [
            'moi_dat' => 'Mới đặt',
            'sale_da_nhan' => 'Sale đang xác nhận',
            'cho_xac_nhan' => 'Chờ Nguồn chốt',
            'cho_sale_xac_nhan_doi_gio' => 'Chờ Sale chốt dời giờ',
            'da_xac_nhan' => 'Đã báo có chìa',
            'hoan_thanh' => 'Hoàn thành',
            'tu_choi' => 'Từ chối',
            'huy' => 'Đã hủy',
        ];

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

        $isDoiGio = static function ($lh) {
            return $lh->trang_thai === 'cho_sale_xac_nhan_doi_gio';
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

        $tabTodoUrl = route('nhanvien.admin.lich-hen.index', array_merge($queryBase, ['tab' => 'todo']));
        $tabListUrl = route('nhanvien.admin.lich-hen.index', array_merge($queryBase, ['tab' => 'list']));
        if (isset($adminMode) && $adminMode) {
            $tabTodoUrl = route(
                'nhanvien.admin.lich-hen.index',
                array_merge($queryBase, ['tab' => 'todo', 'giao_dien' => 'nguon_hang']),
            );
            $tabListUrl = route(
                'nhanvien.admin.lich-hen.index',
                array_merge($queryBase, ['tab' => 'list', 'giao_dien' => 'nguon_hang']),
            );
            $todoCardUrl = route(
                'nhanvien.admin.lich-hen.index',
                array_merge($queryBase, ['tab' => 'todo', 'todo_view' => 'card', 'giao_dien' => 'nguon_hang']),
            );
            $todoListUrl = route(
                'nhanvien.admin.lich-hen.index',
                array_merge($queryBase, ['tab' => 'todo', 'todo_view' => 'list', 'giao_dien' => 'nguon_hang']),
            );
        }
    @endphp

    <ul class="nav nav-tabs mb-4 border-bottom-0" role="tablist" style="gap: 5px;">
        <li class="nav-item">
            <a href="{{ $tabTodoUrl }}" class="nav-link border-0 {{ !$isListTab ? 'active' : '' }}"
                style="border-radius: 6px 6px 0 0;">
                <i class="fas fa-bell me-1"></i> Cần Xử Lý <span
                    class="badge bg-{{ !$isListTab ? 'success' : 'secondary' }} ms-1">{{ $todoMetrics['all'] }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ $tabListUrl }}" class="nav-link border-0 {{ $isListTab ? 'active' : '' }}"
                style="border-radius: 6px 6px 0 0;">
                <i class="fas fa-history me-1"></i> Lịch Sử Nhiệm Vụ
            </a>
        </li>
    </ul>

    <div class="tab-content">
        {{-- ===================== TAB CẦN XỬ LÝ ===================== --}}
        <div class="tab-pane fade {{ !$isListTab ? 'show active' : '' }}" id="tab-todo">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0 text-dark fw-bold">Danh sách công việc</h5>
                <div class="btn-group bg-white shadow-sm rounded-3 p-1 border">
                    <a href="{{ $todoCardUrl }}"
                        class="btn btn-sm rounded-2 {{ $todoView === 'card' ? 'btn-success fw-bold' : 'btn-light border-0 text-muted' }}">
                        <i class="fas fa-grip me-1"></i> Thẻ
                    </a>
                    <a href="{{ $todoListUrl }}"
                        class="btn btn-sm rounded-2 {{ $todoView === 'list' ? 'btn-success fw-bold' : 'btn-light border-0 text-muted' }}">
                        <i class="fas fa-list me-1"></i> Danh sách
                    </a>
                </div>
            </div>

            {{-- FORM BỘ LỌC TÌM KIẾM CHO TAB ĐANG XỬ LÝ NGUỒN --}}
            <div class="bg-white p-3 rounded-3 border mb-4 shadow-sm">
                <form method="GET" action="{{ route('nhanvien.admin.lich-hen.index') }}">
                    @if (isset($adminMode) && $adminMode)
                        <input type="hidden" name="giao_dien" value="nguon_hang">
                    @endif
                    <input type="hidden" name="tab" value="todo">
                    <input type="hidden" name="todo_view" value="{{ $todoView }}">

                    <div class="row g-2">
                        <div class="col-12 col-md-3">
                            <input type="text" name="todo_tim_kiem" class="form-control" placeholder="Tìm BĐS, Sale..."
                                value="{{ request('todo_tim_kiem') }}">
                        </div>
                        <div class="col-6 col-md-2">
                            <input type="date" name="todo_tu_ngay" class="form-control"
                                value="{{ request('todo_tu_ngay') }}">
                        </div>
                        <div class="col-6 col-md-2">
                            <input type="date" name="todo_den_ngay" class="form-control"
                                value="{{ request('todo_den_ngay') }}">
                        </div>
                        <div class="col-6 col-md-3">
                            <select name="todo_trang_thai" class="form-select">
                                <option value="">-- Mọi trạng thái --</option>
                                <option value="cho_xac_nhan" @selected(request('todo_trang_thai') == 'cho_xac_nhan')>Cần chốt chủ</option>
                                <option value="cho_sale_xac_nhan_doi_gio" @selected(request('todo_trang_thai') == 'cho_sale_xac_nhan_doi_gio')>Chờ Sale chốt giờ dời
                                </option>
                                <option value="da_xac_nhan" @selected(request('todo_trang_thai') == 'da_xac_nhan')>Đã báo có chìa</option>
                            </select>
                        </div>
                        <div class="col-6 col-md-2 d-flex gap-2">
                            <button type="submit" class="btn btn-success w-100 fw-bold"><i class="fas fa-filter"></i>
                                Lọc</button>
                            @if (request()->anyFilled(['todo_tim_kiem', 'todo_tu_ngay', 'todo_den_ngay', 'todo_trang_thai']))
                                <a href="{{ route('nhanvien.admin.lich-hen.index', ['tab' => 'todo', 'todo_view' => $todoView] + (isset($adminMode) && $adminMode ? ['giao_dien' => 'nguon_hang'] : [])) }}"
                                    class="btn btn-light border text-danger"><i class="fas fa-times"></i></a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>

            @if ($todoCollection->isEmpty())
                <div class="text-center py-5 bg-white rounded-3 shadow-sm border">
                    <i class="fas fa-coffee text-success mb-3 opacity-25" style="font-size: 5rem;"></i>
                    <h4 class="text-muted fw-bold">Tuyệt vời!</h4>
                    <p class="text-muted">Không có nhiệm vụ nào đang chờ xử lý.</p>
                </div>
            @else
                {{-- DẠNG BẢNG (LIST VIEW) --}}
                @if ($todoView === 'list')
                    <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="py-3 px-3">Thời gian</th>
                                        <th class="py-3">Bất động sản</th>
                                        <th class="py-3">Chủ nhà</th>
                                        <th class="py-3">Trạng thái</th>
                                        <th class="py-3 text-end px-4">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($todoCollection as $lh)
                                        <tr
                                            class="{{ $lh->trang_thai == 'cho_xac_nhan' ? 'table-info table-active' : '' }}">
                                            <td class="px-3">
                                                <div class="fw-bold text-danger fs-6">
                                                    {{ \Carbon\Carbon::parse($lh->thoi_gian_hen)->format('H:i') }}</div>
                                                <div class="small text-muted fw-medium">
                                                    {{ \Carbon\Carbon::parse($lh->thoi_gian_hen)->format('d/m/Y') }}</div>
                                            </td>
                                            <td>
                                                <div class="fw-bold text-primary mb-1">
                                                    {{ $lh->batDongSan->tieu_de ?? 'Nhà lẻ' }}</div>
                                                <div class="small text-muted">
                                                    Sale hẹn: <strong>{{ $lh->nhanVienSale->ho_ten ?? '---' }}</strong>
                                                    @if ($lh->ghi_chu_sale)
                                                        <br><span class="text-warning-emphasis"><i
                                                                class="fas fa-comment-dots"></i>
                                                            {{ Str::limit($lh->ghi_chu_sale, 40) }}</span>
                                                    @endif
                                                </div>
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
                                                    <span class="badge bg-secondary">Chưa có data</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($isDoiGio($lh))
                                                    <span class="badge bg-warning text-dark"><i
                                                            class="fas fa-spinner fa-spin me-1"></i> Chờ Sale chốt</span>
                                                @elseif ($lh->trang_thai == 'cho_xac_nhan')
                                                    <span class="badge bg-info text-dark">Cần chốt chủ</span>
                                                @else
                                                    <span class="badge bg-success">Đã báo có chìa</span>
                                                @endif
                                            </td>
                                            <td class="text-end px-3">
                                                @if ($lh->trang_thai == 'cho_xac_nhan')
                                                    <button class="btn btn-sm btn-success fw-bold mb-1"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalXacNhan{{ $lh->id }}"><i
                                                            class="fas fa-check"></i> Chốt chìa</button>
                                                    <button class="btn btn-sm btn-warning text-dark fw-bold mb-1"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalBaoLai{{ $lh->id }}">Dời</button>
                                                    <button class="btn btn-sm btn-outline-danger fw-bold"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalTuChoi{{ $lh->id }}"><i
                                                            class="fas fa-times"></i></button>
                                                @elseif ($isDoiGio($lh))
                                                    <button class="btn btn-sm btn-light border text-danger fw-bold"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalHuyNguon{{ $lh->id }}">Báo
                                                        Hủy</button>
                                                @else
                                                    <button class="btn btn-sm btn-light border text-dark fw-bold mb-1"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalBaoLai{{ $lh->id }}">Dời</button>
                                                    <button class="btn btn-sm btn-light border text-danger fw-bold"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalHuyNguon{{ $lh->id }}">Hủy</button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- DẠNG THẺ (CARD VIEW) HIỆN ĐẠI --}}
                @else
                    <div class="row g-4">
                        @foreach ($todoCollection as $lh)
                            @php
                                $statusColor =
                                    $lh->trang_thai == 'cho_xac_nhan'
                                        ? 'info'
                                        : ($isDoiGio($lh)
                                            ? 'warning'
                                            : 'success');
                                $statusLabel = '';
                                if ($isDoiGio($lh)) {
                                    $statusLabel = '<i class="fas fa-spinner fa-spin me-1"></i> Chờ Sale chốt';
                                } elseif ($lh->trang_thai == 'cho_xac_nhan') {
                                    $statusLabel = 'Cần chốt chủ';
                                } else {
                                    $statusLabel = 'Đã báo chìa';
                                }
                            @endphp
                            <div class="col-12 col-md-6 col-xxl-4">
                                <div class="src-card border-{{ $statusColor }}">
                                    <div class="src-card-status-indicator bg-{{ $statusColor }}"></div>
                                    <div class="src-card-header">
                                        <div>
                                            <div class="src-card-time">
                                                {{ \Carbon\Carbon::parse($lh->thoi_gian_hen)->format('H:i') }}</div>
                                            <div class="src-card-date">
                                                {{ \Carbon\Carbon::parse($lh->thoi_gian_hen)->format('d/m/Y') }}</div>
                                        </div>
                                        <span
                                            class="badge rounded-pill bg-{{ $statusColor }} {{ $statusColor == 'warning' || $statusColor == 'info' ? 'text-dark' : '' }} px-3 py-2 fw-bold shadow-sm">
                                            {!! $statusLabel !!}
                                        </span>
                                    </div>
                                    <div class="src-card-body">
                                        <div class="src-card-title">{{ $lh->batDongSan->tieu_de ?? 'Ngoài hệ thống' }}
                                        </div>
                                        <div class="text-muted small mb-3"><i
                                                class="fas fa-map-marker-alt text-danger me-1"></i>{{ optional($lh->batDongSan)->khuVuc?->ten_khu_vuc ?? 'Chưa cập nhật khu vực' }}
                                        </div>

                                        <div class="src-card-info">
                                            <div class="row g-2">
                                                <div class="col-6 border-end">
                                                    <div class="small text-muted mb-1"><i
                                                            class="fas fa-user-tie me-1"></i>Sale đặt lịch:</div>
                                                    <div class="fw-bold text-dark text-truncate">
                                                        {{ $lh->nhanVienSale->ho_ten ?? '---' }}</div>
                                                </div>
                                                <div class="col-6 ps-2">
                                                    <div class="small text-success fw-bold mb-1"><i
                                                            class="fas fa-house-user me-1"></i>Chủ nhà:</div>
                                                    <div class="fw-bold text-dark text-truncate">
                                                        {{ optional($lh->batDongSan?->chuNha)->ho_ten ?? 'Chưa rõ' }}</div>
                                                    @if ($lh->batDongSan?->chuNha)
                                                        <a href="tel:{{ $lh->batDongSan->chuNha->so_dien_thoai }}"
                                                            class="small fw-bold text-success text-decoration-none d-block mt-1"><i
                                                                class="fas fa-phone-alt me-1"></i>{{ $lh->batDongSan->chuNha->so_dien_thoai }}</a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        @if ($lh->ghi_chu_sale)
                                            <div
                                                class="small p-2 bg-warning bg-opacity-10 border border-warning rounded text-dark">
                                                <strong class="d-block mb-1"><i class="fas fa-comment-dots me-1"></i>Ghi
                                                    chú từ Sale:</strong>
                                                {{ $lh->ghi_chu_sale }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="src-card-footer">
                                        @if ($lh->trang_thai == 'cho_xac_nhan')
                                            <button class="btn btn-sm btn-success fw-bold flex-fill"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalXacNhan{{ $lh->id }}"><i
                                                    class="fas fa-check me-1"></i> Có chìa</button>
                                            <button class="btn btn-sm btn-warning fw-bold text-dark flex-fill"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalBaoLai{{ $lh->id }}"><i
                                                    class="fas fa-clock me-1"></i> Dời</button>
                                            <button class="btn btn-sm btn-outline-danger fw-bold flex-fill"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalTuChoi{{ $lh->id }}"><i
                                                    class="fas fa-times me-1"></i> Hủy</button>
                                        @elseif ($isDoiGio($lh))
                                            <button class="btn btn-sm btn-outline-danger fw-bold w-100"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalHuyNguon{{ $lh->id }}"><i
                                                    class="fas fa-ban me-1"></i> Báo Hủy Đột Xuất</button>
                                        @else
                                            <button class="btn btn-sm btn-light border fw-bold text-dark flex-fill"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalBaoLai{{ $lh->id }}"><i
                                                    class="fas fa-clock text-warning me-1"></i> Dời lịch</button>
                                            <button class="btn btn-sm btn-light border fw-bold text-danger flex-fill"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalHuyNguon{{ $lh->id }}"><i
                                                    class="fas fa-ban me-1"></i> Hủy lịch</button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            @endif
        </div>

        {{-- TAB LỊCH SỬ TỔNG HỢP NGUỒN HÀNG --}}
        <div class="tab-pane fade {{ $isListTab ? 'show active' : '' }}" id="tab-list">
            <div class="bg-white p-3 rounded-3 border mb-3">
                <form method="GET" action="{{ route('nhanvien.admin.lich-hen.index') }}">
                    @if (isset($adminMode) && $adminMode)
                        <input type="hidden" name="giao_dien" value="nguon_hang">
                    @endif
                    <input type="hidden" name="tab" value="list">
                    <div class="row g-2">
                        <div class="col-12 col-md-3"><input type="text" name="tim_kiem" class="form-control"
                                placeholder="Tìm khách, sđt, dự án..." value="{{ request('tim_kiem') }}"></div>
                        <div class="col-6 col-md-2"><input type="date" name="tu_ngay" class="form-control"
                                value="{{ request('tu_ngay') }}"></div>
                        <div class="col-6 col-md-2"><input type="date" name="den_ngay" class="form-control"
                                value="{{ request('den_ngay') }}"></div>
                        <div class="col-6 col-md-3">
                            <select name="trang_thai" class="form-select">
                                <option value="">-- Mọi trạng thái --</option>
                                <option value="da_xac_nhan" @selected(request('trang_thai') == 'da_xac_nhan')>Đã báo có chìa</option>
                                <option value="hoan_thanh" @selected(request('trang_thai') == 'hoan_thanh')>Hoàn thành</option>
                                <option value="tu_choi" @selected(request('trang_thai') == 'tu_choi')>Từ chối</option>
                                <option value="huy" @selected(request('trang_thai') == 'huy')>Đã hủy</option>
                            </select>
                        </div>
                        <div class="col-6 col-md-2 d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-100"><i class="fas fa-filter"></i>
                                Lọc</button>
                            @if (request()->anyFilled(['tim_kiem', 'tu_ngay', 'den_ngay', 'trang_thai']))
                                <a href="{{ route('nhanvien.admin.lich-hen.index', ['tab' => 'list'] + (isset($adminMode) && $adminMode ? ['giao_dien' => 'nguon_hang'] : [])) }}"
                                    class="btn btn-light border text-danger"><i class="fas fa-times"></i></a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
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
                                        <td>
                                            <div class="fw-bold text-primary">
                                                {{ \Carbon\Carbon::parse($lh->thoi_gian_hen)->format('H:i') }}</div>
                                            <div class="small text-muted">
                                                {{ \Carbon\Carbon::parse($lh->thoi_gian_hen)->format('d/m/Y') }}</div>
                                        </td>
                                        <td>
                                            <div class="fw-bold">{{ $lh->ten_khach_hang }}</div>
                                            <div class="small text-success"><i
                                                    class="fas fa-phone-alt me-1"></i>{{ $lh->sdt_khach_hang }}</div>
                                        </td>
                                        <td>
                                            <div class="fw-medium text-dark">
                                                {{ $lh->batDongSan->tieu_de ?? 'Ngoài hệ thống' }}</div>
                                        </td>
                                        <td><span
                                                class="badge bg-secondary">{{ $trangThaiLabels[$lh->trang_thai] ?? $lh->trang_thai }}</span>
                                        </td>
                                        <td><a href="{{ route('nhanvien.admin.lich-hen.show', $lh->id) }}"
                                                class="btn btn-sm btn-light border"><i
                                                    class="fas fa-eye text-primary"></i></a></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">Không có dữ liệu lịch sử.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if (isset($lichHensList) && $lichHensList->hasPages())
                        <div class="p-3 border-top">
                            {{ $lichHensList->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>
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
                        <input type="hidden" name="_redirect_back" value="{{ url()->full() }}">
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title fw-bold"><i class="fas fa-check-circle me-2"></i> Chốt Mở Cửa Với Chủ
                                Nhà</h5>
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
                                class="btn btn-success px-4 fw-bold w-100">Chắc chắn, Gửi Sale</button></div>
                    </form>
                </div>
            </div>

            {{-- MODAL TỪ CHỐI --}}
            <div class="modal fade" id="modalTuChoi{{ $lh->id }}" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <form action="{{ route('nhanvien.admin.lich-hen.tu-choi', $lh->id) }}" method="POST"
                        class="modal-content border-0 shadow-lg">
                        @csrf @method('PATCH')
                        <input type="hidden" name="_redirect_back" value="{{ url()->full() }}">
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
                        <input type="hidden" name="_redirect_back" value="{{ url()->full() }}">
                        <div class="modal-header bg-warning">
                            <h5 class="modal-title fw-bold text-dark"><i class="fas fa-clock me-2"></i> Đề Xuất Dời Giờ
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body p-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Giờ mới chủ nhà hẹn:</label>
                                <input type="datetime-local" name="thoi_gian_hen" class="form-control form-control-lg"
                                    value="{{ \Carbon\Carbon::parse($lh->thoi_gian_hen)->format('Y-m-d\TH:i') }}"
                                    required>
                            </div>
                            <div class="mb-0">
                                <label class="form-label fw-bold">Lời nhắn cho Sale (Để báo khách):</label>
                                <textarea name="ghi_chu_nguon_hang" class="form-control" rows="3"
                                    placeholder="VD: Chủ nhà đi làm 5h mới về..." required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer bg-light"><button type="submit" class="btn btn-dark fw-bold w-100">Gửi
                                cho Sale chốt lại</button></div>
                    </form>
                </div>
            </div>

            {{-- MODAL HỦY --}}
            <div class="modal fade" id="modalHuyNguon{{ $lh->id }}" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <form action="{{ route('nhanvien.admin.lich-hen.huy', $lh->id) }}" method="POST"
                        class="modal-content border-0 shadow-lg">
                        @csrf @method('PATCH')
                        <input type="hidden" name="_redirect_back" value="{{ url()->full() }}">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title fw-bold"><i class="fas fa-ban me-2"></i> Báo Hủy Lịch Đột Xuất</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body p-4">
                            <label class="form-label fw-bold">Lý do Hủy (Bắt buộc):</label>
                            <textarea name="ly_do" class="form-control" rows="3" required placeholder="VD: Chủ báo hủy bán..."></textarea>
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

        /* Thẻ Card Mới */
        .src-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .src-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.08);
        }

        .src-card-status-indicator {
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            width: 5px;
        }

        .src-card-header {
            padding: 1.25rem 1.25rem 0.5rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .src-card-time {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--bs-danger);
            line-height: 1;
            margin-bottom: 0.25rem;
            letter-spacing: -0.5px;
        }

        .src-card-date {
            font-size: 0.85rem;
            color: #64748b;
            font-weight: 600;
        }

        .src-card-body {
            padding: 0.5rem 1.25rem 1.25rem 1.5rem;
            flex: 1;
        }

        .src-card-title {
            font-size: 1.05rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.3rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            line-height: 1.4;
        }

        .src-card-info {
            background: #f8fafc;
            border-radius: 10px;
            padding: 0.85rem;
            margin-top: 1rem;
            margin-bottom: 0.5rem;
            border: 1px solid #f1f5f9;
        }

        .src-card-footer {
            padding: 1rem 1.25rem;
            background: #f8fafc;
            border-top: 1px solid #f1f5f9;
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
    </style>
@endpush
