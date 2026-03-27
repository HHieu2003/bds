@push('styles')
    <style>
        /* ══════════════════════════════
           SECTION TIN TỨC — MAGAZINE
        ══════════════════════════════ */
        .news-section {
            background: var(--bg-alt);
            padding: 72px 0;
        }

        /* ── Header ── */
        .news-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            margin-bottom: 36px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .news-view-all {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--primary);
            font-weight: 700;
            font-size: .85rem;
            text-decoration: none;
            padding: 8px 18px;
            border: 1.5px solid rgba(192, 102, 42, .3);
            border-radius: 30px;
            background: var(--primary-light);
            transition: all var(--transition);
            white-space: nowrap;
        }

        .news-view-all:hover {
            background: var(--primary);
            color: #fff;
            border-color: var(--primary);
            transform: translateX(3px);
        }

        /* ══ MAGAZINE WRAPPER ══ */
        .news-magazine {
            display: grid;
            grid-template-columns: 1.25fr 1fr;
            gap: 16px;
            align-items: stretch;
            /* ← 2 cột tự kéo cao bằng nhau */
        }

        /* ════════════════════════════
           CỘT TRÁI — chiều cao khớp cột phải
        ════════════════════════════ */
        .news-col-left {
            display: flex;
            flex-direction: column;
            gap: 16px;
            height: 100%;
            /* ← lấp đầy chiều cao grid row */
        }

        /* ── Bài FEATURED ── */
        .news-card-featured {
            background: #fff;
            border-radius: 16px;
            border: 1.5px solid var(--border);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            flex: 1;
            /* ← tự co giãn theo không gian còn lại */
            min-height: 0;
            transition: transform var(--transition), box-shadow var(--transition);
        }

        .news-card-featured:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 48px rgba(0, 0, 0, .1);
            border-color: rgba(192, 102, 42, .2);
        }

        .news-card-featured .ncf-thumb {
            /* ← Không đặt height cố định — để flex tự điều chỉnh */
            flex: 1;
            min-height: 160px;
            max-height: 220px;
            /* ← giới hạn tối đa để không quá cao */
            overflow: hidden;
            background: var(--bg-alt);
            position: relative;
        }

        .news-card-featured .ncf-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .5s;
        }

        .news-card-featured:hover .ncf-thumb img {
            transform: scale(1.05);
        }

        .news-card-featured .ncf-body {
            padding: 14px 16px 16px;
            flex-shrink: 0;
            /* ← phần text không bị nén */
        }

        .ncf-title {
            font-size: .98rem;
            font-weight: 800;
            color: var(--secondary);
            line-height: 1.4;
            text-decoration: none;
            margin-bottom: 6px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            transition: color var(--transition);
        }

        .ncf-title:hover {
            color: var(--primary);
        }

        .ncf-excerpt {
            font-size: .845rem;
            color: var(--text-muted);
            line-height: 1.65;
            margin-bottom: 12px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            /* ← giảm từ 3 → 2 */
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* ── 2 bài MINI ── */
        .news-row-mini {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            flex-shrink: 0;
            /* ← không bị nén bởi featured */
        }

        .news-card-mini {
            background: #fff;
            border-radius: 14px;
            border: 1.5px solid var(--border);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: transform var(--transition), box-shadow var(--transition);
        }

        .news-card-mini:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, .09);
            border-color: rgba(192, 102, 42, .2);
        }

        .news-card-mini .ncm-thumb {
            height: 120px;
            /* ← giảm từ 140 → 120 */
            overflow: hidden;
            background: var(--bg-alt);
            flex-shrink: 0;
        }

        .news-card-mini .ncm-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .4s;
        }

        .news-card-mini:hover .ncm-thumb img {
            transform: scale(1.06);
        }

        .news-card-mini .ncm-body {
            padding: 11px 13px 13px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .ncm-title {
            font-size: .82rem;
            font-weight: 700;
            color: var(--secondary);
            line-height: 1.4;
            text-decoration: none;
            margin-bottom: 5px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            transition: color var(--transition);
        }

        .ncm-title:hover {
            color: var(--primary);
        }

        .ncm-excerpt {
            font-size: .73rem;
            color: var(--text-muted);
            line-height: 1.5;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            flex: 1;
            margin-bottom: 8px;
        }

        /* ════════════════════════════
           CỘT PHẢI: 4 bài xếp dọc
        ════════════════════════════ */
        .news-col-right {
            display: flex;
            flex-direction: column;
            gap: 0;
            background: #fff;
            border-radius: 16px;
            border: 1.5px solid var(--border);
            overflow: hidden;
            height: 100%;
            /* ← khớp chiều cao cột trái */
        }

        /* Header cột phải */
        .news-col-right-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 16px;
            border-bottom: 2px solid var(--primary);
            flex-shrink: 0;
        }

        .news-col-right-header span {
            font-size: .72rem;
            font-weight: 800;
            color: var(--secondary);
            text-transform: uppercase;
            letter-spacing: .6px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .news-col-right-header span i {
            color: var(--primary);
        }

        /* Stack items — tự chia đều chiều cao */
        .news-card-stack {
            display: flex;
            gap: 13px;
            align-items: center;
            padding: 13px 16px;
            border-bottom: 1px solid var(--border);
            transition: background var(--transition);
            text-decoration: none;
            flex: 1;
            /* ← mỗi item chiếm phần bằng nhau */
            min-height: 0;
        }

        .news-card-stack:last-child {
            border-bottom: none;
        }

        .news-card-stack:hover {
            background: var(--bg-alt);
        }

        .news-card-stack .ncs-thumb {
            width: 88px;
            height: 68px;
            flex-shrink: 0;
            border-radius: 10px;
            overflow: hidden;
            background: var(--bg-alt);
        }

        .news-card-stack .ncs-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .4s;
        }

        .news-card-stack:hover .ncs-thumb img {
            transform: scale(1.08);
        }

        .news-card-stack .ncs-body {
            flex: 1;
            min-width: 0;
        }

        .ncs-title {
            font-size: .83rem;
            font-weight: 700;
            color: var(--secondary);
            line-height: 1.4;
            margin-bottom: 4px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            transition: color var(--transition);
        }

        .news-card-stack:hover .ncs-title {
            color: var(--primary);
        }

        .ncs-excerpt {
            font-size: .73rem;
            color: var(--text-muted);
            line-height: 1.5;
            display: -webkit-box;
            -webkit-line-clamp: 1;
            /* ← 1 dòng cho gọn */
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Số thứ tự */
        .ncs-num {
            width: 20px;
            height: 20px;
            border-radius: 5px;
            background: var(--primary-light);
            color: var(--primary);
            font-size: .65rem;
            font-weight: 900;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            margin-right: 4px;
        }

        /* ══ META chung ══ */
        .nc-meta {
            display: flex;
            align-items: center;
            gap: 7px;
            flex-wrap: wrap;
            margin-bottom: 6px;
        }

        .nc-date {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: .67rem;
            font-weight: 700;
            color: var(--text-muted);
        }

        .nc-date i {
            color: var(--primary);
            font-size: .62rem;
        }

        .nc-badge {
            padding: 2px 8px;
            border-radius: 20px;
            font-size: .62rem;
            font-weight: 800;
            background: var(--primary-light);
            color: var(--primary);
            border: 1px solid rgba(192, 102, 42, .2);
        }

        .nc-badge-hot {
            padding: 2px 8px;
            border-radius: 20px;
            font-size: .62rem;
            font-weight: 800;
            background: rgba(229, 57, 53, .07);
            color: var(--status-danger);
            border: 1px solid rgba(229, 57, 53, .18);
            display: inline-flex;
            align-items: center;
            gap: 3px;
        }

        .nc-img-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background: linear-gradient(135deg, #d9834a, var(--primary));
            color: #fff;
            padding: 3px 11px;
            border-radius: 20px;
            font-size: .63rem;
            font-weight: 800;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .nc-read-more {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: .75rem;
            font-weight: 700;
            color: var(--primary);
            text-decoration: none;
            transition: gap var(--transition);
        }

        .nc-read-more:hover {
            gap: 9px;
        }

        /* ══ RESPONSIVE ══ */
        @media (max-width: 1099px) {
            .news-magazine {
                grid-template-columns: 1fr;
            }

            .news-col-right {
                flex-direction: row;
                flex-wrap: wrap;
                border-radius: 14px;
            }

            .news-col-right-header {
                width: 100%;
            }

            .news-card-stack {
                flex: 1 1 calc(50% - 1px);
                border-right: 1px solid var(--border);
            }

            .news-card-stack:nth-child(even) {
                border-right: none;
            }
        }

        @media (max-width: 767px) {
            .news-section {
                padding: 48px 0;
            }

            .news-row-mini {
                grid-template-columns: 1fr;
            }

            .news-card-stack {
                flex: 1 1 100%;
                border-right: none;
            }
        }
    </style>
@endpush


<section class="news-section">
    <div class="container">

        {{-- ── Header ── --}}
        <div class="news-header" data-aos="fade-up">
            <div>
                <h2 class="section-title serif-font">Tin Tức Thị Trường</h2>
                <p class="section-subtitle">Cập nhật thông tin, chính sách và kinh nghiệm mua bán</p>
            </div>
            <a href="{{ route('frontend.tin-tuc.index') }}" class="news-view-all d-none d-md-inline-flex">
                Xem tất cả <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        @php
            $ds = $baiVietMoi->take(7);
            $bai1 = $ds->get(0);
            $bai2 = $ds->get(1);
            $bai3 = $ds->get(2);
            $stack = $ds->slice(3);
        @endphp

        @if ($ds->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="fas fa-newspaper" style="font-size:2.5rem;opacity:.25;display:block;margin-bottom:12px"></i>
                Chưa có tin tức nào.
            </div>
        @else
            <div class="news-magazine" data-aos="fade-up" data-aos-delay="50">

                {{-- ════════════════
                     CỘT TRÁI
                ════════════════ --}}
                <div class="news-col-left">

                    {{-- Hàng trên: 1 bài FEATURED --}}
                    @if ($bai1)
                        @php
                            $anh1 = $bai1->hinh_anh
                                ? asset('storage/' . $bai1->hinh_anh)
                                : asset('images/default-news.jpg');
                            $ngay1 = \Carbon\Carbon::parse($bai1->thoi_diem_dang ?? $bai1->created_at);
                        @endphp
                        <div class="news-card-featured" data-aos="fade-right" data-aos-delay="80">
                            <div class="ncf-thumb">
                                <img src="{{ $anh1 }}" alt="{{ $bai1->tieu_de }}" loading="eager">
                                <span class="nc-img-badge"><i class="fas fa-fire-alt"></i> Nổi bật</span>
                            </div>
                            <div class="ncf-body">
                                <div class="nc-meta">
                                    <span class="nc-date">
                                        <i class="far fa-calendar-alt"></i>
                                        {{ $ngay1->format('d/m/Y') }}
                                    </span>
                                    <span class="nc-badge-hot">
                                        <i class="fas fa-circle" style="font-size:.45rem"></i> Mới nhất
                                    </span>
                                </div>
                                <a href="{{ route('frontend.tin-tuc.show', $bai1->slug) }}" class="ncf-title">
                                    {{ $bai1->tieu_de }}
                                </a>
                                <p class="ncf-excerpt">
                                    {{ $bai1->mo_ta_ngan ?? Str::limit(strip_tags($bai1->noi_dung ?? ''), 150) }}
                                </p>
                                <a href="{{ route('frontend.tin-tuc.show', $bai1->slug) }}" class="nc-read-more">
                                    Đọc tiếp <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    @endif

                    {{-- Hàng dưới: 2 bài MINI --}}
                    <div class="news-row-mini">
                        @foreach ([$bai2, $bai3] as $bvMini)
                            @if ($bvMini)
                                @php
                                    $anhM = $bvMini->hinh_anh
                                        ? asset('storage/' . $bvMini->hinh_anh)
                                        : asset('images/default-news.jpg');
                                    $ngayM = \Carbon\Carbon::parse($bvMini->thoi_diem_dang ?? $bvMini->created_at);
                                @endphp
                                <div class="news-card-mini" data-aos="fade-up"
                                    data-aos-delay="{{ $loop->iteration * 80 }}">
                                    <div class="ncm-thumb">
                                        <img src="{{ $anhM }}" alt="{{ $bvMini->tieu_de }}" loading="lazy">
                                    </div>
                                    <div class="ncm-body">
                                        <div class="nc-meta">
                                            <span class="nc-date">
                                                <i class="far fa-calendar-alt"></i>
                                                {{ $ngayM->format('d/m/Y') }}
                                            </span>
                                        </div>
                                        <a href="{{ route('frontend.tin-tuc.show', $bvMini->slug) }}"
                                            class="ncm-title">
                                            {{ $bvMini->tieu_de }}
                                        </a>
                                        <p class="ncm-excerpt">
                                            {{ $bvMini->mo_ta_ngan ?? Str::limit(strip_tags($bvMini->noi_dung ?? ''), 80) }}
                                        </p>
                                        <a href="{{ route('frontend.tin-tuc.show', $bvMini->slug) }}"
                                            class="nc-read-more" style="font-size:.73rem">
                                            Đọc tiếp <i class="fas fa-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                </div>{{-- .news-col-left --}}

                {{-- ════════════════
                     CỘT PHẢI: 4 bài xếp dọc
                ════════════════ --}}
                <div class="news-col-right">

                    <div class="news-col-right-header">
                        <span><i class="fas fa-list-ul"></i> Tin tức mới nhất</span>
                        <a href="{{ route('frontend.tin-tuc.index') }}"
                            style="font-size:.7rem;color:var(--primary);font-weight:700;text-decoration:none">
                            Xem thêm <i class="fas fa-angle-right"></i>
                        </a>
                    </div>

                    @foreach ($stack as $bvS)
                        @php
                            $anhS = $bvS->hinh_anh
                                ? asset('storage/' . $bvS->hinh_anh)
                                : asset('images/default-news.jpg');
                            $ngayS = \Carbon\Carbon::parse($bvS->thoi_diem_dang ?? $bvS->created_at);
                            $tomS = $bvS->mo_ta_ngan ?? Str::limit(strip_tags($bvS->noi_dung ?? ''), 80);
                        @endphp
                        <a href="{{ route('frontend.tin-tuc.show', $bvS->slug) }}" class="news-card-stack"
                            data-aos="fade-left" data-aos-delay="{{ $loop->iteration * 70 }}">
                            <div class="ncs-thumb">
                                <img src="{{ $anhS }}" alt="{{ $bvS->tieu_de }}" loading="lazy">
                            </div>
                            <div class="ncs-body">
                                <div style="display:flex;align-items:center;gap:6px;margin-bottom:4px">
                                    <span class="ncs-num">{{ $loop->iteration + 3 }}</span>
                                    <span class="nc-date">
                                        <i class="far fa-calendar-alt"></i>
                                        {{ $ngayS->format('d/m/Y') }}
                                    </span>
                                </div>
                                <div class="ncs-title">{{ $bvS->tieu_de }}</div>
                                <div class="ncs-excerpt">{{ $tomS }}</div>
                            </div>
                        </a>
                    @endforeach

                </div>{{-- .news-col-right --}}

            </div>{{-- .news-magazine --}}
        @endif

        {{-- Mobile CTA --}}
        <div class="text-center mt-4 d-md-none">
            <a href="{{ route('frontend.tin-tuc.index') }}" class="btn-primary-brand justify-content-center"
                style="display:inline-flex;width:100%;max-width:340px">
                <i class="fas fa-newspaper me-2"></i> Xem tất cả tin tức
            </a>
        </div>

    </div>
</section>
