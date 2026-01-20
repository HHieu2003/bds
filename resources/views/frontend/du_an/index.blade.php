@extends('frontend.layouts.master')
@section('title', 'Danh Sách Dự Án')

@section('content')
    <div class="py-5 bg-dark text-white text-center" style="background: linear-gradient(135deg, #0B2447, #19376D);">
        <h1 class="fw-bold serif-font" data-aos="fade-down">Dự Án Đẳng Cấp</h1>
    </div>

    <div class="container py-5">
        <div class="row g-4">
            @foreach($du_ans as $index => $item)
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                <div class="card border-0 rounded-4 shadow h-100 project-card">
                    <a href="{{ route('du-an.show', $item->slug) }}" class="overflow-hidden rounded-top-4">
                        <img src="{{ $item->hinh_anh ? asset('storage/'.$item->hinh_anh) : 'https://via.placeholder.com/600x400' }}" class="card-img-top" style="height: 250px; object-fit: cover; transition: 0.5s;">
                    </a>
                    <div class="card-body">
                        <h4 class="fw-bold mb-2 serif-font"><a href="{{ route('du-an.show', $item->slug) }}" class="text-dark text-decoration-none">{{ $item->ten_du_an }}</a></h4>
                        <p class="text-muted small mb-3"><i class="fa-solid fa-map-marker-alt me-1 text-warning"></i> {{ $item->dia_chi }}</p>
                        <div class="d-flex justify-content-between border-top pt-3">
                            <span class="badge bg-light text-dark">{{ $item->batDongSans->count() }} căn đang bán</span>
                            <a href="{{ route('du-an.show', $item->slug) }}" class="fw-bold text-primary text-decoration-none">Xem ngay <i class="fa-solid fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-5 d-flex justify-content-center">{{ $du_ans->links() }}</div>
    </div>
@endsection