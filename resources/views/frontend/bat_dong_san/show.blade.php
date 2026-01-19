@extends('frontend.layouts.master')
@section('title', $bds->tieu_de)

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('project.show', $bds->duAn->id) }}">{{ $bds->duAn->ten_du_an }}</a></li>
            <li class="breadcrumb-item active">{{ $bds->ma_can }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-8">
            <div id="bdsCarousel" class="carousel slide bg-black rounded-4 overflow-hidden mb-4" data-bs-ride="carousel">
                <div class="carousel-inner" style="height: 500px;">
                    @if($bds->hinh_anh && count($bds->hinh_anh) > 0)
                        @foreach($bds->hinh_anh as $index => $file)
                            @php
                                $ext = pathinfo($file, PATHINFO_EXTENSION);
                                $isVideo = in_array(strtolower($ext), ['mp4', 'mov', 'avi']);
                            @endphp
                            <div class="carousel-item h-100 {{ $index == 0 ? 'active' : '' }}">
                                @if($isVideo)
                                    <video controls class="d-block w-100 h-100" style="object-fit: contain;">
                                        <source src="{{ asset('storage/'.$file) }}">
                                    </video>
                                @else
                                    <img src="{{ asset('storage/'.$file) }}" class="d-block w-100 h-100" style="object-fit: contain;">
                                @endif
                            </div>
                        @endforeach
                    @else
                        <div class="carousel-item active h-100 d-flex align-items-center justify-content-center text-white">Chưa có hình ảnh</div>
                    @endif
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#bdsCarousel" data-bs-slide="prev"><span class="carousel-control-prev-icon"></span></button>
                <button class="carousel-control-next" type="button" data-bs-target="#bdsCarousel" data-bs-slide="next"><span class="carousel-control-next-icon"></span></button>
            </div>

            <div class="card border-0 shadow-sm p-4 mb-4 rounded-4">
                <h2 class="fw-bold mb-3 serif-font">{{ $bds->tieu_de }}</h2>
                <h3 class="text-danger fw-bold mb-3">{{ number_format($bds->gia) }} VNĐ</h3>
                
                <div class="row g-3 mb-4 p-3 bg-light rounded">
                    <div class="col-6 col-md-3"><strong>Tòa:</strong> {{ $bds->toa }}</div>
                    <div class="col-6 col-md-3"><strong>Mã:</strong> {{ $bds->ma_can }}</div>
                    <div class="col-6 col-md-3"><strong>DT:</strong> {{ $bds->dien_tich }}m²</div>
                    <div class="col-6 col-md-3"><strong>Phòng:</strong> {{ $bds->phong_ngu }}</div>
                    <div class="col-6 col-md-3"><strong>Hướng:</strong> {{ $bds->huong_cua }}</div>
                </div>

                <h5 class="fw-bold border-bottom pb-2 mb-3">Mô tả chi tiết</h5>
                <div class="text-secondary" style="white-space: pre-line;">{{ $bds->mo_ta }}</div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-lg p-4 rounded-4 position-sticky" style="top: 100px;">
                <div class="text-center mb-3">
                    <img src="https://ui-avatars.com/api/?name={{ $bds->user->name }}" class="rounded-circle mb-2">
                    <h5 class="fw-bold">{{ $bds->user->name }}</h5>
                    <p class="text-muted small">Chuyên viên tư vấn</p>
                </div>
                <div class="d-grid gap-2">
                    <a href="tel:0912345678" class="btn btn-danger fw-bold py-2"><i class="fa-solid fa-phone me-2"></i> Gọi điện</a>
                    <a href="#" class="btn btn-primary fw-bold py-2">Chat Zalo</a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Khai báo biến toàn cục: Đang ở Căn hộ ID ...
    window.chatContext = { 
        type: 'property', 
        id: {{ $bds->id }} 
    };
</script>
@endpush
@endsection