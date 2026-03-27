@extends('frontend.layouts.master')
@section('title', $duAn->ten_du_an . ' - Thành Công Land')

@section('content')

    @php
        $bdsBan = $duAn->batDongSans ? $duAn->batDongSans->where('nhu_cau', 'ban')->where('hien_thi', 1) : collect();
        $bdsThue = $duAn->batDongSans ? $duAn->batDongSans->where('nhu_cau', 'thue')->where('hien_thi', 1) : collect();
        $tongCan = $bdsBan->count() + $bdsThue->count();
    @endphp

    {{-- ══ 1. HERO ══ --}}
    <section class="dact-hero">
        <div class="dact-hero-overlay"></div>
        <div class="container position-relative z-1 h-100 d-flex flex-column justify-content-end pb-5">

            {{-- Breadcrumb --}}
            <nav aria-label="breadcrumb" class="mb-4" data-aos="fade-down" data-aos-duration="500">
                <ol class="breadcrumb dact-breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('frontend.home') }}"><i class="fas fa-home me-1"></i>Trang chủ</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('frontend.du-an.index') }}">Dự án</a>
                    </li>
                    <li class="breadcrumb-item active">{{ Str::limit($duAn->ten_du_an, 30) }}</li>
                </ol>
            </nav>

            <div data-aos="fade-up" data-aos-duration="600" data-aos-delay="100">
                <span class="dact-hero-badge">
                    <i class="fas fa-star me-1"></i>Dự Án Trọng Điểm
                </span>
            </div>

            <h1 class="dact-hero-title" data-aos="fade-up" data-aos-duration="600" data-aos-delay="150">
                {{ $duAn->ten_du_an }}
            </h1>

            <div class="dact-hero-meta" data-aos="fade-up" data-aos-duration="600" data-aos-delay="200">
                <span><i class="fas fa-map-marker-alt me-1 text-primary-brand"></i>{{ $duAn->dia_chi }}</span>
                @if ($duAn->trang_thai)
                    <span class="dact-hero-status">
                        <i class="fas fa-circle me-1" style="font-size:.5rem"></i>{{ $duAn->trang_thai }}
                    </span>
                @endif
            </div>

            {{-- Quick stats --}}
            <div class="dact-hero-quickstats" data-aos="fade-up" data-aos-duration="600" data-aos-delay="250">
                @if ($duAn->chu_dau_tu)
                    <div class="dact-qs-item">
                        <i class="fas fa-building"></i>
                        <span>{{ $duAn->chu_dau_tu }}</span>
                    </div>
                @endif
                @if ($duAn->quy_mo)
                    <div class="dact-qs-item">
                        <i class="fas fa-chart-area"></i>
                        <span>{{ $duAn->quy_mo }}</span>
                    </div>
                @endif
                <div class="dact-qs-item">
                    <i class="fas fa-home"></i>
                    <span>{{ $tongCan }} căn đang giao dịch</span>
                </div>
                @if ($duAn->phap_ly)
                    <div class="dact-qs-item">
                        <i class="fas fa-file-contract"></i>
                        <span>{{ $duAn->phap_ly }}</span>
                    </div>
                @endif
            </div>

        </div>
        {{-- Wave --}}
        <div class="dact-hero-wave">
            <svg viewBox="0 0 1200 80" preserveAspectRatio="none">
                <path d="M0,40 C300,80 900,0 1200,40 L1200,80 L0,80 Z"></path>
            </svg>
        </div>
    </section>

    {{-- ══ 2. MAIN CONTENT ══ --}}
    <section class="py-5 bg-alt-section">
        <div class="container">
            <div class="row g-5">

                {{-- ── CỘT TRÁI ── --}}
                <div class="col-lg-8">

                    {{-- Tổng quan --}}
                    <div class="dact-card mb-4" data-aos="fade-up" data-aos-duration="500">
                        <div class="dact-card-title">
                            <span></span>Tổng Quan Dự Án
                        </div>
                        <div class="row g-4 mt-1">
                            @php
                                $overviews = [
                                    [
                                        'icon' => 'fa-building',
                                        'label' => 'Chủ đầu tư',
                                        'val' => $duAn->chu_dau_tu ?? 'Đang cập nhật',
                                    ],
                                    [
                                        'icon' => 'fa-chart-area',
                                        'label' => 'Tổng diện tích',
                                        'val' => $duAn->quy_mo ?? 'Đang cập nhật',
                                    ],
                                    [
                                        'icon' => 'fa-file-contract',
                                        'label' => 'Pháp lý',
                                        'val' => $duAn->phap_ly ?? 'Sổ hồng lâu dài',
                                    ],
                                    [
                                        'icon' => 'fa-calendar-check',
                                        'label' => 'Tình trạng',
                                        'val' => $duAn->trang_thai ?? 'Đang mở bán',
                                    ],
                                    [
                                        'icon' => 'fa-map-marker-alt',
                                        'label' => 'Vị trí',
                                        'val' => $duAn->dia_chi ?? 'Đang cập nhật',
                                    ],
                                    [
                                        'icon' => 'fa-home',
                                        'label' => 'Số lượng căn',
                                        'val' => $tongCan . ' căn đang giao dịch',
                                    ],
                                ];
                            @endphp
                            @foreach ($overviews as $item)
                                <div class="col-sm-6">
                                    <div class="dact-overview-item">
                                        <div class="dact-ov-icon">
                                            <i class="fas {{ $item['icon'] }}"></i>
                                        </div>
                                        <div>
                                            <div class="dact-ov-label">{{ $item['label'] }}</div>
                                            <div class="dact-ov-val">{{ $item['val'] }}</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Nội dung chi tiết --}}
                    <div class="dact-card mb-4" data-aos="fade-up" data-aos-duration="500" data-aos-delay="50">
                        <div class="dact-card-title">
                            <span></span>Thông Tin Chi Tiết
                        </div>
                        <div class="dact-content-body mt-3">
                            @if ($duAn->noi_dung)
                                {!! $duAn->noi_dung !!}
                            @else
                                <p class="text-muted fst-italic">Đang cập nhật thông tin chi tiết về dự án này...</p>
                            @endif
                        </div>
                    </div>

                    {{-- Quỹ căn --}}
                    <div id="quy-can" data-aos="fade-up" data-aos-duration="500" data-aos-delay="100">

                        <div
                            class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
                            <div class="dact-card-title mb-0">
                                <span></span>Quỹ Căn Chuyển Nhượng & Cho Thuê
                            </div>

                            {{-- Tab pills --}}
                            <ul class="nav dact-tab-pills p-1" id="projectBdsTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="p-ban-tab" data-bs-toggle="tab"
                                        data-bs-target="#p-ban" type="button" role="tab">
                                        <i class="fas fa-tag me-1"></i>Cần Bán
                                        <span class="dact-tab-count">{{ $bdsBan->count() }}</span>
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="p-thue-tab" data-bs-toggle="tab" data-bs-target="#p-thue"
                                        type="button" role="tab">
                                        <i class="fas fa-key me-1"></i>Cho Thuê
                                        <span class="dact-tab-count">{{ $bdsThue->count() }}</span>
                                    </button>
                                </li>
                            </ul>
                        </div>

                        <div class="tab-content" id="projectBdsTabContent">

                            {{-- TAB BÁN --}}
                            <div class="tab-pane fade show active" id="p-ban" role="tabpanel">
                                <div class="row g-4">
                                    @forelse($bdsBan as $bds)
                                        @php $anh = is_array($bds->album_anh) && count($bds->album_anh) > 0 ? $bds->album_anh[0] : null; @endphp
                                        <div class="col-md-6">
                                            <div
                                                class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden position-relative hover-up">
                                                <div class="position-absolute top-0 start-0 m-3 z-1">
                                                    <span class="badge dact-badge-ban rounded-pill px-3 py-2 shadow-sm">
                                                        <i class="fas fa-tag me-1"></i>Đang Bán
                                                    </span>
                                                </div>
                                                <div class="position-absolute top-0 end-0 m-3 z-1">
                                                    <button
                                                        class="btn btn-light rounded-circle shadow-sm dact-heart heart-icon-{{ $bds->id }} {{ $bds->isYeuThich ?? false ? 'liked' : '' }}"
                                                        onclick="toggleYeuThich(this, {{ $bds->id }})"
                                                        title="{{ $bds->isYeuThich ?? false ? 'Bỏ yêu thích' : 'Lưu tin' }}">
                                                        <i
                                                            class="{{ $bds->isYeuThich ?? false ? 'fas' : 'far' }} fa-heart"></i>
                                                    </button>
                                                </div>
                                                <a href="{{ route('frontend.bat-dong-san.show', $bds->slug) }}"
                                                    class="dact-card-thumb overflow-hidden d-block">
                                                    <img src="{{ $anh ? asset('storage/' . $anh) : asset('images/default-bds.jpg') }}"
                                                        class="w-100 h-100 project-img" style="object-fit:cover;"
                                                        alt="{{ $bds->tieu_de }}">
                                                </a>
                                                <div class="card-body p-4 d-flex flex-column">
                                                    <div class="dact-bds-price">{{ $bds->gia_hien_thi ?? 'Thỏa thuận' }}
                                                    </div>
                                                    <h6 class="fw-bold mb-2">
                                                        <a href="{{ route('frontend.bat-dong-san.show', $bds->slug) }}"
                                                            class="card-title-link line-clamp-2">
                                                            {{ $bds->tieu_de }}
                                                        </a>
                                                    </h6>
                                                    <hr class="opacity-10 mt-auto mb-3">
                                                    <div
                                                        class="d-flex justify-content-between text-muted small fw-semibold">
                                                        <span><i class="fas fa-expand me-1"></i>{{ $bds->dien_tich }}
                                                            m²</span>
                                                        <span><i class="fas fa-bed me-1"></i>{{ $bds->so_phong_ngu }}
                                                            PN</span>
                                                        <span><i class="fas fa-bath me-1"></i>{{ $bds->so_phong_tam }}
                                                            WC</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-12">
                                            <div class="dact-empty">
                                                <i class="fas fa-home"></i>
                                                <p>Chưa có căn hộ nào đang rao bán tại dự án này.</p>
                                            </div>
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            {{-- TAB THUÊ --}}
                            <div class="tab-pane fade" id="p-thue" role="tabpanel">
                                <div class="row g-4">
                                    @forelse($bdsThue as $bds)
                                        @php $anh = is_array($bds->album_anh) && count($bds->album_anh) > 0 ? $bds->album_anh[0] : null; @endphp
                                        <div class="col-md-6">
                                            <div
                                                class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden position-relative hover-up">
                                                <div class="position-absolute top-0 start-0 m-3 z-1">
                                                    <span class="badge dact-badge-thue rounded-pill px-3 py-2 shadow-sm">
                                                        <i class="fas fa-key me-1"></i>Cho Thuê
                                                    </span>
                                                </div>
                                                <div class="position-absolute top-0 end-0 m-3 z-1">
                                                    <button
                                                        class="btn btn-light rounded-circle shadow-sm dact-heart heart-icon-{{ $bds->id }} {{ $bds->isYeuThich ?? false ? 'liked' : '' }}"
                                                        onclick="toggleYeuThich(this, {{ $bds->id }})"
                                                        title="{{ $bds->isYeuThich ?? false ? 'Bỏ yêu thích' : 'Lưu tin' }}">
                                                        <i
                                                            class="{{ $bds->isYeuThich ?? false ? 'fas' : 'far' }} fa-heart"></i>
                                                    </button>
                                                </div>
                                                <a href="{{ route('frontend.bat-dong-san.show', $bds->slug) }}"
                                                    class="dact-card-thumb overflow-hidden d-block">
                                                    <img src="{{ $anh ? asset('storage/' . $anh) : asset('images/default-bds.jpg') }}"
                                                        class="w-100 h-100 project-img" style="object-fit:cover;"
                                                        alt="{{ $bds->tieu_de }}">
                                                </a>
                                                <div class="card-body p-4 d-flex flex-column">
                                                    <div class="dact-bds-price">{{ $bds->gia_hien_thi ?? 'Thỏa thuận' }}
                                                    </div>
                                                    <h6 class="fw-bold mb-2">
                                                        <a href="{{ route('frontend.bat-dong-san.show', $bds->slug) }}"
                                                            class="card-title-link line-clamp-2">
                                                            {{ $bds->tieu_de }}
                                                        </a>
                                                    </h6>
                                                    <hr class="opacity-10 mt-auto mb-3">
                                                    <div
                                                        class="d-flex justify-content-between text-muted small fw-semibold">
                                                        <span><i class="fas fa-expand me-1"></i>{{ $bds->dien_tich }}
                                                            m²</span>
                                                        <span><i class="fas fa-bed me-1"></i>{{ $bds->so_phong_ngu }}
                                                            PN</span>
                                                        <span><i class="fas fa-bath me-1"></i>{{ $bds->so_phong_tam }}
                                                            WC</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-12">
                                            <div class="dact-empty">
                                                <i class="fas fa-key"></i>
                                                <p>Chưa có căn hộ nào đang cho thuê tại dự án này.</p>
                                            </div>
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

                {{-- ── CỘT PHẢI: SIDEBAR ── --}}
                <div class="col-lg-4">
                    <div class="position-sticky" style="top:110px;" data-aos="fade-left" data-aos-duration="600">

                        {{-- Form tư vấn --}}
                        <div class="dact-sidebar-card mb-4">
                            <div class="dact-sidebar-head">
                                <div class="dact-sidebar-head-icon">
                                    <i class="fas fa-headset"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-1">Nhận Bảng Giá & Ưu Đãi</h5>
                                    <p class="small opacity-75 mb-0">Chuyên viên liên hệ trong 5 phút</p>
                                </div>
                            </div>
                            <div class="dact-sidebar-body">
                                <form action="#" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <input type="text" class="form-control dact-input"
                                            placeholder="Họ và tên của bạn *" required>
                                    </div>
                                    <div class="mb-3">
                                        <input type="tel" class="form-control dact-input"
                                            placeholder="Số điện thoại *" required>
                                    </div>
                                    <div class="mb-4">
                                        <textarea class="form-control dact-input" rows="3" placeholder="Nội dung cần tư vấn...">Tôi quan tâm đến dự án {{ $duAn->ten_du_an }}, vui lòng gửi thông tin chi tiết.</textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary-brand w-100 py-3 fw-bold rounded-3">
                                        <i class="fas fa-paper-plane me-2"></i>Gửi Yêu Cầu Tư Vấn
                                    </button>
                                </form>

                                <div class="dact-hotline">
                                    <div class="dact-hotline-icon">
                                        <i class="fab fa-whatsapp"></i>
                                    </div>
                                    <div>
                                        <span class="dact-hotline-label">Hotline tư vấn 24/7</span>
                                        <a href="tel:0912345678" class="dact-hotline-num">0912 345 678</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Nút xem quỹ căn --}}
                        @if ($tongCan > 0)
                            <a href="#quy-can" class="btn dact-btn-scroll w-100 py-3 fw-bold rounded-3">
                                <i class="fas fa-home me-2"></i>
                                Xem {{ $tongCan }} Căn Đang Bán / Cho Thuê
                                <i class="fas fa-arrow-down ms-2"></i>
                            </a>
                        @endif

                        {{-- Box tiện ích dự án --}}
                        <div class="dact-amenity-card mt-4">
                            <div class="dact-card-title mb-3">
                                <span></span>Tiện Ích Nổi Bật
                            </div>
                            <div class="dact-amenity-grid">
                                @php
                                    $amenities = [
                                        ['icon' => 'fa-swimming-pool', 'name' => 'Bể bơi'],
                                        ['icon' => 'fa-dumbbell', 'name' => 'Gym'],
                                        ['icon' => 'fa-shield-alt', 'name' => 'An ninh 24/7'],
                                        ['icon' => 'fa-tree', 'name' => 'Công viên'],
                                        ['icon' => 'fa-shopping-cart', 'name' => 'TTTM'],
                                        ['icon' => 'fa-school', 'name' => 'Trường học'],
                                        ['icon' => 'fa-hospital', 'name' => 'Bệnh viện'],
                                        ['icon' => 'fa-parking', 'name' => 'Bãi đỗ xe'],
                                    ];
                                @endphp
                                @foreach ($amenities as $am)
                                    <div class="dact-amenity-item">
                                        <i class="fas {{ $am['icon'] }}"></i>
                                        <span>{{ $am['name'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ══ STYLES ══ --}}
    <style>
        /* ── Hero ── */
        .dact-hero {
            position: relative;
            min-height: 72vh;
            background:
                linear-gradient(to top,
                    rgba(10, 10, 18, .96) 0%,
                    rgba(10, 10, 18, .55) 50%,
                    rgba(0, 0, 0, .15) 100%),
                url('{{ $duAn->hinh_anh ? asset('storage/' . $duAn->hinh_anh) : 'https://vinhomesland.vn/wp-content/uploads/2021/04/phoi-canh-vinhomes-smart-city.jpg' }}') center / cover fixed;
            overflow: hidden;
        }

        .dact-hero-wave {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            line-height: 0;
        }

        .dact-hero-wave svg {
            width: 100%;
            height: 50px;
            fill: var(--bg-alt);
            display: block;
        }

        .dact-breadcrumb .breadcrumb-item a {
            color: rgba(255, 255, 255, .65);
            text-decoration: none;
            transition: color var(--transition);
            font-size: .82rem;
        }

        .dact-breadcrumb .breadcrumb-item a:hover {
            color: var(--primary);
        }

        .dact-breadcrumb .breadcrumb-item.active {
            color: rgba(255, 255, 255, .45);
            font-size: .82rem;
        }

        .dact-breadcrumb .breadcrumb-item+.breadcrumb-item::before {
            color: rgba(255, 255, 255, .3);
        }

        .dact-hero-badge {
            display: inline-flex;
            align-items: center;
            background: var(--primary);
            color: #fff;
            font-size: .72rem;
            font-weight: 800;
            padding: .35rem 1rem;
            border-radius: 20px;
            margin-bottom: 1rem;
            letter-spacing: .5px;
        }

        .dact-hero-title {
            color: #fff;
            font-size: clamp(1.8rem, 4vw, 3rem);
            font-weight: 900;
            line-height: 1.25;
            margin-bottom: 1rem;
            max-width: 800px;
        }

        .dact-hero-meta {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
            color: rgba(255, 255, 255, .75);
            font-size: .9rem;
            font-weight: 500;
            margin-bottom: 1.5rem;
        }

        .dact-hero-status {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            background: rgba(46, 125, 50, .25);
            color: #86efac;
            border: 1px solid rgba(134, 239, 172, .3);
            border-radius: 20px;
            padding: .2rem .8rem;
            font-size: .78rem;
            font-weight: 700;
        }

        .dact-hero-quickstats {
            display: flex;
            flex-wrap: wrap;
            gap: .8rem;
        }

        .dact-qs-item {
            display: flex;
            align-items: center;
            gap: .5rem;
            background: rgba(255, 255, 255, .1);
            border: 1px solid rgba(255, 255, 255, .15);
            color: rgba(255, 255, 255, .85);
            font-size: .78rem;
            font-weight: 600;
            padding: .4rem .9rem;
            border-radius: 8px;
            backdrop-filter: blur(4px);
        }

        .dact-qs-item i {
            color: var(--primary);
        }

        /* ── Section card title ── */
        .dact-card-title {
            display: flex;
            align-items: center;
            gap: .75rem;
            font-size: 1.1rem;
            font-weight: 800;
            color: var(--text-heading);
        }

        .dact-card-title span {
            display: inline-block;
            width: 4px;
            height: 20px;
            background: var(--primary);
            border-radius: 2px;
            flex-shrink: 0;
        }

        /* ── Card trắng chung ── */
        .dact-card {
            background: #fff;
            border-radius: 16px;
            padding: 1.8rem;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border);
        }

        /* ── Overview items ── */
        .dact-overview-item {
            display: flex;
            align-items: flex-start;
            gap: .9rem;
            padding: .9rem 1rem;
            background: var(--bg-alt);
            border-radius: 10px;
            border: 1px solid var(--border);
            transition: all var(--transition);
        }

        .dact-overview-item:hover {
            border-color: var(--primary);
            background: var(--primary-light);
        }

        .dact-ov-icon {
            width: 40px;
            height: 40px;
            background: var(--primary-light);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-size: 1rem;
            flex-shrink: 0;
        }

        .dact-overview-item:hover .dact-ov-icon {
            background: var(--primary);
            color: #fff;
        }

        .dact-ov-label {
            font-size: .68rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: var(--text-muted);
            margin-bottom: .15rem;
        }

        .dact-ov-val {
            font-size: .9rem;
            font-weight: 700;
            color: var(--text-heading);
        }

        /* ── Article content ── */
        .dact-content-body {
            font-size: 1rem;
            line-height: 1.85;
            color: var(--text-body);
            text-align: justify;
        }

        .dact-content-body p {
            margin-bottom: 1.1rem;
        }

        .dact-content-body img {
            max-width: 100% !important;
            height: auto !important;
            border-radius: 10px;
            margin: 1rem 0;
            box-shadow: var(--shadow-sm);
        }

        .dact-content-body h2,
        .dact-content-body h3 {
            font-weight: 800;
            color: var(--text-heading);
            margin-top: 1.8rem;
            margin-bottom: .8rem;
            padding-left: .8rem;
            border-left: 3px solid var(--primary);
        }

        /* ── Tab pills ── */
        .dact-tab-pills {
            background: #fff;
            border-radius: 50px;
            border: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
            list-style: none;
            margin: 0;
            display: flex;
            gap: 2px;
        }

        .dact-tab-pills .nav-link {
            display: flex;
            align-items: center;
            gap: .4rem;
            color: var(--text-muted);
            font-weight: 700;
            font-size: .82rem;
            padding: .55rem 1.2rem;
            border-radius: 50px;
            border: none;
            transition: all var(--transition);
        }

        .dact-tab-pills .nav-link.active {
            background: var(--primary);
            color: #fff;
            box-shadow: 0 4px 12px rgba(192, 102, 42, .35);
        }

        .dact-tab-count {
            background: rgba(255, 255, 255, .25);
            border-radius: 10px;
            padding: .05rem .45rem;
            font-size: .72rem;
        }

        .dact-tab-pills .nav-link:not(.active) .dact-tab-count {
            background: var(--bg-alt);
            color: var(--text-muted);
        }

        /* ── BDS card badge ── */
        .dact-badge-ban {
            background: rgba(198, 40, 40, .9) !important;
            color: #fff !important;
        }

        .dact-badge-thue {
            background: rgba(46, 125, 50, .9) !important;
            color: #fff !important;
        }

        .dact-card-thumb {
            height: 220px;
            display: block;
        }

        .dact-bds-price {
            font-size: 1.1rem;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: .4rem;
        }

        .dact-heart {
            width: 35px;
            height: 35px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
            font-size: .85rem;
            transition: all var(--transition);
        }

        .dact-heart:hover {
            color: #e53e3e;
        }

        .dact-heart.liked {
            color: #e53e3e;
            border-color: #e53e3e;
            background: #fff0f0 !important;
        }

        /* ── Empty state ── */
        .dact-empty {
            text-align: center;
            padding: 3rem 1rem;
            background: #fff;
            border-radius: 12px;
            border: 2px dashed var(--border);
            color: var(--text-muted);
        }

        .dact-empty i {
            font-size: 2rem;
            opacity: .3;
            margin-bottom: .8rem;
            display: block;
        }

        .dact-empty p {
            margin: 0;
            font-size: .9rem;
        }

        /* ── Sidebar ── */
        .dact-sidebar-card {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(27, 58, 107, .15);
            border: 1px solid var(--border);
        }

        .dact-sidebar-head {
            background: linear-gradient(135deg, var(--secondary), var(--secondary-dark));
            color: #fff;
            padding: 1.4rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .dact-sidebar-head-icon {
            width: 48px;
            height: 48px;
            background: rgba(255, 255, 255, .1);
            border: 1px solid rgba(255, 255, 255, .2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            color: var(--primary);
            flex-shrink: 0;
        }

        .dact-sidebar-body {
            background: #fff;
            padding: 1.5rem;
        }

        .dact-input {
            background: var(--bg-alt) !important;
            border: 1.5px solid var(--border) !important;
            border-radius: 8px !important;
            padding: .65rem .9rem !important;
            font-size: .9rem !important;
            transition: border-color var(--transition), box-shadow var(--transition) !important;
        }

        .dact-input:focus {
            border-color: var(--primary) !important;
            box-shadow: 0 0 0 3px var(--primary-light) !important;
            background: #fff !important;
            outline: none !important;
        }

        .dact-hotline {
            display: flex;
            align-items: center;
            gap: .9rem;
            margin-top: 1.2rem;
            padding-top: 1.2rem;
            border-top: 1px solid var(--border);
        }

        .dact-hotline-icon {
            width: 44px;
            height: 44px;
            background: #25D366;
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            flex-shrink: 0;
        }

        .dact-hotline-label {
            display: block;
            font-size: .75rem;
            color: var(--text-muted);
            font-weight: 600;
        }

        .dact-hotline-num {
            display: block;
            font-size: 1.1rem;
            font-weight: 800;
            color: var(--text-heading);
            text-decoration: none;
            transition: color var(--transition);
        }

        .dact-hotline-num:hover {
            color: var(--primary);
        }

        .dact-btn-scroll {
            background: #fff;
            border: 2px solid var(--secondary) !important;
            color: var(--secondary) !important;
            font-size: .9rem;
            transition: all var(--transition);
        }

        .dact-btn-scroll:hover {
            background: var(--secondary) !important;
            color: #fff !important;
        }

        /* ── Amenity ── */
        .dact-amenity-card {
            background: #fff;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border);
        }

        .dact-amenity-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: .6rem;
        }

        .dact-amenity-item {
            display: flex;
            align-items: center;
            gap: .6rem;
            padding: .6rem .8rem;
            background: var(--bg-alt);
            border-radius: 8px;
            font-size: .78rem;
            font-weight: 600;
            color: var(--text-body);
            border: 1px solid var(--border);
            transition: all var(--transition);
        }

        .dact-amenity-item:hover {
            border-color: var(--primary);
            color: var(--primary);
            background: var(--primary-light);
        }

        .dact-amenity-item i {
            color: var(--primary);
            font-size: .85rem;
            flex-shrink: 0;
        }

        /* ── Responsive ── */
        @media (max-width: 767px) {
            .dact-hero-quickstats {
                gap: .5rem;
            }

            .dact-qs-item {
                font-size: .72rem;
                padding: .3rem .7rem;
            }

            .dact-card {
                padding: 1.2rem;
            }
        }
    </style>

@endsection
