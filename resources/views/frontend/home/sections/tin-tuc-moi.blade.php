<section class="py-5 bg-white">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-5" data-aos="fade-up">
            <div>
                <h2 class="fw-bold serif-font mb-2" style="color: #333333;">Tin Tức Thị Trường</h2>
                <p class="text-muted mb-0">Cập nhật thông tin, chính sách và kinh nghiệm mua bán</p>
            </div>
            <a href="{{ route('frontend.tin-tuc.index') }}" class="btn btn-link text-decoration-none fw-bold"
                style="color: #FF8C42;">Xem tất cả <i class="fas fa-angle-right"></i></a>
        </div>

        <div class="row g-4">
            @forelse($baiVietMoi as $baiViet)
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                    <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden hover-up">
                        <a href="{{ route('frontend.tin-tuc.show', $baiViet->slug) }}" class="overflow-hidden d-block"
                            style="height: 240px;">
                            <img src="{{ $baiViet->hinh_anh ? asset('storage/' . $baiViet->hinh_anh) : asset('images/default-news.jpg') }}"
                                class="card-img-top w-100 h-100" style="object-fit: cover; transition: transform 0.5s;"
                                onmouseover="this.style.transform='scale(1.05)'"
                                onmouseout="this.style.transform='scale(1)'">
                        </a>
                        <div class="card-body p-4">
                            <div class="small text-muted mb-2 fw-semibold">
                                <i class="far fa-calendar-alt me-1 text-primary"></i>
                                {{ \Carbon\Carbon::parse($baiViet->thoi_diem_dang ?? $baiViet->created_at)->format('d/m/Y') }}
                            </div>
                            <h5 class="fw-bold mb-3">
                                <a href="{{ route('frontend.tin-tuc.show', $baiViet->slug) }}"
                                    class="text-decoration-none text-dark line-clamp-2">
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
    </div>
</section>

<style>
    /* CSS để cắt text nếu tiêu đề hoặc mô tả quá dài */
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
