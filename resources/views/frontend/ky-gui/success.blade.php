@extends('frontend.layouts.master')

@section('title', 'Gửi yêu cầu thành công')

@section('content')
    <style>
        .success-wrapper {
            min-height: 80vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem 1rem;
            background: linear-gradient(135deg, #fff5ef 0%, #fff 50%, #eff6ff 100%);
            position: relative;
            overflow: hidden;
        }

        /* Decoration blobs */
        .success-wrapper::before {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255, 140, 66, .08), transparent 70%);
            top: -100px;
            right: -100px;
            pointer-events: none;
        }

        .success-wrapper::after {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(26, 60, 94, .06), transparent 70%);
            bottom: -80px;
            left: -80px;
            pointer-events: none;
        }

        .success-card {
            background: #fff;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, .10), 0 4px 20px rgba(255, 140, 66, .08);
            padding: 3rem 2.5rem;
            max-width: 520px;
            width: 100%;
            text-align: center;
            position: relative;
            z-index: 1;
            animation: cardIn .5s cubic-bezier(.19, 1, .22, 1);
        }

        @keyframes cardIn {
            from {
                opacity: 0;
                transform: translateY(30px) scale(.97);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* Icon vòng tròn */
        .success-icon-wrap {
            position: relative;
            width: 100px;
            height: 100px;
            margin: 0 auto 1.8rem;
        }

        .success-icon-ring {
            position: absolute;
            inset: 0;
            border-radius: 50%;
            border: 3px solid #FF8C42;
            opacity: .25;
            animation: pulse 2s ease-in-out infinite;
        }

        .success-icon-ring-2 {
            position: absolute;
            inset: -10px;
            border-radius: 50%;
            border: 2px solid #FF8C42;
            opacity: .12;
            animation: pulse 2s ease-in-out infinite .4s;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
                opacity: .2;
            }

            50% {
                transform: scale(1.1);
                opacity: .08;
            }
        }

        .success-icon-circle {
            position: absolute;
            inset: 0;
            border-radius: 50%;
            background: linear-gradient(135deg, #FF8C42, #FF5722);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.2rem;
            color: #fff;
            box-shadow: 0 8px 24px rgba(255, 140, 66, .4);
        }

        /* Badge "Đã ghi nhận" */
        .success-badge {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            background: #f0fdf4;
            color: #16a34a;
            font-size: .72rem;
            font-weight: 800;
            padding: .3rem .9rem;
            border-radius: 20px;
            border: 1px solid #bbf7d0;
            margin-bottom: 1rem;
            letter-spacing: .3px;
        }

        .success-title {
            font-size: 1.6rem;
            font-weight: 900;
            color: #1a3c5e;
            margin-bottom: .7rem;
            line-height: 1.3;
        }

        .success-sub {
            color: #6b7280;
            font-size: .9rem;
            line-height: 1.75;
            margin-bottom: 1.8rem;
        }

        /* Steps timeline */
        .success-steps {
            background: #fafafa;
            border-radius: 14px;
            padding: 1.2rem 1.4rem;
            margin-bottom: 2rem;
            text-align: left;
            border: 1px solid #f0f0f0;
        }

        .success-steps-title {
            font-size: .72rem;
            font-weight: 800;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 1rem;
        }

        .step-row {
            display: flex;
            gap: .9rem;
            align-items: flex-start;
            margin-bottom: .85rem;
        }

        .step-row:last-child {
            margin-bottom: 0;
        }

        .step-dot {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .7rem;
            font-weight: 800;
            flex-shrink: 0;
            margin-top: .05rem;
        }

        .step-dot.done {
            background: #FF8C42;
            color: #fff;
        }

        .step-dot.next {
            background: #eff6ff;
            color: #2d6a9f;
            border: 2px solid #bfdbfe;
        }

        .step-dot.wait {
            background: #f3f4f6;
            color: #9ca3af;
        }

        .step-content {}

        .step-label {
            font-size: .82rem;
            font-weight: 700;
            color: #1a3c5e;
        }

        .step-desc {
            font-size: .73rem;
            color: #9ca3af;
            margin-top: .1rem;
        }

        /* Buttons */
        .success-actions {
            display: flex;
            gap: .75rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-success-home {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            padding: .7rem 1.6rem;
            background: linear-gradient(135deg, #FF8C42, #FF5722);
            color: #fff;
            font-weight: 800;
            font-size: .88rem;
            border-radius: 12px;
            text-decoration: none;
            box-shadow: 0 4px 16px rgba(255, 140, 66, .35);
            transition: transform .2s, box-shadow .2s;
        }

        .btn-success-home:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(255, 140, 66, .45);
            color: #fff;
        }

        .btn-success-more {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            padding: .7rem 1.4rem;
            background: #fff;
            color: #1a3c5e;
            font-weight: 700;
            font-size: .88rem;
            border-radius: 12px;
            text-decoration: none;
            border: 2px solid #e5e7eb;
            transition: border-color .2s, background .2s;
        }

        .btn-success-more:hover {
            border-color: #FF8C42;
            background: #fff5ef;
            color: #FF8C42;
        }

        /* Mã yêu cầu */
        .success-ref {
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px dashed #e5e7eb;
            font-size: .75rem;
            color: #9ca3af;
        }

        .success-ref span {
            font-weight: 800;
            color: #FF8C42;
            letter-spacing: .5px;
        }

        @media (max-width: 480px) {
            .success-card {
                padding: 2rem 1.3rem;
            }

            .success-title {
                font-size: 1.3rem;
            }
        }
    </style>

    <div class="success-wrapper">
        <div class="success-card">

            {{-- Icon --}}
            <div class="success-icon-wrap">
                <div class="success-icon-ring-2"></div>
                <div class="success-icon-ring"></div>
                <div class="success-icon-circle">
                    <i class="fas fa-check"></i>
                </div>
            </div>

            {{-- Badge --}}
            <div class="success-badge">
                <i class="fas fa-circle-check"></i>
                Đã ghi nhận yêu cầu
            </div>

            {{-- Title --}}
            <h1 class="success-title">Gửi ký gửi thành công!</h1>
            <p class="success-sub">
                Chúng tôi đã nhận được yêu cầu ký gửi bất động sản của bạn.<br>
                Đội ngũ tư vấn sẽ liên hệ trong vòng <strong style="color:#FF8C42;">24 giờ</strong> làm việc.
            </p>

            {{-- Steps --}}
            <div class="success-steps">
                <div class="success-steps-title">
                    <i class="fas fa-list-check"></i> &nbsp;Quy trình tiếp theo
                </div>

                <div class="step-row">
                    <div class="step-dot done"><i class="fas fa-check"></i></div>
                    <div class="step-content">
                        <div class="step-label">Gửi yêu cầu ký gửi</div>
                        <div class="step-desc">Hệ thống đã ghi nhận thông tin của bạn</div>
                    </div>
                </div>

                <div class="step-row">
                    <div class="step-dot next"><i class="fas fa-phone"></i></div>
                    <div class="step-content">
                        <div class="step-label">Tư vấn viên liên hệ</div>
                        <div class="step-desc">Xác nhận thông tin & trao đổi điều khoản ký gửi</div>
                    </div>
                </div>

                <div class="step-row">
                    <div class="step-dot wait"><i class="fas fa-file-signature"></i></div>
                    <div class="step-content">
                        <div class="step-label">Ký hợp đồng ký gửi</div>
                        <div class="step-desc">Hoàn tất thủ tục và bắt đầu đăng tin</div>
                    </div>
                </div>

                <div class="step-row">
                    <div class="step-dot wait"><i class="fas fa-handshake"></i></div>
                    <div class="step-content">
                        <div class="step-label">Kết nối khách hàng mua/thuê</div>
                        <div class="step-desc">Đội ngũ sale tiếp cận khách hàng tiềm năng</div>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="success-actions">
                <a href="{{ route('frontend.home') }}" class="btn-success-home">
                    <i class="fas fa-home"></i> Về trang chủ
                </a>
                <a href="{{ route('frontend.ky-gui.create') }}" class="btn-success-more">
                    <i class="fas fa-plus"></i> Gửi thêm BĐS
                </a>
            </div>

            {{-- Ref --}}
            <div class="success-ref">
                Hotline hỗ trợ:
                <span>0336 123 130</span>
                &nbsp;·&nbsp;
                Thứ 2 – Thứ 7, 8:00 – 18:00
            </div>

        </div>
    </div>
@endsection
