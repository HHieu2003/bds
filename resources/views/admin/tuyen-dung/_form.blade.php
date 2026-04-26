@extends('admin.layouts.master')
@section('title', isset($tinTuyenDung) ? 'Sửa tin tuyển dụng' : 'Thêm tin tuyển dụng')

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4">
        <h1 class="fw-black mb-0" style="font-size:1.3rem;color:var(--navy)">
            <i class="fas fa-{{ isset($tinTuyenDung) ? 'edit' : 'plus' }} me-2" style="color:var(--primary)"></i>
            {{ isset($tinTuyenDung) ? 'Sửa tin tuyển dụng' : 'Thêm vị trí mới' }}
        </h1>
        <a href="{{ route('nhanvien.admin.tuyen-dung.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Quay lại
        </a>
    </div>

    <form method="POST"
        action="{{ isset($tinTuyenDung) ? route('nhanvien.admin.tuyen-dung.update', $tinTuyenDung) : route('nhanvien.admin.tuyen-dung.store') }}">
        @csrf
        @if (isset($tinTuyenDung))
            @method('PUT')
        @endif

        <div class="row g-4">
            {{-- Cột trái --}}
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <span><i class="fas fa-info-circle me-1"></i> Thông tin vị trí</span>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tiêu đề vị trí *</label>
                            <input type="text" name="tieu_de" class="form-control"
                                value="{{ old('tieu_de', $tinTuyenDung->tieu_de ?? '') }}"
                                placeholder="VD: Chuyên Viên Kinh Doanh Bất Động Sản" required>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Phòng ban</label>
                                <input type="text" name="phong_ban" class="form-control"
                                    value="{{ old('phong_ban', $tinTuyenDung->phong_ban ?? '') }}"
                                    placeholder="VD: Phòng Kinh doanh">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Hình thức *</label>
                                <select name="hinh_thuc" class="form-select" required>
                                    @foreach (\App\Models\TinTuyenDung::HINH_THUC as $key => $label)
                                        <option value="{{ $key }}"
                                            {{ old('hinh_thuc', $tinTuyenDung->hinh_thuc ?? '') == $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Địa điểm</label>
                                <input type="text" name="dia_diem" class="form-control"
                                    value="{{ old('dia_diem', $tinTuyenDung->dia_diem ?? '') }}"
                                    placeholder="VD: Hà Nội">
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Số lượng cần tuyển *</label>
                                <input type="number" name="so_luong" class="form-control" min="1"
                                    value="{{ old('so_luong', $tinTuyenDung->so_luong ?? 1) }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Mức lương</label>
                                <input type="text" name="muc_luong" class="form-control"
                                    value="{{ old('muc_luong', $tinTuyenDung->muc_luong ?? '') }}"
                                    placeholder="VD: 6 - 10 Triệu">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Hạn nộp hồ sơ</label>
                                <input type="date" name="han_nop" class="form-control"
                                    value="{{ old('han_nop', isset($tinTuyenDung) && $tinTuyenDung->han_nop ? $tinTuyenDung->han_nop->format('Y-m-d') : '') }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Mô tả ngắn</label>
                            <textarea name="mo_ta_ngan" class="form-control" rows="2"
                                placeholder="Tóm tắt ngắn gọn về vị trí...">{{ old('mo_ta_ngan', $tinTuyenDung->mo_ta_ngan ?? '') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Mô tả công việc</label>
                            <textarea name="mo_ta_cong_viec" class="form-control" rows="5"
                                placeholder="Liệt kê các nhiệm vụ chính...">{{ old('mo_ta_cong_viec', $tinTuyenDung->mo_ta_cong_viec ?? '') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Yêu cầu ứng viên</label>
                            <textarea name="yeu_cau" class="form-control" rows="5"
                                placeholder="Liệt kê các yêu cầu...">{{ old('yeu_cau', $tinTuyenDung->yeu_cau ?? '') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Quyền lợi</label>
                            <textarea name="quyen_loi" class="form-control" rows="5"
                                placeholder="Liệt kê quyền lợi nhận được...">{{ old('quyen_loi', $tinTuyenDung->quyen_loi ?? '') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Cột phải --}}
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <span><i class="fas fa-cog me-1"></i> Cài đặt hiển thị</span>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tag / Nhãn nổi bật</label>
                            <input type="text" name="tag" class="form-control"
                                value="{{ old('tag', $tinTuyenDung->tag ?? '') }}"
                                placeholder="VD: Hot - Tuyển Gấp 10 NV">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Màu nhãn</label>
                            <select name="tag_color" class="form-select">
                                @foreach (\App\Models\TinTuyenDung::TAG_COLORS as $key => $info)
                                    <option value="{{ $key }}"
                                        {{ old('tag_color', $tinTuyenDung->tag_color ?? 'danger') == $key ? 'selected' : '' }}>
                                        {{ $info['label'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Thứ tự hiển thị</label>
                            <input type="number" name="thu_tu" class="form-control"
                                value="{{ old('thu_tu', $tinTuyenDung->thu_tu ?? 0) }}" min="0">
                            <div class="form-text">Số nhỏ hơn sẽ hiển thị trước.</div>
                        </div>

                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" name="hien_thi" value="1"
                                id="hienThi"
                                {{ old('hien_thi', $tinTuyenDung->hien_thi ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="hienThi">Hiển thị trên website</label>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="noi_bat" value="1"
                                id="noiBat"
                                {{ old('noi_bat', $tinTuyenDung->noi_bat ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="noiBat">Vị trí nổi bật</label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-save me-2"></i>
                            {{ isset($tinTuyenDung) ? 'Cập nhật' : 'Tạo tin tuyển dụng' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

@endsection
