@php $isEdit = !is_null($kyGui ?? null); @endphp

<div style="display:grid;grid-template-columns:1fr 300px;gap:20px;align-items:start">

    {{-- ═══ CỘT TRÁI ═══ --}}
    <div>

        {{-- THÔNG TIN CHỦ NHÀ --}}
        <div class="kg-card">
            <div class="kg-card-head"><i class="fas fa-user"></i> Thông tin chủ nhà</div>
            <div class="kg-card-body">
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px">
                    <div class="kg-fg">
                        <label class="kg-lbl req">Họ tên chủ nhà</label>
                        <input type="text" name="ho_ten_chu_nha"
                            class="kg-fi @error('ho_ten_chu_nha') kg-fi-err @enderror"
                            value="{{ old('ho_ten_chu_nha', $isEdit ? $kyGui->ho_ten_chu_nha : '') }}"
                            placeholder="Nguyễn Văn A">
                        @error('ho_ten_chu_nha')
                            <div class="kg-fe-e"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="kg-fg">
                        <label class="kg-lbl req">Số điện thoại</label>
                        <input type="tel" name="so_dien_thoai"
                            class="kg-fi @error('so_dien_thoai') kg-fi-err @enderror"
                            value="{{ old('so_dien_thoai', $isEdit ? $kyGui->so_dien_thoai : '') }}"
                            placeholder="0901 234 567">
                        @error('so_dien_thoai')
                            <div class="kg-fe-e"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="kg-fg">
                        <label class="kg-lbl">Email</label>
                        <input type="email" name="email" class="kg-fi"
                            value="{{ old('email', $isEdit ? $kyGui->email : '') }}" placeholder="email@example.com">
                    </div>
                </div>
            </div>
        </div>

        {{-- PHÂN LOẠI --}}
        <div class="kg-card">
            <div class="kg-card-head"><i class="fas fa-tags"></i> Phân loại</div>
            <div class="kg-card-body">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
                    <div class="kg-fg">
                        <label class="kg-lbl req">Loại hình</label>
                        <select name="loai_hinh" class="kg-fi kg-sel @error('loai_hinh') kg-fi-err @enderror">
                            <option value="">— Chọn loại hình —</option>
                            @foreach (\App\Models\KyGui::LOAI_HINH as $v => $info)
                                <option value="{{ $v }}" @selected(old('loai_hinh', $isEdit ? $kyGui->loai_hinh : '') == $v)>
                                    {{ $info['label'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="kg-fg">
                        <label class="kg-lbl req">Nhu cầu</label>
                        <select name="nhu_cau" class="kg-fi kg-sel kg-nhu-cau-sel">
                            <option value="ban" @selected(old('nhu_cau', $isEdit ? $kyGui->nhu_cau : 'ban') == 'ban')>Bán</option>
                            <option value="thue" @selected(old('nhu_cau', $isEdit ? $kyGui->nhu_cau : 'ban') == 'thue')>Cho thuê</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        {{-- THÔNG TIN BĐS --}}
        <div class="kg-card">
            <div class="kg-card-head"><i class="fas fa-home"></i> Thông tin bất động sản</div>
            <div class="kg-card-body">
                <div class="kg-fg" style="margin-bottom:14px">
                    <label class="kg-lbl">Địa chỉ</label>
                    <input type="text" name="dia_chi" class="kg-fi"
                        value="{{ old('dia_chi', $isEdit ? $kyGui->dia_chi : '') }}"
                        placeholder="Số nhà, đường, phường, quận...">
                </div>
                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px">
                    <div class="kg-fg">
                        <label class="kg-lbl req">Diện tích (m²)</label>
                        <input type="number" name="dien_tich" class="kg-fi @error('dien_tich') kg-fi-err @enderror"
                            value="{{ old('dien_tich', $isEdit ? $kyGui->dien_tich : '') }}" placeholder="75"
                            min="1" step="0.1">
                        @error('dien_tich')
                            <div class="kg-fe-e">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="kg-fg">
                        <label class="kg-lbl">Hướng nhà</label>
                        <select name="huong_nha" class="kg-fi kg-sel">
                            <option value="">— Chọn —</option>
                            @foreach (['dong' => 'Đông', 'tay' => 'Tây', 'nam' => 'Nam', 'bac' => 'Bắc', 'dong_nam' => 'Đông Nam', 'dong_bac' => 'Đông Bắc', 'tay_nam' => 'Tây Nam', 'tay_bac' => 'Tây Bắc'] as $v => $l)
                                <option value="{{ $v }}" @selected(old('huong_nha', $isEdit ? $kyGui->huong_nha : '') == $v)>{{ $l }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="kg-fg">
                        <label class="kg-lbl">Nội thất</label>
                        <select name="noi_that" class="kg-fi kg-sel">
                            <option value="">— Chọn —</option>
                            @foreach (\App\Models\KyGui::NOI_THAT as $v => $l)
                                <option value="{{ $v }}" @selected(old('noi_that', $isEdit ? $kyGui->noi_that : '') == $v)>{{ $l }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="kg-fg">
                        <label class="kg-lbl">Phòng ngủ</label>
                        <input type="number" name="so_phong_ngu" class="kg-fi"
                            value="{{ old('so_phong_ngu', $isEdit ? $kyGui->so_phong_ngu : 0) }}" min="0"
                            max="20">
                    </div>
                    <div class="kg-fg">
                        <label class="kg-lbl">Phòng tắm</label>
                        <input type="number" name="so_phong_tam" class="kg-fi"
                            value="{{ old('so_phong_tam', $isEdit ? $kyGui->so_phong_tam : 0) }}" min="0"
                            max="20">
                    </div>
                </div>

                {{-- GIÁ BÁN --}}
                <div id="kgAdminBanBox"
                    style="{{ old('nhu_cau', $isEdit ? $kyGui->nhu_cau : 'ban') !== 'ban' ? 'display:none' : '' }}">
                    <hr style="border:none;border-top:1px solid #f5f5f5;margin:16px 0">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                        <div class="kg-fg">
                            <label class="kg-lbl">Giá bán mong muốn (VNĐ)</label>
                            <input type="number" name="gia_ban_mong_muon" class="kg-fi"
                                value="{{ old('gia_ban_mong_muon', $isEdit ? $kyGui->gia_ban_mong_muon : '') }}"
                                placeholder="3500000000">
                        </div>
                        <div class="kg-fg">
                            <label class="kg-lbl">Pháp lý</label>
                            <select name="phap_ly" class="kg-fi kg-sel">
                                <option value="">— Chọn —</option>
                                @foreach (\App\Models\KyGui::PHAP_LY as $v => $l)
                                    <option value="{{ $v }}" @selected(old('phap_ly', $isEdit ? $kyGui->phap_ly : '') == $v)>
                                        {{ $l }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- GIÁ THUÊ --}}
                <div id="kgAdminThueBox"
                    style="{{ old('nhu_cau', $isEdit ? $kyGui->nhu_cau : 'ban') !== 'thue' ? 'display:none' : '' }}">
                    <hr style="border:none;border-top:1px solid #f5f5f5;margin:16px 0">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                        <div class="kg-fg">
                            <label class="kg-lbl">Giá thuê/tháng (VNĐ)</label>
                            <input type="number" name="gia_thue_mong_muon" class="kg-fi"
                                value="{{ old('gia_thue_mong_muon', $isEdit ? $kyGui->gia_thue_mong_muon : '') }}"
                                placeholder="15000000">
                        </div>
                        <div class="kg-fg">
                            <label class="kg-lbl">Hình thức thanh toán</label>
                            <select name="hinh_thuc_thanh_toan" class="kg-fi kg-sel">
                                <option value="">— Chọn —</option>
                                @foreach (\App\Models\KyGui::HINH_THUC_THANH_TOAN as $v => $l)
                                    <option value="{{ $v }}" @selected(old('hinh_thuc_thanh_toan', $isEdit ? $kyGui->hinh_thuc_thanh_toan : '') == $v)>
                                        {{ $l }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="kg-fg" style="margin-top:14px">
                    <label class="kg-lbl">Ghi chú</label>
                    <textarea name="ghi_chu" class="kg-fi" rows="3" placeholder="Ghi chú thêm...">{{ old('ghi_chu', $isEdit ? $kyGui->ghi_chu : '') }}</textarea>
                </div>
            </div>
        </div>

        {{-- HÌNH ẢNH --}}
        <div class="kg-card">
            <div class="kg-card-head"><i class="fas fa-images"></i> Hình ảnh tham khảo</div>
            <div class="kg-card-body">

                {{-- Ảnh hiện có (khi edit) --}}
                @if ($isEdit && $kyGui->hinh_anh_tham_khao && count($kyGui->hinh_anh_tham_khao) > 0)
                    <div
                        style="display:grid;grid-template-columns:repeat(auto-fill,minmax(110px,1fr));gap:10px;margin-bottom:14px">
                        @foreach ($kyGui->hinh_anh_tham_khao as $img)
                            <div style="position:relative">
                                <img src="{{ asset('storage/' . $img) }}" alt="Ảnh"
                                    style="width:100%;aspect-ratio:4/3;object-fit:cover;border-radius:8px;border:1.5px solid #f0f2f5">
                                <label
                                    style="position:absolute;top:4px;right:4px;background:#e74c3c;color:#fff;border-radius:5px;width:22px;height:22px;display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:.7rem"
                                    title="Xóa ảnh này">
                                    <input type="checkbox" name="xoa_hinh_anh[]" value="{{ $img }}"
                                        style="display:none">
                                    <i class="fas fa-times"></i>
                                </label>
                            </div>
                        @endforeach
                    </div>
                    <div style="font-size:.75rem;color:#aaa;margin-bottom:10px">
                        <i class="fas fa-info-circle"></i>
                        Tích vào ảnh để xóa khi lưu. Thêm ảnh mới bên dưới.
                    </div>
                @endif

                <input type="file" id="kgAdminFileInput" name="hinh_anh_tham_khao[]" multiple accept="image/*"
                    class="kg-fi" onchange="kgAdminPreview(this)" style="padding:8px">
                <div id="kgAdminImgPreview"
                    style="display:grid;grid-template-columns:repeat(auto-fill,minmax(100px,1fr));gap:8px;margin-top:10px">
                </div>
            </div>
        </div>

    </div>{{-- end left --}}

    {{-- ═══ CỘT PHẢI ═══ --}}
    <div>

        {{-- NÚT LƯU --}}
        <div class="kg-card">
            <div class="kg-card-body" style="padding:16px">
                <button type="submit" form="kgAdminForm" class="kg-btn-save">
                    <i class="fas fa-save"></i>
                    {{ $isEdit ? 'Lưu thay đổi' : 'Tạo ký gửi' }}
                </button>
                <a href="{{ route('nhanvien.admin.ky-gui.index') }}" class="kg-btn-cancel">
                    <i class="fas fa-times"></i> Hủy
                </a>
            </div>
        </div>

        {{-- TRẠNG THÁI & PHÂN CÔNG --}}
        <div class="kg-card">
            <div class="kg-card-head"><i class="fas fa-cog"></i> Xử lý nội bộ</div>
            <div class="kg-card-body">
                <div class="kg-fg">
                    <label class="kg-lbl">Trạng thái</label>
                    <select name="trang_thai" class="kg-fi kg-sel">
                        @foreach (\App\Models\KyGui::TRANG_THAI as $v => $info)
                            <option value="{{ $v }}" @selected(old('trang_thai', $isEdit ? $kyGui->trang_thai : 'cho_duyet') == $v)>
                                {{ $info['label'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="kg-fg">
                    <label class="kg-lbl">Nhân viên phụ trách</label>
                    <select name="nhan_vien_phu_trach_id" class="kg-fi kg-sel">
                        <option value="">— Chưa phân công —</option>
                        @foreach (\App\Models\NhanVien::whereIn('vai_tro', ['admin', 'nguon_hang'])->where('kich_hoat', true)->orderBy('ho_ten')->get() as $nv)
                            <option value="{{ $nv->id }}" @selected(old('nhan_vien_phu_trach_id', $isEdit ? $kyGui->nhan_vien_phu_trach_id : '') == $nv->id)>
                                {{ $nv->ho_ten }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="kg-fg" style="margin-bottom:0">
                    <label class="kg-lbl">Nguồn ký gửi</label>
                    <select name="nguon_ky_gui" class="kg-fi kg-sel">
                        @foreach (['website' => 'Website', 'phone' => 'Điện thoại', 'sale' => 'Qua Sale', 'zalo' => 'Zalo', 'walk_in' => 'Trực tiếp'] as $v => $l)
                            <option value="{{ $v }}" @selected(old('nguon_ky_gui', $isEdit ? $kyGui->nguon_ky_gui : 'phone') == $v)>{{ $l }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- THÔNG TIN HỆ THỐNG (edit) --}}
        @if ($isEdit)
            <div class="kg-card">
                <div class="kg-card-head"><i class="fas fa-info-circle"></i> Thông tin</div>
                <div class="kg-card-body">
                    <div class="kg-sys-row">
                        <span class="kg-sys-key">Mã</span>
                        <span class="kg-sys-val" style="font-family:monospace;color:#2d6a9f">
                            #KG-{{ str_pad($kyGui->id, 5, '0', STR_PAD_LEFT) }}
                        </span>
                    </div>
                    <div class="kg-sys-row">
                        <span class="kg-sys-key">Ngày gửi</span>
                        <span class="kg-sys-val">{{ $kyGui->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>
        @endif

    </div>{{-- end right --}}
</div>

@push('styles')
    <style>
        .kg-card {
            background: #fff;
            border-radius: 14px;
            border: 1px solid #f0f2f5;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .05);
            margin-bottom: 18px;
            overflow: hidden
        }

        .kg-card-head {
            padding: 12px 18px;
            background: linear-gradient(135deg, #f8faff, #eef3ff);
            border-bottom: 1px solid #e8eeff;
            font-weight: 700;
            font-size: .86rem;
            color: #1a3c5e;
            display: flex;
            align-items: center;
            gap: 8px
        }

        .kg-card-head i {
            color: #FF8C42
        }

        .kg-card-body {
            padding: 18px
        }

        .kg-fg {
            margin-bottom: 12px
        }

        .kg-fg:last-child {
            margin-bottom: 0
        }

        .kg-lbl {
            display: block;
            font-size: .73rem;
            font-weight: 700;
            color: #666;
            text-transform: uppercase;
            letter-spacing: .3px;
            margin-bottom: 4px
        }

        .kg-lbl.req::after {
            content: ' *';
            color: #e74c3c
        }

        .kg-fi {
            width: 100%;
            border: 1.5px solid #e8e8e8;
            border-radius: 8px;
            padding: 9px 12px;
            font-size: .875rem;
            color: #333;
            background: #fafafa;
            outline: none;
            font-family: inherit;
            transition: border-color .2s;
            box-sizing: border-box
        }

        input.kg-fi,
        select.kg-fi {
            height: 40px;
            padding: 0 12px
        }

        .kg-fi:focus {
            border-color: #FF8C42;
            background: #fff
        }

        .kg-fi-err {
            border-color: #e74c3c !important
        }

        .kg-fe-e {
            font-size: .76rem;
            color: #e74c3c;
            margin-top: 3px
        }

        .kg-sel {
            appearance: none;
            cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath fill='%23aaa' d='M5 6L0 0h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
            padding-right: 28px !important
        }

        .kg-btn-save {
            width: 100%;
            height: 46px;
            background: linear-gradient(135deg, #FF8C42, #f5a623);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-weight: 700;
            font-size: .9rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 4px 14px rgba(255, 140, 66, .3);
            margin-bottom: 10px
        }

        .kg-btn-save:hover {
            opacity: .9
        }

        .kg-btn-cancel {
            width: 100%;
            height: 40px;
            background: #f5f5f5;
            color: #888;
            border: 1.5px solid #e8e8e8;
            border-radius: 10px;
            font-weight: 600;
            font-size: .875rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            text-decoration: none
        }

        .kg-btn-cancel:hover {
            background: #ffeee0;
            color: #FF8C42;
            border-color: #FF8C42
        }

        .kg-sys-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 5px 0;
            border-bottom: 1px solid #fafafa
        }

        .kg-sys-key {
            font-size: .75rem;
            color: #bbb
        }

        .kg-sys-val {
            font-size: .78rem;
            font-weight: 600;
            color: #555
        }

        @media(max-width:900px){[style*="grid-template-columns:1fr 300px"] {
            grid-template-columns: 1fr
        }
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Nhu cầu → hiện/ẩn box giá
        document.querySelectorAll('.kg-nhu-cau-sel').forEach(sel => {
            sel.addEventListener('change', function() {
                document.getElementById('kgAdminBanBox').style.display = this.value === 'ban' ? '' : 'none';
                document.getElementById('kgAdminThueBox').style.display = this.value === 'thue' ? '' :
                    'none';
            });
        });

        // Checkbox xóa ảnh: làm mờ ảnh khi tích
        document.querySelectorAll('[name="xoa_hinh_anh[]"]').forEach(chk => {
            chk.addEventListener('change', function() {
                const img = this.closest('div').querySelector('img');
                img.style.opacity = this.checked ? '0.35' : '1';
            });
        });

        // Preview ảnh mới
        function kgAdminPreview(input) {
            const preview = document.getElementById('kgAdminImgPreview');
            preview.innerHTML = '';
            Array.from(input.files).slice(0, 5).forEach(file => {
                const reader = new FileReader();
                reader.onload = e => {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.cssText =
                        'width:100%;aspect-ratio:4/3;object-fit:cover;border-radius:8px;border:1.5px solid #e8e8e8';
                    preview.appendChild(img);
                };
                reader.readAsDataURL(file);
            });
        }
    </script>
@endpush
