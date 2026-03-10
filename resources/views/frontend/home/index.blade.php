@extends('frontend.layouts.master')
@section('title', 'Trang Chủ - BatDongSan Pro')

@section('content')
    <section class="position-relative d-flex align-items-center justify-content-center" style="height: 85vh; margin-top: -76px;">
        <div style="position: absolute; top:0; left:0; width:100%; height:100%; background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), url('/images/anh-nhan-vien-cong-ty-Thanh-Cong-Land-1536x702.webp'); background-size: cover; background-position: center; z-index: -1;"></div>
        
        <div class="text-center text-white container" data-aos="fade-up">
            <h1 class="display-3 fw-bold mb-4 serif-font">Tìm Kiếm Căn Hộ Nhanh Nhất</h1>
            <div class="bg-white p-4 rounded-4 shadow-lg d-inline-block text-dark w-100" style="max-width: 800px;">
                <form action="{{ route('tim-kiem') }}" method="GET">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted">Từ khóa tìm kiếm</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="fas fa-search text-muted"></i></span>
                                <input type="text" name="tu_khoa" class="form-control border-0 bg-light" placeholder="Nhập tên dự án, khu vực...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold small text-muted">Loại hình</label>
                            <select name="loai_hinh" class="form-select border-0 bg-light">
                                <option value="">Tất cả loại hình</option>
                                <option value="can_ho">Căn hộ chung cư</option>
                                <option value="nha_dat">Nhà đất thổ cư</option>
                                <option value="biet_thu">Biệt thự</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold small text-muted">Mức giá</label>
                            <select name="muc_gia" class="form-select border-0 bg-light">
                                <option value="">Tất cả mức giá</option>
                                <option value="duoi-2">Dưới 2 Tỷ</option>
                                <option value="2-5">2 - 5 Tỷ</option>
                                <option value="5-8">5 - 8 Tỷ</option>
                                <option value="tren-8">Trên 8 Tỷ</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn w-100 h-100 fw-bold rounded-3" style="min-height: 45px; background-color: #FF8C42; color: white; border: none;">
                                Tìm Kiếm
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </section>

    <!-- Giới Thiệu Công Ty -->
    <section class="py-5" style="background: #FFFFFF;">
        <div class="container">
            <div class="row align-items-center g-4">
                <div class="col-lg-6" data-aos="fade-right">
                    <h2 class="display-5 fw-bold serif-font mb-4" style="color: #FF8C42;">Thành Công Land</h2>
                    <p class="fs-5 mb-3" style="color: #333333;">
                        Là đơn vị chuyên cung cấp dịch vụ bất động sản uy tín, với hơn 10 năm kinh nghiệm trong lĩnh vực quản lý, bán mua và cho thuê căn hộ cao cấp.
                    </p>
                    <p class="mb-4" style="color: #666666;">
                        Chúng tôi cam kết mang đến những giải pháp bất động sản tốt nhất, giúp khách hàng tìm được ngôi nhà lý tưởng với giá cả phải chăng.
                    </p>
                    <a href="{{ route('about') }}" class="btn fw-bold rounded-pill px-4" style="background-color: #FF8C42; color: white; border: none;">Tìm Hiểu Thêm</a>
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="row text-center g-3 p-4 rounded-4" style="background: #F5F5F5;">
                        <div class="col-6">
                            <h3 class="display-5 fw-bold mb-2" style="color: #FF8C42;">10+</h3>
                            <p class="mb-0" style="color: #333333;">Năm Kinh Nghiệm</p>
                        </div>
                        <div class="col-6">
                            <h3 class="display-5 fw-bold mb-2" style="color: #FF8C42;">5000+</h3>
                            <p class="mb-0" style="color: #333333;">Khách Hàng Hài Lòng</p>
                        </div>
                        <div class="col-6">
                            <h3 class="display-5 fw-bold mb-2" style="color: #FF8C42;">50+</h3>
                            <p class="mb-0" style="color: #333333;">Dự Án Quản Lý</p>
                        </div>
                        <div class="col-6">
                            <h3 class="display-5 fw-bold mb-2" style="color: #FF8C42;">100+</h3>
                            <p class="mb-0" style="color: #333333;">Nhân Viên Tâm Huyết</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <div class="d-flex justify-content-between align-items-end mb-4">
                <div><h2 class="fw-bold mb-0" style="color: #333333;">Dự Án Nổi Bật</h2></div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-secondary rounded-pill" id="prevBtn" style="border-color: #FF8C42; color: #FF8C42;">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="btn btn-outline-secondary rounded-pill" id="nextBtn" style="border-color: #FF8C42; color: #FF8C42;">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                    <a href="{{ route('du-an.index') }}" class="btn fw-bold rounded-pill" style="color: #FF8C42; border: 2px solid #FF8C42; background: transparent;">Xem tất cả</a>
                </div>
            </div>
            <div class="position-relative">
                <div class="row g-4" id="projectCarousel" style="overflow-x: auto; scroll-behavior: smooth; display: flex; flex-wrap: nowrap; scrollbar-width: none; -ms-overflow-style: none;">
                    @foreach($du_ans as $da)
                    <div class="col-lg-3 col-md-6" data-aos="fade-up" style="flex: 0 0 calc(25% - 12px); min-width: 250px;">
                        <div class="card border-0 rounded-4 overflow-hidden shadow-sm h-100">
                            <a href="{{ route('du-an.show', $da->slug) }}">
                                <img src="{{ $da->hinh_anh ? asset('storage/'.$da->hinh_anh) : 'https://vinhomesland.vn/wp-content/uploads/2023/10/be-boi-the-canopy-residences-vinhomes-smart-city.jpg' }}" class="card-img-top" style="height: 250px; object-fit: cover; transition: 0.5s;">
                            </a>
                            <div class="card-body">
                                <h5 class="fw-bold mb-1"><a href="{{ route('du-an.show', $da->slug) }}" class="text-decoration-none text-dark">{{ $da->ten_du_an }}</a></h5>
                                <small class="text-muted"><i class="fa-solid fa-location-dot me-1"></i> {{ Str::limit($da->dia_chi, 35) }}</small>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <style>
        #projectCarousel::-webkit-scrollbar {
            display: none;
        }
    </style>

    @push('scripts')
    <script>
        const carousel = document.getElementById('projectCarousel');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        
        const scrollAmount = 320; // Chiều rộng một card + gap

        prevBtn.addEventListener('click', () => {
            carousel.scrollBy({
                left: -scrollAmount,
                behavior: 'smooth'
            });
        });

        nextBtn.addEventListener('click', () => {
            carousel.scrollBy({
                left: scrollAmount,
                behavior: 'smooth'
            });
        });

        // Điều chỉnh trạng thái nút
        function updateButtonStates() {
            prevBtn.disabled = carousel.scrollLeft <= 0;
            nextBtn.disabled = carousel.scrollLeft >= carousel.scrollWidth - carousel.clientWidth;
            
            prevBtn.style.opacity = prevBtn.disabled ? '0.5' : '1';
            nextBtn.style.opacity = nextBtn.disabled ? '0.5' : '1';
        }

        carousel.addEventListener('scroll', updateButtonStates);
        window.addEventListener('load', updateButtonStates);
        window.addEventListener('resize', updateButtonStates);
    </script>
    @endpush

    <!-- Các Đối Tác Tin Cậy -->
    <section class="py-5" style="background: #F5F5F5;">
        <div class="container">
            <h2 class="fw-bold text-center mb-5" style="color: #FF8C42;">Các Đối Tác Tin Cậy</h2>
            <div class="row align-items-center g-4">
                <div class="col-lg-2 col-md-4 col-sm-6" data-aos="fade-up">
                    <div class="text-center p-4 rounded-3" style="background: white; display: flex; align-items: center; justify-content: center; height: 120px; transition: all 0.3s ease; cursor: pointer; box-shadow: 0 2px 8px rgba(0,0,0,0.08);" onmouseover="this.style.boxShadow='0 10px 25px rgba(255, 140, 66, 0.2)'; this.style.transform='translateY(-5px)';" onmouseout="this.style.boxShadow='0 2px 8px rgba(0,0,0,0.08)'; this.style.transform='translateY(0)';">
                        <img src="https://e7.pngegg.com/pngimages/620/176/png-clipart-tpbank-logo-vietnam-trademark-bank-purple-violet.png" alt="TP Bank" style="max-width: 90%; max-height: 70px; object-fit: contain;">
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="text-center p-4 rounded-3" style="background: white; display: flex; align-items: center; justify-content: center; height: 120px; transition: all 0.3s ease; cursor: pointer; box-shadow: 0 2px 8px rgba(0,0,0,0.08);" onmouseover="this.style.boxShadow='0 10px 25px rgba(255, 140, 66, 0.2)'; this.style.transform='translateY(-5px)';" onmouseout="this.style.boxShadow='0 2px 8px rgba(0,0,0,0.08)'; this.style.transform='translateY(0)';">
                        <img src="https://logowik.com/content/uploads/images/vietcombank8188.jpg" alt="Vietcombank" style="max-width: 90%; max-height: 70px; object-fit: contain;">
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="text-center p-4 rounded-3" style="background: white; display: flex; align-items: center; justify-content: center; height: 120px; transition: all 0.3s ease; cursor: pointer; box-shadow: 0 2px 8px rgba(0,0,0,0.08);" onmouseover="this.style.boxShadow='0 10px 25px rgba(255, 140, 66, 0.2)'; this.style.transform='translateY(-5px)';" onmouseout="this.style.boxShadow='0 2px 8px rgba(0,0,0,0.08)'; this.style.transform='translateY(0)';">
                        <img src="https://dongphucvina.vn/wp-content/uploads/2023/05/logo-vietinbank-dongphucvina.vn_.png" alt="Vietinbank" style="max-width: 90%; max-height: 70px; object-fit: contain;">
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="text-center p-4 rounded-3" style="background: white; display: flex; align-items: center; justify-content: center; height: 120px; transition: all 0.3s ease; cursor: pointer; box-shadow: 0 2px 8px rgba(0,0,0,0.08);" onmouseover="this.style.boxShadow='0 10px 25px rgba(255, 140, 66, 0.2)'; this.style.transform='translateY(-5px)';" onmouseout="this.style.boxShadow='0 2px 8px rgba(0,0,0,0.08)'; this.style.transform='translateY(0)';">
                        <img src="https://tse3.mm.bing.net/th/id/OIP.lGwvhNDzTil08ed4TxtbGwHaBA?rs=1&pid=ImgDetMain&o=7&rm=3" alt="Agribank" style="max-width: 90%; max-height: 70px; object-fit: contain;">
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6" data-aos="fade-up" data-aos-delay="400">
                    <div class="text-center p-4 rounded-3" style="background: white; display: flex; align-items: center; justify-content: center; height: 120px; transition: all 0.3s ease; cursor: pointer; box-shadow: 0 2px 8px rgba(0,0,0,0.08);" onmouseover="this.style.boxShadow='0 10px 25px rgba(255, 140, 66, 0.2)'; this.style.transform='translateY(-5px)';" onmouseout="this.style.boxShadow='0 2px 8px rgba(0,0,0,0.08)'; this.style.transform='translateY(0)';">
                        <img src="https://tse1.mm.bing.net/th/id/OIP.L3y8uvnxKud1OK3dnE7NOAHaDH?rs=1&pid=ImgDetMain&o=7&rm=3" alt="Techcombank" style="max-width: 90%; max-height: 70px; object-fit: contain;">
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6" data-aos="fade-up" data-aos-delay="500">
                    <div class="text-center p-4 rounded-3" style="background: white; display: flex; align-items: center; justify-content: center; height: 120px; transition: all 0.3s ease; cursor: pointer; box-shadow: 0 2px 8px rgba(0,0,0,0.08);" onmouseover="this.style.boxShadow='0 10px 25px rgba(255, 140, 66, 0.2)'; this.style.transform='translateY(-5px)';" onmouseout="this.style.boxShadow='0 2px 8px rgba(0,0,0,0.08)'; this.style.transform='translateY(0)';">
                        <img src="https://th.bing.com/th/id/OIP.X_5JgiQ0TmM3FVLtoLwIuQHaDt?o=7rm=3&rs=1&pid=ImgDetMain&o=7&rm=3" alt="VIB" style="max-width: 90%; max-height: 70px; object-fit: contain;">
                    </div>
                </div>
            </div>
        </div>
    </section>
    @push('scripts')
<script>
    window.chatContext = { type: 'general', id: null };
</script>
@endpush
@endsection