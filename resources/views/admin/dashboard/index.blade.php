@extends('admin.layouts.master')

@section('title', 'Dashboard')
@section('page_title', 'Tổng quan hệ thống')

@push('styles')
    <style>
        /* ── Stat Cards ── */
        .stat-cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .stat-card {
            background: #fff;
            border-radius: var(--radius);
            border: 1px solid var(--border);
            padding: 1.2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            box-shadow: var(--shadow);
            transition: transform .2s, box-shadow .2s;
            text-decoration: none;
            color: inherit;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, .1);
            color: inherit;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .stat-icon.orange {
            background: #fff5ef;
            color: var(--primary);
        }

        .stat-icon.navy {
            background: #eff6ff;
            color: var(--navy);
        }

        .stat-icon.green {
            background: #f0fdf4;
            color: #16a34a;
        }

        .stat-icon.red {
            background: #fef2f2;
            color: #dc2626;
        }

        .stat-icon.purple {
            background: #faf5ff;
            color: #9333ea;
        }

        .stat-icon.yellow {
            background: #fefce8;
            color: #ca8a04;
        }

        .stat-icon.teal {
            background: #f0fdfa;
            color: #0d9488;
        }

        .stat-icon.pink {
            background: #fdf2f8;
            color: #db2777;
        }

        .stat-num {
            font-size: 1.6rem;
            font-weight: 900;
            color: var(--navy);
            line-height: 1;
        }

        .stat-label {
            font-size: .75rem;
            color: var(--text-sub);
            margin-top: .2rem;
        }

        /* ── Grid layouts ── */
        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .grid-3 {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        /* ── Avatar mini ── */
        .avatar-mini {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--navy);
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: .72rem;
            font-weight: 700;
            flex-shrink: 0;
            object-fit: cover;
        }

        /* ── Lịch hẹn timeline ── */
        .lich-hen-item {
            display: flex;
            gap: .85rem;
            align-items: flex-start;
            padding: .7rem 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .lich-hen-item:last-child {
            border-bottom: none;
        }

        .lich-hen-time {
            width: 54px;
            text-align: center;
            flex-shrink: 0;
            background: var(--bg);
            border-radius: 8px;
            padding: .4rem .3rem;
            font-size: .72rem;
        }

        .lich-hen-time .hour {
            font-weight: 800;
            color: var(--navy);
            font-size: .85rem;
        }

        .lich-hen-time .day {
            color: var(--text-sub);
        }

        .lich-hen-body {
            flex: 1;
            min-width: 0;
        }

        .lich-hen-name {
            font-weight: 700;
            font-size: .84rem;
            color: var(--navy);
        }

        .lich-hen-bds {
            font-size: .75rem;
            color: var(--text-sub);
            margin-top: .1rem;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* ── Empty state ── */
        .empty-state {
            text-align: center;
            padding: 2.5rem 1rem;
            color: var(--text-sub);
        }

        .empty-state i {
            font-size: 2.5rem;
            margin-bottom: .8rem;
            opacity: .3;
            display: block;
        }

        .empty-state p {
            font-size: .83rem;
            margin: 0;
        }

        /* ── BĐS mini list ── */
        .bds-mini-item {
            display: flex;
            gap: .8rem;
            align-items: center;
            padding: .65rem 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .bds-mini-item:last-child {
            border-bottom: none;
        }

        .bds-mini-thumb {
            width: 52px;
            height: 40px;
            border-radius: 6px;
            object-fit: cover;
            background: var(--bg);
            flex-shrink: 0;
        }

        .bds-mini-name {
            font-weight: 700;
            font-size: .82rem;
            color: var(--navy);
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .bds-mini-price {
            font-size: .78rem;
            color: var(--primary);
            font-weight: 800;
        }

        /* ── Progress bar loại BĐS ── */
        .loai-progress {
            display: flex;
            flex-direction: column;
            gap: .7rem;
        }

        .loai-row label {
            font-size: .78rem;
            font-weight: 600;
            color: var(--text);
            display: flex;
            justify-content: space-between;
            margin-bottom: .2rem;
        }

        .loai-bar {
            height: 6px;
            background: #f3f4f6;
            border-radius: 3px;
            overflow: hidden;
        }

        .loai-bar-fill {
            height: 100%;
            border-radius: 3px;
            transition: width .8s ease;
        }

        @media (max-width: 992px) {

            .grid-2,
            .grid-3 {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')

    {{-- ── Page Header ── --}}
    <div class="page-header">
        <div>
            <div class="page-header-title">
                Xin chào, {{ $nhanVien->ho_ten }} 👋
            </div>
            <div class="page-header-sub">
                {{ $nhanVien->vai_tro_label }} &nbsp;·&nbsp; {{ now()->format('l, d/m/Y') }}
            </div>
        </div>
        <div class="page-header-actions">
            @if ($nhanVien->hasRole(['admin', 'sale']))
                <a href="{{ route('nhanvien.admin.bat-dong-san.create') }}" class="btn-primary-admin">
                    <i class="fas fa-plus"></i> Thêm BĐS
                </a>
            @endif
            <a href="{{ route('nhanvien.admin.lich-hen.create') }}" class="btn-secondary-admin">
                <i class="fas fa-calendar-plus"></i> Tạo lịch hẹn
            </a>
        </div>
    </div>

    {{-- ══════════════════════════
     STAT CARDS
══════════════════════════ --}}
    <div class="stat-cards">

        <a href="{{ route('nhanvien.admin.bat-dong-san.index') }}" class="stat-card">
            <div class="stat-icon orange"><i class="fas fa-building"></i></div>
            <div>
                <div class="stat-num">{{ number_format($tongQuan['tong_bds']) }}</div>
                <div class="stat-label">Tổng BĐS</div>
            </div>
        </a>

        <a href="{{ route('nhanvien.admin.bat-dong-san.index') }}?trang_thai=con_hang" class="stat-card">
            <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
            <div>
                <div class="stat-num">{{ number_format($tongQuan['bds_con_hang']) }}</div>
                <div class="stat-label">Còn hàng</div>
            </div>
        </a>

        <a href="{{ route('nhanvien.admin.bat-dong-san.index') }}?trang_thai=da_ban" class="stat-card">
            <div class="stat-icon purple"><i class="fas fa-handshake"></i></div>
            <div>
                <div class="stat-num">{{ number_format($tongQuan['bds_da_ban']) }}</div>
                <div class="stat-label">Đã bán</div>
            </div>
        </a>

        <a href="{{ route('nhanvien.admin.khach-hang.index') }}" class="stat-card">
            <div class="stat-icon navy"><i class="fas fa-users"></i></div>
            <div>
                <div class="stat-num">{{ number_format($tongQuan['tong_khach_hang']) }}</div>
                <div class="stat-label">Khách hàng</div>
            </div>
        </a>

        <a href="{{ route('nhanvien.admin.lich-hen.index') }}" class="stat-card">
            <div class="stat-icon yellow"><i class="fas fa-calendar-day"></i></div>
            <div>
                <div class="stat-num">{{ $tongQuan['lich_hen_hom_nay'] }}</div>
                <div class="stat-label">Lịch hẹn hôm nay</div>
            </div>
        </a>

        <a href="{{ route('nhanvien.admin.ky-gui.index') }}" class="stat-card">
            <div class="stat-icon pink"><i class="fas fa-file-signature"></i></div>
            <div>
                <div class="stat-num">{{ $tongQuan['ky_gui_cho_duyet'] }}</div>
                <div class="stat-label">Ký gửi chờ duyệt</div>
            </div>
        </a>

        <a href="{{ route('nhanvien.admin.du-an.index') }}" class="stat-card">
            <div class="stat-icon teal"><i class="fas fa-city"></i></div>
            <div>
                <div class="stat-num">{{ $tongQuan['tong_du_an'] }}</div>
                <div class="stat-label">Dự án</div>
            </div>
        </a>

        <a href="{{ route('nhanvien.admin.chat.index') }}" class="stat-card">
            <div class="stat-icon red"><i class="fas fa-comments"></i></div>
            <div>
                <div class="stat-num">{{ $tongQuan['chat_dang_mo'] }}</div>
                <div class="stat-label">Chat đang mở</div>
            </div>
        </a>

    </div>

    {{-- ══════════════════════════
     ROW 1: Lịch hẹn + BĐS mới
══════════════════════════ --}}
    <div class="grid-2">

        {{-- Lịch hẹn hôm nay --}}
        <div class="card-admin">
            <div class="card-admin-header">
                <div class="card-admin-title">
                    <i class="fas fa-calendar-check"></i> Lịch hẹn hôm nay
                </div>
                <a href="{{ route('nhanvien.admin.lich-hen.index') }}"
                    style="font-size:.78rem;color:var(--primary);font-weight:600;">
                    Xem tất cả →
                </a>
            </div>
            <div class="card-admin-body" style="padding: .5rem 1.25rem;">
                @forelse($lichHenHomNay as $lh)
                    <div class="lich-hen-item">
                        <div class="lich-hen-time">
                            <div class="hour">
                                {{ \Carbon\Carbon::parse($lh->thoi_gian_hen)->format('H:i') }}
                            </div>
                            <div class="day">Hôm nay</div>
                        </div>
                        <div class="lich-hen-body">
                            <div class="lich-hen-name">
                                {{ $lh->khachHang?->ho_ten ?? 'Khách vãng lai' }}
                            </div>
                            <div class="lich-hen-bds">
                                <i class="fas fa-map-marker-alt" style="color:var(--primary);font-size:.7rem;"></i>
                                {{ $lh->batDongSan?->tieu_de ?? 'Chưa xác định BĐS' }}
                            </div>
                            <span
                                class="status-badge
                    {{ match ($lh->trang_thai ?? '') {
                        'da_xac_nhan' => 'badge-da-duyet',
                        'hoan_thanh' => 'badge-con-hang',
                        'huy' => 'badge-da-ban',
                        'tu_choi' => 'badge-da-ban',
                        default => 'badge-dang-cho', // moi_dat | cho_xac_nhan
                    } }}"
                                style="margin-top:.3rem;">
                                {{ match ($lh->trang_thai ?? '') {
                                    'da_xac_nhan' => 'Đã xác nhận',
                                    'hoan_thanh' => 'Hoàn thành',
                                    'huy' => 'Đã hủy',
                                    'tu_choi' => 'Từ chối',
                                    'cho_xac_nhan' => 'Chờ xác nhận',
                                    default => 'Mới đặt',
                                } }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="fas fa-calendar-times"></i>
                        <p>Không có lịch hẹn nào hôm nay</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- BĐS mới nhất --}}
        <div class="card-admin">
            <div class="card-admin-header">
                <div class="card-admin-title">
                    <i class="fas fa-home"></i> BĐS mới nhất
                </div>
                <a href="{{ route('nhanvien.admin.bat-dong-san.index') }}"
                    style="font-size:.78rem;color:var(--primary);font-weight:600;">
                    Xem tất cả →
                </a>
            </div>
            <div class="card-admin-body" style="padding: .5rem 1.25rem;">
                @forelse($bdsMoiNhat as $bds)
                    @php
                        $imgs = is_array($bds->hinh_anh) ? $bds->hinh_anh : json_decode($bds->hinh_anh ?? '[]', true);
                        $thumb = !empty($imgs) ? asset('storage/' . $imgs[0]) : asset('images/no-image.jpg');
                    @endphp
                    <div class="bds-mini-item">
                        <img src="{{ $thumb }}" class="bds-mini-thumb" alt="{{ $bds->tieu_de }}"
                            onerror="this.src='{{ asset('images/no-image.jpg') }}'">
                        <div style="flex:1;min-width:0;">
                            <div class="bds-mini-name">{{ $bds->tieu_de }}</div>
                            <div class="bds-mini-price">
                                {{ number_format($bds->gia, 2) }} tỷ
                            </div>
                        </div>
                        <span
                            class="status-badge
                    {{ match ($bds->trang_thai ?? '') {
                        'con_hang' => 'badge-con-hang',
                        'da_ban' => 'badge-da-ban',
                        default => 'badge-cho-thue',
                    } }}">
                            {{ match ($bds->trang_thai ?? '') {
                                'con_hang' => 'Còn hàng',
                                'da_ban' => 'Đã bán',
                                default => 'Cho thuê',
                            } }}
                        </span>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="fas fa-building"></i>
                        <p>Chưa có bất động sản nào</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>

    {{-- ══════════════════════════
     ROW 2: Khách hàng mới + Ký gửi + Loại BĐS
══════════════════════════ --}}
    <div class="grid-3">

        {{-- Khách hàng mới + Ký gửi chờ duyệt --}}
        <div style="display:flex;flex-direction:column;gap:1rem;">

            {{-- Khách hàng mới --}}
            <div class="card-admin">
                <div class="card-admin-header">
                    <div class="card-admin-title">
                        <i class="fas fa-user-plus"></i> Khách hàng mới
                    </div>
                    <a href="{{ route('nhanvien.admin.khach-hang.index') }}"
                        style="font-size:.78rem;color:var(--primary);font-weight:600;">
                        Xem tất cả →
                    </a>
                </div>
                <div class="card-admin-body" style="padding:.4rem 1.25rem;">
                    @forelse($khachHangMoi as $kh)
                        <div
                            style="display:flex;align-items:center;gap:.7rem;
                            padding:.55rem 0;border-bottom:1px solid #f3f4f6;">
                            <div class="avatar-mini">
                                {{ mb_strtoupper(mb_substr($kh->ho_ten, 0, 1)) }}
                            </div>
                            <div style="flex:1;min-width:0;">
                                <div
                                    style="font-weight:700;font-size:.82rem;
                                    color:var(--navy);overflow:hidden;
                                    text-overflow:ellipsis;white-space:nowrap;">
                                    {{ $kh->ho_ten }}
                                </div>
                                <div style="font-size:.73rem;color:var(--text-sub);">
                                    {{ $kh->so_dien_thoai }}
                                </div>
                            </div>
                            <div style="font-size:.7rem;color:var(--text-sub);white-space:nowrap;">
                                {{ $kh->created_at->diffForHumans() }}
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <i class="fas fa-users"></i>
                            <p>Chưa có khách hàng</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Ký gửi chờ duyệt --}}
            <div class="card-admin">
                <div class="card-admin-header">
                    <div class="card-admin-title">
                        <i class="fas fa-file-signature"></i> Ký gửi chờ duyệt
                    </div>
                    <a href="{{ route('nhanvien.admin.ky-gui.index') }}"
                        style="font-size:.78rem;color:var(--primary);font-weight:600;">
                        Xem tất cả →
                    </a>
                </div>
                <div class="card-admin-body" style="padding:.4rem 1.25rem;">
                    @forelse($kyGuiChoDuyet as $kg)
                        <div
                            style="display:flex;align-items:center;gap:.7rem;
                            padding:.55rem 0;border-bottom:1px solid #f3f4f6;">
                            <div style="flex:1;min-width:0;">
                                <div
                                    style="font-weight:700;font-size:.82rem;color:var(--navy);
                                    overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                    {{ $kg->khachHang?->ho_ten ?? 'Khách hàng' }}
                                </div>
                                <div style="font-size:.73rem;color:var(--text-sub);">
                                    {{ $kg->created_at->format('d/m/Y H:i') }}
                                </div>
                            </div>
                            <a href="{{ route('nhanvien.admin.ky-gui.show', $kg->id) }}" class="btn-primary-admin"
                                style="padding:.3rem .7rem;font-size:.72rem;">
                                Xem
                            </a>
                        </div>
                    @empty
                        <div class="empty-state">
                            <i class="fas fa-check-double"></i>
                            <p>Không có ký gửi chờ duyệt</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

        {{-- Phân loại BĐS --}}
        <div class="card-admin">
            <div class="card-admin-header">
                <div class="card-admin-title">
                    <i class="fas fa-chart-pie"></i> Phân loại BĐS
                </div>
            </div>
            <div class="card-admin-body">
                @php
                    $bdsByLoai = collect([
                        [
                            'label' => 'Mua bán — Còn hàng',
                            'color' => '#FF8C42',
                            'tong' => \App\Models\BatDongSan::whereNull('deleted_at')
                                ->where('nhu_cau', 'ban')
                                ->where('trang_thai', 'con_hang')
                                ->count(),
                        ],
                        [
                            'label' => 'Mua bán — Đã bán',
                            'color' => '#1a3c5e',
                            'tong' => \App\Models\BatDongSan::whereNull('deleted_at')
                                ->where('nhu_cau', 'ban')
                                ->where('trang_thai', 'da_ban')
                                ->count(),
                        ],
                        [
                            'label' => 'Cho thuê — Còn hàng',
                            'color' => '#27ae60',
                            'tong' => \App\Models\BatDongSan::whereNull('deleted_at')
                                ->where('nhu_cau', 'thue')
                                ->where('trang_thai', 'con_hang')
                                ->count(),
                        ],
                        [
                            'label' => 'Cho thuê — Đã thuê',
                            'color' => '#8b5cf6',
                            'tong' => \App\Models\BatDongSan::whereNull('deleted_at')
                                ->where('nhu_cau', 'thue')
                                ->where('trang_thai', 'da_thue')
                                ->count(),
                        ],
                    ])->map(fn($item) => (object) $item);

                    $totalBds = $bdsByLoai->sum('tong') ?: 1;
                @endphp

                <div class="loai-progress">
                    @forelse($bdsByLoai as $loai)
                        @if ($loai->tong > 0)
                            <div>
                                <label>
                                    <span>{{ $loai->label }}</span>
                                    <strong>{{ $loai->tong }}</strong>
                                </label>
                                <div class="loai-bar">
                                    <div class="loai-bar-fill"
                                        style="width:{{ round(($loai->tong / $totalBds) * 100) }}%;
                                background:{{ $loai->color }};">
                                    </div>
                                </div>
                            </div>
                        @endif
                    @empty
                        <div class="empty-state">
                            <i class="fas fa-chart-bar"></i>
                            <p>Chưa có dữ liệu</p>
                        </div>
                    @endforelse
                </div>
            </div>


            {{-- Thống kê 6 tháng ── mini ── --}}
            <div style="margin-top:1.5rem;border-top:1px solid var(--border);padding-top:1rem;">
                <div style="font-size:.8rem;font-weight:700;color:var(--navy);margin-bottom:.8rem;">
                    <i class="fas fa-chart-line" style="color:var(--primary)"></i>
                    BĐS 6 tháng gần đây
                </div>
                <div style="display:flex;gap:.3rem;align-items:flex-end;height:60px;">
                    @php $maxVal = max(array_column($bdsSixMonths ?? [], 'them') ?: [1]); @endphp

                    @foreach ($bdsSixMonths as $m)
                        @php $h = $maxVal > 0 ? round($m['them'] / $maxVal * 100) : 0; @endphp
                        <div style="flex:1;display:flex;flex-direction:column;align-items:center;gap:.3rem;">
                            <div style="background:var(--primary);border-radius:3px 3px 0 0;
                                    width:100%;height:{{ max($h, 4) }}%;opacity:.85;"
                                title="{{ $m['them'] }} BĐS mới"></div>
                            <span style="font-size:.6rem;color:var(--text-sub);white-space:nowrap;">
                                {{ explode('/', $m['thang'])[0] }}/{{ substr(explode('/', $m['thang'])[1], 2) }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Nhân viên (chỉ Admin) --}}
            @if ($danhSachNhanVien)
                <div style="margin-top:1.5rem;border-top:1px solid var(--border);padding-top:1rem;">
                    <div style="font-size:.8rem;font-weight:700;color:var(--navy);margin-bottom:.8rem;">
                        <i class="fas fa-user-tie" style="color:var(--primary)"></i>
                        Top nhân viên
                    </div>
                    @foreach ($danhSachNhanVien as $nv)
                        <div style="display:flex;align-items:center;gap:.6rem;margin-bottom:.55rem;">
                            <div class="avatar-mini">
                                {{ mb_strtoupper(mb_substr($nv->ho_ten, 0, 1)) }}
                            </div>
                            <div style="flex:1;min-width:0;">
                                <div
                                    style="font-size:.78rem;font-weight:700;color:var(--navy);
                                    overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                    {{ $nv->ho_ten }}
                                </div>
                                <div style="font-size:.7rem;color:var(--text-sub);">
                                    {{ $nv->so_bds }} BĐS · {{ $nv->so_lich_hen }} lịch hẹn · {{ $nv->so_ky_gui }}
                                    ký gửi
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>

    </div>

@endsection
