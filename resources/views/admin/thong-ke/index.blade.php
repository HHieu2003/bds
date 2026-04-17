@extends('admin.layouts.master')
@section('title', 'Thống kê & Phân tích')

@push('styles')
<style>
/* ── Base ── */
.tk-card { background:#fff; border-radius:14px; box-shadow:0 2px 12px rgba(0,0,0,.06); padding:1.2rem; }
.sec-title { font-size:.85rem; font-weight:700; color:#374151; text-transform:uppercase; letter-spacing:.5px; margin-bottom:.9rem; display:flex; align-items:center; gap:.4rem; }
/* ── KPI Cards ── */
.kpi-card { display:flex; align-items:center; gap:.9rem; }
.kpi-icon { width:46px; height:46px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:1.2rem; flex-shrink:0; }
.kpi-num { font-size:1.75rem; font-weight:800; line-height:1.1; }
.kpi-label { font-size:.72rem; color:#6b7280; font-weight:600; text-transform:uppercase; letter-spacing:.4px; }
.kpi-delta { font-size:.75rem; font-weight:700; }
.kpi-delta.up { color:#10b981; } .kpi-delta.down { color:#ef4444; }
/* ── Rank list (chiều cao cố định, cuộn nội bộ) ── */
.rank-list { overflow-y:auto; }
.rank-list::-webkit-scrollbar { width:4px; }
.rank-list::-webkit-scrollbar-thumb { background:#e2e8f0; border-radius:2px; }
.rank-row { display:flex; align-items:center; gap:.6rem; padding:.55rem 0; border-bottom:1px solid #f1f5f9; }
.rank-row:last-child { border-bottom:0; }
.rank-num { width:24px; height:24px; border-radius:50%; background:#f1f5f9; color:#64748b; font-size:.7rem; font-weight:800; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.rank-num.g1 { background:#fef3c7; color:#b45309; }
.rank-num.g2 { background:#e5e7eb; color:#4b5563; }
.rank-num.g3 { background:#fde8d8; color:#c2410c; }
.rank-bar-wrap { flex:1; min-width:0; }
.rank-name { font-size:.78rem; font-weight:600; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:100%; display:block; margin-bottom:3px; }
.rank-bar { height:5px; border-radius:3px; background:#f1f5f9; overflow:hidden; }
.rank-fill { height:100%; border-radius:3px; transition:width .7s; }
.rank-cnt { font-size:.8rem; font-weight:700; color:#374151; white-space:nowrap; }
/* ── Chart containers ── */
.chart-box { position:relative; }
/* ── Filter bar ── */
.filter-bar { background:#fff; border-radius:12px; box-shadow:0 2px 8px rgba(0,0,0,.05); padding:.85rem 1.2rem; margin-bottom:1.5rem; }
.filter-bar .form-select, .filter-bar .form-control { font-size:.82rem; }
.filter-bar .btn { font-size:.82rem; }
/* ── Tag badge hiện kỳ ── */
.period-badge { display:inline-flex; align-items:center; gap:.35rem; background:#ede9fe; color:#6d28d9; border-radius:8px; padding:.25rem .75rem; font-size:.78rem; font-weight:700; }
</style>
@endpush

@section('content')

{{-- ── Header ── --}}
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <div>
        <h1 class="page-header-title mb-0"><i class="fas fa-chart-bar text-primary me-2"></i>Thống kê & Phân tích</h1>
    </div>
    <div class="period-badge"><i class="fas fa-calendar-alt"></i> {{ $label }}</div>
</div>

{{-- ── FILTER BAR ── --}}
<form method="GET" action="{{ route('nhanvien.thong-ke') }}" class="filter-bar" id="frmFilter">
    <div class="row g-2 align-items-end">
        <div class="col-12 col-sm-auto">
            <label class="form-label small fw-bold mb-1">Xem theo</label>
            <select name="loai_loc" class="form-select form-select-sm" id="selLoaiLoc" onchange="toggleCustom()">
                <option value="ngay"     {{ $loai_loc=='ngay'?'selected':'' }}>Hôm nay</option>
                <option value="tuan"     {{ $loai_loc=='tuan'?'selected':'' }}>Tuần này</option>
                <option value="thang"    {{ $loai_loc=='thang'?'selected':'' }}>Tháng này</option>
                <option value="nam"      {{ $loai_loc=='nam'?'selected':'' }}>Năm nay</option>
                <option value="tuy_chon" {{ $loai_loc=='tuy_chon'?'selected':'' }}>Tuỳ chỉnh...</option>
            </select>
        </div>
        <div class="col-12 col-sm-auto d-flex gap-2 align-items-end" id="customRange" style="display:{{ $loai_loc=='tuy_chon'?'flex':'none' }} !important">
            <div>
                <label class="form-label small fw-bold mb-1">Từ ngày</label>
                <input type="date" name="tu_ngay" class="form-control form-control-sm" value="{{ $tu_ngay_input }}">
            </div>
            <div>
                <label class="form-label small fw-bold mb-1">Đến ngày</label>
                <input type="date" name="den_ngay" class="form-control form-control-sm" value="{{ $den_ngay_input }}">
            </div>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search me-1"></i>Áp dụng</button>
            <a href="{{ route('nhanvien.thong-ke') }}" class="btn btn-outline-secondary btn-sm ms-1"><i class="fas fa-undo"></i></a>
        </div>
    </div>
</form>

{{-- ════════════════════════════════════════════
     ROW 1 – KPI LƯỢT TRUY CẬP (4 thẻ)
════════════════════════════════════════════ --}}
<div class="row g-3 mb-3">
    @php
    $kpis = [
        ['num'=>$visitorHom,   'label'=>'Hôm nay',   'icon'=>'fa-sun',        'bg'=>'#fef9c3','ic'=>'#ca8a04'],
        ['num'=>$visitorTuan,  'label'=>'Tuần này',  'icon'=>'fa-calendar-week','bg'=>'#ede9fe','ic'=>'#7c3aed'],
        ['num'=>$visitorThang, 'label'=>'Tháng này', 'icon'=>'fa-calendar-alt','bg'=>'#dbeafe','ic'=>'#2563eb'],
        ['num'=>$visitorNam,   'label'=>'Năm nay',   'icon'=>'fa-chart-line',  'bg'=>'#dcfce7','ic'=>'#16a34a'],
    ];
    @endphp
    @foreach($kpis as $k)
    <div class="col-6 col-xl-3">
        <div class="tk-card kpi-card h-100">
            <div class="kpi-icon" style="background:{{ $k['bg'] }};color:{{ $k['ic'] }}"><i class="fas {{ $k['icon'] }}"></i></div>
            <div><div class="kpi-num">{{ number_format($k['num']) }}</div><div class="kpi-label">{{ $k['label'] }}</div></div>
        </div>
    </div>
    @endforeach
</div>

{{-- KPI kỳ được chọn + so sánh --}}
<div class="row g-3 mb-4">
    @php
    $kpi2 = [
        ['num'=>$visitorNow,'prev'=>$visitorPrev,'delta'=>$visitorDelta,'label'=>'Truy cập ('.$label.')','icon'=>'fa-globe','bg'=>'#f0f9ff','ic'=>'#0284c7'],
        ['num'=>$lichHenNow,'prev'=>$lichHenPrev,'delta'=>$lichHenDelta,'label'=>'Lịch hẹn ('.$label.')','icon'=>'fa-calendar-check','bg'=>'#f0fdf4','ic'=>'#16a34a'],
        ['num'=>$leadsNow,'prev'=>$leadsPrev,'delta'=>$leadsDelta,'label'=>'Leads ('.$label.')','icon'=>'fa-bullhorn','bg'=>'#fff1f2','ic'=>'#e11d48'],
        ['num'=>$bdsNow,'prev'=>$bdsPrev,'delta'=>($bdsPrev>0?round(($bdsNow-$bdsPrev)/$bdsPrev*100,1):($bdsNow>0?100:0)),'label'=>'BĐS mới ('.$label.')','icon'=>'fa-home','bg'=>'#fdf4ff','ic'=>'#9333ea'],
    ];
    @endphp
    @foreach($kpi2 as $k)
    <div class="col-6 col-xl-3">
        <div class="tk-card kpi-card h-100">
            <div class="kpi-icon" style="background:{{ $k['bg'] }};color:{{ $k['ic'] }}"><i class="fas {{ $k['icon'] }}"></i></div>
            <div>
                <div class="kpi-num">{{ number_format($k['num']) }}</div>
                <div class="kpi-label">{{ $k['label'] }}</div>
                @php $up = $k['delta'] >= 0; @endphp
                <div class="kpi-delta {{ $up?'up':'down' }}">
                    <i class="fas fa-arrow-{{ $up?'up':'down' }}"></i> {{ abs($k['delta']) }}% so với kỳ trước ({{ number_format($k['prev']) }})
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- ════════════════════════════════════════════
     ROW 2 – BIỂU ĐỒ TRUY CẬP + PHÂN LOẠI TRANG
════════════════════════════════════════════ --}}
<div class="row g-3 mb-4">
    <div class="col-12 col-xl-8">
        <div class="tk-card h-100">
            <div class="sec-title"><i class="fas fa-chart-area text-info"></i>Lượt truy cập 30 ngày gần nhất</div>
            <div class="chart-box" style="height:230px"><canvas id="chartVisitors"></canvas></div>
        </div>
    </div>
    <div class="col-12 col-xl-4">
        <div class="tk-card h-100">
            <div class="sec-title"><i class="fas fa-layer-group text-warning"></i>Trang được vào nhiều nhất</div>
            @php $maxTrang = $trangNoiBat->max('luot') ?: 1; @endphp
            <div class="rank-list" style="max-height:230px">
                @forelse($trangNoiBat as $i => $t)
                <div class="rank-row">
                    <div class="rank-num {{ $i==0?'g1':($i==1?'g2':($i==2?'g3':'')) }}">{{ $i+1 }}</div>
                    <div class="rank-bar-wrap">
                        <span class="rank-name">{{ $t['trang'] }}</span>
                        <div class="rank-bar"><div class="rank-fill" style="width:{{ round($t['luot']/$maxTrang*100) }}%;background:linear-gradient(90deg,#f59e0b,#ef4444)"></div></div>
                    </div>
                    <div class="rank-cnt">{{ number_format($t['luot']) }}</div>
                </div>
                @empty<div class="text-muted small text-center py-4">Chưa có dữ liệu truy cập</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- ════════════════════════════════════════════
     ROW 3 – KHU VỰC / DỰ ÁN / BĐS XEM NHIỀU
════════════════════════════════════════════ --}}
<div class="row g-3 mb-4">
    {{-- Khu vực --}}
    <div class="col-12 col-xl-4">
        <div class="tk-card h-100">
            <div class="sec-title"><i class="fas fa-map-marker-alt text-danger"></i>Khu vực xem nhiều nhất</div>
            @php $maxKV = $khuVucNoiBat->max('luot_xem') ?: 1; @endphp
            <div class="rank-list" style="max-height:300px">
                @forelse($khuVucNoiBat as $i => $kv)
                <div class="rank-row">
                    <div class="rank-num {{ $i==0?'g1':($i==1?'g2':($i==2?'g3':'')) }}">{{ $i+1 }}</div>
                    <div class="rank-bar-wrap">
                        <span class="rank-name">{{ $kv->ten_khu_vuc }}</span>
                        <div class="rank-bar"><div class="rank-fill" style="width:{{ round($kv->luot_xem/$maxKV*100) }}%;background:linear-gradient(90deg,#6366f1,#8b5cf6)"></div></div>
                    </div>
                    <div class="rank-cnt">{{ $kv->luot_xem }}</div>
                </div>
                @empty<div class="text-muted small text-center py-4">Chưa có dữ liệu</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Dự án --}}
    <div class="col-12 col-xl-4">
        <div class="tk-card h-100">
            <div class="sec-title"><i class="fas fa-city text-info"></i>Dự án xem nhiều nhất</div>
            @php $maxDA = $duAnNoiBat->max('luot_xem') ?: 1; @endphp
            <div class="rank-list" style="max-height:300px">
                @forelse($duAnNoiBat as $i => $da)
                <div class="rank-row">
                    <div class="rank-num {{ $i==0?'g1':($i==1?'g2':($i==2?'g3':'')) }}">{{ $i+1 }}</div>
                    <div class="rank-bar-wrap">
                        <span class="rank-name">{{ $da->ten_du_an }}</span>
                        <div class="rank-bar"><div class="rank-fill" style="width:{{ round($da->luot_xem/$maxDA*100) }}%;background:linear-gradient(90deg,#06b6d4,#3b82f6)"></div></div>
                    </div>
                    <div class="rank-cnt">{{ $da->luot_xem }}</div>
                </div>
                @empty<div class="text-muted small text-center py-4">Chưa có dữ liệu</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- BĐS --}}
    <div class="col-12 col-xl-4">
        <div class="tk-card h-100">
            <div class="sec-title"><i class="fas fa-home text-success"></i>BĐS xem nhiều nhất (Top 10)</div>
            @php $maxBDS = $bdsNoiBat->max('luot_xem') ?: 1; @endphp
            <div class="rank-list" style="max-height:300px">
                @forelse($bdsNoiBat as $i => $b)
                <div class="rank-row">
                    <div class="rank-num {{ $i==0?'g1':($i==1?'g2':($i==2?'g3':'')) }}">{{ $i+1 }}</div>
                    <div class="rank-bar-wrap">
                        <a href="{{ route('nhanvien.admin.bat-dong-san.edit', $b->id) }}" class="rank-name text-dark text-decoration-none">{{ Str::limit($b->tieu_de, 45) }}</a>
                        <div class="rank-bar"><div class="rank-fill" style="width:{{ round($b->luot_xem/$maxBDS*100) }}%;background:linear-gradient(90deg,#10b981,#06b6d4)"></div></div>
                    </div>
                    <div class="rank-cnt">{{ $b->luot_xem }}</div>
                </div>
                @empty<div class="text-muted small text-center py-4">Chưa có dữ liệu</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- ════════════════════════════════════════════
     ROW 4 – LỊCH HẸN CHART + LEADS + TỪ KHOÁ
════════════════════════════════════════════ --}}
<div class="row g-3 mb-4">
    {{-- Biểu đồ lịch hẹn --}}
    <div class="col-12 col-xl-5">
        <div class="tk-card h-100">
            <div class="sec-title"><i class="fas fa-calendar-check text-primary"></i>Lịch hẹn 6 tháng gần nhất</div>
            <div class="row g-2 mb-3 text-center">
                <div class="col-4"><div class="fw-bold fs-5 text-primary">{{ $lichHenNow }}</div><div class="kpi-label">Kỳ này</div></div>
                <div class="col-4">
                    <div class="fw-bold fs-5 text-success">{{ $lichHenHoanThanh }}</div><div class="kpi-label">Hoàn thành</div>
                </div>
                <div class="col-4"><div class="fw-bold fs-5 text-warning">{{ $lichHenChoXuLy }}</div><div class="kpi-label">Chờ xử lý</div></div>
            </div>
            <div class="chart-box" style="height:180px"><canvas id="chartLichHen"></canvas></div>
        </div>
    </div>

    {{-- Leads --}}
    <div class="col-12 col-xl-3">
        <div class="tk-card h-100">
            <div class="sec-title"><i class="fas fa-bullhorn text-danger"></i>Yêu cầu tư vấn (Leads)</div>
            <div class="text-center mb-3">
                <div class="kpi-num text-danger">{{ $leadsNow }}</div>
                <div class="kpi-label">Trong kỳ</div>
                @php $lu = $leadsDelta >= 0; @endphp
                <div class="kpi-delta {{ $lu?'up':'down' }}"><i class="fas fa-arrow-{{ $lu?'up':'down' }}"></i> {{ abs($leadsDelta) }}% vs kỳ trước</div>
            </div>
            <hr class="my-2">
            <div class="d-flex justify-content-around text-center">
                <div><div class="fw-bold fs-5 text-danger">{{ $leadsChuaXuLy }}</div><div class="kpi-label">Chưa xử lý</div></div>
                <div><div class="fw-bold fs-5 text-secondary">{{ $leadsPrev }}</div><div class="kpi-label">Kỳ trước</div></div>
            </div>
            <a href="{{ route('nhanvien.admin.lien-he.index') }}" class="btn btn-outline-danger btn-sm w-100 mt-3"><i class="fas fa-arrow-right me-1"></i>Xem tất cả Leads</a>
        </div>
    </div>

    {{-- Từ khoá tìm kiếm --}}
    <div class="col-12 col-xl-4">
        <div class="tk-card h-100">
            <div class="sec-title"><i class="fas fa-search text-warning"></i>Từ khoá tìm kiếm phổ biến</div>
            @php $maxTK = $tuKhoaTop->max('so_lan') ?: 1; @endphp
            <div class="rank-list" style="max-height:260px">
                @forelse($tuKhoaTop as $i => $tk)
                <div class="rank-row">
                    <div class="rank-num {{ $i==0?'g1':($i==1?'g2':($i==2?'g3':'')) }}">{{ $i+1 }}</div>
                    <div class="rank-bar-wrap">
                        <span class="rank-name">"{{ $tk->tu_khoa }}"</span>
                        <div class="rank-bar"><div class="rank-fill" style="width:{{ round($tk->so_lan/$maxTK*100) }}%;background:linear-gradient(90deg,#f59e0b,#f97316)"></div></div>
                    </div>
                    <div class="rank-cnt">{{ $tk->so_lan }}x</div>
                </div>
                @empty<div class="text-muted small text-center py-4">Chưa có lịch sử tìm kiếm</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- ════════════════════════════════════════════
     ROW 5 – BDS TỔNG KHO + CHỦ NHÀ + KÝ GỬI + KH
════════════════════════════════════════════ --}}
<div class="row g-3 mb-4">
    @php
    $sysCards = [
        ['num'=>$bdsNow,      'label'=>'BĐS mới kỳ này','icon'=>'fa-home',          'bg'=>'#dbeafe','ic'=>'#2563eb','extra'=>'Tổng kho: '.$bdsTong.' | Còn hàng: '.$bdsConHang],
        ['num'=>$chuNhaNow,   'label'=>'Chủ nhà mới',   'icon'=>'fa-user-tie',       'bg'=>'#f0fdf4','ic'=>'#16a34a','extra'=>'Tổng: '.$chuNhaTong],
        ['num'=>$kyGuiNow,    'label'=>'Ký gửi mới',    'icon'=>'fa-file-signature', 'bg'=>'#fdf4ff','ic'=>'#9333ea','extra'=>'Chờ duyệt: '.$kyGuiChoDuyet],
        ['num'=>$khachHangNow,'label'=>'Khách hàng mới','icon'=>'fa-users',          'bg'=>'#fff7ed','ic'=>'#ea580c','extra'=>'Tổng: '.$khachHangTong],
    ];
    @endphp
    @foreach($sysCards as $c)
    <div class="col-6 col-xl-3">
        <div class="tk-card kpi-card h-100">
            <div class="kpi-icon" style="background:{{ $c['bg'] }};color:{{ $c['ic'] }}"><i class="fas {{ $c['icon'] }}"></i></div>
            <div>
                <div class="kpi-num">{{ number_format($c['num']) }}</div>
                <div class="kpi-label">{{ $c['label'] }}</div>
                <div class="text-muted" style="font-size:.73rem">{{ $c['extra'] }}</div>
            </div>
        </div>
    </div>
    @endforeach
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
function toggleCustom() {
    const v = document.getElementById('selLoaiLoc').value;
    document.getElementById('customRange').style.display = v === 'tuy_chon' ? 'flex' : 'none';
}

document.addEventListener('DOMContentLoaded', function () {
    Chart.defaults.font.family = "'Be Vietnam Pro', sans-serif";
    Chart.defaults.color = '#6b7280';

    // Biểu đồ lượt truy cập 30 ngày
    new Chart(document.getElementById('chartVisitors'), {
        type: 'line',
        data: {
            labels: {!! json_encode(array_column($chart30Ngay, 'ngay')) !!},
            datasets: [{
                label: 'Lượt truy cập',
                data: {!! json_encode(array_column($chart30Ngay, 'luot')) !!},
                borderColor: '#6366f1', backgroundColor: 'rgba(99,102,241,.08)',
                borderWidth: 2, fill: true, tension: 0.4, pointRadius: 2,
            }]
        },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { precision: 0 } } } }
    });

    // Biểu đồ lịch hẹn 6 tháng
    new Chart(document.getElementById('chartLichHen'), {
        type: 'bar',
        data: {
            labels: {!! json_encode(array_column($chartLichHen6Thang, 'thang')) !!},
            datasets: [
                { label: 'Tổng lịch', data: {!! json_encode(array_column($chartLichHen6Thang, 'so_luong')) !!}, backgroundColor: '#bfdbfe', borderRadius: 5 },
                { label: 'Hoàn thành', data: {!! json_encode(array_column($chartLichHen6Thang, 'hoan_thanh')) !!}, backgroundColor: '#6ee7b7', borderRadius: 5 },
            ]
        },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'top', labels: { boxWidth: 10, font: { size: 11 } } } }, scales: { y: { beginAtZero: true, ticks: { precision: 0 } } } }
    });
});
</script>
@endpush
