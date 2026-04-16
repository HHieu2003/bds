@extends('admin.layouts.master')
@section('title', 'Quản lý Ký gửi')

@section('content')
    {{-- HEADER --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h1 class="page-header-title mb-1"><i class="fas fa-file-signature text-primary"></i> Quản lý Ký gửi</h1>
            <div style="font-size:.78rem;color:var(--text-sub)">
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <span><strong>{{ number_format($thongKe['tong']) }}</strong> tổng</span>
                    <span
                        style="width:4px;height:4px;border-radius:50%;background:var(--text-muted);display:inline-block"></span>
                    <span style="color:#FF9800"><strong>{{ number_format($thongKe['cho_duyet']) }}</strong> mới gửi</span>
                    <span
                        style="width:4px;height:4px;border-radius:50%;background:var(--text-muted);display:inline-block"></span>
                    <span style="color:#9C27B0"><strong>{{ number_format($thongKe['dang_tham_dinh']) }}</strong> đang thẩm
                        định</span>
                    <span
                        style="width:4px;height:4px;border-radius:50%;background:var(--text-muted);display:inline-block"></span>
                    <span style="color:#4CAF50"><strong>{{ number_format($thongKe['da_duyet']) }}</strong> đã duyệt</span>
                    <span
                        style="width:4px;height:4px;border-radius:50%;background:var(--text-muted);display:inline-block"></span>
                    <span style="color:#F44336"><strong>{{ number_format($thongKe['tu_choi']) }}</strong> từ chối</span>
                </div>
            </div>
        </div>
        <a href="{{ route('nhanvien.admin.ky-gui.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus me-1"></i> Tạo yêu cầu mới
        </a>
    </div>

    {{-- FLASH MESSAGES --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0"><i
                class="fas fa-check-circle me-1"></i> {!! session('success') !!}<button type="button" class="btn-close"
                data-bs-dismiss="alert"></button></div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0"><i
                class="fas fa-exclamation-circle me-1"></i> {!! session('error') !!}<button type="button" class="btn-close"
                data-bs-dismiss="alert"></button></div>
    @endif

    {{-- TABS ĐIỀU HƯỚNG --}}
    <ul class="nav nav-tabs mb-4 border-bottom-0" style="gap: 5px;">
        <li class="nav-item">
            <a class="nav-link {{ $activeTab == 'can_xu_ly' ? 'active border-primary border-bottom-0 fw-bold text-primary shadow-sm' : 'text-muted border-0 bg-light' }}"
                href="{{ route('nhanvien.admin.ky-gui.index', array_merge(request()->query(), ['tab' => 'can_xu_ly', 'page' => 1])) }}"
                style="{{ $activeTab == 'can_xu_ly' ? 'border-top: 3px solid var(--bs-primary); border-radius: 6px 6px 0 0;' : 'border-radius: 6px 6px 0 0;' }}">
                <i class="fas fa-inbox me-1"></i> Cần xử lý
                @php $soLuongCanXuLy = $thongKe['cho_duyet'] + $thongKe['dang_tham_dinh']; @endphp
                @if ($soLuongCanXuLy > 0)
                    <span class="badge bg-danger ms-1 rounded-pill">{{ $soLuongCanXuLy }}</span>
                @endif
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $activeTab == 'tat_ca' ? 'active border-primary border-bottom-0 fw-bold text-primary shadow-sm' : 'text-muted border-0 bg-light' }}"
                href="{{ route('nhanvien.admin.ky-gui.index', array_merge(request()->query(), ['tab' => 'tat_ca', 'page' => 1])) }}"
                style="{{ $activeTab == 'tat_ca' ? 'border-top: 3px solid var(--bs-primary); border-radius: 6px 6px 0 0;' : 'border-radius: 6px 6px 0 0;' }}">
                <i class="fas fa-list me-1"></i> Tất cả yêu cầu
                <span class="badge bg-secondary ms-1 rounded-pill">{{ $thongKe['tong'] }}</span>
            </a>
        </li>
    </ul>

    {{-- BỘ LỌC --}}
    <div class="filter-box mb-4">
        <form method="GET" id="filterForm">
            <input type="hidden" name="tab" value="{{ $activeTab }}">

            <div class="row g-2 align-items-center">
                <div class="col-12 col-md-3">
                    <input type="text" name="tim_kiem" class="filter-ctrl filter-ctrl-search w-100"
                        value="{{ request('tim_kiem') }}" placeholder="🔍 Tìm tên, SĐT chủ nhà...">
                </div>

                {{-- Chỉ hiển thị ô lọc Trạng thái nếu đang ở tab "Tất cả" --}}
                @if ($activeTab == 'tat_ca')
                    <div class="col-6 col-md-2">
                        <select name="trang_thai" class="filter-ctrl w-100 filter-auto-submit">
                            <option value="">Trạng thái</option>
                            @foreach (\App\Models\KyGui::TRANG_THAI as $v => $info)
                                <option value="{{ $v }}" @selected(request('trang_thai') == $v)>{{ $info['label'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="col-6 col-md-2">
                    <select name="sapxep" class="filter-ctrl w-100 filter-auto-submit">
                        <option value="moi_nhat" @selected(request('sapxep', 'moi_nhat') == 'moi_nhat')>Mới nhất</option>
                        <option value="cu_nhat" @selected(request('sapxep') == 'cu_nhat')>Cũ nhất</option>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <select name="nhan_vien_id" class="filter-ctrl w-100 filter-auto-submit">
                        <option value="">Nhân sự Nguồn</option>
                        @foreach ($nhanViens as $nv)
                            <option value="{{ $nv->id }}" @selected(request('nhan_vien_id') == $nv->id)>{{ $nv->ho_ten }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-1">
                    <button type="submit" class="btn btn-navy btn-sm px-3 w-100 h-100"><i class="fas fa-search"></i>
                        Lọc</button>
                </div>
                <div class="col-6 col-md-1">
                    @if (request()->hasAny(['tim_kiem', 'trang_thai', 'nhan_vien_id', 'sapxep']))
                        <a href="{{ route('nhanvien.admin.ky-gui.index', ['tab' => $activeTab]) }}"
                            class="btn btn-danger btn-sm px-3 w-100 h-100 d-flex align-items-center justify-content-center"><i
                                class="fas fa-times"></i></a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    {{-- BẢNG DỮ LIỆU --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <span class="text-muted fw-normal" style="font-size: 0.85rem">
                @if ($kyGuis->total() > 0)
                    Hiển thị <strong>{{ $kyGuis->firstItem() }}–{{ $kyGuis->lastItem() }}</strong> /
                    <strong>{{ number_format($kyGuis->total()) }}</strong> yêu cầu
                @else
                    Không có kết quả nào trong mục này
                @endif
            </span>
        </div>

        <div class="table-responsive tbl-desktop" style="overflow-x: hidden;">
            <table class="table table-hover align-middle mb-0"
                style="table-layout: fixed; width: 100%; word-wrap: break-word;">
                <thead class="bg-light">
                    <tr>
                        <th class="text-center" style="width: 40px">#</th>
                        <th style="width: 17%">Khách hàng / Chủ nhà</th>
                        <th style="width: 18%">Loại BĐS</th>
                        <th>Địa chỉ / Diện tích</th>
                        <th style="width: 9%">Giá</th>
                        <th style="width: 18%">Trạng thái</th>
                        <th class="text-center" style="width: 120px">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kyGuis as $i => $kg)
                        @php
                            $ttInfo = \App\Models\KyGui::TRANG_THAI[$kg->trang_thai] ?? [
                                'label' => $kg->trang_thai,
                                'color' => '#999',
                                'bg' => '#f5f5f5',
                                'icon' => 'fas fa-info',
                            ];
                            $lhInfo = \App\Models\KyGui::LOAI_HINH[$kg->loai_hinh] ?? [
                                'label' => $kg->loai_hinh,
                                'icon' => 'fas fa-home',
                                'color' => '#999',
                            ];
                            $ncInfo = \App\Models\KyGui::NHU_CAU[$kg->nhu_cau] ?? [
                                'label' => $kg->nhu_cau,
                                'color' => '#999',
                                'bg' => '#f5f5f5',
                            ];
                        @endphp
                        <tr class="{{ $kg->trang_thai === 'cho_duyet' ? 'table-warning' : '' }}"
                            data-kg-ho-ten="{{ $kg->ho_ten_chu_nha }}" data-kg-sdt="{{ $kg->so_dien_thoai }}"
                            data-kg-email="{{ $kg->email }}" data-kg-dia-chi="{{ $kg->dia_chi }}"
                            data-kg-loai-hinh="{{ $lhInfo['label'] }}" data-kg-nhu-cau="{{ $ncInfo['label'] }}"
                            data-kg-dien-tich="{{ (float) $kg->dien_tich }}" data-kg-phong-ngu="{{ $kg->so_phong_ngu }}"
                            data-kg-tang="{{ $kg->tang }}" data-kg-noi-that="{{ $kg->noi_that }}"
                            data-kg-phap-ly="{{ $kg->phap_ly }}" data-kg-gia="{{ $kg->gia_hien_thi }}"
                            data-kg-trang-thai="{{ $ttInfo['label'] }}"
                            data-kg-created="{{ $kg->created_at->format('d/m/Y H:i') }}"
                            data-kg-ghi-chu="{{ $kg->ghi_chu }}"
                            data-kg-nv="{{ optional($kg->nhanVienPhuTrach)->ho_ten }}">
                            <td class="text-center text-muted">{{ $kyGuis->firstItem() + $i }}</td>
                            <td>
                                <a href="{{ route('nhanvien.admin.ky-gui.edit', $kg) }}"
                                    class="fw-bold text-navy text-decoration-none d-block mb-1">{{ $kg->ho_ten_chu_nha }}</a>
                                <div class="text-muted" style="font-size: 0.75rem">
                                    <div>

                                        <span class="copy-phone cursor-pointer" role="button" tabindex="0"
                                            data-phone="{{ $kg->so_dien_thoai }}" title="Bấm để sao chép số điện thoại">
                                            {{ $kg->so_dien_thoai }}
                                        </span>
                                    </div>
                                    @if ($kg->email)
                                        <div>{{ $kg->email }}</div>
                                    @endif
                                </div>
                            </td>
                            <td class="kg-detail-trigger" role="button" tabindex="0">
                                <div class="fw-bold mb-1" style="color: {{ $lhInfo['color'] }}; font-size: 0.85rem;"><i
                                        class="{{ $lhInfo['icon'] }} me-1"></i>{{ $lhInfo['label'] }}</div>
                                <span class="badge"
                                    style="background: {{ $ncInfo['bg'] }}; color: {{ $ncInfo['color'] }}">{{ $ncInfo['label'] }}</span>
                            </td>
                            <td class="kg-detail-trigger" role="button" tabindex="0">
                                <div class="mb-1 text-truncate" title="{{ $kg->dia_chi }}" style="font-size: 0.85rem">
                                    <i
                                        class="fas fa-map-marker-alt text-danger me-1"></i>{{ $kg->dia_chi ?: 'Chưa cập nhật' }}
                                </div>
                                <div class="text-muted" style="font-size: 0.8rem">
                                    <i class="fas fa-vector-square me-1"></i>{{ (float) $kg->dien_tich }}m²
                                    @if ($kg->so_phong_ngu)
                                        | {{ $kg->so_phong_ngu }}
                                    @endif
                                </div>
                            </td>
                            <td class="kg-detail-trigger" role="button" tabindex="0">
                                <div class="fw-bold text-primary">{{ $kg->gia_hien_thi }}</div>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <span class="badge cursor-pointer dropdown-toggle" data-bs-toggle="dropdown"
                                        style="background: {{ $ttInfo['bg'] }}; color: {{ $ttInfo['color'] }}; font-size: 0.75rem; white-space: normal; text-align: left;">
                                        <i class="{{ $ttInfo['icon'] }} me-1"></i>{{ $ttInfo['label'] }}
                                    </span>
                                    <ul class="dropdown-menu shadow-sm" style="font-size: 0.85rem">
                                        @foreach (\App\Models\KyGui::TRANG_THAI as $val => $item)
                                            <li><a class="dropdown-item tt-update-btn" href="javascript:void(0)"
                                                    data-id="{{ $kg->id }}" data-val="{{ $val }}"><i
                                                        class="{{ $item['icon'] }} me-1"
                                                        style="color:{{ $item['color'] }}"></i> {{ $item['label'] }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="text-muted mt-1" style="font-size: 0.7rem" title="Ngày gửi"><i
                                        class="fas fa-calendar-alt"></i> {{ $kg->created_at->format('d/m/Y H:i') }}</div>
                            </td>
                            <td class="text-center">
                                <div class="btn-actions-group justify-content-center">
                                    {{-- Nút xác nhận chuyển đổi (Chỉ hiện nếu chưa xử lý xong) --}}
                                    @if (in_array($kg->trang_thai, ['cho_duyet', 'dang_tham_dinh']))
                                        <a href="{{ route('nhanvien.admin.ky-gui.duyet', $kg->id) }}"
                                            class="btn-action btn-action-view"
                                            style="background: #e6f4ea; color: #198754; border-color: #d1e7dd;"
                                            title="Xác nhận hợp lệ & Duyệt"><i class="fas fa-magic"></i></a>
                                    @endif

                                    <a href="{{ route('nhanvien.admin.ky-gui.edit', $kg) }}"
                                        class="btn-action btn-action-edit" title="Sửa"><i class="fas fa-pen"></i></a>

                                    <form id="frmDel_{{ $kg->id }}"
                                        action="{{ route('nhanvien.admin.ky-gui.destroy', $kg) }}" method="POST"
                                        class="d-none">@csrf @method('DELETE')</form>
                                    <button type="button" class="btn-action btn-action-delete btn-delete-kg"
                                        data-id="{{ $kg->id }}" data-name="{{ $kg->ho_ten_chu_nha }}"
                                        title="Xóa"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state"><i class="fas fa-inbox text-muted mb-3"></i>
                                    <p class="text-muted mb-2">Chưa có yêu cầu ký gửi nào trong mục này</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile Cards --}}
        <div class="mobile-card-list">
            @foreach ($kyGuis as $kg)
                @php $ttInfo = \App\Models\KyGui::TRANG_THAI[$kg->trang_thai] ?? ['label' => $kg->trang_thai, 'color' => '#999', 'bg' => '#f5f5f5', 'icon' => '']; @endphp
                <div class="mobile-card {{ $kg->trang_thai === 'cho_duyet' ? 'border-warning' : '' }}">
                    <div class="mobile-card-top">
                        <div style="flex: 1; min-width: 0;">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="badge"
                                    style="background: {{ $ttInfo['bg'] }}; color: {{ $ttInfo['color'] }}"><i
                                        class="{{ $ttInfo['icon'] }}"></i> {{ $ttInfo['label'] }}</span>
                                <span class="text-muted"
                                    style="font-size: 0.7rem">{{ $kg->created_at->format('d/m H:i') }}</span>
                            </div>
                            <a href="{{ route('nhanvien.admin.ky-gui.edit', $kg) }}"
                                class="fw-bold text-navy text-decoration-none d-block text-truncate mb-1">{{ $kg->ho_ten_chu_nha }}</a>
                            <div class="text-muted" style="font-size: 0.75rem">
                                <i class="fas fa-phone text-success me-1"></i>
                                <span class="copy-phone cursor-pointer" role="button" tabindex="0"
                                    data-phone="{{ $kg->so_dien_thoai }}" title="Bấm để sao chép số điện thoại">
                                    {{ $kg->so_dien_thoai }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="mobile-card-meta flex-column align-items-start gap-1">
                        <div class="kg-card-detail-trigger" role="button" tabindex="0"
                            data-kg-ho-ten="{{ $kg->ho_ten_chu_nha }}" data-kg-sdt="{{ $kg->so_dien_thoai }}"
                            data-kg-email="{{ $kg->email }}" data-kg-dia-chi="{{ $kg->dia_chi }}"
                            data-kg-loai-hinh="{{ \App\Models\KyGui::LOAI_HINH[$kg->loai_hinh]['label'] ?? $kg->loai_hinh }}"
                            data-kg-nhu-cau="{{ \App\Models\KyGui::NHU_CAU[$kg->nhu_cau]['label'] ?? $kg->nhu_cau }}"
                            data-kg-dien-tich="{{ (float) $kg->dien_tich }}" data-kg-phong-ngu="{{ $kg->so_phong_ngu }}"
                            data-kg-tang="{{ $kg->tang }}" data-kg-noi-that="{{ $kg->noi_that }}"
                            data-kg-phap-ly="{{ $kg->phap_ly }}" data-kg-gia="{{ $kg->gia_hien_thi }}"
                            data-kg-trang-thai="{{ $ttInfo['label'] }}"
                            data-kg-created="{{ $kg->created_at->format('d/m/Y H:i') }}"
                            data-kg-ghi-chu="{{ $kg->ghi_chu }}"
                            data-kg-nv="{{ optional($kg->nhanVienPhuTrach)->ho_ten }}">
                            <i class="fas fa-map-marker-alt text-danger w-15px text-center"></i>
                            {{ Str::limit($kg->dia_chi, 30) ?: 'Chưa cập nhật' }}
                        </div>
                        <div class="kg-card-detail-trigger" role="button" tabindex="0"
                            data-kg-ho-ten="{{ $kg->ho_ten_chu_nha }}" data-kg-sdt="{{ $kg->so_dien_thoai }}"
                            data-kg-email="{{ $kg->email }}" data-kg-dia-chi="{{ $kg->dia_chi }}"
                            data-kg-loai-hinh="{{ \App\Models\KyGui::LOAI_HINH[$kg->loai_hinh]['label'] ?? $kg->loai_hinh }}"
                            data-kg-nhu-cau="{{ \App\Models\KyGui::NHU_CAU[$kg->nhu_cau]['label'] ?? $kg->nhu_cau }}"
                            data-kg-dien-tich="{{ (float) $kg->dien_tich }}"
                            data-kg-phong-ngu="{{ $kg->so_phong_ngu }}" data-kg-tang="{{ $kg->tang }}"
                            data-kg-noi-that="{{ $kg->noi_that }}" data-kg-phap-ly="{{ $kg->phap_ly }}"
                            data-kg-gia="{{ $kg->gia_hien_thi }}" data-kg-trang-thai="{{ $ttInfo['label'] }}"
                            data-kg-created="{{ $kg->created_at->format('d/m/Y H:i') }}"
                            data-kg-ghi-chu="{{ $kg->ghi_chu }}"
                            data-kg-nv="{{ optional($kg->nhanVienPhuTrach)->ho_ten }}">
                            <i class="fas fa-tag text-primary w-15px text-center"></i> <strong
                                class="text-primary">{{ $kg->gia_hien_thi }}</strong> - {{ (float) $kg->dien_tich }}m²
                        </div>
                    </div>
                    <div class="mobile-card-foot justify-content-end">
                        <div class="btn-actions-group">
                            @if (in_array($kg->trang_thai, ['cho_duyet', 'dang_tham_dinh']))
                                <a href="{{ route('nhanvien.admin.ky-gui.duyet', $kg->id) }}"
                                    class="btn-action btn-action-view"
                                    style="background: #e6f4ea; color: #198754; border-color: #d1e7dd;"><i
                                        class="fas fa-magic"></i></a>
                            @endif
                            <a href="{{ route('nhanvien.admin.ky-gui.edit', $kg) }}"
                                class="btn-action btn-action-edit"><i class="fas fa-pen"></i></a>
                            <button type="button" class="btn-action btn-action-delete btn-delete-kg"
                                data-id="{{ $kg->id }}" data-name="{{ $kg->ho_ten_chu_nha }}"><i
                                    class="fas fa-trash"></i></button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if ($kyGuis->hasPages())
            <div class="card-footer bg-white border-top p-3 d-flex justify-content-center justify-content-md-end">
                {{ $kyGuis->links('pagination::bootstrap-5') }}</div>
        @endif
    </div>

    {{-- MODAL CHI TIẾT KÝ GỬI (ĐÃ LÀM ĐẸP) --}}
    <div class="modal fade" id="modalKyGuiChiTiet" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-light border-bottom">
                    <h5 class="modal-title fw-bold text-navy"><i class="fas fa-file-alt text-primary me-2"></i>Chi tiết
                        Yêu cầu Ký gửi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">

                    {{-- Khối thông tin khách hàng --}}
                    <h6 class="fw-bold text-primary mb-3"><i class="fas fa-user-circle me-2"></i>Thông tin Khách hàng /
                        Chủ nhà</h6>
                    <div class="row g-3 mb-4 bg-light p-3 rounded border">
                        <div class="col-md-5">
                            <span class="text-muted d-block mb-1" style="font-size: 0.8rem">Họ và tên</span>
                            <strong id="kgd_ho_ten" class="text-dark fs-6"></strong>
                        </div>
                        <div class="col-md-3">
                            <span class="text-muted d-block mb-1" style="font-size: 0.8rem">Số điện thoại</span>
                            <strong id="kgd_sdt" class="text-danger fs-6"></strong>
                        </div>
                        <div class="col-md-4">
                            <span class="text-muted d-block mb-1" style="font-size: 0.8rem">Email</span>
                            <strong id="kgd_email" class="text-dark"></strong>
                        </div>
                    </div>

                    {{-- Khối thông tin Bất động sản --}}
                    <h6 class="fw-bold text-success mb-3"><i class="fas fa-home me-2"></i>Chi tiết Bất động sản</h6>
                    <div class="row g-3 mb-4 p-3 rounded border border-success-subtle">
                        <div class="col-md-4">
                            <span class="text-muted d-block mb-1" style="font-size: 0.8rem">Nhu cầu ký gửi</span>
                            <strong id="kgd_nhu_cau" class="text-dark"></strong>
                        </div>
                        <div class="col-md-4">
                            <span class="text-muted d-block mb-1" style="font-size: 0.8rem">Loại hình</span>
                            <strong id="kgd_loai_hinh" class="text-dark"></strong>
                        </div>
                        <div class="col-md-4">
                            <span class="text-muted d-block mb-1" style="font-size: 0.8rem">Mức giá mong muốn</span>
                            <strong id="kgd_gia" class="text-danger fs-5"></strong>
                        </div>

                        <div class="col-12 mt-3">
                            <span class="text-muted d-block mb-1" style="font-size: 0.8rem">Địa chỉ thực tế</span>
                            <strong id="kgd_dia_chi" class="text-dark"></strong>
                        </div>

                        <div class="col-md-3 mt-3">
                            <span class="text-muted d-block mb-1" style="font-size: 0.8rem">Diện tích</span>
                            <strong id="kgd_dien_tich" class="text-dark"></strong>
                        </div>
                        <div class="col-md-3 mt-3">
                            <span class="text-muted d-block mb-1" style="font-size: 0.8rem">Phòng ngủ</span>
                            <strong id="kgd_phong_ngu" class="text-dark"></strong>
                        </div>
                        <div class="col-md-3 mt-3">
                            <span class="text-muted d-block mb-1" style="font-size: 0.8rem">Tầng</span>
                            <strong id="kgd_tang" class="text-dark"></strong>
                        </div>
                        <div class="col-md-6 mt-3">
                            <span class="text-muted d-block mb-1" style="font-size: 0.8rem">Pháp lý</span>
                            <strong id="kgd_phap_ly" class="text-dark"></strong>
                        </div>
                        <div class="col-md-6 mt-3">
                            <span class="text-muted d-block mb-1" style="font-size: 0.8rem">Nội thất</span>
                            <strong id="kgd_noi_that" class="text-dark"></strong>
                        </div>
                    </div>

                    {{-- Khối thông tin Trạng thái --}}
                    <h6 class="fw-bold text-secondary mb-3"><i class="fas fa-info-circle me-2"></i>Trạng thái & Quản lý
                    </h6>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <span class="text-muted d-block mb-1" style="font-size: 0.8rem">Trạng thái hiện tại</span>
                            <div id="kgd_trang_thai" class="fw-bold text-info"></div>
                        </div>
                        <div class="col-md-4">
                            <span class="text-muted d-block mb-1" style="font-size: 0.8rem">Ngày tiếp nhận</span>
                            <strong id="kgd_created" class="text-dark"></strong>
                        </div>
                        <div class="col-md-4">
                            <span class="text-muted d-block mb-1" style="font-size: 0.8rem">Nhân sự phụ trách</span>
                            <strong id="kgd_nv" class="text-primary"></strong>
                        </div>
                        <div class="col-12">
                            <span class="text-muted d-block mb-1" style="font-size: 0.8rem">Ghi chú từ khách hàng</span>
                            <div id="kgd_ghi_chu" class="p-3 bg-light border rounded text-dark"
                                style="min-height: 60px;"></div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng cửa sổ</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const CSRF = document.querySelector('meta[name=csrf-token]').content;

            document.querySelectorAll('.btn-delete-kg').forEach(btn => {
                btn.addEventListener('click', function() {
                    const name = this.dataset.name;
                    const id = this.dataset.id;
                    if (confirm('Bạn có chắc muốn xóa yêu cầu ký gửi của ' + name + '?')) {
                        document.getElementById('frmDel_' + id).submit();
                    }
                });
            });

            // AJAX Update Trạng Thái từ Dropdown
            document.querySelectorAll('.tt-update-btn').forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    const id = this.dataset.id;
                    const val = this.dataset.val;

                    fetch(`/nhan-vien/admin/ky-gui/${id}/xu-ly`, {
                            method: 'PATCH',
                            headers: {
                                'X-CSRF-TOKEN': CSRF,
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                trang_thai: val
                            })
                        })
                        .then(r => r.json())
                        .then(data => {
                            if (data.ok) window.location.reload();
                        }).catch(() => alert('Lỗi kết nối mạng!'));
                });
            });

            const modalEl = document.getElementById('modalKyGuiChiTiet');
            const kgModal = modalEl ? new bootstrap.Modal(modalEl) : null;

            const showCopyNotice = (message) => {
                const oldNotice = document.getElementById('copyPhoneNotice');
                if (oldNotice) oldNotice.remove();

                const notice = document.createElement('div');
                notice.id = 'copyPhoneNotice';
                notice.textContent = message;
                notice.style.cssText =
                    'position:fixed;top:20px;right:20px;z-index:2000;background:#0d6efd;color:#fff;padding:8px 12px;border-radius:8px;font-size:12px;box-shadow:0 6px 18px rgba(0,0,0,.2);';
                document.body.appendChild(notice);
                setTimeout(() => notice.remove(), 1600);
            };

            const copyPhone = async (phone) => {
                if (!phone) return;
                try {
                    if (navigator.clipboard && window.isSecureContext) {
                        await navigator.clipboard.writeText(phone);
                    } else {
                        const temp = document.createElement('textarea');
                        temp.value = phone;
                        temp.style.position = 'fixed';
                        temp.style.left = '-9999px';
                        document.body.appendChild(temp);
                        temp.focus();
                        temp.select();
                        document.execCommand('copy');
                        temp.remove();
                    }
                    showCopyNotice('Đã sao chép: ' + phone);
                } catch (e) {
                    showCopyNotice('Không thể sao chép số điện thoại');
                }
            };

            const setText = (id, value, fallback = '—') => {
                const el = document.getElementById(id);
                if (!el) return;
                el.textContent = value && String(value).trim() !== '' ? value : fallback;
            };

            const openDetailModal = (dataset) => {
                if (!kgModal) return;

                setText('kgd_ho_ten', dataset.kgHoTen);
                setText('kgd_sdt', dataset.kgSdt);
                setText('kgd_email', dataset.kgEmail);
                setText('kgd_nv', dataset.kgNv, 'Chưa chỉ định');
                setText('kgd_loai_hinh', dataset.kgLoaiHinh);
                setText('kgd_nhu_cau', dataset.kgNhuCau);
                setText('kgd_dia_chi', dataset.kgDiaChi, 'Chưa cập nhật');
                setText('kgd_dien_tich', dataset.kgDienTich ? dataset.kgDienTich + ' m²' : '—');
                setText('kgd_phong_ngu', dataset.kgPhongNgu);
                setText('kgd_tang', dataset.kgTang);
                setText('kgd_noi_that', dataset.kgNoiThat);
                setText('kgd_phap_ly', dataset.kgPhapLy);
                setText('kgd_gia', dataset.kgGia);
                setText('kgd_trang_thai', dataset.kgTrangThai);
                setText('kgd_created', dataset.kgCreated);
                setText('kgd_ghi_chu', dataset.kgGhiChu, 'Không có ghi chú');

                kgModal.show();
            };

            document.querySelectorAll('.kg-detail-trigger').forEach(cell => {
                cell.addEventListener('click', function() {
                    const row = this.closest('tr');
                    if (!row) return;
                    openDetailModal(row.dataset);
                });
                cell.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        const row = this.closest('tr');
                        if (!row) return;
                        openDetailModal(row.dataset);
                    }
                });
            });

            document.querySelectorAll('.kg-card-detail-trigger').forEach(item => {
                item.addEventListener('click', function() {
                    openDetailModal(this.dataset);
                });
                item.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        openDetailModal(this.dataset);
                    }
                });
            });

            document.querySelectorAll('.copy-phone').forEach(item => {
                item.addEventListener('click', function() {
                    copyPhone(this.dataset.phone);
                });
                item.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        copyPhone(this.dataset.phone);
                    }
                });
            });
        });
    </script>
@endpush
