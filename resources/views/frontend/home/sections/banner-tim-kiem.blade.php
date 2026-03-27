<section class="hero-search-section position-relative d-flex align-items-center justify-content-center">
    {{-- Ảnh nền zoom chậm và lớp phủ Gradient Navy/Đen --}}
    <div class="hero-bg" style="background-image: url('/images/anh-nhan-vien-cong-ty-Thanh-Cong-Land-1536x702.webp');">
    </div>
    <div class="hero-overlay"></div>

    <div class="container position-relative z-1 text-center" data-aos="zoom-in" data-aos-duration="1000">

        {{-- ── TIÊU ĐỀ BANNER (Đã giảm margin để thu gọn chiều cao) ── --}}
        <div class="mb-3">
            <span class="badge rounded-pill px-3 py-2 mb-2 fw-bold"
                style="background-color: var(--primary-light); color: var(--primary); border: 1px solid var(--primary); font-size: 0.85rem; letter-spacing: 1px; backdrop-filter: blur(4px);">
                <i class="fas fa-star me-1"></i> THÀNH CÔNG LAND
            </span>
            <h1 class="display-4 fw-bold text-white serif-font mb-2 text-shadow" style="line-height: 1.2;">
                Khởi Nguồn <span class="text-primary-brand">Tổ Ấm</span>, Kiến Tạo <span
                    class="text-primary-brand">Tương
                    Lai</span>
            </h1>
            <p class="fs-6 text-light opacity-75 fw-light mx-auto mb-0">
                Hơn 5,000+ bất động sản cao cấp đang chờ bạn khám phá. Tìm kiếm ngay ngôi nhà mơ ước của bạn!
            </p>
        </div>

        {{-- ── FORM TÌM KIẾM (LUXURY STYLE - Thiết kế mỏng gọn hơn) ── --}}
        <div class="search-card bg-white mx-auto">
            <form action="{{ route('frontend.bat-dong-san.index') }}" method="GET" id="heroSearchForm">

                {{-- Tabs Ẩn: Lưu giá trị Nhu Cầu (Bán / Thuê) --}}
                <input type="hidden" name="nhu_cau" id="nhuCauInput" value="ban">

                {{-- Nút chuyển đổi Mua / Thuê (Dạng Segmented Control) --}}
                <div class="search-tabs-wrapper mb-3 text-start">
                    <div class="search-tabs d-inline-flex p-1 rounded-pill bg-light border">
                        <button type="button" class="btn btn-search-tab active rounded-pill px-4 py-2 fw-bold"
                            onclick="setNhuCau('ban', this)">Mua Bán</button>
                        <button type="button" class="btn btn-search-tab rounded-pill px-4 py-2 fw-bold"
                            onclick="setNhuCau('thue', this)">Cho Thuê</button>
                    </div>
                </div>

                {{-- Các ô nhập liệu (Đã giảm chiều cao ô nhập liệu) --}}
                <div class="row g-2 align-items-end text-start">

                    {{-- 1. Ô Từ khóa --}}
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label search-label">Từ khóa</label>
                        <div class="search-input-group d-flex align-items-center px-3">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" name="tu_khoa"
                                class="form-control border-0 bg-transparent shadow-none" placeholder="Tòa, đường...">
                        </div>
                    </div>

                    {{-- 2. Ô Khu vực --}}
                    <div class="col-lg-2 col-md-6">
                        <label class="form-label search-label">Khu vực</label>
                        <div class="search-input-group d-flex align-items-center px-3">
                            <i class="fas fa-map-marker-alt search-icon"></i>
                            <select name="khu_vuc_id"
                                class="form-select border-0 bg-transparent shadow-none cursor-pointer">
                                <option value="">Tất cả</option>
                                @if (isset($khuVuc))
                                    @foreach ($khuVuc as $kv)
                                        <option value="{{ $kv->id }}">{{ $kv->ten_khu_vuc }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                    {{-- 3. Ô Dự án --}}
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label search-label">Dự án</label>
                        <div class="search-input-group d-flex align-items-center px-3">
                            <i class="fas fa-building search-icon"></i>
                            <select name="du_an"
                                class="form-select border-0 bg-transparent shadow-none cursor-pointer">
                                <option value="">Tất cả dự án</option>
                                @if (isset($danhSachDuAn))
                                    @foreach ($danhSachDuAn as $da)
                                        <option value="{{ $da->id }}">{{ Str::limit($da->ten_du_an, 25) }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                    {{-- 4. Ô Mức giá --}}
                    <div class="col-lg-2 col-md-6">
                        <label class="form-label search-label">Mức giá</label>
                        <div class="search-input-group d-flex align-items-center px-3">
                            <i class="fas fa-money-bill-wave search-icon"></i>
                            <select name="muc_gia"
                                class="form-select border-0 bg-transparent shadow-none cursor-pointer">
                                <option value="">Tất cả</option>
                                <option value="duoi-2">Dưới 2 Tỷ</option>
                                <option value="2-5">2 - 5 Tỷ</option>
                                <option value="5-10">5 - 10 Tỷ</option>
                                <option value="tren-10">Trên 10 Tỷ</option>
                            </select>
                        </div>
                    </div>

                    {{-- 5. Nút Tìm Kiếm --}}
                    <div class="col-lg-2 col-md-12 mt-3 mt-lg-0">
                        <button type="submit" class="btn-primary-brand w-100 justify-content-center"
                            style="height: 46px; border-radius: 12px; font-size: 0.95rem;">
                            <i class="fas fa-search me-1"></i> Tìm
                        </button>
                    </div>

                </div>
            </form>
        </div>

    </div>
</section>

{{-- ==========================================
     CSS CỤC BỘ DÀNH RIÊNG CHO HERO BANNER
========================================== --}}
@push('styles')
    <style>
        /* ÉP CHIỀU CAO BANNER THẤP XUỐNG 60VH */
        .hero-search-section {
            height: 55vh;
            /* Chiều cao cố định bằng 60% màn hình */
            min-height: 450px;
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
        }

        @keyframes slowZoom {
            0% {
                transform: scale(1);
            }

            100% {
                transform: scale(1.1);
            }
        }

        .hero-overlay {
            position: absolute;
            inset: 0;
            z-index: 0;
            background: linear-gradient(135deg, rgba(27, 58, 107, 0.9) 0%, rgba(28, 18, 9, 0.6) 100%);
        }

        .text-shadow {
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
        }

        /* FORM CARD TÌM KIẾM */
        .search-card {
            max-width: 1050px;
            border-radius: 20px;
            padding: 1.5rem 2rem;
            /* Giảm padding bên trong form để form mỏng đi */
            box-shadow: 0 20px 50px rgba(28, 18, 9, 0.25);
            transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .search-card:hover {
            transform: translateY(-5px);
        }

        .search-tabs {
            box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .btn-search-tab {
            color: var(--text-muted);
            border: none;
            font-size: 0.85rem;
            transition: all 0.3s;
        }

        .btn-search-tab.active {
            background-color: var(--primary);
            color: #fff;
            box-shadow: 0 4px 12px rgba(196, 145, 42, 0.3);
        }

        .search-label {
            font-size: 0.75rem;
            font-weight: 800;
            text-transform: uppercase;
            color: var(--secondary);
            margin-bottom: 0.3rem;
            padding-left: 0.3rem;
        }

        .search-input-group {
            background-color: #f8fafc;
            border: 1.5px solid var(--border);
            border-radius: 12px;
            height: 46px;
            /* Giảm chiều cao ô nhập liệu cho gọn gàng */
            transition: all var(--transition);
        }

        .search-input-group:focus-within {
            background-color: #fff;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-light);
        }

        .search-icon {
            color: var(--primary);
            font-size: 0.95rem;
            width: 20px;
            text-align: center;
        }

        .search-input-group .form-control,
        .search-input-group .form-select {
            color: var(--text-heading);
            font-weight: 600;
            font-size: 0.9rem;
            padding: 0.2rem;
        }

        /* Đáp ứng trên giao diện điện thoại */
        @media (max-width: 991px) {
            .hero-search-section {
                height: auto;
                min-height: 100vh;
                padding: 100px 0 50px;
            }

            .search-card {
                padding: 1.2rem;
            }
        }
    </style>
@endpush

{{-- ==========================================
     JS CỤC BỘ DÀNH RIÊNG CHO HERO BANNER
========================================== --}}
@push('scripts')
    <script>
        function setNhuCau(value, btnElement) {
            document.getElementById('nhuCauInput').value = value;
            const tabs = document.querySelectorAll('.btn-search-tab');
            tabs.forEach(tab => tab.classList.remove('active'));
            btnElement.classList.add('active');

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
