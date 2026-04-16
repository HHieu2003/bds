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

        .lb-outerContainer {
            background-color: #919191;
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

        .bds-gallery.count-1 {
            grid-template-columns: 1fr;
            grid-template-rows: 460px;
        }

        .bds-gallery.count-2 {
            grid-template-columns: 2fr 1fr;
            grid-template-rows: 227px 227px;
        }

        .bds-gallery .gal-main {
            grid-column: 1 / 3;
            grid-row: 1 / 3;
        }

        .bds-gallery.count-1 .gal-main {
            grid-column: 1 / -1;
            grid-row: 1 / -1;
        }

        .bds-gallery.count-2 .gal-main {
            grid-column: 1 / 2;
            grid-row: 1 / 3;
        }

        .bds-gallery.count-2 .gal-sub-2 {
            grid-column: 2 / 3;
            grid-row: 1 / 3;
        }

        .bds-gallery .gal-item {
            position: relative;
            overflow: hidden;
            cursor: pointer;
            border: 0;
            padding: 0;
            background: transparent;
            width: 100%;
            display: block;
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

        .gal-more-overlay {
            position: absolute;
            right: 10px;
            bottom: 10px;
            background: rgba(0, 0, 0, .62);
            color: #fff;
            border-radius: 10px;
            padding: .45rem .6rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: .15rem;
            font-weight: 800;
            box-shadow: 0 6px 18px rgba(0, 0, 0, .28);
            border: 1px solid rgba(255, 255, 255, .22);
            line-height: 1;
        }

        .gal-more-overlay span {
            font-size: 1.1rem;
        }

        .gal-more-overlay small {
            font-size: .62rem;
            letter-spacing: .45px;
            opacity: .92;
        }

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

        @media(max-width:768px) {
            .bds-gallery {
                grid-template-columns: 1fr 1fr;
                grid-template-rows: 220px 120px;
            }

            .bds-gallery.count-1,
            .bds-gallery.count-2 {
                grid-template-columns: 1fr;
                grid-template-rows: 260px;
            }

            .bds-gallery .gal-main {
                grid-column: 1/3;
                grid-row: 1/2;
            }

            .bds-gallery.count-1 .gal-main,
            .bds-gallery.count-2 .gal-main {
                grid-column: 1/-1;
                grid-row: 1/-1;
            }

            .bds-gallery.count-2 .gal-sub-2 {
                display: none;
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

        .mo-ta-preview {
            max-height: 300px;
            overflow: hidden;
            position: relative;
            transition: max-height 0.4s ease-in-out;
        }

        .mo-ta-preview.expanded {
            max-height: none;
            transition: max-height 0.6s ease-in-out;
        }

        .mo-ta-fade {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 80px;
            background: linear-gradient(to bottom, transparent, #fff);
            pointer-events: none;
            display: block;
        }

        .mo-ta-preview.expanded .mo-ta-fade {
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

        /* NÚT ĐẶT LỊCH MỚI */
        .btn-dat-lich {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .6rem;
            width: 100%;
            padding: .85rem;
            margin-bottom: 1rem;
            border-radius: 12px;
            font-weight: 800;
            font-size: .95rem;
            background: linear-gradient(135deg, #10b981, #059669);
            color: #fff;
            border: none;
            box-shadow: 0 4px 14px rgba(16, 185, 129, .3);
            transition: all .2s;
            text-transform: uppercase;
            text-decoration: none;
        }

        .btn-dat-lich:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, .4);
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

        .btn-price-alert {
            grid-column: 1 / -1;
            background: linear-gradient(135deg, #fff7ed, #fff);
            border-color: #fdba74;
            color: #c2410c;
        }

        .btn-price-alert i {
            color: #f97316;
        }

        .btn-price-alert:hover {
            background: linear-gradient(135deg, #f97316, #ea580c);
            border-color: #ea580c;
            color: #fff;
        }

        .btn-price-alert:hover i {
            color: #fff;
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

        .lb-data .lb-caption {
            font-size: .82rem;
            font-weight: 600;
        }
    </style>
@endpush

@section('content')

    @php
        $customer = auth('customer')->user();
        $album = [];
        if (!empty($bds->album_anh)) {
            $raw = is_string($bds->album_anh) ? json_decode($bds->album_anh, true) : $bds->album_anh;
            $album = is_array($raw) ? $raw : [];
        }
        $default = asset('images/default-bds.jpg');
        $anhChinh = count($album) > 0 ? asset('storage/' . $album[0]) : $default;
        $anh2 = count($album) > 1 ? asset('storage/' . $album[1]) : null;
        $anh3 = count($album) > 2 ? asset('storage/' . $album[2]) : null;
        $anh4 = count($album) > 3 ? asset('storage/' . $album[3]) : null;
        $anh5 = count($album) > 4 ? asset('storage/' . $album[4]) : null;

        $galleryCount = max(1, min(5, count($album)));
        $extraCount = max(0, count($album) - 5);
        $galleryImages = count($album) > 0 ? array_map(fn($img) => asset('storage/' . $img), $album) : [$default];
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
                <div class="bds-gallery count-{{ $galleryCount }}">
                    {{-- Ảnh chính --}}
                    <a href="{{ $anhChinh }}" data-lightbox="bds-gallery" class="gal-item gal-main"
                        aria-label="Xem ảnh chính">
                        <img src="{{ $anhChinh }}" alt="{{ $bds->tieu_de }}" loading="eager">
                        <div class="gal-overlay"><i class="fas fa-search-plus"></i></div>
                    </a>

                    {{-- Ảnh phụ 2 --}}
                    @if ($anh2)
                        <a href="{{ $anh2 }}" data-lightbox="bds-gallery" class="gal-item gal-sub-2"
                            aria-label="Xem ảnh 2">
                            <img src="{{ $anh2 }}" alt="Ảnh 2" loading="lazy">
                            <div class="gal-overlay"><i class="fas fa-search-plus"></i></div>
                        </a>
                    @endif

                    {{-- Ảnh phụ 3 --}}
                    @if ($anh3)
                        <a href="{{ $anh3 }}" data-lightbox="bds-gallery" class="gal-item gal-sub-3"
                            aria-label="Xem ảnh 3">
                            <img src="{{ $anh3 }}" alt="Ảnh 3" loading="lazy">
                            <div class="gal-overlay"><i class="fas fa-search-plus"></i></div>
                        </a>
                    @endif
                    @if ($anh4)
                        <a href="{{ $anh4 }}" data-lightbox="bds-gallery" class="gal-item gal-sub-4"
                            aria-label="Xem ảnh 4">
                            <img src="{{ $anh4 }}" alt="Ảnh 4" loading="lazy">
                            <div class="gal-overlay"><i class="fas fa-search-plus"></i></div>
                        </a>
                    @endif
                    {{-- Ảnh phụ 4 — overlay "+N" --}}
                    @if ($anh5)
                        <a href="{{ $anh5 }}" data-lightbox="bds-gallery" class="gal-item gal-sub-5"
                            aria-label="Xem ảnh 5">
                            <img src="{{ $anh5 }}" alt="Ảnh 5" loading="lazy">
                            @if ($extraCount > 0)
                                <div class="gal-more-overlay">
                                    <span>+{{ $extraCount }}</span>
                                    <small>XEM THÊM</small>
                                </div>
                            @else
                                <div class="gal-overlay"><i class="fas fa-search-plus"></i></div>
                            @endif
                        </a>
                    @endif
                </div>

                @if (count($album) > 5)
                    @for ($i = 5; $i < count($album); $i++)
                        <a href="{{ asset('storage/' . $album[$i]) }}" data-lightbox="bds-gallery" class="d-none"
                            aria-hidden="true"></a>
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
                            {{ $bds->duAn->dia_chi }}
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
                                    <div class="spec-value">{{ $bds->so_phong_ngu ?? '—' }}</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="spec-item">
                                    <div class="spec-icon"><i class="fas fa-compass"></i></div>
                                    <div class="spec-label">Nội thất</div>
                                    <div class="spec-value">{{ $bds->noi_that ?? '-' }}</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="spec-item">
                                    <div class="spec-icon"><i class="fas fa-compass"></i></div>
                                    <div class="spec-label">Hướng ban công</div>
                                    <div class="spec-value">{{ $bds->huong_ban_cong ?? '-' }}</div>
                                </div>
                            </div>


                        </div>

                        <div class="info-row">
                            <div class="info-item">
                                <div class="info-item-icon"><i class="fas fa-building"></i></div>
                                <div class="info-item-text">
                                    <span class="lbl">Thuộc tòa</span>
                                    <span class="val">{{ $bds->toa ? 'Tòa ' . $bds->toa : 'Đang cập nhật' }}</span>
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="info-item-icon"><i class="fas fa-layer-group"></i></div>
                                <div class="info-item-text">
                                    <span class="lbl">Tầng</span>
                                    <span class="val">
                                        @if (!$bds->tang)
                                            Đang cập nhật
                                        @elseif ($bds->tang <= 7)
                                            Tầng thấp
                                        @elseif ($bds->tang <= 30)
                                            Tầng trung
                                        @else
                                            Tầng cao
                                        @endif
                                    </span>
                                </div>
                            </div>


                            @if ($bds->nhu_cau === 'ban')
                                @php
                                    $phapLyMap = [
                                        'so_hong' => 'Sổ hồng',
                                        'so_do' => 'Sổ đỏ',
                                        'hdmb' => 'Hợp đồng mua bán',
                                        'hop_dong_mua_ban' => 'Hợp đồng mua bán',
                                        'dang_cho_so' => 'Đang chờ sổ',
                                        'dang_cap_so' => 'Đang cấp sổ',
                                        'giay_tay' => 'Giấy tờ tay',
                                        'vi_bang' => 'Vi bằng',
                                        'thua_ke' => 'Thừa kế',
                                        'thoa_thuan' => 'Thỏa thuận',
                                    ];
                                    $phapLyLabel = $bds->phap_ly
                                        ? $phapLyMap[$bds->phap_ly] ?? $bds->phap_ly
                                        : 'Đang cập nhật';
                                @endphp
                                <div class="info-item">
                                    <div class="info-item-icon"><i class="fas fa-file-alt"></i></div>
                                    <div class="info-item-text">
                                        <span class="lbl">Pháp lý</span>
                                        <span class="val">{{ $phapLyLabel }}</span>
                                    </div>
                                </div>
                            @else
                                @php
                                    $hinhThucThanhToanMap = [
                                        'thang_1' => 'Thanh toán 1 tháng/lần',
                                        'thang_3' => 'Thanh toán 3 tháng/lần',
                                        'thang_6' => 'Thanh toán 6 tháng/lần',
                                        'nam_1' => 'Thanh toán 1 năm/lần',
                                    ];
                                    $hinhThucThanhToanLabel = $bds->hinh_thuc_thanh_toan
                                        ? $hinhThucThanhToanMap[$bds->hinh_thuc_thanh_toan] ??
                                            $bds->hinh_thuc_thanh_toan
                                        : 'Đang cập nhật';
                                @endphp
                                <div class="info-item">
                                    <div class="info-item-icon"><i class="fas fa-wallet"></i></div>
                                    <div class="info-item-text">
                                        <span class="lbl">Hình thức thanh toán</span>
                                        <span class="val">{{ $hinhThucThanhToanLabel }}</span>
                                    </div>
                                </div>
                            @endif






                            @if ($bds->duAn)
                                <div class="info-item">
                                    <div class="info-item-icon"><i class="fas fa-city"></i></div>
                                    <div class="info-item-text">
                                        <span class="lbl">Dự án</span>
                                        <a href="{{ route('frontend.du-an.show', $bds->duAn->slug) }}"
                                            class="val text-decoration-none" style="color:#FF8C42;">
                                            {{ $bds->duAn->ten_du_an }}
                                        </a>
                                    </div>
                                </div>
                            @endif
                            <div class="info-item">
                                <div class="info-item-icon"><i class="fas fa-hashtag"></i></div>
                                <div class="info-item-text">
                                    <span class="lbl">Mã BĐS</span>
                                    <span class="val">{{ $bds->ma_bat_dong_san ?? 'BĐS#' . $bds->id }}</span>
                                </div>
                            </div>
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
                            <div class="mo-ta-fade" id="motaFade"></div>
                        </div>
                        <button class="btn-read-more" id="btnReadMore" type="button" onclick="toggleMoTa()">
                            <i class="fas fa-chevron-down" id="iconReadMore"></i>
                            <span id="txtReadMore">Xem thêm nội dung</span>
                        </button>
                    </div>

                    @if ($bds->nhu_cau === 'ban')
                        @include('frontend.partials.cong-cu-tai-chinh')
                    @endif
                </div>

                {{-- ══ CỘT PHẢI — SIDEBAR ══ --}}
                <div class="col-lg-4">
                    <div class="sidebar-sticky">


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
                                <a href="tel:0336 123 130" class="btn-hotline">
                                    <i class="fas fa-phone-alt"></i> 0336 123 130
                                </a>

                                {{-- Zalo --}}
                                <a href="https://zalo.me/0336 123 130" target="_blank" class="btn-zalo">
                                    <i class="fas fa-comment-dots"></i> Chat Zalo ngay
                                </a>

                                {{-- Nút Đặt Lịch Xem Nhà (MỚI) --}}
                                <button type="button" class="btn-dat-lich" data-bs-toggle="modal"
                                    data-bs-target="#modalDatLich">
                                    <i class="fas fa-calendar-check"></i> Đặt lịch xem nhà
                                </button>

                                {{-- Form gọi lại --}}
                                <div class="callback-form-title">
                                    <i class="fas fa-phone-volume"></i> Yêu cầu tư vấn
                                </div>
                                <form id="formCallBack" method="POST" action="{{ route('frontend.lien-he.store') }}"
                                    onsubmit="guiYeuCauGoiLai(event)">
                                    @csrf
                                    <input type="hidden" name="bat_dong_san_id" value="{{ $bds->id }}">
                                    <input type="hidden" name="email" value="{{ $customer?->email ?? '' }}">
                                    <div style="margin-bottom:.6rem;">
                                        <input type="text" class="form-input-custom" name="ho_ten"
                                            value="{{ $customer?->ho_ten ?? '' }}" placeholder="Họ và tên của bạn *"
                                            required>
                                    </div>
                                    <div style="margin-bottom:.6rem;">
                                        <input type="tel" class="form-input-custom" name="so_dien_thoai"
                                            value="{{ $customer?->so_dien_thoai ?? '' }}"
                                            placeholder="Số điện thoại của bạn *" required>
                                    </div>
                                    <div style="margin-bottom:.75rem;">
                                        <textarea class="form-input-custom" name="noi_dung" rows="2" placeholder="Nội dung cần tư vấn..."
                                            style="resize:none;">Tôi quan tâm đến BĐS: {{ $bds->tieu_de }}</textarea>
                                    </div>
                                    <button type="submit" class="btn-callback" id="btnCallBack">
                                        <i class="fas fa-paper-plane"></i> Gửi Yêu Cầu
                                    </button>
                                    <div class="mt-2 small fw-semibold d-none" id="callBackError"></div>
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
                                onclick="addSoSanh({{ $bds->id }}, @js($bds->tieu_de))">
                                <i class="fas fa-balance-scale"></i> So sánh
                            </button>
                            <button class="btn-action btn-price-alert" onclick="dangKyCanhBaoGia()">
                                <i class="fas fa-bell"></i> Nhận thông báo thay đổi giá
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
            @else
                <div class="related-section"
                    style="background: #fff; border-radius: 16px; padding: 2rem 1.8rem; text-align: center;">
                    <h3 class="section-title mb-3">Bất Động Sản Tương Tự</h3>
                    <p style="color: #9ca3af; font-size: 0.95rem; margin: 0;">
                        <i class="fas fa-inbox me-2" style="color: #FF8C42;"></i>
                        Không có bất động sản nào tương tự
                    </p>
                </div>
            @endif

        </div>{{-- end container --}}
    </div>{{-- end page --}}

    @include('frontend.partials.goi-y-bds')

    <div class="modal fade" id="modalDatLich" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
                <div class="modal-header border-0"
                    style="background: linear-gradient(135deg, #10b981, #059669); color: white;">
                    <h5 class="modal-title fw-bold"><i class="fas fa-home me-2"></i> Đặt lịch xem Bất động sản</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <form id="frmDatLich" action="{{ route('frontend.dat-lich') }}" method="POST">
                    @csrf
                    <input type="hidden" name="bat_dong_san_id" value="{{ $bds->id }}">

                    <div class="modal-body p-4">
                        <div class="alert alert-light border border-success-subtle small mb-4 text-dark">
                            Quý khách đang đặt lịch xem: <strong class="text-success">{{ $bds->tieu_de }}</strong>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold" style="font-size: 0.9rem;">Họ và tên <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="ten_khach_hang" class="form-input-custom"
                                value="{{ $customer?->ho_ten ?? '' }}" placeholder="Nhập tên của bạn" required>
                        </div>

                        <div class="row mb-3 g-3">
                            <div class="col-sm-6">
                                <label class="form-label fw-bold" style="font-size: 0.9rem;">Số điện thoại <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="sdt_khach_hang" class="form-input-custom"
                                    value="{{ $customer?->so_dien_thoai ?? '' }}" placeholder="09xx..." required>
                                <input type="hidden" name="email_khach_hang" value="{{ $customer?->email ?? '' }}">
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label fw-bold" style="font-size: 0.9rem;">Thời gian xem <span
                                        class="text-danger">*</span></label>
                                <input type="datetime-local" name="thoi_gian_hen" class="form-input-custom"
                                    min="{{ now()->format('Y-m-d\TH:i') }}" required>
                            </div>
                        </div>

                        <div class="mb-0">
                            <label class="form-label fw-bold" style="font-size: 0.9rem;">Lời nhắn (Không bắt buộc)</label>
                            <textarea name="ghi_chu" class="form-input-custom" rows="3"
                                placeholder="Ví dụ: Tôi chỉ xem được ngoài giờ hành chính..."></textarea>
                        </div>
                    </div>

                    <div class="modal-footer bg-light border-0">
                        <div class="small fw-semibold text-danger me-auto d-none" id="datLichError"></div>
                        <button type="button" class="btn btn-light border fw-bold" data-bs-dismiss="modal"
                            style="border-radius: 10px;">Hủy</button>
                        <button type="submit" class="btn btn-success fw-bold" id="btnSubmitDatLich"
                            style="border-radius: 10px; background: #10b981; border: none; padding: 0.5rem 1.5rem;">
                            <i class="fas fa-paper-plane me-1"></i> GỬI YÊU CẦU
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox-plus-jquery.min.js"></script>
    <script>
        if (typeof lightbox !== 'undefined') {
            lightbox.option({
                resizeDuration: 180,
                wrapAround: true,
                albumLabel: 'Ảnh %1 / %2'
            });
        }

        window.BDS_SHOW = {
            bdsId: {{ $bds->id }},
            title: @json($bds->tieu_de),
            nhuCau: @json($bds->nhu_cau),
            khuVucId: {{ $bds->duAn->khu_vuc_id ?? 'null' }},
            duAnId: {{ $bds->du_an_id ?? 'null' }},
            soPhongNgu: {{ $bds->so_phong_ngu ?? 'null' }},
            csrfToken: @json(csrf_token()),
            isCustomerLoggedIn: {{ $customer ? 'true' : 'false' }},
            routes: {
                lienHeStore: @json(route('frontend.lien-he.store')),
                trackTime: @json(route('frontend.bds.track-time'))
            }
        };
    </script>
    @vite('resources/js/pages/bds-show.js')
@endpush
