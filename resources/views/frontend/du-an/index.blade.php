@extends('frontend.layouts.master')

@section('title', 'Danh sách Dự án - Thành Công Land')

@section('content')
    {{-- 1. HEADER BANNER TÌM KIẾM --}}
    <section class="position-relative d-flex align-items-center justify-content-center"
        style="height: 45vh; background: linear-gradient(rgba(15, 23, 42, 0.7), rgba(15, 23, 42, 0.8)), url('https://vinhomesland.vn/wp-content/uploads/2021/04/phoi-canh-vinhomes-smart-city.jpg') center/cover fixed;">
        <div class="container text-center text-white" data-aos="zoom-in">
            <h1 class="display-4 fw-bold serif-font mb-3">Khám Phá Dự Án Đẳng Cấp</h1>
            <p class="fs-5 opacity-75 mx-auto mb-4" style="max-width: 600px;">
                Nơi hội tụ những biểu tượng sống tinh hoa, quy hoạch đồng bộ và tiện ích vượt trội dành cho giới tinh hoa.
            </p>
            
            {{-- Bộ lọc nhanh --}}
            <form action="{{ route('frontend.du-an.index') }}" method="GET" class="bg-white p-2 rounded-pill shadow-lg mx-auto" style="max-width: 600px;">
                <div class="input-group">
                    <select name="khu_vuc" class="form-select border-0 bg-transparent fw-bold" style="color: #475569; max-width: 200px; box-shadow: none;">
                        <option value="">Tất cả khu vực</option>
                        @foreach($khuVucs as $kv)
                            <option value="{{ $kv->id }}" {{ request('khu_vuc') == $kv->id ? 'selected' : '' }}>
                                {{ $kv->ten_khu_vuc }}
                            </option>
                        @endforeach
                    </select>
                    <div class="input-group-text bg-transparent border-0 text-muted">|</div>
                    <input type="text" name="tu_khoa" class="form-control border-0 bg-transparent shadow-none" placeholder="Tên dự án..." value="{{ request('tu_khoa') }}">
                    <button type="submit" class="btn rounded-pill fw-bold px-4" style="background-color: #FF8C42; color: white;">
                        <i class="fas fa-search me-1"></i> Lọc
                    </button>
                </div>
            </form>
        </div>
    </section>

    {{-- 2. DANH SÁCH DỰ ÁN (GRID) --}}
    <section class="py-5" style="background-color: #F8FAFC;">
        <div class="container py-4">
            
            {{-- Tiêu đề & Kết quả tìm kiếm --}}
            <div class="d-flex justify-content-between align-items-center mb-5 border-bottom pb-3">
                <h3 class="fw-bold serif-font text-dark mb-0">
                    @if(request('khu_vuc') && is_numeric(request('khu_vuc')))
                        Dự án tại: <span style="color: #FF8C42;">{{ $khuVucs->where('id', request('khu_vuc'))->first()->ten_khu_vuc ?? 'Khu vực này' }}</span>
                    @else
                        Tất cả Dự án
                    @endif
                </h3>
                <span class="badge bg-light text-dark border px-3 py-2 fs-6 shadow-sm">
                    Tìm thấy <strong style="color: #FF8C42;">{{ $duAns->total() }}</strong> dự án
                </span>
            </div>

            {{-- Lưới Dự Án --}}
            <div class="row g-4">
                @forelse($duAns as $da)
                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 50 }}">
                        <div class="card border-0 rounded-4 overflow-hidden shadow-sm h-100 position-relative hover-up cursor-pointer" 
                             onclick="window.location.href='{{ route('frontend.du-an.show', $da->slug) }}'">
                            
                            {{-- Hình ảnh dự án --}}
                            <div class="overflow-hidden position-relative w-100" style="height: 300px;">
                                <img src="{{ $da->hinh_anh ? asset('storage/' . $da->hinh_anh) : 'https://vinhomesland.vn/wp-content/uploads/2023/10/be-boi-the-canopy-residences-vinhomes-smart-city.jpg' }}"
                                    class="card-img-top w-100 h-100 project-img" style="object-fit: cover; transition: transform 0.6s ease;"
                                    alt="{{ $da->ten_du_an }}">
                                
                                {{-- Overlay Gradient Đen bọc chữ --}}
                                <div class="position-absolute bottom-0 start-0 w-100 p-4" style="background: linear-gradient(to top, rgba(15,23,42,0.9) 0%, rgba(15,23,42,0.5) 60%, transparent 100%);">
                                    <div class="badge mb-2" style="background-color: #FF8C42;">
                                        Đang mở bán
                                    </div>
                                    <h4 class="fw-bold mb-1 text-white text-truncate" title="{{ $da->ten_du_an }}">
                                        {{ $da->ten_du_an }}
                                    </h4>
                                    <small class="text-light opacity-75 d-block text-truncate">
                                        <i class="fa-solid fa-location-dot me-1" style="color: #FF8C42;"></i> {{ $da->dia_chi }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <img src="https://cdn-icons-png.flaticon.com/512/7486/7486831.png" alt="Không tìm thấy" style="width: 120px; opacity: 0.5;" class="mb-3">
                        <h4 class="text-muted fw-bold">Không tìm thấy dự án nào!</h4>
                        <p class="text-muted">Vui lòng thử chọn khu vực khác hoặc bỏ bớt điều kiện lọc.</p>
                        <a href="{{ route('frontend.du-an.index') }}" class="btn rounded-pill px-4 fw-bold mt-2" style="background-color: #FF8C42; color: white;">
                            Xem tất cả dự án
                        </a>
                    </div>
                @endforelse
            </div>

            {{-- Thanh Phân Trang --}}
            <div class="d-flex justify-content-center mt-5">
                {{ $duAns->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
            
        </div>
    </section>

    <style>
        .hover-up { transition: all 0.3s ease; }
        .hover-up:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important; }
        .hover-up:hover .project-img { transform: scale(1.08); }
        .cursor-pointer { cursor: pointer; }
    </style>
@endsection