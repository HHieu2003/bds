<section class="py-5 bg-white">
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-5" data-aos="fade-up">
            <div>
                <h2 class="fw-bold serif-font mb-2" style="color: #333333;">Nguồn Hàng Mới Nhất</h2>
                <p class="text-muted mb-0">Cập nhật liên tục từ các chủ nhà ký gửi</p>
            </div>

            {{-- Tabs Control --}}
            <ul class="nav nav-pills mt-3 mt-md-0 p-1 bg-light rounded-pill border" id="bdsTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active rounded-pill fw-bold px-4" id="ban-tab" data-bs-toggle="tab"
                        data-bs-target="#ban" type="button" role="tab" style="transition: 0.3s;">
                        Cần Bán
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link rounded-pill fw-bold px-4" id="thue-tab" data-bs-toggle="tab"
                        data-bs-target="#thue" type="button" role="tab" style="transition: 0.3s;">
                        Cho Thuê
                    </button>
                </li>
            </ul>
        </div>

        <div class="tab-content" id="bdsTabContent">
            {{-- TAB: BÁN --}}
            <div class="tab-pane fade show active" id="ban" role="tabpanel" tabindex="0">
                <div class="row g-4">
                    @foreach ($bdsBan as $bds)
                        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                            {{-- THẺ BẤT ĐỘNG SẢN (Gắn trực tiếp không qua file partial) --}}
                            <div
                                class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden position-relative hover-up">
                                <div class="position-absolute top-0 start-0 m-3 z-1">
                                    <span class="badge bg-danger rounded-pill px-3 py-2 shadow-sm">
                                        Đang Bán
                                    </span>
                                </div>
                                <div class="position-absolute top-0 end-0 m-3 z-1">
                                    <button
                                        class="btn btn-light rounded-circle shadow-sm text-muted heart-icon-{{ $bds->id }} focus-ring"
                                        style="width: 35px; height: 35px; padding: 0;"
                                        onclick="toggleFavorite({{ $bds->id }})" title="Lưu tin này">
                                        <i class="fas fa-heart"></i>
                                    </button>
                                </div>
                                <a href="{{ route('frontend.bat-dong-san.show', $bds->slug) }}"
                                    class="overflow-hidden d-block bg-light" style="height: 220px;">
                                    @php
                                        $anh =
                                            is_array($bds->album_anh) && count($bds->album_anh) > 0
                                                ? $bds->album_anh[0]
                                                : null;
                                    @endphp
                                    <img src="{{ $anh ? asset('storage/' . $anh) : asset('images/default-bds.jpg') }}"
                                        class="card-img-top h-100 w-100"
                                        style="object-fit: cover; transition: transform 0.5s;"
                                        onmouseover="this.style.transform='scale(1.1)'"
                                        onmouseout="this.style.transform='scale(1)'" alt="{{ $bds->tieu_de }}">
                                </a>
                                <div class="card-body p-4 d-flex flex-column">
                                    <h4 class="fw-bold mb-2" style="color: #FF8C42;">
                                        {{ $bds->gia_hien_thi ?? 'Thỏa thuận' }}</h4>
                                    <h6 class="fw-bold mb-2">
                                        <a href="{{ route('frontend.bat-dong-san.show', $bds->slug) }}"
                                            class="text-decoration-none text-dark line-clamp-2"
                                            title="{{ $bds->tieu_de }}">
                                            {{ $bds->tieu_de }}
                                        </a>
                                    </h6>
                                    <p class="text-muted small mb-3 flex-grow-1">
                                        <i class="fas fa-map-marker-alt me-1 text-secondary"></i>
                                        {{ $bds->duAn ? $bds->duAn->ten_du_an : Str::limit($bds->dia_chi, 35) }}
                                    </p>
                                    <hr class="text-muted opacity-25 mt-0 mb-3">
                                    <div class="d-flex justify-content-between text-muted small fw-semibold">
                                        <span title="Diện tích"><i class="fas fa-expand me-1"></i>
                                            {{ $bds->dien_tich }} m²</span>
                                        <span title="Phòng ngủ"><i class="fas fa-bed me-1"></i>
                                            {{ $bds->so_phong_ngu }} PN</span>
                                        <span title="Phòng tắm/WC"><i class="fas fa-bath me-1"></i>
                                            {{ $bds->so_phong_tam }} WC</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="text-center mt-5">
                    <a href="{{ route('frontend.tim-kiem.index') }}?loai=ban"
                        class="btn btn-outline-primary rounded-pill px-4 fw-bold">Xem thêm nhà bán <i
                            class="fas fa-arrow-right ms-2"></i></a>
                </div>
            </div>

            {{-- TAB: THUÊ --}}
            <div class="tab-pane fade" id="thue" role="tabpanel" tabindex="0">
                <div class="row g-4">
                    @foreach ($bdsThue as $bds)
                        <div class="col-lg-4 col-md-6">
                            {{-- THẺ BẤT ĐỘNG SẢN (Gắn trực tiếp không qua file partial) --}}
                            <div
                                class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden position-relative hover-up">
                                <div class="position-absolute top-0 start-0 m-3 z-1">
                                    <span class="badge bg-success rounded-pill px-3 py-2 shadow-sm">
                                        Cho Thuê
                                    </span>
                                </div>
                                <div class="position-absolute top-0 end-0 m-3 z-1">
                                    <button
                                        class="btn btn-light rounded-circle shadow-sm text-muted heart-icon-{{ $bds->id }} focus-ring"
                                        style="width: 35px; height: 35px; padding: 0;"
                                        onclick="toggleFavorite({{ $bds->id }})" title="Lưu tin này">
                                        <i class="fas fa-heart"></i>
                                    </button>
                                </div>
                                <a href="{{ route('frontend.bat-dong-san.show', $bds->slug) }}"
                                    class="overflow-hidden d-block bg-light" style="height: 220px;">
                                    @php
                                        $anh =
                                            is_array($bds->album_anh) && count($bds->album_anh) > 0
                                                ? $bds->album_anh[0]
                                                : null;
                                    @endphp
                                    <img src="{{ $anh ? asset('storage/' . $anh) : asset('images/default-bds.jpg') }}"
                                        class="card-img-top h-100 w-100"
                                        style="object-fit: cover; transition: transform 0.5s;"
                                        onmouseover="this.style.transform='scale(1.1)'"
                                        onmouseout="this.style.transform='scale(1)'" alt="{{ $bds->tieu_de }}">
                                </a>
                                <div class="card-body p-4 d-flex flex-column">
                                    <h4 class="fw-bold mb-2" style="color: #FF8C42;">
                                        {{ $bds->gia_hien_thi ?? 'Thỏa thuận' }}</h4>
                                    <h6 class="fw-bold mb-2">
                                        <a href="{{ route('frontend.bat-dong-san.show', $bds->slug) }}"
                                            class="text-decoration-none text-dark line-clamp-2"
                                            title="{{ $bds->tieu_de }}">
                                            {{ $bds->tieu_de }}
                                        </a>
                                    </h6>
                                    <p class="text-muted small mb-3 flex-grow-1">
                                        <i class="fas fa-map-marker-alt me-1 text-secondary"></i>
                                        {{ $bds->duAn ? $bds->duAn->ten_du_an : Str::limit($bds->dia_chi, 35) }}
                                    </p>
                                    <hr class="text-muted opacity-25 mt-0 mb-3">
                                    <div class="d-flex justify-content-between text-muted small fw-semibold">
                                        <span title="Diện tích"><i class="fas fa-expand me-1"></i>
                                            {{ $bds->dien_tich }} m²</span>
                                        <span title="Phòng ngủ"><i class="fas fa-bed me-1"></i>
                                            {{ $bds->so_phong_ngu }} PN</span>
                                        <span title="Phòng tắm/WC"><i class="fas fa-bath me-1"></i>
                                            {{ $bds->so_phong_tam }} WC</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="text-center mt-5">
                    <a href="{{ route('frontend.tim-kiem.index') }}?loai=thue"
                        class="btn btn-outline-primary rounded-pill px-4 fw-bold">Xem thêm nhà cho thuê <i
                            class="fas fa-arrow-right ms-2"></i></a>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    /* CSS cho tab để khi active nó đổi màu cam */
    #bdsTab .nav-link.active {
        background-color: #FF8C42;
        color: white;
    }

    #bdsTab .nav-link {
        color: #64748B;
    }

    /* CSS cắt chữ nếu tiêu đề quá dài */
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
