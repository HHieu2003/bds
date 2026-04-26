@extends('admin.layouts.master')
@section('title', 'Quản lý Bất động sản')

@section('content')
    {{-- ══ HEADER ══ --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h1 class="page-header-title mb-1"><i class="fas fa-building text-primary"></i> Bất động sản</h1>
            <div style="font-size:.78rem;color:var(--text-sub)">
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <span><strong>{{ number_format($thongKe['tong']) }}</strong> tổng</span>
                    <span
                        style="width:4px;height:4px;border-radius:50%;background:var(--text-muted);display:inline-block"></span>
                    <span style="color:#4CAF50"><strong>{{ number_format($thongKe['con_hang']) }}</strong> còn hàng</span>
                    <span
                        style="width:4px;height:4px;border-radius:50%;background:var(--text-muted);display:inline-block"></span>
                    <span style="color:#2d6a9f"><strong>{{ number_format($thongKe['dang_thue']) }}</strong> đang thuê</span>
                    <span
                        style="width:4px;height:4px;border-radius:50%;background:var(--text-muted);display:inline-block"></span>
                    <span style="color:#FF9800"><strong>{{ number_format($thongKe['dat_coc']) }}</strong> đặt cọc</span>
                    <span
                        style="width:4px;height:4px;border-radius:50%;background:var(--text-muted);display:inline-block"></span>
                    <span style="color:#F44336"><strong>{{ number_format($thongKe['da_ban']) }}</strong> đã bán</span>
                </div>
            </div>
        </div>
        @php
            $currentFilters = request()->except(['page']);
            $currentListUrl = url()->full();
            $createPrefill = array_merge($currentFilters, ['redirect_to' => $currentListUrl]);
        @endphp
        <a href="{{ route('nhanvien.admin.bat-dong-san.create', $createPrefill) }}" class="btn btn-primary shadow-sm">
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

    {{-- ══ BỘ LỌC ══ --}}
    <div class="filter-box mb-4 bds-filter-panel">
        <form method="GET" id="filterForm">
            <input type="hidden" name="du_an_id" id="duAnIdInput" value="{{ request('du_an_id') }}">
            <div class="row g-2 align-items-end mb-2 bds-filter-row">
                <div class="col-12 col-lg-3">
                    <input type="text" name="tukhoa" class="filter-ctrl filter-ctrl-search w-100"
                        value="{{ request('tukhoa') }}" placeholder="Tìm nhanh theo tiêu đề hoặc mã BĐS...">
                </div>
                <div class="col-6 col-lg" style="min-width: 115px;">
                    <select name="trang_thai" class="filter-ctrl w-100 filter-auto-submit">
                        <option value="">Trạng thái</option>
                        @foreach (['con_hang' => 'Còn hàng', 'dat_coc' => 'Đặt cọc', 'da_ban' => 'Đã bán', 'dang_thue' => 'Đang thuê', 'da_thue' => 'Đã thuê', 'tam_an' => 'Tạm ẩn'] as $v => $l)
                            <option value="{{ $v }}" @selected(request('trang_thai') == $v)>{{ $l }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-lg" style="min-width: 110px;">
                    <select name="khu_vuc_id" class="filter-ctrl w-100" id="khuVucSelect"
                        onchange="document.getElementById('duAnIdInput').value=''; this.closest('form').submit();">
                        <option value="">Khu vực</option>
                        @foreach ($khuVucs as $kv)
                            <option value="{{ $kv->id }}" @selected((string) request('khu_vuc_id') === (string) $kv->id)>
                                {{ $kv->ten_khu_vuc }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-lg" style="min-width: 50px;">
                    <select name="toa" class="filter-ctrl w-100 filter-auto-submit">
                        <option value="">Tòa</option>
                        @foreach ($toaOptions as $toa)
                            <option value="{{ $toa }}" @selected((string) request('toa') === (string) $toa)>
                                {{ $toa }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-lg" style="min-width: px;">
                    <select name="so_phong_ngu" class="filter-ctrl w-100 filter-auto-submit">
                        <option value="">Phòng ngủ</option>
                        @foreach ($soPhongNguOptions as $pn)
                            <option value="{{ $pn }}" @selected((string) request('so_phong_ngu') === (string) $pn)>
                                {{ $pn == 0 ? 'Studio' : $pn }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-lg" style="min-width: 90px;">
                    <select name="noi_that" class="filter-ctrl w-100 filter-auto-submit">
                        <option value="">Nội thất</option>
                        @foreach ($noiThatOptions as $value => $label)
                            <option value="{{ $value }}" @selected(request('noi_that') === $value)>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-lg" style="min-width: 110px;">
                    <select name="sapxep" class="filter-ctrl w-100 filter-auto-submit">
                        <option value="moi_nhat" @selected(request('sapxep', 'moi_nhat') == 'moi_nhat')>Mới nhất</option>
                        <option value="gia_tang" @selected(request('sapxep') == 'gia_tang')>Giá tăng ↑</option>
                        <option value="gia_giam" @selected(request('sapxep') == 'gia_giam')>Giá giảm ↓</option>
                        <option value="luot_xem" @selected(request('sapxep') == 'luot_xem')>Lượt xem</option>
                    </select>
                </div>
                @if (request()->hasAny([
                        'tukhoa',
                        'trang_thai',
                        'du_an_id',
                        'khu_vuc_id',
                        'sapxep',
                        'toa',
                        'so_phong_ngu',
                        'noi_that',
                        'gia_tu',
                        'gia_den',
                    ]))
                    <div class="col-12 col-lg-auto">
                        <a href="{{ route('nhanvien.admin.bat-dong-san.index') }}" class="btn btn-sm btn-danger w-100">
                            <i class="fas fa-rotate-left me-1"></i>
                        </a>
                    </div>
                @endif
            </div>
        </form>

        @php
            // Base query cho tab: bỏ du_an_id, toa, page — nhưng GIỮ khu_vuc_id
            $projectQueryBase = request()->except(['du_an_id', 'toa', 'page']);
            // Base query cho nút ALL: giữ khu_vuc_id hiện tại, bỏ du_an_id + toa + page
            $allTabQuery = request()->except(['du_an_id', 'toa', 'page']);
            // Tên khu vực đang chọn (để hiển thị label ALL)
            $selectedKhuVuc = request('khu_vuc_id')
                ? $khuVucs->firstWhere('id', request('khu_vuc_id'))
                : null;
        @endphp
        <div class="project-tabs-wrap mt-3">
            <div class="project-tabs-title">
                @if ($selectedKhuVuc)
                    BĐS tại <strong>{{ $selectedKhuVuc->ten_khu_vuc }}</strong>
                @else
                    Chuyển nhanh theo dự án
                @endif
            </div>
            <div class="project-tabs-scroller">
                <a href="{{ route('nhanvien.admin.bat-dong-san.index', $allTabQuery) }}"
                    class="project-tab {{ !request()->filled('du_an_id') ? 'active' : '' }}">
                    @if ($selectedKhuVuc)
                        Tất cả — {{ $selectedKhuVuc->ten_khu_vuc }}
                    @else
                        ALL
                    @endif
                </a>
                @foreach ($duAns as $da)
                    <a href="{{ route('nhanvien.admin.bat-dong-san.index', array_merge($projectQueryBase, ['du_an_id' => $da->id, 'khu_vuc_id' => $da->khu_vuc_id])) }}"
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
        $thoiGianVaoThueMap = [
            'ngay_lap_tuc' => 'Vào ở ngay',
            'sau_1_tuan' => 'Sau 1 tuần',
            'sau_1_thang' => 'Sau 1 tháng',
            'thoa_thuan' => 'Thỏa thuận',
        ];
        $hinhThucThanhToanMap = [
            'thang_1' => 'Thanh toán 1 tháng/lần',
            'thang_3' => 'Thanh toán 3 tháng/lần',
            'thang_6' => 'Thanh toán 6 tháng/lần',
            'nam_1' => 'Thanh toán 1 năm/lần',
        ];
        $nhanVienDangNhap = auth('nhanvien')->user();
        $isSaleView = ($nhanVienDangNhap?->vai_tro ?? null) === 'sale';
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
                        <th class="text-center" style="width: 50px">#</th>
                        <th>Bất động sản</th>
                        @unless ($isSaleView)
                            <th style="width: 12%">Chủ nhà</th>
                        @endunless
                        <th style="width: 10%">Giá</th>
                        <th style="width: 9%">Diện tích</th>
                        <th style="width: 12%">Trạng thái</th>
                        <th class="text-center" style="width: 70px">Hiển thị</th>
                        <th class="text-center" style="width: 105px">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($batDongSans as $i => $bds)
                        @php
                            $tt = $ttMap[$bds->trang_thai] ?? [
                                'label' => $bds->trang_thai,
                                'color' => '#999',
                                'bg' => '#f5f5f5',
                                'icon' => '',
                            ];
                            $giaHienThi =
                                $bds->nhu_cau == 'ban'
                                    ? ($bds->gia
                                        ? number_format($bds->gia, 0, ',', '.') . ' VNĐ'
                                        : 'Thỏa thuận')
                                    : ($bds->gia_thue
                                        ? number_format($bds->gia_thue, 0, ',', '.') . ' VNĐ/tháng'
                                        : 'Thỏa thuận');
                            $hinhAnhBds = $bds->hinh_anh ? \Storage::disk('r2')->url($bds->hinh_anh) : '';
                            $moTaText = trim(strip_tags((string) $bds->mo_ta));
                        @endphp
                        <tr>
                            <td class="text-center text-muted">{{ $batDongSans->firstItem() + $i }}</td>

                            {{-- Cột 1: Thông tin Bất động sản --}}
                            <td>
                                <div class="d-flex align-items-start gap-2">
                                    <div class="position-relative d-flex flex-column flex-shrink-0">
                                        @if ($bds->hinh_anh)
                                            <img src="{{ \Storage::disk('r2')->url($bds->hinh_anh) }}" class="rounded border"
                                                style="min-width: 90px; height: 45px; object-fit: cover;" alt="">
                                        @else
                                            <div class="rounded border bg-light text-muted d-flex align-items-center justify-content-center"
                                                style="width: 55px; height: 45px;"><i class="fas fa-image"></i></div>
                                        @endif
                                        @if ($bds->noi_bat)
                                            <span class="badge bg-danger position-absolute top-0 end-0 translate-middle"
                                                style="font-size: 0.5rem; padding: 0.25em 0.4em;">HOT</span>
                                        @endif
                                        <span
                                            class="badge {{ $bds->nhu_cau == 'ban' ? 'bg-warning text-dark' : 'bg-info text-dark' }} position-absolute top-0 start-0 translate-middle"
                                            style="font-size: 0.66rem; padding: 0.25em 0.4em;">
                                            {{ $bds->nhu_cau == 'ban' ? 'Bán' : 'Thuê' }}
                                        </span>
                                        <span
                                            class="badge bg-primary bg-opacity-10 text-primary border border-primary-subtle fw-normal">#{{ $bds->ma_bat_dong_san }}</span>
                                        <div style="font-size: 0.7rem; color: #666; margin-top: 2px;">
                                            @if ($bds->toa)
                                                <div><strong>{{ $bds->toa }}</strong> - @if ($bds->ma_can)
                                                        <strong>{{ $bds->ma_can }}</strong>
                                                    @endif
                                                </div>
                                            @elseif ($bds->ma_can)
                                                <div><strong>{{ $bds->ma_can }}</strong></div>
                                            @endif
                                        </div>
                                    </div>
                                    <div style="min-width: 0; flex: 1;">
                                        <button type="button"
                                            class="btn btn-link p-0 fw-bold text-navy text-decoration-none mb-1 text-start btn-view-bds"
                                            data-bs-toggle="modal" data-bs-target="#modalViewBds"
                                            data-id="{{ $bds->id }}" data-ma="{{ $bds->ma_bat_dong_san }}"
                                            data-tieu-de="{{ $bds->tieu_de }}"
                                            data-nhu-cau="{{ $bds->nhu_cau == 'ban' ? 'Bán' : 'Thuê' }}"
                                            data-loai-hinh="{{ $loaiMap[$bds->loai_hinh] ?? $bds->loai_hinh }}"
                                            data-gia="{{ $giaHienThi }}"
                                            data-dien-tich="{{ (float) $bds->dien_tich }} m²"
                                            data-phong-ngu="{{ $bds->so_phong_ngu === null ? '—' : ($bds->so_phong_ngu == 0 ? 'Studio' : $bds->so_phong_ngu) }}"
                                            data-toa="{{ $bds->toa ?: '—' }}" data-tang="{{ $bds->tang ?: '—' }}"
                                            data-ma-can="{{ $bds->ma_can ?: '—' }}"
                                            data-noi-that="{{ $bds->noi_that ?: '—' }}"
                                            data-phap-ly="{{ $bds->phap_ly ?: '—' }}"
                                            data-thoi-gian-vao-thue="{{ $thoiGianVaoThueMap[$bds->thoi_gian_vao_thue] ?? '—' }}"
                                            data-hinh-thuc-thanh-toan="{{ $hinhThucThanhToanMap[$bds->hinh_thuc_thanh_toan] ?? '—' }}"
                                            data-trang-thai="{{ $tt['label'] }}"
                                            data-hien-thi="{{ $bds->hien_thi ? 'Đang bật' : 'Đang tắt' }}"
                                            data-luot-xem="{{ number_format($bds->luot_xem) }}"
                                            data-du-an="{{ $bds->duAn?->ten_du_an ?: '—' }}"
                                            data-nv-phu-trach="{{ $bds->nhanVienPhuTrach?->ho_ten ?: '—' }}"
                                            data-ngay-dang="{{ $bds->thoi_diem_dang?->format('d/m/Y H:i') ?: '—' }}"
                                            data-cap-nhat="{{ $bds->updated_at?->format('d/m/Y H:i') ?: '—' }}"
                                            data-ghi-chu="{{ $bds->ghi_chu_noi_bo ?: '' }}"
                                            data-image="{{ $hinhAnhBds }}"
                                            style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; font-size: 0.75rem;"
                                            title="Xem nhanh thông tin BĐS">{{ $bds->tieu_de }}</button>
                                        <div class="d-flex flex-wrap gap-1 align-items-center" style="font-size: 0.7rem">
                                            @if ($bds->duAn)
                                                <span class="text-muted text-truncate" style="max-width: 130px;"
                                                    title="{{ $bds->duAn->ten_du_an }}">{{ $bds->duAn->ten_du_an }}</span>
                                            @endif
                                            @if ($bds->nhanVienPhuTrach)
                                                <span class="text-muted ms-1"><i
                                                        class="fas fa-user-tie me-1"></i>{{ $bds->nhanVienPhuTrach->ho_ten }}</span>
                                            @endif
                                        </div>
                                        <div class="text-muted mt-1" style="font-size: 0.68rem;">
                                            <i class="far fa-clock me-1"></i>Cập nhật:
                                            <strong
                                                class="text-dark">{{ $bds->updated_at?->format('d/m/Y H:i') }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Cột 2: Chủ nhà (ẩn với sale) --}}
                            @unless ($isSaleView)
                                <td>
                                    @if ($bds->chuNha)
                                        <a href="javascript:void(0)"
                                            class="text-decoration-none fw-bold text-dark d-block text-truncate btn-view-chunha"
                                            style="font-size: 0.7rem;" data-hoten="{{ $bds->chuNha->ho_ten }}"
                                            data-sdt="{{ $bds->chuNha->so_dien_thoai }}"
                                            data-email="{{ $bds->chuNha->email }}" data-cccd="{{ $bds->chuNha->cccd }}"
                                            data-diachi="{{ $bds->chuNha->dia_chi }}"
                                            data-ghichu="{{ $bds->chuNha->ghi_chu }}">
                                            {{ $bds->chuNha->ho_ten }}
                                        </a>
                                        <div class="text-muted" style="font-size: 0.75rem;">{{ $bds->chuNha->so_dien_thoai }}
                                        </div>
                                    @else
                                        <span class="text-muted fst-italic" style="font-size: 0.75rem;">— Chưa gán —</span>
                                    @endif
                                </td>
                            @endunless

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
                                    <div class="text-muted" style="font-size: 0.8rem">
                                        {{ $bds->so_phong_ngu == 0 ? 'Studio' : $bds->so_phong_ngu }}
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
                                        {{ $bds->hien_thi ? 'checked' : '' }}
                                        {{ $bds->trang_thai !== 'con_hang' ? 'disabled' : '' }}><span
                                        class="toggle-sw-track"><span class="toggle-sw-thumb"></span></span></label>
                                <div class="text-muted" style="font-size: 0.68rem" title="Lượt xem"><i
                                        class="fas fa-eye"></i>
                                    {{ number_format($bds->luot_xem) }}</div>
                            </td>

                            {{-- Cột 7: Thao tác --}}
                            <td class="text-center">
                                <div class="btn-actions-group justify-content-center">
                                    @unless ($isSaleView)
                                        <button type="button"
                                            class="btn btn-sm btn-outline-success btn-action btn-quick-update-bds"
                                            data-bs-toggle="modal" data-bs-target="#modalQuickUpdateBds"
                                            data-id="{{ $bds->id }}" data-code="{{ $bds->ma_bat_dong_san }}"
                                            data-name="{{ $bds->tieu_de }}" data-nhu-cau="{{ $bds->nhu_cau }}"
                                            data-gia="{{ $bds->gia ?? '' }}" data-gia-thue="{{ $bds->gia_thue ?? '' }}"
                                            title="Cập nhật nhanh (Làm mới ngày)">
                                            <i class="fas fa-headset"></i>
                                        </button>
                                    @endunless
                                    <a href="{{ route('nhanvien.admin.bat-dong-san.edit', array_merge(['batDongSan' => $bds->id], $currentFilters, ['redirect_to' => $currentListUrl])) }}"
                                        class="btn-action btn-action-edit" title="Sửa"><i class="fas fa-pen"></i></a>
                                    @unless ($isSaleView)

                                        <form id="frmDel_{{ $bds->id }}"
                                            action="{{ route('nhanvien.admin.bat-dong-san.destroy', array_merge(['batDongSan' => $bds->id], $currentFilters, ['redirect_to' => $currentListUrl])) }}"
                                            method="POST" class="d-none">@csrf @method('DELETE')</form>
                                        <button type="button" class="btn-action btn-action-delete btn-delete-bds"
                                            data-id="{{ $bds->id }}" data-name="{{ $bds->ma_bat_dong_san }}"
                                            title="Xóa"><i class="fas fa-trash"></i></button>
                                    @else
                                        <button type="button" class="btn-action btn-action-delete btn-delete-bds"
                                            data-no-permission="1" data-name="{{ $bds->ma_bat_dong_san }}"
                                            title="Bạn không có quyền xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @endunless
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $isSaleView ? 7 : 8 }}">
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
                @php
                    $tt = $ttMap[$bds->trang_thai] ?? [
                        'label' => $bds->trang_thai,
                        'color' => '#999',
                        'bg' => '#f5f5f5',
                    ];
                    $giaHienThi =
                        $bds->nhu_cau == 'ban'
                            ? ($bds->gia
                                ? number_format($bds->gia, 0, ',', '.') . ' VNĐ'
                                : 'Thỏa thuận')
                            : ($bds->gia_thue
                                ? number_format($bds->gia_thue, 0, ',', '.') . ' VNĐ/tháng'
                                : 'Thỏa thuận');
                    $hinhAnhBds = $bds->hinh_anh ? \Storage::disk('r2')->url($bds->hinh_anh) : '';
                @endphp
                <div class="mobile-card">
                    <div class="mobile-card-top align-items-start">
                        @if ($bds->hinh_anh)
                            <img src="{{ \Storage::disk('r2')->url($bds->hinh_anh) }}" class="rounded border me-2"
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
                            <button type="button"
                                class="btn btn-link p-0 fw-bold text-navy text-decoration-none d-block text-truncate mb-1 text-start btn-view-bds"
                                data-bs-toggle="modal" data-bs-target="#modalViewBds" data-id="{{ $bds->id }}"
                                data-ma="{{ $bds->ma_bat_dong_san }}" data-tieu-de="{{ $bds->tieu_de }}"
                                data-nhu-cau="{{ $bds->nhu_cau == 'ban' ? 'Bán' : 'Thuê' }}"
                                data-loai-hinh="{{ $loaiMap[$bds->loai_hinh] ?? $bds->loai_hinh }}"
                                data-gia="{{ $giaHienThi }}" data-dien-tich="{{ (float) $bds->dien_tich }} m²"
                                data-phong-ngu="{{ $bds->so_phong_ngu === null ? '—' : ($bds->so_phong_ngu == 0 ? 'Studio' : $bds->so_phong_ngu) }}"
                                data-toa="{{ $bds->toa ?: '—' }}" data-tang="{{ $bds->tang ?: '—' }}"
                                data-ma-can="{{ $bds->ma_can ?: '—' }}" data-noi-that="{{ $bds->noi_that ?: '—' }}"
                                data-phap-ly="{{ $bds->phap_ly ?: '—' }}"
                                data-thoi-gian-vao-thue="{{ $thoiGianVaoThueMap[$bds->thoi_gian_vao_thue] ?? '—' }}"
                                data-hinh-thuc-thanh-toan="{{ $hinhThucThanhToanMap[$bds->hinh_thuc_thanh_toan] ?? '—' }}"
                                data-trang-thai="{{ $tt['label'] }}"
                                data-hien-thi="{{ $bds->hien_thi ? 'Đang bật' : 'Đang tắt' }}"
                                data-luot-xem="{{ number_format($bds->luot_xem) }}"
                                data-du-an="{{ $bds->duAn?->ten_du_an ?: '—' }}"
                                data-nv-phu-trach="{{ $bds->nhanVienPhuTrach?->ho_ten ?: '—' }}"
                                data-ngay-dang="{{ $bds->thoi_diem_dang?->format('d/m/Y H:i') ?: '—' }}"
                                data-cap-nhat="{{ $bds->updated_at?->format('d/m/Y H:i') ?: '—' }}"
                                data-ghi-chu="{{ $bds->ghi_chu_noi_bo ?: '' }}" data-image="{{ $hinhAnhBds }}"
                                title="Xem nhanh thông tin BĐS">{{ $bds->tieu_de }}</button>
                        </div>
                    </div>

                    {{-- Dòng Chủ nhà trên Mobile (ẩn với sale) --}}
                    @if (!$isSaleView && $bds->chuNha)
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
                                {{ $bds->so_phong_ngu == 0 ? 'Studio' : $bds->so_phong_ngu }}</div>
                        @endif
                        <div><i class="far fa-clock text-muted"></i> {{ $bds->updated_at?->format('d/m H:i') }}</div>
                    </div>
                    <div class="mobile-card-foot">
                        <label class="toggle-sw"><input type="checkbox" data-display-id="{{ $bds->id }}"
                                data-toggle-url="/nhan-vien/admin/bat-dong-san/{{ $bds->id }}/toggle"
                                {{ $bds->hien_thi ? 'checked' : '' }}
                                {{ $bds->trang_thai !== 'con_hang' ? 'disabled' : '' }}><span
                                class="toggle-sw-track"><span class="toggle-sw-thumb"></span></span></label>
                        <div class="btn-actions-group">
                            @unless ($isSaleView)
                                <button type="button" class="btn btn-sm btn-outline-success btn-action btn-quick-update-bds"
                                    data-bs-toggle="modal" data-bs-target="#modalQuickUpdateBds"
                                    data-id="{{ $bds->id }}" data-code="{{ $bds->ma_bat_dong_san }}"
                                    data-name="{{ $bds->tieu_de }}" data-nhu-cau="{{ $bds->nhu_cau }}"
                                    data-gia="{{ $bds->gia ?? '' }}" data-gia-thue="{{ $bds->gia_thue ?? '' }}"
                                    title="Cập nhật nhanh (Làm mới ngày)">
                                    <i class="fas fa-headset"></i>
                                </button>
                            @endunless
                            <a href="{{ route('nhanvien.admin.bat-dong-san.edit', $bds) }}"
                                class="btn-action btn-action-edit"><i class="fas fa-pen"></i></a>
                            @unless ($isSaleView)
                                <button type="button" class="btn-action btn-action-delete btn-delete-bds"
                                    data-id="{{ $bds->id }}" data-name="{{ $bds->ma_bat_dong_san }}"><i
                                        class="fas fa-trash"></i></button>
                            @else
                                <button type="button" class="btn-action btn-action-delete btn-delete-bds"
                                    data-no-permission="1" data-name="{{ $bds->ma_bat_dong_san }}"><i
                                        class="fas fa-trash"></i></button>
                            @endunless
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if ($batDongSans->hasPages())
            <div class="card-footer bg-white border-top p-3 d-flex justify-content-center justify-content-md-end"
                id="paginationContainer">
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

    {{-- ============================================================ --}}
    {{-- MODAL CẬP NHẬT NHANH BẤT ĐỘNG SẢN                           --}}
    {{-- ============================================================ --}}
    <div class="modal fade" id="modalQuickUpdateBds" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form id="quickUpdateBdsForm" class="modal-content border-0 shadow" method="POST" action="#">
                @csrf
                @method('PUT')
                <input type="hidden" name="is_quick_update" value="1">

                <div class="modal-header bg-light border-bottom-0">
                    <h5 class="modal-title fw-bold">
                        <i class="fas fa-headset text-success me-2"></i>Cập nhật nhanh BĐS
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4">
                    <div class="alert alert-info border-0 bg-info bg-opacity-10 mb-4" style="font-size:0.85rem">
                        <div>
                            Mã BĐS: <strong id="qu_bds_code" class="text-primary">-</strong>
                        </div>
                        <div class="mt-1">
                            Tiêu đề: <strong id="qu_bds_name" class="text-dark">-</strong>
                        </div>
                        <small class="d-block mt-2">
                            Lưu ở đây sẽ làm mới ngày cập nhật của BĐS để dễ theo dõi BĐS vừa chăm sóc.
                        </small>
                    </div>

                    <div class="mb-3" id="qu_gia_ban_wrap">
                        <label class="form-label fw-medium">Giá bán mới (VNĐ)</label>
                        <input type="number" min="0" step="1000000" class="form-control" name="gia_moi"
                            id="qu_gia_moi" placeholder="Ví dụ: 3500000000">
                    </div>

                    <div class="mb-3" id="qu_gia_thue_wrap">
                        <label class="form-label fw-medium">Giá thuê mới (VNĐ/tháng)</label>
                        <input type="number" min="0" step="100000" class="form-control" name="gia_thue_moi"
                            id="qu_gia_thue_moi" placeholder="Ví dụ: 15000000">
                    </div>

                    <div class="mb-0">
                        <label class="form-label fw-medium">Ghi chú chăm sóc</label>
                        <textarea id="qu_ghi_chu_moi" name="ghi_chu_moi" class="form-control" rows="4"
                            placeholder="Ví dụ: Chủ nhà vừa báo điều chỉnh giá, cần ưu tiên tư vấn khách phù hợp..."></textarea>
                        <div class="form-text text-muted">Nếu nhập ghi chú, hệ thống sẽ tự động đóng dấu thời gian và lưu
                            vào ghi chú nội bộ.</div>
                    </div>
                </div>

                <div class="modal-footer bg-light border-top-0">
                    <button type="button" class="btn btn-secondary bg-white text-dark border"
                        data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-success px-4"><i class="fas fa-save me-2"></i>Lưu cập
                        nhật</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- MODAL XEM NHANH THÔNG TIN BẤT ĐỘNG SẢN                      --}}
    {{-- ============================================================ --}}
    <div class="modal fade" id="modalViewBds" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-light border-bottom-0">
                    <h5 class="modal-title fw-bold text-navy"><span id="vb_tieu_de">Thông tin BĐS</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body pt-3">
                    <div class="row g-3">
                        <div class="col-12 col-md-4">
                            <img id="vb_image" src="" alt="Ảnh BĐS" class="img-fluid rounded border d-none"
                                style="width:100%;height:180px;object-fit:cover;">
                            <div id="vb_image_placeholder"
                                class="rounded border bg-light text-muted d-flex align-items-center justify-content-center"
                                style="height:180px;">
                                <i class="fas fa-image me-2"></i>Không có ảnh
                            </div>
                        </div>
                        <div class="col-12 col-md-8">
                            <div class="row g-2" style="font-size:.84rem;">
                                <div class="col-6"><strong>Mã BĐS:</strong> <span id="vb_ma"></span></div>
                                <div class="col-6"><strong>Trạng thái:</strong> <span id="vb_trang_thai"></span></div>
                                <div class="col-6"><strong>Nhu cầu:</strong> <span id="vb_nhu_cau"></span></div>
                                <div class="col-6"><strong>Loại hình:</strong> <span id="vb_loai_hinh"></span></div>
                                <div class="col-6"><strong>Giá:</strong> <span id="vb_gia"></span></div>
                                <div class="col-6"><strong>Diện tích:</strong> <span id="vb_dien_tich"></span></div>
                                <div class="col-6"><strong>Phòng ngủ:</strong> <span id="vb_phong_ngu"></span></div>
                                <div class="col-6"><strong>Hiển thị:</strong> <span id="vb_hien_thi"></span></div>
                                <div class="col-6"><strong>Tòa/Tầng:</strong> <span id="vb_toa_tang"></span></div>
                                <div class="col-6"><strong>Mã căn:</strong> <span id="vb_ma_can"></span></div>
                                <div class="col-6"><strong>Nội thất:</strong> <span id="vb_noi_that"></span></div>
                                <div class="col-6" id="vb_phap_ly_wrap"><strong>Pháp lý:</strong> <span
                                        id="vb_phap_ly"></span></div>
                                <div class="col-6 d-none" id="vb_tgvt_wrap"><strong>Thời gian vào thuê:</strong> <span
                                        id="vb_tgvt"></span></div>
                                <div class="col-6 d-none" id="vb_httt_wrap"><strong>Hình thức thanh toán:</strong> <span
                                        id="vb_httt"></span></div>
                                <div class="col-6"><strong>Dự án:</strong> <span id="vb_du_an"></span></div>
                                <div class="col-6"><strong>NV phụ trách:</strong> <span id="vb_nv_pt"></span></div>
                                <div class="col-6"><strong>Lượt xem:</strong> <span id="vb_luot_xem"></span></div>
                                <div class="col-6"><strong>Ngày đăng:</strong> <span id="vb_ngay_dang"></span></div>
                                <div class="col-6"><strong>Cập nhật:</strong> <span id="vb_cap_nhat"></span></div>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="text-muted d-block mb-1" style="font-size:0.75rem;">Ghi chú nội bộ</label>
                            <div id="vb_ghi_chu" class="bg-warning bg-opacity-10 border rounded p-2"
                                style="font-size:0.85rem;min-height:50px;"></div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light border-top-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const CSRF = document.querySelector('meta[name=csrf-token]').content;
            const filterForm = document.getElementById('filterForm');
            const searchInput = filterForm?.querySelector('input[name="tukhoa"]');
            const quickUpdateForm = document.getElementById('quickUpdateBdsForm');

            // ══ AJAX PAGINATION ══
            function bindPaginationLinks() {
                document.querySelectorAll('#paginationContainer a').forEach(link => {
                    link.addEventListener('click', function(e) {
                        const href = this.getAttribute('href');
                        if (href && (href.includes('?') || href.includes('&'))) {
                            e.preventDefault();
                            fetchTableData(href);
                        }
                    });
                });
            }

            function fetchTableData(url) {
                // Show loading state
                const tbody = document.querySelector('table tbody');
                if (tbody) {
                    tbody.style.opacity = '0.6';
                }

                fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'text/html'
                        }
                    }).then(response => response.text())
                    .then(html => {
                        // Parse HTML response
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');

                        // Update table tbody
                        const newTbody = doc.querySelector('table tbody');
                        const oldTbody = document.querySelector('table tbody');
                        if (newTbody && oldTbody) {
                            oldTbody.innerHTML = newTbody.innerHTML;
                        }

                        // Update pagination
                        const newPagination = doc.querySelector('#paginationContainer');
                        const oldPagination = document.querySelector('#paginationContainer');
                        if (newPagination && oldPagination) {
                            oldPagination.innerHTML = newPagination.innerHTML;
                        }

                        // Update header stats
                        const newHeader = doc.querySelector('.card-header span');
                        const oldHeader = document.querySelector('.card-header span');
                        if (newHeader && oldHeader) {
                            oldHeader.innerHTML = newHeader.innerHTML;
                        }

                        // Rebind all event listeners
                        bindPaginationLinks();
                        bindDeleteButtons();
                        bindStatusButtons();
                        bindToggleCheckboxes();
                        bindChuNhaButtons();
                        bindQuickUpdateButtons();
                        bindViewBdsButtons();

                        // Scroll to top of table
                        document.querySelector('table').scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });

                        // Restore visibility
                        if (tbody) {
                            tbody.style.opacity = '1';
                        }
                    })
                    .catch(err => {
                        console.error('Pagination error:', err);
                        if (tbody) {
                            tbody.style.opacity = '1';
                        }
                        showAdminToast('Lỗi tải dữ liệu', 'error');
                    });
            }

            // ══ AUTO-SUBMIT KHI CHỌN SELECT ══
            document.querySelectorAll('.filter-auto-submit').forEach(select => {
                select.addEventListener('change', function() {
                    if (filterForm) {
                        filterForm.submit();
                    }
                });
            });

            // ══ SUBMIT KHI ẤN ENTER TRÊN SEARCH ══
            if (searchInput) {
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        if (filterForm) {
                            filterForm.submit();
                        }
                    }
                });
            }

            // ══ DELETE HANDLER ══
            function bindDeleteButtons() {
                document.querySelectorAll('.btn-delete-bds').forEach(btn => {
                    btn.addEventListener('click', function() {
                        if (this.dataset.noPermission === '1') {
                            showAdminToast('Bạn không có quyền xóa bất động sản này', 'error');
                            return;
                        }

                        const name = this.dataset.name,
                            id = this.dataset.id;
                        confirmDelete('bất động sản #' + name, function() {
                            const form = document.getElementById('frmDel_' + id);
                            if (form) {
                                fetch(form.action, {
                                    method: 'POST',
                                    body: new FormData(form),
                                    headers: {
                                        'X-Requested-With': 'XMLHttpRequest'
                                    }
                                }).then(response => {
                                    if (response.ok || response.status === 200 ||
                                        response.status === 302 || response
                                        .status ===
                                        204) {
                                        const row = form.closest('tr');
                                        if (row) {
                                            row.style.opacity = '0';
                                            row.style.transition =
                                                'opacity 0.3s ease';
                                            setTimeout(() => row.remove(), 300);
                                        }

                                        document.querySelectorAll(
                                                `.btn-delete-bds[data-id="${id}"]`)
                                            .forEach(btn => {
                                                const card = btn.closest(
                                                    '.mobile-card');
                                                if (card && !card.contains(
                                                        form)) {
                                                    card.style.opacity = '0';
                                                    card.style.transition =
                                                        'opacity 0.3s ease';
                                                    setTimeout(() => card
                                                        .remove(),
                                                        300);
                                                }
                                            });

                                        showAdminToast('Đã xóa bất động sản',
                                            'success');
                                    } else {
                                        if (response.status === 403) {
                                            showAdminToast(
                                                'Bạn không có quyền xóa bất động sản này',
                                                'error');
                                        } else {
                                            showAdminToast('Có lỗi khi xóa',
                                                'error');
                                        }
                                    }
                                }).catch(err => {
                                    console.error('Delete error:', err);
                                    showAdminToast('Lỗi kết nối', 'error');
                                });
                            }
                        });
                    });
                });
            }

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

            // ══ STATUS UPDATE HANDLER ══
            function bindStatusButtons() {
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

                                // Đồng bộ công tắc hiển thị theo trạng thái mới.
                                document.querySelectorAll(
                                    `input[data-display-id="${bdsId}"]`).forEach(cb => {
                                    const choPhepHienThi = newStatus === 'con_hang';
                                    cb.disabled = !choPhepHienThi;
                                    if (!choPhepHienThi) {
                                        cb.checked = false;
                                    }
                                });

                                showAdminToast('Đã cập nhật trạng thái', 'success');
                            } else showAdminToast('Lỗi cập nhật', 'error');
                        }).catch(() => showAdminToast('Lỗi kết nối', 'error'));
                    });
                });
            }

            // ══ TOGGLE HIỂN THỊ HANDLER ══
            function bindToggleCheckboxes() {
                document.querySelectorAll('input[data-toggle-url]').forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        const previous = !this.checked;
                        const toggleUrl = this.dataset.toggleUrl;

                        fetch(toggleUrl, {
                            method: 'PATCH',
                            headers: {
                                'X-CSRF-TOKEN': CSRF,
                                'Accept': 'application/json'
                            }
                        }).then(async r => {
                            const data = await r.json().catch(() => ({}));

                            if (!r.ok || !data.ok) {
                                this.checked = previous;
                                if (typeof data.hien_thi === 'boolean') {
                                    this.checked = data.hien_thi;
                                }
                                showAdminToast(data.message ||
                                    'Không thể cập nhật hiển thị', 'error');
                                return;
                            }

                            this.checked = !!data.hien_thi;
                            showAdminToast('Đã cập nhật hiển thị', 'success');
                        }).catch(() => {
                            this.checked = previous;
                            showAdminToast('Lỗi kết nối', 'error');
                        });
                    });
                });
            }

            // ══ CHỦ NHÀ MODAL HANDLER ══
            function bindChuNhaButtons() {
                document.querySelectorAll('.btn-view-chunha').forEach(btn => {
                    btn.addEventListener('click', function() {
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
                        document.getElementById('md_diachi').textContent = this.dataset.diachi ?
                            this
                            .dataset.diachi : '—';
                        document.getElementById('md_ghichu').innerHTML = this.dataset.ghichu ? this
                            .dataset.ghichu.replace(/\n/g, '<br>') :
                            '<span class="text-muted fst-italic">Không có ghi chú</span>';

                        new bootstrap.Modal(document.getElementById('modalChuNha')).show();
                    });
                });
            }

            // ══ CẬP NHẬT NHANH BĐS HANDLER ══
            function bindQuickUpdateButtons() {
                document.querySelectorAll('.btn-quick-update-bds').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const id = this.dataset.id;
                        const nhuCau = this.dataset.nhuCau;

                        if (!quickUpdateForm) return;

                        quickUpdateForm.action =
                            `{{ route('nhanvien.admin.bat-dong-san.update', ['batDongSan' => '__ID__']) }}`
                            .replace('__ID__', id);

                        document.getElementById('qu_bds_code').textContent = this.dataset.code ||
                            '-';
                        document.getElementById('qu_bds_name').textContent = this.dataset.name ||
                            '-';

                        const giaBanWrap = document.getElementById('qu_gia_ban_wrap');
                        const giaThueWrap = document.getElementById('qu_gia_thue_wrap');
                        const giaMoiInput = document.getElementById('qu_gia_moi');
                        const giaThueMoiInput = document.getElementById('qu_gia_thue_moi');

                        if (giaMoiInput) {
                            giaMoiInput.value = this.dataset.gia ? Math.round(Number(this.dataset
                                .gia)) : '';
                        }
                        if (giaThueMoiInput) {
                            giaThueMoiInput.value = this.dataset.giaThue ? Math.round(Number(this
                                    .dataset.giaThue)) :
                                '';
                        }

                        if (nhuCau === 'ban') {
                            if (giaBanWrap) giaBanWrap.classList.remove('d-none');
                            if (giaThueWrap) giaThueWrap.classList.add('d-none');
                        } else {
                            if (giaBanWrap) giaBanWrap.classList.add('d-none');
                            if (giaThueWrap) giaThueWrap.classList.remove('d-none');
                        }

                        const ghiChuInput = document.getElementById('qu_ghi_chu_moi');
                        if (ghiChuInput) ghiChuInput.value = '';
                    });
                });
            }

            // ══ XEM NHANH THÔNG TIN BĐS ══
            function bindViewBdsButtons() {
                const escapeHtml = (str) => String(str)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#39;');

                document.querySelectorAll('.btn-view-bds').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const setTxt = (id, val) => {
                            const el = document.getElementById(id);
                            if (el) el.textContent = val || '—';
                        };

                        setTxt('vb_tieu_de', this.dataset.tieuDe || 'Thông tin BĐS');
                        setTxt('vb_ma', this.dataset.ma || '—');
                        setTxt('vb_trang_thai', this.dataset.trangThai || '—');
                        setTxt('vb_nhu_cau', this.dataset.nhuCau || '—');
                        setTxt('vb_loai_hinh', this.dataset.loaiHinh || '—');
                        setTxt('vb_gia', this.dataset.gia || '—');
                        setTxt('vb_dien_tich', this.dataset.dienTich || '—');
                        setTxt('vb_phong_ngu', this.dataset.phongNgu || '—');
                        setTxt('vb_hien_thi', this.dataset.hienThi || '—');
                        setTxt('vb_toa_tang',
                            `${this.dataset.toa || '—'} / ${this.dataset.tang || '—'}`);
                        setTxt('vb_ma_can', this.dataset.maCan || '—');
                        setTxt('vb_noi_that', this.dataset.noiThat || '—');
                        setTxt('vb_phap_ly', this.dataset.phapLy || '—');
                        setTxt('vb_tgvt', this.dataset.thoiGianVaoThue || '—');
                        setTxt('vb_httt', this.dataset.hinhThucThanhToan || '—');
                        setTxt('vb_du_an', this.dataset.duAn || '—');
                        setTxt('vb_nv_pt', this.dataset.nvPhuTrach || '—');
                        setTxt('vb_luot_xem', this.dataset.luotXem || '0');
                        setTxt('vb_ngay_dang', this.dataset.ngayDang || '—');
                        setTxt('vb_cap_nhat', this.dataset.capNhat || '—');

                        const laThue = (this.dataset.nhuCau || '').toLowerCase() === 'thuê';
                        const phapLyWrap = document.getElementById('vb_phap_ly_wrap');
                        const tgvtWrap = document.getElementById('vb_tgvt_wrap');
                        const htttWrap = document.getElementById('vb_httt_wrap');
                        if (phapLyWrap && tgvtWrap && htttWrap) {
                            if (laThue) {
                                phapLyWrap.classList.add('d-none');
                                tgvtWrap.classList.remove('d-none');
                                htttWrap.classList.remove('d-none');
                            } else {
                                phapLyWrap.classList.remove('d-none');
                                tgvtWrap.classList.add('d-none');
                                htttWrap.classList.add('d-none');
                            }
                        }

                        const ghiChuEl = document.getElementById('vb_ghi_chu');
                        if (ghiChuEl) {
                            const ghiChu = this.dataset.ghiChu || '';
                            ghiChuEl.innerHTML = ghiChu.trim() !== '' ?
                                escapeHtml(ghiChu).replace(/\n/g, '<br>') :
                                '<span class="text-muted fst-italic">Không có ghi chú nội bộ</span>';
                        }

                        const img = document.getElementById('vb_image');
                        const ph = document.getElementById('vb_image_placeholder');
                        if (img && ph) {
                            if (this.dataset.image) {
                                img.src = this.dataset.image;
                                img.classList.remove('d-none');
                                ph.classList.add('d-none');
                            } else {
                                img.src = '';
                                img.classList.add('d-none');
                                ph.classList.remove('d-none');
                            }
                        }
                    });
                });
            }

            // Initial bindings
            bindPaginationLinks();
            bindDeleteButtons();
            bindStatusButtons();
            bindToggleCheckboxes();
            bindChuNhaButtons();
            bindQuickUpdateButtons();
            bindViewBdsButtons();
        });
    </script>
@endpush

@push('styles')
    <style>
        .bds-filter-panel {
            border: 1px solid rgba(15, 23, 42, 0.08);
        }

        .bds-filter-row {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-end;
        }

        @media (max-width: 1199.98px) {
            .bds-filter-row>div:not(:first-child) {
                min-width: 90px !important;
                flex: 0 1 auto !important;
            }

            .bds-filter-row>.col-12:first-child {
                flex: 0 0 100%;
                margin-bottom: 0.5rem;
            }
        }

        @media (max-width: 767.98px) {
            .bds-filter-row>div {
                flex: 0 1 calc(50% - 0.5rem) !important;
                min-width: auto !important;
            }

            .bds-filter-row>.col-12 {
                flex: 0 0 100% !important;
            }
        }

        .project-tabs-title {
            font-size: 0.6rem;
            font-weight: 500;
            color: #475569;
            margin-bottom: 0.5rem;
        }

        .project-tabs-scroller {
            display: flex;
            gap: 0.25rem;
            overflow-x: auto;
            padding-bottom: 0.15rem;
        }

        .project-tab {
            flex: 0 0 auto;
            text-decoration: none;
            font-size: 0.6rem;
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
            color: #ffffff;
            background: #e77a27;
            border-color: #c7d2fe;
        }

        .project-tab.active {
            color: #fff;
            background: linear-gradient(135deg, #1d4ed8, #2563eb);
            border-color: #1d4ed8;
            box-shadow: 0 6px 16px rgba(37, 99, 235, 0.25);
        }

        @media (min-width: 992px) {
            .bds-filter-row {
                flex-wrap: wrap;
            }

            .bds-filter-row>div {
                min-width: 0;
            }

            .bds-filter-panel .filter-ctrl {
                font-size: 0.82rem;
                padding-left: 0.55rem;
                padding-right: 0.55rem;
            }

            .bds-filter-panel .filter-ctrl-search::placeholder {
                font-size: 0.8rem;
            }

            .bds-filter-panel .price-range-inline {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 0.35rem;
            }

            .bds-filter-panel .filter-actions .btn {
                white-space: nowrap;
                padding-left: 0.5rem;
                padding-right: 0.5rem;
                font-size: 0.78rem;
            }

            .bds-filter-panel .btn-label-desktop-hide {
                display: inline;
            }
        }

        @media (max-width: 991.98px) {
            .bds-filter-panel .price-range-inline {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 0.5rem;
            }
        }
    </style>
@endpush
