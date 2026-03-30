@php
    $isEdit = isset($batDongSan) && $batDongSan !== null;
    $oldLoaiHinh = old('loai_hinh', $isEdit ? $batDongSan->loai_hinh : '');
    $oldNhuCau = old('nhu_cau', $isEdit ? $batDongSan->nhu_cau : '');
    $oldTrangThai = old('trang_thai', $isEdit ? $batDongSan->trang_thai : 'con_hang');
    $huongs = ['Đông', 'Tây', 'Nam', 'Bắc', 'Đông Nam', 'Đông Bắc', 'Tây Nam', 'Tây Bắc'];
    $ngayDang = old(
        'thoi_diem_dang',
        $isEdit && $batDongSan->thoi_diem_dang ? $batDongSan->thoi_diem_dang->format('Y-m-d') : date('Y-m-d'),
    );
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
                        value="{{ old('tieu_de', $isEdit ? $batDongSan->tieu_de : '') }}"
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
                    <div class="col-md-6">
                        <label class="form-label">Thuộc dự án <span class="text-muted fw-normal">(Tùy
                                chọn)</span></label>
                        <select name="du_an_id" class="form-select">
                            <option value="">-- Không thuộc dự án --</option>
                            @foreach ($duAns as $da)
                                <option value="{{ $da->id }}" @selected(old('du_an_id', $isEdit ? $batDongSan->du_an_id : '') == $da->id)>{{ $da->ten_du_an }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">NV phụ trách <span class="text-muted fw-normal">(Tùy
                                chọn)</span></label>
                        <select name="nhan_vien_phu_trach_id" class="form-select">
                            <option value="">-- Chưa phân công --</option>
                            @foreach ($nhanViens as $nv)
                                <option value="{{ $nv->id }}" @selected(old('nhan_vien_phu_trach_id', $isEdit ? $batDongSan->nhan_vien_phu_trach_id : '') == $nv->id)>{{ $nv->ho_ten }}
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
                            class="form-control" value="{{ old('toa', $isEdit ? $batDongSan->toa : '') }}"
                            placeholder="VD: S2.05"></div>
                    <div class="col-md-4"><label class="form-label">Tầng</label><input type="text" name="tang"
                            class="form-control" value="{{ old('tang', $isEdit ? $batDongSan->tang : '') }}"
                            placeholder="VD: 18"></div>
                    <div class="col-md-4"><label class="form-label">Mã căn</label><input type="text" name="ma_can"
                            class="form-control" value="{{ old('ma_can', $isEdit ? $batDongSan->ma_can : '') }}"
                            placeholder="VD: 1806"></div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Diện tích (m²) <span class="text-danger">*</span></label>
                        <input type="number" name="dien_tich" step="0.1" min="1"
                            class="form-control @error('dien_tich') is-invalid @enderror"
                            value="{{ old('dien_tich', $isEdit ? $batDongSan->dien_tich : '') }}">
                        @error('dien_tich')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Số phòng ngủ</label>
                        <select name="so_phong_ngu" class="form-select">
                            @php $curPN = old('so_phong_ngu', $isEdit ? $batDongSan->so_phong_ngu : 0); @endphp
                            <option value="0" @selected($curPN == 0)>Studio</option>
                            @for ($i = 1; $i <= 6; $i++)
                                <option value="{{ $i }}" @selected($curPN == $i)>{{ $i }}
                                    phòng ngủ</option>
                            @endfor
                            <option value="7" @selected($curPN >= 7)>7+ phòng ngủ</option>
                        </select>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Hướng cửa chính</label>
                        <select name="huong_cua" class="form-select">
                            <option value="">-- Không xác định --</option>
                            @foreach ($huongs as $h)
                                <option value="{{ $h }}" @selected(old('huong_cua', $isEdit ? $batDongSan->huong_cua : '') == $h)>{{ $h }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Hướng ban công</label>
                        <select name="huong_ban_cong" class="form-select">
                            <option value="">-- Không xác định --</option>
                            @foreach ($huongs as $h)
                                <option value="{{ $h }}" @selected(old('huong_ban_cong', $isEdit ? $batDongSan->huong_ban_cong : '') == $h)>{{ $h }}
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
                                <option value="{{ $v }}" @selected(old('noi_that', $isEdit ? $batDongSan->noi_that : '') == $v)>{{ $l }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Pháp lý</label>
                        <select name="phap_ly" class="form-select">
                            <option value="">-- Không xác định --</option>
                            @foreach ($constants['phap_ly'] as $v => $l)
                                <option value="{{ $v }}" @selected(old('phap_ly', $isEdit ? $batDongSan->phap_ly : '') == $v)>{{ $l }}
                                </option>
                            @endforeach
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
                            value="{{ old('gia', $isEdit ? $batDongSan->gia : '') }}">
                        <div class="form-text text-success fw-bold" id="gia_hint"></div>
                        @error('gia')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Phí môi giới (VNĐ)</label>
                        <input type="number" name="phi_moi_gioi" class="form-control"
                            value="{{ old('phi_moi_gioi', $isEdit ? $batDongSan->phi_moi_gioi : '') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Phí sang tên (VNĐ)</label>
                        <input type="number" name="phi_sang_ten" class="form-control"
                            value="{{ old('phi_sang_ten', $isEdit ? $batDongSan->phi_sang_ten : '') }}">
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
                        <input type="number" name="gia_thue"
                            class="form-control @error('gia_thue') is-invalid @enderror"
                            value="{{ old('gia_thue', $isEdit ? $batDongSan->gia_thue : '') }}">
                        @error('gia_thue')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Thời gian vào thuê</label>
                        <select name="thoi_gian_vao_thue" class="form-select">
                            <option value="">-- Chọn --</option>
                            @foreach ($constants['thoi_gian_vao_thue'] as $v => $l)
                                <option value="{{ $v }}" @selected(old('thoi_gian_vao_thue', $isEdit ? $batDongSan->thoi_gian_vao_thue : '') == $v)>{{ $l }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Hình thức thanh toán</label>
                        <select name="hinh_thuc_thanh_toan" class="form-select">
                            <option value="">-- Chọn --</option>
                            @foreach ($constants['hinh_thuc_tt'] as $v => $l)
                                <option value="{{ $v }}" @selected(old('hinh_thuc_thanh_toan', $isEdit ? $batDongSan->hinh_thuc_thanh_toan : '') == $v)>{{ $l }}
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
                <textarea name="mo_ta" id="moTaBdsEditor">{{ old('mo_ta', $isEdit ? $batDongSan->mo_ta : '') }}</textarea>
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
                @if ($isEdit && !empty($batDongSan->album_anh))
                    <div class="d-flex flex-wrap gap-2 mb-3" id="albumExist">
                        @foreach ($batDongSan->album_anh as $imgPath)
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

        {{-- SEO --}}
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

@push('scripts')
    <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
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

            // 6. Format giá VNĐ khi gõ
            const inpGia = document.getElementById('inp_gia');
            const giaHint = document.getElementById('gia_hint');
            if (inpGia) {
                function updateGiaHint() {
                    const val = parseFloat(inpGia.value);
                    if (isNaN(val) || val <= 0) giaHint.textContent = '';
                    else if (val >= 1e9) giaHint.textContent = `= ${(val / 1e9).toFixed(3)} tỷ VNĐ`;
                    else if (val >= 1e6) giaHint.textContent = `= ${(val / 1e6).toFixed(1)} triệu VNĐ`;
                    else giaHint.textContent = `= ${val.toLocaleString('vi-VN')} đ`;
                }
                inpGia.addEventListener('input', updateGiaHint);
                updateGiaHint();
            }
        });
    </script>
@endpush
