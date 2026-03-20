@extends('frontend.layouts.master')

@section('title', 'Về Chúng Tôi - Thành Công Land')

@section('content')

    {{-- 1. HERO SECTION (Banner Giới Thiệu) --}}
    <section class="position-relative d-flex align-items-center justify-content-center"
        style="height: 60vh; margin-top: -76px; background: linear-gradient(rgba(15, 23, 42, 0.75), rgba(15, 23, 42, 0.75)), url('/images/anh-nhan-vien-cong-ty-Thanh-Cong-Land-1536x702.webp') center/cover fixed;">
        <div class="container text-center text-white" data-aos="zoom-in" data-aos-duration="1000">
            <span class="badge rounded-pill px-3 py-2 mb-3 fw-bold"
                style="background-color: rgba(255, 140, 66, 0.2); color: #FF8C42; border: 1px solid #FF8C42;">
                VỀ CHÚNG TÔI
            </span>
            <h1 class="display-3 fw-bold serif-font mb-3">Thành Công Land</h1>
            <p class="fs-5 fw-light mx-auto opacity-75" style="max-width: 700px;">
                Hơn 10 năm kiến tạo giá trị, mang đến những giải pháp bất động sản toàn diện và không gian sống hoàn hảo cho
                hàng ngàn gia đình Việt.
            </p>
        </div>
        {{-- Sóng trang trí đáy banner --}}
        <div class="position-absolute bottom-0 start-0 w-100 overflow-hidden" style="line-height: 0;">
            <svg viewBox="0 0 1200 120" preserveAspectRatio="none" style="width: 100%; height: 50px; fill: #ffffff;">
                <path
                    d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V120H0V95.8C59.71,118.08,130.83,123.63,200.27,112.55Z">
                </path>
            </svg>
        </div>
    </section>

    {{-- 2. CÂU CHUYỆN THƯƠNG HIỆU --}}
    <section class="py-5" style="background-color: #ffffff;">
        <div class="container py-5">
            <div class="row align-items-center g-5">
                {{-- Hình ảnh chồng chéo (Overlap Images) --}}
                <div class="col-lg-6 position-relative" data-aos="fade-right">
                    <img src="https://thanhcongland.com.vn/wp-content/uploads/2025/11/anh-nhan-vien-cong-ty-Thanh-Cong-Land-3-1536x1024.webp"
                        class="img-fluid rounded-4 shadow-lg w-75" alt="Văn phòng Thành Công Land">
                    <img src="https://thanhcongland.com.vn/wp-content/uploads/2025/11/anh-nhan-vien-cong-ty-Thanh-Cong-Land-3-1-600x599.webp"
                        class="img-fluid rounded-4 shadow-lg w-50 position-absolute border border-5 border-white"
                        style="bottom: -10%; right: 5%;" alt="Dự án thực tế">
                </div>

                {{-- Nội dung --}}
                <div class="col-lg-6 ps-lg-5 mt-5 mt-lg-0" data-aos="fade-left">
                    <h2 class="fw-bold serif-font mb-4" style="color: #1E293B;">Khởi Nguồn Của <span
                            style="color: #FF8C42;">Sự Thịnh Vượng</span></h2>
                    <p class="text-muted" style="line-height: 1.8; text-align: justify;">
                        Được thành lập với sứ mệnh "Đồng hành cùng mọi tổ ấm", <strong>Thành Công Land</strong> đã vươn mình
                        trở thành một trong những đơn vị tư vấn và phân phối bất động sản uy tín hàng đầu tại khu vực phía
                        Tây Hà Nội, đặc biệt là đại dự án Vinhomes Smart City.
                    </p>
                    <p class="text-muted mb-4" style="line-height: 1.8; text-align: justify;">
                        Chúng tôi không chỉ bán một căn nhà, chúng tôi trao gửi một không gian sống, một cộng đồng văn minh
                        và một cơ hội đầu tư bền vững cho thế hệ tương lai. Mọi quyết định của chúng tôi đều dựa trên lợi
                        ích cốt lõi của khách hàng.
                    </p>
                    <div class="d-flex align-items-center mt-4">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo"
                            style="height: 60px; filter: grayscale(100%) opacity(50%);">
                        <div class="ms-4 border-start ps-4 border-2" style="border-color: #FF8C42 !important;">
                            <h5 class="fw-bold mb-0">Vũ Đình Thủy</h5>
                            <span class="text-muted small">CEO & Founder</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 3. TẦM NHÌN & SỨ MỆNH --}}
    <section class="py-5" style="background-color: #F8FAFC;">
        <div class="container py-4">
            <div class="row g-4 justify-content-center text-center">
                <div class="col-lg-5" data-aos="fade-up" data-aos-delay="100">
                    <div class="card border-0 rounded-4 shadow-sm h-100 p-5 hover-card">
                        <div class="icon-box mx-auto mb-4"
                            style="width: 80px; height: 80px; background-color: rgba(255,140,66,0.1); border-radius: 50%; display: grid; place-items: center;">
                            <i class="fas fa-eye fa-2x" style="color: #FF8C42;"></i>
                        </div>
                        <h3 class="fw-bold serif-font mb-3">Tầm Nhìn</h3>
                        <p class="text-muted">Trở thành thương hiệu Dịch vụ Bất động sản số 1 tại Việt Nam, nơi hội tụ của
                            những chuyên gia tận tâm, mang lại hệ sinh thái toàn diện và trải nghiệm vượt trội cho khách
                            hàng.</p>
                    </div>
                </div>
                <div class="col-lg-5" data-aos="fade-up" data-aos-delay="200">
                    <div class="card border-0 rounded-4 shadow-sm h-100 p-5 hover-card">
                        <div class="icon-box mx-auto mb-4"
                            style="width: 80px; height: 80px; background-color: rgba(255,140,66,0.1); border-radius: 50%; display: grid; place-items: center;">
                            <i class="fas fa-bullseye fa-2x" style="color: #FF8C42;"></i>
                        </div>
                        <h3 class="fw-bold serif-font mb-3">Sứ Mệnh</h3>
                        <p class="text-muted">Kiến tạo những tổ ấm hạnh phúc, tối ưu hóa giá trị đầu tư và góp phần nâng tầm
                            chất lượng sống cho cộng đồng thông qua các sản phẩm bất động sản chất lượng cao.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 4. GIÁ TRỊ CỐT LÕI --}}
    <section class="py-5" style="background-color: #ffffff;">
        <div class="container py-5">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="fw-bold serif-font mb-2" style="color: #333333;">Giá Trị Cốt Lõi</h2>
                <p class="text-muted">Những nguyên tắc vàng dẫn lối mọi hành động tại Thành Công Land</p>
            </div>
            <div class="row g-4">
                {{-- Item 1 --}}
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="p-4 rounded-4 text-center h-100 value-card"
                        style="border: 1px solid #f0f0f0; transition: 0.3s;">
                        <i class="fas fa-shield-alt fa-3x mb-3" style="color: #FF8C42;"></i>
                        <h5 class="fw-bold mb-3">Minh Bạch</h5>
                        <p class="text-muted small mb-0">Rõ ràng trong pháp lý, trung thực trong tư vấn. Chúng tôi cung cấp
                            thông tin đa chiều, chính xác nhất đến tay khách hàng.</p>
                    </div>
                </div>
                {{-- Item 2 --}}
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="p-4 rounded-4 text-center h-100 value-card"
                        style="border: 1px solid #f0f0f0; transition: 0.3s;">
                        <i class="fas fa-heart fa-3x mb-3" style="color: #FF8C42;"></i>
                        <h5 class="fw-bold mb-3">Tận Tâm</h5>
                        <p class="text-muted small mb-0">Lấy khách hàng làm trung tâm. Luôn lắng nghe, thấu hiểu và phục vụ
                            bằng cả trái tim 24/7 trong suốt quá trình đồng hành.</p>
                    </div>
                </div>
                {{-- Item 3 --}}
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="p-4 rounded-4 text-center h-100 value-card"
                        style="border: 1px solid #f0f0f0; transition: 0.3s;">
                        <i class="fas fa-bolt fa-3x mb-3" style="color: #FF8C42;"></i>
                        <h5 class="fw-bold mb-3">Tốc Độ</h5>
                        <p class="text-muted small mb-0">Xử lý nhanh gọn, thủ tục chuyên nghiệp. Tiết kiệm tối đa thời gian
                            vàng ngọc của khách hàng trong từng giao dịch.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 5. THÔNG ĐIỆP KẾT & LIÊN HỆ --}}
    <section class="py-5 position-relative"
        style=" background: linear-gradient(rgba(15, 23, 42, 0.75), rgba(15, 23, 42, 0.75)), url('/images/anh-nhan-vien-cong-ty-Thanh-Cong-Land-1536x702.webp') center/cover fixed;">
        <div class="container py-5">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8" data-aos="zoom-in">
                    <h2 class="text-white fw-bold serif-font mb-4">Bạn Đã Sẵn Sàng Tìm Ngôi Nhà Mơ Ước?</h2>
                    <p class="text-light opacity-75 mb-5 fs-5">Hãy để đội ngũ chuyên gia của Thành Công Land giúp bạn biến
                        ước mơ thành hiện thực một cách dễ dàng và an tâm nhất.</p>
                    <div class="d-flex justify-content-center gap-3 flex-wrap">
                        <a href="{{ route('frontend.bat-dong-san.index') }}"
                            class="btn rounded-pill fw-bold px-5 py-3 shadow"
                            style="background-color: #FF8C42; color: white; border: none;">
                            <i class="fas fa-search me-2"></i> Tìm Nhà Ngay
                        </a>
                        <a href="{{ route('frontend.ky-gui.create') }}"
                            class="btn btn-outline-light rounded-pill fw-bold px-5 py-3">
                            <i class="fas fa-home me-2"></i> Ký Gửi Cho Chúng Tôi
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- STYLE RIÊNG CHO TRANG ABOUT --}}
    <style>
        .hover-card {
            transition: all 0.3s ease;
            border-bottom: 4px solid transparent;
        }

        .hover-card:hover {
            transform: translateY(-10px);
            border-bottom: 4px solid #FF8C42 !important;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1) !important;
        }

        .value-card:hover {
            border-color: #FF8C42 !important;
            background-color: #fff9f5;
        }
    </style>

@endsection
