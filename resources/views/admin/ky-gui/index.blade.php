@extends('admin.layouts.master')
@section('title', 'Quản lý Ký gửi')

@section('content')

    {{-- THỐNG KÊ --}}
    <div class="kg-stat-row">
        @php
            $stats = [
                ['key' => 'tong', 'label' => 'Tổng', 'icon' => 'fas fa-inbox', 'c' => '#2d6a9f', 'b' => '#e8f4fd'],
                [
                    'key' => 'cho_duyet',
                    'label' => 'Chờ duyệt',
                    'icon' => 'fas fa-clock',
                    'c' => '#e67e22',
                    'b' => '#fff8f0',
                ],
                [
                    'key' => 'da_lien_he',
                    'label' => 'Đã liên hệ',
                    'icon' => 'fas fa-phone',
                    'c' => '#9b59b6',
                    'b' => '#f5eeff',
                ],
                [
                    'key' => 'da_nhan',
                    'label' => 'Đã nhận',
                    'icon' => 'fas fa-check-circle',
                    'c' => '#27ae60',
                    'b' => '#e8f8f0',
                ],
                [
                    'key' => 'tu_choi',
                    'label' => 'Từ chối',
                    'icon' => 'fas fa-times-circle',
                    'c' => '#e74c3c',
                    'b' => '#fff0f0',
                ],
            ];
        @endphp
        @foreach ($stats as $s)
            <div class="kg-stat-card">
                <div class="kg-stat-icon" style="background:{{ $s['b'] }};color:{{ $s['c'] }}">
                    <i class="{{ $s['icon'] }}"></i>
                </div>
                <div>
                    <div class="kg-stat-num" style="color:{{ $s['c'] }}">
                        {{ number_format($thongKe[$s['key']]) }}
                    </div>
                    <div class="kg-stat-lbl">{{ $s['label'] }}</div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- HEADER --}}
    <div class="kg-header">
        <div>
            <h1 class="kg-title"><i class="fas fa-file-signature"></i> Ký gửi BĐS</h1>
            <p class="kg-sub">Quản lý yêu cầu ký gửi bất động sản từ khách hàng</p>
        </div>
        <a href="{{ route('nhanvien.admin.ky-gui.create') }}" class="kg-btn-add">
            <i class="fas fa-plus"></i> Thêm ký gửi
        </a>
    </div>

    {{-- FLASH --}}
    @if (session('success'))
        <div class="kg-flash kg-flash-ok"><i class="fas fa-check-circle"></i> {!! session('success') !!}</div>
    @endif
    @if (session('error'))
        <div class="kg-flash kg-flash-err"><i class="fas fa-exclamation-circle"></i> {!! session('error') !!}</div>
    @endif

    {{-- BỘ LỌC --}}
    <div class="kg-filter-box">
        <form method="GET" id="kgFilterForm">
            <div class="kg-filter-row">

                <input type="text" name="tim_kiem" class="kg-ctrl kg-search" value="{{ request('tim_kiem') }}"
                    placeholder="🔍 Tìm tên, SĐT, địa chỉ...">

                <select name="trang_thai" class="kg-ctrl">
                    <option value="">Tất cả trạng thái</option>
                    @foreach (\App\Models\KyGui::TRANG_THAI as $v => $info)
                        <option value="{{ $v }}" @selected(request('trang_thai') == $v)>
                            {{ $info['label'] }}
                        </option>
                    @endforeach
                </select>

                <select name="loai_hinh" class="kg-ctrl">
                    <option value="">Tất cả loại hình</option>
                    @foreach (\App\Models\KyGui::LOAI_HINH as $v => $info)
                        <option value="{{ $v }}" @selected(request('loai_hinh') == $v)>
                            {{ $info['label'] }}
                        </option>
                    @endforeach
                </select>

                <select name="nhu_cau" class="kg-ctrl">
                    <option value="">Bán & Thuê</option>
                    <option value="ban" @selected(request('nhu_cau') == 'ban')>Bán</option>
                    <option value="thue" @selected(request('nhu_cau') == 'thue')>Thuê</option>
                </select>

                <select name="nhan_vien_id" class="kg-ctrl">
                    <option value="">Tất cả nhân viên</option>
                    @foreach ($nhanViens as $nv)
                        <option value="{{ $nv->id }}" @selected(request('nhan_vien_id') == $nv->id)>
                            {{ $nv->ho_ten }}
                        </option>
                    @endforeach
                </select>

                <input type="date" name="tu_ngay" class="kg-ctrl" value="{{ request('tu_ngay') }}" title="Từ ngày">
                <input type="date" name="den_ngay" class="kg-ctrl" value="{{ request('den_ngay') }}" title="Đến ngày">

                <div class="kg-filter-btns">
                    <button type="submit" class="kg-btn-filter">
                        <i class="fas fa-search"></i> Lọc
                    </button>
                    @if (request()->hasAny(['tim_kiem', 'trang_thai', 'loai_hinh', 'nhu_cau', 'nhan_vien_id', 'tu_ngay', 'den_ngay']))
                        <a href="{{ route('nhanvien.admin.ky-gui.index') }}" class="kg-btn-reset">
                            <i class="fas fa-times"></i> Xóa lọc
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    {{-- BẢNG --}}
    <div class="kg-data-box">
        <div class="kg-data-hdr">
            <span class="kg-result-info">
                @if ($kyGuis->total() > 0)
                    Hiển thị <strong>{{ $kyGuis->firstItem() }}–{{ $kyGuis->lastItem() }}</strong>
                    / <strong>{{ number_format($kyGuis->total()) }}</strong> yêu cầu
                @else
                    Không có kết quả
                @endif
            </span>
        </div>

        <div class="kg-tbl-wrap">
            <table class="kg-tbl">
                <thead>
                    <tr>
                        <th style="width:44px">#</th>
                        <th>Chủ nhà</th>
                        <th style="width:130px">Loại hình</th>
                        <th style="width:90px">Nhu cầu</th>
                        <th>Địa chỉ / Thông tin</th>
                        <th style="width:130px">Giá mong muốn</th>
                        <th style="width:150px">Phụ trách</th>
                        <th style="width:120px">Trạng thái</th>
                        <th style="width:90px">Ngày gửi</th>
                        <th style="width:80px;text-align:center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kyGuis as $kg)
                        @php
                            $ttInfo = \App\Models\KyGui::TRANG_THAI[$kg->trang_thai] ?? [
                                'label' => $kg->trang_thai,
                                'color' => '#999',
                                'bg' => '#f5f5f5',
                                'icon' => 'fas fa-question',
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
                        <tr class="{{ $kg->trang_thai === 'cho_duyet' ? 'kg-row-new' : '' }}">
                            <td style="color:#ccc;font-size:.8rem;text-align:center">
                                {{ $kyGuis->firstItem() + $loop->index }}
                            </td>

                            {{-- Chủ nhà --}}
                            <td>
                                <div class="kg-owner">
                                    <a href="{{ route('nhanvien.admin.ky-gui.show', $kg) }}"
                                        class="kg-owner-name">{{ $kg->ho_ten_chu_nha }}</a>
                                    <div style="font-size:.76rem;color:#888">
                                        <i class="fas fa-phone" style="font-size:.65rem"></i>
                                        {{ $kg->so_dien_thoai }}
                                    </div>
                                    @if ($kg->email)
                                        <div style="font-size:.72rem;color:#bbb">{{ $kg->email }}</div>
                                    @endif
                                </div>
                            </td>

                            {{-- Loại hình --}}
                            <td>
                                <span style="font-size:.78rem;font-weight:600;color:{{ $lhInfo['color'] }}">
                                    <i class="{{ $lhInfo['icon'] }}" style="margin-right:4px"></i>
                                    {{ $lhInfo['label'] }}
                                </span>
                            </td>

                            {{-- Nhu cầu --}}
                            <td>
                                <span class="kg-badge"
                                    style="color:{{ $ncInfo['color'] }};background:{{ $ncInfo['bg'] }}">
                                    {{ $ncInfo['label'] }}
                                </span>
                            </td>

                            {{-- Địa chỉ / Thông tin --}}
                            <td>
                                <div style="font-size:.82rem;color:#444;max-width:220px">
                                    @if ($kg->dia_chi)
                                        <div style="margin-bottom:2px">
                                            <i class="fas fa-map-marker-alt" style="color:#bbb;font-size:.7rem"></i>
                                            {{ Str::limit($kg->dia_chi, 50) }}
                                        </div>
                                    @endif
                                    <div style="color:#aaa;font-size:.74rem">
                                        {{ $kg->dien_tich }} m²
                                        @if ($kg->so_phong_ngu)
                                            · {{ $kg->so_phong_ngu }} PN
                                        @endif
                                        @if ($kg->so_phong_tam)
                                            · {{ $kg->so_phong_tam }} WC
                                        @endif
                                    </div>
                                </div>
                            </td>

                            {{-- Giá --}}
                            <td style="font-size:.85rem;font-weight:700;color:#1a3c5e">
                                {{ $kg->gia_hien_thi }}
                            </td>

                            {{-- Phụ trách --}}
                            <td>
                                @if ($kg->nhanVienPhuTrach)
                                    <div class="kg-nv">
                                        <div class="kg-nv-av">
                                            {{ mb_strtoupper(mb_substr($kg->nhanVienPhuTrach->ho_ten, 0, 1)) }}
                                        </div>
                                        <span style="font-size:.8rem">{{ $kg->nhanVienPhuTrach->ho_ten }}</span>
                                    </div>
                                @else
                                    <span class="kg-unassigned">Chưa phân công</span>
                                @endif
                            </td>

                            {{-- Trạng thái --}}
                            <td>
                                <span class="kg-badge"
                                    style="color:{{ $ttInfo['color'] }};background:{{ $ttInfo['bg'] }}">
                                    <i class="{{ $ttInfo['icon'] }}"></i>
                                    {{ $ttInfo['label'] }}
                                </span>
                                @if ($kg->thoi_diem_xu_ly)
                                    <div style="font-size:.68rem;color:#bbb;margin-top:2px">
                                        {{ $kg->thoi_diem_xu_ly->format('d/m H:i') }}
                                    </div>
                                @endif
                            </td>

                            {{-- Ngày gửi --}}
                            <td style="font-size:.78rem;color:#888">
                                {{ $kg->created_at->format('d/m/Y') }}<br>
                                <span style="color:#ccc">{{ $kg->created_at->format('H:i') }}</span>
                            </td>

                            {{-- Thao tác --}}
                            <td style="text-align:center">
                                <div class="kg-acts">
                                    <a href="{{ route('nhanvien.admin.ky-gui.show', $kg) }}" class="kg-act kg-act-view"
                                        title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('nhanvien.admin.ky-gui.edit', $kg) }}" class="kg-act kg-act-edit"
                                        title="Chỉnh sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('nhanvien.admin.ky-gui.destroy', $kg) }}" method="POST"
                                        style="display:inline;margin:0">
                                        @csrf @method('DELETE')
                                        <button type="button" class="kg-act kg-act-del kg-confirm-del"
                                            data-ten="{{ $kg->ho_ten_chu_nha }}" title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" style="text-align:center;padding:60px 20px">
                                <div style="color:#bbb">
                                    <i class="fas fa-inbox" style="font-size:2.5rem;display:block;margin-bottom:12px"></i>
                                    Chưa có yêu cầu ký gửi nào
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PHÂN TRANG --}}
        @if ($kyGuis->hasPages())
            <div style="padding:14px 18px;border-top:1px solid #f5f6fa">
                {{ $kyGuis->links() }}
            </div>
        @endif
    </div>

@endsection

@push('styles')
    <style>
        .kg-stat-row {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 14px;
            margin-bottom: 22px
        }

        @media(max-width:1000px) {
            .kg-stat-row {
                grid-template-columns: repeat(3, 1fr)
            }
        }

        .kg-stat-card {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #f0f2f5;
            padding: 14px 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .04)
        }

        .kg-stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0
        }

        .kg-stat-num {
            font-size: 1.5rem;
            font-weight: 800;
            line-height: 1
        }

        .kg-stat-lbl {
            font-size: .74rem;
            color: #aaa;
            margin-top: 3px
        }

        .kg-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 18px;
            flex-wrap: wrap;
            gap: 12px
        }

        .kg-title {
            font-size: 1.35rem;
            font-weight: 700;
            color: #1a3c5e;
            margin: 0 0 4px;
            display: flex;
            align-items: center;
            gap: 9px
        }

        .kg-title i {
            color: #FF8C42
        }

        .kg-sub {
            color: #aaa;
            font-size: .83rem;
            margin: 0
        }

        .kg-btn-add {
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
            transition: all .2s
        }

        .kg-btn-add:hover {
            transform: translateY(-1px);
            color: #fff
        }

        .kg-flash {
            border-radius: 10px;
            padding: 12px 18px;
            font-size: .875rem;
            font-weight: 500;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px
        }

        .kg-flash-ok {
            background: #e8f8f0;
            border: 1px solid #b7e4cb;
            color: #1a7a45
        }

        .kg-flash-err {
            background: #fff5f5;
            border: 1px solid #fcc;
            color: #c0392b
        }

        .kg-filter-box {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #f0f2f5;
            padding: 16px 18px;
            margin-bottom: 16px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .04)
        }

        .kg-filter-row {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            align-items: center
        }

        .kg-ctrl {
            height: 38px;
            border: 1.5px solid #e8e8e8;
            border-radius: 8px;
            padding: 0 12px;
            font-size: .83rem;
            color: #333;
            background: #fafafa;
            outline: none;
            transition: border-color .2s
        }

        .kg-ctrl:focus {
            border-color: #FF8C42;
            background: #fff
        }

        .kg-ctrl.kg-search {
            flex: 1;
            min-width: 200px
        }

        select.kg-ctrl {
            appearance: none;
            cursor: pointer;
            padding-right: 28px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath fill='%23aaa' d='M5 6L0 0h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center
        }

        .kg-filter-btns {
            display: flex;
            gap: 8px
        }

        .kg-btn-filter {
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

        .kg-btn-reset {
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

        .kg-data-box {
            background: #fff;
            border-radius: 14px;
            border: 1px solid #f0f2f5;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .06);
            overflow: hidden
        }

        .kg-data-hdr {
            padding: 12px 18px;
            border-bottom: 1px solid #f5f5f5;
            display: flex;
            align-items: center;
            justify-content: space-between
        }

        .kg-result-info {
            font-size: .82rem;
            color: #999
        }

        .kg-result-info strong {
            color: #1a3c5e
        }

        .kg-tbl-wrap {
            overflow-x: auto
        }

        .kg-tbl {
            width: 100%;
            border-collapse: collapse;
            min-width: 1000px
        }

        .kg-tbl thead tr {
            background: #f8faff
        }

        .kg-tbl th {
            padding: 10px 13px;
            font-size: .72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: #6b7a99;
            border-bottom: 1.5px solid #eef0f5;
            text-align: left
        }

        .kg-tbl td {
            padding: 11px 13px;
            border-bottom: 1px solid #f5f6fa;
            vertical-align: middle
        }

        .kg-tbl tbody tr:last-child td {
            border-bottom: none
        }

        .kg-tbl tbody tr:hover {
            background: #fdfeff
        }

        .kg-row-new {
            background: #fffef8 !important
        }

        .kg-row-new:hover {
            background: #fffcf0 !important
        }

        .kg-owner-name {
            font-weight: 700;
            color: #1a3c5e;
            font-size: .87rem;
            text-decoration: none;
            display: block
        }

        .kg-owner-name:hover {
            color: #FF8C42
        }

        .kg-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: .74rem;
            font-weight: 700;
            padding: .25rem .6rem;
            border-radius: 20px;
            white-space: nowrap
        }

        .kg-nv {
            display: flex;
            align-items: center;
            gap: 6px
        }

        .kg-nv-av {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1a3c5e, #2d6a9f);
            color: #fff;
            font-size: .72rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0
        }

        .kg-unassigned {
            font-size: .75rem;
            color: #e67e22;
            font-weight: 600;
            font-style: italic
        }

        .kg-acts {
            display: flex;
            gap: 4px;
            justify-content: center
        }

        .kg-act {
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

        .kg-act-view {
            background: #f0fff4;
            color: #27ae60
        }

        .kg-act-view:hover {
            background: #27ae60;
            color: #fff
        }

        .kg-act-edit {
            background: #eef3ff;
            color: #2d6a9f
        }

        .kg-act-edit:hover {
            background: #2d6a9f;
            color: #fff
        }

        .kg-act-del {
            background: #fff0f0;
            color: #e74c3c
        }

        .kg-act-del:hover {
            background: #e74c3c;
            color: #fff
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.querySelectorAll('#kgFilterForm select').forEach(s => {
            s.addEventListener('change', () => document.getElementById('kgFilterForm').submit());
        });

        document.querySelectorAll('.kg-confirm-del').forEach(btn => {
            btn.addEventListener('click', function() {
                if (confirm('Xóa yêu cầu ký gửi của "' + this.dataset.ten + '"?')) {
                    this.closest('form').submit();
                }
            });
        });
    </script>
@endpush
