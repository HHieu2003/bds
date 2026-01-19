@extends('frontend.layouts.master')

@section('title', 'Giới Thiệu - Thành Công Land')

@section('content')
<section class="about-hero py-5 mt-5" style="background: linear-gradient(135deg, #0F172A 0%, #1A2948 100%);">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6" data-aos="fade-right">
                <h1 class="display-4 fw-bold text-white serif-font mb-4">Thành Công Land</h1>
                <p class="text-light fs-5 mb-4">
                    Là đơn vị chuyên cung cấp dịch vụ bất động sản uy tín, với hơn 10 năm kinh nghiệm trong lĩnh vực quản lý, bán mua và cho thuê căn hộ cao cấp.
                </p>
                <p class="text-light fs-6 mb-4">
                    Chúng tôi cam k承 mang đến những giải pháp bất động sản tốt nhất, giúp khách hàng tìm được ngôi nhà lý tưởng với giá cả phải chăng.
                </p>
            </div>
            <div class="col-lg-6 text-center" data-aos="fade-left">
                <img src="{{ asset('images/logo.png') }}" alt="Thành Công Land" style="max-width: 300px; height: auto;">
            </div>
        </div>
    </div>
</section>

<!-- Sứ mệnh & Tầm nhìn -->
<section class="py-5 bg-white">
    <div class="container">
        <h2 class="text-center serif-font fw-bold mb-5" style="font-size: 2.5rem; color: #0F172A;">Sứ Mệnh & Tầm Nhìn</h2>
        
        <div class="row g-4">
            <div class="col-lg-6" data-aos="zoom-in">
                <div class="p-4 rounded-4" style="background: linear-gradient(135deg, #0F172A 0%, #1A2948 100%); min-height: 300px; display: flex; flex-direction: column; justify-content: center;">
                    <h3 class="text-white serif-font fw-bold mb-3">
                        <i class="fas fa-rocket text-warning"></i> Sứ Mệnh
                    </h3>
                    <p class="text-light">
                        Cung cấp các dịch vụ bất động sản chuyên nghiệp, minh bạch và đáng tin cậy. Giúp khách hàng tìm kiếm được những sản phẩm bất động sản phù hợp với nhu cầu và ngân sách của mình.
                    </p>
                </div>
            </div>
            
            <div class="col-lg-6" data-aos="zoom-in">
                <div class="p-4 rounded-4" style="background: linear-gradient(135deg, #B99044 0%, #D4AF85 100%); min-height: 300px; display: flex; flex-direction: column; justify-content: center;">
                    <h3 class="text-white serif-font fw-bold mb-3">
                        <i class="fas fa-crown"></i> Tầm Nhìn
                    </h3>
                    <p class="text-white">
                        Trở thành công ty bất động sản hàng đầu tại Việt Nam, được khách hàng tin tưởng và yêu thích. Cung cấp giải pháp bất động sản toàn diện, từ mua bán đến cho thuê với mức giá cạnh tranh nhất.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Những giá trị cốt lõi -->
<section class="py-5" style="background-color: #F8FAFC;">
    <div class="container">
        <h2 class="text-center serif-font fw-bold mb-5" style="font-size: 2.5rem; color: #0F172A;">Những Giá Trị Cốt Lõi</h2>
        
        <div class="row g-4">
            <div class="col-md-3" data-aos="fade-up">
                <div class="text-center">
                    <div class="mb-3" style="font-size: 3rem; color: #B99044;">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <h4 class="serif-font fw-bold" style="color: #0F172A;">Uy Tín</h4>
                    <p class="text-muted">Đặt uy tín khách hàng lên hàng đầu, luôn minh bạch trong giao dịch.</p>
                </div>
            </div>
            
            <div class="col-md-3" data-aos="fade-up" data-aos-delay="100">
                <div class="text-center">
                    <div class="mb-3" style="font-size: 3rem; color: #B99044;">
                        <i class="fas fa-star"></i>
                    </div>
                    <h4 class="serif-font fw-bold" style="color: #0F172A;">Chất Lượng</h4>
                    <p class="text-muted">Cung cấp những sản phẩm và dịch vụ tốt nhất trong ngành.</p>
                </div>
            </div>
            
            <div class="col-md-3" data-aos="fade-up" data-aos-delay="200">
                <div class="text-center">
                    <div class="mb-3" style="font-size: 3rem; color: #B99044;">
                        <i class="fas fa-users"></i>
                    </div>
                    <h4 class="serif-font fw-bold" style="color: #0F172A;">Chuyên Nghiệp</h4>
                    <p class="text-muted">Đội ngũ nhân viên được đào tạo chuyên sâu và tâm huyết.</p>
                </div>
            </div>
            
            <div class="col-md-3" data-aos="fade-up" data-aos-delay="300">
                <div class="text-center">
                    <div class="mb-3" style="font-size: 3rem; color: #B99044;">
                        <i class="fas fa-seedling"></i>
                    </div>
                    <h4 class="serif-font fw-bold" style="color: #0F172A;">Bền Vững</h4>
                    <p class="text-muted">Cam kết phát triển bền vững, có trách nhiệm với cộng đồng.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Quá trình phát triển -->
<section class="py-5 bg-white">
    <div class="container">
        <h2 class="text-center serif-font fw-bold mb-5" style="font-size: 2.5rem; color: #0F172A;">Quá Trình Phát Triển</h2>
        
        <div class="row g-4">
            <div class="col-lg-6" data-aos="fade-right">
                <div class="timeline-item mb-4">
                    <div class="d-flex gap-3">
                        <div style="color: #B99044; font-size: 1.5rem;">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold" style="color: #0F172A;">2014 - Thành Lập</h5>
                            <p class="text-muted mb-0">Thành Công Land được thành lập với mục tiêu phục vụ khách hàng trong lĩnh vực bất động sản.</p>
                        </div>
                    </div>
                </div>
                
                <div class="timeline-item mb-4">
                    <div class="d-flex gap-3">
                        <div style="color: #B99044; font-size: 1.5rem;">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold" style="color: #0F172A;">2016 - Mở Rộng</h5>
                            <p class="text-muted mb-0">Mở rộng mạng lưới chi nhánh tại các thành phố lớn, tăng dịch vụ cho thuê và bán mua.</p>
                        </div>
                    </div>
                </div>
                
                <div class="timeline-item mb-4">
                    <div class="d-flex gap-3">
                        <div style="color: #B99044; font-size: 1.5rem;">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold" style="color: #0F172A;">2019 - Chuyển Đổi Số</h5>
                            <p class="text-muted mb-0">Đầu tư vào công nghệ, xây dựng nền tảng số hóa để phục vụ khách hàng tốt hơn.</p>
                        </div>
                    </div>
                </div>

                <div class="timeline-item">
                    <div class="d-flex gap-3">
                        <div style="color: #B99044; font-size: 1.5rem;">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold" style="color: #0F172A;">2024 - Hiện Tại</h5>
                            <p class="text-muted mb-0">Trở thành một trong những công ty bất động sản uy tín, phục vụ hàng nghìn khách hàng mỗi năm.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6" data-aos="fade-left">
                <div class="p-4 rounded-4" style="background: linear-gradient(135deg, #0F172A 0%, #1A2948 100%);">
                    <div class="row text-center text-white g-3">
                        <div class="col-6">
                            <h3 class="display-5 fw-bold mb-2" style="color: #B99044;">10+</h3>
                            <p class="mb-0">Năm Kinh Nghiệm</p>
                        </div>
                        <div class="col-6">
                            <h3 class="display-5 fw-bold mb-2" style="color: #B99044;">5000+</h3>
                            <p class="mb-0">Khách Hàng Hài Lòng</p>
                        </div>
                        <div class="col-6">
                            <h3 class="display-5 fw-bold mb-2" style="color: #B99044;">50+</h3>
                            <p class="mb-0">Dự Án Quản Lý</p>
                        </div>
                        <div class="col-6">
                            <h3 class="display-5 fw-bold mb-2" style="color: #B99044;">100+</h3>
                            <p class="mb-0">Nhân Viên Tâm Huyết</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Đội ngũ -->
<section class="py-5" style="background-color: #F8FAFC;">
    <div class="container">
        <h2 class="text-center serif-font fw-bold mb-5" style="font-size: 2.5rem; color: #0F172A;">Đội Ngũ Chuyên Nghiệp</h2>
        
        <div class="row g-4">
            <div class="col-lg-4 col-md-6" data-aos="fade-up">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div style="height: 250px; background: linear-gradient(135deg, #0F172A 0%, #1A2948 100%); display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-user-tie" style="font-size: 4rem; color: #B99044;"></i>
                    </div>
                    <div class="card-body text-center">
                        <h5 class="card-title serif-font fw-bold" style="color: #0F172A;">Nguyễn Văn A</h5>
                        <p class="text-muted mb-2">Giám Đốc Điều Hành</p>
                        <p class="card-text">15 năm kinh nghiệm trong lĩnh vực bất động sản, được nhiều khách hàng tin tưởng.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div style="height: 250px; background: linear-gradient(135deg, #0F172A 0%, #1A2948 100%); display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-user-tie" style="font-size: 4rem; color: #B99044;"></i>
                    </div>
                    <div class="card-body text-center">
                        <h5 class="card-title serif-font fw-bold" style="color: #0F172A;">Trần Thị B</h5>
                        <p class="text-muted mb-2">Phó Giám Đốc Bán Hàng</p>
                        <p class="card-text">Chuyên gia trong lĩnh vực kinh doanh bất động sản, giúp khách hàng tìm được căn phòng lý tưởng.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div style="height: 250px; background: linear-gradient(135deg, #0F172A 0%, #1A2948 100%); display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-user-tie" style="font-size: 4rem; color: #B99044;"></i>
                    </div>
                    <div class="card-body text-center">
                        <h5 class="card-title serif-font fw-bold" style="color: #0F172A;">Lê Văn C</h5>
                        <p class="text-muted mb-2">Trưởng Phòng Dịch Vụ</p>
                        <p class="card-text">Chuyên phục vụ khách hàng, đảm bảo mỗi giao dịch diễn ra suôn sẻ và hài lòng.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Liên hệ -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-6" data-aos="fade-right">
                <h2 class="serif-font fw-bold mb-4" style="font-size: 2rem; color: #0F172A;">Liên Hệ Với Chúng Tôi</h2>
                <p class="text-muted mb-4">
                    Hãy liên hệ với chúng tôi để được tư vấn miễn phí về các dự án bất động sản hoặc những căn hộ phù hợp với nhu cầu của bạn.
                </p>
                
                <div class="mb-4">
                    <div class="d-flex gap-3 mb-3">
                        <div style="color: #B99044; font-size: 1.5rem;">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold" style="color: #0F172A;">Địa Chỉ</h6>
                            <p class="mb-0 text-muted">123 Đường Thành Công, Quận Hà Đông, Hà Nội</p>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-3 mb-3">
                        <div style="color: #B99044; font-size: 1.5rem;">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold" style="color: #0F172A;">Điện Thoại</h6>
                            <p class="mb-0 text-muted">(+84) 123 456 789</p>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-3">
                        <div style="color: #B99044; font-size: 1.5rem;">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold" style="color: #0F172A;">Email</h6>
                            <p class="mb-0 text-muted">info@thanhcongland.com</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6" data-aos="fade-left">
                <div class="p-4 rounded-4" style="background: linear-gradient(135deg, #0F172A 0%, #1A2948 100%);">
                    <h5 class="text-white fw-bold mb-4 serif-font">Gửi Tin Nhắn Cho Chúng Tôi</h5>
                    <form>
                        <div class="mb-3">
                            <input type="text" class="form-control rounded-pill" placeholder="Tên của bạn" style="border: 1px solid #B99044;">
                        </div>
                        <div class="mb-3">
                            <input type="email" class="form-control rounded-pill" placeholder="Email của bạn" style="border: 1px solid #B99044;">
                        </div>
                        <div class="mb-3">
                            <textarea class="form-control rounded-3" rows="4" placeholder="Tin nhắn của bạn" style="border: 1px solid #B99044;"></textarea>
                        </div>
                        <button type="submit" class="btn w-100 rounded-pill fw-bold" style="background: #B99044; color: white; border: none;">
                            Gửi Tin Nhắn
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
