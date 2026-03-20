@extends('admin.layouts.master')
@section('title', 'Quản lý Bài viết')

@section('content')

    {{-- ══ THỐNG KÊ ══ --}}
    <div class="bv-stat-row">
        <div class="bv-stat-card">
            <div class="bv-stat-icon" style="background:#e8f0ff;color:#2d6a9f"><i class="fas fa-newspaper"></i></div>
            <div>
                <div class="bv-stat-num">{{ number_format($thongKe['tong']) }}</div>
                <div class="bv-stat-lbl">Tổng bài viết</div>
            </div>
        </div>
        <div class="bv-stat-card">
            <div class="bv-stat-icon" style="background:#e8f8f0;color:#27ae60"><i class="fas fa-eye"></i></div>
            <div>
                <div class="bv-stat-num">{{ number_format($thongKe['hien_thi']) }}</div>
                <div class="bv-stat-lbl">Đang hiển thị</div>
            </div>
        </div>
        <div class="bv-stat-card">
            <div class="bv-stat-icon" style="background:#fff8f0;color:#e67e22"><i class="fas fa-star"></i></div>
            <div>
                <div class="bv-stat-num">{{ number_format($thongKe['noi_bat']) }}</div>
                <div class="bv-stat-lbl">Nổi bật</div>
            </div>
        </div>
        <div class="bv-stat-card">
            <div class="bv-stat-icon" style="background:#e8f4fd;color:#2d6a9f"><i class="fas fa-rss"></i></div>
            <div>
                <div class="bv-stat-num">{{ number_format($thongKe['tin_tuc']) }}</div>
                <div class="bv-stat-lbl">Tin tức</div>
            </div>
        </div>
        <div class="bv-stat-card">
            <div class="bv-stat-icon" style="background:#f5eeff;color:#8e44ad"><i class="fas fa-book-open"></i></div>
            <div>
                <div class="bv-stat-num">{{ number_format($thongKe['kien_thuc']) }}</div>
                <div class="bv-stat-lbl">Kiến thức BĐS</div>
            </div>
        </div>
    </div>

    {{-- ══ HEADER ══ --}}
    <div class="bv-header">
        <div>
            <h1 class="bv-title"><i class="fas fa-pen-alt"></i> Bài viết</h1>
            <p class="bv-sub">Quản lý tin tức, bài viết SEO & nội dung marketing</p>
        </div>
        <a href="{{ route('nhanvien.admin.bai-viet.create') }}" class="bv-btn-add">
            <i class="fas fa-plus"></i> Viết bài mới
        </a>
    </div>

    {{-- Flash --}}
    @if (session('success'))
        <div class="bv-flash bv-flash-ok"><i class="fas fa-check-circle"></i> {!! session('success') !!}</div>
    @endif
    @if (session('error'))
        <div class="bv-flash bv-flash-err"><i class="fas fa-exclamation-circle"></i> {!! session('error') !!}</div>
    @endif

    {{-- ══ BỘ LỌC ══ --}}
    <div class="bv-filter-box">
        <form method="GET" id="bvFilterForm">
            <div class="bv-filter-row">

                <input type="text" name="tim_kiem" class="bv-ctrl bv-search" value="{{ request('tim_kiem') }}"
                    placeholder="🔍 Tìm tiêu đề bài viết...">

                <select name="loai" class="bv-ctrl">
                    <option value="">Tất cả loại</option>
                    @foreach (\App\Models\BaiViet::LOAI as $v => $info)
                        <option value="{{ $v }}" @selected(request('loai') == $v)>
                            {{ $info['label'] }}
                        </option>
                    @endforeach
                </select>

                <select name="hien_thi" class="bv-ctrl">
                    <option value="">Tất cả trạng thái</option>
                    <option value="1" @selected(request('hien_thi') === '1')>✅ Đang hiển thị</option>
                    <option value="0" @selected(request('hien_thi') === '0')>🚫 Ẩn</option>
                </select>

                <select name="noi_bat" class="bv-ctrl">
                    <option value="">Tất cả</option>
                    <option value="1" @selected(request('noi_bat') === '1')>⭐ Nổi bật</option>
                    <option value="0" @selected(request('noi_bat') === '0')>Không nổi bật</option>
                </select>

                <select name="sapxep" class="bv-ctrl">
                    <option value="moi_nhat" @selected(request('sapxep', 'moi_nhat') == 'moi_nhat')>📅 Mới nhất</option>
                    <option value="cu_nhat" @selected(request('sapxep') == 'cu_nhat')>📅 Cũ nhất</option>
                    <option value="luot_xem" @selected(request('sapxep') == 'luot_xem')>👁 Lượt xem</option>
                    <option value="thu_tu" @selected(request('sapxep') == 'thu_tu')>🔢 Thứ tự</option>
                    <option value="tieu_de_az"@selected(request('sapxep') == 'tieu_de_az')>🔤 Tiêu đề A→Z</option>
                </select>

                <div class="bv-filter-btns">
                    <button type="submit" class="bv-btn-filter">
                        <i class="fas fa-search"></i> Lọc
                    </button>
                    @if (request()->hasAny(['tim_kiem', 'loai', 'hien_thi', 'noi_bat', 'sapxep']))
                        <a href="{{ route('nhanvien.admin.bai-viet.index') }}" class="bv-btn-reset">
                            <i class="fas fa-times"></i> Xóa lọc
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    {{-- ══ BẢNG DỮ LIỆU ══ --}}
    <div class="bv-data-box">

        <div class="bv-data-hdr">
            <span class="bv-result-info">
                @if ($baiViets->total() > 0)
                    Hiển thị <strong>{{ $baiViets->firstItem() }}–{{ $baiViets->lastItem() }}</strong>
                    / <strong>{{ number_format($baiViets->total()) }}</strong> bài viết
                @else
                    Không có kết quả
                @endif
            </span>
        </div>

        <div class="bv-tbl-wrap">
            <table class="bv-tbl">
                <thead>
                    <tr>
                        <th style="width:44px">#</th>
                        <th>Bài viết</th>
                        <th style="width:120px">Loại</th>
                        <th style="width:110px">Tác giả</th>
                        <th style="width:90px;text-align:center">Lượt xem</th>
                        <th style="width:140px">Thời điểm đăng</th>
                        <th style="width:80px;text-align:center">Nổi bật</th>
                        <th style="width:80px;text-align:center">Hiển thị</th>
                        <th style="width:100px;text-align:center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($baiViets as $bv)
                        @php
                            $loaiInfo = \App\Models\BaiViet::LOAI[$bv->loai_bai_viet] ?? [
                                'label' => $bv->loai_bai_viet,
                                'color' => '#999',
                                'bg' => '#f5f5f5',
                                'icon' => 'fas fa-file',
                            ];
                        @endphp
                        <tr>
                            <td style="color:#ccc;font-size:.8rem;text-align:center">
                                {{ $baiViets->firstItem() + $loop->index }}
                            </td>

                            {{-- Bài viết --}}
                            <td>
                                <div class="bv-row">
                                    {{-- Thumbnail --}}
                                    <div class="bv-thumb">
                                        @if ($bv->hinh_anh_url)
                                            <img src="{{ $bv->hinh_anh_url }}" alt="{{ $bv->tieu_de }}">
                                        @else
                                            <div class="bv-thumb-empty">
                                                <i class="{{ $loaiInfo['icon'] }}"
                                                    style="color:{{ $loaiInfo['color'] }}"></i>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="bv-info">
                                        <a href="{{ route('nhanvien.admin.bai-viet.edit', $bv) }}" class="bv-title-link">
                                            {{ $bv->tieu_de }}
                                        </a>
                                        @if ($bv->mo_ta_ngan)
                                            <div class="bv-desc">
                                                {{ Str::limit($bv->mo_ta_ngan, 80) }}
                                            </div>
                                        @endif
                                        <div class="bv-meta">
                                            <span class="bv-slug">/{{ $bv->slug }}</span>
                                            @if ($bv->seo_title)
                                                <span class="bv-seo-ok" title="Đã có SEO">
                                                    <i class="fas fa-search"></i> SEO
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Loại --}}
                            <td>
                                <span class="bv-badge"
                                    style="color:{{ $loaiInfo['color'] }};background:{{ $loaiInfo['bg'] }}">
                                    <i class="{{ $loaiInfo['icon'] }}"></i>
                                    {{ $loaiInfo['label'] }}
                                </span>
                            </td>

                            {{-- Tác giả --}}
                            <td>
                                @if ($bv->tacGia)
                                    <div class="bv-author">{{ $bv->tacGia->ho_ten }}</div>
                                @else
                                    <span style="color:#ddd;font-size:.8rem">—</span>
                                @endif
                            </td>

                            {{-- Lượt xem --}}
                            <td style="text-align:center">
                                <span class="bv-views">
                                    <i class="fas fa-eye" style="color:#bbb;font-size:.7rem;margin-right:2px"></i>
                                    {{ number_format($bv->luot_xem) }}
                                </span>
                            </td>

                            {{-- Thời điểm đăng --}}
                            <td>
                                @if ($bv->thoi_diem_dang)
                                    <div style="font-size:.8rem;color:#555">
                                        {{ $bv->thoi_diem_dang->format('d/m/Y H:i') }}
                                    </div>
                                    <div style="font-size:.72rem;color:#bbb">
                                        {{ $bv->thoi_diem_dang->diffForHumans() }}
                                    </div>
                                @endif
                            </td>

                            {{-- Toggle nổi bật --}}
                            <td style="text-align:center">
                                <button type="button" class="bv-star {{ $bv->noi_bat ? 'bv-star-on' : '' }}"
                                    data-id="{{ $bv->id }}"
                                    title="{{ $bv->noi_bat ? 'Bỏ nổi bật' : 'Đặt nổi bật' }}">
                                    <i class="{{ $bv->noi_bat ? 'fas' : 'far' }} fa-star"></i>
                                </button>
                            </td>

                            {{-- Toggle hiển thị --}}
                            <td style="text-align:center">
                                <label class="bv-sw">
                                    <input type="checkbox" class="bv-toggle-ht" data-id="{{ $bv->id }}"
                                        {{ $bv->hien_thi ? 'checked' : '' }}>
                                    <span class="bv-sw-track"><span class="bv-sw-thumb"></span></span>
                                </label>
                            </td>

                            {{-- Thao tác --}}
                            <td style="text-align:center">
                                <div class="bv-acts">
                                    <a href="{{ route('nhanvien.admin.bai-viet.show', $bv) }}" class="bv-act bv-act-view"
                                        title="Xem trước">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('nhanvien.admin.bai-viet.edit', $bv) }}" class="bv-act bv-act-edit"
                                        title="Chỉnh sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('nhanvien.admin.bai-viet.destroy', $bv) }}" method="POST"
                                        style="display:inline;margin:0">
                                        @csrf @method('DELETE')
                                        <button type="button" class="bv-act bv-act-del bv-confirm-del"
                                            data-tieu-de="{{ $bv->tieu_de }}" title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="text-align:center;padding:60px 20px">
                                <div class="bv-empty">
                                    <i class="fas fa-pen-alt"></i>
                                    <p>Chưa có bài viết nào</p>
                                    <a href="{{ route('nhanvien.admin.bai-viet.create') }}">
                                        + Viết bài đầu tiên
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PHÂN TRANG --}}
        @if ($baiViets->hasPages())
            <div class="bv-pagi-wrap">
                <div class="bv-pagi-info">
                    Trang {{ $baiViets->currentPage() }} / {{ $baiViets->lastPage() }}
                </div>
                <div class="bv-pagi-links">
                    @if ($baiViets->onFirstPage())
                        <span class="bv-pb bv-pb-dis"><i class="fas fa-angle-double-left"></i></span>
                        <span class="bv-pb bv-pb-dis"><i class="fas fa-angle-left"></i></span>
                    @else
                        <a href="{{ $baiViets->url(1) }}" class="bv-pb"><i class="fas fa-angle-double-left"></i></a>
                        <a href="{{ $baiViets->previousPageUrl() }}" class="bv-pb"><i
                                class="fas fa-angle-left"></i></a>
                    @endif
                    @php
                        $cur = $baiViets->currentPage();
                        $last = $baiViets->lastPage();
                        $s = max(1, $cur - 2);
                        $e = min($last, $cur + 2);
                    @endphp
                    @if ($s > 1)<a href="{{ $baiViets->url(1) }}"
                            class="bv-pb">1</a>
                        @if ($s > 2)
                            <span class="bv-pdots">…</span>
                        @endif
                    @endif
                    @for ($p = $s; $p <= $e; $p++)
                        @if ($p == $cur)
                            <span class="bv-pb bv-pb-act">{{ $p }}</span>
                        @else<a href="{{ $baiViets->url($p) }}" class="bv-pb">{{ $p }}</a>
                        @endif
                    @endfor
                    @if ($e < $last)
                        @if ($e < $last - 1)
                            <span class="bv-pdots">…</span>
                        @endif
                        <a href="{{ $baiViets->url($last) }}" class="bv-pb">{{ $last }}</a>
                    @endif
                    @if ($baiViets->hasMorePages())
                        <a href="{{ $baiViets->nextPageUrl() }}" class="bv-pb"><i class="fas fa-angle-right"></i></a>
                        <a href="{{ $baiViets->url($last) }}" class="bv-pb"><i
                                class="fas fa-angle-double-right"></i></a>
                    @else
                        <span class="bv-pb bv-pb-dis"><i class="fas fa-angle-right"></i></span>
                        <span class="bv-pb bv-pb-dis"><i class="fas fa-angle-double-right"></i></span>
                    @endif
                </div>
            </div>
        @endif

    </div>

@endsection

@push('styles')
    <style>
        .bv-stat-row {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 14px;
            margin-bottom: 22px
        }

        @media(max-width:1100px) {
            .bv-stat-row {
                grid-template-columns: repeat(3, 1fr)
            }
        }

        @media(max-width:640px) {
            .bv-stat-row {
                grid-template-columns: repeat(2, 1fr)
            }
        }

        .bv-stat-card {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #f0f2f5;
            padding: 14px 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .04)
        }

        .bv-stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0
        }

        .bv-stat-num {
            font-size: 1.55rem;
            font-weight: 800;
            color: #1a3c5e;
            line-height: 1
        }

        .bv-stat-lbl {
            font-size: .74rem;
            color: #aaa;
            margin-top: 3px
        }

        .bv-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 18px;
            flex-wrap: wrap;
            gap: 12px
        }

        .bv-title {
            font-size: 1.35rem;
            font-weight: 700;
            color: #1a3c5e;
            margin: 0 0 4px;
            display: flex;
            align-items: center;
            gap: 9px
        }

        .bv-title i {
            color: #FF8C42
        }

        .bv-sub {
            color: #aaa;
            font-size: .83rem;
            margin: 0
        }

        .bv-btn-add {
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

        .bv-btn-add:hover {
            transform: translateY(-1px);
            color: #fff
        }

        .bv-flash {
            border-radius: 10px;
            padding: 12px 18px;
            font-size: .875rem;
            font-weight: 500;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px
        }

        .bv-flash-ok {
            background: #e8f8f0;
            border: 1px solid #b7e4cb;
            color: #1a7a45
        }

        .bv-flash-err {
            background: #fff5f5;
            border: 1px solid #fcc;
            color: #c0392b
        }

        .bv-filter-box {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #f0f2f5;
            padding: 16px 18px;
            margin-bottom: 16px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .04)
        }

        .bv-filter-row {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            align-items: center
        }

        .bv-ctrl {
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

        .bv-ctrl:focus {
            border-color: #FF8C42;
            background: #fff
        }

        .bv-ctrl.bv-search {
            flex: 1;
            min-width: 220px
        }

        select.bv-ctrl {
            appearance: none;
            cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath fill='%23aaa' d='M5 6L0 0h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-color: #fafafa;
            padding-right: 28px
        }

        .bv-filter-btns {
            display: flex;
            gap: 8px
        }

        .bv-btn-filter {
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

        .bv-btn-filter:hover {
            background: #0f2742
        }

        .bv-btn-reset {
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

        .bv-data-box {
            background: #fff;
            border-radius: 14px;
            border: 1px solid #f0f2f5;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .06);
            overflow: hidden
        }

        .bv-data-hdr {
            padding: 14px 20px;
            border-bottom: 1px solid #f5f5f5
        }

        .bv-result-info {
            font-size: .82rem;
            color: #999
        }

        .bv-result-info strong {
            color: #1a3c5e
        }

        .bv-tbl-wrap {
            overflow-x: auto
        }

        .bv-tbl {
            width: 100%;
            border-collapse: collapse;
            min-width: 860px
        }

        .bv-tbl thead tr {
            background: #f8faff
        }

        .bv-tbl th {
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

        .bv-tbl td {
            padding: 12px 14px;
            border-bottom: 1px solid #f5f6fa;
            vertical-align: middle;
            font-size: .855rem;
            color: #333
        }

        .bv-tbl tbody tr:last-child td {
            border-bottom: none
        }

        .bv-tbl tbody tr:hover {
            background: #fdfeff
        }

        .bv-row {
            display: flex;
            align-items: center;
            gap: 12px
        }

        .bv-thumb {
            width: 70px;
            height: 52px;
            border-radius: 8px;
            overflow: hidden;
            flex-shrink: 0;
            background: #f5f7ff;
            display: flex;
            align-items: center;
            justify-content: center
        }

        .bv-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover
        }

        .bv-thumb-empty {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem
        }

        .bv-info {
            flex: 1;
            min-width: 0
        }

        .bv-title-link {
            font-weight: 600;
            color: #1a3c5e;
            font-size: .88rem;
            text-decoration: none;
            display: block;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 340px
        }

        .bv-title-link:hover {
            color: #FF8C42
        }

        .bv-desc {
            font-size: .77rem;
            color: #aaa;
            margin-top: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 340px
        }

        .bv-meta {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 3px
        }

        .bv-slug {
            font-size: .7rem;
            color: #bbb;
            font-family: monospace;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 200px;
            display: inline-block
        }

        .bv-seo-ok {
            font-size: .68rem;
            font-weight: 700;
            background: #e8f8f0;
            color: #27ae60;
            padding: .1rem .4rem;
            border-radius: 4px;
            display: flex;
            align-items: center;
            gap: 3px
        }

        .bv-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: .74rem;
            font-weight: 700;
            padding: .25rem .6rem;
            border-radius: 20px;
            white-space: nowrap
        }

        .bv-author {
            font-size: .8rem;
            font-weight: 600;
            color: #1a3c5e
        }

        .bv-views {
            font-size: .82rem;
            color: #555;
            font-weight: 600
        }

        .bv-star {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1.1rem;
            color: #ddd;
            padding: 4px;
            transition: all .2s;
            border-radius: 6px
        }

        .bv-star:hover {
            color: #f39c12;
            transform: scale(1.15)
        }

        .bv-star.bv-star-on {
            color: #f39c12
        }

        .bv-sw {
            position: relative;
            cursor: pointer;
            display: inline-block
        }

        .bv-sw input {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0
        }

        .bv-sw-track {
            display: block;
            width: 42px;
            height: 24px;
            background: #dde0e8;
            border-radius: 12px;
            transition: background .25s;
            position: relative
        }

        .bv-sw input:checked~.bv-sw-track {
            background: #27ae60
        }

        .bv-sw-thumb {
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

        .bv-sw input:checked~.bv-sw-track .bv-sw-thumb {
            transform: translateX(18px)
        }

        .bv-acts {
            display: flex;
            gap: 4px;
            justify-content: center
        }

        .bv-act {
            width: 30px;
            height: 30px;
            border-radius: 7px;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: .78rem;
            text-decoration: none;
            transition: all .15s;
            padding: 0
        }

        .bv-act-view {
            background: #f0fff4;
            color: #27ae60
        }

        .bv-act-view:hover {
            background: #27ae60;
            color: #fff
        }

        .bv-act-edit {
            background: #eef3ff;
            color: #2d6a9f
        }

        .bv-act-edit:hover {
            background: #2d6a9f;
            color: #fff
        }

        .bv-act-del {
            background: #fff0f0;
            color: #e74c3c
        }

        .bv-act-del:hover {
            background: #e74c3c;
            color: #fff
        }

        .bv-empty {
            text-align: center
        }

        .bv-empty i {
            font-size: 3rem;
            color: #e8ebf5;
            display: block;
            margin-bottom: 12px
        }

        .bv-empty p {
            color: #bbb;
            font-size: .95rem;
            margin: 0 0 10px
        }

        .bv-empty a {
            color: #FF8C42;
            font-weight: 600;
            font-size: .875rem;
            text-decoration: none
        }

        .bv-pagi-wrap {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 20px;
            border-top: 1px solid #f5f6fa;
            flex-wrap: wrap;
            gap: 10px
        }

        .bv-pagi-info {
            font-size: .8rem;
            color: #aaa
        }

        .bv-pagi-links {
            display: flex;
            align-items: center;
            gap: 4px;
            flex-wrap: wrap
        }

        .bv-pb {
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

        .bv-pb:hover:not(.bv-pb-act):not(.bv-pb-dis) {
            background: #e8f0ff;
            color: #1a3c5e;
            border-color: #c8daf5
        }

        .bv-pb-act {
            background: linear-gradient(135deg, #1a3c5e, #2d6a9f) !important;
            color: #fff !important;
            box-shadow: 0 3px 10px rgba(26, 60, 94, .25);
            cursor: default
        }

        .bv-pb-dis {
            color: #d0d8e8 !important;
            background: #fafafa !important;
            cursor: not-allowed;
            pointer-events: none
        }

        .bv-pdots {
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

        // ── Toggle hiển thị ──
        document.querySelectorAll('.bv-toggle-ht').forEach(chk => {
            chk.addEventListener('change', function() {
                const id = this.dataset.id,
                    self = this;
                fetch('/nhan-vien/admin/bai-viet/' + id + '/hien-thi', {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': CSRF,
                            'Accept': 'application/json'
                        }
                    }).then(r => r.json())
                    .catch(() => {
                        self.checked = !self.checked;
                    });
            });
        });

        // ── Toggle nổi bật ──
        document.querySelectorAll('.bv-star').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.dataset.id,
                    self = this;
                fetch('/nhan-vien/admin/bai-viet/' + id + '/noi-bat', {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': CSRF,
                        'Accept': 'application/json'
                    }
                }).then(r => r.json()).then(data => {
                    if (data.ok) {
                        if (data.noi_bat) {
                            self.classList.add('bv-star-on');
                            self.innerHTML = '<i class="fas fa-star"></i>';
                        } else {
                            self.classList.remove('bv-star-on');
                            self.innerHTML = '<i class="far fa-star"></i>';
                        }
                    }
                });
            });
        });

        // ── Confirm xóa ──
        document.querySelectorAll('.bv-confirm-del').forEach(btn => {
            btn.addEventListener('click', function() {
                if (confirm('Xóa bài viết "' + this.dataset.tieuDe + '"?\nThao tác không thể hoàn tác!')) {
                    this.closest('form').submit();
                }
            });
        });

        // ── Auto submit filter ──
        document.querySelectorAll('#bvFilterForm select').forEach(s => {
            s.addEventListener('change', () => document.getElementById('bvFilterForm').submit());
        });
    </script>
@endpush
