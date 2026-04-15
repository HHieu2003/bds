@extends('frontend.layouts.master')

@section('title', $baiViet->tieu_de . ' - Thành Công Land')

@section('content')

    {{-- ── READING PROGRESS BAR ── --}}
    <div id="readingProgress"></div>

    {{-- ── HERO BANNER BÀI VIẾT ── --}}
    <section class="bvct-hero">
        <div class="bvct-hero-overlay"></div>
        <div class="container position-relative z-1">

            {{-- Breadcrumb --}}
            <nav aria-label="breadcrumb" class="mb-4" data-aos="fade-down" data-aos-duration="500">
                <ol class="breadcrumb mb-0 bvct-breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('frontend.home') }}">
                            <i class="fas fa-home me-1"></i>Trang chủ
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('frontend.tin-tuc.index') }}">Tin tức</a>
                    </li>
                    <li class="breadcrumb-item active">Chi tiết</li>
                </ol>
            </nav>

            {{-- Badge loại --}}
            <div data-aos="fade-up" data-aos-duration="500" data-aos-delay="100">
                <span class="bvct-hero-badge">
                    <i class="fas fa-tag me-1"></i>{{ $baiViet->loai_bai_viet ?? 'Tin Tức' }}
                </span>
            </div>

            {{-- Tiêu đề --}}
            <h1 class="bvct-hero-title" data-aos="fade-up" data-aos-duration="600" data-aos-delay="150">
                {{ $baiViet->tieu_de }}
            </h1>

            {{-- Meta --}}
            <div class="bvct-hero-meta" data-aos="fade-up" data-aos-duration="600" data-aos-delay="200">
                <span>
                    <i class="far fa-calendar-alt me-1"></i>
                    {{ \Carbon\Carbon::parse($baiViet->thoi_diem_dang ?? $baiViet->created_at)->format('d/m/Y H:i') }}
                </span>
                <span class="bvct-meta-sep">•</span>
                <span>
                    <i class="far fa-eye me-1"></i>{{ $baiViet->luot_xem ?? 0 }} lượt xem
                </span>
                <span class="bvct-meta-sep">•</span>
                <span>
                    <i class="fas fa-pen-nib me-1"></i>Ban Biên Tập Thành Công Land
                </span>
            </div>

        </div>
    </section>

    {{-- ── MAIN CONTENT ── --}}
    <section class="py-5 bg-alt-section">
        <div class="container">
            <div class="row g-5">

                {{-- ── CỘT TRÁI: NỘI DUNG ── --}}
                <div class="col-lg-8">

                    {{-- Card nội dung chính --}}
                    <article class="bvct-card" data-aos="fade-up" data-aos-duration="600">

                        {{-- Sapo --}}
                        @if ($baiViet->mo_ta_ngan)
                            <div class="bvct-sapo">
                                {!! $baiViet->mo_ta_ngan !!}
                            </div>
                        @endif

                        {{-- Nội dung bài viết --}}
                        <div class="article-content">
                            {!! $baiViet->noi_dung !!}
                        </div>

                        {{-- Footer bài viết --}}
                        <div class="bvct-footer">
                            <div class="bvct-author">
                                <div class="bvct-author-avatar">
                                    <i class="fas fa-pen-nib"></i>
                                </div>
                                <div>
                                    <div class="bvct-author-name">Ban Biên Tập Thành Công Land</div>
                                    <div class="bvct-author-role">Biên tập viên</div>
                                </div>
                            </div>
                            <div class="bvct-share">
                                <span class="bvct-share-label">Chia sẻ:</span>
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                                    target="_blank" class="bvct-share-btn bvct-share-fb" title="Chia sẻ Facebook">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}"
                                    target="_blank" class="bvct-share-btn bvct-share-tw" title="Chia sẻ Twitter">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <button class="bvct-share-btn bvct-share-copy" onclick="copyLink()" title="Sao chép link">
                                    <i class="fas fa-link" id="copyIcon"></i>
                                </button>
                            </div>
                        </div>

                    </article>

                    {{-- ── TIN LIÊN QUAN ── --}}
                    @if ($tinLienQuan->count() > 0)
                        <div class="mt-5" data-aos="fade-up" data-aos-duration="600" data-aos-delay="100">
                            <h4 class="bvct-related-title">
                                <span></span>Bài Viết Cùng Chuyên Mục
                            </h4>
                            <div class="row g-4">
                                @foreach ($tinLienQuan as $bv)
                                    <div class="col-md-4" data-aos="fade-up" data-aos-duration="500"
                                        data-aos-delay="{{ $loop->iteration * 80 }}">
                                        <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden hover-up">
                                            <a href="{{ route('frontend.tin-tuc.show', $bv->slug) }}"
                                                class="bvct-related-thumb overflow-hidden d-block">
                                                <img src="{{ $bv->hinh_anh ? asset('storage/' . $bv->hinh_anh) : asset('images/default-news.jpg') }}"
                                                    class="w-100 h-100 project-img" style="object-fit:cover;"
                                                    alt="{{ $bv->tieu_de }}">
                                            </a>
                                            <div class="card-body p-3">
                                                <h6 class="fw-bold mb-2">
                                                    <a href="{{ route('frontend.tin-tuc.show', $bv->slug) }}"
                                                        class="card-title-link line-clamp-2">
                                                        {{ $bv->tieu_de }}
                                                    </a>
                                                </h6>
                                                <small class="text-muted">
                                                    <i class="far fa-calendar-alt me-1"></i>
                                                    {{ \Carbon\Carbon::parse($bv->thoi_diem_dang ?? $bv->created_at)->format('d/m/Y') }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>

                {{-- ── SIDEBAR ── --}}
                <div class="col-lg-4">
                    <div class="position-sticky" style="top: 100px;">


                        {{-- Tin đọc nhiều --}}
                        <div class="card border-0 shadow-sm rounded-4" data-aos="fade-left" data-aos-duration="600"
                            data-aos-delay="200">
                            <div class="card-body p-4">
                                <h5 class="section-title fw-bold mb-4">
                                    <i class="fas fa-fire me-2 text-primary-brand"></i>Tin Đọc Nhiều Nhất
                                </h5>
                                @foreach ($tinNoiBat as $tin)
                                    <div class="bvct-hot-item {{ !$loop->last ? 'border-bottom mb-3 pb-3' : '' }}">
                                        <div class="bvct-hot-num">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                                        </div>
                                        <div>
                                            <h6 class="mb-1 fw-bold" style="font-size:.875rem">
                                                <a href="{{ route('frontend.tin-tuc.show', $tin->slug) }}"
                                                    class="text-dark text-decoration-none line-clamp-2 bv-hover-link">
                                                    {{ $tin->tieu_de }}
                                                </a>
                                            </h6>
                                            <small class="text-muted">
                                                <i class="far fa-calendar-alt me-1"></i>
                                                {{ \Carbon\Carbon::parse($tin->thoi_diem_dang ?? $tin->created_at)->format('d/m/Y') }}
                                            </small>
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

    {{-- ── STYLES ── --}}
    <style>
        /* ── Reading Progress Bar ── */
        #readingProgress {
            position: fixed;
            top: 0;
            left: 0;
            height: 3px;
            width: 0%;
            background: linear-gradient(90deg, #d9834a, var(--primary));
            z-index: 9999;
            transition: width .1s linear;
            border-radius: 0 2px 2px 0;
        }

        /* ── Hero ── */
        .bvct-hero {
            position: relative;
            padding: 4rem 0 3rem;
            background:
                linear-gradient(rgb(153 145 138 / 69%), rgb(93 90 87 / 73%)),
                url('{{ $baiViet->hinh_anh ? asset('storage/' . $baiViet->hinh_anh) : asset('images/default-news.jpg') }}') center / cover fixed;
            overflow: hidden;
        }

        .bvct-hero::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 60px;
            background: var(--bg-alt);
            clip-path: ellipse(55% 100% at 50% 100%);
        }

        .bvct-breadcrumb .breadcrumb-item a {
            color: rgba(255, 255, 255, .75);
            text-decoration: none;
            transition: color var(--transition);
        }

        .bvct-breadcrumb .breadcrumb-item a:hover {
            color: var(--primary);
        }

        .bvct-breadcrumb .breadcrumb-item.active {
            color: var(--primary);
            font-weight: 600;
        }

        .bvct-breadcrumb .breadcrumb-item+.breadcrumb-item::before {
            color: rgba(255, 255, 255, .4);
        }

        .bvct-hero-badge {
            display: inline-flex;
            align-items: center;
            background: var(--primary);
            color: #fff;
            font-size: .75rem;
            font-weight: 700;
            padding: .3rem .9rem;
            border-radius: 20px;
            margin-bottom: 1rem;
            letter-spacing: .3px;
        }

        .bvct-hero-title {
            color: #fff;
            font-size: clamp(1.6rem, 3vw, 2.4rem);
            font-weight: 800;
            line-height: 1.35;
            max-width: 780px;
            margin-bottom: 1.2rem;
        }

        .bvct-hero-meta {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: .5rem;
            color: rgba(255, 255, 255, .7);
            font-size: .85rem;
        }

        .bvct-meta-sep {
            color: rgba(255, 255, 255, .3);
        }

        /* ── Card nội dung ── */
        .bvct-card {
            background: #fff;
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border);
            animation: fadeInUp .5s ease both;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ── Sapo ── */
        .bvct-sapo {
            font-size: 1.05rem;
            font-weight: 600;
            color: var(--text-body);
            line-height: 1.8;
            padding: 1.2rem 1.5rem;
            border-left: 4px solid var(--primary);
            background: var(--primary-light);
            border-radius: 0 12px 12px 0;
            margin-bottom: 2rem;
        }

        /* ── Nội dung bài viết ── */
        .article-content {
            font-size: 1.05rem;
            line-height: 1.9;
            color: var(--text-body);
        }

        .article-content p {
            margin-bottom: 1.3rem;
        }

        .article-content img {
            max-width: 100% !important;
            height: auto !important;
            border-radius: 12px;
            margin: 1.5rem auto;
            display: block;
            box-shadow: var(--shadow-sm);
        }

        .article-content h2,
        .article-content h3 {
            color: var(--text-heading);
            font-weight: 800;
            margin-top: 2.2rem;
            margin-bottom: 1rem;
            padding-left: .8rem;
            border-left: 4px solid var(--primary);
        }

        .article-content a {
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
            border-bottom: 1px dashed var(--primary);
            transition: opacity var(--transition);
        }

        .article-content a:hover {
            opacity: .75;
        }

        .article-content blockquote {
            border-left: 4px solid var(--primary);
            padding: 1rem 1.2rem;
            font-style: italic;
            color: var(--text-muted);
            background: var(--bg-alt);
            border-radius: 0 12px 12px 0;
            margin: 1.5rem 0;
        }

        .article-content ul,
        .article-content ol {
            padding-left: 1.5rem;
            margin-bottom: 1.2rem;
        }

        .article-content li {
            margin-bottom: .5rem;
        }

        .article-content table {
            width: 100%;
            border-collapse: collapse;
            margin: 1.5rem 0;
            font-size: .95rem;
        }

        .article-content th {
            background: var(--secondary);
            color: #fff;
            padding: .7rem 1rem;
            text-align: left;
        }

        .article-content td {
            padding: .6rem 1rem;
            border-bottom: 1px solid var(--border);
        }

        .article-content tr:nth-child(even) td {
            background: var(--bg-alt);
        }

        /* ── Footer bài viết ── */
        .bvct-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
            margin-top: 2.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border);
        }

        .bvct-author {
            display: flex;
            align-items: center;
            gap: .8rem;
        }

        .bvct-author-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: var(--secondary);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .bvct-author-name {
            font-weight: 700;
            color: var(--text-heading);
            font-size: .9rem;
        }

        .bvct-author-role {
            font-size: .75rem;
            color: var(--text-muted);
        }

        .bvct-share {
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        .bvct-share-label {
            font-size: .82rem;
            font-weight: 600;
            color: var(--text-muted);
        }

        .bvct-share-btn {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .85rem;
            border: none;
            cursor: pointer;
            transition: all var(--transition);
        }

        .bvct-share-fb {
            background: #e8f0fe;
            color: #1877f2;
        }

        .bvct-share-fb:hover {
            background: #1877f2;
            color: #fff;
            transform: translateY(-2px);
        }

        .bvct-share-tw {
            background: #e7f5fd;
            color: #1da1f2;
        }

        .bvct-share-tw:hover {
            background: #1da1f2;
            color: #fff;
            transform: translateY(-2px);
        }

        .bvct-share-copy {
            background: var(--primary-light);
            color: var(--primary);
        }

        .bvct-share-copy:hover {
            background: var(--primary);
            color: #fff;
            transform: translateY(-2px);
        }

        /* ── Tiêu đề tin liên quan ── */
        .bvct-related-title {
            display: flex;
            align-items: center;
            gap: .75rem;
            color: var(--text-heading);
            font-weight: 800;
            margin-bottom: 1.5rem;
        }

        .bvct-related-title span {
            display: inline-block;
            width: 4px;
            height: 22px;
            background: var(--primary);
            border-radius: 2px;
            flex-shrink: 0;
        }

        .bvct-related-thumb {
            height: 160px;
            display: block;
        }

        /* ── Sidebar: Tư vấn ── */
        .bvct-consult-card {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(27, 58, 107, .15);
            border: 1px solid var(--border);
        }

        .bvct-consult-header {
            background: linear-gradient(135deg, var(--secondary), var(--secondary-dark));
            padding: 1.4rem 1.5rem;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .bvct-consult-icon {
            width: 48px;
            height: 48px;
            background: rgba(255, 255, 255, .12);
            border: 1px solid rgba(255, 255, 255, .2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            color: var(--primary);
            flex-shrink: 0;
        }

        .bvct-consult-body {
            background: #fff;
            padding: 1.5rem;
        }

        .bvct-input {
            background: var(--bg-alt) !important;
            border: 1.5px solid var(--border) !important;
            border-radius: 8px !important;
            padding: .65rem .9rem !important;
            font-size: .9rem !important;
            transition: border-color var(--transition), box-shadow var(--transition) !important;
        }

        .bvct-input:focus {
            border-color: var(--primary) !important;
            box-shadow: 0 0 0 3px var(--primary-light) !important;
            background: #fff !important;
            outline: none !important;
        }

        /* ── Sidebar: Tin hot ── */
        .bvct-hot-item {
            display: flex;
            align-items: flex-start;
            gap: .9rem;
        }

        .bvct-hot-num {
            font-size: 1.6rem;
            font-weight: 900;
            line-height: 1;
            color: var(--border);
            flex-shrink: 0;
            min-width: 32px;
        }

        .bvct-hot-item:first-child .bvct-hot-num {
            color: var(--primary);
        }

        .bvct-hot-item:nth-child(2) .bvct-hot-num {
            color: var(--text-muted);
        }

        .bv-hover-link {
            transition: color var(--transition);
        }

        .bv-hover-link:hover {
            color: var(--primary) !important;
        }

        /* ── Responsive ── */
        @media (max-width: 767px) {
            .bvct-card {
                padding: 1.5rem;
            }

            .bvct-hero {
                padding: 3rem 0 2.5rem;
            }

            .bvct-footer {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>

    {{-- ── SCRIPTS ── --}}
    <script>
        /* Reading Progress Bar */
        window.addEventListener('scroll', function() {
            const article = document.querySelector('.article-content');
            const bar = document.getElementById('readingProgress');
            if (!article || !bar) return;

            const rect = article.getBoundingClientRect();
            const total = article.offsetHeight;
            const scrolled = Math.max(0, -rect.top);
            const pct = Math.min(100, (scrolled / total) * 100);
            bar.style.width = pct + '%';
        });

        /* Copy Link */
        function copyLink() {
            navigator.clipboard.writeText(window.location.href).then(function() {
                const icon = document.getElementById('copyIcon');
                icon.className = 'fas fa-check';
                setTimeout(function() {
                    icon.className = 'fas fa-link';
                }, 2000);
            });
        }
    </script>

@endsection
