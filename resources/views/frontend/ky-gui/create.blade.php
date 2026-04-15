@extends('frontend.layouts.master')
@section('title', 'Ký gửi BĐS — Thành Công Land')

@section('content')
    <div class="kg-fe-wrap">

        {{-- ── HERO ── --}}
        <div class="kg-fe-hero">
            <div class="kg-fe-hero-inner" data-aos="fade-up" data-aos-duration="600">
                {{-- <div class="kg-fe-hero-icon-wrap">
                    <i class="fas fa-file-signature"></i>
                </div> --}}
                <h1>Ký gửi Bất động sản</h1>
                <p>Điền thông tin bên dưới — đội ngũ chuyên viên sẽ liên hệ trong vòng <strong>2 giờ</strong></p>
                <div class="kg-fe-hero-badges">
                    <span><i class="fas fa-shield-alt me-1"></i>Bảo mật</span>
                    <span><i class="fas fa-bolt me-1"></i>Phản hồi nhanh</span>
                    <span><i class="fas fa-star me-1"></i>Miễn phí</span>
                </div>
            </div>
        </div>

        <div class="kg-fe-container">

            {{-- VALIDATION ERRORS --}}
            @if ($errors->any())
                <div class="kg-fe-errors" data-aos="fade-down">
                    <div class="kg-fe-errors-icon"><i class="fas fa-exclamation-triangle"></i></div>
                    <ul>
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('frontend.ky-gui.store') }}" method="POST" enctype="multipart/form-data" id="kyGuiForm">
                @csrf

                {{-- ═══ BƯỚC 1: THÔNG TIN CỦA BẠN ═══ --}}
                <div class="kg-fe-step" data-aos="fade-up" data-aos-duration="500" data-aos-delay="50">
                    <div class="kg-fe-step-head">
                        <span class="kg-fe-step-num">1</span>
                        <div>
                            <div class="kg-fe-step-ttl">Thông tin của bạn</div>
                            <div class="kg-fe-step-sub">Chúng tôi sẽ liên lạc qua số điện thoại này</div>
                        </div>
                    </div>
                    <div class="kg-fe-step-body">
                        <div class="kg-fe-row3">
                            <div class="kg-fe-fg">
                                <label class="kg-fe-lbl req">Họ và tên chủ nhà</label>
                                <input type="text" name="ho_ten_chu_nha"
                                    class="kg-fe-fi @error('ho_ten_chu_nha') err @enderror"
                                    value="{{ old('ho_ten_chu_nha', auth('customer')->user()?->ho_ten) }}"
                                    placeholder="Nguyễn Văn A">
                                @error('ho_ten_chu_nha')
                                    <div class="kg-fe-err"><i class="fas fa-times-circle"></i> {{ $message }}</div>
                                @enderror
                            </div>
                            <div class="kg-fe-fg">
                                <label class="kg-fe-lbl req">Số điện thoại</label>
                                <input type="tel" name="so_dien_thoai"
                                    class="kg-fe-fi @error('so_dien_thoai') err @enderror"
                                    value="{{ old('so_dien_thoai', auth('customer')->user()?->so_dien_thoai) }}"
                                    placeholder="0901 234 567">
                                @error('so_dien_thoai')
                                    <div class="kg-fe-err"><i class="fas fa-times-circle"></i> {{ $message }}</div>
                                @enderror
                            </div>
                            <div class="kg-fe-fg">
                                <label class="kg-fe-lbl">Email</label>
                                <input type="email" name="email" class="kg-fe-fi"
                                    value="{{ old('email', auth('customer')->user()?->email) }}"
                                    placeholder="email@example.com">
                                @error('email')
                                    <div class="kg-fe-err"><i class="fas fa-times-circle"></i> {{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ═══ BƯỚC 2: THÔNG TIN BẤT ĐỘNG SẢN ═══ --}}
                <div class="kg-fe-step" data-aos="fade-up" data-aos-duration="500" data-aos-delay="150">
                    <div class="kg-fe-step-head">
                        <span class="kg-fe-step-num">2</span>
                        <div>
                            <div class="kg-fe-step-ttl">Thông tin bất động sản</div>
                            <div class="kg-fe-step-sub">Thông tin càng chi tiết, tư vấn càng chính xác</div>
                        </div>
                    </div>
                    <div class="kg-fe-step-body">

                        {{-- Loại hình --}}
                        <div class="kg-fe-fg" style="margin-bottom:20px">
                            <label class="kg-fe-lbl req">Loại hình bất động sản</label>
                            <div class="kg-fe-loai-grid">
                                @foreach (\App\Models\KyGui::LOAI_HINH as $v => $info)
                                    <label class="kg-fe-loai-item {{ old('loai_hinh', 'can_ho') === $v ? 'active' : '' }}"
                                        data-value="{{ $v }}">
                                        <input type="radio" name="loai_hinh" value="{{ $v }}"
                                            {{ old('loai_hinh', 'can_ho') === $v ? 'checked' : '' }}
                                            class="kg-fe-loai-radio" style="display:none">
                                        <i class="{{ $info['icon'] }}"></i>
                                        <span>{{ $info['label'] }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('loai_hinh')
                                <div class="kg-fe-err"><i class="fas fa-times-circle"></i> {{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Nhu cầu --}}
                        <div class="kg-fe-fg" style="margin-bottom:20px">
                            <label class="kg-fe-lbl req">Nhu cầu</label>
                            <div class="kg-fe-nc-wrap">
                                <label class="kg-fe-nc-item {{ old('nhu_cau', 'ban') === 'ban' ? 'active-ban' : '' }}"
                                    id="ncBan">
                                    <input type="radio" name="nhu_cau" value="ban" class="kg-fe-nc-radio"
                                        {{ old('nhu_cau', 'ban') === 'ban' ? 'checked' : '' }} style="display:none">
                                    <i class="fas fa-tag"></i> Muốn bán
                                </label>
                                <label class="kg-fe-nc-item {{ old('nhu_cau') === 'thue' ? 'active-thue' : '' }}"
                                    id="ncThue">
                                    <input type="radio" name="nhu_cau" value="thue" class="kg-fe-nc-radio"
                                        {{ old('nhu_cau') === 'thue' ? 'checked' : '' }} style="display:none">
                                    <i class="fas fa-key"></i> Muốn cho thuê
                                </label>
                            </div>
                        </div>

                        <div class="kg-fe-fg">
                            <label class="kg-fe-lbl">Địa chỉ</label>
                            <input type="text" name="dia_chi" class="kg-fe-fi" value="{{ old('dia_chi') }}"
                                placeholder="Số nhà, đường, phường/xã, quận/huyện...">
                        </div>

                        <div class="kg-fe-row3">
                            <div class="kg-fe-fg">
                                <label class="kg-fe-lbl">Dự án</label>
                                <input type="text" name="du_an" class="kg-fe-fi @error('du_an') err @enderror"
                                    value="{{ old('du_an') }}" placeholder="VD: Mỹ đình Pearl">
                                @error('du_an')
                                    <div class="kg-fe-err"><i class="fas fa-times-circle"></i> {{ $message }}</div>
                                @enderror
                            </div>
                            <div class="kg-fe-fg">
                                <label class="kg-fe-lbl">Tầng</label>
                                <input type="text" name="tang" class="kg-fe-fi @error('tang') err @enderror"
                                    value="{{ old('tang') }}" placeholder="VD: 12 hoặc Penthouse">
                                @error('tang')
                                    <div class="kg-fe-err"><i class="fas fa-times-circle"></i> {{ $message }}</div>
                                @enderror
                            </div>
                            <div class="kg-fe-fg">
                                <label class="kg-fe-lbl req">Mã căn</label>
                                <input type="text" name="ma_can" class="kg-fe-fi @error('ma_can') err @enderror"
                                    value="{{ old('ma_can') }}" placeholder="VD: S2.03-1212">
                                @error('ma_can')
                                    <div class="kg-fe-err"><i class="fas fa-times-circle"></i> {{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <div class="kg-fe-row3">
                            <div class="kg-fe-fg">
                                <label class="kg-fe-lbl">Diện tích (m²)</label>
                                <input type="text" inputmode="decimal" name="dien_tich"
                                    class="kg-fe-fi js-decimal @error('dien_tich') err @enderror"
                                    value="{{ old('dien_tich') }}" placeholder="75,5 hoặc 75.5">
                                @error('dien_tich')
                                    <div class="kg-fe-err"><i class="fas fa-times-circle"></i> {{ $message }}</div>
                                @enderror
                            </div>

                            <div class="kg-fe-fg">
                                <label class="kg-fe-lbl">Nội thất</label>
                                <select name="noi_that" class="kg-fe-fi kg-fe-sel">
                                    <option value="">— Chọn nội thất —</option>
                                    @foreach (\App\Models\KyGui::NOI_THAT as $v => $l)
                                        <option value="{{ $v }}" @selected(old('noi_that') == $v)>
                                            {{ $l }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="kg-fe-fg">
                                <label class="kg-fe-lbl">Số phòng ngủ</label>
                                <input type="text" name="so_phong_ngu"
                                    class="kg-fe-fi @error('so_phong_ngu') err @enderror"
                                    value="{{ old('so_phong_ngu') }}" placeholder="VD: 2PN hoặc 3PN+1">
                                @error('so_phong_ngu')
                                    <div class="kg-fe-err"><i class="fas fa-times-circle"></i> {{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- GIÁ BÁN --}}
                        <div id="kgBanBox" class="{{ old('nhu_cau', 'ban') !== 'ban' ? 'kg-hidden' : '' }}">
                            <div class="kg-fe-divider"><i class="fas fa-tag me-1"></i> Thông tin giá bán</div>
                            <div class="kg-fe-row3">
                                <div class="kg-fe-fg">
                                    <label class="kg-fe-lbl">Giá bán mong muốn (VNĐ)</label>
                                    <input type="text" inputmode="numeric" name="gia_ban_mong_muon"
                                        class="kg-fe-fi js-price" value="{{ old('gia_ban_mong_muon') }}"
                                        placeholder="3.500.000.000">
                                    <div class="kg-fe-price-hint" id="giaHienThi"></div>
                                </div>
                                <div class="kg-fe-fg">
                                    <label class="kg-fe-lbl">Pháp lý</label>
                                    <select name="phap_ly" class="kg-fe-fi kg-fe-sel">
                                        <option value="">— Chọn pháp lý —</option>
                                        @foreach (\App\Models\KyGui::PHAP_LY as $v => $l)
                                            <option value="{{ $v }}" @selected(old('phap_ly') == $v)>
                                                {{ $l }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- GIÁ THUÊ --}}
                        <div id="kgThueBox" class="{{ old('nhu_cau') !== 'thue' ? 'kg-hidden' : '' }}">
                            <div class="kg-fe-divider"><i class="fas fa-key me-1"></i> Thông tin giá thuê</div>
                            <div class="kg-fe-row3">
                                <div class="kg-fe-fg">
                                    <label class="kg-fe-lbl">Giá thuê/tháng (VNĐ)</label>
                                    <input type="text" inputmode="numeric" name="gia_thue_mong_muon"
                                        class="kg-fe-fi js-price" value="{{ old('gia_thue_mong_muon') }}"
                                        placeholder="15.000.000">
                                    <div class="kg-fe-price-hint" id="giaThueHienThi"></div>
                                </div>
                                <div class="kg-fe-fg">
                                    <label class="kg-fe-lbl">Hình thức thanh toán</label>
                                    <select name="hinh_thuc_thanh_toan" class="kg-fe-fi kg-fe-sel">
                                        <option value="">— Chọn —</option>
                                        @foreach (\App\Models\KyGui::HINH_THUC_THANH_TOAN as $v => $l)
                                            <option value="{{ $v }}" @selected(old('hinh_thuc_thanh_toan') == $v)>
                                                {{ $l }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- ═══ BƯỚC 3: HÌNH ẢNH & GHI CHÚ ═══ --}}
                <div class="kg-fe-step" data-aos="fade-up" data-aos-duration="500" data-aos-delay="200">
                    <div class="kg-fe-step-head">
                        <span class="kg-fe-step-num">3</span>
                        <div>
                            <div class="kg-fe-step-ttl">Hình ảnh & Ghi chú</div>
                            <div class="kg-fe-step-sub">Hình ảnh thực tế giúp tăng khả năng được chọn</div>
                        </div>
                    </div>
                    <div class="kg-fe-step-body">

                        <div class="kg-fe-fg">
                            <label class="kg-fe-lbl">Hình ảnh tham khảo (tối đa 5 ảnh, mỗi ảnh ≤ 3MB)</label>
                            <div class="kg-fe-upload-box" id="uploadBox"
                                onclick="document.getElementById('fileInput').click()">
                                <input type="file" id="fileInput" name="hinh_anh_tham_khao[]" multiple
                                    accept="image/*" style="display:none" onchange="previewImages(this)">
                                <i class="fas fa-cloud-upload-alt kg-fe-upload-icon"></i>
                                <div class="kg-fe-upload-text">Nhấn để chọn ảnh hoặc kéo thả vào đây</div>
                                <div class="kg-fe-upload-hint">JPG, PNG, WebP — tối đa 3MB/ảnh</div>
                            </div>
                            <div id="imgPreview" class="kg-fe-img-preview"></div>
                        </div>

                        <div class="kg-fe-fg">
                            <label class="kg-fe-lbl">Ghi chú thêm</label>
                            <textarea name="ghi_chu" class="kg-fe-fi" rows="4"
                                placeholder="Vị trí cụ thể, lý do bán/cho thuê, yêu cầu đặc biệt...">{{ old('ghi_chu') }}</textarea>
                        </div>

                    </div>
                </div>

                {{-- SUBMIT --}}
                <div class="kg-fe-submit-wrap" data-aos="fade-up" data-aos-duration="500" data-aos-delay="250">
                    <button type="submit" class="kg-fe-submit">
                        <i class="fas fa-paper-plane"></i>
                        Gửi yêu cầu ký gửi
                    </button>
                    <div class="kg-fe-submit-note">
                        <i class="fas fa-shield-alt me-1"></i>
                        Thông tin của bạn được bảo mật tuyệt đối
                    </div>
                </div>

            </form>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* ── Wrap ── */
        .kg-fe-wrap {
            background: var(--bg-alt);
            min-height: 100vh;
            padding-bottom: 60px;
        }

        /* ── Hero ── */
        .kg-fe-hero {
            background: linear-gradient(135deg, var(--primary-dark), var(--secondary-dark));
            padding: 30px 20px 55px;
            text-align: center;
            color: #fff;
            position: relative;
            overflow: hidden;
        }

        .kg-fe-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                radial-gradient(circle at 15% 50%, rgba(255, 255, 255, .06) 0%, transparent 50%),
                radial-gradient(circle at 85% 20%, rgba(255, 255, 255, .05) 0%, transparent 50%);
            pointer-events: none;
        }

        .kg-fe-hero::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 40px;
            background: var(--bg-alt);
            clip-path: ellipse(55% 100% at 50% 100%);
        }

        .kg-fe-hero-inner {
            position: relative;
            z-index: 1;
        }

        .kg-fe-hero-icon-wrap {
            width: 68px;
            height: 68px;
            border-radius: 20px;
            background: rgba(255, 255, 255, .1);
            border: 1.5px solid rgba(255, 255, 255, .2);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: var(--primary);
            margin-bottom: 16px;
            backdrop-filter: blur(4px);
        }

        .kg-fe-hero h1 {
            font-size: clamp(1.5rem, 3vw, 2rem);
            font-weight: 800;
            margin: 0 0 10px;
            color: #fff;
        }

        .kg-fe-hero p {
            font-size: .95rem;
            opacity: .85;
            margin: 0 0 18px;
        }

        .kg-fe-hero p strong {
            color: var(--primary);
        }

        .kg-fe-hero-badges {
            display: flex;
            justify-content: center;
            gap: .8rem;
            flex-wrap: wrap;
        }

        .kg-fe-hero-badges span {
            background: rgba(255, 255, 255, .12);
            border: 1px solid rgba(255, 255, 255, .2);
            color: rgba(255, 255, 255, .9);
            font-size: .75rem;
            font-weight: 700;
            padding: .3rem .9rem;
            border-radius: 20px;
            display: flex;
            align-items: center;
            backdrop-filter: blur(4px);
        }

        /* ── Container ── */
        .kg-fe-container {
            max-width: 800px;
            margin: -20px auto 0;
            padding: 0 16px;
            position: relative;
            z-index: 1;
        }

        /* ── Errors ── */
        .kg-fe-errors {
            background: #fff5f5;
            border: 1.5px solid rgba(198, 40, 40, .3);
            border-radius: 12px;
            padding: 14px 18px;
            margin-bottom: 20px;
            color: var(--status-danger);
            font-size: .875rem;
            display: flex;
            gap: 10px;
            align-items: flex-start;
        }

        .kg-fe-errors-icon {
            font-size: 1rem;
            margin-top: 2px;
            flex-shrink: 0;
        }

        .kg-fe-errors ul {
            margin: 4px 0 0 14px;
            padding: 0;
        }

        /* ── Step Card ── */
        .kg-fe-step {
            background: #fff;
            border-radius: 16px;
            box-shadow: var(--shadow-sm);
            margin-bottom: 20px;
            overflow: hidden;
            border: 1px solid var(--border);
            transition: box-shadow var(--transition);
        }

        .kg-fe-step:hover {
            box-shadow: var(--shadow-gold);
        }

        .kg-fe-step-head {
            padding: 18px 22px;
            background: var(--bg-alt);
            border-bottom: 1.5px solid var(--border);
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .kg-fe-step-num {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, #d9834a, var(--primary));
            color: #fff;
            font-size: 1rem;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(192, 102, 42, .3);
        }

        .kg-fe-step-ttl {
            font-size: 1rem;
            font-weight: 700;
            color: var(--text-heading);
        }

        .kg-fe-step-sub {
            font-size: .78rem;
            color: var(--text-muted);
            margin-top: 2px;
        }

        .kg-fe-step-body {
            padding: 22px;
        }

        /* ── Grid & Field ── */
        .kg-fe-row3 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 14px;
        }

        .kg-fe-fg {
            margin-bottom: 14px;
        }

        .kg-fe-fg:last-child {
            margin-bottom: 0;
        }

        .kg-fe-lbl {
            display: block;
            font-size: .75rem;
            font-weight: 700;
            color: var(--text-body);
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: .3px;
        }

        .kg-fe-lbl.req::after {
            content: ' *';
            color: var(--status-danger);
        }

        /* ── Input / Select ── */
        .kg-fe-fi {
            width: 100%;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            padding: 11px 14px;
            font-size: .9rem;
            color: var(--text-body);
            background: var(--bg-alt);
            outline: none;
            font-family: inherit;
            transition: border-color var(--transition), box-shadow var(--transition), background var(--transition);
            box-sizing: border-box;
        }

        input.kg-fe-fi,
        select.kg-fe-fi {
            height: 44px;
            padding: 0 14px;
        }

        .kg-fe-fi:focus {
            border-color: var(--primary);
            background: #fff;
            box-shadow: 0 0 0 3px var(--primary-light);
        }

        .kg-fe-fi.err {
            border-color: var(--status-danger);
            background: #fff8f8;
        }

        .kg-fe-err {
            font-size: .78rem;
            color: var(--status-danger);
            margin-top: 4px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .kg-fe-price-hint {
            font-size: .72rem;
            color: var(--primary);
            font-weight: 600;
            margin-top: 4px;
            min-height: 16px;
        }

        /* ── Select arrow ── */
        .kg-fe-sel {
            appearance: none;
            cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath fill='%237a7a7a' d='M5 6L0 0h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
            background-color: var(--bg-alt);
            padding-right: 36px !important;
        }

        .kg-fe-sel:focus {
            background-color: #fff;
        }

        /* ── Loại hình grid ── */
        .kg-fe-loai-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 10px;
        }

        .kg-fe-loai-item {
            border: 1.5px solid var(--border);
            border-radius: 12px;
            padding: 14px 8px;
            text-align: center;
            cursor: pointer;
            font-size: .78rem;
            font-weight: 600;
            color: var(--text-muted);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            transition: all var(--transition);
            background: var(--bg-alt);
            user-select: none;
        }

        .kg-fe-loai-item i {
            font-size: 1.4rem;
        }

        .kg-fe-loai-item:hover {
            border-color: var(--primary);
            background: var(--primary-light);
            color: var(--primary);
            transform: translateY(-2px);
        }

        .kg-fe-loai-item.active {
            border-color: var(--primary);
            background: var(--primary-light);
            color: var(--primary);
            box-shadow: 0 4px 14px rgba(192, 102, 42, .2);
            transform: translateY(-2px);
        }

        /* ── Nhu cầu ── */
        .kg-fe-nc-wrap {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .kg-fe-nc-item {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            cursor: pointer;
            font-size: .9rem;
            font-weight: 600;
            color: var(--text-muted);
            transition: all var(--transition);
            background: var(--bg-alt);
            user-select: none;
        }

        .kg-fe-nc-item:hover {
            border-color: var(--text-muted);
        }

        .kg-fe-nc-item.active-ban {
            border-color: var(--status-danger);
            background: rgba(198, 40, 40, .06);
            color: var(--status-danger);
        }

        .kg-fe-nc-item.active-thue {
            border-color: var(--status-success);
            background: rgba(46, 125, 50, .06);
            color: var(--status-success);
        }

        /* ── Divider ── */
        .kg-fe-divider {
            font-size: .75rem;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: .5px;
            padding: 12px 0 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .kg-fe-divider::before,
        .kg-fe-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
        }

        /* ── Hidden box ── */
        .kg-hidden {
            display: none;
        }

        /* ── Upload ── */
        .kg-fe-upload-box {
            border: 2px dashed var(--border);
            border-radius: 12px;
            padding: 32px 20px;
            text-align: center;
            cursor: pointer;
            transition: all var(--transition);
            background: var(--bg-alt);
        }

        .kg-fe-upload-box:hover {
            border-color: var(--primary);
            background: var(--primary-light);
        }

        .kg-fe-upload-icon {
            font-size: 2rem;
            color: var(--text-muted);
            display: block;
            margin-bottom: 10px;
            transition: color var(--transition);
        }

        .kg-fe-upload-box:hover .kg-fe-upload-icon {
            color: var(--primary);
        }

        .kg-fe-upload-text {
            font-size: .875rem;
            color: var(--text-body);
            font-weight: 600;
        }

        .kg-fe-upload-hint {
            font-size: .72rem;
            color: var(--text-muted);
            margin-top: 4px;
        }

        .kg-fe-img-preview {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 8px;
            margin-top: 10px;
        }

        /* ── Submit ── */
        .kg-fe-submit-wrap {
            text-align: center;
            padding: 10px 0 30px;
        }

        .kg-fe-submit {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: linear-gradient(135deg, #d9834a, var(--primary));
            color: #fff;
            border: none;
            padding: 16px 52px;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 800;
            cursor: pointer;
            box-shadow: 0 6px 20px rgba(192, 102, 42, .35);
            transition: all var(--transition);
            letter-spacing: .3px;
            font-family: inherit;
        }

        .kg-fe-submit:hover {
            background: var(--primary-dark);
            transform: translateY(-3px);
            box-shadow: var(--shadow-gold);
        }

        .kg-fe-submit:active {
            transform: translateY(0);
        }

        .kg-fe-submit-note {
            margin-top: 10px;
            font-size: .78rem;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
        }

        /* ── Responsive ── */
        @media (max-width: 640px) {
            .kg-fe-row3 {
                grid-template-columns: 1fr;
            }

            .kg-fe-loai-grid {
                grid-template-columns: repeat(3, 1fr);
            }

            .kg-fe-step-body {
                padding: 16px;
            }

            .kg-fe-submit {
                padding: 14px 32px;
                font-size: .95rem;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        function showToast(message, type = 'warning') {
            if (typeof showFlash === 'function') {
                showFlash(message, type);
            }
        }

        function normalizePhone(value) {
            return (value || '').replace(/\D+/g, '');
        }

        function isValidVnPhone(value) {
            return /^0\d{9,12}$/.test(value);
        }

        function isValidEmail(value) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/i.test(value);
        }

        function sanitizeNumericTyping(value) {
            const cleaned = (value || '').replace(/[^\d.,]/g, '');
            const firstSepIndex = cleaned.search(/[.,]/);
            if (firstSepIndex === -1) return cleaned;

            const intPart = cleaned.slice(0, firstSepIndex);
            const sep = cleaned[firstSepIndex];
            const decimalPart = cleaned.slice(firstSepIndex + 1).replace(/[.,]/g, '');
            return intPart + sep + decimalPart;
        }

        function sanitizeIntegerTyping(value) {
            return (value || '').replace(/\D+/g, '');
        }

        function formatInteger(value) {
            const cleaned = (value || '').replace(/\D+/g, '');
            if (!cleaned) return '';
            return cleaned.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        function normalizeLocalizedNumber(value) {
            if (value === null || value === undefined) return null;
            let raw = String(value).trim();
            if (!raw) return null;
            raw = raw.replace(/\s|\u00A0/g, '');

            if (raw.includes(',') && raw.includes('.')) {
                raw = raw.replace(/\./g, '').replace(',', '.');
            } else if (raw.includes(',')) {
                raw = raw.replace(',', '.');
            } else {
                raw = raw.replace(/,/g, '');
            }

            if (!/^\d+(\.\d+)?$/.test(raw)) return null;
            return raw;
        }

        function normalizeInteger(value) {
            if (value === null || value === undefined) return null;
            const raw = String(value).trim().replace(/\D+/g, '');
            return raw || null;
        }

        function formatViNumber(value) {
            const normalized = normalizeLocalizedNumber(value);
            if (!normalized) return '';
            const [intPart, decimalPart] = normalized.split('.');
            const grouped = intPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            return decimalPart ? `${grouped},${decimalPart}` : grouped;
        }

        function formatMoneyHint(value) {
            const isTy = value >= 1_000_000_000;
            const amount = isTy ? (value / 1_000_000_000) : (value / 1_000_000);
            const unit = isTy ? 'tỷ đồng' : 'triệu đồng';
            const formatted = amount.toLocaleString('vi-VN', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 2,
            });

            return `≈ ${formatted} ${unit}`;
        }

        function setFieldError(input, message) {
            if (!input) return;
            input.classList.add('err');
            showToast(message, 'warning');
            input.focus();
        }

        function clearFieldError(input) {
            if (!input) return;
            input.classList.remove('err');
        }

        document.addEventListener('DOMContentLoaded', function() {
            @if ($errors->any())
                const errorMessages = @json($errors->all());
                errorMessages.forEach((msg, index) => {
                    setTimeout(() => {
                        if (typeof showFlash === 'function') {
                            showFlash(msg, 'warning');
                        }
                    }, index * 220);
                });
            @endif

            @if (session('error'))
                if (typeof showFlash === 'function') {
                    showFlash(@json(session('error')), 'error');
                }
            @endif

            const form = document.getElementById('kyGuiForm');
            const phoneInput = form?.querySelector('[name="so_dien_thoai"]');
            const areaInput = form?.querySelector('[name="dien_tich"]');
            const priceInputs = form ? Array.from(form.querySelectorAll('.js-price')) : [];
            const decimalInputs = form ? Array.from(form.querySelectorAll('.js-decimal')) : [];
            const integerInputs = form ? Array.from(form.querySelectorAll('.js-integer')) : [];

            if (phoneInput) {
                phoneInput.addEventListener('input', () => {
                    phoneInput.value = normalizePhone(phoneInput.value);
                    clearFieldError(phoneInput);
                });
            }

            decimalInputs.forEach((input) => {
                input.addEventListener('input', () => {
                    input.value = sanitizeNumericTyping(input.value);
                    clearFieldError(input);
                });

                input.addEventListener('blur', () => {
                    if (!input.value) return;
                    const formatted = formatViNumber(input.value);
                    if (formatted) {
                        input.value = formatted;
                    }
                });
            });

            integerInputs.forEach((input) => {
                input.addEventListener('input', () => {
                    input.value = sanitizeIntegerTyping(input.value);
                    clearFieldError(input);
                });

                input.addEventListener('blur', () => {
                    if (!input.value) return;
                    const formatted = formatInteger(input.value);
                    if (formatted) {
                        input.value = formatted;
                    }
                });
            });

            priceInputs.forEach((input) => {
                input.addEventListener('input', () => {
                    input.value = sanitizeIntegerTyping(input.value);
                    clearFieldError(input);
                });

                input.addEventListener('blur', () => {
                    if (!input.value) return;
                    const formatted = formatInteger(input.value);
                    if (formatted) {
                        input.value = formatted;
                    }
                });

                if (input.value) {
                    const formatted = formatInteger(input.value);
                    if (formatted) input.value = formatted;
                }
            });

            if (areaInput?.value) {
                const formattedArea = formatViNumber(areaInput.value);
                if (formattedArea) areaInput.value = formattedArea;
            }

            form?.addEventListener('submit', function(e) {
                const hoTenInput = form.querySelector('[name="ho_ten_chu_nha"]');
                const emailInput = form.querySelector('[name="email"]');
                const maCanInput = form.querySelector('[name="ma_can"]');
                const nhuCauInput = form.querySelector('input[name="nhu_cau"]:checked');
                const giaBanInput = form.querySelector('[name="gia_ban_mong_muon"]');
                const giaThueInput = form.querySelector('[name="gia_thue_mong_muon"]');

                [hoTenInput, emailInput, maCanInput, phoneInput, areaInput, giaBanInput, giaThueInput]
                .forEach(clearFieldError);

                const hoTen = (hoTenInput?.value || '').trim();
                const email = (emailInput?.value || '').trim();
                const maCan = (maCanInput?.value || '').trim();
                const phone = normalizePhone(phoneInput?.value || '');
                const areaRaw = areaInput?.value || '';
                const areaNormalized = normalizeLocalizedNumber(areaRaw);

                if (!hoTen) {
                    e.preventDefault();
                    return setFieldError(hoTenInput, 'Vui lòng nhập họ tên chủ nhà.');
                }

                if (!isValidVnPhone(phone)) {
                    e.preventDefault();
                    return setFieldError(phoneInput,
                        'Số điện thoại phải bắt đầu bằng 0 và có từ 10 đến 13 chữ số.');
                }

                if (email && !isValidEmail(email)) {
                    e.preventDefault();
                    return setFieldError(emailInput, 'Email không đúng định dạng.');
                }

                if (!maCan) {
                    e.preventDefault();
                    return setFieldError(maCanInput, 'Vui lòng nhập mã căn.');
                }

                if (!nhuCauInput) {
                    e.preventDefault();
                    return showToast('Vui lòng chọn nhu cầu bán hoặc cho thuê.', 'warning');
                }

                if (areaRaw && (!areaNormalized || Number(areaNormalized) <= 0)) {
                    e.preventDefault();
                    return setFieldError(areaInput,
                        'Diện tích phải là số lớn hơn 0 (có thể có phần thập phân).');
                }

                const normalizedGiaBan = normalizeInteger(giaBanInput?.value || '');
                const normalizedGiaThue = normalizeInteger(giaThueInput?.value || '');

                if (giaBanInput?.value && !normalizedGiaBan) {
                    e.preventDefault();
                    return setFieldError(giaBanInput,
                        'Giá bán chỉ được nhập số nguyên (không có phần thập phân).');
                }

                if (giaThueInput?.value && !normalizedGiaThue) {
                    e.preventDefault();
                    return setFieldError(giaThueInput,
                        'Giá thuê chỉ được nhập số.');
                }

                if (nhuCauInput?.value === 'ban' && normalizedGiaBan !== null && Number(normalizedGiaBan) <
                    0) {
                    e.preventDefault();
                    return setFieldError(giaBanInput, 'Giá bán không được âm.');
                }

                if (nhuCauInput?.value === 'thue' && normalizedGiaThue !== null && Number(
                        normalizedGiaThue) < 0) {
                    e.preventDefault();
                    return setFieldError(giaThueInput, 'Giá thuê không được âm.');
                }

                // Convert ve dang backend de validate numeric.
                phoneInput.value = phone;
                if (areaInput) areaInput.value = areaNormalized ?? '';
                if (giaBanInput) giaBanInput.value = normalizedGiaBan ?? '';
                if (giaThueInput) giaThueInput.value = normalizedGiaThue ?? '';
            });
        });

        /* ── Nhu cầu toggle ── */
        document.querySelectorAll('.kg-fe-nc-radio').forEach(r => {
            r.addEventListener('change', function() {
                document.querySelectorAll('.kg-fe-nc-item').forEach(i =>
                    i.classList.remove('active-ban', 'active-thue')
                );
                this.closest('.kg-fe-nc-item')
                    .classList.add(this.value === 'ban' ? 'active-ban' : 'active-thue');
                document.getElementById('kgBanBox').classList.toggle('kg-hidden', this.value !== 'ban');
                document.getElementById('kgThueBox').classList.toggle('kg-hidden', this.value !== 'thue');
            });
        });
        document.querySelectorAll('.kg-fe-nc-item').forEach(item => {
            item.addEventListener('click', () => {
                const r = item.querySelector('input');
                r.checked = true;
                r.dispatchEvent(new Event('change'));
            });
        });

        /* ── Loại hình radio ── */
        function applyLoaiUI(val) {
            document.querySelectorAll('.kg-fe-loai-item').forEach(item => {
                const iv = item.querySelector('input').value;
                item.classList.toggle('active', iv === val);
            });
        }
        applyLoaiUI('{{ old('loai_hinh', 'can_ho') }}');
        document.querySelectorAll('.kg-fe-loai-item').forEach(item => {
            item.addEventListener('click', () => {
                const r = item.querySelector('input');
                r.checked = true;
                applyLoaiUI(r.value);
            });
        });

        /* ── Hiển thị giá dạng tỷ/triệu ── */
        const giaBanInput = document.querySelector('[name="gia_ban_mong_muon"]');
        const giaHienThi = document.getElementById('giaHienThi');
        if (giaBanInput && giaHienThi) {
            giaBanInput.addEventListener('input', function() {
                const normalized = normalizeLocalizedNumber(this.value);
                const v = normalized ? parseFloat(normalized) : null;
                if (!v) {
                    giaHienThi.textContent = '';
                    return;
                }
                giaHienThi.textContent = formatMoneyHint(v);
            });
        }

        /* ── Hiển thị giá thuê dạng triệu ── */
        const giaThueInput = document.querySelector('[name="gia_thue_mong_muon"]');
        const giaThueHienThi = document.getElementById('giaThueHienThi');
        if (giaThueInput && giaThueHienThi) {
            giaThueInput.addEventListener('input', function() {
                const normalized = normalizeLocalizedNumber(this.value);
                const v = normalized ? parseFloat(normalized) : null;
                if (!v) {
                    giaThueHienThi.textContent = '';
                    return;
                }
                giaThueHienThi.textContent = formatMoneyHint(v);
            });
        }

        /* ── Preview ảnh ── */
        function previewImages(input) {
            const preview = document.getElementById('imgPreview');
            preview.innerHTML = '';
            Array.from(input.files).slice(0, 5).forEach(file => {
                const reader = new FileReader();
                reader.onload = e => {
                    const div = document.createElement('div');
                    div.style.cssText = 'position:relative;border-radius:8px;overflow:hidden;';
                    div.innerHTML = `
                    <img src="${e.target.result}"
                         style="width:100%;aspect-ratio:4/3;object-fit:cover;display:block;">
                    <div style="position:absolute;bottom:0;left:0;right:0;
                                background:linear-gradient(transparent,rgba(0,0,0,.6));
                                color:#fff;font-size:.65rem;padding:6px 6px 4px;
                                white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                        ${file.name}
                    </div>`;
                    preview.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        }

        /* ── Drag & Drop ── */
        const uploadBox = document.getElementById('uploadBox');
        uploadBox.addEventListener('dragover', e => {
            e.preventDefault();
            uploadBox.style.borderColor = 'var(--primary)';
            uploadBox.style.background = 'var(--primary-light)';
        });
        uploadBox.addEventListener('dragleave', () => {
            uploadBox.style.borderColor = 'var(--border)';
            uploadBox.style.background = 'var(--bg-alt)';
        });
        uploadBox.addEventListener('drop', e => {
            e.preventDefault();
            uploadBox.style.borderColor = 'var(--border)';
            uploadBox.style.background = 'var(--bg-alt)';
            const fi = document.getElementById('fileInput');
            fi.files = e.dataTransfer.files;
            previewImages(fi);
        });
    </script>
@endpush
