@extends('frontend.layouts.master')

@section('title', 'Tin Tức Bất Động Sản - Thành Công Land')

@section('content')

    {{-- HEADER BANNER --}}
    <section class="bv-hero d-flex align-items-center justify-content-center">
        <div class="container text-center text-white position-relative z-1" data-aos="fade-up">
            <h1 class="display-4 fw-bold mb-3">Góc Nhìn Thị Trường</h1>
            <p class="fs-5 opacity-75 mx-auto mb-0" style="max-width: 600px;">
                Cập nhật liên tục những tin tức nóng hổi, phân tích chuyên sâu
                và cẩm nang hữu ích về bất động sản.
            </p>
        </div>
    </section>

    {{-- MAIN CONTENT --}}
    <section class="py-5 bg-alt-section">
        <div class="container">
            <div class="row g-5">

                {{-- CỘT TRÁI: DANH SÁCH BÀI VIẾT --}}
                <div class="col-lg-8">
                    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                        <h3 class="fw-bold section-title mb-0">
                            @if (request('loai') == 'tin_tuc')
                                Thị Trường BĐS
                            @elseif(request('loai') == 'kien_thuc')
                                Kiến Thức Nhà Đất
                            @elseif(request('loai') == 'phong_thuy')
                                Phong Thủy
                            @else
                                Bài Viết Mới Nhất
                            @endif
                        </h3>
                        <span class="text-muted small">Có {{ $baiViets->total() }} bài viết</span>
                    </div>

                    <div class="row g-4">
                        @forelse($baiViets as $bv)
                            <div class="col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 50 }}">
                                <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden hover-up">

                                    <a href="{{ route('frontend.tin-tuc.show', $bv->slug) }}"
                                        class="bv-thumb overflow-hidden d-block position-relative">
                                        <div class="position-absolute top-0 start-0 m-3 z-1">
                                            <span class="bv-loai-badge">
                                                {{ $bv->loai_bai_viet ?? 'Tin Tức' }}
                                            </span>
                                        </div>
                                        <img src="{{ $bv->hinh_anh ? asset('storage/' . $bv->hinh_anh) : asset('images/default-news.jpg') }}"
                                            class="card-img-top w-100 h-100 project-img" alt="{{ $bv->tieu_de }}">
                                    </a>

                                    <div class="card-body p-4 d-flex flex-column">
                                        <div class="small text-muted mb-2 fw-semibold d-flex justify-content-between">
                                            <span>
                                                <i class="far fa-calendar-alt me-1 text-primary-brand"></i>
                                                {{ \Carbon\Carbon::parse($bv->thoi_diem_dang ?? $bv->created_at)->format('d/m/Y') }}
                                            </span>
                                            <span title="Lượt xem">
                                                <i class="far fa-eye me-1 icon-muted"></i>
                                                {{ $bv->luot_xem ?? 0 }}
                                            </span>
                                        </div>
                                        <h5 class="fw-bold mb-3">
                                            <a href="{{ route('frontend.tin-tuc.show', $bv->slug) }}"
                                                class="card-title-link line-clamp-2">
                                                {{ $bv->tieu_de }}
                                            </a>
                                        </h5>
                                        <p class="text-muted small line-clamp-3 mb-0 flex-grow-1">
                                            {{ $bv->mo_ta_ngan ?? Str::limit(strip_tags($bv->noi_dung), 120) }}
                                        </p>
                                    </div>

                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-5 bg-white rounded-4 border">
                                <p class="text-muted mb-0">Không tìm thấy bài viết nào phù hợp.</p>
                            </div>
                        @endforelse
                    </div>

                    {{-- Phân trang --}}
                    <div class="d-flex justify-content-center mt-5">
                        {{ $baiViets->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>
                </div>

                {{-- CỘT PHẢI: SIDEBAR --}}
                <div class="col-lg-4">
                    <div class="position-sticky" style="top: 100px;">

                        {{-- Ô Tìm Kiếm --}}
                        <div class="card border-0 shadow-sm rounded-4 mb-4">
                            <div class="card-body p-4">
                                <h5 class="fw-bold section-title mb-3">Tìm Kiếm</h5>
                                <form action="{{ route('frontend.tin-tuc.index') }}" method="GET">
                                    <div class="input-group">
                                        <input type="text" name="tu_khoa" class="form-control bg-light border-0"
                                            placeholder="Nhập từ khóa..." value="{{ request('tu_khoa') }}">
                                        <button class="btn btn-primary-brand" type="submit">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        {{-- Danh mục --}}
                        <div class="card border-0 shadow-sm rounded-4 mb-4">
                            <div class="card-body p-4">
                                <h5 class="fw-bold section-title mb-3">Chuyên Mục</h5>
                                <ul class="list-unstyled mb-0 bv-category-list">
                                    <li class="mb-2">
                                        <a href="{{ route('frontend.tin-tuc.index', ['loai' => 'tin_tuc']) }}"
                                            class="text-decoration-none d-flex justify-content-between align-items-center p-2 rounded
                                                  {{ request('loai') == 'tin_tuc' ? 'active' : '' }}">
                                            Thị trường BĐS <i class="fas fa-angle-right"></i>
                                        </a>
                                    </li>
                                    <li class="mb-2">
                                        <a href="{{ route('frontend.tin-tuc.index', ['loai' => 'kien_thuc']) }}"
                                            class="text-decoration-none d-flex justify-content-between align-items-center p-2 rounded
                                                  {{ request('loai') == 'kien_thuc' ? 'active' : '' }}">
                                            Kiến thức nhà đất <i class="fas fa-angle-right"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('frontend.tin-tuc.index', ['loai' => 'phong_thuy']) }}"
                                            class="text-decoration-none d-flex justify-content-between align-items-center p-2 rounded
                                                  {{ request('loai') == 'phong_thuy' ? 'active' : '' }}">
                                            Phong thủy <i class="fas fa-angle-right"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        {{-- Tin nổi bật --}}
                        <div class="card border-0 shadow-sm rounded-4">
                            <div class="card-body p-4">
                                <h5 class="fw-bold section-title mb-4">Tin Nổi Bật</h5>
                                @foreach ($tinNoiBat as $tin)
                                    <div class="d-flex mb-3 align-items-center">
                                        <a href="{{ route('frontend.tin-tuc.show', $tin->slug) }}" class="flex-shrink-0">
                                            <img src="{{ $tin->hinh_anh ? asset('storage/' . $tin->hinh_anh) : asset('images/default-news.jpg') }}"
                                                alt="{{ $tin->tieu_de }}" class="rounded-3 bv-sidebar-thumb">
                                        </a>
                                        <div class="ms-3">
                                            <h6 class="mb-1 fw-bold" style="font-size: 0.9rem;">
                                                <a href="{{ route('frontend.tin-tuc.show', $tin->slug) }}"
                                                    class="text-dark text-decoration-none line-clamp-2 bv-hover-link">
                                                    {{ $tin->tieu_de }}
                                                </a>
                                            </h6>
                                            <small class="text-muted">
                                                <i class="far fa-calendar-alt me-1"></i>
                                                {{ \Carbon\Carbon::parse($tin->thoi_diem_dang ?? $tin->created_at)->format('d/m/Y') }}
                                            </small>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

    <style>
        /* Hero Banner */
        .bv-hero {
            height: 35vh;
            background:
                linear-gradient(rgba(28, 18, 9, 0.78), rgba(28, 18, 9, 0.78)),
                url('https://vinhomesland.vn/wp-content/uploads/2021/04/phoi-canh-vinhomes-smart-city.jpg') center / cover fixed;
        }

        /* Ảnh bài viết */
        .bv-thumb {
            height: 220px;
        }

        .bv-thumb .project-img {
            object-fit: cover;
            transition: transform 0.5s;
        }

        .hover-up:hover .project-img {
            transform: scale(1.08);
        }

        /* Badge loại bài viết */
        .bv-loai-badge {
            background: rgba(255, 255, 255, 0.95);
            color: var(--primary);
            border: 1px solid var(--primary);
            border-radius: 20px;
            padding: .25rem .8rem;
            font-size: .75rem;
            font-weight: 700;
        }

        /* Sidebar thumbnail */
        .bv-sidebar-thumb {
            width: 80px;
            height: 60px;
            object-fit: cover;
        }

        /* Hover link sidebar */
        .bv-hover-link {
            transition: color var(--transition);
        }

        .bv-hover-link:hover {
            color: var(--primary) !important;
        }

        /* Danh mục sidebar */
        .bv-category-list a {
            color: var(--text-body);
            transition: all var(--transition);
            border: 1px solid transparent;
        }

        .bv-category-list a:hover,
        .bv-category-list a.active {
            background-color: var(--primary-light);
            color: var(--primary);
            font-weight: 700;
            border-color: var(--primary);
        }
    </style>

@endsection
