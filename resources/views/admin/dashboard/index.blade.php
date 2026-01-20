@extends('admin.layout.master')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tổng Quan Hệ Thống</h1>
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Xuất Báo Cáo</a>
    </div>

    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Tổng Số Bất Động Sản</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['tong_bds'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-building fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Khách Hàng Liên Hệ</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['tong_lien_he'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-comments fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Lịch Xem Nhà</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['tong_lich_hen'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Tin Tức & Bài Viết</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['tong_bai_viet'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-newspaper fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Tỷ Lệ Lịch Hẹn</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="myPieChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        <span class="mr-2"><i class="fas fa-circle text-warning"></i> Mới đặt</span>
                        <span class="mr-2"><i class="fas fa-circle text-primary"></i> Đã xác nhận</span>
                        <span class="mr-2"><i class="fas fa-circle text-success"></i> Hoàn thành</span>
                        <span class="mr-2"><i class="fas fa-circle text-danger"></i> Đã hủy</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Top Bất Động Sản Được Quan Tâm Nhất</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Mã Căn</th>
                                    <th>Tiêu Đề</th>
                                    <th>Giá</th>
                                    <th>Lượt Xem</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topViewed as $bds)
                                <tr>
                                    <td>{{ $bds->ma_can }}</td>
                                    <td><a href="{{ route('bat-dong-san.show', $bds->id) }}" target="_blank">{{ Str::limit($bds->tieu_de, 40) }}</a></td>
                                    <td>{{ number_format($bds->gia, 2) }} Tỷ</td>
                                    <td class="text-center font-weight-bold text-success">{{ $bds->luot_xem }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Set new default font family and font color to mimic Bootstrap's default styling
    Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
    Chart.defaults.global.defaultFontColor = '#858796';

    // Pie Chart Example
    var ctx = document.getElementById("myPieChart");
    var myPieChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: ["Mới đặt", "Đã xác nhận", "Hoàn thành", "Hủy"],
        datasets: [{
        data: @json($chartData), // Dữ liệu từ Controller truyền sang
        backgroundColor: ['#f6c23e', '#4e73df', '#1cc88a', '#e74a3b'],
        hoverBackgroundColor: ['#dda20a', '#2e59d9', '#17a673', '#be2617'],
        hoverBorderColor: "rgba(234, 236, 244, 1)",
        }],
    },
    options: {
        maintainAspectRatio: false,
        tooltips: {
        backgroundColor: "rgb(255,255,255)",
        bodyFontColor: "#858796",
        borderColor: '#dddfeb',
        borderWidth: 1,
        xPadding: 15,
        yPadding: 15,
        displayColors: false,
        caretPadding: 10,
        },
        legend: {
        display: false
        },
        cutoutPercentage: 80,
    },
    });
</script>
@endsection