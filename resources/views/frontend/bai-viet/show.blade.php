@extends('frontend.layouts.master')

@section('title', $baiViet->tieu_de . ' - Thành Công Land')

@section('content')
    <section class="py-5 bg-light" style="min-height: 80vh;">
        <div class="container py-3">

            {{-- Breadcrumb --}}
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('frontend.home') }}"
                            class="text-decoration-none text-muted hover-orange"><i class="fas fa-home"></i> Trang chủ</a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ route('frontend.tin-tuc.index') }}"
                            class="text-decoration-none text-muted hover-orange">Tin tức</a></li>
                    <li class="breadcrumb-item active fw-bold" aria-current="page" style="color: #FF8C42;">Chi tiết</li>
                </ol>
            </nav>

            <div class="row g-5">

                {{-- NỘI DUNG BÀI VIẾT --}}
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">

                        {{-- Tiêu đề & Thông tin phụ --}}
                        <div class="border-bottom pb-4 mb-4">
                            <div class="badge bg-light text-dark mb-3 px-3 py-2 border fw-bold"
                                style="color: #FF8C42 !important; border-color: #FF8C42 !important;">
                                {{ $baiViet->loai_bai_viet ?? 'Tin Tức' }}
                            </div>
                            <h1 class="fw-bold serif-font mb-3" style="color: #0F172A; line-height: 1.4;">
                                {{ $baiViet->tieu_de }}</h1>
                            <div class="d-flex align-items-center text-muted small fw-semibold">
                                <span class="me-4"><i class="far fa-calendar-alt me-1 text-primary"></i> Đăng ngày:
                                    {{ \Carbon\Carbon::parse($baiViet->thoi_diem_dang ?? $baiViet->created_at)->format('d/m/Y H:i') }}</span>
                                <span><i class="far fa-eye me-1 text-secondary"></i> Lượt xem:
                                    {{ $baiViet->luot_xem ?? 0 }}</span>
                            </div>
                        </div>

                        {{-- Mô tả ngắn (Sapo) --}}
                        @if ($baiViet->mo_ta_ngan)
                            <p class="fw-bold fs-6 mb-4" style="color: #334155; line-height: 1.8;">
                                {!! $baiViet->mo_ta_ngan !!}
                            </p>
                        @endif

                        {{-- Nội dung chính (SEO Content) --}}
                        <div class="article-content text-justify" style="color: #475569;">
                            {!! $baiViet->noi_dung !!}
                        </div>

                        {{-- Tác giả & Chia sẻ --}}
                        <div class="d-flex justify-content-between align-items-center mt-5 pt-4 border-top">
                            <div class="fw-bold text-dark"><i class="fas fa-pen-nib me-2 text-muted"></i>Ban Biên Tập Thành
                                Công Land</div>
                            <div class="d-flex gap-2">
                                <span class="text-muted fw-semibold me-2 mt-1">Chia sẻ:</span>
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                                    target="_blank" class="btn btn-sm btn-light rounded-circle text-primary"
                                    style="width: 32px; height: 32px;"><i class="fab fa-facebook-f"></i></a>
                                <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}"
                                    target="_blank" class="btn btn-sm btn-light rounded-circle text-info"
                                    style="width: 32px; height: 32px;"><i class="fab fa-twitter"></i></a>
                            </div>
                        </div>
                    </div>

                    {{-- TIN LIÊN QUAN --}}
                    @if ($tinLienQuan->count() > 0)
                        <div class="mt-5">
                            <h4 class="fw-bold serif-font mb-4"
                                style="color: #0F172A; border-left: 4px solid #FF8C42; padding-left: 10px;">Bài Viết Cùng
                                Chuyên Mục</h4>
                            <div class="row g-4">
                                @foreach ($tinLienQuan as $bv)
                                    <div class="col-md-4">
                                        <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden hover-up">
                                            <a href="{{ route('frontend.tin-tuc.show', $bv->slug) }}"
                                                class="overflow-hidden d-block" style="height: 160px;">
                                                <img src="{{ $bv->hinh_anh ? asset('storage/' . $bv->hinh_anh) : asset('images/default-news.jpg') }}"
                                                    class="card-img-top w-100 h-100 project-img"
                                                    style="object-fit: cover; transition: 0.5s;">
                                            </a>
                                            <div class="card-body p-3">
                                                <h6 class="fw-bold mb-2">
                                                    <a href="{{ route('frontend.tin-tuc.show', $bv->slug) }}"
                                                        class="text-decoration-none text-dark line-clamp-2 hover-orange">{{ $bv->tieu_de }}</a>
                                                </h6>
                                                <small class="text-muted"><i class="far fa-calendar-alt me-1"></i>
                                                    {{ \Carbon\Carbon::parse($bv->thoi_diem_dang ?? $bv->created_at)->format('d/m/Y') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                {{-- SIDEBAR --}}
                <div class="col-lg-4">
                    <div class="position-sticky" style="top: 100px;">
                        {{-- Form Ký gửi nhanh --}}
                        <div class="card border-0 shadow-lg rounded-4 overflow-hidden mb-4">
                            <div class="p-4 text-center text-white"
                                style="background: linear-gradient(135deg, #0F172A, #1A2948);">
                                <h5 class="fw-bold mb-1">Tư Vấn Miễn Phí</h5>
                                <p class="small opacity-75 mb-0">Hỗ trợ pháp lý, định giá nhà đất</p>
                            </div>
                            <div class="card-body p-4 bg-white">
                                <form action="#" method="POST">
                                    <div class="mb-3"><input type="text" class="form-control bg-light border-0"
                                            placeholder="Số điện thoại của bạn *" required></div>
                                    <button type="button" class="btn w-100 fw-bold py-2 text-white shadow-sm"
                                        style="background-color: #FF8C42; border-radius: 8px;">Yêu Cầu Gọi Lại</button>
                                </form>
                            </div>
                        </div>

                        {{-- Tin nổi bật Sidebar --}}
                        <div class="card border-0 shadow-sm rounded-4">
                            <div class="card-body p-4">
                                <h5 class="fw-bold serif-font mb-4">Tin Đọc Nhiều Nhất</h5>
                                @foreach ($tinNoiBat as $tin)
                                    <div class="d-flex mb-3 align-items-center pb-3 border-bottom border-light">
                                        <div class="fs-4 fw-bold me-3" style="color: #e2e8f0;">0{{ $loop->iteration }}
                                        </div>
                                        <div>
                                            <h6 class="mb-1 fw-bold" style="font-size: 0.9rem;">
                                                <a href="{{ route('frontend.tin-tuc.show', $tin->slug) }}"
                                                    class="text-dark text-decoration-none line-clamp-2 hover-orange">{{ $tin->tieu_de }}</a>
                                            </h6>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <style>
        .hover-up {
            transition: all 0.3s ease;
        }

        .hover-up:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
        }

        .hover-up:hover .project-img {
            transform: scale(1.08);
        }

        .hover-orange {
            transition: 0.2s;
        }

        .hover-orange:hover {
            color: #FF8C42 !important;
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* ĐỊNH DẠNG NỘI DUNG BÀI VIẾT CHUẨN BÁO CHÍ */
        .article-content {
            font-size: 1.1rem;
            line-height: 1.8;
        }

        .article-content p {
            margin-bottom: 1.2rem;
        }

        .article-content img {
            max-width: 100% !important;
            height: auto !important;
            border-radius: 8px;
            margin: 1.5rem auto;
            display: block;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .article-content h2,
        .article-content h3 {
            color: #0F172A;
            font-weight: 700;
            margin-top: 2rem;
            margin-bottom: 1rem;
            font-family: serif;
        }

        .article-content a {
            color: #FF8C42;
            text-decoration: none;
            font-weight: 600;
        }

        .article-content a:hover {
            text-decoration: underline;
        }

        .article-content blockquote {
            border-left: 4px solid #FF8C42;
            padding-left: 1rem;
            font-style: italic;
            color: #64748B;
            background: #f8fafc;
            padding: 1rem;
            border-radius: 0 8px 8px 0;
        }
    </style>
@endsection
