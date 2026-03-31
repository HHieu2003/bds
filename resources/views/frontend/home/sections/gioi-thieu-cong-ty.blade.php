{{-- ══════════════════════════════════════════════
     GIỚI THIỆU CÔNG TY — THÀNH CÔNG LAND
══════════════════════════════════════════════ --}}
<section class="ve-cty-section">

    {{-- Nền trang trí --}}
    <div class="ve-cty-bg-accent" aria-hidden="true"></div>
    <div class="ve-cty-bg-grid" aria-hidden="true"></div>

    <div class="container py-5">
        <div class="ve-cty-wrapper">

            {{-- ── CỘT TRÁI: VISUAL BLOCK ── --}}
            <div class="ve-cty-visual" data-aos="fade-right" data-aos-duration="800">

                {{-- Ảnh chính --}}
                <div class="ve-cty-img-main-wrap">
                    <img src="https://thanhcongland.com.vn/wp-content/uploads/2025/11/anh-nhan-vien-cong-ty-Thanh-Cong-Land-2-1536x1040.webp"
                        alt="Đội ngũ nhân viên Thành Công Land" class="ve-cty-img-main" width="640" height="433"
                        loading="lazy">

                    {{-- Overlay gradient nhẹ --}}
                    <div class="ve-cty-img-overlay" aria-hidden="true"></div>
                </div>

                {{-- Badge nổi: Năm kinh nghiệm --}}
                <div class="ve-cty-badge-float ve-cty-badge-years" data-aos="zoom-in" data-aos-delay="300">
                    <div class="ve-cty-badge-num">10<span>+</span></div>
                    <div class="ve-cty-badge-label">Năm<br>Uy Tín</div>
                </div>

                {{-- Badge nổi: Khách hàng --}}
                <div class="ve-cty-badge-float ve-cty-badge-clients" data-aos="zoom-in" data-aos-delay="450">
                    <div class="ve-cty-badge-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div>
                        <div class="ve-cty-badge-num-sm">5000<span>+</span></div>
                        <div class="ve-cty-badge-label-sm">Khách hài lòng</div>
                    </div>
                </div>

                {{-- Đường kẻ trang trí --}}
                <div class="ve-cty-line-deco" aria-hidden="true"></div>
            </div>

            {{-- ── CỘT PHẢI: NỘI DUNG ── --}}
            <div class="ve-cty-content" data-aos="fade-left" data-aos-duration="800" data-aos-delay="100">

                {{-- Nhãn phân mục --}}
                <div class="ve-cty-eyebrow">
                    <span class="ve-cty-eyebrow-line"></span>
                    <span>VỀ THÀNH CÔNG LAND</span>
                </div>

                {{-- Tiêu đề chính --}}
                <h2 class="ve-cty-heading">
                    Nơi Gửi Trọn Niềm Tin<br>
                    <em class="ve-cty-heading-em">An Cư Lập Nghiệp</em>
                </h2>

                {{-- Mô tả --}}
                <p class="ve-cty-desc">
                    Với hơn một thập kỷ tiên phong trong lĩnh vực phân phối, mua bán và cho thuê bất động sản
                    cao cấp tại khu vực phía Tây Hà Nội — <strong>Thành Công Land</strong> là cầu nối vững chắc
                    mang đến những giải pháp an cư hoàn hảo và cơ hội đầu tư sinh lời vượt trội.
                </p>

                {{-- Danh sách điểm mạnh --}}
                <ul class="ve-cty-features" aria-label="Điểm mạnh của Thành Công Land">
                    @php
                        $features = [
                            [
                                'icon' => 'fa-gem',
                                'title' => 'Sản phẩm thực, Giá trị thực',
                                'sub' => 'Cam kết minh bạch từ đầu đến cuối',
                            ],
                            [
                                'icon' => 'fa-shield-halved',
                                'title' => 'Pháp lý an toàn',
                                'sub' => 'Đầy đủ giấy tờ, sổ hồng rõ ràng',
                            ],
                            [
                                'icon' => 'fa-handshake',
                                'title' => 'Đồng hành trọn đời',
                                'sub' => 'Hỗ trợ sau giao dịch tận tâm',
                            ],
                            [
                                'icon' => 'fa-chart-line',
                                'title' => 'Đầu tư sinh lời tối đa',
                                'sub' => 'Tư vấn chiến lược từ chuyên gia',
                            ],
                        ];
                    @endphp
                    @foreach ($features as $ft)
                        <li class="ve-cty-feat-item">
                            <div class="ve-cty-feat-icon" aria-hidden="true">
                                <i class="fas {{ $ft['icon'] }}"></i>
                            </div>
                            <div class="ve-cty-feat-text">
                                <strong>{{ $ft['title'] }}</strong>
                                <span>{{ $ft['sub'] }}</span>
                            </div>
                        </li>
                    @endforeach
                </ul>

                {{-- CTA --}}
                <div class="ve-cty-cta-row">
                    <a href="{{ route('frontend.gioi-thieu') }}" class="ve-cty-btn-primary">
                        Khám Phá Chi Tiết
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.5" aria-hidden="true">
                            <path d="M5 12h14M12 5l7 7-7 7" />
                        </svg>
                    </a>
                    <a href="tel:+84123456789" class="ve-cty-btn-ghost">
                        <i class="fas fa-phone-flip me-2" aria-hidden="true"></i>
                        Gọi Tư Vấn Ngay
                    </a>
                </div>
            </div>

        </div>{{-- /.ve-cty-wrapper --}}

        {{-- ── THANH THỐNG KÊ DƯỚI ── --}}
        <div class="ve-cty-stats" data-aos="fade-up" data-aos-delay="200">
            @php
                $stats = [
                    ['num' => '5000', 'suffix' => '+', 'label' => 'Khách Hàng Hài Lòng', 'icon' => 'fa-users'],
                    ['num' => '50', 'suffix' => '+', 'label' => 'Dự Án Quản Lý', 'icon' => 'fa-building'],
                    ['num' => '100', 'suffix' => '+', 'label' => 'Nhân Sự Tâm Huyết', 'icon' => 'fa-user-tie'],
                    ['num' => '24', 'suffix' => '/7', 'label' => 'Hỗ Trợ Tận Tình', 'icon' => 'fa-headset'],
                ];
            @endphp
            @foreach ($stats as $i => $st)
                <div class="ve-cty-stat-item" data-aos="fade-up" data-aos-delay="{{ 200 + $i * 80 }}">
                    <div class="ve-cty-stat-icon" aria-hidden="true">
                        <i class="fas {{ $st['icon'] }}"></i>
                    </div>
                    <div class="ve-cty-stat-num">
                        {{ $st['num'] }}<span class="ve-cty-stat-suffix">{{ $st['suffix'] }}</span>
                    </div>
                    <div class="ve-cty-stat-label">{{ $st['label'] }}</div>
                </div>
                @if (!$loop->last)
                    <div class="ve-cty-stat-divider" aria-hidden="true"></div>
                @endif
            @endforeach
        </div>

    </div>{{-- /.container --}}
</section>

@push('styles')
    <style>
        /* ══════════════════════════════════════════
                   SECTION VỀ CÔNG TY — THÀNH CÔNG LAND
                ══════════════════════════════════════════ */

        .ve-cty-section {
            position: relative;
            overflow: hidden;
            background: var(--bg-alt);
            padding: clamp(3.5rem, 4vw, 6rem) 0;
        }

        /* Nền accent tinh tế */
        .ve-cty-bg-accent {
            position: absolute;
            top: -120px;
            right: -120px;
            width: 520px;
            height: 520px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(var(--primary-rgb, 179, 134, 64), .07) 0%, transparent 70%);
            pointer-events: none;
        }

        /* Lưới chấm nền */
        .ve-cty-bg-grid {
            position: absolute;
            inset: 0;
            background-image: radial-gradient(circle, rgba(0, 0, 0, .05) 1px, transparent 1px);
            background-size: 28px 28px;
            pointer-events: none;
            opacity: .5;
        }

        /* ── WRAPPER 2 CỘT ── */
        .ve-cty-wrapper {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: clamp(2rem, 5vw, 5rem);
            align-items: center;
            margin-bottom: clamp(2.5rem, 5vw, 4rem);
        }

        /* ═══════════════════
                   CỘT TRÁI: VISUAL
                ═══════════════════ */
        .ve-cty-visual {
            position: relative;
        }

        .ve-cty-img-main-wrap {
            position: relative;
            border-radius: 1.25rem;
            overflow: hidden;
            box-shadow:
                0 2px 4px rgba(0, 0, 0, .06),
                0 16px 48px rgba(0, 0, 0, .12);
            aspect-ratio: 4/3;
        }

        .ve-cty-img-main {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform .8s cubic-bezier(.25, .8, .25, 1);
        }

        .ve-cty-img-main-wrap:hover .ve-cty-img-main {
            transform: scale(1.04);
        }

        .ve-cty-img-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top,
                    rgba(10, 20, 40, .18) 0%,
                    transparent 50%);
            pointer-events: none;
        }

        /* Badge nổi chung */
        .ve-cty-badge-float {
            position: absolute;
            background: #fff;
            border-radius: 1rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, .13);
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: .9rem 1.2rem;
        }

        /* Badge Năm Uy Tín — góc dưới phải */
        .ve-cty-badge-years {
            bottom: -1.2rem;
            right: -1.2rem;
            flex-direction: column;
            align-items: center;
            gap: .2rem;
            padding: 1.1rem 1.4rem;
            border-top: 4px solid var(--primary);
            min-width: 96px;
            text-align: center;
        }

        .ve-cty-badge-num {
            font-size: 2.2rem;
            font-weight: 900;
            color: var(--primary);
            line-height: 1;
        }

        .ve-cty-badge-num span {
            font-size: 1.1rem;
            color: var(--primary);
        }

        .ve-cty-badge-label {
            font-size: .7rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: .6px;
            line-height: 1.4;
        }

        /* Badge Khách hàng — góc trên trái */
        .ve-cty-badge-clients {
            top: 1.2rem;
            left: -1.2rem;
            padding: .75rem 1rem;
            gap: .6rem;
        }

        .ve-cty-badge-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: var(--primary-light, #FFF3E0);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .9rem;
            flex-shrink: 0;
        }

        .ve-cty-badge-num-sm {
            font-size: 1.05rem;
            font-weight: 800;
            color: var(--text-heading, #1a3c5e);
            line-height: 1;
        }

        .ve-cty-badge-num-sm span {
            font-size: .8rem;
            color: var(--primary);
        }

        .ve-cty-badge-label-sm {
            font-size: .68rem;
            color: #94a3b8;
            font-weight: 500;
            margin-top: 1px;
        }

        /* Đường kẻ trang trí góc dưới trái */
        .ve-cty-line-deco {
            position: absolute;
            bottom: -2.5rem;
            left: 2rem;
            width: 80px;
            height: 4px;
            background: var(--primary);
            border-radius: 2px;
        }

        .ve-cty-line-deco::before {
            content: '';
            position: absolute;
            left: 90px;
            width: 16px;
            height: 4px;
            background: var(--primary);
            border-radius: 2px;
            opacity: .4;
        }

        /* ══════════════════
                   CỘT PHẢI: NỘI DUNG
                ══════════════════ */
        .ve-cty-content {
            display: flex;
            flex-direction: column;
            gap: 1.4rem;
        }

        /* Eyebrow */
        .ve-cty-eyebrow {
            display: flex;
            align-items: center;
            gap: .75rem;
            font-size: .72rem;
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--primary);
        }

        .ve-cty-eyebrow-line {
            display: block;
            width: 32px;
            height: 2px;
            background: var(--primary);
            border-radius: 1px;
            flex-shrink: 0;
        }

        /* Tiêu đề */
        .ve-cty-heading {
            font-size: clamp(1.7rem, 3vw, 2.4rem);
            font-weight: 800;
            color: var(--text-heading, #1a3c5e);
            line-height: 1.25;
            margin: 0;
        }

        .ve-cty-heading-em {
            font-style: normal;
            color: var(--primary);
            position: relative;
            display: inline-block;
        }

        /* Gạch chân sóng dưới tiêu đề nhấn */
        .ve-cty-heading-em::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -4px;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, var(--primary), transparent);
            border-radius: 2px;
        }

        /* Mô tả */
        .ve-cty-desc {
            font-size: .95rem;
            color: #64748b;
            line-height: 1.85;
            margin: 0;
        }

        .ve-cty-desc strong {
            color: var(--text-heading, #1a3c5e);
            font-weight: 700;
        }

        /* Danh sách đặc điểm */
        .ve-cty-features {
            list-style: none;
            margin: 0;
            padding: 0;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: .9rem;
        }

        .ve-cty-feat-item {
            display: flex;
            align-items: flex-start;
            gap: .8rem;
            padding: .85rem 1rem;
            background: #fff;
            border-radius: .875rem;
            border: 1px solid rgba(0, 0, 0, .06);
            transition: box-shadow .22s ease, transform .22s ease;
        }

        .ve-cty-feat-item:hover {
            box-shadow: 0 6px 20px rgba(0, 0, 0, .08);
            transform: translateY(-2px);
        }

        .ve-cty-feat-icon {
            width: 36px;
            height: 36px;
            border-radius: 9px;
            background: var(--primary-light, #FFF3E0);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .82rem;
            flex-shrink: 0;
            transition: background .22s, color .22s;
        }

        .ve-cty-feat-item:hover .ve-cty-feat-icon {
            background: var(--primary);
            color: #fff;
        }

        .ve-cty-feat-text {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .ve-cty-feat-text strong {
            font-size: .82rem;
            font-weight: 700;
            color: var(--text-heading, #1a3c5e);
            line-height: 1.3;
        }

        .ve-cty-feat-text span {
            font-size: .72rem;
            color: #94a3b8;
            line-height: 1.4;
        }

        /* Hàng CTA */
        .ve-cty-cta-row {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
            padding-top: .4rem;
        }

        .ve-cty-btn-primary {
            display: inline-flex;
            align-items: center;
            gap: .6rem;
            background: var(--primary);
            color: #fff;
            font-weight: 700;
            font-size: .88rem;
            padding: .8rem 1.8rem;
            border-radius: 50px;
            text-decoration: none;
            transition: background .22s, transform .22s, box-shadow .22s;
            box-shadow: 0 4px 16px rgba(var(--primary-rgb, 179, 134, 64), .3);
        }

        .ve-cty-btn-primary:hover {
            background: var(--primary-dark, #9a7030);
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 8px 28px rgba(var(--primary-rgb, 179, 134, 64), .4);
        }

        .ve-cty-btn-primary svg {
            transition: transform .22s;
        }

        .ve-cty-btn-primary:hover svg {
            transform: translateX(4px);
        }

        .ve-cty-btn-ghost {
            display: inline-flex;
            align-items: center;
            font-size: .85rem;
            font-weight: 600;
            color: var(--text-heading, #1a3c5e);
            text-decoration: none;
            padding: .75rem 1.4rem;
            border-radius: 50px;
            border: 1.5px solid rgba(0, 0, 0, .12);
            background: transparent;
            transition: border-color .2s, color .2s, background .2s;
        }

        .ve-cty-btn-ghost:hover {
            border-color: var(--primary);
            color: var(--primary);
            background: var(--primary-light, #FFF3E0);
        }

        /* ══════════════════
                   THANH THỐNG KÊ
                ══════════════════ */
        .ve-cty-stats {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0;
            background: #fff;
            border-radius: 1.25rem;
            box-shadow:
                0 2px 6px rgba(0, 0, 0, .05),
                0 12px 40px rgba(0, 0, 0, .08);
            padding: 0;
            overflow: hidden;
            border: 1px solid rgba(0, 0, 0, .06);
        }

        .ve-cty-stat-item {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: .35rem;
            padding: 1.8rem 1rem;
            text-align: center;
            transition: background .22s;
            position: relative;
        }

        .ve-cty-stat-item:hover {
            background: #F8FAFC;
        }

        .ve-cty-stat-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: var(--primary-light, #FFF3E0);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .9rem;
            margin-bottom: .2rem;
            transition: background .22s, color .22s, transform .22s;
        }

        .ve-cty-stat-item:hover .ve-cty-stat-icon {
            background: var(--primary);
            color: #fff;
            transform: scale(1.08);
        }

        .ve-cty-stat-num {
            font-size: clamp(1.5rem, 3vw, 2rem);
            font-weight: 900;
            color: var(--primary);
            line-height: 1;
        }

        .ve-cty-stat-suffix {
            font-size: .9em;
            font-weight: 700;
            color: #94a3b8;
        }

        .ve-cty-stat-label {
            font-size: .73rem;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: .5px;
            line-height: 1.3;
        }

        .ve-cty-stat-divider {
            width: 1px;
            height: 60px;
            background: rgba(0, 0, 0, .07);
            flex-shrink: 0;
            align-self: center;
        }

        /* ══ RESPONSIVE ══ */
        @media (max-width: 991px) {
            .ve-cty-wrapper {
                grid-template-columns: 1fr;
                gap: 3rem;
            }

            .ve-cty-visual {
                max-width: 560px;
                margin: 0 auto;
                width: 100%;
            }

            .ve-cty-heading {
                font-size: clamp(1.6rem, 5vw, 2rem);
            }
        }

        @media (max-width: 767px) {
            .ve-cty-badge-clients {
                left: .5rem;
                top: .5rem;
            }

            .ve-cty-badge-years {
                right: .5rem;
                bottom: -1rem;
                min-width: 80px;
            }

            .ve-cty-features {
                grid-template-columns: 1fr;
            }

            .ve-cty-stats {
                display: grid;
                grid-template-columns: 1fr 1fr;
                border-radius: 1rem;
            }

            .ve-cty-stat-divider {
                display: none;
            }

            .ve-cty-stat-item {
                padding: 1.3rem .75rem;
                border-bottom: 1px solid rgba(0, 0, 0, .06);
            }

            .ve-cty-stat-item:nth-child(odd):not(:last-child) {
                border-right: 1px solid rgba(0, 0, 0, .06);
            }

            .ve-cty-cta-row {
                flex-direction: column;
                align-items: stretch;
            }

            .ve-cty-btn-primary,
            .ve-cty-btn-ghost {
                justify-content: center;
                text-align: center;
            }
        }

        @media (max-width: 479px) {
            .ve-cty-stats {
                grid-template-columns: 1fr;
            }

            .ve-cty-stat-item:nth-child(odd) {
                border-right: none;
            }

            .ve-cty-stat-divider {
                display: none;
            }
        }
    </style>
@endpush
