@extends('admin.layouts.master')
@section('title', 'Thêm bài viết')

@section('content')

    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px">
        <h1 style="font-size:1.3rem;font-weight:800;color:#1a3c5e;margin:0;display:flex;align-items:center;gap:9px">
            <i class="fas fa-plus-circle" style="color:#FF8C42"></i> Thêm bài viết mới
        </h1>
        <a href="{{ route('nhanvien.admin.bai-viet.index') }}"
            style="background:#f5f7ff;color:#555;border:1.5px solid #e8e8e8;padding:9px 18px;border-radius:10px;font-size:.875rem;font-weight:600;text-decoration:none">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    <form method="POST" action="{{ route('nhanvien.admin.bai-viet.store') }}" enctype="multipart/form-data" id="formBaiViet">
        @csrf

        <div style="display:grid;grid-template-columns:1fr 300px;gap:20px;align-items:start">

            {{-- ═══ CỘT TRÁI ═══ --}}
            <div>
                {{-- Tiêu đề --}}
                <div
                    style="background:#fff;border-radius:14px;border:1.5px solid #f0f2f5;margin-bottom:16px;overflow:hidden">
                    <div
                        style="padding:12px 18px;border-bottom:1px solid #f5f5f5;font-weight:700;color:#1a3c5e;font-size:.875rem">
                        <i class="fas fa-heading" style="color:#FF8C42"></i> Tiêu đề <span style="color:#e74c3c">*</span>
                    </div>
                    <div style="padding:14px 18px">
                        <input type="text" name="tieude" id="inputTieude" value="{{ old('tieude') }}"
                            placeholder="Nhập tiêu đề bài viết..."
                            style="width:100%;padding:10px 14px;border:1.5px solid {{ $errors->has('tieude') ? '#e74c3c' : '#e8e8e8' }};border-radius:9px;font-size:.95rem;font-weight:600;outline:none;box-sizing:border-box"
                            required>
                        @error('tieude')
                            <p style="color:#e74c3c;font-size:.75rem;margin:5px 0 0"><i class="fas fa-exclamation-circle"></i>
                                {{ $message }}</p>
                        @enderror
                        <p style="color:#bbb;font-size:.75rem;margin:6px 0 0">
                            <i class="fas fa-link"></i> /tin-tuc/<span id="slugPreview" style="color:#2d6a9f">---</span>
                        </p>
                    </div>
                </div>

                {{-- Mô tả ngắn --}}
                <div
                    style="background:#fff;border-radius:14px;border:1.5px solid #f0f2f5;margin-bottom:16px;overflow:hidden">
                    <div
                        style="padding:12px 18px;border-bottom:1px solid #f5f5f5;font-weight:700;color:#1a3c5e;font-size:.875rem">
                        <i class="fas fa-align-left" style="color:#FF8C42"></i> Mô tả ngắn
                        <span style="font-size:.72rem;font-weight:400;color:#aaa;margin-left:5px">(hiển thị ở trang danh
                            sách)</span>
                    </div>
                    <div style="padding:14px 18px">
                        <textarea name="mota_ngan" id="inputMota" rows="3" maxlength="300"
                            placeholder="Tóm tắt nội dung bài viết ngắn gọn..."
                            style="width:100%;padding:10px 14px;border:1.5px solid #e8e8e8;border-radius:9px;font-size:.875rem;outline:none;box-sizing:border-box;resize:vertical">{{ old('mota_ngan') }}</textarea>
                        <p style="text-align:right;font-size:.72rem;color:#ccc;margin:3px 0 0">
                            <span id="motaCount">0</span>/300
                        </p>
                    </div>
                </div>

                {{-- ✅ NỘI DUNG — CKEditor --}}
                <div
                    style="background:#fff;border-radius:14px;border:1.5px solid {{ $errors->has('noidung') ? '#e74c3c' : '#f0f2f5' }};margin-bottom:16px;overflow:hidden">
                    <div
                        style="padding:12px 18px;border-bottom:1px solid #f5f5f5;font-weight:700;color:#1a3c5e;font-size:.875rem">
                        <i class="fas fa-file-alt" style="color:#FF8C42"></i> Nội dung bài viết <span
                            style="color:#e74c3c">*</span>
                    </div>
                    <div style="padding:14px 18px">
                        <textarea name="noidung" id="noidung">{{ old('noidung') }}</textarea>
                        @error('noidung')
                            <p style="color:#e74c3c;font-size:.75rem;margin:5px 0 0"><i class="fas fa-exclamation-circle"></i>
                                {{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- ═══ CỘT PHẢI ═══ --}}
            <div>
                {{-- Nút lưu --}}
                <div style="background:#fff;border-radius:14px;border:1.5px solid #f0f2f5;margin-bottom:16px;padding:14px">
                    <button type="submit"
                        style="width:100%;background:linear-gradient(135deg,#1a3c5e,#2d6a9f);color:#fff;border:none;padding:12px;border-radius:10px;font-weight:700;font-size:.95rem;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px">
                        <i class="fas fa-save"></i> Đăng bài viết
                    </button>
                </div>

                {{-- Ảnh bìa --}}
                <div
                    style="background:#fff;border-radius:14px;border:1.5px solid #f0f2f5;margin-bottom:16px;overflow:hidden">
                    <div
                        style="padding:12px 18px;border-bottom:1px solid #f5f5f5;font-weight:700;color:#1a3c5e;font-size:.875rem">
                        <i class="fas fa-image" style="color:#FF8C42"></i> Ảnh bìa
                    </div>
                    <div style="padding:14px 18px">
                        <div id="anhBiaPreview" onclick="document.getElementById('inputAnhBia').click()"
                            style="width:100%;aspect-ratio:16/9;border:2px dashed #e8e8e8;border-radius:10px;display:flex;flex-direction:column;align-items:center;justify-content:center;cursor:pointer;background:#fafbff;overflow:hidden"
                            onmouseover="this.style.borderColor='#FF8C42'" onmouseout="this.style.borderColor='#e8e8e8'">
                            <i class="fas fa-cloud-upload-alt" style="font-size:2rem;color:#ccc;margin-bottom:6px"></i>
                            <span style="font-size:.8rem;color:#bbb">Click để chọn ảnh</span>
                        </div>
                        <input type="file" name="hinhanh" id="inputAnhBia" accept="image/*" style="display:none"
                            onchange="previewAnhBia(this)">
                    </div>
                </div>

                {{-- Phân loại --}}
                <div
                    style="background:#fff;border-radius:14px;border:1.5px solid #f0f2f5;margin-bottom:16px;overflow:hidden">
                    <div
                        style="padding:12px 18px;border-bottom:1px solid #f5f5f5;font-weight:700;color:#1a3c5e;font-size:.875rem">
                        <i class="fas fa-tags" style="color:#FF8C42"></i> Loại bài viết
                    </div>
                    <div style="padding:14px 18px">
                        <select name="loaibaiviet"
                            style="width:100%;padding:9px 12px;border:1.5px solid #e8e8e8;border-radius:9px;font-size:.875rem;outline:none">
                            <option value="tintuc" @selected(old('loaibaiviet', 'tintuc') == 'tintuc')>📰 Tin tức</option>
                            <option value="phongthuy" @selected(old('loaibaiviet') == 'phongthuy')>🧿 Phong thủy</option>
                            <option value="kienthuc" @selected(old('loaibaiviet') == 'kienthuc')>📚 Kiến thức</option>
                            <option value="tuyendung" @selected(old('loaibaiviet') == 'tuyendung')>💼 Tuyển dụng</option>
                        </select>
                    </div>
                </div>

                {{-- Tùy chọn --}}
                <div style="background:#fff;border-radius:14px;border:1.5px solid #f0f2f5;overflow:hidden">
                    <div
                        style="padding:12px 18px;border-bottom:1px solid #f5f5f5;font-weight:700;color:#1a3c5e;font-size:.875rem">
                        <i class="fas fa-sliders-h" style="color:#FF8C42"></i> Tùy chọn
                    </div>
                    <div style="padding:14px 18px;display:flex;flex-direction:column;gap:14px">
                        @php
                            $hienthi = old('hienthi', true);
                            $noibat = old('noibat', false);
                        @endphp

                        <label style="display:flex;align-items:center;justify-content:space-between;cursor:pointer">
                            <span style="font-size:.875rem;color:#444">Hiển thị công khai</span>
                            <div id="switchHienthi" onclick="toggleSwitch('cbHienthi','switchHienthi')"
                                style="width:44px;height:24px;border-radius:12px;background:{{ $hienthi ? '#27ae60' : '#ccc' }};cursor:pointer;position:relative;transition:background .2s;flex-shrink:0">
                                <div
                                    style="position:absolute;top:3px;left:{{ $hienthi ? '23' : '3' }}px;width:18px;height:18px;border-radius:50%;background:#fff;transition:left .2s;box-shadow:0 1px 3px rgba(0,0,0,.2)">
                                </div>
                            </div>
                            <input type="checkbox" name="hienthi" id="cbHienthi" {{ $hienthi ? 'checked' : '' }}
                                style="display:none">
                        </label>

                        <label style="display:flex;align-items:center;justify-content:space-between;cursor:pointer">
                            <span style="font-size:.875rem;color:#444">Bài nổi bật</span>
                            <div id="switchNoibat" onclick="toggleSwitch('cbNoibat','switchNoibat')"
                                style="width:44px;height:24px;border-radius:12px;background:{{ $noibat ? '#27ae60' : '#ccc' }};cursor:pointer;position:relative;transition:background .2s;flex-shrink:0">
                                <div
                                    style="position:absolute;top:3px;left:{{ $noibat ? '23' : '3' }}px;width:18px;height:18px;border-radius:50%;background:#fff;transition:left .2s;box-shadow:0 1px 3px rgba(0,0,0,.2)">
                                </div>
                            </div>
                            <input type="checkbox" name="noibat" id="cbNoibat" {{ $noibat ? 'checked' : '' }}
                                style="display:none">
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </form>

@endsection

@push('scripts')
    {{-- ✅ CKEditor --}}
    <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('noidung', {
            height: 450,
            language: 'vi'
        });

        document.getElementById('formBaiViet').addEventListener('submit', function() {
            CKEDITOR.instances['noidung'].updateElement();
        });

        // Slug preview
        document.getElementById('inputTieude').addEventListener('input', function() {
            const slug = this.value.toLowerCase()
                .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
                .replace(/đ/g, 'd').replace(/[^a-z0-9\s-]/g, '')
                .trim().replace(/\s+/g, '-');
            document.getElementById('slugPreview').textContent = slug || '---';
        });

        // Đếm ký tự mô tả
        document.getElementById('inputMota').addEventListener('input', function() {
            document.getElementById('motaCount').textContent = this.value.length;
        });

        // Preview ảnh bìa
        function previewAnhBia(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    document.getElementById('anhBiaPreview').innerHTML =
                        `<img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover">`;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Toggle switch
        function toggleSwitch(cbId, swId) {
            const cb = document.getElementById(cbId);
            const sw = document.getElementById(swId);
            cb.checked = !cb.checked;
            sw.style.background = cb.checked ? '#27ae60' : '#ccc';
            sw.querySelector('div').style.left = cb.checked ? '23px' : '3px';
        }
    </script>
@endpush
