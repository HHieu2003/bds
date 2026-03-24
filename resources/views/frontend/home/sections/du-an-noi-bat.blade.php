<section class="py-5" style="background-color: #ffffff;">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4" data-aos="fade-up">
            <div>
                <h2 class="fw-bold serif-font mb-2" style="color: #333333;">Dự Án Nổi Bật</h2>
                <p class="text-muted mb-0">Khám phá các siêu dự án đang được quan tâm nhất</p>
            </div>

            {{-- Nút điều hướng & Xem tất cả (Bản Desktop) --}}
            <div class="d-flex gap-2 align-items-center">
                <a href="{{ route('frontend.du-an.index') }}" class="text-decoration-none fw-bold me-3 d-none d-md-block"
                    style="color: #FF8C42; transition: 0.3s;" onmouseover="this.style.color='#e67a32'"
                    onmouseout="this.style.color='#FF8C42'">
                    Xem tất cả <i class="fas fa-arrow-right ms-1"></i>
                </a>
                <button
                    class="btn btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center focus-ring"
                    id="prevBtn"
                    style="width: 40px; height: 40px; border-color: #e0e0e0; color: #333; background: #fff;">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button
                    class="btn btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center focus-ring"
                    id="nextBtn"
                    style="width: 40px; height: 40px; border-color: #e0e0e0; color: #333; background: #fff;">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>

        <div class="position-relative">
            {{-- CONTAINER TRƯỢT NGANG --}}
            <div class="project-slider-container py-2" id="projectCarousel">
                @forelse ($du_ans as $da)
                    <div class="project-card-item" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 50 }}">
                        <div class="card border-0 rounded-4 overflow-hidden shadow-sm h-100 position-relative hover-up cursor-pointer"
                            onclick="window.location.href='{{ route('frontend.du-an.show', $da->slug) }}'">

                            {{-- Hình ảnh dự án --}}
                            <div class="overflow-hidden position-relative w-100" style="height: 280px;">
                                <img src="{{ $da->hinh_anh_dai_dien ? asset('storage/' . $da->hinh_anh_dai_dien) : 'https://vinhomesland.vn/wp-content/uploads/2023/10/be-boi-the-canopy-residences-vinhomes-smart-city.jpg' }}"
                                    class="card-img-top w-100 h-100 project-img"
                                    style="object-fit: cover; transition: transform 0.6s ease;"
                                    alt="{{ $da->ten_du_an }}">

                                {{-- Overlay Gradient Đen bọc chữ --}}
                                <div class="position-absolute bottom-0 start-0 w-100 p-4"
                                    style="background: linear-gradient(to top, rgba(0,0,0,0.85) 0%, rgba(0,0,0,0.4) 60%, transparent 100%);">
                                    <h5 class="fw-bold mb-1 text-white text-truncate" title="{{ $da->ten_du_an }}">
                                        {{ $da->ten_du_an }}
                                    </h5>
                                    <small class="text-light opacity-75 d-block text-truncate">
                                        <i class="fa-solid fa-location-dot me-1 text-warning"></i> {{ $da->dia_chi }}
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

        {{-- Nút xem tất cả (Bản Mobile) --}}
        <div class="text-center mt-4 d-md-none">
            <a href="{{ route('frontend.du-an.index') }}"
                class="btn btn-outline-primary rounded-pill px-4 fw-bold w-100"
                style="border-color: #FF8C42; color: #FF8C42;">
                Xem tất cả dự án
            </a>
        </div>
    </div>
</section>

<style>
    /* CSS CỐT LÕI CHO THANH TRƯỢT NGANG */
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

    /* ĐÃ SỬA: Kích thước thẻ Card chia theo màn hình */
    .project-card-item {
        scroll-snap-align: start;
        /* Tính toán: 100% chia 4 = 25%. Trừ đi khoảng cách gap */
        flex: 0 0 calc(25% - 18px);
        min-width: 240px;
        /* Giảm min-width xuống để ép vừa 4 thẻ trên màn hình */
    }

    /* Màn hình Laptop nhỏ gọn / Tablet ngang (hiển thị 3 thẻ) */
    @media (max-width: 1199px) {
        .project-card-item {
            flex: 0 0 calc(33.333% - 16px);
            min-width: 280px;
        }
    }

    /* Màn hình Tablet dọc (hiển thị 2 thẻ) */
    @media (max-width: 991px) {
        .project-card-item {
            flex: 0 0 calc(50% - 12px);
        }
    }

    /* Màn hình Mobile (hiển thị 1.5 thẻ để mồi vuốt) */
    @media (max-width: 767px) {
        .project-card-item {
            flex: 0 0 85%;
            min-width: 260px;
        }
    }

    .cursor-pointer {
        cursor: pointer;
    }

    .hover-up:hover .project-img {
        transform: scale(1.08);
    }
</style>

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const carousel = document.getElementById('projectCarousel');
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');

            if (prevBtn && nextBtn && carousel) {
                // Tự động tính toán chiều rộng của 1 thẻ để cuộn đúng 1 thẻ đó
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
                    prevBtn.style.cursor = prevBtn.disabled ? 'default' : 'pointer';
                    nextBtn.style.cursor = nextBtn.disabled ? 'default' : 'pointer';
                }

                carousel.addEventListener('scroll', updateButtonStates);
                window.addEventListener('resize', updateButtonStates);
                updateButtonStates();
            }
        });
    </script>
@endpush
