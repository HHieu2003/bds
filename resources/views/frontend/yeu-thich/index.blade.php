@extends('frontend.layouts.master')

@section('title', 'Bất Động Sản Đã Lưu - Thành Công Land')

@section('content')
    <section class="bg-light pt-4 pb-3 border-bottom">
        <div class="container">
            <div class="d-flex justify-content-between align-items-end flex-wrap gap-3">
                <div>
                    <h2 class="fw-bold serif-font mb-2" style="color: #0F172A;"><i
                            class="fas fa-heart me-2 text-danger"></i>Bất Động Sản Đã Lưu</h2>
                    <p class="text-muted mb-0">Bạn đang có <strong
                            style="color: #FF8C42;">{{ $batDongSans->total() }}</strong> tin được lưu lại.</p>
                </div>
                <a href="{{ route('frontend.bat-dong-san.index') }}" class="btn btn-outline-dark rounded-pill fw-bold">
                    <i class="fas fa-search me-1"></i> Tìm thêm BĐS khác
                </a>
            </div>
        </div>
    </section>

    <section class="py-5" style="background-color: #F8FAFC; min-height: 60vh;">
        <div class="container">
            <div class="row g-4">
                @forelse($batDongSans as $bds)
                    <div class="col-md-4 col-xl-3" data-aos="fade-up" id="bds-card-{{ $bds->id }}">
                        <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden position-relative hover-up">

                            {{-- Nhãn Nhu cầu --}}
                            <div class="position-absolute top-0 start-0 m-2 z-1">
                                <span
                                    class="badge {{ $bds->nhu_cau == 'ban' ? 'bg-danger' : 'bg-success' }} rounded-pill px-3 py-1 shadow-sm">
                                    {{ $bds->nhu_cau == 'ban' ? 'Đang Bán' : 'Cho Thuê' }}
                                </span>
                            </div>

                            {{-- Nút Bỏ Yêu Thích (Gọi hàm JS để xóa) --}}
                            <div class="position-absolute top-0 end-0 m-2 z-1">
                                <button class="btn btn-light rounded-circle shadow-sm text-danger liked"
                                    style="width: 35px; height: 35px; padding: 0;"
                                    onclick="toggleYeuThich(this, {{ $bds->id }})" title="Bỏ lưu tin này">
                                    <i class="fas fa-heart"></i>
                                </button>
                            </div>

                            <a href="{{ route('frontend.bat-dong-san.show', $bds->slug) }}"
                                class="overflow-hidden d-block bg-light" style="height: 200px;">
                                @php $anh = is_array($bds->album_anh) && count($bds->album_anh) > 0 ? $bds->album_anh[0] : null; @endphp
                                <img src="{{ $anh ? asset('storage/' . $anh) : asset('images/default-bds.jpg') }}"
                                    class="card-img-top h-100 w-100 bds-img"
                                    style="object-fit: cover; transition: transform 0.5s;">
                            </a>

                            <div class="card-body p-3 d-flex flex-column">
                                <h5 class="fw-bold mb-2" style="color: #FF8C42;">{{ $bds->gia_hien_thi ?? 'Thỏa thuận' }}
                                </h5>
                                <h6 class="fw-bold mb-2 flex-grow-1">
                                    <a href="{{ route('frontend.bat-dong-san.show', $bds->slug) }}"
                                        class="text-decoration-none text-dark line-clamp-2">
                                        {{ $bds->tieu_de }}
                                    </a>
                                </h6>
                                <hr class="text-muted opacity-25 mt-1 mb-2">
                                <div class="d-flex justify-content-between text-muted small fw-semibold">
                                    <span title="Diện tích"><i class="fas fa-expand me-1" style="color:#FF8C42;"></i>
                                        {{ $bds->dien_tich }}m²</span>
                                    <span title="Phòng ngủ"><i class="fas fa-bed me-1" style="color:#FF8C42;"></i>
                                        {{ $bds->so_phong_ngu }} PN</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <img src="https://cdn-icons-png.flaticon.com/512/7486/7486831.png" alt="Trống"
                            style="width: 100px; opacity: 0.3;" class="mb-3">
                        <h5 class="text-muted fw-bold">Chưa có bất động sản nào được lưu!</h5>
                        <p class="text-muted small">Hãy lướt xem các dự án và bấm vào nút Trái tim để lưu lại những căn bạn
                            ưng ý nhé.</p>
                    </div>
                @endforelse
            </div>

            <div class="d-flex justify-content-center mt-5">
                {{ $batDongSans->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </section>

    <style>
        .hover-up {
            transition: all 0.3s ease;
        }

        .hover-up:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1) !important;
        }

        .hover-up:hover .bds-img {
            transform: scale(1.08);
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
@endsection
