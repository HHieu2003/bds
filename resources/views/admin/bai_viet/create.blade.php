@extends('admin.layout.master')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Thêm Bài Viết Mới</h1>
    <form action="{{ route('admin.bai-viet.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-8">
                <div class="mb-3">
                    <label>Tiêu đề</label>
                    <input type="text" name="tieu_de" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Mô tả ngắn</label>
                    <textarea name="mo_ta_ngan" class="form-control" rows="3"></textarea>
                </div>
                <div class="mb-3">
                    <label>Nội dung chi tiết</label>
                    <textarea name="noi_dung" class="form-control" rows="10" required></textarea>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3">
                    <div class="mb-3">
                        <label>Loại bài viết</label>
                        <select name="loai_bai_viet" class="form-control">
                            <option value="tin_tuc">Tin Tức Thị Trường</option>
                            <option value="phong_thuy">Phong Thủy</option>
                            <option value="tuyen_dung">Tuyển Dụng</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Hình ảnh đại diện</label>
                        <input type="file" name="hinh_anh" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Trạng thái</label>
                        <select name="hien_thi" class="form-control">
                            <option value="1">Hiển thị</option>
                            <option value="0">Ẩn</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Đăng Bài</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection