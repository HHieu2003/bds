@extends('frontend.layouts.master')
@section('title', $duAn->ten_du_an)

@section('content')
    <div style="height: 400px; background: url('{{ $duAn->hinh_anh ? asset('storage/'.$duAn->hinh_anh) : 'https://via.placeholder.com/1920x600' }}') fixed center/cover; position: relative;">
        <div class="position-absolute w-100 h-100 bg-dark opacity-50 top-0 start-0"></div>
        <div class="position-absolute w-100 h-100 top-0 start-0 d-flex align-items-center justify-content-center text-white flex-column">
            <h1 class="display-4 fw-bold text-uppercase serif-font">{{ $duAn->ten_du_an }}</h1>
            <p class="fs-5"><i class="fa-solid fa-location-dot me-2"></i> {{ $duAn->dia_chi }}</p>
        </div>
    </div>

    <div class="container py-5">
        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm p-4 mb-5 rounded-4">
                    <h3 class="fw-bold text-primary mb-3 border-bottom pb-2">Tổng Quan</h3>
                    <div class="row mb-3">
                        <div class="col-md-6"><strong>Chủ đầu tư:</strong> {{ $duAn->chu_dau_tu }}</div>
                        <div class="col-md-6"><strong>Thi công:</strong> {{ $duAn->don_vi_thi_cong }}</div>
                    </div>
                    <div class="bg-light p-3 rounded content-html">
                        {!! $duAn->mo_ta !!}
                    </div>
                </div>

                <h3 class="fw-bold mb-4">Danh Sách Căn Hộ</h3>
                <div class="row g-4">
                    @foreach($duAn->batDongSans as $bds)
                    <div class="col-md-6">
                        <div class="card h-100 border-0 shadow-sm">
                            <a href="{{ route('bat-dong-san.show', $bds->id) }}">
                                <img src="{{ $bds->hinh_anh && count($bds->hinh_anh)>0 ? asset('storage/'.$bds->hinh_anh[0]) : 'https://via.placeholder.com/400x300' }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                            </a>
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="badge bg-warning text-dark">{{ $bds->toa }}</span>
                                    <span class="text-danger fw-bold">{{ number_format($bds->gia) }}</span>
                                </div>
                                <h6 class="fw-bold"><a href="{{ route('bat-dong-san.show', $bds->id) }}" class="text-dark text-decoration-none">{{ Str::limit($bds->tieu_de, 40) }}</a></h6>
                                <hr>
                                <div class="small text-muted d-flex justify-content-between">
                                    <span><i class="fa-solid fa-bed"></i> {{ $bds->phong_ngu }}</span>
                                    <span><i class="fa-solid fa-vector-square"></i> {{ $bds->dien_tich }}m²</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-lg p-4 rounded-4 position-sticky" style="top: 100px;">
                    <h4 class="fw-bold mb-3">Đăng Ký Tư Vấn</h4>
                    <p class="text-muted small">Nhận bảng giá và chính sách ưu đãi mới nhất.</p>
                    <form>
                        <input class="form-control mb-3" placeholder="Họ tên">
                        <input class="form-control mb-3" placeholder="Số điện thoại">
                        <button class="btn btn-primary w-100 fw-bold">Gửi Yêu Cầu</button>
                    </form>
                    <hr>
                    <a href="tel:0912345678" class="btn btn-outline-danger w-100 rounded-pill fw-bold"><i class="fa-solid fa-phone me-2"></i> 0912.345.678</a>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
<script>
    // Khai báo biến toàn cục: Đang ở Dự án ID ...
    window.chatContext = { 
        type: 'project', 
        id: {{ $duAn->id }} 
    };
</script>
@endpush
@endsection