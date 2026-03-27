@extends('admin.layouts.master')
@section('title', 'Quản lý Nhân viên')

@section('page_title', 'Bất động sản')
@section('page_parent', 'Quản lý')   {{-- tuỳ chọn, có thể bỏ --}}


@section('content')

    {{-- ══ THỐNG KÊ ══ --}}
    <div class="nv-stat-row">
        <div class="nv-stat-card">
            <div class="nv-stat-icon" style="background:#e8f0ff;color:#2d6a9f">
                <i class="fas fa-users"></i>
            </div>
            <div>
                <div class="nv-stat-num">{{ number_format($thongKe['tong']) }}</div>
                <div class="nv-stat-lbl">Tổng nhân viên</div>
            </div>
        </div>
        <div class="nv-stat-card">
            <div class="nv-stat-icon" style="background:#fff0f0;color:#e74c3c">
                <i class="fas fa-crown"></i>
            </div>
            <div>
                <div class="nv-stat-num">{{ number_format($thongKe['admin']) }}</div>
                <div class="nv-stat-lbl">Admin</div>
            </div>
        </div>
        <div class="nv-stat-card">
            <div class="nv-stat-icon" style="background:#f5eeff;color:#8e44ad">
                <i class="fas fa-building"></i>
            </div>
            <div>
                <div class="nv-stat-num">{{ number_format($thongKe['nguon_hang']) }}</div>
                <div class="nv-stat-lbl">Nguồn hàng</div>
            </div>
        </div>
        <div class="nv-stat-card">
            <div class="nv-stat-icon" style="background:#e8f4fd;color:#2d6a9f">
                <i class="fas fa-user-tie"></i>
            </div>
            <div>
                <div class="nv-stat-num">{{ number_format($thongKe['sale']) }}</div>
                <div class="nv-stat-lbl">Sale</div>
            </div>
        </div>
        <div class="nv-stat-card">
            <div class="nv-stat-icon" style="background:#e8f8f0;color:#27ae60">
                <i class="fas fa-check-circle"></i>
            </div>
            <div>
                <div class="nv-stat-num">{{ number_format($thongKe['kich_hoat']) }}</div>
                <div class="nv-stat-lbl">Đang hoạt động</div>
            </div>
        </div>
    </div>

    {{-- ══ HEADER ══ --}}
    <div class="nv-header">
        <div>
            <h1 class="nv-title"><i class="fas fa-id-badge"></i> Nhân viên</h1>
            <p class="nv-sub">Quản lý tài khoản, vai trò & phân quyền nhân viên</p>
        </div>
        <a href="{{ route('nhanvien.admin.nhan-vien.create') }}" class="nv-btn-add">
            <i class="fas fa-user-plus"></i> Thêm nhân viên
        </a>
    </div>

    {{-- Flash --}}
    @if (session('success'))
        <div class="nv-flash nv-flash-ok"><i class="fas fa-check-circle"></i> {!! session('success') !!}</div>
    @endif
    @if (session('error'))
        <div class="nv-flash nv-flash-err"><i class="fas fa-exclamation-circle"></i> {!! session('error') !!}</div>
    @endif

    {{-- ══ BỘ LỌC ══ --}}
    <div class="nv-filter-box">
        <form method="GET" id="nvFilterForm">
            <div class="nv-filter-row">

                <input type="text" name="tukhoa" class="nv-ctrl nv-search" value="{{ request('tukhoa') }}"
                    placeholder="🔍 Tìm tên, email, số điện thoại...">

                <select name="vai_tro" class="nv-ctrl">
                    <option value="">Tất cả vai trò</option>
                    @foreach (\App\Models\NhanVien::VAI_TRO as $v => $info)
                        <option value="{{ $v }}" @selected(request('vai_tro') == $v)>
                            {{ $info['label'] }}
                        </option>
                    @endforeach
                </select>

                <select name="kich_hoat" class="nv-ctrl">
                    <option value="">Tất cả trạng thái</option>
                    <option value="1" @selected(request('kich_hoat') === '1')>✅ Đang hoạt động</option>
                    <option value="0" @selected(request('kich_hoat') === '0')>🚫 Vô hiệu hóa</option>
                </select>

                <select name="sapxep" class="nv-ctrl">
                    <option value="moi_nhat" @selected(request('sapxep', 'moi_nhat') == 'moi_nhat')>📅 Mới nhất</option>
                    <option value="ten_az" @selected(request('sapxep') == 'ten_az')>🔤 Tên A → Z</option>
                    <option value="ten_za" @selected(request('sapxep') == 'ten_za')>🔤 Tên Z → A</option>
                    <option value="dang_nhap" @selected(request('sapxep') == 'dang_nhap')>🕐 Đăng nhập gần nhất</option>
                    <option value="bds_nhieu" @selected(request('sapxep') == 'bds_nhieu')>🏠 BĐS nhiều nhất</option>
                </select>

                <div class="nv-filter-btns">
                    <button type="submit" class="nv-btn-filter">
                        <i class="fas fa-search"></i> Lọc
                    </button>
                    @if (request()->hasAny(['tukhoa', 'vai_tro', 'kich_hoat', 'sapxep']))
                        <a href="{{ route('nhanvien.admin.nhan-vien.index') }}" class="nv-btn-reset">
                            <i class="fas fa-times"></i> Xóa lọc
                        </a>
                    @endif
                </div>

            </div>
        </form>
    </div>

    {{-- ══ BẢNG DỮ LIỆU ══ --}}
    <div class="nv-data-box">

        <div class="nv-data-header">
            <span class="nv-result-info">
                @if ($nhanViens->total() > 0)
                    Hiển thị
                    <strong>{{ $nhanViens->firstItem() }}–{{ $nhanViens->lastItem() }}</strong>
                    / <strong>{{ number_format($nhanViens->total()) }}</strong> nhân viên
                @else
                    Không có kết quả
                @endif
            </span>
        </div>

        <div class="nv-tbl-wrap">
            <table class="nv-tbl">
                <thead>
                    <tr>
                        <th style="width:44px">#</th>
                        <th>Nhân viên</th>
                        <th style="width:200px">Liên hệ</th>
                        <th style="width:120px">Vai trò</th>
                        <th style="width:100px;text-align:center">BĐS</th>
                        <th style="width:100px;text-align:center">Khách hàng</th>
                        <th style="width:140px">Đăng nhập cuối</th>
                        <th style="width:90px;text-align:center">Kích hoạt</th>
                        <th style="width:110px;text-align:center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($nhanViens as $nv)
                        @php
                            $vtInfo = \App\Models\NhanVien::VAI_TRO[$nv->vai_tro] ?? [
                                'label' => $nv->vai_tro,
                                'color' => '#999',
                                'bg' => '#f5f5f5',
                                'icon' => 'fas fa-user',
                            ];
                            $isMe = $nv->id === auth('nhanvien')->id();
                        @endphp
                        <tr class="{{ $isMe ? 'nv-row-me' : '' }}">

                            {{-- STT --}}
                            <td style="color:#ccc;font-size:.8rem;text-align:center">
                                {{ $nhanViens->firstItem() + $loop->index }}
                            </td>

                            {{-- Avatar + Tên --}}
                            <td>
                                <div class="nv-person">
                                    <div class="nv-avatar-wrap">
                                        <img src="{{ $nv->anh_dai_dien_url }}" alt="{{ $nv->ho_ten }}"
                                            class="nv-avatar">
                                        <span class="nv-status-dot {{ $nv->kich_hoat ? 'dot-on' : 'dot-off' }}"></span>
                                    </div>
                                    <div class="nv-pinfo">
                                        <a href="{{ route('nhanvien.admin.nhan-vien.edit', $nv) }}" class="nv-name">
                                            {{ $nv->ho_ten }}
                                            @if ($isMe)
                                                <span class="nv-me-badge">Bạn</span>
                                            @endif
                                        </a>
                                        <div style="font-size:.73rem;color:#bbb">
                                            Tham gia {{ $nv->created_at->format('d/m/Y') }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Liên hệ --}}
                            <td>
                                <div class="nv-contact">
                                    <i class="fas fa-envelope" style="color:#2d6a9f"></i>
                                    <span title="{{ $nv->email }}">{{ Str::limit($nv->email, 28) }}</span>
                                </div>
                                @if ($nv->so_dien_thoai)
                                    <div class="nv-contact">
                                        <i class="fas fa-phone" style="color:#27ae60"></i>
                                        <a href="tel:{{ $nv->so_dien_thoai }}">{{ $nv->so_dien_thoai }}</a>
                                    </div>
                                @endif
                            </td>

                            {{-- Vai trò --}}
                            <td>
                                <span class="nv-badge"
                                    style="color:{{ $vtInfo['color'] }};background:{{ $vtInfo['bg'] }}">
                                    <i class="{{ $vtInfo['icon'] }}"></i>
                                    {{ $vtInfo['label'] }}
                                </span>
                            </td>

                            {{-- Số BĐS --}}
                            <td style="text-align:center">
                                @if (isset($nv->bat_dong_san_phu_trach_count) && $nv->bat_dong_san_phu_trach_count > 0)
                                    <a href="#" class="nv-count-link" style="color:#e67e22">
                                        {{ number_format($nv->bat_dong_san_phu_trach_count) }}
                                    </a>
                                @else
                                    <span style="color:#ddd">—</span>
                                @endif


                            <td style="text-align:center">
                                @if (isset($nv->khach_hang_phu_trach_count) && $nv->khach_hang_phu_trach_count > 0)
                                    <a href="{{ route('nhanvien.admin.khach-hang.index', ['nhan_vien_id' => $nv->id]) }}"
                                        class="nv-count-link" style="color:#2d6a9f">
                                        {{ number_format($nv->khach_hang_phu_trach_count) }}
                                    </a>
                                @else
                                    <span style="color:#ddd">—</span>
                                @endif
                            </td>

                            {{-- Đăng nhập cuối --}}
                            <td>
                                @if ($nv->dang_nhap_cuoi_at)
                                    <div style="font-size:.8rem;color:#555">
                                        {{ $nv->dang_nhap_cuoi_at->format('d/m/Y H:i') }}
                                    </div>
                                    <div style="font-size:.72rem;color:#bbb">
                                        {{ $nv->dang_nhap_cuoi_at->diffForHumans() }}
                                    </div>
                                @else
                                    <span style="color:#ddd;font-size:.8rem">Chưa đăng nhập</span>
                                @endif
                            </td>

                            {{-- Toggle kích hoạt --}}
                            <td style="text-align:center">
                                <label class="nv-sw {{ $isMe ? 'nv-sw-disabled' : '' }}"
                                    title="{{ $isMe ? 'Không thể tự vô hiệu hóa' : '' }}">
                                    <input type="checkbox" class="nv-toggle" data-id="{{ $nv->id }}"
                                        {{ $nv->kich_hoat ? 'checked' : '' }} {{ $isMe ? 'disabled' : '' }}>
                                    <span class="nv-sw-track"><span class="nv-sw-thumb"></span></span>
                                </label>
                            </td>

                            {{-- Thao tác --}}
                            <td style="text-align:center">
                                <div class="nv-acts">
                                    <a href="{{ route('nhanvien.admin.nhan-vien.show', $nv) }}"
                                        class="nv-act nv-act-view" title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('nhanvien.admin.nhan-vien.edit', $nv) }}"
                                        class="nv-act nv-act-edit" title="Chỉnh sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="nv-act nv-act-pw" data-id="{{ $nv->id }}"
                                        data-ten="{{ $nv->ho_ten }}" title="Đổi mật khẩu">
                                        <i class="fas fa-key"></i>
                                    </button>
                                    @if (!$isMe)
                                        <form action="{{ route('nhanvien.admin.nhan-vien.destroy', $nv) }}"
                                            method="POST" style="display:inline;margin:0">
                                            @csrf @method('DELETE')
                                            <button type="button" class="nv-act nv-act-del nv-confirm-del"
                                                data-ten="{{ $nv->ho_ten }}" title="Xóa">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="text-align:center;padding:60px 20px">
                                <div class="nv-empty">
                                    <i class="fas fa-id-badge"></i>
                                    <p>Không tìm thấy nhân viên nào</p>
                                    @if (request()->hasAny(['tukhoa', 'vai_tro', 'kich_hoat']))
                                        <a href="{{ route('nhanvien.admin.nhan-vien.index') }}">Xóa bộ lọc</a>
                                    @else
                                        <a href="{{ route('nhanvien.admin.nhan-vien.create') }}">+ Thêm nhân viên đầu
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
        @if ($nhanViens->hasPages())
            <div class="nv-pagi-wrap">
                <div class="nv-pagi-info">
                    Trang {{ $nhanViens->currentPage() }} / {{ $nhanViens->lastPage() }}
                </div>
                <div class="nv-pagi-links">
                    @if ($nhanViens->onFirstPage())
                        <span class="nv-pb nv-pb-dis"><i class="fas fa-angle-double-left"></i></span>
                        <span class="nv-pb nv-pb-dis"><i class="fas fa-angle-left"></i></span>
                    @else
                        <a href="{{ $nhanViens->url(1) }}" class="nv-pb"><i class="fas fa-angle-double-left"></i></a>
                        <a href="{{ $nhanViens->previousPageUrl() }}" class="nv-pb"><i
                                class="fas fa-angle-left"></i></a>
                    @endif

                    @php
                        $cur = $nhanViens->currentPage();
                        $last = $nhanViens->lastPage();
                        $start = max(1, $cur - 2);
                        $end = min($last, $cur + 2);
                    @endphp

                    @if ($start > 1)
                        <a href="{{ $nhanViens->url(1) }}" class="nv-pb">1</a>
                        @if ($start > 2)
                            <span class="nv-pdots">…</span>
                        @endif
                    @endif

                    @for ($p = $start; $p <= $end; $p++)
                        @if ($p == $cur)
                            <span class="nv-pb nv-pb-act">{{ $p }}</span>
                        @else
                            <a href="{{ $nhanViens->url($p) }}" class="nv-pb">{{ $p }}</a>
                        @endif
                    @endfor

                    @if ($end < $last)
                        @if ($end < $last - 1)
                            <span class="nv-pdots">…</span>
                        @endif
                        <a href="{{ $nhanViens->url($last) }}" class="nv-pb">{{ $last }}</a>
                    @endif

                    @if ($nhanViens->hasMorePages())
                        <a href="{{ $nhanViens->nextPageUrl() }}" class="nv-pb"><i class="fas fa-angle-right"></i></a>
                        <a href="{{ $nhanViens->url($last) }}" class="nv-pb"><i
                                class="fas fa-angle-double-right"></i></a>
                    @else
                        <span class="nv-pb nv-pb-dis"><i class="fas fa-angle-right"></i></span>
                        <span class="nv-pb nv-pb-dis"><i class="fas fa-angle-double-right"></i></span>
                    @endif
                </div>
            </div>
        @endif

    </div>{{-- end nv-data-box --}}

    {{-- ══ MODAL ĐỔI MẬT KHẨU ══ --}}
    <div id="pwModal" class="nv-modal-overlay" style="display:none">
        <div class="nv-modal">
            <div class="nv-modal-head">
                <span><i class="fas fa-key"></i> Đổi mật khẩu — <span id="pwModalTen"></span></span>
                <button type="button" onclick="closePwModal()" class="nv-modal-close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="nv-modal-body">
                <div class="nv-fg" style="margin-bottom:14px">
                    <label class="nv-fl req">Mật khẩu mới</label>
                    <div class="nv-pw-wrap">
                        <input type="password" id="pwNew" class="nv-fi" placeholder="Tối thiểu 6 ký tự"
                            autocomplete="new-password">
                        <button type="button" class="nv-pw-eye" onclick="toggleModalPwd('pwNew','eyeNew')">
                            <i class="fas fa-eye" id="eyeNew"></i>
                        </button>
                    </div>
                </div>
                <div class="nv-fg">
                    <label class="nv-fl req">Xác nhận mật khẩu</label>
                    <div class="nv-pw-wrap">
                        <input type="password" id="pwConfirm" class="nv-fi" placeholder="Nhập lại mật khẩu mới"
                            autocomplete="new-password">
                        <button type="button" class="nv-pw-eye" onclick="toggleModalPwd('pwConfirm','eyeConfirm')">
                            <i class="fas fa-eye" id="eyeConfirm"></i>
                        </button>
                    </div>
                </div>
                <div id="pwError" style="color:#e74c3c;font-size:.8rem;margin-top:8px;display:none">
                    <i class="fas fa-exclamation-circle"></i>
                    <span id="pwErrorMsg"></span>
                </div>
            </div>
            <div class="nv-modal-foot">
                <button type="button" onclick="closePwModal()" class="nv-btn-modal-cancel">
                    Hủy
                </button>
                <button type="button" id="pwSubmitBtn" onclick="submitDoiMatKhau()" class="nv-btn-modal-ok">
                    <i class="fas fa-save"></i> Lưu mật khẩu
                </button>
            </div>
        </div>
    </div>

@endsection

@push('styles')
    <style>
        /* ─── STAT ─── */
        .nv-stat-row {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 14px;
            margin-bottom: 22px
        }

        @media(max-width:1100px) {
            .nv-stat-row {
                grid-template-columns: repeat(3, 1fr)
            }
        }

        @media(max-width:640px) {
            .nv-stat-row {
                grid-template-columns: repeat(2, 1fr)
            }
        }

        .nv-stat-card {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #f0f2f5;
            padding: 14px 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .04)
        }

        .nv-stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0
        }

        .nv-stat-num {
            font-size: 1.55rem;
            font-weight: 800;
            color: #1a3c5e;
            line-height: 1
        }

        .nv-stat-lbl {
            font-size: .74rem;
            color: #aaa;
            margin-top: 3px
        }

        /* ─── HEADER ─── */
        .nv-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 18px;
            flex-wrap: wrap;
            gap: 12px
        }

        .nv-title {
            font-size: 1.35rem;
            font-weight: 700;
            color: #1a3c5e;
            margin: 0 0 4px;
            display: flex;
            align-items: center;
            gap: 9px
        }

        .nv-title i {
            color: #FF8C42
        }

        .nv-sub {
            color: #aaa;
            font-size: .83rem;
            margin: 0
        }

        .nv-btn-add {
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

        .nv-btn-add:hover {
            transform: translateY(-1px);
            color: #fff
        }

        /* ─── FLASH ─── */
        .nv-flash {
            border-radius: 10px;
            padding: 12px 18px;
            font-size: .875rem;
            font-weight: 500;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px
        }

        .nv-flash-ok {
            background: #e8f8f0;
            border: 1px solid #b7e4cb;
            color: #1a7a45
        }

        .nv-flash-err {
            background: #fff5f5;
            border: 1px solid #fcc;
            color: #c0392b
        }

        /* ─── FILTER ─── */
        .nv-filter-box {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #f0f2f5;
            padding: 16px 18px;
            margin-bottom: 16px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .04)
        }

        .nv-filter-row {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            align-items: center
        }

        .nv-ctrl {
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

        .nv-ctrl:focus {
            border-color: #FF8C42;
            background: #fff
        }

        .nv-ctrl.nv-search {
            flex: 1;
            min-width: 200px
        }

        select.nv-ctrl {
            appearance: none;
            cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath fill='%23aaa' d='M5 6L0 0h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-color: #fafafa;
            padding-right: 28px
        }

        .nv-filter-btns {
            display: flex;
            gap: 8px
        }

        .nv-btn-filter {
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

        .nv-btn-filter:hover {
            background: #0f2742
        }

        .nv-btn-reset {
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

        /* ─── DATA BOX ─── */
        .nv-data-box {
            background: #fff;
            border-radius: 14px;
            border: 1px solid #f0f2f5;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .06);
            overflow: hidden
        }

        .nv-data-header {
            padding: 14px 20px;
            border-bottom: 1px solid #f5f5f5
        }

        .nv-result-info {
            font-size: .82rem;
            color: #999
        }

        .nv-result-info strong {
            color: #1a3c5e
        }

        .nv-tbl-wrap {
            overflow-x: auto
        }

        .nv-tbl {
            width: 100%;
            border-collapse: collapse;
            min-width: 900px
        }

        .nv-tbl thead tr {
            background: #f8faff
        }

        .nv-tbl th {
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

        .nv-tbl td {
            padding: 13px 14px;
            border-bottom: 1px solid #f5f6fa;
            vertical-align: middle;
            font-size: .855rem;
            color: #333
        }

        .nv-tbl tbody tr:last-child td {
            border-bottom: none
        }

        .nv-tbl tbody tr:hover {
            background: #fdfeff
        }

        .nv-row-me {
            background: #fffdf5 !important
        }

        .nv-row-me:hover {
            background: #fffaee !important
        }

        /* ─── PERSON ─── */
        .nv-person {
            display: flex;
            align-items: center;
            gap: 10px
        }

        .nv-avatar-wrap {
            position: relative;
            flex-shrink: 0
        }

        .nv-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #f0f2f5
        }

        .nv-status-dot {
            position: absolute;
            bottom: 1px;
            right: 1px;
            width: 11px;
            height: 11px;
            border-radius: 50%;
            border: 2px solid #fff
        }

        .dot-on {
            background: #27ae60
        }

        .dot-off {
            background: #e74c3c
        }

        .nv-pinfo {
            flex: 1;
            min-width: 0
        }

        .nv-name {
            font-weight: 600;
            color: #1a3c5e;
            font-size: .875rem;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 5px;
            white-space: nowrap
        }

        .nv-name:hover {
            color: #FF8C42
        }

        .nv-me-badge {
            font-size: .65rem;
            font-weight: 700;
            background: linear-gradient(135deg, #FF8C42, #f5a623);
            color: #fff;
            padding: .1rem .4rem;
            border-radius: 5px;
            letter-spacing: .3px
        }

        /* ─── CONTACT ─── */
        .nv-contact {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: .8rem;
            margin-bottom: 2px
        }

        .nv-contact:last-child {
            margin-bottom: 0
        }

        .nv-contact a {
            color: #1a3c5e;
            text-decoration: none
        }

        .nv-contact a:hover {
            color: #FF8C42
        }

        /* ─── BADGE ─── */
        .nv-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: .75rem;
            font-weight: 700;
            padding: .25rem .6rem;
            border-radius: 20px;
            white-space: nowrap
        }

        /* ─── COUNT LINK ─── */
        .nv-count-link {
            font-weight: 700;
            font-size: .9rem;
            text-decoration: none;
            padding: .2rem .5rem;
            border-radius: 6px;
            background: #fafafa;
            transition: background .15s
        }

        .nv-count-link:hover {
            background: #f0f4ff
        }

        /* ─── TOGGLE ─── */
        .nv-sw {
            position: relative;
            cursor: pointer;
            display: inline-block
        }

        .nv-sw.nv-sw-disabled {
            opacity: .5;
            cursor: not-allowed
        }

        .nv-sw input {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0
        }

        .nv-sw-track {
            display: block;
            width: 42px;
            height: 24px;
            background: #dde0e8;
            border-radius: 12px;
            transition: background .25s;
            position: relative
        }

        .nv-sw input:checked~.nv-sw-track {
            background: #27ae60
        }

        .nv-sw-thumb {
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

        .nv-sw input:checked~.nv-sw-track .nv-sw-thumb {
            transform: translateX(18px)
        }

        /* ─── ACTIONS ─── */
        .nv-acts {
            display: flex;
            gap: 4px;
            justify-content: center;
            flex-wrap: wrap
        }

        .nv-act {
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

        .nv-act-view {
            background: #f0fff4;
            color: #27ae60
        }

        .nv-act-view:hover {
            background: #27ae60;
            color: #fff
        }

        .nv-act-edit {
            background: #eef3ff;
            color: #2d6a9f
        }

        .nv-act-edit:hover {
            background: #2d6a9f;
            color: #fff
        }

        .nv-act-pw {
            background: #fff8e1;
            color: #e67e22
        }

        .nv-act-pw:hover {
            background: #e67e22;
            color: #fff
        }

        .nv-act-del {
            background: #fff0f0;
            color: #e74c3c
        }

        .nv-act-del:hover {
            background: #e74c3c;
            color: #fff
        }

        /* ─── EMPTY ─── */
        .nv-empty {
            text-align: center
        }

        .nv-empty i {
            font-size: 3rem;
            color: #e8ebf5;
            display: block;
            margin-bottom: 12px
        }

        .nv-empty p {
            color: #bbb;
            font-size: .95rem;
            margin: 0 0 10px
        }

        .nv-empty a {
            color: #FF8C42;
            font-weight: 600;
            font-size: .875rem;
            text-decoration: none
        }

        /* ─── PAGI ─── */
        .nv-pagi-wrap {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 20px;
            border-top: 1px solid #f5f6fa;
            flex-wrap: wrap;
            gap: 10px
        }

        .nv-pagi-info {
            font-size: .8rem;
            color: #aaa
        }

        .nv-pagi-links {
            display: flex;
            align-items: center;
            gap: 4px;
            flex-wrap: wrap
        }

        .nv-pb {
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

        .nv-pb:hover:not(.nv-pb-act):not(.nv-pb-dis) {
            background: #e8f0ff;
            color: #1a3c5e;
            border-color: #c8daf5
        }

        .nv-pb-act {
            background: linear-gradient(135deg, #1a3c5e, #2d6a9f) !important;
            color: #fff !important;
            box-shadow: 0 3px 10px rgba(26, 60, 94, .25);
            cursor: default
        }

        .nv-pb-dis {
            color: #d0d8e8 !important;
            background: #fafafa !important;
            cursor: not-allowed;
            pointer-events: none
        }

        .nv-pdots {
            min-width: 28px;
            height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #aaa;
            font-size: .85rem;
            font-weight: 700
        }

        /* ─── MODAL ─── */
        .nv-modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .5);
            z-index: 9000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px
        }

        .nv-modal {
            background: #fff;
            border-radius: 16px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, .2);
            overflow: hidden
        }

        .nv-modal-head {
            padding: 16px 20px;
            background: linear-gradient(135deg, #f8faff, #eef3ff);
            border-bottom: 1px solid #e8eeff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-weight: 700;
            font-size: .9rem;
            color: #1a3c5e
        }

        .nv-modal-head i {
            color: #FF8C42;
            margin-right: 6px
        }

        .nv-modal-close {
            background: none;
            border: none;
            cursor: pointer;
            color: #bbb;
            font-size: 1rem;
            padding: 0;
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            transition: all .15s
        }

        .nv-modal-close:hover {
            background: #f5f5f5;
            color: #e74c3c
        }

        .nv-modal-body {
            padding: 20px
        }

        .nv-modal-foot {
            padding: 14px 20px;
            border-top: 1px solid #f5f5f5;
            display: flex;
            gap: 10px;
            justify-content: flex-end
        }

        .nv-btn-modal-cancel {
            height: 38px;
            background: #f5f5f5;
            color: #888;
            border: 1.5px solid #e8e8e8;
            border-radius: 8px;
            padding: 0 18px;
            font-weight: 600;
            font-size: .875rem;
            cursor: pointer;
            transition: all .2s
        }

        .nv-btn-modal-cancel:hover {
            background: #ffeee0;
            color: #FF8C42;
            border-color: #FF8C42
        }

        .nv-btn-modal-ok {
            height: 38px;
            background: linear-gradient(135deg, #FF8C42, #f5a623);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 0 20px;
            font-weight: 700;
            font-size: .875rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 3px 10px rgba(255, 140, 66, .3);
            transition: all .2s
        }

        .nv-btn-modal-ok:hover {
            transform: translateY(-1px)
        }

        .nv-btn-modal-ok:disabled {
            opacity: .6;
            cursor: not-allowed;
            transform: none
        }

        /* ─── FORM fields dùng trong modal ─── */
        .nv-fg {
            margin-bottom: 0
        }

        .nv-fl {
            display: block;
            font-weight: 700;
            font-size: .75rem;
            color: #666;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: .3px
        }

        .nv-fl.req::after {
            content: ' *';
            color: #e74c3c
        }

        .nv-fi {
            width: 100%;
            height: 40px;
            border: 1.5px solid #e8e8e8;
            border-radius: 8px;
            padding: 0 12px;
            font-size: .875rem;
            color: #333;
            background: #fafafa;
            outline: none;
            font-family: inherit;
            transition: border-color .2s, box-shadow .2s;
            box-sizing: border-box
        }

        .nv-fi:focus {
            border-color: #FF8C42;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(255, 140, 66, .1)
        }

        .nv-pw-wrap {
            position: relative
        }

        .nv-pw-wrap .nv-fi {
            padding-right: 42px
        }

        .nv-pw-eye {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #bbb;
            font-size: .85rem;
            padding: 0
        }

        .nv-pw-eye:hover {
            color: #555
        }
    </style>
@endpush

@push('scripts')
    <script>
        const CSRF = document.querySelector('meta[name=csrf-token]').content;
        let pwTargetId = null;

        // ── 1. Toggle kích hoạt ──
        document.querySelectorAll('.nv-toggle').forEach(chk => {
            chk.addEventListener('change', function() {
                const id = this.dataset.id,
                    self = this;
                fetch('/nhan-vien/admin/nhan-vien/' + id + '/toggle', {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': CSRF,
                        'Accept': 'application/json'
                    }
                }).then(r => r.json()).then(data => {
                    if (!data.ok) {
                        self.checked = !self.checked;
                        alert(data.msg || 'Không thể thực hiện!');
                    }
                }).catch(() => {
                    self.checked = !self.checked;
                });
            });
        });

        // ── 2. Modal đổi mật khẩu ──
        document.querySelectorAll('.nv-act-pw').forEach(btn => {
            btn.addEventListener('click', function() {
                pwTargetId = this.dataset.id;
                document.getElementById('pwModalTen').textContent = this.dataset.ten;
                document.getElementById('pwNew').value = '';
                document.getElementById('pwConfirm').value = '';
                document.getElementById('pwError').style.display = 'none';
                document.getElementById('pwModal').style.display = 'flex';
                setTimeout(() => document.getElementById('pwNew').focus(), 100);
            });
        });

        function closePwModal() {
            document.getElementById('pwModal').style.display = 'none';
            pwTargetId = null;
        }

        // Đóng khi click overlay
        document.getElementById('pwModal').addEventListener('click', function(e) {
            if (e.target === this) closePwModal();
        });

        function toggleModalPwd(inputId, iconId) {
            const inp = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (inp.type === 'password') {
                inp.type = 'text';
                icon.className = 'fas fa-eye-slash';
            } else {
                inp.type = 'password';
                icon.className = 'fas fa-eye';
            }
        }

        function submitDoiMatKhau() {
            const pw1 = document.getElementById('pwNew').value.trim();
            const pw2 = document.getElementById('pwConfirm').value.trim();
            const errEl = document.getElementById('pwError');
            const msgEl = document.getElementById('pwErrorMsg');
            const btn = document.getElementById('pwSubmitBtn');

            errEl.style.display = 'none';

            if (!pw1) {
                showPwErr('Vui lòng nhập mật khẩu mới!');
                return;
            }
            if (pw1.length < 6) {
                showPwErr('Mật khẩu tối thiểu 6 ký tự!');
                return;
            }
            if (pw1 !== pw2) {
                showPwErr('Mật khẩu xác nhận không khớp!');
                return;
            }

            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang lưu...';

            fetch('/nhan-vien/admin/nhan-vien/' + pwTargetId + '/doi-mat-khau', {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': CSRF,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    mat_khau_moi: pw1,
                    xac_nhan_mat_khau: pw2
                })
            }).then(r => r.json()).then(data => {
                if (data.ok) {
                    closePwModal();
                    // Hiển thị toast
                    showToast('✅ ' + data.msg);
                } else {
                    showPwErr(data.message || 'Có lỗi xảy ra!');
                }
            }).catch(() => {
                showPwErr('Không thể kết nối server!');
            }).finally(() => {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-save"></i> Lưu mật khẩu';
            });
        }

        function showPwErr(msg) {
            document.getElementById('pwErrorMsg').textContent = msg;
            document.getElementById('pwError').style.display = 'flex';
        }

        // ── 3. Toast thông báo ──
        function showToast(msg) {
            const t = document.createElement('div');
            t.style.cssText =
                'position:fixed;bottom:24px;right:24px;background:#1a3c5e;color:#fff;padding:12px 20px;border-radius:10px;font-size:.875rem;font-weight:600;z-index:9999;box-shadow:0 6px 20px rgba(0,0,0,.2);display:flex;align-items:center;gap:8px;animation:fadeInUp .3s ease';
            t.innerHTML = msg;
            document.body.appendChild(t);
            setTimeout(() => {
                t.style.opacity = '0';
                t.style.transition = 'opacity .4s';
            }, 2500);
            setTimeout(() => t.remove(), 3000);
        }

        // ── 4. Confirm xóa ──
        document.querySelectorAll('.nv-confirm-del').forEach(btn => {
            btn.addEventListener('click', function() {
                if (confirm('Xóa nhân viên "' + this.dataset.ten + '"?\nThao tác không thể hoàn tác!')) {
                    this.closest('form').submit();
                }
            });
        });

        // ── 5. Auto-submit filter khi đổi select ──
        document.querySelectorAll('#nvFilterForm select').forEach(s => {
            s.addEventListener('change', () => document.getElementById('nvFilterForm').submit());
        });

        // ── 6. Phím ESC đóng modal ──
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closePwModal();
        });

        @keyframes fadeInUp {
            from {
                opacity: 0;transform: translateY(10 px);
            }
            to {
                opacity: 1;transform: translateY(0);
            }
        }
    </script>
@endpush
