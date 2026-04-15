@push('styles')
    <style>
        /* ══ HERO WRAPPER ══ */
        .hero-section {
            position: relative;
            min-height: 72vh;
            /* ← Giảm từ 90vh xuống 72vh */
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            margin-top: -76px;
            padding: 100px 0 70px;
            /* ← Giảm padding dưới */
        }

        /* ── Ảnh nền ── */
        .hero-bg {
            position: absolute;
            inset: 0;
            z-index: 0;
            background-image: url('/images/anh-nhan-vien-cong-ty-Thanh-Cong-Land-1536x702.webp');
            background-size: cover;
            background-position: center;
            will-change: transform;
        }

        @keyframes heroZoom {
            0% {
                transform: scale(1);
            }

            100% {
                transform: scale(1.08);
            }
        }

        /* ── Lớp phủ ── */
        .hero-overlay {
            position: absolute;
            inset: 0;
            z-index: 1;
            background: linear-gradient(160deg,
                    rgba(10, 18, 40, 0.90) 0%,
                    rgba(15, 30, 65, 0.80) 50%,
                    rgba(10, 18, 40, 0.75) 100%);
        }

        /* ── Hạt sáng trang trí ── */
        .hero-particles {
            position: absolute;
            inset: 0;
            z-index: 1;
            pointer-events: none;
            background-image:
                radial-gradient(1.5px 1.5px at 15% 25%, rgba(255, 140, 66, .4) 0%, transparent 100%),
                radial-gradient(1.5px 1.5px at 85% 15%, rgba(255, 200, 100, .3) 0%, transparent 100%),
                radial-gradient(1px 1px at 50% 60%, rgba(255, 255, 255, .2) 0%, transparent 100%),
                radial-gradient(1px 1px at 70% 80%, rgba(255, 140, 66, .25) 0%, transparent 100%);
        }

        /* ── Wave bottom ── */
        .hero-wave {
            position: absolute;
            bottom: -1px;
            left: 0;
            right: 0;
            z-index: 3;
            line-height: 0;
        }

        .hero-wave svg {
            width: 100%;
            height: 56px;
            fill: var(--bg-alt);
            display: block;
        }

        /* ══ CONTENT ══ */
        .hero-content {
            position: relative;
            z-index: 2;
            width: 100%;
            text-align: center;
        }

        /* Badge */
        .hero-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 140, 66, .15);
            border: 1px solid rgba(255, 140, 66, .4);
            color: #ffb878;
            padding: 5px 16px;
            border-radius: 30px;
            font-size: .7rem;
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            backdrop-filter: blur(6px);
            margin-bottom: 14px;
            /* ← Giảm margin */
        }

        .hero-eyebrow i {
            color: var(--primary);
        }

        /* Tiêu đề */
        .hero-title {
            font-size: clamp(1.7rem, 4vw, 3rem);
            /* ← Nhỏ hơn */
            font-weight: 900;
            color: #fff;
            line-height: 1.2;
            margin-bottom: 10px;
            text-shadow: 0 4px 24px rgba(0, 0, 0, .4);
            letter-spacing: -.5px;
        }

        .hero-title .accent {
            background: linear-gradient(90deg, var(--primary), #ffb878);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Sub */
        .hero-sub {
            font-size: .9rem;
            font-weight: 400;
            color: rgba(255, 255, 255, .7);
            max-width: 520px;
            margin: 0 auto 20px;
            /* ← Giảm margin */
            line-height: 1.65;
        }

        /* ══ SEARCH CARD ══ */
        .hero-search-card {
            background: rgba(255, 255, 255, .97);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 20px 24px 18px;
            /* ← Giảm padding */
            box-shadow: 0 24px 64px rgba(0, 0, 0, .28), 0 0 0 1px rgba(255, 255, 255, .2);
            max-width: 1200px;
            margin: 0 auto;
            transition: transform .3s, box-shadow .3s;
        }

        .hero-search-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 30px 72px rgba(0, 0, 0, .3);
        }

        /* Header card */
        .hs-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--border);
            flex-wrap: wrap;
            gap: 8px;
        }

        .hs-tabs {
            display: inline-flex;
            background: var(--bg-alt);
            border: 1.5px solid var(--border);
            border-radius: 30px;
            padding: 3px;
            gap: 2px;
        }

        .hs-tab {
            padding: 5px 18px;
            border-radius: 28px;
            font-size: .78rem;
            font-weight: 800;
            color: var(--text-muted);
            border: none;
            background: transparent;
            cursor: pointer;
            transition: all var(--transition);
            font-family: inherit;
            white-space: nowrap;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .hs-tab.active {
            background: var(--primary);
            color: #fff;
            box-shadow: 0 4px 12px rgba(192, 102, 42, .35);
        }

        .hs-tab.active.thue {
            background: var(--secondary);
            box-shadow: 0 4px 12px rgba(27, 58, 107, .3);
        }

        .hs-verified {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: .7rem;
            color: var(--text-muted);
            font-weight: 600;
        }

        .hs-verified i {
            color: var(--status-success);
        }

        /* ══ FIELDS GRID — nút tìm nằm cùng hàng ══ */
        .hs-fields {
            display: grid;
            /*  từ khóa  |  khu vực  |  dự án  |  mức giá  |  nút  */
            grid-template-columns: 2.2fr 1.3fr 1.8fr 1.4fr 130px;
            gap: 8px;
            align-items: end;
        }

        /* Label */
        .hs-label {
            font-size: .65rem;
            font-weight: 800;
            color: var(--secondary);
            letter-spacing: .4px;
            text-transform: uppercase;
            margin-bottom: 4px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .hs-label i {
            color: var(--primary);
            font-size: .65rem;
        }

        /* Input wrap */
        .hs-input-wrap {
            display: flex;
            align-items: center;
            gap: 7px;
            padding: 0 11px;
            height: 44px;
            /* ← Giảm height */
            background: var(--bg-alt);
            border: 1.5px solid var(--border);
            border-radius: 10px;
            transition: all var(--transition);
        }

        .hs-input-wrap:focus-within {
            background: #fff;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-light);
        }

        .hs-input-wrap>i:first-child {
            color: var(--primary);
            font-size: .82rem;
            flex-shrink: 0;
        }

        .hs-input-wrap input,
        .hs-input-wrap select {
            flex: 1;
            border: none;
            outline: none;
            background: transparent;
            font-family: inherit;
            font-size: .83rem;
            font-weight: 600;
            color: var(--text-heading);
            min-width: 0;
            appearance: none;
            cursor: pointer;
        }

        .hs-input-wrap input::placeholder {
            color: var(--text-muted);
            font-weight: 500;
        }

        .hs-caret {
            color: var(--text-muted);
            font-size: .6rem;
            flex-shrink: 0;
            pointer-events: none;
        }

        /* ── Nút submit — luôn cùng hàng ── */
        .hs-submit-col {
            display: flex;
            flex-direction: column;
        }

        .hs-submit {
            height: 44px;
            /* ← Bằng chiều cao input */
            padding: 0 18px;
            background: linear-gradient(135deg, #d9834a, var(--primary));
            color: #fff;
            border: none;
            border-radius: 10px;
            font-weight: 800;
            font-size: .85rem;
            font-family: inherit;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            white-space: nowrap;
            width: 100%;
            transition: transform var(--transition), box-shadow var(--transition);
            box-shadow: 0 5px 18px rgba(192, 102, 42, .35);
            margin-top: auto;
            /* ← Đẩy xuống ngang hàng input */
        }

        .hs-submit:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-gold);
        }

        /* Quick chips */
        .hs-quick {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
            margin-top: 12px;
            padding-top: 10px;
            border-top: 1px solid var(--border);
        }

        .hs-quick-label {
            font-size: .68rem;
            color: var(--text-muted);
            font-weight: 700;
            white-space: nowrap;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .hs-quick-chip {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 11px;
            border-radius: 20px;
            font-size: .68rem;
            font-weight: 700;
            background: #fff;
            color: var(--secondary);
            border: 1.5px solid var(--border);
            text-decoration: none;
            transition: all var(--transition);
            white-space: nowrap;
        }

        .hs-quick-chip:hover {
            border-color: var(--primary);
            color: var(--primary);
            background: var(--primary-light);
        }

        .hs-quick-chip i {
            font-size: .6rem;
            color: var(--primary);
        }

        /* ══ RESPONSIVE ══ */
        @media (max-width: 1099px) {
            .hs-fields {
                grid-template-columns: 1fr 1fr 1fr;
                grid-template-rows: auto auto;
            }

            .hs-submit-col {
                grid-column: 1 / -1;
            }

            .hs-submit {
                margin-top: 0;
            }
        }

        @media (max-width: 767px) {
            .hero-section {
                min-height: 100svh;
                padding: 100px 0 80px;
            }

            .hs-fields {
                grid-template-columns: 1fr;
            }

            .hs-submit-col {
                grid-column: 1;
            }

            .hero-title {
                font-size: 1.75rem;
            }

            .hs-quick {
                display: none;
            }

        }

        @media (max-width: 480px) {}
    </style>
@endpush

<section class="hero-section">
    <div class="hero-bg"></div>
    <div class="hero-overlay"></div>
    <div class="hero-particles"></div>

    <div class="container hero-content">

        <div data-aos="fade-down" data-aos-duration="600">
            <div class="hero-eyebrow">
                <i class="fas fa-star"></i>
                Thành Công Land — Đồng Hành Mọi Tổ Ấm
            </div>
        </div>

        <h1 class="hero-title" data-aos="zoom-in" data-aos-duration="700" data-aos-delay="80">
            Kiến Tạo <span class="accent">Tổ Ấm</span>,
            Vươn Tới <span class="accent">Tương Lai</span>
        </h1>

        <p class="hero-sub" data-aos="fade-up" data-aos-duration="600" data-aos-delay="140">
            Hơn 5,000+ bất động sản cao cấp tại Vinhomes Smart City và các dự án
            hàng đầu Hà Nội. Tìm ngôi nhà mơ ước của bạn ngay hôm nay!
        </p>

        {{-- ══ SEARCH CARD ══ --}}
        <div class="hero-search-card" data-aos="fade-up" data-aos-duration="700" data-aos-delay="230">

            <div class="hs-card-header">
                <div class="hs-tabs">
                    <button type="button" class="hs-tab active" onclick="setHeroNhuCau('ban', this)">
                        <i class="fas fa-tag"></i> Mua bán
                    </button>
                    <button type="button" class="hs-tab thue" onclick="setHeroNhuCau('thue', this)">
                        <i class="fas fa-key"></i> Cho thuê
                    </button>
                </div>
                <div class="hs-verified">
                    <i class="fas fa-shield-alt"></i>
                    Tư vấn miễn phí 24/7
                </div>
            </div>

            <form action="{{ route('frontend.bat-dong-san.index') }}" method="GET" id="heroForm">
                <input type="hidden" name="nhu_cau" id="heroNhuCau" value="ban">

                <div class="hs-fields">

                    {{-- Từ khóa --}}
                    <div>
                        <div class="hs-label"><i class="fas fa-search"></i> Từ khóa</div>
                        <div class="hs-input-wrap">
                            <i class="fas fa-pencil-alt"></i>
                            <input type="text" name="timkiem" placeholder="Tòa, đường, tiêu đề...">
                        </div>
                    </div>

                    {{-- Khu vực --}}
                    <div>
                        <div class="hs-label"><i class="fas fa-map-marker-alt"></i> Khu vực</div>
                        <div class="hs-input-wrap">
                            <i class="fas fa-map-marker-alt"></i>
                            <select name="khu_vuc" id="heroKhuVuc" onchange="filterHeroDuAnByKhuVuc(this.value)">
                                <option value="">Tất cả khu vực</option>
                                @foreach ($khuVuc ?? [] as $kv)
                                    <option value="{{ $kv->id }}"
                                        {{ request('khu_vuc') == $kv->id ? 'selected' : '' }}>
                                        {{ $kv->ten_khu_vuc }}
                                    </option>
                                @endforeach
                            </select>
                            <i class="fas fa-chevron-down hs-caret"></i>
                        </div>
                    </div>

                    {{-- Dự án --}}
                    <div>
                        <div class="hs-label"><i class="fas fa-building"></i> Dự án</div>
                        <div class="hs-input-wrap">
                            <i class="fas fa-city"></i>
                            <select name="du_an" id="heroDuAn">
                                <option value="">Tất cả dự án</option>
                                @foreach ($danhSachDuAn ?? [] as $da)
                                    <option value="{{ $da->id }}" data-khu-vuc="{{ $da->khu_vuc_id ?? '' }}"
                                        {{ request('du_an') == $da->id ? 'selected' : '' }}>
                                        {{ Str::limit($da->ten_du_an, 22) }}
                                    </option>
                                @endforeach
                            </select>
                            <i class="fas fa-chevron-down hs-caret"></i>
                        </div>
                    </div>

                    {{-- Mức giá --}}
                    <div>
                        <div class="hs-label"><i class="fas fa-tag"></i> Mức giá</div>
                        <div class="hs-input-wrap">
                            <i class="fas fa-money-bill-wave"></i>
                            <select name="muc_gia" id="heroMucGia">
                                <option value="">Tất cả mức giá</option>
                                <option value="duoi2ty">Dưới 2 tỷ</option>
                                <option value="2-5ty">2 – 5 tỷ</option>
                                <option value="5-10ty">5 – 10 tỷ</option>
                                <option value="tren10ty">Trên 10 tỷ</option>
                            </select>
                            <i class="fas fa-chevron-down hs-caret"></i>
                        </div>
                    </div>

                    {{-- Nút tìm -- luôn cùng hàng nhờ margin-top:auto --}}
                    <div class="hs-submit-col">
                        <div class="hs-label" style="visibility:hidden;user-select:none">.</div>
                        <button type="submit" class="hs-submit">
                            <i class="fas fa-search"></i> Tìm kiếm
                        </button>
                    </div>

                </div>

                {{-- Quick chips --}}
                <div class="hs-quick">
                    <span class="hs-quick-label">
                        <i class="fas fa-bolt" style="color:var(--primary)"></i>
                        Tìm nhanh:
                    </span>
                    <a href="{{ route('frontend.bat-dong-san.index', ['nhu_cau' => 'ban', 'sophongngu' => 'studio']) }}"
                        class="hs-quick-chip">
                        <i class="fas fa-home"></i> Studio
                    </a>
                    <a href="{{ route('frontend.bat-dong-san.index', ['nhu_cau' => 'ban', 'sophongngu' => '2']) }}"
                        class="hs-quick-chip">
                        <i class="fas fa-bed"></i> 2 PN
                    </a>
                    <a href="{{ route('frontend.bat-dong-san.index', ['nhu_cau' => 'ban', 'sophongngu' => '3']) }}"
                        class="hs-quick-chip">
                        <i class="fas fa-bed"></i> 3 PN
                    </a>
                    <a href="{{ route('frontend.bat-dong-san.index', ['nhu_cau' => 'thue']) }}"
                        class="hs-quick-chip">
                        <i class="fas fa-key"></i> Cho thuê
                    </a>
                    <a href="{{ route('frontend.bat-dong-san.index', ['nhu_cau' => 'ban', 'muc_gia' => 'duoi2ty']) }}"
                        class="hs-quick-chip">
                        <i class="fas fa-tag"></i> Dưới 2 tỷ
                    </a>
                    @if (isset($danhSachDuAn) && $danhSachDuAn->isNotEmpty())
                        <a href="{{ route('frontend.bat-dong-san.index', ['nhu_cau' => 'ban', 'du_an' => $danhSachDuAn->first()->id]) }}"
                            class="hs-quick-chip">
                            <i class="fas fa-city"></i>
                            {{ Str::limit($danhSachDuAn->first()->ten_du_an, 18) }}
                        </a>
                    @endif
                </div>

            </form>
        </div>

    </div>

    <div class="hero-wave">
        <svg viewBox="0 0 1440 56" preserveAspectRatio="none">
            <path d="M0,28 C360,60 1080,0 1440,28 L1440,56 L0,56 Z"></path>
        </svg>
    </div>

</section>

@push('scripts')
    <script>
        const HERO_GIA_BAN = ['Tất cả mức giá', 'Dưới 2 tỷ', '2 – 5 tỷ', '5 – 10 tỷ', 'Trên 10 tỷ'];
        const HERO_GIA_THUE = ['Tất cả mức giá', 'Dưới 10tr/tháng', '10 – 20tr/tháng', '20 – 50tr/tháng',
            'Trên 50tr/tháng'
        ];
        const HERO_VAL_BAN = ['', 'duoi2ty', '2-5ty', '5-10ty', 'tren10ty'];
        const HERO_VAL_THUE = ['', 'duoi10tr', '10-20tr', '20-50tr', 'tren50tr'];

        function setHeroNhuCau(val, btn) {
            document.getElementById('heroNhuCau').value = val;
            document.querySelectorAll('.hs-tab').forEach(t => t.classList.remove('active'));
            btn.classList.add('active');

            const sel = document.getElementById('heroMucGia');
            const labs = val === 'thue' ? HERO_GIA_THUE : HERO_GIA_BAN;
            const vals = val === 'thue' ? HERO_VAL_THUE : HERO_VAL_BAN;
            sel.innerHTML = '';
            labs.forEach((text, i) => sel.add(new Option(text, vals[i] ?? '')));
        }

        function filterHeroDuAnByKhuVuc(khuVucId) {
            const duAnSelect = document.getElementById('heroDuAn');
            if (!duAnSelect) return;

            duAnSelect.querySelectorAll('option:not([value=""])').forEach((opt) => {
                opt.style.display = (!khuVucId || opt.dataset.khuVuc == khuVucId) ? '' : 'none';
            });

            const selected = duAnSelect.options[duAnSelect.selectedIndex];
            if (selected && selected.style.display === 'none') {
                duAnSelect.value = '';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const khuVucSelect = document.getElementById('heroKhuVuc');
            if (khuVucSelect) {
                filterHeroDuAnByKhuVuc(khuVucSelect.value);
            }
        });
    </script>
@endpush
