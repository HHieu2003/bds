<section class="py-5" style="background-color: #F8FAFC;">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="fw-bold serif-font mb-2" style="color: #333333;">Bất Động Sản Nổi Bật</h2>
            <p class="text-muted">Những căn hộ, nhà đất vị trí đẹp, giá tốt nhất tuần qua</p>
        </div>

        <div class="row g-4">
            @forelse($bdsNoiBat as $bds)
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                    <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden position-relative hover-up">
                        {{-- Nhãn Nhu cầu (Bán/Thuê) --}}
                        <div class="position-absolute top-0 start-0 m-3 z-1">
                            <span
                                class="badge {{ $bds->nhu_cau == 'ban' ? 'bg-danger' : 'bg-success' }} rounded-pill px-3 py-2 shadow-sm">
                                {{ $bds->nhu_cau == 'ban' ? 'Đang Bán' : 'Cho Thuê' }}
                            </span>
                        </div>

                        {{-- Ảnh đại diện (Lấy ảnh đầu tiên trong mảng album_anh hoặc ảnh mặc định) --}}
                        <a href="{{ route('frontend.bat-dong-san.show', $bds->slug) }}" class="overflow-hidden d-block"
                            style="height: 220px;">
                            @php
                                $anh =
                                    is_array($bds->album_anh) && count($bds->album_anh) > 0 ? $bds->album_anh[0] : null;
                            @endphp
                            <img src="{{ $anh ? asset('storage/' . $anh) : asset('images/default-bds.jpg') }}"
                                class="card-img-top h-100 w-100" style="object-fit: cover; transition: transform 0.5s;"
                                onmouseover="this.style.transform='scale(1.1)'"
                                onmouseout="this.style.transform='scale(1)'" alt="{{ $bds->tieu_de }}">
                        </a>

                        <div class="card-body p-4">
                            {{-- Giá hiển thị --}}
                            <h4 class="fw-bold mb-2" style="color: #FF8C42;">{{ $bds->gia_hien_thi ?? 'Thỏa thuận' }}
                            </h4>

                            {{-- Tiêu đề --}}
                            <h6 class="fw-bold mb-2">
                                <a href="{{ route('frontend.bat-dong-san.show', $bds->slug) }}"
                                    class="text-decoration-none text-dark" title="{{ $bds->tieu_de }}">
                                    {{ Str::limit($bds->tieu_de, 45) }}
                                </a>
                            </h6>

                            {{-- Địa chỉ/Dự án --}}
                            <p class="text-muted small mb-3">
                                <i class="fas fa-map-marker-alt me-1 text-secondary"></i>
                                {{ $bds->duAn ? $bds->duAn->ten_du_an : Str::limit($bds->dia_chi, 35) }}
                            </p>

                            <hr class="text-muted opacity-25">

                            {{-- Thông số cơ bản --}}
                            <div class="d-flex justify-content-between text-muted small fw-semibold">
                                <span><i class="fas fa-expand me-1"></i> {{ $bds->dien_tich }} m²</span>
                                <span><i class="fas fa-bed me-1"></i> {{ $bds->so_phong_ngu }} PN</span>
                                <span><i class="fas fa-bath me-1"></i> {{ $bds->so_phong_tam }} WC</span>
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
