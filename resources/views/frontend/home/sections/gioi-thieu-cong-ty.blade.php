<section class="py-5 position-relative" style="background-color: #FAFAFA;">
    <div class="container py-4">

        <div class="row align-items-center g-5 mb-5">
            {{-- Cột Trái: Hình ảnh & Badge nổi --}}
            <div class="col-lg-6 position-relative" data-aos="fade-right">
                <div class="position-relative" style="padding-right: 2rem; padding-bottom: 2rem;">
                    {{-- Hình ảnh đại diện (Có thể đổi link ảnh thực tế của công ty bạn) --}}
                    <img src="https://thanhcongland.com.vn/wp-content/uploads/2025/11/anh-nhan-vien-cong-ty-Thanh-Cong-Land-2-1536x1040.webp"
                        alt="Thành Công Land" class="img-fluid rounded-4 shadow-lg w-100"
                        style="object-fit: cover; height: 450px;">

                    {{-- Thẻ nổi (Floating Badge) --}}
                    <div class="position-absolute bottom-0 end-0 bg-white p-4 rounded-4 shadow-lg text-center"
                        style="transform: translate(-5%, -5%); border-bottom: 5px solid #FF8C42;">
                        <h2 class="display-4 fw-bold mb-0" style="color: #FF8C42;">10+</h2>
                        <p class="text-muted fw-bold mb-0 text-uppercase small">Năm Uy Tín</p>
                    </div>
                </div>
            </div>

            {{-- Cột Phải: Nội dung thuyết phục --}}
            <div class="col-lg-6" data-aos="fade-left">
                <span class="badge rounded-pill px-3 py-2 mb-3 fw-bold"
                    style="background-color: rgba(255, 140, 66, 0.1); color: #FF8C42; font-size: 0.85rem;">
                    <i class="fas fa-building me-1"></i> VỀ THÀNH CÔNG LAND
                </span>
                <h2 class="display-5 fw-bold serif-font mb-4" style="color: #1E293B; line-height: 1.3;">
                    Nơi Gửi Trọn Niềm Tin <br>
                    <span style="color: #FF8C42;">An Cư Lập Nghiệp</span>
                </h2>

                <p class="fs-6 mb-4" style="color: #64748B; line-height: 1.8;">
                    Với hơn thập kỷ tiên phong trong lĩnh vực phân phối, mua bán và cho thuê bất động sản cao cấp tại
                    khu vực phía Tây Hà Nội. <strong>Thành Công Land</strong> tự hào là cầu nối vững chắc, mang đến
                    những giải pháp an cư hoàn hảo và cơ hội đầu tư sinh lời vượt trội.
                </p>

                {{-- Các điểm mạnh (Checklist) --}}
                <div class="row g-3 mb-4">
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle d-flex justify-content-center align-items-center me-3 shadow-sm"
                                style="width: 40px; height: 40px; background-color: rgba(255, 140, 66, 0.1); color: #FF8C42;">
                                <i class="fas fa-check"></i>
                            </div>
                            <span class="fw-bold text-dark">Sản phẩm thực, Giá trị thực</span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle d-flex justify-content-center align-items-center me-3 shadow-sm"
                                style="width: 40px; height: 40px; background-color: rgba(255, 140, 66, 0.1); color: #FF8C42;">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <span class="fw-bold text-dark">Pháp lý minh bạch, an toàn</span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle d-flex justify-content-center align-items-center me-3 shadow-sm"
                                style="width: 40px; height: 40px; background-color: rgba(255, 140, 66, 0.1); color: #FF8C42;">
                                <i class="fas fa-handshake"></i>
                            </div>
                            <span class="fw-bold text-dark">Đồng hành trọn đời</span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle d-flex justify-content-center align-items-center me-3 shadow-sm"
                                style="width: 40px; height: 40px; background-color: rgba(255, 140, 66, 0.1); color: #FF8C42;">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <span class="fw-bold text-dark">Đầu tư sinh lời tối đa</span>
                        </div>
                    </div>
                </div>

                {{-- Nút CTA --}}
                <a href="{{ route('frontend.gioi-thieu') }}"
                    class="btn fw-bold rounded-pill px-5 py-3 shadow hover-up mt-2"
                    style="background-color: #FF8C42; color: white; border: none; transition: 0.3s;">
                    Khám Phá Chi Tiết <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
        </div>

        {{-- Hàng Thống Kê (Con Số) --}}
        <div class="row g-4 mt-2" data-aos="fade-up" data-aos-delay="200">
            <div class="col-lg-3 col-6">
                <div class="card border-0 rounded-4 shadow-sm h-100 p-4 text-center hover-up"
                    style="border-bottom: 4px solid #FF8C42 !important; transition: transform 0.3s;">
                    <div class="display-5 fw-bold mb-2" style="color: #FF8C42;">5000<span
                            class="fs-4 text-muted">+</span></div>
                    <h6 class="fw-bold text-dark mb-0">Khách Hàng Hài Lòng</h6>
                    <p class="text-muted small mt-2 mb-0 d-none d-md-block">Đã giao dịch thành công</p>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="card border-0 rounded-4 shadow-sm h-100 p-4 text-center hover-up"
                    style="border-bottom: 4px solid #FF8C42 !important; transition: transform 0.3s;">
                    <div class="display-5 fw-bold mb-2" style="color: #FF8C42;">50<span class="fs-4 text-muted">+</span>
                    </div>
                    <h6 class="fw-bold text-dark mb-0">Dự Án Quản Lý</h6>
                    <p class="text-muted small mt-2 mb-0 d-none d-md-block">Đa dạng phân khúc cao cấp</p>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="card border-0 rounded-4 shadow-sm h-100 p-4 text-center hover-up"
                    style="border-bottom: 4px solid #FF8C42 !important; transition: transform 0.3s;">
                    <div class="display-5 fw-bold mb-2" style="color: #FF8C42;">100<span
                            class="fs-4 text-muted">+</span></div>
                    <h6 class="fw-bold text-dark mb-0">Nhân Sự Tâm Huyết</h6>
                    <p class="text-muted small mt-2 mb-0 d-none d-md-block">Chuyên nghiệp, tận tâm</p>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="card border-0 rounded-4 shadow-sm h-100 p-4 text-center hover-up"
                    style="border-bottom: 4px solid #FF8C42 !important; transition: transform 0.3s;">
                    <div class="display-5 fw-bold mb-2" style="color: #FF8C42;">24<span
                            class="fs-4 text-muted">/7</span></div>
                    <h6 class="fw-bold text-dark mb-0">Hỗ Trợ Tận Tình</h6>
                    <p class="text-muted small mt-2 mb-0 d-none d-md-block">Luôn sẵn sàng phục vụ</p>
                </div>
            </div>
        </div>

    </div>
</section>
