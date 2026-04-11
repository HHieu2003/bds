@extends('admin.layouts.master')
@section('title', 'Quản lý Điều phối Lịch Hẹn')

@push('styles')
    <style>
        .lh-card {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
            margin-bottom: 1rem;
            display: flex;
            overflow: hidden;
            transition: all 0.2s;
        }

        .lh-card:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.06);
            transform: translateY(-2px);
            border-color: #cbd5e1;
        }

        .lh-time-col {
            width: 140px;
            background: #f8fafc;
            border-right: 1px dashed #cbd5e1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 1.2rem;
            text-align: center;
        }

        .lh-time-col .date {
            font-size: 0.8rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
        }

        .lh-time-col .time {
            font-size: 1.8rem;
            font-weight: 900;
            color: var(--navy);
            line-height: 1;
            margin: 5px 0;
        }

        .lh-body {
            padding: 1.2rem 1.5rem;
            flex: 1;
            min-width: 0;
        }

        .target-box {
            background: #f1f5f9;
            padding: 10px 15px;
            border-radius: 8px;
            margin-top: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .lh-actions {
            width: 180px;
            border-left: 1px dashed #cbd5e1;
            background: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 1rem;
            gap: 0.5rem;
        }

        .status-ribbon {
            width: 6px;
            flex-shrink: 0;
        }

        .status-warning {
            background: #f59e0b;
        }

        .status-success {
            background: #10b981;
        }

        .status-info {
            background: #3b82f6;
        }

        .status-danger {
            background: #ef4444;
        }

        @media (max-width: 768px) {
            .lh-card {
                flex-direction: column;
            }

            .status-ribbon {
                width: 100%;
                height: 6px;
            }

            .lh-time-col {
                width: 100%;
                border-right: none;
                border-bottom: 1px dashed #cbd5e1;
                flex-direction: row;
                justify-content: space-between;
                padding: 1rem;
            }

            .lh-actions {
                width: 100%;
                border-left: none;
                border-top: 1px dashed #cbd5e1;
                flex-direction: row;
                flex-wrap: wrap;
            }

            .lh-actions .btn {
                flex: 1;
            }

            .target-box {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h1 class="page-header-title"><i class="fas fa-calendar-check text-primary me-2"></i> Trạm Điều Phối Lịch Hẹn</h1>
            <p class="text-muted mb-0">
                @if ($user->isSale())
                    Quản lý lịch đưa khách hàng đi xem BĐS của bạn.
                @elseif($user->isNguonHang())
                    Xét duyệt lịch xin xem nhà từ bộ phận Sale.
                @else
                    Quản lý toàn bộ lịch trình điều phối hệ thống.
                @endif
            </p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-warning shadow-sm position-relative">
                <i class="fas fa-bell me-1"></i> Cần xử lý
                @if ($thongKe['cho_xac_nhan'] > 0)
                    <span
                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">{{ $thongKe['cho_xac_nhan'] }}</span>
                @endif
            </button>
            @if ($user->isSale() || $user->isAdmin())
                <a href="{{ route('nhanvien.admin.lich-hen.create') }}" class="btn btn-primary shadow-sm"><i
                        class="fas fa-plus me-1"></i> Tạo Lịch Mới</a>
            @endif
        </div>
    </div>

    @include('frontend.partials.flash-messages')

    {{-- BỘ LỌC --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <form method="GET" class="row g-2 align-items-center">
                <div class="col-12 col-md-4">
                    <input type="text" name="search" class="form-control bg-light" value="{{ request('search') }}"
                        placeholder="🔍 Tìm SĐT, Mã căn...">
                </div>
                <div class="col-6 col-md-3">
                    <select name="ngay" class="form-select" onchange="this.form.submit()">
                        <option value="">-- Mọi thời gian --</option>
                        <option value="hom_nay" @selected(request('ngay') == 'hom_nay')>🔥 Hôm nay ({{ $thongKe['hom_nay'] }} lịch)
                        </option>
                        <option value="ngay_mai" @selected(request('ngay') == 'ngay_mai')>📅 Ngày mai</option>
                        <option value="tuan_nay" @selected(request('ngay') == 'tuan_nay')>🗓️ Tuần này</option>
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <select name="trang_thai" class="form-select" onchange="this.form.submit()">
                        <option value="">-- Tất cả trạng thái --</option>
                        <option value="cho_xac_nhan" @selected(request('trang_thai') == 'cho_xac_nhan')>🟠 Chờ Đầu chủ Xác nhận</option>
                        <option value="da_xac_nhan" @selected(request('trang_thai') == 'da_xac_nhan')>🟢 Đã Chốt (Sắp đi)</option>
                        <option value="hoan_thanh" @selected(request('trang_thai') == 'hoan_thanh')>✅ Hoàn thành</option>
                    </select>
                </div>
                <div class="col-12 col-md-2 text-end">
                    <button type="submit" class="btn btn-navy w-100"><i class="fas fa-filter"></i> Lọc</button>
                </div>
            </form>
        </div>
    </div>

    {{-- DANH SÁCH LỊCH HẸN --}}
    <div>
        @forelse($lichHens as $lh)
            @php
                $thoigian = \Carbon\Carbon::parse($lh->thoi_gian_hen);
                $bgRibbon = 'status-warning';
                $txtStatus = 'Chờ xử lý';

                if ($lh->trang_thai == 'da_xac_nhan') {
                    $bgRibbon = 'status-success';
                    $txtStatus = 'Đã chốt OK';
                } elseif (in_array($lh->trang_thai, ['bao_lai_gio', 'sale_doi_gio'])) {
                    $bgRibbon = 'status-info';
                    $txtStatus = 'Đang đổi giờ';
                } elseif ($lh->trang_thai == 'hoan_thanh') {
                    $bgRibbon = 'status-success';
                    $txtStatus = 'Xem xong';
                } elseif (in_array($lh->trang_thai, ['huy', 'tu_choi'])) {
                    $bgRibbon = 'status-danger';
                    $txtStatus = 'Đã Hủy';
                }
            @endphp

            <div class="lh-card">
                <div class="status-ribbon {{ $bgRibbon }}"></div>

                <div class="lh-time-col">
                    <div class="date">
                        {{ $thoigian->isToday() ? 'Hôm nay' : ($thoigian->isTomorrow() ? 'Ngày mai' : $thoigian->format('d/m/Y')) }}
                    </div>
                    <div class="time">{{ $thoigian->format('H:i') }}</div>
                    <div class="badge bg-light text-dark border w-100 py-2">{{ $txtStatus }}</div>
                </div>

                <div class="lh-body">
                    @if ($user->isSale() || $user->isAdmin())
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <div class="small text-muted text-uppercase fw-bold mb-1">Khách hàng của bạn</div>
                                <h5 class="fw-bold text-navy mb-1"><i
                                        class="fas fa-user-circle me-1"></i>{{ $lh->khachHang->ho_ten ?? $lh->ho_ten_khach }}
                                </h5>
                                <a href="tel:{{ $lh->sdt_khach ?? ($lh->khachHang->so_dien_thoai ?? '') }}"
                                    class="fw-bold text-success text-decoration-none fs-6"><i
                                        class="fas fa-phone-alt me-1"></i>{{ $lh->sdt_khach ?? ($lh->khachHang->so_dien_thoai ?? 'Trống') }}</a>
                            </div>
                        </div>
                        <div class="target-box border-start border-3 border-warning">
                            <div>
                                <span class="d-block small text-muted">BĐS Khách muốn xem:</span>
                                <a href="{{ route('frontend.bat-dong-san.show', $lh->batDongSan->slug ?? '') }}"
                                    target="_blank" class="fw-bold text-dark text-decoration-none">
                                    {{ $lh->batDongSan->ma_can ?? '' ? '[' . $lh->batDongSan->ma_can . ']' : '' }}
                                    {{ \Illuminate\Support\Str::limit($lh->batDongSan->ten_bat_dong_san ?? 'N/A', 50) }}
                                </a>
                            </div>
                            <div class="text-end mt-2 mt-md-0">
                                <span class="d-block small text-muted">Đầu chủ (Nguồn hàng):</span>
                                {{-- ĐÃ FIX: Lấy tên người nắm nguồn từ BĐS chứ không gọi qua lịch hẹn --}}
                                <strong class="text-primary"><i
                                        class="fas fa-key me-1"></i>{{ $lh->batDongSan->nhanVienPhuTrach->ho_ten ?? 'Hệ thống chung' }}</strong>
                            </div>
                        </div>
                    @endif

                    @if ($user->isNguonHang() && !$user->isAdmin())
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <div class="small text-muted text-uppercase fw-bold mb-1">Bất động sản của bạn</div>
                                <h5 class="fw-bold text-navy mb-1">
                                    <i class="fas fa-building me-1"></i>
                                    {{ $lh->batDongSan->ma_can ?? '' ? '[' . $lh->batDongSan->ma_can . ']' : '' }}
                                    {{ \Illuminate\Support\Str::limit($lh->batDongSan->ten_bat_dong_san ?? '', 50) }}
                                </h5>
                                <div class="text-muted small"><i
                                        class="fas fa-map-marker-alt me-1"></i>{{ $lh->batDongSan->dia_chi ?? '' }}</div>
                            </div>
                        </div>
                        <div class="target-box border-start border-3 border-success">
                            <div>
                                <span class="d-block small text-muted">Liên hệ Chủ nhà để xin khóa:</span>
                                @if ($lh->batDongSan && $lh->batDongSan->chuNha)
                                    <strong class="text-dark"><i
                                            class="fas fa-user-tie me-1"></i>{{ $lh->batDongSan->chuNha->ho_ten }}</strong>
                                    <a href="tel:{{ $lh->batDongSan->chuNha->so_dien_thoai }}"
                                        class="text-success ms-2 fw-bold text-decoration-none"><i
                                            class="fas fa-phone-alt"></i> {{ $lh->batDongSan->chuNha->so_dien_thoai }}</a>
                                @else
                                    <span class="text-danger fst-italic">BĐS này chưa gán Chủ nhà!</span>
                                @endif
                            </div>
                            <div class="text-end mt-2 mt-md-0">
                                <span class="d-block small text-muted">Sale dẫn khách:</span>
                                <strong class="text-primary"><i
                                        class="fas fa-user me-1"></i>{{ $lh->nhanVienSale->ho_ten ?? 'N/A' }}</strong>
                            </div>
                        </div>
                    @endif

                    @if ($lh->ghi_chu)
                        <div class="mt-2 text-danger small fw-medium fst-italic"><i class="fas fa-comment-dots me-1"></i>
                            Ghi chú: "{{ $lh->ghi_chu }}"</div>
                    @endif
                </div>

                <div class="lh-actions">
                    @if ($user->isSale() || $user->isAdmin())
                        @if (in_array($lh->trang_thai, ['cho_xac_nhan', 'bao_lai_gio']))
                            <div class="text-center small text-warning fw-bold mb-2"><i class="fas fa-hourglass-half"></i>
                                Chờ Đầu chủ duyệt</div>
                            <button class="btn btn-sm btn-outline-info fw-bold mb-2" data-bs-toggle="modal"
                                data-bs-target="#modalDoiGio" data-id="{{ $lh->id }}" data-role="sale"><i
                                    class="fas fa-clock me-1"></i> Xin đổi giờ</button>
                            <form action="{{ route('nhanvien.admin.lich-hen.huy', $lh->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-light border text-danger fw-bold w-100"
                                    onclick="return confirm('Xác nhận khách hủy lịch?')"><i class="fas fa-times me-1"></i>
                                    Khách báo Hủy</button>
                            </form>
                        @elseif($lh->trang_thai == 'da_xac_nhan')
                            <form action="{{ route('nhanvien.admin.lich-hen.hoan-thanh', $lh->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-success fw-bold shadow-sm mb-2 w-100"
                                    onclick="return confirm('Xác nhận khách đã xem xong?')"><i
                                        class="fas fa-check-circle me-1"></i> Khách Xem Xong</button>
                            </form>
                        @endif
                    @endif

                    @if ($user->isNguonHang() || $user->isAdmin())
                        @if (in_array($lh->trang_thai, ['cho_xac_nhan', 'sale_doi_gio']))
                            <form action="{{ route('nhanvien.admin.lich-hen.xac-nhan', $lh->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-success fw-bold shadow-sm mb-2 w-100"><i
                                        class="fas fa-key me-1"></i> Duyệt (Có Khóa)</button>
                            </form>
                            <button class="btn btn-sm btn-outline-warning fw-bold mb-2" data-bs-toggle="modal"
                                data-bs-target="#modalDoiGio" data-id="{{ $lh->id }}" data-role="nguon"><i
                                    class="fas fa-history me-1"></i> Báo lùi giờ</button>
                            <form action="{{ route('nhanvien.admin.lich-hen.tu-choi', $lh->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-light border text-danger w-100"
                                    onclick="return confirm('Từ chối lịch xem này?')"><i class="fas fa-lock me-1"></i> Từ
                                    chối (Đã bán/Chủ bận)</button>
                            </form>
                        @elseif($lh->trang_thai == 'da_xac_nhan')
                            <div class="text-center small text-success fw-bold mb-2"><i class="fas fa-check-circle"></i>
                                Đã cấp quyền xem</div>
                        @endif
                    @endif

                    @if (in_array($lh->trang_thai, ['hoan_thanh', 'huy', 'tu_choi']))
                        <div class="text-center text-muted"><i
                                class="fas fa-folder-open mb-1 fs-4 opacity-50 d-block"></i> Đã lưu trữ</div>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-5 bg-white rounded-3 shadow-sm border">
                <i class="fas fa-calendar-times fs-1 text-muted opacity-25 mb-3 d-block"></i>
                <h5 class="fw-bold text-muted">Trống lịch!</h5>
                <p class="text-muted mb-0">Không có lịch hẹn nào phù hợp với bộ lọc hiện tại.</p>
            </div>
        @endforelse

        @if ($lichHens->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $lichHens->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

    {{-- MODAL CHUNG ĐỂ ĐỔI GIỜ --}}
    <div class="modal fade" id="modalDoiGio" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form id="frmDoiGio" class="modal-content border-0 shadow" method="POST" action="#">
                @csrf @method('PATCH')
                <div class="modal-header bg-light border-bottom-0">
                    <h5 class="modal-title fw-bold text-info"><i class="fas fa-clock me-2"></i>Thương lượng Đổi Giờ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-info border-0 bg-info bg-opacity-10 small mb-3">
                        Bạn đang gửi đề xuất thay đổi thời gian xem nhà cho đối tác.
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Giờ đề xuất mới <span class="text-danger">*</span></label>
                        <input type="datetime-local" name="thoi_gian_hen" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Lý do đổi giờ / Lời nhắn</label>
                        <textarea name="ghi_chu" class="form-control" rows="2"
                            placeholder="Ví dụ: Chủ nhà bận đón con, xin lùi lại 30 phút..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top-0">
                    <button type="button" class="btn btn-secondary border bg-white text-dark"
                        data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-info text-white fw-bold"><i
                            class="fas fa-paper-plane me-2"></i>Gửi Đề Xuất</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('[data-bs-target="#modalDoiGio"]').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;
                const role = this.dataset.role;
                const form = document.getElementById('frmDoiGio');

                if (role === 'sale') {
                    form.action = `{{ route('nhanvien.admin.lich-hen.sale-doi-gio', '__ID__') }}`.replace(
                        '__ID__', id);
                } else {
                    form.action = `{{ route('nhanvien.admin.lich-hen.bao-lai-gio', '__ID__') }}`.replace(
                        '__ID__', id);
                }
            });
        });
    </script>
@endpush
