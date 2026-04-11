@extends('admin.layouts.master')
@section('title', 'Quản lý Yêu cầu Liên hệ (Leads)')

@push('styles')
    <style>
        /* Chỉnh Dropdown Trạng thái */
        .status-select {
            font-size: 0.75rem;
            font-weight: 700;
            padding: 6px 20px 6px 10px;
            border-radius: 50px;
            border: 2px solid transparent;
            cursor: pointer;
            outline: none;
            appearance: none;
            text-align: left;
            width: 100%;
        }

        .status-wrapper {
            position: relative;
            display: inline-block;
            width: 125px;
        }

        .status-wrapper::after {
            content: '\f0d7';
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 0.7rem;
            pointer-events: none;
        }

        /* Hiệu ứng chớp nháy thu hút Sale nhận Lead */
        .pulse-btn {
            animation: pulse-animation 2s infinite;
        }

        @keyframes pulse-animation {
            0% {
                box-shadow: 0 0 0 0 rgba(231, 76, 60, 0.7);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(231, 76, 60, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(231, 76, 60, 0);
            }
        }

        .lead-row-new {
            background-color: #fff9f9 !important;
            border-left: 4px solid #e74c3c;
        }

        /* Modal Enhancement */
        .modal-content {
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1) !important;
        }

        .shadow-xl {
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15) !important;
        }

        .btn-close-white {
            filter: brightness(0) invert(1);
        }

        /* Cursor pointer trên cột ngữ cảnh tư vấn */
        table tbody td:nth-child(2) {
            transition: background-color 0.2s ease;
        }

        table tbody tr:hover td:nth-child(2) {
            background-color: #f0f7ff !important;
        }

        /* Mobile UX */
        @media (max-width: 768px) {
            .mobile-card {
                background: #fff;
                border-radius: 12px;
                border: 1px solid #eaeaea;
                margin-bottom: 1rem;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.03);
                overflow: hidden;
            }

            .mobile-card-header {
                padding: 12px;
                border-bottom: 1px dashed #eaeaea;
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
            }

            .mobile-card-body {
                padding: 12px;
            }

            .mobile-card-footer {
                padding: 10px 12px;
                background: #fafafa;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
        }
    </style>
@endpush

@section('content')
    <div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h1 class="page-header-title"><i class="fas fa-headset text-primary me-2"></i> Pipeline: Yêu cầu Tư vấn
                @if ($leadChuaNhan > 0)
                    <span class="badge bg-danger rounded-pill pulse-btn ms-2"
                        style="font-size: 0.75rem; vertical-align: middle;">{{ $leadChuaNhan }} Lead chưa nhận</span>
                @endif
            </h1>
            {{-- THỐNG KÊ NHANH --}}
            <div style="font-size:.78rem;color:var(--text-sub);">
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <span><strong>{{ number_format($thongKe['tat_ca'] ?? 0) }}</strong> tổng</span>
                    <span
                        style="width:4px;height:4px;border-radius:50%;background:var(--text-muted);display:inline-block"></span>
                    <span style="color:#0d6efd"><strong>{{ number_format($thongKe['moi'] ?? 0) }}</strong> 🆕 mới</span>
                    <span
                        style="width:4px;height:4px;border-radius:50%;background:var(--text-muted);display:inline-block"></span>
                    <span style="color:#FF9800"><strong>{{ number_format($thongKe['dang_xu_ly'] ?? 0) }}</strong> ⏳ đang xử
                        lý</span>
                    <span
                        style="width:4px;height:4px;border-radius:50%;background:var(--text-muted);display:inline-block"></span>
                    <span style="color:#28a745"><strong>{{ number_format($thongKe['da_chot'] ?? 0) }}</strong> ✅ đã
                        chốt</span>
                </div>
            </div>
        </div>
    </div>

    @include('frontend.partials.flash-messages')


    {{-- BỘ LỌC --}}
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body p-3">
            <form method="GET" class="row g-2 align-items-center">
                <div class="col-12 col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0"
                            value="{{ request('search') }}" placeholder="Tìm SĐT, Tên khách hàng...">
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <select name="trang_thai" class="form-select" onchange="this.form.submit()">
                        <option value="">-- Tất cả trạng thái --</option>
                        @foreach (\App\Models\YeuCauLienHe::TRANG_THAI as $v => $info)
                            <option value="{{ $v }}" @selected(request('trang_thai') == $v)>{{ $info['label'] }}</option>
                        @endforeach
                    </select>
                </div>
                @if (!$nhanVienAuth->isSale())
                    <div class="col-6 col-md-3">
                        <select name="nhan_vien" class="form-select" onchange="this.form.submit()">
                            <option value="">-- Tất cả Sale --</option>
                            @foreach ($nhanViens as $nv)
                                <option value="{{ $nv->id }}" @selected(request('nhan_vien') == $nv->id)>{{ $nv->ho_ten }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <div class="col-12 col-md-2 d-flex gap-2">
                    @if (request()->anyFilled(['search', 'trang_thai', 'nhan_vien']))
                        <a href="{{ route('nhanvien.admin.lien-he.index') }}"
                            class="btn btn-light border w-100 text-danger"><i class="fas fa-times me-1"></i> Xóa lọc</a>
                    @else
                        <button type="submit" class="btn btn-primary w-100"><i class="fas fa-filter"></i></button>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        {{-- BẢNG DESKTOP --}}
        <div class="table-responsive d-none d-md-block">
            <table class="table table-hover align-middle mb-0" style="table-layout: fixed; width: 100%;">
                <thead class="table-light">
                    <tr>
                        <th style="width: 25%">Khách hàng</th>
                        <th style="width: 22%">Ngữ cảnh tư vấn</th>
                        <th style="width: 25%">Tiến độ / Ghi chú</th>
                        <th class="text-center" style="width: 150px">Phụ trách</th>
                        <th class="text-center" style="width: 100px">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lienHes as $lh)
                        @php
                            $ttInfo = $lh->trang_thai_info ?? \App\Models\YeuCauLienHe::TRANG_THAI['moi'];
                            $isUnassigned = is_null($lh->nhan_vien_phu_trach_id);
                        @endphp
                        <tr class="{{ $isUnassigned ? 'lead-row-new' : '' }}" data-lead-id="{{ $lh->id }}">
                            {{-- CỘT 1: KHÁCH HÀNG --}}
                            <td>
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="badge bg-dark rounded-pill px-2 py-1"
                                        style="font-size: 0.65rem;">{{ $lh->ma_yeu_cau }}</span>
                                    <strong class="text-dark fs-6">{{ $lh->ho_ten }}</strong>
                                </div>
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <a href="tel:{{ preg_replace('/[^0-9]/', '', $lh->so_dien_thoai) }}"
                                        class="text-success fw-bold text-decoration-none"><i
                                            class="fas fa-phone-alt me-1"></i>{{ $lh->so_dien_thoai }}</a>
                                    <a href="https://zalo.me/{{ preg_replace('/[^0-9]/', '', $lh->so_dien_thoai) }}"
                                        target="_blank" class="badge bg-primary text-decoration-none">Zalo</a>
                                </div>
                                <div class="small text-muted"><i class="far fa-clock me-1"></i>Vào lúc:
                                    {{ $lh->thoi_diem_lien_he?->format('H:i d/m') ?? $lh->created_at->format('H:i d/m') }}
                                </div>
                            </td>

                            {{-- CỘT 2: BỐI CẢNH (CLICK ĐỂ MỞ MODAL) --}}
                            <td style="cursor: pointer;" onclick="showLeadDetail({{ $lh->id }}); return false;">
                                @if ($lh->batDongSan)
                                    <span class="badge bg-success-subtle text-success mb-1 border border-success-subtle"><i
                                            class="fas fa-home"></i> Hỏi mua BĐS</span>
                                    <div class="fw-bold text-navy text-decoration-none d-block small lh-sm line-clamp-2"
                                        title="{{ $lh->batDongSan->ten_bat_dong_san }}" style="cursor: pointer;">
                                        {{ $lh->batDongSan->ten_bat_dong_san }}
                                    </div>
                                @else
                                    <span
                                        class="badge bg-secondary-subtle text-secondary mb-1 border border-secondary-subtle"><i
                                            class="fas fa-globe"></i> Hỏi đáp chung</span>
                                    <div class="small text-dark fw-medium line-clamp-2" title="{{ $lh->noi_dung }}"
                                        style="cursor: pointer;">
                                        "{{ $lh->noi_dung }}"</div>
                                @endif
                            </td>

                            {{-- CỘT 3: TRẠNG THÁI VÀ GHI CHÚ --}}
                            <td>
                                @if ($isUnassigned)
                                    <div class="text-danger small fw-bold mb-1"><i class="fas fa-exclamation-circle"></i>
                                        Đang chờ người tiếp nhận!</div>
                                @else
                                    <div class="d-flex gap-2 align-items-center mb-1">
                                        <div class="status-wrapper">
                                            <select class="status-select"
                                                style="background-color: {{ $ttInfo['bg'] }}; color: {{ $ttInfo['color'] }};"
                                                onchange="capNhatNhanh({{ $lh->id }}, this)">
                                                @foreach (\App\Models\YeuCauLienHe::TRANG_THAI as $v => $info)
                                                    <option value="{{ $v }}" @selected($lh->trang_thai == $v)
                                                        data-bg="{{ $info['bg'] }}" data-color="{{ $info['color'] }}">
                                                        {{ $info['label'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <button type="button"
                                            class="btn btn-sm btn-light border text-primary btn-quick-note"
                                            data-bs-toggle="modal" data-bs-target="#modalNote"
                                            data-id="{{ $lh->id }}" data-name="{{ $lh->ho_ten }}"
                                            title="Thêm lịch sử chăm sóc">
                                            <i class="fas fa-pen-nib"></i> Note
                                        </button>
                                    </div>
                                    <div class="small text-muted"
                                        style="max-height: 40px; overflow-y: auto; font-size: 0.75rem;">
                                        {!! $lh->ghi_chu_admin
                                            ? nl2br(e($lh->ghi_chu_admin))
                                            : '<i class="fst-italic opacity-50">Chưa có ghi chú chăm sóc...</i>' !!}
                                    </div>
                                @endif
                            </td>

                            {{-- CỘT 4: PHỤ TRÁCH (NÚT NHẬN LEAD) --}}
                            <td class="text-center">
                                @if ($isUnassigned)
                                    <form action="{{ route('nhanvien.admin.lien-he.nhan-lead', $lh->id) }}"
                                        method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="btn btn-sm btn-danger rounded-pill fw-bold px-3 pulse-btn shadow-sm">
                                            <i class="fas fa-hand-paper me-1"></i> Nhận Lead
                                        </button>
                                    </form>
                                @else
                                    <div class="fw-bold text-dark" style="font-size: 0.85rem;"><i
                                            class="fas fa-user-tie text-primary me-1"></i>{{ $lh->nhanVienPhuTrach->ho_ten }}
                                    </div>
                                    <div class="small text-muted mt-1"><i
                                            class="fas fa-sign-in-alt me-1"></i>{{ \App\Models\YeuCauLienHe::NGUON[$lh->nguon_lien_he] ?? $lh->nguon_lien_he }}
                                    </div>
                                @endif
                            </td>

                            {{-- CỘT 5: THAO TÁC --}}
                            <td class="text-center">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light border rounded-circle" type="button"
                                        data-bs-toggle="dropdown" style="width: 32px; height: 32px;"><i
                                            class="fas fa-ellipsis-v"></i></button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                        <li><a class="dropdown-item text-primary"
                                                href="{{ route('nhanvien.admin.lien-he.show', $lh) }}"><i
                                                    class="fas fa-eye me-2"></i> Xem chi tiết</a></li>

                                        @if (!$isUnassigned && $lh->trang_thai != 'da_chot')
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <form action="{{ route('nhanvien.admin.lien-he.chuyen-khach', $lh->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item fw-bold text-success"
                                                        onclick="return confirm('Chốt khách và chuyển thẳng vào Danh bạ CRM?')">
                                                        <i class="fas fa-trophy text-success me-2"></i> Chốt! Tạo Khách
                                                        Hàng CRM
                                                    </button>
                                                </form>
                                            </li>
                                        @endif

                                        @if ($nhanVienAuth->vai_tro == 'admin')
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <form id="frmDel_{{ $lh->id }}"
                                                    action="{{ route('nhanvien.admin.lien-he.destroy', $lh->id) }}"
                                                    method="POST">@csrf @method('DELETE')</form>
                                                <button type="button" class="dropdown-item text-danger"
                                                    onclick="confirmDelete('Lead {{ $lh->ho_ten }}', () => document.getElementById('frmDel_{{ $lh->id }}').submit())"><i
                                                        class="fas fa-trash me-2"></i> Xóa Lead</button>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted"><i
                                    class="fas fa-inbox fs-1 mb-3 opacity-50 d-block"></i> Chưa có dữ liệu liên hệ.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- BẢNG MOBILE CARDS --}}
        <div class="d-md-none p-2 bg-light">
            @foreach ($lienHes as $lh)
                @php
                    $ttInfo = $lh->trang_thai_info ?? \App\Models\YeuCauLienHe::TRANG_THAI['moi'];
                    $isUnassigned = is_null($lh->nhan_vien_phu_trach_id);
                @endphp
                <div class="mobile-card {{ $isUnassigned ? 'lead-row-new' : '' }}">
                    <div class="mobile-card-header">
                        <div>
                            <div class="fw-bold text-dark fs-6">{{ $lh->ho_ten }}</div>
                            <div class="small text-muted mt-1"><i
                                    class="far fa-clock me-1"></i>{{ $lh->thoi_diem_lien_he?->format('H:i d/m') ?? $lh->created_at->format('H:i d/m') }}
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="tel:{{ preg_replace('/[^0-9]/', '', $lh->so_dien_thoai) }}"
                                class="btn btn-success btn-sm rounded-circle px-2"><i class="fas fa-phone-alt"></i></a>
                            <a href="https://zalo.me/{{ preg_replace('/[^0-9]/', '', $lh->so_dien_thoai) }}"
                                target="_blank" class="btn btn-primary btn-sm rounded-circle px-2"
                                style="background:#0068FF; border:none;"><strong>Z</strong></a>
                        </div>
                    </div>

                    <div class="mobile-card-body">
                        @if ($lh->batDongSan)
                            <div class="small mb-2"><span class="badge bg-success-subtle text-success me-1">Xem BĐS</span>
                                <span
                                    class="fw-medium text-navy">{{ \Illuminate\Support\Str::limit($lh->batDongSan->ten_bat_dong_san, 40) }}</span>
                            </div>
                        @else
                            <div class="small text-dark fst-italic mb-2 border-start border-3 border-secondary ps-2">
                                "{{ \Illuminate\Support\Str::limit($lh->noi_dung, 60) }}"</div>
                        @endif

                        @if ($isUnassigned)
                            <form action="{{ route('nhanvien.admin.lien-he.nhan-lead', $lh->id) }}" method="POST"
                                class="mt-3">
                                @csrf
                                <button type="submit" class="btn btn-danger w-100 fw-bold pulse-btn rounded-pill"><i
                                        class="fas fa-hand-paper me-1"></i> NHẬN LEAD NGAY</button>
                            </form>
                        @else
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <div class="status-wrapper">
                                    <select class="status-select"
                                        style="background-color: {{ $ttInfo['bg'] }}; color: {{ $ttInfo['color'] }};"
                                        onchange="capNhatNhanh({{ $lh->id }}, this)">
                                        @foreach (\App\Models\YeuCauLienHe::TRANG_THAI as $v => $info)
                                            <option value="{{ $v }}" @selected($lh->trang_thai == $v)
                                                data-bg="{{ $info['bg'] }}" data-color="{{ $info['color'] }}">
                                                {{ $info['label'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary btn-quick-note rounded-pill"
                                    data-bs-toggle="modal" data-bs-target="#modalNote" data-id="{{ $lh->id }}"
                                    data-name="{{ $lh->ho_ten }}"><i class="fas fa-pen-nib"></i> Ghi chú</button>
                            </div>
                        @endif
                    </div>

                    @if (!$isUnassigned)
                        <div class="mobile-card-footer">
                            <div class="small fw-bold text-muted"><i class="fas fa-user-tie me-1"></i>
                                {{ Str::limit($lh->nhanVienPhuTrach->ho_ten, 15) }}</div>
                            <a href="{{ route('nhanvien.admin.lien-he.show', $lh) }}"
                                class="btn btn-sm btn-light border">Chi tiết <i class="fas fa-angle-right"></i></a>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        @if ($lienHes->hasPages())
            <div class="card-footer bg-white p-3 d-flex justify-content-center justify-content-md-end">
                {{ $lienHes->appends(request()->query())->links('pagination::bootstrap-5') }}</div>
        @endif
    </div>

    {{-- MODAL CHI TIẾT LEAD (REDESIGNED) --}}
    <div class="modal fade" id="modalLeadDetail" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content border-0 shadow" style="border-radius: 12px; overflow: hidden;">
                <div class="modal-header py-2 px-3" style="background: linear-gradient(90deg,#1a3c5e,#2d5a8a);">
                    <h6 class="modal-title text-white mb-0"><i class="fas fa-file-alt me-2"></i>Chi tiết Lead</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <div class="modal-body p-3">
                    <div class="d-flex align-items-start gap-3">
                        <div style="flex:1; min-width:0">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <div class="small text-muted">Khách hàng</div>
                                    <div class="fw-bold" id="ld_ho_ten">—</div>
                                </div>
                                <div><span class="badge bg-dark" id="ld_ma_yeu_cau">—</span></div>
                            </div>

                            <div class="d-flex gap-3 mb-2 flex-wrap">
                                <div>
                                    <div class="small text-muted">📞</div>
                                    <div id="ld_so_dien_thoai" class="fw-semibold text-success">—</div>
                                </div>
                                <div>
                                    <div class="small text-muted">📧</div>
                                    <div id="ld_email">—</div>
                                </div>
                            </div>

                            <div class="mt-2">
                                <div class="small text-muted">Nội dung / Bối cảnh</div>
                                <div id="ld_bds_info" class="mb-1"></div>
                                <div id="ld_noi_dung" class="small text-dark" style="line-height:1.4;">—</div>
                            </div>
                        </div>

                        <div style="width:38%; min-width:200px">
                            <div class="small text-muted mb-1">Trạng thái</div>
                            <div id="ld_trang_thai" class="mb-2"></div>

                            <div class="small text-muted mb-1">Phụ trách</div>
                            <div id="ld_nhan_vien" class="fw-semibold mb-2">—</div>

                            <div class="small text-muted mb-1">Tiềm năng</div>
                            <div id="ld_muc_do" class="mb-2">—</div>

                            <div class="small text-muted mb-1">Nhận lúc</div>
                            <div id="ld_thoi_gian">—</div>
                        </div>
                    </div>

                    <hr class="my-3">

                    <div>
                        <div class="small text-muted mb-2">Lịch sử Chăm sóc</div>
                        <div id="ld_ghi_chu" class="bg-light p-2"
                            style="max-height:180px;overflow:auto;font-size:0.9rem;line-height:1.5;">
                            <div class="text-muted fst-italic">Chưa có ghi chú</div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer py-2 px-3 bg-white border-top-0">
                    <a href="#" class="btn btn-sm btn-outline-secondary" id="ld_btn_detail" target="_blank">Xem
                        full</a>
                    <button type="button" class="btn btn-sm btn-primary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL GHI CHÚ NHANH --}}
    <div class="modal fade" id="modalNote" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form id="noteForm" class="modal-content border-0 shadow" method="POST" action="#">
                @csrf @method('PUT')
                <input type="hidden" name="is_quick_update" value="1">
                <div class="modal-header bg-light border-bottom-0">
                    <h5 class="modal-title fw-bold"><i class="fas fa-pen-nib text-primary me-2"></i>Cập nhật Lịch sử gọi
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-info border-0 bg-info bg-opacity-10 mb-3 small">
                        Đang thao tác với khách hàng: <strong id="note_kh_name" class="text-primary"></strong><br>
                        Ghi chú sẽ tự động được đóng dấu ngày giờ hiện tại.
                    </div>
                    <textarea name="ghi_chu_moi" class="form-control" rows="4"
                        placeholder="Khách hàng vừa phản hồi thế nào? Nhập vào đây..." required></textarea>
                </div>
                <div class="modal-footer bg-light border-top-0">
                    <button type="button" class="btn btn-secondary border bg-white text-dark"
                        data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-2"></i>Lưu ghi
                        chú</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Logic cho thẻ Select đổi màu trạng thái bằng Ajax
        function capNhatNhanh(id, selectElement) {
            const newStatus = selectElement.value;
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            const oldVal = selectElement.dataset.old || newStatus;
            selectElement.disabled = true;

            fetch(`/nhan-vien/admin/lien-he/${id}/cap-nhat-nhanh`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        trang_thai: newStatus
                    })
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        selectElement.style.backgroundColor = selectedOption.dataset.bg;
                        selectElement.style.color = selectedOption.dataset.color;
                        selectElement.dataset.old = newStatus;
                        showAdminToast('Đã đổi trạng thái thành công', 'success');
                    } else {
                        selectElement.value = oldVal;
                        showAdminToast('Lỗi cập nhật trạng thái', 'error');
                    }
                })
                .catch(() => {
                    selectElement.value = oldVal;
                    showAdminToast('Lỗi kết nối', 'error');
                })
                .finally(() => {
                    selectElement.disabled = false;
                });
        }

        // Modal Ghi chú nhanh
        document.querySelectorAll('.btn-quick-note').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('note_kh_name').innerText = this.dataset.name;
                document.getElementById('noteForm').action =
                    `{{ route('nhanvien.admin.lien-he.update', ['lien_he' => '__ID__']) }}`.replace(
                        '__ID__', this.dataset.id);
                document.querySelector('textarea[name="ghi_chu_moi"]').value = '';
            });
        });

        // Hiển thị chi tiết Lead trong Modal
        function showLeadDetail(leadId) {
            const modal = new bootstrap.Modal(document.getElementById('modalLeadDetail'));
            const spinner = document.getElementById('leadDetailSpinner');
            const content = document.getElementById('leadDetailContent');

            // Hiển thị spinner
            modal.show();
            if (spinner) spinner.style.display = 'block';
            if (content) content.style.display = 'none';

            // Fetch dữ liệu
            fetch(`/nhan-vien/admin/lien-he/${leadId}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(r => r.json())
                .then(data => {
                    // Điền dữ liệu vào modal
                    document.getElementById('ld_ho_ten').textContent = data.ho_ten || '—';
                    document.getElementById('ld_ma_yeu_cau').textContent = data.ma_yeu_cau || '—';
                    document.getElementById('ld_so_dien_thoai').innerHTML =
                        `<a href="tel:${data.so_dien_thoai}" class="text-decoration-none">${data.so_dien_thoai}</a>`;
                    document.getElementById('ld_email').textContent = data.email || '—';

                    // Thông tin tư vấn
                    if (data.bat_dong_san) {
                        document.getElementById('ld_bds_info').innerHTML =
                            `<span class="badge bg-success-subtle text-success me-1">Hỏi mua BĐS</span><a href="${data.bat_dong_san.url}" target="_blank" class="text-decoration-none fw-bold">${data.bat_dong_san.ten}</a>`;
                    } else {
                        document.getElementById('ld_bds_info').innerHTML =
                            '<span class="badge bg-secondary-subtle text-secondary">Hỏi đáp chung</span>';
                    }
                    document.getElementById('ld_noi_dung').textContent = data.noi_dung || 'Không có nội dung';

                    // Trạng thái
                    document.getElementById('ld_trang_thai').innerHTML =
                        `<span class="badge" style="background-color: ${data.trang_thai_info.bg}; color: ${data.trang_thai_info.color};">${data.trang_thai_info.label}</span>`;
                    document.getElementById('ld_nhan_vien').textContent = data.nhan_vien || 'Chưa gán';
                    document.getElementById('ld_muc_do').textContent = data.muc_do || '—';
                    document.getElementById('ld_thoi_gian').textContent = data.thoi_gian_nhan || '—';

                    // Ghi chú
                    const ghiChuEl = document.getElementById('ld_ghi_chu');
                    if (data.ghi_chu_admin) {
                        ghiChuEl.innerHTML = data.ghi_chu_admin.replace(/\n/g, '<br>');
                    } else {
                        ghiChuEl.innerHTML = '<span class="text-muted fst-italic">Chưa có ghi chú</span>';
                    }

                    // Link xem chi tiết
                    document.getElementById('ld_btn_detail').href = data.detail_url;

                    // Ẩn spinner, hiển thị content
                    if (spinner) spinner.style.display = 'none';
                    if (content) content.style.display = 'block';
                })
                .catch(err => {
                    if (spinner) spinner.style.display = 'none';
                    if (content) {
                        content.style.display = 'block';
                        content.innerHTML =
                            '<div class="alert alert-danger">Lỗi khi tải dữ liệu. Vui lòng thử lại.</div>';
                    } else {
                        document.getElementById('ld_noi_dung').textContent = 'Lỗi khi tải dữ liệu. Vui lòng thử lại.';
                    }
                    showAdminToast('Lỗi khi tải chi tiết lead', 'error');
                });
        }
    </script>
@endpush
