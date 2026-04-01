@extends('frontend.layouts.master')
@section('title', 'Liên hệ với chúng tôi')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Liên hệ</li>
@endsection

@push('styles')
    <style>
        .lh-page {
            padding: 48px 0 68px;
            background: radial-gradient(circle at top right, rgba(var(--primary-rgb), 0.13), transparent 34%),
                linear-gradient(180deg, #fdf9f5 0%, #f8fafc 45%, #ffffff 100%);
        }

        .lh-wrapper {
            max-width: 1180px;
            margin: 0 auto;
            padding: 0 16px;
        }

        .lh-hero {
            border-radius: 24px;
            border: 1px solid rgba(192, 102, 42, 0.16);
            background: linear-gradient(140deg, #ffffff 0%, #fff8ef 50%, #f2f7ff 100%);
            box-shadow: 0 18px 44px rgba(18, 40, 75, 0.12);
            padding: 32px;
            margin-bottom: 24px;
            display: grid;
            grid-template-columns: 1.45fr 1fr;
            gap: 24px;
            align-items: center;
        }

        .lh-badge {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            font-size: 0.75rem;
            font-weight: 800;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: var(--secondary);
            background: rgba(27, 58, 107, 0.08);
            border: 1px solid rgba(27, 58, 107, 0.16);
            border-radius: 999px;
            padding: 7px 12px;
            margin-bottom: 12px;
        }

        .lh-title {
            margin: 0;
            color: var(--text-heading);
            font-size: clamp(1.55rem, 2.7vw, 2.35rem);
            font-weight: 800;
            line-height: 1.28;
        }

        .lh-subtitle {
            margin: 14px 0 0;
            color: var(--text-body);
            line-height: 1.75;
            font-size: 0.96rem;
        }

        .lh-highlights {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }

        .lh-highlight {
            background: #fff;
            border: 1px solid rgba(192, 102, 42, 0.15);
            border-radius: 14px;
            padding: 13px;
        }

        .lh-highlight-title {
            font-size: 0.72rem;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 4px;
            font-weight: 700;
            letter-spacing: 0.04em;
        }

        .lh-highlight strong {
            color: var(--text-heading);
            font-size: 0.93rem;
            font-weight: 800;
        }

        .lh-grid {
            display: grid;
            grid-template-columns: 1fr 430px;
            gap: 24px;
            align-items: start;
        }

        .lh-card,
        .lh-form-card {
            background: #fff;
            border-radius: 20px;
            border: 1px solid rgba(18, 40, 75, 0.13);
            box-shadow: 0 16px 38px rgba(18, 40, 75, 0.09);
            padding: 24px;
        }

        .lh-info-list {
            display: grid;
            gap: 12px;
            margin-bottom: 16px;
        }

        .lh-info-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            background: linear-gradient(135deg, #fffefc, #f8fafc);
            border: 1px solid rgba(192, 102, 42, 0.14);
            border-radius: 13px;
            padding: 12px;
        }

        .lh-info-icon {
            width: 40px;
            height: 40px;
            border-radius: 11px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 0.95rem;
            color: #fff;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
        }

        .lh-info-body small {
            display: block;
            color: var(--text-muted);
            margin-bottom: 2px;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .lh-info-body a,
        .lh-info-body span {
            color: var(--text-body);
            font-weight: 700;
            text-decoration: none;
            line-height: 1.4;
            font-size: 0.92rem;
        }

        .lh-map-wrap {
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid rgba(27, 58, 107, 0.2);
            box-shadow: var(--shadow-sm);
            background: #eef2f7;
        }

        .lh-map-wrap iframe {
            width: 100%;
            height: 360px;
            border: 0;
            display: block;
        }

        .lh-form-card {
            position: sticky;
            top: 20px;
        }

        .lh-form-title {
            margin: 0 0 16px;
            font-size: 1.1rem;
            font-weight: 800;
            color: var(--secondary);
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .lh-label {
            display: block;
            margin-bottom: 6px;
            font-size: 0.81rem;
            color: var(--text-body);
            font-weight: 700;
        }

        .lh-group {
            margin-bottom: 14px;
        }

        .lh-input,
        .lh-textarea {
            width: 100%;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            padding: 10px 12px;
            font-size: 0.9rem;
            color: var(--text-body);
            background: var(--bg-main);
            transition: all 0.2s ease;
            box-sizing: border-box;
            font-family: inherit;
        }

        .lh-input:focus,
        .lh-textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-light);
        }

        .lh-textarea {
            resize: vertical;
            min-height: 124px;
        }

        .lh-meta {
            margin-top: 12px;
            font-size: 0.76rem;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .lh-success {
            background: rgba(46, 125, 50, 0.09);
            border: 1px solid rgba(46, 125, 50, 0.24);
            border-radius: 12px;
            padding: 14px;
            text-align: center;
            margin-bottom: 15px;
            color: var(--status-success);
            font-weight: 600;
        }

        .lh-error {
            font-size: 0.76rem;
            color: var(--status-danger);
            margin-top: 5px;
            font-weight: 600;
        }

        @media (max-width: 991px) {
            .lh-hero {
                grid-template-columns: 1fr;
                padding: 24px;
            }

            .lh-grid {
                grid-template-columns: 1fr;
            }

            .lh-form-card {
                position: static;
            }
        }

        @media (max-width: 576px) {
            .lh-page {
                padding: 34px 0 52px;
            }

            .lh-hero,
            .lh-card,
            .lh-form-card {
                border-radius: 16px;
                padding: 18px;
            }

            .lh-highlights {
                grid-template-columns: 1fr;
            }

            .lh-map-wrap iframe {
                height: 300px;
            }
        }
    </style>
@endpush

@section('content')
    @php
        $contacts = [
            [
                'icon' => 'fas fa-phone-volume',
                'label' => 'Hotline tư vấn',
                'val' => '0336 123 130',
                'href' => 'tel:+84336123130',
            ],
            [
                'icon' => 'fas fa-envelope',
                'label' => 'Email',
                'val' => 'contact@thanhcongland.vn',
                'href' => 'mailto:contact@thanhcongland.vn',
            ],
            [
                'icon' => 'fas fa-map-marker-alt',
                'label' => 'Văn phòng giao dịch',
                'val' => 'Tòa SA5 Vinhomes Smart City, Tây Mỗ, Nam Từ Liêm, Hà Nội',
                'href' => null,
            ],
            [
                'icon' => 'fas fa-clock',
                'label' => 'Giờ làm việc',
                'val' => 'Thứ 2 - Thứ 7: 8:00 - 17:30 | Chủ nhật: 8:00 - 12:00',
                'href' => null,
            ],
        ];
    @endphp

    <section class="lh-page">
        <div class="lh-wrapper">
            <div class="lh-hero">
                <div>
                    <span class="lh-badge"><i class="fas fa-headset"></i> Chăm sóc khách hàng</span>
                    <h1 class="lh-title">Liên hệ ngay để được tư vấn bất động sản nhanh và chính xác</h1>
                    <p class="lh-subtitle">
                        Thành Công Land hỗ trợ mua bán, cho thuê và ký gửi bất động sản chuyên sâu khu vực Vinhomes Smart
                        City.
                        Chúng tôi phản hồi trong vòng <strong class="text-primary-brand">2 giờ làm việc</strong>.
                    </p>
                </div>
                <div class="lh-highlights">
                    <div class="lh-highlight">
                        <div class="lh-highlight-title">Thời gian phản hồi</div>
                        <strong>Tối đa 2 giờ</strong>
                    </div>
                    <div class="lh-highlight">
                        <div class="lh-highlight-title">Hỗ trợ trực tiếp</div>
                        <strong>7 ngày / tuần</strong>
                    </div>
                    <div class="lh-highlight">
                        <div class="lh-highlight-title">Kênh liên hệ</div>
                        <strong>Hotline - Email - Form</strong>
                    </div>
                    <div class="lh-highlight">
                        <div class="lh-highlight-title">Khu vực chính</div>
                        <strong>Vinhomes Smart City</strong>
                    </div>
                </div>
            </div>

            <div class="lh-grid">
                <div class="lh-card">
                    <h2 class="section-title" style="font-size:1.15rem;margin-bottom:12px">Thông tin liên hệ</h2>
                    <div class="lh-info-list">
                        @foreach ($contacts as $c)
                            <div class="lh-info-item">
                                <div class="lh-info-icon">
                                    <i class="{{ $c['icon'] }}"></i>
                                </div>
                                <div class="lh-info-body">
                                    <small>{{ $c['label'] }}</small>
                                    @if ($c['href'])
                                        <a href="{{ $c['href'] }}">{{ $c['val'] }}</a>
                                    @else
                                        <span>{{ $c['val'] }}</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="lh-map-wrap">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m17!1m12!1m3!1d1862.3936209103308!2d105.73223175184341!3d21.001163952083395!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m2!1m1!2zMjHCsDAwJzA0LjIiTiAxMDXCsDQ0JzAyLjciRQ!5e0!3m2!1svi!2s!4v1774940311746!5m2!1svi!2s"
                            loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen>
                        </iframe>
                    </div>
                </div>

                <div class="lh-form-card">
                    @if (session('lien_he_success'))
                        <div class="lh-success">
                            <i class="fas fa-check-circle"></i>
                            <div><strong>Gửi thành công!</strong> Chúng tôi sẽ liên hệ bạn trong thời gian sớm nhất.</div>
                        </div>
                    @endif

                    <h2 class="lh-form-title">
                        <i class="fas fa-paper-plane text-primary-brand"></i>
                        Gửi yêu cầu tư vấn
                    </h2>

                    <form method="POST" action="{{ route('frontend.lien-he.store') }}" id="lienHeForm">
                        @csrf

                        <div class="lh-group">
                            <label class="lh-label">Họ và tên <span style="color:var(--status-danger)">*</span></label>
                            <input class="lh-input" type="text" name="ho_ten"
                                value="{{ old('ho_ten', auth('customer')->user()?->ho_ten ?? '') }}"
                                placeholder="Nguyễn Văn A"
                                style="border-color:{{ $errors->has('ho_ten') ? 'var(--status-danger)' : '' }}">
                            @error('ho_ten')
                                <div class="lh-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                            @enderror
                        </div>

                        <div class="lh-group">
                            <label class="lh-label">Số điện thoại <span style="color:var(--status-danger)">*</span></label>
                            <input class="lh-input" type="tel" name="so_dien_thoai"
                                value="{{ old('so_dien_thoai', auth('customer')->user()?->so_dien_thoai ?? '') }}"
                                placeholder="0901 234 567"
                                style="border-color:{{ $errors->has('so_dien_thoai') ? 'var(--status-danger)' : '' }}">
                            @error('so_dien_thoai')
                                <div class="lh-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                            @enderror
                        </div>

                        <div class="lh-group">
                            <label class="lh-label">Email</label>
                            <input class="lh-input" type="email" name="email"
                                value="{{ old('email', auth('customer')->user()?->email ?? '') }}"
                                placeholder="email@example.com">
                        </div>

                        <div class="lh-group">
                            <label class="lh-label">Nội dung yêu cầu</label>
                            <textarea class="lh-textarea" name="noi_dung" placeholder="Bạn cần tư vấn loại hình bất động sản nào?">{{ old('noi_dung') }}</textarea>
                        </div>

                        <button type="submit" class="btn-primary-theme w-100" style="padding:0.75rem 1.1rem">
                            <i class="fas fa-paper-plane"></i>
                            Gửi yêu cầu tư vấn
                        </button>

                        <p class="lh-meta">
                            <i class="fas fa-shield-alt"></i>
                            Thông tin của bạn được bảo mật và chỉ phục vụ mục đích tư vấn.
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
