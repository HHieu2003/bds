<section class="py-5" style="background-color: var(--bg-main);">
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-5" data-aos="fade-up">
            <div>
                {{-- Tiêu đề đồng nhất --}}
                <h2 class="section-title serif-font">Nguồn Hàng Mới Nhất</h2>
                <p class="section-subtitle">Cập nhật liên tục từ các chủ nhà ký gửi</p>
            </div>

            <ul class="nav nav-pills mt-3 mt-md-0 p-1 rounded-pill border custom-tabs" id="bdsTab" role="tablist"
                style="background: #fff;">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active rounded-pill fw-bold px-4" id="ban-tab" data-bs-toggle="tab"
                        data-bs-target="#ban" type="button" role="tab">Cần Bán</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link rounded-pill fw-bold px-4" id="thue-tab" data-bs-toggle="tab"
                        data-bs-target="#thue" type="button" role="tab">Cho Thuê</button>
                </li>
            </ul>
        </div>

        <div class="tab-content" id="bdsTabContent">
            {{-- TAB: BÁN --}}
            <div class="tab-pane fade show active" id="ban" role="tabpanel" tabindex="0">
                <div class="row g-4">
                    @foreach ($bdsBan as $bds)
                        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                            <div class="card border-0 h-100 rounded-4 overflow-hidden position-relative hover-up">
                                <div class="position-absolute top-0 start-0 m-3 z-1"><span
                                        class="badge-hot shadow-sm">Đang Bán</span></div>
                                <div class="position-absolute top-0 end-0 m-3 z-1">
                                    <button
                                        class="btn btn-light rounded-circle shadow-sm text-muted heart-icon-{{ $bds->id }} fav-btn {{ $bds->isYeuThich ?? false ? 'liked' : '' }}"
                                        onclick="toggleYeuThich(this, {{ $bds->id }})"
                                        title="{{ $bds->isYeuThich ?? false ? 'Bỏ yêu thích' : 'Lưu tin này' }}"><i
                                            class="{{ $bds->isYeuThich ?? false ? 'fas' : 'far' }} fa-heart"></i></button>
                                </div>
                                <a href="{{ route('frontend.bat-dong-san.show', $bds->slug) }}"
                                    class="overflow-hidden d-block bg-light img-wrapper" style="height: 220px;">
                                    @php $anh = is_array($bds->album_anh) && count($bds->album_anh) > 0 ? $bds->album_anh[0] : null; @endphp
                                    <img src="{{ $anh ? \Storage::disk('r2')->url($anh) : asset('images/default-bds.jpg') }}"
                                        class="card-img-top h-100 w-100 bds-img" alt="{{ $bds->tieu_de }}">
                                </a>
                                <div class="card-body p-4 d-flex flex-column bg-alt-section">
                                    {{-- Clean Code ở thẻ con --}}
                                    <h4 class="price-text mb-2">{{ $bds->gia_hien_thi ?? 'Thỏa thuận' }}</h4>
                                    <h6 class="fw-bold mb-2">
                                        <a href="{{ route('frontend.bat-dong-san.show', $bds->slug) }}"
                                            class="card-title-link line-clamp-2"
                                            title="{{ $bds->tieu_de }}">{{ $bds->tieu_de }}</a>
                                    </h6>
                                    <p class="text-muted small mb-3 flex-grow-1 line-clamp-1">
                                        <i class="fas fa-map-marker-alt me-1 text-primary-brand"></i>
                                        {{ $bds->duAn ? $bds->duAn->ten_du_an : $bds->dia_chi }}
                                    </p>
                                    <hr class="text-muted opacity-25 mt-0 mb-3">
                                    <div class="d-flex justify-content-between text-muted small fw-semibold">
                                        <span title="Diện tích"><i class="fas fa-expand me-1 icon-muted"></i>
                                            {{ $bds->dien_tich }} m²</span>
                                        <span title="Phòng ngủ"><i class="fas fa-bed me-1 icon-muted"></i>
                                            {{ $bds->so_phong_ngu }}</span>

                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="text-center mt-5">
                    <a href="{{ route('frontend.tim-kiem.index') }}?loai=ban" class="btn-outline-theme">Xem thêm nhà
                        bán <i class="fas fa-arrow-right ms-2"></i></a>
                </div>
            </div>

            {{-- TAB: THUÊ (Tương tự) --}}
            <div class="tab-pane fade" id="thue" role="tabpanel" tabindex="0">
                <div class="row g-4">
                    @foreach ($bdsThue as $bds)
                        <div class="col-lg-4 col-md-6">
                            <div class="card border-0 h-100 rounded-4 overflow-hidden position-relative hover-up">
                                <div class="position-absolute top-0 start-0 m-3 z-1"><span
                                        class="badge-moi shadow-sm">Cho Thuê</span></div>
                                <div class="position-absolute top-0 end-0 m-3 z-1">
                                    <button
                                        class="btn btn-light rounded-circle shadow-sm text-muted heart-icon-{{ $bds->id }} fav-btn {{ $bds->isYeuThich ?? false ? 'liked' : '' }}"
                                        onclick="toggleYeuThich(this, {{ $bds->id }})"
                                        title="{{ $bds->isYeuThich ?? false ? 'Bỏ yêu thích' : 'Lưu tin này' }}"><i
                                            class="{{ $bds->isYeuThich ?? false ? 'fas' : 'far' }} fa-heart"></i></button>
                                </div>
                                <a href="{{ route('frontend.bat-dong-san.show', $bds->slug) }}"
                                    class="overflow-hidden d-block bg-light img-wrapper" style="height: 220px;">
                                    @php $anh = is_array($bds->album_anh) && count($bds->album_anh) > 0 ? $bds->album_anh[0] : null; @endphp
                                    <img src="{{ $anh ? \Storage::disk('r2')->url($anh) : asset('images/default-bds.jpg') }}"
                                        class="card-img-top h-100 w-100 bds-img" alt="{{ $bds->tieu_de }}">
                                </a>
                                <div class="card-body p-4 d-flex flex-column bg-alt-section">
                                    <h4 class="price-text mb-2">{{ $bds->gia_hien_thi ?? 'Thỏa thuận' }}</h4>
                                    <h6 class="fw-bold mb-2">
                                        <a href="{{ route('frontend.bat-dong-san.show', $bds->slug) }}"
                                            class="card-title-link line-clamp-2"
                                            title="{{ $bds->tieu_de }}">{{ $bds->tieu_de }}</a>
                                    </h6>
                                    <p class="text-muted small mb-3 flex-grow-1 line-clamp-1">
                                        <i class="fas fa-map-marker-alt me-1 text-primary-brand"></i>
                                        {{ $bds->duAn ? $bds->duAn->ten_du_an : $bds->dia_chi }}
                                    </p>
                                    <hr class="text-muted opacity-25 mt-0 mb-3">
                                    <div class="d-flex justify-content-between text-muted small fw-semibold">
                                        <span title="Diện tích"><i class="fas fa-expand me-1 icon-muted"></i>
                                            {{ $bds->dien_tich }} m²</span>
                                        <span title="Phòng ngủ"><i class="fas fa-bed me-1 icon-muted"></i>
                                            {{ $bds->so_phong_ngu }}</span>

                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="text-center mt-5">
                    <a href="{{ route('frontend.tim-kiem.index') }}?loai=thue" class="btn-outline-theme">Xem thêm nhà
                        cho thuê <i class="fas fa-arrow-right ms-2"></i></a>
                </div>
            </div>
        </div>
    </div>
</section>

@push('styles')
    <style>
        .custom-tabs .nav-link {
            color: var(--text-muted);
            transition: all 0.3s ease;
            border: 1px solid transparent;
        }

        .custom-tabs .nav-link.active {
            background-color: var(--primary-light);
            color: var(--primary);
            border-color: var(--primary);
        }

        .bds-img {
            object-fit: cover;
            transition: transform 0.6s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        .hover-up:hover .bds-img {
            transform: scale(1.08);
        }

        /* CSS Nút Yêu Thích */
        .fav-btn {
            width: 38px;
            height: 38px;
            padding: 0;
            transition: all 0.2s;
            border: 1px solid var(--border);
            z-index: 10;
        }

        .fav-btn:hover {
            color: #e74c3c !important;
            border-color: #e74c3c;
            transform: scale(1.1);
        }

        /* TRẠNG THÁI ĐÃ THÍCH: Trái tim đỏ rực */
        .fav-btn.liked {
            color: #e74c3c !important;
            border-color: #e74c3c;
            background: #fff0f0 !important;
        }

        .fav-btn.liked i {
            font-weight: 900;
        }

        /* Biến viền trái tim (far) thành trái tim đặc (fas) */
    </style>
@endpush
