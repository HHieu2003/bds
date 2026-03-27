@extends('frontend.layouts.master')

@section('title', $bds->tieu_de . ' — Thành Công Land')

@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css" rel="stylesheet" />
    <style>
        /* ═══════════════════════════════════════
           TRANG CHI TIẾT BĐS — Global Styles
        ═══════════════════════════════════════ */
        .bds-detail-page {
            background: #f4f6f9;
            min-height: 100vh;
        }

        /* ── BREADCRUMB ── */
        .bds-breadcrumb-wrap {
            background: #fff;
            border-bottom: 1px solid #e9ecef;
            padding: .65rem 0;
        }

        .bds-breadcrumb {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: .25rem;
            font-size: .8rem;
            margin: 0;
        }

        .bds-breadcrumb a {
            color: #6b7280;
            text-decoration: none;
            transition: color .2s;
        }

        .bds-breadcrumb a:hover {
            color: #FF8C42;
        }

        .bds-breadcrumb .sep {
            color: #d1d5db;
            margin: 0 .2rem;
        }

        .bds-breadcrumb .active {
            color: #FF8C42;
            font-weight: 700;
        }

        /* ── GALLERY ── */
        .bds-gallery {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr 1fr;
            grid-template-rows: 260px 260px;
            gap: 6px;
            border-radius: 20px;
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .bds-gallery .gal-main {
            grid-column: 1 / 3;
            grid-row: 1 / 3;
        }

        .bds-gallery .gal-item {
            position: relative;
            overflow: hidden;
            cursor: pointer;
        }

        .bds-gallery .gal-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .5s ease;
            display: block;
        }

        .bds-gallery .gal-item:hover img {
            transform: scale(1.06);
        }

        .bds-gallery .gal-overlay {
            position: absolute;
            inset: 0;
            background: rgba(15, 23, 42, .55);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity .3s;
        }

        .bds-gallery .gal-item:hover .gal-overlay {
            opacity: 1;
        }

        .bds-gallery .gal-overlay i {
            color: #fff;
            font-size: 1.6rem;
        }

        /* Overlay "+N ảnh" */
        .gal-more-overlay {
            position: absolute;
            inset: 0;
            background: rgba(15, 23, 42, .65);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: .3rem;
            color: #fff;
            font-weight: 800;
            backdrop-filter: blur(2px);
        }

        .gal-more-overlay span {
            font-size: 1.6rem;
            line-height: 1;
        }

        .gal-more-overlay small {
            font-size: .72rem;
            opacity: .85;
            letter-spacing: .5px;
        }

        /* Badge nhu cầu trên gallery */
        .bds-nhu-cau-badge {
            position: absolute;
            top: 16px;
            left: 16px;
            z-index: 2;
            padding: .35rem .9rem;
            border-radius: 8px;
            font-size: .75rem;
            font-weight: 800;
            letter-spacing: .5px;
            text-transform: uppercase;
            color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .25);
        }

        .bds-nhu-cau-badge.ban {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
        }

        .bds-nhu-cau-badge.thue {
            background: linear-gradient(135deg, #27ae60, #219a52);
        }

        /* Mobile gallery */
        @media(max-width:768px) {
            .bds-gallery {
                grid-template-columns: 1fr 1fr;
                grid-template-rows: 220px 120px;
            }

            .bds-gallery .gal-main {
                grid-column: 1/3;
                grid-row: 1/2;
            }

            .bds-gallery .gal-sub-3,
            .bds-gallery .gal-sub-4 {
                display: none;
            }
        }

        /* ── TITLE BLOCK ── */
        .bds-title-block {
            background: #fff;
            border-radius: 16px;
            padding: 1.6rem 1.8rem;
            margin-bottom: 1.2rem;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .06);
            border-left: 4px solid #FF8C42;
        }

        .bds-main-title {
            font-size: 1.55rem;
            font-weight: 900;
            color: #0F172A;
            line-height: 1.35;
            margin-bottom: .6rem;
        }

        .bds-address {
            font-size: .88rem;
            color: #6b7280;
            display: flex;
            align-items: center;
            gap: .4rem;
            margin-bottom: 1rem;
        }

        .bds-address i {
            color: #FF8C42;
            flex-shrink: 0;
        }

        .bds-price-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: .6rem;
        }

        .bds-price {
            font-size: 1.7rem;
            font-weight: 900;
            background: linear-gradient(135deg, #FF8C42, #FF5722);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .bds-meta-badges {
            display: flex;
            flex-wrap: wrap;
            gap: .5rem;
        }

        .bds-meta-badge {
            display: inline-flex;
            align-items: center;
            gap: .3rem;
            background: #f4f6f9;
            border-radius: 20px;
            padding: .25rem .75rem;
            font-size: .75rem;
            color: #6b7280;
            font-weight: 600;
        }

        .bds-meta-badge i {
            color: #FF8C42;
            font-size: .7rem;
        }

        /* ── SPECS GRID ── */
        .specs-card {
            background: #fff;
            border-radius: 16px;
            padding: 1.5rem 1.8rem;
            margin-bottom: 1.2rem;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .06);
        }

        .specs-card h5 {
            font-size: .95rem;
            font-weight: 800;
            color: #0F172A;
            text-transform: uppercase;
            letter-spacing: .5px;
            margin-bottom: 1.2rem;
            padding-bottom: .8rem;
            border-bottom: 2px solid #f0e4da;
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        .specs-card h5 i {
            color: #FF8C42;
        }

        .spec-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 1rem .75rem;
            background: linear-gradient(135deg, #fff8f3, #fff);
            border: 1.5px solid #f0e4da;
            border-radius: 14px;
            transition: all .2s;
        }

        .spec-item:hover {
            border-color: #FF8C42;
            box-shadow: 0 4px 16px rgba(255, 140, 66, .15);
            transform: translateY(-2px);
        }

        .spec-item .spec-icon {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, #FF8C42, #FF5722);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: .6rem;
            font-size: 1.1rem;
            color: #fff;
            box-shadow: 0 4px 12px rgba(255, 140, 66, .3);
        }

        .spec-item .spec-label {
            font-size: .68rem;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: .5px;
            font-weight: 700;
            margin-bottom: .2rem;
        }

        .spec-item .spec-value {
            font-size: .92rem;
            font-weight: 800;
            color: #0F172A;
        }

        /* Row thông tin thêm */
        .info-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: .6rem;
            margin-top: 1.2rem;
            padding-top: 1.2rem;
            border-top: 1px solid #f0e4da;
        }

        @media(max-width:576px) {
            .info-row {
                grid-template-columns: 1fr;
            }
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: .6rem;
        }

        .info-item-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: #fff8f3;
            border: 1px solid #f0e4da;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .8rem;
            color: #FF8C42;
            flex-shrink: 0;
        }

        .info-item-text .lbl {
            font-size: .68rem;
            color: #94a3b8;
            display: block;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .3px;
        }

        .info-item-text .val {
            font-size: .82rem;
            font-weight: 700;
            color: #1a3c5e;
        }

        /* ── MÔ TẢ ── */
        .mo-ta-card {
            background: #fff;
            border-radius: 16px;
            padding: 1.5rem 1.8rem;
            margin-bottom: 1.2rem;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .06);
        }

        .mo-ta-card h5 {
            font-size: .95rem;
            font-weight: 800;
            color: #0F172A;
            text-transform: uppercase;
            letter-spacing: .5px;
            margin-bottom: 1.2rem;
            padding-bottom: .8rem;
            border-bottom: 2px solid #f0e4da;
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        .mo-ta-card h5 i {
            color: #FF8C42;
        }

        .article-content {
            color: #475569;
            line-height: 1.85;
            font-size: .92rem;
        }

        .article-content img {
            max-width: 100% !important;
            border-radius: 10px;
            margin: 1rem 0;
        }

        .article-content p {
            margin-bottom: 1rem;
        }

        /* Đọc thêm */
        .mo-ta-preview {
            max-height: 200px;
            overflow: hidden;
            position: relative;
        }

        .mo-ta-preview.expanded {
            max-height: none;
        }

        .mo-ta-fade {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 80px;
            background: linear-gradient(to bottom, transparent, #fff);
            pointer-events: none;
        }

        .mo-ta-preview.expanded~.mo-ta-fade {
            display: none;
        }

        .btn-read-more {
            background: none;
            border: none;
            color: #FF8C42;
            font-weight: 700;
            font-size: .82rem;
            cursor: pointer;
            padding: .5rem 0;
            display: flex;
            align-items: center;
            gap: .3rem;
            transition: color .2s;
        }

        .btn-read-more:hover {
            color: #FF5722;
        }

        /* ── SIDEBAR ── */
        .sidebar-sticky {
            position: sticky;
            top: 90px;
        }

        /* Card chuyên viên */
        .consultant-card {
            background: #fff;
            border-radius: 20px;
            overflow: hidden;
            margin-bottom: 1rem;
            box-shadow: 0 4px 24px rgba(0, 0, 0, .1);
        }

        .consultant-header {
            background: linear-gradient(135deg, #0F172A 0%, #1a3c5e 60%, #2d6a9f 100%);
            padding: 1.5rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .consultant-header::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .consultant-avatar {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: linear-gradient(135deg, #FF8C42, #FF5722);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.8rem;
            color: #fff;
            border: 3px solid rgba(255, 255, 255, .3);
            box-shadow: 0 8px 24px rgba(255, 140, 66, .4);
            position: relative;
        }

        .consultant-avatar .online-dot {
            position: absolute;
            bottom: 3px;
            right: 3px;
            width: 14px;
            height: 14px;
            background: #2ecc71;
            border-radius: 50%;
            border: 2px solid #fff;
        }

        .consultant-name {
            font-size: 1rem;
            font-weight: 800;
            color: #fff;
            margin-bottom: .2rem;
        }

        .consultant-role {
            font-size: .72rem;
            color: rgba(255, 255, 255, .65);
        }

        .consultant-body {
            padding: 1.3rem;
        }

        /* Buttons liên hệ */
        .btn-hotline {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .6rem;
            width: 100%;
            padding: .75rem;
            margin-bottom: .6rem;
            border-radius: 12px;
            font-weight: 800;
            font-size: .88rem;
            background: #fff;
            border: 2px solid #0F172A;
            color: #0F172A;
            text-decoration: none;
            transition: all .2s;
        }

        .btn-hotline:hover {
            background: #0F172A;
            color: #fff;
            border-color: #0F172A;
        }

        .btn-hotline i {
            color: #FF8C42;
            font-size: 1rem;
            transition: color .2s;
        }

        .btn-hotline:hover i {
            color: #FF8C42;
        }

        .btn-zalo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .6rem;
            width: 100%;
            padding: .75rem;
            margin-bottom: 1rem;
            border-radius: 12px;
            font-weight: 800;
            font-size: .88rem;
            background: linear-gradient(135deg, #0068FF, #0052cc);
            color: #fff;
            text-decoration: none;
            border: none;
            box-shadow: 0 4px 14px rgba(0, 104, 255, .3);
            transition: all .2s;
        }

        .btn-zalo:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 104, 255, .4);
            color: #fff;
        }

        /* Form yêu cầu gọi lại */
        .callback-form-title {
            font-size: .82rem;
            font-weight: 800;
            color: #0F172A;
            text-transform: uppercase;
            letter-spacing: .5px;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: .4rem;
        }

        .callback-form-title i {
            color: #FF8C42;
        }

        .form-input-custom {
            width: 100%;
            padding: .7rem 1rem;
            border: 1.5px solid #e9ecef;
            border-radius: 10px;
            font-size: .85rem;
            background: #f8fafc;
            color: #333;
            outline: none;
            transition: border-color .2s, background .2s;
            font-family: inherit;
        }

        .form-input-custom:focus {
            border-color: #FF8C42;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(255, 140, 66, .1);
        }

        .form-input-custom::placeholder {
            color: #9ca3af;
        }

        .btn-callback {
            width: 100%;
            padding: .75rem;
            background: linear-gradient(135deg, #FF8C42, #FF5722);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-weight: 800;
            font-size: .88rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .5rem;
            box-shadow: 0 4px 14px rgba(255, 140, 66, .35);
            transition: all .2s;
            font-family: inherit;
        }

        .btn-callback:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 140, 66, .45);
        }

        /* Nút Lưu & So sánh */
        .action-btns {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: .6rem;
            margin-top: .8rem;
        }

        .btn-action {
            padding: .7rem .5rem;
            border-radius: 12px;
            font-size: .78rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .4rem;
            cursor: pointer;
            transition: all .2s;
            border: 1.5px solid;
            font-family: inherit;
        }

        .btn-save {
            background: #fff5ef;
            border-color: #fed7aa;
            color: #c2410c;
        }

        .btn-save:hover {
            background: #c2410c;
            color: #fff;
            border-color: #c2410c;
        }

        .btn-save.liked {
            background: #c2410c;
            color: #fff;
            border-color: #c2410c;
        }

        .btn-save.liked i {
            color: #fff;
        }

        .btn-compare {
            background: #f0f9ff;
            border-color: #bae6fd;
            color: #0369a1;
        }

        .btn-compare:hover {
            background: #0369a1;
            color: #fff;
            border-color: #0369a1;
        }

        .btn-compare i {
            color: #0369a1;
            transition: color .2s;
        }

        .btn-compare:hover i {
            color: #fff;
        }

        /* ── THỐNG KÊ NHANH ── */
        .quick-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: .5rem;
            margin-bottom: .8rem;
        }

        .qs-item {
            background: #f8fafc;
            border-radius: 10px;
            padding: .65rem .5rem;
            text-align: center;
            border: 1px solid #e9ecef;
        }

        .qs-item .qs-num {
            font-size: 1rem;
            font-weight: 900;
            color: #0F172A;
            display: block;
        }

        .qs-item .qs-lbl {
            font-size: .62rem;
            color: #9ca3af;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .4px;
        }

        /* ── BĐS LIÊN QUAN ── */
        .related-section {
            margin-top: 2.5rem;
        }

        .section-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }

        .section-title {
            font-size: 1.15rem;
            font-weight: 900;
            color: #0F172A;
            padding-left: .9rem;
            border-left: 4px solid #FF8C42;
        }

        .section-link {
            font-size: .82rem;
            font-weight: 700;
            color: #FF8C42;
            text-decoration: none;
            transition: color .2s;
        }

        .section-link:hover {
            color: #FF5722;
        }

        .bds-lq-card {
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .07);
            transition: transform .25s, box-shadow .25s;
            height: 100%;
        }

        .bds-lq-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, .12);
        }

        .bds-lq-img {
            height: 180px;
            overflow: hidden;
            position: relative;
        }

        .bds-lq-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .5s;
        }

        .bds-lq-card:hover .bds-lq-img img {
            transform: scale(1.08);
        }

        .bds-lq-nhu-cau {
            position: absolute;
            top: 10px;
            left: 10px;
            font-size: .62rem;
            font-weight: 800;
            color: #fff;
            padding: .18rem .55rem;
            border-radius: 6px;
            text-transform: uppercase;
            letter-spacing: .4px;
        }

        .bds-lq-nhu-cau.ban {
            background: #e74c3c;
        }

        .bds-lq-nhu-cau.thue {
            background: #27ae60;
        }

        .bds-lq-body {
            padding: 1rem;
        }

        .bds-lq-price {
            font-size: 1rem;
            font-weight: 900;
            color: #FF8C42;
            margin-bottom: .4rem;
        }

        .bds-lq-title {
            font-size: .83rem;
            font-weight: 700;
            color: #0F172A;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            line-height: 1.4;
            margin-bottom: .65rem;
            text-decoration: none;
        }

        .bds-lq-title:hover {
            color: #FF8C42;
        }

        .bds-lq-footer {
            display: flex;
            gap: .8rem;
            font-size: .72rem;
            color: #6b7280;
            font-weight: 600;
            border-top: 1px solid #f0f0f0;
            padding-top: .65rem;
        }

        .bds-lq-footer i {
            color: #FF8C42;
        }
    </style>
@endpush

@section('content')

    @php
        $album = [];
        if (!empty($bds->album_anh)) {
            $raw = is_string($bds->album_anh) ? json_decode($bds->album_anh, true) : $bds->album_anh;
            $album = is_array($raw) ? $raw : [];
        }
        $default = asset('images/default-bds.jpg');
        $anhChinh = count($album) > 0 ? asset('storage/' . $album[0]) : $default;
        $anh2 = count($album) > 1 ? asset('storage/' . $album[1]) : $default;
        $anh3 = count($album) > 2 ? asset('storage/' . $album[2]) : $default;
        $anh4 = count($album) > 3 ? asset('storage/' . $album[3]) : $default;
        $extraCount = max(0, count($album) - 4);
    @endphp

    <div class="bds-detail-page">

        {{-- BREADCRUMB --}}
        <div class="bds-breadcrumb-wrap">
            <div class="container">
                <ol class="bds-breadcrumb">
                    <li><a href="{{ route('frontend.home') }}"><i class="fas fa-home me-1"></i>Trang chủ</a></li>
                    <span class="sep">/</span>
                    <li>
                        <a href="{{ route('frontend.bat-dong-san.index', ['nhu_cau' => $bds->nhu_cau]) }}">
                            {{ $bds->nhu_cau == 'ban' ? 'Mua Bán' : 'Cho Thuê' }}
                        </a>
                    </li>
                    @if ($bds->duAn)
                        <span class="sep">/</span>
                        <li>
                            <a href="{{ route('frontend.du-an.show', $bds->duAn->slug) }}">
                                {{ $bds->duAn->ten_du_an }}
                            </a>
                        </li>
                    @endif
                    <span class="sep">/</span>
                    <li class="active">{{ Str::limit($bds->tieu_de, 50) }}</li>
                </ol>
            </div>
        </div>

        <div class="container py-4">

            {{-- ═══════ GALLERY ═══════ --}}
            <div class="position-relative">
                <div class="bds-gallery">
                    {{-- Ảnh chính --}}
                    <a href="{{ $anhChinh }}" data-lightbox="bds-gallery" class="gal-item gal-main">
                        <img src="{{ $anhChinh }}" alt="{{ $bds->tieu_de }}" loading="eager">
                        <div class="gal-overlay"><i class="fas fa-search-plus"></i></div>
                    </a>

                    {{-- Ảnh phụ 2 --}}
                    <a href="{{ $anh2 }}" data-lightbox="bds-gallery" class="gal-item gal-sub-2">
                        <img src="{{ $anh2 }}" alt="Ảnh 2" loading="lazy">
                        <div class="gal-overlay"><i class="fas fa-search-plus"></i></div>
                    </a>

                    {{-- Ảnh phụ 3 --}}
                    <a href="{{ $anh3 }}" data-lightbox="bds-gallery" class="gal-item gal-sub-3">
                        <img src="{{ $anh3 }}" alt="Ảnh 3" loading="lazy">
                        <div class="gal-overlay"><i class="fas fa-search-plus"></i></div>
                    </a>

                    {{-- Ảnh phụ 4 — overlay "+N" nếu còn nhiều --}}
                    <a href="{{ $anh4 }}" data-lightbox="bds-gallery" class="gal-item gal-sub-4">
                        <img src="{{ $anh4 }}" alt="Ảnh 4" loading="lazy">
                        @if ($extraCount > 0)
                            <div class="gal-more-overlay">
                                <span>+{{ $extraCount }}</span>
                                <small>XEM THÊM</small>
                            </div>
                        @else
                            <div class="gal-overlay"><i class="fas fa-search-plus"></i></div>
                        @endif
                    </a>
                </div>

                {{-- Ảnh ẩn cho lightbox --}}
                @if (count($album) > 4)
                    @for ($i = 4; $i < count($album); $i++)
                        <a href="{{ asset('storage/' . $album[$i]) }}" data-lightbox="bds-gallery" class="d-none"></a>
                    @endfor
                @endif

                {{-- Badge nhu cầu --}}
                <span class="bds-nhu-cau-badge {{ $bds->nhu_cau == 'ban' ? 'ban' : 'thue' }}">
                    <i class="fas {{ $bds->nhu_cau == 'ban' ? 'fa-tag' : 'fa-key' }} me-1"></i>
                    {{ $bds->nhu_cau == 'ban' ? 'Đang Bán' : 'Cho Thuê' }}
                </span>
            </div>

            {{-- ═══════ LAYOUT CHÍNH ═══════ --}}
            <div class="row g-4">

                {{-- ══ CỘT TRÁI ══ --}}
                <div class="col-lg-8">

                    {{-- TITLE BLOCK --}}
                    <div class="bds-title-block">
                        <h1 class="bds-main-title">{{ $bds->tieu_de }}</h1>
                        <p class="bds-address">
                            <i class="fas fa-map-marker-alt"></i>
                            {{ $bds->dia_chi }}
                        </p>
                        <div class="bds-price-row">
                            <div class="bds-price">{{ $bds->gia_hien_thi ?? 'Thỏa thuận' }}</div>
                            <div class="bds-meta-badges">
                                <span class="bds-meta-badge">
                                    <i class="far fa-eye"></i> {{ number_format($bds->luot_xem ?? 0) }} lượt xem
                                </span>
                                <span class="bds-meta-badge">
                                    <i class="far fa-clock"></i> {{ $bds->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- THÔNG SỐ CƠ BẢN --}}
                    <div class="specs-card">
                        <h5><i class="fas fa-list-ul"></i> Thông Số Cơ Bản</h5>

                        <div class="row g-3 mb-1">
                            <div class="col-6 col-md-3">
                                <div class="spec-item">
                                    <div class="spec-icon"><i class="fas fa-vector-square"></i></div>
                                    <div class="spec-label">Diện tích</div>
                                    <div class="spec-value">{{ $bds->dien_tich }} m²</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="spec-item">
                                    <div class="spec-icon"><i class="fas fa-bed"></i></div>
                                    <div class="spec-label">Phòng ngủ</div>
                                    <div class="spec-value">{{ $bds->so_phong_ngu ?? '—' }} PN</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="spec-item">
                                    <div class="spec-icon"><i class="fas fa-bath"></i></div>
                                    <div class="spec-label">Phòng tắm</div>
                                    <div class="spec-value">{{ $bds->so_phong_tam ?? '—' }} WC</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="spec-item">
                                    <div class="spec-icon"><i class="fas fa-compass"></i></div>
                                    <div class="spec-label">Hướng nhà</div>
                                    <div class="spec-value">{{ $bds->huong ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-item">
                                <div class="info-item-icon"><i class="fas fa-file-alt"></i></div>
                                <div class="info-item-text">
                                    <span class="lbl">Pháp lý</span>
                                    <span class="val">{{ $bds->phap_ly ?? 'Sổ đỏ / Sổ hồng' }}</span>
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="info-item-icon"><i class="fas fa-couch"></i></div>
                                <div class="info-item-text">
                                    <span class="lbl">Nội thất</span>
                                    <span class="val">{{ $bds->noi_that ?? 'Cơ bản' }}</span>
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="info-item-icon"><i class="fas fa-layer-group"></i></div>
                                <div class="info-item-text">
                                    <span class="lbl">Tầng số</span>
                                    <span class="val">{{ $bds->tang ?? 'Đang cập nhật' }}</span>
                                </div>
                            </div>
                            @if ($bds->duAn)
                                <div class="info-item">
                                    <div class="info-item-icon"><i class="fas fa-building"></i></div>
                                    <div class="info-item-text">
                                        <span class="lbl">Dự án</span>
                                        <a href="{{ route('frontend.du-an.show', $bds->duAn->slug) }}"
                                            class="val text-decoration-none" style="color:#FF8C42;">
                                            {{ $bds->duAn->ten_du_an }}
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- MÔ TẢ --}}
                    <div class="mo-ta-card">
                        <h5><i class="fas fa-align-left"></i> Thông Tin Mô Tả</h5>

                        <div class="mo-ta-preview" id="motaPreview">
                            <div class="article-content">
                                @if ($bds->mo_ta)
                                    {!! $bds->mo_ta !!}
                                @else
                                    <p class="text-muted fst-italic">Đang cập nhật mô tả cho bất động sản này...</p>
                                @endif
                            </div>
                        </div>
                        <div class="mo-ta-fade" id="motaFade"></div>
                        <button class="btn-read-more" id="btnReadMore" onclick="toggleMoTa()">
                            <i class="fas fa-chevron-down" id="iconReadMore"></i>
                            <span id="txtReadMore">Xem thêm nội dung</span>
                        </button>
                    </div>

                </div>

                {{-- ══ CỘT PHẢI — SIDEBAR ══ --}}
                <div class="col-lg-4">
                    <div class="sidebar-sticky">

                        {{-- Thống kê nhanh --}}
                        <div class="quick-stats">
                            <div class="qs-item">
                                <span class="qs-num">{{ $bds->dien_tich }}</span>
                                <span class="qs-lbl">m² DT</span>
                            </div>
                            <div class="qs-item">
                                <span class="qs-num">{{ $bds->so_phong_ngu ?? '—' }}</span>
                                <span class="qs-lbl">Phòng ngủ</span>
                            </div>
                            <div class="qs-item">
                                <span class="qs-num">{{ $bds->so_phong_tam ?? '—' }}</span>
                                <span class="qs-lbl">Phòng tắm</span>
                            </div>
                        </div>

                        {{-- Card chuyên viên --}}
                        <div class="consultant-card">
                            <div class="consultant-header">
                                <div class="consultant-avatar">
                                    <i class="fas fa-user-tie"></i>
                                    <span class="online-dot"></span>
                                </div>
                                <div class="consultant-name">Chuyên Viên Tư Vấn</div>
                                <div class="consultant-role">Thành Công Land • Đang trực tuyến</div>
                            </div>

                            <div class="consultant-body">
                                {{-- Hotline --}}
                                <a href="tel:0912345678" class="btn-hotline">
                                    <i class="fas fa-phone-alt"></i> 0912 345 678
                                </a>

                                {{-- Zalo --}}
                                <a href="https://zalo.me/0912345678" target="_blank" class="btn-zalo">
                                    <i class="fas fa-comment-dots"></i> Chat Zalo ngay
                                </a>

                                {{-- Form gọi lại --}}
                                <div class="callback-form-title">
                                    <i class="fas fa-phone-volume"></i> Yêu cầu tư vấn
                                </div>
                                <form id="formCallBack" onsubmit="guiYeuCauGoiLai(event)">
                                    @csrf
                                    <input type="hidden" name="bat_dong_san_id" value="{{ $bds->id }}">
                                    <div style="margin-bottom:.6rem;">
                                        <input type="tel" class="form-input-custom" name="sodienthoai"
                                            placeholder="Số điện thoại của bạn *" required>
                                    </div>
                                    <div style="margin-bottom:.75rem;">
                                        <textarea class="form-input-custom" name="noi_dung" rows="2" placeholder="Nội dung cần tư vấn..."
                                            style="resize:none;">Tôi quan tâm đến BĐS: {{ $bds->tieu_de }}</textarea>
                                    </div>
                                    <button type="submit" class="btn-callback" id="btnCallBack">
                                        <i class="fas fa-paper-plane"></i> Gửi Yêu Cầu
                                    </button>
                                </form>
                            </div>
                        </div>

                        {{-- Nút Lưu & So sánh --}}
                        <div class="action-btns">
                            <button
                                class="btn-action btn-save heart-icon-{{ $bds->id }} {{ $bds->isYeuThich ?? false ? 'liked' : '' }}"
                                id="btnLuuTin" onclick="toggleYeuThich(this, {{ $bds->id }})"
                                title="{{ $bds->isYeuThich ?? false ? 'Bỏ yêu thích' : 'Lưu tin' }}">
                                <i class="{{ $bds->isYeuThich ?? false ? 'fas' : 'far' }} fa-heart"></i> Lưu tin
                            </button>
                            <button class="btn-action btn-compare"
                                onclick="addSoSanh({{ $bds->id }}, '{{ addslashes($bds->tieu_de) }}')">
                                <i class="fas fa-balance-scale"></i> So sánh
                            </button>
                        </div>

                    </div>
                </div>

            </div>

            {{-- ═══════ BĐS LIÊN QUAN ═══════ --}}
            @if (isset($bdsLienQuan) && $bdsLienQuan->count() > 0)
                <div class="related-section">
                    <div class="section-heading">
                        <h3 class="section-title">Bất Động Sản Tương Tự</h3>
                        <a href="{{ route('frontend.bat-dong-san.index', ['nhu_cau' => $bds->nhu_cau]) }}"
                            class="section-link">
                            Xem tất cả <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>

                    <div class="row g-3">
                        @foreach ($bdsLienQuan as $item)
                            @php
                                $anhLQ = [];
                                if (!empty($item->album_anh)) {
                                    $r = is_string($item->album_anh)
                                        ? json_decode($item->album_anh, true)
                                        : $item->album_anh;
                                    $anhLQ = is_array($r) ? $r : [];
                                }
                                $hinhAnhLQ =
                                    count($anhLQ) > 0 ? asset('storage/' . $anhLQ[0]) : asset('images/default-bds.jpg');
                            @endphp
                            <div class="col-6 col-md-6 col-lg-3">
                                <div class="bds-lq-card">
                                    <div class="bds-lq-img">
                                        <a href="{{ route('frontend.bat-dong-san.show', $item->slug) }}">
                                            <img src="{{ $hinhAnhLQ }}" alt="{{ $item->tieu_de }}" loading="lazy">
                                        </a>
                                        <span class="bds-lq-nhu-cau {{ $item->nhu_cau == 'ban' ? 'ban' : 'thue' }}">
                                            {{ $item->nhu_cau == 'ban' ? 'Bán' : 'Thuê' }}
                                        </span>
                                    </div>
                                    <div class="bds-lq-body">
                                        <div class="bds-lq-price">{{ $item->gia_hien_thi ?? 'Thỏa thuận' }}</div>
                                        <a href="{{ route('frontend.bat-dong-san.show', $item->slug) }}"
                                            class="bds-lq-title d-block">{{ $item->tieu_de }}</a>
                                        <div class="bds-lq-footer">
                                            <span><i class="fas fa-vector-square me-1"></i>{{ $item->dien_tich }}m²</span>
                                            <span><i class="fas fa-bed me-1"></i>{{ $item->so_phong_ngu }}PN</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>{{-- end container --}}
    </div>{{-- end page --}}

    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox-plus-jquery.min.js"></script>
        <script>
            /* Lightbox config */
            lightbox.option({
                resizeDuration: 200,
                wrapAround: true,
                albumLabel: 'Ảnh %1 / %2'
            });

            /* Đọc thêm / Thu gọn */
            function toggleMoTa() {
                var el = document.getElementById('motaPreview');
                var icon = document.getElementById('iconReadMore');
                var txt = document.getElementById('txtReadMore');
                var fade = document.getElementById('motaFade');
                var open = el.classList.toggle('expanded');
                icon.className = open ? 'fas fa-chevron-up' : 'fas fa-chevron-down';
                txt.textContent = open ? 'Thu gọn' : 'Xem thêm nội dung';
                if (fade) fade.style.display = open ? 'none' : 'block';
            }

            /* Gửi yêu cầu gọi lại */
            function guiYeuCauGoiLai(e) {
                e.preventDefault();
                var btn = document.getElementById('btnCallBack');
                var form = document.getElementById('formCallBack');
                var sdt = form.querySelector('[name=sodienthoai]').value.trim();

                if (!sdt) {
                    showFlash('Vui lòng nhập số điện thoại.', 'warning');
                    return;
                }

                btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Đang gửi...';
                btn.disabled = true;

                fetch('{{ route('frontend.lien-he.store') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            ho_ten: 'Khách hàng',
                            so_dien_thoai: sdt,
                            noi_dung: form.querySelector('[name=noi_dung]').value,
                            bat_dong_san_id: {{ $bds->id }}
                        })
                    })
                    .then(r => r.json())
                    .then(data => {
                        showFlash('Yêu cầu đã gửi! Chúng tôi sẽ liên hệ sớm.', 'success');
                        form.reset();
                        form.querySelector('textarea').value = 'Tôi quan tâm đến BĐS: {{ addslashes($bds->tieu_de) }}';
                    })
                    .catch(() => showFlash('Có lỗi xảy ra, vui lòng thử lại.', 'error'))
                    .finally(() => {
                        btn.innerHTML = '<i class="fas fa-paper-plane"></i> Gửi Yêu Cầu';
                        btn.disabled = false;
                    });
            }
        </script>
    @endpush

@endsection
