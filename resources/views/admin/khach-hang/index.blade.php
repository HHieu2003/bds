@extends('admin.layouts.master')
@section('title', 'Quản lý Khách Hàng')

@push('styles')
    <style>
        /* Chỉ những gì Bootstrap không có */
        .avatar-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: .92rem;
            flex-shrink: 0;
        }

        .kh-table>tbody>tr:hover>td {
            background-color: #f8f9fa;
        }

        .kh-table>thead>tr>th {
            font-size: .72rem;
            text-transform: uppercase;
            letter-spacing: .5px;
            font-weight: 600;
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            color: #6c757d;
            padding: 10px 14px;
        }

        .kh-table>tbody>tr>td {
            padding: 11px 14px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f3f5;
        }

        .kh-table>tbody>tr:last-child>td {
            border-bottom: none;
        }

        .src-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 2px 9px;
            border-radius: 6px;
            font-size: .74rem;
            background: #f1f3f5;
            color: #495057;
            border: 1px solid #dee2e6;
        }

        .tone-danger {
            background: #feecec;
            color: #b42318;
        }

        .tone-warning {
            background: #fff6e5;
            color: #b54708;
        }

        .tone-secondary {
            background: #f2f4f7;
            color: #475467;
        }

        .potential-badge {
            border-radius: 999px;
            font-size: .74rem;
            font-weight: 600;
            padding: 4px 10px;
            border: 1px solid transparent;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .potential-badge.tone-danger {
            border-color: #fecdca;
        }

        .potential-badge.tone-warning {
            border-color: #fedf89;
        }

        .potential-badge.tone-secondary {
            border-color: #d0d5dd;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid py-3 px-4">

        {{-- HEADER --}}
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
            <div>
                <h4 class="fw-bold mb-0"><i class="fas fa-users text-primary me-2"></i>Quản lý Khách Hàng</h4>
                <small class="text-muted">Phân loại tiềm năng · Chăm sóc · Theo dõi hành trình mua</small>
            </div>
            <button class="btn btn-primary fw-semibold" data-bs-toggle="modal" data-bs-target="#modalThemKH">
                <i class="fas fa-user-plus me-2"></i>Thêm Khách Hàng
            </button>
        </div>

        {{-- KPI CARDS — dùng Bootstrap card + text-{color} --}}
        <div class="row g-3 mb-4">
            @php
                $kpiList = [
                    ['label' => 'Tổng khách hàng', 'val' => $stats['tong'], 'icon' => 'fa-users', 'color' => 'primary'],
                    [
                        'label' => '🔥 Nóng – Sắp chốt',
                        'val' => $stats['nong'],
                        'icon' => 'fa-fire',
                        'color' => 'danger',
                    ],
                    ['label' => '☀️ Ấm – Đang chăm', 'val' => $stats['am'], 'icon' => 'fa-sun', 'color' => 'warning'],
                    [
                        'label' => '❄️ Lạnh',
                        'val' => $stats['tong'] - $stats['nong'] - $stats['am'],
                        'icon' => 'fa-snowflake',
                        'color' => 'secondary',
                    ],
                ];
            @endphp
            @foreach ($kpiList as $k)
                <div class="col-6 col-md-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body d-flex align-items-center gap-3 py-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center bg-{{ $k['color'] }} bg-opacity-10 flex-shrink-0"
                                style="width:46px;height:46px;">
                                <i class="fas {{ $k['icon'] }} text-{{ $k['color'] }}"></i>
                            </div>
                            <div>
                                <div class="fw-bold fs-4 lh-1 text-{{ $k['color'] }}">{{ $k['val'] }}</div>
                                <div class="text-muted" style="font-size:.76rem;">{{ $k['label'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- BỘ LỌC --}}
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body py-3">
                <form method="GET" class="row g-2 align-items-end">
                    <div class="col-12 col-md-4">
                        <label class="form-label small fw-semibold mb-1">
                            <i class="fas fa-search text-muted me-1"></i>Tìm kiếm
                        </label>
                        <input type="text" name="tim_kiem" class="form-control form-control-sm"
                            placeholder="Tên, SĐT, Email..." value="{{ request('tim_kiem') }}">
                    </div>
                    <div class="col-6 col-md-2">
                        <label class="form-label small fw-semibold mb-1">Tiềm năng</label>
                        <select name="muc_do_tiem_nang" class="form-select form-select-sm">
                            <option value="">Tất cả</option>
                            @foreach ($mucDoTiemNang as $key => $tt)
                                <option value="{{ $key }}"
                                    {{ request('muc_do_tiem_nang') == $key ? 'selected' : '' }}>
                                    {{ $tt['label'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 col-md-2">
                        <label class="form-label small fw-semibold mb-1">Nguồn KH</label>
                        <select name="nguon_khach_hang" class="form-select form-select-sm">
                            <option value="">Tất cả</option>
                            @foreach (['facebook' => 'Facebook', 'zalo' => 'Zalo', 'gioi_thieu' => 'Giới thiệu', 'website' => 'Website', 'sale' => 'Sale', 'khac' => 'Khác'] as $k => $v)
                                <option value="{{ $k }}"
                                    {{ request('nguon_khach_hang') == $k ? 'selected' : '' }}>
                                    {{ $v }}</option>
                            @endforeach
                        </select>
                    </div>
                    @if (!$nhanVien->isSale())
                        <div class="col-6 col-md-2">
                            <label class="form-label small fw-semibold mb-1">Sale phụ trách</label>
                            <select name="nhan_vien_id" class="form-select form-select-sm">
                                <option value="">Tất cả</option>
                                @foreach ($dsSale as $sale)
                                    <option value="{{ $sale->id }}"
                                        {{ request('nhan_vien_id') == $sale->id ? 'selected' : '' }}>
                                        {{ $sale->ho_ten }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    <div class="col-6 col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm flex-fill">
                            <i class="fas fa-filter me-1"></i>Lọc
                        </button>
                        <a href="{{ route('nhanvien.admin.khach-hang.index') }}" class="btn btn-outline-secondary btn-sm"
                            title="Xóa bộ lọc">
                            <i class="fas fa-undo"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        {{-- BẢNG DANH SÁCH --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex align-items-center justify-content-between py-2 border-bottom">
                <span class="fw-semibold">
                    Danh sách khách hàng
                    <span class="badge bg-primary rounded-pill ms-1">{{ $khachHangs->total() }}</span>
                </span>
                <small class="text-muted">Trang {{ $khachHangs->currentPage() }} / {{ $khachHangs->lastPage() }}</small>
            </div>

            <div class="table-responsive">
                <table class="table kh-table mb-0">
                    <thead>
                        <tr>
                            <th style="width:270px;">Khách hàng</th>
                            <th style="width:130px;">Tiềm năng</th>
                            <th style="width:160px;">Nguồn · Liên hệ</th>
                            <th style="width:160px;">Sale phụ trách</th>
                            <th class="text-center" style="width:90px;">Lịch hẹn</th>
                            <th class="text-center" style="width:100px;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($khachHangs as $kh)
                            @php
                                $tt = $mucDoTiemNang[$kh->muc_do_tiem_nang] ?? ['label' => '?', 'color' => 'secondary'];
                                $color = $tt['color']; // danger / warning / secondary
                                $initial = mb_strtoupper(
                                    mb_substr(
                                        trim($kh->ho_ten),
                                        -1 * mb_strlen(trim($kh->ho_ten)) + mb_strrpos(trim($kh->ho_ten), ' ') + 1,
                                        1,
                                    ),
                                );
                                $nguonMap = [
                                    'facebook' => ['fab fa-facebook', 'text-primary', 'Facebook'],
                                    'zalo' => ['fas fa-comment-dots', 'text-info', 'Zalo'],
                                    'gioi_thieu' => ['fas fa-user-friends', 'text-success', 'Giới thiệu'],
                                    'website' => ['fas fa-globe', 'text-purple', 'Website'],
                                    'sale' => ['fas fa-headset', 'text-warning', 'Sale'],
                                    'default' => ['fas fa-question-circle', 'text-muted', 'Khác'],
                                ];
                                $ng = $nguonMap[$kh->nguon_khach_hang] ?? $nguonMap['default'];
                            @endphp
                            @php
                                $toneClass = match ($color) {
                                    'danger' => 'tone-danger',
                                    'warning' => 'tone-warning',
                                    default => 'tone-secondary',
                                };
                            @endphp
                            <tr>
                                {{-- Tên --}}
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar-circle {{ $toneClass }}">
                                            {{ $initial ?: '?' }}
                                        </div>
                                        <div class="overflow-hidden">
                                            <a href="{{ route('nhanvien.admin.khach-hang.show', $kh) }}"
                                                class="fw-semibold text-dark text-decoration-none d-block text-truncate"
                                                style="max-width:195px;">{{ $kh->ho_ten }}</a>
                                            <div class="d-flex flex-wrap gap-2 mt-1">
                                                @if ($kh->so_dien_thoai)
                                                    <a href="tel:{{ $kh->so_dien_thoai }}"
                                                        class="text-muted text-decoration-none" style="font-size:.78rem;">
                                                        <i class="fas fa-phone-alt text-success me-1"
                                                            style="font-size:.7rem;"></i>{{ $kh->so_dien_thoai }}
                                                    </a>
                                                @endif
                                                @if ($kh->email)
                                                    <span class="text-muted" style="font-size:.75rem;">
                                                        <i class="fas fa-envelope me-1"
                                                            style="font-size:.7rem;"></i>{{ Str::limit($kh->email, 20) }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Tiềm năng — dùng $tt['color'] từ controller --}}
                                <td>
                                    <span class="potential-badge {{ $toneClass }}">
                                        @if ($color === 'danger')
                                            🔥
                                        @elseif($color === 'warning')
                                            ☀️
                                        @else
                                            ❄️
                                        @endif
                                        {{ $tt['label'] }}
                                    </span>
                                </td>

                                {{-- Nguồn --}}
                                <td>
                                    <span class="src-badge">
                                        <i class="{{ $ng[0] }} {{ $ng[1] }}"></i> {{ $ng[2] }}
                                    </span>
                                    <div class="text-muted mt-1" style="font-size:.75rem;">
                                        <i class="far fa-clock me-1"></i>
                                        {{ $kh->lien_he_cuoi_at ? \Carbon\Carbon::parse($kh->lien_he_cuoi_at)->diffForHumans() : 'Chưa liên hệ' }}
                                    </div>
                                </td>

                                {{-- Sale --}}
                                <td>
                                    @if ($kh->nhanVienPhuTrach)
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="avatar-circle bg-primary bg-opacity-10 text-primary"
                                                style="width:28px;height:28px;font-size:.7rem;">
                                                {{ mb_strtoupper(mb_substr($kh->nhanVienPhuTrach->ho_ten, 0, 1)) }}
                                            </div>
                                            <span style="font-size:.84rem;">{{ $kh->nhanVienPhuTrach->ho_ten }}</span>
                                        </div>
                                    @else
                                        <span class="text-muted fst-italic" style="font-size:.8rem;">Chưa gán</span>
                                    @endif
                                </td>

                                {{-- Lịch hẹn --}}
                                <td class="text-center">
                                    @if (($kh->lich_hens_count ?? 0) > 0)
                                        <span
                                            class="badge bg-info bg-opacity-10 text-info border border-info rounded-pill px-2">
                                            <i class="fas fa-calendar-check me-1"
                                                style="font-size:.65rem;"></i>{{ $kh->lich_hens_count }}
                                        </span>
                                    @else
                                        <span class="text-muted">–</span>
                                    @endif
                                </td>

                                {{-- Thao tác --}}
                                <td class="text-center">
                                    <a href="{{ route('nhanvien.admin.khach-hang.show', $kh) }}"
                                        class="btn btn-sm btn-primary">
                                        <i class="fas fa-id-card me-1"></i>Hồ sơ
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fas fa-users fa-3x d-block mb-3 opacity-25"></i>
                                    <p class="fw-semibold mb-1 text-secondary">Chưa có dữ liệu</p>
                                    <small>Thêm khách hàng đầu tiên để bắt đầu</small>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($khachHangs->hasPages())
                <div
                    class="card-footer bg-white border-top d-flex align-items-center justify-content-between flex-wrap gap-2 py-2 px-3">
                    <small class="text-muted">
                        Hiển thị
                        <strong>{{ $khachHangs->firstItem() }}</strong>–<strong>{{ $khachHangs->lastItem() }}</strong>
                        trong tổng <strong>{{ $khachHangs->total() }}</strong>
                    </small>
                    {{ $khachHangs->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>

    </div>

    {{-- MODAL THÊM KH --}}
    <div class="modal fade" id="modalThemKH" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold fs-6">
                        <i class="fas fa-user-plus me-2"></i>Thêm Khách Hàng Mới
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('nhanvien.admin.khach-hang.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Họ tên <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="ho_ten" class="form-control" placeholder="Nguyễn Văn A"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Số điện thoại <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="so_dien_thoai" class="form-control"
                                    placeholder="0901 234 567" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Email</label>
                                <input type="email" name="email" class="form-control"
                                    placeholder="email@example.com">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Nguồn khách hàng</label>
                                <select name="nguon_khach_hang" class="form-select">
                                    <option value="sale">Sale tự tạo</option>
                                    <option value="facebook">Facebook</option>
                                    <option value="zalo">Zalo</option>
                                    <option value="website">Website</option>
                                    <option value="gioi_thieu">Giới thiệu</option>
                                    <option value="khac">Khác</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Mức độ tiềm năng</label>
                                <select name="muc_do_tiem_nang" class="form-select">
                                    @foreach ($mucDoTiemNang as $key => $tt)
                                        <option value="{{ $key }}" {{ $key === 'am' ? 'selected' : '' }}>
                                            {{ $tt['label'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if (!$nhanVien->isSale())
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Giao cho Sale</label>
                                    <select name="nhan_vien_phu_trach_id" class="form-select">
                                        <option value="">– Chưa gán –</option>
                                        @foreach ($dsSale as $sale)
                                            <option value="{{ $sale->id }}">{{ $sale->ho_ten }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                            <div class="col-12">
                                <label class="form-label small fw-semibold">Ghi chú nội bộ</label>
                                <textarea name="ghi_chu_noi_bo" class="form-control" rows="3"
                                    placeholder="Nhu cầu, ngân sách, khu vực quan tâm..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary fw-semibold px-4">
                            <i class="fas fa-save me-2"></i>Lưu Khách Hàng
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
