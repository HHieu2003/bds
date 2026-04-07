@extends('admin.layouts.master')
@section('title', 'Quản lý Bất động sản')

@section('content')
    {{-- ══ HEADER ══ --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h1 class="page-header-title mb-1"><i class="fas fa-building text-primary"></i> Bất động sản</h1>
            <p class="page-header-sub mb-0">Quản lý toàn bộ danh sách bất động sản</p>
        </div>
        <a href="{{ route('nhanvien.admin.bat-dong-san.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus me-1"></i> Thêm BĐS mới
        </a>
    </div>

    {{-- Flash Messages --}}
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

    {{-- ══ THỐNG KÊ ══ --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-4 col-xl">
            <div class="stat-card p-3">
                <div class="stat-icon blue bg-opacity-10"><i class="fas fa-building"></i></div>
                <div>
                    <div class="stat-num fs-4">{{ number_format($thongKe['tong']) }}</div>
                    <div class="stat-label">Tổng BĐS</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl">
            <div class="stat-card p-3">
                <div class="stat-icon green bg-opacity-10"><i class="fas fa-check-circle"></i></div>
                <div>
                    <div class="stat-num fs-4">{{ number_format($thongKe['con_hang']) }}</div>
                    <div class="stat-label">Còn hàng</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl">
            <div class="stat-card p-3">
                <div class="stat-icon navy bg-opacity-10"><i class="fas fa-key"></i></div>
                <div>
                    <div class="stat-num fs-4">{{ number_format($thongKe['dang_thue']) }}</div>
                    <div class="stat-label">Đang cho thuê</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-6 col-xl">
            <div class="stat-card p-3">
                <div class="stat-icon orange bg-opacity-10"><i class="fas fa-handshake"></i></div>
                <div>
                    <div class="stat-num fs-4">{{ number_format($thongKe['dat_coc']) }}</div>
                    <div class="stat-label">Đặt cọc</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl">
            <div class="stat-card p-3">
                <div class="stat-icon red bg-opacity-10"><i class="fas fa-times-circle"></i></div>
                <div>
                    <div class="stat-num fs-4">{{ number_format($thongKe['da_ban']) }}</div>
                    <div class="stat-label">Đã giao dịch</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ══ BỘ LỌC ══ --}}
    <div class="filter-box mb-4 bds-filter-panel">
        <form method="GET" id="filterForm">
            <div class="row g-2 align-items-center mb-2">
                <div class="col-12 col-lg-4">
                    <input type="text" name="tukhoa" class="filter-ctrl filter-ctrl-search w-100"
                        value="{{ request('tukhoa') }}" placeholder="Tìm nhanh theo tiêu đề hoặc mã BĐS...">
                </div>
                <div class="col-6 col-md-3 col-lg-2">
                    <select name="nhu_cau" class="filter-ctrl w-100 filter-auto-submit">
                        <option value="">Nhu cầu</option>
                        <option value="ban" @selected(request('nhu_cau') == 'ban')>Bán</option>
                        <option value="thue" @selected(request('nhu_cau') == 'thue')>Cho thuê</option>
                    </select>
                </div>
                <div class="col-6 col-md-3 col-lg-2">
                    <select name="loai_hinh" class="filter-ctrl w-100 filter-auto-submit">
                        <option value="">Loại hình</option>
                        @foreach (['can_ho' => 'Căn hộ', 'nha_pho' => 'Nhà phố', 'biet_thu' => 'Biệt thự', 'dat_nen' => 'Đất nền', 'shophouse' => 'Shophouse'] as $v => $l)
                            <option value="{{ $v }}" @selected(request('loai_hinh', 'can_ho') == $v)>{{ $l }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-3 col-lg-2">
                    <select name="trang_thai" class="filter-ctrl w-100 filter-auto-submit">
                        <option value="">Trạng thái</option>
                        @foreach (['con_hang' => 'Còn hàng', 'dat_coc' => 'Đặt cọc', 'da_ban' => 'Đã bán', 'dang_thue' => 'Đang thuê', 'da_thue' => 'Đã thuê', 'tam_an' => 'Tạm ẩn'] as $v => $l)
                            <option value="{{ $v }}" @selected(request('trang_thai', 'con_hang') == $v)>{{ $l }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-3 col-lg-2">
                    <select name="sapxep" class="filter-ctrl w-100 filter-auto-submit">
                        <option value="moi_nhat" @selected(request('sapxep', 'moi_nhat') == 'moi_nhat')>Mới nhất</option>
                        <option value="gia_tang" @selected(request('sapxep') == 'gia_tang')>Giá tăng dần</option>
                        <option value="gia_giam" @selected(request('sapxep') == 'gia_giam')>Giá giảm dần</option>
                        <option value="luot_xem" @selected(request('sapxep') == 'luot_xem')>Lượt xem nhiều</option>
                    </select>
                </div>
                <div class="col-6 col-md-3 col-lg-2">
                    <select name="khu_vuc_id" class="filter-ctrl w-100 filter-auto-submit">
                        <option value="">Tất cả khu vực</option>
                        @foreach ($khuVucs as $kv)
                            <option value="{{ $kv->id }}" @selected((string) request('khu_vuc_id') === (string) $kv->id)>
                                {{ $kv->ten_khu_vuc }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-3 d-flex gap-2 justify-content-end">
                    <button type="submit" class="btn btn-navy"><i class="fas fa-search me-1"></i> Lọc nhanh</button>
                    @if (request()->hasAny(['tukhoa', 'nhu_cau', 'loai_hinh', 'trang_thai', 'du_an_id', 'khu_vuc_id', 'sapxep']))
                        <a href="{{ route('nhanvien.admin.bat-dong-san.index') }}" class="btn btn-danger"><i
                                class="fas fa-rotate-left me-1"></i> Xóa lọc</a>
                    @endif
                </div>
            </div>
        </form>

        @php
            $projectQueryBase = request()->except(['du_an_id', 'page']);
        @endphp
        <div class="project-tabs-wrap mt-3">
            <div class="project-tabs-title">
                Chuyển nhanh theo dự án
                @if (request('khu_vuc_id'))
                    <span class="text-muted fw-normal">- đã lọc theo khu vực</span>
                @endif
            </div>
            <div class="project-tabs-scroller">
                <a href="{{ route('nhanvien.admin.bat-dong-san.index', $projectQueryBase) }}"
                    class="project-tab {{ !request()->filled('du_an_id') ? 'active' : '' }}">
                    Tất cả dự án
                </a>
                @foreach ($duAns as $da)
                    <a href="{{ route('nhanvien.admin.bat-dong-san.index', array_merge($projectQueryBase, ['du_an_id' => $da->id])) }}"
                        class="project-tab {{ (string) request('du_an_id') === (string) $da->id ? 'active' : '' }}"
                        title="{{ $da->ten_du_an }}">
                        {{ Str::limit($da->ten_du_an, 28) }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ══ BẢNG DỮ LIỆU & MOBILE CARD ══ --}}
    @php
        $ttMap = [
            'con_hang' => ['label' => 'Còn hàng', 'color' => '#27ae60', 'bg' => '#e8f8f0', 'icon' => '✅'],
            'dat_coc' => ['label' => 'Đặt cọc', 'color' => '#e67e22', 'bg' => '#fff3e0', 'icon' => '🤝'],
            'da_ban' => ['label' => 'Đã bán', 'color' => '#e74c3c', 'bg' => '#ffeaea', 'icon' => '❌'],
            'dang_thue' => ['label' => 'Đang thuê', 'color' => '#2d6a9f', 'bg' => '#e8f4fd', 'icon' => '🔑'],
            'da_thue' => ['label' => 'Đã thuê', 'color' => '#8e44ad', 'bg' => '#f5eeff', 'icon' => '📦'],
            'tam_an' => ['label' => 'Tạm ẩn', 'color' => '#6c757d', 'bg' => '#f8f9fa', 'icon' => '⏸'],
        ];
        $loaiMap = [
            'can_ho' => 'Căn hộ',
            'nha_pho' => 'Nhà phố',
            'biet_thu' => 'Biệt thự',
            'dat_nen' => 'Đất nền',
            'shophouse' => 'Shophouse',
        ];
    @endphp

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <span class="text-muted fw-normal" style="font-size: 0.85rem">
                @if ($batDongSans->total() > 0)
                    Hiển thị <strong>{{ $batDongSans->firstItem() }}–{{ $batDongSans->lastItem() }}</strong> /
                    <strong>{{ number_format($batDongSans->total()) }}</strong> BĐS
                @else
                    Không có kết quả nào
                @endif
            </span>
        </div>

        {{-- BẢNG DESKTOP (Đã chèn thêm cột Chủ nhà và cân đối lại % độ rộng) --}}
        <div class="table-responsive tbl-desktop" style="overflow-x: hidden;">
            <table class="table table-hover align-middle mb-0"
                style="table-layout: fixed; width: 100%; word-wrap: break-word;">
                <thead class="bg-light">
                    <tr>
                        <th class="text-center" style="width: 40px">#</th>
                        <th>Bất động sản</th>
                        <th style="width: 14%">Chủ nhà</th>
                        <th style="width: 13%">Giá</th>
                        <th style="width: 9%">Diện tích</th>
                        <th style="width: 13%">Trạng thái</th>
                        <th class="text-center" style="width: 92px">Hiển thị</th>
                        <th class="text-center" style="width: 90px">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($batDongSans as $i => $bds)
                        @php $tt = $ttMap[$bds->trang_thai] ?? ['label' => $bds->trang_thai, 'color' => '#999', 'bg' => '#f5f5f5', 'icon' => '']; @endphp
                        <tr>
                            <td class="text-center text-muted">{{ $batDongSans->firstItem() + $i }}</td>

                            {{-- Cột 1: Thông tin Bất động sản --}}
                            <td>
                                <div class="d-flex align-items-start gap-2">
                                    <div class="position-relative flex-shrink-0">
                                        @if ($bds->hinh_anh)
                                            <img src="{{ asset('storage/' . $bds->hinh_anh) }}" class="rounded border"
                                                style="width: 55px; height: 45px; object-fit: cover;" alt="">
                                        @else
                                            <div class="rounded border bg-light text-muted d-flex align-items-center justify-content-center"
                                                style="width: 55px; height: 45px;"><i class="fas fa-image"></i></div>
                                        @endif
                                        @if ($bds->noi_bat)
                                            <span class="badge bg-danger position-absolute top-0 start-0 translate-middle"
                                                style="font-size: 0.5rem; padding: 0.25em 0.4em;">HOT</span>
                                        @endif
                                        <span
                                            class="badge bg-primary bg-opacity-10 text-primary border border-primary-subtle fw-normal">#{{ $bds->ma_bat_dong_san }}</span>

                                    </div>
                                    <div style="min-width: 0; flex: 1;">
                                        <a href="{{ route('nhanvien.admin.bat-dong-san.edit', $bds) }}"
                                            class="fw-bold text-navy text-decoration-none mb-1"
                                            style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; font-size: 0.85rem;"
                                            title="{{ $bds->tieu_de }}">{{ $bds->tieu_de }}</a>
                                        <div class="d-flex flex-wrap gap-1 align-items-center" style="font-size: 0.7rem">
                                            @if ($bds->duAn)
                                                <span class="text-muted text-truncate" style="max-width: 130px;"
                                                    title="{{ $bds->duAn->ten_du_an }}"><i
                                                        class="fas fa-city me-1"></i>{{ $bds->duAn->ten_du_an }}</span>
                                            @endif
                                            <span class="badge bg-light text-dark border" style="font-size: 0.66rem;">
                                                {{ $loaiMap[$bds->loai_hinh] ?? $bds->loai_hinh }}
                                            </span>
                                            <span
                                                class="badge {{ $bds->nhu_cau == 'ban' ? 'bg-warning text-dark' : 'bg-info text-dark' }}"
                                                style="font-size: 0.66rem;">
                                                {{ $bds->nhu_cau == 'ban' ? 'Bán' : 'Thuê' }}
                                            </span>
                                            @if ($bds->nhanVienPhuTrach)
                                                <span class="text-muted ms-1"><i
                                                        class="fas fa-user-tie me-1"></i>{{ $bds->nhanVienPhuTrach->ho_ten }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Cột 2: Chủ nhà (Cột MỚI) --}}
                            <td>
                                @if ($bds->chuNha)
                                    <a href="javascript:void(0)"
                                        class="text-decoration-none fw-bold text-dark d-block text-truncate btn-view-chunha"
                                        style="font-size: 0.85rem;" data-hoten="{{ $bds->chuNha->ho_ten }}"
                                        data-sdt="{{ $bds->chuNha->so_dien_thoai }}"
                                        data-email="{{ $bds->chuNha->email }}" data-cccd="{{ $bds->chuNha->cccd }}"
                                        data-diachi="{{ $bds->chuNha->dia_chi }}"
                                        data-ghichu="{{ $bds->chuNha->ghi_chu }}">
                                        <i class="fas fa-user-tie text-secondary me-1"></i>{{ $bds->chuNha->ho_ten }}
                                    </a>
                                    <div class="text-muted" style="font-size: 0.75rem;"><i
                                            class="fas fa-phone-alt text-success me-1"></i>{{ $bds->chuNha->so_dien_thoai }}
                                    </div>
                                @else
                                    <span class="text-muted fst-italic" style="font-size: 0.75rem;">— Chưa gán —</span>
                                @endif
                            </td>

                            {{-- Cột 3: Giá --}}
                            <td>
                                @if ($bds->nhu_cau == 'ban' && $bds->gia)
                                    <div class="fw-bold text-danger">{{ number_format($bds->gia / 1e9, 2) }} tỷ</div>
                                @elseif($bds->nhu_cau == 'thue' && $bds->gia_thue)
                                    <div class="fw-bold text-primary">{{ number_format($bds->gia_thue / 1e6, 1) }} tr/th
                                    </div>
                                @else
                                    <span class="text-muted fst-italic" style="font-size: 0.8rem">Thỏa thuận</span>
                                @endif
                            </td>

                            {{-- Cột 4: Diện tích --}}
                            <td>
                                <div class="fw-bold text-dark">{{ (float) $bds->dien_tich }} m²</div>
                                @if ($bds->so_phong_ngu !== null)
                                    <div class="text-muted" style="font-size: 0.8rem"><i
                                            class="fas fa-bed me-1"></i>{{ $bds->so_phong_ngu == 0 ? 'Studio' : $bds->so_phong_ngu . ' PN' }}
                                    </div>
                                @endif
                            </td>

                            {{-- Cột 5: Trạng thái --}}
                            <td>
                                <div class="dropdown">
                                    <span class="badge cursor-pointer dropdown-toggle js-status-badge"
                                        data-status-id="{{ $bds->id }}" data-bs-toggle="dropdown"
                                        style="background: {{ $tt['bg'] }}; color: {{ $tt['color'] }}; font-size: 0.75rem; white-space: normal; text-align: left;">{{ $tt['label'] }}</span>
                                    <ul class="dropdown-menu shadow-sm" style="font-size: 0.85rem">
                                        @foreach ($ttMap as $val => $item)
                                            <li><a class="dropdown-item tt-update-btn" href="javascript:void(0)"
                                                    data-id="{{ $bds->id }}"
                                                    data-val="{{ $val }}">{{ $item['icon'] }}
                                                    {{ $item['label'] }}</a></li>
                                        @endforeach
                                    </ul>
                                </div>
                            </td>

                            {{-- Cột 6: Hiển thị --}}
                            <td class="text-center">
                                <label class="toggle-sw"><input type="checkbox" data-display-id="{{ $bds->id }}"
                                        data-toggle-url="/nhan-vien/admin/bat-dong-san/{{ $bds->id }}/toggle"
                                        {{ $bds->hien_thi ? 'checked' : '' }}><span class="toggle-sw-track"><span
                                            class="toggle-sw-thumb"></span></span></label>
                                <div class="mt-1" style="font-size: 0.68rem">
                                    <span class="js-display-label {{ $bds->hien_thi ? 'text-success' : 'text-muted' }}"
                                        data-display-id="{{ $bds->id }}">{{ $bds->hien_thi ? 'Đang hiện' : 'Đang ẩn' }}</span>
                                </div>
                                <div class="text-muted" style="font-size: 0.68rem" title="Lượt xem"><i
                                        class="fas fa-eye"></i> {{ number_format($bds->luot_xem) }}</div>
                            </td>

                            {{-- Cột 7: Thao tác --}}
                            <td class="text-center">
                                <div class="btn-actions-group justify-content-center">
                                    <a href="{{ route('nhanvien.admin.bat-dong-san.edit', $bds) }}"
                                        class="btn-action btn-action-edit" title="Sửa"><i class="fas fa-pen"></i></a>
                                    <form id="frmDel_{{ $bds->id }}"
                                        action="{{ route('nhanvien.admin.bat-dong-san.destroy', $bds) }}" method="POST"
                                        class="d-none">@csrf @method('DELETE')</form>
                                    <button type="button" class="btn-action btn-action-delete btn-delete-bds"
                                        data-id="{{ $bds->id }}" data-name="{{ $bds->ma_bat_dong_san }}"
                                        title="Xóa"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="empty-state"><i class="fas fa-building text-muted mb-3"></i>
                                    <p class="text-muted mb-2">Không tìm thấy bất động sản nào</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- MOBILE CARD (Cập nhật hiển thị chủ nhà) --}}
        <div class="mobile-card-list">
            @foreach ($batDongSans as $bds)
                @php $tt = $ttMap[$bds->trang_thai] ?? ['label' => $bds->trang_thai, 'color' => '#999', 'bg' => '#f5f5f5']; @endphp
                <div class="mobile-card">
                    <div class="mobile-card-top align-items-start">
                        @if ($bds->hinh_anh)
                            <img src="{{ asset('storage/' . $bds->hinh_anh) }}" class="rounded border me-2"
                                style="width: 70px; height: 50px; object-fit: cover;" alt="">
                        @else
                            <div class="rounded border bg-light text-muted d-flex align-items-center justify-content-center me-2"
                                style="width: 70px; height: 50px;"><i class="fas fa-image"></i></div>
                        @endif
                        <div style="flex: 1; min-width: 0;">
                            <div class="d-flex justify-content-between mb-1">
                                <span
                                    class="badge bg-primary bg-opacity-10 text-primary border border-primary-subtle fw-normal"
                                    style="font-size: 0.7rem">#{{ $bds->ma_bat_dong_san }}</span>
                                <span class="badge"
                                    style="background: {{ $tt['bg'] }}; color: {{ $tt['color'] }}">{{ $tt['label'] }}</span>
                            </div>
                            <a href="{{ route('nhanvien.admin.bat-dong-san.edit', $bds) }}"
                                class="fw-bold text-navy text-decoration-none d-block text-truncate mb-1">{{ $bds->tieu_de }}</a>
                        </div>
                    </div>

                    {{-- Dòng Chủ nhà trên Mobile --}}
                    @if ($bds->chuNha)
                        <div class="px-3 pb-2 pt-0">
                            <a href="javascript:void(0)"
                                class="badge bg-secondary bg-opacity-10 text-dark border text-decoration-none py-2 px-2 d-inline-block btn-view-chunha"
                                data-hoten="{{ $bds->chuNha->ho_ten }}" data-sdt="{{ $bds->chuNha->so_dien_thoai }}"
                                data-email="{{ $bds->chuNha->email }}" data-cccd="{{ $bds->chuNha->cccd }}"
                                data-diachi="{{ $bds->chuNha->dia_chi }}" data-ghichu="{{ $bds->chuNha->ghi_chu }}">
                                <i class="fas fa-user-tie text-secondary me-1"></i> {{ $bds->chuNha->ho_ten }} - <span
                                    class="text-success"><i
                                        class="fas fa-phone-alt ms-1 me-1"></i>{{ $bds->chuNha->so_dien_thoai }}</span>
                            </a>
                        </div>
                    @endif

                    <div class="mobile-card-meta">
                        <div>
                            @if ($bds->nhu_cau == 'ban')
                                <span class="fw-bold text-danger">{{ number_format($bds->gia / 1e9, 2) }} tỷ</span>
                            @else
                                <span class="fw-bold text-primary">{{ number_format($bds->gia_thue / 1e6, 1) }}
                                    tr/th</span>
                            @endif
                        </div>
                        <div><i class="fas fa-vector-square text-muted"></i> {{ (float) $bds->dien_tich }}m²</div>
                        @if ($bds->so_phong_ngu !== null)
                            <div><i class="fas fa-bed text-muted"></i>
                                {{ $bds->so_phong_ngu == 0 ? 'Studio' : $bds->so_phong_ngu . 'PN' }}</div>
                        @endif
                    </div>
                    <div class="mobile-card-foot">
                        <label class="toggle-sw"><input type="checkbox"
                                data-toggle-url="/nhan-vien/admin/bat-dong-san/{{ $bds->id }}/toggle"
                                {{ $bds->hien_thi ? 'checked' : '' }}><span class="toggle-sw-track"><span
                                    class="toggle-sw-thumb"></span></span></label>
                        <div class="btn-actions-group">
                            <a href="{{ route('nhanvien.admin.bat-dong-san.edit', $bds) }}"
                                class="btn-action btn-action-edit"><i class="fas fa-pen"></i></a>
                            <button type="button" class="btn-action btn-action-delete btn-delete-bds"
                                data-id="{{ $bds->id }}" data-name="{{ $bds->ma_bat_dong_san }}"><i
                                    class="fas fa-trash"></i></button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if ($batDongSans->hasPages())
            <div class="card-footer bg-white border-top p-3 d-flex justify-content-center justify-content-md-end">
                {{ $batDongSans->links('pagination::bootstrap-5') }}</div>
        @endif
    </div>

    {{-- ============================================================ --}}
    {{-- MODAL HIỂN THỊ THÔNG TIN CHỦ NHÀ (POPUP)                     --}}
    {{-- ============================================================ --}}
    <div class="modal fade" id="modalChuNha" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-light border-bottom-0 pb-3">
                    <h5 class="modal-title fw-bold text-navy"><i class="fas fa-address-card text-primary me-2"></i>Thông
                        tin Nguồn hàng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-0">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="avatar bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fs-3 fw-bold flex-shrink-0"
                            style="width: 60px; height: 60px;" id="md_avatar">
                            A
                        </div>
                        <div>
                            <h5 class="mb-1 fw-bold text-dark" id="md_hoten">Nguyễn Văn A</h5>
                            <span
                                class="badge bg-success bg-opacity-10 text-success border border-success-subtle px-2 py-1"><i
                                    class="fas fa-check-circle me-1"></i>Đã xác thực</span>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-6">
                            <label class="text-muted d-block"
                                style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Số điện
                                thoại</label>
                            <div class="fw-bold text-dark fs-6" id="md_sdt"></div>
                        </div>
                        <div class="col-6">
                            <label class="text-muted d-block"
                                style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Email</label>
                            <div class="fw-bold text-dark text-truncate" id="md_email" style="font-size: 0.9rem;"
                                title=""></div>
                        </div>
                        <div class="col-12">
                            <label class="text-muted d-block"
                                style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Số CCCD /
                                CMND</label>
                            <div class="fw-bold text-dark" id="md_cccd" style="font-size: 0.9rem;"></div>
                        </div>
                        <div class="col-12">
                            <label class="text-muted d-block"
                                style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Địa chỉ liên
                                hệ</label>
                            <div class="text-dark" id="md_diachi" style="font-size: 0.9rem;"></div>
                        </div>
                        <div class="col-12">
                            <label class="text-muted d-block"
                                style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Ghi chú nội
                                bộ</label>
                            <div class="bg-light p-2 rounded text-dark border" id="md_ghichu"
                                style="font-size: 0.85rem; min-height: 50px;"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 bg-light">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Đóng</button>
                    <a href="tel:" id="md_call" class="btn btn-success px-4"><i class="fas fa-phone-alt me-2"></i>
                        Gọi điện ngay</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const CSRF = document.querySelector('meta[name=csrf-token]').content;

            // Xóa BĐS
            document.querySelectorAll('.btn-delete-bds').forEach(btn => {
                btn.addEventListener('click', function() {
                    const name = this.dataset.name,
                        id = this.dataset.id;
                    confirmDelete('bất động sản #' + name, function() {
                        document.getElementById('frmDel_' + id).submit();
                    });
                });
            });

            const statusMap = {
                con_hang: {
                    label: 'Còn hàng',
                    color: '#27ae60',
                    bg: '#e8f8f0'
                },
                dat_coc: {
                    label: 'Đặt cọc',
                    color: '#e67e22',
                    bg: '#fff3e0'
                },
                da_ban: {
                    label: 'Đã bán',
                    color: '#e74c3c',
                    bg: '#ffeaea'
                },
                dang_thue: {
                    label: 'Đang thuê',
                    color: '#2d6a9f',
                    bg: '#e8f4fd'
                },
                da_thue: {
                    label: 'Đã thuê',
                    color: '#8e44ad',
                    bg: '#f5eeff'
                },
                tam_an: {
                    label: 'Tạm ẩn',
                    color: '#6c757d',
                    bg: '#f8f9fa'
                }
            };

            // Đổi trạng thái AJAX
            document.querySelectorAll('.tt-update-btn').forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    const bdsId = this.dataset.id;
                    const newStatus = this.dataset.val;

                    fetch(`/nhan-vien/admin/bat-dong-san/${this.dataset.id}/trang-thai`, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': CSRF,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            trang_thai: newStatus
                        })
                    }).then(r => r.json()).then(data => {
                        if (data.ok) {
                            const badge = document.querySelector(
                                `.js-status-badge[data-status-id="${bdsId}"]`);
                            const statusInfo = statusMap[newStatus];
                            if (badge && statusInfo) {
                                badge.textContent = statusInfo.label;
                                badge.style.background = statusInfo.bg;
                                badge.style.color = statusInfo.color;
                            }
                            showAdminToast('Đã cập nhật trạng thái', 'success');
                        } else showAdminToast('Lỗi cập nhật', 'error');
                    }).catch(() => showAdminToast('Lỗi kết nối', 'error'));
                });
            });

            // Toggle hiển thị AJAX
            document.querySelectorAll('input[type="checkbox"][data-toggle-url]').forEach(cb => {
                cb.addEventListener('change', function() {
                    const checkbox = this;
                    const oldState = !checkbox.checked;
                    checkbox.disabled = true;

                    fetch(checkbox.dataset.toggleUrl, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': CSRF,
                            'Accept': 'application/json'
                        }
                    }).then(r => r.json()).then(data => {
                        const ok = data.ok === true || typeof data.hien_thi !== 'undefined';
                        if (!ok) {
                            checkbox.checked = oldState;
                            showAdminToast('Không thể cập nhật hiển thị', 'error');
                            return;
                        }

                        const isVisible = typeof data.hien_thi === 'boolean' ? data
                            .hien_thi : checkbox.checked;
                        checkbox.checked = isVisible;
                        const label = document.querySelector(
                            `.js-display-label[data-display-id="${checkbox.dataset.displayId}"]`
                        );
                        if (label) {
                            label.textContent = isVisible ? 'Đang hiện' : 'Đang ẩn';
                            label.classList.toggle('text-success', isVisible);
                            label.classList.toggle('text-muted', !isVisible);
                        }
                        showAdminToast('Đã cập nhật hiển thị', 'success');
                    }).catch(() => {
                        checkbox.checked = oldState;
                        showAdminToast('Lỗi kết nối', 'error');
                    }).finally(() => {
                        checkbox.disabled = false;
                    });
                });
            });

            // Bật Modal Thông tin Chủ nhà (JS Thuần)
            document.querySelectorAll('.btn-view-chunha').forEach(btn => {
                btn.addEventListener('click', function() {
                    // Đẩy dữ liệu vào Modal
                    const hoten = this.dataset.hoten;
                    const sdt = this.dataset.sdt;

                    document.getElementById('md_avatar').textContent = hoten.charAt(0)
                        .toUpperCase();
                    document.getElementById('md_hoten').textContent = hoten;
                    document.getElementById('md_sdt').innerHTML =
                        `<i class="fas fa-phone-alt text-success me-1"></i> ${sdt}`;
                    document.getElementById('md_call').href = 'tel:' + sdt;

                    const email = this.dataset.email;
                    document.getElementById('md_email').textContent = email ? email : '—';
                    document.getElementById('md_email').title = email ? email : '';

                    document.getElementById('md_cccd').textContent = this.dataset.cccd ? this
                        .dataset.cccd : '—';
                    document.getElementById('md_diachi').textContent = this.dataset.diachi ? this
                        .dataset.diachi : '—';
                    document.getElementById('md_ghichu').innerHTML = this.dataset.ghichu ? this
                        .dataset.ghichu.replace(/\n/g, '<br>') :
                        '<span class="text-muted fst-italic">Không có ghi chú</span>';

                    // Mở modal bằng Bootstrap API
                    new bootstrap.Modal(document.getElementById('modalChuNha')).show();
                });
            });
        });
    </script>
@endpush

@push('styles')
    <style>
        .bds-filter-panel {
            border: 1px solid rgba(15, 23, 42, 0.08);
        }

        .project-tabs-title {
            font-size: 0.78rem;
            font-weight: 700;
            color: #475569;
            margin-bottom: 0.5rem;
        }

        .project-tabs-scroller {
            display: flex;
            gap: 0.45rem;
            overflow-x: auto;
            padding-bottom: 0.15rem;
        }

        .project-tab {
            flex: 0 0 auto;
            text-decoration: none;
            font-size: 0.78rem;
            font-weight: 600;
            color: #334155;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 999px;
            padding: 0.38rem 0.75rem;
            transition: all 0.18s ease;
            white-space: nowrap;
        }

        .project-tab:hover {
            color: #0f172a;
            background: #eef2ff;
            border-color: #c7d2fe;
        }

        .project-tab.active {
            color: #fff;
            background: linear-gradient(135deg, #1d4ed8, #2563eb);
            border-color: #1d4ed8;
            box-shadow: 0 6px 16px rgba(37, 99, 235, 0.25);
        }
    </style>
@endpush
