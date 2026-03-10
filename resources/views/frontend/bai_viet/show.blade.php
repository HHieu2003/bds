@extends('frontend.layouts.master')

@section('content')

{{-- CSS để xử lý nội dung từ CKEditor --}}
<style>
    .content-body img {
        max-width: 100% !important;
        height: auto !important;
        border-radius: 8px;
        margin: 10px 0;
    }
    .content-body table {
        width: 100% !important;
        border-collapse: collapse;
        margin: 15px 0;
    }
    .content-body table td, .content-body table th {
        border: 1px solid #ddd;
        padding: 8px;
    }
    .content-body p {
        margin-bottom: 1rem;
    }
</style>

<div class="container py-5 mt-5">
    <div class="row">
        {{-- NỘI DUNG CHÍNH --}}
        <div class="col-lg-8">
            <h1 class="serif-font fw-bold mb-3" style="color: #0F172A;">{{ $baiViet->tieu_de }}</h1>
            <p class="text-muted">
                <i class="far fa-clock"></i> {{ $baiViet->created_at->format('d/m/Y') }} | 
                <i class="far fa-eye"></i> {{ $baiViet->luot_xem }} lượt xem
            </p>
            
            {{-- Ảnh đại diện bài viết --}}
            @if($baiViet->hinh_anh)
                <img src="{{ asset($baiViet->hinh_anh) }}" class="w-100 rounded-4 mb-4 shadow-sm" alt="{{ $baiViet->tieu_de }}" style="object-fit: cover; max-height: 500px;">
            @endif
            
            {{-- Nội dung từ CKEditor --}}
            <div class="content-body fs-5 text-dark" style="line-height: 1.8; text-align: justify;">
                {!! $baiViet->noi_dung !!}
            </div>
        </div>
        
        {{-- SIDEBAR BÀI VIẾT LIÊN QUAN --}}
        <div class="col-lg-4 mt-4 mt-lg-0">
            <div class="sticky-top" style="top: 100px;">
                <h4 class="fw-bold mb-4 position-relative ps-3" style="color: #B99044; border-left: 4px solid #B99044;">
                    Bài Viết Liên Quan
                </h4>
                
                @forelse($lienQuan as $lq)
                <div class="d-flex gap-3 mb-4 align-items-center bg-light p-2 rounded-3 hover-shadow transition">
                    <a href="{{ route('bai-viet.show', $lq->slug) }}" class="flex-shrink-0">
                        <img src="{{ asset($lq->hinh_anh ?? 'images/no-image.jpg') }}" class="rounded-3" style="width: 80px; height: 80px; object-fit: cover;">
                    </a>
                    <div>
                        <h6 class="mb-1" style="font-size: 0.95rem;">
                            <a href="{{ route('bai-viet.show', $lq->slug) }}" class="text-decoration-none text-dark fw-bold text-truncate-2">
                                {{ Str::limit($lq->tieu_de, 50) }}
                            </a>
                        </h6>
                        <small class="text-muted"><i class="far fa-calendar-alt me-1"></i> {{ $lq->created_at->format('d/m/Y') }}</small>
                    </div>
                </div>
                @empty
                <p class="text-muted fst-italic">Chưa có bài viết liên quan.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection