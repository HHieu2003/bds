<section class="py-5 bg-alt-section">
    <div class="container">
        {{-- Tiêu đề đã được làm sạch và tự động ăn màu chuẩn --}}
        <h2 class="section-title text-center mb-5">Các Đối Tác Tin Cậy</h2>

        <div class="row align-items-center g-4 justify-content-center">
            @php
                $partners = [
                    'https://e7.pngegg.com/pngimages/620/176/png-clipart-tpbank-logo-vietnam-trademark-bank-purple-violet.png',
                    'https://logowik.com/content/uploads/images/vietcombank8188.jpg',
                    'https://dongphucvina.vn/wp-content/uploads/2023/05/logo-vietinbank-dongphucvina.vn_.png',
                    'https://tse3.mm.bing.net/th/id/OIP.lGwvhNDzTil08ed4TxtbGwHaBA?rs=1&pid=ImgDetMain&o=7&rm=3',
                    'https://tse1.mm.bing.net/th/id/OIP.L3y8uvnxKud1OK3dnE7NOAHaDH?rs=1&pid=ImgDetMain&o=7&rm=3',
                    'https://th.bing.com/th/id/OIP.X_5JgiQ0TmM3FVLtoLwIuQHaDt?o=7rm=3&rs=1&pid=ImgDetMain&o=7&rm=3',
                ];
            @endphp
            @foreach ($partners as $index => $logo)
                <div class="col-lg-2 col-md-4 col-6" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                    <div class="partner-card">
                        <img src="{{ $logo }}" alt="Partner" class="partner-img">
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

@push('styles')
    <style>
        /* Chuyển toàn bộ CSS nội tuyến vào đây để dễ quản lý */
        .partner-card {
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 110px;
            padding: 1.5rem;
            border-radius: var(--radius);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            cursor: pointer;
            box-shadow: var(--shadow-sm);
            border: 1px solid transparent;
        }

        .partner-card:hover {
            box-shadow: var(--shadow-gold);
            transform: translateY(-6px);
            border-color: var(--primary-light);
        }

        .partner-img {
            max-width: 100%;
            max-height: 60px;
            object-fit: contain;

            opacity: 0.7;
            transition: all 0.4s ease;
        }

        .partner-card:hover .partner-img {
            filter: grayscale(0%);
            opacity: 1;
            transform: scale(1.05);
        }
    </style>
@endpush
