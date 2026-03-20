@extends('frontend.layouts.master')

@section('title', $bds->tieu_de . ' - Thành Công Land')

@section('content')

    {{-- Xử lý mảng ảnh Album --}}
    @php
        $album = [];
        if (!empty($bds->album_anh)) {
            $album = is_string($bds->album_anh) ? json_decode($bds->album_anh, true) : $bds->album_anh;
        }
        if (!is_array($album)) {
            $album = [];
        }

        $anhChinh = count($album) > 0 ? asset('storage/' . $album[0]) : asset('images/default-bds.jpg');
        $anh2 = count($album) > 1 ? asset('storage/' . $album[1]) : $anhChinh;
        $anh3 = count($album) > 2 ? asset('storage/' . $album[2]) : $anhChinh;
    @endphp

    <section class="bg-light pt-4 pb-5" style="margin-top: -30px; min-height: 80vh;">
        <div class="container">

            {{-- 1. BREADCRUMB --}}
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb mb-0 fw-semibold">
                    <li class="breadcrumb-item"><a href="{{ route('frontend.home') }}"
                            class="text-decoration-none text-muted"><i class="fas fa-home"></i> Trang chủ</a></li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('frontend.bat-dong-san.index', ['nhu_cau' => $bds->nhu_cau]) }}"
                            class="text-decoration-none text-muted">
                            {{ $bds->nhu_cau == 'ban' ? 'Mua Bán' : 'Cho Thuê' }}
                        </a>
                    </li>
                    @if ($bds->duAn)
                        <li class="breadcrumb-item"><a href="{{ route('frontend.du-an.show', $bds->duAn->slug) }}"
                                class="text-decoration-none text-muted">{{ $bds->duAn->ten_du_an }}</a></li>
                    @endif
                    <li class="breadcrumb-item active text-truncate" aria-current="page"
                        style="max-width: 300px; color: #FF8C42;">{{ $bds->tieu_de }}</li>
                </ol>
            </nav>

            {{-- 2. GALLERY ẢNH (Phong cách Airbnb) --}}
            <div class="row g-2 mb-5 rounded-4 overflow-hidden shadow-sm" style="height: 50vh; min-height: 400px;">
                <div class="col-md-8 h-100">
                    <a href="{{ $anhChinh }}" data-lightbox="property-gallery"
                        class="d-block h-100 w-100 position-relative hover-zoom">
                        <img src="{{ $anhChinh }}" class="w-100 h-100 object-fit-cover" alt="{{ $bds->tieu_de }}">
                        <div class="position-absolute top-0 start-0 m-3">
                            <span
                                class="badge {{ $bds->nhu_cau == 'ban' ? 'bg-danger' : 'bg-success' }} px-3 py-2 fs-6 shadow">
                                {{ $bds->nhu_cau == 'ban' ? 'Đang Bán' : 'Cho Thuê' }}
                            </span>
                        </div>
                    </a>
                </div>
                <div class="col-md-4 h-100 d-none d-md-flex flex-column gap-2">
                    <a href="{{ $anh2 }}" data-lightbox="property-gallery" class="d-block h-50 w-100 hover-zoom">
                        <img src="{{ $anh2 }}" class="w-100 h-100 object-fit-cover" alt="Ảnh 2">
                    </a>
                    <a href="{{ $anh3 }}" data-lightbox="property-gallery"
                        class="d-block h-50 w-100 position-relative hover-zoom">
                        <img src="{{ $anh3 }}" class="w-100 h-100 object-fit-cover" alt="Ảnh 3">
                        @if (count($album) > 3)
                            <div class="position-absolute inset-0 d-flex align-items-center justify-content-center w-100 h-100"
                                style="background: rgba(0,0,0,0.5); top:0; left:0;">
                                <span class="text-white fw-bold fs-5">+{{ count($album) - 3 }} Ảnh</span>
                            </div>
                        @endif
                    </a>
                </div>
                {{-- Lightbox cho các ảnh còn lại (ẩn đi) --}}
                @if (count($album) > 3)
                    @for ($i = 3; $i < count($album); $i++)
                        <a href="{{ asset('storage/' . $album[$i]) }}" data-lightbox="property-gallery" class="d-none"></a>
                    @endfor
                @endif
            </div>

            <div class="row g-5">
                {{-- 3. CỘT TRÁI: THÔNG TIN CHI TIẾT --}}
                <div class="col-lg-8">

                    {{-- Header Bất Động Sản --}}
                    <div class="mb-4 pb-4 border-bottom">
                        <h1 class="fw-bold serif-font mb-3" style="color: #0F172A; font-size: 2rem; line-height: 1.4;">
                            {{ $bds->tieu_de }}</h1>
                        <p class="text-muted fs-6 mb-3"><i class="fas fa-map-marker-alt me-2"
                                style="color: #FF8C42;"></i>{{ $bds->dia_chi }}</p>

                        <div class="d-flex flex-wrap justify-content-between align-items-center">
                            <h2 class="fw-bold mb-0" style="color: #FF8C42;">{{ $bds->gia_hien_thi ?? 'Giá thỏa thuận' }}
                            </h2>
                            <div class="d-flex gap-3 text-muted fw-semibold">
                                <span title="Lượt xem"><i class="far fa-eye me-1"></i> {{ $bds->luot_xem ?? 0 }} lượt
                                    xem</span>
                                <span><i class="far fa-clock me-1"></i> {{ $bds->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Thông số nổi bật (Specs Grid) --}}
                    <div class="card border-0 bg-white shadow-sm rounded-4 p-4 mb-4">
                        <h5 class="fw-bold mb-4" style="color: #0F172A;">Thông Số Cơ Bản</h5>
                        <div class="row g-4">
                            <div class="col-6 col-md-3">
                                <div class="d-flex flex-column align-items-center text-center p-3 rounded-3"
                                    style="background-color: #f8fafc; border: 1px solid #e2e8f0;">
                                    <i class="fas fa-expand fs-4 mb-2" style="color: #FF8C42;"></i>
                                    <span class="text-muted small mb-1">Diện tích</span>
                                    <strong class="text-dark">{{ $bds->dien_tich }} m²</strong>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="d-flex flex-column align-items-center text-center p-3 rounded-3"
                                    style="background-color: #f8fafc; border: 1px solid #e2e8f0;">
                                    <i class="fas fa-bed fs-4 mb-2" style="color: #FF8C42;"></i>
                                    <span class="text-muted small mb-1">Phòng ngủ</span>
                                    <strong class="text-dark">{{ $bds->so_phong_ngu }} PN</strong>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="d-flex flex-column align-items-center text-center p-3 rounded-3"
                                    style="background-color: #f8fafc; border: 1px solid #e2e8f0;">
                                    <i class="fas fa-bath fs-4 mb-2" style="color: #FF8C42;"></i>
                                    <span class="text-muted small mb-1">Phòng tắm</span>
                                    <strong class="text-dark">{{ $bds->so_phong_tam }} WC</strong>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="d-flex flex-column align-items-center text-center p-3 rounded-3"
                                    style="background-color: #f8fafc; border: 1px solid #e2e8f0;">
                                    <i class="fas fa-compass fs-4 mb-2" style="color: #FF8C42;"></i>
                                    <span class="text-muted small mb-1">Hướng nhà</span>
                                    <strong class="text-dark">{{ $bds->huong ?? 'Đang cập nhật' }}</strong>
                                </div>
                            </div>
                        </div>

                        {{-- Thông tin thêm --}}
                        <div class="row mt-4 pt-4 border-top">
                            <div class="col-md-6 mb-2">
                                <span class="text-muted d-inline-block" style="width: 100px;">Pháp lý:</span>
                                <strong class="text-dark">{{ $bds->phap_ly ?? 'Sổ đỏ / Sổ hồng' }}</strong>
                            </div>
                            <div class="col-md-6 mb-2">
                                <span class="text-muted d-inline-block" style="width: 100px;">Nội thất:</span>
                                <strong class="text-dark">{{ $bds->noi_that ?? 'Cơ bản' }}</strong>
                            </div>
                            @if ($bds->duAn)
                                <div class="col-md-12 mt-2">
                                    <span class="text-muted d-inline-block" style="width: 100px;">Thuộc dự án:</span>
                                    <a href="{{ route('frontend.du-an.show', $bds->duAn->slug) }}"
                                        class="fw-bold text-decoration-none" style="color: #FF8C42;">
                                        {{ $bds->duAn->ten_du_an }} <i class="fas fa-external-link-alt small ms-1"></i>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Thông tin mô tả --}}
                    <div class="card border-0 bg-white shadow-sm rounded-4 p-4 mb-4">
                        <h5 class="fw-bold mb-4" style="color: #0F172A;">Thông Tin Mô Tả</h5>
                        <div class="article-content text-justify" style="color: #475569; line-height: 1.8;">
                            @if ($bds->mo_ta)
                                {!! $bds->mo_ta !!}
                            @else
                                <p>Đang cập nhật mô tả cho bất động sản này...</p>
                            @endif
                        </div>
                    </div>

                </div>

                {{-- 4. CỘT PHẢI: STICKY SIDEBAR LIÊN HỆ --}}
                <div class="col-lg-4">
                    <div class="position-sticky" style="top: 100px;">

                        {{-- Card Tư Vấn --}}
                        <div class="card border-0 shadow-lg rounded-4 overflow-hidden mb-4">
                            <div class="p-4 text-center text-white"
                                style="background: linear-gradient(135deg, #0F172A, #1A2948);">
                                <div class="avatar-circle mx-auto mb-3 border border-2 border-white d-flex align-items-center justify-content-center shadow"
                                    style="width: 80px; height: 80px; border-radius: 50%; background: #FF8C42;">
                                    <i class="fas fa-user-tie fs-1"></i>
                                </div>
                                <h5 class="fw-bold mb-1">Chuyên Viên Tư Vấn</h5>
                                <p class="small opacity-75 mb-0">Thành Công Land</p>
                            </div>
                            <div class="card-body p-4 bg-white text-center">
                                <a href="tel:0912345678"
                                    class="btn btn-outline-dark w-100 rounded-pill fw-bold py-3 mb-3 d-flex align-items-center justify-content-center">
                                    <i class="fas fa-phone-alt me-2" style="color: #FF8C42;"></i> 0912 345 678
                                </a>
                                <a href="https://zalo.me/0912345678" target="_blank"
                                    class="btn w-100 rounded-pill fw-bold py-3 text-white shadow-sm mb-4 d-flex align-items-center justify-content-center"
                                    style="background-color: #0068FF;">
                                    <i class="fas fa-comment-dots me-2"></i> Chat qua Zalo
                                </a>

                                <hr class="text-muted opacity-25 mb-4">

                                <h6 class="fw-bold text-start mb-3" style="color: #0F172A;">Yêu cầu gọi lại</h6>
                                <form action="#" method="POST">
                                    <div class="mb-3">
                                        <input type="text" class="form-control rounded-3 bg-light border-0"
                                            placeholder="Số điện thoại của bạn *" required style="padding: 12px 15px;">
                                    </div>
                                    <div class="mb-3">
                                        <textarea class="form-control rounded-3 bg-light border-0" rows="2" placeholder="Nội dung cần tư vấn..."
                                            style="padding: 12px 15px;">Tôi quan tâm đến BĐS này, vui lòng liên hệ lại.</textarea>
                                    </div>
                                    <button type="button"
                                        class="btn w-100 rounded-pill fw-bold py-2 text-white shadow-sm hover-up"
                                        style="background-color: #FF8C42;">
                                        Gửi Yêu Cầu <i class="fas fa-paper-plane ms-2"></i>
                                    </button>
                                </form>
                            </div>
                        </div>

                        {{-- Nút Lưu Tin --}}
                        <button
                            class="btn btn-light border w-100 rounded-pill fw-bold py-3 text-muted hover-up shadow-sm d-flex align-items-center justify-content-center"
                            onclick="toggleYeuThich(this, {{ $bds->id }})">
                            <i class="far fa-heart fs-5 me-2 text-danger"></i> Lưu Tin Bất Động Sản Này
                        </button>
                        <button
                            class="btn btn-outline-dark w-100 rounded-pill fw-bold py-3 mt-3 hover-up shadow-sm d-flex align-items-center justify-content-center"
                            onclick="addSoSanh({{ $bds->id }}, '{{ addslashes($bds->tieu_de) }}')">
                            <i class="fas fa-balance-scale fs-5 me-2" style="color: #FF8C42;"></i> Thêm Vào So Sánh
                        </button>
                    </div>

                </div>
                @include('frontend.partials.form-lien-he-bds', ['bds' => $bds])

            </div>

            {{-- 5. BẤT ĐỘNG SẢN LIÊN QUAN --}}
            @if (isset($bdsLienQuan) && $bdsLienQuan->count() > 0)
                <div class="mt-5 pt-5 border-top">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="fw-bold serif-font mb-0"
                            style="color: #0F172A; border-left: 4px solid #FF8C42; padding-left: 10px;">Bất Động Sản Tương
                            Tự</h3>
                        <a href="{{ route('frontend.bat-dong-san.index', ['nhu_cau' => $bds->nhu_cau]) }}"
                            class="text-decoration-none fw-bold" style="color: #FF8C42;">Xem tất cả <i
                                class="fas fa-angle-right ms-1"></i></a>
                    </div>

                    <div class="row g-4">
                        @foreach ($bdsLienQuan as $item)
                            <div class="col-md-6 col-lg-3">
                                <div
                                    class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden position-relative hover-up">
                                    <a href="{{ route('frontend.bat-dong-san.show', $item->slug) }}"
                                        class="overflow-hidden d-block bg-light" style="height: 180px;">
                                        @php
                                            $anhLQ = [];
                                            if (!empty($item->album_anh)) {
                                                $anhLQ = is_string($item->album_anh)
                                                    ? json_decode($item->album_anh, true)
                                                    : $item->album_anh;
                                            }
                                            $hinhAnhLQ =
                                                is_array($anhLQ) && count($anhLQ) > 0
                                                    ? asset('storage/' . $anhLQ[0])
                                                    : asset('images/default-bds.jpg');
                                        @endphp
                                        <img src="{{ $hinhAnhLQ }}" class="card-img-top w-100 h-100 bds-img"
                                            style="object-fit: cover; transition: 0.5s;" alt="{{ $item->tieu_de }}">
                                    </a>
                                    <div class="card-body p-3 d-flex flex-column">
                                        <h5 class="fw-bold mb-2" style="color: #FF8C42; font-size: 1.1rem;">
                                            {{ $item->gia_hien_thi ?? 'Thỏa thuận' }}</h5>
                                        <h6 class="fw-bold mb-2 flex-grow-1">
                                            <a href="{{ route('frontend.bat-dong-san.show', $item->slug) }}"
                                                class="text-decoration-none text-dark line-clamp-2"
                                                title="{{ $item->tieu_de }}">{{ $item->tieu_de }}</a>
                                        </h6>
                                        <div
                                            class="d-flex justify-content-between text-muted small fw-semibold pt-2 border-top mt-2">
                                            <span><i class="fas fa-expand me-1" style="color:#FF8C42;"></i>
                                                {{ $item->dien_tich }}m²</span>
                                            <span><i class="fas fa-bed me-1" style="color:#FF8C42;"></i>
                                                {{ $item->so_phong_ngu }} PN</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </section>

    {{-- Gói CSS thêm cho trang này --}}
    <style>
        .hover-zoom {
            overflow: hidden;
        }

        .hover-zoom img {
            transition: transform 0.5s ease;
        }

        .hover-zoom:hover img {
            transform: scale(1.05);
        }

        .hover-up {
            transition: all 0.3s ease;
        }

        .hover-up:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1) !important;
        }

        .hover-up:hover .bds-img {
            transform: scale(1.08);
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .article-content img {
            max-width: 100% !important;
            height: auto !important;
            border-radius: 8px;
            margin: 15px 0;
        }
    </style>

    {{-- Yêu cầu thư viện Lightbox (Tùy chọn) để xem ảnh to --}}
    @push('styles')
        <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css" rel="stylesheet" />
    @endpush

    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox-plus-jquery.min.js"></script>
        <script>
            lightbox.option({
                'resizeDuration': 200,
                'wrapAround': true,
                'albumLabel': "Ảnh %1 / %2"
            })
        </script>
    @endpush

@endsection
