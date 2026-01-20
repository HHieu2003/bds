@extends('frontend.layouts.master')
@section('title', $batDongSan->tieu_de)

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('du-an.show', $batDongSan->duAn->id) }}">{{ $batDongSan->duAn->ten_du_an }}</a></li>
            <li class="breadcrumb-item active">{{ $batDongSan->ma_can }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-8">
            <div id="bdsCarousel" class="carousel slide bg-black rounded-4 overflow-hidden mb-4" data-bs-ride="carousel">
                <div class="carousel-inner" style="height: 500px;">
                    @if($batDongSan->hinh_anh && count($batDongSan->hinh_anh) > 0)
                        @foreach($batDongSan->hinh_anh as $index => $file)
                            @php
                                $ext = pathinfo($file, PATHINFO_EXTENSION);
                                $isVideo = in_array(strtolower($ext), ['mp4', 'mov', 'avi']);
                            @endphp
                            <div class="carousel-item h-100 {{ $index == 0 ? 'active' : '' }}">
                                @if($isVideo)
                                    <video controls class="d-block w-100 h-100" style="object-fit: contain;">
                                        <source src="{{ asset('storage/'.$file) }}">
                                    </video>
                                @else
                                    <img src="{{ asset('storage/'.$file) }}" class="d-block w-100 h-100" style="object-fit: contain;">
                                @endif
                            </div>
                        @endforeach
                    @else
                        <div class="carousel-item active h-100 d-flex align-items-center justify-content-center text-white">Chưa có hình ảnh</div>
                    @endif
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#bdsCarousel" data-bs-slide="prev"><span class="carousel-control-prev-icon"></span></button>
                <button class="carousel-control-next" type="button" data-bs-target="#bdsCarousel" data-bs-slide="next"><span class="carousel-control-next-icon"></span></button>
            </div>

            <div class="card border-0 shadow-sm p-4 mb-4 rounded-4">
                <h2 class="fw-bold mb-3 serif-font">{{ $batDongSan->tieu_de }}</h2>
                <h3 class="text-danger fw-bold mb-3">{{ number_format($batDongSan->gia) }} VNĐ</h3>
                
                <div class="row g-3 mb-4 p-3 bg-light rounded">
                    <div class="col-6 col-md-3"><strong>Tòa:</strong> {{ $batDongSan->toa }}</div>
                    <div class="col-6 col-md-3"><strong>Mã:</strong> {{ $batDongSan->ma_can }}</div>
                    <div class="col-6 col-md-3"><strong>DT:</strong> {{ $batDongSan->dien_tich }}m²</div>
                    <div class="col-6 col-md-3"><strong>Phòng:</strong> {{ $batDongSan->phong_ngu }}</div>
                    <div class="col-6 col-md-3"><strong>Hướng:</strong> {{ $batDongSan->huong_cua }}</div>
                </div>

                <h5 class="fw-bold border-bottom pb-2 mb-3">Mô tả chi tiết</h5>
                <div class="text-secondary" style="white-space: pre-line;">{{ $batDongSan->mo_ta }}</div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-lg p-4 rounded-4 position-sticky" style="top: 100px;">
                <div class="text-center mb-3">
                    <img src="https://ui-avatars.com/api/?name={{ $batDongSan->user->name }}" class="rounded-circle mb-2">
                    <h5 class="fw-bold">{{ $batDongSan->user->name }}</h5>
                    <p class="text-muted small">Chuyên viên tư vấn</p>
                </div>
                <div class="d-grid gap-2">
                    <a href="tel:0912345678" class="btn btn-danger fw-bold py-2"><i class="fa-solid fa-phone me-2"></i> Gọi điện</a>
                    <a href="#" class="btn btn-primary fw-bold py-2">Chat Zalo</a>
                </div>
                <button type="button" class="btn btn-outline-danger rounded-pill px-3 ms-2" onclick="toggleFavorite({{ $batDongSan->id }})" id="btn-favorite-{{ $batDongSan->id }}">
    @php
        // Kiểm tra xem khách hiện tại đã like chưa để hiện icon phù hợp
        $isLiked = false;
        if(Auth::check()) {
            $isLiked = \App\Models\YeuThich::where('bat_dong_san_id', $batDongSan->id)->where('user_id', Auth::id())->exists();
        } else {
            $isLiked = \App\Models\YeuThich::where('bat_dong_san_id', $batDongSan->id)->where('session_id', Session::getId())->exists();
        }
    @endphp
    
    @if($isLiked)
        <i class="fas fa-heart"></i> Đã Lưu
    @else
        <i class="far fa-heart"></i> Lưu Tin
    @endif
</button>
                <button type="button" class="btn btn-warning fw-bold text-white rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#bookingModal">
    <i class="far fa-calendar-check me-2"></i> Đặt Lịch Xem Nhà
</button>
<button type="button" class="btn btn-outline-dark rounded-pill px-3 ms-2" onclick="openCompareModal()">
    <i class="fas fa-exchange-alt"></i> So Sánh
</button>
            </div>
        </div>
    </div>
</div>


<div class="card border-0 shadow-sm rounded-4 mt-4 overflow-hidden">
    <div class="card-header bg-white border-bottom-0 pt-4 px-4">
        <h4 class="fw-bold" style="color: #0F172A;">
            <i class="fas fa-calculator me-2 text-primary"></i>Dự Toán Chi Phí & Trả Góp
        </h4>
        <ul class="nav nav-pills mt-3" id="financeTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active rounded-pill fw-bold px-4" id="loan-tab" data-bs-toggle="tab" data-bs-target="#loan-content" type="button" role="tab">
                    <i class="fas fa-university me-1"></i> Tính Lãi Vay
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link rounded-pill fw-bold px-4 ms-2" id="tax-tab" data-bs-toggle="tab" data-bs-target="#tax-content" type="button" role="tab">
                    <i class="fas fa-file-invoice-dollar me-1"></i> Phí Sang Tên
                </button>
            </li>
        </ul>
    </div>

    <div class="card-body p-4">
        <div class="tab-content">
            
            <div class="tab-pane fade show active" id="loan-content" role="tabpanel">
                <div class="row g-4">
                    <div class="col-lg-5">
                        <div class="bg-light p-4 rounded-4">
                            <label class="form-label fw-bold small text-muted">Giá trị bất động sản</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control fw-bold text-primary" id="loan_price" value="{{ number_format($batDongSan ->gia * 1000000000, 0, ',', '.') }}" readonly>
                                <span class="input-group-text">VNĐ</span>
                            </div>

                            <label class="form-label fw-bold small text-muted">Tỷ lệ vay (%)</label>
                            <input type="range" class="form-range" id="loan_rate_range" min="0" max="80" step="5" value="70" oninput="updateLoanInput(this.value)">
                            <div class="d-flex justify-content-between mb-3">
                                <span class="small text-muted">0%</span>
                                <span class="fw-bold text-primary" id="loan_rate_display">70%</span>
                                <span class="small text-muted">80%</span>
                            </div>

                            <div class="row g-2">
                                <div class="col-6">
                                    <label class="form-label fw-bold small text-muted">Thời hạn (Năm)</label>
                                    <input type="number" class="form-control" id="loan_year" value="20">
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-bold small text-muted">Lãi suất (%/năm)</label>
                                    <input type="number" class="form-control" id="loan_interest" value="8.5" step="0.1">
                                </div>
                            </div>

                            <button onclick="calculateLoan()" class="btn btn-primary w-100 rounded-pill fw-bold mt-4">
                                <i class="fas fa-sync-alt me-1"></i> Tính Toán Ngay
                            </button>
                        </div>
                    </div>

                    <div class="col-lg-7">
                        <div class="border rounded-4 p-4 h-100 d-flex flex-column justify-content-center text-center" id="loan_result">
                            <img src="https://cdn-icons-png.flaticon.com/512/2666/2666508.png" width="80" class="mx-auto mb-3 opacity-50">
                            <h6 class="text-muted">Nhập thông tin và bấm "Tính Toán" để xem kết quả trả góp hàng tháng.</h6>
                        </div>
                        
                        <div class="d-none" id="loan_result_show">
                            <div class="row text-center mb-4">
                                <div class="col-4">
                                    <small class="text-muted d-block">Số tiền vay</small>
                                    <strong class="text-dark fs-5" id="rs_amount">0</strong>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted d-block">Gốc hàng tháng</small>
                                    <strong class="text-info fs-5" id="rs_original">0</strong>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted d-block">Trả tháng đầu</small>
                                    <strong class="text-danger fs-5" id="rs_first_month">0</strong>
                                </div>
                            </div>
                            <div class="alert alert-warning border-0 small">
                                <i class="fas fa-info-circle me-1"></i> Kết quả tính toán chỉ mang tính chất tham khảo dựa trên dư nợ giảm dần. Lãi suất thực tế có thể thay đổi theo chính sách ngân hàng.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="tax-content" role="tabpanel">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Loại phí / Thuế</th>
                                        <th class="text-center" width="15%">Tỷ lệ</th>
                                        <th class="text-end" width="25%">Thành tiền (VNĐ)</th>
                                        <th>Ghi chú</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="fw-bold text-primary">Thuế thu nhập cá nhân</td>
                                        <td class="text-center">2%</td>
                                        <td class="text-end fw-bold" id="tax_tncn">0</td>
                                        <td class="small text-muted">Người bán đóng (Thường thỏa thuận)</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold text-success">Lệ phí trước bạ</td>
                                        <td class="text-center">0.5%</td>
                                        <td class="text-end fw-bold" id="tax_truocba">0</td>
                                        <td class="small text-muted">Người mua đóng (Giá đất NN hoặc Giá HĐ)</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Phí công chứng & Thẩm định</td>
                                        <td class="text-center">~ 0.1%</td>
                                        <td class="text-end fw-bold" id="tax_congchung">0</td>
                                        <td class="small text-muted">Ước tính theo khung nhà nước</td>
                                    </tr>
                                    <tr class="bg-primary bg-opacity-10">
                                        <td colspan="2" class="fw-bold text-uppercase">Tổng chi phí sang tên ước tính</td>
                                        <td class="text-end fw-bold text-danger fs-5" id="tax_total">0</td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-end mt-3">
                            <button onclick="calculateTax()" class="btn btn-success rounded-pill fw-bold">
                                <i class="fas fa-calculator me-1"></i> Tính Chi Phí
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="compareModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered"> <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header bg-white border-bottom-0">
                <h5 class="modal-title fw-bold text-uppercase" style="color: #0F172A;">
                    <i class="fas fa-balance-scale me-2 text-primary"></i>Bảng So Sánh
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0" id="compareModalBody">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-2 text-muted">Đang tải dữ liệu...</p>
                </div>
            </div>
            <div class="modal-footer border-top-0">
                <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<script>
// ===== HỆ THỐNG SO SÁNH BẤT ĐỘNG SẢN =====

// 1. Mở modal so sánh và thêm căn hiện tại vào danh sách
function openCompareModal() {
    // Thêm căn hiện tại vào so sánh
    fetch('/so-sanh/add/{{ $batDongSan->id }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) throw new Error('Failed to add item');
        return response.json();
    })
    .then(data => {
        console.log('Add result:', data);
        
        // Mở modal sau khi thêm thành công
        var myModal = new bootstrap.Modal(document.getElementById('compareModal'));
        myModal.show();
        
        // Load danh sách so sánh
        loadCompareTable();
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra. Vui lòng thử lại!');
    });
}

// 2. Load bảng so sánh từ server
function loadCompareTable() {
    fetch('/so-sanh/load-table', {
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => {
        if (!response.ok) throw new Error('Failed to load table');
        return response.text();
    })
    .then(html => {
        console.log('HTML received:', html);
        document.getElementById('compareModalBody').innerHTML = html;
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('compareModalBody').innerHTML = 
            '<div class="text-center py-5"><p class="text-danger">Có lỗi khi tải danh sách. Vui lòng F5 trang và thử lại.</p></div>';
    });
}

// 3. Xóa item khỏi danh sách so sánh
function removeCompareItem(id) {
    if (!confirm('Bỏ căn này khỏi so sánh?')) return;

    fetch('/so-sanh/remove/' + id, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) throw new Error('Failed to remove item');
        return response.json();
    })
    .then(data => {
        // Load lại bảng sau khi xóa
        loadCompareTable();
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi xóa. Vui lòng thử lại!');
    });
}
</script>


<script>
    // Hàm định dạng tiền tệ (VD: 1.000.000)
    function formatCurrency(number) {
        return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(number);
    }

    // --- LOGIC 1: TÍNH LÃI VAY ---
    function updateLoanInput(val) {
        document.getElementById('loan_rate_display').innerText = val + '%';
    }

    function calculateLoan() {
        // Lấy giá trị đầu vào
        let priceStr = document.getElementById('loan_price').value.replace(/\./g, '').replace(/,/g, ''); // Xóa dấu chấm
        let price = parseFloat(priceStr) || 0;
        
        let rate = parseFloat(document.getElementById('loan_rate_range').value) / 100; // Tỷ lệ vay (VD: 0.7)
        let years = parseFloat(document.getElementById('loan_year').value) || 0;
        let interestYear = parseFloat(document.getElementById('loan_interest').value) || 0;

        if (price <= 0 || years <= 0) {
            alert('Vui lòng kiểm tra lại giá trị và thời hạn vay!');
            return;
        }

        // Toán tử tài chính
        let loanAmount = price * rate; // Tổng tiền vay
        let months = years * 12; // Tổng số tháng
        let interestMonth = interestYear / 12 / 100; // Lãi suất tháng (VD: 0.007)

        // Tính theo dư nợ giảm dần (Gốc cố định)
        let originalPerMonth = loanAmount / months; // Gốc hàng tháng
        let firstMonthInterest = loanAmount * interestMonth; // Lãi tháng đầu
        let firstMonthTotal = originalPerMonth + firstMonthInterest; // Tổng trả tháng đầu

        // Hiển thị kết quả
        document.getElementById('rs_amount').innerText = formatCurrency(loanAmount);
        document.getElementById('rs_original').innerText = formatCurrency(originalPerMonth);
        document.getElementById('rs_first_month').innerText = formatCurrency(firstMonthTotal);

        // Ẩn ảnh chờ, hiện kết quả
        document.getElementById('loan_result').classList.add('d-none');
        document.getElementById('loan_result_show').classList.remove('d-none');
    }

    // --- LOGIC 2: TÍNH PHÍ SANG TÊN ---
    function calculateTax() {
        let priceStr = document.getElementById('loan_price').value.replace(/\./g, '').replace(/,/g, '');
        let price = parseFloat(priceStr) || 0;

        // Công thức tính
        let tncn = price * 0.02; // 2%
        let truocba = price * 0.005; // 0.5%
        let congchung = price * 0.001; // Ước tính 0.1% (Thực tế theo bậc thang)

        // Nếu phí trước bạ > 500tr thì lấy 500tr (Luật quy định trần, nhưng ở đây tính đơn giản)
        
        let total = tncn + truocba + congchung;

        document.getElementById('tax_tncn').innerText = formatCurrency(tncn);
        document.getElementById('tax_truocba').innerText = formatCurrency(truocba);
        document.getElementById('tax_congchung').innerText = formatCurrency(congchung);
        document.getElementById('tax_total').innerText = formatCurrency(total);
    }

    // Tự động chạy tính thuế khi load trang
    window.onload = function() {
        calculateTax();
    };
</script>

<div class="modal fade" id="bookingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header bg-primary text-white rounded-top-4">
                <h5 class="modal-title fw-bold">Đăng Ký Xem Nhà</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('lich-hen.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <input type="hidden" name="bat_dong_san_id" value="{{ $batDongSan->id }}">

                    <div class="mb-3">
                        <label class="form-label fw-bold text-secondary">Họ và Tên <span class="text-danger">*</span></label>
                        <input type="text" name="ten_khach_hang" class="form-control rounded-pill" required placeholder="Nhập tên của bạn">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold text-secondary">Số Điện Thoại <span class="text-danger">*</span></label>
                        <input type="text" name="sdt_khach_hang" class="form-control rounded-pill" required placeholder="Nhập SĐT liên hệ">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-secondary">Email (Tùy chọn)</label>
                        <input type="email" name="email_khach_hang" class="form-control rounded-pill" placeholder="Nhập email">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-secondary">Thời Gian Muốn Xem <span class="text-danger">*</span></label>
                        <input type="datetime-local" name="thoi_gian_hen" class="form-control rounded-pill" required>
                    </div>

                    <div class="alert alert-light border small text-muted">
                        <i class="fas fa-info-circle text-primary"></i> 
                        Bạn đang đặt lịch xem căn hộ: <strong>{{ $batDongSan->ma_can }}</strong><br>
                        Nhân viên của chúng tôi sẽ gọi lại để xác nhận lịch hẹn.
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">Gửi Yêu Cầu</button>
                </div>
            </form>
        </div>
    </div>
</div>



<script>
function toggleFavorite(id) {
    fetch('/yeu-thich/' + id, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        },
    })
    .then(response => response.json())
    .then(data => {
        let btn = document.getElementById('btn-favorite-' + id);
        if (data.status === 'added') {
            btn.innerHTML = '<i class="fas fa-heart"></i> Đã Lưu';
            btn.classList.add('btn-danger');
            btn.classList.remove('btn-outline-danger');
            alert('Đã thêm vào danh sách yêu thích!');
        } else {
            btn.innerHTML = '<i class="far fa-heart"></i> Lưu Tin';
            btn.classList.add('btn-outline-danger');
            btn.classList.remove('btn-danger');
            alert('Đã xóa khỏi danh sách yêu thích!');
        }
    })
    .catch(error => console.error('Error:', error));
}
</script>




@push('scripts')
<script>
    // Khai báo biến toàn cục: Đang ở Căn hộ ID ...
    window.chatContext = { 
        type: 'property', 
        id: {{ $batDongSan->id }} 
    };
</script>
@endpush






@endsection