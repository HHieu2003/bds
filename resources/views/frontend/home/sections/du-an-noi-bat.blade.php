<section class="py-5" style="background-color: var(--bg-main);">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4" data-aos="fade-up">
            <div>
                {{-- Đổi tiêu đề --}}
                <h2 class="section-title serif-font">Dự Án Nổi Bật</h2>
                <p class="section-subtitle">Khám phá các siêu dự án đang được quan tâm nhất</p>
            </div>

            <div class="d-flex gap-2 align-items-center">
                <a href="{{ route('frontend.du-an.index') }}"
                    class="text-decoration-none fw-bold me-3 d-none d-md-block project-view-all">
                    Xem tất cả <i class="fas fa-arrow-right ms-1"></i>
                </a>
                <button
                    class="btn slider-nav-btn rounded-circle d-flex align-items-center justify-content-center focus-ring"
                    id="prevBtn"><i class="fas fa-chevron-left"></i></button>
                <button
                    class="btn slider-nav-btn rounded-circle d-flex align-items-center justify-content-center focus-ring"
                    id="nextBtn"><i class="fas fa-chevron-right"></i></button>
            </div>
        </div>

        <div class="position-relative">
            <div class="project-slider-container py-3" id="projectCarousel">
                @forelse ($du_ans as $da)
                    <div class="project-card-item" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 50 }}">
                        <div class="card border-0 rounded-4 overflow-hidden h-100 position-relative hover-up cursor-pointer"
                            onclick="window.location.href='{{ route('frontend.du-an.show', $da->slug) }}'">

                            <div class="overflow-hidden position-relative w-100" style="height: 280px;">
                                <img src="{{ $da->hinh_anh_dai_dien ? asset('storage/' . $da->hinh_anh_dai_dien) : 'https://vinhomesland.vn/wp-content/uploads/2023/10/be-boi-the-canopy-residences-vinhomes-smart-city.jpg' }}"
                                    class="card-img-top w-100 h-100 project-img" alt="{{ $da->ten_du_an }}">

                                <div class="position-absolute bottom-0 start-0 w-100 p-4"
                                    style="background: linear-gradient(to top, rgba(28, 18, 9, 0.9) 0%, rgba(28, 18, 9, 0.4) 60%, transparent 100%);">
                                    <h5 class="fw-bold mb-1 text-white text-truncate" title="{{ $da->ten_du_an }}">
                                        {{ $da->ten_du_an }}
                                    </h5>
                                    <small class="text-light opacity-75 d-block text-truncate">
                                        <i class="fa-solid fa-location-dot me-1 text-primary-brand"></i>
                                        {{ $da->dia_chi }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="w-100 text-center text-muted py-5">
                        <p>Đang cập nhật danh sách dự án...</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="text-center mt-4 d-md-none">
            <a href="{{ route('frontend.du-an.index') }}" class="btn-outline-theme w-100 d-inline-block">Xem tất cả dự
                án</a>
        </div>
    </div>
</section>

@push('styles')
    <style>
        .project-slider-container {
            display: flex;
            flex-wrap: nowrap;
            gap: 24px;
            overflow-x: auto;
            scroll-behavior: smooth;
            scroll-snap-type: x mandatory;
            padding-bottom: 10px;
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .project-slider-container::-webkit-scrollbar {
            display: none;
        }

        .project-card-item {
            scroll-snap-align: start;
            flex: 0 0 calc(25% - 18px);
            min-width: 240px;
        }

        .project-img {
            object-fit: cover;
            transition: transform 0.7s ease;
        }

        .hover-up:hover .project-img {
            transform: scale(1.08);
        }

        .project-view-all {
            color: var(--primary);
            transition: all 0.3s ease;
        }

        .project-view-all:hover {
            color: var(--primary-dark);
            letter-spacing: 0.5px;
        }

        .slider-nav-btn {
            width: 42px;
            height: 42px;
            border: 1.5px solid var(--border);
            color: var(--secondary);
            background: #fff;
            transition: all 0.3s ease;
        }

        .slider-nav-btn:hover:not(:disabled) {
            background: var(--primary);
            color: #fff;
            border-color: var(--primary);
            box-shadow: var(--shadow-gold);
        }

        @media (max-width: 1199px) {
            .project-card-item {
                flex: 0 0 calc(33.333% - 16px);
                min-width: 280px;
            }
        }

        @media (max-width: 991px) {
            .project-card-item {
                flex: 0 0 calc(50% - 12px);
            }
        }

        @media (max-width: 767px) {
            .project-card-item {
                flex: 0 0 85%;
                min-width: 260px;
            }
        }
    </style>
@endpush
@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const carousel = document.getElementById('projectCarousel');
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');

            if (prevBtn && nextBtn && carousel) {
                const getScrollAmount = () => {
                    const firstCard = carousel.querySelector('.project-card-item');
                    return firstCard ? firstCard.offsetWidth + 24 : 320;
                };
                prevBtn.addEventListener('click', () => {
                    carousel.scrollBy({
                        left: -getScrollAmount(),
                        behavior: 'smooth'
                    });
                });
                nextBtn.addEventListener('click', () => {
                    carousel.scrollBy({
                        left: getScrollAmount(),
                        behavior: 'smooth'
                    });
                });

                function updateButtonStates() {
                    prevBtn.disabled = carousel.scrollLeft <= 0;
                    nextBtn.disabled = carousel.scrollLeft >= (carousel.scrollWidth - carousel.clientWidth - 2);
                    prevBtn.style.opacity = prevBtn.disabled ? '0.4' : '1';
                    nextBtn.style.opacity = nextBtn.disabled ? '0.4' : '1';
                }
                carousel.addEventListener('scroll', updateButtonStates);
                window.addEventListener('resize', updateButtonStates);
                updateButtonStates();
            }
        });
    </script>
@endpush
