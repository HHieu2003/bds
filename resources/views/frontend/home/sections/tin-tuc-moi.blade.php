<section class="py-5" style="background-color: var(--bg-main);">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-5" data-aos="fade-up">
            <div>
                <h2 class="section-title serif-font">Tin Tức Thị Trường</h2>
                <p class="section-subtitle">Cập nhật thông tin, chính sách và kinh nghiệm mua bán</p>
            </div>
            <a href="{{ route('frontend.tin-tuc.index') }}"
                class="text-decoration-none fw-bold news-view-all d-none d-md-block">
                Xem tất cả <i class="fas fa-angle-right ms-1"></i>
            </a>
        </div>

        <div class="row g-4">
            @forelse($baiVietMoi as $baiViet)
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                    <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden hover-up">
                        <a href="{{ route('frontend.tin-tuc.show', $baiViet->slug) }}"
                            class="overflow-hidden d-block bg-light" style="height: 240px;">
                            <img src="{{ $baiViet->hinh_anh ? asset('storage/' . $baiViet->hinh_anh) : asset('images/default-news.jpg') }}"
                                class="card-img-top w-100 h-100 news-img" alt="{{ $baiViet->tieu_de }}">
                        </a>
                        <div class="card-body p-4 bg-alt-section">
                            <div class="small mb-2 fw-semibold" style="color: var(--secondary);">
                                <i class="far fa-calendar-alt me-1 icon-muted"></i>
                                {{ \Carbon\Carbon::parse($baiViet->thoi_diem_dang ?? $baiViet->created_at)->format('d/m/Y') }}
                            </div>
                            <h5 class="fw-bold mb-3">
                                {{-- Gắn class card-title-link --}}
                                <a href="{{ route('frontend.tin-tuc.show', $baiViet->slug) }}"
                                    class="card-title-link line-clamp-2">
                                    {{ $baiViet->tieu_de }}
                                </a>
                            </h5>
                            <p class="text-muted small line-clamp-3 mb-0">
                                {{ $baiViet->mo_ta_ngan ?? Str::limit(strip_tags($baiViet->noi_dung), 120) }}
                            </p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center text-muted">Chưa có tin tức nào mới.</div>
            @endforelse
        </div>

        <div class="text-center mt-4 d-md-none">
            <a href="{{ route('frontend.tin-tuc.index') }}" class="btn-outline-theme w-100 d-inline-block">Xem tất cả
                tin tức</a>
        </div>
    </div>
</section>

@push('styles')
    <style>
        .news-view-all {
            color: var(--primary);
            transition: all 0.2s;
        }

        .news-view-all:hover {
            color: var(--primary-dark);
            letter-spacing: 0.5px;
        }

        .news-img {
            object-fit: cover;
            transition: transform 0.6s ease;
        }

        .hover-up:hover .news-img {
            transform: scale(1.06);
        }
    </style>
@endpush
