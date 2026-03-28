@extends('admin.layouts.master')
@section('title', 'Lịch hẹn')
@section('page_title', 'Quản lý Lịch hẹn')

@push('styles')
    <style>
        /* ── Status badges ── */
        .lh-badge {
            display: inline-flex;
            align-items: center;
            gap: .3rem;
            padding: .25rem .65rem;
            border-radius: 20px;
            font-size: .72rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .lh-moi_dat {
            background: #fff3cd;
            color: #856404;
        }

        .lh-cho_xac_nhan {
            background: #cfe2ff;
            color: #084298;
        }

        .lh-da_xac_nhan {
            background: #d1e7dd;
            color: #0a3622;
        }

        .lh-hoan_thanh {
            background: #d1fae5;
            color: #065f46;
        }

        .lh-tu_choi {
            background: #fde8e8;
            color: #991b1b;
        }

        .lh-huy {
            background: #f3f4f6;
            color: #6b7280;
        }

        /* ── Stat cards ── */
        .stat-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
            gap: .75rem;
            margin-bottom: 1.25rem;
        }

        .stat-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: .85rem 1rem;
            text-align: center;
            cursor: pointer;
            transition: all .2s;
        }

        .stat-card:hover,
        .stat-card.active {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(var(--primary-rgb), .12);
        }

        .stat-card .sc-num {
            font-size: 1.6rem;
            font-weight: 800;
            line-height: 1;
        }

        .stat-card .sc-lbl {
            font-size: .7rem;
            color: var(--text-sub);
            margin-top: .2rem;
        }

        /* ── Table ── */
        .lh-table th {
            font-size: .72rem;
            font-weight: 700;
            color: var(--text-sub);
            background: #f8f9fa;
            white-space: nowrap;
        }

        .lh-table td {
            vertical-align: middle;
            font-size: .82rem;
        }

        /* ── Timeline badge ── */
        .src-badge {
            font-size: .65rem;
            border-radius: 4px;
            padding: .1rem .4rem;
            background: #e9ecef;
            color: #555;
        }

        .src-website {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .src-sale {
            background: #ede9fe;
            color: #5b21b6;
        }

        .src-phone {
            background: #dcfce7;
            color: #166534;
        }

        .src-chat {
            background: #fef9c3;
            color: #854d0e;
        }

        /* ── Action row ── */
        .lh-actions .btn {
            font-size: .75rem;
            padding: .3rem .7rem;
        }

        /* ── Filter bar ── */
        .filter-bar {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: .85rem 1rem;
            margin-bottom: 1rem;
            display: flex;
            flex-wrap: wrap;
            gap: .6rem;
            align-items: flex-end;
        }
    </style>
@endpush

@section('content')
    @php $nhanVien = Auth::guard('nhanvien')->user(); @endphp

    <div class="container-fluid py-3">

        {{-- ── Header ── --}}
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h5 class="mb-0 fw-800"><i class="fas fa-calendar-check me-2 text-primary"></i>Lịch hẹn</h5>
                <p class="text-muted mb-0" style="font-size:.78rem">
                    @if ($nhanVien->isSale())
                        Lịch hẹn bạn phụ trách
                    @elseif($nhanVien->isNguonHang())
                        Lịch hẹn cần bạn xác nhận
                    @else
                        Tất cả lịch hẹn trong hệ thống
                    @endif
                </p>
            </div>
            @if ($nhanVien->hasRole(['admin', 'sale']))
                <a href="{{ route('nhanvien.admin.lich-hen.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i> Tạo lịch hẹn
                </a>
            @endif
        </div>

        {{-- ── Stat cards ── --}}
        <div class="stat-cards">
            @php
                $tabs = [
                    ['', $stats['hom_nay'], 'Hôm nay', '#f59e0b', 'fas fa-sun'],
                    ['moi_dat', $stats['moi_dat'], 'Mới đặt', '#d97706', 'fas fa-bell'],
                    ['cho_xac_nhan', $stats['cho_xac_nhan'], 'Chờ xác nhận', '#3b82f6', 'fas fa-clock'],
                    ['da_xac_nhan', $stats['da_xac_nhan'], 'Đã xác nhận', '#10b981', 'fas fa-check'],
                    ['hoan_thanh', $stats['hoan_thanh'], 'Hoàn thành', '#059669', 'fas fa-flag-checkered'],
                    ['tu_choi', $stats['tu_choi'], 'Từ chối', '#ef4444', 'fas fa-times-circle'],
                ];
                $current = request('trang_thai', '');
            @endphp
            @foreach ($tabs as [$val, $num, $lbl, $color, $icon])
                <a href="{{ route('nhanvien.admin.lich-hen.index', array_merge(request()->except('trang_thai', 'page'), $val ? ['trang_thai' => $val] : [])) }}"
                    class="stat-card text-decoration-none {{ $current === $val ? 'active' : '' }}">
                    <div class="sc-num" style="color:{{ $color }}">{{ $num }}</div>
                    <div class="sc-lbl"><i class="{{ $icon }} me-1"></i>{{ $lbl }}</div>
                </a>
            @endforeach
        </div>

        {{-- ── Filter bar ── --}}
        <form method="GET" class="filter-bar">
            @if (request('trang_thai'))
                <input type="hidden" name="trang_thai" value="{{ request('trang_thai') }}">
            @endif
            <div>
                <label class="form-label mb-1" style="font-size:.72rem;font-weight:600">Ngày hẹn</label>
                <input type="date" name="ngay" value="{{ request('ngay') }}" class="form-control form-control-sm"
                    style="width:150px">
            </div>
            @if ($nhanVien->isAdmin())
                <div>
                    <label class="form-label mb-1" style="font-size:.72rem;font-weight:600">Nguồn hàng</label>
                    <select name="nguon_hang" class="form-select form-select-sm" style="width:160px">
                        <option value="">Tất cả</option>
                        @foreach ($dsNguonHang as $nv)
                            <option value="{{ $nv->id }}" {{ request('nguon_hang') == $nv->id ? 'selected' : '' }}>
                                {{ $nv->ho_ten }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
            <div class="d-flex gap-2 align-items-end">
                <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-filter me-1"></i>Lọc</button>
                <a href="{{ route('nhanvien.admin.lich-hen.index') }}" class="btn btn-outline-secondary btn-sm">Xóa lọc</a>
            </div>
        </form>

        {{-- ── Table ── --}}
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                @if ($lichHen->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-calendar-times fa-3x mb-3 opacity-25"></i>
                        <p class="mb-0">Không có lịch hẹn nào.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover lh-table mb-0">
                            <thead>
                                <tr>
                                    <th style="width:42px">#</th>
                                    <th>Khách hàng</th>
                                    <th>Bất động sản</th>
                                    <th>Thời gian hẹn</th>
                                    <th>Sale</th>
                                    <th>Nguồn hàng</th>
                                    <th>Trạng thái</th>
                                    <th style="width:180px">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($lichHen as $lh)
                                    <tr>
                                        <td class="text-muted" style="font-size:.72rem">{{ $lh->id }}</td>

                                        {{-- Khách hàng --}}
                                        <td>
                                            <div class="fw-600">{{ $lh->ten_khach_hang }}</div>
                                            <div class="text-muted" style="font-size:.72rem">{{ $lh->sdt_khach_hang }}
                                            </div>
                                            <span class="src-badge src-{{ $lh->nguon_dat_lich }}">
                                                @php $srcMap=['website'=>'Website','sale'=>'Sale','phone'=>'Điện thoại','chat'=>'Chat']; @endphp
                                                {{ $srcMap[$lh->nguon_dat_lich] ?? $lh->nguon_dat_lich }}
                                            </span>
                                        </td>

                                        {{-- BDS --}}
                                        <td>
                                            <div class="fw-600"
                                                style="max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                                                {{ optional($lh->batDongSan)->ten_bat_dong_san ?? '—' }}
                                            </div>
                                            @if (optional($lh->batDongSan)->ma_can)
                                                <div class="text-muted" style="font-size:.72rem">
                                                    {{ $lh->batDongSan->ma_can }}</div>
                                            @endif
                                        </td>

                                        {{-- Thời gian --}}
                                        <td>
                                            <div class="fw-600">{{ $lh->thoi_gian_hen->format('H:i') }}</div>
                                            <div class="text-muted" style="font-size:.72rem">
                                                {{ $lh->thoi_gian_hen->format('d/m/Y') }}</div>
                                            @if ($lh->thoi_gian_hen->isToday())
                                                <span class="badge bg-warning text-dark" style="font-size:.6rem">Hôm
                                                    nay</span>
                                            @elseif($lh->thoi_gian_hen->isPast() && !in_array($lh->trang_thai, ['hoan_thanh', 'huy', 'tu_choi']))
                                                <span class="badge bg-danger" style="font-size:.6rem">Quá hạn</span>
                                            @endif
                                        </td>

                                        {{-- Sale --}}
                                        <td>
                                            @if ($lh->nhanVienSale)
                                                <div style="font-size:.78rem">{{ $lh->nhanVienSale->ho_ten }}</div>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>

                                        {{-- Nguồn hàng --}}
                                        <td>
                                            @if ($lh->nhanVienNguonHang)
                                                <div style="font-size:.78rem">{{ $lh->nhanVienNguonHang->ho_ten }}</div>
                                            @else
                                                <span class="text-danger fw-600" style="font-size:.72rem">Chưa assign</span>
                                            @endif
                                        </td>

                                        {{-- Trạng thái --}}
                                        <td>
                                            @php
                                                $badgeMap = [
                                                    'moi_dat' => ['lh-moi_dat', 'fas fa-bell', 'Mới đặt'],
                                                    'cho_xac_nhan' => [
                                                        'lh-cho_xac_nhan',
                                                        'fas fa-clock',
                                                        'Chờ xác nhận',
                                                    ],
                                                    'da_xac_nhan' => [
                                                        'lh-da_xac_nhan',
                                                        'fas fa-check-circle',
                                                        'Đã xác nhận',
                                                    ],
                                                    'hoan_thanh' => ['lh-hoan_thanh', 'fas fa-flag', 'Hoàn thành'],
                                                    'tu_choi' => ['lh-tu_choi', 'fas fa-times-circle', 'Từ chối'],
                                                    'huy' => ['lh-huy', 'fas fa-ban', 'Đã hủy'],
                                                ];
                                                [$cls, $ico, $lbl] = $badgeMap[$lh->trang_thai] ?? [
                                                    'lh-huy',
                                                    'fas fa-question',
                                                    '?',
                                                ];
                                            @endphp
                                            <span class="lh-badge {{ $cls }}"><i
                                                    class="{{ $ico }}"></i>{{ $lbl }}</span>
                                            @if ($lh->trang_thai === 'tu_choi' && $lh->ly_do_tu_choi)
                                                <div class="text-danger mt-1" style="font-size:.68rem"
                                                    title="{{ $lh->ly_do_tu_choi }}">
                                                    <i class="fas fa-info-circle"></i>
                                                    {{ Str::limit($lh->ly_do_tu_choi, 40) }}
                                                </div>
                                            @endif
                                        </td>

                                        {{-- Thao tác --}}
                                        <td class="lh-actions">
                                            <div class="d-flex flex-wrap gap-1">

                                                {{-- Xem chi tiết --}}
                                                <a href="{{ route('nhanvien.admin.lich-hen.show', $lh) }}"
                                                    class="btn btn-outline-secondary btn-sm" title="Xem chi tiết">
                                                    <i class="fas fa-eye"></i>
                                                </a>

                                                {{-- SALE: Tiếp nhận lịch từ KH (moi_dat) --}}
                                                @if ($lh->trang_thai === 'moi_dat' && $nhanVien->hasRole(['admin', 'sale']))
                                                    <button class="btn btn-warning btn-sm"
                                                        title="Tiếp nhận & phân công Nguồn hàng"
                                                        onclick="openTiepNhan({{ $lh->id }}, '{{ addslashes($lh->ten_khach_hang) }}', '{{ $lh->thoi_gian_hen->format('Y-m-d\TH:i') }}')">
                                                        <i class="fas fa-user-check"></i> Tiếp nhận
                                                    </button>
                                                @endif

                                                {{-- NGUỒN HÀNG: Xác nhận --}}
                                                @if ($lh->trang_thai === 'cho_xac_nhan' && $nhanVien->hasRole(['admin', 'nguon_hang']))
                                                    <form action="{{ route('nhanvien.admin.lich-hen.xac-nhan', $lh) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf @method('PATCH')
                                                        <button class="btn btn-success btn-sm" title="Xác nhận lịch hẹn"
                                                            onclick="return confirm('Xác nhận lịch hẹn này?')">
                                                            <i class="fas fa-check"></i> Xác nhận
                                                        </button>
                                                    </form>
                                                    <button class="btn btn-danger btn-sm" title="Từ chối lịch hẹn"
                                                        onclick="openTuChoi({{ $lh->id }}, '{{ addslashes($lh->ten_khach_hang) }}')">
                                                        <i class="fas fa-times"></i> Từ chối
                                                    </button>
                                                @endif

                                                {{-- SALE: Hoàn thành --}}
                                                @if ($lh->trang_thai === 'da_xac_nhan' && $nhanVien->hasRole(['admin', 'sale']))
                                                    <form action="{{ route('nhanvien.admin.lich-hen.hoan-thanh', $lh) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf @method('PATCH')
                                                        <button class="btn btn-success btn-sm"
                                                            onclick="return confirm('Đánh dấu hoàn thành?')">
                                                            <i class="fas fa-flag-checkered"></i> Xong
                                                        </button>
                                                    </form>
                                                @endif

                                                {{-- Hủy --}}
                                                @if (!in_array($lh->trang_thai, ['hoan_thanh', 'huy']) && $nhanVien->hasRole(['admin', 'sale']))
                                                    <button class="btn btn-outline-danger btn-sm" title="Hủy lịch"
                                                        onclick="openHuy({{ $lh->id }})">
                                                        <i class="fas fa-ban"></i>
                                                    </button>
                                                @endif

                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
            @if ($lichHen->hasPages())
                <div class="card-footer bg-transparent d-flex justify-content-end">
                    {{ $lichHen->links() }}
                </div>
            @endif
        </div>

    </div>

    {{-- ── MODAL: Tiếp nhận lịch từ KH ── --}}
    <div class="modal fade" id="modalTiepNhan" tabindex="-1">
        <div class="modal-dialog">
            <form action="" method="POST" id="formTiepNhan">
                @csrf @method('PATCH')
                <div class="modal-content">
                    <div class="modal-header bg-warning-subtle">
                        <h5 class="modal-title"><i class="fas fa-user-check me-2"></i>Tiếp nhận & Phân công Nguồn hàng
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted mb-3">Lịch hẹn của: <strong id="tnKhach"></strong></p>
                        <div class="mb-3">
                            <label class="form-label fw-600">Nhân viên Nguồn hàng <span
                                    class="text-danger">*</span></label>
                            <select name="nhan_vien_nguon_hang_id" class="form-select" required>
                                <option value="">— Chọn nguồn hàng —</option>
                                @foreach ($dsNguonHang as $nv)
                                    <option value="{{ $nv->id }}">{{ $nv->ho_ten }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-600">Thời gian hẹn xác nhận <span
                                    class="text-danger">*</span></label>
                            <input type="datetime-local" name="thoi_gian_hen" id="tnThoiGian" class="form-control"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-600">Địa điểm</label>
                            <input type="text" name="dia_diem_hen" class="form-control"
                                placeholder="Địa chỉ xem nhà...">
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-600">Ghi chú cho Nguồn hàng</label>
                            <textarea name="ghi_chu_sale" class="form-control" rows="2" placeholder="Yêu cầu đặc biệt, lưu ý..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-warning text-dark fw-700">
                            <i class="fas fa-paper-plane me-1"></i> Gửi xác nhận đến Nguồn hàng
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- ── MODAL: Từ chối lịch ── --}}
    <div class="modal fade" id="modalTuChoi" tabindex="-1">
        <div class="modal-dialog">
            <form action="" method="POST" id="formTuChoi">
                @csrf @method('PATCH')
                <div class="modal-content">
                    <div class="modal-header bg-danger-subtle">
                        <h5 class="modal-title"><i class="fas fa-times-circle me-2 text-danger"></i>Từ chối lịch hẹn</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted">Từ chối lịch hẹn của: <strong id="tcKhach"></strong></p>
                        <div class="mb-0">
                            <label class="form-label fw-600">Lý do từ chối <span class="text-danger">*</span></label>
                            <textarea name="ly_do_tu_choi" class="form-control" rows="3"
                                placeholder="Không có khả năng, lịch bị trùng, khách không đủ điều kiện..." required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-danger fw-700">
                            <i class="fas fa-times me-1"></i> Xác nhận từ chối
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- ── MODAL: Hủy lịch ── --}}
    <div class="modal fade" id="modalHuy" tabindex="-1">
        <div class="modal-dialog modal-sm">
            <form action="" method="POST" id="formHuy">
                @csrf @method('PATCH')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-ban me-2"></i>Hủy lịch hẹn</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <textarea name="ly_do" class="form-control" rows="2" placeholder="Lý do hủy (không bắt buộc)"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Không</button>
                        <button type="submit" class="btn btn-danger btn-sm">Hủy lịch</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        function openTiepNhan(id, khach, thoiGian) {
            document.getElementById('formTiepNhan').action = `/nhan-vien/admin/lich-hen/${id}/tiep-nhan`;
            document.getElementById('tnKhach').textContent = khach;
            document.getElementById('tnThoiGian').value = thoiGian;
            new bootstrap.Modal(document.getElementById('modalTiepNhan')).show();
        }

        function openTuChoi(id, khach) {
            document.getElementById('formTuChoi').action = `/nhan-vien/admin/lich-hen/${id}/tu-choi`;
            document.getElementById('tcKhach').textContent = khach;
            new bootstrap.Modal(document.getElementById('modalTuChoi')).show();
        }

        function openHuy(id) {
            document.getElementById('formHuy').action = `/nhan-vien/admin/lich-hen/${id}/huy`;
            new bootstrap.Modal(document.getElementById('modalHuy')).show();
        }
    </script>
@endpush
