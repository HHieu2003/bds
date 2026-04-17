@extends('admin.layouts.master')
@section('title', 'Hồ Sơ: ' . $khachHang->ho_ten)

@push('styles')
    <style>
        /* Chỉ những gì Bootstrap không hỗ trợ trực tiếp */
        .avatar-circle-lg {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.4rem;
        }

        .profile-header-bg {
            height: 72px;
            background: linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%);
            border-radius: .375rem .375rem 0 0;
        }

        /* Nav tabs — override Bootstrap để không conflict admin.css */
        .kh-nav-tabs {
            border-bottom: 2px solid #dee2e6;
            flex-wrap: nowrap;
            overflow-x: auto;
        }

        .kh-nav-tabs .nav-link {
            color: #6c757d;
            font-weight: 600;
            font-size: .85rem;
            border: none;
            border-bottom: 2px solid transparent;
            margin-bottom: -2px;
            padding: 10px 18px;
            white-space: nowrap;
            background: none;
        }

        .kh-nav-tabs .nav-link.active {
            color: #0d6efd;
            border-bottom-color: #0d6efd;
        }

        .kh-nav-tabs .nav-link:hover:not(.active) {
            color: #343a40;
            background-color: #f8f9fa;
        }

        /* Timeline */
        .tl-dot {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .68rem;
            font-weight: 700;
            color: #fff;
            flex-shrink: 0;
        }

        .tl-line {
            width: 2px;
            flex: 1;
            background: #dee2e6;
            margin-top: 3px;
        }

        .tl-bubble {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: .875rem;
            white-space: pre-line;
            line-height: 1.6;
            color: #495057;
        }

        /* Lịch hẹn date block */
        .lh-date-box {
            width: 52px;
            background: #e7f3ff;
            border-radius: 8px;
            padding: 6px 4px;
            text-align: center;
            flex-shrink: 0;
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
            padding: .25rem .75rem;
            font-weight: 600;
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

        /* Modal chỉnh sửa hồ sơ */
        .kh-edit-modal .modal-dialog {
            max-width: 780px;
        }

        .kh-edit-modal .modal-content {
            max-height: calc(100vh - 2.5rem);
        }

        .kh-edit-modal .modal-body {
            max-height: calc(100vh - 190px);
            overflow-y: auto;
            padding: 1rem 1.25rem;
        }

        .kh-edit-modal .form-label {
            font-size: .78rem;
            margin-bottom: .35rem;
        }

        @media (max-width: 575.98px) {
            .kh-edit-modal .modal-body {
                max-height: calc(100vh - 170px);
                padding: .9rem 1rem;
            }
        }
    </style>
@endpush

@section('content')
    @php
        $tt = $mucDoTiemNang[$khachHang->muc_do_tiem_nang] ?? ['label' => '?', 'color' => 'secondary'];
        $color = $tt['color']; // danger / warning / secondary
        $initial =
            collect(explode(' ', trim($khachHang->ho_ten)))
                ->filter()
                ->map(fn($w) => mb_strtoupper(mb_substr($w, 0, 1)))
                ->last() ?? '?';
        $nguonMap = [
            'facebook' => ['fab fa-facebook', 'text-primary', 'Facebook'],
            'zalo' => ['fas fa-comment-dots', 'text-info', 'Zalo'],
            'gioi_thieu' => ['fas fa-user-friends', 'text-success', 'Giới thiệu'],
            'website' => ['fas fa-globe', 'text-purple', 'Website'],
            'sale' => ['fas fa-headset', 'text-warning', 'Sale'],
            'default' => ['fas fa-question-circle', 'text-muted', 'Khác'],
        ];
        $ng = $nguonMap[$khachHang->nguon_khach_hang] ?? $nguonMap['default'];
        $toneClass = match ($color) {
            'danger' => 'tone-danger',
            'warning' => 'tone-warning',
            default => 'tone-secondary',
        };
        $emailVerified = !is_null($khachHang->email_xac_thuc_at);
    @endphp

    <div class="container-fluid py-2 px-3">

        {{-- Breadcrumb --}}
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb mb-0" style="font-size:.83rem;">
                <li class="breadcrumb-item">
                    <a href="{{ route('nhanvien.admin.khach-hang.index') }}" class="text-decoration-none">
                        <i class="fas fa-users me-1"></i>Khách hàng
                    </a>
                </li>
                <li class="breadcrumb-item active fw-semibold">{{ $khachHang->ho_ten }}</li>
            </ol>
        </nav>

        <div class="row g-4">

            {{-- ══ CỘT TRÁI ══ --}}
            <div class="col-lg-4 col-xl-3">

                {{-- Profile card --}}
                <div class="card border-0 shadow-sm mb-1 overflow-hidden">

                    <div class="card-body text-center pt-1 pb-1">

                        <h5 class="fw-bold mb-1">{{ $khachHang->ho_ten }}</h5>


                        {{-- Badge dùng $tt['color'] từ controller --}}
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


                        <hr class="my-2">

                        {{-- Thông tin liên hệ --}}
                        <div class="text-start">
                            @foreach ([['fas fa-phone text-success', 'SĐT', $khachHang->so_dien_thoai], ['fas fa-envelope text-primary', 'Email', $khachHang->email]] as [$icn, $lbl, $val])
                                <div class="d-flex align-items-start gap-2 mb-2" style="font-size:.85rem;">
                                    <i class="{{ $icn }} mt-1 flex-shrink-0"
                                        style="width:16px;font-size:.78rem;"></i>

                                    <div class="text-uppercase text-muted" style="font-size:.68rem;letter-spacing:.4px;">
                                        {{ $lbl }}</div>
                                    <div class="{{ $val ? 'fw-semibold text-dark' : 'text-muted fst-italic' }}">
                                        {{ $val ?? 'Chưa cập nhật' }}</div>

                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- CRM Meta --}}
                <div class="card border-0 shadow-sm mb-1">

                    <div class="card-body py-2">
                        <div class="row g-2" style="font-size:.83rem;">
                            <div class="col-6">
                                <div class="text-muted"
                                    style="font-size:.7rem;text-transform:uppercase;letter-spacing:.4px;">Nguồn</div>
                                <div class="fw-semibold"><i
                                        class="{{ $ng[0] }} {{ $ng[1] }} me-1"></i>{{ $ng[2] }}
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-muted"
                                    style="font-size:.7rem;text-transform:uppercase;letter-spacing:.4px;">Phụ trách</div>
                                <div class="fw-semibold">{{ optional($khachHang->nhanVienPhuTrach)->ho_ten ?? '–' }}</div>
                            </div>
                            <div class="col-6">
                                <div class="text-muted"
                                    style="font-size:.7rem;text-transform:uppercase;letter-spacing:.4px;">Tạo ngày</div>
                                <div>{{ $khachHang->created_at->format('d/m/Y') }}</div>
                            </div>
                            <div class="col-6">
                                <div class="text-muted"
                                    style="font-size:.7rem;text-transform:uppercase;letter-spacing:.4px;">Liên hệ cuối</div>
                                <div>
                                    {{ $khachHang->lien_he_cuoi_at ? \Carbon\Carbon::parse($khachHang->lien_he_cuoi_at)->diffForHumans() : '–' }}
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-muted"
                                    style="font-size:.7rem;text-transform:uppercase;letter-spacing:.4px;">Email xác thực
                                </div>
                                <div>
                                    <span
                                        class="badge {{ $emailVerified ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-warning-subtle text-warning border border-warning-subtle' }}">
                                        <i
                                            class="fas {{ $emailVerified ? 'fa-check-circle' : 'fa-exclamation-circle' }} me-1"></i>
                                        {{ $emailVerified ? 'Đã xác thực' : 'Chưa xác thực' }}
                                    </span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- Nút mở modal chỉnh sửa hồ sơ --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-3">
                        <button type="button" class="btn btn-warning w-100 fw-semibold" data-bs-toggle="modal"
                            data-bs-target="#modalEditKhachHang">
                            <i class="fas fa-edit me-1"></i>Chỉnh sửa hồ sơ
                        </button>
                    </div>
                </div>

            </div>{{-- end col trái --}}

            {{-- ══ CỘT PHẢI: Tabs ══ --}}
            <div class="col-lg-8 col-xl-9">

                {{-- Tab nav --}}
                <ul class="nav kh-nav-tabs mb-0" id="khTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-nhatky" type="button">
                            <i class="fas fa-clipboard-list me-1"></i>Nhật Ký Chăm Sóc
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-lichhen" type="button">
                            <i class="fas fa-calendar-alt me-1"></i>Lịch Hẹn
                            <span class="badge bg-info rounded-pill ms-1">{{ $lichHens->count() }}</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-yeuthich" type="button">
                            <i class="fas fa-heart text-danger me-1"></i>Yêu Thích
                            <span
                                class="badge bg-danger rounded-pill ms-1">{{ $khachHang->danhSachYeuThich->count() }}</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-actions" type="button">
                            <i class="fas fa-bolt me-1"></i>Hành Động
                        </button>
                    </li>
                </ul>

                <div class="tab-content border border-top-0 rounded-bottom bg-white shadow-sm p-4">

                    {{-- NHẬT KÝ --}}
                    <div class="tab-pane fade show active" id="tab-nhatky" role="tabpanel">
                        <form action="{{ route('nhanvien.admin.khach-hang.nhat-ky', $khachHang) }}" method="POST"
                            class="mb-4">
                            @csrf
                            <div class="p-3 rounded-3 bg-light border border-dashed">
                                <p class="text-uppercase text-muted fw-bold mb-2"
                                    style="font-size:.72rem;letter-spacing:.5px;">
                                    <i class="fas fa-plus-circle text-primary me-1"></i>Thêm nhật ký chăm sóc
                                </p>
                                <textarea name="noi_dung_cham_soc" class="form-control border-0 bg-transparent shadow-none" rows="3" required
                                    style="resize:none;font-size:.9rem;" placeholder="Ghi lại nội dung cuộc gọi, gặp mặt, tình trạng khách..."></textarea>
                                <div class="d-flex align-items-center justify-content-between mt-2 flex-wrap gap-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="small fw-semibold text-muted">Cập nhật tiềm năng:</span>
                                        <select name="muc_do_tiem_nang" class="form-select form-select-sm"
                                            style="width:auto;">
                                            @foreach ($mucDoTiemNang as $key => $item)
                                                <option value="{{ $key }}"
                                                    {{ $khachHang->muc_do_tiem_nang === $key ? 'selected' : '' }}>
                                                    {{ $item['label'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-sm px-4">
                                        <i class="fas fa-save me-1"></i>Lưu nhật ký
                                    </button>
                                </div>
                            </div>
                        </form>

                        {{-- Timeline --}}
                        @if ($khachHang->ghi_chu_noi_bo)
                            @php $entries = preg_split('/\n\n---\n/', $khachHang->ghi_chu_noi_bo); @endphp
                            <div>
                                @foreach ($entries as $i => $entry)
                                    @if (trim($entry))
                                        @php
                                            preg_match('/^\[(.+?)\]\s(.+?):\n(.*)/s', trim($entry), $m);
                                            $tgian = $m[1] ?? null;
                                            $nguoiGhi = $m[2] ?? null;
                                            $noiDung = $m[3] ?? trim($entry);
                                        @endphp
                                        <div class="d-flex gap-3 mb-3">
                                            <div class="d-flex flex-column align-items-center">
                                                <div class="tl-dot {{ $i === 0 ? 'bg-primary' : 'bg-secondary' }}">
                                                    {{ $i + 1 }}</div>
                                                @if (!$loop->last)
                                                    <div class="tl-line"></div>
                                                @endif
                                            </div>
                                            <div class="flex-grow-1 pb-2">
                                                <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                                                    @if ($nguoiGhi)
                                                        <span class="fw-semibold"
                                                            style="font-size:.84rem;">{{ $nguoiGhi }}</span>
                                                    @endif
                                                    @if ($tgian)
                                                        <span class="text-muted" style="font-size:.76rem;">
                                                            <i class="far fa-clock me-1"></i>{{ $tgian }}
                                                        </span>
                                                    @endif
                                                    @if ($i === 0)
                                                        <span class="badge bg-primary" style="font-size:.65rem;">Mới
                                                            nhất</span>
                                                    @endif
                                                </div>
                                                <div class="tl-bubble">{{ $noiDung }}</div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-2 text-muted">
                                <i class="fas fa-clipboard fa-3x d-block mb-3 opacity-25"></i>
                                <p class="fw-semibold text-secondary mb-1">Chưa có nhật ký nào</p>
                                <small>Thêm ghi chú đầu tiên để bắt đầu theo dõi</small>
                            </div>
                        @endif
                    </div>

                    {{-- LỊCH HẸN --}}
                    <div class="tab-pane fade" id="tab-lichhen" role="tabpanel">
                        @if ($lichHens->isEmpty())
                            <div class="text-center py-5 text-muted">
                                <i class="fas fa-calendar-times fa-3x d-block mb-3 opacity-25"></i>
                                <p class="fw-semibold text-secondary mb-1">Chưa có lịch hẹn nào</p>
                                <small>Tạo lịch hẹn để đưa khách đi xem nhà</small>
                            </div>
                        @else
                            <div class="d-flex flex-column gap-2">
                                @foreach ($lichHens as $lh)
                                    @php
                                        $stMap = [
                                            'cho_xac_nhan' => ['warning', 'Chờ xác nhận', 'far fa-clock'],
                                            'da_xac_nhan' => ['info', 'Đã xác nhận', 'fas fa-check'],
                                            'da_xem' => ['success', 'Đã đi xem', 'fas fa-check-double'],
                                            'huy' => ['danger', 'Đã hủy', 'fas fa-times'],
                                        ];
                                        $st = $stMap[$lh->trang_thai] ?? ['secondary', '?', 'fas fa-question'];
                                    @endphp
                                    <div class="d-flex gap-3 p-3 border rounded-3 bg-white">
                                        <div class="lh-date-box">
                                            <div class="fw-bold text-primary lh-1" style="font-size:1.2rem;">
                                                {{ \Carbon\Carbon::parse($lh->thoi_gian_hen)->format('d') }}
                                            </div>
                                            <div class="text-muted text-uppercase"
                                                style="font-size:.64rem;margin-top:2px;">
                                                {{ \Carbon\Carbon::parse($lh->thoi_gian_hen)->isoFormat('MMM YY') }}
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 min-width-0">
                                            <div class="d-flex justify-content-between align-items-start gap-2 mb-1">
                                                <a href="{{ $lh->batDongSan ? route('nhanvien.admin.bat-dong-san.edit', $lh->batDongSan) : '#' }}"
                                                    class="fw-semibold text-dark text-decoration-none text-truncate">
                                                    {{ optional($lh->batDongSan)->ten ?? 'BĐS không xác định' }}
                                                </a>
                                                {{-- Badge dùng $st[0] là Bootstrap color --}}
                                                <span
                                                    class="badge bg-{{ $st[0] }} bg-opacity-15 text-{{ $st[0] }} border border-{{ $st[0] }} rounded-pill flex-shrink-0"
                                                    style="font-size:.72rem;">
                                                    <i class="{{ $st[2] }} me-1"></i>{{ $st[1] }}
                                                </span>
                                            </div>
                                            <div class="text-muted d-flex gap-3 flex-wrap" style="font-size:.8rem;">
                                                <span><i
                                                        class="fas fa-map-marker-alt text-danger me-1"></i>{{ optional(optional($lh->batDongSan)->khuVuc)->ten ?? '–' }}</span>
                                                <span><i
                                                        class="far fa-clock me-1"></i>{{ \Carbon\Carbon::parse($lh->thoi_gian_hen)->format('H:i') }}</span>
                                                @if ($lh->nhanVienNguonHang)
                                                    <span><i
                                                            class="fas fa-user me-1"></i>{{ $lh->nhanVienNguonHang->ho_ten }}</span>
                                                @endif
                                            </div>
                                            @if ($lh->ghi_chu)
                                                <div class="mt-1 text-muted fst-italic" style="font-size:.78rem;">
                                                    "{{ Str::limit($lh->ghi_chu, 80) }}"
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- YÊU THÍCH --}}
                    <div class="tab-pane fade" id="tab-yeuthich" role="tabpanel">
                        @if ($khachHang->danhSachYeuThich->isEmpty())
                            <div class="text-center py-5 text-muted">
                                <i class="fas fa-heart fa-3x d-block mb-3 opacity-25"></i>
                                <p class="fw-semibold text-secondary mb-1">Chưa yêu thích BĐS nào</p>
                            </div>
                        @else
                            <div class="row g-3">
                                @foreach ($khachHang->danhSachYeuThich as $bds)
                                    <div class="col-sm-6 col-xl-4">
                                        @php
                                            $anhThuNho = null;
                                            if (!empty($bds->album_anh)) {
                                                $album = is_string($bds->album_anh) ? json_decode($bds->album_anh, true) : $bds->album_anh;
                                                if (is_array($album) && count($album) > 0) {
                                                    $anhThuNho = asset('storage/' . $album[0]);
                                                }
                                            }
                                            
                                            $giaHienThi = $bds->nhu_cau == 'ban' 
                                                ? ($bds->gia ? number_format($bds->gia, 0, ',', '.') . ' đ' : 'Thỏa thuận') 
                                                : ($bds->gia_thue ? number_format($bds->gia_thue, 0, ',', '.') . ' đ/tháng' : 'Thỏa thuận');
                                        @endphp
                                        <div class="card h-100 border shadow-sm overflow-hidden">
                                            {{-- Thumbnail --}}
                                            @if($anhThuNho)
                                                <div style="height: 140px; overflow: hidden; position: relative;">
                                                    <img src="{{ $anhThuNho }}" alt="{{ $bds->tieu_de }}" class="w-100 h-100 object-fit-cover">
                                                    <span class="badge {{ $bds->nhu_cau == 'ban' ? 'bg-primary' : 'bg-info' }} position-absolute top-0 start-0 m-2">
                                                        {{ $bds->nhu_cau == 'ban' ? 'Đang bán' : 'Cho thuê' }}
                                                    </span>
                                                </div>
                                            @else
                                                <div class="bg-light d-flex align-items-center justify-content-center position-relative" style="height: 140px;">
                                                    <i class="fas fa-image text-muted fa-2x"></i>
                                                    <span class="badge {{ $bds->nhu_cau == 'ban' ? 'bg-primary' : 'bg-info' }} position-absolute top-0 start-0 m-2">
                                                        {{ $bds->nhu_cau == 'ban' ? 'Đang bán' : 'Cho thuê' }}
                                                    </span>
                                                </div>
                                            @endif

                                            <div class="card-body p-3">
                                                <h6 class="fw-semibold mb-2 text-truncate" title="{{ $bds->tieu_de }}">
                                                    {{ $bds->tieu_de ?? 'BĐS #' . $bds->id }}
                                                </h6>
                                                
                                                <p class="text-muted mb-2 text-truncate" style="font-size:.78rem;">
                                                    <i class="fas fa-map-marker-alt text-danger me-1"></i>{{ optional($bds->duAn)->ten_du_an ?? (optional($bds->khuVuc)->ten ?? 'Chưa cập nhật') }}
                                                </p>
                                                
                                                <div class="d-flex align-items-center gap-3 mb-2 text-muted" style="font-size: .8rem;">
                                                    <span><i class="fas fa-vector-square me-1"></i>{{ $bds->dien_tich ? $bds->dien_tich . ' m²' : '--' }}</span>
                                                    <span><i class="fas fa-bed me-1"></i>{{ $bds->so_phong_ngu ?? '--' }}</span>
                                                </div>
                                                
                                                <p class="fw-bold text-danger mb-0" style="font-size: 1.05rem;">
                                                    {{ $giaHienThi }}
                                                </p>
                                            </div>

                                            <div class="card-footer bg-transparent pt-0 border-top-0 px-3 pb-3">
                                                <a href="{{ route('nhanvien.admin.bat-dong-san.edit', $bds) }}"
                                                    class="btn btn-outline-primary btn-sm w-100">
                                                    <i class="fas fa-eye me-1"></i>Xem Chi Tiết BĐS
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- HÀNH ĐỘNG --}}
                    <div class="tab-pane fade" id="tab-actions" role="tabpanel">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="card border-0 bg-light h-100">
                                    <div class="card-body p-3">
                                        <h6 class="fw-bold mb-2"><i class="fas fa-calendar-plus text-primary me-2"></i>Tạo
                                            lịch hẹn</h6>
                                        <p class="text-muted small mb-3">Đặt lịch xem nhà cho khách hàng này</p>
                                        <a href="{{ route('nhanvien.admin.lich-hen.create', ['khach_hang_id' => $khachHang->id]) }}"
                                            class="btn btn-primary w-100">
                                            <i class="fas fa-calendar-plus me-1"></i>Đặt lịch xem nhà
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @if (!$nhanVien->isSale())
                                <div class="col-md-6">
                                    <div class="card border-danger border-opacity-25 h-100">
                                        <div class="card-body p-3">
                                            <h6 class="fw-bold text-danger mb-2"><i class="fas fa-trash-alt me-2"></i>Xóa
                                                khách hàng</h6>
                                            <p class="text-muted small mb-3">Xóa vĩnh viễn toàn bộ hồ sơ</p>
                                            <form action="{{ route('nhanvien.admin.khach-hang.destroy', $khachHang) }}"
                                                method="POST"
                                                onsubmit="return confirm('Xóa vĩnh viễn khách hàng {{ addslashes($khachHang->ho_ten) }}?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger w-100">
                                                    <i class="fas fa-trash-alt me-1"></i>Xóa hồ sơ
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>{{-- end tab-content --}}
            </div>{{-- end col phải --}}
        </div>{{-- end row --}}
    </div>

    {{-- Modal chỉnh sửa hồ sơ --}}
    <div class="modal fade kh-edit-modal" id="modalEditKhachHang" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 shadow">
                <form action="{{ route('nhanvien.admin.khach-hang.update', $khachHang) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">
                            <i class="fas fa-user-edit text-warning me-2"></i>Chỉnh sửa hồ sơ khách hàng
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row g-2">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Họ tên *</label>
                                <input type="text" name="ho_ten" class="form-control form-control-sm"
                                    value="{{ $khachHang->ho_ten }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">SĐT *</label>
                                <input type="text" name="so_dien_thoai" class="form-control form-control-sm"
                                    value="{{ $khachHang->so_dien_thoai }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Email</label>
                                <input type="email" name="email" class="form-control form-control-sm"
                                    value="{{ $khachHang->email }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tiềm năng</label>
                                <select name="muc_do_tiem_nang" class="form-select form-select-sm">
                                    @foreach ($mucDoTiemNang as $key => $item)
                                        <option value="{{ $key }}"
                                            {{ $khachHang->muc_do_tiem_nang === $key ? 'selected' : '' }}>
                                            {{ $item['label'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Mật khẩu mới</label>
                                <input type="password" name="password" class="form-control form-control-sm"
                                    minlength="6" placeholder="Để trống nếu không đổi mật khẩu">
                                <small class="text-muted">Chỉ nhập khi cần đổi mật khẩu đăng nhập.</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nhập lại mật khẩu mới</label>
                                <input type="password" name="password_confirmation" class="form-control form-control-sm"
                                    minlength="6" placeholder="Nhập lại mật khẩu mới">
                            </div>

                            @if (!$nhanVien->isSale())
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Giao Sale</label>
                                    <select name="nhan_vien_phu_trach_id" class="form-select form-select-sm">
                                        <option value="">– Chưa gán –</option>
                                        @foreach ($dsSale as $sale)
                                            <option value="{{ $sale->id }}"
                                                {{ $khachHang->nhan_vien_phu_trach_id === $sale->id ? 'selected' : '' }}>
                                                {{ $sale->ho_ten }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                        </div>

                        @if ($nhanVien->hasRole('admin'))
                            <hr class="my-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="emailVerifiedSwitch"
                                    name="email_verified" value="1" {{ $emailVerified ? 'checked' : '' }}>
                                <label class="form-check-label" for="emailVerifiedSwitch">
                                    Đánh dấu tài khoản đã xác thực email
                                </label>
                            </div>
                            <small class="text-muted d-block mt-1">Chỉ Admin mới có quyền thay đổi trạng thái xác
                                thực.</small>
                        @endif
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-warning fw-semibold">
                            <i class="fas fa-save me-1"></i>Lưu thay đổi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
