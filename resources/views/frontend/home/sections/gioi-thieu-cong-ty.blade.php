<section class="py-5 position-relative bg-alt-section">
    <div class="container py-4">

        <div class="row align-items-center g-5 mb-5">
            <div class="col-lg-6 position-relative" data-aos="fade-right">
                <div class="position-relative" style="padding-right: 2rem; padding-bottom: 2rem;">
                    <img src="https://thanhcongland.com.vn/wp-content/uploads/2025/11/anh-nhan-vien-cong-ty-Thanh-Cong-Land-2-1536x1040.webp"
                        alt="Thành Công Land" class="img-fluid rounded-4 shadow-lg w-100"
                        style="object-fit: cover; height: 450px;">

                    <div class="position-absolute bottom-0 end-0 bg-white p-4 rounded-4 shadow-lg text-center"
                        style="transform: translate(-5%, -5%); border-bottom: 5px solid var(--primary);">
                        <h2 class="display-4 fw-bold mb-0 text-primary-brand">10+</h2>
                        <p class="text-muted fw-bold mb-0 text-uppercase small">Năm Uy Tín</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-6" data-aos="fade-left">
                <span class="badge rounded-pill px-3 py-2 mb-3 fw-bold"
                    style="background-color: var(--primary-light); color: var(--primary); font-size: 0.85rem;">
                    <i class="fas fa-building me-1"></i> VỀ THÀNH CÔNG LAND
                </span>

                {{-- Đã loại bỏ thẻ style lằng nhằng --}}
                <h2 class="display-5 fw-bold serif-font mb-4" style="color: var(--text-heading); line-height: 1.3;">
                    Nơi Gửi Trọn Niềm Tin <br>
                    <span class="text-primary-brand">An Cư Lập Nghiệp</span>
                </h2>

                <p class="fs-6 mb-4 text-muted" style="line-height: 1.8;">
                    Với hơn thập kỷ tiên phong trong lĩnh vực phân phối, mua bán và cho thuê bất động sản cao cấp tại
                    khu vực phía Tây Hà Nội. <strong>Thành Công Land</strong> tự hào là cầu nối vững chắc, mang đến
                    những giải pháp an cư hoàn hảo và cơ hội đầu tư sinh lời vượt trội.
                </p>

                <div class="row g-3 mb-4">
                    @php
                        $features = [
                            ['icon' => 'fa-check', 'text' => 'Sản phẩm thực, Giá trị thực'],
                            ['icon' => 'fa-shield-alt', 'text' => 'Pháp lý minh bạch, an toàn'],
                            ['icon' => 'fa-handshake', 'text' => 'Đồng hành trọn đời'],
                            ['icon' => 'fa-chart-line', 'text' => 'Đầu tư sinh lời tối đa'],
                        ];
                    @endphp
                    @foreach ($features as $ft)
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center">
                                <div
                                    class="rounded-circle d-flex justify-content-center align-items-center me-3 shadow-sm feature-icon">
                                    <i class="fas {{ $ft['icon'] }}"></i>
                                </div>
                                <span class="fw-bold" style="color: var(--text-heading);">{{ $ft['text'] }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Nút CTA tự động ăn màu chủ đạo --}}
                <a href="{{ route('frontend.gioi-thieu') }}" class="btn-primary-brand rounded-pill px-5 py-3 mt-2">
                    Khám Phá Chi Tiết <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
        </div>

        <div class="row g-4 mt-2" data-aos="fade-up" data-aos-delay="200">
            @php
                $stats = [
                    [
                        'num' => '5000',
                        'suffix' => '+',
                        'title' => 'Khách Hàng Hài Lòng',
                        'desc' => 'Đã giao dịch thành công',
                    ],
                    ['num' => '50', 'suffix' => '+', 'title' => 'Dự Án Quản Lý', 'desc' => 'Đa dạng phân khúc cao cấp'],
                    [
                        'num' => '100',
                        'suffix' => '+',
                        'title' => 'Nhân Sự Tâm Huyết',
                        'desc' => 'Chuyên nghiệp, tận tâm',
                    ],
                    ['num' => '24', 'suffix' => '/7', 'title' => 'Hỗ Trợ Tận Tình', 'desc' => 'Luôn sẵn sàng phục vụ'],
                ];
            @endphp
            @foreach ($stats as $st)
                <div class="col-lg-3 col-6">
                    <div class="card border-0 rounded-4 shadow-sm h-100 p-4 text-center hover-up stat-card">
                        <div class="display-5 fw-bold mb-2 text-primary-brand">{{ $st['num'] }}<span
                                class="fs-4 text-muted">{{ $st['suffix'] }}</span></div>
                        <h6 class="fw-bold mb-0" style="color: var(--text-heading);">{{ $st['title'] }}</h6>
                        <p class="text-muted small mt-2 mb-0 d-none d-md-block">{{ $st['desc'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</section>

@push('styles')
    <style>
        .feature-icon {
            width: 42px;
            height: 42px;
            background-color: var(--primary-light);
            color: var(--primary);
            transition: all 0.3s ease;
        }

        .feature-icon:hover {
            background-color: var(--primary);
            color: #fff;
            box-shadow: var(--shadow-gold);
            transform: scale(1.1);
        }

        .stat-card {
            border-bottom: 4px solid var(--primary) !important;
            transition: all 0.4s ease;
            background: #fff;
        }

        .stat-card:hover {
            border-bottom-color: var(--secondary) !important;
        }
    </style>
@endpush
