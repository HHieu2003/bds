@extends('frontend.layouts.master')
@section('title', 'Trang Chủ - BatDongSan Pro')

@section('content')
    <section class="position-relative d-flex align-items-center justify-content-center" style="height: 85vh; margin-top: -76px;">
        <div style="position: absolute; top:0; left:0; width:100%; height:100%; background: linear-gradient(rgba(15,23,42,0.5), rgba(15,23,42,0.4)), url('https://images.unsplash.com/photo-1600596542815-2495db98dada?auto=format&fit=crop&w=1920'); background-size: cover; background-position: center; z-index: -1;"></div>
        
        <div class="text-center text-white container" data-aos="fade-up">
            <h1 class="display-3 fw-bold mb-4 serif-font">Tìm Kiếm Ngôi Nhà Mơ Ước</h1>
            <div class="bg-white p-4 rounded-4 shadow-lg d-inline-block text-dark w-100" style="max-width: 800px;">
                <div class="row g-2">
                    <div class="col-md-8"><input type="text" class="form-control form-control-lg border-0 bg-light" placeholder="Nhập tên dự án, địa chỉ..."></div>
                    <div class="col-md-4 d-grid"><button class="btn btn-primary btn-lg fw-bold">TÌM KIẾM</button></div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 container">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div><span class="text-warning fw-bold text-uppercase small">Bộ sưu tập</span><h2 class="fw-bold mb-0 text-primary">Dự Án Nổi Bật</h2></div>
            <a href="{{ route('project.index') }}" class="btn btn-outline-dark rounded-pill fw-bold">Xem tất cả</a>
        </div>
        <div class="row g-4">
            @foreach($duAnNoiBat as $da)
            <div class="col-lg-4 col-md-6" data-aos="fade-up">
                <div class="card border-0 rounded-4 overflow-hidden shadow-sm h-100">
                    <a href="{{ route('project.show', $da->id) }}">
                        <img src="{{ $da->hinh_anh ? asset('storage/'.$da->hinh_anh) : 'https://via.placeholder.com/600x400' }}" class="card-img-top" style="height: 250px; object-fit: cover; transition: 0.5s;">
                    </a>
                    <div class="card-body">
                        <h5 class="fw-bold mb-1"><a href="{{ route('project.show', $da->id) }}" class="text-decoration-none text-dark">{{ $da->ten_du_an }}</a></h5>
                        <small class="text-muted"><i class="fa-solid fa-location-dot me-1"></i> {{ Str::limit($da->dia_chi, 35) }}</small>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>

    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="fw-bold text-center mb-5 text-primary">Bất Động Sản Mới</h2>
            <div class="row g-4">
                @foreach($dsBatDongSan as $bds)
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="card border-0 rounded-4 shadow-sm h-100">
                        <div class="position-relative">
                            <span class="badge bg-primary position-absolute top-0 start-0 m-3">{{ $bds->loai_hinh == 'ban' ? 'ĐANG BÁN' : 'CHO THUÊ' }}</span>
                            <a href="{{ route('home.show', $bds->slug) }}">
                                <img src="{{ $bds->hinh_anh && count($bds->hinh_anh)>0 ? asset('storage/'.$bds->hinh_anh[0]) : 'https://via.placeholder.com/600x400' }}" class="card-img-top rounded-top-4" style="height: 220px; object-fit: cover;">
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="mb-2"><small class="text-muted">{{ $bds->duAn->ten_du_an }}</small></div>
                            <h5 class="fw-bold text-truncate"><a href="{{ route('home.show', $bds->slug) }}" class="text-dark text-decoration-none">{{ $bds->tieu_de }}</a></h5>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <span class="text-danger fw-bold fs-5">{{ number_format($bds->gia) }} ₫</span>
                                <div class="text-muted small">
                                    <span class="me-2"><i class="fa-solid fa-bed"></i> {{ $bds->phong_ngu }}</span>
                                    <span><i class="fa-solid fa-ruler"></i> {{ $bds->dien_tich }}m²</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    @push('scripts')
<script>
    window.chatContext = { type: 'general', id: null };
</script>
@endpush
@endsection