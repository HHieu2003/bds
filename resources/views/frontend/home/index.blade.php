@extends('frontend.layouts.master')
@section('title', 'Trang Chủ - BatDongSan Pro')

@section('content')
    <section class="position-relative d-flex align-items-center justify-content-center" style="height: 85vh; margin-top: -76px;">
        <div style="position: absolute; top:0; left:0; width:100%; height:100%; background: linear-gradient(rgba(15,23,42,0.5), rgba(15,23,42,0.4)), url('https://images.unsplash.com/photo-1600596542815-2495db98dada?auto=format&fit=crop&w=1920'); background-size: cover; background-position: center; z-index: -1;"></div>
        
        <div class="text-center text-white container" data-aos="fade-up">
            <h1 class="display-3 fw-bold mb-4 serif-font">Tìm Kiếm Căn Hộ Nhanh Nhất</h1>
            <div class="bg-white p-4 rounded-4 shadow-lg d-inline-block text-dark w-100" style="max-width: 800px;">
    <form action="{{ route('tim-kiem') }}" method="GET">
        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-bold small text-muted">Từ khóa tìm kiếm</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-0"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" name="tu_khoa" class="form-control border-0 bg-light" placeholder="Nhập tên dự án, khu vực...">
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold small text-muted">Loại hình</label>
                <select name="loai_hinh" class="form-select border-0 bg-light">
                    <option value="">Tất cả loại hình</option>
                    <option value="can_ho">Căn hộ chung cư</option>
                    <option value="nha_dat">Nhà đất thổ cư</option>
                    <option value="biet_thu">Biệt thự</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold small text-muted">Mức giá</label>
                <select name="muc_gia" class="form-select border-0 bg-light">
                    <option value="">Tất cả mức giá</option>
                    <option value="duoi-2">Dưới 2 Tỷ</option>
                    <option value="2-5">2 - 5 Tỷ</option>
                    <option value="5-8">5 - 8 Tỷ</option>
                    <option value="tren-8">Trên 8 Tỷ</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100 h-100 fw-bold rounded-3" style="min-height: 45px;">
                    Tìm Kiếm
                </button>
            </div>
        </div>
    </form>

            </div>
        </div>
    </section>

    <section class="py-5 container">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div><span class="text-warning fw-bold text-uppercase small">Bộ sưu tập</span><h2 class="fw-bold mb-0 text-primary">Dự Án Nổi Bật</h2></div>
            <a href="{{ route('du-an.index') }}" class="btn btn-outline-dark rounded-pill fw-bold">Xem tất cả</a>
        </div>
        <div class="row g-4">
            @foreach($du_ans as $da)
            <div class="col-lg-4 col-md-6" data-aos="fade-up">
                <div class="card border-0 rounded-4 overflow-hidden shadow-sm h-100">
                    <a href="{{ route('du-an.show', $da->slug) }}">
                        <img src="{{ $da->hinh_anh ? asset('storage/'.$da->hinh_anh) : 'https://via.placeholder.com/600x400' }}" class="card-img-top" style="height: 250px; object-fit: cover; transition: 0.5s;">
                    </a>
                    <div class="card-body">
                        <h5 class="fw-bold mb-1"><a href="{{ route('du-an.show', $da->slug) }}" class="text-decoration-none text-dark">{{ $da->ten_du_an }}</a></h5>
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
                @foreach($bat_dong_sans as $bds)
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="card border-0 rounded-4 shadow-sm h-100">
                        <div class="position-relative">
                            <span class="badge bg-primary position-absolute top-0 start-0 m-3">{{ $bds->loai_hinh == 'ban' ? 'ĐANG BÁN' : 'CHO THUÊ' }}</span>
                            <a href="{{ route('bat-dong-san.show', $bds->id) }}">
                                <img src="{{ $bds->hinh_anh && count($bds->hinh_anh)>0 ? asset('storage/'.$bds->hinh_anh[0]) : 'https://via.placeholder.com/600x400' }}" class="card-img-top rounded-top-4" style="height: 220px; object-fit: cover;">
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="mb-2"><small class="text-muted">{{ $bds->duAn->ten_du_an }}</small></div>
                            <h5 class="fw-bold text-truncate"><a href="{{ route('bat-dong-san.show', $bds->id) }}" class="text-dark text-decoration-none">{{ $bds->tieu_de }}</a></h5>
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