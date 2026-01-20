@extends('frontend.layouts.master')

@section('content')
<div class="container py-5 mt-5">
    <h1 class="text-center serif-font fw-bold mb-5" style="color: #0F172A;">Tin Tức & Phong Thủy</h1>
    
    <div class="row g-4">
        @foreach($bai_viets as $tin)
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                <img src="{{ asset($tin->hinh_anh ?? 'images/no-image.jpg') }}" class="card-img-top" alt="{{ $tin->tieu_de }}" style="height: 200px; object-fit: cover;">
                <div class="card-body">
                    <span class="badge bg-warning text-dark mb-2">
                        {{ $tin->loai_bai_viet == 'phong_thuy' ? 'Phong Thủy' : 'Tin Tức' }}
                    </span>
                    <h5 class="card-title fw-bold">
                        <a href="{{ route('bai-viet.show', $tin->slug) }}" class="text-decoration-none text-dark">{{ $tin->tieu_de }}</a>
                    </h5>
                    <p class="card-text text-muted small">
                        {{ Str::limit($tin->mo_ta_ngan, 100) }}
                    </p>
                </div>
                <div class="card-footer bg-white border-0 pb-3">
                    <a href="{{ route('bai-viet.show', $tin->slug) }}" class="btn btn-outline-primary rounded-pill btn-sm">Xem chi tiết</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    
    <div class="mt-4">
        {{ $bai_viets->links() }}
    </div>
</div>
@endsection