@extends('frontend.layouts.master')

@php
    $nhuCau = request('nhu_cau', 'ban');
    $title = $nhuCau == 'ban' ? 'Mua Bán Bất Động Sản' : 'Cho Thuê Bất Động Sản';
@endphp

@section('title', $title . ' - Thành Công Land')

@section('content')

    {{-- HEADER TRANG --}}
    <section class="bg-light pt-4 pb-3 border-bottom" style="margin-top: -30px;">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-2">
                    <li class="breadcrumb-item"><a href="{{ route('frontend.home') }}" class="text-decoration-none"
                            style="color: #FF8C42;"><i class="fas fa-home"></i> Trang chủ</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between align-items-end flex-wrap gap-3">
                <h1 class="fw-bold serif-font mb-0" style="color: #0F172A;">{{ $title }}</h1>
                <div class="d-flex align-items-center">
                    <span class="text-muted me-3">Tìm thấy <strong
                            style="color: #FF8C42;">{{ $batDongSans->total() }}</strong> kết quả</span>
                    <form action="{{ route('frontend.bat-dong-san.index') }}" method="GET" id="sortForm">
                        {{-- Giữ lại các param hiện tại khi sort --}}
                        @foreach (request()->except(['sap_xep', 'page']) as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        <select name="sap_xep" class="form-select form-select-sm border-0 bg-white shadow-sm fw-bold"
                            onchange="document.getElementById('sortForm').submit();"
                            style="color: #0F172A; cursor: pointer;">
                            <option value="moi_nhat" {{ request('sap_xep') == 'moi_nhat' ? 'selected' : '' }}>Mới nhất
                            </option>
                            <option value="gia_thap" {{ request('sap_xep') == 'gia_thap' ? 'selected' : '' }}>Giá: Thấp đến
                                cao</option>
                            <option value="gia_cao" {{ request('sap_xep') == 'gia_cao' ? 'selected' : '' }}>Giá: Cao xuống
                                thấp</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>
    </section>

    {{-- NỘI DUNG CHÍNH (SIDEBAR + LƯỚI BĐS) --}}
    <section class="py-5" style="background-color: #F8FAFC; min-height: 70vh;">
        <div class="container">
            <div class="row g-4">

                {{-- CỘT TRÁI: BỘ LỌC (SIDEBAR) --}}
                <div class="col-lg-3">
                    <div class="card border-0 shadow-sm rounded-4 position-sticky" style="top: 100px;">
                        <div
                            class="card-header bg-white border-bottom p-3 d-flex justify-content-between align-items-center rounded-top-4">
                            <h6 class="fw-bold mb-0" style="color: #0F172A;"><i class="fas fa-filter me-2"
                                    style="color: #FF8C42;"></i> Bộ Lọc</h6>
                            <a href="{{ route('frontend.bat-dong-san.index', ['nhu_cau' => $nhuCau]) }}"
                                class="text-danger small text-decoration-none"><i class="fas fa-redo-alt me-1"></i> Xóa
                                lọc</a>
                        </div>
                        <div class="card-body p-3">
                            <form action="{{ route('frontend.bat-dong-san.index') }}" method="GET">
                                <input type="hidden" name="nhu_cau" value="{{ $nhuCau }}">

                                {{-- Lọc Từ khóa --}}
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted">Từ khóa</label>
                                    <input type="text" name="tu_khoa" class="form-control form-control-sm"
                                        placeholder="Nhập tên dự án, đường..." value="{{ request('tu_khoa') }}">
                                </div>

                                {{-- Lọc Khu vực --}}
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted">Khu vực</label>
                                    <select name="khu_vuc" class="form-select form-select-sm">
                                        <option value="">Tất cả</option>
                                        @foreach ($khuVucs as $kv)
                                            <option value="{{ $kv->id }}"
                                                {{ request('khu_vuc') == $kv->id ? 'selected' : '' }}>
                                                {{ $kv->ten_khu_vuc }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Lọc Dự án --}}
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted">Dự án</label>
                                    <select name="du_an" class="form-select form-select-sm">
                                        <option value="">Tất cả</option>
                                        @foreach ($duAns as $da)
                                            <option value="{{ $da->id }}"
                                                {{ request('du_an') == $da->id ? 'selected' : '' }}>
                                                {{ Str::limit($da->ten_du_an, 30) }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Lọc Mức giá --}}
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted">Mức giá</label>
                                    <select name="muc_gia" class="form-select form-select-sm">
                                        <option value="">Tất cả</option>
                                        @if ($nhuCau == 'ban')
                                            <option value="duoi-2" {{ request('muc_gia') == 'duoi-2' ? 'selected' : '' }}>
                                                Dưới 2 Tỷ</option>
                                            <option value="2-5" {{ request('muc_gia') == '2-5' ? 'selected' : '' }}>2 -
                                                5 Tỷ</option>
                                            <option value="5-10" {{ request('muc_gia') == '5-10' ? 'selected' : '' }}>5 -
                                                10 Tỷ</option>
                                            <option value="tren-10"
                                                {{ request('muc_gia') == 'tren-10' ? 'selected' : '' }}>Trên 10 Tỷ</option>
                                        @else
                                            <option value="duoi-10"
                                                {{ request('muc_gia') == 'duoi-10' ? 'selected' : '' }}>Dưới 10 Triệu
                                            </option>
                                            <option value="10-20" {{ request('muc_gia') == '10-20' ? 'selected' : '' }}>10
                                                - 20 Triệu</option>
                                            <option value="20-50" {{ request('muc_gia') == '20-50' ? 'selected' : '' }}>20
                                                - 50 Triệu</option>
                                            <option value="tren-50"
                                                {{ request('muc_gia') == 'tren-50' ? 'selected' : '' }}>Trên 50 Triệu
                                            </option>
                                        @endif
                                    </select>
                                </div>

                                {{-- Lọc Phòng ngủ --}}
                                <div class="mb-4">
                                    <label class="form-label small fw-bold text-muted">Phòng ngủ</label>
                                    <select name="so_phong_ngu" class="form-select form-select-sm">
                                        <option value="">Tất cả</option>
                                        <option value="studio" {{ request('so_phong_ngu') == 'studio' ? 'selected' : '' }}>
                                            Studio</option>
                                        <option value="1" {{ request('so_phong_ngu') == '1' ? 'selected' : '' }}>1
                                            Phòng ngủ</option>
                                        <option value="2" {{ request('so_phong_ngu') == '2' ? 'selected' : '' }}>2
                                            Phòng ngủ</option>
                                        <option value="3" {{ request('so_phong_ngu') == '3' ? 'selected' : '' }}>3
                                            Phòng ngủ trở lên</option>
                                    </select>
                                </div>

                                <button type="submit" class="btn w-100 fw-bold rounded-3 text-white shadow-sm"
                                    style="background-color: #FF8C42;">
                                    Áp Dụng Lọc
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- CỘT PHẢI: LƯỚI KẾT QUẢ BĐS --}}
                <div class="col-lg-9">
                    <div class="row g-4">
                        @forelse($batDongSans as $bds)
                            <div class="col-md-6 col-xl-4" data-aos="fade-up"
                                data-aos-delay="{{ $loop->iteration * 50 }}">
                                {{-- GHI CHÚ: THẺ BĐS CODE TRỰC TIẾP KHÔNG DÙNG PARTIAL --}}
                                <div
                                    class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden position-relative hover-up">
                                    {{-- Nhãn Nổi bật / Nhu cầu --}}
                                    <div class="position-absolute top-0 start-0 m-3 z-1 d-flex gap-1 flex-column">
                                        <span
                                            class="badge {{ $bds->nhu_cau == 'ban' ? 'bg-danger' : 'bg-success' }} rounded-pill px-3 py-2 shadow-sm">
                                            {{ $bds->nhu_cau == 'ban' ? 'Đang Bán' : 'Cho Thuê' }}
                                        </span>
                                        @if ($bds->noi_bat)
                                            <span class="badge bg-warning text-dark rounded-pill px-3 py-1 shadow-sm"><i
                                                    class="fas fa-star me-1"></i>Nổi bật</span>
                                        @endif
                                    </div>

                                    {{-- Nút Yêu thích --}}
                                    <div class="position-absolute top-0 end-0 m-3 z-1 d-flex flex-column gap-2">
                                        <button
                                            class="btn btn-light rounded-circle shadow-sm text-muted heart-icon-{{ $bds->id }} focus-ring"
                                            style="width: 35px; height: 35px; padding: 0;"
                                            onclick="toggleYeuThich(this, {{ $bds->id }})" title="Lưu tin này">
                                            <i class="fas fa-heart"></i>
                                        </button>
                                        <button class="btn btn-light rounded-circle shadow-sm text-muted focus-ring"
                                            style="width: 35px; height: 35px; padding: 0;"
                                            onclick="addSoSanh({{ $bds->id }}, '{{ addslashes($bds->tieu_de) }}')"
                                            title="Thêm vào so sánh">
                                            <i class="fas fa-balance-scale" style="color: #FF8C42;"></i>
                                        </button>
                                    </div>

                                    {{-- Ảnh BĐS --}}
                                    <a href="{{ route('frontend.bat-dong-san.show', $bds->slug) }}"
                                        class="overflow-hidden d-block bg-light" style="height: 220px;">
                                        @php
                                            $anh =
                                                is_array($bds->album_anh) && count($bds->album_anh) > 0
                                                    ? $bds->album_anh[0]
                                                    : null;
                                        @endphp
                                        <img src="{{ $anh ? asset('storage/' . $anh) : asset('images/default-bds.jpg') }}"
                                            class="card-img-top h-100 w-100 bds-img"
                                            style="object-fit: cover; transition: transform 0.5s;"
                                            alt="{{ $bds->tieu_de }}">
                                    </a>

                                    <div class="card-body p-4 d-flex flex-column">
                                        {{-- Giá --}}
                                        <h4 class="fw-bold mb-2" style="color: #FF8C42;">
                                            {{ $bds->gia_hien_thi ?? 'Thỏa thuận' }}</h4>

                                        {{-- Tiêu đề --}}
                                        <h6 class="fw-bold mb-2">
                                            <a href="{{ route('frontend.bat-dong-san.show', $bds->slug) }}"
                                                class="text-decoration-none text-dark line-clamp-2"
                                                title="{{ $bds->tieu_de }}">
                                                {{ $bds->tieu_de }}
                                            </a>
                                        </h6>

                                        {{-- Địa chỉ --}}
                                        <p class="text-muted small mb-3 flex-grow-1">
                                            <i class="fas fa-map-marker-alt me-1 text-secondary"></i>
                                            {{ $bds->duAn ? $bds->duAn->ten_du_an : Str::limit($bds->dia_chi, 35) }}
                                        </p>

                                        <hr class="text-muted opacity-25 mt-0 mb-3">

                                        {{-- Thông số --}}
                                        <div class="d-flex justify-content-between text-muted small fw-semibold">
                                            <span title="Diện tích"><i class="fas fa-expand me-1"
                                                    style="color:#FF8C42;"></i> {{ $bds->dien_tich }} m²</span>
                                            <span title="Phòng ngủ"><i class="fas fa-bed me-1"
                                                    style="color:#FF8C42;"></i> {{ $bds->so_phong_ngu }} PN</span>
                                            <span title="Phòng tắm/WC"><i class="fas fa-bath me-1"
                                                    style="color:#FF8C42;"></i> {{ $bds->so_phong_tam }} WC</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-5 bg-white rounded-4 shadow-sm border border-dashed">
                                <img src="https://cdn-icons-png.flaticon.com/512/7486/7486831.png" alt="Không tìm thấy"
                                    style="width: 100px; opacity: 0.4;" class="mb-3">
                                <h5 class="text-muted fw-bold">Không tìm thấy bất động sản nào!</h5>
                                <p class="text-muted small">Vui lòng thay đổi tiêu chí bộ lọc bên trái hoặc thử một từ khóa
                                    khác.</p>
                                <a href="{{ route('frontend.bat-dong-san.index', ['nhu_cau' => $nhuCau]) }}"
                                    class="btn rounded-pill px-4 mt-2 text-white fw-bold"
                                    style="background-color: #0F172A;">
                                    Hiển thị tất cả
                                </a>
                            </div>
                        @endforelse
                    </div>

                    {{-- Phân trang chuẩn --}}
                    <div class="d-flex justify-content-center mt-5">
                        {{ $batDongSans->links('pagination::bootstrap-5') }}
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

        .hover-up:hover .bds-img {
            transform: scale(1.08);
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .border-dashed {
            border-style: dashed !important;
            border-color: #cbd5e1 !important;
        }
    </style>
@endsection
