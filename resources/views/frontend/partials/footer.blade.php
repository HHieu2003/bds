<footer class="pt-5 pb-3 text-white mt-auto" style="background-color: #0F172A;"> {{-- Màu Slate 900 sang trọng --}}
    <div class="container">
        <div class="row g-4">
            
            {{-- CỘT 1: THÔNG TIN CÔNG TY --}}
            <div class="col-lg-4 col-md-6">
                <a href="{{ route('home') }}" class="d-flex align-items-center mb-4 text-decoration-none text-white">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height: 50px; filter: brightness(0) invert(1);"> {{-- Logo trắng --}}
                  
                </a>
                <p class="text-white-50 small mb-4">
                    Đơn vị phân phối bất động sản uy tín hàng đầu tại Vinhomes Smart City và khu vực phía Tây Hà Nội. Chúng tôi cam kết mang đến giá trị thực cho khách hàng.
                </p>
                <div class="d-flex gap-3">
                    <a href="#" class="btn btn-outline-light btn-sm rounded-circle" style="width: 35px; height: 35px; display: grid; place-items: center;"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="btn btn-outline-light btn-sm rounded-circle" style="width: 35px; height: 35px; display: grid; place-items: center;"><i class="fab fa-youtube"></i></a>
                    <a href="#" class="btn btn-outline-light btn-sm rounded-circle" style="width: 35px; height: 35px; display: grid; place-items: center;"><i class="fab fa-tiktok"></i></a>
                    <a href="#" class="btn btn-outline-light btn-sm rounded-circle" style="width: 35px; height: 35px; display: grid; place-items: center;"><i class="fab fa-zalo"></i></a>
                </div>
            </div>

            {{-- CỘT 2: LIÊN KẾT NHANH (Đã thêm Tuyển Dụng) --}}
            <div class="col-lg-2 col-md-6">
                <h6 class="fw-bold text-uppercase mb-4 text-primary">Về Chúng Tôi</h6>
                <ul class="list-unstyled d-flex flex-column gap-2">
                    <li><a href="{{ route('about') }}" class="text-white-50 text-decoration-none hover-white"><i class="fas fa-angle-right me-2 small"></i>Giới thiệu</a></li>
                    <li><a href="{{ route('du-an.index') }}" class="text-white-50 text-decoration-none hover-white"><i class="fas fa-angle-right me-2 small"></i>Dự án nổi bật</a></li>
                    <li><a href="{{ route('bai-viet.index') }}" class="text-white-50 text-decoration-none hover-white"><i class="fas fa-angle-right me-2 small"></i>Tin tức thị trường</a></li>
                    {{-- Mục Tuyển dụng được chuyển xuống đây --}}
                    <li><a href="#" class="text-white-50 text-decoration-none hover-white"><i class="fas fa-angle-right me-2 small"></i>Tuyển dụng</a></li>
                    <li><a href="{{ route('lien-he.index') }}" class="text-white-50 text-decoration-none hover-white"><i class="fas fa-angle-right me-2 small"></i>Liên hệ</a></li>
                </ul>
            </div>

            {{-- CỘT 3: SẢN PHẨM --}}
            <div class="col-lg-2 col-md-6">
                <h6 class="fw-bold text-uppercase mb-4 text-primary">Sản Phẩm</h6>
                <ul class="list-unstyled d-flex flex-column gap-2">
                    <li><a href="{{ route('tim-kiem') }}?loai=ban" class="text-white-50 text-decoration-none hover-white"><i class="fas fa-angle-right me-2 small"></i>Căn hộ chuyển nhượng</a></li>
                    <li><a href="{{ route('tim-kiem') }}?loai=thue" class="text-white-50 text-decoration-none hover-white"><i class="fas fa-angle-right me-2 small"></i>Căn hộ cho thuê</a></li>
                    <li><a href="#" class="text-white-50 text-decoration-none hover-white"><i class="fas fa-angle-right me-2 small"></i>Shophouse khối đế</a></li>
                    <li><a href="#" class="text-white-50 text-decoration-none hover-white"><i class="fas fa-angle-right me-2 small"></i>Biệt thự liền kề</a></li>
                    <li><a href="{{ route('ky-gui.create') }}" class="text-white-50 text-decoration-none hover-white"><i class="fas fa-angle-right me-2 small"></i>Ký gửi nhà đất</a></li>
                </ul>
            </div>

            {{-- CỘT 4: THÔNG TIN LIÊN HỆ --}}
            <div class="col-lg-4 col-md-6">
                <h6 class="fw-bold text-uppercase mb-4 text-primary">Thông Tin Liên Hệ</h6>
                <ul class="list-unstyled text-white-50">
                    <li class="mb-3 d-flex">
                        <i class="fas fa-map-marker-alt text-primary mt-1 me-3"></i>
                        <span>S2.01 Vinhomes Smart City, Tây Mỗ, Nam Từ Liêm, Hà Nội</span>
                    </li>
                    <li class="mb-3 d-flex">
                        <i class="fas fa-phone-alt text-primary mt-1 me-3"></i>
                        <div>
                            <span class="d-block">Hotline tư vấn 24/7</span>
                            <a href="tel:0912345678" class="text-white fw-bold text-decoration-none fs-5">0912 345 678</a>
                        </div>
                    </li>
                    <li class="mb-3 d-flex">
                        <i class="fas fa-envelope text-primary mt-1 me-3"></i>
                        <a href="mailto:contact@smartcity.vn" class="text-white-50 text-decoration-none">contact@smartcity.vn</a>
                    </li>
                </ul>
            </div>
        </div>

        <hr class="border-secondary my-4 opacity-25">

        {{-- BẢN QUYỀN --}}
        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                <p class="small text-white-50 mb-0">
                    &copy; {{ date('Y') }} <strong>Smart City Real Estate</strong>. All Rights Reserved.
                </p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <a href="#" class="text-white-50 text-decoration-none small me-3 hover-white">Điều khoản sử dụng</a>
                <a href="#" class="text-white-50 text-decoration-none small hover-white">Chính sách bảo mật</a>
            </div>
        </div>
    </div>
</footer>

<style>
    /* CSS Riêng cho Footer */
    .text-primary { color: #38bdf8 !important; } /* Màu xanh sáng nổi bật trên nền tối */
    .hover-white:hover { color: #fff !important; padding-left: 5px; transition: all 0.3s; }
    .opacity-25 { opacity: 0.25; }
</style>