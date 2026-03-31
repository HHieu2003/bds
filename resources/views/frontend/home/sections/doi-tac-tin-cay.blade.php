<section class="py-5 bg-alt-section partner-section">
    <div class="container">
        <h2 class="section-title text-center mb-4">Các Đối Tác Tin Cậy</h2>

        @php
            $partners = [
                'https://e7.pngegg.com/pngimages/620/176/png-clipart-tpbank-logo-vietnam-trademark-bank-purple-violet.png',
                'https://logowik.com/content/uploads/images/vietcombank8188.jpg',
                'https://dongphucvina.vn/wp-content/uploads/2023/05/logo-vietinbank-dongphucvina.vn_.png',
                'https://tse3.mm.bing.net/th/id/OIP.lGwvhNDzTil08ed4TxtbGwHaBA?rs=1&pid=ImgDetMain&o=7&rm=3',
                'https://tse1.mm.bing.net/th/id/OIP.L3y8uvnxKud1OK3dnE7NOAHaDH?rs=1&pid=ImgDetMain&o=7&rm=3',
                'https://th.bing.com/th/id/OIP.X_5JgiQ0TmM3FVLtoLwIuQHaDt?o=7rm=3&rs=1&pid=ImgDetMain&o=7&rm=3',
                'https://uploads-ssl.webflow.com/5fb85f26f126ce08d792d2d9/62ce8018d5cacb6b28727421_MB%20bank%20logo.jpg',
                'https://hiepthanh.net/wp-content/uploads/2019/12/logo-vlnnx-3.png',
                'https://thanhcongland.com.vn/wp-content/uploads/2025/11/logo-khach-hang-6-1-600x450.webp',
                'https://thanhcongland.com.vn/wp-content/uploads/2025/11/logo-khach-hang-3-1-600x450.webp',
                'https://thanhcongland.com.vn/wp-content/uploads/2025/11/logo-khach-hang-5-1-600x450.webp',
            ];
        @endphp

        <div class="partner-marquee" aria-label="Các logo đối tác">
            <div class="partner-track">
                @foreach ($partners as $logo)
                    <div class="partner-card">
                        <img src="{{ $logo }}" alt="Đối tác" class="partner-img" loading="lazy" decoding="async">
                    </div>
                @endforeach

                @foreach ($partners as $logo)
                    <div class="partner-card" aria-hidden="true">
                        <img src="{{ $logo }}" alt="" class="partner-img" loading="lazy"
                            decoding="async">
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

@push('styles')
    <style>
        .partner-section {
            overflow: hidden;
        }

        .partner-marquee {
            position: relative;
            overflow: hidden;
            border-radius: 16px;
            padding: 14px 0;
            background: linear-gradient(180deg, rgba(255, 255, 255, .65), rgba(255, 255, 255, .25));

        }

        .partner-marquee::before,
        .partner-marquee::after {
            content: "";
            position: absolute;
            top: 0;
            bottom: 0;
            width: 56px;
            z-index: 2;
            pointer-events: none;
        }

        .partner-marquee::before {
            left: 0;
            background: linear-gradient(to right, #f8fafc, rgba(248, 250, 252, 0));
        }

        .partner-marquee::after {
            right: 0;
            background: linear-gradient(to left, #f8fafc, rgba(248, 250, 252, 0));
        }

        .partner-track {
            display: flex;
            align-items: center;
            gap: 14px;
            width: max-content;
            animation: partner-scroll 86s linear infinite;
            will-change: transform;
        }

        .partner-card {
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 88px;
            width: 180px;
            padding: 1rem;
            border-radius: 12px;
            transition: transform .3s ease, box-shadow .3s ease, border-color .3s ease;
            cursor: pointer;
            box-shadow: 0 6px 18px rgba(0, 0, 0, .06);
            border: 1px solid rgba(255, 140, 66, .12);
        }

        .partner-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 24px rgba(255, 140, 66, .22);
            border-color: rgba(255, 140, 66, .35);
        }

        .partner-img {
            max-width: 100%;
            max-height: 52px;
            object-fit: contain;
            transition: transform .35s ease, filter .35s ease;
            filter: saturate(.92) contrast(1.02);
        }

        .partner-card:hover .partner-img {
            transform: scale(1.04);
            filter: saturate(1.08) contrast(1.05);
        }

        .partner-marquee:hover .partner-track {
            animation-play-state: paused;
        }

        @keyframes partner-scroll {
            from {
                transform: translateX(0);
            }

            to {
                transform: translateX(calc(-50% - 7px));
            }
        }

        @media (max-width: 768px) {
            .partner-card {
                width: 150px;
                height: 78px;
                padding: .85rem;
            }

            .partner-img {
                max-height: 46px;
            }

            .partner-track {
                animation-duration: 28s;
            }
        }
    </style>
@endpush
