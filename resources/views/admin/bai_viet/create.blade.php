@extends('admin.layout.master')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Thêm Bài Viết Mới</h1>
    
    {{-- Hiển thị lỗi nếu có --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.bai-viet.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-8">
                <div class="mb-3">
                    <label class="fw-bold">Tiêu đề <span class="text-danger">*</span></label>
                    <input type="text" name="tieu_de" class="form-control" value="{{ old('tieu_de') }}" required>
                </div>
                
                <div class="mb-3">
                    <label class="fw-bold">Mô tả ngắn</label>
                    <textarea name="mo_ta_ngan" class="form-control" rows="3" placeholder="Tóm tắt nội dung bài viết...">{{ old('mo_ta_ngan') }}</textarea>
                </div>
                
                <div class="mb-3">
                    <label class="fw-bold">Nội dung chi tiết <span class="text-danger">*</span></label>
                    {{-- QUAN TRỌNG: Thêm id="noi_dung" để CKEditor bắt được --}}
                    <textarea name="noi_dung" id="noi_dung" class="form-control" rows="10" required>{{ old('noi_dung') }}</textarea>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card p-3 shadow-sm border-0">
                    <div class="mb-3">
                        <label class="fw-bold">Loại bài viết</label>
                        <select name="loai_bai_viet" class="form-control">
                            <option value="tin_tuc">Tin Tức Thị Trường</option>
                            <option value="phong_thuy">Phong Thủy</option>
                            <option value="tuyen_dung">Tuyển Dụng</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="fw-bold">Hình ảnh đại diện</label>
                        <input type="file" name="hinh_anh" class="form-control" accept="image/*">
                    </div>
                    
                    <div class="mb-3">
                        <label class="fw-bold">Trạng thái</label>
                        <select name="hien_thi" class="form-control">
                            <option value="1">Hiển thị</option>
                            <option value="0">Ẩn</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-success w-100 py-2 fw-bold">
                        <i class="fas fa-save me-2"></i> Đăng Bài
                    </button>
                    <a href="{{ route('admin.bai-viet.index') }}" class="btn btn-secondary w-100 mt-2">Quay lại</a>
                </div>
            </div>
        </div>
    </form>
</div>

{{-- TÍCH HỢP CKEDITOR 4 --}}
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<script>
    // Thay thế textarea có id="noi_dung" bằng CKEditor
    CKEDITOR.replace('noi_dung', {
        height: 500, // Chiều cao khung soạn thảo
        removeButtons: 'PasteFromWord' // Tùy chọn bỏ bớt nút nếu cần
    });
</script>

@endsection