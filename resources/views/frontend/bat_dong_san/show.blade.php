@extends('frontend.layouts.master')

@section('content')

@if(!$batDongSan)
    <div class="container py-5 mt-4">
        <div class="alert alert-danger" role="alert">
            <h4 class="alert-heading">Không tìm thấy bất động sản!</h4>
            <p>Bất động sản bạn đang tìm kiếm không tồn tại hoặc đã bị xóa.</p>
            <hr>
            <p class="mb-0"><a href="{{ route('home') }}" class="btn btn-primary">Quay lại trang chủ</a></p>
        </div>
    </div>
@else

<style>
    /* CSS cho nội dung từ CKEditor */
    .bds-description img {
        max-width: 100% !important;
        height: auto !important;
        border-radius: 8px;
        margin: 10px 0;
    }
    .bds-description table {
        width: 100% !important;
        border-collapse: collapse;
        margin: 15px 0;
    }
    .bds-description td, .bds-description th {
        border: 1px solid #dee2e6;
        padding: 8px;
    }
</style>

<div class="container py-5 mt-4">
    <div class="row">
        {{-- CỘT TRÁI: HÌNH ẢNH & THÔNG TIN --}}
        <div class="col-lg-8">
            {{-- Slider Hình Ảnh --}}
            @if($batDongSan->hinh_anh && is_array($batDongSan->hinh_anh) && count($batDongSan->hinh_anh) > 0)
                <div id="carouselBDS" class="carousel slide mb-4 rounded-4 overflow-hidden shadow" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @foreach($batDongSan->hinh_anh as $key => $img)
                            <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                <img src="{{ asset($img) }}" class="d-block w-100" style="height: 500px; object-fit: cover;" alt="Hình ảnh {{ $batDongSan->ma_can }}">
                            </div>
                        @endforeach
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselBDS" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon bg-dark rounded-circle p-3" aria-hidden="true"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselBDS" data-bs-slide="next">
                        <span class="carousel-control-next-icon bg-dark rounded-circle p-3" aria-hidden="true"></span>
                    </button>
                </div>
            @endif

            <h1 class="fw-bold mb-2 text-dark">{{ $batDongSan->tieu_de }}</h1>
            <p class="text-muted"><i class="fas fa-map-marker-alt me-2"></i> {{ $batDongSan->duAn ? $batDongSan->duAn->ten_du_an : 'Đang cập nhật' }} - {{ $batDongSan->toa }}</p>
            
            <div class="d-flex gap-4 mb-4 border-top border-bottom py-3">
                <div class="text-center">
                    <span class="d-block text-muted small">Mức giá</span>
                    <span class="fw-bold text-danger fs-5">{{ number_format($batDongSan->gia, 2) }} Tỷ</span>
                </div>
                <div class="text-center border-start ps-4">
                    <span class="d-block text-muted small">Diện tích</span>
                    <span class="fw-bold fs-5">{{ $batDongSan->dien_tich }} m²</span>
                </div>
                <div class="text-center border-start ps-4">
                    <span class="d-block text-muted small">Phòng ngủ</span>
                    <span class="fw-bold fs-5">{{ $batDongSan->so_phong_ngu }} PN</span>
                </div>
            </div>

            <h4 class="fw-bold mb-3 border-start border-4 border-primary ps-3">Thông Tin Chi Tiết</h4>
            <div class="row mb-4 g-3">
                <div class="col-md-6"><i class="fas fa-barcode me-2 text-muted"></i> Mã căn: <strong>{{ $batDongSan->ma_can }}</strong></div>
                <div class="col-md-6"><i class="fas fa-compass me-2 text-muted"></i> Hướng cửa: <strong>{{ $batDongSan->huong_cua }}</strong></div>
                <div class="col-md-6"><i class="fas fa-bath me-2 text-muted"></i> Phòng tắm: <strong>{{ $batDongSan->so_phong_tam }} WC</strong></div>
                <div class="col-md-6"><i class="fas fa-building me-2 text-muted"></i> Loại hình: <strong>{{ ucfirst($batDongSan->loai_hinh) }}</strong></div>
            </div>

            <h4 class="fw-bold mb-3 border-start border-4 border-primary ps-3">Mô Tả</h4>
            <div class="bds-description text-justify fs-6" style="line-height: 1.7;">
                {!! $batDongSan->mo_ta !!}
            </div>
        </div>

        {{-- CỘT PHẢI: FORM LIÊN HỆ --}}
        <div class="col-lg-4">
            <div class="card shadow border-0 sticky-top" style="top: 100px; z-index: 10;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4">
                        <img src="https://ui-avatars.com/api/?name={{ $batDongSan->user->name ?? 'Admin' }}&background=random" class="rounded-circle me-3" width="60">
                        <div>
                            <h5 class="fw-bold mb-0">{{ $batDongSan->user->name ?? 'Chuyên viên tư vấn' }}</h5>
                            <small class="text-muted">Nhà môi giới chuyên nghiệp</small>
                        </div>
                    </div>

                    <a href="tel:0909000000" class="btn btn-danger w-100 py-2 mb-2 fw-bold">
                        <i class="fas fa-phone-alt me-2"></i> 0909.000.000
                    </a>
                    <a href="https://zalo.me/0909000000" class="btn btn-primary w-100 py-2 mb-3 fw-bold">
                        <i class="fas fa-comment me-2"></i> Chat Zalo
                    </a>

                    <hr>
                    <h5 class="fw-bold text-center mb-3">Đăng ký xem nhà</h5>
                    <form action="{{ route('lich-hen.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="bat_dong_san_id" value="{{ $batDongSan->id }}">
                        <div class="mb-3">
                            <input type="text" name="ho_ten" class="form-control" placeholder="Họ và tên của bạn" required>
                        </div>
                        <div class="mb-3">
                            <input type="text" name="so_dien_thoai" class="form-control" placeholder="Số điện thoại" required>
                        </div>
                        <div class="mb-3">
                            <input type="datetime-local" name="thoi_gian_hen" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-dark w-100">Gửi Yêu Cầu</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection