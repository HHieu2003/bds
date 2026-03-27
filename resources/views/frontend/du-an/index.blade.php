@extends('frontend.layouts.master')

@section('title', 'Danh sách Dự án - Thành Công Land')

@section('content')

    {{-- ══════════════════════════════════════════════
     1. HERO BANNER
══════════════════════════════════════════════ --}}
    <section class="du-an-hero position-relative overflow-hidden">

        <div class="du-an-hero-bg" style="background-image: url('/images/banner_duan.jpg')"></div>
        <div class="du-an-hero-overlay"></div>

        <div class="hero-dot hero-dot-1"></div>
        <div class="hero-dot hero-dot-2"></div>
        <div class="hero-dot hero-dot-3"></div>

        <div class="container position-relative z-2 py-4">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8" data-aos="fade-up">

                    <div class="d-inline-flex align-items-center gap-2 hero-badge mb-3">
                        <span class="hero-badge-dot"></span>
                        <span>{{ $duAns->total() }} dự án đang mở bán</span>
                    </div>

                    <h1 class="du-an-hero-title mb-2">
                        Khám Phá Dự Án
                        <span class="title-highlight">Đẳng Cấp</span>
                    </h1>

                    <p class="du-an-hero-desc mb-4">
                        Nơi hội tụ những biểu tượng sống tinh hoa, quy hoạch đồng bộ
                        và tiện ích vượt trội dành cho giới thượng lưu.
                    </p>

                    {{-- Form lọc --}}
                    <div class="hero-search-box" data-aos="fade-up" data-aos-delay="150">
                        <form action="{{ route('frontend.du-an.index') }}" method="GET">
                            <div class="hero-search-inner">

                                <div class="hero-search-field">
                                    <div class="hero-field-icon">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <div class="hero-field-body">
                                        <label>Khu vực</label>
                                        <select name="khu_vuc">
                                            <option value="">Tất cả khu vực</option>
                                            @foreach ($khuVucs as $kv)
                                                <option value="{{ $kv->id }}"
                                                    {{ request('khu_vuc') == $kv->id ? 'selected' : '' }}>
                                                    {{ $kv->ten_khu_vuc }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="hero-search-divider"></div>

                                <div class="hero-search-field flex-grow-1">
                                    <div class="hero-field-icon">
                                        <i class="fas fa-search"></i>
                                    </div>
                                    <div class="hero-field-body">
                                        <label>Tên dự án</label>
                                        <input type="text" name="tu_khoa" placeholder="Nhập tên dự án..."
                                            value="{{ request('tu_khoa') }}">
                                    </div>
                                </div>

                                <button type="submit" class="hero-search-btn">
                                    <i class="fas fa-search me-2"></i> Tìm kiếm
                                </button>
                            </div>
                        </form>

                        <div class="hero-tags mt-3">
                            <span class="hero-tag-label">
                                <i class="fas fa-fire me-1" style="color:#FF8C42"></i> Phổ biến:
                            </span>
                            <a href="{{ route('frontend.du-an.index', ['tu_khoa' => 'Vinhomes']) }}"
                                class="hero-tag">Vinhomes</a>
                            <a href="{{ route('frontend.du-an.index', ['tu_khoa' => 'Masteri']) }}"
                                class="hero-tag">Masteri</a>
                            <a href="{{ route('frontend.du-an.index', ['tu_khoa' => 'Sunwah Pearl']) }}"
                                class="hero-tag">Sunwah Pearl</a>
                            @foreach ($khuVucs->take(2) as $kv)
                                <a href="{{ route('frontend.du-an.index', ['khu_vuc' => $kv->id]) }}"
                                    class="hero-tag">{{ $kv->ten_khu_vuc }}</a>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>

            {{-- Thống kê --}}
            <div class="row justify-content-center mt-4" data-aos="fade-up" data-aos-delay="250">
                <div class="col-auto">
                    <div class="hero-stats">
                        <div class="hero-stat-item">
                            <strong>{{ $duAns->total() }}+</strong>
                            <span>Dự án</span>
                        </div>
                        <div class="hero-stat-divider"></div>
                        <div class="hero-stat-item">
                            <strong>{{ $khuVucs->count() }}+</strong>
                            <span>Khu vực</span>
                        </div>
                        <div class="hero-stat-divider"></div>
                        <div class="hero-stat-item">
                            <strong>500+</strong>
                            <span>Căn hộ</span>
                        </div>
                        <div class="hero-stat-divider"></div>
                        <div class="hero-stat-item">
                            <strong>98%</strong>
                            <span>Hài lòng</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════════════
     2. DANH SÁCH DỰ ÁN
══════════════════════════════════════════════ --}}
    <section class="py-5" style="background-color:#F8FAFC;">
        <div class="container py-4">

            <div class="d-flex justify-content-between align-items-center mb-5 border-bottom pb-3">
                <div>
                    <h3 class="fw-bold serif-font text-dark mb-1">
                        @if (request('khu_vuc') && is_numeric(request('khu_vuc')))
                            Dự án tại:
                            <span style="color:#FF8C42">
                                {{ $khuVucs->where('id', request('khu_vuc'))->first()->ten_khu_vuc ?? 'Khu vực này' }}
                            </span>
                        @elseif(request('tu_khoa'))
                            Kết quả tìm kiếm:
                            <span style="color:#FF8C42">"{{ request('tu_khoa') }}"</span>
                        @else
                            Tất cả Dự Án
                        @endif
                    </h3>
                    @if (request('khu_vuc') || request('tu_khoa'))
                        <a href="{{ route('frontend.du-an.index') }}" class="text-muted text-decoration-none"
                            style="font-size:.82rem">
                            <i class="fas fa-times-circle me-1"></i> Xóa bộ lọc
                        </a>
                    @endif
                </div>
                <span class="badge bg-light text-dark border px-3 py-2 fs-6 shadow-sm">
                    Tìm thấy <strong style="color:#FF8C42">{{ $duAns->total() }}</strong> dự án
                </span>
            </div>

            <div class="row g-4">
                @forelse($duAns as $da)
                    <div class="col-lg-4 col-md-6" data-aos="fade-up"
                        data-aos-delay="{{ (($loop->iteration - 1) % 3) * 100 }}">

                        <div class="da-card h-100"
                            onclick="window.location.href='{{ route('frontend.du-an.show', $da->slug) }}'">

                            <div class="da-card-img-wrap">
                                <img src="{{ $da->hinh_anh_dai_dien
                                    ? asset('storage/' . $da->hinh_anh_dai_dien)
                                    : 'https://vinhomesland.vn/wp-content/uploads/2023/10/be-boi-the-canopy-residences-vinhomes-smart-city.jpg' }}"
                                    class="da-card-img" alt="{{ $da->ten_du_an }}">

                                <div class="da-card-overlay"></div>

                                <div class="da-card-badges">
                                    <span class="da-badge-status">
                                        <span class="da-badge-dot"></span>
                                        Đang mở bán
                                    </span>
                                    @if ($loop->iteration <= 3)
                                        <span class="da-badge-hot">
                                            <i class="fas fa-fire me-1"></i> Hot
                                        </span>
                                    @endif
                                </div>

                                @if ($da->khuVuc)
                                    <div class="da-card-kv">
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        {{ $da->khuVuc->ten_khu_vuc }}
                                    </div>
                                @endif

                                <div class="da-card-info">
                                    <h4 class="da-card-title">{{ $da->ten_du_an }}</h4>
                                    <p class="da-card-addr">
                                        <i class="fas fa-location-dot me-1" style="color:#FF8C42"></i>
                                        {{ $da->dia_chi }}
                                    </p>
                                </div>
                            </div>

                            {{-- Footer card --}}
                            <div class="da-card-footer">
                                <a href="{{ route('frontend.du-an.show', $da->slug) }}" class="da-footer-btn">
                                    <span>Xem chi tiết dự án</span>
                                    <div class="da-footer-arrow">
                                        <i class="fas fa-arrow-right"></i>
                                    </div>
                                </a>
                            </div>

                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <img src="https://cdn-icons-png.flaticon.com/512/7486/7486831.png" alt="Không tìm thấy"
                            style="width:120px;opacity:.4" class="mb-4">
                        <h4 class="text-muted fw-bold mb-2">Không tìm thấy dự án nào!</h4>
                        <p class="text-muted mb-4">Vui lòng thử chọn khu vực khác hoặc bỏ bớt điều kiện lọc.</p>
                        <a href="{{ route('frontend.du-an.index') }}" class="btn rounded-pill px-4 fw-bold"
                            style="background:#FF8C42;color:#fff">
                            <i class="fas fa-redo me-2"></i> Xem tất cả dự án
                        </a>
                    </div>
                @endforelse
            </div>

            <div class="d-flex justify-content-center mt-5">
                {{ $duAns->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>

        </div>
    </section>

    <style>
        /* ══ HERO ══ */
        .du-an-hero {
            min-height: 40vh;
            display: flex;
            align-items: center;
        }

        .du-an-hero-bg {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            transform: scale(1.05);
            transition: transform 8s ease;
        }

        .du-an-hero:hover .du-an-hero-bg {
            transform: scale(1);
        }

        .du-an-hero-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(10, 20, 40, .88) 0%, rgba(26, 60, 94, .75) 50%, rgba(10, 20, 40, .88) 100%);
        }

        .hero-dot {
            position: absolute;
            border-radius: 50%;
            opacity: .08;
            background: #FF8C42;
            animation: pulse-dot 4s ease-in-out infinite;
        }

        .hero-dot-1 {
            width: 400px;
            height: 400px;
            top: -100px;
            right: -100px;
        }

        .hero-dot-2 {
            width: 250px;
            height: 250px;
            bottom: -60px;
            left: -60px;
            animation-delay: 2s;
        }

        .hero-dot-3 {
            width: 150px;
            height: 150px;
            top: 40%;
            left: 5%;
            animation-delay: 1s;
        }

        @keyframes pulse-dot {

            0%,
            100% {
                transform: scale(1);
                opacity: .08;
            }

            50% {
                transform: scale(1.1);
                opacity: .13;
            }
        }

        .hero-badge {
            background: rgba(255, 140, 66, .15);
            border: 1px solid rgba(255, 140, 66, .35);
            color: #FFB380;
            font-size: .78rem;
            font-weight: 600;
            padding: .4rem 1rem;
            border-radius: 50px;
            letter-spacing: .5px;
        }

        .hero-badge-dot {
            width: 7px;
            height: 7px;
            background: #FF8C42;
            border-radius: 50%;
            animation: blink 1.5s infinite;
        }

        @keyframes blink {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: .2;
            }
        }

        .du-an-hero-title {
            font-size: clamp(1.8rem, 4vw, 2.8rem);
            font-weight: 900;
            color: #fff;
            line-height: 1.2;
            letter-spacing: -1px;
        }

        .title-highlight {
            background: linear-gradient(135deg, #FF8C42, #FFD700);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .du-an-hero-desc {
            color: rgba(255, 255, 255, .7);
            font-size: .92rem;
            line-height: 1.7;
            max-width: 520px;
            margin: 0 auto;
        }

        .hero-search-box {
            background: rgba(255, 255, 255, .06);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, .12);
            border-radius: 20px;
            padding: 1rem 1.2rem;
        }

        .hero-search-inner {
            display: flex;
            align-items: center;
            background: #fff;
            border-radius: 14px;
            padding: .5rem .5rem .5rem 1rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, .15);
        }

        .hero-search-field {
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: .4rem .6rem;
            min-width: 175px;
        }

        .hero-field-icon {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            background: #fff5ef;
            color: #FF8C42;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .82rem;
            flex-shrink: 0;
        }

        .hero-field-body {
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .hero-field-body label {
            font-size: .65rem;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: .5px;
            margin-bottom: 1px;
        }

        .hero-field-body select,
        .hero-field-body input {
            border: none;
            outline: none;
            font-size: .86rem;
            font-weight: 600;
            color: #1a3c5e;
            background: transparent;
            padding: 0;
            width: 100%;
        }

        .hero-search-divider {
            width: 1px;
            height: 34px;
            background: #e5e7eb;
            margin: 0 .5rem;
            flex-shrink: 0;
        }

        .hero-search-btn {
            background: linear-gradient(135deg, #FF8C42, #FF5722);
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: .7rem 1.4rem;
            font-weight: 700;
            font-size: .86rem;
            cursor: pointer;
            white-space: nowrap;
            transition: all .25s;
            box-shadow: 0 4px 15px rgba(255, 87, 34, .35);
            flex-shrink: 0;
        }

        .hero-search-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 87, 34, .45);
        }

        .hero-tags {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: .4rem;
        }

        .hero-tag-label {
            color: rgba(255, 255, 255, .6);
            font-size: .75rem;
            font-weight: 600;
        }

        .hero-tag {
            background: rgba(255, 255, 255, .1);
            border: 1px solid rgba(255, 255, 255, .18);
            color: rgba(255, 255, 255, .85);
            font-size: .73rem;
            font-weight: 600;
            padding: .22rem .7rem;
            border-radius: 50px;
            text-decoration: none;
            transition: all .2s;
        }

        .hero-tag:hover {
            background: #FF8C42;
            border-color: #FF8C42;
            color: #fff;
        }

        .hero-stats {
            display: flex;
            align-items: center;
            background: rgba(255, 255, 255, .07);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, .1);
            border-radius: 60px;
            padding: .7rem 1.8rem;
            gap: 1.4rem;
        }

        .hero-stat-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1px;
        }

        .hero-stat-item strong {
            font-size: 1.2rem;
            font-weight: 900;
            color: #FF8C42;
            line-height: 1;
        }

        .hero-stat-item span {
            font-size: .7rem;
            color: rgba(255, 255, 255, .6);
            font-weight: 500;
            white-space: nowrap;
        }

        .hero-stat-divider {
            width: 1px;
            height: 28px;
            background: rgba(255, 255, 255, .15);
        }

        /* ══ CARD ══ */
        .da-card {
            background: #fff;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, .07);
            transition: all .35s cubic-bezier(.25, .8, .25, 1);
            cursor: pointer;
            border: 1px solid #f1f5f9;
            display: flex;
            flex-direction: column;
        }

        .da-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 50px rgba(0, 0, 0, .13);
        }

        .da-card-img-wrap {
            position: relative;
            height: 280px;
            overflow: hidden;
            flex-shrink: 0;
        }

        .da-card-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .65s ease;
        }

        .da-card:hover .da-card-img {
            transform: scale(1.08);
        }

        .da-card-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(10, 20, 40, .88) 0%, rgba(10, 20, 40, .3) 55%, transparent 100%);
        }

        .da-card-badges {
            position: absolute;
            top: 14px;
            left: 14px;
            display: flex;
            gap: 6px;
        }

        .da-badge-status {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: rgba(16, 185, 129, .9);
            color: #fff;
            font-size: .72rem;
            font-weight: 700;
            padding: .28rem .7rem;
            border-radius: 50px;
        }

        .da-badge-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #fff;
            animation: blink 1.4s infinite;
        }

        .da-badge-hot {
            background: rgba(255, 87, 34, .9);
            color: #fff;
            font-size: .72rem;
            font-weight: 700;
            padding: .28rem .7rem;
            border-radius: 50px;
        }

        .da-card-kv {
            position: absolute;
            top: 14px;
            right: 14px;
            background: rgba(15, 23, 42, .7);
            backdrop-filter: blur(6px);
            color: #fff;
            font-size: .72rem;
            font-weight: 600;
            padding: .28rem .7rem;
            border-radius: 50px;
            border: 1px solid rgba(255, 255, 255, .15);
        }

        .da-card-info {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 1.2rem 1.4rem;
        }

        .da-card-title {
            font-size: 1rem;
            font-weight: 800;
            color: #fff;
            margin: 0 0 4px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .da-card-addr {
            font-size: .78rem;
            color: rgba(255, 255, 255, .75);
            margin: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* ══ FOOTER CARD ══ */
        .da-card-footer {
            padding: .9rem 1.2rem 1.1rem;
            margin-top: auto;
        }

        .da-footer-btn {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: linear-gradient(135deg, #fff5ef, #fff8f4);
            border: 1.5px solid #ffe0cc;
            border-radius: 12px;
            padding: .7rem 1rem;
            text-decoration: none;
            transition: all .25s ease;
            gap: .5rem;
        }

        .da-footer-btn span {
            font-size: .82rem;
            font-weight: 700;
            color: #FF5722;
            white-space: nowrap;
        }

        .da-footer-arrow {
            width: 30px;
            height: 30px;
            border-radius: 9px;
            background: linear-gradient(135deg, #FF8C42, #FF5722);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .78rem;
            flex-shrink: 0;
            transition: all .25s ease;
            box-shadow: 0 4px 12px rgba(255, 87, 34, .35);
        }

        .da-footer-btn:hover {
            background: linear-gradient(135deg, #FF8C42, #FF5722);
            border-color: #FF8C42;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(255, 87, 34, .25);
        }

        .da-footer-btn:hover span {
            color: #fff;
        }

        .da-footer-btn:hover .da-footer-arrow {
            background: rgba(255, 255, 255, .25);
            transform: translateX(3px);
            box-shadow: none;
        }

        /* ══ RESPONSIVE ══ */
        @media(max-width:768px) {
            .du-an-hero {
                min-height: auto;
            }

            .hero-search-inner {
                flex-direction: column;
                padding: .8rem;
                border-radius: 12px;
            }

            .hero-search-field {
                width: 100%;
                min-width: unset;
            }

            .hero-search-divider {
                width: 100%;
                height: 1px;
                margin: 0;
            }

            .hero-search-btn {
                width: 100%;
                border-radius: 10px;
            }

            .hero-stats {
                gap: .9rem;
                padding: .6rem 1rem;
            }

            .hero-stat-item strong {
                font-size: 1rem;
            }

            .da-card-img-wrap {
                height: 220px;
            }
        }
    </style>

@endsection
