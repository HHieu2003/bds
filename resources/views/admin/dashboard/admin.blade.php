@extends('admin.layouts.master')

@section('title', 'Dashboard')

@push('styles')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .dashboard-title {
            font-size: 1.4rem;
            font-weight: 800;
            color: var(--navy);
            margin: 0;
        }

        .dashboard-subtitle {
            font-size: 0.85rem;
            color: var(--text-sub);
            margin-top: 0.2rem;
        }

        .stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 1.25rem;
            margin-bottom: 1.5rem;
        }

        .mini-list-item {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            padding: 0.85rem 0;
            border-bottom: 1px solid var(--border);
        }

        .mini-list-item:last-child {
            border-bottom: none;
        }

        .mini-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: #fff;
            background: var(--navy-light);
            flex-shrink: 0;
        }

        .mini-thumb {
            width: 56px;
            height: 42px;
            border-radius: 6px;
            object-fit: cover;
            flex-shrink: 0;
        }

        .mini-info {
            flex: 1;
            min-width: 0;
        }

        .mini-title {
            font-weight: 700;
            color: var(--navy);
            font-size: 0.85rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .mini-sub {
            font-size: 0.72rem;
            color: var(--text-sub);
            margin-top: 0.15rem;
        }

        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }

        .doughnut-container {
            position: relative;
            height: 220px;
            width: 100%;
            display: flex;
            justify-content: center;
        }
    </style>
@endpush

@section('content')

    {{-- ── Header ── --}}
    <div class="dashboard-header">
        <div>
            <h1 class="dashboard-title">Tổng quan hệ thống</h1>
            <div class="dashboard-subtitle">Xin chào <strong>{{ $nhanVien->ho_ten }}</strong>
                ({{ $nhanVien->vai_tro_label }}) - {{ now()->format('l, d/m/Y') }}</div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('nhanvien.admin.bat-dong-san.create') }}" class="btn btn-primary"><i
                    class="fas fa-plus me-1"></i> Thêm BĐS</a>
            <a href="{{ route('nhanvien.admin.lich-hen.create') }}" class="btn btn-secondary"><i
                    class="fas fa-calendar-plus me-1"></i> Tạo lịch hẹn</a>
        </div>
    </div>

    {{-- ── 8 Stat Cards ── --}}
    <div class="stat-grid">
        <a href="{{ route('nhanvien.admin.bat-dong-san.index') }}" class="stat-card">
            <div class="stat-icon orange"><i class="fas fa-building"></i></div>
            <div>
                <div class="stat-num">{{ number_format($tongQuan['tong_bds']) }}</div>
                <div class="stat-label">Tổng Kho BĐS</div>
            </div>
        </a>
        <a href="{{ route('nhanvien.admin.lien-he.index') }}" class="stat-card">
            <div class="stat-icon red"><i class="fas fa-bullhorn"></i></div>
            <div>
                <div class="stat-num">{{ number_format($tongQuan['yeu_cau_moi']) }}</div>
                <div class="stat-label">Yêu cầu LH mới</div>
            </div>
        </a>
        <a href="{{ route('nhanvien.admin.lich-hen.index') }}" class="stat-card">
            <div class="stat-icon blue"><i class="fas fa-calendar-day"></i></div>
            <div>
                <div class="stat-num">{{ number_format($tongQuan['lich_hen_hom_nay']) }}</div>
                <div class="stat-label">Lịch hẹn hôm nay</div>
            </div>
        </a>
        <a href="{{ route('nhanvien.admin.ky-gui.index') }}" class="stat-card">
            <div class="stat-icon pink"><i class="fas fa-file-signature"></i></div>
            <div>
                <div class="stat-num">{{ number_format($tongQuan['ky_gui_cho_duyet']) }}</div>
                <div class="stat-label">Ký gửi chờ duyệt</div>
            </div>
        </a>
        <a href="{{ route('nhanvien.admin.bat-dong-san.index') }}?trang_thai=con_hang" class="stat-card">
            <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
            <div>
                <div class="stat-num">{{ number_format($tongQuan['bds_con_hang']) }}</div>
                <div class="stat-label">BĐS Còn hàng</div>
            </div>
        </a>
        <a href="{{ route('nhanvien.admin.khach-hang.index') }}" class="stat-card">
            <div class="stat-icon navy"><i class="fas fa-users"></i></div>
            <div>
                <div class="stat-num">{{ number_format($tongQuan['tong_khach_hang']) }}</div>
                <div class="stat-label">Tổng Khách hàng</div>
            </div>
        </a>
        <a href="{{ route('nhanvien.admin.du-an.index') }}" class="stat-card">
            <div class="stat-icon teal"><i class="fas fa-city"></i></div>
            <div>
                <div class="stat-num">{{ number_format($tongQuan['tong_du_an']) }}</div>
                <div class="stat-label">Dự án</div>
            </div>
        </a>
        <a href="{{ route('nhanvien.admin.chat.index') }}" class="stat-card">
            <div class="stat-icon purple"><i class="fas fa-comments"></i></div>
            <div>
                <div class="stat-num">{{ number_format($tongQuan['chat_dang_mo']) }}</div>
                <div class="stat-label">Chat đang mở</div>
            </div>
        </a>
    </div>

    {{-- ── Main Layout: Cột Trái (8/12) & Cột Phải (4/12) ── --}}
    <div class="row g-4">

        {{-- ================= CỘT TRÁI ================= --}}
        <div class="col-12 col-xl-8 d-flex flex-column gap-4">

            {{-- Biểu đồ 6 tháng --}}
            <div class="card h-100">
                <div class="card-header">
                    <div><i class="fas fa-chart-area me-2"></i>Thống kê Bất Động Sản (6 Tháng)</div>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="chart6Thang"></canvas>
                    </div>
                </div>
            </div>

            {{-- Lịch hẹn hôm nay --}}
            <div class="card">
                <div class="card-header">
                    <div><i class="fas fa-calendar-check me-2"></i>Lịch hẹn hôm nay</div>
                    <a href="{{ route('nhanvien.admin.lich-hen.index') }}"
                        class="badge bg-light text-primary text-decoration-none">Xem tất cả</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Thời gian</th>
                                    <th>Khách hàng</th>
                                    <th>Bất động sản</th>
                                    <th>Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($lichHenHomNay as $lh)
                                    <tr>
                                        <td class="fw-bold text-primary">
                                            {{ \Carbon\Carbon::parse($lh->thoi_gian_hen)->format('H:i') }}</td>
                                        <td class="fw-bold">{{ $lh->khachHang?->ho_ten ?? 'Khách vãng lai' }}</td>
                                        <td class="text-muted"><i class="fas fa-map-marker-alt me-1 text-danger"></i>
                                            {{ $lh->batDongSan?->tieu_de ?? 'Chưa xác định' }}</td>
                                        <td>
                                            <span
                                                class="badge-status {{ $lh->trang_thai == 'da_xac_nhan' ? 'badge-active' : 'badge-pending' }}">
                                                {{ $lh->trang_thai == 'da_xac_nhan' ? 'Đã xác nhận' : 'Mới / Chờ' }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">Hôm nay không có lịch hẹn
                                            nào.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- BĐS Mới Nhất --}}
            <div class="card">
                <div class="card-header">
                    <div><i class="fas fa-home me-2"></i>Bất động sản mới cập nhật</div>
                </div>
                <div class="card-body p-3">
                    <div class="row g-3">
                        @forelse($bdsMoiNhat as $bds)
                            @php
                                $imgs = is_array($bds->hinh_anh)
                                    ? $bds->hinh_anh
                                    : json_decode($bds->hinh_anh ?? '[]', true);
                                $thumb = !empty($imgs) ? asset('storage/' . $imgs[0]) : asset('images/no-image.jpg');
                            @endphp
                            <div class="col-12 col-md-6">
                                <div class="mini-list-item p-2 rounded"
                                    style="background: var(--bg-alt); border: 1px solid var(--border);">
                                    <img src="{{ $thumb }}" class="mini-thumb" alt="thumb">
                                    <div class="mini-info">
                                        <div class="mini-title">{{ $bds->tieu_de }}</div>
                                        <div class="mini-sub d-flex justify-content-between pe-2">
                                            <strong class="text-danger">{{ number_format($bds->gia, 2) }} tỷ</strong>
                                            <span
                                                class="badge {{ $bds->trang_thai == 'con_hang' ? 'bg-success' : 'bg-secondary' }}">{{ $bds->trang_thai == 'con_hang' ? 'Còn hàng' : 'Đã bán' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-3 text-muted">Chưa có bất động sản nào.</div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>

        {{-- ================= CỘT PHẢI ================= --}}
        <div class="col-12 col-xl-4 d-flex flex-column gap-4">

            {{-- Biểu đồ Tròn: Phân loại BĐS --}}
            <div class="card">
                <div class="card-header">
                    <div><i class="fas fa-chart-pie me-2"></i>Tỷ trọng Bất Động Sản</div>
                </div>
                <div class="card-body">
                    <div class="doughnut-container">
                        <canvas id="chartPhanLoai"></canvas>
                    </div>
                </div>
            </div>

            {{-- Ký gửi chờ duyệt --}}
            <div class="card">
                <div class="card-header">
                    <div><i class="fas fa-file-signature me-2"></i>Ký gửi chờ duyệt</div>
                </div>
                <div class="card-body p-0 px-3">
                    @forelse($kyGuiChoDuyet as $kg)
                        <div class="mini-list-item">
                            <div class="mini-info">
                                <div class="mini-title">{{ $kg->khachHang?->ho_ten ?? 'Khách hàng' }}</div>
                                <div class="mini-sub"><i class="fas fa-clock"></i>
                                    {{ $kg->created_at->format('d/m/Y H:i') }}</div>
                            </div>
                            <a href="{{ route('nhanvien.admin.ky-gui.show', $kg->id) }}"
                                class="btn btn-sm btn-outline-primary">Xem</a>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted">Không có ký gửi chờ duyệt.</div>
                    @endforelse
                </div>
            </div>

            {{-- Khách hàng mới --}}
            <div class="card">
                <div class="card-header">
                    <div><i class="fas fa-user-plus me-2"></i>Khách hàng mới</div>
                </div>
                <div class="card-body p-0 px-3">
                    @forelse($khachHangMoi as $kh)
                        <div class="mini-list-item">
                            <div class="mini-avatar bg-primary">{{ mb_strtoupper(mb_substr($kh->ho_ten, 0, 1)) }}</div>
                            <div class="mini-info">
                                <div class="mini-title">{{ $kh->ho_ten }}</div>
                                <div class="mini-sub"><i class="fas fa-phone"></i> {{ $kh->so_dien_thoai }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted">Chưa có khách hàng mới.</div>
                    @endforelse
                </div>
            </div>

            {{-- Top Nhân viên (Chỉ Admin) --}}
            @if ($danhSachNhanVien)
                <div class="card">
                    <div class="card-header">
                        <div><i class="fas fa-trophy me-2 text-warning"></i>Top Nhân Viên Tương Tác</div>
                    </div>
                    <div class="card-body p-0 px-3">
                        @foreach ($danhSachNhanVien as $nv)
                            <div class="mini-list-item">
                                <div class="mini-avatar" style="background: linear-gradient(135deg, #f59e0b, #ea580c);">
                                    {{ mb_strtoupper(mb_substr($nv->ho_ten, 0, 1)) }}
                                </div>
                                <div class="mini-info">
                                    <div class="mini-title">{{ $nv->ho_ten }}</div>
                                    <div class="mini-sub">{{ $nv->so_bds }} BĐS · {{ $nv->so_lich_hen }} Lịch hẹn</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ── 1. Cấu hình màu sắc mặc định của Chart.js ──
            Chart.defaults.font.family = "'Be Vietnam Pro', sans-serif";
            Chart.defaults.color = '#6b7280';

            // ── 2. Render Biểu đồ 6 tháng (Bar & Line hỗn hợp) ──
            const ctx6Thang = document.getElementById('chart6Thang').getContext('2d');
            new Chart(ctx6Thang, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($chart6Thang['labels']) !!},
                    datasets: [{
                            label: 'BĐS Bán được',
                            type: 'line',
                            data: {!! json_encode($chart6Thang['ban']) !!},
                            borderColor: '#10b981',
                            backgroundColor: '#10b981',
                            borderWidth: 2,
                            tension: 0.3,
                            yAxisID: 'y'
                        },
                        {
                            label: 'BĐS Thêm mới',
                            type: 'bar',
                            data: {!! json_encode($chart6Thang['them']) !!},
                            backgroundColor: 'rgba(59, 130, 246, 0.8)',
                            borderRadius: 4,
                            yAxisID: 'y'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    plugins: {
                        legend: {
                            position: 'top'
                        },
                        tooltip: {
                            padding: 10,
                            cornerRadius: 8
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                borderDash: [4, 4]
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });

            // ── 3. Render Biểu đồ Tròn Phân Loại BĐS ──
            const ctxPhanLoai = document.getElementById('chartPhanLoai').getContext('2d');
            new Chart(ctxPhanLoai, {
                type: 'doughnut',
                data: {
                    labels: ['Bán (Còn hàng)', 'Bán (Đã bán)', 'Thuê (Còn trống)', 'Thuê (Đã thuê)'],
                    datasets: [{
                        data: [
                            {{ $bdsByLoai['ban_con_hang'] }},
                            {{ $bdsByLoai['ban_da_ban'] }},
                            {{ $bdsByLoai['thue_con_hang'] }},
                            {{ $bdsByLoai['thue_da_thue'] }}
                        ],
                        backgroundColor: [
                            '#3b82f6', // Blue
                            '#1a3c5e', // Navy
                            '#10b981', // Green
                            '#8b5cf6' // Purple
                        ],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 12,
                                padding: 15
                            }
                        }
                    }
                }
            });
        });
    </script>
@endpush
