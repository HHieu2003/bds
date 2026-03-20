<section class="hero-search-section position-relative d-flex align-items-center justify-content-center">
    {{-- Ảnh nền và lớp phủ Gradient Xanh Navy --}}
    <div class="hero-bg" style="background-image: url('/images/anh-nhan-vien-cong-ty-Thanh-Cong-Land-1536x702.webp');">
    </div>
    <div class="hero-overlay"
        style="background: linear-gradient(135deg, rgba(15,23,42,0.85) 0%, rgba(15,23,42,0.4) 100%);"></div>

    <div class="container position-relative z-1 text-center" data-aos="zoom-in" data-aos-duration="800">

        {{-- Tiêu đề Banner --}}
        <div class="mb-5">
            <span class="badge rounded-pill px-3 py-2 mb-3 fw-bold"
                style="background-color: rgba(255,140,66,0.2); color: #FF8C42; border: 1px solid #FF8C42; font-size: 0.85rem; letter-spacing: 1px;">
                <i class="fas fa-star me-1"></i> THÀNH CÔNG LAND
            </span>
            <h1 class="display-4 fw-bold text-white serif-font mb-3 text-shadow">
                Khởi Nguồn <span style="color: #FF8C42;">Tổ Ấm</span>, Kiến Tạo <span style="color: #FF8C42;">Tương
                    Lai</span>
            </h1>
            <p class="fs-5 text-light opacity-75 fw-light mx-auto" style="max-width: 700px;">
                Hơn 5,000+ bất động sản cao cấp đang chờ bạn khám phá. Tìm kiếm ngay ngôi nhà mơ ước của bạn!
            </p>
        </div>

        {{-- Form Tìm Kiếm Nổi (Floating Card) --}}
        <div class="search-card bg-white p-2 p-md-4 rounded-4 shadow-lg mx-auto" style="max-width: 950px;">

            <form action="{{ route('frontend.bat-dong-san.index') }}" method="GET" id="heroSearchForm">

                {{-- Tabs Ẩn: Lưu giá trị Nhu Cầu (Bán / Thuê) --}}
                <input type="hidden" name="nhu_cau" id="nhuCauInput" value="ban">

                {{-- Nút chuyển đổi Mua / Thuê --}}
                <div class="d-flex justify-content-center justify-content-md-start mb-4 gap-2 border-bottom pb-3">
                    <button type="button" class="btn btn-search-tab active px-4 py-2 rounded-pill fw-bold"
                        onclick="setNhuCau('ban', this)">
                        Mua Bất Động Sản
                    </button>
                    <button type="button" class="btn btn-search-tab px-4 py-2 rounded-pill fw-bold"
                        onclick="setNhuCau('thue', this)">
                        Thuê Bất Động Sản
                    </button>
                </div>

                {{-- Các ô nhập liệu --}}
                <div class="row g-3 align-items-center text-start">

                    {{-- Ô Từ khóa --}}
                    <div class="col-lg-4 col-md-6">
                        <label class="form-label fw-bold small text-muted mb-1 ps-2">Từ khóa tìm kiếm</label>
                        <div class="input-group search-input-group rounded-pill overflow-hidden bg-light">
                            <span class="input-group-text bg-transparent border-0 ps-4 text-muted"><i
                                    class="fas fa-search"></i></span>
                            <input type="text" name="tu_khoa"
                                class="form-control border-0 bg-transparent shadow-none py-3"
                                placeholder="Tên dự án, đường, quận...">
                        </div>
                    </div>

                    {{-- Ô Khu vực --}}
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label fw-bold small text-muted mb-1 ps-2">Khu vực</label>
                        <div class="input-group search-input-group rounded-pill overflow-hidden bg-light">
                            <span class="input-group-text bg-transparent border-0 ps-4" style="color: #FF8C42;"><i
                                    class="fas fa-map-marker-alt"></i></span>
                            <select name="khu_vuc"
                                class="form-select border-0 bg-transparent shadow-none py-3 text-dark fw-semibold cursor-pointer">
                                <option value="">Tất cả khu vực</option>
                                {{-- Tận dụng biến $khuVucMenu từ AppServiceProvider --}}
                                @if (isset($khuVucMenu))
                                    @foreach ($khuVucMenu as $kv)
                                        <option value="{{ $kv->id }}">{{ $kv->ten_khu_vuc }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                    {{-- Ô Mức giá --}}
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label fw-bold small text-muted mb-1 ps-2">Mức giá</label>
                        <div class="input-group search-input-group rounded-pill overflow-hidden bg-light">
                            <span class="input-group-text bg-transparent border-0 ps-4" style="color: #FF8C42;"><i
                                    class="fas fa-money-bill-wave"></i></span>
                            <select name="muc_gia"
                                class="form-select border-0 bg-transparent shadow-none py-3 text-dark fw-semibold cursor-pointer">
                                <option value="">Tất cả mức giá</option>
                                <option value="duoi-2">Dưới 2 Tỷ</option>
                                <option value="2-5">2 - 5 Tỷ</option>
                                <option value="5-10">5 - 10 Tỷ</option>
                                <option value="tren-10">Trên 10 Tỷ</option>
                            </select>
                        </div>
                    </div>

                    {{-- Nút Tìm Kiếm --}}
                    <div class="col-lg-2 col-md-6 mt-4 mt-lg-0 pt-lg-4">
                        <button type="submit"
                            class="btn btn-primary w-100 rounded-pill fw-bold py-3 text-white d-flex align-items-center justify-content-center btn-search-submit">
                            <i class="fas fa-search me-2"></i> Tìm Ngay
                        </button>
                    </div>

                </div>
            </form>
        </div>

    </div>
</section>

{{-- ==========================================
     CSS CHUẨN MÀU CAM & XANH NAVY
========================================== --}}
<style>
    /* Bố cục Hero */
    .hero-search-section {
        min-height: 85vh;
        /* Chiều cao banner */
        padding-top: 50px;
        padding-bottom: 80px;
        overflow: hidden;
    }

    .hero-bg {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-size: cover;
        background-position: center;
        z-index: 0;
        transform: scale(1.02);
        /* Zoom nhẹ ảnh nền */
    }

    .hero-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 0;
    }

    .text-shadow {
        text-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
    }

    /* Thiết kế Form Card */
    .search-card {
        transition: transform 0.3s ease;
    }

    .search-card:hover {
        transform: translateY(-5px);
    }

    /* Thiết kế Input Group */
    .search-input-group {
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
    }

    .search-input-group:focus-within {
        background-color: #fff !important;
        border-color: #FF8C42;
        box-shadow: 0 0 0 4px rgba(255, 140, 66, 0.15);
    }

    .form-control:focus,
    .form-select:focus {
        background-color: transparent !important;
    }

    /* Tabs Mua/Thuê */
    .btn-search-tab {
        background-color: transparent;
        color: #64748B;
        border: 2px solid transparent;
        transition: all 0.3s ease;
    }

    .btn-search-tab:hover {
        color: #0F172A;
        background-color: #f1f5f9;
    }

    .btn-search-tab.active {
        background-color: #fff4ed;
        color: #FF8C42;
        border-color: #FF8C42;
    }

    /* Nút Submit */
    .btn-search-submit {
        background: linear-gradient(135deg, #FF8C42 0%, #ff6b1a 100%);
        border: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(255, 140, 66, 0.3);
    }

    .btn-search-submit:hover {
        background: linear-gradient(135deg, #e67a32 0%, #e65c00 100%);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(255, 140, 66, 0.4);
    }

    .cursor-pointer {
        cursor: pointer;
    }
</style>

{{-- ==========================================
     JS XỬ LÝ CHUYỂN TAB MUA / THUÊ
========================================== --}}
@push('scripts')
    <script>
        function setNhuCau(value, btnElement) {
            // 1. Cập nhật giá trị vào thẻ input hidden
            document.getElementById('nhuCauInput').value = value;

            // 2. Xóa class active ở tất cả các tab
            const tabs = document.querySelectorAll('.btn-search-tab');
            tabs.forEach(tab => tab.classList.remove('active'));

            // 3. Thêm class active vào tab vừa được click
            btnElement.classList.add('active');

            // Hiệu ứng nhẹ: thay đổi chữ "Mức giá" tương ứng
            const selectGia = document.querySelector('select[name="muc_gia"]');
            if (value === 'thue') {
                selectGia.options[1].text = "Dưới 10 Triệu";
                selectGia.options[2].text = "10 - 20 Triệu";
                selectGia.options[3].text = "20 - 50 Triệu";
                selectGia.options[4].text = "Trên 50 Triệu";
            } else {
                selectGia.options[1].text = "Dưới 2 Tỷ";
                selectGia.options[2].text = "2 - 5 Tỷ";
                selectGia.options[3].text = "5 - 10 Tỷ";
                selectGia.options[4].text = "Trên 10 Tỷ";
            }
        }
    </script>
@endpush
