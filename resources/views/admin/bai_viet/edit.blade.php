@extends('admin.layout.master')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Chỉnh Sửa Bài Viết</h1>
        <a href="{{ route('admin.bai-viet.index') }}" class="btn btn-secondary">
            <i class="fa-solid fa-arrow-left me-2"></i> Quay Lại
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Lỗi!</strong>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('admin.bai-viet.update', $baiViet->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-8">
                <!-- Tiêu đề -->
                <div class="mb-3">
                    <label for="tieu_de" class="form-label fw-bold">Tiêu đề <span class="text-danger">*</span></label>
                    <input type="text" name="tieu_de" id="tieu_de" class="form-control @error('tieu_de') is-invalid @enderror" 
                           value="{{ old('tieu_de', $baiViet->tieu_de) }}" required>
                    @error('tieu_de')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Mô tả ngắn -->
                <div class="mb-3">
                    <label for="mo_ta_ngan" class="form-label fw-bold">Mô tả ngắn</label>
                    <textarea name="mo_ta_ngan" id="mo_ta_ngan" class="form-control @error('mo_ta_ngan') is-invalid @enderror" 
                              rows="3" placeholder="Mô tả ngắn gọn bài viết (tối đa 500 ký tự)">{{ old('mo_ta_ngan', $baiViet->mo_ta_ngan) }}</textarea>
                    @error('mo_ta_ngan')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                    <small class="form-text text-muted">Hiển thị trên trang danh sách</small>
                </div>

                <!-- Nội dung chi tiết -->
                <div class="mb-3">
                    <label for="noi_dung" class="form-label fw-bold">Nội dung chi tiết <span class="text-danger">*</span></label>
                    <textarea name="noi_dung" id="noi_dung" class="form-control @error('noi_dung') is-invalid @enderror" 
                              rows="12" placeholder="Nhập nội dung đầy đủ của bài viết" required>{{ old('noi_dung', $baiViet->noi_dung) }}</textarea>
                    @error('noi_dung')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                    <small class="form-text text-muted d-block mt-2">
                        Hỗ trợ HTML cơ bản (nên sử dụng thẻ <code>&lt;p&gt;</code>, <code>&lt;h2&gt;</code>, <code>&lt;ul&gt;</code>, etc.)
                    </small>
                </div>

                <!-- Thông tin bổ sung -->
                <div class="card bg-light p-3">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <p class="text-muted mb-1"><small>Slug (URL)</small></p>
                            <p class="fw-bold" style="word-break: break-all;">
                                <code>{{ $baiViet->slug }}</code>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted mb-1"><small>Lượt xem</small></p>
                            <p class="fw-bold">
                                <i class="fa-solid fa-eye text-primary"></i> 
                                {{ $baiViet->luot_xem ?? 0 }} lượt
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted mb-1"><small>Tác giả</small></p>
                            <p class="fw-bold">{{ $baiViet->user->name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted mb-1"><small>Cập nhật lần cuối</small></p>
                            <p class="fw-bold">{{ $baiViet->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-md-4">
                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-header bg-primary text-white fw-bold">
                        <i class="fa-solid fa-cog me-2"></i> Cài đặt bài viết
                    </div>
                    <div class="card-body">
                        <!-- Loại bài viết -->
                        <div class="mb-3">
                            <label for="loai_bai_viet" class="form-label fw-bold">Loại bài viết <span class="text-danger">*</span></label>
                            <select name="loai_bai_viet" id="loai_bai_viet" class="form-select @error('loai_bai_viet') is-invalid @enderror">
                                <option value="">-- Chọn loại --</option>
                                <option value="tin_tuc" {{ old('loai_bai_viet', $baiViet->loai_bai_viet) == 'tin_tuc' ? 'selected' : '' }}>
                                    <i class="fa-solid fa-newspaper"></i> Tin Tức Thị Trường
                                </option>
                                <option value="phong_thuy" {{ old('loai_bai_viet', $baiViet->loai_bai_viet) == 'phong_thuy' ? 'selected' : '' }}>
                                    <i class="fa-solid fa-leaf"></i> Phong Thủy
                                </option>
                                <option value="tuyen_dung" {{ old('loai_bai_viet', $baiViet->loai_bai_viet) == 'tuyen_dung' ? 'selected' : '' }}>
                                    <i class="fa-solid fa-briefcase"></i> Tuyển Dụng
                                </option>
                            </select>
                            @error('loai_bai_viet')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Trạng thái hiển thị -->
                        <div class="mb-3">
                            <label for="hien_thi" class="form-label fw-bold">Trạng thái</label>
                            <select name="hien_thi" id="hien_thi" class="form-select @error('hien_thi') is-invalid @enderror">
                                <option value="1" {{ old('hien_thi', $baiViet->hien_thi) == 1 ? 'selected' : '' }}>
                                    <i class="fa-solid fa-eye"></i> Hiển thị
                                </option>
                                <option value="0" {{ old('hien_thi', $baiViet->hien_thi) == 0 ? 'selected' : '' }}>
                                    <i class="fa-solid fa-eye-slash"></i> Ẩn
                                </option>
                            </select>
                            @error('hien_thi')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <hr>

                        <!-- Hình ảnh đại diện -->
                        <div class="mb-3">
                            <label for="hinh_anh" class="form-label fw-bold">Hình ảnh đại diện</label>
                            
                            @if($baiViet->hinh_anh)
                                <div class="mb-2">
                                    <img src="{{ asset($baiViet->hinh_anh) }}" alt="{{ $baiViet->tieu_de }}" 
                                         class="img-thumbnail" style="max-width: 100%; height: auto;">
                                    <p class="text-muted small mt-2">Ảnh hiện tại</p>
                                </div>
                            @endif

                            <input type="file" name="hinh_anh" id="hinh_anh" class="form-control @error('hinh_anh') is-invalid @enderror" 
                                   accept="image/*">
                            @error('hinh_anh')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted d-block mt-2">Định dạng: JPG, PNG, GIF. Dung lượng tối đa: 5MB</small>
                        </div>
                    </div>
                </div>

                <!-- Nút hành động -->
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="fa-solid fa-save me-2"></i> Lưu Thay Đổi
                    </button>
                    <a href="{{ route('admin.bai-viet.index') }}" class="btn btn-secondary btn-lg">
                        <i class="fa-solid fa-times me-2"></i> Hủy
                    </a>
                </div>

                <!-- Thông tin hữu ích -->
                <div class="alert alert-info mt-3" role="alert">
                    <strong><i class="fa-solid fa-lightbulb me-2"></i> Mẹo:</strong>
                    <ul class="mb-0 mt-2">
                        <li>Tiêu đề nên ngắn gọn, có SEO tốt</li>
                        <li>Mô tả ngắn hiển thị trên danh sách</li>
                        <li>Hình ảnh nên chất lượng cao</li>
                        <li>Nội dung rõ ràng, dễ hiểu</li>
                    </ul>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
    .form-control:focus, .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }
    
    code {
        background-color: #f8f9fa;
        padding: 2px 6px;
        border-radius: 3px;
        font-size: 0.875rem;
    }
    
    .card {
        border-radius: 8px;
        border: 1px solid #dee2e6;
    }
</style>
@endsection
