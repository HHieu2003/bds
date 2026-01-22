@extends('frontend.layouts.master')

@section('content')
<div class="container py-5 mt-5">
    <h2 class="serif-font fw-bold mb-4 text-center" style="color: #0F172A;">Danh Sách Quan Tâm Của Bạn</h2>
    
    @if($yeuThichs->count() > 0)
        <div class="row g-4">
            @foreach($yeuThichs as $item)
                @if($item->batDongSan) 
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden position-relative">
                        <button onclick="toggleFavorite({{ $item->batDongSan->id }}, this)" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2 rounded-circle" style="z-index: 10;" title="Bỏ lưu">
                            <i class="fas fa-times"></i>
                        </button>

                        <div class="position-relative">
                            <img src="{{ asset($item->batDongSan->hinh_anh[0] ?? 'assets/img/no-image.jpg') }}" class="card-img-top" style="height: 220px; object-fit: cover;">
                            <span class="badge bg-primary position-absolute bottom-0 start-0 m-3">
                                {{ $item->batDongSan->loai_hinh == 'can_ho' ? 'Căn Hộ' : 'Nhà Đất' }}
                            </span>
                        </div>
                        
                        <div class="card-body">
                            <h5 class="card-title fw-bold text-truncate">
                                <a href="{{ route('bat-dong-san.show', $item->batDongSan->slug) }}" class="text-decoration-none text-dark">
                                    {{ $item->batDongSan->tieu_de }}
                                </a>
                            </h5>
                            <p class="text-danger fw-bold fs-5 mb-1">
                                {{ number_format($item->batDongSan->gia, 2) }} Tỷ
                            </p>
                            <div class="d-flex justify-content-between small text-muted">
                                <span><i class="fas fa-ruler-combined"></i> {{ $item->batDongSan->dien_tich }} m²</span>
                                <span><i class="fas fa-bed"></i> {{ $item->batDongSan->so_phong_ngu }} PN</span>
                                <span><i class="fas fa-bath"></i> {{ $item->batDongSan->so_phong_tam }} WC</span>
                            </div>
                            <p class="small text-muted mt-2 mb-0">
                                <i class="fas fa-map-marker-alt"></i> 
                                {{ $item->batDongSan->duAn ? $item->batDongSan->duAn->dia_chi : 'Nhà phố lẻ' }}
                            </p>
                        </div>
                        
                        <div class="card-footer bg-white border-0 pb-3">
                             <a href="{{ route('bat-dong-san.show', $item->batDongSan->slug) }}" class="btn btn-primary w-100 rounded-pill">Xem Chi Tiết</a>
                        </div>
                    </div>
                </div>
                @endif
            @endforeach
        </div>
    @else
        <div class="text-center py-5">
            <img src="https://cdn-icons-png.flaticon.com/512/4076/4076432.png" width="120" class="mb-3 opacity-50">
            <h5 class="text-muted">Bạn chưa lưu tin nào cả!</h5>
            <a href="{{ route('home') }}" class="btn btn-outline-primary rounded-pill mt-3">Khám phá ngay</a>
        </div>
    @endif
</div>

<script>
function toggleFavorite(id, btnElement) {
    // Sửa đường dẫn cho đúng với route web.php
    fetch('/yeu-thich/toggle/' + id, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        },
    })
    .then(response => response.json())
    .then(data => {
        if(data.status === 'removed') {
            // Xóa ngay phần tử HTML ra khỏi giao diện
            btnElement.closest('.col-md-6').remove();
            alert('Đã bỏ lưu tin!');
        } else {
            alert(data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}
</script>
@endsection