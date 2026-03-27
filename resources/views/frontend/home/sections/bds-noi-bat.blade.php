<section class="py-5 bg-alt-section">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="section-title serif-font">Bất Động Sản Nổi Bật</h2>
            <p class="section-subtitle">Những căn hộ, nhà đất vị trí đẹp, giá tốt nhất tuần qua</p>
        </div>

        <div class="row g-4">
            @forelse($bdsNoiBat as $bds)
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                    <div class="card border-0 h-100 rounded-4 overflow-hidden position-relative hover-up bds-card">
                        <div class="position-absolute top-0 start-0 m-3 z-1">
                            <span class="{{ $bds->nhu_cau == 'ban' ? 'badge-hot' : 'badge-moi' }} shadow-sm">
                                {{ $bds->nhu_cau == 'ban' ? 'Đang Bán' : 'Cho Thuê' }}
                            </span>
                        </div>

                        <a href="{{ route('frontend.bat-dong-san.show', $bds->slug) }}"
                            class="overflow-hidden d-block img-wrapper" style="height: 220px;">
                            @php $anh = is_array($bds->album_anh) && count($bds->album_anh) > 0 ? $bds->album_anh[0] : null; @endphp
                            <img src="{{ $anh ? asset('storage/' . $anh) : asset('images/default-bds.jpg') }}"
                                class="card-img-top h-100 w-100 bds-img" alt="{{ $bds->tieu_de }}">
                        </a>

                        <div class="card-body p-4">
                            {{-- Sử dụng class giá tiền --}}
                            <h4 class="price-text mb-2">{{ $bds->gia_hien_thi ?? 'Thỏa thuận' }}</h4>

                            <h6 class="fw-bold mb-2">
                                {{-- Sử dụng class title để có hiệu ứng hover sáng lên màu Gold --}}
                                <a href="{{ route('frontend.bat-dong-san.show', $bds->slug) }}"
                                    class="card-title-link line-clamp-2" title="{{ $bds->tieu_de }}">
                                    {{ $bds->tieu_de }}
                                </a>
                            </h6>

                            <p class="text-muted small mb-3 line-clamp-1">
                                <i class="fas fa-map-marker-alt me-1 text-primary-brand"></i>
                                {{ $bds->duAn ? $bds->duAn->ten_du_an : $bds->dia_chi }}
                            </p>
                            <hr class="text-muted opacity-25">
                            <div class="d-flex justify-content-between text-muted small fw-semibold">
                                {{-- Dùng icon-muted cho biểu tượng --}}
                                <span><i class="fas fa-expand me-1 icon-muted"></i> {{ $bds->dien_tich }} m²</span>
                                <span><i class="fas fa-bed me-1 icon-muted"></i> {{ $bds->so_phong_ngu }} PN</span>
                                <span><i class="fas fa-bath me-1 icon-muted"></i> {{ $bds->so_phong_tam }} WC</span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center text-muted">
                    <p>Đang cập nhật danh sách bất động sản nổi bật...</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

@push('styles')
    <style>
        .bds-img {
            object-fit: cover;
            transition: transform 0.6s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        .hover-up:hover .bds-img {
            transform: scale(1.08);
        }
    </style>
@endpush
