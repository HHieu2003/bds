{{-- resources/views/frontend/partials/goi-y-bds.blade.php --}}
<style>
    /* Section gợi ý */
    .badge-goi-y {
        position: absolute;
        top: 10px;
        left: 10px;
        z-index: 2;
        background: linear-gradient(135deg, #01696f, #0c4e54);
        color: #fff;
        font-size: 11px;
        font-weight: 600;
        padding: 3px 8px;
        border-radius: 20px;
        letter-spacing: 0.3px;
    }

    .bds-card-img-wrap {
        position: relative;
        overflow: hidden;
        border-radius: 10px 10px 0 0;
        aspect-ratio: 4/3;
    }

    .bds-card-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform .35s ease;
    }

    .bds-card:hover .bds-card-img {
        transform: scale(1.05);
    }

    .badge-nhu-cau {
        position: absolute;
        bottom: 8px;
        right: 8px;
        font-size: 11px;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 4px;
    }

    .badge-nhu-cau.ban {
        background: #fff3cd;
        color: #856404;
    }

    .badge-nhu-cau.thue {
        background: #d1ecf1;
        color: #0c5460;
    }

    .goi-y-score-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        z-index: 2;
        background: rgba(1, 105, 111, 0.92);
        color: #fff;
        font-size: 11px;
        font-weight: 700;
        padding: 3px 9px;
        border-radius: 20px;
        backdrop-filter: blur(4px);
    }

    .goi-y-ly-do {
        font-size: 11px;
        color: #01696f;
        background: #e8f5f5;
        border-radius: 6px;
        padding: 4px 8px;
        font-weight: 500;
    }
</style>
{{-- resources/views/frontend/partials/goi-y-bds.blade.php --}}
@if ($goiYBds->isNotEmpty())
    <section class="section-goi-y py-5">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    @auth('customer')
                        <h2 class="section-title mb-1">
                            <i class="fas fa-magic text-primary-brand me-2"></i>
                            Dành riêng cho {{ auth('customer')->user()->ho_ten }}
                        </h2>
                        <p class="text-muted small">Dựa trên lịch sử tìm kiếm của bạn</p>
                    @else
                        <h2 class="section-title mb-1">
                            <i class="fas fa-fire text-primary-brand me-2"></i>
                            Có thể bạn quan tâm
                        </h2>
                    @endauth
                </div>
                <a href="{{ route('frontend.bat-dong-san.index') }}" class="btn btn-outline-primary-brand btn-sm">
                    Xem tất cả <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>

            <div class="row g-3">
                @foreach ($goiYBds as $bds)
                    @php $meta = $bds->goi_y_meta ?? []; @endphp
                    <div class="col-12 col-sm-6 col-lg-3">
                        <div class="bds-card h-100 position-relative">

                            {{-- Badge điểm phù hợp --}}
                            @if (($meta['diem'] ?? 0) > 0)
                                <div class="goi-y-score-badge">
                                    <i class="fas fa-check-circle me-1"></i>
                                    {{ round(min(($meta['diem'] / 30) * 100, 99)) }}% phù hợp
                                </div>
                            @endif

                            <a href="{{ route('frontend.bat-dong-san.show', $bds->slug) }}">
                                <div class="bds-card-img-wrap">
                                    <img src="{{ $bds->hinh_anh_url }}" alt="{{ $bds->tieu_de }}" loading="lazy">
                                    <span class="badge-nhu-cau {{ $bds->nhu_cau }}">
                                        {{ $bds->nhu_cau === 'ban' ? 'Bán' : 'Cho thuê' }}
                                    </span>
                                </div>
                            </a>

                            <div class="bds-card-body p-3">
                                <p class="text-muted small mb-1">
                                    {{ $bds->loai_hinh }} •
                                    {{ $bds->duAn?->khuVuc?->ten_khu_vuc ?? '' }}
                                </p>
                                <h6 class="fw-bold mb-2">
                                    <a href="{{ route('frontend.bat-dong-san.show', $bds->slug) }}"
                                        class="text-dark text-decoration-none">
                                        {{ Str::limit($bds->tieu_de, 48) }}
                                    </a>
                                </h6>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-bold text-primary-brand">{{ $bds->gia_hien_thi }}</span>
                                    <span class="text-muted small">{{ $bds->dien_tich }}m²</span>
                                </div>

                                {{-- Lý do gợi ý --}}
                                @if (!empty($meta['ly_do']))
                                    <div class="goi-y-ly-do">
                                        <i class="fas fa-lightbulb me-1"></i>
                                        {{ $meta['ly_do'][0] }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
