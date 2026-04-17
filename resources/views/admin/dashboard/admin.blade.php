@extends('admin.layouts.master')

@section('title', 'Dashboard Admin')

@push('styles')
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

        /* Filter Bar Styles */
        .filter-bar {
            background: #fff;
            border-radius: 12px;
            padding: 1rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
            margin-bottom: 1.5rem;
            border: 1px solid var(--border);
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            align-items: center;
            justify-content: space-between;
        }

        .filter-group {
            display: flex;
            gap: 0.75rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 1.25rem;
            margin-bottom: 1.5rem;
        }

        .stat-card {
            text-decoration: none;
            padding: 1.25rem;
            border-radius: 12px;
            background: #fff;
            border: 1px solid var(--border);
            box-shadow: var(--sh-sm);
            transition: transform 0.2s, box-shadow 0.2s;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--sh-md);
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

    @php
        $trangThaiLabels = [
            'moi_dat' => 'Mới đặt',
            'sale_da_nhan' => 'Sale đã nhận',
            'cho_xac_nhan' => 'Chờ Nguồn chốt',
            'cho_sale_xac_nhan_doi_gio' => 'Chờ Sale chốt dời giờ',
            'da_xac_nhan' => 'Đã chốt đi xem',
            'hoan_thanh' => 'Hoàn thành',
            'tu_choi' => 'Từ chối',
            'huy' => 'Đã hủy',
        ];
    @endphp

    {{-- ── Header ── --}}
    <div class="dashboard-header">
        <div>
            <h1 class="dashboard-title">Tổng quan hệ thống</h1>
            <div class="dashboard-subtitle">Xin chào <strong>{{ $nhanVien->ho_ten }}</strong>
                ({{ $nhanVien->vai_tro_label }}) - {{ now()->translatedFormat('l, d/m/Y') }}</div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('nhanvien.admin.bat-dong-san.create') }}" class="btn btn-primary"><i
                    class="fas fa-plus me-1"></i> Thêm BĐS</a>
            <a href="{{ route('nhanvien.admin.lich-hen.create') }}" class="btn btn-secondary"><i
                    class="fas fa-calendar-plus me-1"></i> Tạo lịch hẹn</a>
        </div>
    </div>

    {{-- ── Filter Bar ── --}}
    <div class="filter-bar">
        <form method="GET" action="{{ route('nhanvien.dashboard') }}" id="formFilter" class="filter-group m-0 w-100">
            <div class="d-flex align-items-center gap-2">
                <i class="fas fa-filter text-muted"></i>
                <span class="fw-bold text-dark">Kỳ báo cáo:</span>
            </div>

            <select name="loai_loc" id="loai_loc" class="form-select form-select-sm" style="width: auto;"
                onchange="toggleFilterInputs()">
                <option value="ngay" {{ request('loai_loc') == 'ngay' ? 'selected' : '' }}>Theo Ngày</option>
                <option value="thang" {{ request('loai_loc', 'thang') == 'thang' ? 'selected' : '' }}>Theo Tháng</option>
                <option value="nam" {{ request('loai_loc') == 'nam' ? 'selected' : '' }}>Theo Năm</option>
            </select>

            <div id="wrap_ngay" class="filter-input" style="{{ request('loai_loc') == 'ngay' ? '' : 'display: none;' }}">
                <input type="date" name="ngay" class="form-control form-control-sm"
                    value="{{ request('ngay', now()->toDateString()) }}">
            </div>

            <div id="wrap_thang" class="filter-input"
                style="{{ request('loai_loc', 'thang') == 'thang' ? '' : 'display: none;' }}">
                <input type="month" name="thang" class="form-control form-control-sm"
                    value="{{ request('thang', now()->format('Y-m')) }}">
            </div>

            <div id="wrap_nam" class="filter-input" style="{{ request('loai_loc') == 'nam' ? '' : 'display: none;' }}">
                <input type="number" name="nam" class="form-control form-control-sm" min="2020" max="2099"
                    value="{{ request('nam', now()->year) }}">
            </div>

            <button type="submit" class="btn btn-sm btn-primary fw-bold px-3">Truy vấn</button>

            {{-- Nút Export CSV --}}
            <button type="button" class="btn btn-sm btn-success fw-bold ms-auto" onclick="exportData()"><i
                    class="fas fa-file-excel me-1"></i> Xuất Excel (CSV)</button>
        </form>
    </div>

    {{-- Hiển thị Nhãn Kỳ Báo Cáo --}}
    <div class="mb-3 text-muted fw-bold"><i class="fas fa-chart-line me-1"></i> Số liệu phát sinh trong: <span
            class="text-primary">{{ $labelKyBaoCao }}</span></div>

    {{-- ── 8 Stat Cards (Dữ liệu Đã được lọc) ── --}}
    <div class="stat-grid">
        <a href="{{ route('nhanvien.admin.bat-dong-san.index') }}" class="stat-card">
            <div class="stat-icon orange"><i class="fas fa-building"></i></div>
            <div>
                <div class="stat-num">{{ number_format($tongQuan['bds_moi']) }}</div>
                <div class="stat-label">BĐS Mới (Kỳ này)</div>
            </div>
        </a>
        <a href="{{ route('nhanvien.admin.bat-dong-san.index') }}?trang_thai=da_ban" class="stat-card">
            <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
            <div>
                <div class="stat-num">{{ number_format($tongQuan['bds_da_ban']) }}</div>
                <div class="stat-label">BĐS Đã chốt/bán</div>
            </div>
        </a>
        <a href="{{ route('nhanvien.admin.lien-he.index') }}" class="stat-card">
            <div class="stat-icon red"><i class="fas fa-bullhorn"></i></div>
            <div>
                <div class="stat-num">{{ number_format($tongQuan['yeu_cau_moi']) }}</div>
                <div class="stat-label">Yêu cầu LH mới</div>
            </div>
        </a>
        <a href="{{ route('nhanvien.admin.khach-hang.index') }}" class="stat-card">
            <div class="stat-icon navy"><i class="fas fa-users"></i></div>
            <div>
                <div class="stat-num">{{ number_format($tongQuan['khach_hang_moi']) }}</div>
                <div class="stat-label">Khách hàng mới</div>
            </div>
        </a>
        <a href="{{ route('nhanvien.admin.lich-hen.index') }}" class="stat-card">
            <div class="stat-icon blue"><i class="fas fa-calendar-day"></i></div>
            <div>
                <div class="stat-num">{{ number_format($tongQuan['lich_hen_trong_ky']) }}</div>
                <div class="stat-label">Lịch đi xem nhà</div>
            </div>
        </a>
        <a href="{{ route('nhanvien.admin.lich-hen.index') }}" class="stat-card">
            <div class="stat-icon purple"><i class="fas fa-flag-checkered"></i></div>
            <div>
                <div class="stat-num">{{ number_format($tongQuan['lich_chot']) }}</div>
                <div class="stat-label">Lịch chốt thành công</div>
            </div>
        </a>
        <a href="{{ route('nhanvien.admin.ky-gui.index') }}" class="stat-card">
            <div class="stat-icon pink"><i class="fas fa-file-signature"></i></div>
            <div>
                <div class="stat-num">{{ number_format($tongQuan['ky_gui_moi']) }}</div>
                <div class="stat-label">Ký gửi mới</div>
            </div>
        </a>
        <a href="{{ route('nhanvien.admin.du-an.index') }}" class="stat-card">
            <div class="stat-icon teal"><i class="fas fa-city"></i></div>
            <div>
                <div class="stat-num">{{ number_format($tongQuan['du_an_moi']) }}</div>
                <div class="stat-label">Dự án mới</div>
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
                    <div><i class="fas fa-chart-area me-2"></i>Thống kê BĐS (6 Tháng tính từ kỳ báo cáo)</div>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="chart6Thang"></canvas>
                    </div>
                </div>
            </div>

            {{-- Lịch hẹn hôm nay (Bảng theo dõi nóng) --}}
            <div class="card">
                <div class="card-header">
                    <div><i class="fas fa-calendar-check me-2"></i>Theo dõi Lịch hẹn Hôm nay</div>
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
                                        <td class="fw-bold">{{ $lh->khachHang?->ho_ten ?? $lh->ten_khach_hang }}</td>
                                        <td class="text-muted"><i class="fas fa-map-marker-alt me-1 text-danger"></i>
                                            {{ $lh->batDongSan?->tieu_de ?? 'Chưa xác định' }}</td>
                                        <td>
                                            @php
                                                $lbl = $trangThaiLabels[$lh->trang_thai] ?? $lh->trang_thai;
                                                $badgeClass = match ($lh->trang_thai) {
                                                    'moi_dat' => 'bg-info text-dark',
                                                    'sale_da_nhan' => 'bg-primary',
                                                    'cho_xac_nhan',
                                                    'cho_sale_xac_nhan_doi_gio'
                                                        => 'bg-warning text-dark',
                                                    'da_xac_nhan' => 'bg-success',
                                                    'hoan_thanh' => 'bg-secondary',
                                                    'tu_choi', 'huy' => 'bg-danger',
                                                    default => 'bg-dark',
                                                };
                                            @endphp
                                            <span class="badge {{ $badgeClass }}">{{ $lbl }}</span>
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
                    <div><i class="fas fa-home me-2"></i>Bất động sản mới cập nhật hệ thống</div>
                </div>
                <div class="card-body p-3">
                    <div class="row g-3">
                        @forelse($bdsMoiNhat as $bds)
                            @php
                                $albumAnh = is_array($bds->album_anh)
                                    ? $bds->album_anh
                                    : json_decode($bds->album_anh ?? '[]', true);
                                $hinhAnhChinh = $bds->hinh_anh;
                                if (!empty($albumAnh) && is_array($albumAnh)) {
                                    $hinhAnhChinh = $albumAnh[0];
                                }

                                $thumb = $hinhAnhChinh
                                    ? asset('storage/' . ltrim($hinhAnhChinh, '/'))
                                    : asset('images/no-image.jpg');

                                $giaHienThi = 'Thỏa thuận';
                                if (($bds->nhu_cau ?? null) === 'thue') {
                                    $giaHienThi = $bds->gia_thue
                                        ? number_format((float) $bds->gia_thue, 0, ',', '.') . ' đ/tháng'
                                        : 'Thỏa thuận';
                                } else {
                                    $giaHienThi = $bds->gia
                                        ? number_format((float) $bds->gia, 0, ',', '.') . ' đ'
                                        : 'Thỏa thuận';
                                }
                            @endphp
                            <div class="col-12 col-md-6">
                                <div class="mini-list-item p-2 rounded"
                                    style="background: var(--bg-alt); border: 1px solid var(--border);">
                                    <img src="{{ $thumb }}" class="mini-thumb" alt="thumb">
                                    <div class="mini-info">
                                        <div class="mini-title">{{ $bds->tieu_de }}</div>
                                        <div class="mini-sub d-flex justify-content-between pe-2">
                                            <div>
                                                <strong class="text-danger">{{ $giaHienThi }}</strong>
                                                <span class="ms-2 small text-muted">|
                                                    {{ $bds->nhanVienPhuTrach->ho_ten ?? 'Nguồn ẩn' }}</span>
                                            </div>
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

            {{-- Biểu đồ Tròn: Phân loại BĐS (Tổng kho hiện tại) --}}
            <div class="card">
                <div class="card-header">
                    <div><i class="fas fa-chart-pie me-2"></i>Tỷ trọng Tổng kho BĐS</div>
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
                    <div><i class="fas fa-file-signature me-2"></i>Ký gửi cần duyệt</div>
                </div>
                <div class="card-body p-0 px-3">
                    @forelse($kyGuiChoDuyet as $kg)
                        <div class="mini-list-item">
                            <div class="mini-info">
                                <div class="mini-title">
                                    {{ $kg->khachHang?->ho_ten ?? ($kg->ho_ten_chu_nha ?? 'Khách hàng') }}</div>
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
                    <div><i class="fas fa-user-plus me-2"></i>Khách hàng mới nhất</div>
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

            {{-- Top Nhân viên --}}
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
                                    <div class="mini-sub text-truncate">
                                        <span class="text-primary fw-bold">{{ $nv->so_bds }}</span> BĐS ·
                                        <span class="text-success fw-bold">{{ $nv->so_lich_hen }}</span> Lịch ·
                                        <span class="text-info fw-bold">{{ $nv->so_ky_gui }}</span> Ký gửi
                                    </div>
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Xử lý chuyển đổi Form Bộ Lọc
        function toggleFilterInputs() {
            let val = document.getElementById('loai_loc').value;
            document.querySelectorAll('.filter-input').forEach(el => el.style.display = 'none');
            document.getElementById('wrap_' + val).style.display = 'block';
        }

        // Xử lý nút Xuất CSV
        function exportData() {
            const form = document.getElementById('formFilter');
            const url = new URL(form.action, window.location.origin);
            const formData = new FormData(form);

            formData.forEach((value, key) => {
                if (value !== null && value !== '') {
                    url.searchParams.set(key, value);
                }
            });

            url.searchParams.set('export', 'csv');
            window.location.href = url.toString();
        }

        document.addEventListener('DOMContentLoaded', function() {
            Chart.defaults.font.family = "'Be Vietnam Pro', sans-serif";
            Chart.defaults.color = '#6b7280';

            // ── 2. Render Biểu đồ 6 tháng ──
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
                        backgroundColor: ['#3b82f6', '#1a3c5e', '#10b981', '#8b5cf6'],
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
