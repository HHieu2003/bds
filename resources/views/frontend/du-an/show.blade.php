@extends('frontend.layouts.master')

@section('title', $duAn->ten_du_an . ' - Thành Công Land')

@section('content')

    {{-- 1. HERO BANNER --}}
    <section class="position-relative d-flex align-items-end pb-5"
        style="height: 60vh; background: linear-gradient(to top, rgba(15, 23, 42, 0.95) 0%, rgba(15, 23, 42, 0.4) 50%, rgba(0,0,0,0.1) 100%), url('{{ $duAn->hinh_anh ? asset('storage/' . $duAn->hinh_anh) : 'https://vinhomesland.vn/wp-content/uploads/2021/04/phoi-canh-vinhomes-smart-city.jpg' }}') center/cover fixed;">
        <div class="container position-relative z-1" data-aos="fade-up">
            <span class="badge mb-3 px-3 py-2 fs-6 rounded-pill" style="background-color: #FF8C42; color: white;">
                <i class="fas fa-star me-1 text-warning"></i> Dự Án Trọng Điểm
            </span>
            <h1 class="display-4 fw-bold text-white serif-font mb-2">{{ $duAn->ten_du_an }}</h1>
            <p class="fs-5 text-light opacity-75 mb-0">
                <i class="fas fa-map-marker-alt me-2" style="color: #FF8C42;"></i>{{ $duAn->dia_chi }}
            </p>
        </div>
    </section>

    {{-- LẤY DỮ LIỆU QUỸ CĂN CỦA DỰ ÁN --}}
    @php
        // Phân loại BĐS thuộc dự án này thành Bán và Thuê
        $bdsBan = $duAn->batDongSans ? $duAn->batDongSans->where('nhu_cau', 'ban')->where('hien_thi', 1) : collect();
        $bdsThue = $duAn->batDongSans ? $duAn->batDongSans->where('nhu_cau', 'thue')->where('hien_thi', 1) : collect();
    @endphp

    {{-- 2. NỘI DUNG CHÍNH --}}
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row g-5">

                {{-- CỘT TRÁI: THÔNG TIN CHI TIẾT --}}
                <div class="col-lg-8">

                    {{-- Box Tổng quan --}}
                    <div class="card border-0 shadow-sm rounded-4 p-4 mb-5" data-aos="fade-up">
                        <h4 class="fw-bold serif-font mb-4"
                            style="color: #0F172A; border-left: 4px solid #FF8C42; padding-left: 10px;">Tổng Quan Dự Án</h4>
                        <div class="row g-4">
                            <div class="col-sm-6 d-flex">
                                <div class="icon-box me-3 mt-1" style="color: #FF8C42; font-size: 1.5rem;"><i
                                        class="fas fa-building"></i></div>
                                <div>
                                    <span class="d-block text-muted small text-uppercase fw-bold">Chủ đầu tư</span>
                                    <span class="fw-semibold text-dark">{{ $duAn->chu_dau_tu ?? 'Đang cập nhật' }}</span>
                                </div>
                            </div>
                            <div class="col-sm-6 d-flex">
                                <div class="icon-box me-3 mt-1" style="color: #FF8C42; font-size: 1.5rem;"><i
                                        class="fas fa-chart-area"></i></div>
                                <div>
                                    <span class="d-block text-muted small text-uppercase fw-bold">Tổng diện tích</span>
                                    <span class="fw-semibold text-dark">{{ $duAn->quy_mo ?? 'Đang cập nhật' }}</span>
                                </div>
                            </div>
                            <div class="col-sm-6 d-flex">
                                <div class="icon-box me-3 mt-1" style="color: #FF8C42; font-size: 1.5rem;"><i
                                        class="fas fa-file-contract"></i></div>
                                <div>
                                    <span class="d-block text-muted small text-uppercase fw-bold">Pháp lý</span>
                                    <span class="fw-semibold text-dark">{{ $duAn->phap_ly ?? 'Sổ hồng lâu dài' }}</span>
                                </div>
                            </div>
                            <div class="col-sm-6 d-flex">
                                <div class="icon-box me-3 mt-1" style="color: #FF8C42; font-size: 1.5rem;"><i
                                        class="fas fa-calendar-check"></i></div>
                                <div>
                                    <span class="d-block text-muted small text-uppercase fw-bold">Tình trạng</span>
                                    <span class="fw-semibold text-dark">{{ $duAn->trang_thai ?? 'Đang mở bán' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Box Mô tả chi tiết --}}
                    <div class="card border-0 shadow-sm rounded-4 p-4 mb-5" data-aos="fade-up">
                        <h4 class="fw-bold serif-font mb-4"
                            style="color: #0F172A; border-left: 4px solid #FF8C42; padding-left: 10px;">Thông Tin Chi Tiết
                        </h4>
                        <div class="content-body text-justify text-muted" style="line-height: 1.8;">
                            @if ($duAn->noi_dung)
                                {!! $duAn->noi_dung !!}
                            @else
                                <p>Đang cập nhật thông tin chi tiết về dự án này...</p>
                            @endif
                        </div>
                    </div>

                    {{-- QUỸ CĂN ĐANG MỞ BÁN / CHO THUÊ THUỘC DỰ ÁN --}}
                    <div class="mb-5" id="quy-can" data-aos="fade-up">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
                            <h4 class="fw-bold serif-font mb-0"
                                style="color: #0F172A; border-left: 4px solid #FF8C42; padding-left: 10px;">Quỹ Căn Chuyển
                                Nhượng & Cho Thuê</h4>

                            {{-- Tab Bán/Thuê --}}
                            <ul class="nav nav-pills mt-3 mt-md-0 p-1 bg-white rounded-pill shadow-sm" id="projectBdsTab"
                                role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active rounded-pill fw-bold px-4" id="p-ban-tab"
                                        data-bs-toggle="tab" data-bs-target="#p-ban" type="button" role="tab">Cần Bán
                                        ({{ $bdsBan->count() }})</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link rounded-pill fw-bold px-4" id="p-thue-tab" data-bs-toggle="tab"
                                        data-bs-target="#p-thue" type="button" role="tab">Cho Thuê
                                        ({{ $bdsThue->count() }})</button>
                                </li>
                            </ul>
                        </div>

                        <div class="tab-content" id="projectBdsTabContent">
                            {{-- TAB BÁN --}}
                            <div class="tab-pane fade show active" id="p-ban" role="tabpanel">
                                <div class="row g-4">
                                    @forelse($bdsBan as $bds)
                                        <div class="col-md-6">
                                            {{-- GHI CHÚ: MÃ HTML THẺ BĐS ĐƯỢC VIẾT TRỰC TIẾP TẠI ĐÂY (KHÔNG DÙNG PARTIAL) --}}
                                            <div
                                                class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden position-relative hover-up">
                                                <div class="position-absolute top-0 start-0 m-3 z-1">
                                                    <span class="badge bg-danger rounded-pill px-3 py-2 shadow-sm">Đang
                                                        Bán</span>
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
                                                    @php $anh = is_array($bds->album_anh) && count($bds->album_anh) > 0 ? $bds->album_anh[0] : null; @endphp
                                                    <img src="{{ $anh ? asset('storage/' . $anh) : asset('images/default-bds.jpg') }}"
                                                        class="card-img-top h-100 w-100 project-img"
                                                        style="object-fit: cover; transition: transform 0.5s;"
                                                        alt="{{ $bds->tieu_de }}">
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
                                                    <hr class="text-muted opacity-25 mt-auto mb-3">
                                                    <div
                                                        class="d-flex justify-content-between text-muted small fw-semibold">
                                                        <span title="Diện tích"><i
                                                                class="fas fa-expand me-1 text-secondary"></i>
                                                            {{ $bds->dien_tich }} m²</span>
                                                        <span title="Phòng ngủ"><i
                                                                class="fas fa-bed me-1 text-secondary"></i>
                                                            {{ $bds->so_phong_ngu }} PN</span>
                                                        <span title="Phòng tắm/WC"><i
                                                                class="fas fa-bath me-1 text-secondary"></i>
                                                            {{ $bds->so_phong_tam }} WC</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-12 text-center py-4 bg-white rounded-4 border border-dashed">
                                            <p class="text-muted mb-0">Chưa có căn hộ nào đang rao bán tại dự án này.</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            {{-- TAB THUÊ --}}
                            <div class="tab-pane fade" id="p-thue" role="tabpanel">
                                <div class="row g-4">
                                    @forelse($bdsThue as $bds)
                                        <div class="col-md-6">
                                            {{-- GHI CHÚ: MÃ HTML THẺ BĐS ĐƯỢC VIẾT TRỰC TIẾP TẠI ĐÂY (KHÔNG DÙNG PARTIAL) --}}
                                            <div
                                                class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden position-relative hover-up">
                                                <div class="position-absolute top-0 start-0 m-3 z-1">
                                                    <span class="badge bg-success rounded-pill px-3 py-2 shadow-sm">Cho
                                                        Thuê</span>
                                                </div>
                                                <div class="position-absolute top-0 end-0 m-3 z-1">
                                                    <button
                                                        class="btn btn-light rounded-circle shadow-sm text-muted heart-icon-{{ $bds->id }} focus-ring"
                                                        style="width: 35px; height: 35px; padding: 0;"
                                                        onclick="toggleFavorite({{ $bds->id }})"
                                                        title="Lưu tin này">
                                                        <i class="fas fa-heart"></i>
                                                    </button>
                                                </div>
                                                <a href="{{ route('frontend.bat-dong-san.show', $bds->slug) }}"
                                                    class="overflow-hidden d-block bg-light" style="height: 220px;">
                                                    @php $anh = is_array($bds->album_anh) && count($bds->album_anh) > 0 ? $bds->album_anh[0] : null; @endphp
                                                    <img src="{{ $anh ? asset('storage/' . $anh) : asset('images/default-bds.jpg') }}"
                                                        class="card-img-top h-100 w-100 project-img"
                                                        style="object-fit: cover; transition: transform 0.5s;"
                                                        alt="{{ $bds->tieu_de }}">
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
                                                    <hr class="text-muted opacity-25 mt-auto mb-3">
                                                    <div
                                                        class="d-flex justify-content-between text-muted small fw-semibold">
                                                        <span title="Diện tích"><i
                                                                class="fas fa-expand me-1 text-secondary"></i>
                                                            {{ $bds->dien_tich }} m²</span>
                                                        <span title="Phòng ngủ"><i
                                                                class="fas fa-bed me-1 text-secondary"></i>
                                                            {{ $bds->so_phong_ngu }} PN</span>
                                                        <span title="Phòng tắm/WC"><i
                                                                class="fas fa-bath me-1 text-secondary"></i>
                                                            {{ $bds->so_phong_tam }} WC</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-12 text-center py-4 bg-white rounded-4 border border-dashed">
                                            <p class="text-muted mb-0">Chưa có căn hộ nào đang cho thuê tại dự án này.</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- CỘT PHẢI: STICKY SIDEBAR (FORM LIÊN HỆ) --}}
                <div class="col-lg-4">
                    <div class="position-sticky" style="top: 120px;" data-aos="fade-left">
                        <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                            <div class="p-4 text-center text-white"
                                style="background: linear-gradient(135deg, #0F172A, #1A2948);">
                                <h5 class="fw-bold mb-1">Nhận Bảng Giá & Ưu Đãi</h5>
                                <p class="small opacity-75 mb-0">Chuyên viên sẽ liên hệ với bạn trong vòng 5 phút.</p>
                            </div>
                            <div class="card-body p-4 bg-white">
                                <form action="#" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <input type="text" class="form-control rounded-3"
                                            placeholder="Họ và tên của bạn *" required
                                            style="border-color: #e2e8f0; padding: 12px 15px;">
                                    </div>
                                    <div class="mb-3">
                                        <input type="text" class="form-control rounded-3"
                                            placeholder="Số điện thoại *" required
                                            style="border-color: #e2e8f0; padding: 12px 15px;">
                                    </div>
                                    <div class="mb-4">
                                        <textarea class="form-control rounded-3" rows="3" placeholder="Nội dung cần tư vấn..."
                                            style="border-color: #e2e8f0; padding: 12px 15px;">Tôi quan tâm đến dự án {{ $duAn->ten_du_an }}, vui lòng gửi thông tin chi tiết.</textarea>
                                    </div>
                                    <button type="button"
                                        class="btn w-100 rounded-pill fw-bold py-3 text-white shadow-sm hover-up"
                                        style="background-color: #FF8C42; border: none;">
                                        Gửi Yêu Cầu <i class="fas fa-paper-plane ms-2"></i>
                                    </button>
                                </form>

                                <div class="d-flex align-items-center justify-content-center mt-4 pt-3 border-top">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white me-3 shadow-sm"
                                        style="width: 45px; height: 45px; background-color: #25D366;">
                                        <i class="fab fa-whatsapp fs-4"></i>
                                    </div>
                                    <div>
                                        <span class="d-block small text-muted fw-semibold">Hotline tư vấn 24/7</span>
                                        <a href="tel:0912345678" class="fs-5 fw-bold text-dark text-decoration-none">0912
                                            345 678</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Nút Liên kết mỏ neo xuống Quỹ căn --}}
                        <a href="#quy-can" class="btn btn-outline-dark w-100 rounded-pill fw-bold py-3 mt-4 hover-up"
                            style="border-color: #0F172A;">
                            Xem {{ $bdsBan->count() + $bdsThue->count() }} Căn Đang Bán/Thuê <i
                                class="fas fa-arrow-down ms-1"></i>
                        </a>
                    </div>
                </div>

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

        .hover-up:hover .project-img {
            transform: scale(1.08);
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .content-body img {
            max-width: 100% !important;
            height: auto !important;
            border-radius: 10px;
            margin: 15px 0;
        }

        /* Style cho Tab Quỹ căn */
        #projectBdsTab .nav-link {
            color: #64748B;
            padding: 10px 20px;
        }

        #projectBdsTab .nav-link.active {
            background-color: #FF8C42;
            color: white;
        }

        .border-dashed {
            border-style: dashed !important;
            border-color: #cbd5e1 !important;
        }
    </style>
@endsection
