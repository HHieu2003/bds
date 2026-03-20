@extends('admin.layouts.master')
@section('title', 'Quản lý Bất động sản')

@section('content')

    {{-- ══ THỐNG KÊ NHANH ══ --}}
    <div class="stat-row">
        <div class="stat-card">
            <div class="stat-icon" style="background:#e8f0ff;color:#2d6a9f">
                <i class="fas fa-building"></i>
            </div>
            <div class="stat-body">
                <div class="stat-num">{{ number_format($thongKe['tong']) }}</div>
                <div class="stat-label">Tổng BĐS</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:#e8f8f0;color:#27ae60">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-body">
                <div class="stat-num">{{ number_format($thongKe['con_hang']) }}</div>
                <div class="stat-label">Còn hàng</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:#e8f4fd;color:#2d6a9f">
                <i class="fas fa-key"></i>
            </div>
            <div class="stat-body">
                <div class="stat-num">{{ number_format($thongKe['dang_thue']) }}</div>
                <div class="stat-label">Đang cho thuê</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:#fff3e0;color:#e67e22">
                <i class="fas fa-handshake"></i>
            </div>
            <div class="stat-body">
                <div class="stat-num">{{ number_format($thongKe['dat_coc']) }}</div>
                <div class="stat-label">Đặt cọc</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:#ffeaea;color:#e74c3c">
                <i class="fas fa-times-circle"></i>
            </div>
            <div class="stat-body">
                <div class="stat-num">{{ number_format($thongKe['da_ban']) }}</div>
                <div class="stat-label">Đã bán / Đã thuê</div>
            </div>
        </div>
    </div>

    {{-- ══ HEADER ══ --}}
    <div class="page-header">
        <div>
            <h1 class="page-title">
                <i class="fas fa-building"></i> Bất động sản
            </h1>
            <p class="page-sub">Quản lý toàn bộ danh sách bất động sản</p>
        </div>
        <a href="{{ route('nhanvien.admin.bat-dong-san.create') }}" class="btn-add">
            <i class="fas fa-plus"></i> Thêm BĐS mới
        </a>
    </div>

    {{-- Flash --}}
    @if (session('success'))
        <div class="flash-bar flash-success">
            <i class="fas fa-check-circle"></i> {!! session('success') !!}
        </div>
    @endif
    @if (session('error'))
        <div class="flash-bar flash-error">
            <i class="fas fa-exclamation-circle"></i> {!! session('error') !!}
        </div>
    @endif

    {{-- ══ BỘ LỌC ══ --}}
    <div class="filter-box">
        <form method="GET" id="filterForm">
            <div class="filter-grid">
                <input type="text" name="tukhoa" class="f-ctrl" value="{{ request('tukhoa') }}"
                    placeholder="🔍 Tìm tiêu đề, mã BĐS, mã căn...">

                <select name="nhu_cau" class="f-ctrl">
                    <option value="">Tất cả nhu cầu</option>
                    <option value="ban" @selected(request('nhu_cau') == 'ban')>🏷 Bán</option>
                    <option value="thue" @selected(request('nhu_cau') == 'thue')>🔑 Cho thuê</option>
                </select>

                <select name="loai_hinh" class="f-ctrl">
                    <option value="">Tất cả loại hình</option>
                    @foreach (['can_ho' => 'Căn hộ', 'nha_pho' => 'Nhà phố', 'biet_thu' => 'Biệt thự', 'dat_nen' => 'Đất nền', 'shophouse' => 'Shophouse'] as $v => $l)
                        <option value="{{ $v }}" @selected(request('loai_hinh') == $v)>{{ $l }}</option>
                    @endforeach
                </select>

                <select name="trang_thai" class="f-ctrl">
                    <option value="">Tất cả trạng thái</option>
                    @foreach (['con_hang' => '✅ Còn hàng', 'dat_coc' => '🤝 Đặt cọc', 'da_ban' => '❌ Đã bán', 'dang_thue' => '🔑 Đang thuê', 'da_thue' => '📦 Đã thuê', 'tam_an' => '⏸ Tạm ẩn'] as $v => $l)
                        <option value="{{ $v }}" @selected(request('trang_thai') == $v)>{{ $l }}</option>
                    @endforeach
                </select>

                <select name="du_an_id" class="f-ctrl">
                    <option value="">Tất cả dự án</option>
                    @foreach ($duAns as $da)
                        <option value="{{ $da->id }}" @selected(request('du_an_id') == $da->id)>
                            {{ Str::limit($da->ten_du_an, 30) }}
                        </option>
                    @endforeach
                </select>

                <select name="hien_thi" class="f-ctrl">
                    <option value="">Tất cả</option>
                    <option value="1" @selected(request('hien_thi') === '1')>👁 Đang hiển thị</option>
                    <option value="0" @selected(request('hien_thi') === '0')>🚫 Đang ẩn</option>
                </select>

                <select name="sapxep" class="f-ctrl">
                    <option value="moi_nhat" @selected(request('sapxep', 'moi_nhat') == 'moi_nhat')>📅 Mới nhất</option>
                    <option value="gia_tang" @selected(request('sapxep') == 'gia_tang')>💰 Giá tăng dần</option>
                    <option value="gia_giam" @selected(request('sapxep') == 'gia_giam')>💰 Giá giảm dần</option>
                    <option value="luot_xem" @selected(request('sapxep') == 'luot_xem')>👁 Lượt xem nhiều</option>
                </select>

                <div class="filter-btns">
                    <button type="submit" class="btn-filter">
                        <i class="fas fa-search"></i> Lọc
                    </button>
                    @if (request()->hasAny(['tukhoa', 'nhu_cau', 'loai_hinh', 'trang_thai', 'du_an_id', 'hien_thi', 'sapxep']))
                        <a href="{{ route('nhanvien.admin.bat-dong-san.index') }}" class="btn-reset-filter">
                            <i class="fas fa-times"></i> Xóa lọc
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    {{-- ══ BẢNG DỮ LIỆU ══ --}}
    <div class="data-box">
        {{-- Header bảng --}}
        <div class="data-box-header">
            <div class="result-info">
                @if ($batDongSans->total() > 0)
                    Hiển thị
                    <strong>{{ $batDongSans->firstItem() }}–{{ $batDongSans->lastItem() }}</strong>
                    trong tổng số <strong>{{ number_format($batDongSans->total()) }}</strong> bất động sản
                @else
                    Không có kết quả nào
                @endif
            </div>
        </div>

        {{-- Table --}}
        <div class="tbl-wrap">
            <table class="tbl">
                <thead>
                    <tr>
                        <th class="th-stt">#</th>
                        <th>Bất động sản</th>
                        <th class="th-loai">Loại / Nhu cầu</th>
                        <th class="th-gia">Giá</th>
                        <th class="th-dt">Diện tích</th>
                        <th class="th-da">Dự án</th>
                        <th class="th-tt">Trạng thái</th>
                        <th class="th-ht">Hiển thị</th>
                        <th class="th-act">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($batDongSans as $bds)
                        @php
                            $ttMap = [
                                'con_hang' => ['label' => 'Còn hàng', 'color' => '#27ae60', 'bg' => '#e8f8f0'],
                                'dat_coc' => ['label' => 'Đặt cọc', 'color' => '#e67e22', 'bg' => '#fff3e0'],
                                'da_ban' => ['label' => 'Đã bán', 'color' => '#e74c3c', 'bg' => '#ffeaea'],
                                'dang_thue' => ['label' => 'Đang cho thuê', 'color' => '#2d6a9f', 'bg' => '#e8f4fd'],
                                'da_thue' => ['label' => 'Đã cho thuê', 'color' => '#8e44ad', 'bg' => '#f5eeff'],
                                'tam_an' => ['label' => 'Tạm ẩn', 'color' => '#95a5a6', 'bg' => '#f5f5f5'],
                            ];
                            $tt = $ttMap[$bds->trang_thai] ?? [
                                'label' => $bds->trang_thai,
                                'color' => '#999',
                                'bg' => '#f5f5f5',
                            ];

                            $loaiMap = [
                                'can_ho' => 'Căn hộ',
                                'nha_pho' => 'Nhà phố',
                                'biet_thu' => 'Biệt thự',
                                'dat_nen' => 'Đất nền',
                                'shophouse' => 'Shophouse',
                            ];
                        @endphp
                        <tr>
                            {{-- STT --}}
                            <td class="td-stt">{{ $batDongSans->firstItem() + $loop->index }}</td>

                            {{-- Tên + ảnh --}}
                            <td class="td-main">
                                <div class="bds-row">
                                    <a href="{{ route('nhanvien.admin.bat-dong-san.edit', $bds) }}" class="bds-thumb">
                                        @if ($bds->hinh_anh)
                                            <img src="{{ asset('storage/' . $bds->hinh_anh) }}" alt="">
                                        @else
                                            <div class="no-img"><i class="fas fa-image"></i></div>
                                        @endif
                                        @if ($bds->noi_bat)
                                            <span class="hot-badge">HOT</span>
                                        @endif
                                    </a>
                                    <div class="bds-info">
                                        <a href="{{ route('nhanvien.admin.bat-dong-san.edit', $bds) }}"
                                            class="bds-name">{{ Str::limit($bds->tieu_de, 60) }}</a>

                                        <div class="bds-chips">
                                            <span class="chip chip-code">
                                                <i class="fas fa-hashtag"></i>{{ $bds->ma_bat_dong_san }}
                                            </span>
                                            @if ($bds->toa || $bds->tang || $bds->ma_can)
                                                <span class="chip chip-loc">
                                                    <i class="fas fa-map-pin"></i>
                                                    {{ implode('/', array_filter([$bds->toa ? 'Tòa ' . $bds->toa : null, $bds->tang ? 'T' . $bds->tang : null, $bds->ma_can])) }}
                                                </span>
                                            @endif
                                            @if ($bds->lich_hens_count > 0)
                                                <span class="chip chip-lich">
                                                    <i class="fas fa-calendar-check"></i>{{ $bds->lich_hens_count }} lịch
                                                    hẹn
                                                </span>
                                            @endif
                                        </div>

                                        @if ($bds->nhanVienPhuTrach)
                                            <div class="bds-nv">
                                                <i class="fas fa-user-tie"></i>
                                                {{ $bds->nhanVienPhuTrach->ho_ten }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            {{-- Loại / Nhu cầu --}}
                            <td class="td-loai">
                                <span class="tag-loai">{{ $loaiMap[$bds->loai_hinh] ?? $bds->loai_hinh }}</span>
                                <span class="tag-nhucau {{ $bds->nhu_cau == 'ban' ? 'tag-ban' : 'tag-thue' }}">
                                    {{ $bds->nhu_cau == 'ban' ? '🏷 Bán' : '🔑 Thuê' }}
                                </span>
                            </td>

                            {{-- Giá --}}
                            <td class="td-gia">
                                @if ($bds->nhu_cau == 'ban' && $bds->gia)
                                    <div class="price-main price-ban">
                                        {{ number_format($bds->gia / 1e9, 2) }} tỷ
                                    </div>
                                    @if ($bds->phi_moi_gioi)
                                        <div class="price-sub">MG: {{ number_format($bds->phi_moi_gioi / 1e6, 0) }}tr</div>
                                    @endif
                                @elseif($bds->nhu_cau == 'thue' && $bds->gia_thue)
                                    <div class="price-main price-thue">
                                        {{ number_format($bds->gia_thue / 1e6, 1) }}tr/th
                                    </div>
                                @else
                                    <span class="price-tl">Thương lượng</span>
                                @endif
                            </td>

                            {{-- Diện tích --}}
                            <td class="td-dt">
                                <div class="dt-main">{{ $bds->dien_tich }} m²</div>
                                @if ($bds->so_phong_ngu !== null)
                                    <div class="dt-sub">
                                        <i class="fas fa-bed"></i>
                                        {{ $bds->so_phong_ngu == 0 ? 'Studio' : $bds->so_phong_ngu . ' PN' }}
                                    </div>
                                @endif
                                @if ($bds->huong_cua)
                                    <div class="dt-sub">
                                        <i class="fas fa-compass"></i> {{ $bds->huong_cua }}
                                    </div>
                                @endif
                            </td>

                            {{-- Dự án --}}
                            <td class="td-da">
                                @if ($bds->duAn)
                                    <div class="da-name">{{ Str::limit($bds->duAn->ten_du_an, 22) }}</div>
                                    @if (isset($bds->duAn->khuVuc))
                                        <div class="da-kv">
                                            <i class="fas fa-map-marker-alt"></i>
                                            {{ $bds->duAn->khuVuc->ten_khu_vuc }}
                                        </div>
                                    @endif
                                @else
                                    <span class="td-empty">—</span>
                                @endif
                            </td>

                            {{-- Trạng thái --}}
                            <td class="td-tt">
                                <span class="badge-tt" style="color:{{ $tt['color'] }};background:{{ $tt['bg'] }}"
                                    data-id="{{ $bds->id }}" onclick="openTTPopup(this)">
                                    {{ $tt['label'] }}
                                    <i class="fas fa-caret-down" style="font-size:.6rem;margin-left:3px"></i>
                                </span>
                            </td>

                            {{-- Toggle hiển thị + lượt xem --}}
                            <td class="td-ht">
                                <label class="sw">
                                    <input type="checkbox" class="toggle-ht" data-id="{{ $bds->id }}"
                                        {{ $bds->hien_thi ? 'checked' : '' }}>
                                    <span class="sw-track">
                                        <span class="sw-thumb"></span>
                                    </span>
                                </label>
                                <div class="view-count">
                                    <i class="fas fa-eye"></i> {{ number_format($bds->luot_xem) }}
                                </div>
                            </td>

                            {{-- Thao tác --}}
                            <td class="td-act">
                                <div class="act-group">
                                    <a href="{{ route('nhanvien.admin.bat-dong-san.edit', $bds) }}"
                                        class="act-btn btn-edit" title="Chỉnh sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('nhanvien.admin.bat-dong-san.destroy', $bds) }}"
                                        method="POST" class="del-form">
                                        @csrf @method('DELETE')
                                        <button type="button" class="act-btn btn-del js-confirm-del"
                                            data-ten="{{ $bds->tieu_de }}" title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="td-empty-row">
                                <div class="empty-state">
                                    <i class="fas fa-building"></i>
                                    <p>Không tìm thấy bất động sản nào</p>
                                    @if (request()->hasAny(['tukhoa', 'nhu_cau', 'loai_hinh', 'trang_thai', 'du_an_id']))
                                        <a href="{{ route('nhanvien.admin.bat-dong-san.index') }}">
                                            Xóa bộ lọc để xem tất cả
                                        </a>
                                    @else
                                        <a href="{{ route('nhanvien.admin.bat-dong-san.create') }}">
                                            + Thêm bất động sản đầu tiên
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ══ PHÂN TRANG ══ --}}
        @if ($batDongSans->hasPages())
            <div class="pagi-wrap">
                <div class="pagi-info">
                    Trang {{ $batDongSans->currentPage() }} / {{ $batDongSans->lastPage() }}
                </div>
                <div class="pagi-links">
                    {{-- Nút Đầu --}}
                    @if ($batDongSans->onFirstPage())
                        <span class="pagi-btn pagi-disabled">
                            <i class="fas fa-angle-double-left"></i>
                        </span>
                    @else
                        <a href="{{ $batDongSans->url(1) }}" class="pagi-btn">
                            <i class="fas fa-angle-double-left"></i>
                        </a>
                    @endif

                    {{-- Nút Trước --}}
                    @if ($batDongSans->onFirstPage())
                        <span class="pagi-btn pagi-disabled">
                            <i class="fas fa-angle-left"></i>
                        </span>
                    @else
                        <a href="{{ $batDongSans->previousPageUrl() }}" class="pagi-btn">
                            <i class="fas fa-angle-left"></i>
                        </a>
                    @endif

                    {{-- Số trang --}}
                    @php
                        $current = $batDongSans->currentPage();
                        $last = $batDongSans->lastPage();
                        $start = max(1, $current - 2);
                        $end = min($last, $current + 2);
                    @endphp

                    @if ($start > 1)
                        <a href="{{ $batDongSans->url(1) }}" class="pagi-btn">1</a>
                        @if ($start > 2)
                            <span class="pagi-dots">…</span>
                        @endif
                    @endif

                    @for ($p = $start; $p <= $end; $p++)
                        @if ($p == $current)
                            <span class="pagi-btn pagi-active">{{ $p }}</span>
                        @else
                            <a href="{{ $batDongSans->url($p) }}" class="pagi-btn">{{ $p }}</a>
                        @endif
                    @endfor

                    @if ($end < $last)
                        @if ($end < $last - 1)
                            <span class="pagi-dots">…</span>
                        @endif
                        <a href="{{ $batDongSans->url($last) }}" class="pagi-btn">{{ $last }}</a>
                    @endif

                    {{-- Nút Sau --}}
                    @if ($batDongSans->hasMorePages())
                        <a href="{{ $batDongSans->nextPageUrl() }}" class="pagi-btn">
                            <i class="fas fa-angle-right"></i>
                        </a>
                    @else
                        <span class="pagi-btn pagi-disabled">
                            <i class="fas fa-angle-right"></i>
                        </span>
                    @endif

                    {{-- Nút Cuối --}}
                    @if ($batDongSans->hasMorePages())
                        <a href="{{ $batDongSans->url($last) }}" class="pagi-btn">
                            <i class="fas fa-angle-double-right"></i>
                        </a>
                    @else
                        <span class="pagi-btn pagi-disabled">
                            <i class="fas fa-angle-double-right"></i>
                        </span>
                    @endif
                </div>
            </div>
        @endif

    </div>{{-- end data-box --}}

    {{-- ══ POPUP ĐỔI TRẠNG THÁI ══ --}}
    <div id="ttPopup" class="tt-popup" style="display:none">
        @foreach ([
            'con_hang' => ['ico' => '✅', 'txt' => 'Còn hàng'],
            'dat_coc' => ['ico' => '🤝', 'txt' => 'Đặt cọc'],
            'da_ban' => ['ico' => '❌', 'txt' => 'Đã bán'],
            'dang_thue' => ['ico' => '🔑', 'txt' => 'Đang cho thuê'],
            'da_thue' => ['ico' => '📦', 'txt' => 'Đã cho thuê'],
            'tam_an' => ['ico' => '⏸', 'txt' => 'Tạm ẩn'],
        ] as $val => $item)
            <div class="tt-item" data-val="{{ $val }}">
                {{ $item['ico'] }} {{ $item['txt'] }}
            </div>
        @endforeach
    </div>

@endsection

@push('styles')
    <style>
        /* ══════════════════════════════════════════
       STAT ROW
    ══════════════════════════════════════════ */
        .stat-row {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 14px;
            margin-bottom: 22px;
        }

        @media (max-width: 1024px) {
            .stat-row {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 640px) {
            .stat-row {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .stat-card {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #f0f2f5;
            padding: 14px 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .04);
            transition: box-shadow .2s;
        }

        .stat-card:hover {
            box-shadow: 0 4px 16px rgba(0, 0, 0, .08);
        }

        .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .stat-num {
            font-size: 1.55rem;
            font-weight: 800;
            color: #1a3c5e;
            line-height: 1;
        }

        .stat-label {
            font-size: .74rem;
            color: #aaa;
            margin-top: 4px;
        }

        /* ══════════════════════════════════════════
       PAGE HEADER
    ══════════════════════════════════════════ */
        .page-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 18px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .page-title {
            font-size: 1.35rem;
            font-weight: 700;
            color: #1a3c5e;
            margin: 0 0 4px;
            display: flex;
            align-items: center;
            gap: 9px;
        }

        .page-title i {
            color: #FF8C42;
        }

        .page-sub {
            color: #aaa;
            font-size: .83rem;
            margin: 0;
        }

        .btn-add {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: linear-gradient(135deg, #FF8C42, #f5a623);
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 700;
            font-size: .875rem;
            text-decoration: none;
            box-shadow: 0 4px 14px rgba(255, 140, 66, .3);
            transition: all .2s;
            white-space: nowrap;
        }

        .btn-add:hover {
            transform: translateY(-1px);
            color: #fff;
            box-shadow: 0 6px 20px rgba(255, 140, 66, .4);
        }

        /* ══════════════════════════════════════════
       FLASH
    ══════════════════════════════════════════ */
        .flash-bar {
            border-radius: 10px;
            padding: 12px 18px;
            font-size: .875rem;
            font-weight: 500;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .flash-success {
            background: #e8f8f0;
            border: 1px solid #b7e4cb;
            color: #1a7a45;
        }

        .flash-error {
            background: #fff5f5;
            border: 1px solid #fcc;
            color: #c0392b;
        }

        /* ══════════════════════════════════════════
       BỘ LỌC
    ══════════════════════════════════════════ */
        .filter-box {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #f0f2f5;
            padding: 16px 18px;
            margin-bottom: 16px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .04);
        }

        .filter-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            align-items: center;
        }

        .f-ctrl {
            height: 38px;
            border: 1.5px solid #e8e8e8;
            border-radius: 8px;
            padding: 0 12px;
            font-size: .83rem;
            color: #333;
            background: #fafafa;
            outline: none;
            cursor: pointer;
            transition: border-color .2s;
            font-family: inherit;
        }

        .f-ctrl:focus {
            border-color: #FF8C42;
            background: #fff;
        }

        .f-ctrl[type=text] {
            flex: 1;
            min-width: 200px;
        }

        select.f-ctrl {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath fill='%23aaa' d='M5 6L0 0h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-color: #fafafa;
            padding-right: 28px;
        }

        .filter-btns {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .btn-filter {
            height: 38px;
            background: #1a3c5e;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 0 18px;
            font-weight: 700;
            font-size: .83rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
            transition: background .2s;
        }

        .btn-filter:hover {
            background: #0f2742;
        }

        .btn-reset-filter {
            height: 38px;
            background: #fff5f5;
            color: #e74c3c;
            border: 1.5px solid #fcc;
            border-radius: 8px;
            padding: 0 14px;
            font-size: .83rem;
            font-weight: 600;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 5px;
            white-space: nowrap;
        }

        .btn-reset-filter:hover {
            background: #ffeaea;
        }

        /* ══════════════════════════════════════════
       DATA BOX
    ══════════════════════════════════════════ */
        .data-box {
            background: #fff;
            border-radius: 14px;
            border: 1px solid #f0f2f5;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .06);
            overflow: hidden;
        }

        .data-box-header {
            padding: 14px 20px;
            border-bottom: 1px solid #f5f5f5;
        }

        .result-info {
            font-size: .82rem;
            color: #999;
        }

        .result-info strong {
            color: #1a3c5e;
        }

        /* ══════════════════════════════════════════
       TABLE
    ══════════════════════════════════════════ */
        .tbl-wrap {
            overflow-x: auto;
        }

        .tbl {
            width: 100%;
            border-collapse: collapse;
            min-width: 900px;
        }

        .tbl thead tr {
            background: #f8faff;
        }

        .tbl th {
            padding: 11px 14px;
            font-size: .72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: #6b7a99;
            border-bottom: 1.5px solid #eef0f5;
            text-align: left;
            white-space: nowrap;
        }

        .tbl td {
            padding: 13px 14px;
            border-bottom: 1px solid #f5f6fa;
            vertical-align: middle;
            font-size: .855rem;
            color: #333;
        }

        .tbl tbody tr:last-child td {
            border-bottom: none;
        }

        .tbl tbody tr:hover {
            background: #fdfeff;
        }

        /* Column widths */
        .th-stt {
            width: 46px;
            text-align: center;
        }

        .th-loai {
            width: 110px;
        }

        .th-gia {
            width: 120px;
        }

        .th-dt {
            width: 110px;
        }

        .th-da {
            width: 130px;
        }

        .th-tt {
            width: 130px;
        }

        .th-ht {
            width: 80px;
            text-align: center;
        }

        .th-act {
            width: 88px;
            text-align: center;
        }

        .td-stt {
            text-align: center;
            color: #ccc;
            font-size: .8rem;
        }

        .td-ht {
            text-align: center;
        }

        .td-act {
            text-align: center;
        }

        .td-empty {
            color: #ccc;
            font-size: .82rem;
        }

        /* ══════════════════════════════════════════
       BDS ROW (ảnh + tên + chip)
    ══════════════════════════════════════════ */
        .bds-row {
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .bds-thumb {
            width: 72px;
            height: 52px;
            flex-shrink: 0;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #eee;
            display: block;
            position: relative;
            text-decoration: none;
            background: #f5f7ff;
        }

        .bds-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .no-img {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #d0dff0;
            font-size: 1.3rem;
        }

        .hot-badge {
            position: absolute;
            top: 3px;
            left: 3px;
            background: #e74c3c;
            color: #fff;
            font-size: .55rem;
            font-weight: 800;
            padding: 1px 5px;
            border-radius: 3px;
            letter-spacing: .4px;
        }

        .bds-info {
            flex: 1;
            min-width: 0;
        }

        .bds-name {
            display: block;
            font-weight: 600;
            color: #1a3c5e;
            font-size: .87rem;
            line-height: 1.35;
            text-decoration: none;
            margin-bottom: 5px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .bds-name:hover {
            color: #FF8C42;
        }

        .bds-chips {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
            margin-bottom: 4px;
        }

        .chip {
            font-size: .69rem;
            font-weight: 600;
            padding: .12rem .42rem;
            border-radius: 5px;
            display: inline-flex;
            align-items: center;
            gap: 3px;
            white-space: nowrap;
        }

        .chip-code {
            background: #f0f4ff;
            color: #5580aa;
        }

        .chip-loc {
            background: #f5fff8;
            color: #27ae60;
        }

        .chip-lich {
            background: #fff3e0;
            color: #e67e22;
        }

        .bds-nv {
            font-size: .75rem;
            color: #bbb;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* ══════════════════════════════════════════
       TAGS LOẠI / NHU CẦU
    ══════════════════════════════════════════ */
        .tag-loai,
        .tag-nhucau {
            display: block;
            font-size: .71rem;
            font-weight: 700;
            padding: .22rem .6rem;
            border-radius: 6px;
            text-align: center;
            white-space: nowrap;
        }

        .tag-loai {
            background: #f0f4ff;
            color: #2d6a9f;
            margin-bottom: 4px;
        }

        .tag-ban {
            background: #fff3e0;
            color: #e67e22;
        }

        .tag-thue {
            background: #e8f4fd;
            color: #2d6a9f;
        }

        /* ══════════════════════════════════════════
       GIÁ
    ══════════════════════════════════════════ */
        .price-main {
            font-weight: 700;
            font-size: .92rem;
        }

        .price-ban {
            color: #e74c3c;
        }

        .price-thue {
            color: #2d6a9f;
        }

        .price-sub {
            font-size: .72rem;
            color: #bbb;
            margin-top: 2px;
        }

        .price-tl {
            font-size: .78rem;
            color: #bbb;
            font-style: italic;
        }

        /* ══════════════════════════════════════════
       DIỆN TÍCH
    ══════════════════════════════════════════ */
        .dt-main {
            font-weight: 600;
            color: #333;
            font-size: .88rem;
        }

        .dt-sub {
            font-size: .72rem;
            color: #aaa;
            display: flex;
            align-items: center;
            gap: 3px;
            margin-top: 3px;
        }

        /* ══════════════════════════════════════════
       DỰ ÁN
    ══════════════════════════════════════════ */
        .da-name {
            font-weight: 600;
            color: #1a3c5e;
            font-size: .82rem;
            margin-bottom: 3px;
        }

        .da-kv {
            font-size: .72rem;
            color: #aaa;
            display: flex;
            align-items: center;
            gap: 3px;
        }

        /* ══════════════════════════════════════════
       TRẠNG THÁI
    ══════════════════════════════════════════ */
        .badge-tt {
            display: inline-flex;
            align-items: center;
            font-size: .73rem;
            font-weight: 700;
            padding: .28rem .7rem;
            border-radius: 20px;
            cursor: pointer;
            user-select: none;
            white-space: nowrap;
            transition: opacity .15s, transform .1s;
        }

        .badge-tt:hover {
            opacity: .82;
            transform: scale(.97);
        }

        .tt-popup {
            position: fixed;
            background: #fff;
            border: 1px solid #eef0f5;
            border-radius: 12px;
            box-shadow: 0 12px 40px rgba(0, 0, 0, .14);
            z-index: 9999;
            min-width: 185px;
            padding: 5px;
            animation: popIn .15s ease;
        }

        @keyframes popIn {
            from {
                opacity: 0;
                transform: translateY(-6px) scale(.97);
            }

            to {
                opacity: 1;
                transform: none;
            }
        }

        .tt-item {
            padding: .55rem 1rem;
            font-size: .84rem;
            border-radius: 8px;
            cursor: pointer;
            transition: background .12s;
            color: #333;
        }

        .tt-item:hover {
            background: #f0f6ff;
            color: #1a3c5e;
            font-weight: 600;
        }

        /* ══════════════════════════════════════════
       TOGGLE SWITCH
    ══════════════════════════════════════════ */
        .sw {
            position: relative;
            display: inline-block;
            cursor: pointer;
        }

        .sw input {
            opacity: 0;
            width: 0;
            height: 0;
            position: absolute;
        }

        .sw-track {
            display: block;
            width: 42px;
            height: 24px;
            background: #dde0e8;
            border-radius: 12px;
            transition: background .25s;
            position: relative;
        }

        .sw input:checked~.sw-track {
            background: #27ae60;
        }

        .sw-thumb {
            position: absolute;
            width: 18px;
            height: 18px;
            background: #fff;
            border-radius: 50%;
            top: 3px;
            left: 3px;
            transition: transform .25s;
            box-shadow: 0 1px 5px rgba(0, 0, 0, .2);
        }

        .sw input:checked~.sw-track .sw-thumb {
            transform: translateX(18px);
        }

        .view-count {
            font-size: .69rem;
            color: #ccc;
            text-align: center;
            margin-top: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 3px;
        }

        /* ══════════════════════════════════════════
       ACTION BUTTONS
    ══════════════════════════════════════════ */
        .act-group {
            display: flex;
            gap: 6px;
            justify-content: center;
            align-items: center;
        }

        .act-btn {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: .8rem;
            text-decoration: none;
            transition: all .15s;
        }

        .btn-edit {
            background: #eef3ff;
            color: #2d6a9f;
        }

        .btn-edit:hover {
            background: #2d6a9f;
            color: #fff;
        }

        .btn-del {
            background: #fff0f0;
            color: #e74c3c;
        }

        .btn-del:hover {
            background: #e74c3c;
            color: #fff;
        }

        .del-form {
            margin: 0;
            display: inline;
        }

        /* ══════════════════════════════════════════
       EMPTY STATE
    ══════════════════════════════════════════ */
        .td-empty-row {
            text-align: center;
            padding: 60px 20px !important;
        }

        .empty-state i {
            font-size: 3rem;
            color: #e8ebf5;
            display: block;
            margin-bottom: 14px;
        }

        .empty-state p {
            color: #bbb;
            font-size: .95rem;
            margin: 0 0 12px;
        }

        .empty-state a {
            color: #FF8C42;
            font-weight: 600;
            font-size: .875rem;
            text-decoration: none;
        }

        .empty-state a:hover {
            text-decoration: underline;
        }

        /* ══════════════════════════════════════════
       PHÂN TRANG (tự viết, không dùng Laravel default)
    ══════════════════════════════════════════ */
        .pagi-wrap {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 20px;
            border-top: 1px solid #f5f6fa;
            flex-wrap: wrap;
            gap: 10px;
        }

        .pagi-info {
            font-size: .8rem;
            color: #aaa;
        }

        .pagi-links {
            display: flex;
            align-items: center;
            gap: 4px;
            flex-wrap: wrap;
        }

        .pagi-btn {
            min-width: 34px;
            height: 34px;
            padding: 0 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            font-size: .83rem;
            font-weight: 600;
            color: #4a6a8a;
            background: #f5f7ff;
            text-decoration: none;
            border: 1.5px solid transparent;
            transition: all .15s;
            cursor: pointer;
            line-height: 1;
        }

        .pagi-btn:hover:not(.pagi-active):not(.pagi-disabled) {
            background: #e8f0ff;
            color: #1a3c5e;
            border-color: #c8daf5;
        }

        .pagi-active {
            background: linear-gradient(135deg, #1a3c5e, #2d6a9f) !important;
            color: #fff !important;
            border-color: transparent !important;
            box-shadow: 0 3px 10px rgba(26, 60, 94, .25);
            cursor: default;
        }

        .pagi-disabled {
            color: #d0d8e8 !important;
            background: #fafafa !important;
            cursor: not-allowed;
            pointer-events: none;
        }

        .pagi-dots {
            min-width: 28px;
            height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #aaa;
            font-size: .85rem;
            font-weight: 700;
        }
    </style>
@endpush

@push('scripts')
    <script>
        const CSRF = document.querySelector('meta[name=csrf-token]').content;

        // ── 1. Toggle hiển thị ──
        document.querySelectorAll('.toggle-ht').forEach(chk => {
            chk.addEventListener('change', function() {
                const id = this.dataset.id;
                const self = this;
                fetch(`/nhan-vien/admin/bat-dong-san/${id}/toggle`, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': CSRF,
                            'Accept': 'application/json'
                        }
                    })
                    .then(r => r.json())
                    .then(data => {
                        // Nếu thất bại thì revert
                        if (data.ok === undefined) self.checked = !self.checked;
                    })
                    .catch(() => {
                        self.checked = !self.checked;
                    });
            });
        });

        // ── 2. Popup đổi trạng thái ──
        const ttPopup = document.getElementById('ttPopup');
        let ttTargetId = null;
        let ttTargetBadge = null;

        function openTTPopup(badge) {
            ttTargetId = badge.dataset.id;
            ttTargetBadge = badge;

            const rect = badge.getBoundingClientRect();
            const popW = 185;
            let left = rect.left + window.scrollX;
            let top = rect.bottom + window.scrollY + 6;

            // Tránh tràn ra khỏi màn hình
            if (left + popW > window.innerWidth - 10) {
                left = window.innerWidth - popW - 10;
            }

            ttPopup.style.left = left + 'px';
            ttPopup.style.top = top + 'px';
            ttPopup.style.display = 'block';
        }

        ttPopup.querySelectorAll('.tt-item').forEach(item => {
            item.addEventListener('click', function() {
                const val = this.dataset.val;
                ttPopup.style.display = 'none';

                fetch(`/nhan-vien/admin/bat-dong-san/${ttTargetId}/trang-thai`, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': CSRF,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            trang_thai: val
                        })
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.ok) {
                            // Reload để cập nhật badge màu chính xác
                            window.location.reload();
                        }
                    });
            });
        });

        document.addEventListener('click', e => {
            if (ttPopup.style.display === 'block' &&
                !ttPopup.contains(e.target) &&
                !e.target.classList.contains('badge-tt') &&
                !e.target.closest('.badge-tt')) {
                ttPopup.style.display = 'none';
            }
        });

        // ── 3. Confirm xóa ──
        document.querySelectorAll('.js-confirm-del').forEach(btn => {
            btn.addEventListener('click', function() {
                const ten = this.dataset.ten;
                if (confirm(`⚠️ Xóa bất động sản:\n"${ten}"?\n\nThao tác không thể hoàn tác!`)) {
                    this.closest('.del-form').submit();
                }
            });
        });

        // ── 4. Submit form lọc khi đổi select ──
        document.querySelectorAll('#filterForm select').forEach(sel => {
            sel.addEventListener('change', () => {
                document.getElementById('filterForm').submit();
            });
        });
    </script>
@endpush
