@extends('frontend.layouts.master')

@section('content')
<div class="container py-5 mt-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Trang chủ</a></li>
            <li class="breadcrumb-item active text-dark fw-bold" aria-current="page">Tìm kiếm bất động sản</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-3 mb-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <h5 class="fw-bold mb-3 text-primary"><i class="fas fa-filter me-2"></i>Bộ Lọc Tìm Kiếm</h5>
                
                <form action="{{ route('tim-kiem') }}" method="GET" id="filterForm">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Từ khóa</label>
                        <input type="text" name="tu_khoa" class="form-control rounded-pill" value="{{ request('tu_khoa') }}" placeholder="Tên dự án, địa chỉ...">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Dự án</label>
                        <select name="du_an" class="form-select rounded-pill">
                            <option value="">-- Tất cả dự án --</option>
                            @foreach($du_ans as $da)
                                <option value="{{ $da->id }}" {{ request('du_an') == $da->id ? 'selected' : '' }}>
                                    {{ $da->ten_du_an }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Loại hình</label>
                        <select name="loai_hinh" class="form-select rounded-pill">
                            <option value="">-- Tất cả --</option>
                            <option value="can_ho" {{ request('loai_hinh') == 'can_ho' ? 'selected' : '' }}>Căn hộ chung cư</option>
                            <option value="nha_dat" {{ request('loai_hinh') == 'nha_dat' ? 'selected' : '' }}>Nhà đất thổ cư</option>
                            <option value="biet_thu" {{ request('loai_hinh') == 'biet_thu' ? 'selected' : '' }}>Biệt thự</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Mức giá</label>
                        <div class="d-flex flex-column gap-2">
                            @foreach(['duoi-2'=>'Dưới 2 Tỷ', '2-5'=>'2 - 5 Tỷ', '5-8'=>'5 - 8 Tỷ', '8-15'=>'8 - 15 Tỷ', 'tren-15'=>'Trên 15 Tỷ'] as $val => $label)
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="muc_gia" value="{{ $val }}" id="price_{{ $val }}" {{ request('muc_gia') == $val ? 'checked' : '' }}>
                                <label class="form-check-label" for="price_{{ $val }}">{{ $label }}</label>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Diện tích</label>
                        <select name="dien_tich" class="form-select rounded-pill">
                            <option value="">-- Chọn diện tích --</option>
                            <option value="duoi-50" {{ request('dien_tich') == 'duoi-50' ? 'selected' : '' }}>Dưới 50m²</option>
                            <option value="50-80" {{ request('dien_tich') == '50-80' ? 'selected' : '' }}>50 - 80m²</option>
                            <option value="80-120" {{ request('dien_tich') == '80-120' ? 'selected' : '' }}>80 - 120m²</option>
                            <option value="tren-120" {{ request('dien_tich') == 'tren-120' ? 'selected' : '' }}>Trên 120m²</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted">Hướng nhà</label>
                        <select name="huong_nha" class="form-select rounded-pill">
                            <option value="">-- Tất cả hướng --</option>
                            @foreach(['Đông', 'Tây', 'Nam', 'Bắc', 'Đông Nam', 'Đông Bắc', 'Tây Nam', 'Tây Bắc'] as $huong)
                                <option value="{{ $huong }}" {{ request('huong_nha') == $huong ? 'selected' : '' }}>{{ $huong }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold">
                        <i class="fas fa-search me-2"></i> Áp Dụng Lọc
                    </button>
                    <a href="{{ route('tim-kiem') }}" class="btn btn-outline-secondary w-100 rounded-pill mt-2">Xóa bộ lọc</a>
                </form>
            </div>
        </div>

        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4 bg-white p-3 rounded-4 shadow-sm">
                <h5 class="mb-0 fw-bold">Tìm thấy <span class="text-primary">{{ $batDongSans->total() }}</span> bất động sản</h5>
                
                <form method="GET" class="d-flex align-items-center">
                    @foreach(request()->except('sort', 'page') as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach
                    
                    <label class="me-2 small text-muted text-nowrap">Sắp xếp:</label>
                    <select name="sort" class="form-select form-select-sm rounded-pill border-0 bg-light" onchange="this.form.submit()">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá thấp đến cao</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá cao đến thấp</option>
                    </select>
                </form>
            </div>

            @if($batDongSans->count() > 0)
                <div class="row g-4">
                    @foreach($batDongSans as $bds)
                    <div class="col-md-6 col-xl-4">
                        <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden product-card">
                            <div class="position-relative">
                                <a href="{{ route('bat-dong-san.show', $bds->slug) }}">
                                    <img src="{{ asset($bds->hinh_anh ?? 'images/no-image.jpg') }}" class="card-img-top" style="height: 220px; object-fit: cover;">
                                </a>
                                <span class="badge bg-danger position-absolute top-0 start-0 m-3">{{ $bds->loai_hinh == 'can_ho' ? 'Chung cư' : 'Nhà đất' }}</span>
                                <span class="badge bg-dark bg-opacity-75 position-absolute bottom-0 end-0 m-3">
                                    <i class="fas fa-camera"></i> {{ count(json_decode($bds->album_anh) ?? []) + 1 }}
                                </span>
                            </div>
                            <div class="card-body">
                                <h6 class="card-title fw-bold text-truncate mb-2">
                                    <a href="{{ route('bat-dong-san.show', $bds->slug) }}" class="text-decoration-none text-dark">{{ $bds->tieu_de }}</a>
                                </h6>
                                <p class="text-danger fw-bold fs-5 mb-2">{{ number_format($bds->gia, 2) }} Tỷ</p>
                                <div class="d-flex justify-content-between text-muted small mb-3">
                                    <span><i class="fas fa-vector-square me-1"></i> {{ $bds->dien_tich }} m²</span>
                                    <span><i class="fas fa-bed me-1"></i> {{ $bds->so_phong_ngu }} PN</span>
                                    <span><i class="fas fa-compass me-1"></i> {{ $bds->huong_nha ?? 'KXĐ' }}</span>
                                </div>
                                <p class="text-muted small mb-0 text-truncate">
                                    <i class="fas fa-map-marker-alt me-1 text-primary"></i> 
                                    {{ $bds->duAn->ten_du_an ?? $bds->duAn->dia_chi ?? 'Đang cập nhật' }}
                                </p>
                            </div>
                            <div class="card-footer bg-white border-top-0 pt-0 pb-3">
                                <a href="{{ route('bat-dong-san.show', $bds->slug) }}" class="btn btn-outline-primary w-100 rounded-pill btn-sm">Xem chi tiết</a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <div class="mt-5 d-flex justify-content-center">
                    {{ $batDongSans->links() }}
                </div>
            @else
                <div class="text-center py-5 bg-white rounded-4 shadow-sm">
                    <img src="https://cdn-icons-png.flaticon.com/512/6134/6134065.png" width="100" class="mb-3 opacity-50">
                    <h5 class="text-muted fw-bold">Không tìm thấy bất động sản nào!</h5>
                    <p class="text-muted">Hãy thử thay đổi tiêu chí lọc hoặc xóa bộ lọc để tìm lại.</p>
                    <a href="{{ route('tim-kiem') }}" class="btn btn-primary rounded-pill px-4">Xóa bộ lọc</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection