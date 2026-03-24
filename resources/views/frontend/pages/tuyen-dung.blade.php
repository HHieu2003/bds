@extends('frontend.layouts.master')

@section('title', 'Tuyển Dụng Nhân Tài - Thành Công Land')
@section('meta_description', 'Gia nhập Thành Công Land - Môi trường làm việc chuyên nghiệp, hoa hồng hấp dẫn nhất thị
    trường bất động sản. Khám phá các vị trí đang tuyển dụng.')

@section('content')

    {{-- 1. HERO SECTION --}}
    <section class="position-relative py-5"
        style="background: linear-gradient(135deg, #0F172A 0%, #1A2948 100%); min-height: 400px; display: flex; align-items: center;">
        <div class="position-absolute inset-0 w-100 h-100"
            style="background-image: url('https://images.unsplash.com/photo-1560518883-ce09059eeffa?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80'); background-size: cover; background-position: center; opacity: 0.15;">
        </div>
        <div class="container position-relative z-1 text-center">
            <span class="badge px-3 py-2 rounded-pill mb-3"
                style="background-color: rgba(255, 140, 66, 0.2); color: #FF8C42; border: 1px solid #FF8C42;">Cơ hội nghề
                nghiệp 2026</span>
            <h1 class="display-4 fw-bold text-white mb-3 serif-font">Kiến Tạo Sự Nghiệp <br> <span style="color: #FF8C42;">Bất
                    Động Sản Đỉnh Cao</span></h1>
            <p class="text-white-50 fs-5 mx-auto mb-4" style="max-width: 700px;">
                Tại Thành Công Land, chúng tôi không chỉ cung cấp cho bạn một công việc, chúng tôi trao cho bạn cơ hội làm
                chủ tài chính và phát triển bản thân không giới hạn.
            </p>
            <a href="#danh-sach-viec-lam" class="btn rounded-pill px-5 py-3 fw-bold text-white shadow hover-up"
                style="background-color: #FF8C42;">
                Xem Vị Trí Tuyển Dụng <i class="fas fa-arrow-down ms-2"></i>
            </a>
        </div>
    </section>

    {{-- 2. TẠI SAO CHỌN CHÚNG TÔI (CORE VALUES) --}}
    <section class="py-5 bg-light">
        <div class="container py-4">
            <div class="text-center mb-5">
                <h2 class="fw-bold serif-font text-dark mb-2">Đặc Quyền Của Bạn</h2>
                <div class="mx-auto bg-primary rounded"
                    style="width: 60px; height: 4px; background-color: #FF8C42 !important;"></div>
            </div>

            <div class="row g-4">
                {{-- Box 1 --}}
                <div class="col-md-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100 rounded-4 text-center p-4 hover-up bg-white">
                        <div class="mx-auto d-flex align-items-center justify-content-center rounded-circle mb-3"
                            style="width: 70px; height: 70px; background-color: #fff5ef; color: #FF8C42;">
                            <i class="fas fa-money-bill-wave fs-2"></i>
                        </div>
                        <h5 class="fw-bold mb-2 text-dark">Thu Nhập Đột Phá</h5>
                        <p class="text-muted small mb-0">Lương cứng hấp dẫn + Hoa hồng lũy tiến cao nhất thị trường lên đến
                            60%. Thưởng nóng từng giao dịch.</p>
                    </div>
                </div>
                {{-- Box 2 --}}
                <div class="col-md-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100 rounded-4 text-center p-4 hover-up bg-white">
                        <div class="mx-auto d-flex align-items-center justify-content-center rounded-circle mb-3"
                            style="width: 70px; height: 70px; background-color: #eff6ff; color: #0068FF;">
                            <i class="fas fa-chalkboard-teacher fs-2"></i>
                        </div>
                        <h5 class="fw-bold mb-2 text-dark">Đào Tạo Chuyên Sâu</h5>
                        <p class="text-muted small mb-0">Được coaching trực tiếp bởi các Giám đốc dự án dày dặn kinh nghiệm.
                            Đào tạo Digital Marketing, kỹ năng chốt sale.</p>
                    </div>
                </div>
                {{-- Box 3 --}}
                <div class="col-md-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100 rounded-4 text-center p-4 hover-up bg-white">
                        <div class="mx-auto d-flex align-items-center justify-content-center rounded-circle mb-3"
                            style="width: 70px; height: 70px; background-color: #ecfdf5; color: #10B981;">
                            <i class="fas fa-database fs-2"></i>
                        </div>
                        <h5 class="fw-bold mb-2 text-dark">Nguồn Hàng Độc Quyền</h5>
                        <p class="text-muted small mb-0">Sở hữu giỏ hàng độc quyền, quỹ căn đẹp nhất từ các chủ đầu tư lớn:
                            Vinhomes, Masterise, Sun Group...</p>
                    </div>
                </div>
                {{-- Box 4 --}}
                <div class="col-md-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100 rounded-4 text-center p-4 hover-up bg-white">
                        <div class="mx-auto d-flex align-items-center justify-content-center rounded-circle mb-3"
                            style="width: 70px; height: 70px; background-color: #f5f3ff; color: #8B5CF6;">
                            <i class="fas fa-chart-line fs-2"></i>
                        </div>
                        <h5 class="fw-bold mb-2 text-dark">Lộ Trình Thăng Tiến</h5>
                        <p class="text-muted small mb-0">Cơ hội thăng tiến rõ ràng sau 6 tháng lên Trưởng nhóm, Giám đốc
                            sàn. Phát triển năng lực quản lý toàn diện.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 3. DANH SÁCH VỊ TRÍ TUYỂN DỤNG --}}
    <section id="danh-sach-viec-lam" class="py-5">
        <div class="container py-4">
            <div class="row align-items-center mb-5">
                <div class="col-md-8">
                    <h2 class="fw-bold serif-font text-dark mb-2">Vị Trí Đang Mở</h2>
                    <p class="text-muted mb-0">Hãy chọn vị trí phù hợp với thế mạnh của bạn và gia nhập đội ngũ tinh nhuệ
                        của chúng tôi.</p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <a href="mailto:tuyendung@thanhcongland.com" class="btn btn-outline-dark rounded-pill fw-bold px-4">
                        <i class="fas fa-envelope me-2" style="color: #FF8C42;"></i> Gửi CV Trực Tiếp
                    </a>
                </div>
            </div>

            <div class="row g-4">
                {{-- Vị trí 1 --}}
                <div class="col-12">
                    <div class="card border border-light shadow-sm rounded-4 hover-up bg-white overflow-hidden p-0">
                        <div class="row g-0 align-items-center">
                            <div class="col-md-8 p-4 p-md-5 border-end">
                                <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill mb-3 fw-semibold">Hot
                                    - Tuyển Gấp 10 NV</span>
                                <h3 class="fw-bold text-dark mb-3">Chuyên Viên Kinh Doanh Bất Động Sản (Sale)</h3>
                                <ul class="text-muted mb-0 ps-3" style="line-height: 1.8;">
                                    <li>Tìm kiếm, khai thác và tư vấn khách hàng mua/thuê các dự án BĐS cao cấp.</li>
                                    <li>Chăm sóc khách hàng hiện tại và phát triển mạng lưới khách hàng mới.</li>
                                    <li><strong class="text-dark">Yêu cầu:</strong> Đam mê kinh doanh, giao tiếp tốt, có
                                        laptop cá nhân. Không yêu cầu kinh nghiệm (sẽ được đào tạo từ A-Z).</li>
                                </ul>
                            </div>
                            <div
                                class="col-md-4 p-4 text-center bg-light h-100 d-flex flex-column justify-content-center align-items-center">
                                <h5 class="fw-bold mb-1" style="color: #FF8C42;">Lương Cứng: 6 - 10 Triệu</h5>
                                <p class="text-muted small mb-4">+ Hoa hồng 3% - 5% / Giao dịch</p>
                                <button class="btn w-100 rounded-pill text-white fw-bold py-3 shadow-sm btn-ung-tuyen"
                                    style="background-color: #0F172A;">
                                    Ứng Tuyển Vị Trí Này
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Vị trí 2 --}}
                <div class="col-12">
                    <div class="card border border-light shadow-sm rounded-4 hover-up bg-white overflow-hidden p-0">
                        <div class="row g-0 align-items-center">
                            <div class="col-md-8 p-4 p-md-5 border-end">
                                <span
                                    class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill mb-3 fw-semibold">Khối
                                    Văn Phòng</span>
                                <h3 class="fw-bold text-dark mb-3">Chuyên Viên Digital Marketing</h3>
                                <ul class="text-muted mb-0 ps-3" style="line-height: 1.8;">
                                    <li>Lên kế hoạch và thực thi các chiến dịch quảng cáo Facebook Ads, Google Ads cho các
                                        dự án.</li>
                                    <li>Quản trị nội dung Website, Fanpage, Tiktok của công ty.</li>
                                    <li><strong class="text-dark">Yêu cầu:</strong> Có ít nhất 1 năm kinh nghiệm chạy Ads
                                        (Ưu tiên đã làm mảng BĐS), tư duy hình ảnh và ngôn từ tốt.</li>
                                </ul>
                            </div>
                            <div
                                class="col-md-4 p-4 text-center bg-light h-100 d-flex flex-column justify-content-center align-items-center">
                                <h5 class="fw-bold mb-1" style="color: #FF8C42;">Thu nhập: 12 - 20 Triệu</h5>
                                <p class="text-muted small mb-4">+ Thưởng theo hiệu quả Data</p>
                                <button class="btn w-100 rounded-pill text-white fw-bold py-3 shadow-sm btn-ung-tuyen"
                                    style="background-color: #0F172A;">
                                    Ứng Tuyển Vị Trí Này
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- MODAL ỨNG TUYỂN NHANH --}}
    <div class="modal fade" id="modalUngTuyen" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow-lg">
                <div class="modal-header border-bottom-0 p-4 pb-0">
                    <h5 class="modal-title fw-bold text-dark fs-4">Gửi Hồ Sơ Ứng Tuyển</h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="text-muted small mb-4">Để lại thông tin, Bộ phận Nhân sự sẽ liên hệ với bạn trong vòng 24h
                        làm việc.</p>
                    <form action="#" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-dark">Họ và tên *</label>
                            <input type="text" class="form-control rounded-3 bg-light border-0 py-2 shadow-none"
                                required placeholder="Nguyễn Văn A">
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label class="form-label fw-semibold small text-dark">Số điện thoại *</label>
                                <input type="text" class="form-control rounded-3 bg-light border-0 py-2 shadow-none"
                                    required placeholder="09xx...">
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold small text-dark">Năm sinh</label>
                                <input type="number" class="form-control rounded-3 bg-light border-0 py-2 shadow-none"
                                    placeholder="199x">
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold small text-dark">Link CV của bạn (Google Drive, TopCV...)
                                *</label>
                            <input type="url" class="form-control rounded-3 bg-light border-0 py-2 shadow-none"
                                required placeholder="Dán đường link CV vào đây...">
                        </div>
                        <button type="button" class="btn w-100 rounded-pill py-3 text-white fw-bold shadow-sm"
                            style="background-color: #FF8C42;"
                            onclick="alert('Đã gửi hồ sơ thành công! Nhân sự sẽ sớm liên hệ với bạn.')"
                            data-bs-dismiss="modal">
                            Nộp Hồ Sơ Ngay <i class="fas fa-paper-plane ms-2"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- CSS TÙY CHỈNH --}}
    <style>
        .hover-up {
            transition: all 0.3s ease;
        }

        .hover-up:hover {
            transform: translateY(-7px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08) !important;
        }

        .btn-ung-tuyen {
            transition: 0.3s;
        }

        .btn-ung-tuyen:hover {
            background-color: #FF8C42 !important;
            color: white !important;
            transform: translateY(-3px);
        }
    </style>

    {{-- JS KÍCH HOẠT MODAL --}}
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const btns = document.querySelectorAll('.btn-ung-tuyen');
                const myModal = new bootstrap.Modal(document.getElementById('modalUngTuyen'));

                btns.forEach(btn => {
                    btn.addEventListener('click', () => {
                        myModal.show();
                    });
                });
            });
        </script>
    @endpush

@endsection
