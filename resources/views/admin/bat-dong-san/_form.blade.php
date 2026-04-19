@php
    $isEdit = isset($batDongSan) && $batDongSan !== null;
    // Nguồn dữ liệu: BDS đang sửa (edit) hoặc BDS được clone (tạo mới từ bản sao)
    $sourceBds = $sourceBds ?? null;
    $dataSource = $isEdit ? $batDongSan : $sourceBds; // null khi tạo mới hoàn toàn

    $prefillNhuCau = $prefillNhuCau ?? null;
    $prefillDuAnId = $prefillDuAnId ?? null;
    $oldLoaiHinh = old('loai_hinh', $dataSource ? $dataSource->loai_hinh : 'can_ho');
    $oldNhuCau = old('nhu_cau', $dataSource ? $dataSource->nhu_cau : $prefillNhuCau ?? '');
    $oldTrangThai = old('trang_thai', $dataSource ? $dataSource->trang_thai : 'con_hang');
    $defaultNhanVienPhuTrachId = $defaultNhanVienPhuTrachId ?? '';
    $huongs = ['Đông', 'Tây', 'Nam', 'Bắc', 'Đông Nam', 'Đông Bắc', 'Tây Nam', 'Tây Bắc'];
    $ngayDang = old(
        'thoi_diem_dang',
        $isEdit && $batDongSan->thoi_diem_dang ? $batDongSan->thoi_diem_dang->format('Y-m-d') : date('Y-m-d'),
    );

    $albumAnhHienTai = [];
    if ($isEdit) {
        $rawAlbum = $batDongSan->album_anh ?? [];

        if (is_array($rawAlbum)) {
            $albumAnhHienTai = $rawAlbum;
        } elseif (is_string($rawAlbum) && $rawAlbum !== '') {
            $decoded = json_decode($rawAlbum, true);
            if (is_array($decoded)) {
                $albumAnhHienTai = $decoded;
            } elseif (is_string($decoded) && $decoded !== '') {
                $decodedNested = json_decode($decoded, true);
                $albumAnhHienTai = is_array($decodedNested) ? $decodedNested : [];
            }
        }
    }
@endphp

<div class="row g-4">
    {{-- ════ CỘT TRÁI (Nội dung chính) ════ --}}
    <div class="col-12 col-lg-8">

        {{-- THÔNG TIN CƠ BẢN --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3"><i class="fas fa-home text-primary me-2"></i>Thông tin cơ bản</div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Tiêu đề tin đăng <span class="text-danger">*</span></label>
                    <input type="text" name="tieu_de" class="form-control @error('tieu_de') is-invalid @enderror"
                        value="{{ old('tieu_de', $isEdit ? $batDongSan->tieu_de : $dataSource?->tieu_de ?? '') }}"
                        placeholder="VD: Bán căn hộ 2PN Vinhomes Smart City..." autofocus>
                    @error('tieu_de')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Loại hình BĐS <span class="text-danger">*</span></label>
                        <select name="loai_hinh" id="sel_loai"
                            class="form-select @error('loai_hinh') is-invalid @enderror">
                            <option value="">-- Chọn loại --</option>
                            @foreach ($constants['loai_hinh'] as $v => $l)
                                <option value="{{ $v }}" @selected($oldLoaiHinh == $v)>{{ $l }}
                                </option>
                            @endforeach
                        </select>
                        @error('loai_hinh')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nhu cầu <span class="text-danger">*</span></label>
                        <select name="nhu_cau" id="sel_nhucau"
                            class="form-select @error('nhu_cau') is-invalid @enderror">
                            <option value="">-- Chọn --</option>
                            @foreach ($constants['nhu_cau'] as $v => $l)
                                <option value="{{ $v }}" @selected($oldNhuCau == $v)>{{ $l }}
                                </option>
                            @endforeach
                        </select>
                        @error('nhu_cau')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row g-3 mb-0">
                    <div class="col-md-4">
                        <label class="form-label">Thuộc dự án <span class="text-muted fw-normal">(Tùy
                                chọn)</span></label>
                        <select name="du_an_id" id="sel_du_an" class="form-select">
                            <option value="">-- Không thuộc dự án --</option>
                            @foreach ($duAns as $da)
                                <option value="{{ $da->id }}" @selected(old('du_an_id', $dataSource?->du_an_id ?? ($prefillDuAnId ?? '')) == $da->id)>{{ $da->ten_du_an }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Chủ sở hữu <span class="text-muted fw-normal">(Nguồn
                                hàng)</span></label>
                        <select name="chu_nha_id" id="sel_chu_nha" class="form-select border-success border-opacity-50">
                            <option value="">-- Chưa gán chủ nhà --</option>
                            @foreach ($chuNhas as $cn)
                                <option value="{{ $cn->id }}" @selected(old('chu_nha_id', $dataSource?->chu_nha_id ?? '') == $cn->id)>{{ $cn->ho_ten }} -
                                    {{ $cn->so_dien_thoai }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">NV phụ trách <span class="text-muted fw-normal">(Tùy
                                chọn)</span></label>
                        <select name="nhan_vien_phu_trach_id" class="form-select">
                            <option value="">-- Chưa phân công --</option>
                            @foreach ($nhanViens as $nv)
                                <option value="{{ $nv->id }}" @selected(old('nhan_vien_phu_trach_id', $isEdit ? $batDongSan->nhan_vien_phu_trach_id : $defaultNhanVienPhuTrachId) == $nv->id)>{{ $nv->ho_ten }}
                                </option>
                            @endforeach
                        </select>

                    </div>
                </div>
            </div>
        </div>

        {{-- VỊ TRÍ & CHI TIẾT --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3"><i class="fas fa-map-pin text-success me-2"></i>Vị trí & Chi tiết căn
            </div>
            <div class="card-body">
                <div class="row g-3 mb-3" id="row_toatang">
                    <div class="col-md-4"><label class="form-label">Tòa nhà</label><input type="text" name="toa"
                            class="form-control" value="{{ old('toa', $dataSource?->toa ?? '') }}"
                            placeholder="VD: S2.05"></div>
                    <div class="col-md-4"><label class="form-label">Tầng</label><input type="text" name="tang"
                            class="form-control" value="{{ old('tang', $dataSource?->tang ?? '') }}"
                            placeholder="VD: 18"></div>
                    <div class="col-md-4"><label class="form-label">Mã căn</label><input type="text" name="ma_can"
                            class="form-control" value="{{ old('ma_can', $dataSource?->ma_can ?? '') }}"
                            placeholder="VD: 1806"></div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Diện tích (m²) <span class="text-danger">*</span></label>
                        <input type="number" name="dien_tich" step="0.1" min="1"
                            class="form-control @error('dien_tich') is-invalid @enderror"
                            value="{{ old('dien_tich', $dataSource?->dien_tich ?? '') }}">
                        @error('dien_tich')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Số phòng ngủ</label>
                        <input type="text" name="so_phong_ngu" class="form-control"
                            value="{{ old('so_phong_ngu', $dataSource?->so_phong_ngu ?? '') }}"
                            placeholder="VD: 2, 3, Studio, 2+1, Penthouse, v.v...">
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Hướng cửa chính</label>
                        <select name="huong_cua" class="form-select">
                            <option value="">-- Không xác định --</option>
                            @foreach ($huongs as $h)
                                <option value="{{ $h }}" @selected(old('huong_cua', $dataSource?->huong_cua ?? '') == $h)>{{ $h }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Hướng ban công</label>
                        <select name="huong_ban_cong" class="form-select">
                            <option value="">-- Không xác định --</option>
                            @foreach ($huongs as $h)
                                <option value="{{ $h }}" @selected(old('huong_ban_cong', $dataSource?->huong_ban_cong ?? '') == $h)>{{ $h }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row g-3 mb-0">
                    <div class="col-md-6">
                        <label class="form-label">Nội thất</label>
                        <select name="noi_that" class="form-select">
                            <option value="">-- Không xác định --</option>
                            @foreach ($constants['noi_that'] as $v => $l)
                                <option value="{{ $v }}" @selected(old('noi_that', $dataSource?->noi_that ?? '') == $v)>{{ $l }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Pháp lý</label>
                        <select name="phap_ly" class="form-select">
                            <option value="">-- Không xác định --</option>
                            <option value="so_hong" @selected(old('phap_ly', $dataSource?->phap_ly ?? '') == 'so_hong')>Sổ hồng</option>
                            <option value="hop_dong_mua_ban" @selected(old('phap_ly', $dataSource?->phap_ly ?? '') == 'hop_dong_mua_ban')>Hợp đồng mua bán</option>
                            <option value="hop_dong_50_nam" @selected(old('phap_ly', $dataSource?->phap_ly ?? '') == 'hop_dong_50_nam')>Hợp đồng mua bán 50 năm
                            </option>
                            <option value="so_50_nam" @selected(old('phap_ly', $dataSource?->phap_ly ?? '') == 'so_50_nam')>Sổ 50 năm</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        {{-- GIÁ BÁN (JS sẽ ẩn hiện) --}}
        <div class="card border-0 shadow-sm border-top border-warning border-3 mb-4 d-none" id="gb_ban">
            <div class="card-body">
                <h6 class="fw-bold text-warning mb-3"><i class="fas fa-tag"></i> Thông tin giá bán</h6>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Giá bán (VNĐ) <span class="text-danger">*</span></label>
                        <input type="number" name="gia" id="inp_gia" step="100000" min="0"
                            class="form-control @error('gia') is-invalid @enderror"
                            value="{{ old('gia', $dataSource?->gia ?? '') }}">
                        <div class="form-text text-success fw-bold" id="gia_hint"></div>
                        @error('gia')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Phí môi giới (VNĐ)</label>
                        <input type="number" name="phi_moi_gioi" class="form-control"
                            value="{{ old('phi_moi_gioi', $dataSource?->phi_moi_gioi ?? '') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Phí sang tên (VNĐ)</label>
                        <input type="number" name="phi_sang_ten" class="form-control"
                            value="{{ old('phi_sang_ten', $dataSource?->phi_sang_ten ?? '') }}">
                    </div>
                </div>
            </div>
        </div>

        {{-- GIÁ THUÊ (JS sẽ ẩn hiện) --}}
        <div class="card border-0 shadow-sm border-top border-info border-3 mb-4 d-none" id="gb_thue">
            <div class="card-body">
                <h6 class="fw-bold text-info mb-3"><i class="fas fa-key"></i> Thông tin cho thuê</h6>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Giá thuê / tháng (VNĐ) <span class="text-danger">*</span></label>
                        <input type="number" name="gia_thue" id="inp_gia_thue"
                            class="form-control @error('gia_thue') is-invalid @enderror"
                            value="{{ old('gia_thue', $dataSource?->gia_thue ?? '') }}">
                        <div class="form-text text-info fw-bold" id="gia_thue_hint"></div>
                        @error('gia_thue')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Thời gian vào thuê</label>
                        <select name="thoi_gian_vao_thue" class="form-select">
                            <option value="">-- Chọn --</option>
                            @foreach ($constants['thoi_gian_vao_thue'] as $v => $l)
                                <option value="{{ $v }}" @selected(old('thoi_gian_vao_thue', $dataSource?->thoi_gian_vao_thue ?? '') == $v)>{{ $l }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Hình thức thanh toán</label>
                        <select name="hinh_thuc_thanh_toan" class="form-select">
                            <option value="">-- Chọn --</option>
                            @foreach ($constants['hinh_thuc_tt'] as $v => $l)
                                <option value="{{ $v }}" @selected(old('hinh_thuc_thanh_toan', $dataSource?->hinh_thuc_thanh_toan ?? '') == $v)>{{ $l }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        {{-- MÔ TẢ --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3"><i class="fas fa-align-left text-navy me-2"></i>Mô tả chi tiết
            </div>
            <div class="card-body">
                <textarea name="mo_ta" id="moTaBdsEditor">{{ old('mo_ta', $dataSource?->mo_ta ?? '') }}</textarea>
            </div>
        </div>

        {{-- GHI CHÚ NỘI BỘ --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3"><i class="fas fa-sticky-note text-warning me-2"></i>Ghi chú nội bộ
            </div>
            <div class="card-body">
                <textarea name="ghi_chu_noi_bo" class="form-control @error('ghi_chu_noi_bo') is-invalid @enderror" rows="4"
                    placeholder="Ghi chú chỉ nội bộ nhìn thấy: lịch sử trao đổi, lưu ý đặc biệt, mức giá chốt nhanh...">{{ old('ghi_chu_noi_bo', $isEdit ? $batDongSan->ghi_chu_noi_bo : '') }}</textarea>
                <div class="form-text">Phần này không hiển thị ra website khách hàng.</div>
                @error('ghi_chu_noi_bo')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- ALBUM ẢNH --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <i class="fas fa-images text-warning me-2"></i>Album ảnh
                <span class="text-muted fw-normal" style="font-size: 0.8rem">(Tối đa 20 ảnh, mỗi ảnh ≤ 3MB)</span>
            </div>
            <div class="card-body">
                {{-- Ảnh cũ --}}
                @if ($isEdit && !empty($albumAnhHienTai))
                    <div class="d-flex flex-wrap gap-2 mb-3" id="albumExist">
                        @foreach ($albumAnhHienTai as $imgPath)
                            @php $imgKey = substr(md5($imgPath), 0, 12); @endphp
                            <div class="position-relative border rounded" id="alb_{{ $imgKey }}"
                                style="width: 100px; height: 100px;">
                                <img src="{{ asset('storage/' . $imgPath) }}"
                                    class="w-100 h-100 object-fit-cover rounded">
                                <button type="button"
                                    class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 alb-del-btn p-0 d-flex align-items-center justify-content-center"
                                    style="width: 20px; height: 20px;" data-bds="{{ $batDongSan->id }}"
                                    data-path="{{ $imgPath }}" data-key="{{ $imgKey }}"><i
                                        class="fas fa-times" style="font-size: 0.6rem"></i></button>
                            </div>
                        @endforeach
                    </div>
                @endif
                {{-- Preview ảnh mới --}}
                <div class="d-flex flex-wrap gap-2 mb-3" id="albumPreview"></div>

                {{-- Nút Upload --}}
                <label for="inp_album"
                    class="d-flex flex-column align-items-center justify-content-center border border-dashed rounded bg-light cursor-pointer"
                    style="height: 120px; transition: 0.2s">
                    <i class="fas fa-cloud-upload-alt fs-2 text-muted mb-2"></i>
                    <span class="text-secondary" style="font-size: 0.85rem">Click để chọn nhiều ảnh</span>
                    <input type="file" id="inp_album" name="album_anh[]" multiple
                        accept="image/jpeg,image/png,image/webp" class="d-none">
                </label>
                @error('album_anh.*')
                    <div class="text-danger mt-2" style="font-size: 0.8rem"><i class="fas fa-exclamation-circle"></i>
                        {{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- ALBUM VIDEO --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <i class="fas fa-film text-danger me-2"></i>Album video
                <span class="text-muted fw-normal" style="font-size: 0.8rem">(MP4, WebM, MOV — tối đa
                    100MB/video)</span>
            </div>
            <div class="card-body">
                {{-- Video cũ (chỉ hiện khi edit) --}}
                @if ($isEdit && !empty($batDongSan->album_video))
                    <div class="d-flex flex-wrap gap-3 mb-3" id="videoExist">
                        @foreach ($batDongSan->album_video as $vidPath)
                            @php $vidKey = substr(md5($vidPath), 0, 12); @endphp
                            <div class="position-relative border rounded" id="vid_{{ $vidKey }}"
                                style="width: 160px;">
                                <video src="{{ asset('storage/' . $vidPath) }}" class="w-100 rounded"
                                    style="height: 100px; object-fit: cover;" muted preload="metadata"></video>
                                <div class="text-muted text-truncate px-1" style="font-size: 0.7rem">
                                    {{ basename($vidPath) }}
                                </div>
                                <button type="button"
                                    class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 vid-del-btn p-0 d-flex align-items-center justify-content-center"
                                    style="width: 20px; height: 20px;" data-bds="{{ $batDongSan->id }}"
                                    data-path="{{ $vidPath }}" data-key="{{ $vidKey }}">
                                    <i class="fas fa-times" style="font-size: 0.6rem"></i>
                                </button>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Preview video mới --}}
                <div class="d-flex flex-wrap gap-3 mb-3" id="videoPreview"></div>

                {{-- Nút Upload --}}
                <label for="inp_video"
                    class="d-flex flex-column align-items-center justify-content-center border border-dashed rounded bg-light cursor-pointer"
                    style="height: 120px; transition: 0.2s; border-color: #f87171 !important;">
                    <i class="fas fa-video fs-2 text-danger opacity-50 mb-2"></i>
                    <span class="text-secondary" style="font-size: 0.85rem">Click để chọn video (MP4, WebM,
                        MOV)</span>
                    <input type="file" id="inp_video" name="album_video[]" multiple
                        accept="video/mp4,video/webm,video/quicktime,video/x-msvideo" class="d-none">
                </label>
                @error('album_video.*')
                    <div class="text-danger mt-2" style="font-size: 0.8rem">
                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                    </div>
                @enderror
            </div>
        </div>


        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3"><i class="fas fa-search text-secondary me-2"></i>Tối ưu SEO (Tùy
                chọn)</div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">SEO Title</label>
                    <input type="text" name="seo_title" class="form-control"
                        value="{{ old('seo_title', $isEdit ? $batDongSan->seo_title : '') }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">SEO Description</label>
                    <textarea name="seo_description" class="form-control" rows="2">{{ old('seo_description', $isEdit ? $batDongSan->seo_description : '') }}</textarea>
                </div>
                <div class="mb-0">
                    <label class="form-label">Từ khóa SEO</label>
                    <input type="text" name="seo_keywords" class="form-control"
                        value="{{ old('seo_keywords', $isEdit ? $batDongSan->seo_keywords : '') }}"
                        placeholder="Phân cách bằng dấu phẩy">
                </div>
            </div>
        </div>
    </div>

    {{-- ════ CỘT PHẢI (Cài đặt) ════ --}}
    <div class="col-12 col-lg-4">

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-3">
                <button type="submit" class="btn btn-primary w-100 mb-2 py-2">
                    <i class="fas fa-save me-1"></i> {{ $isEdit ? 'Lưu thay đổi' : 'Đăng tin BĐS' }}
                </button>
                <a href="{{ route('nhanvien.admin.bat-dong-san.index') }}"
                    class="btn btn-light border w-100 py-2">Hủy bỏ</a>
            </div>
        </div>

        {{-- ẢNH ĐẠI DIỆN --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3"><i class="fas fa-image text-info me-2"></i>Ảnh đại diện</div>
            <div class="card-body text-center">
                @if ($isEdit && $batDongSan->hinh_anh)
                    <div id="currentImageWrapper" class="mb-3">
                        <img src="{{ asset('storage/' . $batDongSan->hinh_anh) }}"
                            class="img-fluid rounded border mb-2"
                            style="max-height: 200px; width: 100%; object-fit: cover;" alt="Ảnh đại diện">
                        <span class="badge bg-success bg-opacity-10 text-success"><i
                                class="fas fa-check-circle me-1"></i>Đã có ảnh</span>
                    </div>
                @else
                    <div id="imgPlaceholder"
                        class="p-4 mb-3 rounded bg-light border border-dashed text-muted cursor-pointer"
                        onclick="document.getElementById('inp_hinhanh').click()">
                        <i class="fas fa-camera fs-2 mb-2"></i>
                        <div style="font-size: 0.85rem">Click chọn ảnh đại diện</div>
                    </div>
                @endif

                <img src="" id="imgPreviewNew" class="img-fluid rounded border mb-2 d-none"
                    style="max-height: 200px; width: 100%; object-fit: cover;">
                <div id="imgFilename" class="text-primary fw-bold mb-3" style="font-size: 0.8rem"></div>

                <div class="position-relative">
                    <input type="file" name="hinh_anh" id="inp_hinhanh" accept="image/jpeg,image/png,image/webp"
                        class="d-none">
                    <label for="inp_hinhanh" class="btn btn-light border w-100"><i class="fas fa-upload me-1"></i>
                        {{ $isEdit && $batDongSan->hinh_anh ? 'Đổi ảnh khác' : 'Tải ảnh lên' }}</label>
                </div>
                @error('hinh_anh')
                    <div class="text-danger mt-2" style="font-size: 0.8rem"><i class="fas fa-exclamation-circle"></i>
                        {{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- CÀI ĐẶT & TRẠNG THÁI --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3"><i class="fas fa-sliders-h text-secondary me-2"></i>Cài đặt & Trạng
                thái</div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <div class="fw-bold" style="font-size: 0.85rem">Hiển thị website</div>
                        <div class="text-muted" style="font-size: 0.75rem">Khách hàng có thể xem</div>
                    </div>
                    <label class="toggle-sw"><input type="checkbox" name="hien_thi" value="1"
                            @checked(old('hien_thi', $isEdit ? $batDongSan->hien_thi : true))><span class="toggle-sw-track"><span
                                class="toggle-sw-thumb"></span></span></label>
                </div>
                <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
                    <div>
                        <div class="fw-bold" style="font-size: 0.85rem">Tin nổi bật (HOT)</div>
                        <div class="text-muted" style="font-size: 0.75rem">Ưu tiên hiển thị trên cùng</div>
                    </div>
                    <label class="toggle-sw"><input type="checkbox" name="noi_bat" value="1"
                            @checked(old('noi_bat', $isEdit ? $batDongSan->noi_bat : false))><span class="toggle-sw-track"><span
                                class="toggle-sw-thumb"></span></span></label>
                </div>

                <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
                    <div>
                        <div class="fw-bold" style="font-size: 0.85rem">Gửi mail cảnh báo giá</div>
                        <div class="text-muted" style="font-size: 0.75rem">Tắt để không gửi email giảm giá cho BĐS này
                        </div>
                    </div>
                    <label class="toggle-sw"><input type="checkbox" name="gui_mail_canh_bao_gia" value="1"
                            @checked(old('gui_mail_canh_bao_gia', $isEdit ? $batDongSan->gui_mail_canh_bao_gia ?? true : true))><span class="toggle-sw-track"><span
                                class="toggle-sw-thumb"></span></span></label>
                </div>

                <div class="mb-3">
                    <label class="form-label">Trạng thái giao dịch</label>
                    <select name="trang_thai" class="form-select">
                        @foreach ($constants['trang_thai'] as $v => $info)
                            <option value="{{ $v }}" @selected($oldTrangThai == $v)>{{ $info['label'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Thứ tự ưu tiên <span class="text-muted fw-normal">(Nhỏ lên
                            trước)</span></label>
                    <input type="number" name="thu_tu_hien_thi" class="form-control" min="0"
                        value="{{ old('thu_tu_hien_thi', $isEdit ? $batDongSan->thu_tu_hien_thi : 0) }}">
                </div>

                <div class="mb-0">
                    <label class="form-label">Ngày đăng tin</label>
                    <input type="date" name="thoi_diem_dang" class="form-control" value="{{ $ngayDang }}">
                </div>
            </div>
        </div>

        @if ($isEdit)
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body" style="font-size: 0.8rem">
                    <div class="d-flex justify-content-between border-bottom pb-2 mb-2"><span>Mã BĐS</span> <span
                            class="fw-bold text-primary">#{{ $batDongSan->ma_bat_dong_san }}</span></div>
                    <div class="d-flex justify-content-between border-bottom pb-2 mb-2"><span>Lượt xem</span> <span
                            class="fw-bold">{{ number_format($batDongSan->luot_xem) }}</span></div>
                    <div class="d-flex justify-content-between"><span>Cập nhật</span> <span
                            class="text-muted">{{ $batDongSan->updated_at->format('d/m/Y H:i') }}</span></div>
                </div>
            </div>
        @endif
    </div>
</div>

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
    <style>
        /* ── BDS ERROR BANNER ── */
        .bds-error-banner {
            animation: slideDown 0.35s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-12px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .bds-error-banner-inner {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            background: linear-gradient(135deg, #fff1f0 0%, #fff8f7 100%);
            border: 1.5px solid #fca5a5;
            border-left: 5px solid #ef4444;
            border-radius: 10px;
            padding: 16px 18px;
            box-shadow: 0 4px 16px rgba(239, 68, 68, .10);
        }

        .bds-error-icon {
            flex-shrink: 0;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #fee2e2;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #dc2626;
            font-size: 1rem;
        }

        .bds-error-content {
            flex: 1;
        }

        .bds-error-title {
            font-weight: 700;
            color: #b91c1c;
            font-size: .92rem;
            margin-bottom: 6px;
        }

        .bds-error-list {
            margin: 0;
            padding-left: 0;
            list-style: none;
            font-size: .84rem;
            color: #7f1d1d;
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        .bds-error-list li {
            display: flex;
            align-items: baseline;
            gap: 4px;
        }

        .bds-error-list .fa-dot-circle {
            color: #ef4444;
            font-size: .65rem;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .bds-error-close {
            flex-shrink: 0;
            background: none;
            border: none;
            padding: 4px 6px;
            color: #ef4444;
            opacity: .7;
            cursor: pointer;
            border-radius: 6px;
            line-height: 1;
            transition: opacity .2s, background .2s;
        }

        .bds-error-close:hover {
            opacity: 1;
            background: #fee2e2;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const CSRF = document.querySelector('meta[name=csrf-token]').content;

            // 1. CKEditor
            if (document.getElementById('moTaBdsEditor')) {
                CKEDITOR.replace('moTaBdsEditor', {
                    height: 350,
                    language: 'vi'
                });
            }
            document.getElementById('bdsForm').addEventListener('submit', () => {
                if (CKEDITOR.instances['moTaBdsEditor']) CKEDITOR.instances['moTaBdsEditor']
                    .updateElement();
            });

            // 2. Logic Nhu cầu -> Hiện giá
            const selNhuCau = document.getElementById('sel_nhucau');
            const gbBan = document.getElementById('gb_ban');
            const gbThue = document.getElementById('gb_thue');

            function toggleGia() {
                if (selNhuCau.value === 'ban') {
                    gbBan.classList.remove('d-none');
                    gbThue.classList.add('d-none');
                } else if (selNhuCau.value === 'thue') {
                    gbThue.classList.remove('d-none');
                    gbBan.classList.add('d-none');
                } else {
                    gbBan.classList.add('d-none');
                    gbThue.classList.add('d-none');
                }
            }
            if (selNhuCau) {
                selNhuCau.addEventListener('change', toggleGia);
                toggleGia();
            }

            // 3. Logic Loại hình -> Hiện Tòa/Tầng
            const selLoai = document.getElementById('sel_loai');
            const rowToaTang = document.getElementById('row_toatang');

            function toggleToaTang() {
                if (['can_ho', 'nha_pho', 'biet_thu', 'shophouse'].includes(selLoai.value)) rowToaTang.classList
                    .remove('d-none');
                else rowToaTang.classList.add('d-none');
            }
            if (selLoai) {
                selLoai.addEventListener('change', toggleToaTang);
                toggleToaTang();
            }

            // 4. Preview Ảnh Đại Diện
            const inpHinhAnh = document.getElementById('inp_hinhanh');
            if (inpHinhAnh) {
                inpHinhAnh.addEventListener('change', function() {
                    const file = this.files[0];
                    if (!file) return;
                    const reader = new FileReader();
                    reader.onload = e => {
                        document.getElementById('imgPreviewNew').src = e.target.result;
                        document.getElementById('imgPreviewNew').classList.remove('d-none');
                        const curr = document.getElementById('currentImageWrapper');
                        if (curr) curr.classList.add('d-none');
                        const hold = document.getElementById('imgPlaceholder');
                        if (hold) hold.classList.add('d-none');
                    };
                    reader.readAsDataURL(file);
                    document.getElementById('imgFilename').textContent = '📎 ' + file.name;
                });
            }

            // 5. Preview & Xóa Ảnh Album
            const inpAlbum = document.getElementById('inp_album');
            if (inpAlbum) {
                inpAlbum.addEventListener('change', function() {
                    const preview = document.getElementById('albumPreview');
                    Array.from(this.files).forEach(file => {
                        const reader = new FileReader();
                        reader.onload = e => {
                            const div = document.createElement('div');
                            div.className = 'position-relative border rounded';
                            div.style = 'width: 100px; height: 100px;';
                            div.innerHTML =
                                `<img src="${e.target.result}" class="w-100 h-100 object-fit-cover rounded">
                                         <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 p-0 d-flex align-items-center justify-content-center" style="width: 20px; height: 20px;" onclick="this.parentElement.remove()"><i class="fas fa-times" style="font-size: 0.6rem"></i></button>`;
                            preview.appendChild(div);
                        };
                        reader.readAsDataURL(file);
                    });
                });
            }

            document.querySelectorAll('.alb-del-btn[data-bds]').forEach(btn => {
                btn.addEventListener('click', function() {
                    if (!confirm('Xóa ảnh này khỏi hệ thống?')) return;
                    const path = this.dataset.path,
                        bdsId = this.dataset.bds,
                        key = this.dataset.key;
                    fetch(`/nhan-vien/admin/bat-dong-san/${bdsId}/xoa-anh`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': CSRF,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            path: path
                        })
                    }).then(r => r.json()).then(data => {
                        if (data.ok) document.getElementById('alb_' + key).remove();
                    });
                });
            });

            // 5b. Preview & Xóa Video Album
            const inpVideo = document.getElementById('inp_video');
            if (inpVideo) {
                inpVideo.addEventListener('change', function() {
                    const preview = document.getElementById('videoPreview');
                    Array.from(this.files).forEach(file => {
                        const url = URL.createObjectURL(file);
                        const div = document.createElement('div');
                        div.className = 'position-relative border rounded';
                        div.style = 'width: 160px;';
                        div.innerHTML = `
                            <video src="${url}" class="w-100 rounded" style="height:100px;object-fit:cover;" muted preload="metadata"></video>
                            <div class="text-muted text-truncate px-1" style="font-size:0.7rem">${file.name}</div>
                            <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 p-0 d-flex align-items-center justify-content-center"
                                style="width:20px;height:20px;" onclick="this.parentElement.remove()">
                                <i class="fas fa-times" style="font-size:0.6rem"></i>
                            </button>`;
                        preview.appendChild(div);
                    });
                });
            }

            document.querySelectorAll('.vid-del-btn[data-bds]').forEach(btn => {
                btn.addEventListener('click', function() {
                    if (!confirm('Xóa video này khỏi hệ thống?')) return;
                    const path = this.dataset.path,
                        bdsId = this.dataset.bds,
                        key = this.dataset.key;
                    fetch(`/nhan-vien/admin/bat-dong-san/${bdsId}/xoa-video`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': CSRF,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            path
                        })
                    }).then(r => r.json()).then(data => {
                        if (data.ok) document.getElementById('vid_' + key).remove();
                    });
                });
            });


            // 6. Format giá VNĐ khi gõ
            const inpGia = document.getElementById('inp_gia');
            const giaHint = document.getElementById('gia_hint');
            const inpGiaThue = document.getElementById('inp_gia_thue');
            const giaThueHint = document.getElementById('gia_thue_hint');

            function formatCompactNumber(value) {
                return parseFloat(Number(value).toFixed(3)).toString();
            }

            function formatVndHint(value) {
                const val = Number(value);
                if (!Number.isFinite(val) || val <= 0) return '';
                if (val >= 1e9) return `= ${formatCompactNumber(val / 1e9)} tỷ VNĐ`;
                if (val >= 1e6) return `= ${formatCompactNumber(val / 1e6)} triệu VNĐ`;
                if (val >= 1e3) return `= ${formatCompactNumber(val / 1e3)} nghìn VNĐ`;
                return `= ${val.toLocaleString('vi-VN')} VNĐ`;
            }

            function bindHint(inputEl, hintEl) {
                if (!inputEl || !hintEl) return;
                const updateHint = () => {
                    hintEl.textContent = formatVndHint(inputEl.value);
                };
                inputEl.addEventListener('input', updateHint);
                updateHint();
            }

            bindHint(inpGia, giaHint);
            bindHint(inpGiaThue, giaThueHint);

            // 7. Khởi tạo TomSelect cho dự án và chủ nhà
            new TomSelect('#sel_du_an', {
                create: false,
                sortField: {
                    field: "text",
                    direction: "asc"
                }
            });

            new TomSelect('#sel_chu_nha', {
                create: false,
                sortField: {
                    field: "text",
                    direction: "asc"
                }
            });
        });
    </script>
@endpush
