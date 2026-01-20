@extends('frontend.layouts.master')

@section('content')
<div class="container py-5 mt-5">
    <div class="row">
        <div class="col-lg-8">
            <h1 class="serif-font fw-bold mb-3" style="color: #0F172A;">{{ $baiViet->tieu_de }}</h1>
            <p class="text-muted">
                <i class="far fa-clock"></i> {{ $baiViet->created_at->format('d/m/Y') }} | 
                <i class="far fa-eye"></i> {{ $baiViet->luot_xem }} lượt xem
            </p>
            
            @if($baiViet->hinh_anh)
                <img src="{{ asset($baiViet->hinh_anh) }}" class="w-100 rounded-4 mb-4" alt="{{ $baiViet->tieu_de }}">
            @endif
            
            <div class="content-body fs-5 text-dark" style="line-height: 1.8;">
                {!! $baiViet->noi_dung !!}
            </div>
        </div>
        
        <div class="col-lg-4">
            <h4 class="fw-bold mb-4" style="color: #B99044;">Bài Viết Liên Quan</h4>
            @forelse($lienQuan as $lq)
            <div class="d-flex gap-3 mb-3">
                <img src="{{ asset($lq->hinh_anh ?? 'images/no-image.jpg') }}" class="rounded-3" style="width: 80px; height: 80px; object-fit: cover;">
                <div>
                    <h6 class="mb-1"><a href="{{ route('bai-viet.show', $lq->slug) }}" class="text-decoration-none text-dark fw-bold">{{ $lq->tieu_de }}</a></h6>
                    <small class="text-muted">{{ $lq->created_at->format('d/m/Y') }}</small>
                </div>
            </div>
            @empty
            <p class="text-muted">Chưa có bài viết liên quan.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection