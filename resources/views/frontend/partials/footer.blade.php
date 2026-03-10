<footer class="pt-4 pb-2" style="background-color: #F5F5F5; color: #333333;">
    <div class="container">
        <div class="row g-4">
            
            {{-- CỘT 1: THÔNG TIN CÔNG TY --}}
            <div class="col-lg-4 col-md-6">
                <a href="{{ route('home') }}" class="d-flex align-items-center mb-4 text-decoration-none text-white">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height: 50px;"> {{-- Logo trắng --}}
                  
                </a>
                <p class="text-muted small mb-4">
                    Đơn vị phân phối bất động sản uy tín hàng đầu tại Vinhomes Smart City và khu vực phía Tây Hà Nội. Chúng tôi cam kết mang đến giá trị thực cho khách hàng.
                {{-- Theo quy định website thương mại điện tử/doanh nghiệp tại Việt Nam, Footer nên có thêm dòng Mã số thuế. --}}

    <span class="d-block mt-2 text-muted">
        <strong style="color: #333333;">MST:</strong> 0123456789 <br>
        <strong style="color: #333333;">Ngày cấp:</strong> 01/01/2026 - <strong style="color: #333333;">Nơi cấp:</strong> Sở KHĐT TP. Hà Nội
    </span>
</p>
                <div class="d-flex gap-3">
                    <a href="#" class="btn rounded-circle" style="width: 35px; height: 35px; display: grid; place-items: center; border: 2px solid #FF8C42; color: #FF8C42; transition: all 0.3s;" onmouseover="this.style.backgroundColor='#FF8C42'; this.style.color='white';" onmouseout="this.style.backgroundColor='transparent'; this.style.color='#FF8C42';"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="btn rounded-circle" style="width: 35px; height: 35px; display: grid; place-items: center; border: 2px solid #FF8C42; color: #FF8C42; transition: all 0.3s;" onmouseover="this.style.backgroundColor='#FF8C42'; this.style.color='white';" onmouseout="this.style.backgroundColor='transparent'; this.style.color='#FF8C42';"><i class="fab fa-youtube"></i></a>
                    <a href="#" class="btn rounded-circle" style="width: 35px; height: 35px; display: grid; place-items: center; border: 2px solid #FF8C42; color: #FF8C42; transition: all 0.3s;" onmouseover="this.style.backgroundColor='#FF8C42'; this.style.color='white';" onmouseout="this.style.backgroundColor='transparent'; this.style.color='#FF8C42';"><i class="fab fa-tiktok"></i></a>
                    <a href="#" class="btn rounded-circle" style="width: 35px; height: 35px; display: grid; place-items: center; border: 2px solid #FF8C42; color: #FF8C42; transition: all 0.3s;" onmouseover="this.style.backgroundColor='#FF8C42'; this.style.color='white';" onmouseout="this.style.backgroundColor='transparent'; this.style.color='#FF8C42';"><i class="fab fa-zalo"></i></a>
                </div>
            </div>

            {{-- CỘT 2: LIÊN KẾT NHANH (Đã thêm Tuyển Dụng) --}}
            <div class="col-lg-2 col-md-6">
                <h6 class="fw-bold text-uppercase mb-4" style="color: #FF8C42;">Về Chúng Tôi</h6>
                <ul class="list-unstyled d-flex flex-column gap-2">
                    <li><a href="{{ route('about') }}" class="text-muted text-decoration-none" style="transition: all 0.3s;" onmouseover="this.style.color='#FF8C42';" onmouseout="this.style.color='#999999';"><i class="fas fa-angle-right me-2 small"></i>Giới thiệu</a></li>
                    <li><a href="{{ route('du-an.index') }}" class="text-muted text-decoration-none" style="transition: all 0.3s;" onmouseover="this.style.color='#FF8C42';" onmouseout="this.style.color='#999999';"><i class="fas fa-angle-right me-2 small"></i>Dự án nổi bật</a></li>
                    <li><a href="{{ route('bai-viet.index') }}" class="text-muted text-decoration-none" style="transition: all 0.3s;" onmouseover="this.style.color='#FF8C42';" onmouseout="this.style.color='#999999';"><i class="fas fa-angle-right me-2 small"></i>Tin tức thị trường</a></li>
                    {{-- Mục Tuyển dụng được chuyển xuống đây --}}
                    <li><a href="#" class="text-muted text-decoration-none" style="transition: all 0.3s;" onmouseover="this.style.color='#FF8C42';" onmouseout="this.style.color='#999999';"><i class="fas fa-angle-right me-2 small"></i>Tuyển dụng</a></li>
                    <li><a href="{{ route('lien-he.index') }}" class="text-muted text-decoration-none" style="transition: all 0.3s;" onmouseover="this.style.color='#FF8C42';" onmouseout="this.style.color='#999999';"><i class="fas fa-angle-right me-2 small"></i>Liên hệ</a></li>
                </ul>
            </div>

            {{-- CỘT 3: SẢN PHẨM --}}
            <div class="col-lg-2 col-md-6">
                <h6 class="fw-bold text-uppercase mb-4" style="color: #FF8C42;">Sản Phẩm</h6>
                <ul class="list-unstyled d-flex flex-column gap-2">
                    <li><a href="{{ route('tim-kiem') }}?loai=ban" class="text-muted text-decoration-none" style="transition: all 0.3s;" onmouseover="this.style.color='#FF8C42';" onmouseout="this.style.color='#999999';"><i class="fas fa-angle-right me-2 small"></i>Căn hộ chuyển nhượng</a></li>
                    <li><a href="{{ route('tim-kiem') }}?loai=thue" class="text-muted text-decoration-none" style="transition: all 0.3s;" onmouseover="this.style.color='#FF8C42';" onmouseout="this.style.color='#999999';"><i class="fas fa-angle-right me-2 small"></i>Căn hộ cho thuê</a></li>
                    <li><a href="#" class="text-muted text-decoration-none" style="transition: all 0.3s;" onmouseover="this.style.color='#FF8C42';" onmouseout="this.style.color='#999999';"><i class="fas fa-angle-right me-2 small"></i>Shophouse khối đế</a></li>
                    <li><a href="#" class="text-muted text-decoration-none" style="transition: all 0.3s;" onmouseover="this.style.color='#FF8C42';" onmouseout="this.style.color='#999999';"><i class="fas fa-angle-right me-2 small"></i>Biệt thự liền kề</a></li>
                    <li><a href="{{ route('ky-gui.create') }}" class="text-muted text-decoration-none" style="transition: all 0.3s;" onmouseover="this.style.color='#FF8C42';" onmouseout="this.style.color='#999999';"><i class="fas fa-angle-right me-2 small"></i>Ký gửi nhà đất</a></li>
                </ul>
            </div>

            {{-- CỘT 4: THÔNG TIN LIÊN HỆ --}}
            <div class="col-lg-4 col-md-6">
                <h6 class="fw-bold text-uppercase mb-4" style="color: #FF8C42;">Thông Tin Liên Hệ</h6>
                <ul class="list-unstyled text-muted">
                    <li class="mb-3 d-flex">
                        <i class="fas fa-map-marker-alt" style="color: #FF8C42; margin-top: 4px; margin-right: 12px;"></i>
                        <span>S2.01 Vinhomes Smart City, Tây Mỗ, Nam Từ Liêm, Hà Nội</span>
                    </li>
                    <li class="mb-3 d-flex">
                        <i class="fas fa-phone-alt" style="color: #FF8C42; margin-top: 4px; margin-right: 12px;"></i>
                        <div>
                            <span class="d-block">Hotline tư vấn 24/7</span>
                            <a href="tel:0912345678" class="text-dark fw-bold text-decoration-none" style="font-size: 1.2rem;">0912 345 678</a>
                        </div>
                    </li>
                    <li class="mb-3 d-flex">
                        <i class="fas fa-envelope" style="color: #FF8C42; margin-top: 4px; margin-right: 12px;"></i>
                        <a href="mailto:contact@smartcity.vn" class="text-muted text-decoration-none">contact@smartcity.vn</a>
                    </li>
                </ul>
            </div>
        </div>


        {{-- BẢN QUYỀN --}}
        <div class="row align-items-center mt-4">
            <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                <p class="small text-muted mb-0">
                    &copy; {{ date('Y') }} <strong style="color: #333333;">Smart City Real Estate</strong>. All Rights Reserved.
                </p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <a href="#" class="text-muted text-decoration-none small me-3" style="transition: all 0.3s;" onmouseover="this.style.color='#FF8C42';" onmouseout="this.style.color='#999999';">Điều khoản sử dụng</a>
                <a href="#" class="text-muted text-decoration-none small" style="transition: all 0.3s;" onmouseover="this.style.color='#FF8C42';" onmouseout="this.style.color='#999999';">Chính sách bảo mật</a>
            </div>
        </div>
    </div>
</footer>

<style>
    /* CSS Riêng cho Footer */
    footer {
        border-top: 3px solid #FF8C42;
    }
</style>