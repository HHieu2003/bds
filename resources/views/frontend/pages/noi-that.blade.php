@extends('frontend.layouts.master')

@section('title', 'Nội thất Thành Công Land — Thiết kế & Thi công nội thất cao cấp')
@section('meta_description',
    'Đơn vị thiết kế và thi công nội thất uy tín tại Hà Nội. Chuyên biệt căn hộ Vinhomes Smart
    City với phong cách hiện đại, tối giản và sang trọng.')

@section('breadcrumb')
    <li class="breadcrumb-item active">Nội thất</li>
@endsection

@push('styles')
    <style>
        /* ══ HERO ══ */
        .nt-hero {
            position: relative;
            min-height: 540px;
            background: linear-gradient(135deg, #1a3c5e 0%, #0f2338 50%, #1a2a1a 100%);
            display: flex;
            align-items: center;
            overflow: hidden;
        }

        .nt-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 60% 80% at 70% 50%, rgba(255, 140, 66, .18) 0%, transparent 70%),
                radial-gradient(ellipse 40% 60% at 20% 80%, rgba(45, 106, 159, .25) 0%, transparent 60%);
        }

        .nt-hero-grid {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255, 255, 255, .03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, .03) 1px, transparent 1px);
            background-size: 60px 60px;
        }

        .nt-hero-content {
            position: relative;
            z-index: 2;
            max-width: 620px;
        }

        .nt-hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: rgba(255, 140, 66, .15);
            border: 1px solid rgba(255, 140, 66, .35);
            color: #FF8C42;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: .78rem;
            font-weight: 700;
            letter-spacing: .5px;
            text-transform: uppercase;
            margin-bottom: 20px;
        }

        .nt-hero h1 {
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 900;
            color: #fff;
            line-height: 1.15;
            margin: 0 0 18px;
        }

        .nt-hero h1 span {
            color: #FF8C42;
        }

        .nt-hero p {
            color: rgba(255, 255, 255, .75);
            font-size: 1.05rem;
            line-height: 1.7;
            margin: 0 0 32px;
        }

        .nt-hero-btns {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .nt-btn-primary {
            background: linear-gradient(135deg, #FF8C42, #FF6B1A);
            color: #fff;
            border: none;
            padding: 14px 28px;
            border-radius: 12px;
            font-weight: 800;
            font-size: .95rem;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 6px 24px rgba(255, 140, 66, .4);
            transition: transform .2s, box-shadow .2s;
        }

        .nt-btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(255, 140, 66, .5);
            color: #fff;
        }

        .nt-btn-outline {
            background: rgba(255, 255, 255, .08);
            color: #fff;
            border: 1.5px solid rgba(255, 255, 255, .25);
            padding: 13px 24px;
            border-radius: 12px;
            font-weight: 700;
            font-size: .95rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: background .2s, border-color .2s;
        }

        .nt-btn-outline:hover {
            background: rgba(255, 255, 255, .15);
            border-color: rgba(255, 255, 255, .5);
            color: #fff;
        }

        /* Stats ngang */
        .nt-hero-stats {
            position: relative;
            z-index: 2;
            display: flex;
            gap: 0;
            background: rgba(255, 255, 255, .06);
            border: 1px solid rgba(255, 255, 255, .1);
            border-radius: 16px;
            backdrop-filter: blur(8px);
            overflow: hidden;
            margin-top: 48px;
        }

        .nt-stat-item {
            flex: 1;
            padding: 20px 16px;
            text-align: center;
            border-right: 1px solid rgba(255, 255, 255, .08);
        }

        .nt-stat-item:last-child {
            border-right: none;
        }

        .nt-stat-num {
            font-size: 1.8rem;
            font-weight: 900;
            color: #FF8C42;
            line-height: 1;
            margin-bottom: 4px;
        }

        .nt-stat-label {
            font-size: .75rem;
            color: rgba(255, 255, 255, .6);
            font-weight: 500;
        }

        /* ══ SECTION BASE ══ */
        .nt-section {
            padding: 72px 0;
        }

        .nt-section-alt {
            background: #fdf9f6;
        }

        .nt-section-dark {
            background: linear-gradient(135deg, #1a3c5e, #0f2338);
            color: #fff;
        }

        .nt-label {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #fff5ef;
            color: #FF8C42;
            border: 1px solid #ffd4b0;
            padding: 5px 14px;
            border-radius: 20px;
            font-size: .75rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .6px;
            margin-bottom: 12px;
        }

        .nt-section-dark .nt-label {
            background: rgba(255, 140, 66, .15);
            border-color: rgba(255, 140, 66, .3);
        }

        .nt-title {
            font-size: clamp(1.6rem, 3vw, 2.2rem);
            font-weight: 900;
            color: #1a3c5e;
            line-height: 1.25;
            margin: 0 0 12px;
        }

        .nt-section-dark .nt-title {
            color: #fff;
        }

        .nt-subtitle {
            font-size: 1rem;
            color: #6b7280;
            line-height: 1.7;
            max-width: 560px;
        }

        .nt-section-dark .nt-subtitle {
            color: rgba(255, 255, 255, .65);
        }

        /* ══ DỊCH VỤ CARDS ══ */
        .nt-service-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 40px;
        }

        .nt-service-card {
            background: #fff;
            border-radius: 18px;
            padding: 28px 24px;
            border: 1.5px solid #f0f2f5;
            transition: transform .25s, box-shadow .25s, border-color .25s;
            position: relative;
            overflow: hidden;
        }

        .nt-service-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #FF8C42, #FF6B1A);
            transform: scaleX(0);
            transition: transform .3s;
            transform-origin: left;
        }

        .nt-service-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 48px rgba(0, 0, 0, .1);
            border-color: #ffe0c8;
        }

        .nt-service-card:hover::before {
            transform: scaleX(1);
        }

        .nt-service-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            margin-bottom: 16px;
        }

        .nt-service-card h3 {
            font-size: 1rem;
            font-weight: 800;
            color: #1a3c5e;
            margin: 0 0 8px;
        }

        .nt-service-card p {
            font-size: .875rem;
            color: #6b7280;
            line-height: 1.65;
            margin: 0;
        }

        .nt-service-tag {
            display: inline-block;
            margin-top: 14px;
            font-size: .72rem;
            font-weight: 700;
            color: #FF8C42;
            background: #fff5ef;
            padding: 3px 10px;
            border-radius: 20px;
        }

        /* ══ PHONG CÁCH ══ */
        .nt-style-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-top: 40px;
        }

        .nt-style-card {
            border-radius: 16px;
            overflow: hidden;
            position: relative;
            aspect-ratio: 3/4;
            cursor: pointer;
        }

        .nt-style-bg {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: flex-end;
            transition: transform .4s;
        }

        .nt-style-card:hover .nt-style-bg {
            transform: scale(1.05);
        }

        .nt-style-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, .75) 0%, rgba(0, 0, 0, .1) 50%, transparent 100%);
        }

        .nt-style-info {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 20px 16px;
            color: #fff;
        }

        .nt-style-info h4 {
            font-size: .95rem;
            font-weight: 800;
            margin: 0 0 4px;
        }

        .nt-style-info p {
            font-size: .75rem;
            opacity: .8;
            margin: 0;
        }

        .nt-style-badge {
            position: absolute;
            top: 12px;
            left: 12px;
            background: rgba(255, 255, 255, .15);
            backdrop-filter: blur(6px);
            border: 1px solid rgba(255, 255, 255, .25);
            color: #fff;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: .7rem;
            font-weight: 700;
        }

        /* ══ QUY TRÌNH ══ */
        .nt-process-wrap {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 0;
            margin-top: 48px;
            position: relative;
        }

        .nt-process-wrap::before {
            content: '';
            position: absolute;
            top: 28px;
            left: 10%;
            right: 10%;
            height: 2px;
            background: linear-gradient(90deg, #FF8C42, #2d6a9f);
            z-index: 0;
        }

        .nt-process-step {
            text-align: center;
            position: relative;
            z-index: 1;
            padding: 0 8px;
        }

        .nt-step-num {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: #fff;
            border: 3px solid #FF8C42;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            font-size: 1.1rem;
            font-weight: 900;
            color: #FF8C42;
            box-shadow: 0 4px 16px rgba(255, 140, 66, .25);
        }

        .nt-section-dark .nt-step-num {
            background: rgba(255, 255, 255, .08);
        }

        .nt-process-step h4 {
            font-size: .88rem;
            font-weight: 800;
            color: #fff;
            margin: 0 0 6px;
        }

        .nt-process-step p {
            font-size: .75rem;
            color: rgba(255, 255, 255, .6);
            margin: 0;
            line-height: 1.5;
        }

        /* ══ GÓI THIẾT KẾ ══ */
        .nt-pkg-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 40px;
        }

        .nt-pkg-card {
            background: #fff;
            border-radius: 20px;
            border: 2px solid #f0f2f5;
            overflow: hidden;
            transition: transform .25s, box-shadow .25s;
        }

        .nt-pkg-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 48px rgba(0, 0, 0, .1);
        }

        .nt-pkg-card.featured {
            border-color: #FF8C42;
            box-shadow: 0 8px 32px rgba(255, 140, 66, .2);
            transform: scale(1.02);
        }

        .nt-pkg-card.featured:hover {
            transform: scale(1.02) translateY(-4px);
        }

        .nt-pkg-header {
            padding: 24px 24px 20px;
        }

        .nt-pkg-badge {
            display: inline-block;
            background: #FF8C42;
            color: #fff;
            font-size: .65rem;
            font-weight: 800;
            padding: 3px 10px;
            border-radius: 20px;
            text-transform: uppercase;
            letter-spacing: .5px;
            margin-bottom: 10px;
        }

        .nt-pkg-card:not(.featured) .nt-pkg-badge {
            background: #f0f2f5;
            color: #888;
        }

        .nt-pkg-header h3 {
            font-size: 1.1rem;
            font-weight: 900;
            color: #1a3c5e;
            margin: 0 0 6px;
        }

        .nt-pkg-header p {
            font-size: .82rem;
            color: #9ca3af;
            margin: 0;
        }

        .nt-pkg-price {
            padding: 16px 24px;
            background: #fdf9f6;
            border-top: 1px solid #f5f0ec;
            border-bottom: 1px solid #f5f0ec;
        }

        .nt-pkg-card.featured .nt-pkg-price {
            background: #fff8f3;
        }

        .nt-pkg-price-num {
            font-size: 1.6rem;
            font-weight: 900;
            color: #FF8C42;
            line-height: 1;
        }

        .nt-pkg-price-unit {
            font-size: .78rem;
            color: #aaa;
            margin-top: 2px;
        }

        .nt-pkg-body {
            padding: 20px 24px 24px;
        }

        .nt-pkg-features {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .nt-pkg-features li {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            font-size: .875rem;
            color: #444;
            padding: 7px 0;
            border-bottom: 1px solid #f5f5f5;
        }

        .nt-pkg-features li:last-child {
            border-bottom: none;
        }

        .nt-pkg-features li i {
            color: #FF8C42;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .nt-pkg-features li.off {
            color: #ccc;
        }

        .nt-pkg-features li.off i {
            color: #e5e7eb;
        }

        .nt-pkg-footer {
            padding: 0 24px 24px;
        }

        .nt-pkg-btn {
            display: block;
            text-align: center;
            padding: 12px;
            border-radius: 12px;
            font-weight: 800;
            font-size: .9rem;
            text-decoration: none;
            transition: all .2s;
        }

        .nt-pkg-btn-outline {
            border: 2px solid #1a3c5e;
            color: #1a3c5e;
            background: transparent;
        }

        .nt-pkg-btn-outline:hover {
            background: #1a3c5e;
            color: #fff;
        }

        .nt-pkg-btn-fill {
            background: linear-gradient(135deg, #FF8C42, #FF6B1A);
            color: #fff;
            border: none;
            box-shadow: 0 4px 16px rgba(255, 140, 66, .3);
        }

        .nt-pkg-btn-fill:hover {
            box-shadow: 0 6px 24px rgba(255, 140, 66, .5);
            color: #fff;
            transform: translateY(-1px);
        }

        /* ══ VÌ SAO CHỌN ══ */
        .nt-why-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
            margin-top: 40px;
        }

        .nt-why-item {
            display: flex;
            align-items: flex-start;
            gap: 16px;
            background: rgba(255, 255, 255, .06);
            border: 1px solid rgba(255, 255, 255, .1);
            border-radius: 14px;
            padding: 20px;
            transition: background .2s;
        }

        .nt-why-item:hover {
            background: rgba(255, 255, 255, .1);
        }

        .nt-why-icon {
            width: 44px;
            height: 44px;
            flex-shrink: 0;
            border-radius: 12px;
            background: rgba(255, 140, 66, .15);
            border: 1px solid rgba(255, 140, 66, .25);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            color: #FF8C42;
        }

        .nt-why-item h4 {
            font-size: .9rem;
            font-weight: 800;
            color: #fff;
            margin: 0 0 5px;
        }

        .nt-why-item p {
            font-size: .82rem;
            color: rgba(255, 255, 255, .6);
            margin: 0;
            line-height: 1.6;
        }

        /* ══ DỰ ÁN THỰC TẾ ══ */
        .nt-project-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 40px;
        }

        .nt-project-card {
            border-radius: 16px;
            overflow: hidden;
            background: #fff;
            border: 1.5px solid #f0f2f5;
            transition: transform .25s, box-shadow .25s;
        }

        .nt-project-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 16px 40px rgba(0, 0, 0, .1);
        }

        .nt-project-thumb {
            aspect-ratio: 4/3;
            position: relative;
            overflow: hidden;
        }

        .nt-project-thumb-bg {
            width: 100%;
            height: 100%;
            transition: transform .4s;
        }

        .nt-project-card:hover .nt-project-thumb-bg {
            transform: scale(1.07);
        }

        .nt-project-thumb-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, .55) 0%, transparent 60%);
            opacity: 0;
            transition: opacity .3s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .nt-project-card:hover .nt-project-thumb-overlay {
            opacity: 1;
        }

        .nt-project-view {
            background: rgba(255, 255, 255, .95);
            color: #1a3c5e;
            padding: 8px 18px;
            border-radius: 20px;
            font-weight: 800;
            font-size: .82rem;
        }

        .nt-project-body {
            padding: 16px;
        }

        .nt-project-body h4 {
            font-size: .95rem;
            font-weight: 800;
            color: #1a3c5e;
            margin: 0 0 6px;
        }

        .nt-project-meta {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            font-size: .78rem;
            color: #9ca3af;
        }

        .nt-project-meta span {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .nt-project-meta i {
            color: #FF8C42;
            font-size: .72rem;
        }

        /* ══ FORM TƯ VẤN ══ */
        .nt-contact-wrap {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 48px;
            align-items: center;
        }

        .nt-form-card {
            background: #fff;
            border-radius: 20px;
            padding: 32px;
            box-shadow: 0 16px 48px rgba(0, 0, 0, .1);
        }

        .nt-form-title {
            font-size: 1.3rem;
            font-weight: 900;
            color: #1a3c5e;
            margin: 0 0 6px;
        }

        .nt-form-subtitle {
            font-size: .875rem;
            color: #9ca3af;
            margin: 0 0 24px;
        }

        .nt-field {
            margin-bottom: 14px;
        }

        .nt-field label {
            display: block;
            font-size: .78rem;
            font-weight: 700;
            color: #374151;
            margin-bottom: 6px;
        }

        .nt-field input,
        .nt-field select,
        .nt-field textarea {
            width: 100%;
            padding: 11px 14px;
            border: 1.5px solid #e8e8e8;
            border-radius: 10px;
            font-size: .875rem;
            outline: none;
            font-family: inherit;
            transition: border-color .2s, box-shadow .2s;
            box-sizing: border-box;
        }

        .nt-field input:focus,
        .nt-field select:focus,
        .nt-field textarea:focus {
            border-color: #FF8C42;
            box-shadow: 0 0 0 3px rgba(255, 140, 66, .12);
        }

        .nt-field textarea {
            resize: vertical;
            min-height: 100px;
        }

        .nt-field-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .nt-form-submit {
            width: 100%;
            background: linear-gradient(135deg, #FF8C42, #FF6B1A);
            color: #fff;
            border: none;
            padding: 14px;
            border-radius: 12px;
            font-weight: 800;
            font-size: .95rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 6px 20px rgba(255, 140, 66, .35);
            transition: transform .2s, box-shadow .2s;
            font-family: inherit;
            margin-top: 4px;
        }

        .nt-form-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 28px rgba(255, 140, 66, .45);
        }

        /* ══ TESTIMONIALS ══ */
        .nt-reviews-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 40px;
        }

        .nt-review-card {
            background: #fff;
            border-radius: 16px;
            padding: 24px;
            border: 1.5px solid #f0f2f5;
            position: relative;
        }

        .nt-review-quote {
            position: absolute;
            top: 18px;
            right: 20px;
            font-size: 2.5rem;
            color: #ffe0c8;
            line-height: 1;
            font-family: Georgia, serif;
        }

        .nt-review-stars {
            color: #f59e0b;
            font-size: .85rem;
            margin-bottom: 12px;
        }

        .nt-review-text {
            font-size: .88rem;
            color: #555;
            line-height: 1.7;
            margin: 0 0 16px;
            font-style: italic;
        }

        .nt-review-author {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .nt-review-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: .9rem;
            color: #fff;
            flex-shrink: 0;
        }

        .nt-review-name {
            font-size: .875rem;
            font-weight: 700;
            color: #1a3c5e;
        }

        .nt-review-project {
            font-size: .75rem;
            color: #9ca3af;
        }

        /* ══ RESPONSIVE ══ */
        @media (max-width: 991px) {

            .nt-service-grid,
            .nt-pkg-grid,
            .nt-project-grid,
            .nt-reviews-grid {
                grid-template-columns: 1fr 1fr;
            }

            .nt-style-grid {
                grid-template-columns: 1fr 1fr;
            }

            .nt-process-wrap {
                grid-template-columns: 1fr 1fr 1fr;
            }

            .nt-process-wrap::before {
                display: none;
            }

            .nt-contact-wrap {
                grid-template-columns: 1fr;
            }

            .nt-why-grid {
                grid-template-columns: 1fr;
            }

            .nt-pkg-card.featured {
                transform: none;
            }
        }

        @media (max-width: 576px) {

            .nt-service-grid,
            .nt-pkg-grid,
            .nt-project-grid,
            .nt-reviews-grid,
            .nt-style-grid,
            .nt-process-wrap {
                grid-template-columns: 1fr;
            }

            .nt-hero {
                min-height: auto;
                padding: 60px 0;
            }

            .nt-hero-stats {
                flex-wrap: wrap;
            }

            .nt-hero-stats .nt-stat-item {
                min-width: 50%;
            }

            .nt-field-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')

    {{-- ══════════════════════════════════════════════════
    HERO
══════════════════════════════════════════════════ --}}
    <section class="nt-hero">
        <div class="nt-hero-grid"></div>
        <div class="container py-5 py-lg-0">
            <div class="row align-items-center" style="min-height:520px">
                <div class="col-lg-6">
                    <div class="nt-hero-content">
                        <div class="nt-hero-badge">
                            <i class="fas fa-award"></i> Đơn vị nội thất uy tín
                        </div>
                        <h1>
                            Không gian sống<br>
                            <span>đẳng cấp</span> từ<br>
                            Thành Công Land
                        </h1>
                        <p>
                            Chúng tôi mang đến giải pháp thiết kế và thi công nội thất toàn diện
                            cho căn hộ Vinhomes Smart City — từ bản vẽ đến bàn giao,
                            đảm bảo thẩm mỹ, công năng và tiến độ.
                        </p>
                        <div class="nt-hero-btns">
                            <a href="#tuvan" class="nt-btn-primary">
                                <i class="fas fa-comments"></i> Tư vấn miễn phí
                            </a>
                            <a href="#du-an" class="nt-btn-outline">
                                <i class="fas fa-images"></i> Xem dự án thực tế
                            </a>
                        </div>
                    </div>

                    <div class="nt-hero-stats">
                        <div class="nt-stat-item">
                            <div class="nt-stat-num">200+</div>
                            <div class="nt-stat-label">Dự án hoàn thành</div>
                        </div>
                        <div class="nt-stat-item">
                            <div class="nt-stat-num">5★</div>
                            <div class="nt-stat-label">Đánh giá khách hàng</div>
                        </div>
                        <div class="nt-stat-item">
                            <div class="nt-stat-num">8+</div>
                            <div class="nt-stat-label">Năm kinh nghiệm</div>
                        </div>
                        <div class="nt-stat-item">
                            <div class="nt-stat-num">100%</div>
                            <div class="nt-stat-label">Đúng tiến độ</div>
                        </div>
                    </div>
                </div>

                {{-- Ảnh phải --}}
                <div class="col-lg-6 d-none d-lg-flex justify-content-end" style="position:relative;z-index:2">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;max-width:480px">
                        {{-- Ảnh lớn --}}
                        <div
                            style="border-radius:20px;overflow:hidden;aspect-ratio:3/4;grid-row:span 2;
                                background:linear-gradient(160deg,#2d3a2e,#1a2a1a);
                                display:flex;align-items:center;justify-content:center;
                                border:1px solid rgba(255,255,255,.1)">
                            <div style="text-align:center;color:rgba(255,255,255,.3)">
                                <i class="fas fa-couch" style="font-size:3rem;display:block;margin-bottom:8px"></i>
                                <span style="font-size:.75rem">Phòng khách</span>
                            </div>
                        </div>
                        <div
                            style="border-radius:16px;overflow:hidden;aspect-ratio:4/3;
                                background:linear-gradient(160deg,#2a2a3e,#1a1a2e);
                                display:flex;align-items:center;justify-content:center;
                                border:1px solid rgba(255,255,255,.1)">
                            <div style="text-align:center;color:rgba(255,255,255,.3)">
                                <i class="fas fa-bed" style="font-size:2rem;display:block;margin-bottom:6px"></i>
                                <span style="font-size:.7rem">Phòng ngủ</span>
                            </div>
                        </div>
                        <div
                            style="border-radius:16px;overflow:hidden;aspect-ratio:4/3;
                                background:linear-gradient(160deg,#3e2a20,#2e1a10);
                                display:flex;align-items:center;justify-content:center;
                                border:1px solid rgba(255,255,255,.1)">
                            <div style="text-align:center;color:rgba(255,255,255,.3)">
                                <i class="fas fa-utensils" style="font-size:2rem;display:block;margin-bottom:6px"></i>
                                <span style="font-size:.7rem">Bếp & Bàn ăn</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════════════════
    DỊCH VỤ
══════════════════════════════════════════════════ --}}
    <section class="nt-section nt-section-alt" id="dich-vu">
        <div class="container">
            <div class="text-center">
                <div class="nt-label"><i class="fas fa-star"></i> Dịch vụ của chúng tôi</div>
                <h2 class="nt-title">Giải pháp nội thất <span style="color:#FF8C42">toàn diện</span></h2>
                <p class="nt-subtitle mx-auto">Từ tư vấn ý tưởng đến thi công hoàn thiện, chúng tôi đồng hành
                    cùng bạn trong từng bước tạo nên tổ ấm mơ ước.</p>
            </div>

            <div class="nt-service-grid">
                {{-- Thiết kế nội thất --}}
                <div class="nt-service-card">
                    <div class="nt-service-icon" style="background:#fff5ef;color:#FF8C42">
                        <i class="fas fa-pencil-ruler"></i>
                    </div>
                    <h3>Thiết kế nội thất</h3>
                    <p>Đội ngũ kiến trúc sư và nhà thiết kế giàu kinh nghiệm sẽ biến ý tưởng của bạn
                        thành bản vẽ 3D chi tiết, sống động trước khi thi công.</p>
                    <span class="nt-service-tag">Bản vẽ 3D · 2D · Phối cảnh</span>
                </div>

                {{-- Thi công --}}
                <div class="nt-service-card">
                    <div class="nt-service-icon" style="background:#f0f7ff;color:#2d6a9f">
                        <i class="fas fa-hard-hat"></i>
                    </div>
                    <h3>Thi công trọn gói</h3>
                    <p>Đội thợ lành nghề, vật liệu chính hãng, tiến độ cam kết. Chúng tôi quản lý
                        toàn bộ quá trình thi công từ A đến Z, không phát sinh chi phí ẩn.</p>
                    <span class="nt-service-tag" style="color:#2d6a9f;background:#f0f7ff">Cam kết tiến độ · Giá cố
                        định</span>
                </div>

                {{-- Tư vấn phong cách --}}
                <div class="nt-service-card">
                    <div class="nt-service-icon" style="background:#f5f0ff;color:#8b5cf6">
                        <i class="fas fa-palette"></i>
                    </div>
                    <h3>Tư vấn phong cách</h3>
                    <p>Không biết chọn phong cách nào? Chuyên viên tư vấn sẽ gợi ý phong cách
                        phù hợp nhất với sở thích, diện tích và ngân sách của bạn.</p>
                    <span class="nt-service-tag" style="color:#8b5cf6;background:#f5f0ff">Miễn phí tư vấn</span>
                </div>

                {{-- Mua sắm nội thất --}}
                <div class="nt-service-card">
                    <div class="nt-service-icon" style="background:#f0fff4;color:#27ae60">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                    <h3>Cung cấp nội thất</h3>
                    <p>Hệ thống đối tác với các thương hiệu nội thất hàng đầu: IKEA, JYSK,
                        AA Corporation, Hòa Phát... đảm bảo chất lượng với giá tốt nhất.</p>
                    <span class="nt-service-tag" style="color:#27ae60;background:#f0fff4">Giá đại lý · Bảo hành chính
                        hãng</span>
                </div>

                {{-- Cải tạo căn hộ --}}
                <div class="nt-service-card">
                    <div class="nt-service-icon" style="background:#fff8e1;color:#f59e0b">
                        <i class="fas fa-tools"></i>
                    </div>
                    <h3>Cải tạo & Nâng cấp</h3>
                    <p>Căn hộ cũ muốn làm mới? Chúng tôi nhận cải tạo, thay thế nội thất
                        và nâng cấp không gian sống theo xu hướng hiện đại nhất.</p>
                    <span class="nt-service-tag" style="color:#f59e0b;background:#fff8e1">Giữ nguyên kết cấu · Nhanh
                        chóng</span>
                </div>

                {{-- Bảo trì --}}
                <div class="nt-service-card">
                    <div class="nt-service-icon" style="background:#fef2f2;color:#e74c3c">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>Bảo hành & Bảo trì</h3>
                    <p>Tất cả công trình được bảo hành lên đến 36 tháng. Đội ngũ kỹ thuật
                        sẵn sàng xử lý sự cố trong vòng 24 giờ, kể cả ngày lễ.</p>
                    <span class="nt-service-tag" style="color:#e74c3c;background:#fef2f2">Bảo hành 36 tháng · 24/7</span>
                </div>
            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════════════════
    PHONG CÁCH THIẾT KẾ
══════════════════════════════════════════════════ --}}
    <section class="nt-section" id="phong-cach">
        <div class="container">
            <div class="text-center">
                <div class="nt-label"><i class="fas fa-paint-brush"></i> Phong cách</div>
                <h2 class="nt-title">Đa dạng phong cách <span style="color:#FF8C42">thiết kế</span></h2>
                <p class="nt-subtitle mx-auto">Chúng tôi thành thạo nhiều phong cách thiết kế khác nhau,
                    đảm bảo không gian sống thể hiện đúng cá tính của gia chủ.</p>
            </div>

            <div class="nt-style-grid">
                {{-- Modern --}}
                <div class="nt-style-card">
                    <div class="nt-style-bg"
                        style="background:linear-gradient(160deg,#1a1a2e,#16213e,#0f3460);align-items:flex-end">
                        <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center">
                            <i class="fas fa-building" style="font-size:3.5rem;color:rgba(255,255,255,.15)"></i>
                        </div>
                    </div>
                    <div class="nt-style-overlay"></div>
                    <div class="nt-style-badge">Phổ biến nhất</div>
                    <div class="nt-style-info">
                        <h4>Hiện đại</h4>
                        <p>Đường nét sạch, tối giản, tiện nghi</p>
                    </div>
                </div>

                {{-- Scandinavian --}}
                <div class="nt-style-card">
                    <div class="nt-style-bg" style="background:linear-gradient(160deg,#e8e0d4,#d4c8b8,#c8b89a)">
                        <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center">
                            <i class="fas fa-leaf" style="font-size:3.5rem;color:rgba(100,80,50,.2)"></i>
                        </div>
                    </div>
                    <div class="nt-style-overlay"></div>
                    <div class="nt-style-badge">Xu hướng 2025</div>
                    <div class="nt-style-info">
                        <h4>Scandinavian</h4>
                        <p>Mộc mạc, ấm áp, gần gũi thiên nhiên</p>
                    </div>
                </div>

                {{-- Luxury --}}
                <div class="nt-style-card">
                    <div class="nt-style-bg" style="background:linear-gradient(160deg,#1a1200,#332200,#4d3300)">
                        <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center">
                            <i class="fas fa-crown" style="font-size:3.5rem;color:rgba(212,175,55,.25)"></i>
                        </div>
                    </div>
                    <div class="nt-style-overlay"></div>
                    <div class="nt-style-badge">Cao cấp</div>
                    <div class="nt-style-info">
                        <h4>Luxury</h4>
                        <p>Sang trọng, quý phái, đẳng cấp</p>
                    </div>
                </div>

                {{-- Japandi --}}
                <div class="nt-style-card">
                    <div class="nt-style-bg" style="background:linear-gradient(160deg,#2c2c2c,#1a1a1a,#0d0d0d)">
                        <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center">
                            <i class="fas fa-yin-yang" style="font-size:3.5rem;color:rgba(255,255,255,.1)"></i>
                        </div>
                    </div>
                    <div class="nt-style-overlay"></div>
                    <div class="nt-style-badge">Xu hướng mới</div>
                    <div class="nt-style-info">
                        <h4>Japandi</h4>
                        <p>Kết hợp Nhật Bản & Scandinavian</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════════════════
    QUY TRÌNH
══════════════════════════════════════════════════ --}}
    <section class="nt-section nt-section-dark" id="quy-trinh">
        <div class="container">
            <div class="text-center">
                <div class="nt-label"><i class="fas fa-list-ol"></i> Quy trình</div>
                <h2 class="nt-title">5 bước đơn giản<br><span style="color:#FF8C42">đến ngôi nhà mơ ước</span></h2>
                <p class="nt-subtitle mx-auto">Quy trình làm việc chuyên nghiệp, minh bạch — bạn luôn được
                    cập nhật tiến độ và có toàn quyền quyết định.</p>
            </div>

            <div class="nt-process-wrap">
                <div class="nt-process-step">
                    <div class="nt-step-num"><i class="fas fa-comments"></i></div>
                    <h4>Tư vấn<br>& Khảo sát</h4>
                    <p>Lắng nghe nhu cầu, đo đạc thực tế, xác định phong cách và ngân sách</p>
                </div>
                <div class="nt-process-step">
                    <div class="nt-step-num"><i class="fas fa-drafting-compass"></i></div>
                    <h4>Thiết kế<br>3D & 2D</h4>
                    <p>Xuất bản vẽ phối cảnh 3D, layout mặt bằng và danh sách vật liệu</p>
                </div>
                <div class="nt-process-step">
                    <div class="nt-step-num"><i class="fas fa-file-signature"></i></div>
                    <h4>Ký hợp đồng<br>& Báo giá</h4>
                    <p>Báo giá chi tiết, ký hợp đồng rõ ràng, cam kết tiến độ và chất lượng</p>
                </div>
                <div class="nt-process-step">
                    <div class="nt-step-num"><i class="fas fa-hard-hat"></i></div>
                    <h4>Thi công<br>& Giám sát</h4>
                    <p>Thi công theo đúng thiết kế, cập nhật tiến độ hàng tuần qua Zalo/app</p>
                </div>
                <div class="nt-process-step">
                    <div class="nt-step-num"><i class="fas fa-key"></i></div>
                    <h4>Bàn giao<br>& Bảo hành</h4>
                    <p>Nghiệm thu từng hạng mục, bàn giao đúng hạn kèm chứng thư bảo hành</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════════════════
    DỰ ÁN THỰC TẾ
══════════════════════════════════════════════════ --}}
    <section class="nt-section nt-section-alt" id="du-an">
        <div class="container">
            <div class="d-flex align-items-end justify-content-between flex-wrap gap-3">
                <div>
                    <div class="nt-label"><i class="fas fa-images"></i> Portfolio</div>
                    <h2 class="nt-title">Dự án <span style="color:#FF8C42">thực tế</span></h2>
                    <p class="nt-subtitle">Những công trình đã bàn giao — minh chứng cho chất lượng tay nghề.</p>
                </div>
                <a href="#"
                    style="color:#FF8C42;font-weight:700;font-size:.875rem;text-decoration:none;white-space:nowrap">
                    Xem tất cả <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>

            <div class="nt-project-grid">
                @php
                    $duAnMau = [
                        [
                            'Phòng khách hiện đại — S2.08',
                            '65m²',
                            '2 phòng ngủ',
                            'Hiện đại',
                            'fas fa-couch',
                            'linear-gradient(160deg,#1a2e3e,#0f1e2e)',
                        ],
                        [
                            'Căn studio full nội thất — S3.15',
                            '38m²',
                            'Studio',
                            'Japandi',
                            'fas fa-door-open',
                            'linear-gradient(160deg,#2c2416,#1a1509)',
                        ],
                        [
                            'Phòng ngủ master Luxury — S4.12',
                            '72m²',
                            '3 phòng ngủ',
                            'Luxury',
                            'fas fa-bed',
                            'linear-gradient(160deg,#1a1200,#2a1e00)',
                        ],
                        [
                            'Bếp & bàn ăn mở — S1.05',
                            '55m²',
                            '2 phòng ngủ',
                            'Scandinavian',
                            'fas fa-utensils',
                            'linear-gradient(160deg,#d4c8b8,#c0a882)',
                        ],
                        [
                            'Phòng trẻ em — S2.18',
                            '48m²',
                            '2 phòng ngủ',
                            'Hiện đại',
                            'fas fa-child',
                            'linear-gradient(160deg,#1a3a5e,#0f2238)',
                        ],
                        [
                            'Văn phòng tại nhà — S3.22',
                            '60m²',
                            '2 phòng ngủ',
                            'Tối giản',
                            'fas fa-laptop',
                            'linear-gradient(160deg,#2c1a2c,#1a0f1a)',
                        ],
                    ];
                @endphp

                @foreach ($duAnMau as $da)
                    <div class="nt-project-card">
                        <div class="nt-project-thumb">
                            <div class="nt-project-thumb-bg"
                                style="background:{{ $da[5] }};display:flex;align-items:center;justify-content:center">
                                <i class="{{ $da[4] }}" style="font-size:3rem;color:rgba(255,255,255,.2)"></i>
                            </div>
                            <div class="nt-project-thumb-overlay">
                                <span class="nt-project-view"><i class="fas fa-eye me-1"></i> Xem chi tiết</span>
                            </div>
                            <div
                                style="position:absolute;top:12px;right:12px;background:rgba(255,140,66,.9);
                                color:#fff;padding:4px 10px;border-radius:20px;font-size:.7rem;font-weight:700">
                                {{ $da[3] }}
                            </div>
                        </div>
                        <div class="nt-project-body">
                            <h4>{{ $da[0] }}</h4>
                            <div class="nt-project-meta">
                                <span><i class="fas fa-ruler-combined"></i> {{ $da[1] }}</span>
                                <span><i class="fas fa-bed"></i> {{ $da[2] }}</span>
                                <span><i class="fas fa-check-circle" style="color:#27ae60"></i> Đã bàn giao</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════════════════
    GÓI DỊCH VỤ
══════════════════════════════════════════════════ --}}
    <section class="nt-section" id="bang-gia">
        <div class="container">
            <div class="text-center">
                <div class="nt-label"><i class="fas fa-tags"></i> Bảng giá</div>
                <h2 class="nt-title">Gói thiết kế <span style="color:#FF8C42">phù hợp mọi ngân sách</span></h2>
                <p class="nt-subtitle mx-auto">Báo giá minh bạch, không phát sinh. Chọn gói phù hợp
                    hoặc liên hệ để được tư vấn gói tùy chỉnh.</p>
            </div>

            <div class="nt-pkg-grid">
                {{-- Gói Cơ bản --}}
                <div class="nt-pkg-card">
                    <div class="nt-pkg-header">
                        <div class="nt-pkg-badge">Tiết kiệm</div>
                        <h3>Gói Cơ bản</h3>
                        <p>Phù hợp căn hộ Studio & 1PN muốn hoàn thiện nhanh</p>
                    </div>
                    <div class="nt-pkg-price">
                        <div class="nt-pkg-price-num">3 — 5 triệu</div>
                        <div class="nt-pkg-price-unit">đồng / m² · Đã bao gồm thi công</div>
                    </div>
                    <div class="nt-pkg-body">
                        <ul class="nt-pkg-features">
                            <li><i class="fas fa-check"></i> Tư vấn thiết kế 1 buổi</li>
                            <li><i class="fas fa-check"></i> Bản vẽ mặt bằng 2D</li>
                            <li><i class="fas fa-check"></i> Thi công hoàn thiện cơ bản</li>
                            <li><i class="fas fa-check"></i> Vật liệu tiêu chuẩn</li>
                            <li><i class="fas fa-check"></i> Bảo hành 12 tháng</li>
                            <li class="off"><i class="fas fa-times"></i> Phối cảnh 3D</li>
                            <li class="off"><i class="fas fa-times"></i> Nội thất cao cấp</li>
                        </ul>
                    </div>
                    <div class="nt-pkg-footer">
                        <a href="#tuvan" class="nt-pkg-btn nt-pkg-btn-outline">Tìm hiểu thêm</a>
                    </div>
                </div>

                {{-- Gói Tiêu chuẩn —FEATURED --}}
                <div class="nt-pkg-card featured">
                    <div class="nt-pkg-header">
                        <div class="nt-pkg-badge">⭐ Được chọn nhiều nhất</div>
                        <h3>Gói Tiêu chuẩn</h3>
                        <p>Dành cho căn 2PN — 3PN, cân bằng chất lượng và chi phí</p>
                    </div>
                    <div class="nt-pkg-price">
                        <div class="nt-pkg-price-num">5 — 8 triệu</div>
                        <div class="nt-pkg-price-unit">đồng / m² · Đã bao gồm thi công</div>
                    </div>
                    <div class="nt-pkg-body">
                        <ul class="nt-pkg-features">
                            <li><i class="fas fa-check"></i> Tư vấn thiết kế không giới hạn</li>
                            <li><i class="fas fa-check"></i> Bản vẽ 2D + Phối cảnh 3D</li>
                            <li><i class="fas fa-check"></i> Thi công hoàn thiện đầy đủ</li>
                            <li><i class="fas fa-check"></i> Vật liệu cao cấp thương hiệu</li>
                            <li><i class="fas fa-check"></i> Giám sát tiến độ hàng ngày</li>
                            <li><i class="fas fa-check"></i> Bảo hành 24 tháng</li>
                            <li class="off"><i class="fas fa-times"></i> Nội thất nhập khẩu</li>
                        </ul>
                    </div>
                    <div class="nt-pkg-footer">
                        <a href="#tuvan" class="nt-pkg-btn nt-pkg-btn-fill">Đăng ký tư vấn</a>
                    </div>
                </div>

                {{-- Gói Cao cấp --}}
                <div class="nt-pkg-card">
                    <div class="nt-pkg-header">
                        <div class="nt-pkg-badge">Premium</div>
                        <h3>Gói Luxury</h3>
                        <p>Dành cho khách hàng muốn không gian sống đẳng cấp nhất</p>
                    </div>
                    <div class="nt-pkg-price">
                        <div class="nt-pkg-price-num">8 — 15 triệu</div>
                        <div class="nt-pkg-price-unit">đồng / m² · Đã bao gồm thi công</div>
                    </div>
                    <div class="nt-pkg-body">
                        <ul class="nt-pkg-features">
                            <li><i class="fas fa-check"></i> Tư vấn thiết kế chuyên sâu</li>
                            <li><i class="fas fa-check"></i> 3D + Video walkthrough</li>
                            <li><i class="fas fa-check"></i> Thi công hoàn thiện cao cấp</li>
                            <li><i class="fas fa-check"></i> Vật liệu & nội thất nhập khẩu</li>
                            <li><i class="fas fa-check"></i> Project Manager riêng</li>
                            <li><i class="fas fa-check"></i> Bảo hành 36 tháng</li>
                            <li><i class="fas fa-check"></i> Hỗ trợ VIP 24/7</li>
                        </ul>
                    </div>
                    <div class="nt-pkg-footer">
                        <a href="#tuvan" class="nt-pkg-btn nt-pkg-btn-outline">Tìm hiểu thêm</a>
                    </div>
                </div>
            </div>

            <p class="text-center mt-4" style="font-size:.82rem;color:#9ca3af">
                <i class="fas fa-info-circle" style="color:#FF8C42"></i>
                Giá trên là giá tham khảo. Báo giá chính xác sau khi khảo sát thực tế.
                <a href="#tuvan" style="color:#FF8C42;font-weight:700">Nhận báo giá miễn phí →</a>
            </p>
        </div>
    </section>

    {{-- ══════════════════════════════════════════════════
    VÌ SAO CHỌN CHÚNG TÔI
══════════════════════════════════════════════════ --}}
    <section class="nt-section nt-section-dark" id="ve-chung-toi">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-4">
                    <div class="nt-label"><i class="fas fa-shield-alt"></i> Cam kết</div>
                    <h2 class="nt-title">Vì sao chọn<br><span style="color:#FF8C42">Thành Công Land</span><br>cho nội
                        thất?</h2>
                    <p class="nt-subtitle" style="margin-top:12px">
                        Hơn 8 năm chuyên biệt thi công nội thất tại Vinhomes Smart City —
                        chúng tôi hiểu rõ từng tòa nhà, từng căn hộ hơn bất kỳ đơn vị nào khác.
                    </p>
                    <a href="#tuvan" class="nt-btn-primary mt-4" style="display:inline-flex">
                        <i class="fas fa-phone-alt"></i> Liên hệ ngay
                    </a>
                </div>
                <div class="col-lg-8">
                    <div class="nt-why-grid">
                        <div class="nt-why-item">
                            <div class="nt-why-icon"><i class="fas fa-map-marker-alt"></i></div>
                            <div>
                                <h4>Chuyên biệt Vinhomes Smart City</h4>
                                <p>Nắm rõ quy định BQL, kết cấu căn hộ, thời gian thi công được phép —
                                    tiết kiệm thời gian và tránh rủi ro phát sinh.</p>
                            </div>
                        </div>
                        <div class="nt-why-item">
                            <div class="nt-why-icon"><i class="fas fa-clock"></i></div>
                            <div>
                                <h4>Đúng tiến độ, không chậm trễ</h4>
                                <p>Cam kết bàn giao đúng ngày ghi trong hợp đồng. Nếu chậm, chúng tôi
                                    chịu phạt 0.1%/ngày theo giá trị hợp đồng.</p>
                            </div>
                        </div>
                        <div class="nt-why-item">
                            <div class="nt-why-icon"><i class="fas fa-eye"></i></div>
                            <div>
                                <h4>Minh bạch chi phí 100%</h4>
                                <p>Báo giá chi tiết từng hạng mục, không phát sinh ẩn.
                                    Khách hàng xem tiến độ và chi phí real-time qua app.</p>
                            </div>
                        </div>
                        <div class="nt-why-item">
                            <div class="nt-why-icon"><i class="fas fa-certificate"></i></div>
                            <div>
                                <h4>Đội ngũ có chứng chỉ nghề</h4>
                                <p>100% thợ thi công có chứng chỉ nghề, kinh nghiệm 5+ năm.
                                    Kiến trúc sư thiết kế tốt nghiệp đại học có danh tiếng.</p>
                            </div>
                        </div>
                        <div class="nt-why-item">
                            <div class="nt-why-icon"><i class="fas fa-handshake"></i></div>
                            <div>
                                <h4>Hậu mãi tận tâm</h4>
                                <p>Đội kỹ thuật phản hồi trong 24 giờ, xử lý sự cố bảo hành
                                    không tính phí trong suốt thời gian bảo hành.</p>
                            </div>
                        </div>
                        <div class="nt-why-item">
                            <div class="nt-why-icon"><i class="fas fa-percentage"></i></div>
                            <div>
                                <h4>Ưu đãi dành cho khách mua BĐS</h4>
                                <p>Khách hàng mua căn hộ qua Thành Công Land được ưu đãi
                                    <strong style="color:#FF8C42">10% chi phí nội thất</strong> và tư vấn thiết kế miễn
                                    phí.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════════════════
    ĐÁNH GIÁ KHÁCH HÀNG
══════════════════════════════════════════════════ --}}
    <section class="nt-section nt-section-alt" id="danh-gia">
        <div class="container">
            <div class="text-center">
                <div class="nt-label"><i class="fas fa-star"></i> Đánh giá</div>
                <h2 class="nt-title">Khách hàng <span style="color:#FF8C42">nói gì về chúng tôi</span></h2>
            </div>

            <div class="nt-reviews-grid">
                <div class="nt-review-card">
                    <div class="nt-review-quote">"</div>
                    <div class="nt-review-stars">★★★★★</div>
                    <p class="nt-review-text">Rất hài lòng với chất lượng thi công. Đội thợ làm việc gọn gàng,
                        sạch sẽ, đúng hẹn. Căn hộ sau khi hoàn thiện đẹp hơn cả bản 3D mình tưởng tượng!</p>
                    <div class="nt-review-author">
                        <div class="nt-review-avatar" style="background:linear-gradient(135deg,#FF8C42,#FF6B1A)">N</div>
                        <div>
                            <div class="nt-review-name">Chị Nguyễn Thu Hà</div>
                            <div class="nt-review-project">Căn hộ 2PN — S2.08 Vinhomes Smart City</div>
                        </div>
                    </div>
                </div>

                <div class="nt-review-card">
                    <div class="nt-review-quote">"</div>
                    <div class="nt-review-stars">★★★★★</div>
                    <p class="nt-review-text">Mình mua căn hộ qua Thành Công Land và được giới thiệu dịch vụ nội thất.
                        Thật sự tiện lợi vì họ hiểu rõ tòa nhà, không mất thời gian giải thích lại từ đầu.</p>
                    <div class="nt-review-author">
                        <div class="nt-review-avatar" style="background:linear-gradient(135deg,#1a3c5e,#2d6a9f)">T</div>
                        <div>
                            <div class="nt-review-name">Anh Trần Minh Tuấn</div>
                            <div class="nt-review-project">Căn hộ 3PN — S4.12 Vinhomes Smart City</div>
                        </div>
                    </div>
                </div>

                <div class="nt-review-card">
                    <div class="nt-review-quote">"</div>
                    <div class="nt-review-stars">★★★★★</div>
                    <p class="nt-review-text">Báo giá rõ ràng, không có chi phí phát sinh bất ngờ như nhiều chỗ khác.
                        Điều mình thích nhất là được cập nhật tiến độ qua Zalo mỗi ngày, rất yên tâm.</p>
                    <div class="nt-review-author">
                        <div class="nt-review-avatar" style="background:linear-gradient(135deg,#27ae60,#2ecc71)">L</div>
                        <div>
                            <div class="nt-review-name">Chị Lê Phương Linh</div>
                            <div class="nt-review-project">Căn Studio — S3.15 Vinhomes Smart City</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════════════════
    FORM TƯ VẤN
══════════════════════════════════════════════════ --}}
    <section class="nt-section" id="tuvan">
        <div class="container">
            <div class="nt-contact-wrap">

                {{-- Bên trái — Thông tin --}}
                <div>
                    <div class="nt-label"><i class="fas fa-comments"></i> Tư vấn miễn phí</div>
                    <h2 class="nt-title">Nhận tư vấn<br><span style="color:#FF8C42">hoàn toàn miễn phí</span></h2>
                    <p class="nt-subtitle" style="margin-top:12px">
                        Điền thông tin, chuyên viên tư vấn sẽ liên hệ lại trong vòng <strong>30 phút</strong>
                        trong giờ hành chính để hỗ trợ bạn.
                    </p>

                    <div style="margin-top:32px;display:flex;flex-direction:column;gap:20px">
                        <div style="display:flex;align-items:center;gap:14px">
                            <div
                                style="width:48px;height:48px;border-radius:12px;background:#fff5ef;
                                    display:flex;align-items:center;justify-content:center;
                                    color:#FF8C42;font-size:1.1rem;flex-shrink:0">
                                <i class="fas fa-phone-alt"></i>
                            </div>
                            <div>
                                <div style="font-weight:800;color:#1a3c5e;font-size:.9rem">Hotline tư vấn nội thất</div>
                                <a href="tel:0123456789" style="color:#FF8C42;font-weight:700;font-size:1.05rem">
                                    0123 456 789
                                </a>
                            </div>
                        </div>

                        <div style="display:flex;align-items:center;gap:14px">
                            <div
                                style="width:48px;height:48px;border-radius:12px;background:#e8f5e9;
                                    display:flex;align-items:center;justify-content:center;
                                    color:#27ae60;font-size:1.1rem;flex-shrink:0">
                                <i class="fas fa-comment-dots"></i>
                            </div>
                            <div>
                                <div style="font-weight:800;color:#1a3c5e;font-size:.9rem">Zalo tư vấn 24/7</div>
                                <a href="#" style="color:#27ae60;font-weight:700">Chat ngay trên Zalo</a>
                            </div>
                        </div>

                        <div style="display:flex;align-items:center;gap:14px">
                            <div
                                style="width:48px;height:48px;border-radius:12px;background:#f0f7ff;
                                    display:flex;align-items:center;justify-content:center;
                                    color:#2d6a9f;font-size:1.1rem;flex-shrink:0">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div>
                                <div style="font-weight:800;color:#1a3c5e;font-size:.9rem">Showroom</div>
                                <div style="color:#6b7280;font-size:.875rem">SA5 Vinhomes Smart City, Tây Mỗ, Nam Từ Liêm,
                                    Hà Nội</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Form --}}
                <div>
                    <div class="nt-form-card">
                        <div class="nt-form-title">Đăng ký tư vấn miễn phí</div>
                        <div class="nt-form-subtitle">Chuyên viên liên hệ trong 30 phút</div>

                        <form action="#" method="POST">
                            @csrf
                            <div class="nt-field-row">
                                <div class="nt-field">
                                    <label>Họ và tên <span style="color:#e74c3c">*</span></label>
                                    <input type="text" name="ho_ten" placeholder="Nguyễn Văn A" required>
                                </div>
                                <div class="nt-field">
                                    <label>Số điện thoại <span style="color:#e74c3c">*</span></label>
                                    <input type="tel" name="dien_thoai" placeholder="0912 345 678" required>
                                </div>
                            </div>

                            <div class="nt-field">
                                <label>Căn hộ của bạn</label>
                                <input type="text" name="can_ho" placeholder="Ví dụ: S2.08 — 2 phòng ngủ — 65m²">
                            </div>

                            <div class="nt-field-row">
                                <div class="nt-field">
                                    <label>Phong cách yêu thích</label>
                                    <select name="phong_cach">
                                        <option value="">— Chọn phong cách —</option>
                                        <option>Hiện đại</option>
                                        <option>Scandinavian</option>
                                        <option>Luxury</option>
                                        <option>Japandi</option>
                                        <option>Tối giản</option>
                                        <option>Chưa biết, cần tư vấn</option>
                                    </select>
                                </div>
                                <div class="nt-field">
                                    <label>Ngân sách dự kiến</label>
                                    <select name="ngan_sach">
                                        <option value="">— Chọn ngân sách —</option>
                                        <option>Dưới 100 triệu</option>
                                        <option>100 — 200 triệu</option>
                                        <option>200 — 500 triệu</option>
                                        <option>Trên 500 triệu</option>
                                        <option>Linh hoạt</option>
                                    </select>
                                </div>
                            </div>

                            <div class="nt-field">
                                <label>Ghi chú thêm</label>
                                <textarea name="ghi_chu" placeholder="Mô tả nhu cầu, yêu cầu đặc biệt..."></textarea>
                            </div>

                            <button type="submit" class="nt-form-submit">
                                <i class="fas fa-paper-plane"></i> Gửi yêu cầu tư vấn
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
