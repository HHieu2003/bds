@extends('frontend.layouts.master')
@section('title', 'Ký gửi BĐS — Thành Công Land')

@section('content')
    <div class="kg-fe-wrap">

        {{-- HERO --}}
        <div class="kg-fe-hero">
            <div class="kg-fe-hero-inner">
                <i class="fas fa-file-signature kg-fe-hero-icon"></i>
                <h1>Ký gửi Bất động sản</h1>
                <p>Điền thông tin bên dưới — đội ngũ chuyên viên sẽ liên hệ trong vòng <strong>2 giờ</strong></p>
            </div>
        </div>

        <div class="kg-fe-container">

            {{-- VALIDATION ERRORS --}}
            @if ($errors->any())
                <div class="kg-fe-errors">
                    <i class="fas fa-exclamation-triangle"></i>
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
                <div class="kg-fe-step">
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
                                    <div class="kg-fe-err">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="kg-fe-fg">
                                <label class="kg-fe-lbl req">Số điện thoại</label>
                                <input type="tel" name="so_dien_thoai"
                                    class="kg-fe-fi @error('so_dien_thoai') err @enderror"
                                    value="{{ old('so_dien_thoai', auth('customer')->user()?->so_dien_thoai) }}"
                                    placeholder="0901 234 567">
                                @error('so_dien_thoai')
                                    <div class="kg-fe-err">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="kg-fe-fg">
                                <label class="kg-fe-lbl">Email</label>
                                <input type="email" name="email" class="kg-fe-fi"
                                    value="{{ old('email', auth('customer')->user()?->email) }}"
                                    placeholder="email@example.com">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ═══ BƯỚC 2: LOẠI HÌNH & NHU CẦU ═══ --}}
                <div class="kg-fe-step">
                    <div class="kg-fe-step-head">
                        <span class="kg-fe-step-num">2</span>
                        <div>
                            <div class="kg-fe-step-ttl">Loại hình & Nhu cầu</div>
                            <div class="kg-fe-step-sub">Bạn muốn bán hay cho thuê?</div>
                        </div>
                    </div>
                    <div class="kg-fe-step-body">

                        {{-- Loại hình --}}
                        <div class="kg-fe-fg" style="margin-bottom:20px">
                            <label class="kg-fe-lbl req">Loại hình bất động sản</label>
                            <div class="kg-fe-loai-grid">
                                @foreach (\App\Models\KyGui::LOAI_HINH as $v => $info)
                                    <label class="kg-fe-loai-item {{ old('loai_hinh', 'can_ho') === $v ? 'active' : '' }}"
                                        id="loaiLabel_{{ $v }}"
                                        style="{{ old('loai_hinh', 'can_ho') === $v ? 'border-color:' . $info['color'] . ';color:' . $info['color'] . ';background:' . ($v === 'can_ho' ? '#e8f4fd' : ($v === 'nha_pho' ? '#fff8f0' : ($v === 'biet_thu' ? '#f5eeff' : ($v === 'dat_nen' ? '#e8f8f0' : '#fff0f0')))) : '' }}">
                                        <input type="radio" name="loai_hinh" value="{{ $v }}"
                                            {{ old('loai_hinh', 'can_ho') === $v ? 'checked' : '' }} style="display:none"
                                            class="kg-fe-loai-radio">
                                        <i class="{{ $info['icon'] }}" style="font-size:1.4rem;margin-bottom:6px"></i>
                                        <span>{{ $info['label'] }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('loai_hinh')
                                <div class="kg-fe-err">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Nhu cầu --}}
                        <div class="kg-fe-fg">
                            <label class="kg-fe-lbl req">Nhu cầu</label>
                            <div style="display:flex;gap:12px">
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

                    </div>
                </div>

                {{-- ═══ BƯỚC 3: THÔNG TIN BẤT ĐỘNG SẢN ═══ --}}
                <div class="kg-fe-step">
                    <div class="kg-fe-step-head">
                        <span class="kg-fe-step-num">3</span>
                        <div>
                            <div class="kg-fe-step-ttl">Thông tin bất động sản</div>
                            <div class="kg-fe-step-sub">Thông tin càng chi tiết, tư vấn càng chính xác</div>
                        </div>
                    </div>
                    <div class="kg-fe-step-body">

                        <div class="kg-fe-fg" style="margin-bottom:14px">
                            <label class="kg-fe-lbl">Địa chỉ</label>
                            <input type="text" name="dia_chi" class="kg-fe-fi" value="{{ old('dia_chi') }}"
                                placeholder="Số nhà, đường, phường/xã, quận/huyện...">
                        </div>

                        <div class="kg-fe-row3">
                            <div class="kg-fe-fg">
                                <label class="kg-fe-lbl req">Diện tích (m²)</label>
                                <input type="number" name="dien_tich" class="kg-fe-fi @error('dien_tich') err @enderror"
                                    value="{{ old('dien_tich') }}" placeholder="75" min="1" step="0.1">
                                @error('dien_tich')
                                    <div class="kg-fe-err">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="kg-fe-fg">
                                <label class="kg-fe-lbl">Hướng nhà</label>
                                <select name="huong_nha" class="kg-fe-fi kg-fe-sel">
                                    <option value="">— Chọn hướng —</option>
                                    @foreach (['dong' => 'Đông', 'tay' => 'Tây', 'nam' => 'Nam', 'bac' => 'Bắc', 'dong_nam' => 'Đông Nam', 'dong_bac' => 'Đông Bắc', 'tay_nam' => 'Tây Nam', 'tay_bac' => 'Tây Bắc'] as $v => $l)
                                        <option value="{{ $v }}" @selected(old('huong_nha') == $v)>
                                            {{ $l }}</option>
                                    @endforeach
                                </select>
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
                                <select name="so_phong_ngu" class="kg-fe-fi kg-fe-sel">
                                    <option value="0">Studio / Không có</option>
                                    @for ($i = 1; $i <= 6; $i++)
                                        <option value="{{ $i }}" @selected(old('so_phong_ngu') == $i)>
                                            {{ $i }} phòng</option>
                                    @endfor
                                    <option value="7" @selected(old('so_phong_ngu') >= 7)>7+ phòng</option>
                                </select>
                            </div>
                            <div class="kg-fe-fg">
                                <label class="kg-fe-lbl">Số phòng tắm/WC</label>
                                <select name="so_phong_tam" class="kg-fe-fi kg-fe-sel">
                                    <option value="0">Không có</option>
                                    @for ($i = 1; $i <= 5; $i++)
                                        <option value="{{ $i }}" @selected(old('so_phong_tam') == $i)>
                                            {{ $i }} WC</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        {{-- GIÁ BÁN (dynamic) --}}
                        <div id="kgBanBox" style="{{ old('nhu_cau', 'ban') !== 'ban' ? 'display:none' : '' }}">
                            <div class="kg-fe-divider">💰 Thông tin giá bán</div>
                            <div class="kg-fe-row3">
                                <div class="kg-fe-fg">
                                    <label class="kg-fe-lbl">Giá bán mong muốn (VNĐ)</label>
                                    <input type="number" name="gia_ban_mong_muon" class="kg-fe-fi"
                                        value="{{ old('gia_ban_mong_muon') }}" placeholder="3500000000" step="1000000">
                                    <div style="font-size:.72rem;color:#bbb;margin-top:3px" id="giaHienThi"></div>
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

                        {{-- GIÁ THUÊ (dynamic) --}}
                        <div id="kgThueBox" style="{{ old('nhu_cau') !== 'thue' ? 'display:none' : '' }}">
                            <div class="kg-fe-divider">💰 Thông tin giá thuê</div>
                            <div class="kg-fe-row3">
                                <div class="kg-fe-fg">
                                    <label class="kg-fe-lbl">Giá thuê/tháng (VNĐ)</label>
                                    <input type="number" name="gia_thue_mong_muon" class="kg-fe-fi"
                                        value="{{ old('gia_thue_mong_muon') }}" placeholder="15000000" step="500000">
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

                {{-- ═══ BƯỚC 4: HÌNH ẢNH & GHI CHÚ ═══ --}}
                <div class="kg-fe-step">
                    <div class="kg-fe-step-head">
                        <span class="kg-fe-step-num">4</span>
                        <div>
                            <div class="kg-fe-step-ttl">Hình ảnh & Ghi chú</div>
                            <div class="kg-fe-step-sub">Hình ảnh thực tế giúp tăng khả năng được chọn</div>
                        </div>
                    </div>
                    <div class="kg-fe-step-body">

                        <div class="kg-fe-fg" style="margin-bottom:16px">
                            <label class="kg-fe-lbl">Hình ảnh tham khảo (tối đa 5 ảnh, mỗi ảnh ≤ 3MB)</label>
                            <div class="kg-fe-upload-box" id="uploadBox"
                                onclick="document.getElementById('fileInput').click()">
                                <input type="file" id="fileInput" name="hinh_anh_tham_khao[]" multiple
                                    accept="image/*" style="display:none" onchange="previewImages(this)">
                                <i class="fas fa-cloud-upload-alt"
                                    style="font-size:2rem;color:#dde;margin-bottom:8px"></i>
                                <div>Nhấn để chọn ảnh hoặc kéo thả vào đây</div>
                                <div style="font-size:.75rem;color:#bbb;margin-top:4px">JPG, PNG, WebP</div>
                            </div>
                            <div id="imgPreview"
                                style="display:grid;grid-template-columns:repeat(auto-fill,minmax(100px,1fr));gap:8px;margin-top:10px">
                            </div>
                        </div>

                        <div class="kg-fe-fg">
                            <label class="kg-fe-lbl">Ghi chú thêm</label>
                            <textarea name="ghi_chu" class="kg-fe-fi" rows="4"
                                placeholder="Ghi chú thêm: vị trí cụ thể, lý do bán/cho thuê, yêu cầu đặc biệt...">{{ old('ghi_chu') }}</textarea>
                        </div>

                    </div>
                </div>

                {{-- SUBMIT --}}
                <div style="text-align:center;padding:10px 0 30px">
                    <button type="submit" class="kg-fe-submit">
                        <i class="fas fa-paper-plane"></i>
                        Gửi yêu cầu ký gửi
                    </button>
                    <div style="margin-top:10px;font-size:.8rem;color:#aaa">
                        <i class="fas fa-shield-alt"></i>
                        Thông tin của bạn được bảo mật tuyệt đối
                    </div>
                </div>

            </form>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .kg-fe-wrap {
            background: #f8faff;
            min-height: 100vh;
            padding-bottom: 60px
        }

        .kg-fe-hero {
            background: linear-gradient(135deg, #1a3c5e, #2d6a9f);
            padding: 60px 20px 40px;
            text-align: center;
            color: #fff
        }

        .kg-fe-hero-icon {
            font-size: 2.5rem;
            margin-bottom: 12px;
            opacity: .9;
            display: block
        }

        .kg-fe-hero h1 {
            font-size: 1.8rem;
            font-weight: 800;
            margin: 0 0 10px
        }

        .kg-fe-hero p {
            font-size: .95rem;
            opacity: .85;
            margin: 0
        }

        .kg-fe-hero p strong {
            color: #FF8C42
        }

        .kg-fe-container {
            max-width: 800px;
            margin: -30px auto 0;
            padding: 0 16px
        }

        .kg-fe-errors {
            background: #fff5f5;
            border: 1.5px solid #fcc;
            border-radius: 12px;
            padding: 14px 18px;
            margin-bottom: 20px;
            color: #c0392b;
            font-size: .875rem
        }

        .kg-fe-errors ul {
            margin: 6px 0 0 18px;
            padding: 0
        }

        .kg-fe-step {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, .07);
            margin-bottom: 20px;
            overflow: hidden
        }

        .kg-fe-step-head {
            padding: 18px 22px;
            background: linear-gradient(135deg, #f8faff, #eef3ff);
            border-bottom: 1.5px solid #e8eeff;
            display: flex;
            align-items: center;
            gap: 14px
        }

        .kg-fe-step-num {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: linear-gradient(135deg, #FF8C42, #f5a623);
            color: #fff;
            font-size: 1rem;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 3px 10px rgba(255, 140, 66, .3)
        }

        .kg-fe-step-ttl {
            font-size: 1rem;
            font-weight: 700;
            color: #1a3c5e
        }

        .kg-fe-step-sub {
            font-size: .78rem;
            color: #aaa;
            margin-top: 2px
        }

        .kg-fe-step-body {
            padding: 22px
        }

        .kg-fe-row3 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 14px
        }

        @media(max-width:640px) {
            .kg-fe-row3 {
                grid-template-columns: 1fr
            }
        }

        .kg-fe-fg {
            margin-bottom: 14px
        }

        .kg-fe-fg:last-child {
            margin-bottom: 0
        }

        .kg-fe-lbl {
            display: block;
            font-size: .78rem;
            font-weight: 700;
            color: #555;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: .3px
        }

        .kg-fe-lbl.req::after {
            content: ' *';
            color: #e74c3c
        }

        .kg-fe-fi {
            width: 100%;
            border: 1.5px solid #e8e8e8;
            border-radius: 10px;
            padding: 11px 14px;
            font-size: .9rem;
            color: #333;
            background: #fafafa;
            outline: none;
            font-family: inherit;
            transition: border-color .2s, box-shadow .2s;
            box-sizing: border-box
        }

        input.kg-fe-fi,
        select.kg-fe-fi {
            height: 44px;
            padding: 0 14px
        }

        .kg-fe-fi:focus {
            border-color: #FF8C42;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(255, 140, 66, .1)
        }

        .kg-fe-fi.err {
            border-color: #e74c3c;
            background: #fff8f8
        }

        .kg-fe-err {
            font-size: .78rem;
            color: #e74c3c;
            margin-top: 4px;
            display: flex;
            align-items: center;
            gap: 3px
        }

        .kg-fe-sel {
            appearance: none;
            cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath fill='%23aaa' d='M5 6L0 0h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
            background-color: #fafafa;
            padding-right: 36px !important
        }

        .kg-fe-loai-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 10px
        }

        @media(max-width:600px) {
            .kg-fe-loai-grid {
                grid-template-columns: repeat(3, 1fr)
            }
        }

        .kg-fe-loai-item {
            border: 1.5px solid #f0f2f5;
            border-radius: 12px;
            padding: 14px 8px;
            text-align: center;
            cursor: pointer;
            font-size: .78rem;
            font-weight: 600;
            color: #aaa;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 5px;
            transition: all .2s;
            background: #fafafa
        }

        .kg-fe-loai-item:hover {
            border-color: #dde;
            background: #f5f8ff
        }

        .kg-fe-loai-item.active {
            box-shadow: 0 4px 12px rgba(0, 0, 0, .1)
        }

        .kg-fe-nc-item {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            border: 1.5px solid #e8e8e8;
            border-radius: 10px;
            cursor: pointer;
            font-size: .9rem;
            font-weight: 600;
            color: #aaa;
            transition: all .2s;
            background: #fafafa
        }

        .kg-fe-nc-item.active-ban {
            border-color: #e74c3c;
            background: #fff0f0;
            color: #e74c3c
        }

        .kg-fe-nc-item.active-thue {
            border-color: #27ae60;
            background: #e8f8f0;
            color: #27ae60
        }

        .kg-fe-divider {
            font-size: .78rem;
            font-weight: 700;
            color: #aaa;
            text-transform: uppercase;
            letter-spacing: .5px;
            padding: 10px 0 14px;
            display: flex;
            align-items: center;
            gap: 8px
        }

        .kg-fe-divider::before,
        .kg-fe-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #f0f2f5
        }

        .kg-fe-upload-box {
            border: 2px dashed #d8e0f0;
            border-radius: 12px;
            padding: 30px;
            text-align: center;
            cursor: pointer;
            transition: all .2s;
            font-size: .875rem;
            color: #888;
            background: #fafbff
        }

        .kg-fe-upload-box:hover {
            border-color: #FF8C42;
            background: #fff8f5
        }

        .kg-fe-submit {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: linear-gradient(135deg, #FF8C42, #f5a623);
            color: #fff;
            border: none;
            padding: 16px 48px;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 800;
            cursor: pointer;
            box-shadow: 0 6px 20px rgba(255, 140, 66, .4);
            transition: all .2s;
            letter-spacing: .3px
        }

        .kg-fe-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 28px rgba(255, 140, 66, .5)
        }
    </style>
@endpush

@push('scripts')
    <script>
        // ── Nhu cầu toggle ──
        document.querySelectorAll('.kg-fe-nc-radio').forEach(r => {
            r.addEventListener('change', function() {
                document.querySelectorAll('.kg-fe-nc-item').forEach(i => {
                    i.classList.remove('active-ban', 'active-thue');
                });
                this.closest('.kg-fe-nc-item').classList.add(
                    this.value === 'ban' ? 'active-ban' : 'active-thue'
                );
                document.getElementById('kgBanBox').style.display = this.value === 'ban' ? '' : 'none';
                document.getElementById('kgThueBox').style.display = this.value === 'thue' ? '' : 'none';
            });
        });

        document.querySelectorAll('.kg-fe-nc-item').forEach(item => {
            item.addEventListener('click', () => {
                const r = item.querySelector('input');
                r.checked = true;
                r.dispatchEvent(new Event('change'));
            });
        });

        // ── Loại hình radio color ──
        const LOAI_COLORS = {
            can_ho: {
                color: '#2d6a9f',
                bg: '#e8f4fd'
            },
            nha_pho: {
                color: '#e67e22',
                bg: '#fff8f0'
            },
            biet_thu: {
                color: '#9b59b6',
                bg: '#f5eeff'
            },
            dat_nen: {
                color: '#27ae60',
                bg: '#e8f8f0'
            },
            shophouse: {
                color: '#e74c3c',
                bg: '#fff0f0'
            },
        };

        function applyLoaiUI(val) {
            document.querySelectorAll('.kg-fe-loai-item').forEach(item => {
                const iv = item.querySelector('input').value;
                item.classList.remove('active');
                item.style.borderColor = '#f0f2f5';
                item.style.background = '#fafafa';
                item.style.color = '#aaa';
                if (iv === val) {
                    const c = LOAI_COLORS[iv] || {
                        color: '#999',
                        bg: '#f5f5f5'
                    };
                    item.classList.add('active');
                    item.style.borderColor = c.color;
                    item.style.background = c.bg;
                    item.style.color = c.color;
                }
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

        // ── Hiển thị giá bán dạng tỷ/triệu ──
        const giaBanInput = document.querySelector('[name="gia_ban_mong_muon"]');
        const giaHienThi = document.getElementById('giaHienThi');
        if (giaBanInput && giaHienThi) {
            giaBanInput.addEventListener('input', function() {
                const v = parseFloat(this.value);
                if (!v) {
                    giaHienThi.textContent = '';
                    return;
                }
                if (v >= 1_000_000_000) {
                    giaHienThi.textContent = '≈ ' + (v / 1_000_000_000).toFixed(2) + ' tỷ đồng';
                } else {
                    giaHienThi.textContent = '≈ ' + (v / 1_000_000).toFixed(0) + ' triệu đồng';
                }
            });
        }

        // ── Preview ảnh ──
        function previewImages(input) {
            const preview = document.getElementById('imgPreview');
            preview.innerHTML = '';
            const files = Array.from(input.files).slice(0, 5);
            files.forEach(file => {
                const reader = new FileReader();
                reader.onload = e => {
                    const div = document.createElement('div');
                    div.style.cssText = 'position:relative';
                    div.innerHTML = `
                <img src="${e.target.result}"
                     style="width:100%;aspect-ratio:4/3;object-fit:cover;border-radius:8px;border:1.5px solid #e8e8e8">
                <div style="position:absolute;bottom:4px;left:4px;right:4px;background:rgba(0,0,0,.5);color:#fff;font-size:.65rem;padding:2px 5px;border-radius:4px;overflow:hidden;white-space:nowrap;text-overflow:ellipsis">
                    ${file.name}
                </div>`;
                    preview.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        }

        // ── Drag & Drop upload ──
        const uploadBox = document.getElementById('uploadBox');
        uploadBox.addEventListener('dragover', e => {
            e.preventDefault();
            uploadBox.style.borderColor = '#FF8C42';
            uploadBox.style.background = '#fff8f5';
        });
        uploadBox.addEventListener('dragleave', () => {
            uploadBox.style.borderColor = '#d8e0f0';
            uploadBox.style.background = '#fafbff';
        });
        uploadBox.addEventListener('drop', e => {
            e.preventDefault();
            uploadBox.style.borderColor = '#d8e0f0';
            uploadBox.style.background = '#fafbff';
            const fileInput = document.getElementById('fileInput');
            fileInput.files = e.dataTransfer.files;
            previewImages(fileInput);
        });
    </script>
@endpush
