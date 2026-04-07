{{-- resources/views/frontend/partials/goi-y-bds.blade.php --}}
@if ($goiYBds->isNotEmpty())
    <section class="section-goi-y py-5" style="background: var(--bg-alt);">
        <div class="container">
            <div class="d-flex align-items-end justify-content-between mb-4 border-bottom pb-3"
                style="border-color: rgba(196, 145, 42, 0.2) !important;">
                <div>
                    @auth('customer')
                        <h2 class="section-title mb-1" style="font-size: 1.8rem;">
                            Phù hợp với bạn
                        </h2>
                        <p class="text-muted small m-0"><i class="fas fa-magic me-1"></i> Gợi ý tự động dựa trên lịch sử tìm
                            kiếm & tương tác của bạn</p>
                    @else
                        <h2 class="section-title mb-1" style="font-size: 1.8rem;">
                            <i class="fas fa-fire text-primary-brand me-2"></i>
                            Có thể bạn sẽ thích
                        </h2>
                        <p class="text-muted small m-0"><i class="fas fa-magic me-1"></i> Bất động sản được cá nhân hóa cho
                            bạn</p>
                    @endauth
                </div>
                <a href="{{ route('frontend.bat-dong-san.index') }}"
                    class="btn btn-outline-theme btn-sm d-none d-md-inline-flex align-items-center">
                    Xem tất cả <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>

            <div class="row g-4">
                @foreach ($goiYBds as $bds)
                    @php $meta = $bds->goi_y_meta ?? []; @endphp
                    <div class="col-12 col-sm-6 col-lg-3">
                        <div class="bds-card hover-up h-100 position-relative rounded-3 overflow-hidden bg-white"
                            style="border: 1px solid var(--border);">

                            {{-- Badge điểm phù hợp (Màu Gold Gradient) --}}
                            @if (($meta['diem'] ?? 0) > 0)
                                <div class="goi-y-score-badge position-absolute"
                                    style="top: 12px; left: 12px; z-index: 2; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: #fff; font-size: 0.7rem; font-weight: 800; padding: 4px 10px; border-radius: 20px; box-shadow: var(--shadow-gold);">
                                    <i class="fas fa-star me-1" style="color: #ffeba0;"></i>
                                    Độ phù hợp: {{ $meta['phan_tram'] ?? 0 }}%
                                </div>
                            @endif

                            <a href="{{ route('frontend.bat-dong-san.show', $bds->slug) }}"
                                class="d-block bds-card-img-wrap position-relative"
                                style="aspect-ratio: 4/3; overflow: hidden;">
                                <img src="{{ $bds->hinh_anh_url }}" alt="{{ $bds->tieu_de }}"
                                    class="w-100 h-100 object-fit-cover" style="transition: transform 0.4s ease;"
                                    loading="lazy" onmouseover="this.style.transform='scale(1.08)'"
                                    onmouseout="this.style.transform='scale(1)'">

                                <span class="position-absolute badge-nhu-cau {{ $bds->nhu_cau }}"
                                    style="bottom: 10px; right: 10px; font-size: 0.7rem; font-weight: 800; padding: 4px 10px; border-radius: 6px; {{ $bds->nhu_cau === 'ban' ? 'background: rgba(192, 102, 42, 0.9); color: #fff;' : 'background: rgba(27, 58, 107, 0.9); color: #fff;' }} backdrop-filter: blur(4px);">
                                    {{ $bds->nhu_cau === 'ban' ? 'CẦN BÁN' : 'CHO THUÊ' }}
                                </span>
                            </a>

                            <div class="bds-card-body p-3">
                                <p class="text-muted mb-2" style="font-size: 0.75rem; font-weight: 600;">
                                    <i class="fas fa-map-marker-alt text-primary-brand me-1"></i>
                                    {{ $bds->loai_hinh }} • {{ $bds->duAn?->khuVuc?->ten_khu_vuc ?? 'Đang cập nhật' }}
                                </p>

                                <h6 class="fw-bold mb-3" style="font-size: 0.95rem; line-height: 1.4;">
                                    <a href="{{ route('frontend.bat-dong-san.show', $bds->slug) }}"
                                        class="card-title-link line-clamp-2">
                                        {{ $bds->tieu_de }}
                                    </a>
                                </h6>

                                <div class="d-flex justify-content-between align-items-end mb-3">
                                    <div class="price-text m-0" style="line-height: 1;">{{ $bds->gia_hien_thi }}</div>
                                    <div class="text-muted" style="font-size: 0.85rem; font-weight: 600;">
                                        <i class="fas fa-vector-square opacity-75"></i> {{ $bds->dien_tich }}m²
                                    </div>
                                </div>

                                {{-- Lý do gợi ý (Bo góc, Nền kem, Chữ màu Primary) --}}
                                @if (!empty($meta['ly_do']))
                                    <div class="goi-y-ly-do mt-auto"
                                        style="font-size: 0.7rem; color: var(--primary-dark); background: var(--primary-light); border-radius: 8px; padding: 6px 10px; font-weight: 600; border: 1px dashed rgba(196, 145, 42, 0.3);">
                                        <i class="fas fa-check-circle me-1"></i> {{ $meta['ly_do'][0] }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-4 d-md-none">
                <a href="{{ route('frontend.bat-dong-san.index') }}" class="btn btn-outline-theme btn-sm w-100">
                    Xem tất cả Bất động sản <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </section>
@endif
