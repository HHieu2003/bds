@php
    // Biến helper dùng xuyên suốt form — tránh lặp lại
    $isEdit = isset($batDongSan) && $batDongSan !== null;
    $oldLoaiHinh = old('loai_hinh', $isEdit ? $batDongSan->loai_hinh : '');
    $oldNhuCau = old('nhu_cau', $isEdit ? $batDongSan->nhu_cau : '');
    $oldTrangThai = old('trang_thai', $isEdit ? $batDongSan->trang_thai : 'con_hang');

    $huongs = ['Đông', 'Tây', 'Nam', 'Bắc', 'Đông Nam', 'Đông Bắc', 'Tây Nam', 'Tây Bắc'];

    // Ngày đăng
    $ngayDang = '';
    if ($isEdit && $batDongSan->thoi_diem_dang) {
        $ngayDang = $batDongSan->thoi_diem_dang->format('Y-m-d');
    } else {
        $ngayDang = date('Y-m-d');
    }
    $ngayDang = old('thoi_diem_dang', $ngayDang);
@endphp

<div class="form-grid">

    {{-- ══════════════════════════════
     CỘT TRÁI
══════════════════════════════ --}}
    <div class="form-left">

        {{-- ① THÔNG TIN CƠ BẢN --}}
        <div class="fc">
            <div class="fc-head"><i class="fas fa-home"></i> Thông tin cơ bản</div>
            <div class="fc-body">

                <div class="fg">
                    <label class="fl req">Tiêu đề tin đăng</label>
                    <input type="text" name="tieu_de" class="fi @error('tieu_de') fi-err @enderror"
                        value="{{ old('tieu_de', $isEdit ? $batDongSan->tieu_de : '') }}"
                        placeholder="VD: Cho thuê căn hộ 2PN Vinhomes Smart City, full nội thất, view hồ" autofocus>
                    @error('tieu_de')
                        <div class="fe-msg"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="fg-row2">
                    <div class="fg">
                        <label class="fl req">Loại hình BĐS</label>
                        <select name="loai_hinh" id="sel_loai" class="fi @error('loai_hinh') fi-err @enderror">
                            <option value="">-- Chọn loại --</option>
                            @foreach ($constants['loai_hinh'] as $v => $l)
                                <option value="{{ $v }}" {{ $oldLoaiHinh == $v ? 'selected' : '' }}>
                                    {{ $l }}
                                </option>
                            @endforeach
                        </select>
                        @error('loai_hinh')
                            <div class="fe-msg"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    <div class="fg">
                        <label class="fl req">Nhu cầu</label>
                        <select name="nhu_cau" id="sel_nhucau" class="fi @error('nhu_cau') fi-err @enderror">
                            <option value="">-- Chọn --</option>
                            @foreach ($constants['nhu_cau'] as $v => $l)
                                <option value="{{ $v }}" {{ $oldNhuCau == $v ? 'selected' : '' }}>
                                    {{ $l }}
                                </option>
                            @endforeach
                        </select>
                        @error('nhu_cau')
                            <div class="fe-msg"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="fg-row2">
                    <div class="fg">
                        <label class="fl">Thuộc dự án <span class="fhint">không bắt buộc</span></label>
                        <select name="du_an_id" class="fi">
                            <option value="">-- Không thuộc dự án --</option>
                            @foreach ($duAns as $da)
                                <option value="{{ $da->id }}"
                                    {{ old('du_an_id', $isEdit ? $batDongSan->du_an_id : '') == $da->id ? 'selected' : '' }}>
                                    {{ $da->ten_du_an }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="fg">
                        <label class="fl">NV phụ trách <span class="fhint">không bắt buộc</span></label>
                        <select name="nhan_vien_phu_trach_id" class="fi">
                            <option value="">-- Chưa phân công --</option>
                            @foreach ($nhanViens as $nv)
                                <option value="{{ $nv->id }}"
                                    {{ old('nhan_vien_phu_trach_id', $isEdit ? $batDongSan->nhan_vien_phu_trach_id : '') == $nv->id ? 'selected' : '' }}>
                                    {{ $nv->ho_ten }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

            </div>
        </div>{{-- end fc --}}

        {{-- ② VỊ TRÍ & CHI TIẾT CĂN --}}
        <div class="fc">
            <div class="fc-head"><i class="fas fa-map-pin"></i> Vị trí & Chi tiết căn</div>
            <div class="fc-body">

                {{-- Tòa / Tầng / Mã căn --}}
                <div class="fg-row3" id="row_toatang">
                    <div class="fg">
                        <label class="fl">Tòa nhà</label>
                        <input type="text" name="toa" class="fi"
                            value="{{ old('toa', $isEdit ? $batDongSan->toa : '') }}" placeholder="VD: S2.05">
                    </div>
                    <div class="fg">
                        <label class="fl">Tầng</label>
                        <input type="text" name="tang" class="fi"
                            value="{{ old('tang', $isEdit ? $batDongSan->tang : '') }}" placeholder="VD: 18">
                    </div>
                    <div class="fg">
                        <label class="fl">Mã căn</label>
                        <input type="text" name="ma_can" class="fi"
                            value="{{ old('ma_can', $isEdit ? $batDongSan->ma_can : '') }}" placeholder="VD: 1806">
                    </div>
                </div>

                {{-- Diện tích + Phòng ngủ --}}
                <div class="fg-row2">
                    <div class="fg">
                        <label class="fl req">Diện tích (m²)</label>
                        <input type="number" name="dien_tich" step="0.1" min="1"
                            class="fi @error('dien_tich') fi-err @enderror"
                            value="{{ old('dien_tich', $isEdit ? $batDongSan->dien_tich : '') }}"
                            placeholder="VD: 65.5">
                        @error('dien_tich')
                            <div class="fe-msg"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    <div class="fg">
                        <label class="fl">Số phòng ngủ</label>
                        <select name="so_phong_ngu" class="fi">
                            @php $curPN = old('so_phong_ngu', $isEdit ? $batDongSan->so_phong_ngu : 0); @endphp
                            <option value="0" {{ $curPN == 0 ? 'selected' : '' }}>Studio</option>
                            @for ($i = 1; $i <= 6; $i++)
                                <option value="{{ $i }}" {{ $curPN == $i ? 'selected' : '' }}>
                                    {{ $i }} phòng ngủ</option>
                            @endfor
                            <option value="7" {{ $curPN >= 7 ? 'selected' : '' }}>7+ phòng ngủ</option>
                        </select>
                    </div>
                </div>

                {{-- Hướng cửa + Hướng ban công --}}
                <div class="fg-row2">
                    <div class="fg">
                        <label class="fl">Hướng cửa chính</label>
                        <select name="huong_cua" class="fi">
                            <option value="">-- Không xác định --</option>
                            @foreach ($huongs as $h)
                                <option value="{{ $h }}"
                                    {{ old('huong_cua', $isEdit ? $batDongSan->huong_cua : '') == $h ? 'selected' : '' }}>
                                    {{ $h }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="fg">
                        <label class="fl">Hướng ban công</label>
                        <select name="huong_ban_cong" class="fi">
                            <option value="">-- Không xác định --</option>
                            @foreach ($huongs as $h)
                                <option value="{{ $h }}"
                                    {{ old('huong_ban_cong', $isEdit ? $batDongSan->huong_ban_cong : '') == $h ? 'selected' : '' }}>
                                    {{ $h }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Nội thất + Pháp lý --}}
                <div class="fg-row2">
                    <div class="fg">
                        <label class="fl">Nội thất</label>
                        <select name="noi_that" class="fi">
                            <option value="">-- Không xác định --</option>
                            @foreach ($constants['noi_that'] as $v => $l)
                                <option value="{{ $v }}"
                                    {{ old('noi_that', $isEdit ? $batDongSan->noi_that : '') == $v ? 'selected' : '' }}>
                                    {{ $l }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="fg">
                        <label class="fl">Pháp lý</label>
                        <select name="phap_ly" class="fi">
                            <option value="">-- Không xác định --</option>
                            @foreach ($constants['phap_ly'] as $v => $l)
                                <option value="{{ $v }}"
                                    {{ old('phap_ly', $isEdit ? $batDongSan->phap_ly : '') == $v ? 'selected' : '' }}>
                                    {{ $l }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

            </div>
        </div>{{-- end fc --}}

        {{-- ③ GIÁ BÁN (ẩn/hiện theo nhu cầu) --}}
        <div class="fc gia-block" id="gb_ban">
            <div class="fc-head" style="border-bottom-color:#fff3e0">
                <i class="fas fa-tag" style="color:#e67e22"></i> Thông tin giá bán
            </div>
            <div class="fc-body">
                <div class="fg-row3">
                    <div class="fg">
                        <label class="fl req">Giá bán (VNĐ)</label>
                        <input type="number" name="gia" step="100000000" min="0"
                            class="fi @error('gia') fi-err @enderror" id="inp_gia"
                            value="{{ old('gia', $isEdit ? $batDongSan->gia : '') }}" placeholder="VD: 3500000000">
                        <small class="gia-hint" id="gia_hint">
                            @if ($isEdit && $batDongSan->gia > 0)
                                = {{ number_format($batDongSan->gia / 1e9, 3) }} tỷ
                            @else
                                Nhập số VNĐ đầy đủ
                            @endif
                        </small>
                        @error('gia')
                            <div class="fe-msg"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    <div class="fg">
                        <label class="fl">Phí môi giới (VNĐ)</label>
                        <input type="number" name="phi_moi_gioi" step="1000000" min="0" class="fi"
                            value="{{ old('phi_moi_gioi', $isEdit ? $batDongSan->phi_moi_gioi : '') }}"
                            placeholder="VD: 50000000">
                    </div>

                    <div class="fg">
                        <label class="fl">Phí sang tên (VNĐ)</label>
                        <input type="number" name="phi_sang_ten" step="1000000" min="0" class="fi"
                            value="{{ old('phi_sang_ten', $isEdit ? $batDongSan->phi_sang_ten : '') }}"
                            placeholder="VD: 30000000">
                    </div>
                </div>
            </div>
        </div>

        {{-- ④ GIÁ THUÊ (ẩn/hiện theo nhu cầu) --}}
        <div class="fc gia-block" id="gb_thue">
            <div class="fc-head" style="border-bottom-color:#e8f4fd">
                <i class="fas fa-key" style="color:#2d6a9f"></i> Thông tin cho thuê
            </div>
            <div class="fc-body">
                <div class="fg-row3">
                    <div class="fg">
                        <label class="fl req">Giá thuê / tháng (VNĐ)</label>
                        <input type="number" name="gia_thue" step="500000" min="0"
                            class="fi @error('gia_thue') fi-err @enderror"
                            value="{{ old('gia_thue', $isEdit ? $batDongSan->gia_thue : '') }}"
                            placeholder="VD: 8000000">
                        @error('gia_thue')
                            <div class="fe-msg"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    <div class="fg">
                        <label class="fl">Thời gian vào thuê</label>
                        <select name="thoi_gian_vao_thue" class="fi">
                            <option value="">-- Chọn --</option>
                            @foreach ($constants['thoi_gian_vao_thue'] as $v => $l)
                                <option value="{{ $v }}"
                                    {{ old('thoi_gian_vao_thue', $isEdit ? $batDongSan->thoi_gian_vao_thue : '') == $v ? 'selected' : '' }}>
                                    {{ $l }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="fg">
                        <label class="fl">Hình thức thanh toán</label>
                        <select name="hinh_thuc_thanh_toan" class="fi">
                            <option value="">-- Chọn --</option>
                            @foreach ($constants['hinh_thuc_tt'] as $v => $l)
                                <option value="{{ $v }}"
                                    {{ old('hinh_thuc_thanh_toan', $isEdit ? $batDongSan->hinh_thuc_thanh_toan : '') == $v ? 'selected' : '' }}>
                                    {{ $l }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        {{-- ⑤ MÔ TẢ (ĐÃ TÍCH HỢP CKEDITOR) --}}
        <div class="fc">
            <div class="fc-head"><i class="fas fa-align-left"></i> Mô tả bất động sản</div>
            <div class="fc-body">
                <div class="fg">
                    <label class="fl">
                        Nội dung mô tả chi tiết
                        <span class="fhint">Soạn thảo phong phú</span>
                    </label>
                    <textarea name="mo_ta" id="moTaBdsEditor">{{ old('mo_ta', $isEdit ? $batDongSan->mo_ta : '') }}</textarea>
                </div>
            </div>
        </div>

        {{-- ⑥ ALBUM ẢNH --}}
        <div class="fc">
            <div class="fc-head">
                <i class="fas fa-images"></i> Album ảnh
                <span class="fhint">tối đa 20 ảnh • mỗi ảnh ≤ 3MB • JPEG, PNG, WEBP</span>
            </div>
            <div class="fc-body">

                {{-- Ảnh hiện có khi edit --}}
                @if ($isEdit && !empty($batDongSan->album_anh) && count($batDongSan->album_anh) > 0)
                    <div class="album-grid" id="albumExist">
                        @foreach ($batDongSan->album_anh as $imgPath)
                            @php $imgKey = substr(md5($imgPath), 0, 12); @endphp
                            <div class="alb-item" id="alb_{{ $imgKey }}">
                                <img src="{{ asset('storage/' . $imgPath) }}" alt="">
                                <button type="button" class="alb-del-btn" data-bds="{{ $batDongSan->id }}"
                                    data-path="{{ $imgPath }}" data-key="{{ $imgKey }}" title="Xóa ảnh">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Preview ảnh mới chọn --}}
                <div class="album-grid" id="albumPreview"></div>

                {{-- Upload zone --}}
                <div class="upload-zone" id="uploadZone" onclick="document.getElementById('inp_album').click()">
                    <input type="file" id="inp_album" name="album_anh[]" multiple
                        accept="image/jpeg,image/png,image/webp" style="display:none">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <span>Click để chọn nhiều ảnh</span>
                    <small>JPEG, PNG, WEBP • Tối đa 3MB/ảnh</small>
                </div>

                @error('album_anh.*')
                    <div class="fe-msg" style="margin-top:8px">
                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                    </div>
                @enderror

            </div>
        </div>

        {{-- ⑦ SEO --}}
        <div class="fc">
            <div class="fc-head">
                <i class="fas fa-search"></i> SEO
                <span class="fhint">không bắt buộc</span>
            </div>
            <div class="fc-body">
                <div class="fg">
                    <label class="fl">SEO Title <span class="fhint">để trống = dùng tiêu đề tin
                            đăng</span></label>
                    <input type="text" name="seo_title" class="fi" maxlength="255"
                        value="{{ old('seo_title', $isEdit ? $batDongSan->seo_title : '') }}"
                        placeholder="Tiêu đề hiển thị trên Google">
                </div>
                <div class="fg">
                    <label class="fl">SEO Description <span class="fhint">tối đa 160 ký tự</span></label>
                    <textarea name="seo_description" class="fi" rows="2" maxlength="500"
                        placeholder="Mô tả ngắn xuất hiện dưới tiêu đề trên Google...">{{ old('seo_description', $isEdit ? $batDongSan->seo_description : '') }}</textarea>
                </div>
                <div class="fg">
                    <label class="fl">Từ khóa SEO <span class="fhint">phân cách bằng dấu phẩy</span></label>
                    <input type="text" name="seo_keywords" class="fi"
                        value="{{ old('seo_keywords', $isEdit ? $batDongSan->seo_keywords : '') }}"
                        placeholder="căn hộ cho thuê hà nội, vinhomes smart city, 2 phòng ngủ">
                </div>
            </div>
        </div>

    </div>{{-- end form-left --}}

    {{-- ══════════════════════════════
     CỘT PHẢI — SIDEBAR
══════════════════════════════ --}}
    <div class="form-right">

        {{-- NÚT LƯU trên cùng --}}
        <div class="fc fc-save">
            <div class="fc-body" style="padding:16px">
                <button type="submit" class="btn-save" form="bdsForm">
                    <i class="fas fa-save"></i>
                    {{ $isEdit ? 'Lưu thay đổi' : 'Đăng tin BĐS' }}
                </button>
                <a href="{{ route('nhanvien.admin.bat-dong-san.index') }}" class="btn-cancel">
                    <i class="fas fa-times"></i> Hủy bỏ
                </a>
            </div>
        </div>

        {{-- ẢNH ĐẠI DIỆN --}}
        <div class="fc">
            <div class="fc-head"><i class="fas fa-image"></i> Ảnh đại diện</div>
            <div class="fc-body">

                {{-- Ảnh hiện tại --}}
                @if ($isEdit && $batDongSan->hinh_anh)
                    <div class="current-img-wrap">
                        <img src="{{ asset('storage/' . $batDongSan->hinh_anh) }}" alt="Ảnh đại diện"
                            id="imgCurrent" class="current-img">
                        <div class="current-img-label">Ảnh hiện tại</div>
                    </div>
                @else
                    <div class="img-placeholder" id="imgPlaceholder"
                        onclick="document.getElementById('inp_hinhanh').click()">
                        <i class="fas fa-camera"></i>
                        <span>Click để chọn ảnh đại diện</span>
                    </div>
                @endif

                {{-- Preview ảnh mới --}}
                <img id="imgPreview" alt="Preview"
                    style="display:none;width:100%;border-radius:10px;border:2px solid #FF8C42;margin-bottom:10px;aspect-ratio:16/9;object-fit:cover">

                {{-- Input ẩn --}}
                <input type="file" id="inp_hinhanh" name="hinh_anh" accept="image/jpeg,image/png,image/webp"
                    style="display:none">

                <label for="inp_hinhanh" class="upload-lbl">
                    <i class="fas fa-upload"></i>
                    {{ $isEdit && $batDongSan->hinh_anh ? 'Đổi ảnh' : 'Chọn ảnh' }}
                </label>
                <div id="imgFname" class="img-fname-hint"></div>

                <div class="upload-hint">
                    Định dạng: JPEG, PNG, WEBP<br>
                    Kích thước tối đa: 3MB<br>
                    Tỉ lệ khuyến nghị: 16:9
                </div>

                @error('hinh_anh')
                    <div class="fe-msg" style="margin-top:6px">
                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                    </div>
                @enderror

            </div>
        </div>

        {{-- CÀI ĐẶT --}}
        <div class="fc">
            <div class="fc-head"><i class="fas fa-sliders-h"></i> Cài đặt & Trạng thái</div>
            <div class="fc-body">

                {{-- Toggle hiển thị --}}
                <div class="tg-row">
                    <div class="tg-info">
                        <span>Hiển thị trên website</span>
                        <small>Khách hàng tìm kiếm được tin này</small>
                    </div>
                    <label class="sw">
                        <input type="checkbox" name="hien_thi" value="1"
                            {{ old('hien_thi', $isEdit ? $batDongSan->hien_thi : true) ? 'checked' : '' }}>
                        <span class="sw-track"><span class="sw-thumb"></span></span>
                    </label>
                </div>

                <div class="fc-divider"></div>

                {{-- Toggle nổi bật --}}
                <div class="tg-row">
                    <div class="tg-info">
                        <span>Tin nổi bật (HOT)</span>
                        <small>Gắn badge HOT, ưu tiên hiển thị đầu</small>
                    </div>
                    <label class="sw">
                        <input type="checkbox" name="noi_bat" value="1"
                            {{ old('noi_bat', $isEdit ? $batDongSan->noi_bat : false) ? 'checked' : '' }}>
                        <span class="sw-track"><span class="sw-thumb"></span></span>
                    </label>
                </div>

                <div class="fc-divider"></div>

                {{-- Trạng thái --}}
                <div class="fg">
                    <label class="fl">Trạng thái giao dịch</label>
                    <select name="trang_thai" class="fi">
                        @foreach ($constants['trang_thai'] as $v => $info)
                            <option value="{{ $v }}" {{ $oldTrangThai == $v ? 'selected' : '' }}>
                                {{ $info['label'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="fc-divider"></div>

                {{-- Thứ tự + Ngày đăng --}}
                <div class="fg-row2">
                    <div class="fg">
                        <label class="fl">Thứ tự <span class="fhint">nhỏ = lên trước</span></label>
                        <input type="number" name="thu_tu_hien_thi" class="fi" min="0" max="9999"
                            value="{{ old('thu_tu_hien_thi', $isEdit ? $batDongSan->thu_tu_hien_thi : 0) }}">
                    </div>
                    <div class="fg">
                        <label class="fl">Ngày đăng tin</label>
                        <input type="date" name="thoi_diem_dang" class="fi" value="{{ $ngayDang }}">
                    </div>
                </div>

            </div>
        </div>

        {{-- THÔNG TIN HỆ THỐNG (chỉ hiện khi edit) --}}
        @if ($isEdit)
            <div class="fc">
                <div class="fc-head"><i class="fas fa-info-circle"></i> Thông tin hệ thống</div>
                <div class="fc-body">
                    <div class="sysinfo">

                        <div class="sysrow">
                            <span class="syskey"><i class="fas fa-barcode"></i> Mã BĐS</span>
                            <span class="sysval sysval-code">{{ $batDongSan->ma_bat_dong_san }}</span>
                        </div>

                        <div class="sysrow">
                            <span class="syskey"><i class="fas fa-link"></i> Slug</span>
                            <span class="sysval sysval-slug">{{ $batDongSan->slug }}</span>
                        </div>

                        <div class="sysrow">
                            <span class="syskey"><i class="fas fa-eye"></i> Lượt xem</span>
                            <span class="sysval">{{ number_format($batDongSan->luot_xem) }}</span>
                        </div>

                        <div class="sysrow">
                            <span class="syskey"><i class="fas fa-calendar-plus"></i> Tạo lúc</span>
                            <span class="sysval">{{ $batDongSan->created_at->format('d/m/Y H:i') }}</span>
                        </div>

                        <div class="sysrow">
                            <span class="syskey"><i class="fas fa-sync"></i> Cập nhật</span>
                            <span class="sysval">{{ $batDongSan->updated_at->format('d/m/Y H:i') }}</span>
                        </div>

                        @if ($batDongSan->nhanVienPhuTrach)
                            <div class="sysrow">
                                <span class="syskey"><i class="fas fa-user-tie"></i> Phụ trách</span>
                                <span class="sysval">{{ $batDongSan->nhanVienPhuTrach->ho_ten }}</span>
                            </div>
                        @endif

                    </div>

                    {{-- Nút xem trang ngoài --}}
                    @if (Route::has('bat-dong-san.show'))
                        <a href="{{ route('bat-dong-san.show', $batDongSan->slug) }}" target="_blank"
                            class="btn-preview-page">
                            <i class="fas fa-external-link-alt"></i> Xem trang hiển thị
                        </a>
                    @else
                        <a href="{{ url('/bat-dong-san/' . $batDongSan->slug) }}" target="_blank"
                            class="btn-preview-page">
                            <i class="fas fa-external-link-alt"></i> Xem trang hiển thị
                        </a>
                    @endif
                </div>
            </div>
        @endif

        {{-- NÚT LƯU dưới cùng --}}
        <div class="fc">
            <div class="fc-body" style="padding:16px">
                <button type="submit" class="btn-save" form="bdsForm">
                    <i class="fas fa-save"></i>
                    {{ $isEdit ? 'Lưu thay đổi' : 'Đăng tin BĐS' }}
                </button>
            </div>
        </div>

    </div>{{-- end form-right --}}

</div>{{-- end form-grid --}}

@push('styles')
    <style>
        /* ─── GRID ─── */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 300px;
            gap: 20px;
            align-items: start;
        }

        @media (max-width: 980px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-right {
                order: -1;
            }
        }

        /* ─── CARD ─── */
        .fc {
            background: #fff;
            border-radius: 14px;
            border: 1px solid #f0f2f5;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .05);
            margin-bottom: 18px;
            overflow: hidden;
        }

        .fc:last-child {
            margin-bottom: 0;
        }

        .fc-head {
            padding: 12px 18px;
            background: linear-gradient(135deg, #f8faff, #eef3ff);
            border-bottom: 1px solid #e8eeff;
            font-weight: 700;
            font-size: .86rem;
            color: #1a3c5e;
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .fc-head i {
            color: #FF8C42;
            font-size: .9rem;
        }

        .fhint {
            font-weight: 400;
            color: #bbb;
            font-size: .72rem;
            margin-left: 4px;
        }

        .fc-body {
            padding: 18px;
        }

        .fc-divider {
            border: none;
            border-top: 1px solid #f5f5f5;
            margin: 14px 0;
        }

        /* ─── FORM GROUPS ─── */
        .fg {
            margin-bottom: 14px;
        }

        .fg:last-child {
            margin-bottom: 0;
        }

        .fg-row2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 14px;
        }

        .fg-row3 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 12px;
            margin-bottom: 14px;
        }

        @media (max-width: 600px) {

            .fg-row2,
            .fg-row3 {
                grid-template-columns: 1fr;
            }
        }

        .fl {
            display: block;
            font-weight: 700;
            font-size: .75rem;
            color: #666;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: .3px;
        }

        .fl.req::after {
            content: ' *';
            color: #e74c3c;
        }

        .fi {
            width: 100%;
            height: 40px;
            border: 1.5px solid #e8e8e8;
            border-radius: 8px;
            padding: 0 12px;
            font-size: .875rem;
            color: #333;
            background: #fafafa;
            outline: none;
            font-family: inherit;
            transition: border-color .2s, background .2s, box-shadow .2s;
            box-sizing: border-box;
        }

        .fi:focus {
            border-color: #FF8C42;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(255, 140, 66, .1);
        }

        .fi.fi-err {
            border-color: #e74c3c;
            background: #fff8f8;
        }

        textarea.fi {
            height: auto;
            padding: 10px 12px;
            resize: vertical;
            line-height: 1.6;
        }

        select.fi {
            appearance: none;
            cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath fill='%23aaa' d='M5 6L0 0h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-color: #fafafa;
            padding-right: 32px;
        }

        .fe-msg {
            font-size: .78rem;
            color: #e74c3c;
            margin-top: 4px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* ─── GIÁ BLOCK ─── */
        .gia-block {
            display: none;
        }

        .gia-block.show {
            display: block;
        }

        .gia-hint {
            display: block;
            font-size: .75rem;
            color: #27ae60;
            margin-top: 4px;
            font-weight: 600;
        }

        /* ─── TOGGLE SWITCH ─── */
        .tg-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 2px 0;
        }

        .tg-info span {
            display: block;
            font-weight: 600;
            font-size: .875rem;
            color: #333;
            margin-bottom: 2px;
        }

        .tg-info small {
            color: #bbb;
            font-size: .77rem;
        }

        .sw {
            position: relative;
            cursor: pointer;
            flex-shrink: 0;
        }

        .sw input {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        .sw-track {
            display: block;
            width: 44px;
            height: 25px;
            background: #dde0e8;
            border-radius: 13px;
            transition: background .25s;
            position: relative;
        }

        .sw input:checked~.sw-track {
            background: #27ae60;
        }

        .sw-thumb {
            position: absolute;
            width: 19px;
            height: 19px;
            background: #fff;
            border-radius: 50%;
            top: 3px;
            left: 3px;
            transition: transform .25s;
            box-shadow: 0 1px 5px rgba(0, 0, 0, .2);
        }

        .sw input:checked~.sw-track .sw-thumb {
            transform: translateX(19px);
        }

        /* ─── NÚT LƯU ─── */
        .btn-save {
            width: 100%;
            height: 48px;
            background: linear-gradient(135deg, #FF8C42, #f5a623);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-weight: 700;
            font-size: .95rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 4px 14px rgba(255, 140, 66, .35);
            transition: all .2s;
            margin-bottom: 10px;
        }

        .btn-save:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(255, 140, 66, .45);
        }

        .btn-cancel {
            width: 100%;
            height: 40px;
            background: #f5f5f5;
            color: #888;
            border: 1.5px solid #e8e8e8;
            border-radius: 10px;
            font-weight: 600;
            font-size: .875rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            text-decoration: none;
            transition: all .2s;
        }

        .btn-cancel:hover {
            background: #ffeee0;
            color: #FF8C42;
            border-color: #FF8C42;
        }

        /* ─── ẢNH ĐẠI DIỆN ─── */
        .current-img-wrap {
            margin-bottom: 10px;
        }

        .current-img {
            width: 100%;
            aspect-ratio: 16/9;
            object-fit: cover;
            border-radius: 10px;
            border: 1px solid #eee;
            display: block;
        }

        .current-img-label {
            font-size: .72rem;
            color: #aaa;
            text-align: center;
            margin-top: 4px;
        }

        .img-placeholder {
            width: 100%;
            aspect-ratio: 16/9;
            background: linear-gradient(135deg, #f8faff, #eef3ff);
            border: 2px dashed #d5e0f5;
            border-radius: 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #c0d0e8;
            gap: 6px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: border-color .2s;
        }

        .img-placeholder:hover {
            border-color: #FF8C42;
            color: #FF8C42;
        }

        .img-placeholder i {
            font-size: 2rem;
        }

        .img-placeholder span {
            font-size: .8rem;
        }

        .upload-lbl {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #f0f4ff;
            color: #1a3c5e;
            border: 1.5px solid #d5e0f5;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 600;
            font-size: .82rem;
            cursor: pointer;
            transition: all .2s;
            margin-bottom: 8px;
        }

        .upload-lbl:hover {
            background: #1a3c5e;
            color: #fff;
            border-color: #1a3c5e;
        }

        .img-fname-hint {
            font-size: .75rem;
            color: #FF8C42;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .upload-hint {
            font-size: .72rem;
            color: #bbb;
            line-height: 1.7;
        }

        /* ─── ALBUM ─── */
        .album-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
            gap: 8px;
            margin-bottom: 12px;
        }

        .alb-item {
            position: relative;
            aspect-ratio: 1;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #eee;
        }

        .alb-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .alb-del-btn {
            position: absolute;
            top: 3px;
            right: 3px;
            width: 20px;
            height: 20px;
            background: rgba(231, 76, 60, .88);
            color: #fff;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .6rem;
            padding: 0;
            line-height: 1;
        }

        .upload-zone {
            border: 2px dashed #d5e0f5;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all .2s;
            background: #fafeff;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
        }

        .upload-zone:hover {
            border-color: #FF8C42;
            background: #fff9f5;
        }

        .upload-zone i {
            font-size: 2rem;
            color: #d5e0f5;
        }

        .upload-zone span {
            font-size: .82rem;
            color: #aaa;
        }

        .upload-zone small {
            font-size: .72rem;
            color: #ccc;
        }

        /* ─── SYSINFO ─── */
        .sysinfo {
            margin-bottom: 14px;
        }

        .sysrow {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 8px;
            padding: 6px 0;
            border-bottom: 1px solid #fafafa;
        }

        .sysrow:last-child {
            border-bottom: none;
        }

        .syskey {
            font-size: .75rem;
            color: #bbb;
            display: flex;
            align-items: center;
            gap: 4px;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .sysval {
            font-size: .78rem;
            font-weight: 600;
            color: #555;
            text-align: right;
        }

        .sysval-code {
            font-family: monospace;
            background: #f0f4ff;
            color: #2d6a9f;
            padding: .1rem .4rem;
            border-radius: 4px;
        }

        .sysval-slug {
            word-break: break-all;
            font-weight: 400;
            color: #8aabcc;
            font-family: monospace;
            font-size: .7rem;
        }

        .btn-preview-page {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            width: 100%;
            padding: 9px;
            background: #f0f4ff;
            color: #1a3c5e;
            border: 1.5px solid #d5e0f5;
            border-radius: 8px;
            font-size: .82rem;
            font-weight: 600;
            text-decoration: none;
            transition: all .2s;
        }

        .btn-preview-page:hover {
            background: #1a3c5e;
            color: #fff;
        }
    </style>
@endpush

@push('scripts')
    {{-- TÍCH HỢP CKEDITOR --}}
    <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
    <script>
        // ══ 1. Kích hoạt CKEditor cho Mô tả ══
        if (document.getElementById('moTaBdsEditor')) {
            CKEDITOR.replace('moTaBdsEditor', {
                height: 350,
                language: 'vi'
            });
        }

        // Đồng bộ CKEditor trước khi Submit
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', function() {
                if (CKEDITOR.instances['moTaBdsEditor']) {
                    CKEDITOR.instances['moTaBdsEditor'].updateElement();
                }
            });
        });

        // ══ 2. Toggle khối giá theo nhu cầu ══
        const selNhuCau = document.getElementById('sel_nhucau');
        const gbBan = document.getElementById('gb_ban');
        const gbThue = document.getElementById('gb_thue');

        function toggleGia() {
            const v = selNhuCau ? selNhuCau.value : '';
            if (gbBan) gbBan.classList.toggle('show', v === 'ban');
            if (gbThue) gbThue.classList.toggle('show', v === 'thue');
        }

        if (selNhuCau) {
            selNhuCau.addEventListener('change', toggleGia);
            toggleGia(); // gọi ngay khi load (cho trang edit)
        }

        // ══ 3. Ẩn/hiện Tòa/Tầng/Mã căn theo loại hình ══
        const selLoai = document.getElementById('sel_loai');
        const rowToaTang = document.getElementById('row_toatang');

        function toggleToaTang() {
            if (!selLoai || !rowToaTang) return;
            const v = selLoai.value;
            const show = ['can_ho', 'nha_pho', 'biet_thu', 'shophouse'].includes(v);
            rowToaTang.style.display = show ? 'grid' : 'none';
        }

        if (selLoai) {
            selLoai.addEventListener('change', toggleToaTang);
            toggleToaTang();
        }

        // ══ 4. Preview ảnh đại diện ══
        const inpHinhAnh = document.getElementById('inp_hinhanh');
        const imgPreview = document.getElementById('imgPreview');
        const imgCurrent = document.getElementById('imgCurrent');
        const imgPlaceholder = document.getElementById('imgPlaceholder');
        const imgFname = document.getElementById('imgFname');

        if (inpHinhAnh) {
            inpHinhAnh.addEventListener('change', function() {
                const file = this.files[0];
                if (!file) return;

                const reader = new FileReader();
                reader.onload = function(e) {
                    imgPreview.src = e.target.result;
                    imgPreview.style.display = 'block';
                    if (imgCurrent) imgCurrent.style.display = 'none';
                    if (imgPlaceholder) imgPlaceholder.style.display = 'none';
                };
                reader.readAsDataURL(file);

                if (imgFname) {
                    imgFname.textContent = file.name + ' (' + (file.size / 1024).toFixed(0) + ' KB)';
                }
            });
        }

        // ══ 5. Preview album ảnh mới ══
        const inpAlbum = document.getElementById('inp_album');
        const albumPreview = document.getElementById('albumPreview');

        if (inpAlbum) {
            inpAlbum.addEventListener('change', function() {
                const existCount = document.querySelectorAll('#albumExist .alb-item').length;
                const previewCount = albumPreview ? albumPreview.querySelectorAll('.alb-item').length : 0;
                const files = Array.from(this.files);

                if (existCount + previewCount + files.length > 20) {
                    alert('Tối đa 20 ảnh trong album!');
                    this.value = '';
                    return;
                }

                files.forEach(function(file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'alb-item';
                        div.innerHTML =
                            '<img src="' + e.target.result + '" alt="">' +
                            '<button type="button" class="alb-del-btn" ' +
                            'onclick="this.closest(\'.alb-item\').remove()" title="Bỏ ảnh">' +
                            '<i class="fas fa-times"></i></button>';
                        if (albumPreview) albumPreview.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                });
            });
        }

        // ══ 6. Xóa ảnh album đã lưu (AJAX) ══
        document.querySelectorAll('.alb-del-btn[data-bds]').forEach(function(btn) {
            btn.addEventListener('click', function() {
                if (!confirm('Xóa ảnh này khỏi album?')) return;

                const bdsId = this.dataset.bds;
                const path = this.dataset.path;
                const key = this.dataset.key;
                const item = document.getElementById('alb_' + key);
                const csrf = document.querySelector('meta[name=csrf-token]').content;

                fetch('/nhan-vien/admin/bat-dong-san/' + bdsId + '/xoa-anh', {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrf,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            path: path
                        })
                    })
                    .then(function(r) {
                        return r.json();
                    })
                    .then(function(data) {
                        if (data.ok && item) {
                            item.style.transition = 'all .3s';
                            item.style.opacity = '0';
                            item.style.transform = 'scale(.7)';
                            setTimeout(function() {
                                item.remove();
                            }, 300);
                        }
                    });
            });
        });

        // ══ 7. Hiển thị giá theo tỷ khi nhập ══
        const inpGia = document.getElementById('inp_gia');
        const giaHint = document.getElementById('gia_hint');

        if (inpGia && giaHint) {
            inpGia.addEventListener('input', function() {
                const val = parseFloat(this.value);
                if (isNaN(val) || val <= 0) {
                    giaHint.textContent = 'Nhập số VNĐ đầy đủ';
                    giaHint.style.color = '#aaa';
                } else if (val >= 1e9) {
                    giaHint.textContent = '= ' + (val / 1e9).toFixed(3) + ' tỷ';
                    giaHint.style.color = '#27ae60';
                } else if (val >= 1e6) {
                    giaHint.textContent = '= ' + (val / 1e6).toFixed(1) + ' triệu';
                    giaHint.style.color = '#2d6a9f';
                } else {
                    giaHint.textContent = '= ' + val.toLocaleString('vi-VN') + ' đ';
                    giaHint.style.color = '#aaa';
                }
            });
        }
    </script>
@endpush
