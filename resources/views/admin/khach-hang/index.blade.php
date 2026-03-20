@extends('admin.layouts.master')
@section('title', 'Quản lý Khách hàng')

@section('content')

    {{-- ══ THỐNG KÊ ══ --}}
    <div class="kh-stat-row">
        <div class="kh-stat-card">
            <div class="kh-stat-icon" style="background:#e8f0ff;color:#2d6a9f">
                <i class="fas fa-users"></i>
            </div>
            <div>
                <div class="kh-stat-num">{{ number_format($thongKe['tong']) }}</div>
                <div class="kh-stat-lbl">Tổng khách hàng</div>
            </div>
        </div>
        <div class="kh-stat-card">
            <div class="kh-stat-icon" style="background:#fff0f0;color:#e74c3c">
                <i class="fas fa-fire"></i>
            </div>
            <div>
                <div class="kh-stat-num">{{ number_format($thongKe['nong']) }}</div>
                <div class="kh-stat-lbl">🔥 Nóng</div>
            </div>
        </div>
        <div class="kh-stat-card">
            <div class="kh-stat-icon" style="background:#fff8f0;color:#e67e22">
                <i class="fas fa-sun"></i>
            </div>
            <div>
                <div class="kh-stat-num">{{ number_format($thongKe['am']) }}</div>
                <div class="kh-stat-lbl">🌤 Ấm</div>
            </div>
        </div>
        <div class="kh-stat-card">
            <div class="kh-stat-icon" style="background:#f5f5f5;color:#95a5a6">
                <i class="fas fa-snowflake"></i>
            </div>
            <div>
                <div class="kh-stat-num">{{ number_format($thongKe['lanh']) }}</div>
                <div class="kh-stat-lbl">❄️ Lạnh</div>
            </div>
        </div>
        <div class="kh-stat-card">
            <div class="kh-stat-icon" style="background:#e8f8f0;color:#27ae60">
                <i class="fas fa-check-circle"></i>
            </div>
            <div>
                <div class="kh-stat-num">{{ number_format($thongKe['kich_hoat']) }}</div>
                <div class="kh-stat-lbl">Đang kích hoạt</div>
            </div>
        </div>
    </div>

    {{-- ══ HEADER ══ --}}
    <div class="kh-header">
        <div>
            <h1 class="kh-title"><i class="fas fa-users"></i> Khách hàng</h1>
            <p class="kh-sub">Quản lý toàn bộ danh sách khách hàng & mức độ tiềm năng</p>
        </div>
        <a href="{{ route('nhanvien.admin.khach-hang.create') }}" class="kh-btn-add">
            <i class="fas fa-user-plus"></i> Thêm khách hàng
        </a>
    </div>

    {{-- Flash --}}
    @if (session('success'))
        <div class="kh-flash kh-flash-ok"><i class="fas fa-check-circle"></i> {!! session('success') !!}</div>
    @endif
    @if (session('error'))
        <div class="kh-flash kh-flash-err"><i class="fas fa-exclamation-circle"></i> {!! session('error') !!}</div>
    @endif

    {{-- ══ BỘ LỌC ══ --}}
    <div class="kh-filter-box">
        <form method="GET" id="khFilterForm">
            <div class="kh-filter-grid">

                <input type="text" name="tukhoa" class="kh-ctrl kh-search" value="{{ request('tukhoa') }}"
                    placeholder="🔍 Tìm tên, SĐT, email...">

                <select name="muc_do" class="kh-ctrl">
                    <option value="">Tất cả mức độ</option>
                    <option value="nong" @selected(request('muc_do') == 'nong')>🔥 Nóng</option>
                    <option value="am" @selected(request('muc_do') == 'am')>🌤 Ấm</option>
                    <option value="lanh" @selected(request('muc_do') == 'lanh')>❄️ Lạnh</option>
                </select>

                <select name="nguon" class="kh-ctrl">
                    <option value="">Tất cả nguồn</option>
                    @foreach (\App\Http\Controllers\Admin\KhachHangController::NGUON as $v => $info)
                        <option value="{{ $v }}" @selected(request('nguon') == $v)>{{ $info['label'] }}</option>
                    @endforeach
                </select>

                <select name="nhan_vien_id" class="kh-ctrl">
                    <option value="">Tất cả NV</option>
                    @foreach ($nhanViens as $nv)
                        <option value="{{ $nv->id }}" @selected(request('nhan_vien_id') == $nv->id)>
                            {{ $nv->ho_ten }}
                        </option>
                    @endforeach
                </select>

                <select name="kich_hoat" class="kh-ctrl">
                    <option value="">Tất cả</option>
                    <option value="1" @selected(request('kich_hoat') === '1')>✅ Kích hoạt</option>
                    <option value="0" @selected(request('kich_hoat') === '0')>🚫 Vô hiệu</option>
                </select>

                <select name="xac_thuc" class="kh-ctrl">
                    <option value="">Tất cả</option>
                    <option value="co" @selected(request('xac_thuc') == 'co')>✔ Đã xác thực</option>
                    <option value="chua" @selected(request('xac_thuc') == 'chua')>✘ Chưa xác thực</option>
                </select>

                <select name="sapxep" class="kh-ctrl">
                    <option value="moi_nhat" @selected(request('sapxep', 'moi_nhat') == 'moi_nhat')>📅 Mới nhất</option>
                    <option value="cu_nhat" @selected(request('sapxep') == 'cu_nhat')>📅 Cũ nhất</option>
                    <option value="ten_az" @selected(request('sapxep') == 'ten_az')>🔤 Tên A→Z</option>
                    <option value="lien_he_gan" @selected(request('sapxep') == 'lien_he_gan')>📞 LH gần nhất</option>
                    <option value="lich_nhieu" @selected(request('sapxep') == 'lich_nhieu')>📆 Lịch hẹn nhiều</option>
                </select>

                <div class="kh-filter-btns">
                    <button type="submit" class="kh-btn-filter">
                        <i class="fas fa-search"></i> Lọc
                    </button>
                    @if (request()->hasAny(['tukhoa', 'muc_do', 'nguon', 'nhan_vien_id', 'kich_hoat', 'xac_thuc', 'sapxep']))
                        <a href="{{ route('nhanvien.admin.khach-hang.index') }}" class="kh-btn-reset">
                            <i class="fas fa-times"></i> Xóa lọc
                        </a>
                    @endif
                </div>

            </div>
        </form>
    </div>

    {{-- ══ BẢNG DỮ LIỆU ══ --}}
    <div class="kh-data-box">
        <div class="kh-data-header">
            <span class="kh-result-info">
                @if ($khachHangs->total() > 0)
                    Hiển thị
                    <strong>{{ $khachHangs->firstItem() }}–{{ $khachHangs->lastItem() }}</strong>
                    / <strong>{{ number_format($khachHangs->total()) }}</strong> khách hàng
                @else
                    Không có kết quả
                @endif
            </span>
        </div>

        <div class="kh-tbl-wrap">
            <table class="kh-tbl">
                <thead>
                    <tr>
                        <th style="width:44px">#</th>
                        <th>Khách hàng</th>
                        <th style="width:130px">Liên hệ</th>
                        <th style="width:110px">Nguồn</th>
                        <th style="width:110px">Mức độ</th>
                        <th style="width:130px">NV phụ trách</th>
                        <th style="width:120px">Liên hệ cuối</th>
                        <th style="width:80px;text-align:center">Kích hoạt</th>
                        <th style="width:96px;text-align:center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($khachHangs as $kh)
                        @php
                            $md = \App\Http\Controllers\Admin\KhachHangController::MUC_DO[$kh->muc_do_tiem_nang] ?? [
                                'label' => $kh->muc_do_tiem_nang,
                                'color' => '#999',
                                'bg' => '#f5f5f5',
                            ];
                            $ng = \App\Http\Controllers\Admin\KhachHangController::NGUON[$kh->nguon_khach_hang] ?? [
                                'label' => $kh->nguon_khach_hang,
                                'icon' => 'fas fa-question',
                                'color' => '#999',
                            ];
                        @endphp
                        <tr>
                            {{-- STT --}}
                            <td style="color:#ccc;font-size:.8rem;text-align:center">
                                {{ $khachHangs->firstItem() + $loop->index }}
                            </td>

                            {{-- Tên + avatar --}}
                            <td>
                                <div class="kh-row">
                                    <div class="kh-avatar"
                                        style="background:{{ ['#3498db', '#e74c3c', '#27ae60', '#8e44ad', '#e67e22'][$kh->id % 5] }}">
                                        {{ mb_strtoupper(mb_substr($kh->ho_ten ?: 'K', 0, 1)) }}
                                    </div>
                                    <div class="kh-info">
                                        <a href="{{ route('nhanvien.admin.khach-hang.edit', $kh) }}" class="kh-name">
                                            {{ $kh->ho_ten ?: '(Chưa có tên)' }}
                                        </a>
                                        <div class="kh-chips">
                                            @if (isset($kh->lich_hens_count) && $kh->lich_hens_count > 0)
                                                <span class="kh-chip kh-chip-lich">
                                                    <i class="fas fa-calendar-check"></i>
                                                    {{ $kh->lich_hens_count }} lịch hẹn
                                                </span>
                                            @endif
                                            @if (isset($kh->yeu_thichs_count) && $kh->yeu_thichs_count > 0)
                                                <span class="kh-chip kh-chip-fav">
                                                    <i class="fas fa-heart"></i>
                                                    {{ $kh->yeu_thichs_count }} yêu thích
                                                </span>
                                            @endif
                                            @if ($kh->sdt_xac_thuc_at || $kh->email_xac_thuc_at)
                                                <span class="kh-chip kh-chip-ok">
                                                    <i class="fas fa-check-circle"></i> Đã xác thực
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Liên hệ --}}
                            <td>
                                @if ($kh->so_dien_thoai)
                                    <div class="kh-contact">
                                        <i class="fas fa-phone" style="color:#27ae60"></i>
                                        <a href="tel:{{ $kh->so_dien_thoai }}">{{ $kh->so_dien_thoai }}</a>
                                    </div>
                                @endif
                                @if ($kh->email)
                                    <div class="kh-contact">
                                        <i class="fas fa-envelope" style="color:#2d6a9f"></i>
                                        <span title="{{ $kh->email }}">{{ Str::limit($kh->email, 22) }}</span>
                                    </div>
                                @endif
                                @if (!$kh->so_dien_thoai && !$kh->email)
                                    <span style="color:#ddd;font-size:.8rem">—</span>
                                @endif
                            </td>

                            {{-- Nguồn --}}
                            <td>
                                <span class="kh-nguon" style="color:{{ $ng['color'] }}">
                                    <i class="{{ $ng['icon'] }}"></i> {{ $ng['label'] }}
                                </span>
                            </td>

                            {{-- Mức độ tiềm năng --}}
                            <td>
                                <span class="kh-mucdo" style="color:{{ $md['color'] }};background:{{ $md['bg'] }}"
                                    data-id="{{ $kh->id }}" onclick="openMucDoPopup(this)">
                                    {{ $md['label'] }}
                                    <i class="fas fa-caret-down" style="font-size:.6rem;margin-left:3px"></i>
                                </span>
                            </td>

                            {{-- NV phụ trách --}}
                            <td>
                                @if ($kh->nhanVienPhuTrach)
                                    <div style="font-size:.82rem;font-weight:600;color:#1a3c5e">
                                        {{ $kh->nhanVienPhuTrach->ho_ten }}
                                    </div>
                                @else
                                    <span style="color:#ddd;font-size:.8rem">Chưa phân công</span>
                                @endif
                            </td>

                            {{-- Liên hệ cuối --}}
                            <td>
                                @if ($kh->lien_he_cuoi_at)
                                    <div style="font-size:.8rem;color:#555">
                                        {{ $kh->lien_he_cuoi_at->format('d/m/Y H:i') }}
                                    </div>
                                    <div style="font-size:.72rem;color:#bbb">
                                        {{ $kh->lien_he_cuoi_at->diffForHumans() }}
                                    </div>
                                @else
                                    <span style="color:#ddd;font-size:.8rem">Chưa có</span>
                                @endif
                                <button type="button" class="kh-btn-lh" data-id="{{ $kh->id }}"
                                    title="Ghi nhận liên hệ ngay">
                                    <i class="fas fa-phone-alt"></i>
                                </button>
                            </td>

                            {{-- Toggle kích hoạt --}}
                            <td style="text-align:center">
                                <label class="kh-sw">
                                    <input type="checkbox" class="kh-toggle-kich" data-id="{{ $kh->id }}"
                                        {{ $kh->kich_hoat ? 'checked' : '' }}>
                                    <span class="kh-sw-track"><span class="kh-sw-thumb"></span></span>
                                </label>
                            </td>

                            {{-- Thao tác --}}
                            <td style="text-align:center">
                                <div class="kh-acts">
                                    <a href="{{ route('nhanvien.admin.khach-hang.show', $kh) }}"
                                        class="kh-act kh-act-view" title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('nhanvien.admin.khach-hang.edit', $kh) }}"
                                        class="kh-act kh-act-edit" title="Chỉnh sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('nhanvien.admin.khach-hang.destroy', $kh) }}" method="POST"
                                        style="display:inline;margin:0">
                                        @csrf @method('DELETE')
                                        <button type="button" class="kh-act kh-act-del kh-confirm-del"
                                            data-ten="{{ $kh->ten_hien_thi }}" title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="text-align:center;padding:60px 20px">
                                <div class="kh-empty">
                                    <i class="fas fa-users"></i>
                                    <p>Không tìm thấy khách hàng nào</p>
                                    @if (request()->hasAny(['tukhoa', 'muc_do', 'nguon', 'nhan_vien_id', 'kich_hoat']))
                                        <a href="{{ route('nhanvien.admin.khach-hang.index') }}">Xóa bộ lọc</a>
                                    @else
                                        <a href="{{ route('nhanvien.admin.khach-hang.create') }}">+ Thêm khách hàng đầu
                                            tiên</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ══ PHÂN TRANG ══ --}}
        @if ($khachHangs->hasPages())
            <div class="kh-pagi-wrap">
                <div class="kh-pagi-info">
                    Trang {{ $khachHangs->currentPage() }} / {{ $khachHangs->lastPage() }}
                </div>
                <div class="kh-pagi-links">
                    @if ($khachHangs->onFirstPage())
                        <span class="kh-pb kh-pb-dis"><i class="fas fa-angle-double-left"></i></span>
                        <span class="kh-pb kh-pb-dis"><i class="fas fa-angle-left"></i></span>
                    @else
                        <a href="{{ $khachHangs->url(1) }}" class="kh-pb"><i class="fas fa-angle-double-left"></i></a>
                        <a href="{{ $khachHangs->previousPageUrl() }}" class="kh-pb"><i
                                class="fas fa-angle-left"></i></a>
                    @endif

                    @php
                        $cur = $khachHangs->currentPage();
                        $last = $khachHangs->lastPage();
                        $start = max(1, $cur - 2);
                        $end = min($last, $cur + 2);
                    @endphp

                    @if ($start > 1)
                        <a href="{{ $khachHangs->url(1) }}" class="kh-pb">1</a>
                        @if ($start > 2)
                            <span class="kh-pdots">…</span>
                        @endif
                    @endif

                    @for ($p = $start; $p <= $end; $p++)
                        @if ($p == $cur)
                            <span class="kh-pb kh-pb-act">{{ $p }}</span>
                        @else
                            <a href="{{ $khachHangs->url($p) }}" class="kh-pb">{{ $p }}</a>
                        @endif
                    @endfor

                    @if ($end < $last)
                        @if ($end < $last - 1)
                            <span class="kh-pdots">…</span>
                        @endif
                        <a href="{{ $khachHangs->url($last) }}" class="kh-pb">{{ $last }}</a>
                    @endif

                    @if ($khachHangs->hasMorePages())
                        <a href="{{ $khachHangs->nextPageUrl() }}" class="kh-pb"><i class="fas fa-angle-right"></i></a>
                        <a href="{{ $khachHangs->url($last) }}" class="kh-pb"><i
                                class="fas fa-angle-double-right"></i></a>
                    @else
                        <span class="kh-pb kh-pb-dis"><i class="fas fa-angle-right"></i></span>
                        <span class="kh-pb kh-pb-dis"><i class="fas fa-angle-double-right"></i></span>
                    @endif
                </div>
            </div>
        @endif

    </div>{{-- end kh-data-box --}}

    {{-- ══ POPUP ĐỔI MỨC ĐỘ ══ --}}
    <div id="mucDoPopup"
        style="display:none;position:fixed;z-index:9999;background:#fff;border:1px solid #eee;border-radius:12px;box-shadow:0 12px 40px rgba(0,0,0,.14);min-width:155px;padding:5px">
        <div class="kh-md-item" data-val="nong" style="color:#e74c3c">🔥 Nóng</div>
        <div class="kh-md-item" data-val="am" style="color:#e67e22">🌤 Ấm</div>
        <div class="kh-md-item" data-val="lanh" style="color:#95a5a6">❄️ Lạnh</div>
    </div>

@endsection

@push('styles')
    <style>
        /* STAT */
        .kh-stat-row {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 14px;
            margin-bottom: 22px
        }

        @media(max-width:1024px) {
            .kh-stat-row {
                grid-template-columns: repeat(3, 1fr)
            }
        }

        @media(max-width:640px) {
            .kh-stat-row {
                grid-template-columns: repeat(2, 1fr)
            }
        }

        .kh-stat-card {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #f0f2f5;
            padding: 14px 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .04)
        }

        .kh-stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0
        }

        .kh-stat-num {
            font-size: 1.55rem;
            font-weight: 800;
            color: #1a3c5e;
            line-height: 1
        }

        .kh-stat-lbl {
            font-size: .74rem;
            color: #aaa;
            margin-top: 3px
        }

        /* HEADER */
        .kh-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 18px;
            flex-wrap: wrap;
            gap: 12px
        }

        .kh-title {
            font-size: 1.35rem;
            font-weight: 700;
            color: #1a3c5e;
            margin: 0 0 4px;
            display: flex;
            align-items: center;
            gap: 9px
        }

        .kh-title i {
            color: #FF8C42
        }

        .kh-sub {
            color: #aaa;
            font-size: .83rem;
            margin: 0
        }

        .kh-btn-add {
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

        .kh-btn-add:hover {
            transform: translateY(-1px);
            color: #fff
        }

        /* FLASH */
        .kh-flash {
            border-radius: 10px;
            padding: 12px 18px;
            font-size: .875rem;
            font-weight: 500;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px
        }

        .kh-flash-ok {
            background: #e8f8f0;
            border: 1px solid #b7e4cb;
            color: #1a7a45
        }

        .kh-flash-err {
            background: #fff5f5;
            border: 1px solid #fcc;
            color: #c0392b
        }

        /* FILTER */
        .kh-filter-box {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #f0f2f5;
            padding: 16px 18px;
            margin-bottom: 16px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .04)
        }

        .kh-filter-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            align-items: center
        }

        .kh-ctrl {
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
            font-family: inherit
        }

        .kh-ctrl:focus {
            border-color: #FF8C42;
            background: #fff
        }

        .kh-ctrl.kh-search {
            flex: 1;
            min-width: 200px
        }

        select.kh-ctrl {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath fill='%23aaa' d='M5 6L0 0h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-color: #fafafa;
            padding-right: 28px
        }

        .kh-filter-btns {
            display: flex;
            gap: 8px
        }

        .kh-btn-filter {
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

        .kh-btn-filter:hover {
            background: #0f2742
        }

        .kh-btn-reset {
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

        /* DATA BOX */
        .kh-data-box {
            background: #fff;
            border-radius: 14px;
            border: 1px solid #f0f2f5;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .06);
            overflow: hidden
        }

        .kh-data-header {
            padding: 14px 20px;
            border-bottom: 1px solid #f5f5f5
        }

        .kh-result-info {
            font-size: .82rem;
            color: #999
        }

        .kh-result-info strong {
            color: #1a3c5e
        }

        .kh-tbl-wrap {
            overflow-x: auto
        }

        .kh-tbl {
            width: 100%;
            border-collapse: collapse;
            min-width: 860px
        }

        .kh-tbl thead tr {
            background: #f8faff
        }

        .kh-tbl th {
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

        .kh-tbl td {
            padding: 13px 14px;
            border-bottom: 1px solid #f5f6fa;
            vertical-align: middle;
            font-size: .855rem;
            color: #333
        }

        .kh-tbl tbody tr:last-child td {
            border-bottom: none
        }

        .kh-tbl tbody tr:hover {
            background: #fdfeff
        }

        /* KH ROW */
        .kh-row {
            display: flex;
            align-items: center;
            gap: 10px
        }

        .kh-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            font-weight: 700;
            color: #fff;
            flex-shrink: 0
        }

        .kh-info {
            flex: 1;
            min-width: 0
        }

        .kh-name {
            font-weight: 600;
            color: #1a3c5e;
            font-size: .875rem;
            text-decoration: none;
            display: block;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis
        }

        .kh-name:hover {
            color: #FF8C42
        }

        .kh-chips {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
            margin-top: 4px
        }

        .kh-chip {
            font-size: .68rem;
            font-weight: 600;
            padding: .1rem .4rem;
            border-radius: 5px;
            display: inline-flex;
            align-items: center;
            gap: 3px
        }

        .kh-chip-lich {
            background: #fff3e0;
            color: #e67e22
        }

        .kh-chip-fav {
            background: #fff0f0;
            color: #e74c3c
        }

        .kh-chip-ok {
            background: #e8f8f0;
            color: #27ae60
        }

        /* CONTACT */
        .kh-contact {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: .8rem;
            margin-bottom: 3px
        }

        .kh-contact:last-child {
            margin-bottom: 0
        }

        .kh-contact a {
            color: #1a3c5e;
            text-decoration: none
        }

        .kh-contact a:hover {
            color: #FF8C42
        }

        /* NGUỒN */
        .kh-nguon {
            font-size: .78rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 4px
        }

        /* MỨC ĐỘ */
        .kh-mucdo {
            display: inline-flex;
            align-items: center;
            font-size: .76rem;
            font-weight: 700;
            padding: .25rem .65rem;
            border-radius: 20px;
            cursor: pointer;
            user-select: none;
            white-space: nowrap;
            transition: opacity .15s
        }

        .kh-mucdo:hover {
            opacity: .8
        }

        .kh-md-item {
            padding: .55rem 1rem;
            font-size: .84rem;
            border-radius: 8px;
            cursor: pointer;
            transition: background .12s;
            font-weight: 600
        }

        .kh-md-item:hover {
            background: #f0f6ff
        }

        /* BUTTON LIÊN HỆ */
        .kh-btn-lh {
            background: #e8f8f0;
            color: #27ae60;
            border: none;
            border-radius: 6px;
            padding: 3px 7px;
            font-size: .7rem;
            cursor: pointer;
            margin-top: 4px;
            transition: all .15s
        }

        .kh-btn-lh:hover {
            background: #27ae60;
            color: #fff
        }

        /* TOGGLE */
        .kh-sw {
            position: relative;
            cursor: pointer;
            display: inline-block
        }

        .kh-sw input {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0
        }

        .kh-sw-track {
            display: block;
            width: 42px;
            height: 24px;
            background: #dde0e8;
            border-radius: 12px;
            transition: background .25s;
            position: relative
        }

        .kh-sw input:checked~.kh-sw-track {
            background: #27ae60
        }

        .kh-sw-thumb {
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

        .kh-sw input:checked~.kh-sw-track .kh-sw-thumb {
            transform: translateX(18px)
        }

        /* ACTIONS */
        .kh-acts {
            display: flex;
            gap: 5px;
            justify-content: center
        }

        .kh-act {
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
            transition: all .15s
        }

        .kh-act-view {
            background: #f0fff4;
            color: #27ae60
        }

        .kh-act-view:hover {
            background: #27ae60;
            color: #fff
        }

        .kh-act-edit {
            background: #eef3ff;
            color: #2d6a9f
        }

        .kh-act-edit:hover {
            background: #2d6a9f;
            color: #fff
        }

        .kh-act-del {
            background: #fff0f0;
            color: #e74c3c
        }

        .kh-act-del:hover {
            background: #e74c3c;
            color: #fff
        }

        /* EMPTY */
        .kh-empty {
            text-align: center
        }

        .kh-empty i {
            font-size: 3rem;
            color: #e8ebf5;
            display: block;
            margin-bottom: 12px
        }

        .kh-empty p {
            color: #bbb;
            font-size: .95rem;
            margin: 0 0 10px
        }

        .kh-empty a {
            color: #FF8C42;
            font-weight: 600;
            font-size: .875rem;
            text-decoration: none
        }

        /* PAGI */
        .kh-pagi-wrap {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 20px;
            border-top: 1px solid #f5f6fa;
            flex-wrap: wrap;
            gap: 10px
        }

        .kh-pagi-info {
            font-size: .8rem;
            color: #aaa
        }

        .kh-pagi-links {
            display: flex;
            align-items: center;
            gap: 4px;
            flex-wrap: wrap
        }

        .kh-pb {
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

        .kh-pb:hover:not(.kh-pb-act):not(.kh-pb-dis) {
            background: #e8f0ff;
            color: #1a3c5e;
            border-color: #c8daf5
        }

        .kh-pb-act {
            background: linear-gradient(135deg, #1a3c5e, #2d6a9f) !important;
            color: #fff !important;
            box-shadow: 0 3px 10px rgba(26, 60, 94, .25);
            cursor: default
        }

        .kh-pb-dis {
            color: #d0d8e8 !important;
            background: #fafafa !important;
            cursor: not-allowed;
            pointer-events: none
        }

        .kh-pdots {
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

        // ── 1. Toggle kích hoạt ──
        document.querySelectorAll('.kh-toggle-kich').forEach(chk => {
            chk.addEventListener('change', function() {
                const id = this.dataset.id,
                    self = this;
                fetch('/nhan-vien/admin/khach-hang/' + id + '/toggle', {
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

        // ── 2. Ghi nhận liên hệ cuối ──
        document.querySelectorAll('.kh-btn-lh').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;
                const row = this.closest('tr');
                fetch('/nhan-vien/admin/khach-hang/' + id + '/lien-he-cuoi', {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': CSRF,
                        'Accept': 'application/json'
                    }
                }).then(r => r.json()).then(data => {
                    if (data.ok) {
                        const cell = this.closest('td');
                        cell.querySelector('div:first-child') && (cell.querySelector(
                            'div:first-child').textContent = data.time);
                        this.style.background = '#27ae60';
                        this.style.color = '#fff';
                        setTimeout(() => {
                            this.style.background = '';
                            this.style.color = '';
                        }, 2000);
                    }
                });
            });
        });

        // ── 3. Popup đổi mức độ tiềm năng ──
        const mdPopup = document.getElementById('mucDoPopup');
        let mdTargetId = null,
            mdTargetBadge = null;

        function openMucDoPopup(badge) {
            mdTargetId = badge.dataset.id;
            mdTargetBadge = badge;
            const rect = badge.getBoundingClientRect();
            let left = rect.left + window.scrollX;
            const top = rect.bottom + window.scrollY + 6;
            if (left + 155 > window.innerWidth - 10) left = window.innerWidth - 165;
            mdPopup.style.left = left + 'px';
            mdPopup.style.top = top + 'px';
            mdPopup.style.display = 'block';
        }

        document.querySelectorAll('.kh-md-item').forEach(item => {
            item.addEventListener('click', function() {
                const val = this.dataset.val;
                mdPopup.style.display = 'none';
                fetch('/nhan-vien/admin/khach-hang/' + mdTargetId + '/muc-do', {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': CSRF,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        muc_do: val
                    })
                }).then(r => r.json()).then(data => {
                    if (data.ok) window.location.reload();
                });
            });
        });

        document.addEventListener('click', e => {
            if (mdPopup.style.display === 'block' &&
                !mdPopup.contains(e.target) &&
                !e.target.closest('.kh-mucdo')) {
                mdPopup.style.display = 'none';
            }
        });

        // ── 4. Confirm xóa ──
        document.querySelectorAll('.kh-confirm-del').forEach(btn => {
            btn.addEventListener('click', function() {
                if (confirm('Xóa khách hàng "' + this.dataset.ten + '"?\nThao tác không thể hoàn tác!')) {
                    this.closest('form').submit();
                }
            });
        });

        // ── 5. Auto submit filter khi đổi select ──
        document.querySelectorAll('#khFilterForm select').forEach(s => {
            s.addEventListener('change', () => document.getElementById('khFilterForm').submit());
        });
    </script>
@endpush
