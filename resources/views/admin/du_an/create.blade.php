@extends('admin.layout.master')

@section('content')
    <div class="card shadow-sm border-0" style="max-width: 800px;">
        <div class="card-body p-4">
            <h3 class="mb-4 text-primary">Thêm Dự Án Mới</h3>

            <form action="{{ route('du-an.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Tên dự án <span class="text-danger">*</span></label>
                        <input type="text" name="ten_du_an" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Ảnh đại diện</label>
                        <input type="file" name="hinh_anh" class="form-control" accept="image/*">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Địa chỉ</label>
                    <input type="text" name="dia_chi" class="form-control">
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Chủ đầu tư</label>
                        <input type="text" name="chu_dau_tu" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Đơn vị thi công</label>
                        <input type="text" name="don_vi_thi_cong" class="form-control">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Mô tả dự án</label>
                    <textarea name="mo_ta" class="form-control" rows="5" placeholder="Thông tin chi tiết về tiện ích, vị trí..."></textarea>
                </div>

                <button type="submit" class="btn btn-success px-4">Lưu Dự Án</button>
                <a href="{{ route('du-an.index') }}" class="btn btn-secondary">Quay lại</a>
            </form>
        </div>
    </div>
@endsection