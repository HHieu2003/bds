@extends('admin.layouts.master')
@section('title', 'Quản lý Khu vực')

@section('content')

    {{-- ══ THỐNG KÊ ══ --}}
    <div class="kv-stat-row">
        <div class="kv-stat-card">
            <div class="kv-stat-icon" style="background:#e8f0ff;color:#2d6a9f">
                <i class="fas fa-map"></i>
            </div>
            <div>
                <div class="kv-stat-num">{{ number_format($thongKe['tong']) }}</div>
                <div class="kv-stat-lbl">Tổng khu vực</div>
            </div>
        </div>
        <div class="kv-stat-card">
            <div class="kv-stat-icon" style="background:#fff0f0;color:#e74c3c">
                <i class="fas fa-city"></i>
            </div>
            <div>
                <div class="kv-stat-num">{{ number_format($thongKe['tinh_thanh']) }}</div>
                <div class="kv-stat-lbl">Tỉnh / Thành phố</div>
            </div>
        </div>
        <div class="kv-stat-card">
            <div class="kv-stat-icon" style="background:#e8f4fd;color:#2d6a9f">
                <i class="fas fa-map-marked-alt"></i>
            </div>
            <div>
                <div class="kv-stat-num">{{ number_format($thongKe['quan_huyen']) }}</div>
                <div class="kv-stat-lbl">Quận / Huyện</div>
            </div>
        </div>
        <div class="kv-stat-card">
            <div class="kv-stat-icon" style="background:#e8f8f0;color:#27ae60">
                <i class="fas fa-map-pin"></i>
            </div>
            <div>
                <div class="kv-stat-num">{{ number_format($thongKe['phuong_xa']) }}</div>
                <div class="kv-stat-lbl">Phường / Xã</div>
            </div>
        </div>
        <div class="kv-stat-card">
            <div class="kv-stat-icon" style="background:#fff8f0;color:#e67e22">
                <i class="fas fa-eye"></i>
            </div>
            <div>
                <div class="kv-stat-num">{{ number_format($thongKe['hien_thi']) }}</div>
                <div class="kv-stat-lbl">Đang hiển thị</div>
            </div>
        </div>
    </div>

    {{-- ══ HEADER ══ --}}
    <div class="kv-header">
        <div>
            <h1 class="kv-title"><i class="fas fa-map-marked-alt"></i> Khu vực</h1>
            <p class="kv-sub">Quản lý danh mục địa lý Tỉnh → Quận → Phường theo cấp bậc</p>
        </div>
        <a href="{{ route('nhanvien.admin.khu-vuc.create') }}" class="kv-btn-add">
            <i class="fas fa-plus"></i> Thêm khu vực
        </a>
    </div>

    {{-- Flash --}}
    @if (session('success'))
        <div class="kv-flash kv-flash-ok"><i class="fas fa-check-circle"></i> {!! session('success') !!}</div>
    @endif
    @if (session('error'))
        <div class="kv-flash kv-flash-err"><i class="fas fa-exclamation-circle"></i> {!! session('error') !!}</div>
    @endif

    {{-- ══ BỘ LỌC ══ --}}
    <div class="kv-filter-box">
        <form method="GET" id="kvFilterForm">
            <div class="kv-filter-row">

                <input type="text" name="tim_kiem" class="kv-ctrl kv-search" value="{{ request('tim_kiem') }}"
                    placeholder="🔍 Tìm tên, mã hành chính, slug...">

                <select name="cap" class="kv-ctrl">
                    <option value="">Tất cả cấp</option>
                    @foreach (\App\Models\KhuVuc::CAP as $v => $info)
                        <option value="{{ $v }}" @selected(request('cap') == $v)>
                            {{ $info['label'] }}
                        </option>
                    @endforeach
                </select>

                <select name="cha_id" class="kv-ctrl">
                    <option value="">Tất cả tỉnh/thành</option>
                    @foreach ($tinhThanhs as $tinh)
                        <option value="{{ $tinh->id }}" @selected(request('cha_id') == $tinh->id)>
                            {{ $tinh->ten_khu_vuc }}
                        </option>
                    @endforeach
                </select>

                <select name="hien_thi" class="kv-ctrl">
                    <option value="">Tất cả trạng thái</option>
                    <option value="1" @selected(request('hien_thi') === '1')>✅ Đang hiển thị</option>
                    <option value="0" @selected(request('hien_thi') === '0')>🚫 Ẩn</option>
                </select>

                <select name="sapxep" class="kv-ctrl">
                    <option value="thu_tu" @selected(request('sapxep', 'thu_tu') == 'thu_tu')>🔢 Thứ tự</option>
                    <option value="cap" @selected(request('sapxep') == 'cap')>🗂 Theo cấp</option>
                    <option value="ten_az" @selected(request('sapxep') == 'ten_az')>🔤 Tên A→Z</option>
                    <option value="ten_za" @selected(request('sapxep') == 'ten_za')>🔤 Tên Z→A</option>
                    <option value="moi_nhat" @selected(request('sapxep') == 'moi_nhat')>📅 Mới nhất</option>
                </select>

                <div class="kv-filter-btns">
                    <button type="submit" class="kv-btn-filter">
                        <i class="fas fa-search"></i> Lọc
                    </button>
                    @if (request()->hasAny(['tim_kiem', 'cap', 'cha_id', 'hien_thi', 'sapxep']))
                        <a href="{{ route('nhanvien.admin.khu-vuc.index') }}" class="kv-btn-reset">
                            <i class="fas fa-times"></i> Xóa lọc
                        </a>
                    @endif
                </div>

            </div>
        </form>
    </div>

    {{-- ══ BẢNG DỮ LIỆU ══ --}}
    <div class="kv-data-box">

        <div class="kv-data-hdr">
            <span class="kv-result-info">
                @if ($khuVucs->total() > 0)
                    Hiển thị <strong>{{ $khuVucs->firstItem() }}–{{ $khuVucs->lastItem() }}</strong>
                    / <strong>{{ number_format($khuVucs->total()) }}</strong> khu vực
                @else
                    Không có kết quả
                @endif
            </span>
            {{-- Nút thêm nhanh theo cấp --}}
            <div class="kv-quick-add">
                <a href="{{ route('nhanvien.admin.khu-vuc.create', ['cap' => 'tinh_thanh']) }}" class="kv-qa-btn"
                    style="color:#e74c3c;background:#fff0f0;border-color:#fcc">
                    <i class="fas fa-city"></i> + Tỉnh/TP
                </a>
                <a href="{{ route('nhanvien.admin.khu-vuc.create', ['cap' => 'quan_huyen']) }}" class="kv-qa-btn"
                    style="color:#2d6a9f;background:#e8f4fd;border-color:#c8daf5">
                    <i class="fas fa-map-marked-alt"></i> + Quận/Huyện
                </a>
                <a href="{{ route('nhanvien.admin.khu-vuc.create', ['cap' => 'phuong_xa']) }}" class="kv-qa-btn"
                    style="color:#27ae60;background:#e8f8f0;border-color:#b7e4cb">
                    <i class="fas fa-map-pin"></i> + Phường/Xã
                </a>
            </div>
        </div>

        <div class="kv-tbl-wrap">
            <table class="kv-tbl">
                <thead>
                    <tr>
                        <th style="width:44px">#</th>
                        <th>Khu vực</th>
                        <th style="width:140px">Cấp hành chính</th>
                        <th style="width:200px">Khu vực cha</th>
                        <th style="width:100px">Mã HC</th>
                        <th style="width:80px;text-align:center">Khu vực con</th>
                        <th style="width:80px;text-align:center">Dự án</th>
                        <th style="width:60px;text-align:center">Thứ tự</th>
                        <th style="width:80px;text-align:center">Hiển thị</th>
                        <th style="width:100px;text-align:center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($khuVucs as $kv)
                        @php
                            $capInfo = \App\Models\KhuVuc::CAP[$kv->cap_khu_vuc] ?? [
                                'label' => $kv->cap_khu_vuc,
                                'color' => '#999',
                                'bg' => '#f5f5f5',
                                'icon' => 'fas fa-map',
                            ];
                            $capOrder = $capInfo['order'] ?? 1;
                        @endphp
                        <tr>
                            <td style="color:#ccc;font-size:.8rem;text-align:center">
                                {{ $khuVucs->firstItem() + $loop->index }}
                            </td>

                            {{-- Tên khu vực + slug --}}
                            <td>
                                <div class="kv-name-wrap">
                                    {{-- Indent theo cấp --}}
                                    @if ($kv->cap_khu_vuc !== 'tinh_thanh')
                                        <span class="kv-indent" style="width:{{ ($capOrder - 1) * 16 }}px"></span>
                                        @if ($kv->cap_khu_vuc === 'quan_huyen')
                                            <i class="fas fa-level-up-alt kv-indent-icon"
                                                style="color:#ddd;margin-right:4px;font-size:.75rem;transform:rotate(90deg)"></i>
                                        @elseif($kv->cap_khu_vuc === 'phuong_xa')
                                            <i class="fas fa-level-up-alt kv-indent-icon"
                                                style="color:#eee;margin-right:4px;font-size:.75rem;transform:rotate(90deg)"></i>
                                        @endif
                                    @endif

                                    <div class="kv-name-info">
                                        <a href="{{ route('nhanvien.admin.khu-vuc.edit', $kv) }}" class="kv-name-link">
                                            {{ $kv->ten_khu_vuc }}
                                        </a>
                                        <div style="font-size:.7rem;color:#bbb;font-family:monospace">
                                            /{{ $kv->slug }}
                                            @if ($kv->seo_title)
                                                <span class="kv-seo-ok" title="Đã có SEO"><i class="fas fa-search"></i>
                                                    SEO</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Cấp --}}
                            <td>
                                <span class="kv-badge"
                                    style="color:{{ $capInfo['color'] }};background:{{ $capInfo['bg'] }}">
                                    <i class="{{ $capInfo['icon'] }}"></i>
                                    {{ $capInfo['label'] }}
                                </span>
                            </td>

                            {{-- Khu vực cha --}}
                            <td>
                                @if ($kv->cha)
                                    <div class="kv-parent">
                                        @if ($kv->cha->cha)
                                            <span style="font-size:.72rem;color:#bbb">{{ $kv->cha->cha->ten_khu_vuc }}
                                                ›</span><br>
                                        @endif
                                        <span
                                            style="font-size:.82rem;color:#555;font-weight:500">{{ $kv->cha->ten_khu_vuc }}</span>
                                    </div>
                                @else
                                    <span style="color:#ddd;font-size:.8rem">—</span>
                                @endif
                            </td>

                            {{-- Mã HC --}}
                            <td>
                                @if ($kv->ma_hanh_chinh)
                                    <span class="kv-ma-hc">{{ $kv->ma_hanh_chinh }}</span>
                                @else
                                    <span style="color:#ddd">—</span>
                                @endif
                            </td>

                            {{-- Khu vực con --}}
                            <td style="text-align:center">
                                @if ($kv->con_count > 0)
                                    <a href="{{ route('nhanvien.admin.khu-vuc.index', ['cha_id' => $kv->id]) }}"
                                        class="kv-count-link" style="color:#2d6a9f">
                                        {{ $kv->con_count }}
                                    </a>
                                @else
                                    <span style="color:#ddd">—</span>
                                @endif
                            </td>

                            {{-- Dự án --}}
                            <td style="text-align:center">
                                <span style="font-size:.82rem;font-weight:600;color:#e67e22">
                                    {{ $kv->du_an_count ?? '—' }}
                                </span>
                            </td>

                            {{-- Thứ tự --}}
                            <td style="text-align:center">
                                <span class="kv-thu-tu">{{ $kv->thu_tu_hien_thi }}</span>
                            </td>

                            {{-- Toggle hiển thị --}}
                            <td style="text-align:center">
                                <label class="kv-sw">
                                    <input type="checkbox" class="kv-toggle-ht" data-id="{{ $kv->id }}"
                                        {{ $kv->hien_thi ? 'checked' : '' }}>
                                    <span class="kv-sw-track"><span class="kv-sw-thumb"></span></span>
                                </label>
                            </td>

                            {{-- Thao tác --}}
                            <td style="text-align:center">
                                <div class="kv-acts">
                                    <a href="{{ route('nhanvien.admin.khu-vuc.show', $kv) }}" class="kv-act kv-act-view"
                                        title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('nhanvien.admin.khu-vuc.edit', $kv) }}" class="kv-act kv-act-edit"
                                        title="Chỉnh sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    {{-- Thêm khu vực con nhanh --}}
                                    @if ($kv->cap_khu_vuc !== 'phuong_xa')
                                        <a href="{{ route('nhanvien.admin.khu-vuc.create', [
                                            'cap' => $kv->cap_khu_vuc === 'tinh_thanh' ? 'quan_huyen' : 'phuong_xa',
                                            'cha_id' => $kv->id,
                                        ]) }}"
                                            class="kv-act kv-act-add" title="Thêm khu vực con">
                                            <i class="fas fa-plus"></i>
                                        </a>
                                    @endif

                                    <form action="{{ route('nhanvien.admin.khu-vuc.destroy', $kv) }}" method="POST"
                                        style="display:inline;margin:0">
                                        @csrf @method('DELETE')
                                        <button type="button" class="kv-act kv-act-del kv-confirm-del"
                                            data-ten="{{ $kv->ten_khu_vuc }}" data-con="{{ $kv->con_count }}"
                                            title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" style="text-align:center;padding:60px 20px">
                                <div class="kv-empty">
                                    <i class="fas fa-map-marked-alt"></i>
                                    <p>Chưa có khu vực nào</p>
                                    <a href="{{ route('nhanvien.admin.khu-vuc.create') }}">
                                        + Thêm khu vực đầu tiên
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PHÂN TRANG --}}
        @if ($khuVucs->hasPages())
            <div class="kv-pagi-wrap">
                <div class="kv-pagi-info">
                    Trang {{ $khuVucs->currentPage() }} / {{ $khuVucs->lastPage() }}
                </div>
                <div class="kv-pagi-links">
                    @if ($khuVucs->onFirstPage())
                        <span class="kv-pb kv-pb-dis"><i class="fas fa-angle-double-left"></i></span>
                        <span class="kv-pb kv-pb-dis"><i class="fas fa-angle-left"></i></span>
                    @else
                        <a href="{{ $khuVucs->url(1) }}" class="kv-pb"><i class="fas fa-angle-double-left"></i></a>
                        <a href="{{ $khuVucs->previousPageUrl() }}" class="kv-pb"><i class="fas fa-angle-left"></i></a>
                    @endif

                    @php
                        $cur = $khuVucs->currentPage();
                        $last = $khuVucs->lastPage();
                        $s = max(1, $cur - 2);
                        $e = min($last, $cur + 2);
                    @endphp

                    @if ($s > 1)
                        <a href="{{ $khuVucs->url(1) }}" class="kv-pb">1</a>
                        @if ($s > 2)
                            <span class="kv-pdots">…</span>
                        @endif
                    @endif

                    @for ($p = $s; $p <= $e; $p++)
                        @if ($p == $cur)
                            <span class="kv-pb kv-pb-act">{{ $p }}</span>
                        @else<a href="{{ $khuVucs->url($p) }}" class="kv-pb">{{ $p }}</a>
                        @endif
                    @endfor

                    @if ($e < $last)
                        @if ($e < $last - 1)
                            <span class="kv-pdots">…</span>
                        @endif
                        <a href="{{ $khuVucs->url($last) }}" class="kv-pb">{{ $last }}</a>
                    @endif

                    @if ($khuVucs->hasMorePages())
                        <a href="{{ $khuVucs->nextPageUrl() }}" class="kv-pb"><i class="fas fa-angle-right"></i></a>
                        <a href="{{ $khuVucs->url($last) }}" class="kv-pb"><i
                                class="fas fa-angle-double-right"></i></a>
                    @else
                        <span class="kv-pb kv-pb-dis"><i class="fas fa-angle-right"></i></span>
                        <span class="kv-pb kv-pb-dis"><i class="fas fa-angle-double-right"></i></span>
                    @endif
                </div>
            </div>
        @endif

    </div>

@endsection

@push('styles')
    <style>
        .kv-stat-row {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 14px;
            margin-bottom: 22px
        }

        @media(max-width:1100px) {
            .kv-stat-row {
                grid-template-columns: repeat(3, 1fr)
            }
        }

        @media(max-width:640px) {
            .kv-stat-row {
                grid-template-columns: repeat(2, 1fr)
            }
        }

        .kv-stat-card {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #f0f2f5;
            padding: 14px 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .04)
        }

        .kv-stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0
        }

        .kv-stat-num {
            font-size: 1.55rem;
            font-weight: 800;
            color: #1a3c5e;
            line-height: 1
        }

        .kv-stat-lbl {
            font-size: .74rem;
            color: #aaa;
            margin-top: 3px
        }

        .kv-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 18px;
            flex-wrap: wrap;
            gap: 12px
        }

        .kv-title {
            font-size: 1.35rem;
            font-weight: 700;
            color: #1a3c5e;
            margin: 0 0 4px;
            display: flex;
            align-items: center;
            gap: 9px
        }

        .kv-title i {
            color: #FF8C42
        }

        .kv-sub {
            color: #aaa;
            font-size: .83rem;
            margin: 0
        }

        .kv-btn-add {
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
            white-space: nowrap
        }

        .kv-btn-add:hover {
            transform: translateY(-1px);
            color: #fff
        }

        .kv-flash {
            border-radius: 10px;
            padding: 12px 18px;
            font-size: .875rem;
            font-weight: 500;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px
        }

        .kv-flash-ok {
            background: #e8f8f0;
            border: 1px solid #b7e4cb;
            color: #1a7a45
        }

        .kv-flash-err {
            background: #fff5f5;
            border: 1px solid #fcc;
            color: #c0392b
        }

        .kv-filter-box {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #f0f2f5;
            padding: 16px 18px;
            margin-bottom: 16px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .04)
        }

        .kv-filter-row {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            align-items: center
        }

        .kv-ctrl {
            height: 38px;
            border: 1.5px solid #e8e8e8;
            border-radius: 8px;
            padding: 0 12px;
            font-size: .83rem;
            color: #333;
            background: #fafafa;
            outline: none;
            font-family: inherit;
            transition: border-color .2s
        }

        .kv-ctrl:focus {
            border-color: #FF8C42;
            background: #fff
        }

        .kv-ctrl.kv-search {
            flex: 1;
            min-width: 220px
        }

        select.kv-ctrl {
            appearance: none;
            cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath fill='%23aaa' d='M5 6L0 0h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-color: #fafafa;
            padding-right: 28px
        }

        .kv-filter-btns {
            display: flex;
            gap: 8px
        }

        .kv-btn-filter {
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
            gap: 6px
        }

        .kv-btn-filter:hover {
            background: #0f2742
        }

        .kv-btn-reset {
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
            gap: 5px
        }

        .kv-data-box {
            background: #fff;
            border-radius: 14px;
            border: 1px solid #f0f2f5;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .06);
            overflow: hidden
        }

        .kv-data-hdr {
            padding: 12px 18px;
            border-bottom: 1px solid #f5f5f5;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 8px
        }

        .kv-result-info {
            font-size: .82rem;
            color: #999
        }

        .kv-result-info strong {
            color: #1a3c5e
        }

        .kv-quick-add {
            display: flex;
            gap: 6px;
            flex-wrap: wrap
        }

        .kv-qa-btn {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 10px;
            border-radius: 7px;
            border: 1.5px solid;
            font-size: .75rem;
            font-weight: 700;
            text-decoration: none;
            transition: all .15s;
            white-space: nowrap
        }

        .kv-qa-btn:hover {
            opacity: .8
        }

        .kv-tbl-wrap {
            overflow-x: auto
        }

        .kv-tbl {
            width: 100%;
            border-collapse: collapse;
            min-width: 900px
        }

        .kv-tbl thead tr {
            background: #f8faff
        }

        .kv-tbl th {
            padding: 11px 14px;
            font-size: .72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: #6b7a99;
            border-bottom: 1.5px solid #eef0f5;
            text-align: left;
            white-space: nowrap
        }

        .kv-tbl td {
            padding: 11px 14px;
            border-bottom: 1px solid #f5f6fa;
            vertical-align: middle;
            font-size: .855rem;
            color: #333
        }

        .kv-tbl tbody tr:last-child td {
            border-bottom: none
        }

        .kv-tbl tbody tr:hover {
            background: #fdfeff
        }

        .kv-name-wrap {
            display: flex;
            align-items: center
        }

        .kv-name-info {
            flex: 1
        }

        .kv-name-link {
            font-weight: 600;
            color: #1a3c5e;
            font-size: .87rem;
            text-decoration: none;
            display: block
        }

        .kv-name-link:hover {
            color: #FF8C42
        }

        .kv-seo-ok {
            display: inline-flex;
            align-items: center;
            gap: 2px;
            font-size: .65rem;
            font-weight: 700;
            background: #e8f8f0;
            color: #27ae60;
            padding: .05rem .3rem;
            border-radius: 3px;
            margin-left: 6px
        }

        .kv-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: .74rem;
            font-weight: 700;
            padding: .25rem .6rem;
            border-radius: 20px;
            white-space: nowrap
        }

        .kv-ma-hc {
            font-family: monospace;
            font-size: .78rem;
            font-weight: 700;
            background: #f0f4ff;
            color: #2d6a9f;
            padding: .15rem .45rem;
            border-radius: 5px
        }

        .kv-count-link {
            font-size: .85rem;
            font-weight: 700;
            text-decoration: none;
            padding: .15rem .5rem;
            border-radius: 6px;
            background: #e8f4fd
        }

        .kv-count-link:hover {
            opacity: .8
        }

        .kv-thu-tu {
            font-size: .8rem;
            font-weight: 700;
            color: #aaa;
            background: #f5f5f5;
            padding: .15rem .5rem;
            border-radius: 5px
        }

        .kv-sw {
            position: relative;
            cursor: pointer;
            display: inline-block
        }

        .kv-sw input {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0
        }

        .kv-sw-track {
            display: block;
            width: 42px;
            height: 24px;
            background: #dde0e8;
            border-radius: 12px;
            transition: background .25s;
            position: relative
        }

        .kv-sw input:checked~.kv-sw-track {
            background: #27ae60
        }

        .kv-sw-thumb {
            position: absolute;
            width: 18px;
            height: 18px;
            background: #fff;
            border-radius: 50%;
            top: 3px;
            left: 3px;
            transition: transform .25s;
            box-shadow: 0 1px 5px rgba(0, 0, 0, .2)
        }

        .kv-sw input:checked~.kv-sw-track .kv-sw-thumb {
            transform: translateX(18px)
        }

        .kv-acts {
            display: flex;
            gap: 4px;
            justify-content: center
        }

        .kv-act {
            width: 28px;
            height: 28px;
            border-radius: 7px;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: .75rem;
            text-decoration: none;
            transition: all .15s;
            padding: 0
        }

        .kv-act-view {
            background: #f0fff4;
            color: #27ae60
        }

        .kv-act-view:hover {
            background: #27ae60;
            color: #fff
        }

        .kv-act-edit {
            background: #eef3ff;
            color: #2d6a9f
        }

        .kv-act-edit:hover {
            background: #2d6a9f;
            color: #fff
        }

        .kv-act-add {
            background: #fff8f0;
            color: #e67e22
        }

        .kv-act-add:hover {
            background: #e67e22;
            color: #fff
        }

        .kv-act-del {
            background: #fff0f0;
            color: #e74c3c
        }

        .kv-act-del:hover {
            background: #e74c3c;
            color: #fff
        }

        .kv-empty {
            text-align: center
        }

        .kv-empty i {
            font-size: 3rem;
            color: #e8ebf5;
            display: block;
            margin-bottom: 12px
        }

        .kv-empty p {
            color: #bbb;
            font-size: .95rem;
            margin: 0 0 10px
        }

        .kv-empty a {
            color: #FF8C42;
            font-weight: 600;
            font-size: .875rem;
            text-decoration: none
        }

        .kv-pagi-wrap {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 20px;
            border-top: 1px solid #f5f6fa;
            flex-wrap: wrap;
            gap: 10px
        }

        .kv-pagi-info {
            font-size: .8rem;
            color: #aaa
        }

        .kv-pagi-links {
            display: flex;
            align-items: center;
            gap: 4px;
            flex-wrap: wrap
        }

        .kv-pb {
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
            transition: all .15s
        }

        .kv-pb:hover:not(.kv-pb-act):not(.kv-pb-dis) {
            background: #e8f0ff;
            color: #1a3c5e;
            border-color: #c8daf5
        }

        .kv-pb-act {
            background: linear-gradient(135deg, #1a3c5e, #2d6a9f) !important;
            color: #fff !important;
            box-shadow: 0 3px 10px rgba(26, 60, 94, .25);
            cursor: default
        }

        .kv-pb-dis {
            color: #d0d8e8 !important;
            background: #fafafa !important;
            cursor: not-allowed;
            pointer-events: none
        }

        .kv-pdots {
            min-width: 28px;
            height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #aaa;
            font-size: .85rem;
            font-weight: 700
        }
    </style>
@endpush

@push('scripts')
    <script>
        const CSRF = document.querySelector('meta[name=csrf-token]').content;

        document.querySelectorAll('.kv-toggle-ht').forEach(chk => {
            chk.addEventListener('change', function() {
                const id = this.dataset.id,
                    self = this;
                fetch('/nhan-vien/admin/khu-vuc/' + id + '/toggle', {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': CSRF,
                        'Accept': 'application/json'
                    }
                }).catch(() => {
                    self.checked = !self.checked;
                });
            });
        });

        document.querySelectorAll('.kv-confirm-del').forEach(btn => {
            btn.addEventListener('click', function() {
                const ten = this.dataset.ten;
                const con = parseInt(this.dataset.con || 0);
                if (con > 0) {
                    alert('❌ Không thể xóa "' + ten + '" vì còn ' + con + ' khu vực con!');
                    return;
                }
                if (confirm('Xóa khu vực "' + ten + '"?\nThao tác không thể hoàn tác!')) {
                    this.closest('form').submit();
                }
            });
        });

        document.querySelectorAll('#kvFilterForm select').forEach(s => {
            s.addEventListener('change', () => document.getElementById('kvFilterForm').submit());
        });
    </script>
@endpush
