@extends('admin.layouts.master')
@section('title', 'Sửa bài viết')

@section('content')

    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px">
        <div>
            <h1
                style="font-size:1.3rem;font-weight:800;color:#1a3c5e;margin:0 0 4px;display:flex;align-items:center;gap:9px">
                <i class="fas fa-edit" style="color:#FF8C42"></i> Sửa bài viết
            </h1>
            <p
                style="color:#aaa;font-size:.8rem;margin:0;max-width:480px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                {{ $baiViet->tieude }}
            </p>
        </div>
        <div style="display:flex;gap:8px">
            <a href="{{ route('frontend.bai-viet.show', $baiViet->slug) }}" target="_blank"
                style="background:#f0f7ff;color:#2d6a9f;border:1.5px solid #d0e8ff;padding:9px 16px;border-radius:10px;font-size:.875rem;font-weight:600;text-decoration:none">
                <i class="fas fa-eye"></i> Xem trước
            </a>
            <a href="{{ route('nhanvien.admin.bai-viet.index') }}"
                style="background:#f5f7ff;color:#555;border:1.5px solid #e8e8e8;padding:9px 18px;border-radius:10px;font-size:.875rem;font-weight:600;text-decoration:none">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <form method="POST" action="{{ route('nhanvien.admin.bai-viet.update', $baiViet) }}" enctype="multipart/form-data"
        id="formBaiViet">
        @csrf @method('PUT')

        <div style="display:grid;grid-template-columns:1fr 300px;gap:20px;align-items:start">

            {{-- ═══ CỘT TRÁI ═══ --}}
            <div>
                {{-- Tiêu đề --}}
                <div
                    style="background:#fff;border-radius:14px;border:1.5px solid #f0f2f5;margin-bottom:16px;overflow:hidden">
                    <div
                        style="padding:12px 18px;border-bottom:1px solid #f5f5f5;font-weight:700;color:#1a3c5e;font-size:.875rem">
                        <i class="fas fa-heading" style="color:#FF8C42"></i> Tiêu đề
                    </div>
                    <div style="padding:14px 18px">
                        <input type="text" name="tieude" id="inputTieude" value="{{ old('tieude', $baiViet->tieude) }}"
                            style="width:100%;padding:10px 14px;border:1.5px solid #e8e8e8;border-radius:9px;font-size:.95rem;font-weight:600;outline:none;box-sizing:border-box"
                            required>
                        <p style="color:#bbb;font-size:.75rem;margin:6px 0 0">
                            <i class="fas fa-link"></i> /tin-tuc/<span id="slugPreview"
                                style="color:#2d6a9f">{{ $baiViet->slug }}</span>
                        </p>
                    </div>
                </div>

                {{-- Mô tả ngắn --}}
                <div
                    style="background:#fff;border-radius:14px;border:1.5px solid #f0f2f5;margin-bottom:16px;overflow:hidden">
                    <div
                        style="padding:12px 18px;border-bottom:1px solid #f5f5f5;font-weight:700;color:#1a3c5e;font-size:.875rem">
                        <i class="fas fa-align-left" style="color:#FF8C42"></i> Mô tả ngắn
                    </div>
                    <div style="padding:14px 18px">
                        <textarea name="mota_ngan" id="inputMota" rows="3" maxlength="300"
                            style="width:100%;padding:10px 14px;border:1.5px solid #e8e8e8;border-radius:9px;font-size:.875rem;outline:none;box-sizing:border-box;resize:vertical">{{ old('mota_ngan', $baiViet->mota_ngan) }}</textarea>
                        <p style="text-align:right;font-size:.72rem;color:#ccc;margin:3px 0 0">
                            <span id="motaCount">{{ strlen($baiViet->mota_ngan ?? '') }}</span>/300
                        </p>
                    </div>
                </div>

                {{-- ✅ NỘI DUNG — CKEditor --}}
                <div
                    style="background:#fff;border-radius:14px;border:1.5px solid #f0f2f5;margin-bottom:16px;overflow:hidden">
                    <div
                        style="padding:12px 18px;border-bottom:1px solid #f5f5f5;font-weight:700;color:#1a3c5e;font-size:.875rem">
                        <i class="fas fa-file-alt" style="color:#FF8C42"></i> Nội dung bài viết
                    </div>
                    <div style="padding:14px 18px">
                        <textarea name="noidung" id="noidung">{{ old('noidung', $baiViet->noidung) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- ═══ CỘT PHẢI ═══ --}}
            <div>
                {{-- Nút lưu --}}
                <div
                    style="background:#fff;border-radius:14px;border:1.5px solid #f0f2f5;margin-bottom:16px;padding:14px;display:flex;flex-direction:column;gap:8px">
                    <button type="submit"
                        style="width:100%;background:linear-gradient(135deg,#27ae60,#2ecc71);color:#fff;border:none;padding:12px;border-radius:10px;font-weight:700;font-size:.95rem;cursor:pointer">
                        <i class="fas fa-save"></i> Lưu thay đổi
                    </button>
                    <form method="POST" action="{{ route('nhanvien.admin.bai-viet.destroy', $baiViet) }}"
                        onsubmit="return confirm('Xóa bài viết này?')">
                        @csrf @method('DELETE')
                        <button type="submit"
                            style="width:100%;background:#fff;color:#e74c3c;border:1.5px solid #fde8e8;padding:10px;border-radius:10px;font-weight:600;font-size:.875rem;cursor:pointer">
                            <i class="fas fa-trash"></i> Xóa bài viết
                        </button>
                    </form>
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
                            style="width:100%;aspect-ratio:16/9;border:2px dashed #e8e8e8;border-radius:10px;cursor:pointer;overflow:hidden;background:#fafbff">
                            @if ($baiViet->hinhanh)
                                <img src="{{ asset('storage/' . $baiViet->hinhanh) }}" alt=""
                                    style="width:100%;height:100%;object-fit:cover">
                            @else
                                <div
                                    style="width:100%;height:100%;display:flex;flex-direction:column;align-items:center;justify-content:center">
                                    <i class="fas fa-cloud-upload-alt"
                                        style="font-size:2rem;color:#ccc;margin-bottom:6px"></i>
                                    <span style="font-size:.8rem;color:#bbb">Click để chọn ảnh</span>
                                </div>
                            @endif
                        </div>
                        <input type="file" name="hinhanh" id="inputAnhBia" accept="image/*" style="display:none"
                            onchange="previewAnhBia(this)">
                        @if ($baiViet->hinhanh)
                            <p style="font-size:.72rem;color:#bbb;text-align:center;margin:6px 0 0">Click ảnh để đổi ảnh mới
                            </p>
                        @endif
                    </div>
                </div>

                {{-- Loại --}}
                <div
                    style="background:#fff;border-radius:14px;border:1.5px solid #f0f2f5;margin-bottom:16px;overflow:hidden">
                    <div
                        style="padding:12px 18px;border-bottom:1px solid #f5f5f5;font-weight:700;color:#1a3c5e;font-size:.875rem">
                        <i class="fas fa-tags" style="color:#FF8C42"></i> Loại bài viết
                    </div>
                    <div style="padding:14px 18px">
                        <select name="loaibaiviet"
                            style="width:100%;padding:9px 12px;border:1.5px solid #e8e8e8;border-radius:9px;font-size:.875rem;outline:none">
                            <option value="tintuc" @selected(old('loaibaiviet', $baiViet->loaibaiviet) == 'tintuc')>📰 Tin tức</option>
                            <option value="phongthuy" @selected(old('loaibaiviet', $baiViet->loaibaiviet) == 'phongthuy')>🧿 Phong thủy</option>
                            <option value="kienthuc" @selected(old('loaibaiviet', $baiViet->loaibaiviet) == 'kienthuc')>📚 Kiến thức</option>
                            <option value="tuyendung" @selected(old('loaibaiviet', $baiViet->loaibaiviet) == 'tuyendung')>💼 Tuyển dụng</option>
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
                            $hienthi = old('hienthi', $baiViet->hienthi);
                            $noibat = old('noibat', $baiViet->noibat);
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
    <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('noidung', {
            height: 450,
            language: 'vi'
        });

        document.getElementById('formBaiViet').addEventListener('submit', function() {
            CKEDITOR.instances['noidung'].updateElement();
        });

        document.getElementById('inputTieude').addEventListener('input', function() {
            const slug = this.value.toLowerCase()
                .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
                .replace(/đ/g, 'd').replace(/[^a-z0-9\s-]/g, '')
                .trim().replace(/\s+/g, '-');
            document.getElementById('slugPreview').textContent = slug || '{{ $baiViet->slug }}';
        });

        document.getElementById('inputMota').addEventListener('input', function() {
            document.getElementById('motaCount').textContent = this.value.length;
        });

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

        function toggleSwitch(cbId, swId) {
            const cb = document.getElementById(cbId);
            const sw = document.getElementById(swId);
            cb.checked = !cb.checked;
            sw.style.background = cb.checked ? '#27ae60' : '#ccc';
            sw.querySelector('div').style.left = cb.checked ? '23px' : '3px';
        }
    </script>
@endpush
