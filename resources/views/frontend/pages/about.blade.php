@extends('frontend.layouts.master')
@section('title', 'Về Chúng Tôi - Thành Công Land')

@section('content')

    {{-- ══ 1. HERO ══ --}}
    <section class="vct-hero">
        <div class="vct-hero-overlay"></div>
        <div class="container text-center text-white position-relative z-1">
            <div data-aos="fade-down" data-aos-duration="600">
                <span class="vct-hero-badge">VỀ CHÚNG TÔI</span>
            </div>
            <h1 class="vct-hero-title" data-aos="zoom-in" data-aos-duration="700" data-aos-delay="100">
                Thành Công Land
            </h1>
            <p class="vct-hero-sub" data-aos="fade-up" data-aos-duration="600" data-aos-delay="200">
                Hơn 10 năm kiến tạo giá trị — mang đến những giải pháp bất động sản toàn diện
                và không gian sống hoàn hảo cho hàng ngàn gia đình Việt.
            </p>
            {{-- Số liệu nổi bật --}}
            <div class="vct-hero-stats" data-aos="fade-up" data-aos-duration="600" data-aos-delay="300">
                <div class="vct-hero-stat">
                    <strong>10+</strong><span>Năm kinh nghiệm</span>
                </div>
                <div class="vct-hero-stat-sep"></div>
                <div class="vct-hero-stat">
                    <strong>5.000+</strong><span>Khách hàng tin tưởng</span>
                </div>
                <div class="vct-hero-stat-sep"></div>
                <div class="vct-hero-stat">
                    <strong>98%</strong><span>Hài lòng</span>
                </div>
                <div class="vct-hero-stat-sep"></div>
                <div class="vct-hero-stat">
                    <strong>50+</strong><span>Chuyên viên tư vấn</span>
                </div>
            </div>
        </div>
        {{-- Wave bottom --}}
        <div class="vct-hero-wave">
            <svg viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path
                    d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V120H0V95.8C59.71,118.08,130.83,123.63,200.27,112.55Z">
                </path>
            </svg>
        </div>
    </section>

    {{-- ══ 2. CÂU CHUYỆN THƯƠNG HIỆU ══ --}}
    <section class="py-6 bg-white">
        <div class="container">
            <div class="row align-items-center g-5">
                {{-- Ảnh chồng chéo --}}
                <div class="col-lg-6 position-relative vct-img-wrap" data-aos="fade-right" data-aos-duration="700">
                    <img src="https://thanhcongland.com.vn/wp-content/uploads/2025/11/anh-nhan-vien-cong-ty-Thanh-Cong-Land-3-1536x1024.webp"
                        class="img-fluid rounded-4 shadow-lg vct-img-main" alt="Văn phòng Thành Công Land">
                    <img src="https://thanhcongland.com.vn/wp-content/uploads/2025/11/anh-nhan-vien-cong-ty-Thanh-Cong-Land-3-1-600x599.webp"
                        class="img-fluid rounded-4 shadow-lg vct-img-sub border border-4 border-white" alt="Dự án thực tế">
                    {{-- Badge kinh nghiệm --}}
                    <div class="vct-exp-badge" data-aos="zoom-in" data-aos-delay="400">
                        <strong>10+</strong>
                        <span>Năm<br>kinh nghiệm</span>
                    </div>
                </div>

                {{-- Nội dung --}}
                <div class="col-lg-6" data-aos="fade-left" data-aos-duration="700" data-aos-delay="100">
                    <div class="vct-section-label">Câu chuyện thương hiệu</div>
                    <h2 class="section-title fw-800 mb-4">
                        Khởi Nguồn Của <span class="text-primary-brand">Sự Thịnh Vượng</span>
                    </h2>
                    <p class="text-muted mb-3" style="line-height: 1.9; text-align: justify;">
                        Được thành lập với sứ mệnh <em>"Đồng hành cùng mọi tổ ấm"</em>,
                        <strong>Thành Công Land</strong> đã vươn mình trở thành một trong những đơn vị
                        tư vấn và phân phối bất động sản uy tín hàng đầu tại khu vực phía Tây Hà Nội,
                        đặc biệt là đại dự án Vinhomes Smart City.
                    </p>
                    <p class="text-muted mb-4" style="line-height: 1.9; text-align: justify;">
                        Chúng tôi không chỉ bán một căn nhà — chúng tôi trao gửi một không gian sống,
                        một cộng đồng văn minh và một cơ hội đầu tư bền vững. Mọi quyết định đều
                        dựa trên lợi ích cốt lõi của khách hàng.
                    </p>

                    {{-- Điểm mạnh nhanh --}}
                    <div class="vct-quick-points mb-4">
                        <div class="vct-qp-item">
                            <i class="fas fa-check-circle"></i>
                            Pháp lý minh bạch, hỗ trợ 100% thủ tục
                        </div>
                        <div class="vct-qp-item">
                            <i class="fas fa-check-circle"></i>
                            Đội ngũ chuyên viên giàu kinh nghiệm
                        </div>
                        <div class="vct-qp-item">
                            <i class="fas fa-check-circle"></i>
                            Tư vấn miễn phí — không phát sinh chi phí ẩn
                        </div>
                        <div class="vct-qp-item">
                            <i class="fas fa-check-circle"></i>
                            Chăm sóc khách hàng sau bán hàng 24/7
                        </div>
                    </div>

                    {{-- Chữ ký CEO --}}
                    <div class="vct-ceo-sig">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="vct-ceo-logo">
                        <div class="vct-ceo-divider"></div>
                        <div>
                            <div class="vct-ceo-name">Vũ Đình Thủy</div>
                            <div class="vct-ceo-role">CEO & Founder — Thành Công Land</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ══ 3. SỐ LIỆU NỔI BẬT ══ --}}
    <section class="vct-stats-section">
        <div class="container">
            <div class="row g-4 text-center">
                @php
                    $stats = [
                        ['icon' => 'fa-home', 'num' => '5.000+', 'label' => 'Căn nhà đã bàn giao', 'delay' => 0],
                        ['icon' => 'fa-users', 'num' => '50+', 'label' => 'Chuyên viên tư vấn', 'delay' => 100],
                        ['icon' => 'fa-award', 'num' => '10+', 'label' => 'Năm kinh nghiệm', 'delay' => 200],
                        ['icon' => 'fa-star', 'num' => '98%', 'label' => 'Khách hàng hài lòng', 'delay' => 300],
                        ['icon' => 'fa-building', 'num' => '20+', 'label' => 'Dự án hợp tác', 'delay' => 400],
                        [
                            'icon' => 'fa-handshake',
                            'num' => '1.200+',
                            'label' => 'Giao dịch thành công',
                            'delay' => 500,
                        ],
                    ];
                @endphp
                @foreach ($stats as $s)
                    <div class="col-lg-2 col-md-4 col-6" data-aos="fade-up" data-aos-duration="500"
                        data-aos-delay="{{ $s['delay'] }}">
                        <div class="vct-stat-card">
                            <div class="vct-stat-icon">
                                <i class="fas {{ $s['icon'] }}"></i>
                            </div>
                            <div class="vct-stat-num">{{ $s['num'] }}</div>
                            <div class="vct-stat-label">{{ $s['label'] }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ══ 4. TẦM NHÌN & SỨ MỆNH ══ --}}
    <section class="py-6 bg-white">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <div class="vct-section-label">Định hướng phát triển</div>
                <h2 class="section-title fw-800">Tầm Nhìn & Sứ Mệnh</h2>
                <p class="section-subtitle">Những kim chỉ nam định hướng mọi hành động của chúng tôi</p>
            </div>
            <div class="row g-4 justify-content-center">
                <div class="col-lg-5" data-aos="fade-right" data-aos-duration="600">
                    <div class="vct-vm-card vct-vm-card--vision">
                        <div class="vct-vm-icon">
                            <i class="fas fa-eye"></i>
                        </div>
                        <h3 class="vct-vm-title">Tầm Nhìn</h3>
                        <p class="vct-vm-text">
                            Trở thành thương hiệu Dịch vụ Bất động sản số 1 tại Việt Nam — nơi hội tụ
                            của những chuyên gia tận tâm, mang lại hệ sinh thái toàn diện và trải nghiệm
                            vượt trội cho từng khách hàng.
                        </p>
                        <ul class="vct-vm-list">
                            <li><i class="fas fa-angle-right"></i> Mở rộng hoạt động toàn quốc vào 2027</li>
                            <li><i class="fas fa-angle-right"></i> Ứng dụng công nghệ AI vào tư vấn BĐS</li>
                            <li><i class="fas fa-angle-right"></i> Top 5 sàn giao dịch BĐS tại Hà Nội</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-5" data-aos="fade-left" data-aos-duration="600" data-aos-delay="100">
                    <div class="vct-vm-card vct-vm-card--mission">
                        <div class="vct-vm-icon">
                            <i class="fas fa-bullseye"></i>
                        </div>
                        <h3 class="vct-vm-title">Sứ Mệnh</h3>
                        <p class="vct-vm-text">
                            Kiến tạo những tổ ấm hạnh phúc, tối ưu hóa giá trị đầu tư và góp phần nâng
                            tầm chất lượng sống cho cộng đồng thông qua các sản phẩm và dịch vụ bất động
                            sản chất lượng cao.
                        </p>
                        <ul class="vct-vm-list">
                            <li><i class="fas fa-angle-right"></i> Đặt lợi ích khách hàng làm trung tâm</li>
                            <li><i class="fas fa-angle-right"></i> Xây dựng cộng đồng cư dân văn minh</li>
                            <li><i class="fas fa-angle-right"></i> Phát triển bền vững & có trách nhiệm</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ══ 5. GIÁ TRỊ CỐT LÕI ══ --}}
    <section class="py-6 bg-alt-section">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <div class="vct-section-label">DNA của chúng tôi</div>
                <h2 class="section-title fw-800">Giá Trị Cốt Lõi</h2>
                <p class="section-subtitle">Những nguyên tắc vàng dẫn lối mọi hành động tại Thành Công Land</p>
            </div>
            <div class="row g-4">
                @php
                    $values = [
                        [
                            'icon' => 'fa-shield-alt',
                            'title' => 'Minh Bạch',
                            'delay' => 100,
                            'desc' =>
                                'Rõ ràng trong pháp lý, trung thực trong tư vấn. Chúng tôi cung cấp thông tin đa chiều, chính xác nhất đến tay khách hàng.',
                        ],
                        [
                            'icon' => 'fa-heart',
                            'title' => 'Tận Tâm',
                            'delay' => 200,
                            'desc' =>
                                'Lấy khách hàng làm trung tâm. Luôn lắng nghe, thấu hiểu và phục vụ bằng cả trái tim 24/7 trong suốt quá trình đồng hành.',
                        ],
                        [
                            'icon' => 'fa-bolt',
                            'title' => 'Tốc Độ',
                            'delay' => 300,
                            'desc' =>
                                'Xử lý nhanh gọn, thủ tục chuyên nghiệp. Tiết kiệm tối đa thời gian vàng ngọc của khách hàng trong từng giao dịch.',
                        ],
                        [
                            'icon' => 'fa-gem',
                            'title' => 'Chất Lượng',
                            'delay' => 400,
                            'desc' =>
                                'Chỉ giới thiệu những sản phẩm được kiểm định kỹ lưỡng về pháp lý, tiến độ và chủ đầu tư uy tín trên thị trường.',
                        ],
                        [
                            'icon' => 'fa-handshake',
                            'title' => 'Cam Kết',
                            'delay' => 500,
                            'desc' =>
                                'Đồng hành trọn vẹn từ khi tìm kiếm đến khi nhận nhà và cả sau đó. Chúng tôi giữ lời hứa trong từng chi tiết nhỏ.',
                        ],
                        [
                            'icon' => 'fa-lightbulb',
                            'title' => 'Sáng Tạo',
                            'delay' => 600,
                            'desc' =>
                                'Không ngừng đổi mới phương thức tư vấn, ứng dụng công nghệ hiện đại để nâng cao trải nghiệm cho khách hàng.',
                        ],
                    ];
                @endphp
                @foreach ($values as $v)
                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-duration="500"
                        data-aos-delay="{{ $v['delay'] }}">
                        <div class="vct-value-card">
                            <div class="vct-value-icon">
                                <i class="fas {{ $v['icon'] }}"></i>
                            </div>
                            <h5 class="vct-value-title">{{ $v['title'] }}</h5>
                            <p class="vct-value-desc">{{ $v['desc'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ══ 6. QUY TRÌNH LÀM VIỆC ══ --}}
    <section class="py-6 bg-white">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <div class="vct-section-label">Cách chúng tôi làm việc</div>
                <h2 class="section-title fw-800">Quy Trình 4 Bước</h2>
                <p class="section-subtitle">Đơn giản — minh bạch — hiệu quả</p>
            </div>
            <div class="vct-process-wrap">
                @php
                    $steps = [
                        [
                            'num' => '01',
                            'icon' => 'fa-phone-alt',
                            'title' => 'Tiếp nhận',
                            'desc' => 'Lắng nghe nhu cầu và mong muốn thực sự của bạn qua tư vấn 1-1 miễn phí.',
                        ],
                        [
                            'num' => '02',
                            'icon' => 'fa-search',
                            'title' => 'Tìm kiếm',
                            'desc' =>
                                'Sàng lọc và đề xuất danh sách BĐS phù hợp nhất từ cơ sở dữ liệu 10.000+ sản phẩm.',
                        ],
                        [
                            'num' => '03',
                            'icon' => 'fa-file-contract',
                            'title' => 'Pháp lý',
                            'desc' => 'Hỗ trợ toàn bộ thủ tục pháp lý, đàm phán giá và các điều khoản hợp đồng.',
                        ],
                        [
                            'num' => '04',
                            'icon' => 'fa-key',
                            'title' => 'Bàn giao',
                            'desc' => 'Đồng hành đến khi bạn nhận chìa khóa và tiếp tục hỗ trợ sau khi vào ở.',
                        ],
                    ];
                @endphp
                @foreach ($steps as $idx => $step)
                    <div class="vct-process-item" data-aos="fade-up" data-aos-duration="500"
                        data-aos-delay="{{ $idx * 100 }}">
                        <div class="vct-process-icon">
                            <i class="fas {{ $step['icon'] }}"></i>
                        </div>
                        <div class="vct-process-num">{{ $step['num'] }}</div>
                        <h5 class="vct-process-title">{{ $step['title'] }}</h5>
                        <p class="vct-process-desc">{{ $step['desc'] }}</p>
                        @if (!$loop->last)
                            <div class="vct-process-arrow"><i class="fas fa-chevron-right"></i></div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ══ 7. ĐỘI NGŨ LÃNH ĐẠO ══ --}}
    <section class="py-6 bg-alt-section">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <div class="vct-section-label">Con người của chúng tôi</div>
                <h2 class="section-title fw-800">Đội Ngũ Lãnh Đạo</h2>
                <p class="section-subtitle">Những người dẫn dắt Thành Công Land phát triển vững bền</p>
            </div>
            <div class="row g-4 justify-content-center">
                @php
                    $team = [
                        [
                            'name' => 'Vũ Đình Thủy',
                            'role' => 'CEO & Founder',
                            'exp' => '15 năm kinh nghiệm',
                            'delay' => 0,
                        ],
                        [
                            'name' => 'Nguyễn Thu Hà',
                            'role' => 'Giám đốc Kinh doanh',
                            'exp' => '12 năm kinh nghiệm',
                            'delay' => 100,
                        ],
                        [
                            'name' => 'Trần Minh Khoa',
                            'role' => 'Giám đốc Pháp lý',
                            'exp' => '10 năm kinh nghiệm',
                            'delay' => 200,
                        ],
                    ];
                @endphp
                @foreach ($team as $member)
                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-duration="600"
                        data-aos-delay="{{ $member['delay'] }}">
                        <div class="vct-team-card">
                            <div class="vct-team-avatar">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <div class="vct-team-info">
                                <h5 class="vct-team-name">{{ $member['name'] }}</h5>
                                <div class="vct-team-role">{{ $member['role'] }}</div>
                                <div class="vct-team-exp">
                                    <i class="fas fa-briefcase me-1"></i>{{ $member['exp'] }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ══ 8. CTA CUỐI TRANG ══ --}}
    <section class="vct-cta">
        <div class="vct-cta-overlay"></div>
        <div class="container position-relative z-1">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8" data-aos="zoom-in" data-aos-duration="700">
                    <div class="vct-cta-badge">Bắt đầu hành trình</div>
                    <h2 class="text-white fw-800 mb-4" style="font-size:clamp(1.6rem,3vw,2.5rem)">
                        Bạn Đã Sẵn Sàng Tìm Ngôi Nhà Mơ Ước?
                    </h2>
                    <p class="vct-cta-sub mb-5">
                        Hãy để đội ngũ chuyên gia của Thành Công Land giúp bạn biến ước mơ thành
                        hiện thực một cách dễ dàng và an tâm nhất.
                    </p>
                    <div class="d-flex justify-content-center gap-3 flex-wrap">
                        <a href="{{ route('frontend.bat-dong-san.index') }}"
                            class="btn btn-primary-brand rounded-pill px-5 py-3 fw-bold shadow">
                            <i class="fas fa-search me-2"></i>Tìm Nhà Ngay
                        </a>
                        <a href="{{ route('frontend.ky-gui.create') }}"
                            class="btn vct-cta-outline rounded-pill px-5 py-3 fw-bold">
                            <i class="fas fa-home me-2"></i>Ký Gửi Cho Chúng Tôi
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ══ STYLES ══ --}}
    <style>
        .py-6 {
            padding-top: 5rem;
            padding-bottom: 5rem;
        }

        /* ── Hero ── */
        .vct-hero {
            position: relative;
            min-height: 70vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 6rem 0 5rem;
            background:
                linear-gradient(rgba(32, 44, 54, 0.82), rgba(26, 51, 71, 0.82)),
                url('/images/anh-nhan-vien-cong-ty-Thanh-Cong-Land-1536x702.webp') center / cover fixed;
            overflow: hidden;
        }

        .vct-hero-overlay {
            position: absolute;
            inset: 0;
            pointer-events: none;
            background-image:
                radial-gradient(circle at 20% 50%, rgba(192, 102, 42, .12) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(27, 58, 107, .15) 0%, transparent 50%);
        }

        .vct-hero-wave {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            line-height: 0;
        }

        .vct-hero-wave svg {
            width: 100%;
            height: 55px;
            fill: var(--bg-alt);
            display: block;
        }

        .vct-hero-badge {
            display: inline-flex;
            align-items: center;
            background: rgba(192, 102, 42, .2);
            color: var(--primary);
            border: 1px solid var(--primary);
            border-radius: 20px;
            padding: .35rem 1.2rem;
            font-size: .78rem;
            font-weight: 800;
            letter-spacing: 1.5px;
            margin-bottom: 1.2rem;
        }

        .vct-hero-title {
            font-size: clamp(2.2rem, 5vw, 4rem);
            font-weight: 900;
            color: #fff;
            margin-bottom: 1rem;
            line-height: 1.15;
        }

        .vct-hero-sub {
            font-size: 1.05rem;
            color: rgba(255, 255, 255, .8);
            max-width: 680px;
            margin: 0 auto 2.5rem;
            line-height: 1.8;
        }

        .vct-hero-stats {
            display: inline-flex;
            align-items: center;
            background: rgba(255, 255, 255, .08);
            border: 1px solid rgba(255, 255, 255, .15);
            border-radius: 50px;
            padding: .8rem 2rem;
            gap: 1.5rem;
            flex-wrap: wrap;
            justify-content: center;
            backdrop-filter: blur(8px);
        }

        .vct-hero-stat {
            text-align: center;
        }

        .vct-hero-stat strong {
            display: block;
            font-size: 1.4rem;
            font-weight: 900;
            color: var(--primary);
            line-height: 1;
        }

        .vct-hero-stat span {
            font-size: .7rem;
            color: rgba(255, 255, 255, .7);
            white-space: nowrap;
        }

        .vct-hero-stat-sep {
            width: 1px;
            height: 36px;
            background: rgba(255, 255, 255, .2);
        }

        /* ── Section Label ── */
        .vct-section-label {
            display: inline-block;
            font-size: .72rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: var(--primary);
            background: var(--primary-light);
            padding: .3rem 1rem;
            border-radius: 20px;
            margin-bottom: .8rem;
        }

        /* ── Images Story ── */
        .vct-img-wrap {
            min-height: 420px;
        }

        .vct-img-main {
            width: 78%;
            border-radius: 16px !important;
        }

        .vct-img-sub {
            width: 50%;
            position: absolute;
            bottom: -8%;
            right: 3%;
            border-radius: 12px !important;
            transition: transform var(--transition);
        }

        .vct-img-wrap:hover .vct-img-sub {
            transform: translateY(-6px);
        }

        .vct-exp-badge {
            position: absolute;
            top: 20px;
            left: -10px;
            background: var(--secondary);
            color: #fff;
            border-radius: 14px;
            padding: .8rem 1.2rem;
            text-align: center;
            box-shadow: 0 8px 24px rgba(27, 58, 107, .3);
        }

        .vct-exp-badge strong {
            display: block;
            font-size: 1.6rem;
            font-weight: 900;
            color: var(--primary);
            line-height: 1;
        }

        .vct-exp-badge span {
            font-size: .7rem;
            opacity: .85;
            line-height: 1.3;
        }

        /* ── Quick Points ── */
        .vct-quick-points {
            display: flex;
            flex-direction: column;
            gap: .6rem;
        }

        .vct-qp-item {
            display: flex;
            align-items: center;
            gap: .7rem;
            font-size: .9rem;
            color: var(--text-body);
            font-weight: 500;
        }

        .vct-qp-item i {
            color: var(--primary);
            font-size: .85rem;
            flex-shrink: 0;
        }

        /* ── CEO Sig ── */
        .vct-ceo-sig {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 1.2rem;
            background: var(--bg-alt);
            border-radius: 12px;
            border: 1px solid var(--border);
        }

        .vct-ceo-logo {
            height: 48px;
            filter: grayscale(1) opacity(.5);
        }

        .vct-ceo-divider {
            width: 1px;
            height: 40px;
            background: var(--primary);
            flex-shrink: 0;
        }

        .vct-ceo-name {
            font-weight: 800;
            color: var(--text-heading);
            font-size: .95rem;
        }

        .vct-ceo-role {
            font-size: .78rem;
            color: var(--text-muted);
        }

        /* ── Stats Section ── */
        .vct-stats-section {
            background: var(--secondary);
            padding: 4rem 0;
        }

        .vct-stat-card {
            padding: 1.5rem 1rem;
            border-radius: 16px;
            background: rgba(255, 255, 255, .06);
            border: 1px solid rgba(255, 255, 255, .1);
            transition: all var(--transition);
        }

        .vct-stat-card:hover {
            background: rgba(255, 255, 255, .1);
            transform: translateY(-4px);
            border-color: var(--primary);
        }

        .vct-stat-icon {
            font-size: 1.4rem;
            color: var(--primary);
            margin-bottom: .6rem;
        }

        .vct-stat-num {
            font-size: 1.8rem;
            font-weight: 900;
            color: #fff;
            line-height: 1;
            margin-bottom: .3rem;
        }

        .vct-stat-label {
            font-size: .72rem;
            color: rgba(255, 255, 255, .65);
            font-weight: 600;
        }

        /* ── Vision & Mission Cards ── */
        .vct-vm-card {
            border-radius: 20px;
            padding: 2.5rem;
            height: 100%;
            border: 2px solid var(--border);
            background: #fff;
            transition: all var(--transition);
            position: relative;
            overflow: hidden;
        }

        .vct-vm-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), #d9834a);
        }

        .vct-vm-card--mission::before {
            background: linear-gradient(90deg, var(--secondary), var(--secondary-dark));
        }

        .vct-vm-card:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-gold);
            border-color: var(--primary);
        }

        .vct-vm-card--mission:hover {
            box-shadow: 0 8px 24px rgba(27, 58, 107, .2);
            border-color: var(--secondary);
        }

        .vct-vm-icon {
            width: 64px;
            height: 64px;
            background: var(--primary-light);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            color: var(--primary);
            margin-bottom: 1.2rem;
        }

        .vct-vm-card--mission .vct-vm-icon {
            background: rgba(27, 58, 107, .08);
            color: var(--secondary);
        }

        .vct-vm-title {
            font-size: 1.3rem;
            font-weight: 800;
            color: var(--text-heading);
            margin-bottom: .8rem;
        }

        .vct-vm-text {
            color: var(--text-muted);
            line-height: 1.8;
            margin-bottom: 1.2rem;
            font-size: .95rem;
        }

        .vct-vm-list {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            gap: .5rem;
        }

        .vct-vm-list li {
            display: flex;
            align-items: center;
            gap: .6rem;
            font-size: .875rem;
            color: var(--text-body);
            font-weight: 500;
        }

        .vct-vm-list li i {
            color: var(--primary);
            font-size: .75rem;
            flex-shrink: 0;
        }

        /* ── Value Cards ── */
        .vct-value-card {
            padding: 2rem;
            border-radius: 16px;
            border: 1px solid var(--border);
            background: #fff;
            text-align: center;
            height: 100%;
            transition: all var(--transition);
            position: relative;
            overflow: hidden;
        }

        .vct-value-card::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--primary);
            transform: scaleX(0);
            transition: transform var(--transition);
            transform-origin: left;
        }

        .vct-value-card:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-gold);
        }

        .vct-value-card:hover::after {
            transform: scaleX(1);
        }

        .vct-value-icon {
            width: 72px;
            height: 72px;
            background: var(--primary-light);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: var(--primary);
            margin: 0 auto 1rem;
            transition: all var(--transition);
        }

        .vct-value-card:hover .vct-value-icon {
            background: var(--primary);
            color: #fff;
            transform: rotate(-6deg) scale(1.1);
        }

        .vct-value-title {
            font-weight: 800;
            color: var(--text-heading);
            margin-bottom: .6rem;
        }

        .vct-value-desc {
            font-size: .875rem;
            color: var(--text-muted);
            line-height: 1.75;
            margin: 0;
        }

        /* ── Process ── */
        .vct-process-wrap {
            display: flex;
            align-items: flex-start;
            gap: 0;
            flex-wrap: nowrap;
            position: relative;
        }

        .vct-process-item {
            flex: 1;
            text-align: center;
            padding: 2rem 1.5rem;
            position: relative;
            background: #fff;
            border-radius: 16px;
            border: 1px solid var(--border);
            transition: all var(--transition);
        }

        .vct-process-item:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-gold);
        }

        .vct-process-icon {
            width: 64px;
            height: 64px;
            background: var(--primary-light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            color: var(--primary);
            margin: 0 auto .8rem;
            transition: all var(--transition);
        }

        .vct-process-item:hover .vct-process-icon {
            background: var(--primary);
            color: #fff;
        }

        .vct-process-num {
            font-size: .7rem;
            font-weight: 900;
            color: var(--text-muted);
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: .4rem;
        }

        .vct-process-title {
            font-weight: 800;
            color: var(--text-heading);
            margin-bottom: .5rem;
        }

        .vct-process-desc {
            font-size: .82rem;
            color: var(--text-muted);
            line-height: 1.7;
            margin: 0;
        }

        .vct-process-arrow {
            position: absolute;
            top: 50%;
            right: -14px;
            transform: translateY(-50%);
            width: 28px;
            height: 28px;
            background: var(--primary);
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .65rem;
            z-index: 1;
            box-shadow: 0 2px 8px rgba(192, 102, 42, .4);
        }

        /* ── Team ── */
        .vct-team-card {
            background: #fff;
            border-radius: 16px;
            border: 1px solid var(--border);
            padding: 2rem;
            display: flex;
            align-items: center;
            gap: 1.2rem;
            transition: all var(--transition);
        }

        .vct-team-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-gold);
            border-color: var(--primary);
        }

        .vct-team-avatar {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--secondary), var(--secondary-dark));
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            flex-shrink: 0;
            border: 3px solid var(--primary-light);
        }

        .vct-team-name {
            font-weight: 800;
            color: var(--text-heading);
            font-size: 1rem;
            margin-bottom: .2rem;
        }

        .vct-team-role {
            font-size: .82rem;
            color: var(--primary);
            font-weight: 700;
            margin-bottom: .3rem;
        }

        .vct-team-exp {
            font-size: .75rem;
            color: var(--text-muted);
        }

        /* ── CTA Section ── */
        .vct-cta {
            position: relative;
            padding: 7rem 0;
            background:
                linear-gradient(rgba(64, 74, 97, 0.85), rgba(17, 18, 46, 0.85)),
                url('/images/anh-nhan-vien-cong-ty-Thanh-Cong-Land-1536x702.webp') center / cover fixed;
            overflow: hidden;
        }

        .vct-cta-overlay {
            position: absolute;
            inset: 0;
            background-image: radial-gradient(circle at 30% 60%, rgba(192, 102, 42, .15) 0%, transparent 50%);
            pointer-events: none;
        }

        .vct-cta-badge {
            display: inline-block;
            font-size: .72rem;
            font-weight: 800;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--primary);
            border: 1px solid var(--primary);
            border-radius: 20px;
            padding: .3rem 1rem;
            margin-bottom: 1rem;
        }

        .vct-cta-sub {
            font-size: 1.05rem;
            color: rgba(255, 255, 255, .75);
            max-width: 600px;
            margin: 0 auto;
            line-height: 1.8;
        }

        .vct-cta-outline {
            border: 2px solid rgba(255, 255, 255, .5) !important;
            color: #fff !important;
            transition: all var(--transition);
        }

        .vct-cta-outline:hover {
            background: rgba(255, 255, 255, .15) !important;
            border-color: #fff !important;
        }

        /* ── Responsive ── */
        @media (max-width: 991px) {
            .vct-img-main {
                width: 85%;
            }

            .vct-process-wrap {
                flex-wrap: wrap;
                gap: 1rem;
            }

            .vct-process-item {
                flex: 0 0 calc(50% - .5rem);
            }

            .vct-process-arrow {
                display: none;
            }
        }

        @media (max-width: 767px) {
            .vct-hero-stats {
                gap: .8rem;
                padding: .8rem 1.2rem;
            }

            .vct-hero-stat-sep {
                display: none;
            }

            .vct-process-item {
                flex: 0 0 100%;
            }

            .vct-img-sub {
                display: none;
            }

            .vct-exp-badge {
                left: 0;
            }
        }
    </style>

@endsection
